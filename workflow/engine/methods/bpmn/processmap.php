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
  
  //$oHeadPublisher->setExtSkin( 'xtheme-gray');
  $oHeadPublisher->addExtJsScript('bpmn/MyWorkflow',true );  
  $oHeadPublisher->addExtJsScript('bpmn/pmosExt', true );    
  $oHeadPublisher->addExtJsScript('bpmn/TaskContext', true ); 
  $oHeadPublisher->addExtJsScript('bpmn/ProcessMapContext', false );
  $oHeadPublisher->addExtJsScript('bpmn/processmap', true );    
  $oHeadPublisher->addExtJsScript('bpmn/Annotation' );
  $oHeadPublisher->addExtJsScript('bpmn/bpmnShapes', true); 
  //$oHeadPublisher->addExtJsScript('bpmn/LoopingSubProcess');
  //$oHeadPublisher->addExtJsScript('bpmn/LoopingTask'); //
  //$oHeadPublisher->addExtJsScript('bpmn/Dataobject'); //
  //$oHeadPublisher->addExtJsScript('bpmn/Pool',true);
  //$oHeadPublisher->addExtJsScript('bpmn/Lane');
    
  $oHeadPublisher->addExtJsScript('bpmn/EventEmptyStart');
  $oHeadPublisher->addExtJsScript('bpmn/EventMessageStart');
  //$oHeadPublisher->addExtJsScript('bpmn/EventRuleStart');
  $oHeadPublisher->addExtJsScript('bpmn/EventTimerStart');
  //$oHeadPublisher->addExtJsScript('bpmn/EventSignalStart');
  //$oHeadPublisher->addExtJsScript('bpmn/EventMulStart');
  //$oHeadPublisher->addExtJsScript('bpmn/EventLinkStart');
  $oHeadPublisher->addExtJsScript('bpmn/EventEmptyInter');
  $oHeadPublisher->addExtJsScript('bpmn/EventMessageRecInter');
  $oHeadPublisher->addExtJsScript('bpmn/EventMessageSendInter');
  $oHeadPublisher->addExtJsScript('bpmn/EventTimerInter');
  //$oHeadPublisher->addExtJsScript('bpmn/EventBoundaryTimerInter');
  //$oHeadPublisher->addExtJsScript('bpmn/EventErrorInter');
  //$oHeadPublisher->addExtJsScript('bpmn/EventCompInter');
  //$oHeadPublisher->addExtJsScript('bpmn/EventRuleInter');
  //$oHeadPublisher->addExtJsScript('bpmn/EventCancelInter');
  //$oHeadPublisher->addExtJsScript('bpmn/EventInterSignal');
  //$oHeadPublisher->addExtJsScript('bpmn/EventMultipleInter');
  //$oHeadPublisher->addExtJsScript('bpmn/EventLinkInter');
  $oHeadPublisher->addExtJsScript('bpmn/EventEmptyEnd');
  $oHeadPublisher->addExtJsScript('bpmn/EventMessageEnd');
  //$oHeadPublisher->addExtJsScript('bpmn/EventErrorEnd');
  //$oHeadPublisher->addExtJsScript('bpmn/EventCompEnd');
  $oHeadPublisher->addExtJsScript('bpmn/EventTerminateEnd');
  //$oHeadPublisher->addExtJsScript('bpmn/EventEndSignal');
  //$oHeadPublisher->addExtJsScript('bpmn/EventMultipleEnd');
  //$oHeadPublisher->addExtJsScript('bpmn/EventCancelEnd');
  //$oHeadPublisher->addExtJsScript('bpmn/EventLinkEnd');

  $oHeadPublisher->addExtJsScript('bpmn/GatewayInclusive' );
  $oHeadPublisher->addExtJsScript('bpmn/GatewayExclusiveData' );
  $oHeadPublisher->addExtJsScript('bpmn/GatewayExclusiveEvent' );
  $oHeadPublisher->addExtJsScript('bpmn/GatewayParallel' );
  //$oHeadPublisher->addExtJsScript('bpmn/GatewayComplex' );
  $oHeadPublisher->addExtJsScript('bpmn/GridPanel');
  $oHeadPublisher->addExtJsScript('bpmn/SubProcess' );
  $oHeadPublisher->addExtJsScript('bpmn/ProcessOptions',true);

  $oHeadPublisher->addContent( 'bpmn/processmap'); //adding a html file  .html.
  
  $oHeadPublisher->assign('pro_title', $process->getProTitle());
  $oHeadPublisher->assign('pro_uid', $process->getProUid());
  G::RenderPage('publish', 'extJs');
 
