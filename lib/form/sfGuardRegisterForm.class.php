<?php

class sfGuardRegisterForm extends sfForm
{

  public function configure()
  {
    parent::configure();

    $this->setWidgets(array(
      "username"  =>  new sfWidgetFormInput(array("label" => "Felhasználónév")),
      "password"  =>  new sfWidgetFormInputPassword(array("label" => "Jelszó")),
      "password2" =>  new sfWidgetFormInputPassword(array("label" => "Jelszó mégegyszer")),
      "email"     =>  new sfWidgetFormInput(array("label" => "E-mail")),
    ));

    $this->setValidators(array(
      "username"  =>  new sfValidatorString(array(
        "min_length"  =>  6,
        "max_length"  =>  128,
      ), array(
        "min_length"  =>  "Túl rövid (legalább %min_length% karakter)",
        "max_length"  =>  "Túl hosszú (legfeljebb %max_length% karakter)",
      )),

      "password"  =>  new sfValidatorString(array("min_length" => 8), array("min_length" => "Túl rövid (legalább %min_length% karakter)")),
      "password2" =>  new sfValidatorString(),

      "email"     =>  new sfValidatorEmail(),
    ));

    $this->mergePostValidator(
      new sfValidatorSchemaCompare("password", "==", "password2", array(), array("invalid" => "Nem egyezik a két jelszó"))
    );

    $this->widgetSchema->setNameFormat("register[%s]");
  }
}
