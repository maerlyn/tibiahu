<?php

/**
 * House form base class.
 *
 * @package    tibiahu
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BaseHouseForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'   => new sfWidgetFormInput(),
      'name' => new sfWidgetFormInput(),
      'slug' => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'id'   => new sfValidatorInteger(array('required' => false)),
      'name' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'slug' => new sfValidatorPropelChoice(array('model' => 'House', 'column' => 'slug', 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorPropelUnique(array('model' => 'House', 'column' => array('id')))
    );

    $this->widgetSchema->setNameFormat('house[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'House';
  }


}
