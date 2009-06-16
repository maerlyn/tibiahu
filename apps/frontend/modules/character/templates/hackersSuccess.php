<?php if (!$sf_request->isXmlHttpRequest()): ?>
<?php slot("title", __("Hackerek")) ?>
<div class="containerbox">
  <h3><?php echo __("Hackerek") ?></h3>
  <div class="panel">
    <?php echo __("Ezek a karakterek legalább egyszer bannolva voltak hackelés miatt.") ?><br />
    <br />
    <div id="tabs">
      <ul>
      <?php foreach ($servers as $server): ?>
        <li><a href="<?php echo url_for("@character_hackers_for_server?server=".$server->getName()) ?>"><span><?php echo $server->getName() ?></span></a></li>
      <?php endforeach ?>
      </ul>
    </div>
  </div>
</div>
<?php else: ?>
  <?php include_partial("botterlist", array(
    "botters" => $hackers,
    "feedurl" => url_for("@character_banfeed?reason=hackers&server=" . $sf_request->getParameter("server"))
  )); ?>
<?php endif; ?>