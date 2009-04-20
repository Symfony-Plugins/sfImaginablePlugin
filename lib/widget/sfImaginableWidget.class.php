<?php
class sfImaginableWidget extends sfWidgetForm
{
  protected function configure($options = array(), $attributes = array())   
  {
    $this->addRequiredOption('object');
  }

  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    sfProjectConfiguration::getActive()->loadHelpers(array('Partial'));
    
    if ($this->getOption('object')->isNew()) {
      return get_component('sfImaginable', 'noImagesForObject', array('object'=>$this->getOption('object')));
    } else {
      return get_component('sfImaginable', 'manageImages', array('object'=>$this->getOption('object')));
    }
    
    
  }
}
