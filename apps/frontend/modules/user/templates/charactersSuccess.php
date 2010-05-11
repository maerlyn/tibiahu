<div class="containerbox">
  <h3>Karaktereim</h3>
  <div class="panel">
    <?php echo format_number_choice("[0]Nincs karaktered|[1,+Inf]%count% karaktered van",
      array("%count%" => count($characters)), count($characters)) ?>
    <table class="searchresults">
      <thead>
        <tr>
          <th>Név</th>
          <th>Kaszt</th>
          <th>Szint</th>
          <th>Guild</th>
          <th>Szerver</th>
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
      Engedélyezd a javascriptet, hogy új karaktert tudj hozzáadni a fiókodhoz.
    </noscript>
    
    <a id="addlink" class="button addbutton hidden">Hozzáadás</a>
    <div id="addbox" class="hidden">
      Karakter neve: <input type="text" name="charname" id="input-charname" />
      <a href="<?php echo url_for("@user_characters_add") ?>" id="get-code"><em>Tovább...</em></a><br />
      <div id="ajaxcontainer-code"></div>
    </div>
  </div>
</div>
