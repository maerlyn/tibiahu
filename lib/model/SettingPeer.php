<?php

class SettingPeer extends BaseSettingPeer
{
  
  public static function get($key)
  {
    $c = new Criteria();
    $c->add(SettingPeer::KEY, $key);
    if (parent::doCount($c)) {
      return parent::doSelectOne($c);
    } else {
      $ret = new Setting();
      $ret->setKey($key);
      $ret->setValue(null);
      return $ret;
    }
  }
  
}
