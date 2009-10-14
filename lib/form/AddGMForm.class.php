<?php
  
  class AddGMForm extends sfForm
  {
    
    public function configure()
    {
      $this->setWidgets(array(
        "name"  =>  new sfWidgetFormInput(array("label" => "Név"))
      ));
      
      $this->setValidators(array(
        "name"  =>  new sfValidatorString(
            array(
              "required"  =>  true,
              "min_length"  =>  3,
              "max_length"  =>  255
            ),
            array(
              "required"  =>  "Adj meg egy nevet!",
              "min_length"  =>  "Adj meg legalább %min_length% karaktert!",
              "max_length"  =>  "Legfeljebb %max_length% karaktert adhatsz meg!"
            )
          ),
      ));
      
      $this->validatorSchema->setPostValidator(new sfValidatorAnd(array(
        new sfValidatorPropelUnique(
          array(
            "model"       =>  "Gamemaster",
            "column"      =>  "name",
            "field"       =>  "name",
            "primary_key" =>  "id",
            "throw_global_error"  =>  false,
          ),
          array(
            "invalid" =>  "Már van ilyen nevű GM az adatbázisban"
          )
        ),
        
        new sfValidatorCallback(array(
          "callback"    =>  array("AddGMForm", "checkGMStatus"),
        ), array(
          "invalid"     =>  "Nem létező, vagy nem GM karakter",
          "required"    =>  "Nem olyan szerveren van, amit figyel az oldal",
        )),
      )));
      
      $this->widgetSchema->setNameFormat("addgm[%s]");
    }
    
    public static function checkGMStatus($validator, $values, $arguments)
    {
      if (!TibiaWebsite::isGamemaster($values["name"])) {
        throw new sfValidatorError($validator, "invalid");
      }
      
      $info = TibiaWebsite::characterInfo($values["name"]);
      if (!ServerPeer::retrieveByName($info["world"])) {
        throw new sfValidatorError($validator, "required");
      }
      
      return $values;
    }
    
  }