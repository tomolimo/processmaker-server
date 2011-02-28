<?php

if (! isset ( $_REQUEST ['action'] )) {
    $res ['success'] = 'failure';
    $res ['message'] = 'You may request an action';
    print G::json_encode ( $res);
    die ();
}
if (! function_exists ( $_REQUEST ['action'] )) {
    $res ['success'] = 'failure';
    $res ['message'] = 'The requested action doesn\'t exists';
    print G::json_encode ( $res );
    die ();
}

$functionName = $_REQUEST ['action'];
$functionParams = isset ( $_REQUEST ['params'] ) ? $_REQUEST ['params'] : array ();

$functionName ( $functionParams );

function updatePageSize(){
    G::LoadClass('configuration');
    $c = new Configurations();
    $arr['pageSize'] = $_REQUEST['size'];
    $arr['dateSave'] = date('Y-m-d H:i:s');
    $config = Array();
    $config[] = $arr;
    $c->aConfig = $config;
    $c->saveConfig('calendarList', 'pageSize','',$_SESSION['USER_LOGGED']);
    echo '{success: true}';
}
function skinList(){
    //Get Skin Config files
    $skinListArray=array();
    $customSkins=glob(PATH_CUSTOM_SKINS."*/config.xml");
    $configurationFile    =    G::ExpandPath( "skinEngine" ).'base'.PATH_SEP.'config.xml';
    array_unshift($customSkins,$configurationFile);
    
    //Read and parse each Configuration File
    foreach($customSkins as $key => $configInformation){
      $xmlConfiguration = file_get_contents ( $configInformation );
      $xmlConfigurationObj=G::xmlParser($xmlConfiguration);
      $skinInformationArray=$skinFilesArray=$xmlConfigurationObj->result['skinConfiguration']['__CONTENT__']['information']['__CONTENT__'];
      
      $res=array();
      foreach($skinInformationArray as $keyInfo => $infoValue){
          $res['SKIN_'.strtoupper($keyInfo)]=$infoValue['__VALUE__'];
      }
      $skinListArray['skins'][]=$res;
    }
    print_r(G::json_encode($skinListArray));
}