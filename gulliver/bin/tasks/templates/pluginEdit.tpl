<?php    
  $aux = explode('|', isset($_GET['id'])? $_GET['id'] : '');
<!-- START BLOCK : keys -->
  ${phpName} = str_replace('"', '', $aux[{index}]);
<!-- END BLOCK : keys --> 
  
<!-- START BLOCK : plugin -->
  require_once (PATH_PLUGINS . '{pluginName}' . PATH_SEP . 'class.{pluginName}.php');
  $pluginObj = new {pluginName}Class();
<!-- END BLOCK : plugin -->

  require_once ("classes/model/{className}.php");
  //if exists the row in the database propel will update it, otherwise will insert.
  $tr = {className}Peer::retrieveByPK({keylist});
  
  if ((is_object($tr) && get_class($tr) == '{className}')) { 
<!-- START BLOCK : fields -->
    $fields['{name}'] = $tr->get{phpName}();
<!-- END BLOCK : fields -->
  }
  else
    $fields = array();  
  
  $G_MAIN_MENU = '{projectName}';
  $G_SUB_MENU = '{phpClassName}';
  $G_ID_MENU_SELECTED = '{menuId}';
  $G_ID_SUB_MENU_SELECTED = '{menuId}';

  $G_PUBLISH = new Publisher;
  $G_PUBLISH->AddContent('xmlform', 'xmlform', '{phpFolderName}/{phpClassName}Edit', '', $fields, '{phpClassName}Save');
  G::RenderPage('publish');
?>