<?php

class Character extends BaseCharacter
{
  private $banisment = null;
  private $needIndexUpdate = false;

  public function wasOnlineLastTime()
  {
    return (time() - $this->getLastSeen("U") < 900); //15 perc
  }
  
  public function setName($v)
  {
    $this->needIndexUpdate = true;
    $this->setSlug(Tibiahu::slugify($v));
    return parent::setName($v);
  }
  
  public function setVocation($value)
  {
    $this->setVocationId(CharacterPeer::getVocationFromValue($value));
  }
  
  public function getVocation()
  {
    return CharacterPeer::getVocationFromIndex($this->getVocationId());
  }
  
  public function getViolations()
  {
    return BanishmentPeer::getViolations($this->getId());
  }
  
  public function hasBanishment()
  {
    return $this->banishment !== null;
  }
  
  public function setBanishment(array $ban)
  {
    $this->banishment = $ban;
  }
  
  public function getBanishedAt()
  {
    return isset($this->banishment["at"]) && 0 != $this->banishment["at"] ? $this->banishment["at"] : null;
  }
  
  public function getBanishedUntil()
  {
    return isset($this->banishment["until"]) ? $this->banishment["until"] : null;
  }
  
  public function getBanishedLevel()
  {
    return isset($this->banishment["level"]) ? $this->banishment["level"] : null;
  }
  
  public function getGuildName()
  {
    return isset($this->banishment["guild"]) ? $this->banishment["guild"] : null;
  }
  
  public function getGuildSlug()
  {
    return isset($this->banishment["slug"])  ? $this->banishment["slug"]  : null;
  }
  
  public function save(PropelPDO $con = null)
  {
    if (is_null($con)) {
      $con = Propel::getConnection(CharacterPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
    }
    
    $con->beginTransaction();

    try {
      $ret = parent::save($con);
      if ($this->needIndexUpdate) {
        $this->updateLuceneIndex();
      }
      $con->commit();
      return $ret;
    }           
    catch (Exception $e) {
      $con->rollBack();
      throw $e;
    }
      
    
    return $ret;
  }
  
  public function updateLuceneIndex()
  {
    $index = CharacterPeer::getLuceneIndex();
    
    foreach ($index->find("pk:" . $this->getId()) as $hit) {
      $index->delete($hit->id);
    }
    
    $char = new Zend_Search_Lucene_Document();
    
    $char->addField(Zend_Search_Lucene_Field::Keyword("pk", $this->getId()));
    $char->addField(Zend_Search_Lucene_Field::UnStored("name", $this->getName()));
    
    $index->addDocument($char);
    $index->commit();
  }
  
  public function delete(PropelPDO $con = null)
  {
    $index = CharacterPeer::getLuceneIndex();
    
    foreach ($index->find("pk:" . $this->getId()) as $hit) {
      $index->delete($hit->id);
    }
    
    return parent::delete($con);
  }
  
}
