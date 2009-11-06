<?php use_helper("Date") ?>

<a href="<?php echo $feedurl ?>"><img src="<?php echo image_path("feed.png") ?>" alt="feed" /></a><br />
∑<?php echo $total ?>, <?php echo $first ?> - <?php echo $last ?>:

<br />
<table class="botterlist">
   <thead>
    <tr>
      <th><?php echo __("Név") ?></th>
      <th><?php echo __("Szint") ?></th>
      <th><?php echo __("Kaszt") ?></th>
      <th><?php echo __("Guild") ?></th>
      <th><?php echo __("Kezdet") ?></th>
      <th><?php echo __("Vég") ?></th>
      <th><?php echo __("Akkori szint") ?></th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($botters as $botter): ?>
    <tr>
      <td><?php echo link_to($botter->getName(), "@character_show?slug=" . $botter->getSlug()) ?></td>
      <td><?php echo $botter->getLevel() ?></td>
      <td><?php echo $botter->getVocation() ?></td>
      <td><?php if (null !== ($gname = $botter->getGuildName()))  { echo link_to($gname, "@guild_show?slug=" . $botter->getGuildSlug()); } ?></td>
      <td><?php if (null !== ($at    = $botter->getBanishedAt())) { echo format_datetime($at); } ?></td>
      <td><?php if ($until = $botter->getBanishedUntil())         { echo format_datetime($until); } else { echo("until deletion"); } ?></td>
      <td><?php if ($level = $botter->getBanishedLevel())         { echo $level; } ?></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
