<?php

class Guild extends BaseGuild
{

  public function setName($v)
  {
    $this->setSlug(Tibiahu::slugify($v));
    return parent::setName($v);
  }
  
  public function __toString()
  {
    return $this->getName();
  }

}
