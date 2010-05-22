<?php slot("title", __("Karaktereim", null, "user")) ?>
<div class="containerbox">
  <h3><?php echo link_to(__("Karaktereim", null, "user"), "@user_characters") ?></h3>
  <div class="panel">
    <?php echo format_number_choice("[0]Nincs karaktered|[1,+Inf]%count% karaktered van",
      array("%count%" => count($characters)), count($characters), "user") ?>
    <table class="searchresults">
      <thead>
        <tr>
          <th><?php echo __("Név") ?></th>
          <th><?php echo __("Kaszt") ?></th>
          <th><?php echo __("Szint") ?></th>
          <th><?php echo __("Guild") ?></th>
          <th><?php echo __("Szerver") ?></th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($characters as $character): ?>
        <tr>
          <td><?php echo link_to($character->getName(), "character_show", $character) ?></td>
          <td><?php echo $character->getVocation() ?></td>
          <td><?php echo $character->getLevel() ?></td>
          <td><?php echo link_to($character->getGuild(), "guild_show", $character->getGuild()) ?></td>
          <td><?php echo $character->getServer() ?></td>
        </tr>
      <?php endforeach ?>
      </tbody>
    </table>
    <br />
    <noscript>
      <?php echo __("Engedélyezd a javascriptet, hogy új karaktert tudj hozzáadni a fiókodhoz.", null, "user") ?>
    </noscript>
    
    <a id="addlink" class="button addbutton hidden"><?php echo __("Hozzáadás", null, "user") ?></a>
    <div id="addbox" class="hidden">
      <img src="<?php echo public_path("images/ajax-loader.gif") ?>" class="loader hidden" alt="loader" />
      <?php echo __("Név") ?>: <input type="text" name="charname" id="input-charname" />
      <a href="<?php echo url_for("@user_characters_add") ?>" id="get-code"><em><?php echo __("Tovább") ?>...</em></a><br />
      <div id="ajaxcontainer-code"></div>
    </div>
  </div>
</div>
