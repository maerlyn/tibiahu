<?php

/**
 * user actions.
 *
 * @package    tibiahu
 * @subpackage user
 * @author     Maerlyn <maerlyng@gmail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class userActions extends sfActions
{

  public function executeCharacters(sfWebRequest $request)
  {
    $this->characters = CharacterPeer::retrieveForUser($this->getUser()->getGuardUser());
  }

  public function executeAddCharacter(sfWebRequest $request)
  {
    $this->redirectUnless($request->isXmlHttpRequest(), $this->getContext()->getRouting()->generate("user_characters"));
    $this->character = CharacterPeer::retrieveByName($request->getParameter("charname"));

    if ($this->character && !$request->hasParameter("code")) {
      $this->code = "tibiahu-character-" . dechex($this->character->getId());
    }
  }

  public function executeVerify(sfWebRequest $request)
  {
    $this->redirectUnless($request->isXmlHttpRequest(), $this->getContext()->getRouting()->generate("user_characters"));
    $this->character = CharacterPeer::retrieveByName($request->getParameter("charname"));

    if ($this->character && !$request->hasParameter("code")) {
      $this->code = "tibiahu-character-" . dechex($this->character->getId());
      $this->verification = TibiaWebsite::verifyCode($this->character->getName(), $this->code);

      if ($this->verification) {
        $uc = new UserCharacter();
        $uc->setUserId($this->getUser()->getGuardUser()->getId());
        $uc->setCharacter($this->character);
        try {
          $uc->save();
        }
        catch (Exception $e) {}
      }
    }
  }

  public function executeSettings(sfWebRequest $request)
  {
    $this->form = new SettingsForm(array(), array("user" => $this->getUser()->getGuardUser()));

    if ($request->isMethod("post")) {
      $this->form->bind($request->getParameter("settings"));
      if ($this->form->isValid()) {
        if ($this->form->save()) {
          $this->getUser()->setFlash("saved", "Mentve.");
        }
      }
    }
  }

}
