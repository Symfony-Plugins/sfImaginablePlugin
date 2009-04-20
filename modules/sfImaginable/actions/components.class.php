<?php
class sfImaginableComponents extends sfComponents
{
  
  public function executeManageImages()
  {
    self::setMessageSource();
  }
  
  public function executeNoImagesForObject()
  {
    self::setMessageSource();
  }
  
  protected function setMessageSource()
  {
    $this->getContext()->getI18N()->setMessageSource(
      $this->getContext()->getConfiguration()->getI18NDirs('sfImaginable'),
      sfContext::getInstance()->getUser()->getCulture()
    );          
  }
  
}