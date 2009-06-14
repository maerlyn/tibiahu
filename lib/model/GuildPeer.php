<?php

class GuildPeer extends BaseGuildPeer
{

  public static function retrieveByName($name)
  {
    $c = new Criteria();
    $c->add(GuildPeer::NAME, $name);
    return parent::doSelectOne($c);
  }
  
  public static function getAllForAdvancedSearch()
  {
    $c = new Criteria();
    $c->addAscendingOrderByColumn(GuildPeer::NAME);
    $temp = parent::doSelect($c);
    $ret = array();
    foreach ($temp as $guild) {
      $ret["{$guild->getId()}"] = $guild->getName();
    }
    return $ret;
  }
  
  public static function getAllForServer($server_id)
  {
    $c = new Criteria();
    $c->add(GuildPeer::SERVER_ID, $server_id);
    $c->addAscendingOrderByColumn(GuildPeer::NAME);
    return parent::doSelect($c);
  }
  
  public static function getGuildsLike($search)
  {
    $c = new Criteria();
    $c->add(GuildPeer::NAME, $search, Criteria::LIKE);
    $c->addAscendingOrderByColumn(GuildPeer::NAME);
    return parent::doSelectJoinServer($c);
  }

}
