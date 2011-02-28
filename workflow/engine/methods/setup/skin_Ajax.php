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
    $skinListArray=array();
    $customSkins=glob(PATH_CUSTOM_SKINS."*");
    foreach($customSkins as $skin){
        if(is_dir($skin)){
            $res['CALENDAR_UID']=$skin;
            $res['CALENDAR_NAME']=basename($skin);
            
            $skinListArray['cals'][]=$res;
        }
    }
    print_r(G::json_encode($skinListArray));
}