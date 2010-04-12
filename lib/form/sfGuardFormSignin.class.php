<?php

class sfGuardFormSignin extends sfForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'username' => new sfWidgetFormInput(array("label" =>  "Felhasználónév")),
      'password' => new sfWidgetFormInputPassword(array("label" => "Jelszó")),
    ));

    $this->setValidators(array(
      'username' => new sfValidatorString(),
      'password' => new sfValidatorString(),
    ));

    $this->validatorSchema->setPostValidator(new sfGuardValidatorUser());

    $this->widgetSchema->setNameFormat('signin[%s]');
  }
}
