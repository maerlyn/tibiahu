<?php

class NewsCategoryPeer extends BaseNewsCategoryPeer
{
  
  public static function getNewstickerCategoryId()
  {
    $c = new Criteria();
    $c->add(NewsCategoryI18NPeer::SLUG, "newsticker");
    $categ = NewsCategoryI18NPeer::doSelectOne($c);
    return $categ->getId();
  }
  
  public static function getNewsCategoryId()
  {
    $c = new Criteria();
    $c->add(NewsCategoryI18NPeer::SLUG, "news");
    $categ = NewsCategoryI18NPeer::doSelectOne($c);
    return $categ->getId();
  }
  
  public static function getFeaturedArticlesCategoryId()
  {
    $c = new Criteria();
    $c->add(NewsCategoryI18NPeer::SLUG, "featured_articles");
    $categ = NewsCategoryI18NPeer::doSelectOne($c);
    return $categ->getId();
  }
  
}
