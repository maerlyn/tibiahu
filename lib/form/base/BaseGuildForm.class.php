<?php

/**
 * Guild form base class.
 *
 * @package    tibiahu
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BaseGuildForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'name'       => new sfWidgetFormInputHidden(),
      'slug'       => new sfWidgetFormInput(),
      'updated_at' => new sfWidgetFormDateTime(),
      'members'    => new sfWidgetFormInput(),
      'server_id'  => new sfWidgetFormPropelChoice(array('model' => 'Server', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorPropelChoice(array('model' => 'Guild', 'column' => 'id', 'required' => false)),
      'name'       => new sfValidatorPropelChoice(array('model' => 'Guild', 'column' => 'name', 'required' => false)),
      'slug'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'updated_at' => new sfValidatorDateTime(array('required' => false)),
      'members'    => new sfValidatorInteger(array('required' => false)),
      'server_id'  => new sfValidatorPropelChoice(array('model' => 'Server', 'column' => 'id', 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorPropelUnique(array('model' => 'Guild', 'column' => array('slug')))
    );

    $this->widgetSchema->setNameFormat('guild[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Guild';
  }


}
