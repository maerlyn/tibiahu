<?php

class frontendConfiguration extends sfApplicationConfiguration
{
  public function configure()
  {
    ini_set("memory_limit", "32M");
  }
}
