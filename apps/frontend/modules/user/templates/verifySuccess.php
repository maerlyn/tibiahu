<br />
<?php if (!isset($character) || !$character): ?>
Nincs ilyen nevű karakter az adatbázisban.
<?php elseif (!$verification): ?>
Nem találom az ellenőrzőkódot. Biztosan jó helyre raktad? Próbáld meg mégegyszer!
<?php elseif ($verification): ?>
Sikeres ellenőrzés. Frissítsd az oldalt, és látni fogod a listában.
<?php endif ?>
