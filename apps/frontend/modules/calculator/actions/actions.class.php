<?php

/**
 * calculator actions.
 *
 * @package    tibiahu
 * @subpackage calculator
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class calculatorActions extends sfActions
{

  public function executeLevel(sfWebRequest $request)
  {
    $this->form = new LevelCalculatorForm();
    
    if ($request->isMethod("post")) {
      $temp = $request->getParameter("calculator_level");
      $temp["xp"] = str_replace(" ", "", $temp["xp"]);
      $this->form->bind($temp);
      if ($this->valid = $this->form->isValid()) {
        $this->level_current = Tibiahu::getLevelForXp($this->form->getValue("xp"));
        $this->xp_next = Tibiahu::getXpForLevel($this->level_current+1);
        $this->xp_remaining = $this->xp_next - $this->form->getValue("xp");
        
        $this->monsters = array(
          "Rat"           => ceil($this->xp_remaining / 5),
          "Bear"          => ceil($this->xp_remaining / 23),
          "Elf"           => ceil($this->xp_remaining / 42),
          "Dwarf Soldier" => ceil($this->xp_remaining / 70),
          "Cyclops"       => ceil($this->xp_remaining / 150),
          "Dragon"        => ceil($this->xp_remaining / 700),
          "Dragon Lord"   => ceil($this->xp_remaining / 2100),
          "Demon"         => ceil($this->xp_remaining / 6000),
          "Ferumbras"     => ceil($this->xp_remaining / 12000),
          "Morgaroth"     => ceil($this->xp_remaining / 15000)
        );
      }
    }
  }
  
  public function executeStamina(sfWebRequest $request)
  {
    $this->form = new StaminaCalculatorForm();
    
    if ($request->isMethod("post")) {
      $this->form->bind($request->getParameter("calculator_stamina"));
      
      if ($this->form->isValid()) {
        if ($this->form->getValue("stamina") == "42:00") {
          $time = 0;
        } else {
          $time = 10; //logoff utan ennyivel kezd visszajonni
          
          $temp = explode(":", $this->form->getValue("stamina"));
          $stamina = intval($temp[0]) * 60 + intval($temp[1]);
          
          $this->partials = Tibiahu::getPartialStaminaRegenerationTimes($stamina);
          
          if ($stamina < (41*60)) {
            $time += (41*60 - $stamina)*3; //3 percenkent 1 stamina
            $stamina = 41*60;
          }
          
          $time += (42*60-$stamina)*12; //utolso oraban 12 percenkent 1 stamina
        }
        
        $this->time = array(
          "hours"   =>  floor($time / 60),
          "minutes" =>  $time - (floor($time/60))*60
        );
      }
    }
  }
  
  public function executeBlessing(sfWebRequest $request)
  {
    if ($request->isXmlHttpRequest()) {
      sfForm::disableCSRFProtection();
    }
    $this->form = new BlessingCalculatorForm();
    
    if (!$request->isXmlHttpRequest() && $request->isMethod("post")) {
      $this->form->bind($request->getParameter("calculator_blessing"));
      if ($this->form->isValid()) {
        $this->prices = Tibiahu::getBlessingPrices($this->form->getValue("level"));
        $this->level = $this->form->getValue("level");
      }
    } else
    if ($request->isXmlHttpRequest() && $request->hasParameter("calculator_blessing")) {
      $this->form->bind($request->getParameter("calculator_blessing"));
      if ($this->form->hasErrors()) {
        $this->errors = $this->form->getErrorSchema();
      } else {
        $this->prices = Tibiahu::getBlessingPrices($this->form->getValue("level"));
        $this->level = $this->form->getValue("level");
      }
    }
    
  }
  
}
