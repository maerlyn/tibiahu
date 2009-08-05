<?php slot("title", __("GM-kereső", null, "gamemaster")) ?>
<div class="containerbox">
  <h3><?php echo __("GM-kereső", null, "gamemaster") ?></h3>
  <div class="panel">
    <?php echo __("Válassz szervert", null, "gamemaster") ?>:<br />
    <?php include_partial("serverlist", array("servers" => $servers)) ?>
  </div>
</div>
