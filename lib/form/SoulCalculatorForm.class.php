<?php

class SoulCalculatorForm extends BaseForm
{

  public function configure()
  {
    $this->setWidgets(array(
      "current_soul"  
        =>  new sfWidgetFormInputText(array(
              "label" =>  "Aktuális soul"
            )),
            
      "promotion"
        =>  new sfWidgetFormInputCheckbox(array(
              "label" =>  "Promotion"
            )),
    ));

    $this->setDefault("current_soul", "0");
    
    $this->setValidators(array(
      "current_soul"
        =>  new sfValidatorInteger(array(
              "required"  => true,
              "max"       => 199,
              "min"       => 0,
            ), array(
              "invalid"   => "Ez nem egy szám!",
              "max"       => "Nem lehet nagyobb, mint %max%!",
              "min"       => "Legalább %min% legyen!",
            )),
      
      "promotion"
        =>  new sfValidatorBoolean(),
        
    ));
    
    $this->widgetSchema->setNameFormat("soulcalc[%s]");
  }
  
}
