<?php
/**
 * BPMN Designer v1.1
 * @date Feb 2th, 2011
 * @author Erik A. O. <erik@colosa.com>
 */
 
class BpmnDesigner extends Controller
{
  function index($httpData)
  {
    if( ! isset($httpData->id) )
      throw new Exception('The Process ID was not set!');

    require_once 'classes/model/Process.php';
    $process = ProcessPeer::retrieveByPK($httpData->id);

    if( get_class($process) != 'Process' ) {
      throw new Exception("The Process with UID: {$httpData->id} doesn't exist!");
    }

    $_SESSION['PROCESS'] = $httpData->id;
    $_SESSION['PROCESSMAP'] = 'BPMN';
  
    $this->includeExtJS('bpmn/MyWorkflow',true ); 
    $this->includeExtJS('bpmn/pmosExt', true );  
    $this->includeExtJS('bpmn/TaskContext', true );

    $this->includeExtJS('bpmn/designerComponents', true );
    $this->includeExtJS('bpmn/designer', true );
    
    $this->includeExtJS('bpmn/Annotation',true );
    $this->includeExtJS('bpmn/bpmnShapes', true);
    $this->includeExtJS('bpmn/EventEmptyStart');
    $this->includeExtJS('bpmn/EventMessageStart');
    $this->includeExtJS('bpmn/EventTimerStart');
    $this->includeExtJS('bpmn/EventEmptyInter');
    $this->includeExtJS('bpmn/EventMessageRecInter');
    $this->includeExtJS('bpmn/EventMessageSendInter');
    $this->includeExtJS('bpmn/EventTimerInter');
    $this->includeExtJS('bpmn/EventEmptyEnd');
    $this->includeExtJS('bpmn/EventMessageEnd');
    $this->includeExtJS('bpmn/GatewayInclusive' );
    $this->includeExtJS('bpmn/GatewayExclusiveData');
    $this->includeExtJS('bpmn/GatewayParallel' );
    $this->includeExtJS('bpmn/GridPanel');
    $this->includeExtJS('bpmn/SubProcess' );
    $this->includeExtJS('bpmn/ProcessOptions',true);
    $this->includeExtJS('bpmn/ProcessMapContext', true );

    $this->includeExtJS('bpmn/ProcessOptions', true);

    $this->setJSVar('pro_uid', $httpData->id);
    $this->setJSVar('pro_title', $process->getProTitle());
    
    $this->setView('bpmn/designer');

    G::RenderPage('publish', 'extJs');
  }
}