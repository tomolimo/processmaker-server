<?php

G::LoadClass('case');

//variables
$oApp= new Cases();
$aFields = $oApp->loadCase($_SESSION['APPLICATION']);
$aStoredVarNames = array_keys($aFields['APP_DATA']);

$aVariables = Array();
for($i=0; $i<count($_SESSION['TRIGGER_DEBUG']['DATA']); $i++) {
  $aVariables[$_SESSION['TRIGGER_DEBUG']['DATA'][$i]['key']] = $_SESSION['TRIGGER_DEBUG']['DATA'][$i]['value'];
}

$aVariables = array_merge($aFields['APP_DATA'], $aVariables);
ksort($aVariables);

//print_r($aVariables);
$return_object->totalCount=1;
$return_object->data[0]=$aVariables;

echo json_encode($return_object);
//echo '{"totalCount":1,"data":[{"param1":"173","param2":"923","param3":"value param3"}]}';