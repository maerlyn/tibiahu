<?php slot("title", __("Kapcsolat")) ?>
<div class="containerbox">
  <h3><?php echo __("Kapcsolat") ?></h3>
  <div class="panel">
    <?php echo __("Ha kérdésed, megjegyzésed, ötleted van az oldallal kapcsolatban, netán " 
    . "hibát találtál, általában megtalálsz Securán, mint %ttd%, vagy e-mailben elérsz "
    . "a %email% címen.", 
    array("%ttd%" => link_to("Tele the Druid", "@character_show?slug=tele_the_druid"),
    "%email%" => "<span class=\"email\">" . strrev("tele@tibia.hu") . "</span>")) ?>
  </div>
</div>
