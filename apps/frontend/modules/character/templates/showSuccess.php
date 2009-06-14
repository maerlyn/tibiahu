<?php use_helper("Date") ?>
<?php slot("title", trim($character->getName())) ?>
<div class="containerbox">
  <h3><a href="<?php echo url_for(array("sf_route" => "character_show", "sf_subject" => $character)) ?>"><?php echo $character->getName() ?></a></h3>
  <div class="panel">
    <a href="http://www.tibia.com/community/?subtopic=character&amp;name=<?php echo urlencode($character->getName()) ?>">
      <?php echo __("Karakterprofil a tibia.com-on") ?>
    </a><br />
    <?php echo __("Szerver") ?>: <?php echo $character->getServer() ?><br />
    <?php echo __("%vocation%, szintje %level%", array("%vocation%" => $character->getVocation(), "%level%" => $character->getLevel())) ?><br />
    <?php if ($character->getGuild()): ?>
      <?php echo __("A %guild% guild tagja", array("%guild%" => "<a href=\"".url_for(array("sf_route"=>"guild_show", "sf_subject"=>$character->getGuild()))."\">".$character->getGuild()->getName()."</a>")) ?><br />
    <?php else: ?>  
      <?php echo __("Nem guildtag.") ?><br />
    <?php endif; ?>  
    <?php echo __("Először ekkor láttam: %first_seen%", array("%first_seen%" => format_datetime($character->getCreatedAt()))) ?><br />
    <?php echo __("Legutóbb ekkor láttam: %last_seen%", array("%last_seen%" => format_datetime($character->getLastSeen()))) ?><br />
    <a href="<?php echo url_for(array("sf_route" => "character_feed", "sf_subject" => $character)) ?>"><?php echo __("Karakter szintlépéseinek feedje") ?> <img src="<?php echo image_path("feed.png") ?>" alt="feed icon" /></a><br />
    <br/><br/>
    <?php if (count($violations = $character->getViolations())): ?>
    <?php echo __("Bannolva volt a következőkért") ?>:<br />
    <ul class="error">
      <?php foreach ($violations as $violation): ?>
      <li><?php echo $violation->getReason() ?></li>
      <?php endforeach ?>
    </ul>
    <br /><br />
    <?php endif ?>
    <table class="levelhistory">
      <thead>
        <tr>
          <th><?php echo __("Új szint") ?></th>
          <th><?php echo __("Dátum") ?></th>
          <th><?php echo __("Szörny") ?></th>
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
              <?php if (isset($dir) && $dir == "up") { echo link_to(image_tag("file_edit", array("alt" => __("szerkesztés"))), "@character_addlvlup?slug=".$character->getSlug()."&lvlupid=".$lvl->getId(), array("class"=>"addlevelup", "id"=>"levelup".$lvl->getId())); } ?>
            <?php else: ?>
              <?php echo link_to(image_tag("file_add", array("alt" => __("szerkesztés"))), "@character_addlvlup?slug=".$character->getSlug()."&lvlupid=".$lvl->getId(), array("class"=>"addlevelup", "id"=>"levelup".$lvl->getId())) ?>
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
