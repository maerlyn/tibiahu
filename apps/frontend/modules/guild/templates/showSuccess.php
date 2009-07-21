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

  <h3><?php echo __("Statisztika", null, "guild") ?><a name="stat">&nbsp;</a></h3>  
  <div class="panel">
  
  <?php
    $max_seen = array(
      "id"  =>  0,
      "time"  =>  $members[0]->getLastSeen()
    );
    $vocations = array(
      "None"  =>            0,
      "Knight"  =>          0,
      "Paladin" =>          0,
      "Druid" =>            0,
      "Sorcerer"  =>        0,
      "Elite Knight"  =>    0,
      "Royal Paladin" =>    0,
      "Elder Druid" =>      0,
      "Master Sorcerer" =>  0,
    );
    $promoted = 0;
    $sum_level = 0;
  
    foreach ($members as $k => $v) {
      if ($time = $v->getLastSeen() < $max_seen["time"]) {
        $max_seen = array("id" => $k, "time" => $v->getLastSeen());
      }
      
      ++$vocations[$v->getVocation()];
      if (false !== strpos($v->getVocation(), " ")) {
        ++$promoted;
      }
      $sum_level += $v->getLevel();
    }
    arsort($vocations);
    
  ?>
  
    <?php use_helper("Date", "Number") ?>
    <?php echo __("Legrégebben látott karakter", null, "guild");$char = $members[$max_seen["id"]] ?>: 
      <?php echo link_to($char->getName(), "@character_show?slug=" . $char->getSlug()) ?>
      (<?php echo format_datetime($char->getLastSeen()) ?>)<br />
    <br />
    
    <?php echo __("Promoted karakterek", null, "guild") ?>:
      <?php echo $promoted ?> (<?php echo format_number(round($promoted / count($members) * 100, 1)) ?>%)<br />
    <br />
    
    <?php echo __("Átlagos szint", null, "guild") ?>:
      <?php echo format_number(round($sum_level / count($members), 1)) ?><br />
      
    <br />      
    <table border="1">
      <thead>
        <tr>
          <th><?php echo __("Kaszt") ?></th>
          <th><?php echo __("Darabszám", null, "calculators") ?></th>
          <th><?php echo __("Arány", null, "guild") ?></th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($vocations as $k => $v): ?>
        <tr>
          <td><?php echo $k ?></td>
          <td><?php echo $v ?></td>
          <td><?php echo format_number(round($v / count($members) * 100, 1)) ?>%</td>
        </tr>
      <?php endforeach ?>
      </tbody>
    </table>
  </div>
</div>
