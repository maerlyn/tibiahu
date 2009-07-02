<?php foreach($news as $item): ?>
<div class="containerbox news">
  <h3><a href="<?php echo url_for(array("sf_route" => "news_show", "sf_subject" => $item)) ?>"><?php echo $item->getTitle() ?></a></h3>
  <div class="panel">
    <ul class="prop">
      <li>
        <img src="<?php echo image_path("icon-calendar.png") ?>" alt="<?php echo __("Dátum", array(), "news") ?>" title="<?php echo __("Dátum", array(), "news") ?>" />
        <?php echo format_date($item->getCreatedAt()) ?>
      </li>
      <li>
        <img src="<?php echo image_path("icon-category.png") ?>" alt="<?php echo __("Kategória", array(), "news") ?>" title="<?php echo __("Kategória", array(), "news") ?>" />
        <?php echo $item->getNewsCategory()->getName() ?>
      </li>
      <li>
        <img src="<?php echo image_path("icon-user.png") ?>" alt="<?php echo __("Írta", array(), "news") ?>" title="<?php echo __("Írta", array(), "news") ?>" />
        <?php echo $item->getsfGuardUser()->getUsername() ?>
      </li>
    </ul>
    <br />
    <?php echo ($item->getBody(ESC_RAW)) ?>
  </div>
</div>
<?php endforeach ?>
