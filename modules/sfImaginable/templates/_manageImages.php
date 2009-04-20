<?php use_helper('sfImaginable', 'Javascript', 'I18N'); ?>


<?php echo swf_upload_javascript("sfImaginable/ajaxUpload",array(
  'object_class'=>$object_class = get_class($object),
  'object_id'=>$object_id = $object->getId()
));
echo javascript_tag("
  var object_class = '$object_class';
  var object_id    = $object_id;
");
?>


<div id="imaginable-content">
  <h1> <?php echo __('Multi-Image Upload',null,'sfImaginable')?> </h1>
  
  <div id="divSWFUploadUI">
    <div class="fieldset flash" id="fsUploadProgress">
      <span class="legend"><?php echo __('Image Upload Queue',null,'sfImaginable')?></span>
    </div>
    <div id="divStatus">0 Files Uploaded</div>
    <div>
      <span id="spanButtonPlaceholder"></span>
      <input id="btnCancel" type="button" value="<?php echo __('Cancel All Uploads',null,'sfImaginable')?>" 
        onclick="swfu.cancelQueue();" disabled="disabled" style="margin-left: 2px; font-size: 8pt; height: 22px;" />
    </div>
    <br />
    <br />
  
    <div id="ajax-image-list">
      <?php include_partial('sfImaginable/list_images',array('object'=>$object)) ?>      
    </div>
  </div> <!-- end divSWFUploadUI -->
  
  
  <noscript style="background-color: #FFFF66; border-top: solid 4px #FF9966; border-bottom: solid 4px #FF9966; margin: 10px 25px; padding: 10px 15px;">
    <?php echo __('We are sorry.  SWFUpload could not load.  You must have JavaScript enabled to enjoy SWFUpload.',null,'sfImaginable') ?> 
  </noscript>

  <div id="divLoadingContent" class="content" style="background-color: #FFFF66; border-top: solid 4px #FF9966; border-bottom: solid 4px #FF9966; margin: 10px 25px; padding: 10px 15px; display: none;">
    <?php echo __('SWFUpload is loading. Please wait a moment...',null,'sfImaginable') ?>     
  </div>

  <div id="divLongLoading" class="content" style="background-color: #FFFF66; border-top: solid 4px #FF9966; border-bottom: solid 4px #FF9966; margin: 10px 25px; padding: 10px 15px; display: none;">
    <?php echo __('SWFUpload is taking a long time to load or the load has failed.  Please make sure JavaScript is enabled and that a working version of the Adobe Flash Player is installed.',null,'sfImaginable') ?>  
  </div>

  <div id="divAlternateContent" class="content" style="background-color: #FFFF66; border-top: solid 4px #FF9966; border-bottom: solid 4px #FF9966; margin: 10px 25px; padding: 10px 15px; display: none;">
    <?php echo __('We are sorry.  SWFUpload could not load.  You may need to install or upgrade Flash Player version 9+.
    Visit the <em><a href="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash">Adobe website</a></em> to get the Flash Player.',null,'sfImaginable') ?>
  </div>

</div>


<!--
<?php echo link_to_remote('ajax_link',array(
  'url'=>'sfImaginable/ajaxList',
  'update'=>'ajax-image-list',
  'with'=>"'object_class=".get_class($object)."&object_id=".$object->getId()."'",
  //'complete'=>visual_effect('Highlight', 'ajax-image-list'),
  'script'=>true,
));?>
-->
