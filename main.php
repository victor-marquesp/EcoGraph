<?php

require 'vendor/autoload.php';

use App\Command\GraphCommand;
use App\Command\InterspecificInteractionCommand;
use App\Command\SpeciesCommand;
use App\Graph\GraphBuilder;
use App\Repositories\InterspecificInteractionRepository;
use App\Repositories\SpeciesRepository;
use Symfony\Component\Console\Application;

// Database Setup

try {
            
    $pdo = new PDO('sqlite:database/database.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {

    echo "Oops... PDO Exception: " .$e->getMessage();
    exit(1);
}

// Dependency Setup

$speciesRepository = new SpeciesRepository($pdo);
$interactionRepository = new InterspecificInteractionRepository($pdo);

$graphBuilder = new GraphBuilder();

// Symfony Console Setup

$symfConsoleApp = new Application('EcoGraph');

$speciesCommands = new SpeciesCommand($speciesRepository);
$symfConsoleApp->addCommand($speciesCommands->findAll(...));
$symfConsoleApp->addCommand($speciesCommands->findById(...));
$symfConsoleApp->addCommand($speciesCommands->findByName(...));
$symfConsoleApp->addCommand($speciesCommands->findByScientificName(...));

$interactionCommands = new InterspecificInteractionCommand($interactionRepository);
$symfConsoleApp->addCommand($interactionCommands->findAll(...));
$symfConsoleApp->addCommand($interactionCommands->findById(...));
$symfConsoleApp->addCommand($interactionCommands->findByType(...));
$symfConsoleApp->addCommand($interactionCommands->findBySpecies(...));

$graphCommands = new GraphCommand($graphBuilder, $speciesRepository, $interactionRepository);
$symfConsoleApp->addCommand($graphCommands->buildGraph(...));
// Start 

$symfConsoleApp->run();
