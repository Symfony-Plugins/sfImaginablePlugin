<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * sfImaginable filter form base class.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage filter
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfPropelFormFilterGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BasesfImaginableFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'position'     => new sfWidgetFormFilterInput(),
      'object_class' => new sfWidgetFormFilterInput(),
      'object_id'    => new sfWidgetFormFilterInput(),
      'file_name'    => new sfWidgetFormFilterInput(),
      'caption'      => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'position'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'object_class' => new sfValidatorPass(array('required' => false)),
      'object_id'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'file_name'    => new sfValidatorPass(array('required' => false)),
      'caption'      => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('sf_imaginable_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'sfImaginable';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'position'     => 'Number',
      'object_class' => 'Text',
      'object_id'    => 'Number',
      'file_name'    => 'Text',
      'caption'      => 'Text',
    );
  }
}
