<?php if (!$sf_request->isXmlHttpRequest()): ?>
<?php slot("title", __("Tradelt/megosztott karakterek")) ?>
<div class="containerbox">
  <h3><?php echo __("Tradelt/megosztott karakterek") ?></h3>
  <div class="panel">
    <?php echo __("Ezek a karakterek legalább egyszer bannolva voltak account trading miatt.") ?><br />
    <br />
    <div id="tabs">
      <ul>
      <?php foreach ($servers as $server): ?>
        <li><a href="<?php echo url_for("@character_acctraders_for_server?server=".$server->getName()) ?>"><span><?php echo $server->getName() ?></span></a></li>
      <?php endforeach ?>
      </ul>
    </div>
  </div>
</div>
<?php else: ?>
  <?php include_partial("botterlist", array("botters" => $acctraders)); ?>
<?php endif; ?>