<?php

namespace App\Command;

use App\Exceptions\InterspecificInteractionNotFoundException;
use App\Models\InterspecificInteraction;
use App\Repositories\InterspecificInteractionRepository;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;

use App\Enums\Type;
use ValueError;

class InterspecificInteractionCommand {

    public function __construct(
        private InterspecificInteractionRepository $interactionRepository
    ) {}

    #[AsCommand(
        name: 'interaction:all|interaction:list|interactions',
        description: 'list all the interactions'
    )]
    public function findAll(OutputInterface $output) : int {

        $interactionList = $this->interactionRepository->getAll();

        $this->displayInteractionList($interactionList, $output);

        return Command::SUCCESS;
    }

    #[AsCommand(
        name: 'interaction:type',
        description: 'list all interactions with the provided type'
    )]
    public function findByType(#[Argument('type to search')] string $type, OutputInterface $output) : int {

        try {
            $type = Type::from($type);
        } catch(ValueError $e) {
            $this->failure($e->getMessage(), $output);
            return Command::INVALID;
        }

        $interactionList = $this->interactionRepository->getByType($type);

        $this->displayInteractionList($interactionList, $output);

        return Command::SUCCESS;
    }

    #[AsCommand(
        name: 'interaction:id',
        description: 'finds an interactions by its id'
    )]
    public function findById(
        #[Argument('species A of the ID')] int $speciesA_id, 
        #[Argument('species B of the ID')] int $speciesB_id, 
        OutputInterface $output
    ) : int {

        try {

            $interaction = $this->interactionRepository->getById($speciesA_id, $speciesB_id);

        } catch (InterspecificInteractionNotFoundException $e) {
            $this->failure($e->getMessage(), $output);
            return Command::FAILURE;
        }   

        $this->displayInteraction($interaction, $output);

        return Command::SUCCESS;
    }

    #[AsCommand(
        name: 'interaction:species',
        description: 'list all interactions of an species'
    )]
    public function findBySpecies(#[Argument('species id')] int $speciesId, OutputInterface $output) : int {

        $interactionList = $this->interactionRepository->getBySpecies($speciesId);

        $this->displayInteractionList($interactionList, $output);

        return Command::SUCCESS;
    }

    private function displayInteraction(InterspecificInteraction $interaction, OutputInterface $output) : void {

        $output->writeln('---------------');
        $output->writeln("ID: $interaction->speciesA_id - $interaction->speciesB_id");
        $output->writeln("Type: " .$interaction->type->value);
        $output->writeln("Desc: $interaction->description");
        $output->writeln('---------------');

        readline('...');
    }

    private function displayInteractionList(array $interactionList, OutputInterface $output) : void {

        $output->writeln('---------------');
        $output->writeln('INTERACTIONS');
        $output->writeln('---------------');

        foreach($interactionList as $interaction) {
            $output->writeln("[$interaction->speciesA_id + $interaction->speciesB_id] - $interaction->description");
        }

        readline('...');
    }

    private function failure(string $message, OutputInterface $output) : void {

        $output->writeln('---------------');
        $output->writeln('Oops: ' .$message);
        $output->writeln('---------------');

        readline('...');
    }

}
