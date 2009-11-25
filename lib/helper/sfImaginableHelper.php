<?php 


function uploadify_javascript($upload_url, $post_params = array(), $options = array()) 
{
  $request = sfContext::getInstance()->getResponse();

  //jQuery inclusion (only if necessary, cause if we include it 2 times jQuery stops working)
  $javascripts = array_keys($request->getJavascripts());
  foreach ($javascripts as $i => $js)  if (false !== stripos($js, 'jquery-1.')) break; 
  if (++$i == count($javascripts)) $request->addJavascript( sfConfig::get('jquery_web_dir').'/'.sfConfig::get('jquery_core'),'first' );
  
  $request->addJavascript( sfConfig::get('sf_imaginable_js_dir').'/'.sfConfig::get('sf_imaginable_jquery_ui') );
  $request->addJavascript( sfConfig::get('sf_imaginable_js_dir').'/'.sfConfig::get('sf_imaginable_uploadify_js') );
  $request->addJavascript( sfConfig::get('sf_imaginable_js_dir').'/'.sfConfig::get('sf_imaginable_handlers_js') );
  
  //CSS goodness
  $request->addStylesheet( sfConfig::get('sf_imaginable_css_dir') . '/default.css' );
  
  $post_params_out = _parse_array_to_name_value_pair_for_js($post_params);
  $file_size_limit = _parse_file_size_limit_to_bytes(isset($options["file_size_limit"]) ? $options["file_size_limit"] : '');

  
  $output = 
'
jQuery(document).ready(function() {


   jQuery("#imaginableFileUpload").fileUpload({
      uploader:         "'.sfConfig::get('sf_imaginable_swf_dir') .'/uploadify.uploader.swf",
      script:           "'.url_for($upload_url, false).'",
      buttonImg:        "'.sfConfig::get('sf_imaginable_images_dir').'/XPButtonUploadText_61x22.png",
      cancelImg:        "'.sfConfig::get('sf_imaginable_images_dir').'/cancel.png",
      scriptData:       '.$post_params_out.',
      sizeLimit:        "'.$file_size_limit.'",
      fileExt:          "*.jpg;*.jpeg;*.gif;*.png;*.bmp", 
      fileDesc:         "Image Files",       
      multi:            true,
      simUploadLimit:   3,
      width:            61,
      height:           22, // the actual image is 61 x 88, with 4 states -> normal, highligh, pressed, disabled
      rollover:         true,
      auto:             true,
      onComplete:       function(event, queueID, fileObj, response, data){
                          
                          return true;
                        },
      onError:          function(event, queueID, fileObj, errorObj)
                        {
                          jQuery("#" + queueID).fadeOut(750).remove();
                          return true;
                        },
      onProgress:       function(event, queueID, fileObj){
                          jQuery("#btnCancel").removeAttr("disabled");   
                        },
      onAllComplete:    function(event, data){
                          ajaxUpdateImageList();
                          jQuery("#divStatus").html(data.filesUploaded + " Files Uploaded");
                          jQuert("#btnCancel").attr("disabled","disabled");
                        } 
   });
   
});      


  function ajaxUpdateImageList(){
    jQuery.ajax({
      url:        "'.url_for('sfImaginable/ajaxList').'",
      type:       "GET",
      dataType:   "html",
      data:       '.$post_params_out.',
      success:    function(data, textStatus){jQuery("#ajax-image-list").html(data);}
    });
  };

 ';
  
  return _enclose_in_sript_tag($output);
}


function _enclose_in_sript_tag($output) { return '<script type="text/javascript">'."\n//"."<![CDATA[\n$output\n//]]>"."\n".'</script>'; }


function _admin_imaginable_link_to_remove(sfImaginable $image, $element_id = null, $hide_effect = 'blind', $hide_effect_options = array('speed'=>'300'))
{
  $output = sprintf(
<<<EOF
    <a href="#" onclick="if (confirm('%s')) { jQuery.ajax({url:'%s',type:'POST',dataType:'html',beforeSend:function(){jQuery('%s').effect('%s',%s);},complete:function(){ajaxUpdateImageList();}}); }; return false;">%s</a>
EOF
  ,sfContext::getInstance()->getI18N()->__('Are you sure you want to delete this file?',null,'sfImaginable'),
  url_for('sfImaginable/removeImage?id='.$image->getId()),
  $element_id ? $element_id : '#item_'.$image->getId(),
  strtolower($hide_effect),
  _parse_array_to_name_value_pair_for_js($hide_effect_options),
  sfContext::getInstance()->getI18N()->__('DELETE',null,'sfImaginable')
  );
  
  return ($output);
}


function _parse_array_to_name_value_pair_for_js($options)
{
  $ret = array();
  foreach ($options as $key => $value)
  {
    $ret[] = "'$key':'$value'";
  }
  sort($ret);                  
   
  return '{'.join(", ", $ret).'}';
}


function _parse_file_size_limit_to_bytes($file_size_limit)
{
  if (false === stripos($file_size_limit, 'mb') && false === stripos($file_size_limit, 'kb')) return is_numeric($file_size_limit) ? $file_size_limit : _parse_file_size_limit_to_bytes(sfConfig::get('app_sf_imaginable_file_size_limit', '3MB'));

  if (false !== $pos = stripos($file_size_limit, 'mb'))
  {
    $file_size_limit = (int) (substr($file_size_limit, 0, $pos));
    $file_size_limit = $file_size_limit * 1024 * 1024;
  }
  if (false !== $pos = stripos($file_size_limit, 'kb'))
  {
    $file_size_limit = (int) (substr($file_size_limit, 0, $pos));
    $file_size_limit = $file_size_limit * 1024;
  }  
  
  return (string) $file_size_limit;
}


function imaginable_tag($source, $options = null)
{
  if (!$source) return NULL;
  $options = is_array($options) ? $options : sfToolkit::stringToArray($options);
  
  $ret = null;
  if (isset($options['thumbnail']))
  {  
    $thumbnailDir = sfConfig::get('app_sf_imaginable_plugin_thumbnail_dir', 'thumbnail');

    if ($options['thumbnail'] == 'small')
      $source = $thumbnailDir . '/' . 'small_' . $source;
    if ($options['thumbnail'] == 'large')
      $source = $thumbnailDir . '/' . 'large_' . $source;
    if ($options['thumbnail'] == 'custom')
    {
      if (isset($options['thumb_width']) && isset($options['thumb_height']))
      {
        $source = '/'.sfConfig::get('app_sf_imaginable_plugin_upload_dir','uploads').'/'.$source;
        $width = $options['thumb_width'];
        $height = $options['thumb_height'];
        unset ($options['thumb_width'], $options['thumb_height']);
        unset ($options['thumbnail']); 
        if (sfConfig::get('app_sf_imaginable_thumbnail_crop', false))
          $ret = auto_crop_thumbnail_tag($source, $width, $height, $options); 
        else
          $ret = thumbnail_tag ($source, $width, $height, $options);
      }
    }
    unset ($options['thumbnail']);
  }
  
  $source = '/'.sfConfig::get('app_sf_imaginable_plugin_upload_dir','uploads').'/'.$source;  
  return $ret ? $ret : image_tag($source, $options);
}
      
                       
function light_imaginable_tag($source, $options)
{
  if (!$source) return NULL;
  
  $request = sfContext::getInstance()->getResponse();         
  
  $ret = '<a rel="lightbox['.$options['collection'].']" href="/'.sfConfig::get('app_sf_imaginable_plugin_upload_dir','uploads').'/'.$source.'">';
  unset ($options['collection']);
  $ret .= imaginable_tag($source, $options);
  $ret .= '</a>';
  return $ret;
}


function high_imaginable_tag($source, $options)
{
  if (!$source) return NULL;
  $options = is_array($options) ? $options : sfToolkit::stringToArray($options);
  
  $group = '';
  if (isset($options['group']))
  {
    $group = ",{ slideshowGroup: '".$options['group']."' }";
    unset ($options['group']);
  }
  
  $ret  = '<a onclick="return hs.expand(this'.$group.')" class="highslide" href="/'.sfConfig::get('app_sf_imaginable_plugin_upload_dir','uploads').'/'.$source.'">';
  $ret .= imaginable_tag($source, $options);
  $ret .= '</a>';
  return $ret;
}


function auto_crop_thumbnail_tag($source, $width, $height, $options)
{
  $img_src = auto_crop_thumbnail_path($source, $width, $height);
  return image_tag($img_src, $options);
}


function auto_crop_thumbnail_path($source, $width, $height)
{
  $thumbnails_dir = 'generated_thumbnails';
  
  $width = intval($width);
  $height = intval($height);
  
  if (substr($source, 0, 1) == '/') {
    $realpath = sfConfig::get('sf_web_dir') . $source;
  } else {
    $realpath = sfConfig::get('sf_web_dir') . '/images/' . $source;
  }
  
  $real_dir = dirname($realpath);
  $thumb_dir = '/' . $thumbnails_dir . substr($real_dir, strlen(sfConfig::get('sf_web_dir')));
  $thumb_name = 'auto_crop_'.preg_replace('/^(.*?)(\..+)?$/', '$1_' . $width . 'x' . $height . '$2', basename($source));
  
  $img_from = $realpath;
  $thumb = $thumb_dir . '/' . $thumb_name;
  $img_to = sfConfig::get('sf_web_dir') . $thumb;
  
  if (!is_dir(dirname($img_to))) {
    if (!mkdir(dirname($img_to), 0755, true)) {
      throw new Exception('Cannot create directory for thumbnail : ' . $img_to);
    }
  }
  
  if (!is_file($img_to) || filemtime($img_from) > filemtime($img_to)) {
    $thumbnail = new sfImage($img_from);
    $thumbnail->setQuality(90);
    $thumbnail = imaginableTools::autoCropImage($thumbnail, $width, $height);
    $thumbnail->saveAs($img_to,'image/jpeg');
  }
  
  return image_path($thumb);
}