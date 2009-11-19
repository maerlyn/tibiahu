<?php

class CharacterPeer extends BaseCharacterPeer
{
  protected static $VOCATION_INTEGERS = array(
    0 => "None",
    1 => "Knight",
    2 => "Paladin",
    3 => "Sorcerer",
    4 => "Druid",
    5 => "Elite Knight",
    6 => "Royal Paladin",
    7 => "Master Sorcerer",
    8 => "Elder Druid"
  );
  protected static $VOCATION_VALUES = null;
  
  public static function getVocations()
  {
    return self::$VOCATION_INTEGERS;
  }

  public static function getVocationFromIndex($index)
  {
    return self::$VOCATION_INTEGERS[$index];
  }
  
  public static function getVocationFromValue($value)
  {
    if (!self::$VOCATION_VALUES) {
      self::$VOCATION_VALUES = array_flip(self::$VOCATION_INTEGERS);
    }
    
    if (!isset(self::$VOCATION_VALUES[$value])) {
      throw new PropelException(sprintf("Vocation cannot take '%s' as a value", $value));
    }
    
    return self::$VOCATION_VALUES[$value];
  }
  
  public static function searchByName($name, $max = null)
  {
    $c = new Criteria();
    $c->add(CharacterPeer::NAME, "%" . $name . "%", Criteria::LIKE);
    if ($max) {
      $c->setLimit($max);
    }
    $c->addAscendingOrderByColumn(CharacterPeer::NAME);
    return parent::doSelect($c);
  }
  
  public static function retrieveByName($name)
  {
    $c = new Criteria();
    $c->add(CharacterPeer::NAME, $name);
    return parent::doSelectOne($c);
  }
  
  public static function retrieveBySlug($slug)
  {
    $c = new Criteria;
    $c->add(CharacterPeer::SLUG, $slug);
    return parent::doSelectOne($c);
  }
  
  public static function doAdvancedSearch(array $values, $countOnly)
  {
    $c = new Criteria();
    if ($values["name"] != "") {
      $c->add(CharacterPeer::NAME, "%{$values["name"]}%", Criteria::LIKE);
    }
    
    if ($values["level"] != "") {
      $lvl = $values["level"];
      $dir = Criteria::EQUAL;
      if (preg_match("#(\+|-)$#", $values["level"])) { //+ vagy - a legvegen
        $lvl = (int)(substr($values["level"], 0, -1));
        $dir = (substr($values["level"], "-1") == "+") ? Criteria::GREATER_EQUAL : Criteria::LESS_EQUAL;
        $c->add(CharacterPeer::LEVEL, $lvl, $dir);
      } else 
      if (preg_match("#\d-\d#", $values["level"])) { //- kozepen, ergo lvl range
        $temp = explode("-", $values["level"]);
        
        $crit_from = $c->getNewCriterion(CharacterPeer::LEVEL, $temp[0], Criteria::GREATER_EQUAL);
        $crit_to   = $c->getNewCriterion(CharacterPeer::LEVEL, $temp[1], Criteria::LESS_EQUAL);
        
        $crit_from->addAnd($crit_to);
        $c->add($crit_from);
        
      } else { //ha egyik elozo sem, azaz egy sima szamot kaptunk
        $c->add(CharacterPeer::LEVEL, $lvl, $dir);
      }
    }
      
    if (is_numeric($values["guild"])) { //konkret guild
      $c->add(CharacterPeer::GUILD_ID, $values["guild"]);
    } else
    if ($values["guild"] == "yes" || $values["guild"] == "no") {
      $c->add(CharacterPeer::GUILD_ID, null, ($values["guild"]=="yes" ? Criteria::ISNOTNULL : Criteria::ISNULL));
    }
      
    if ($values["vocation"] != "---") {
      $c->add(CharacterPeer::VOCATION_ID, $values["vocation"]);
    }
    
    if ($values["server"]) {
      $c->add(CharacterPeer::SERVER_ID, $values["server"]);
    }
    
    $c->addAscendingOrderByColumn(CharacterPeer::NAME);
    
    if ($countOnly) {
      return parent::doCount($c);
    } else {
      return parent::doSelectJoinAll($c);
    }
  }

  public static function getForUpdate($start_id, $count)
  {
    $c = new Criteria();
    $c->add(CharacterPeer::ID, $start_id, Criteria::GREATER_THAN);
    $c->setLimit($count);
    $c->addAscendingOrderByColumn(CharacterPeer::ID);
    return parent::doSelect($c);
  }

  private static function getBanishedCharactersCriteria($server_id, $reason, $for = "list")
  {
    $c = new Criteria();
    CharacterPeer::addSelectColumns($c);
    $c->addSelectColumn(BanishmentPeer::BANISHED_AT);
    $c->addSelectColumn(BanishmentPeer::BANISHED_UNTIL);
    $c->addSelectColumn(BanishmentPeer::LEVEL);
    
    $c->addSelectColumn(GuildPeer::NAME);
    $c->addSelectColumn(GuildPeer::SLUG);
    
    $c->add(CharacterPeer::SERVER_ID, $server_id);
    $c->add(BanishmentPeer::BANISHED_FOR_ID, BanishmentPeer::getReasonFromValue($reason));
    
    $c->addJoin(CharacterPeer::ID, BanishmentPeer::CHARACTER_ID, Criteria::INNER_JOIN);
    $c->addJoin(CharacterPeer::GUILD_ID, GuildPeer::ID, Criteria::LEFT_JOIN);
    
    switch ($for) {
      case "feed":
        $c->add(BanishmentPeer::LEVEL, 0, Criteria::NOT_EQUAL);
        $c->add(BanishmentPeer::BANISHED_AT, 0, Criteria::NOT_EQUAL);
        $c->addDescendingOrderByColumn(BanishmentPeer::BANISHED_AT);
        $c->setLimit(35);
        break;
        
      case "list":
      default:
        $c->addAScendingOrderByColumn(CharacterPeer::NAME);
    }

    return $c;
  }
  
  public static function doSelectBanishedCharacters(Criteria $c, $con = null)
  {
    $stmt = parent::doSelectStmt($c, $con);
    $ret = array();
    
    while ($row = $stmt->fetch()) {
      $char = new Character();
      $offset = $char->hydrate($row);
      $char->setBanishment(array(
        "at"    =>  strtotime($row[$offset + 0]),
        "until" =>  strtotime($row[$offset + 1]),
        "level" =>  $row[$offset + 2],
        "guild" =>  $row[$offset + 3],
        "slug"  =>  $row[$offset + 4],
      ));
      $ret[] = $char;
    }   
    
    return $ret; 
  }
  
  public static function getBottersCriteria(Server $server)
  {
    return self::getBanishedCharactersCriteria($server->getId(), BanishmentPeer::REASON_UNOFFICIAL);
  }
  
  public static function getBotters($server_id, $for = "list")
  {
    return self::doSelectBanishedCharacters(
      self::getBanishedCharactersCriteria($server_id, BanishmentPeer::REASON_UNOFFICIAL, $for)
    );
  }
  
  public static function getHackersCriteria(Server $server)
  {
    return self::getBanishedCharactersCriteria($server->getId(), BanishmentPeer::REASON_HACKING);
  }
  
  public static function getHackers($server_id, $for = "list")
  {
    return self::doSelectBanishedCharacters(
      self::getBanishedCharactersCriteria($server_id, BanishmentPeer::REASON_HACKING, $for)
    );
  }
  
  public static function getAcctradersCriteria(Server $server)
  {
    return self::getBanishedCharactersCriteria($server->getId(), BanishmentPeer::REASON_ACCTRADE);
  }
  
  public static function getAcctraders($server_id, $for = "list")
  {
    return self::doSelectBanishedCharacters(
      self::getBanishedCharactersCriteria($server_id, BanishmentPeer::REASON_ACCTRADE, $for)
    );
  }
  
  public static function getForShow($character_id)
  {
    $c = new Criteria();
    $c->add(CharacterPeer::ID, $character_id);
    $a = parent::doSelectJoinAll($c);
    return isset($a[0]) ? $a[0] : null;
  }
  
  public static function doDeleteAll($con = null)
  {
    if (file_exists($index = self::getLuceneIndexFile())) {
      sfToolkit::clearDirectory($index);
      @rmdir($index);
    }
    
    return parent::doDeleteAll($con);
  }
  
  public static function getLuceneIndex()
  {
    if (file_exists($index = self::getLuceneIndexFile())) {
      return Zend_Search_Lucene::open($index);
    } else {
      return Zend_Search_Lucene::create($index);
    }
  }
  
  public static function getLuceneIndexFile()
  {
    return sfConfig::get("sf_data_dir") . "/character.index/";
  }
  
}
