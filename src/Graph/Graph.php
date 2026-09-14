<?php

namespace App\Graph;

class Graph {

    /** @var array<int, Node> */
    public private(set) array $nodes;

    /** @var array<int, Edge> */
    public private(set) array $edges;

    public private(set) GraphRepresentation $representation;

    public function __construct(GraphRepresentation $representation) {
        $this->representation = $representation;
    }
 
    public function addNode(Node $node) : void {
        
        $this->nodes[$node->id] = $node;
        $this->representation->addNode($node);
        
    }

    public function addEdge(Edge $edge) : void {
        
        $this->edges[$edge->id] = $edge;
        $this->representation->addEdge($edge);

    }

}
