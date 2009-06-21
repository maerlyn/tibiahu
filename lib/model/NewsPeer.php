<?php

class NewsPeer extends BaseNewsPeer
{
  
  public static function getUseridForTibiaCom()
  {
    $c = new Criteria();
    $c->add(sfGuardUserPeer::USERNAME, "tibia.com");
    $user = sfGuardUserPeer::doSelectOne($c);
    return $user->getId();
  }
  
}
