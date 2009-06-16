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
    
      if ($prev_item = LevelHistoryPeer::getPreviousItem($lvlhistory)) {
        if ($prev_item->getLevel() < $lvlhistory->getLevel()) {
          $lvlupdown = "lvlup \o/";
        } else {
          $lvlupdown = "lvldown :'(";
        }
      } else {
        $lvlupdown = "";
      }
    
      $description = "{$lvlupdown}\n"
                   . "{$lvlhistory->getCharacter()->getName()}\n"
                   . "new level: {$lvlhistory->getLevel()}\n"
                   . "date: {$lvlhistory->getCreatedAt("Y. m. d. H:i")}\n"
                   . "reason: {$lvlhistory->getReason()}";

      $item->initialize(array(
        "title"       => $lvlhistory->getCharacter()->getName(),
        "link"        => url_for(array("sf_route" => "character_show", "sf_subject" => $lvlhistory->getCharacter())),
        "pubDate"     => $lvlhistory->getCreatedAt("U"),
        "uniqueId"    => $lvlhistory->getId(),
        "description" => $description
      ));
      
      $feed->addItem($item);
    }
  }
  
  public function executeGuild(sfWebRequest $request)
  {
    sfLoader::loadHelpers("Url");
  
    $guild = $this->getRoute()->getObject();
    $levelhistory = LevelHistoryPeer::getForGuild($guild->getId(), 15);
    
    $feed = new sfAtom1Feed();
    $feed->initialize(array(
      "title"      => $guild->getName() . " feed",
      "link"       => url_for(array("sf_route" => "guild_feed", "sf_subject" => $guild), true),
      "authorName" => "Magyar Tibia rajongói oldal"
    ));
    $this->generateFeedForHistory($feed, $levelhistory);
    $this->renderText($feed->asXml());
    
    return sfView::NONE;
  }
  
  public function executeCharacter(sfWebRequest $request)
  {
    sfLoader::loadHelpers("Url");
  
    $char = $this->getRoute()->getObject();
    $levelhistory = LevelHistoryPeer::getForCharacter($char, 15);
    
    $feed = new sfAtom1Feed();
    $feed->initialize(array(
      "title"      => $char->getName() . " feed",
      "link"       => url_for(array("sf_route" => "character_feed", "sf_subject" => $char), true),
      "authorName" => "Magyar Tibia rajongói oldal"
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
        break;
        
      case "hackers":
        $characters = CharacterPeer::getHackers($server->getId(), "feed");
        break;
        
      case "acctraders":
        $characters = CharacterPeer::getAcctraders($server->getId(), "feed");
        break;
    }
   
    sfLoader::loadHelpers(("Url"));
  
    $feed = new sfAtom1Feed();
    $feed->initialize(array(
      "title"      => ucfirst($request->getParameter("reason")) . " feed for " . $server->getName(),
      "link"       => url_for("@character_banfeed?reason=" . $request->getParameter("reason") . "&server=" . $server->getName(), true),
      "authorName" => "Magyar Tibia rajongói oldal"
    ));
    
    foreach ($characters as $character) {
      $item = new sfFeedItem();
    
      $description = "Banished at " . date("Y-m-d H:i:s", $character->getBanishedAt()) . "\n" 
                   . "Banished until " . date("Y-m-d H:i:s", $character->getBanishedUntil()) . "\n"
                   . "Vocation: " . $character->getVocation() . "\n"
                   . "Banished at level " . $character->getLevel();
                   
      $item->initialize(array(
        "title"       => $character->getName(),
        "link"        => url_for("@character_show?slug=" . $character->getSlug(), true),
        "pubDate"     => $character->getBanishedAt(),
        "uniqueId"    => $character->getId(),
        "description" => $description
      ));
      
      $feed->addItem($item);
    }

    $this->renderText($feed->asXml());   
    return sfView::NONE;
  }
  
}
