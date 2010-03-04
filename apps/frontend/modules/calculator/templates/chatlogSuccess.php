<?php slot("title", "Chatlog statisztika") ?>
<?php include_javascripts_for_form($form); include_stylesheets_for_form($form) ?>
<div class="containerbox">
  <h3><a href="<?php echo url_for("@calculator_chatlog") ?>">Chatlog statisztika</a></h3>
  <div class="panel">
    <?php if (!isset($stat)): ?>
    <form method="post" action="<?php echo url_for("@calculator_chatlog") ?>">
    <table>
      <tr>
        <td><?php echo $form["log"]->renderLabel() ?></td>
        <td>
          <?php echo $form["log"]->render() ?>
        </td>
        <td>
          <?php echo $form["log"]->renderError() ?>
        </td>
      </tr>
      <tr>
        <td></td>
        <td>
          <?php echo $form->renderHiddenFields() ?>
          <input type="submit" value="<?php echo __("Számolj!", null, "calculator") ?>" />
        </td>
      </tr>
    </table>
    </form>
    <?php else: ?>
    Összes feldolgozott sor: <?php echo $stat["sum_lines"] ?>

    <table class="logstat">
      <thead>
        <tr>
          <th class="string">Név</th>
          <th class="numeric">Sorok</th>
          <th class="numeric">Karakterek</th>
          <th class="numeric">Karakter/sor átlag</th>
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
