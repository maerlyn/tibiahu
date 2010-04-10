<?php slot("title", __("Userscriptek")) ?>
<div class="containerbox">
  <h3><?php echo link_to(__("Userscriptek"), "@userscripts") ?></h3>
  <div class="panel">
    <?php echo __(<<<EDDIG
A userscriptek olyan speciális javascript fájlok, amelyek egy-egy meghatározott oldal
minden látogatásakor lefutnak, és ki tudják bővíteni annak funkcióit. Az Internet
Explorer böngésző kivételével minden böngésző támogatja a userscriptek használatát:
EDDIG
) ?>
    <ul>
      <li><a href="https://addons.mozilla.org/en-US/firefox/addon/748">Firefox (Greasemonkey)</a></li>
      <li><a href="http://lifehacker.com/5180010/enable-user-scripts-in-google-chrome">Chrome</a></li>
      <li><a href="http://www.opera.com/browser/tutorials/userjs/">Opera</a></li>
      <li><a href="http://www.simplehelp.net/2007/11/14/how-to-run-greasemonkey-scripts-in-safari/">Safari</a></li>
    </ul>

    <?php echo __("Lentebb megtalálod a %link%-val kapcsolatos scripteket.",
      array("%link%" => "<a href=\"http://tibia.hu/\">tibia.hu</a>")) ?>
  </div>

  <h3><?php echo __("Karakterlinkelő") ?></h3>
  <div class="panel">
    <?php echo __(<<<EDDIG
Tibia.com-on a karakterlapokon a karakter nevét átalakítja linkké, ami a tibia.hu-n
levő karakterlapra mutat. Minden lapon, tehát nem ellenőrzi, olyan szerveren van-e
a karakter, amivel foglalkozik az oldal, vagy létezik-e a karakter az oldal adatbázisában.
EDDIG
) ?>
    <br /><br />
    <img src="<?php echo public_path("/uploads/assets/characterlinker-" . $sf_user->getCulture() . ".png") ?>" alt="charlinker" class="center" />
    <br /><br />
    <a href="<?php echo public_path("/uploads/assets/tibia_character_linker.user.js") ?>"><?php echo __("Karakterlinkelő script letöltése") ?></a>
  </div>

  <h3><?php echo __("Házlinkelő") ?></h3>
  <div class="panel">
    <?php echo __(<<<EDDIG
Tibia.com-on a karakterlapokon ha az épp nézett karakternek van háza, a ház nevét
átalakítja linkké, amely a tibia.hu-n keresztül a ház tibia.com-os adatlapjára vezet.
EDDIG
) ?>
    <br /><br />
    <img src="<?php echo public_path("/uploads/assets/houselinker-" . $sf_user->getCulture() . ".png") ?>" alt="houselinker" class="center" />
    <br /><br />
    <a href="<?php echo public_path("/uploads/assets/tibia_house_linker.user.js") ?>"><?php echo __("Házlinkelő script letöltése") ?></a>
  </div>
</div>
