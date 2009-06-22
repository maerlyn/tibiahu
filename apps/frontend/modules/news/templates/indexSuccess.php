<?php slot("title", __("Hírek")) ?>
<?php use_helper("Date") ?>
<?php include_partial("list", array("news" => $news)) ?>
