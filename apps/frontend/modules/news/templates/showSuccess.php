<?php use_helper("Date") ?>
<?php slot("title", $news[0]->getTitle()) ?>
<?php include_partial("list", array("news" => $news)) ?>
