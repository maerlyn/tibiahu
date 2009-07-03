<?php slot("title", __("Szintlépés kalkulátor")) ?>
<div class="containerbox">
  <h3><?php echo link_to(__("Szint kalkulátor"), "@calculator_level") ?></h3>
  <div class="panel">
    <form action="<?php echo url_for("@calculator_level") ?>" method="post">
      <table>
        <?php echo $form ?>
        <tr>
          <td colspan="2">
            <input type="submit" value="<?php echo __("Számolj!") ?>" />
          </td>
        </tr>
      </table>
    </form>
    <?php if (isset($level_current)):  #ha volt ervenyes kuldott adat, es szamoltunk ?>
    <?php use_helper("Number") ?>
    <p>
      <?php echo __("Ennyi tapasztalattal az aktuális szinted <b>%level_current%</b>,<br /> "
      . "a következő szinthez <b>%xp_next%</b> xp kell, ebből <b>%xp_remaining%</b> van még hátra.",
      array(
        "%level_current%" =>  format_number($level_current),
        "%xp_next%"       =>  format_number($xp_next),
        "%xp_remaining%"  =>  format_number($xp_remaining)
      ), "calculators") ?>
      <br />
      <?php echo __("Szörnyszerűsítve ez így néz ki:", array(), "calculators") ?>
    </p>
    
    <table border="1">
      <thead>
        <tr>
          <th><?php echo __("Szörny") ?></th>
          <th><?php echo __("Darabszám") ?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($monsters as $monster => $count): ?>
        <tr>
          <td><?php echo link_to($monster,"http://tibia.wikia.com/wiki/Special:Search?search=".urlencode($monster)) ?></td>
          <td class="center"><?php echo $count ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>  
    
   <?php echo __("Experience share minimum szint", array(), "character") ?>: <b><?php echo $xpshare_levels["min"] ?></b><br />
   <?php echo __("Experience share maximum szint", array(), "character") ?>: <b><?php echo $xpshare_levels["max"] ?></b><br />
    
    <?php endif; ?>
    
  </div>
</div>
