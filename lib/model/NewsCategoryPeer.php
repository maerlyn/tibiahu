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
  
}
