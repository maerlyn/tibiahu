<?php

class TibiahuUpdateGamemastersTask extends sfBaseTask
{

  protected function configure()
  {
    $this->namespace = "tibiahu";
    $this->name = "update-gamemasters";
    $this->briefDescription = "Checks GMs for last login times";
    
    $this->detailedDescription = <<<EOF
Checks all GM characters on tibia.com to see if they still exist, and 
updates last_seen if neccesary.
EOF;
  }
  
  protected function execute($arguments = array(), $options = array())
  {
    ini_set("memory_limit", "128M");
    $databaseManager = new sfDatabaseManager($this->configuration);
    
    $gamemasters = GamemasterPeer::doSelect(new Criteria());
    foreach ($gamemasters as $gm) {
      if (TibiaWebsite::isGamemaster($gm->getName())) {
        $info = TibiaWebsite::characterInfo($gm->getName());
        if ($info["lastlogin"] != $gm->getLastSeen("U")) {
          $gm->setLastSeen($info["lastlogin"]);
          $gm->save();
          $this->logSection("update", $gm->getName());
        }
      } else {
        $gm->delete();
        $this->logSection("delete", $gm->getName());
      }
      
      usleep(200);
    }
  }
  
}
