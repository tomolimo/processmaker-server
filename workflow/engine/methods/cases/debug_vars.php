<?php
$request = isset($_POST['request']) ? $_POST['request'] : '';
switch($request){
  case 'getRows':
    
    $fieldname = $_POST['fieldname'];

    $response->headers = Array(Array('name'=>'uno'), Array('name'=>'dos'));
    $response->rows = Array(Array(1,2), Array(3,4), Array(5,6));
    $response->columns = Array();
    $response->columns[] = Array('header'=>'Uno', 'width'=>100, 'dataIndex'=>'uno');
    $response->columns[] = Array('header'=>'Dos', 'width'=>100, 'dataIndex'=>'dos');
     //{header: "ID", width: 100, sortable: true, dataIndex: 'uno'},


    G::LoadClass('case');
    $oApp= new Cases();
    $aFields = $oApp->loadCase($_SESSION['APPLICATION']);

    $aVariables = Array();
    for($i=0; $i<count($_SESSION['TRIGGER_DEBUG']['DATA']); $i++) {
      $aVariables[$_SESSION['TRIGGER_DEBUG']['DATA'][$i]['key']] = $_SESSION['TRIGGER_DEBUG']['DATA'][$i]['value'];
    }

    $aVariables = array_merge($aFields['APP_DATA'], $aVariables);



    $o = new stdClass();
    $o->name = 'erik';
    $o->nick = 'neyek';
    $aVariables['neyek'] = $o;



    

    $field = $aVariables[$fieldname];
    $response->headers = Array();
    $response->columns = Array();
    $response->rows    = Array();

    $sw = true;
    $j = 0;
    if(is_array($field)){
      foreach ($field as $row) {
        if($sw){
          foreach ($row as $key=>$value) {
            $response->headers[] = Array('name'=>$key);
            $response->columns[] = Array('header'=>$key, 'width'=>100, 'dataIndex'=>$key);
          }
          $sw = false;
        }

        $tmp = Array();
        foreach ($row as $key=>$value) {
          $tmp[] = $value;
        }
        $response->rows[$j++] = $tmp;
      }
    } else if( is_object($field) ) {
      $response->headers = Array(Array('name'=>'name'), Array('name'=>'value'));
      $response->columns = Array(Array('header'=>'Property', 'width'=>100, 'dataIndex'=>'name'), Array('header'=>'Value', 'width'=>100, 'dataIndex'=>'value'));

      foreach ($field as $key => $value) {  
        $response->rows[] = Array($key, $value);
      }
    }
    
    echo G::json_encode($response);
  break;

  default:
    G::LoadClass('case');
    $oApp= new Cases();
    $aFields = $oApp->loadCase($_SESSION['APPLICATION']);

    $aVariables = Array();
    for($i=0; $i<count($_SESSION['TRIGGER_DEBUG']['DATA']); $i++) {
      $aVariables[$_SESSION['TRIGGER_DEBUG']['DATA'][$i]['key']] = $_SESSION['TRIGGER_DEBUG']['DATA'][$i]['value'];
    }

    $aVariables = array_merge($aFields['APP_DATA'], $aVariables);


    if( isset($_POST['filter']) && $_POST['filter'] == 'dyn' ){
      $sysVars = array_keys(G::getSystemConstants());
      $varNames = array_keys($aVariables);
      foreach($varNames as $var){
        if( in_array($var, $sysVars) ){
          unset($aVariables[$var]);
        }
      }
    }
    if( isset($_POST['filter']) && $_POST['filter'] == 'sys' ){
      $aVariables = G::getSystemConstants();
    }

    ksort($aVariables);

    //print_r($aVariables);
    $return_object->totalCount=1;


    $o = new stdClass();
    $o->name = 'erik';
    $o->nick = 'neyek';
    $aVariables['neyek'] = $o;
    
    foreach ($aVariables as $i=>$var) {
      if( is_object($var) ){
        $aVariables[$i] = '<object>';
      }
      if( is_array($var) ){
        $aVariables[$i] = '<array>';
      }
    }

    

    $return_object->data[0]=$aVariables;

    echo json_encode($return_object);
    //echo '{"totalCount":1,"data":[{"param1":"173","param2":"923","param3":"value param3"}]}';
  break;
}