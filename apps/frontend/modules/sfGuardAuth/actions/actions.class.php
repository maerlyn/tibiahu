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
        $user = $this->form->save();

        $this->forward($this->generateUrl("@sf_guard_pending"));
      }
    }
  }

  public function executePending(sfWebRequest $request)
  {
  }

}
