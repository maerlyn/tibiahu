<?php

class Banishment extends BaseBanishment
{
  
  public function getReason()
  {
    return BanishmentPeer::getReasonFromIndex($this->getBanishedForId());
  }
  
  public function setReason($value)
  {
    return $this->setBanishedForId(BanishmentPeer::getReasonFromValue($value));
  }
  
}
