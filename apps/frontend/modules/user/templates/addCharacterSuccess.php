<br />
<?php if (!isset($character) || !$character): ?>
Nincs ilyen nevű karakter az adatbázisban.
<?php else: ?>
Az ellenőrzőkódod: <strong><?php echo $code ?></strong>
<a href="<?php echo url_for("@user_characters_verify") ?>" id="ajax-verify"><em>Ellenőrzés...</em></a><br />
<div id="ajaxcontainer-verify"></div>
<?php endif ?>
