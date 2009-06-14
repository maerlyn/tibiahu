<?php

class CronLogPeer extends BaseCronLogPeer
{

  public static function getTimeOfLastUpdate($type = "whoisonline")
  {
    $c = new Criteria();
    $c->add(CronLogPeer::TYPE, $type);
    $c->addDescendingOrderByColumn(CronLogPeer::CREATED_AT);
    $cl = parent::doSelectOne($c);
    return $cl->getCreatedAt("U");
  }

  public static function getLast($count, $type)
  {
    $c = new Criteria();
    $c->add(CronLogPeer::TYPE, $type);
    $c->addDescendingOrderByColumn(CronLogPeer::CREATED_AT);
    $c->setLimit($count);
    return parent::doSelect($c);
  }
  
}
