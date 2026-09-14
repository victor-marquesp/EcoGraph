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

    public function getNode(int $nodeId) : Node {
        return $this->nodes[$nodeId];
    }

    public function getEdge(int $edgeId) : Edge {
        return $this->edges[$edgeId];
    }

    public function nodeCount() : int {
        return count($this->nodes);
    }

    public function edgeCount() : int {
        return count($this->edges);
    }

    public function hasEdge(int $sourceId, int $targetId) : Edge | bool {

        $edgeId = $this->representation->hasEdge($sourceId, $targetId);

        if($edgeId) {  
            return $this->edges[$edgeId];
        }

        return false;
    }

    /** @return Node[] */
    public function neighbors(int $nodeId) : array {

        $edgeIDs = $this->representation->neighbors($nodeId);

        $edges = [];
        foreach($edgeIDs as $id) {
            $edges[] = $this->edges[$id];
        }

        return $edges;
    }

    public function degree(int $nodeId) : int {
        return $this->representation->degree($nodeId);
    }


}
