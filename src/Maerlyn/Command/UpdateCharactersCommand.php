<?php

namespace Maerlyn\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateCharactersCommand extends Command {
    public function configure()
    {
        $this
            ->setName("tibiahu:update-characters")
            ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("hello");
    }
}
