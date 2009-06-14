∑<?php echo count($guilds) ?><br />
      <table class="searchresults">
        <thead>
          <tr>
            <th><?php echo __("Név") ?></th>
            <th><?php echo __("Nyilvántartott tagok") ?></th>
            <th><?php if (isset($server) && $server) { echo __("Szerver"); } ?></th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($guilds as $guild): ?>
          <tr>
            <td><a href="<?php echo url_for(array("sf_route" => "guild_show", "sf_subject" => $guild)) ?>"><?php echo $guild->getName() ?></a></td>
            <td><?php echo $guild->getMembers() ?></td>
            <td><?php if (isset($server) && $server) { echo $guild->getServer(); } ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
