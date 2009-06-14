<?php slot("title", __("Stamina kalkulátor")) ?>
<div class="containerbox">
  <h3><?php echo link_to(__("Stamina kalkulátor"), "@calculator_stamina") ?></h3>
  <div class="panel">
    <form action="<?php echo url_for("@calculator_stamina") ?>" method="post">
      <table>
        <?php echo $form ?>
        <tr>
          <td colspan="2"><input type="submit" value="<?php echo __("Számolj!") ?>" /></td>
        </tr>
      </table>
    </form>
    
    <?php if (isset($time)): ?>
    <?php echo __("A stamina teljes regenerálódásához %hours% órát és %minutes% percet kell kilépve töltened.",
    array("%hours%" => $time["hours"], "%minutes%" => $time["minutes"])) ?><br />
    <?php endif; ?>
    
    <?php if (isset($partials) && count($partials)): ?>
    <?php echo __("Részleges regeneráció:") ?><br />
    <table>
      <thead>
        <tr>
          <th>Stamina</th>
          <th><?php echo __("Offline idő") ?></th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($partials as $hour => $partial): ?>
        <tr>
          <td><?php echo $hour ?>:00</td>
          <td><?php echo $partial ?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
    <?php endif; ?>
  </div>
</div>
