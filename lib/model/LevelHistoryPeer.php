<?php

class LevelHistoryPeer extends BaseLevelHistoryPeer
{
  public static function retrieveById($id)
  {
    $c = new Criteria();
    $c->add(LevelHistoryPeer::ID, $id);
    return parent::doSelectOne($c);
  }
  
  public static function getForCharacter(Character $char, $max = null)
  {
    $c = new Criteria();
    $c->add(LevelHistoryPeer::CHARACTER_ID, $char->getId());
    if ($max) { //we need it for the feed
      $c->setLimit($max);
      $c->addDescendingOrderByColumn(LevelHistoryPeer::CREATED_AT);
    } else { //null, we need it for the character page
      $c->addAscendingOrderByColumn(LevelHistoryPeer::CREATED_AT);
    }
    return parent::doSelectJoinAll($c);
  }
  
  public static function getForGuild($guildid, $max = 15)
  {
    $c = new Criteria();
    $c->add(GuildPeer::ID, $guildid);
    $c->addJoin(LevelHistoryPeer::CHARACTER_ID, CharacterPeer::ID);
    $c->addJoin(CharacterPeer::GUILD_ID, GuildPeer::ID);
    $c->addDescendingOrderByColumn(LevelHistoryPeer::CREATED_AT);
    $c->setLimit($max);
    return parent::doSelectJoinCharacter($c);
  }
  
  public static function getPreviousItem(LevelHistory $lvlhistory)
  {
    $c = new Criteria();
    $c->add(LevelHistoryPeer::CHARACTER_ID, $lvlhistory->getCharacter()->getId());
    $c->add(LevelHistoryPeer::CREATED_AT, $lvlhistory->getCreatedAt(), Criteria::LESS_THAN);
    $c->addDescendingOrderByColumn(LevelHistoryPeer::CREATED_AT);
    return LevelHistoryPeer::doSelectOne($c);
  }
  
}
