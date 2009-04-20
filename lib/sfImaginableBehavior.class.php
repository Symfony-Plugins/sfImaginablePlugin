<?php 

class sfImaginableBehavior
{                         
  
  protected $images = null;
  protected $object_class = null;
  protected $object_id = null;
  
  public function postDelete(BaseObject $object)
  {
    $this->removeAllImages($object);
  }
  
  
  /**
    * Add a new image to the object
    *
    * @param BaseObject   this value is automatically passed when you call $object->addImage()
    * @param Mixed        string or array with the new images
    *
    * @return Boolean     True or Flase based on wether the function executed sucessfully
  **/  
  
  public function addImage(BaseObject $object, $filename)
  {
    if (!$filename) return false;
    if (is_array($filename))
    {
      foreach ($filename as $record)
      {
        $this->addImage($object, $record);
      }
    }
    
    $image = new sfImaginable();
    $image->setObjectClass( get_class($object) );
    $image->setObjectId( $object->getId() );
    $image->setFileName( $filename );
    $image->setPosition( $this->getMaxPosition($object) + 1 );
    $image->save();    
    return true;
  }
  
  
  /**
    * Returns the highest value for the position field of the image objects 
    * associated with the object passed as parameter
    *
    * @param BaseObject   This value is automatically passed when you call $object->getMaxPosition()
    * @param BaseObject   Propel connection object
    *
    * @return Boolean     True or Flase based on wether the function executed sucessfully
  **/   
  
  public function getMaxPosition(BaseObject $object, $con = null)
  {
    $con = $con ? $con : Propel::getConnection(sfImaginablePeer::DATABASE_NAME);

    $sql = sprintf('SELECT MAX(%s) AS max_position FROM %s WHERE %s = "%s" AND %s = "%s"',
       sfImaginablePeer::POSITION,
       sfImaginablePeer::TABLE_NAME,
       sfImaginablePeer::OBJECT_CLASS,
       get_class($object),
       sfImaginablePeer::OBJECT_ID,
       $object->getId()
    );

    if(method_exists('sfImaginablePeer', 'doSelectRS'))
    {
      $propelVersion = '1.2';
      $rs = $con->prepareStatement($sql)->executeQuery();
      $rs->next();                        
      $ret = $rs->getInt('max_position');
    } else {
      $propelVersion = '1.3';
      $stmt = $con->prepare($sql);
      $stmt->execute();
      $row = $stmt->fetch();
      $ret = $row['max_position'];      
    }   
    
    return $ret;
  }
  
  
  /**
    * Returns an array of sortable objects ordered by position
    *
    * @param BaseObject   This value is automatically passed when you call $object->getImages()
    * @param Integer      Return images starting from this position onwards     
    *
    * @return             Array list of Image objects or NULL
  **/
  
  public function getImages(BaseObject $object, $start_position = null )
  {
    $ret = $this->getOrCreateImages($object);
    if( $start_position )
    {
      $return = array();
      while( isset($ret[$start_position-1]) )
      {
        $return[] = $ret[$start_position-1];
        $start_position++;
      }
      
      return $return;
    }
    
    return $ret;
  }
  
  protected function getOrCreateImages(BaseObject $object)
  {
    if( get_class($object) != $this->object_class || $object->getId() != $this->object_id )
    { 
      $c = new Criteria();
      $c->add(sfImaginablePeer::OBJECT_CLASS, get_class($object));
      $c->addAnd(sfImaginablePeer::OBJECT_ID, $object->getId());
      $c->addAscendingOrderByColumn( sfImaginablePeer::POSITION );
      $this->images = sfImaginablePeer::doSelect($c);
      $this->object_class = get_class($object);
      $this->object_id = $object->getId();
    }
    
    return $this->images;
  }
    
    
  /**
    * Returns the first image associated with this object
    *
    * @param BaseObject   This value is automatically passed when you call $object->getImages()
    *
    * @return             Array list of Image objects or NULL
  **/
  
  public function getFirstImage(BaseObject $object, $full_link = null)
  {
    $ret = $this->getImage($object, 1, $full_link);
    return $ret ? $ret : NULL;
  }
  
  
  /**
    * Reorders the array of Image objects
    *
    * @param BaseObject   this value is automatically passed when you call $object->reorder()
    * @param Array        array with the new positions. Must be $position => $id
    * @param BaseObject   Propel connection object
    *
    * @return Boolean     True or Flase based on wether the function executed sucessfully
    * @throws Exception   If the $position is not int, or there is a database-related exception
  **/
    
  public function reorderImages(BaseObject $object, $order, $con = null)
  {                                                       
    if (!is_array($order))
    {
      $msg = sprintf('reorderImages() requires an array of positions and ids to work. Vriable given was of type : %s.', get_class($order));
      throw new Exception($msg);
      return false;
    }   
    $con = $con ? $con : Propel::getConnection(sfImaginablePeer::DATABASE_NAME); 
    if (method_exists('sfImaginablePeer', 'doSelectRS'))
    {
      $propelVersion = '1.2';
      $start = 'begin';
      $rollback = 'rollback';
    } else {
      $propelVersion = '1.3';
      $start = 'beginTransaction';
      $rollback = 'rollBack';
    }       
    $con->$start();
    try
    {
      foreach ($order as $position => $id)
      {
        $image = sfImaginablePeer::retrieveByPK($id);
        if ($image->getPosition() != $position + 1)
        {
          $image->setPosition( $position + 1 );
          $image->save();    
        }
      }
      $con->commit();
      return true; 
    }
    catch (Exception $e)
    {
      $con->$rollback();
      throw new Exception($e);
      return false;
    }
  }
  
  
  /**
    * Returns an Image object based on it's positon
    *
    * @param BaseObject   this value is automatically passed when you call $object->getImage()
    * @param Integer      Position of the image
    *
    * @return mixed       The image name or Flase based on wether the function executed sucessfully
    * @throws Exception   If the $position is not int
  **/  
  
  public function getImage(BaseObject $object, $position, $full_link = false)
  {
    if (!is_int($position))
    {
      $msg = sprintf('getImage() requires a position of the image array to work. Value given was of type : %s.', get_class($position));
      throw new Exception($msg);
      return false;
    } 
    $ret = $this->getOrCreateImages($object);
    if( !isset( $ret[$position-1] ) )
      return NULL;
    $ret = $ret[$position-1];
    if($full_link ) $ret = '/'.imaginableTools::getUploadDir().'/'.$ret->getFullName();
    return $ret;
  }
  
  
  /**
    * Remove all images associated with a given object
    *
    * @param BaseObject   this value is automatically passed when you call $object->removeAllImages()
    *
    * @return Boolean     True or Flase based on wether the function executed sucessfully
    * @throws Exception   If there is a database-related exception
  **/  
  
  public function removeAllImages(BaseObject $object)
  {
    try
    {
      $c = new Criteria();  
      $c->add(sfImaginablePeer::OBJECT_CLASS, get_class($object));
      $c->addAnd(sfImaginablePeer::OBJECT_ID, $object->getId());
      sfImaginablePeer::doDelete($c);
    }
    catch (Exception $e)
    {
      throw new Exception($e);
      return false;
    }
    return true;
  }
  
  
  /**
    * Remove an image associated with a given object based on it's position
    *
    * @param BaseObject   this value is automatically passed when you call $object->removeImage()
    * @param Integer      Position of the image to remove
    * @param BaseObject   Propel connection object
    *
    * @return Boolean     True or Flase based on wether the function executed sucessfully
    * @throws Exception   If the $position is not int, or there is not such record to delete
  **/  
  
  public function removeImageByPosition(BaseObject $object, $position, $con = null)
  {
    if(!is_int($position))
    {
      $msg = sprintf('removeImage() requires a position of the image array to work. Value given was of type : %s.', get_class($position));
      throw new Exception($msg);
      return false;
    }
    
    $c = new Criteria();
    $c->add(sfImaginablePeer::POSITION, $position);
    $c->addAnd(sfImaginablePeer::OBJECT_CLASS, get_class($object));
    $c->addAnd(sfImaginablePeer::OBJECT_ID, $object->getId());
    $image = sfImaginablePeer::doSelectOne($c);
    
    if(!$image) return false;
    $image->removeImage();
    return true;
  }
   
  
  /**
    * Returns number of images associated with the object
    *
    * @param BaseObject   this value is automatically passed when you call $object->getNbImages()
    * 
    * @return Integer     Number of images associated with the object
  **/
   
  public function getNbImages(BaseObject $object)
  {
    $c = new Criteria();
    $c->add(sfImaginablePeer::OBJECT_CLASS, get_class($object));
    $c->addAnd(sfImaginablePeer::OBJECT_ID, $object->getId());
    $ret = sfImaginablePeer::doCount($c);
    return $ret ? $ret : 'No images';
  }
 
}