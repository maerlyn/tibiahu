<?php if (isset($searchvalue)) { $searchvalue = $sf_data->getRaw("searchvalue"); } ?>
      <table class="searchresults">
        <thead>
          <tr>
            <th><?php echo __("Név") ?></th>
            <th><?php echo __("Szint") ?></th>
            <th><?php echo __("Kaszt") ?></th>
            <th><?php echo __("Online volt-e a legutóbbi frissítéskor?") ?></th>
<?php if (isset($include_server) && $include_server): ?>
            <th><?php echo __("Szerver") ?></th>
<?php endif; ?>
          </tr>
        </thead>
        <tbody>
<?php foreach($characters as $character): ?>
          <tr>
            <td>
              <a href="<?php echo url_for(array("sf_route" => "character_show", "sf_subject" => $character)) ?>">
                <?php echo isset($searchvalue)?preg_replace("#({$searchvalue})#is", "<b>\\1</b>", $character->getName()):$character->getName() ?>
              </a>
            </td>    
            <td><?php echo $character->getLevel() ?></td>
            <td><?php echo $character->getVocation() ?></td>
            <td class="<?php echo $character->wasOnlineLastTime() ? 'up' : 'down' ?>">
              <?php echo $character->wasOnlineLastTime() ? __('igen') : __('nem') ?>
            </td>
<?php if (isset($include_server) && $include_server): ?>
            <td><?php echo $character->getServer() ?></td>
<?php endif; ?>

          </tr>
<?php endforeach; ?>
        </tbody>
      </table>