<?php

class CreaturePeer extends BaseCreaturePeer
{
  
  public static function creatureExists($name)
  {
    $c = new Criteria();
    $c->add(CreaturePeer::NAME, $name);
    return parent::doCount($c) !== 0;
  }
  
  public static function getIdForName($name)
  {
    $c = new Criteria();
    $c->add(CreaturePeer::NAME, $name);
    if ($creature = parent::doSelectOne($c)) {
      return $creature->getId();
    } else {
      return null;
    }
  }
  
}
