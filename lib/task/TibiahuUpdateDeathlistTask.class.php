<?php

class TibiahuUpdateDeathlistTask extends sfBaseTask
{

  protected function configure()
  {
    $this->namespace = "tibiahu";
    $this->name = "update-deathlist";
    $this->briefDescription = "Az elmult 24 oraban online levo karik halalainak frissitese";
    
    $this->detailedDescription = <<<EOF
Sajat nyilvantartas szerint az elmult 24 oraban online karik halalainak listajat lekeri tibia.com-rol,
ha meg nincs az adatbazisban, berakja.
Akkor hasznos, ha valaki hal, de a szintje ezzel nem valtozik.
EOF;
  }
  
  protected function execute($arguments = array(), $options = array())
  {
    ini_set("memory_limit", "128M");
    $databaseManager = new sfDatabaseManager($this->configuration);
    
    $c = new Criteria();
    $c->add(CharacterPeer::LAST_SEEN, time()-11000, Criteria::GREATER_THAN); //bo 3 ora
    $characters = CharacterPeer::doSelect($c);
    $this->logSection('update', sprintf("%d karakter online az elmult 3 oraban", count($characters)));
    
    $inserts = 0;
    foreach ($characters as $character) {
      set_time_limit(15);
      $deaths = TibiaWebsite::getDeaths($character->getName());
      if ($deaths === null) {
        echo("403 forbidden, megallas ({$character->getName()})\n");
        return;
      }
      
      if (is_array($deaths)) {
        foreach ($deaths as $death) {
          $c = new Criteria();
          $c->add(LevelHistoryPeer::CHARACTER_ID, $character->getId());
          $c->add(LevelHistoryPeer::CREATED_AT, $death["time"]);
          
          if (!LevelHistoryPeer::doCount($c)) {
            $lvlh = new LevelHistory();
            $lvlh->setCharacterId($character->getId());
            $lvlh->setCreatedAt($death["time"]);
            $lvlh->setReason($death["reason"]);
            $lvlh->setLevel($death["level"]);
            
            try {
              $lvlh->save();
              ++$inserts;
            }
            catch (Exception $e) {
              echo("MENTESI HIBA\n" . $e->getMessage() . "\n");
            }
          }
        }
      }
      usleep(300);
    }
    
    $this->logSection("update", "{$inserts} uj halal");
  }

}
