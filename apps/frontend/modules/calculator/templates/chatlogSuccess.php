<?php slot("title", __("Chatlog statisztika", null, "calculators")) ?>
<?php include_javascripts_for_form($form); include_stylesheets_for_form($form) ?>
<div class="containerbox">
  <h3><a href="<?php echo url_for("@calculator_chatlog") ?>"><?php echo __("Chatlog statisztika", null, "calculators") ?></a></h3>
  <div class="panel">
    <?php if (!isset($stat)): ?>
    <form method="post" action="<?php echo url_for("@calculator_chatlog") ?>">
      <?php echo $form["log"]->render() ?><br />
      <?php echo $form["log"]->renderError() ?>
      <?php echo $form->renderHiddenFields() ?>
      <input type="submit" value="<?php echo __("Számolj!", null, "calculators") ?>" />
    </form>
    <?php else: ?>
    <a href="<?php echo url_for("@calculator_chatlog") ?>">&laquo; <?php echo __("Új log feldolgozása", null, "calculators") ?></a><br />
    <?php echo __("Összes feldolgozott sor", null, "calculators") ?>: <?php echo $stat["sum_lines"] ?>

    <table class="logstat">
      <thead>
        <tr>
          <th class="string"><?php echo __("Név", null, "calculators") ?></th>
          <th class="numeric"><?php echo __("Sorok", null, "calculators") ?></th>
          <th class="numeric"><?php echo __("Karakterek", null, "calculators") ?></th>
          <th class="numeric"><?php echo __("Karakter/sor átlag", null, "calculators") ?></th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($stat["character"] as $k => $v): ?>
        <tr>
          <td><?php echo $k ?></td>
          <td><?php echo $v["lines"] ?></td>
          <td><?php echo $v["characters"] ?></td>
          <td><?php echo sprintf("%.2f", $v["avg"]) ?></td>
        </tr>
      <?php endforeach ?>
      </tbody>
    </table>
    <?php endif ?>
  </div>
</div>
