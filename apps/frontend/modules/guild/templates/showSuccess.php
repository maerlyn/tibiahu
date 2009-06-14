<?php slot("title", $guild->getName()) ?>
<div class="containerbox">
  <h3><a href="<?php echo url_for(array("sf_route" => "guild_show", "sf_subject" => $guild)) ?>"><?php echo $guild->getName() ?></a></h3>
  <div class="panel">

    <a href="http://www.tibia.com/community/?subtopic=guilds&page=view&GuildName=<?php echo urlencode($guild->getName()) ?>">
      <?php echo __("Guild adatlapja a tibia.com-on") ?>
    </a><br />
    <a href="<?php echo url_for(array("sf_route" => "guild_feed", "sf_subject" => $guild)) ?>">
      <?php echo __("Guildtagok szintlépéseinek feedje") ?> <img src="<?php echo image_path("feed.png") ?>">
    </a><br />
    <?php echo __("Szerver") ?>: <?php echo $guild->getServer() ?><br />

    <?php echo __("Összesen %count% tag.", array("%count%" => count($members))) ?>
<?php include_partial("character/list", array("characters" => $members)) ?>    
  </div>
</div>