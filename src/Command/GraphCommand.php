<?php

namespace App\Command;

use App\Graph\Edge;
use App\Graph\Graph;
use App\Graph\GraphBuilder;
use App\Graph\Node;
use App\Repositories\InterspecificInteractionRepository;
use App\Repositories\SpeciesRepository;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;

class GraphCommand {

    private Graph $graph;

    public function __construct(
        private GraphBuilder $graphBuilder,
        private SpeciesRepository $speciesRepository,
        private InterspecificInteractionRepository $interactionRepository
    ) {

        $species = $this->speciesRepository->getAll();
        $interactions = $this->interactionRepository->getAll();

        $this->graph = $this->graphBuilder->buildGraph($species, $interactions);
    }

    #[AsCommand(
        name: 'graph:build|graph',
        description: 'builds the graph and display it'
    )]
    public function buildGraph(OutputInterface $output) : int {  

        $this->displayMenu('build:graph', $output);
        $output->writeln('──────────────────');
        $output->writeln('Total Nodes: ' .$this->graph->nodeCount());
        $output->writeln('Total Edges: ' .$this->graph->edgeCount());
        
        $this->displayNodes($this->graph->nodes, $output);  
        $this->displayEdges($this->graph->edges, $output);   
        $output->writeln('──────────────────');

        readline('...');

        return Command::SUCCESS;
    }

    #[AsCommand(
        name: 'graph:neighbors',
        description: 'find the neighbors of an Node'
    )]
    public function findNeighbors(#[Argument('the node id')] int $nodeId, OutputInterface $output) : int {
        
        $this->displayMenu('graph:neighbors', $output);
        $neighbors = $this->graph->neighbors($nodeId);

        $this->displayNode($this->graph->getNode($nodeId), $output);
        $this->displayEdges($neighbors, $output);

        readline('...');

        return Command::SUCCESS;
    }

    #[AsCommand(
        name: 'graph:degree',
        description: 'calculates the degree of a node'
    )]
    public function findDegree(#[Argument('the node id')] int $nodeId, OutputInterface $output) : int {

        $this->displayMenu('graph:degree', $output);
        $node = $this->graph->getNode($nodeId);

        $this->displayNode($node, $output);
        $output->writeln('──────────────────');
        $output->writeln('DEGREE: ' .$this->graph->degree($nodeId));
        $output->writeln('──────────────────');

        readline('...');

        return Command::SUCCESS;
    }

    #[AsCommand(
        name: 'graph:between',
        description: 'finds the edge between two nodes'
    )]
    public function findEdge(
        #[Argument('the first node id')] int $sourceId,
        #[Argument('the second node id')] int $targetId, 
        OutputInterface $output
    ) {

        $this->displayMenu('graph:between', $output);

        $edge = $this->graph->hasEdge($sourceId, $targetId);

        if($edge) {

            $output->writeln('──────────────────');
            $output->writeln('INTERACTION FOUND!');
            $output->writeln('──────────────────');
            $this->displayEdge($edge, $output);

            readline('...');

            return Command::SUCCESS;
        }

        $output->writeln('──────────────────');
        $output->writeln('INTERACTION NOT FOUND BETWEEN NODES ' .$sourceId .' AND ' .$targetId);
        $output->writeln('──────────────────');

        readline('...');

        return Command::INVALID;
    }

    private function displayNodes(array $nodes, OutputInterface $output) {

        $output->writeln('──────────────────');
        $output->writeln('NODES: ');
        $output->writeln('──────────────────');

        foreach($nodes as $node) {
            $output->writeln('Node #' .$node->id);
            $output->writeln('  Labels: ' .$this->getLabels($node));
            $output->writeln('  Name: ' .$node->properties['name']);
        }
    }

    private function displayEdges(array $edges, OutputInterface $output) {

        $output->writeln('──────────────────');
        $output->writeln('EDGES: ');
        $output->writeln('──────────────────');

        foreach($edges as $edge) {
            $output->writeln('Edge #' .$edge->id);
            $output->writeln('  '
                .$edge->source->properties['name'] 
                .' ─────' .$edge->type->value .'───── ' 
                .$edge->target->properties['name']
            );
        }
    }

    private function displayNode(Node $node, OutputInterface $output) {

        $output->writeln('──────────────────');
        $output->writeln('NODE #' .$node->id);
        $output->writeln('──────────────────');
        $output->writeln('Labels: ' .$this->getLabels($node));
        $output->writeln('Name: ' .$node->properties['name']);
        $output->writeln('Scientific name: ' .$node->properties['scientific_name']);

    }

    private function displayEdge(Edge $edge, OutputInterface $output) {

        $output->writeln('EDGE #' .$edge->id);
        $output->writeln('  Relation: ');
        $output->writeln(
            '      '
            .$edge->source->properties['name'] 
            .' ─────' .$edge->type->value .'───── ' 
            .$edge->target->properties['name']
        );
        $output->writeln('Description: ' .$edge->properties['description']);

    }

    private function getLabels(Node $node) : string {
        $labels = '';

        foreach($node->labels as $label) {
            $labels .= $label;
        }

        return $labels;
    }

    private function displayMenu(string $command, OutputInterface $output) {

        $output->writeln('────────────────────────────────────────────────────────────────────────');
        $output->writeln("
        <fg=green>        
 _____         _____             _   
|   __|___ ___|   __|___ ___ ___| |_ 
|   __|  _| . |  |  |  _| .'| . |   |
|_____|___|___|_____|_| |__,|  _|_|_|
                            |_|      
        </>");

        $output->writeln("
        <fg=green>
                      _       _._
               _,,-''' ''-,_ }'._''.,_.=._
            ,-'      _ _    '        (  @)'-,
          ,'  _..==;;::_::'-     __..----'''}
         :  .'::_;==''       ,'',: : : '' '}
        }  '::-'            /   },: : : :_,'
       :  :'     _..,,_    '., '._-,,,--\'    _
      :  ;   .-'       :      '-, ';,__\.\_.-'
     {   '  :    _,,,   :__,,--::',,}___}^}_.-'
     }        _,'__''',  ;_.-''_.-'
    :      ,':-''  ';, ;  ;_..-'
_.-' }    ,',' ,''',  : ^^
_.-''{    { ; ; ,', '  :
      }   } :  ;_,' ;  }
       {   ',',___,'   '
   pils ',           ,'
          '-,,__,,-'

        </>
        ");
        $output->writeln('────────────────────────────────────────────────────────────────────────');
        $output->writeln('COMMAND: ' .$command);
        readline('proceed : any key to continue, ctrl c to exit ...');
    }

}
