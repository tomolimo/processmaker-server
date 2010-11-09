<?php

if (! isset ( $_REQUEST ['action'] )) {
  $return ['success'] = 'failure';
  $return ['message'] = 'You may request an action';
  print json_encode ( $return );
  die ();
}
if (! function_exists ( $_REQUEST ['action'] )) {
  $return ['success'] = 'failure';
  $return ['message'] = 'The requested action doesn\'t exists';
  print json_encode ( $return );
  die ();
}

$functionName = $_REQUEST ['action'];
//var_dump($functionName);
$functionParams = isset($_REQUEST ['params'] ) ? $_REQUEST ['params'] : array ();

$functionName ( $functionParams );

function searchSavedJob($schUid){
  
}

function pluginsList(){
$oPluginRegistry =& PMPluginRegistry::getSingleton();
$activePluginsForCaseScheduler=$oPluginRegistry->getCaseSchedulerPlugins();
if(!empty($activePluginsForCaseScheduler)){
  echo '<select style="width: 300px;" name="form[CASE_SH_PLUGIN_UID]" id="form[CASE_SH_PLUGIN_UID]" class="module_app_input___gray" required="1" onChange="showPluginSelection(this.options[this.selectedIndex].value)">';
  echo "<option value=\"\">- Select -</option>";
  foreach($activePluginsForCaseScheduler as $key => $caseSchedulerPluginDetail){
    $sActionId=$caseSchedulerPluginDetail->sActionId;
    $sNamespace=$caseSchedulerPluginDetail->sNamespace;
    
    echo "<option value=\"".$sNamespace."--".$sActionId."\">".$sActionId."</option>";
  }
  echo '</select>';
    //G::pr($activePlugnsForCaseScheduler);
}
}
function pluginCaseSchedulerForm(){
  if(!isset($_REQUEST ['selectedOption'])) die;
  $G_PUBLISH = new Publisher;
  $params=explode("--",$_REQUEST ['selectedOption']);
  $oPluginRegistry =& PMPluginRegistry::getSingleton();
   $activePluginsForCaseScheduler=$oPluginRegistry->getCaseSchedulerPlugins();

   foreach($activePluginsForCaseScheduler as $key => $caseSchedulerPluginDetail){
    if(($caseSchedulerPluginDetail->sNamespace==$params[0])&&($caseSchedulerPluginDetail->sActionId==$params[1])){
      $caseSchedulerSelected=$caseSchedulerPluginDetail;
     
    }
  }
  if((isset($caseSchedulerSelected))&&(is_object($caseSchedulerSelected))){
    //Render the form
      $oData=array();
      $oPluginRegistry->executeMethod( $caseSchedulerPluginDetail->sNamespace, $caseSchedulerPluginDetail->sActionForm, $oData );   
  }
  
}
?>