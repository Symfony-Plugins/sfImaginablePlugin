<?php use_helper('Javascript') ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>Iframe</title>

</head>

<body onload="flash_sortables()">
<?php echo javascript_tag("
function flash_sortables()
{
  ".visual_effect('highlight','sortable_list')."
}
")?>
  <?php include_partial('list_images',array('object'=>$object)) ?>
</body>
</html>
