<?php

class TibiahuUpdateCharactersTask extends sfBaseTask
{

  protected function configure()
  {
    $this->namespace = "tibiahu";
    $this->name = "update-characters";
    $this->briefDescription = "Ellenoriz 10k karaktert a legutobb mentett offsettol";
    
    $this->detailedDescription = <<<EOF
Beallitasok kozt mentett id-tol szamitva 10k karit ellenoriz.
Karilapot leker, kezeli a torolt, atnevezett karaktereket.
EOF;
  }
  
  protected function execute($arguments = array(), $options = array())
  {
    ini_set("memory_limit", "128M");
    $databaseManager = new sfDatabaseManager($this->configuration);
    
    $last_id = SettingPeer::get("task.update-characters.last-id");
    $update_count = SettingPeer::get("task.update-characters.count");
    
    $this->logSection("update", "kezdunk a {$last_id->get()} indextol, probalunk {$update_count->get()} karit frissiteni");
    $characters = CharacterPeer::getForUpdate($last_id->get(), $update_count->get());

    $deleted = $renamed = $forbidden = $banishments = 0;
    $is_aborted = false;
    foreach ($characters as $character) {
      set_time_limit(15);
      
      if (!$info = TibiaWebsite::characterInfo($character->getName())) {
        sleep(5);
        continue;
      }
      //echo $character->getName() . " - " . count($info) . "\n";
      
      try {
        if (is_null($info)) { //deleted
          $this->logSection("delete", $character->getName() . " ({$character->getId()})");
          $character->delete();
          ++$deleted;
          continue;
        }
        else
        if ($info === null) {
          $this->logSection("error", "403, varakozas es uj proba (aktualis index: {$character->getId()})");
          ++$forbidden;
          sleep(10);
          set_time_limit(15);
          $info = TibiaWebsite::characterInfo($character->getName());
          if ($info == "403") {
            $last_id->set($character->getId() - 1);
            $last_id->save();
            $this->logSection("update", "ismet 403, utolso indexnek mentve a {$last_id->get()}");
            $is_aborted = true;
            break;
          }
        }
        
        //minden rendben, mehet a feldolgozas
        if (isset($info["name"]) && $character->getName() != $info["name"]) { //rename
          
          if ($character_new = CharacterPeer::retrieveByName($info["name"])) { //ha mar megvan az uj, egyesites
            //regi lvlupjainak atvezetese az ujhoz, regi torlese
            $this->logSection("rename", "{$character->getName()} # {$info["name"]} ({$character->getId()} # {$character_new->getId()})");            
            $selectCriteria = new Criteria();
            $selectCriteria->add(LevelHistoryPeer::CHARACTER_ID, $character->getId());
            $updateCriteria = new Criteria();
            $updateCriteria->add(LevelHistoryPeer::CHARACTER_ID, $character_new->getId());
            BasePeer::doUpdate($selectCriteria, $updateCriteria, Propel::getConnection());
            
            $character_new->setCreatedAt($character->getCreatedAt());
            $character_new->save();
            
            if ($guild = $character->getGuild()) {
              $guild->setMembers($guild->getMembers() - 1);
              $guild->save();
            }
            $character->delete();
            
          } else { //meg nem talalkoztunk az uj karival, sima atnevezes
            $this->logSection("rename", "{$character->getName()} # {$info["name"]}");
            $character->setName($info["name"]);
            $character->save();
          }
          
          ++$renamed;
        }
        
        if (isset($info["profession"]) && !$character->isDeleted() && $character->getVocation() != $info["profession"]) {
          $this->logSection("vocation", "{$character->getName()} ({$character->getVocation()} -> {$info["profession"]})");
          $character->setVocation($info["profession"]);
          $character->save();
        }
        
        if (isset($info["banishment"]) && 
            is_array($info["banishment"]) && 
            (
              $info["banishment"]["reason"] == BanishmentPeer::REASON_UNOFFICIAL ||
              $info["banishment"]["reason"] == BanishmentPeer::REASON_ACCTRADE ||
              $info["banishment"]["reason"] == BanishmentPeer::REASON_HACKING
            )
           ) {
          
          $this->logSection("banishment", $character->getName() . " ({$info["banishment"]["reason"]})");
          ++$banishments;
          if (!$ban = BanishmentPeer::retrieveByCharacterIdAndReason($character->getId(), $info["banishment"]["reason"])) {
            $ban = new Banishment();
            $ban->setCharacterId($character->getId());
            $ban->setBanishedUntil($info["banishment"]["until"]);
            $ban->setBanishedAt(Tibiahu::guessBanishmentDate($info["banishment"]["until"]));
            $ban->setLevel($info["level"]);
          } else {
            if ($ban->getBanishedUntil("U") != $info["banishment"]["reason"]) {
              $ban->setBanishedUntil($info["banishment"]["until"]);
              if ($info["banishment"]["until"] != 0) { //ban until deletion
                $ban->setBanishedAt(Tibiahu::guessBanishmentDate($info["banishment"]["until"]));
              }
              $ban->setLevel($info["level"]);
            }
          }
          $ban->setReason($info["banishment"]["reason"]);
          $ban->save();
        }
         
//        if (!$character->isDeleted()) {  
//          $character->save();
//        }
      }
      catch (Exception $e) {
        echo("MENTESI HIBA: " . $e->getMessage() . "\n");
      }
      
      usleep(300000);
    }
    
    if (!$is_aborted) {
      $last_id->set(count($characters) == $update_count->get() ? end($characters)->getId() : 0);
      $last_id->save();
    }

    $cronlog = new CronLog();
    $cronlog->setType("characters");
    $cronlog->setData(serialize(array(
      "deleted"    => $deleted,
      "renamed"    => $renamed,
      "forbidden"  => $forbidden,
      "banishment" => $banishments
    )));
    $cronlog->save();
    
    $this->logSection("update", "vegeztunk a {$last_id->get()} indexnel");
    $this->logSection("update", "{$deleted} torolt, {$renamed} atnevezett kari, {$forbidden} forbidden, {$banishments} banishment");
  }

}
