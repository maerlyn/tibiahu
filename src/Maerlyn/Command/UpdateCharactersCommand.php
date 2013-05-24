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
        $app["monolog"]->addInfo("Updating " . count($characters) . " characters");

        foreach ($characters as $character) {
            $info = $tibiacom->characterInfo($character["name"]);
            $modified = false;

            if ($info["vocation"] != $character["vocation"]) {
                $character["vocation"] = $info["vocation"];
                $modified = true;
            }

            if ($info["level"] != $character["level"]) {
                if ($info["level"] > $character["level"]) {
                    // levelup
                    $app["db.levelhistory"]->insert(array(
                        "character_id"  =>  $character["id"],
                        "level"         =>  $info["level"],
                        "date"          =>  date("Y-m-d H:i:s"),
                        "is_death"      =>  0,
                    ));
                } else {
                    $deaths = $tibiacom->characterDeaths($character["name"]);
                    $death = isset($deaths[0]) ? $deaths[0] : null;

                    $app["db.levelhistory"]->insert(array(
                        "character_id"  =>  $character["id"],
                        "level"         =>  $info["level"],
                        "date"          =>  isset($death["date"]) ? $death["date"]->format("Y-m-d H:i:s") : date("Y-m-d H:i:s"),
                        "is_death"      =>  1,
                    ));
                }

                $character["level"] = $info["level"];
                $modified = true;
            }

            $is_online = $tibiacom->isOnline($character["name"]);
            if ($is_online != $character["is_online"]) {
                $character["is_online"] = ($is_online ? 1 : 0);
                $modified = true;
            }

            if ($modified) {
                $app["db.character"]->update($character, array("id" => $character["id"]));
            }
        }
    }
}
