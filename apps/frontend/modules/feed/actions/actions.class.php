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
    foreach ($levelhistory as $lvlhistory) { /** @var LevelHistory $lvlhistory */
      $item = new sfFeedItem();
      
      $this->getContext()->getConfiguration()->loadHelpers(array("Date"));
    
      $isDeath = null;;
      if (null !== $lvlhistory->getIsDeath()) {
        $isDeath = $lvlhistory->getIsDeath();
      } else
      if (null !== ($prev_item = LevelHistoryPeer::getPreviousItem($lvlhistory))) {
        $isDeath = ($prev_item->getLevel() >= $lvlhistory->getLevel());
      }
      
      if ($isDeath !== null) {
        if (!$isDeath) {
          $lvlupdown = $this->getContext()->getI18N()->__("Szintlépés \\o/", null, "feed");
        } else {
          $lvlupdown = $this->getContext()->getI18N()->__("Halál :(", null, "feed");
        }
      } else {
        $lvlupdown = "";
      }
      
      $content = $lvlupdown . "<br />\n" . 
        sprintf($this->getContext()->getI18N()->__("%s<br />\nÚj szint: %d<br />\nDátum: %s<br />\nOk: %s", null, "feed"),
          $lvlhistory->getCharacter()->getName(),
          $lvlhistory->getLevel(),
          format_datetime($lvlhistory->getCreatedAt()),
          $lvlhistory->getReason()
        );
      
    
      $item->initialize(array(
        "title"       => $lvlhistory->getCharacter()->getName(),
        "link"        => $this->generateUrl("character_show", $lvlhistory->getCharacter(), true),
        "pubDate"     => $lvlhistory->getCreatedAt("U"),
        "uniqueId"    => $lvlhistory->getId(),
        "content"     => $content
      ));
      
      $feed->addItem($item);
    }
  }
  
  public function executeGuild(sfWebRequest $request)
  {
    $guild = $this->getRoute()->getObject();
    $levelhistory = LevelHistoryPeer::getForGuild($guild->getId(), 15);
    
    $feed = new sfAtom1Feed();
    $feed->initialize(array(
      "title"      => sprintf($this->getContext()->getI18N()->__("A %s guild tagjainak szintlépései", null, "feed"), $guild->getName()),
      "link"       => $this->generateUrl("guild_feed", $guild, true),
      "authorName" => $this->getContext()->getI18N()->__("Magyar Tibia rajongói oldal")
    ));
    $this->generateFeedForHistory($feed, $levelhistory);
    $this->renderText($feed->asXml());
    
    return sfView::NONE;
  }
  
  public function executeCharacter(sfWebRequest $request)
  {
    $char = $this->getRoute()->getObject();
    $levelhistory = LevelHistoryPeer::getForCharacter($char, 15);
    
    $feed = new sfAtom1Feed();
    $feed->initialize(array(
      "title"      => sprintf($this->getContext()->getI18N()->__("%s szintváltozásai", null, "feed"), $char->getName()),
      "link"       => $this->generateUrl("character_feed", $char, true),
      "authorName" => $this->getContext()->getI18N()->__("Magyar Tibia rajongói oldal"),
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
   
    $this->getContext()->getConfiguration()->loadHelpers(array("Date"));
  
    $feed = new sfAtom1Feed();
    $feed->initialize(array(
      "title"      => sprintf($this->getContext()->getI18N()->__("%si %s", null, "feed"), $server->getName(), $this->getContext()->getI18N()->__($reason, null, "feed")),
      //ucfirst($request->getParameter("reason")) . " feed for " . $server->getName(),
      "link"       => $this->generateUrl("character_banfeed", array("reason" => $request->getParameter("reason"), "server" => $server->getName()), true),
#      "link"       => url_for("@character_banfeed?reason=" . $request->getParameter("reason") . "&server=" . $server->getName(), true),
      "authorName" => $this->getContext()->getI18N()->__("Magyar Tibia rajongói oldal")
    ));
    
    foreach ($characters as $character) {
      $item = new sfFeedItem();
      
      $content = sprintf($this->getContext()->getI18N()->__("Bannolva ekkor: %s<br />\nBannolva eddig: %s<br />\nKaszt: %s<br />\nSzint: %d", null, "feed"),
        format_datetime($character->getBanishedAt()),
        format_datetime($character->getBanishedUntil()),
        $character->getVocation(),
        $character->getLevel()
      );
    
      $item->initialize(array(
        "title"       => $character->getName(),
        #"link"        => url_for("@character_show?slug=" . $character->getSlug(), true),
        "link"        => $this->generateUrl("character_show", $character, true),
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
    $feed = new sfAtom1Feed();
    $feed->initialize(array(
      "title"      => $this->getContext()->getI18N()->__("Hírek feed", null, "feed") . " # " . $this->getContext()->getI18N()->__("Magyar Tibia rajongói oldal"),
      #"link"       => url_for("@news_feed", true),
      "link"       => $this->generateUrl("news_feed", array(), true),
      "authorName" => $this->getContext()->getI18N()->__("Magyar Tibia rajongói oldal")
    ));
    
    $news = NewsPeer::getLast(sfConfig::get("app_max_news_on_index", 10));
    foreach ($news as $news_item) {
      $item = new sfFeedItem();
    
      $item->initialize(array(
        "title"       => $news_item->getTitle(),
        #"link"        => url_for(sprintf("@news_show?id=%s&slug=%s", $news_item->getId(), $news_item->getSlug())),
        "link"        => $this->generateUrl("news_show", $news_item, true),
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
