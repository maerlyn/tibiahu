<?php

class NewsI18N extends BaseNewsI18N
{
  
  public function setTitle($v)
  {
    $this->setSlug(Tibiahu::slugify($v));
    
    return parent::setTitle($v);
  }
  
}
