<?php

/**
 * character actions.
 *
 * @package    tibiahu
 * @subpackage character
 * @author     Maerlyn <maerlyng@gmail.com>
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class characterActions extends sfActions
{

  public function executeIndex(sfWebRequest $request)
  {
    $this->form = new SearchForm();
    
    if ($request->isMethod("post")) {
      $this->forwardIf($request->getParameter("target") == "Guild", "guild", "index");
      
      $this->form->bind($request->getParameter("search"));
      
      if ($this->form->isValid()) {
        $this->characters = CharacterPeer::searchByName($this->form->getValue("name"));
        
        if (count($this->characters) == 1) {
          $this->getUser()->setFlash("redirect", "Csak egy találat volt, ezért továbbvittünk a karakter oldalára");
          $this->redirect("@character_show?slug=" . $this->characters[0]->getSlug());
        }
      }
    }
  }
  
  public function executeShow(sfWebRequest $request) 
  {
    $this->forward404Unless($this->character = $this->getRoute()->getObject());
    $this->character = CharacterPeer::getForShow($this->character->getId());
    
    $this->levelhistory = LevelHistoryPeer::getForCharacter($this->character);
  }
  
  public function executeAddLvlUp(sfWebRequest $request)
  {
    $code = "tibiahu-levelup-" . dechex($request->getParameter("lvlupid"));      
    $this->forward404Unless($character = CharacterPeer::retrieveBySlug($request->getParameter("slug")));
    $this->forward404Unless($lvlup = LevelHistoryPeer::retrieveById($request->getParameter("lvlupid")));
    $this->reason = $lvlup->getReason();    
    $this->code = $code;
    $this->level = $lvlup->getLevel();
    $this->uri = $request->getUri();

    if ($request->isMethod("post")) {
      $reason = strtolower($request->getParameter("reason"));
      sfLoader::loadHelpers("I18N");      
      if (TibiaWebsite::verifyCode($character->getName(), $code)) {
        if (CreaturePeer::creatureExists($reason)) {
          $lvlup->setReason($reason);
          $lvlup->save();
          $this->renderText(__("Mentve. Frissítés után látszódni fog."));
          return sfView::NONE;
        } else { //nincs ilyen leny
          $this->error = __("Nem létező lényt adtál meg!");
        }
      } else { //rossz az ellenorzokod
        $this->error = __("Nem találom a kódot, próbáld meg mégegyszer.");
      }
    }
  }

  public function executeAdvancedSearch(sfWebRequest $request)
  {
    $this->form = new AdvancedSearchForm();
    
    if ($request->isMethod("post")) {
      $this->form->bind($request->getParameter("advancedsearch"));
        
      if ($this->form->isValid()) {
          $this->count = CharacterPeer::doAdvancedSearch($this->form->getValues(), true);
          if ($this->count < 300) {
            $this->characters = CharacterPeer::doAdvancedSearch($this->form->getValues(), false);
          }
      }
    }
  }
  
  public function executeBotters(sfWebRequest $request)
  {
    $this->servers = ServerPeer::getAllEnabled();
    
    if ($request->hasParameter("name")) {
      $this->server = $this->getRoute()->getObject();
      
      $this->pager = new sfPropelPager(
        "Character", sfConfig::get("app_characters_per_page", 100)
      );
      $this->pager->setCriteria(CharacterPeer::getBottersCriteria($this->server));
      $this->pager->setPeerMethod("doSelectBanishedCharacters");
      $this->pager->setPage($request->getParameter("page"));
      $this->pager->init();
    }
  }
  
  public function executeHackers(sfWebRequest $request)
  {
    $this->servers = ServerPeer::getAllEnabled();
    
    if ($request->hasParameter("name")) {
      $this->server = $this->getRoute()->getObject();
      
      $this->pager = new sfPropelPager(
        "Character", sfConfig::get("app_characters_per_page", 100)
      );
      $this->pager->setCriteria(CharacterPeer::getHackersCriteria($this->server));
      $this->pager->setPeerMethod("doSelectBanishedCharacters");
      $this->pager->setPage($request->getParameter("page"));
      $this->pager->init();
    }
  }

  public function executeAcctraders(sfWebRequest $request)
  {
    $this->servers = ServerPeer::getAllEnabled();
    
    if ($request->hasParameter("name")) {
      $this->server = $this->getRoute()->getObject();
      
      $this->pager = new sfPropelPager(
        "Character", sfConfig::get("app_characters_per_page", 100)
      );
      $this->pager->setCriteria(CharacterPeer::getAcctradersCriteria($this->server));
      $this->pager->setPeerMethod("doSelectBanishedCharacters");
      $this->pager->setPage($request->getParameter("page"));
      $this->pager->init();
    }
  }
  
}
