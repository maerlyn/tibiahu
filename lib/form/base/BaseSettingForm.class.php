<?php

/**
 * Setting form base class.
 *
 * @package    tibiahu
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BaseSettingForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'key'   => new sfWidgetFormInputHidden(),
      'value' => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'key'   => new sfValidatorPropelChoice(array('model' => 'Setting', 'column' => 'key', 'required' => false)),
      'value' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('setting[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Setting';
  }


}
