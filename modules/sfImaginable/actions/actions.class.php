<?php

class sfImaginableActions extends sfActions 
{

  public function executeFormUpload()
  {
    
  }    
  
  public function executeAjaxUpload()
  {

    if ($this->getRequest()->getMethod() != sfRequest::POST)
    {
      return sfView::NONE;
    }
    
    $upload_file = 'Filedata';
    $object_class = $this->getRequestParameter('object_class');
    $object_id = $this->getRequestParameter('object_id');

    $uploadDir = imaginableTools::getUploadDir();
    $thumbnailDir = imaginableTools::getThumbnailDir();            
    
    $fileName = imaginableTools::randomStringGen() . '_' . imaginableTools::stripFilename($this->getRequest()->getFileName($upload_file));
    $this->getRequest()->moveFile($upload_file, $uploadDir . DIRECTORY_SEPARATOR . $fileName);                     
 
    
    if (!is_dir($uploadDir)) 
    {
      mkdir($uploadDir, 755);  
    }
    if (!is_dir($thumbnailDir)) 
    {
      mkdir($thumbnailDir, 755);
    }
                                 
    $object = imaginableTools::selectParentObjectByClassAndId( $object_class, $object_id );
    $object -> addImage( $fileName );
    
    if (sfConfig::get('app_sf_imaginable_save_object_after_image_upload',false)) 
    {
      $object -> save();
    }                                            
 
    if (sfConfig::get('app_sf_imaginable_master_resize', false))
    {
      $image = new sfImage($uploadDir . DIRECTORY_SEPARATOR . $fileName);
      $image->setQuality(95);
      $image->thumbnail(sfConfig::get('app_sf_imaginable_master_width', 800), sfConfig::get('app_sf_imaginable_master_height', 600));
      $image->saveAs($uploadDir . DIRECTORY_SEPARATOR . $fileName, 'image/jpeg'); 
    }                                                                                      
 

    $small_thumbnail = new sfImage($uploadDir . DIRECTORY_SEPARATOR . $fileName);
    if (sfConfig::get('app_sf_imaginable_thumbnail_crop', false)) {
      $small_thumbnail->thumbnail(sfConfig::get('app_sf_imaginable_thumbnail_small_width',84),  sfConfig::get('app_sf_imaginable_thumbnail_small_height',84), 'center');
    } else {
      $small_thumbnail->resize(sfConfig::get('app_sf_imaginable_thumbnail_small_width',84),  sfConfig::get('app_sf_imaginable_thumbnail_small_height',84));
    }
    $small_thumbnail->setQuality(95);       
    $small_thumbnail->saveAs($thumbnailDir . DIRECTORY_SEPARATOR . 'small_' . $fileName, 'image/jpeg');

       
    $thumbnail = new sfImage($uploadDir . DIRECTORY_SEPARATOR . $fileName);
    if (sfConfig::get('app_sf_imaginable_thumbnail_crop', false)) {
      $thumbnail->thumbnail(sfConfig::get('app_sf_imaginable_thumbnail_large_width',194),  sfConfig::get('app_sf_imaginable_thumbnail_large_height',158), 'center');
    } else {
      $thumbnail->resize(sfConfig::get('app_sf_imaginable_thumbnail_small_width',84),  sfConfig::get('app_sf_imaginable_thumbnail_small_height',84));
    }    
    $thumbnail->setQuality(95);
    $thumbnail->saveAs($thumbnailDir . DIRECTORY_SEPARATOR . 'large_' . $fileName, 'image/jpeg');
    
    

  
    return sfView::NONE;
  }                 
  
  public function executeList() 
  {                     
    sfConfig::set('sf_web_debug', false);
    $request = sfContext::getInstance()->getResponse(); 
    $request->addJavascript(sfConfig::get('sf_prototype_web_dir').'/js/prototype');
    
    $object_class = $this->getRequestParameter('object_class'); 
    $object_id = $this->getRequestParameter('object_id');

    $this->object = imaginableTools::selectParentObjectByClassAndId( $object_class, $object_id ); 
  }  
  
  public function executeAjaxList() 
  {                     
    //sfConfig::set('sf_web_debug', false);
    //$request = sfContext::getInstance()->getResponse(); 
    //$request->addJavascript(sfConfig::get('sf_prototype_web_dir').'/js/prototype');
    $isAjax = $this->getRequest()->isXmlHttpRequest();
    if ($isAjax) {
      $object_class = $this->getRequestParameter('object_class'); 
      $object_id = $this->getRequestParameter('object_id');
      $this->object = imaginableTools::selectParentObjectByClassAndId( $object_class, $object_id ); 
    } else {
      return sfView::NONE;
    }
  }
  
  public function executeReorder()
  {
    $order = $this->getRequestParameter('sortable_list');
    $object_class = $this->getRequestParameter('object_class');
    $object_id = $this->getRequestParameter('object_id');

    $object = imaginableTools::selectParentObjectByClassAndId( $object_class, $object_id ); 
    $object->reorderImages( $order );
    
    return sfView::NONE;
  }

  public function executeRemoveImage()
  {
    sfConfig::set('sf_web_debug', false);      
    $id = $this->getRequestParameter('id');
    $image = sfImaginablePeer::retrieveByPK( $id );
    $image->removeImage();
    
    return sfView::NONE;
  }
}