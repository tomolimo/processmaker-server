<?php
<!-- START BLOCK : plugin -->
  require_once (PATH_PLUGINS . '{pluginName}' . PATH_SEP . 'class.{pluginName}.php');
  $pluginObj = new {pluginName}Class();
<!-- END BLOCK : plugin -->

  require_once ("classes/model/{className}.php");

<!-- START BLOCK : dummy -->
  //if exists the row in the database propel will update it, otherwise will insert.
  //$tr = {phpClassName}Peer::retrieveByPK( {keylist}  );
  //if ((is_object($tr) && get_class($tr) == '{phpClassName}')) { 
  //}
  //else
  //  $fields = array();  
  //$fields['ITM_UID'] = $ItmUid;
<!-- END BLOCK : dummy --> 

<!-- START BLOCK : keys -->
  $fields['{name}'] = G::GenerateUniqueID();;
<!-- END BLOCK : keys --> 

<!-- START BLOCK : onlyFields -->
  $fields['{name}'] = '';
<!-- END BLOCK : onlyFields --> 

  $G_MAIN_MENU = '{projectName}';
  $G_SUB_MENU = '{phpClassName}';
  $G_ID_MENU_SELECTED = '{menuId}';
  $G_ID_SUB_MENU_SELECTED = '{menuId}';

  $G_PUBLISH = new Publisher;
  $G_PUBLISH->AddContent('xmlform', 'xmlform', '{phpFolderName}/{phpClassName}Edit', '', $fields, '{phpClassName}Save');
  G::RenderPage('publish');
?>