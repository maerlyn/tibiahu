<?php slot("title", "Bejelentkezés") ?>
<div class="containerbox">
  <h3><?php echo __("Bejelentkezés") ?></h3>
  <div class="panel">
    <?php if ($form->hasGlobalErrors()): ?>
      <?php $form->renderGlobalErrors() ?>
    <?php endif; if ($form["username"]->hasError()): ?>
      <?php echo $form["username"]->renderError() ?>
    <?php endif ?>
    <form method="post" action="<?php echo url_for("@sf_guard_signin") ?>">
      <table>
        <tr>
          <th><?php echo $form["username"]->renderLabel() ?></th>
          <td><?php echo $form["username"]->render() ?></td>
        </tr>
        <tr>
          <th><?php echo $form["password"]->renderLabel() ?></th>
          <td><?php echo $form["password"]->render() ?></td>
        </tr>
        <tr>
          <td></td>
          <td>
            <?php echo $form->renderHiddenFields() ?>
            <input type="submit" value="<?php echo __("Bejelentkezés") ?>" />
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>