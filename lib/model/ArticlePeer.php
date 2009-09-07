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
  
  public static function doSelectOneForRoute(array $params)
  {
    $c = new Criteria();
    $c->add(ArticlePeer::ID, $params["id"]);
    $c->setLimit(1);
    if (count($arr = self::doSelectJoinAll($c))) {
      return $arr[0];
    } else {
      return null;
    }
  }
  
}
