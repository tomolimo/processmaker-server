<?php

if (! isset ( $_REQUEST ['action'] )) {
  $ruturn ['success'] = 'failure';
  $ruturn ['message'] = 'You may request an action';
  print json_encode ( $ruturn );
  die ();
}
if (! function_exists ( $_REQUEST ['action'] )) {
  $ruturn ['success'] = 'failure';
  $ruturn ['message'] = 'The requested action doesn\'t exists';
  print json_encode ( $ruturn );
  die ();
}

$functionName = $_REQUEST ['action'];
//var_dump($functionName);
$functionParams = isset($_REQUEST ['params'] ) ? $_REQUEST ['params'] : array ();

$functionName ( $functionParams );

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
  G::pr($params);
  $oPluginRegistry =& PMPluginRegistry::getSingleton();
   $activePluginsForCaseScheduler=$oPluginRegistry->getCaseSchedulerPlugins();

   foreach($activePluginsForCaseScheduler as $key => $caseSchedulerPluginDetail){
    if(($caseSchedulerPluginDetail->sNamespace==$params[0])&&($caseSchedulerPluginDetail->sActionId==$params[1])){
      //Render the form
      G::pr($caseSchedulerPluginDetail);
      $caseSchedulerForm=$caseSchedulerPluginDetail->sActionForm;
    }    
    
  }
  if($caseSchedulerForm!=""){
        try {
    //the setup page is a special page
    if ( substr($caseSchedulerForm,-4) == '.php' && file_exists ( PATH_PLUGINS . $caseSchedulerForm ) ) {
        require_once ( PATH_PLUGINS . $caseSchedulerForm  );
        die;
    }
        
    //the setup page is a xmlform and using the default showform and saveform function to serialize data
    if ( !file_exists ( PATH_PLUGINS.$caseSchedulerForm.'.xml' ) ) throw ( new Exception ('Error') );
  
  
    $Fields = array();
    $G_PUBLISH->AddContent( 'xmlform', 'xmlform', $xmlform, '',$Fields ,'pluginsSetupSave?id='.$pluginFile );  
  }
  catch ( Exception $e ){
    $aMessage['MESSAGE'] = $e->getMessage();
    $G_PUBLISH->AddContent('xmlform', 'xmlform', 'login/showMessage', '', $aMessage );
  }
  G::RenderPage('publishBlank', 'blank');
  }
  
}
?>