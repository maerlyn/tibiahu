<br />
<?php if (!isset($character) || !$character): ?>
  <?php echo __("Nincs ilyen nevű karakter az adatbázisban.", null, "user") ?>
<?php else: ?>
  <?php echo __("Az ellenőrzőkódod: %code%", array("%code%" => "<strong>".$code."</strong>"), "user") ?>&nbsp;
<a href="<?php echo url_for("@user_characters_verify") ?>" id="ajax-verify"><em><?php echo __("Tovább") ?>...</em></a><br />
<div id="ajaxcontainer-verify"></div>
<?php endif ?>
