<?php

require 'vendor/autoload.php';

use App\Command\GreetCommand;
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

// Start 

$symfConsoleApp->run();
