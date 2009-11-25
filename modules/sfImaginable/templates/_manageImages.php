<?php use_helper('sfImaginable', 'Javascript', 'I18N'); ?>

<div id="imaginable-content"> 
<?php echo uploadify_javascript("sfImaginable/ajaxUpload",array(
  'object_class'=>$object_class = get_class($object),
  'object_id'=>$object_id = $object->getId()
));
echo javascript_tag("
  var object_class = '$object_class';
  var object_id    = $object_id;
");
?>



  <h1> <?php echo __('Multi-Image Upload',null,'sfImaginable')?> </h1>
  
  <div id="divSWFUploadUI">

    <div class="fieldset  flash" id="fsUploadProgress">

      <span class="legend"><?php echo __('Image Upload Queue',null,'sfImaginable')?></span>
      <br />
      <input style="padding-left: 30px;" type="file" id="imaginableFileUpload" name="imaginableFileUpload" />
      <br />

    </div>
    <p id="divStatus">0 Files Uploaded</p>
    <div>
      <div id="spanButtonPlaceholder"></div>
      <input id="btnCancel" type="button" value="<?php echo __('Cancel All Uploads',null,'sfImaginable')?>" 
        onclick="jQuery('#imaginableFileUpload').fileUploadClearQueue(); return false;" disabled="disabled" style="margin-left: 2px; font-size: 8pt; height: 22px;" />
      <br />
    </div>
    <br />
    <noscript class="loading-error">
      <?php echo __('We are sorry.  sfImaginable could not load.  You must have JavaScript enabled to enjoy sfImaginable.',null,'sfImaginable') ?> 
    </noscript>

  </div> <!-- end divSWFUploadUI -->

  <div id="ajax-image-list">
    <?php include_partial('sfImaginable/list_images',array('object'=>$object)) ?>
  </div>
  
  

  <div id="divLoadingContent" class="content loading-error">
    <?php echo __('SWFUpload is loading. Please wait a moment...',null,'sfImaginable') ?>     
  </div>

  <div id="divLongLoading" class="content loading-error">
    <?php echo __('SWFUpload is taking a long time to load or the load has failed.  Please make sure JavaScript is enabled and that a working version of the Adobe Flash Player is installed.',null,'sfImaginable') ?>  
  </div>

  <div id="divAlternateContent" class="content loading-error">
    <?php echo __('We are sorry.  SWFUpload could not load.  You may need to install or upgrade Flash Player version 9+.
    Visit the <em><a href="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash">Adobe website</a></em> to get the Flash Player.',null,'sfImaginable') ?>
  </div>

</div>


