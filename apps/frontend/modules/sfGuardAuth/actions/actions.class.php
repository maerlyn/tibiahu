<?php

require_once(dirname(__FILE__).'/../../../../../plugins/sfGuardPlugin/modules/sfGuardAuth/lib/BasesfGuardAuthActions.class.php');

class sfGuardAuthActions extends BasesfGuardAuthActions
{

  public function executeRegister(sfWebRequest $request)
  {
    $this->form = new sfGuardRegisterForm();
    if ($request->isMethod("post")) {
      $this->form->bind($request->getParameter("register"));
      if ($this->form->isValid()) {
        $values = $this->form->getValues();
        $user = new sfGuardUser();
        $user->setUsername($values["username"]);
        $user->setPassword($values["password"]);
        $user->setIsActive(false);
        $profile = new sfGuardUserProfile();
        $profile->setsfGuardUser($user);
        $profile->setEmail($values["email"]);
        $user->save();

        $this->redirect($this->generateUrl("@sf_guard_pending"));
      }
    }
  }

}
