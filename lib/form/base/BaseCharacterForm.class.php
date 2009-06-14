<?php

/**
 * Character form base class.
 *
 * @package    tibiahu
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BaseCharacterForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'name'        => new sfWidgetFormInput(),
      'level'       => new sfWidgetFormInput(),
      'slug'        => new sfWidgetFormInputHidden(),
      'last_seen'   => new sfWidgetFormDateTime(),
      'guild_id'    => new sfWidgetFormPropelChoice(array('model' => 'Guild', 'add_empty' => true)),
      'created_at'  => new sfWidgetFormDateTime(),
      'updated_at'  => new sfWidgetFormDateTime(),
      'vocation_id' => new sfWidgetFormInput(),
      'server_id'   => new sfWidgetFormPropelChoice(array('model' => 'Server', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorPropelChoice(array('model' => 'Character', 'column' => 'id', 'required' => false)),
      'name'        => new sfValidatorString(array('max_length' => 255)),
      'level'       => new sfValidatorInteger(),
      'slug'        => new sfValidatorPropelChoice(array('model' => 'Character', 'column' => 'slug', 'required' => false)),
      'last_seen'   => new sfValidatorDateTime(array('required' => false)),
      'guild_id'    => new sfValidatorPropelChoice(array('model' => 'Guild', 'column' => 'id', 'required' => false)),
      'created_at'  => new sfValidatorDateTime(array('required' => false)),
      'updated_at'  => new sfValidatorDateTime(array('required' => false)),
      'vocation_id' => new sfValidatorInteger(array('required' => false)),
      'server_id'   => new sfValidatorPropelChoice(array('model' => 'Server', 'column' => 'id', 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorPropelUnique(array('model' => 'Character', 'column' => array('slug')))
    );

    $this->widgetSchema->setNameFormat('character[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Character';
  }


}
