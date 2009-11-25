<?php

/**
 * sfImaginable form base class.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BasesfImaginableForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'position'     => new sfWidgetFormInput(),
      'object_class' => new sfWidgetFormInput(),
      'object_id'    => new sfWidgetFormInput(),
      'file_name'    => new sfWidgetFormInput(),
      'caption'      => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorPropelChoice(array('model' => 'sfImaginable', 'column' => 'id', 'required' => false)),
      'position'     => new sfValidatorInteger(),
      'object_class' => new sfValidatorString(array('max_length' => 100)),
      'object_id'    => new sfValidatorInteger(),
      'file_name'    => new sfValidatorString(array('max_length' => 255)),
      'caption'      => new sfValidatorString(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('sf_imaginable[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'sfImaginable';
  }


}
