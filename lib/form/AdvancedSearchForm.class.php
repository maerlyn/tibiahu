<?php
  
  class AdvancedSearchForm extends sfForm
  {
    
    public function configure()
    {
      $this->setWidgets(array(
        "name"  =>  new sfWidgetFormInput(array("label" => "Név")),
        "level" =>  new sfWidgetFormInput(array("label" => "Szint")),
        "guild" =>  new sfWidgetFormChoice(array(
          "label"   =>  "Guild",
          "choices" =>  array(
              "whatever"  =>  sfContext::getInstance()->getI18N()->__("mindegy"),
              "yes"       =>  sfContext::getInstance()->getI18N()->__("legyen neki"),
              "no"        =>  sfContext::getInstance()->getI18N()->__("ne legyen neki")
            ) + 
            GuildPeer::getAllForAdvancedSearch()
        )),
        "vocation"  =>  new sfWidgetFormChoice(array(
          "label"   =>  "Kaszt",
          "choices" =>  array_merge(array("---" => "---"), CharacterPeer::getVocations())
        )),
        "server"    =>  new sfWidgetFormPropelChoice(array(
          "label"     =>  "Szerver",
          "model"     =>  "Server",
          "add_empty" =>  true,
          "order_by"  =>  array("Name", "asc")
          
        ))
      ));
      
      $this->widgetSchema->setHelp("level", "Példák: 60 - pontosan hatvan, 60+ - hatvan feletti, 60- - hatvan alatti, 50-70 - ötven és hetven közötti");
      
      $this->setValidators(array(
        "name"  =>  new sfValidatorString(
          array("max_length"  =>  255, "required" => false),
          array("max_length"  =>  "Legfeljebb %max_length% karaktert adhatsz meg!")
        ),
        "level" =>  new sfValidatorAnd(array(
          new sfValidatorString(
            array("max_length"  =>  7, "required" => false),
            array("max_length"  =>  "Túl hosszú lesz az szintnek")
          ),
          new sfValidatorRegex(
            array("pattern" =>  "#^(?:\d{0,3}(?:\+|-(?:\d{1,3}|)|)|)$#is", "required" => false), 
            array("invalid"  => "Hibás formátum.")
          )
        ), array("required" => false)),
        "guild" =>  new sfValidatorChoice(array(
          "choices" =>  array("whatever","yes","no") + array_keys(GuildPeer::getAllForAdvancedSearch())
        )),
        "vocation"  =>  new sfValidatorChoice(array(
          "choices" =>  array_merge(array("---"), array_keys(CharacterPeer::getVocations()))
        )),
        "server"    =>  new sfValidatorPropelChoice(array(
          "model"     =>  "Server",
          "required"  =>  false
        ))
      ));
      
      $this->widgetSchema->setNameFormat("advancedsearch[%s]");
    }
    
  }