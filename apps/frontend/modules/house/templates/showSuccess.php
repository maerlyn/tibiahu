<?php slot("title", __("Házlinkelő")) ?>
<div class="containerbox">
  <h3><?php echo __("Házlinkelő") ?></h3>
  <div class="panel">
    <?php echo __("Hamarosan átirányítunk a %server%i %house% oldalára. Ha nem akarsz várni, nyomd meg a gombot.",
      array(
        "%server%"  =>  $server,
        "%house%"   =>  $house->getName()
      )) ?>
    <form action="http://www.tibia.com/community/?subtopic=houses&amp;page=view" method="post" id="house">
    <p>
      <input type="hidden" name="world" value="<?php echo $server ?>" />
      <input type="hidden" name="houseid" value="<?php echo $house->getId() ?>" />
      <input type="submit" value="<?php echo __("Megyek!") ?>" />
    </p>
    </form>
    <br />
    <img src="<?php echo image_path("house/house_{$house->getId()}.jpg") ?>" alt="<?php echo $house->getName() ?>" /><br />
  </div>
</div>