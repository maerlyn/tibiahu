<?php use_helper("Date", "Number") ?>
<?php slot("title", __("Utolsó frissítések")) ?>
<div class="containerbox">
  <h3><?php echo __("Utolsó frissítések") ?></h3>
  <div class="panel">
    <?php error_reporting(0);foreach($servers as $server): ?>
      <b><?php echo($servername = $server->getName()) ?></b>
      <table class="cronlog" border="1">
        <thead>
          <tr>
            <th><?php echo __("Dátum") ?></th>
            <th><?php echo __("Már ismert karakterek") ?></th>
            <th><?php echo __("Új karakterek") ?></th>
            <th><?php echo __("Szintlépések") ?></th>
            <th><?php echo __("Halálok") ?></th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($whoisonline as $whoisonline_object): ?>
        <?php if (!isset($wio[$timestamp = $whoisonline_object->getCreatedAt()])) { $wio[$timestamp] = unserialize(str_replace("&quot;", '"', $whoisonline_object->getData())); } ?>
          <tr>
            <td><?php echo format_datetime($timestamp) ?></td>
            <td><?php echo format_number($wio[$timestamp][$servername]["updates"]) ?></td>
            <td><?php echo format_number($wio[$timestamp][$servername]["inserts"]) ?></td>
            <td><?php echo format_number($wio[$timestamp][$servername]["levelups"]) ?></td>
            <td><?php echo format_number($wio[$timestamp][$servername]["leveldowns"]) ?></td>
          </tr>
        <?php endforeach ?>
        </tbody>
      </table>
      <br /><br />
    <?php endforeach; ?>
  </div>
</div>
