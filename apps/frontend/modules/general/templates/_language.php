<?php if ($sf_user->getCulture() != "en"): ?>
<a href="<?php echo $url_en ?>">
  <img src="<?php echo image_path("english.gif") ?>" alt="switch to English" />
</a>
<?php endif;
if ($sf_user->getCulture() != "hu"): ?>
<a href="<?php echo $url_hu ?>">
  <img src="<?php echo image_path("hungarian.gif") ?>" alt="váltás magyarra" />
</a>
<?php endif ?>
