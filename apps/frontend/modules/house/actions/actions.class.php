<?php

/**
 * house actions.
 *
 * @package    tibiahu
 * @subpackage house
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class houseActions extends sfActions
{
  
  public function executeShow(sfWebRequest $request)
  {
    if ($this->getUser()->isFirstRequest()) {
      $culture = $request->getPreferredCulture(array("hu", "en"));
      $this->getUser()->setCulture($culture);
      $this->getUser()->isFirstRequest(false);
    }
    
    $this->forward404Unless($this->house = HousePeer::retrieveByPK($request->getParameter("slug")));
    $this->forward404If(false === ($serverid = array_search($request->getParameter("server"), $servers = TibiaWebsite::getWorlds())));
    $this->server = $servers[$serverid];
  }
}
