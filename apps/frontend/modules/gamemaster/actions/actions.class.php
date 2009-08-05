<?php

/**
 * gamemaster actions.
 *
 * @package    tibiahu
 * @subpackage gamemaster
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class gamemasterActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->servers = ServerPeer::getAllEnabled();
  }
  
  public function executeShow(sfWebRequest $request)
  {
    $this->forward404Unless($this->current_server = $this->getRoute()->getObject());
    $this->servers = ServerPeer::getAllEnabled();
    $this->gamemasters = GamemasterPeer::getForServer($this->current_server->getId());
  }
}
