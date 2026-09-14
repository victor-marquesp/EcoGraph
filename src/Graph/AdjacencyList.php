<?php

namespace App\Graph;

use Override;

class AdjacencyList implements GraphRepresentation {

    /** @var array<int, array<int>> */
    public private(set) array $adjacency = []; 

    #[Override]
    public function addNode(Node $node) : void {

        $this->adjacency[$node->id] = [];

    }

    #[Override]
    public function addEdge(Edge $edge) : void {

        $this->adjacency[$edge->source->id][] = $edge->id;
        $this->adjacency[$edge->target->id][] = $edge->id;

    }

}
