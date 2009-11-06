<?php slot("title", __("Tradelt/megosztott karakterek")) ?>
<div class="containerbox">
  <h3><?php echo __("Tradelt/megosztott karakterek") ?></h3>
  <div class="panel">
    <?php echo __("Ezek a karakterek legalább egyszer bannolva voltak account trading miatt.") ?><br />
    <br />
    
    <?php include_partial("serverlist", array(
      "servers"         =>  $servers,
      "current_server"  =>  isset($server) ? $server : null,
      "route"           =>  "character_acctraders_for_server",
    )) ?>
    <br /><br />
    
    <?php if (isset($pager)): ?>
      <?php include_partial("botterlist", array(
        "total"   =>  $pager->getNbResults(),
        "first"   =>  $pager->getFirstIndice(),
        "last"    =>  $pager->getLastIndice(),
        "botters" =>  $pager->getResults(),
        "feedurl" =>  url_for("@character_banfeed?reason=acctraders&server=" . $sf_request->getParameter("server"))
      )) ?>
    <?php endif ?>
    
    <?php if (isset($pager) && $pager->haveToPaginate()): ?>
    <div class="pagination">
      <?php foreach ($pager->getLinks($pager->getLastPage()) as $page): ?>
        <?php if ($page == $pager->getPage()): ?>
          <b><?php echo $page ?></b>
        <?php else: ?>
          <a href="<?php echo url_for("@character_acctraders_for_server?page=" . $page . "&name=" . $server->getName()) ?>"><?php echo $page ?></a> 
        <?php endif ?>
      <?php endforeach ?>   
     </div>
     <?php endif ?>     
  </div>
</div>
