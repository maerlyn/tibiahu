<?php

class ServerPeer extends BaseServerPeer
{
  
  /**
  * Fetch all enabled servers
  * @return array all enabled servers
  */
  public static function getAllEnabled()
  {
    $c = new Criteria();
    $c->add(ServerPeer::IS_ENABLED, true);
    return parent::doSelect($c);
  }
  
  public static function retrieveByName($name)
  {
    $c = new Criteria();
    $c->add(ServerPeer::NAME, $name);
    return parent::doSelectOne($c);
  }
  
}
