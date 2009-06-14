<?php if (!$sf_request->isXmlHttpRequest()): ?>
  <?php slot("title", __("Guildek")) ?>
  <div class="containerbox">
    <h3><?php echo __("Guildek listája") ?></h3>
    <div class="panel">
      <br />
    
    <?php if (isset($form)): ?>
      <form action="<?php echo url_for("@guild_index") ?>" method="post">
        <table border="0">
          <?php echo $form ?>
          <tr>
            <td colspan="2">
              <input type="submit" value="<?php echo __("Keress") ?>" />
            </td>
            </tr>
        </table>
      </form>    
      <br />
      <?php include_partial("list", array("guilds" => $guilds, "server" => true)) ?>
    <?php else: ?>
      <div id="tabs">
      <ul>
      <?php foreach ($servers as $server): ?>
        <li><a href="<?php echo url_for("@guild_index_for_server?server=".$server->getName()) ?>"><span><?php echo $server->getName() ?></span></a></li>
      <?php endforeach ?>
      </ul>
    </div>
    <?php endif; ?>
    </div>
  </div>
<?php else: ?>
<?php include_partial("list", array("guilds" => $guilds)) ?>
<?php endif; ?>