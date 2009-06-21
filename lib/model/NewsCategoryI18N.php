<?php

class NewsCategoryI18N extends BaseNewsCategoryI18N
{
  
  public function setName($v)
  {
    $this->setSlug(Tibiahu::slugify($v));
    
    return parent::setName($v);
  }
  
}
