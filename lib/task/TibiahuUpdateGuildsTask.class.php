<?php

class TibiahuUpdateGuildsTask extends sfBaseTask
{

  protected function configure()
  {
    $this->namespace = "tibiahu";
    $this->name = "update-guilds";
    $this->briefDescription = "Frissíti a guildek listajat";
    
    $this->detailedDescription = <<<EOF
A [tibiahu:update-guilds|INFO] task a tibia.com adatai alapjan frissitia  a guildek
listajat es az egyes karakterek guildbetartozását.
EOF;
  }
  
  protected function execute($arguments = array(), $options = array())
  {
    $databaseManager = new sfDatabaseManager($this->configuration);
    $con = Propel::getConnection();
    $task_start = time();
    
    $servers = ServerPeer::getAllEnabled();
    $sum_chars = $sum_guilds = 0;
    $had_empty_server = false;
    
    foreach ($servers as $server) {
      echo("Guilds for " . $server->getName() . "... ");
      $guilds = TibiaWebsite::getGuildList($server->getName());
      
      
      $sum_guilds += count($guilds);
      echo(count($guilds) . "\n");
      
      if (!count($guilds)) {
        $had_empty_server = true;
        continue;
      }
      
      foreach ($guilds as $guild) {
        set_time_limit(15);
        //echo($guild . "\n");
        if (!$g_db = GuildPeer::retrieveByName($guild)) {
          $g_db = new Guild();
          $g_db->setName($guild);
          $g_db->setServerId($server->getId());
        } else {
          $selectCriteria = new Criteria();
          $selectCriteria->add(CharacterPeer::GUILD_ID, $g_db->getId());
          $updateCriteria = new Criteria();
          $updateCriteria->add(CharacterPeer::GUILD_ID, null);
          BasePeer::doUpdate($selectCriteria, $updateCriteria, $con);
        }
        
        $g_db->setUpdatedAt(time());
        try {
          $g_db->save();
        }
        catch (Exception $e) {
          echo("{$guild}: MENTESI HIBA\n" . $e->getMessage() . "\n");
        }

        $tries = 0;
        $members = TibiaWebsite::getGuildMembers($guild);
        while (!count($members) && $tries < 5) {
          $this->logSection("error", "0 members for {$guild}, retrying...");
          set_time_limit(5 * $tries + 10);
          sleep(5 * $tries);
          $members = TibiaWebsite::getGuildMembers($guild);
          ++$tries;
        }
        $selectCriteria = new Criteria();
        $selectCriteria->add(CharacterPeer::NAME, $members, Criteria::IN);
        $updateCriteria = new Criteria();
        $updateCriteria->add(CharacterPeer::GUILD_ID, $g_db->getId());
        $affected_chars = BasePeer::doUpdate($selectCriteria, $updateCriteria, $con);
        $sum_chars += $affected_chars;
        $g_db->setMembers($affected_chars);
        try {
          $g_db->save();
        }
        catch (Exception $e) {
          echo("{$guild}: MENTESI HIBA\n" . $e->getMessage() . "\n");
        }    
        usleep(300);
      }

      usleep(500);
    }
    
    if (!$had_empty_server) {
      $deleteCriteria = new Criteria();
      $deleteCriteria->add(GuildPeer::UPDATED_AT, $task_start, Criteria::LESS_THAN);
      $deleted_guilds = BasePeer::doDelete($deleteCriteria, $con);
    }
    
    $cl = new CronLog();
    $cl->setType("guild");
    $cl->setData(serialize(array(
      "guilds"     => $sum_guilds,
      "characters" => $sum_chars
    )));
    $cl->save();
    
    $this->logSection('update', sprintf("%s guild frissitve", $sum_guilds));
    $this->logSection('update', sprintf("%s guild torolve", $deleted_guilds));
    $this->logSection('update', sprintf("%s karakter frissitve", $sum_chars));
  }

}