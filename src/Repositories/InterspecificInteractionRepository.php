<?php

namespace App\Repositories;

use App\Models\InterspecificInteraction;
use App\Enums\Type;
use App\Exceptions\InterspecificInteractionNotFoundException;
use PDO;

class InterspecificInteractionRepository {

    public function __construct(
        private PDO $connection
    ) {}

    /** @return array<InterspecificInteraction> */
    public function getAll() : array {

        $query = $this->connection->query('SELECT * FROM interspecific_interactions');
        $interactionList = $query->fetchAll(PDO::FETCH_ASSOC);

        return array_map(
            fn($interaction) => InterspecificInteraction::fromArray($interaction),
            $interactionList
        );
    }

    /** @return array<InterspecificInteraction> */
    public function getByType(Type $type) : array {

        $query = $this->connection->prepare('
            SELECT * FROM interspecific_interactions 
            WHERE type = :type'
        );

        $query->execute([
            'type' => $type->value
        ]);

        $interactionList = $query->fetchAll(PDO::FETCH_ASSOC);

        return array_map(
            fn($interaction) => InterspecificInteraction::fromArray($interaction),
            $interactionList
        );
    }

    public function getById(int $speciesA_id, int $speciesB_id) : InterspecificInteraction {

        $query = $this->connection->prepare('
            SELECT * FROM interspecific_interactions 
            WHERE speciesA_id = :speciesA_id
            AND speciesB_id = :speciesB_id'
        );

        $query->execute([
            'speciesA_id' => $speciesA_id,
            'speciesB_id' => $speciesB_id,
        ]);

        $interaction = $query->fetch(PDO::FETCH_ASSOC);

        if($interaction === false) {
            throw new InterspecificInteractionNotFoundException('Interaction not found!');
        }

        return InterspecificInteraction::fromArray($interaction);
    }

    public function getBySpecies(int $speciesId) {

        $query = $this->connection->query('
            SELECT * FROM interspecific_interactions 
            WHERE speciesA_id = :speciesId OR speciesB_id = :speciesId'
        );

        $query->execute([
            'speciesId' => $speciesId
        ]);

        $interactionList = $query->fetchAll(PDO::FETCH_ASSOC);

        return array_map(
            fn($interaction) => InterspecificInteraction::fromArray($interaction),
            $interactionList
        );
    }

}