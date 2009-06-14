<?php
  
  class BlessingCalculatorForm extends sfForm
  {
    
    public function configure()
    {
      $this->setWidgets(array(
        "level"  =>  new sfWidgetFormInput(array("label" => "Jelenlegi szinted"))
      ));
      
      $this->setValidators(array(
        "level"  =>  new sfValidatorInteger(
          array(
            "required"  =>  true,
            "min"       =>  1,
            "max"       =>  500
          ),
          array(
            "required"  =>  "Adj meg valami szintet!",
            "invalid"   =>  "Ez nem egy szám!",
            "min"       =>  "Legalább %min% legyen!",
            "max"       =>  "Nem lehet nagyobb, mint %max%!"
          )
      )));
      
      $this->widgetSchema->setNameFormat("calculator_blessing[%s]");
    }
    
  }