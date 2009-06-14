<?php $isAjax = sfContext::getInstance()->getRequest()->isXmlHttpRequest() ?>
<?php slot("title", __("Blessing árkalkulátor", array(), "calculators")) ?>
<?php if (!$isAjax): ?>
  <div class="containerbox">
    <h3><?php echo link_to(__("Blessing árkalkulátor", array(), "calculators"), "@calculator_blessing") ?></h3>
    <div class="panel">
      <form action="<?php echo url_for("@calculator_blessing") ?>" method="post" id="blessingform">
        <table>
          <?php echo $form ?>
          <tr>
            <td colspan="2">
              <input type="submit" value="<?php echo __("Számolj!") ?>" />
            </td>
          </tr>
        </table>
      </form>
      <div id="ajaxresult">
<?php endif; ?>      
      <?php if (isset($errors)) { echo __("Érvénytelen szint.", array(), "calculators"); } ?>
      <?php if (isset($prices)): ?>
      <?php echo __("Ennyibe kerülnek a blessingek, ha a szinted %level%:", array("%level%" => $form->getValue("level")), "calculators") ?><br />
        <table>
          <thead>
            <tr>
              <th><?php echo __("Blessingek száma", array(), "calculators") ?></th>
              <th><?php echo __("Összköltség", array(), "calculators") ?></th>
            </tr>
          </thead>
          <tbody>
          <?php use_helper("Number") ?>
          <?php foreach ($prices as $count => $price): ?>
            <tr>
              <td class="center"><?php echo $count ?></td>
              <td><?php echo format_number($price) ?>gp</td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
        <?php if ($level >= 28) echo __("Ha megcsináltad a %paradox%et és nálad van a %phoenixegglink%, a %sparkofphoenixlink% 10%-al olcsóbb, azaz %sotp_price%gp, így az összköltséged %total_price%gp.",
          array(
            "%paradox%" => "<a href=\"http://tibia.wikia.com/wiki/Paradox_Quest\">Paradox Tower Quest</a>",
            "%phoenixegglink%" => "<a href=\"http://tibia.wikia.com/wiki/Phoenix_Egg\">Phoenix Egg</a>",
            "%sparkofphoenixlink%" => "<a href=\"http://tibia.wikia.com/wiki/The_Spark_of_the_Phoenix\">Spark of the Phoenix</a>",
            "%sotp_price%" => format_number(0.9 * $prices[1]),
            "%total_price%" => format_number($prices[5] - 0.1 * $prices[1])
          ), "calculators") ?><br />
        <?php if ($level >= 95) echo __("Ha megvan az <a href=\"%inq_link%\">Inquisition Quest</a>ed, megveheted őket egyszerre %price%gp-ért.",
          array(
            "%inq_link%" => "http://tibia.wikia.com/wiki/The_Inquisition_Quest", 
            "%price%" => format_number(1.1 * $prices[5])
          ), "calculators") ?><br />
      <?php endif; ?>
<?php if (!$isAjax): ?>    
      </div>  
    </div>
  </div>
<?php endif; ?>
