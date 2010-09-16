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

  $aux = explode ( '|', isset($_GET['id']) ? $_GET['id'] : '' );
  $CategoryUid = str_replace ( '"', '', $aux[0] );
  

  require_once ( "classes/model/ProcessCategory.php" );
  //if exists the row in the database propel will update it, otherwise will insert.
  $tr = ProcessCategoryPeer::retrieveByPK( $CategoryUid  );
  
  if ( ( is_object ( $tr ) &&  get_class ($tr) == 'ProcessCategory' ) ) { 
     $fields['CATEGORY_UID'] = $tr->getCategoryUid();
     $fields['CATEGORY_PARENT'] = $tr->getCategoryParent();
     $fields['CATEGORY_NAME'] = $tr->getCategoryName();
     $fields['CATEGORY_ICON'] = $tr->getCategoryIcon();
  }
  else
    $fields = array();  
  
  $G_MAIN_MENU = 'workflow';
  $G_SUB_MENU = 'processCategory';
  $G_ID_MENU_SELECTED = '';
  $G_ID_SUB_MENU_SELECTED = '';


  $G_PUBLISH = new Publisher;
  $G_PUBLISH->AddContent('xmlform', 'xmlform', 'processCategory/processCategoryEdit', '', $fields, 'processCategorySave' );
  G::RenderPage('publishBlank', 'blank');   
?>