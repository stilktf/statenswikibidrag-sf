<?php

namespace App\Command;

use App\Service\ContributionGetter;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:getcontribs',
    description: 'Add a short description for your command',
)]
class GetcontribsCommand extends Command
{
    public function __construct(
        private ContributionGetter $contributionGetter
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Tell user we're getting contributions
        $io->comment("Getting contributions.");
        // Get contributions
        $contributions = $this->contributionGetter->getContributions();

        if(empty($contributions)) {
            $io->error('No contributions found.');
            return Command::FAILURE;
        } else {
            $io->success("Contributions found: " . count($contributions));
            return Command::SUCCESS;
        }
    }
}
