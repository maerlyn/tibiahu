<?php

/**
 * Banishment form base class.
 *
 * @package    tibiahu
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BaseBanishmentForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'character_id'    => new sfWidgetFormPropelChoice(array('model' => 'Character', 'add_empty' => true)),
      'banished_until'  => new sfWidgetFormDateTime(),
      'banished_for_id' => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorPropelChoice(array('model' => 'Banishment', 'column' => 'id', 'required' => false)),
      'character_id'    => new sfValidatorPropelChoice(array('model' => 'Character', 'column' => 'id', 'required' => false)),
      'banished_until'  => new sfValidatorDateTime(array('required' => false)),
      'banished_for_id' => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('banishment[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Banishment';
  }


}
