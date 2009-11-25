<?php echo form_tag('sfImaginable/ajaxUpload', array('multipart'=>true)) ?>
<?php echo input_hidden_tag('object_class','SpecialOffer') ?>
<?php echo input_hidden_tag('object_id','9') ?>
<?php echo input_file_tag('Filedata') ?>
<?php echo submit_tag('upload') ?>
</form>
