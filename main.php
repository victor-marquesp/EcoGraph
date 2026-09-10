<?php

require 'vendor/autoload.php';

use App\Command\GreetCommand;
use App\Command\SpeciesCommand;
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

// Symfony Console Setup

$symfConsoleApp = new Application('EcoGraph');

$symfConsoleApp->addCommand(new GreetCommand());

$speciesCommands = new SpeciesCommand($speciesRepository);
$symfConsoleApp->addCommand($speciesCommands->findAll(...));
$symfConsoleApp->addCommand($speciesCommands->findById(...));
$symfConsoleApp->addCommand($speciesCommands->findByName(...));
$symfConsoleApp->addCommand($speciesCommands->findByScientificName(...));

// Start 

$symfConsoleApp->run();
