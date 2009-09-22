<?php use_helper("Date") ?>
<?php slot("title", trim($character->getName())) ?>
<div class="containerbox">
  <h3><?php echo __("Karakter adatlap", array(), "character") ?></h3>
  <div class="panel">
    <?php if ($sf_user->hasFlash("redirect")): ?>
    <div class="flash info">
      <p>
        <img src="<?php echo image_path("info.png") ?>" alt="information" />
        <?php echo __($sf_user->getFlash("redirect"), null, "character") ?>
      </p>  
    </div>
    <?php endif ?>
    <h2>
      <?php echo link_to($character->getName(), "@character_show?slug=" . $character->getSlug()) ?> 
      <a href="http://www.tibia.com/community/?subtopic=character&amp;name=<?php echo urlencode($character->getName(ESC_RAW)) ?>" title="<?php echo __("Karakterprofil a tibia.com-on", array(), "character") ?>"><img src="<?php echo image_path("tibiaicon.png") ?>" alt="tibia icon" /></a>
    </h2>
    <br />
    <?php echo __("Szerver") ?>: <?php echo $character->getServer() ?><br />
    <?php echo __("Kaszt") ?>: <?php echo $character->getVocation() ?><br />
    <?php echo __("Szint") ?>: <?php echo $character->getLevel() ?><br />
    <?php if ($character->getGuild()): ?>
      <?php echo __("A %guild% guild tagja", array("%guild%" => "<a href=\"".url_for(array("sf_route"=>"guild_show", "sf_subject"=>$character->getGuild()), "character")."\">".$character->getGuild()->getName()."</a>"), "character") ?><br />
    <?php else: ?>  
      <?php echo __("Nem guildtag.", array(), "character") ?><br />
    <?php endif; ?>  
    <br />
    <p>
      <?php echo __("Először ekkor láttam: %first_seen%", array("%first_seen%" => format_datetime($character->getCreatedAt())), "character") ?><br />
      <?php echo __("Legutóbb ekkor láttam: %last_seen%", array("%last_seen%" => format_datetime($character->getLastSeen())), "character") ?><br />
    </p><br />
    <p>
      <?php $sharelevels = Tibiahu::getXpShareLevels($character->getLevel()); ?>
      <?php echo __("Experience share minimum szint", array(), "character") ?>: <?php echo $sharelevels["min"] ?><br />
      <?php echo __("Experience share maximum szint", array(), "character") ?>: <?php echo $sharelevels["max"] ?><br />
    </p><br />
    <?php if (count($violations = $character->getViolations())): ?>
    <?php echo __("Bannolva volt a következőkért", array(), "character") ?>:<br />
    <ul class="error">
      <?php foreach ($violations as $violation): ?>
      <li><?php echo $violation->getReason() ?></li>
      <?php endforeach ?>
    </ul>
    <br /><br />
    <?php endif ?>
    <?php echo __("Szintváltozásai", array(), "character") ?>:
    <a href="<?php echo url_for(array("sf_route" => "character_feed", "sf_subject" => $character)) ?>"><img src="<?php echo image_path("feed.png") ?>" alt="<?php echo __("Karakter szintlépéseinek feedje", array(), "character") ?>" /></a><br />
    <table class="levelhistory">
      <thead>
        <tr>
          <th class="col1"><?php echo __("Új szint", array(), "character") ?></th>
          <th class="col2"><?php echo __("Dátum", array(), "character") ?></th>
          <th class="col3"><?php echo __("Szörny", array(), "character") ?></th>
        </tr>
      </thead>
      <tbody>
<?php foreach($levelhistory as $lvl): ?>
        <tr <?php if (isset($oldlvl)) {echo "class=\"".($oldlvl<$lvl->getLevel()?$dir="up":$dir="down")."\"";} $oldlvl=$lvl->getLevel(); ?>>
          <td><?php echo $lvl->getLevel() ?></td>
          <td><?php echo format_datetime($lvl->getCreatedAt()) ?></td>
          <td>
            <?php if ($lvl->getReason()): ?>
              <?php echo link_to($lvl->getReason(),"http://tibia.wikia.com/wiki/Special:Search?search=".urlencode($lvl->getReason())) ?> 
              <?php if (isset($dir) && $dir == "up") { echo link_to(image_tag("file_edit", array("alt" => __("Szerkesztés", null, "character"))), "@character_addlvlup?slug=".$character->getSlug()."&lvlupid=".$lvl->getId(), array("class"=>"addlevelup", "id"=>"levelup".$lvl->getId())); } ?>
            <?php else: ?>
              <?php echo link_to(image_tag("file_add", array("alt" => __("Szerkesztés", null, "character"))), "@character_addlvlup?slug=".$character->getSlug()."&lvlupid=".$lvl->getId(), array("class"=>"addlevelup", "id"=>"levelup".$lvl->getId())) ?>
            <?php endif; ?>
           </td>
        </tr>
<?php endforeach; ?>
      </tbody>
    </table>
    <br />
    <div id="editlevelup" class="jqmWindow"></div>
    <img src="<?php echo image_path("ajax-loader.gif") ?>" class="ajaxloader" alt="ajax loader" />
  </div>
</div>
