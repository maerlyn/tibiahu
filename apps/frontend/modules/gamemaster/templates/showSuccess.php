<?php slot("title", __("GM-kereső", null, "gamemaster")) ?>
<?php use_helper("Date") ?>
<div class="containerbox">
  <h3><?php echo __("GM-kereső", null, "gamemaster") ?></h3>
  <div class="panel">
    <?php echo __("Szerverváltás", null, "gamemaster") ?>:<br />
    <?php include_partial("serverlist", array("servers" => $servers, "current_server" => $current_server->getName())) ?>
    <br /><br />
    <?php echo __("Ismert GM-ek a <b>%server%</b> szerveren", array("%server%" => $current_server->getName()), "gamemaster") ?>:<br />
    <table>
      <thead>
        <tr>
          <th><?php echo __("Név") ?></th>
          <th><?php echo __("Utoljára bejelentkezett", null, "gamemaster") ?></th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($gamemasters as $gm): ?>
        <tr>
          <td><a href="http://www.tibia.com/community/?subtopic=character&amp;name=<?php echo urlencode($gm->getName()) ?>"><?php echo $gm->getName() ?></a></td>
          <td><?php echo format_datetime($gm->getLastSeen()) ?></td>
        </tr>
      <?php endforeach ?>
      </tbody>
    </table>
  </div>
  <h3>GM hozzáadása<a name="addgmform">&nbsp;</a></h3>
  <div class="panel">
    <?php if (isset($saved)): ?>
    <i><?php echo $saved ?></i> mentve, a következő frissítéstől látszódni fog.
    <?php endif ?>
    <form action="<?php echo url_for($sf_context->getRouting()->getCurrentInternalUri(true)) ?>#addgmform" method="post">
    <?php if ($form->hasGlobalErrors()) { echo $form->renderGlobalErrors(); } ?>
    <?php if ($form["name"]->hasError()) { echo $form["name"]->renderError(); } ?>
    <table>
      <tr>
        <td><?php echo $form["name"]->renderLabel() ?></td>
        <td><?php echo $form["name"]->render() ?></td>
      </tr>
      <tr>
        <td colspan="2">
          <?php echo $form["_csrf_token"]->render() ?>
          <input type="submit" value="Hozzáad" />
        </td>
      </tr>
    </table>
    </form>
  </div>
</div>
