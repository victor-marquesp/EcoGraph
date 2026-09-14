<?php

namespace App\Graph;

use App\Models\Species;
use App\Models\InterspecificInteraction;

class GraphBuilder {

    /** @param array<Species> $speciesList 
     * @param array<InterspecificInteraction> $interactions 
     * */
    public function buildGraph(array $speciesList, array $interactions) : Graph {

        $graph = new Graph(new AdjacencyList());

        // Add Nodes
        foreach($speciesList as $species) {

            $node = new Node(
                id: $species->id,
                labels: ['Species'],
                properties: $species->toNodeProperties()
            );

            $graph->addNode($node);

        }

        // Add Edges
        // Future fix: make Graph or Edge responsible for the id
        $id = 1;                    
        foreach($interactions as $interaction) {

            $edge = new Edge(
                id: $id,
                source: $graph->nodes[$interaction->speciesA_id],
                target: $graph->nodes[$interaction->speciesB_id],
                type: $interaction->type,
                properties: $interaction->toEdgeProperties()
            );

            $graph->addEdge($edge);

            $id++;
        }

        return $graph;
    }

}