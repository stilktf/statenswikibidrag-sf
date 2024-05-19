<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\ContributionGetter;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedJsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class IndexController extends AbstractController
{
    #[Route('/')]
    public function index(ContributionGetter $contributionGetter, LoggerInterface $logger): Response
    {
        $contributions = $contributionGetter->getContributions();
        $logger->info("The type of contributions array is..." . gettype($contributions));
        return $this->render("index/index.html.twig", [
            'contribs' => $contributions,
        ]);
    }

    #[Route('/json')]
    public function json_response(ContributionGetter $contributionGetter, LoggerInterface $logger): Response
    {
        $contributions = $contributionGetter->getContributions();

        return new JsonResponse($contributions);
    }

    #[Route('/bidrag.rss')]
    public function rss_response(ContributionGetter $contributionGetter, LoggerInterface $logger): Response
    {
        $contributions = $contributionGetter->getContributions();

        $contents = $this->renderView("index/bidrag.rss.twig", [
            'contributions' => $contributions,
        ]);

        $response = new Response($contents);

        $response->headers->set('Content-Type', 'text/xml');

        return $response;
    }
}
