<?php

/**
 * LevelHistory form base class.
 *
 * @package    tibiahu
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BaseLevelHistoryForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'character_id' => new sfWidgetFormInputHidden(),
      'level'        => new sfWidgetFormInput(),
      'created_at'   => new sfWidgetFormDateTime(),
      'reason_id'    => new sfWidgetFormPropelChoice(array('model' => 'Creature', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorPropelChoice(array('model' => 'LevelHistory', 'column' => 'id', 'required' => false)),
      'character_id' => new sfValidatorPropelChoice(array('model' => 'Character', 'column' => 'id', 'required' => false)),
      'level'        => new sfValidatorInteger(array('required' => false)),
      'created_at'   => new sfValidatorDateTime(array('required' => false)),
      'reason_id'    => new sfValidatorPropelChoice(array('model' => 'Creature', 'column' => 'id', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('level_history[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'LevelHistory';
  }


}
