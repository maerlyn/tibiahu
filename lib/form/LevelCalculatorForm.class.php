<?php
  
  class LevelCalculatorForm extends sfForm
  {
    
    public function configure()
    {
      $this->setWidgets(array(
        "xp"  =>  new sfWidgetFormInput(array("label" => "Jelenlegi XP-d"), array("class" => "number"))
      ));
      
      $this->setValidators(array(
        "xp"  =>  new sfValidatorInteger(
          array(
            "required"  =>  true,
            "min"       =>  1,
            "max"       =>  2058474800 //lvl500
          ),
          array(
            "required"  =>  "Adj meg valami xpt!",
            "invalid"   =>  "Ez nem egy szám!",
            "min"       =>  "Legalább %min% legyen!",
            "max"       =>  "Nem lehet nagyobb, mint %max%!"
          )
      )));
      
      $this->widgetSchema->setHelp("xp", "Elválasztásra használhatsz szóközt, vesszőt vagy pontot.");
      
      $this->widgetSchema->setNameFormat("calculator_level[%s]");
    }
    
  }