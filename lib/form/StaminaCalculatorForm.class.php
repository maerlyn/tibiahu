<?php
  
  class StaminaCalculatorForm extends sfForm
  {
    
    public function configure()
    {
      $this->setWidgets(array(
        "stamina"  =>  new sfWidgetFormInput(array("label" => "Jelenlegi staminád"))
      ));
      
      $this->widgetSchema->setHelp("stamina", 
        "Elfogadott formátumok: 0:00 vagy 00:00");
      
      $this->setValidators(array(
        "stamina" =>  new sfValidatorAnd(array(
          new sfValidatorRegex(
            array(
              "required"  =>  true,
              "pattern"   =>  "#^(?:[0-4]|)\d:[0-5]\d$#"
            ),
            array(
              "required"  =>  "Adj meg valamit!",
              "invalid"   =>  "Érvénytelen formátum!"
            )
          ),
          new sfValidatorCallback(
            array("callback" => array("StaminaCalculatorForm", "validator_callback")),
            array("invalid" =>  "Nem érvényes stamina!")
          )
        ))
      ));
      
      $this->widgetSchema->setNameFormat("calculator_stamina[%s]");
    }
    
    public static function validator_callback($validator, $value, $arguments)
    {
      $temp = explode(":", $value);
      
      if (intval($temp[0]) > 42 || ($temp[0] == 42 && $temp[1] != 0)) {
        throw new sfValidatorError($validator, 'invalid');
      }
      
      return $value;
    }
    
  }