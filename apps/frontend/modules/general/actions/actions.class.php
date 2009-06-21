<?php

/**
 * general actions.
 *
 * @package    tibiahu
 * @subpackage general
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class generalActions extends sfActions
{

  public function executeError404(sfWebRequest $request)
  {
    //
  }
  
  public function executeHomepage(sfWebRequest $request)
  {
    $this->cronlog = CronLogPeer::getLast(10, "whoisonline");
  }
  
  public function executeLastUpdate(sfWebRequest $request)
  {
    $this->cronlog = CronLogPeer::getLast(10, "whoisonline");
  }
      
}
