<?php

namespace App\Service;

use App\Entity\Contribution;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\Cache\CacheInterface;

class ContributionGetter
{
    public function __construct(
        private HttpClientInterface $client,
        private LoggerInterface $logger,
        private CacheInterface $cache
    ) {

    }

    private function getRanges(): array
    {
        return array(
            ["Stortinget", "85.88.64.0/19"],
            ["Kripos", "45.88.116.0/22"],
            ["Miljødirektoratet", "185.76.84.0/22"],
            ["MET Oslo", "157.249.0.0/16"],
            ["Norsk Institutt for Vannforskning (NIVA)", "151.157.0.0/16"],
            ["NRK", "160.68.0.0/16"],
            ["NRK", "185.97.188.0/22"],
            ["Norsk Hydro", "185.97.188.0/22"],
            ["Norsk Hydro", "149.209.0.0/16"],
            ["Politiets IKT-tjenester", "163.174.0.0/18"]
        );
    }

    private function getWikis(): array
    {
        return array(
            "en",
            "no"
        );
    }

    public function getContributions(): array
    {
        $value = $this->cache->get('contributions', function(ItemInterface $item): array {
            $item->expiresAfter(1800);

            // Variable to return
            $contributions = array();
            // Loop through all wikis
            foreach ($this->getWikis() as $wiki) {
                // Loop through all IP ranges
                foreach ($this->getRanges() as $range) {
                    $name = $range[0];
                    $ip = $range[1];

                    // request wiki for contributions
                    $response = $this->client->request(
                        'GET',
                        "https://{$wiki}.wikipedia.org/w/api.php", [
                            'query' => [
                                'action' => 'query',
                                'format' => 'json',
                                'list' => 'usercontribs',
                                'uciprange' => $ip,
                                'uclimit' => 500
                            ]
                        ]
                    );

                    $contribsArray = $response->toArray();

                    $currentContribs = $contribsArray["query"]["usercontribs"];
                    foreach ($currentContribs as $contrib) {
                        // TODO: add feature to not duplicate entries
                        // Set date of contribution
                        $dateTime = new \DateTime($contrib["timestamp"]);
                        // create new contribution
                        $contribution = new Contribution();
                        $contribution->setLink("https://{$wiki}.wikipedia.org/w/index.php/?title={$contrib["title"]}&diff=prev&oldid={$contrib["revid"]}");
                        $contribution->setTitle($contrib["title"]);
                        $contribution->setIp($contrib["user"]);
                        $contribution->setIprange($ip);
                        $contribution->setComment($contrib["comment"]);
                        $contribution->setDate($dateTime->getTimestamp());
                        $contribution->setRevid($contrib["revid"]);
                        $contribution->setBody($name);
                        $contribution->setSize($contrib["size"]);
                        $contribution->setWiki($wiki);

                        // Add to contributions array
                        $contributions[] = $contribution;
                    }
                }
            }

            return $contributions;
        });

        return $value;
    }
}