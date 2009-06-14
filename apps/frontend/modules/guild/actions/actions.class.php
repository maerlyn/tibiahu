<?php

/**
 * guild actions.
 *
 * @package    tibiahu
 * @subpackage guild
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class guildActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    if ($request->isXmlHttpRequest() && $request->hasParameter("server")) {
      $this->forward404If(!$server = ServerPeer::retrieveByName($request->getParameter("server")));
      $this->guilds = GuildPeer::getAllForServer($server->getId());
    } else {
      if ($request->isMethod("post") && $request->hasParameter("search")) {
        $this->form = new SearchForm();
        $this->form->bind($request->getParameter("search"));
        if ($this->form->isValid()) {
          $this->guilds = GuildPeer::getGuildsLike("%" . $this->form->getValue("name") . "%");
        } else {
          $this->guilds = array();
        }
      } else {
        $this->servers = ServerPeer::getAllEnabled();
      }
    }
  }
  
  public function executeShow(sfWebRequest $request)
  {
    $this->guild = $this->getRoute()->getObject();
    $this->forward404Unless($this->guild);
    
    $c = new Criteria();
    $c->add(CharacterPeer::GUILD_ID, $this->guild->getId());
    $c->addAscendingOrderByColumn(CharacterPeer::NAME);
    $this->members = CharacterPeer::doSelect($c);
  }
}
