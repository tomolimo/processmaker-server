<?php

$access = $RBAC->userCanAccess('PM_SETUP_ADVANCE');
if( $access != 1 ){
  switch ($access)
  {
  	case -1:
  	  G::SendTemporalMessage('ID_USER_HAVENT_RIGHTS_PAGE', 'error', 'labels');
  	  G::header('location: ../login/login');
  	  die;
  	break;
  	case -2:
  	  G::SendTemporalMessage('ID_USER_HAVENT_RIGHTS_SYSTEM', 'error', 'labels');
  	  G::header('location: ../login/login');
  	  die;
  	break;
  	default:
  	  G::SendTemporalMessage('ID_USER_HAVENT_RIGHTS_PAGE', 'error', 'labels');
  	  G::header('location: ../login/login');
  	  die;
  	break;  	
  }
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
