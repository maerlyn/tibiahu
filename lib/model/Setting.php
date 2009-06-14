<?php

class Setting extends BaseSetting
{
  
  public function get()
  {
    return parent::getValue();
  }
  
  public function set($v)
  {
    return parent::setValue($v);
  }
  
}
