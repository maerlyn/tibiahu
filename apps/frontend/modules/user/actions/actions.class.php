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
    $this->characters = UserCharacterPeer::retrieveForUserId($this->getUser()->getGuardUser()->getId());
  }
}
