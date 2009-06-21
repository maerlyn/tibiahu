<?php

class TibiahuUpdateNewsTask extends sfBaseTask
{

  protected function configure()
  {
    $this->namespace = "tibiahu";
    $this->name = "update-news";
    $this->briefDescription = "Fetches every type of news from tibia.com";
    
    $this->detailedDescription = <<<EOF
Tibia.com-rol leszed mindenfele hirt, ami meg nincs helyben tarolva, beilleszti az adatbazisba.
EOF;
  }
  
  protected function execute($arguments = array(), $options = array())
  {
    ini_set("memory_limit", "128M");
    $databaseManager = new sfDatabaseManager($this->configuration);
    
    $new_newsticker_items = 0;
    $newsticker_items = TibiaWebsite::getNewsTicker();
    $last_newsticker_item = SettingPeer::get("last.newsticker");
    foreach ($newsticker_items as $item) {
      if ($item["body"] == $last_newsticker_item->get()) {
        break;
      }
      
      $database_item = new News();
      $database_item->setCreatedAt($item["date"]);
      $database_item->setCategoryId(NewsCategoryPeer::getNewstickerCategoryId());
      
      $database_item->setTitle("New newsticker item", "en");
      $database_item->setTitle("Új rövidír", "hu");
      
      $database_item->setBody($item["body"], "en");
      $database_item->setBody($item["body"], "hu");
      
      $database_item->save();
      ++$new_newsticker_items;
    }
    $last_newsticker_item->set($newsticker_items[0]["body"]);
    $last_newsticker_item->save();
    $this->logSection("newsticker", $new_newsticker_items . " new items");
  }
  
}
