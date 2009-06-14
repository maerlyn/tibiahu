<?php

class Character extends BaseCharacter
{
  private $banisment = null;

  public function wasOnlineLastTime()
  {
    return (time() - $this->getLastSeen("U") < 900); //15 perc
  }
  
  public function setName($v)
  {
    $this->setSlug(Tibiahu::slugify($v));
    return parent::setName($v);
  }
  
  public function setVocation($value)
  {
    $this->setVocationId(CharacterPeer::getVocationFromValue($value));
  }
  
  public function getVocation()
  {
    return CharacterPeer::getVocationFromIndex($this->getVocationId());
  }
  
  public function getViolations()
  {
    return BanishmentPeer::getViolations($this->getId());
  }
  
  public function hasBanishment()
  {
    return $this->banishment !== null;
  }
  
  public function setBanishment(array $ban)
  {
    $this->banishment = $ban;
  }
  
  public function getBanishedAt()
  {
    return isset($this->banishment["at"]) && 0 != $this->banishment["at"] ? $this->banishment["at"] : null;
  }
  
  public function getBanishedUntil()
  {
    return isset($this->banishment["until"]) ? $this->banishment["until"] : null;
  }
  
  public function getBanishedLevel()
  {
    return isset($this->banishment["level"]) ? $this->banishment["level"] : null;
  }
  
  public function getGuildName()
  {
    return isset($this->banishment["guild"]) ? $this->banishment["guild"] : null;
  }
  
  public function getGuildSlug()
  {
    return isset($this->banishment["slug"])  ? $this->banishment["slug"]  : null;
  }
  
}
