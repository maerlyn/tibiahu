<form action="<?php url_for("@character_search") ?>" method="post">
  <table border="0">
    <?php echo $form ?>
    <tr>
      <td colspan="2">
        <input type="submit" name="target" value="<?php echo __("Karakter") ?>" />
        <input type="submit" name="target" value="<?php echo __("Guild") ?>" />
      </td>
    </tr>
  </table>
</form>
