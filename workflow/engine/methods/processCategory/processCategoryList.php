<?php

if($RBAC->userCanAccess('PM_SETUP') != 1 && $RBAC->userCanAccess('PM_SETUP_ADVANCE') != 1){	
  G::SendTemporalMessage('krlos', 'error', 'labels');
  //G::header('location: ../login/login');
  die;
}

  try {  	

  $G_MAIN_MENU = 'workflow';
  $G_SUB_MENU = 'processCategory';
  $G_ID_MENU_SELECTED = '';
  $G_ID_SUB_MENU_SELECTED = '';

  $G_PUBLISH = new Publisher;


  require_once ( "classes/model/ProcessCategory.php" );

  $Criteria = new Criteria('workflow');
  $Criteria->clearSelectColumns ( );
  
  $Criteria->addSelectColumn (  ProcessCategoryPeer::CATEGORY_UID );
  $Criteria->addSelectColumn (  ProcessCategoryPeer::CATEGORY_PARENT );
  $Criteria->addSelectColumn (  ProcessCategoryPeer::CATEGORY_NAME );
  $Criteria->addSelectColumn (  ProcessCategoryPeer::CATEGORY_ICON );

  $Criteria->add (  processCategoryPeer::CATEGORY_UID, "xx" , CRITERIA::NOT_EQUAL );
  
  $G_PUBLISH->AddContent('propeltable', 'paged-table', 'processCategory/processCategoryList', $Criteria , array(),'');
  G::RenderPage('publishBlank', 'blank');

  }
  catch ( Exception $e ) {
    $G_PUBLISH = new Publisher;
    $aMessage['MESSAGE'] = $e->getMessage();
    $G_PUBLISH->AddContent('xmlform', 'xmlform', 'login/showMessage', '', $aMessage );
    G::RenderPage( 'publishBlank', 'blank' );
  }      
