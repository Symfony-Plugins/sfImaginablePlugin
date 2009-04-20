<?php use_helper('I18N'); ?>
<h1>
  <?php echo __('We are sorry, but you first need to save your (%1%) record before you can add images to it.',array('%1%'=>__(get_class($object),array(),'messages'))) ?>
</h1>