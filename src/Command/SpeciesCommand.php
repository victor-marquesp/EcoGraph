<?php

namespace App\Command;

use App\Exceptions\SpeciesNotFoundException;
use App\Models\Species;
use App\Repositories\SpeciesRepository;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;

class SpeciesCommand {

    public function __construct(
        private SpeciesRepository $speciesRepository
    ) {}    

    #[AsCommand(
        name: 'species:all|species:list|species',
        description: 'list all species'
    )]
    public function findAll(OutputInterface $output) : int {

        $speciesList = $this->speciesRepository->getAll();

        $this->displaySpeciesList($speciesList, $output);    

        return Command::SUCCESS;
    }

    #[AsCommand(
        name: 'species:id',
        description: 'finds an species by its ID'
    )]
    public function findById(#[Argument('The species ID')] int $id, OutputInterface $output) : int {


        try {
            $species = $this->speciesRepository->getById($id);
        } catch (SpeciesNotFoundException $e) {

            $this->failure($e->getMessage(), $output);

            return Command::INVALID;
        }

        $this->displaySpecies($species, $output);

        return Command::SUCCESS;
    }

    #[AsCommand(
        name: 'species:name',
        description: 'finds all species with the provided name'
    )]
    public function findByName(#[Argument('The species Name')] string $name, OutputInterface $output) : int {

        $speciesList = $this->speciesRepository->getByName($name);

        $this->displaySpeciesList($speciesList, $output);

        return Command::SUCCESS;
    }

    #[AsCommand(
        name: 'species:sci|species:scientificName|species:sci-name',
        description: 'find an species by its Scientific Name'
    )]
    public function findByScientificName(
        #[Argument('The species ScientificName')] string $scientificName, 
        OutputInterface $output
    ) : int {

        try {

            $species = $this->speciesRepository->getByScientificName($scientificName);
            
        } catch (SpeciesNotFoundException $e) {

            $this->failure($e->getMessage(), $output);

            return Command::INVALID;
        }
        
        $this->displaySpecies($species, $output);

        return Command::SUCCESS;
    }

    private function displaySpecies(Species $species, OutputInterface $output) : void {

        $output->writeln('---------------');
        $output->writeln('ID: ' .$species->id);
        $output->writeln('Name: ' .$species->name);
        $output->writeln('Scientific Name: ' .$species->scientificName);
        $output->writeln('Desc: ' .$species->description);
        $output->writeln('---------------');

        readline('...');
    }

    private function displaySpeciesList(array $speciesList, OutputInterface $output) : void {

        $output->writeln('---------------');
        $output->writeln('SPECIES');
        $output->writeln('---------------');

        foreach($speciesList as $species) {
            $output->writeln("[$species->id] - $species->name ($species->scientificName)");
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
