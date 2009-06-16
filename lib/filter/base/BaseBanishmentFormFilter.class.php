<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * Banishment filter form base class.
 *
 * @package    tibiahu
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormFilterGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BaseBanishmentFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'character_id'    => new sfWidgetFormPropelChoice(array('model' => 'Character', 'add_empty' => true)),
      'banished_until'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'banished_for_id' => new sfWidgetFormFilterInput(),
      'banished_at'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'level'           => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'character_id'    => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Character', 'column' => 'id')),
      'banished_until'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'banished_for_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'banished_at'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'level'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('banishment_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Banishment';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'character_id'    => 'ForeignKey',
      'banished_until'  => 'Date',
      'banished_for_id' => 'Number',
      'banished_at'     => 'Date',
      'level'           => 'Number',
    );
  }
}
