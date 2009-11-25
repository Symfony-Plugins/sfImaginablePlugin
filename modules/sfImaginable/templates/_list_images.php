<div class="fieldset">
  <span class="legend"><?php echo __('Images',null,'sfImaginable')?></span>
  
<?php use_helper('Javascript','I18N','sfImaginable');
  
  $images = $object->getImages();
  if ($images): //Start if section for when there are images attatched to the object
?>

  <div id="sortable_list">    
  <?php foreach ($images as $image): ?>

    <div class="sfImaginable_list_record" id="item_<?php echo $image->getId()?>" > 
      <?php echo imaginable_tag($image, array('thumbnail'=>'small','style'=>'float: left;')); ?>
      <p>
        <a target="blank" href="<?php echo '/uploads/'.$image?>"> <?php echo __('Full Size',null,'sfImaginable')?> </a> <br />
        <?php echo __('Image ID',null,'sfImaginable')?>: <?php echo $image->getId()?><br />                                             
        <?php echo __('Image position',null,'sfImaginable')?>: <?php echo $image->getPosition()?><br />
        <?php echo __('Image filename',null,'sfImaginable')?>: <?php echo $image?><br />   
        <?php echo _admin_imaginable_link_to_remove($image); ?><br />   
        <?php echo __('Caption'); ?>: <span class="sfImaginable_item_caption"><?php echo $image->getCaption(); ?></span> <input type="button" value="Change" />
        
      </p>
    </div>   

  <?php endforeach; ?>
  </div>

  <div class="clearer">&nbsp;</div>
  
  <script type="text/javascript">
  //<![CDATA[
    jQuery(document).ready(function() {
      jQuery('#sortable_list').sortable({
          axis: 'y',
          placeholder: 'sfImaginable_list_record-highlight',
          update: function(event, ui)
            {
              var serialized = jQuery('#sortable_list').sortable('serialize', {key: 'sortable_list[]'});
              jQuery.ajax({
                url: "<?php echo url_for('sfImaginable/reorder')?>",
                type: "POST",
                success: function(){ajaxUpdateImageList();},
                data: serialized + '&object_id=<?php echo $object->getId(); ?>&object_class=<?php echo get_class($object); ?>'
              });
            }
      });
      
      jQuery('.sfImaginable_list_record input[value=Change]').live('click' ,function(event) {
          event.preventDefault();
          jQuery(this).parent().children('span').each(function() { 
            jQuery(this).replaceWith('<input type="text" value="' + jQuery(this).text() + '" />');
          });                                                        
          jQuery(this).after('<input type="button" value="Update" />').remove();
      });
    
      jQuery('.sfImaginable_list_record input[value=Update]').live('click', function(event) {
        event.preventDefault();
        thisObject = jQuery(this);  
        thisObject.attr('value', 'Sending...');
        jQuery.ajax({
          url: "<?php echo url_for('sfImaginable/ajaxUpdateCaption'); ?>",
          type: "POST",
          data: {
            id:       jQuery(this).parents('.sfImaginable_list_record').attr('id').substring(5),
            caption:  jQuery(this).parent().children('input[type=text]').val() 
          },
          success: function(data) {
            thisObject.parent().children('input[type=text]').replaceWith('<span class="sfImaginable_item_caption">' + data + '</span>');
            thisObject.after('<input type="button" value="Change" />').remove(); 
          }
        });
      });
      
      jQuery('.sfImaginable_list_record input[type=text]').live('keypress', function(event) {
        if(event.which == 13) // enter key 
        { 
          event.preventDefault();
          jQuery(this).parent().children('input[value=Update]').trigger('click');
        }
      });
    
    });
  //]]>
  </script>
  
                    
  <div id="indicator">
    <?php echo image_tag('/sfImaginablePlugin/images/ajax-loader.gif',array('alt'=>'Loading!','width'=>'22','height'=>'22'))?>
  </div>

<?php else: //Inform the user in case no images are attached to the object?>
  <h3> <?php echo __('No images are avaliable for this object',null,'sfImaginable') ?> </h3>
<?php endif; //end if section?>

</div> <!-- end fieldset -->

<div class="clearer">&nbsp;</div>   

