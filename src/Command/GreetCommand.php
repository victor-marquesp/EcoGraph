<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;

#[AsCommand(name: 'greet', description: 'greets the user')]
class GreetCommand {

    public function __invoke() {

        echo "Hello!!! Welcome to EcoGraph\n";

        return Command::SUCCESS;

    }

}
