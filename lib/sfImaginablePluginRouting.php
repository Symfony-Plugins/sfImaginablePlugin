<?php
class sfImaginablePluginRouting 
{
  static public function listenToRoutingLoadConfigurationEvent(sfEvent $event) 
  { 
    $routing = $event->getSubject(); // add plug-in routing rules on top of the existing ones 
    $routing->prependRoute('sfImaginable', new sfRoute('/sfImaginable/:action', array('module' => 'sfImaginable'))); 
  }  
}
