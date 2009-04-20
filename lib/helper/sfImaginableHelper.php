<?php use_helper('Javascript');


function swf_upload_javascript($upload_url, $post_params = array(), $options = array()) 
{
  $output = '';      
  $request = sfContext::getInstance()->getResponse();
  
  //SWF Upload
  $request->addJavascript(sfConfig::get('sf_imaginable_js_dir').'/swfupload.js');
  $request->addJavascript(sfConfig::get('sf_imaginable_js_dir').'/swfupload.swfobject.js');
  $request->addJavascript(sfConfig::get('sf_imaginable_js_dir').'/swfupload.queue.js');
  $request->addJavascript(sfConfig::get('sf_imaginable_js_dir').'/fileprogress.js');
  $request->addJavascript(sfConfig::get('sf_imaginable_js_dir').'/handlers.js');

  //Prototype
  $request->addJavascript( sfConfig::get('sf_prototype_web_dir')  . '/js/prototype');
  //CSS goodness
  $request->addStylesheet( sfConfig::get('sf_imaginable_css_dir') . '/default.css' );
  
  $post_params_out = _swf_upload_options_for_javascript($post_params);
  
  if (!isset($options["file_size_limit"])) {
  	$options["file_size_limit"] = sfConfig::get('app_sf_imaginable_file_size_limit', '3MB');
  } 

  $output .= 
"var swfu;

	SWFUpload.onload = function () {
		var settings = {
      // Main settings:
			flash_url : '".  sfConfig::get('sf_imaginable_swf_dir') . '/swfupload.swf'  ."',
			upload_url : '".url_for($upload_url, true)."',
			post_params: ".$post_params_out.",
			file_size_limit : '".$options['file_size_limit']."',
      file_types : '*.jpg;*.jpeg;*.gif;*.png;*.bmp', 
      file_types_description: 'Image Files', 
			custom_settings : {
				progressTarget : 'fsUploadProgress',
				cancelButtonId : 'btnCancel'
			},
			debug: false,
	
      //Button Settings:
      button_image_url : '".sfConfig::get('sf_imaginable_images_dir')."/XPButtonUploadText_61x22.png',  // Relative to the SWF file
      button_placeholder_id : 'spanButtonPlaceholder',
      button_width: 61,
      button_height: 22,
      
      
      // The event handler functions are defined in handlers.js
      swfupload_loaded_handler : swfUploadLoaded,
      file_queued_handler : fileQueued,
      file_queue_error_handler : fileQueueError,
      file_dialog_complete_handler : fileDialogComplete,
      upload_start_handler : uploadStart,
      upload_progress_handler : uploadProgress,
      upload_error_handler : uploadError,
      upload_success_handler : uploadSuccess,
      upload_complete_handler : uploadComplete,
      queue_complete_handler : queueComplete,  // Queue plugin event


      // SWFObject settings
      minimum_flash_version : '9.0.28',
      swfupload_pre_load_handler : swfUploadPreLoad,
      swfupload_load_failed_handler : swfUploadLoadFailed

		};
	
		swfu = new SWFUpload(settings);
}";
  
  return javascript_tag($output);
}


function _swf_upload_options_for_javascript($options)
{
  $ret = array();
  foreach ($options as $key => $value)
  {
    $ret[] = "'$key':'$value'";
  }
  sort($ret);                  
   
  return '{'.join(", ", $ret).'}';
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