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
 
  $G_PUBLISH = new Publisher;
 
  G::LoadClass('serverConfiguration');
	$oServerConf =& serverConf::getSingleton();
	/*you can use SYS_TEMP or SYS_SYS ON HEAR_BEAT_CONF to save for each workspace*/
	$sflag = $oServerConf->getHeartbeatProperty('HB_OPTION','HEART_BEAT_CONF');
	
    if($sflag){
      $aRow['HB_OPTION']='1';
    }else{
      $aRow['HB_OPTION']='0';
    }
    if($oServerConf->getHeartbeatProperty('HB_BEAT_TYPE','HEART_BEAT_CONF')=="endbeat"){
      $oHeadPublisher =& headPublisher::getSingleton();
      $oHeadPublisher->addScriptCode('
      
      function processHbInfo(){
      ajax_server="../services/processHeartBeat_Ajax.php";
      parameters="action=processInformation";
      method="POST";
      callback="";
      asynchronous=true;
      ajax_post(ajax_server, parameters, method, callback, asynchronous );
      }
      processHbInfo();
      ');
    }
  $G_PUBLISH->AddContent ( 'xmlform', 'xmlform', 'setup/processHeartBeatConfig', '', $aRow, 'processHeartBeatSave' );

  G::RenderPage('publishBlank', 'blank');

  }
  catch ( Exception $e ) {
    $G_PUBLISH = new Publisher;
    $aMessage['MESSAGE'] = $e->getMessage();
    $G_PUBLISH->AddContent('xmlform', 'xmlform', 'login/showMessage', '', $aMessage );
    G::RenderPage( 'publishBlank', 'blank' );
  }      
?>