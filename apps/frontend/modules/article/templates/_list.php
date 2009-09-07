<?php foreach($articles as $item): ?>
<div class="containerbox news">
  <h3><a href="<?php echo url_for(array("sf_route" => "article_show", "sf_subject" => $item)) ?>"><?php echo $item->getTitle() ?></a></h3>
  <div class="panel">
    <ul class="prop">
      <li>
        <img src="<?php echo image_path("icon-calendar.png") ?>" alt="<?php echo __("Dátum", array(), "article") ?>" title="<?php echo __("Dátum", array(), "article") ?>" />
        <?php echo format_date($item->getCreatedAt()) ?>
      </li>
      <li>
        <img src="<?php echo image_path("tag.png") ?>" alt="<?php echo __("Cimkék", array(), "article") ?>" title="<?php echo __("Cimkék", array(), "article") ?>" />
        <?php $tags = array(); foreach ($item->getTags() as $v) { $tags[] = $v; } echo implode(", ", $tags); ?>
      </li>
      <li>
        <img src="<?php echo image_path("icon-user.png") ?>" alt="<?php echo __("Írta", array(), "article") ?>" title="<?php echo __("Írta", array(), "article") ?>" />
        <?php echo $item->getsfGuardUser()->getUsername() ?>
      </li>
    </ul>
    <br />
    <?php echo ($item->getBodyHtml(ESC_RAW)) ?>
  </div>
</div>
<?php endforeach ?>
