<?php

/**
* General utility class
* @author Maerlyn <maerlyng@gmail.com>
*/
class Tibiahu
{
  static public function slugify($text)
  { 
    $text = str_replace(" ", "_", $text);
    
    // replace non letter or digits by -
    $text = preg_replace('~[^\\pL\d_]+~u', '-', $text);

    // trim
    $text = trim($text, '-');

    // transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

    // lowercase
    $text = strtolower($text);

    // remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);

    if (empty($text))
    {
      return 'n-a';
    }

    return $text;

  }
  
  static public function getLevelForXp($xp)
  {
    for ($i = 1; $xp >= self::getXpForLevel($i); ++$i);
    return --$i;
  }
  
  static public function getXpForLevel($level)
  {
    return (50 / 3 * $level * $level * $level - 100 * $level * $level + 850 / 3 * $level - 200); // tibiawiki: experience formula
  }
  
  static public function getPartialStaminaRegenerationTimes($stamina)
  {
    $partials = array();
    for ($i = 1; $i <= 41; ++$i) {
      if ($i * 60 > $stamina) {
        $regen = (($i * 60) - $stamina) * 3 + 10;
        $partials[$i] = str_pad(floor($regen/60), 2, "0", STR_PAD_LEFT) . ":" . str_pad(($regen - (floor($regen/60)*60)), 2, "0", STR_PAD_LEFT);
      }
    }
    return $partials;
  }
  
  static public function getBlessingPrices($level)
  {
    $ppb = 0; // price per blessing
    if ($level < 30) {
      $ppb = 2000;
    } else 
    if ($level > 120) {
      $ppb = 20000;
    } else {
      $ppb = 2000 + 200 * ($level - 30);
    }
    
    $ret = array();
    for ($i = 1; $i <= 5; ++$i) {
      $ret[$i] = $ppb * $i;
    }
    return $ret;
  }
  
  static public function guessBanishmentDate($banished_until)
  {
    $ban_durations = array(7, 15, 30);
    
    foreach ($ban_durations as $days) {
      //echo($days . " - " . date("Y-m-d H:i:s\n", strtotime("+{$days} days")));
      $expiration = strtotime("+{$days} days");
      if ($banished_until < $expiration) {
        return $banished_until - $days*86400;
      }
    }
  }
  
  static public function getXpShareLevels($level)
  {
    return array(
      "min" =>  ceil($level * 2 / 3),
      "max" => floor($level * 3 / 2)
    );
  }
  
  /**
  * Returns mana required to advance to next magic level
  *
  * @param int the current magic level
  * @param int vocation (0 Knight, 1 Paladin, 2 Druid, 3 Sorcerer)
  * @return float required mana
  */
  static public function getManaForMlvl($current_mlvl, $vocation)
  {
    $vocation_multipliers = array(
      0 =>  3,
      1 =>  1.4,
      2 =>  1.1,
      3 =>  1.1,
    );
    
    if (!isset($vocation_multipliers[$vocation])) {
      throw new InvalidArgumentException("Invalid vocation");
    }
    
    return floor(1600 * pow($vocation_multipliers[$vocation], $current_mlvl));
  }
  
  /**
  * Returns mana required to advance from $from_level+$percent_remaining% to $target_mlvl
  * 
  * @param int original mlvl
  * @param int percentage remaining, according to the tibia client
  * @param int target mlvl
  * @param int vocation (0 Knight, 1 Paladin, 2 Druid, 3 Sorcerer)
  * @return float total mana
  */
  static public function getManaNeededToAdvance($from_mlvl, $percent_remaining, $target_mlvl, $vocation)
  {
    $mana = round(($percent_remaining / 100) * Tibiahu::getManaForMlvl($from_mlvl, $vocation));
    
    if ($from_mlvl + 1 != $target_mlvl) {
      for ($i = $from_mlvl + 1; $i < $target_mlvl; ++$i) {
        $mana += Tibiahu::getManaForMlvl($i, $vocation);
      }
    }
    
    return $mana;
  }
  
  static public function getSoulRegenerationTimes($soul, $promotion)
  {
    $ret = array();
    
    switch ($promotion) {
      case false:
        $time_per_soul = 240;
        $max_soul = 100;
        break;
        
      case true:
        $time_per_soul = 15;
        $max_soul = 200;
        $ret["full"]["sleeping"] = self::secondsToTime(900 * (200 - $soul));
        break;
    }
    
    $ret["full"]["hunting"] = self::secondsToTime($time_per_soul * ($max_soul - $soul));
    for ($i = 60; $i <= $max_soul; $i += 60) {
      if ($soul < $i) {
        $ret["partial"]["hunting"][$i] = self::secondsToTime($time_per_soul * ($i - $soul));
        
        if ($promotion) {
          $ret["partial"]["sleeping"][$i] = self::secondsToTime(900 * ($i - $soul));
        }
      }
    }
    
    return $ret;
  }

  static public function secondsToTime($seconds)
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
