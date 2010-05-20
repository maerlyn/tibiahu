<div class="containerbox">
  <h3><?php echo link_to("Beállítások", "@user_settings") ?></h3>
  <div class="panel">
    <?php if ($sf_user->hasFlash("saved")): ?>
    <div class="flash info">
      <p>
        <img src="<?php echo image_path("info.png") ?>" alt="information" />
        <?php echo __($sf_user->getFlash("saved"), null, "character") ?>
      </p>
    </div>
    <?php endif ?>

    <?php if ($form->hasGlobalErrors()): ?>
      <?php echo $form->renderGlobalErrors() ?>
    <?php endif ?>
    <form method="post" action="<?php echo url_for("@user_settings") ?>">
      <table>
      <?php foreach ($form as $field): ?>
      <?php if ($field->isHidden()) { continue; } ?>
        <tr>
          <th><?php echo $field->renderLabel() ?></th>
          <td><?php echo $field->render() ?></td>
          <td>
            <?php echo $field->renderError() ?>
            <?php echo strip_tags($field->renderHelp()) ?>
          </td>
        </tr>
      <?php endforeach ?>
        <tr>
          <td></td>
          <td>
            <?php echo $form->renderHiddenFields() ?>
            <input type="submit" value="Mentés" />
          </td>
          <td></td>
        </tr>
      </table>
    </form>
  </div>
</div>
