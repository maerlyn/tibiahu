<?php if (!$sf_request->isXmlHttpRequest()): ?>
<?php slot("title", __("Botterek")) ?>
<div class="containerbox">
  <h3><?php echo __("Botterek") ?></h3>
  <div class="panel">
    <?php echo __("Ezek a karakterek legalább egyszer bannolva voltak bot vagy makróhasználat miatt.") ?><br />
    <br />
    <div id="tabs">
      <ul>
      <?php foreach ($servers as $server): ?>
        <li><a href="<?php echo url_for("@character_botters_for_server?server=".$server->getName()) ?>"><span><?php echo $server->getName() ?></span></a></li>
      <?php endforeach ?>
      </ul>
    </div>
  </div>
</div>
<?php else: ?>
  <?php include_partial("botterlist", array(
    "botters" => $botters,
    "feedurl" =>  url_for("@character_banfeed?reason=botters&server=" . $sf_request->getParameter("server"))
  )); ?>
<?php endif; ?>