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
    if (!$request->getParameter("sf_culture")) {
      if ($this->getUser()->isFirstRequest()) {
        $culture = $request->getPreferredCulture(array("hu", "en"));
        $this->getUser()->setCulture($culture);
        $this->getUser()->isFirstRequest(false);
      }
      else {
        $culture = $this->getUser()->getCulture();
      }  
      
      $this->redirect("@localized_homepage");
    }
        
    $this->cronlog = CronLogPeer::getLast(10, "whoisonline");
  }
  
  public function executeChangeLanguage(sfWebRequest $request)
  {
    $form = new sfFormLanguage(
      $this->getUser(),
      array('languages' => array('en', 'hu'))
    );
 
    $form->process($request);
 
    return $this->redirect('@localized_homepage');
  }
    
}
