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

  require_once ( "classes/model/ProcessCategory.php" );


  $fields['CATEGORY_UID'] = G::GenerateUniqueID();;

  $fields['CATEGORY_PARENT'] = '';
  $fields['CATEGORY_NAME'] = '';
  $fields['CATEGORY_ICON'] = '';

  $G_MAIN_MENU = 'workflow';
  $G_SUB_MENU = 'processCategory';
  $G_ID_MENU_SELECTED = '';
  $G_ID_SUB_MENU_SELECTED = '';


  $G_PUBLISH = new Publisher;
  $G_PUBLISH->AddContent('xmlform', 'xmlform', 'processCategory/processCategoryEdit', '', $fields, 'processCategorySave' );  
  G::RenderPage('publishBlank', 'blank');   
?>