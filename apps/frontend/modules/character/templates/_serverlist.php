<ul>
<?php foreach ($servers as $server): ?>
  <li><?php echo link_to_unless(
    isset($current_server) && $server->getName() == $current_server->getName(), 
    $server->getName(), "@" . $route . "?name=" . $server->getName()) ?></li>
<?php endforeach ?>  
</ul>
