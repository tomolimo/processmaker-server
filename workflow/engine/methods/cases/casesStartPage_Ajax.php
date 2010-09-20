<?php

if(!isset($_POST['action'])) {
	$ruturn['success']='failure';
	$ruturn['message']='You may request an action';
	print json_encode( $ruturn ) ;
	die;
}
if(!function_exists($_POST['action'])){
	$ruturn['success']='failure';
	$ruturn['message']='The requested action doesn\'t exists';
	print json_encode( $ruturn ) ;
	die;
}

$functionName=$_POST['action'];
$functionParams=isset($_POST['params'])?$_POST['params']:array();

$functionName($functionParams);

function getProcessList(){
	G::LoadClass('case');
	$oCase = new Cases();
  $bCanStart = $oCase->canStartCase( $_SESSION['USER_LOGGED'] );
  if ($bCanStart){
    $processListInitial = $oCase->getStartCasesPerType( $_SESSION['USER_LOGGED'], 'category' );
    $processList=array();
    foreach($processListInitial as $key => $procInfo){
	    if(isset($procInfo['cat'])){
	    	if(trim($procInfo['cat'])=="") $procInfo['cat']="_OTHER_";
	    	$processList[$procInfo['catname']][$procInfo['value']]=$procInfo;
	    }
    }
    ksort($processList);
    foreach($processList as $key => $processInfo){
    	ksort($processList[$key]);
    }
    
    if(!isset($_POST['node'])){
    	$node='root';
    }else{
    	$node=$_POST['node'];
    }
    
    $processListTree=array();
    if($node=='root'){
    	foreach($processList as $key => $processInfo){
    		$tempTree['text']=$key;
    		$tempTree['id']=$key;
    		$tempTree['cls']='folder';
    		$tempTree['draggable']=true;
    		$tempTree['optionType']="category";
    		//$tempTree['allowDrop']=false;
    		$tempTree['singleClickExpand']=true;
    		if($key != "No Category"){
    		  $tempTree['expanded']=true;
    	  }else{
    	    $tempTree['expanded']=false;
    	  }
    		$processListTree[]=$tempTree;
    	}
    }else{
    	foreach($processList[$node] as $key => $processInfo){
    	  //print_r($processInfo);
    		$tempTree['text']=$key;
    		$tempTree['id']=$key;
    		$tempTree['draggable']=true;
        $tempTree['leaf']=true;
    		$tempTree['icon']='/images/icon.trigger.png';
    		$tempTree['allowChildren']=false;
    		$tempTree['optionType']="startProcess";
    		$tempTree['pro_uid']=$processInfo['pro_uid'];
    		$tempTree['tas_uid']=$processInfo['uid'];
    		
    		
    		//$tempTree['cls']='file';
    		$processListTree[]=$tempTree;
    	}
    	
    }
		$processList=$processListTree;
  }else{
  	$processList['success']='failure';
  	$processList['message']='User can\'t start process';
  }
	print json_encode( $processList ) ;
	die;
}

function startCase(){
  /* Includes */
  G::LoadClass('case');

  /* GET , POST & $_SESSION Vars */

  /* unset any variable, because we are starting a new case */
  if (isset($_SESSION['APPLICATION']))   unset($_SESSION['APPLICATION']);
  if (isset($_SESSION['PROCESS']))       unset($_SESSION['PROCESS']);
  if (isset($_SESSION['TASK']))          unset($_SESSION['TASK']);
  if (isset($_SESSION['INDEX']))         unset($_SESSION['INDEX']);
  if (isset($_SESSION['STEP_POSITION'])) unset($_SESSION['STEP_POSITION']);

  /* Process */
  try {  	
    $oCase = new Cases();
    $aData = $oCase->startCase( $_POST['taskId'], $_SESSION['USER_LOGGED'] );
    $_SESSION['APPLICATION']   = $aData['APPLICATION'];
    $_SESSION['INDEX']         = $aData['INDEX'];
    $_SESSION['PROCESS']       = $aData['PROCESS'];
    $_SESSION['TASK']          = $_POST['taskId'];
    $_SESSION['STEP_POSITION'] = 0;
    
    $_SESSION['CASES_REFRESH'] = true;

    $oCase     = new Cases();
    $aNextStep = $oCase->getNextStep($_SESSION['PROCESS'], $_SESSION['APPLICATION'], $_SESSION['INDEX'], $_SESSION['STEP_POSITION']);
   	$_SESSION['BREAKSTEP']['NEXT_STEP'] = $aNextStep;
   	$aData['status']='success';
   	$aData['openCase']=$aNextStep;
   	print(json_encode($aData) );
    //G::header('location: ' . $aNextStep['PAGE']);
  }
  catch ( Exception $e ) {
    $_SESSION['G_MESSAGE']      = $e->getMessage();
    $_SESSION['G_MESSAGE_TYPE'] = 'error';
    //G::header('location: cases_New' );
    $aData['status']='failure';
    $aData['message']=$e->getMessage();
    print_r(json_encode($aData));
  }
}
  try{



  }
  catch ( Exception $e ) {
    $G_PUBLISH = new Publisher;
    $aMessage['MESSAGE'] = $e->getMessage();
    $G_PUBLISH->AddContent('xmlform', 'xmlform', 'login/showMessage', '', $aMessage );
    G::RenderPage( 'publish', 'blank' );
  }
