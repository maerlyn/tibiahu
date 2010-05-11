<form method="post" action="<?php echo url_for("@sf_guard_signin") ?>">
<p>
  <?php echo $form["username"]->renderLabel() ?><br />
  <?php echo $form["username"]->render() ?><br />
  <?php echo $form["password"]->renderLabel() ?><br />
  <?php echo $form["password"]->render() ?><br /><br />
  <?php echo $form->renderHiddenFields() ?>
  <input type="submit" value="<?php echo __("Bejelentkezés", null, "user") ?>" />
</p>
</form>
<?php echo link_to(__("Regisztráció", null, "user"), "@sf_guard_register") ?>
