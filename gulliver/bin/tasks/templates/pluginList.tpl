<?php
  try {

    $G_MAIN_MENU = '{projectName}';
    $G_SUB_MENU = '{phpClassName}';
    $G_ID_MENU_SELECTED = '{menuId}';
    $G_ID_SUB_MENU_SELECTED = '{menuId}';

    $G_PUBLISH = new Publisher;

<!-- START BLOCK : plugin -->
    require_once (PATH_PLUGINS . '{pluginName}' . PATH_SEP . 'class.{pluginName}.php');
    $pluginObj = new {pluginName}Class();
<!-- END BLOCK : plugin -->

    require_once ("classes/model/{className}.php");

    $Criteria = new Criteria('workflow');
    $Criteria->clearSelectColumns();
  
<!-- START BLOCK : fields -->
    $Criteria->addSelectColumn({className}Peer::{name});
<!-- END BLOCK : fields -->

    $Criteria->add({phpClassName}Peer::{firstKey}, "xx", CRITERIA::NOT_EQUAL);
  
    $G_PUBLISH->AddContent('propeltable', 'paged-table', '{phpFolderName}/{phpClassName}List', $Criteria, array(), '');
    G::RenderPage('publish');
  }
  catch (Exception $e) {
    $G_PUBLISH = new Publisher;
    $aMessage['MESSAGE'] = $e->getMessage();
    $G_PUBLISH->AddContent('xmlform', 'xmlform', 'login/showMessage', '', $aMessage);
    G::RenderPage('publish', 'blank');
  }
