<?php

class NewsCategory extends BaseNewsCategory
{
  
  public function __toString()
  {
    return $this->getName();
  }
  
}
