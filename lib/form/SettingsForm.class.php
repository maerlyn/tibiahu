<?php

class SettingsForm extends sfForm
{
  private $user;

  public function  __construct($defaults = array(), $options = array(), $CSRFSecret = null) {
    parent::__construct($defaults, $options, $CSRFSecret);

    if (!isset($options["user"]) || !($options["user"] instanceof sfGuardUser)) {
      throw new LogicException("No user passed in options[user]");
    }
    $this->user = $options["user"];
  }

  public function configure()
  {
    $this->setWidgets(array(
      "password1" =>  new sfWidgetFormInputPassword(array("label" => "Jelszó")),
      "password2" =>  new sfWidgetFormInputPassword(array("label" => "Jelszó mégegyszer")),
    ));

    $this->widgetSchema->setHelp("password1", "Hagyd üresen, ha nem akarod megváltoztatni");

    $this->setValidators(array(
      "password1" =>  new sfValidatorString(
        array("min_length" => 8),
        array("min_length" => "Túl rövid (legalább %min_length% karakter)")
      ),
      "password2" =>  new sfValidatorPass(),
    ));

    $this->mergePostValidator(new sfValidatorAnd(array(
      new sfValidatorSchemaCompare("password1", "==", "password2", array(), array("invalid" => "Nem egyezik a két jelszó")),
    )));

    $this->widgetSchema->setNameFormat("settings[%s]");
  }

  public function doBind(array $values)
  {
    if (empty($values["password1"]) && empty($values["password2"])) {
      unset(
        $values["password1"],
        $values["password2"],
        $this->validatorSchema["password1"],
        $this->validatorSchema["password2"]
      );
    }

    parent::doBind($values);
  }

  public function save()
  {
    if (!$this->isValid()) {
      throw new LogicException("Form is not valid");
    }

    $values = $this->getValues();

    if (!empty($values["password1"])) {
      $this->user->setPassword($values["password1"]);
    }

    if ($modified = $this->user->isModified()) {
      $this->user->save();
    }

    return $modified;
  }

}
