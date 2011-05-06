<?php
$request = isset($_REQUEST['action'])?$_REQUEST['action']:"";
G::LoadClass ( 'serverConfiguration' );
$oServerConf = & serverConf::getSingleton ();
$oServerConf->setHeartbeatProperty('HB_BEAT_URL','http://heartbeat.processmaker.com/syspmLicenseSrv/en/green/services/beat','HEART_BEAT_CONF');
switch ($request) {
    case 'processInformation':
        try{
        $heartBeatUrl = $oServerConf->getHeartbeatProperty('HB_BEAT_URL','HEART_BEAT_CONF');
        //Test connection
        if(!(validateConnectivity($heartBeatUrl))){
          $oServerConf->setHeartbeatProperty('HB_NEXT_BEAT_DATE',strtotime ( "+1 day" ),'HEART_BEAT_CONF');
          throw new Exception("Heartbeat::No connection");
        }
        //Build Data to be sent
        $params=buildData();
        
        //Send the information
        postHeartBeat($params);
        } catch (Exception $e) {
          G::pr($e->getMessage());
        }
    break;
    
}

function validateConnectivity($url){
	ini_set('allow_url_fopen', 1);
      $sContent = file_get_conditional_contents($url);
    	$sw_connect=true;
    	//if ($sContent == '' || $sContent === false || strpos ( $sContent, 'address location' ) === false ) {    		4
    	if ($sContent == '' || $sContent === false  ) {    		
    		$sw_connect=false;
    	}
    	return $sw_connect;
}
function file_get_conditional_contents($szURL){
    
    // if curl module is not installed 
    if (!function_exists('curl_init')) {
      return false;
    }
    
    $pCurl = curl_init ();
    curl_setopt ( $pCurl, CURLOPT_URL, $szURL );
    curl_setopt ( $pCurl, CURLOPT_RETURNTRANSFER, true );
    curl_setopt ( $pCurl, CURLOPT_HEADER, true );
    curl_setopt ( $pCurl, CURLOPT_FOLLOWLOCATION, false );
    curl_setopt ( $pCurl, CURLOPT_AUTOREFERER, true );
    //To avoid SSL error
    curl_setopt ( $pCurl, CURLOPT_SSL_VERIFYHOST, 0 );
    curl_setopt ( $pCurl, CURLOPT_SSL_VERIFYPEER, 0);
  
    //To avoid timeouts
    curl_setopt ( $pCurl, CURLOPT_CONNECTTIMEOUT, 10 );
    curl_setopt ( $pCurl, CURLOPT_TIMEOUT, 20 );
    
    curl_setopt ( $pCurl, CURLOPT_NOPROGRESS, FALSE);
    curl_setopt ( $pCurl, CURLOPT_VERBOSE, TRUE);
    
    

    $szContents = curl_exec($pCurl);
    $aInfo = curl_getinfo($pCurl);
    
    $curl_session = curl_getinfo($pCurl, CURLINFO_HTTP_CODE);
    $headers = curl_getinfo ( $pCurl );
    $header = substr ( $szContents, 0, $headers ['header_size'] );
    $content = substr ( $szContents, $headers ['header_size'] );
    

    if($aInfo['http_code'] === 200)
    {
        return $content;
    }

    return false;
}
function buildData(){
G::LoadClass ( 'serverConfiguration' );
$oServerConf = & serverConf::getSingleton ();  
  if (! defined ( 'PM_VERSION' )) {
      if (file_exists ( PATH_METHODS . 'login/version-pmos.php' )) {
        require_once (PATH_METHODS . 'login/version-pmos.php');
      } else {
        define ( 'PM_VERSION', 'Development Version' );
      }
    }

    $os = '';
    if (file_exists ( '/etc/redhat-release' )) {
      $fnewsize = filesize ( '/etc/redhat-release' );
      $fp = fopen ( '/etc/redhat-release', 'r' );
      $os = trim ( fread ( $fp, $fnewsize ) );
      fclose ( $fp );
    }
    $os .= " (" . PHP_OS . ")";
    
    
  
  
  $params = array ();
    $params ['ip'] = getenv ( 'SERVER_ADDR' );
    $oServerConf->setHeartbeatProperty('HB_BEAT_INDEX',intval($oServerConf->getHeartbeatProperty('HB_BEAT_INDEX','HEART_BEAT_CONF'))+1,'HEART_BEAT_CONF');
    
    $params ['index'] = $oServerConf->getHeartbeatProperty('HB_BEAT_INDEX','HEART_BEAT_CONF');//$this->index;
    $params ['beatType'] = is_null($oServerConf->getHeartbeatProperty('HB_BEAT_TYPE','HEART_BEAT_CONF'))?"starting":$oServerConf->getHeartbeatProperty('HB_BEAT_TYPE','HEART_BEAT_CONF');//1;//$this->beatType;
    $params ['date'] = date ( 'Y-m-d H:i:s' );
    $params ['host'] = getenv ( 'SERVER_NAME' );
    $params ['os'] = $os;
    $params ['webserver'] = getenv ( 'SERVER_SOFTWARE' );
    $params ['php'] = phpversion ();
    $params ['pmVersion'] = PM_VERSION;
    if(class_exists('pmLicenseManager')){
      $params ['pmProduct'] = 'PMEE';
    }else{
      $params ['pmProduct'] = 'PMCE';
    }
    
    $params ['logins'] = $oServerConf->logins;
    $params ['workspaces'] = serialize ( $oServerConf->getWSList () );
    $params ['plugins'] = serialize ( $oServerConf->getPluginsList () );
    $params ['dbVersion'] = $oServerConf->getDBVersion();
    //$params ['errors'] = serialize( $oServerConf->errors );
    if($licInfo=$oServerConf->getProperty('LICENSE_INFO')){
      $params ['license'] = serialize ( $licInfo );
    }
    return $params;
}
  function postHeartBeat($params) {
   if(is_array($params)){
     //No matter what happens with the result let's set the nextBeat to 2 hours from now
     G::LoadClass ( 'serverConfiguration' );
     $oServerConf = & serverConf::getSingleton ();
     $oServerConf->setHeartbeatProperty('HB_NEXT_BEAT_DATE',strtotime ( "+2 hour" ),'HEART_BEAT_CONF');
     $nextBeatDate = $oServerConf->getHeartbeatProperty('HB_NEXT_BEAT_DATE','HEART_BEAT_CONF');
     
     $heartBeatUrl = $oServerConf->getHeartbeatProperty('HB_BEAT_URL','HEART_BEAT_CONF');
     
   $ch = curl_init ();
    curl_setopt ( $ch, CURLOPT_URL, $heartBeatUrl );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt ( $ch, CURLOPT_HEADER, true );
    curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, false );
    curl_setopt ( $ch, CURLOPT_AUTOREFERER, true );
    //To avoid SSL error
    curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
    curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, 0 );

    curl_setopt ( $ch, CURLOPT_POST, 1 );
    curl_setopt ( $ch, CURLOPT_POSTFIELDS, $params );    

    //To avoid timeouts
    curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 10 );
    curl_setopt ( $ch, CURLOPT_TIMEOUT, 20 );
    
    $response = curl_exec ( $ch );
    $curl_session = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $headers = curl_getinfo ( $ch );
    $header = substr ( $response, 0, $headers ['header_size'] );
    $content = substr ( $response, $headers ['header_size'] );
    curl_close ( $ch );

    if ($headers ['http_code'] == 200) {
      $oServerConf->setHeartbeatProperty('HB_BEAT_TYPE','beat','HEART_BEAT_CONF');
      $oServerConf->resetLogins ();
      $oServerConf->setHeartbeatProperty('HB_NEXT_BEAT_DATE',strtotime ( "+7 day" ),'HEART_BEAT_CONF');
      //Reset Errors
      
    } else {
        //Catch the error
        
      $oServerConf->setHeartbeatProperty('HB_NEXT_BEAT_DATE',strtotime ( "+1 day" ),'HEART_BEAT_CONF');
    }
     
   }
    /*
    
    $ch = curl_init ();
    curl_setopt ( $ch, CURLOPT_URL, $heartBeatUrl );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt ( $ch, CURLOPT_HEADER, true );
    curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, false );
    curl_setopt ( $ch, CURLOPT_AUTOREFERER, true );
    //To avoid SSL error
    curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
    curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, 0 );

    curl_setopt ( $ch, CURLOPT_POST, 1 );
    curl_setopt ( $ch, CURLOPT_POSTFIELDS, $params );    

    //To avoid timeouts
    curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 10 );
    curl_setopt ( $ch, CURLOPT_TIMEOUT, 20 );

    $response = curl_exec ( $ch );
    $curl_session = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $headers = curl_getinfo ( $ch );
    $header = substr ( $response, 0, $headers ['header_size'] );
    $content = substr ( $response, $headers ['header_size'] );
    curl_close ( $ch );

    if ($headers ['http_code'] == 200) {
      $this->beatType = 'beat';
      $this->resetLogins ();
      $this->nextBeatDate = strtotime ( "+7 day" ); //next beat in 7 days
      //Reset Errors
      $this->errors=array();
    } else {
        //Catch the error
        $this->errors[]=$curl_session;
      $this->nextBeatDate = strtotime ( "+1 day" ); //retry in 30 mins
    }

    $this->saveSingleton ();
    */
  }