<?php use_helper("Date", "Number") ?>
<?php slot("title", __("Utolsó frissítések")) ?>
<div class="containerbox">
  <h3><?php echo __("Utolsó frissítések") ?></h3>
  <div class="panel">
    <?php $stat = array() ?>
    <?php foreach($servers as $server): ?>
      <b><?php echo($servername = $server->getName()) ?></b>
      <?php $stat[$servername] = array("updates" => array(), "levelups" => array(), "leveldowns" => array()) ?>
      <table class="cronlog" border="1">
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
        <?php foreach ($whoisonline as $whoisonline_object): ?>
        <?php if (!isset($wio[$timestamp = $whoisonline_object->getCreatedAt()])) { $wio[$timestamp] = unserialize(str_replace("&quot;", '"', $whoisonline_object->getData())); } ?>
          <tr>
            <td><?php echo format_datetime($timestamp) ?></td>
            <td><?php echo format_number($a = $wio[$timestamp][$servername]["updates"]) ?></td>
            <td><?php echo format_number($b = $wio[$timestamp][$servername]["inserts"]); array_unshift($stat[$servername]["updates"], $a + $b); ?></td>
            <td><?php echo format_number($a = $wio[$timestamp][$servername]["levelups"]); array_unshift($stat[$servername]["levelups"], $a); ?></td>
            <td><?php echo format_number($a = $wio[$timestamp][$servername]["leveldowns"]); array_unshift($stat[$servername]["leveldowns"], $a); ?></td>
          </tr>
        <?php endforeach ?>
        </tbody>
      </table>
      <br /><br />
    <?php endforeach; ?>
    <?php $keys = array_keys($wio); sort($keys); $dates = array(urlencode(format_datetime($keys[0])), urlencode(format_datetime($keys[count($keys)-1]))); ?>
    <?php foreach($stat as $servername => $v) { $names[] = $servername; $statdata[$servername] = implode(",", $v["updates"]); } ?>
    <?php $statdata = implode("|",$statdata); ?>
    <b><?php echo __("Online karakterek") ?>:</b><br />
    <img src="http://chart.apis.google.com/chart?cht=lc&amp;chs=500x175&amp;chds=0,1000,0,1000&amp;chd=t:<?php echo $statdata ?>&amp;chco=ff0000,00ff00,0000ff&amp;chdl=<?php echo implode("|", $names) ?>&amp;chxt=x,y&amp;chxl=0:|<?php echo implode ("|", $dates) ?>|1:||500|1000&amp;chf=bg,s,363636&amp;chxs=0,bababa|1,bababa&amp;chg=-1,10" alt="online stat" />
    <br /><br />
    
    <?php $statdata = array(); foreach($stat as $servername => $v) { $statdata[$servername] = implode(",", $v["levelups"]); } $statdata = implode("|", $statdata); ?>
    <b><?php echo __("Szintlépések") ?>:</b><br />
    <img src="http://chart.apis.google.com/chart?cht=lc&amp;chs=500x175&amp;chds=0,50,0,50&amp;chd=t:<?php echo $statdata ?>&amp;chco=ff0000,00ff00,0000ff&amp;chdl=<?php echo implode("|", $names) ?>&amp;chxt=x,y&amp;chxl=0:|<?php echo implode("|", $dates) ?>|1:||25|50&amp;chf=bg,s,363636&amp;chxs=0,bababa|1,bababa&chg=-1,20" alt="levelup stat" />
    <br /><br />
    
    <?php $statdata = array(); foreach($stat as $servername => $v) { $statdata[$servername] = implode(",", $v["leveldowns"]); } $statdata = implode("|", $statdata); ?>
    <b><?php echo __("Halálok") ?>:</b><br />
    <img src="http://chart.apis.google.com/chart?cht=lc&amp;chs=500x175&amp;chds=0,20,0,20&amp;chd=t:<?php echo $statdata ?>&amp;chco=ff0000,00ff00,0000ff&amp;chdl=<?php echo implode("|", $names) ?>&amp;chxt=x,y&amp;chxl=0:|<?php echo implode("|", $dates) ?>|1:||20&amp;chf=bg,s,363636&amp;chxs=0,bababa|1,bababa&amp;chg=-1,25" alt="levelup stat" />
    
  </div>
</div>
