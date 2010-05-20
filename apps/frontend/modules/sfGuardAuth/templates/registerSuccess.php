<?php slot("title", __("Regisztráció")) ?>
<div class="containerbox">
  <h3><?php echo __("Regisztráció") ?></h3>
  <div class="panel">
    <form method="post" action="<?php echo url_for("@sf_guard_register") ?>">
      <?php if ($form->hasGlobalErrors()): ?>
        <?php echo $form->renderGlobalErrors() ?>
      <?php endif ?>
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
            <input type="submit" value="<?php echo __("Regisztráció") ?>" />
          </td>
          <td></td>
        </tr>
      </table>
    </form>
  </div>
</div>