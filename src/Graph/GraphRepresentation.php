<?php

namespace App\Graph;

interface GraphRepresentation {

    public function addNode(Node $node) : void;

    public function addEdge(Edge $edge) : void;

    public function hasEdge(int $sourceId, int $targetId) : int | bool;

    public function neighbors(int $nodeId) : array;

    public function degree(int $nodeId) : int;

}
