<?php

namespace App\Graph;

use App\Enums\Type;

class Edge {

    public private(set) int $id;

    public private(set) Node $source;
    public private(set) Node $target;
    
    public private(set) Type $type;

    public private(set) array $properties;

    public function __construct(int $id, Node $source, Node $target, Type $type, array $properties) {
        $this->id = $id;
        $this->source = $source;
        $this->target = $target;
        $this->type = $type;
        $this->properties = $properties;
    }

}
