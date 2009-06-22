<?php slot("title", __("Hírek")) ?>
<?php use_helper("Date") ?>

<?php foreach($news as $item): ?>
<div class="containerbox">
  <h3><?php echo $item->getTitle() ?></h3>
  <div class="panel">
    <h4>Dátum: <?php echo format_date($item->getCreatedAt()) ?></h4>
    <h4>Kategória: <?php echo $item->getNewsCategory()->getName() ?></h4>
    <h4>Írta: <?php echo $item->getsfGuardUser()->getUsername() ?></h4>
    <br />
    <?php echo nl2br($item->getBody(ESC_RAW)) ?>
  </div>
</div>
<?php endforeach ?>
