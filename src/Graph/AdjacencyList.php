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

    #[Override]
    public function hasEdge(int $sourceId, int $targetId) : int | bool {

        $sourceEdges = $this->adjacency[$sourceId];
        $targetEdges = $this->adjacency[$targetId];

        foreach ($sourceEdges as $sourceEdge) {

            foreach($targetEdges as $targetEdge) {
                if($sourceEdge === $targetEdge) {
                    return $sourceEdge;
                }
            }

        }

        return false;
    }

    #[Override]
    public function neighbors(int $nodeId) : array {

        return $this->adjacency[$nodeId];

    }

    #[Override]
    public function degree(int $nodeId) : int {

        return count($this->adjacency[$nodeId]);

    }

}
