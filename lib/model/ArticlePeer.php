<?php

class ArticlePeer extends BaseArticlePeer
{
  
  public static function getIndexCriteria()
  {
    $c = new Criteria();
    $c->addDescendingOrderByColumn(ArticlePeer::CREATED_AT);
    $c->addDescendingOrderByColumn(ArticlePeer::ID);
    return $c;
  }
  
  public static function doSelectJoinAllWithI18n(Criteria $c, $culture = null, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
  {
    foreach (sfMixer::getCallables('BaseArticlePeer:doSelectJoinAll:doSelectJoinAll') as $callable)
    {
      call_user_func($callable, 'BaseArticlePeer', $c, $con);
    }

    $c = clone $c;
    if ($culture === null) {
      $culture = sfPropel::getDefaultCulture();
    }

    // Set the correct dbName if it has not been overridden
    if ($c->getDbName() == Propel::getDefaultDB()) {
      $c->setDbName(self::DATABASE_NAME);
    }

    ArticlePeer::addSelectColumns($c);
    $startcol2 = (ArticlePeer::NUM_COLUMNS - ArticlePeer::NUM_LAZY_LOAD_COLUMNS);

    sfGuardUserPeer::addSelectColumns($c);
    $startcol3 = $startcol2 + (sfGuardUserPeer::NUM_COLUMNS - sfGuardUserPeer::NUM_LAZY_LOAD_COLUMNS);

    ArticleI18NPeer::addSelectColumns($c);
    $startcol4 = $startcol3 + (ArticleI18NPeer::NUM_COLUMNS - ArticleI18NPeer::NUM_LAZY_LOAD_COLUMNS);
    
    $c->addJoin(array(ArticlePeer::USER_ID,), array(sfGuardUserPeer::ID,), $join_behavior);
    $c->addJoin(ArticlePeer::ID, ArticleI18NPeer::ID);
    $c->add(ArticleI18NPeer::CULTURE, $culture);
    $stmt = BasePeer::doSelect($c, $con);
    $results = array();

    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
      $key1 = ArticlePeer::getPrimaryKeyHashFromRow($row, 0);
      if (null !== ($obj1 = ArticlePeer::getInstanceFromPool($key1))) {
        // We no longer rehydrate the object, since this can cause data loss.
        // See http://propel.phpdb.org/trac/ticket/509
        // $obj1->hydrate($row, 0, true); // rehydrate
      } else {
        $omClass = ArticlePeer::getOMClass();

        $cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
        $obj1 = new $cls();
        $obj1->hydrate($row);
        $obj1->setCulture($culture);
        ArticlePeer::addInstanceToPool($obj1, $key1);
      } // if obj1 already loaded

      // Add objects for joined sfGuardUser rows

      $key2 = sfGuardUserPeer::getPrimaryKeyHashFromRow($row, $startcol2);
      if ($key2 !== null) {
        $obj2 = sfGuardUserPeer::getInstanceFromPool($key2);
        if (!$obj2) {

          $omClass = sfGuardUserPeer::getOMClass();

          $cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
          $obj2 = new $cls();
          $obj2->hydrate($row, $startcol2);
          sfGuardUserPeer::addInstanceToPool($obj2, $key2);
        } // if obj2 loaded

        // Add the $obj1 (Article) to the collection in $obj2 (sfGuardUser)
        $obj2->addArticle($obj1);
      } // if joined row not null

      $omClass = ArticleI18NPeer::getOMClass();
      $cls = Propel::importClass($omClass);
      $obj3 = new $cls();
      $obj3->hydrate($row, $startcol3);
      
      $obj1->setArticleI18NForCulture($obj3, $culture);
      $obj3->setArticle($obj1);
      
      $results[] = $obj1;
    }
    $stmt->closeCursor();
    return $results;
    
    ###

    $stmt = BasePeer::doSelect($c, $con);
    $results = array();

    while($row = $stmt->fetch(PDO::FETCH_NUM)) {

      $omClass = ArticlePeer::getOMClass();

      $cls = Propel::importClass($omClass);
      $obj1 = new $cls();
      $obj1->hydrate($row);
      $obj1->setCulture($culture);

      $omClass = ArticleI18NPeer::getOMClass();

      $cls = Propel::importClass($omClass);
      $obj2 = new $cls();
      $obj2->hydrate($row, $startcol);

      $obj1->setArticleI18NForCulture($obj2, $culture);
      $obj2->setArticle($obj1);

      $results[] = $obj1;
    }
    return $results;    
  }
  
  public static function doSelectOneForRoute(array $params)
  {
    $c = new Criteria();
    $c->add(ArticlePeer::ID, $params["id"]);
    $c->setLimit(1);
    if (count($arr = self::doSelectJoinAllWithI18n($c))) {
      return $arr[0];
    } else {
      return null;
    }
  }
  
}
