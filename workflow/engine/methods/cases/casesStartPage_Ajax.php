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
    		if($key != 'No Category')
    		$tempTree['expanded']=true;
    		$processListTree[]=$tempTree;
    	}
    }else{
    	foreach($processList[$node] as $key => $processInfo){
    		$tempTree['text']=$key;
    		$tempTree['id']=$key;
    		$tempTree['expanded']=true;
    		$tempTree['draggable']=true;
    	
    		//$tempTree['leaf']=false;
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

  try{



  }
  catch ( Exception $e ) {
    $G_PUBLISH = new Publisher;
    $aMessage['MESSAGE'] = $e->getMessage();
    $G_PUBLISH->AddContent('xmlform', 'xmlform', 'login/showMessage', '', $aMessage );
    G::RenderPage( 'publish', 'blank' );
  }
