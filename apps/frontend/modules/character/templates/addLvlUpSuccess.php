<a href="#" class="jqmClose"><?php echo image_tag("close.gif", array("alt"=>"close")) ?></a><br />
<?php echo __("Szóval megmondod nekünk, min lépted meg a %level%-est? Dícséretes.", array("%level%"=>$level)) ?> 
<?php echo __("Ezt a kódot tedd a karakterlapod komment mezőjébe%info%, majd nyomd meg az ok gombot:", 
  array("%info%" => link_to(image_tag("info", array("alt"=>"info")), image_path("commenthelp")))) ?><br />
<?php echo $code ?><br />
<input type="text" id="levelup-reason" value="<?php if (isset($reason)) { echo $reason; } ?>" />
<input type="hidden" id="levelup-url" value="<?php echo $uri ?>" />
<input type="button" value="ok" id="levelup-ok" />
<?php if (isset($error)): ?>
<span class="error"><?php echo $error ?></span>
<?php endif; ?>