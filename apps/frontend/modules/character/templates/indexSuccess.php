<?php slot("title", __("Keresés")) ?>
<div class="containerbox">
  <h3><?php echo __("Keresés") ?></h3>
  <div class="panel">
    <form action="<?php echo url_for("@character_search") ?>" method="post">
      <table border="0">
        <?php echo $form ?>
        <tr>
          <td colspan="2">
            <input type="submit" value="<?php echo __("Keress") ?>" />
          </td>
        </tr>
      </table>
    </form>
    
    <?php if (isset($characters)): ?>
      <?php echo __("%count% karaktert találtam.", array("%count%" => count($characters))) ?>
      <br />
<?php include_partial("list", 
  array(
    "characters" => $characters, 
    "searchvalue" => $form->getValue("name"),
    "include_server"  =>  true
  )) ?>
    <?php endif; ?>
  </div>
</div>