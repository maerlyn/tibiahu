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
      $temp["xp"] = str_replace(array(" ", ",", "."), "", $temp["xp"]);
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
        
        $this->xpshare_levels = Tibiahu::getXpShareLevels($this->level_current);
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
  
  public function executeMlvl(sfWebRequest $request)
  {
    $this->form = new MlvlCalculatorForm();
    if ($request->isMethod("post")) {
      $this->form->bind($request->getParameter("mlvlcalc"));
      
      if ($this->form->isValid()) {
        $values = $this->form->getValues();
        
        $this->mana = Tibiahu::getManaNeededToAdvance(
          $values["current_mlvl"], $values["percent_remaining"], $values["target_mlvl"], $values["vocation"]
        );
        
        $mana_per_sec_per_vocation = array(
          0 => array(false => 1/3, true => 1/3),
          1 => array(false => 1/2, true => 2/3),
          2 => array(false => 2/3, true => 1),
          3 => array(false => 2/3, true => 1),
        );
        $mana_per_sec = $mana_per_sec_per_vocation[$values["vocation"]][$values["promotion"]];
        
        if ($this->soft_boots = $values["soft_boots"]) {
          $mana_per_sec += 2;
        }

        $time = $this->mana / $mana_per_sec;
        $this->time = $this->secondsToTime($time);

        if ($values["current_mlvl"] + 1 == $values["target_mlvl"]) {
          $this->one_percent = floor(0.01 * Tibiahu::getManaForMlvl($values["current_mlvl"], $values["vocation"]));
          $this->one_percent_time = $this->one_percent / $mana_per_sec;
          $this->one_percent_time = $this->secondsToTime($this->one_percent_time);
        }
        
        $instant_spells = array(
          0 =>  array(
            "Exura"             =>  20,
            "Whirlwind throw"   =>  40,
            "Berserk"           =>  115,
            "Fierce berserk"    =>  340
          ),
          1 =>  array(
            "Divine missile"    =>  20,
            "Divine caldera"    =>  160,
            "Divine healing"    =>  210,
          ),
          2 =>  array(
            "Exura"             =>  20,
            "Strong haste"      =>  100,
            "Undead legion"     =>  500,
            "Eternal winter"    =>  1200,
          ),
          3 =>  array(
            "Exura"             =>  20,
            "Great energy beam" =>  110,
            "Invisible"         =>  440,
            "Hell's core"       =>  1200,
          )
        );
        $this->instant_spells = $instant_spells[$values["vocation"]];
        
        $rune_spells = array(
          1 =>  array(
            "Holy missile"  =>  array("mana" => 300, "price" => 1600),
            "Enchant spear" =>  array("mana" => 350, "price" => 300000),
          ),
          2 =>  array(
            "Stalagmite"    =>  array("mana" => 350, "price" => 1500),
            "Stone shower"  =>  array("mana" => 430, "price" => 2300),
            "Icicle"        =>  array("mana" => 460, "price" => 2500),
            "Avalanche"     =>  array("mana" => 530, "price" => 2700),
          ),
          3 =>  array(
            "Thunderstorm"  =>  array("mana" => 430, "price" => 2300),
            "Fireball"      =>  array("mana" => 460, "price" => 2500),
            "Great fireball"=>  array("mana" => 530, "price" => 2700),
            "Sudden death"  =>  array("mana" => 985, "price" => 6000),
          )
        );
        if (isset($rune_spells[$values["vocation"]])) {
          $this->rune_spells = $rune_spells[$values["vocation"]];
        }
      }
    }
  }
    
  private function secondsToTime($seconds)
  {
    $temp["days"] = floor($seconds / 86400);
    $seconds -= $temp["days"] * 86400;
    $temp["hours"] = floor($seconds / 3600);
    $seconds -= $temp["hours"] * 3600;
    $temp["minutes"] = floor($seconds / 60);
    $seconds -= $temp["minutes"] * 60;
    $temp["seconds"] = floor($seconds);
    return $temp;
  }
 
}
