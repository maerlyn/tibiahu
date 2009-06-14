<?php

class LevelHistory extends BaseLevelHistory
{
  
  public function setReason($name)
  {
    return $this->setReasonId(CreaturePeer::getIdForName($name));
  }
  
  public function getReason()
  {
    return $this->getCreature();
  }
  
}
