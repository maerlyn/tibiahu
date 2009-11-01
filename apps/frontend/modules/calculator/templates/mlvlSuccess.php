<?php slot("title", __("Magic level kalkulátor", null, "calculators")) ?>
<div class="containerbox">
  <h3><a href="<?php echo url_for("@calculator_mlvl") ?>"><?php echo __("Magic level kalkulátor", null, "calculators") ?></a></h3>
  <div class="panel">
    <form action="<?php echo url_for("@calculator_mlvl") ?>" method="post">
      <table>
      <?php echo $form ?>
      <tr>
        <td>&nbsp;</td>
        <td>
          <input type="submit" value="<?php echo __("Számolj!", null, "calculators") ?>" />
        </td>
      </td>
      </table>
    </form>
    
    <?php if (isset($mana)): ?>
    <?php use_helper("Number") ?>
      <?php echo __("Ez a fejlődés összesen <b>%mana%</b> manát jelent, ennyi regenerálódásához szükséges idő:",
        array("%mana%" => format_number($mana)), "calculators") ?>
      <b><?php include_partial("time", array("time" => $time)) ?></b>.<br /><br />
      
      <?php if (isset($one_percent)): ?>
        1% = <?php echo format_number($one_percent) ?> mana = 
        <?php include_partial("time", array("time" => $one_percent_time)) ?><br /><br />
      <?php endif ?>
      
      <?php if ($soft_boots): ?>
      <?php echo __("Felhasznált soft boots töltés ára: %price% gp", 
        array("%price%" => format_number(floor($mana / 28800 * 10000))), "calculators") ?>
      <br /><br />
      <?php endif ?>
      
      <?php echo __("Instant varázslatok", null, "calculators") ?>:<br />
      <table border="1">
        <thead>
          <tr>
            <th><?php echo __("Varázslat", null, "calculators") ?></th>
            <th>Mana</th>
            <th><?php echo __("Darabszám", null, "calculators") ?></th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($instant_spells as $spellname => $spellmana): ?>
          <tr>
            <td><a href="http://tibia.wikia.com/wiki/Special:Search?search=<?php echo urlencode($spellname) ?>&amp;go=1"><?php echo $spellname ?></a></td>
            <td class="center"><?php echo $spellmana ?></td>
            <td class="center"><?php echo format_number(ceil($mana / $spellmana)) ?></td>
          </tr>
        <?php endforeach ?>
        </tbody>
      </table>
      
      <?php if (isset($rune_spells)): ?>
      <br /><br />
      <?php echo __("Rúnavarázslatok", null, "calculators") ?>:<br />
        <table border="1">
          <thead>
            <tr>
              <th><?php echo __("Varázslat", null, "calculators") ?></th>
              <th>Mana</th>
              <th><?php echo __("Gyártott bp", null, "calculators") ?></th>
              <th><?php echo __("Profit", null, "calculators") ?></th>
            </tr>
          </thead>
          <tbody>
          <?php foreach($rune_spells as $spellname => $spelldata): ?>
            <tr>
              <td><a href="http://tibia.wikia.com/wiki/Special:Search?search=<?php echo urlencode($spellname) ?>&amp;go=1"><?php echo $spellname ?></a></td>              
              <td class="center"><?php echo $spelldata["mana"] ?></td>
              <td class="center"><?php echo format_number(sprintf("%.2f", $bps = ($mana / $spelldata["mana"] / (isset($spelldata["perbp"]) ? $spelldata["perbp"] : 20)))) ?></td>
              <td class="center"><?php echo sprintf("%sgp (%s gp/bp)", format_number(floor($bps * $spelldata["price"])), format_number($spelldata["price"])) ?></td>
            </tr>
          <?php endforeach ?>
          </tbody>
        </table>
      <?php endif ?>
    <?php endif ?>
  </div>
</div>
