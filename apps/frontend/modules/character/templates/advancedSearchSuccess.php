<?php slot("title", __("Részletes keresés")) ?>
<div class="containerbox">
  <h3><?php echo __("Részletes keresés") ?></h3>
  <div class="panel">

    <form action="<?php url_for("@character_advancedsearch") ?>" method="post">
      <table border="0">
        <?php echo $form ?>
        <tr>
          <td colspan="2">
            <input type="submit" value="<?php echo __("Keress") ?>" />
          </td>
        </tr>
      </table>
    </form>

    <?php if (isset($count)): ?>
      <?php echo __("%count% karaktert találtam.", array("%count%" => $count)) ?><br />
      <?php if (isset($characters)): ?>
        <?php include_partial("list", array("characters" => $characters, "include_server"  =>  true)) ?>
      <?php else: ?>
        <?php echo __("Túl sok ez a megjelenítéshez, próbáld finomítani a feltételeidet!") ?>
      <?php endif; ?>
    <?php endif; ?>
    
  </div>
</div>
