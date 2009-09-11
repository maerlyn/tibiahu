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
      sfConfig::get("app_max_articles_on_index", 5)
    );
    $this->pager->setCriteria(ArticlePeer::getIndexCriteria());
    $this->pager->setPeerMethod("doSelectJoinAllWithI18N");
    $this->pager->setPage($request->getParameter("page", 1));
    $this->pager->init(); 
    
    $this->results = $this->pager->getResults();
    sfPropelActAsTaggableBehavior::preloadTags($this->results);
  }
  
  public function executeShow(sfWebRequest $request)
  {
    $this->forward404Unless($this->article = $this->getRoute()->getObject());    
  }
  
}
