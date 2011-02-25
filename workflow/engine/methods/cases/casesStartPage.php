<?php
unset($_SESSION['__currentTabDashboard']);
if(isset($_GET['action'])){
    $_SESSION['__currentTabDashboard']=$_GET['action'];
}
$page="";
if(isset($_GET['action'])){
    $page=$_GET['action'];
}

$oHeadPublisher =& headPublisher::getSingleton();
//$oHeadPublisher->setExtSkin( 'xtheme-gray');
//$oHeadPublisher->usingExtJs('ux/TabCloseMenu');



//$oHeadPublisher->usingExtJs('ux/ColumnHeaderGroup');

//G::pr($TRANSLATIONS_STARTCASE);
//print "<br /><br /><br /><br /><center><img src='/images/gears.gif' valign='absmiddle' width='35'><br />".G::LoadTranslation('ID_LOADING')."</center>";

$loadingHTML ='
  <style>
    #loading{
        left:40%;
        top:37%;
        padding:2px;
        height:auto;
    }
    
    #loading .loading-indicator{
        background:white;
        color:#444;
        font:bold 13px tahoma,arial,helvetica;
        padding:10px;
        margin:0;
        height:auto;
    }
    #loading-msg {
        font: bold 11px arial,tahoma,sans-serif;
    }
    
  </style>

 
  <div id="loading">
    <div class="loading-indicator">
      <center>
        <img src="/images/gears.gif" valign="absmiddle" width="35"><br />
        <span id="loading-msg">'.G::LoadTranslation('ID_LOADING').'</span><br />
              </center>
    </div>
  </div>
';
print_r($loadingHTML);

switch($page){
    case "startCase":

        $labels = G::getTranslations(Array(
        'ID_FIND_A_PROCESS',
				'ID_PROCESS_INFORMATION', 'ID_PROCESS', 'ID_TASK', 'ID_DESCRIPTION', 'ID_CATEGORY',
        'ID_GENERAL_PROCESS_NUMBERS', 'ID_INBOX', 'ID_DRAFT', 'ID_COMPLETED', 'ID_CANCELLED', 'ID_TOTAL_CASES',
        'ID_CALENDAR', 'ID_CALENDAR_DESCRIPTION', 'ID_WORKING_DAYS', 'ID_DEBUG_MODE',
        'ID_SUN', 'ID_MON', 'ID_TUE', 'ID_WEN', 'ID_THU', 'ID_FRI', 'ID_SAT',
        'ID_TITLE_START_CASE', 'ID_STARTING_NEW_CASE', 'ID_ERROR_CREATING_NEW_CASE',
        'ID_ERROR', 'ID_UNABLE_START_CASE'
        ));
        $oHeadPublisher->assign('TRANSLATIONS', $labels);

        $oHeadPublisher->usingExtJs('ux.treefilterx/Ext.ux.tree.TreeFilterX');
        $oHeadPublisher->addExtJsScript('cases/casesStartCase', false);    //adding a javascript file .js

        $oHeadPublisher->addContent( 'cases/casesStartCase'); //adding a html file  .html.
        break;
    case "documents":
        
        G::LoadClass('configuration');
        $c = new Configurations();
        $configPage = $c->getConfiguration('documentsModule', 'pageSize','',$_SESSION['USER_LOGGED']);
        $configEnv = $c->getConfiguration('ENVIRONMENT_SETTINGS', '');
        $Config['pageSize'] = isset($configPage['pageSize']) ? $configPage['pageSize'] : 20;
        //$Config['fullNameFormat'] = isset($configEnv['format']) ? $configEnv['format'] : '@userName';
        //$Config['dateFormat'] = isset($configEnv['dateFormat']) ? $configEnv['dateFormat'] : 'Y-m-d';
        $oHeadPublisher->assign('CONFIG', $Config);
        $oHeadPublisher->assign('FORMATS',$c->getFormats());
        

        $oHeadPublisher->usingExtJs('ux.locationbar/Ext.ux.LocationBar');
        $oHeadPublisher->usingExtJs('ux.statusbar/ext-statusbar');

        $oHeadPublisher->addExtJsScript('cases/casesDocuments', false);    //adding a javascript file .js
        $oHeadPublisher->addContent( 'cases/casesDocuments'); //adding a html file  .html.
        break;
    default:

        $oHeadPublisher->usingExtJs('ux.treefilterx/Ext.ux.tree.TreeFilterX');

        $oHeadPublisher->usingExtJs('ux.locationbar/Ext.ux.LocationBar');
        $oHeadPublisher->usingExtJs('ux.statusbar/ext-statusbar');
        $oHeadPublisher->addExtJsScript('cases/casesStartPage', false);    //adding a javascript file .js
        $oHeadPublisher->addContent( 'cases/casesStartPage'); //adding a html file  .html.
        break;

}


G::RenderPage('publish', 'extJs');