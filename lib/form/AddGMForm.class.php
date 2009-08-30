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
      
      $this->validatorSchema->setPostValidator(
        new sfValidatorPropelUnique(
          array(
            "model"   =>  "Gamemaster",
            "column"  =>  "name",
            "primary_key" =>  "id",
          ),
          array(
            "invalid" =>  "asdasd"
          )
        )
      );
      
      $this->widgetSchema->setNameFormat("addgm[%s]");
    }
    
  }