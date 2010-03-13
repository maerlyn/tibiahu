<?php

class LogstatForm extends sfForm
{

  /**
   * Returns the probable max length acceptable, using php.ini's post_max_size
   */
  public  function getMaxLength()
  {
    if ($pms = @ini_get("post_max_size")) {
      if (strpos($pms, "M") !== false) {
        $pms *= 1024*1024;
      }
      return floor($pms * 0.95);
    } else {
      return 2*1024*1024;
    }
  }

  public function configure()
  {
    parent::configure();

    $this->setWidget("log", new sfWidgetFormTextarea(array(
      "label" =>  "Log",
    )));

    $this->setValidator("log", new sfValidatorString(array(
      "min_length"  =>  10,
      "max_length"  =>  $this->getMaxLength(),
    ), array(
      "min_length"  =>  "Túl rövid!",
      "max_length"  =>  "Túl hosszú!",
    )));

    $this->widgetSchema->setNameFormat("logstatform[%s]");
  }

}
