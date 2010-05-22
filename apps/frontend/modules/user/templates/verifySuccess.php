<br />
<?php if (!isset($character) || !$character): ?>
  <?php echo __("Nincs ilyen nevű karakter az adatbázisban.", null, "user") ?>
<?php elseif (!$verification): ?>
  <?php echo __("Nem találom az ellenőrzőkódot. Biztosan jó helyre raktad? Próbáld meg mégegyszer!", null, "user") ?>
<?php elseif ($verification): ?>
  <?php echo __("Sikeres ellenőrzés. Frissítsd az oldalt, és látni fogod a listában.", null, "user") ?>
<?php endif ?>
