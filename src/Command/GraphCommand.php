<?php

namespace App\Command;

use App\Graph\GraphBuilder;
use App\Graph\Node;
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

        $output->writeln('===============');
        $output->writeln('ECO GRAPH:');
        $output->writeln('===============');
        $output->writeln('Total Nodes: ' .$graph->nodeCount());
        $output->writeln('Total Edges: ' .$graph->edgeCount());
        $output->writeln('---------------');
        
        $this->displayNodes($graph->nodes, $output);  
        $this->displayEdges($graph->edges, $output);   
        $output->writeln('===============');
        readline('...');

        return Command::SUCCESS;
    }

    private function displayNodes(array $nodes, OutputInterface $output) {

        $output->writeln('NODES: ');
        $output->writeln('---------------');

        foreach($nodes as $node) {
            $output->writeln('Node #' .$node->id);
            $output->writeln('  Labels: ' .$this->getLabels($node));
            $output->writeln('  Name: ' .$node->properties['name']);
        }
    }

    private function displayEdges(array $edges, OutputInterface $output) {

        $output->writeln('EDGES: ');
        $output->writeln('---------------');

        foreach($edges as $edge) {
            $output->writeln('Edge #' .$edge->id);
            $output->writeln('  Species: ' .$edge->source->properties['name'] .' - ' .$edge->target->properties['name']);
            $output->writeln('  Type: ' .$edge->type->value);
        }
    }

    private function getLabels(Node $node) : string {
        $labels = '';

        foreach($node->labels as $label) {
            $labels .= $label;
        }

        return $labels;
    }

}
