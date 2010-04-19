<?php slot("title", __("Cikkek", null, "article")) ?>
<?php use_helper("Date") ?>
<?php include_partial("list", array("articles" => $results)) ?>

<?php if ($pager->haveToPaginate()): /* @var $pager sfPropelPager */?>
  <div class="pagination">
    <?php if (!$pager->isFirstPage()): ?>
    <a href="<?php echo url_for("@article_index?page=1") ?>">&laquo;</a>
    <a href="<?php echo url_for("@article_index?page=" . $pager->getPreviousPage()) ?>">&lsaquo;</a>
    <?php endif ?>

    <?php $is_first = true; foreach ($pager->getLinks() as $page): ?>
      <?php if ($is_first && $page != 1) { echo "…"; } $is_first = false; ?>

      <?php if ($page == $pager->getPage()): ?>
        <b><?php echo $page ?></b>
      <?php else: ?>
        <a href="<?php echo url_for("@article_index?page=" . $page) ?>"><?php echo $page ?></a>
      <?php endif ?>

    <?php $last = $page; endforeach; ?>

    <?php if ($last != $pager->getLastPage()) { echo "…"; } ?>

    <?php if (!$pager->isLastPage()): ?>
    <a href="<?php echo url_for("@article_index?page=" . $pager->getNextPage()) ?>">&rsaquo;</a>
    <a href="<?php echo url_for("@article_index?page=" . $pager->getLastPage()) ?>">&raquo;</a>
    <?php endif ?>
  </div>
<?php endif ?>
