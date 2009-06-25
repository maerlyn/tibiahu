<?php

class NewsPeer extends BaseNewsPeer
{
  
  public static function getUseridForTibiaCom()
  {
    $c = new Criteria();
    $c->add(sfGuardUserPeer::USERNAME, "tibia.com");
    $user = sfGuardUserPeer::doSelectOne($c);
    return $user->getId();
  }
  
  public static function getIndexCriteria()
  {
    $c = new Criteria();
    $c->addDescendingOrderByColumn(NewsPeer::CREATED_AT);
    return $c;
  }

  public static function getLast($max = 10)
  {
    $c = self::getIndexCriteria();
    $c->setLimit($max);
    return self::doSelectJoinAllWithI18n($c);
  }

  public static function doSelectJoinAllWithI18n(Criteria $c, $culture = null, PropelPDO $con = null)
  {
    $c = clone $c;
    
    if ($culture === null) {
      $culture = sfPropel::getDefaultCulture();
    }

    // Set the correct dbName if it has not been overridden
    if ($c->getDbName() == Propel::getDefaultDB()) {
      $c->setDbName(self::DATABASE_NAME);
    }

    NewsPeer::addSelectColumns($c);
    $startcol2 = (NewsPeer::NUM_COLUMNS - NewsPeer::NUM_LAZY_LOAD_COLUMNS);

    sfGuardUserPeer::addSelectColumns($c);
    $startcol3 = $startcol2 + (sfGuardUserPeer::NUM_COLUMNS - sfGuardUserPeer::NUM_LAZY_LOAD_COLUMNS);

    NewsCategoryPeer::addSelectColumns($c);
    $startcol4 = $startcol3 + (NewsCategoryPeer::NUM_COLUMNS - NewsCategoryPeer::NUM_LAZY_LOAD_COLUMNS);
    
    NewsI18NPeer::addSelectColumns($c);
    $startcol5 = $startcol4 + (NewsI18NPeer::NUM_COLUMNS - NewsI18NPeer::NUM_LAZY_LOAD_COLUMNS);
    
    NewsCategoryI18NPeer::addSelectColumns($c);
    $startcol6 = $startcol5 + (NewsCategoryI18NPeer::NUM_COLUMNS - NewsCategoryI18NPeer::NUM_LAZY_LOAD_COLUMNS);

    $c->addJoin(array(NewsPeer::USER_ID,), array(sfGuardUserPeer::ID,), Criteria::LEFT_JOIN);
    $c->addJoin(array(NewsPeer::CATEGORY_ID,), array(NewsCategoryPeer::ID,), Criteria::LEFT_JOIN);
    $c->addJoin(array(NewsPeer::ID), array(NewsI18NPeer::ID), Criteria::LEFT_JOIN);
    $c->addJoin(array(NewsCategoryPeer::ID), array(NewsCategoryI18NPeer::ID), Criteria::LEFT_JOIN);
    $c->add(NewsI18NPeer::CULTURE, $culture);
    $c->add(NewsCategoryI18NPeer::CULTURE, $culture);
    
    $stmt = BasePeer::doSelect($c, $con);
    $results = array();

    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
      $key1 = NewsPeer::getPrimaryKeyHashFromRow($row, 0);
      if (null !== ($obj1 = NewsPeer::getInstanceFromPool($key1))) {
      } else {
        $omClass = NewsPeer::getOMClass();

        $cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
        $obj1 = new $cls();
        $obj1->hydrate($row);
        NewsPeer::addInstanceToPool($obj1, $key1);
      } // if obj1 already loaded

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

        // Add the $obj1 (News) to the collection in $obj2 (sfGuardUser)
        $obj2->addNews($obj1);
      } // if joined row not null

      // Add objects for joined NewsCategory rows

      $key3 = NewsCategoryPeer::getPrimaryKeyHashFromRow($row, $startcol3);
      if ($key3 !== null) {
        $obj3 = NewsCategoryPeer::getInstanceFromPool($key3);
        if (!$obj3) {

          $omClass = NewsCategoryPeer::getOMClass();

          $cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
          $obj3 = new $cls();
          $obj3->hydrate($row, $startcol3);
          NewsCategoryPeer::addInstanceToPool($obj3, $key3);
        } // if obj3 loaded

        // Add the $obj1 (News) to the collection in $obj3 (NewsCategory)
        $obj3->addNews($obj1);
      } // if joined row not null
      
      $obj1->setCulture($culture);
      
      $key4 = NewsI18NPeer::getPrimaryKeyHashFromRow($row, $startcol4);
      if ($key4 !== null) {
        $obj4 = NewsI18NPeer::getInstanceFromPool($key4);
        if (!$obj4) {
          $omClass = NewsI18NPeer::getOMClass();
          $cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
          $obj4 = new $cls();
          $obj4->hydrate($row, $startcol4);
          NewsI18NPeer::addInstanceToPool($obj4, $key4);
        }
        $obj1->setNewsI18NForCulture($obj4, $culture);
        $obj4->setNews($obj1);
      }
      
      $key5 = NewsCategoryI18NPeer::getPrimaryKeyHashFromRow($row, $startcol5);
      if ($key5 !== null) {
        $obj5 = NewsCategoryI18NPeer::getInstanceFromPool($key5);
        if (!$obj5) {
          $omClass = NewsCategoryI18NPeer::getOMClass();
          $cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
          $obj5 = new $cls();
          $obj5->hydrate($row, $startcol5);
          NewsCategoryI18NPeer::addInstanceToPool($obj5, $key5);
        }
        $obj1->getNewsCategory()->setNewsCategoryI18NForCulture($obj5, $culture);
        //$obj5->getsetNews($obj1);
      }
      
      $results[] = $obj1;
    }
    $stmt->closeCursor();
    return $results;
  }

  public static function retrieveForShow($id, $slug)
  {
    $c = new Criteria();
    $c->add(NewsPeer::ID, $id);
//    $c->add(NewsI18NPeer::SLUG, $slug);
    return self::doSelectJoinAllWithI18n($c);
  }
  
  public static function doSelectOneForRoute(array $params)
  {
    return self::retrieveForShow($params["id"], $params["slug"]);
  }
    
}
