<?php

  if( ! isset($_GET['PRO_UID']) )
    throw new Exception('The Process ID was not set!');

  require_once 'classes/model/Process.php';
  $process = ProcessPeer::retrieveByPK( $_GET['PRO_UID'] );
  
  if( get_class($process) != 'Process' ) {
    throw new Exception("The Process with UID: {$_GET['PRO_UID']} doesn't exist!");
  }
  
  $processUID = $_GET['PRO_UID'];
  $_SESSION['PROCESS'] = $processUID;
  $_SESSION['PROCESSMAP'] = 'BPMN';
  
  $oHeadPublisher =& headPublisher::getSingleton();
  //$oHeadPublisher->usingExtJs('ux/miframe');
  $oHeadPublisher->addExtJsScript('bpmn/ProcessOptions', true);
  $oHeadPublisher->addExtJsScript('bpmn/processmap', true);
  $oHeadPublisher->addContent( 'bpmn/processmap');
  $oHeadPublisher->assign('pro_title', $process->getProTitle());
  $oHeadPublisher->assign('pro_uid', $process->getProUid());
  G::RenderPage('publish', 'extJs');
 
