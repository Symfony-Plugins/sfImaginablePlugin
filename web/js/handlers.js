queueComplete = function (event, data){
  ajaxUpdateImageList();  
};

function echoInfo(event, queueID, fileObj, response, data)
{
  alert('lol');
  alert(response);
} 
      /*
function ajaxUpdateImageList() {
  //new Ajax.Updater('ajax-image-list', '/backend.php/sfImaginable/ajaxList', {asynchronous:true, evalScripts:true, parameters:'object_class=' + object_class + '&object_id=' + object_id });
  jQuery('#ajax-image-list').load('/backend.php/sfImaginable/ajaxList', {'object_class' = object_class, 'object_id' = object_id});
  return false;
};
*/