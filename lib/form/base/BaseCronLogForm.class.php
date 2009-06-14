<?php

/**
 * CronLog form base class.
 *
 * @package    tibiahu
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BaseCronLogForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'created_at' => new sfWidgetFormDateTime(),
      'type'       => new sfWidgetFormInput(),
      'data'       => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorPropelChoice(array('model' => 'CronLog', 'column' => 'id', 'required' => false)),
      'created_at' => new sfValidatorDateTime(array('required' => false)),
      'type'       => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'data'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('cron_log[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'CronLog';
  }


}
