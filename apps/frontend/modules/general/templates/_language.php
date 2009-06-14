<?php $url = sfContext::getInstance()->getRouting()->getCurrentInternalUri("true"); ?>
<a href="<?php try {echo url_for($url . "&sf_culture=en");} catch (Exception $e) {} ?>">
  <img src="<?php echo image_path("english.gif") ?>" alt="switch to English" />
</a>
<a href="<?php try {echo url_for($url . "&sf_culture=hu");} catch (Exception $e) {} ?>">
  <img src="<?php echo image_path("hungarian.gif") ?>" alt="váltás magyarra" />
</a>
