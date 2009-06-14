<?php

class Server extends BaseServer
{
  
  public function __toString()
  {
    return $this->getName();
  }
  
}
