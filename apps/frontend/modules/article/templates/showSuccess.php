<?php use_helper("Date") ?>
<?php slot("title", $article->getTitle()) ?>
<?php include_partial("list", array("articles" => array($article))) ?>
