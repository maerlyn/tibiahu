<?php

/**
 * feed actions.
 *
 * @package    tibiahu
 * @subpackage feed
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class feedActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  
  private function generateFeedForHistory(sfFeed $feed, array $levelhistory)
  {
    foreach ($levelhistory as $lvlhistory) {
      $item = new sfFeedItem();
      
      $this->getContext()->getConfiguration()->loadHelpers(array("I18N", "Date"));
    
      if ($prev_item = LevelHistoryPeer::getPreviousItem($lvlhistory)) {
        if ($prev_item->getLevel() < $lvlhistory->getLevel()) {
          $lvlupdown = __("Szintlépés \\o/", null, "feed");
        } else {
          $lvlupdown = __("Halál :(", null, "feed");
        }
      } else {
        $lvlupdown = "";
      }
      
      $content = $lvlupdown . "\n" . 
        sprintf(__("%s\nÚj szint: %d\nDátum: %s\nOk: %s", null, "feed"),
          $lvlhistory->getCharacter()->getName(),
          $lvlhistory->getLevel(),
          format_datetime($lvlhistory->getCreatedAt()),
          $lvlhistory->getReason()
        );
      
    
      $item->initialize(array(
        "title"       => $lvlhistory->getCharacter()->getName(),
        "link"        => url_for(array("sf_route" => "character_show", "sf_subject" => $lvlhistory->getCharacter())),
        "pubDate"     => $lvlhistory->getCreatedAt("U"),
        "uniqueId"    => $lvlhistory->getId(),
        "content"     => $content
      ));
      
      $feed->addItem($item);
    }
  }
  
  public function executeGuild(sfWebRequest $request)
  {
    $this->getContext()->getConfiguration()->loadHelpers(array("Url", "I18N"));
  
    $guild = $this->getRoute()->getObject();
    $levelhistory = LevelHistoryPeer::getForGuild($guild->getId(), 15);
    
    $feed = new sfAtom1Feed();
    $feed->initialize(array(
      "title"      => sprintf(__("A %s guild tagjainak szintlépései", null, "feed"), $guild->getName()),
      "link"       => url_for(array("sf_route" => "guild_feed", "sf_subject" => $guild), true),
      "authorName" => __("Magyar Tibia rajongói oldal")
    ));
    $this->generateFeedForHistory($feed, $levelhistory);
    $this->renderText($feed->asXml());
    
    return sfView::NONE;
  }
  
  public function executeCharacter(sfWebRequest $request)
  {
    $this->getContext()->getConfiguration()->loadHelpers(array("Url", "I18N"));
  
    $char = $this->getRoute()->getObject();
    $levelhistory = LevelHistoryPeer::getForCharacter($char, 15);
    
    $feed = new sfAtom1Feed();
    $feed->initialize(array(
      "title"      => sprintf(__("%s szintváltozásai", null, "feed"), $char->getName()),
      "link"       => url_for(array("sf_route" => "character_feed", "sf_subject" => $char), true),
      "authorName" => __("Magyar Tibia rajongói oldal")
    ));
    $this->generateFeedForHistory($feed, $levelhistory);
    $this->renderText($feed->asXml());
    
    return sfView::NONE;
  }
  
  public function executeBanishment(sfWebRequest $request)
  {
    $this->forward404Unless($server = ServerPeer::retrieveByName($request->getParameter("server")));
    
    switch ($request->getParameter("reason")) {
      case "botters":
        $characters = CharacterPeer::getBotters($server->getId(), "feed");
        $reason = "botterek";
        break;
        
      case "hackers":
        $characters = CharacterPeer::getHackers($server->getId(), "feed");
        $reason = "hackerek";
        break;
        
      case "acctraders":
        $characters = CharacterPeer::getAcctraders($server->getId(), "feed");
        $reason = "acctraderek";
        break;
    }
   
    $this->getContext()->getConfiguration()->loadHelpers(array("Url", "I18N", "Date"));
  
    $feed = new sfAtom1Feed();
    $feed->initialize(array(
      "title"      => sprintf(__("%si %s", null, "feed"), $server->getName(), __($reason, null, "feed")),
      //ucfirst($request->getParameter("reason")) . " feed for " . $server->getName(),
      "link"       => url_for("@character_banfeed?reason=" . $request->getParameter("reason") . "&server=" . $server->getName(), true),
      "authorName" => __("Magyar Tibia rajongói oldal")
    ));
    
    foreach ($characters as $character) {
      $item = new sfFeedItem();
      
      $content = sprintf(__("Bannolva ekkor: %s\nBannolva eddig: %s\nKaszt: %s\nSzint: %d", null, "feed"),
        format_datetime($character->getBanishedAt()),
        format_datetime($character->getBanishedUntil()),
        $character->getVocation(),
        $character->getLevel()
      );
    
      $item->initialize(array(
        "title"       => $character->getName(),
        "link"        => url_for("@character_show?slug=" . $character->getSlug(), true),
        "pubDate"     => $character->getBanishedAt(),
        "uniqueId"    => $character->getId(),
        "content"     => $content
      ));
      
      $feed->addItem($item);
    }

    $this->renderText($feed->asXml());   
    return sfView::NONE;
  }
  
  public function executeNews(sfWebRequest $request)
  {
    sfLoader::loadHelpers(array("Url", "I18N"));
  
    $feed = new sfAtom1Feed();
    $feed->initialize(array(
      "title"      => __("Hírek feed", null, "feed") . " # " . __("Magyar Tibia rajongói oldal"),
      "link"       => url_for("@news_feed", true),
      "authorName" => __("Magyar Tibia rajongói oldal")
    ));
    
    $news = NewsPeer::getLast(sfConfig::get("app_max_news_on_index", 10));
    foreach ($news as $news_item) {
      $item = new sfFeedItem();
    
      $item->initialize(array(
        "title"       => $news_item->getTitle(),
        "link"        => url_for(sprintf("@news_show?id=%s&slug=%s", $news_item->getId(), $news_item->getSlug())),
        "pubDate"     => $news_item->getCreatedAt("U"),
        "author"      => $news_item->getsfGuardUser(),
        "uniqueId"    => $news_item->getId(),
        "content"     => $news_item->getBody()
      ));
      
      $feed->addItem($item);
    }

    $this->renderText($feed->asXml());   
    return sfView::NONE;    
  }
  
  
}
