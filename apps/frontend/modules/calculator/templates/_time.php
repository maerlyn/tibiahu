<?php echo format_number_choice("[0][1]1 nap (1,+Inf]%count% nap ", 
  array("%count%" => $time["days"]), $time["days"], "calculators") ?> 
<?php echo format_number_choice("[0]0 óra [1]1 óra (1,+Inf]%count% óra ",
  array("%count%" => $time["hours"]), $time["hours"], "calculators") ?> 
<?php echo format_number_choice("[0]0 perc [1]1 perc (1,+Inf]%count% perc ",
  array("%count%" => $time["minutes"]), $time["minutes"], "calculators") ?> 
<?php echo format_number_choice("[0]0 másodperc[1]1 másodperc(1,+Inf]%count% másodperc",
  array("%count%" => $time["seconds"]), $time["seconds"], "calculators") ?>
