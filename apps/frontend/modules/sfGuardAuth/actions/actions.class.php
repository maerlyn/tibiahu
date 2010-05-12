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

        $values = $this->form->getValues();
        $mail = $this->getMailer()->compose(
          array("noreply@tibia.hu" => "Tibia.hu"),
          $values["email"],
          $this->getContext()->getI18N()->__("register.subject", null, "email"),
          $this->getContext()->getI18N()->__("register.body", array(
            "%username%" => $values["username"],
            "%link%"     => $this->getContext()->getRouting()->generate("user_verifymail", array("hash" => $user->getPassword()), true),
          ), "email")
        );
        $this->getMailer()->send($mail);

        $this->redirect($this->generateUrl("sf_guard_pending"));
      }
    }
  }

  public function executePending(sfWebRequest $request)
  {
  }

  public function executeVerifyMail(sfWebRequest $request)
  {
    $this->forward404Unless($user = sfGuardUserPeer::retrieveForVerify($request->getParameter("hash")));
    $user->setIsActive(true);
    $user->save();
  }

}
