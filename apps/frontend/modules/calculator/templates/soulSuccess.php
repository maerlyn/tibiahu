<?php slot("title", __("Soul kalkulátor", null, "calculators")) ?>
<div class="containerbox">
  <h3><?php echo __("Soul kalkulátor", null, "calculators") ?></h3>
  <div class="panel">
    <form action="<?php echo url_for("@calculator_soul") ?>" method="post">
      <table>
        <?php echo $form ?>
        <tr>
          <td colspan="2">
            <input type="submit" value="<?php echo __("Számolj!", null, "calculators") ?>" />
          </td>
        </tr>
      </table>
    </form>
    <?php if (isset($results)): ?>
    <?php echo __("A soulod teljes regenerációjához ennyit kell vadásznod", null, "calculators") ?>:
      <b><?php include_partial("time", array("time" => $results["full"]["hunting"])) ?></b><br />
      
    <?php if (isset($results["partial"]["hunting"])): ?>
    <?php echo __("Részleges regeneráció", null, "calculators") ?>:<br />
    <table border="1">
      <thead>
        <tr>
          <th>Soul</th>
          <th><?php echo __("Idő", null, "calculators") ?></th>
        </tr>                   
      </thead>
      <tbody>
      <?php foreach ($results["partial"]["hunting"] as $soul => $time): ?>
        <tr>
          <td><?php echo $soul ?></td>
          <td><?php include_partial("time", array("time" => $time)) ?></td>
        </tr>
      <?php endforeach ?>
      </tbody>
    </table>
    <br />
    <?php endif ?>
    
    <?php if (isset($results["full"]["sleeping"])): ?>
    <?php echo __("Mivel van promotionöd, esetleg alhatsz, ennyit", null, "calculators") ?>:
      <b><?php include_partial("time", array("time" => $results["full"]["sleeping"])) ?></b><br />
      
    <?php if (isset($results["partial"]["sleeping"])): ?>
      <?php echo __("Részleges regeneráció", null, "calculators") ?>:<br />
      <table border="1">
        <thead>
          <tr>
            <th>Soul</th>
            <th><?php echo __("Idő", null, "calculators") ?></th>
          </tr>                   
        </thead>
        <tbody>
        <?php foreach ($results["partial"]["sleeping"] as $soul => $time): ?>
          <tr>
            <td><?php echo $soul ?></td>
            <td><?php include_partial("time", array("time" => $time)) ?></td>
          </tr>
        <?php endforeach ?>
        </tbody>
      </table>
      <br />
    <?php endif ?>  
    <?php endif ?>
    
    <?php endif ?>
  </div>
</div>
