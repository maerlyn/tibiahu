<?php

/**
 * Botter form base class.
 *
 * @package    tibiahu
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BaseBotterForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'character_id'   => new sfWidgetFormInputHidden(),
      'banished_until' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'character_id'   => new sfValidatorPropelChoice(array('model' => 'Character', 'column' => 'id', 'required' => false)),
      'banished_until' => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('botter[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Botter';
  }


}
