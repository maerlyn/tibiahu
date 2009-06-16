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
    $ppb = 0;
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
  
}
