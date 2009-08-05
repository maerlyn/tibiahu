<ul>
<?php foreach ($servers as $server): ?>
  <?php if (isset($current_server) && $current_server == $server->getName()): ?>
  <li><?php echo $server->getName() ?></li>
  <?php else: ?>
  <li><?php echo link_to($server->getName(), "@gmfinder_show?name=" . $server->getName()) ?></li>
  <?php endif ?>
<?php endforeach ?>  
</ul>
