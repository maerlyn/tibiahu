<?php

class Article extends BaseArticle
{
}

sfPropelBehavior::add('Article', array('sfPropelActAsTaggableBehavior'));
