<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * Guild filter form base class.
 *
 * @package    tibiahu
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormFilterGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BaseGuildFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'slug'       => new sfWidgetFormFilterInput(),
      'updated_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'members'    => new sfWidgetFormFilterInput(),
      'server_id'  => new sfWidgetFormPropelChoice(array('model' => 'Server', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'slug'       => new sfValidatorPass(array('required' => false)),
      'updated_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'members'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'server_id'  => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Server', 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('guild_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Guild';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'name'       => 'Text',
      'slug'       => 'Text',
      'updated_at' => 'Date',
      'members'    => 'Number',
      'server_id'  => 'ForeignKey',
    );
  }
}
