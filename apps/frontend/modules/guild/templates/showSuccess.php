<?php slot("title", $guild->getName()) ?>
<div class="containerbox">
  <h3><?php echo __("Guild adatlap", array(), "guild") ?></h3>
  <div class="panel">
    <?php if ($sf_user->hasFlash("redirect")): ?>
    <div class="flash info">
      <p>
        <img src="<?php echo image_path("info.png") ?>" alt="information" />
        <?php echo __($sf_user->getFlash("redirect"), null, "guild") ?>
      </p>  
    </div>
    <?php endif ?>
    <h2>
      <?php echo link_to($guild->getName(), "@guild_show?slug=" . $guild->getSlug()) ?> 
      <a href="http://www.tibia.com/community/?subtopic=guilds&page=view&GuildName=<?php echo urlencode($guild->getName()) ?>" title="<?php echo __("Guild adatlapja a tibia.com-on", array(), "guild") ?>">
        <img src="<?php echo image_path("tibiaicon.png") ?>" alt="tibia icon" />
      </a>
    </h2>
    <br />
    <a href="<?php echo url_for(array("sf_route" => "guild_feed", "sf_subject" => $guild)) ?>">
      <?php echo __("Guildtagok szintlépéseinek feedje", array(), "guild") ?> <img src="<?php echo image_path("feed.png") ?>">
    </a><br />
    <?php echo __("Szerver") ?>: <?php echo $guild->getServer() ?><br />

    <?php echo __("Összesen %count% tag.", array("%count%" => count($members)), "guild") ?>
<?php include_partial("character/list", array("characters" => $members)) ?>    
  </div>
</div>