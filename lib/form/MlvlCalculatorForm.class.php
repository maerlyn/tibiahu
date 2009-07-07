<?php

class MlvlCalculatorForm extends sfForm
{

  public function configure()
  {
    $vocations = array(
      0 =>  "Knight",
      1 =>  "Paladin",
      2 =>  "Druid",
      3 =>  "Sorcerer",
    );
    
    $this->setWidgets(array(
      "current_mlvl"  
        =>  new sfWidgetFormInput(array(
              "label" =>  "Aktuális mlvl"
            )),
            
      "percent_remaining" 
        =>  new sfWidgetFormInput(array(
              "label" =>  "Hátralevő százalék"
            )),
            
      "target_mlvl"
        =>  new sfWidgetFormInput(array(
              "label" =>  "Cél mlvl"
            )),
            
      "vocation"
        =>  new sfWidgetFormChoice(array(
              "label" =>    "Kaszt",
              "choices" =>  $vocations,
            )),
            
      "promotion"
        =>  new sfWidgetFormInputCheckbox(array(
              "label" =>  "Promotion"
            )),
            
      "soft_boots"
        =>  new sfWidgetFormInputCheckbox(array(
              "label" =>  "Soft boots"
            )),
    ));

    sfValidatorSchema::setRequiredMessage("Kötelező kitölteni.");
    
    $mlvl_validator = new sfValidatorInteger(array(
      "required"  =>  true,
      "max"       =>  150,
      "min"       =>  0
    ), array(
      "invalid"   =>  "Ez nem egy szám!",
      "max"       =>  "Nem lehet nagyobb, mint %max%!",
      "min"       =>  "Legalább %min% legyen!",
    ));
    
    $this->setDefault("percent_remaining", "100");
    
    $this->setValidators(array(
      "current_mlvl"  
        =>  $mlvl_validator,
      
      "percent_remaining" 
        =>  new sfValidatorInteger(array(
              "required"  =>  true,
              "max"       =>  100,
              "min"       =>  1,
            ), array(
              "invalid"   =>  "Ez nem egy szám!",
              "max"       =>  "Nem lehet nagyobb, mint %max%!",
              "min"       =>  "Legalább %min% legyen!"
            )),
        
      "target_mlvl"
        =>  $mlvl_validator,
        
      "vocation" 
        =>  new sfValidatorChoice(array(
              "required"  =>  true,
              "choices"   =>  array_keys($vocations)
            )),
      
      "promotion"
        =>  new sfValidatorBoolean(),
        
      "soft_boots"
        =>  new sfValidatorBoolean(),
        
    ));
    
    $check_levels = new sfValidatorCallback(array(
      "callback"  =>  array($this, "checkLevels")
    ), array (
      "invalid"   =>  "Túl magas szint!"
    ));
    
    $this->validatorSchema->setPostValidator(
      new sfValidatorAnd(array(
        new sfValidatorSchemaCompare("current_mlvl", "<=", "target_mlvl", array(), array(
          "invalid" =>  "A cél mlvl legyen nagyobb, mint az aktuális!"
        )),
        $check_levels
      ))
    );
    
    $this->widgetSchema->setNameFormat("mlvlcalc[%s]");
  }
  
  public function checkLevels($validator, $values, $arguments)
  {
    if (($values["vocation"] == 0 && $values["current_mlvl"] > 15) ||
        ($values["vocation"] == 1 && $values["current_mlvl"] > 40)) {
      $error = new sfValidatorError($validator, "invalid");
      throw new sfValidatorErrorSchema($validator, array("current_mlvl" => $error));
    }
        
    if (($values["vocation"] == 0 && $values["target_mlvl"] > 15) ||
        ($values["vocation"] == 1 && $values["target_mlvl"] > 40)) {
      $error = new sfValidatorError($validator, "invalid");
      throw new sfValidatorErrorSchema($validator, array("target_mlvl" => $error));
    }
    
    return $values;
  }
}

