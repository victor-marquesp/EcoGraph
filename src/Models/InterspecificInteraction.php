<?php

namespace App\Models;

use App\Enums\Type;
use App\Exceptions\InvalidInteractionException;
use App\Exceptions\TooLongStringException;

class InterspecificInteraction {

    public private(set) int $speciesA_id;
    public private(set) int $speciesB_id {
        set(int $speciesB_id) {

            if($this->speciesA_id < $speciesB_id) {
                [$this->speciesA_id, $speciesB_id] = [$speciesB_id, $this->speciesA_id];
            }

            if($speciesB_id === $this->speciesA_id) {
                throw new InvalidInteractionException('Interaction: Intraspecific interactions not supported');
            }

            $this->speciesB_id = $speciesB_id;
        }
    }

    public private(set) Type $type;

    public private(set) ?string $description {
        set(?string $description) {

            if($description !== null && mb_strlen($description) >= 1000) {
                throw new TooLongStringException('Interaction: Description too long');
            }

            $this->description = $description;
        }
    }

    public function __construct(int $speciesA_id,  int $speciesB_id, Type $type, ?string $description) {
        $this->speciesA_id = $speciesA_id;
        $this->speciesB_id = $speciesB_id;
        $this->type = $type;
        $this->description = $description;
    }
 
    public static function fromArray(array $data) : self {
        return new self(
            speciesA_id: $data['speciesA_id'],
            speciesB_id: $data['speciesB_id'],
            type: Type::from($data['type']),
            description: $data['description'] ?? null
        );
    }
}
