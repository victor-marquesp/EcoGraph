<?php

namespace App\Repositories;

use App\Exceptions\SpeciesNotFoundException;
use App\Models\Species;
use PDO;

class SpeciesRepository {

    public function __construct(
        private PDO $connection
    ) {}

    /** @return array<Species> */
    public function getAll() : array {

        $query = $this->connection->query('SELECT * FROM species');

        $speciesList = $query->fetchAll(PDO::FETCH_ASSOC);

        return array_map(
            fn ($species) => Species::fromArray($species),
            $speciesList
        );
    }

    public function getById(int $id) : Species {

        $query = $this->connection->prepare('SELECT * FROM species WHERE id = :id');
        $query->execute([
            'id' => $id
        ]);

        $species = $query->fetch(PDO::FETCH_ASSOC);

        if($species === false) {
            throw new SpeciesNotFoundException('Species not found!');
        }

        return Species::fromArray($species);
    }

    /** @return array<Species> */
    public function getByName(string $name) : array {

        $query = $this->connection->prepare('SELECT * FROM species WHERE name = :name');
        $query->execute([
            'name' => $name
        ]);

        $speciesList = $query->fetchAll(PDO::FETCH_ASSOC);

        return array_map(
            fn ($species) => Species::fromArray($species),
            $speciesList
        );
    }

    public function getByScientificName(string $scientificName) : Species {

        $query = $this->connection->prepare('SELECT * FROM species WHERE scientific_name = :scientificName');
        $query->execute([
            'scientificName' => $scientificName
        ]);

        $species = $query->fetch(PDO::FETCH_ASSOC);

        if($species === false) {
            throw new SpeciesNotFoundException('Species not found!');
        }

        return Species::fromArray($species);
    }

}
