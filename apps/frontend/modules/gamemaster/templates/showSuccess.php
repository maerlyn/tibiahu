<?php slot("title", __("GM-kereső", null, "gamemaster")) ?>
<?php use_helper("Date") ?>
<div class="containerbox">
  <h3><?php echo __("GM-kereső", null, "gamemaster") ?></h3>
  <div class="panel">
    <?php echo __("Szerverváltás", null, "gamemaster") ?>:<br />
    <?php include_partial("serverlist", array("servers" => $servers, "current_server" => $current_server->getName())) ?>
    <br /><br />
    <?php echo __("Ismert GM-ek a <b>%server%</b> szerveren", array("%server%" => $current_server->getName()), "gamemaster") ?>:<br />
    <table>
      <thead>
        <tr>
          <th><?php echo __("Név") ?></th>
          <th><?php echo __("Utoljára bejelentkezett", null, "gamemaster") ?></th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($gamemasters as $gm): ?>
        <tr>
          <td><a href="http://www.tibia.com/community/?subtopic=character&name=<?php echo urlencode($gm->getName()) ?>"><?php echo $gm->getName() ?></a></td>
          <td><?php echo format_datetime($gm->getLastSeen()) ?></td>
        </tr>
      <?php endforeach ?>
      </tbody>
    </table>
  </div>
</div>
