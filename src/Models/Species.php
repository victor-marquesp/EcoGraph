<?php

namespace App\Models;

use App\Exceptions\IdAlreadySetException;
use App\Exceptions\NullDataException;
use App\Exceptions\TooLongStringException;

class Species {

    public ?int $id {
        set(?int $id) {

            if($id === null) {
                throw new NullDataException('Species: Null ID');
            }

            if(isset($this->id)) {
                throw new IdAlreadySetException('Species: Entity already has ID');
            }

            $this->id = $id;
        }
    }
    
    public private(set) string $name {
        set(string $name) {

            if(trim($name) === '') {
                throw new NullDataException('Species: Null name');
            }

            if(mb_strlen($name) > 120) {
                throw new TooLongStringException('Species: Name too long');
            }

            $this->name = trim($name);
        }
    }

    public private(set) string $scientificName {
        set(string $scientificName) {

            if(trim($scientificName) === '') {
                throw new NullDataException('Species: Null Scientific name');
            }

            if(mb_strlen($scientificName) > 120) {
                throw new TooLongStringException('Species: Scientific name too long');
            }

            $this->scientificName = trim($scientificName);
        }
    }

    public private(set) ?string $description {
        set(?string $description) {

            if($description !== null && mb_strlen($description) >= 1000) {
                throw new TooLongStringException('Species: Description too long');
            }

            $this->description = $description;
        }
    }

    public function __construct(string $name, string $scientificName, ?string $description, ?int $id) {
        $this->name = $name;
        $this->scientificName = $scientificName;
        $this->description = $description;

        if($id !== null) {
            $this->id = $id;
        }
    }

    public static function fromArray(array $data) : self {
        return new self(
            id: $data['id'],
            name: $data['name'],
            scientificName: $data['scientific_name'],
            description: $data['description'] ?? null
        );  
    }
}
