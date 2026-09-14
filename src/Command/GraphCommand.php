<?php

namespace App\Command;

use App\Graph\GraphBuilder;
use App\Repositories\InterspecificInteractionRepository;
use App\Repositories\SpeciesRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;

class GraphCommand {

    public function __construct(
        private GraphBuilder $graphBuilder,
        private SpeciesRepository $speciesRepository,
        private InterspecificInteractionRepository $interactionRepository
    ) {}

    #[AsCommand(
        name: 'graph:build|graph',
        description: 'builds the graph and display it'
    )]
    public function buildGraph(OutputInterface $output) : int {

        $species = $this->speciesRepository->getAll();
        $interactions = $this->interactionRepository->getAll();

        $graph = $this->graphBuilder->buildGraph($species, $interactions);

        $output->writeln('---------------');
        print_r($graph);
        readline('...');

        return Command::SUCCESS;
    }

}
