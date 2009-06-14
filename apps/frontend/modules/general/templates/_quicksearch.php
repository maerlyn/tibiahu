<?php use_helper("Url") ?>
<?php use_helper("Form") ?>
<!-- <form action="<?php url_for("@character_search") ?>" method="post"> -->
<?php echo form_tag("@character_search") ?>
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
