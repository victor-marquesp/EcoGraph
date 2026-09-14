<?php

namespace App\Graph;

interface GraphRepresentation {

    public function addNode(Node $node);

    public function addEdge(Edge $edge);

}
