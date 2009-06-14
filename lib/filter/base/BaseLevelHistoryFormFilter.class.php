<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * LevelHistory filter form base class.
 *
 * @package    tibiahu
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormFilterGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BaseLevelHistoryFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'level'        => new sfWidgetFormFilterInput(),
      'created_at'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'reason_id'    => new sfWidgetFormPropelChoice(array('model' => 'Creature', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'level'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'reason_id'    => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Creature', 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('level_history_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'LevelHistory';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'character_id' => 'ForeignKey',
      'level'        => 'Number',
      'created_at'   => 'Date',
      'reason_id'    => 'ForeignKey',
    );
  }
}
