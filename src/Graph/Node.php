<?php

namespace App\Graph; 

class Node {

    public private(set) int $id;

    /** @var string[] */
    public private(set) array $labels;

    /** @var array<string, mixed> */
    public private(set) array $properties;

    public function __construct(int $id, array $labels, array $properties) {
        $this->id = $id;
        $this->labels = $labels;
        $this->properties = $properties;
    }

}
