<?php

/**
 * article actions.
 *
 * @package    tibiahu
 * @subpackage article
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class articleActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->pager = new sfPropelPager(
      "Article",
      sfConfig::get("app_max_news_on_index")
    );
    $this->pager->setCriteria(ArticlePeer::getIndexCriteria());
    $this->pager->setPeerMethod("doSelectJoinAll");
    $this->pager->setPage($request->getParameter("page", 1));
    $this->pager->init(); 
  }
  
  public function executeShow(sfWebRequest $request)
  {
    $this->forward404Unless($this->article = $this->getRoute()->getObject());    
  }
  
}
