<div class="fieldset">
  <span class="legend"><?php echo __('Images',null,'sfImaginable')?></span>
  
<?php use_helper('Javascript','I18N','sfImaginable');
  $response = sfContext::getInstance()->getResponse();
  $response->addJavascript(sfConfig::get('sf_prototype_web_dir').'/js/prototype');
  $response->addJavascript(sfConfig::get('sf_prototype_web_dir').'/js/builder');
  $response->addJavascript(sfConfig::get('sf_prototype_web_dir').'/js/effects');
  $response->addJavascript(sfConfig::get('sf_prototype_web_dir').'/js/dragdrop');
  $images = $object->getImages();
  if ($images): //Start if section for when there are images attatched to the object
?>

  <div id="sortable_list">    
  <?php foreach ($images as $image): ?>

    <div class="sfImaginable_list_record" id="item_<?=$image->getId()?>" style="width: 100%; clear: both;"> 
      <?php echo imaginable_tag($image, array('thumbnail'=>'small','style'=>'float: left;')); ?>
      <p style="margin-left: 90px; margin-bottom: 10px">
        <a href="<?='/uploads/'.$image?>"> <?=__('Full Size',null,'sfImaginable')?> </a> <br />
        <?=__('Image ID',null,'sfImaginable')?>: <?=$image->getId()?><br />                                             
        <?=__('Image position',null,'sfImaginable')?>: <?=$image->getPosition()?><br />
        <?=__('Image filename',null,'sfImaginable')?>: <?=$image?><br />   
        <?php echo link_to_remote(__('DELETE',null,'sfImaginable'),array(
              'url'=>'sfImaginable/removeImage?id='.$image->getId(),
              'loading'=>visual_effect('BlindUp','item_'.$image->getId(),array('duration'=>'0.3')),
              'complete'=>'ajaxUpdateImageList()',
              'confirm'=>__('Are you sure you want to delete this file?',null,'sfImaginable'),
        )); ?>                                         
      </p>
    </div>   

  <?php endforeach; ?>
  </div>

  <div class="clearer">&nbsp;</div>

  <?php echo sortable_element('sortable_list', array(
    'url'=>'sfImaginable/reorder',
    'tag'=>'div',
    'with'=>"Sortable.serialize('sortable_list') + '&object_class=".get_class($object)."&object_id=".$object->getId()."'",
    //'loading'=>'Element.show("indicator")',
    //'complete'=>visual_effect('BlindUp','indicator')
    'complete'=>'ajaxUpdateImageList()',
  )); ?>

  <div id="indicator" style="display:none; margin: auto; vertical-align:middle;">
    <?php echo image_tag('/sfImaginablePlugin/images/ajax-loader.gif',array('alt'=>'Loading!','width'=>'22','height'=>'22'))?>
  </div>

<?php else: //Inform the user in case no images are attached to the object?>
  <h3> <?php echo __('No images are avaliable for this object',null,'sfImaginable') ?> </h3>
<?php endif; //end if section?>

</div> <!-- end fieldset -->

<div class="clearer">&nbsp;</div>   

