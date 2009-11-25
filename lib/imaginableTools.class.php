<?php
class imaginableTools
{
  public static function selectParentObjectByClassAndId( $object_class, $object_id )
  {
    $objectPeerClass = constant($object_class.'::PEER'); // $object_class.'Peer';             
    $object = call_user_func(array($objectPeerClass, 'retrieveByPk'), $object_id);

    return $object;
  }
  
  public static function randomStringGen($length = 8) 
  {
    if($length < 1) 
      return '';    
      
    $keychars = "abcdefghijklmnopqrstuvwxyz";  
    $max = strlen($keychars)-1;
    $ret = '';
    for ($i=0; $i<$length; $i++)     
      $ret .= substr($keychars, rand(0, $max), 1);
    
    return $ret;
  }  
  
  public static function getUploadDir()
  {
    return sfConfig::get('sf_web_dir') . DIRECTORY_SEPARATOR . 
      sfConfig::get('app_sf_imaginable_plugin_upload_dir','uploads');
  }
  
  public static function getThumbnailDir()
  {
    return imaginableTools::getUploadDir() . DIRECTORY_SEPARATOR . 
      sfConfig::get('app_sf_imaginable_plugin_thumbnail_dir','thumbnail'); 
  }
  
  public static function stripFilename($text)
  {
     $text = strtolower($text);

     // strip all non word chars, except for .jpg / .gif / . png
     $text = preg_replace('/\W?=\.(jpg|jpeg|gif|png)/', ' ', $text);

     // replace all white space sections with an underscore
     $text = preg_replace('/\ +/', '_', $text);

     // trim underscores
     $text = preg_replace('/\_$/', '', $text);
     $text = preg_replace('/^\_/', '', $text);

     return $text;
  }  
  
  public static function autoCropImage(sfImage $image, $width, $height)
  {
    $org_width  = $image->getWidth();
    $org_height = $image->getHeight();
    $org_prop = round($org_width/$org_height, 2);
    $prop     = round($width / $height, 2);
    
    $scale_w = $width / $org_width;
    $scale_h = $height / $org_height;
    
    $ratio_w = $org_width / $width;
    $ratio_h = $org_height / $height;
    
    if ($prop == $org_prop) {
      $image->resize($width, $height);
    } else if (($prop > $org_prop)) {
      $image->resize($width, $new_height = (int)round(($org_height / $org_width)*$width));
      $image->crop(0, (int)round(($new_height - $height) / 2), $width, $height);        
    } else if (($prop < $org_prop)) {
      $image->resize($new_width = (int)round(($org_width / $org_height)*$height), $height);
      $image->crop((int)round(($new_width-$width) / 2), 0, $width,$height);     
    }
    
    return $image;
  }
  
  
  
  
}

  