<?php

/**
 * news actions.
 *
 * @package    tibiahu
 * @subpackage news
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class newsActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->pager = new sfPropelPager(
      "News",
      sfConfig::get("app_max_news_on_index", 10)
    );
    $this->pager->setCriteria(NewsPeer::getIndexCriteria());
    $this->pager->setPeerMethod("doSelectJoinAllWithI18n");
    $this->pager->setPage($request->getParameter("page", 1));
    $this->pager->init();
  }
  
  public function executeShow(sfWebRequest $request)
  {
    $this->forward404Unless($this->news = $this->getRoute()->getObject());
  }
}
