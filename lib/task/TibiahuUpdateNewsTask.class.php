<?php

class TibiahuUpdateNewsTask extends sfBaseTask
{

  protected function configure()
  {
    $this->namespace = "tibiahu";
    $this->name = "update-news";
    $this->briefDescription = "Fetches every type of news from tibia.com";
    
    $this->detailedDescription = <<<EOF
Fetches the newsticker, the latest news and the featured article from tibia.com,
and stores it locally if it aint stored already.
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
      $this->logSection("newsticker", "new: " . $item["body"]);
      
      $database_item = new News();
      $database_item->setCreatedAt($item["date"]);
      $database_item->setCategoryId(NewsCategoryPeer::getNewstickerCategoryId());
      $database_item->setUserId(NewsPeer::getUseridForTibiaCom());
      
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
    
    $new_news_items = 0;
    $news_items = TibiaWebsite::getLatestNews();
    $last_news_item = SettingPeer::get("last.news");
    foreach ($news_items as $item) {
      if ($last_news_item->get() == $item["title"]) {
        break;
      }
      $this->logSection("news", "new: " . $item["title"]);
      
      $database_item = new News();
      $database_item->setCreatedAt($item["date"]);
      $database_item->setUserId(NewsPeer::getUseridForTibiaCom());
      $database_item->setCategoryId(NewsCategoryPeer::getNewsCategoryId());
      
      $database_item->setTitle($item["title"], "hu");
      $database_item->setTitle($item["title"], "en");
      
      $database_item->setBody($item["body"], "hu");
      $database_item->setBody($item["body"], "en");
      
      $database_item->save();
      ++$new_news_items;
    }
    $last_news_item->set($news_items[0]["title"]);
    $last_news_item->save();
    $this->logSection("news", $new_news_items . " new items");
    
    $item = TibiaWebsite::getFeaturedArticle();
    $last_featured_article = SettingPeer::get("last.featured_article");
    if ($last_featured_article->get() != $item["title"]) {
      $database_item = new News();
      $database_item->setCreatedAt($item["date"]);
      $database_item->setUserId(NewsPeer::getUseridForTibiaCom());
      $database_item->setCategoryId(NewsCategoryPeer::getFeaturedArticlesCategoryId());
      
      $database_item->setTitle($item["title"], "hu");
      $database_item->setTitle($item["title"], "en");
      
      $database_item->setBody($item["body"], "hu");
      $database_item->setBody($item["body"], "en");
      
      $database_item->save();
      $last_featured_article->set($item["title"]);
      $last_featured_article->save();
      
      $this->logSection("featured", "New featured article: " . $item["title"]);
    } else {
      $this->logSection("featured", "No new featured article");
    }
    
  }
  
}
