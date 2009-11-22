<?php

class TibiahuRebuildIndexTask extends sfBaseTask
{

  protected function configure()
  {
    $this->namespace = "tibiahu";
    $this->name = "rebuild-index";
    $this->briefDescription = "Ujraepiti a karakterindexet";
    
    $this->detailedDescription = <<<EOF
Torli a Lucene teljes indexet, majd ujraepiti
EOF;
  }
  
  protected function execute($arguments = array(), $options = array())
  {
    ini_set("memory_limit", "1024M");
    $databaseManager = new sfDatabaseManager($this->configuration);
    
    set_include_path(sfConfig::get("sf_lib_dir") . "/vendor" . PATH_SEPARATOR . get_include_path());
    require_once sfConfig::get("sf_lib_dir") . "/vendor/Zend/Loader/Autoloader.php";
    Zend_Loader_Autoloader::getInstance();
    
    $con = Propel::getConnection(CharacterPeer::DATABASE_NAME, Propel::CONNECTION_READ);
    
    $sql = "SELECT COUNT(*) FROM `tibia_character`;";
    $stmt = $con->query($sql);
    $row = $stmt->fetch(PDO::FETCH_NUM);
    $this->logSection("rebuild", sprintf("%d characters in total", $row[0]));
    
    $sql = "SELECT `id`, `name` FROM `tibia_character`;";
    $stmt = $con->query($sql); /** @var PDOStatement $stmt */
    
    $index = CharacterPeer::getLuceneIndexFile(); /** @var Zend_Search_Lucene $index */
    sfToolkit::clearDirectory($index);
    rmdir($index);
    
    $index = CharacterPeer::getLuceneIndex();
    
    $i = 0;
    while (false !== ($row = $stmt->fetch(PDO::FETCH_ASSOC))) {
      
      $char = new Zend_Search_Lucene_Document();
      
      $char->addField(Zend_Search_Lucene_Field::Keyword("pk", $row["id"]));
      $char->addField(Zend_Search_Lucene_Field::UnStored("name", $row["name"]));
      
      $index->addDocument($char);
      
      if (++$i % 1000 == 0) {
        echo($i . "\r");
      }
    }
    $index->commit();
    
    echo "\n";
    
    $this->logSection("rebuild", sprintf("index rebuilt with %d characters", $i));
  }

}
