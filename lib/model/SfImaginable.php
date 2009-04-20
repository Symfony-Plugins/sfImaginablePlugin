<?php

/**
 * Subclass for representing a row from the 'sf_imaginable' table.
 *
 * 
 *
 * @package plugins.sfImaginablePlugin.lib.model
 */ 
class sfImaginable extends BasesfImaginable
{
  public $position;
  public $file_name;
  public $object_class;
  public $object_id;

  
  public function __construct()
  {
    parent::__construct();
    //Allow the object to be used as an array
    $this->position = $this->getPosition();
    $this->file_name = $this->getFileName();
    $this->object_class = $this->getObjectClass();
    $this->object_id = $this->getObjectId();
  }
  
  public function __toString()
  {
    return $this->getFileName();
  }
  
  public function removeImage($con = null)
  {
    $con = $con ? $con : Propel::getConnection(sfImaginablePeer::DATABASE_NAME);     
    $con = Propel::getConnection(sfImaginablePeer::DATABASE_NAME);   
    $query = sprintf('UPDATE %s SET %s = %s - 1 WHERE %s > ? AND %s = "%s" AND %s = "%s"',
       sfImaginablePeer::TABLE_NAME,
       sfImaginablePeer::POSITION,
       sfImaginablePeer::POSITION,
       sfImaginablePeer::POSITION,
       sfImaginablePeer::OBJECT_CLASS,
       $this->getObjectClass(),       
       sfImaginablePeer::OBJECT_ID,
       $this->getObjectId()       
    );  
   
    if(method_exists('SfImaginablePeer', 'doSelectRS'))
    {
      $propelVersion = '1.2';
      $stmt = $con->prepareStatement($query);
      $stmt->setInt(1, $this->getPosition());
      $stmt->executeQuery();    
    } else {
      $propelVersion = '1.3';
      $stmt = $con->prepare($query);
      $stmt->bindValue(1, $this->getPosition());
      $stmt->execute();
    } 

    $this->delete(); 
  }

  public function delete(PropelPDO $con = null)
  {
    parent::delete($con);
    
    $uploadDir = imaginableTools::getUploadDir();    
    $thumbnailDir = imaginableTools::getThumbnailDir();
                    
    unlink($uploadDir    . DIRECTORY_SEPARATOR .            $this->getFileName());
    unlink($thumbnailDir . DIRECTORY_SEPARATOR . 'large_' . $this->getFileName());
    unlink($thumbnailDir . DIRECTORY_SEPARATOR . 'small_' . $this->getFileName());    
  }  
  
  public function getParentObject()
  {
    $objectPeerClass = $this->getObjectClass().'Peer';             
    $object = call_user_func(array($objectPeerClass, 'retrieveByPk'), $this->getObjectId());
    return $object;    
  }
  
}
