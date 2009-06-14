<?php

class Creature extends BaseCreature
{
  
  public function __toString()
  {
    return $this->getName();
  }
  
}
