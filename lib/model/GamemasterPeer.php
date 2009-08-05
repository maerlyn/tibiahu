<?php

class GamemasterPeer extends BaseGamemasterPeer
{
  
  public static function getForServer($server_id)
  {
    $c = new Criteria();
    $c->add(self::SERVER_ID, $server_id);
    $c->add(self::LAST_SEEN, null, Criteria::ISNOTNULL);
    $c->addDescendingOrderByColumn(self::LAST_SEEN);
    return parent::doSelect($c);
  }
  
}
