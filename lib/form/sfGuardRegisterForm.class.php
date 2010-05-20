<?php

class sfGuardRegisterForm extends BaseForm
{

  public function configure()
  {
    parent::configure();

    $this->setWidgets(array(
      "username"  =>  new sfWidgetFormInputText(array("label" => "Felhasználónév")),
      "password"  =>  new sfWidgetFormInputPassword(array("label" => "Jelszó")),
      "password2" =>  new sfWidgetFormInputPassword(array("label" => "Jelszó mégegyszer")),
      "email"     =>  new sfWidgetFormInputText(array("label" => "E-mail")),
    ));

    $this->widgetSchema->setHelp("password", "Legyen legalább nyolc karakter");

    $this->setValidators(array(
      "username"  =>  new sfValidatorString(array(
        "min_length"  =>  4,
        "max_length"  =>  128,
      ), array(
        "min_length"  =>  "Túl rövid (legalább %min_length% karakter)",
        "max_length"  =>  "Túl hosszú (legfeljebb %max_length% karakter)",
      )),

      "password"  =>  new sfValidatorString(array("min_length" => 8), array("min_length" => "Túl rövid (legalább %min_length% karakter)")),
      "password2" =>  new sfValidatorString(),

      "email"     =>  new sfValidatorEmail(),
    ));

    $this->mergePostValidator(new sfValidatorAnd(array(
      new sfValidatorSchemaCompare("password", "==", "password2", array(), array("invalid" => "Nem egyezik a két jelszó")),

      new sfValidatorPropelUnique(array(
        "model"   =>  "sfGuardUser",
        "column"  =>  "username",
      ), array(
        "invalid" =>  "Már használt felhasználónév",
      )),

      new sfValidatorPropelUnique(array(
        "model"   =>  "sfGuardUserProfile",
        "column"  =>  "email",
      ), array(
        "invalid" =>  "Már használt email cím",
      )),
    )));

    $this->widgetSchema->setNameFormat("register[%s]");
  }

  public function save()
  {
    if (!$this->isValid()) {
      throw $this->getErrorSchema();
    }

    $values = $this->getValues();
    $user = new sfGuardUser();
    $user->setUsername($values["username"]);
    $user->setPassword($values["password"]);
    $user->setIsActive(false);
    $profile = new sfGuardUserProfile();
    $profile->setsfGuardUser($user);
    $profile->setEmail($values["email"]);
    $user->save();

    return $user;
  }

}
