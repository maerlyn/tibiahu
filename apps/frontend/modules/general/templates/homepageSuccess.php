<?php use_helper("Date") ?>
<div class="containerbox">
  <h3><?php echo __("Üdv") ?></h3>
  <div class="panel">
    <p>
      <?php echo __("Üdv a Magyar Tibia rajongói oldalon.") ?>
    </p>
    <p>
      <?php echo __("Főleg naplózunk, a legutóbbi 10 frissítésünk így nézett ki:") ?><br />
    </p>       
      <br/>
      <table class="cronlog">
        <thead>
          <tr>
            <th><?php echo __("Dátum") ?></th>
            <th><?php echo __("Már ismert karakterek") ?></th>
            <th><?php echo __("Új karakterek") ?></th>
            <th><?php echo __("Szintlépések") ?></th>
            <th><?php echo __("Halálok") ?></th>
          </tr>
        </thead>
        <tbody>
<?php foreach ($cronlog as $cronlog_obj): ?>
<?php $temp = unserialize(str_replace("&quot;",'"',$cronlog_obj->getData())) ?>
          <tr>
            <td><?php echo format_datetime($cronlog_obj->getCreatedAt()) ?></td>
            <td><?php echo $temp["updates"] ?></td>
            <td><?php echo $temp["inserts"] ?></td>
            <td><?php echo $temp["levelups"] ?></td>
            <td><?php echo $temp["leveldowns"] ?></td>
          </tr>
<?php endforeach; ?>        
        </tbody>
      </table>
  </div>
</div>