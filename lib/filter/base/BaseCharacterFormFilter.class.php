<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * Character filter form base class.
 *
 * @package    tibiahu
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormFilterGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BaseCharacterFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'        => new sfWidgetFormFilterInput(),
      'level'       => new sfWidgetFormFilterInput(),
      'last_seen'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'guild_id'    => new sfWidgetFormPropelChoice(array('model' => 'Guild', 'add_empty' => true)),
      'created_at'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'updated_at'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'vocation_id' => new sfWidgetFormFilterInput(),
      'server_id'   => new sfWidgetFormPropelChoice(array('model' => 'Server', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'name'        => new sfValidatorPass(array('required' => false)),
      'level'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'last_seen'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'guild_id'    => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Guild', 'column' => 'id')),
      'created_at'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'updated_at'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'vocation_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'server_id'   => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Server', 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('character_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Character';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'name'        => 'Text',
      'level'       => 'Number',
      'slug'        => 'Text',
      'last_seen'   => 'Date',
      'guild_id'    => 'ForeignKey',
      'created_at'  => 'Date',
      'updated_at'  => 'Date',
      'vocation_id' => 'Number',
      'server_id'   => 'ForeignKey',
    );
  }
}
