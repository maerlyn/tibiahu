<?php

namespace Maerlyn\Command;

use Maerlyn\Tibia\TibiaDotCom;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateCharactersCommand extends Command {
    protected $app;

    public function __construct($app)
    {
        $this->app = $app;

        parent::__construct();
    }

    public function configure()
    {
        $this
            ->setName("tibiahu:update-characters")
            ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $app = $this->app;
        $tibiacom = new TibiaDotCom();
        $characters = $app["db.character"]->findAll();

        foreach ($characters as $character) {
            $info = $tibiacom->characterInfo($character["name"]);

            if (!$info["vocation"] != $character["vocation"]) {
                $character["vocation"] = $info["vocation"];
            }

            if ($info["level"] != $character["level"]) {
                $character["level"] = $info["level"];
            }

            $app["db.character"]->update($character, array("id" => $character["id"]));
        }
    }
}
