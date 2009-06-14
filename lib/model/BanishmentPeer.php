<?php

class BanishmentPeer extends BaseBanishmentPeer
{
  const REASON_UNOFFICIAL = "using unofficial software to play";
  const REASON_ACCTRADE   = "account trading or sharing";
  const REASON_HACKING    = "hacking";
  
  protected static $REASON_INTEGERS = array(
    0 => self::REASON_UNOFFICIAL,
    1 => self::REASON_ACCTRADE,
    2 => self::REASON_HACKING
  );
  protected static $REASON_VALUES = null;
  
  public static function getReasons()
  {
    return self::$REASON_INTEGERS;
  }

  public static function getReasonFromIndex($index)
  {
    return self::$REASON_INTEGERS[$index];
  }
  
  public static function getReasonFromValue($value)
  {
    if (!self::$REASON_VALUES) {
      self::$REASON_VALUES = array_flip(self::$REASON_INTEGERS);
    }
    
    if (!isset(self::$REASON_VALUES[$value])) {
      throw new PropelException(sprintf("Reason cannot take '%s' as a value", $value));
    }
    
    return self::$REASON_VALUES[$value];
  }
  
  public static function getViolations($character_id)
  {
    $c = new Criteria();
    $c->add(BanishmentPeer::CHARACTER_ID, $character_id);
    $c->addAscendingOrderByColumn(BanishmentPeer::BANISHED_FOR_ID);
    return parent::doSelect($c);
  }
  
  public static function retrieveByCharacterIdAndReason($character_id, $reason)
  {
    $c = new Criteria();
    $c->add(BanishmentPeer::CHARACTER_ID, $character_id);
    $c->add(BanishmentPeer::BANISHED_FOR_ID, self::getReasonFromValue($reason));
    return parent::doSelectOne($c);
  }
  
}
