<?php

class TibiahuUpdateCreaturesTask extends sfBaseTask
{

  protected function configure()
  {
    $this->namespace = "tibiahu";
    $this->name = "update-creatures";
    $this->briefDescription = "Tibia.wikia.com-rol frissiti a monsztak listajat";
    
    $this->detailedDescription = <<<EOF
A tibia.wikia.com-ról lekapja a List_of_Creatures lapot, majd helyi adatbázisba belapátolja.
EOF;
  }
  
  protected function execute($arguments = array(), $options = array())
  {
    $databaseManager = new sfDatabaseManager($this->configuration);
    
    $creatures = TibiaWebsite::getListOfCreatures();
    
    $new = 0;
    foreach($creatures as $creature) {
      $creature = trim(str_replace("(creature)", "", strtolower($creature)));
      if (!CreaturePeer::creatureExists($creature)) {
        $creature_obj = new Creature;
        $creature_obj->setName($creature);
        try {
          $creature_obj->save();
        }
        catch (Exception $e) {
          echo("MENTESI HIBA\n" . $e->getMessage() . "\n");
        }
        ++$new;
      }
    }
    
    $this->logSection('update', sprintf("%s leny frissitve", count($creatures)));
    $this->logSection("update", "{$new} uj leny mentve");
  }

}