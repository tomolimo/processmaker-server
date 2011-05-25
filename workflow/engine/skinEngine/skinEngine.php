<?php

global $G_SKIN;
global $G_SKIN_MAIN;

if((!isset($G_SKIN))||($G_SKIN=="")){
    $G_SKIN="classic";
}
if($G_SKIN=="green-submenu") $G_SKIN = "submenu";
$skinVariants=array('blank','extjs','raw','tracker','submenu');
$forceTemplateCompile=true;
if(!(in_array(strtolower($G_SKIN), $skinVariants))){
    //Only save in session the main SKIN
    $forceTemplateCompile=true;
    if((isset($_SESSION['currentSkin']))&&($_SESSION['currentSkin']!=$G_SKIN)){
        $forceTemplateCompile=true;
    }
    $_SESSION['currentSkin']=$G_SKIN;

}else{
    $_SESSION['currentSkinVariant']=$G_SKIN;
}
if(!(isset($_SESSION['currentSkin']))) $_SESSION['currentSkin'] = "classic";
$G_SKIN_MAIN=$_SESSION['currentSkin'];

//Set defaults "classic"
$configurationFile    =    G::ExpandPath( "skinEngine" ).'base'.PATH_SEP.'config.xml';
$layoutFile           =    G::ExpandPath( "skinEngine" ).'base'.PATH_SEP.'layout.html';
$layoutFileBlank      =    G::ExpandPath( "skinEngine" ).'base'.PATH_SEP.'layout-blank.html';
$layoutFileExtjs      =    G::ExpandPath( "skinEngine" ).'base'.PATH_SEP.'layout-extjs.html';
$layoutFileRaw        =    G::ExpandPath( "skinEngine" ).'base'.PATH_SEP.'layout-raw.html';
$layoutFileTracker    =    G::ExpandPath( "skinEngine" ).'base'.PATH_SEP.'layout-tracker.html';
$layoutFileSubmenu    =    G::ExpandPath( "skinEngine" ).'base'.PATH_SEP.'layout-submenu.html';


//Based on requested Skin look if there is any registered with that name
if(strtolower($G_SKIN_MAIN)!="classic"){
    if((file_exists(PATH_CUSTOM_SKINS.$G_SKIN_MAIN))&&(is_dir(PATH_CUSTOM_SKINS.$G_SKIN_MAIN))){
        //This should have an XML definition and a layout html
        $skinObject=PATH_CUSTOM_SKINS.$G_SKIN_MAIN;
        $sw_config=file_exists ($skinObject.PATH_SEP.'config.xml');
        $sw_layout=file_exists ($skinObject.PATH_SEP.'layout.html');
        if ($sw_config && $sw_layout ) {
            $configurationFile    =    $skinObject.PATH_SEP.'config.xml';
            $layoutFile           =    $skinObject.PATH_SEP.'layout.html';
            if(file_exists ($skinObject.PATH_SEP.'layout-blank.html')){
                $layoutFileBlank      =    $skinObject.PATH_SEP.'layout-blank.html';
            }
            if(file_exists ($skinObject.PATH_SEP.'layout-extjs.html')){
                $layoutFileExtjs      =   $skinObject.PATH_SEP.'layout-extjs.html' ;
            }
            if(file_exists ($skinObject.PATH_SEP.'layout-raw.html')){
                $layoutFileRaw        =    $skinObject.PATH_SEP.'layout-raw.html';
            }
            if(file_exists ($skinObject.PATH_SEP.'layout-tracker.html')){
                $layoutFileTracker    =    $skinObject.PATH_SEP.'layout-tracker.html';
            }
            if(file_exists ($skinObject.PATH_SEP.'layout-submenu.html')){
                $layoutFileSubmenu    =    $skinObject.PATH_SEP.'layout-submenu.html';
            }

        }else{
            //define a error message.. but continue and show a smooth message
            $G_SKIN_MAIN="classic";

        }
    }else{
        //Skin doesn't exist
        $G_SKIN_MAIN="classic";
    }

}

$layoutFile = pathInfo($layoutFile);
$layoutFileBlank = pathInfo($layoutFileBlank);
$layoutFileExtjs = pathInfo($layoutFileExtjs);
$layoutFileTracker = pathInfo($layoutFileTracker);
$layoutFileRaw  = pathInfo($layoutFileRaw);
$layoutFileSubmenu  = pathInfo($layoutFileSubmenu);

$cssFileName=$G_SKIN_MAIN;
if(($G_SKIN!=$G_SKIN_MAIN)&&(in_array(strtolower($G_SKIN), $skinVariants))){
    $cssFileName.="-".$G_SKIN;
}



if(isset($_GET['debug'])){
//if(true){
    //Render
    print "Requested Skin: $G_SKIN<br />";
    print "Main Skin: ".$G_SKIN_MAIN;

    print "Rendering... <br />";
    print "<b>Configuration file:</b> $configurationFile";
    print "<br />";
    print "<b>layout file:</b>"; G::pr($layoutFile);
    print "<br />";
    print "<b>layout Blank file:</b>"; G::pr($layoutFileBlank);
    print "<br />";
    print "<b>layout ExtJs file:</b>"; G::pr($layoutFileExtjs);
    print "<br />";
    print "<b>layout Raw file:</b>"; G::pr($layoutFileRaw);
    print "<br />";
    print "<b>layout Tracker file:</b>"; G::pr($layoutFileTracker);
    print "<br />";
    print "<b>layout submenu file:</b>"; G::pr($layoutFileSubmenu);

}

switch(strtolower($G_SKIN)){
    case "blank"://This is a special template but need main skin styles
        G::verifyPath ( PATH_SMARTY_C,     true );
        G::verifyPath ( PATH_SMARTY_CACHE, true );

        // put full path to Smarty.class.php
        require_once(PATH_THIRDPARTY . 'smarty/libs/Smarty.class.php');


        $smarty = new Smarty();

        $smarty->template_dir = $layoutFileBlank['dirname'];
        $smarty->compile_dir  = PATH_SMARTY_C;
        $smarty->cache_dir    = PATH_SMARTY_CACHE;
        $smarty->config_dir   = PATH_THIRDPARTY . 'smarty/configs';

        $oHeadPublisher =& headPublisher::getSingleton();
        if (isset($oHeadPublisher)){
            $header = $oHeadPublisher->printHeader();
            $header .= $oHeadPublisher->getExtJsStylesheets($cssFileName);
        }
        $smarty->assign('username', (isset($_SESSION['USR_USERNAME']) ? '(' . $_SESSION['USR_USERNAME'] . ' ' . G::LoadTranslation('ID_IN') . ' ' . SYS_SYS . ')' : '') );
        $smarty->assign('header', $header );
        //$smarty->assign('tpl_menu', PATH_TEMPLATE . 'menu.html' );
        //$smarty->assign('tpl_submenu', PATH_TEMPLATE . 'submenu.html' );
        $smarty->force_compile=$forceTemplateCompile;
        $smarty->display($layoutFileBlank['basename']);
        break;
     case "submenu"://This is a special template but need main skin styles
if (! defined('DB_SYSTEM_INFORMATION'))
    define('DB_SYSTEM_INFORMATION', 1);

G::verifyPath(PATH_SMARTY_C, true);
G::verifyPath(PATH_SMARTY_CACHE, true);

// put full path to Smarty.class.php
require_once (PATH_THIRDPARTY . 'smarty/libs/Smarty.class.php');

$smarty = new Smarty();

$smarty->template_dir = $layoutFileSubmenu['dirname'];
$smarty->compile_dir = PATH_SMARTY_C;
$smarty->cache_dir = PATH_SMARTY_CACHE;
$smarty->config_dir = PATH_THIRDPARTY . 'smarty/configs';

$oHeadPublisher = & headPublisher::getSingleton();
global $G_ENABLE_BLANK_SKIN;

if (isset($G_ENABLE_BLANK_SKIN) && $G_ENABLE_BLANK_SKIN) {
    $smarty->display($layoutFileBlank['basename']);
} else {
    
    $header = '';
    if (isset($oHeadPublisher)) {
        $oHeadPublisher->title = isset($_SESSION['USR_USERNAME']) ? '(' . $_SESSION['USR_USERNAME'] . ' ' . G::LoadTranslation('ID_IN') . ' ' . SYS_SYS . ')' : '';
        $header = $oHeadPublisher->printHeader();
        $header .= $oHeadPublisher->getExtJsStylesheets($cssFileName);
    }
    $footer = '';
    if (strpos($_SERVER['REQUEST_URI'], '/login/login') !== false) {
        if (DB_SYSTEM_INFORMATION == 1) {
            $footer = "<a href=\"#\" onclick=\"openInfoPanel();return false;\" class=\"FooterLink\">| System Information |</a><br />";
        }
        
        $freeOfChargeText = "";
        if (! defined('SKIP_FREE_OF_CHARGE_TEXT'))
            $freeOfChargeText = "Supplied free of charge with no support, certification, warranty, <br>maintenance nor indemnity by Colosa and its Certified Partners.";
        $footer .= "<br />Copyright &copy; 2003-" . date('Y') . " <a href=\"http://www.colosa.com\" alt=\"Colosa, Inc.\" target=\"_blank\">Colosa, Inc.</a> All rights reserved.<br /> $freeOfChargeText " . "<br><br/><a href=\"http://www.processmaker.com\" alt=\"Powered by ProcessMaker - Open Source Workflow & Business Process Management (BPM) Management Software\" title=\"Powered by ProcessMaker\" target=\"_blank\"><img src=\"/images/PowerdbyProcessMaker.png\" border=\"0\" /></a>";
    }
    
    //menu
    global $G_MAIN_MENU;
    global $G_SUB_MENU;
    global $G_MENU_SELECTED;
    global $G_SUB_MENU_SELECTED;
    global $G_ID_MENU_SELECTED;
    global $G_ID_SUB_MENU_SELECTED;
    
    $oMenu = new Menu();
    $menus = $oMenu->generateArrayForTemplate($G_MAIN_MENU, 'SelectedMenu', 'mainMenu', $G_MENU_SELECTED, $G_ID_MENU_SELECTED);
    $smarty->assign('menus', $menus);
    
    $oSubMenu = new Menu();
    $subMenus = $oSubMenu->generateArrayForTemplate($G_SUB_MENU, 'selectedSubMenu', 'subMenu', $G_SUB_MENU_SELECTED, $G_ID_SUB_MENU_SELECTED);
    $smarty->assign('subMenus', $subMenus);
    
    if (! defined('NO_DISPLAY_USERNAME')) {
        define('NO_DISPLAY_USERNAME', 0);
    }
    if (NO_DISPLAY_USERNAME == 0) {
        $smarty->assign('userfullname', isset($_SESSION['USR_FULLNAME']) ? $_SESSION['USR_FULLNAME'] : '');
        $smarty->assign('user', isset($_SESSION['USR_USERNAME']) ? '(' . $_SESSION['USR_USERNAME'] . ')' : '');
        $smarty->assign('rolename', isset($_SESSION['USR_ROLENAME']) ? $_SESSION['USR_ROLENAME'] . '' : '');
        $smarty->assign('pipe', isset($_SESSION['USR_USERNAME']) ? ' | ' : '');
        $smarty->assign('logout', G::LoadTranslation('ID_LOGOUT'));
        $smarty->assign('workspace', defined('SYS_SYS')?SYS_SYS: '');
        $uws = (isset($_SESSION['USR_ROLENAME']) && $_SESSION['USR_ROLENAME'] != '')? strtolower(G::LoadTranslation('ID_WORKSPACE_USING')): G::LoadTranslation('ID_WORKSPACE_USING');
        $smarty->assign('workspace_label', $uws);
        $smarty->assign('udate', G::getformatedDate(date('Y-m-d'), 'M d, yyyy', SYS_LANG));
        
    }
    if (defined('SYS_SYS'))
        $logout = '/sys' . SYS_SYS . '/' . SYS_LANG . '/' . SYS_SKIN . '/login/login';
    else
        $logout = '/sys/' . SYS_LANG . '/' . SYS_SKIN . '/login/login';
    $smarty->assign('linklogout', $logout);
    $smarty->assign('header', $header);
    $smarty->assign('footer', $footer);
    $smarty->assign('tpl_menu', PATH_TEMPLATE . 'menu.html');
    $smarty->assign('tpl_submenu', PATH_TEMPLATE . 'submenu.html');
    
    if (class_exists('PMPluginRegistry')) {
        $oPluginRegistry = &PMPluginRegistry::getSingleton();
        $sCompanyLogo = $oPluginRegistry->getCompanyLogo('/images/processmaker.logo.jpg');
    } else
        $sCompanyLogo = '/images/processmaker.logo.jpg';
    
    $smarty->assign('logo_company', $sCompanyLogo);
    $smarty->display($layoutFileSubmenu['basename']);
}
        break;
     case "raw"://This is a special template but need main skin styles
        G::verifyPath ( PATH_SMARTY_C,     true );
        G::verifyPath ( PATH_SMARTY_CACHE, true );

        // put full path to Smarty.class.php
        require_once(PATH_THIRDPARTY . 'smarty/libs/Smarty.class.php');


        $smarty = new Smarty();

        $smarty->template_dir = $layoutFileRaw['dirname'];
        $smarty->compile_dir  = PATH_SMARTY_C;
        $smarty->cache_dir    = PATH_SMARTY_CACHE;
        $smarty->config_dir   = PATH_THIRDPARTY . 'smarty/configs';

        $oHeadPublisher =& headPublisher::getSingleton();
        if (isset($oHeadPublisher)) $header = $oHeadPublisher->printRawHeader();
        $smarty->assign('header', $header );
        $smarty->force_compile=$forceTemplateCompile;
        $smarty->display($layoutFileRaw['basename']);
        break;
    case "extjs"://This is a special template but need main skin styles
        G::LoadClass('serverConfiguration');
        $oServerConf =& serverConf::getSingleton();

        $oHeadPublisher =& headPublisher::getSingleton();

        /*$extSkin=$oServerConf->getProperty("extSkin");
         if(isset($extSkin[SYS_SKIN])){
         $oHeadPublisher->setExtSkin( $extSkin[SYS_SKIN]);
         }*/

        if( $oHeadPublisher->extJsInit === true){
            $header = $oHeadPublisher->getExtJsVariablesScript();
            $styles = $oHeadPublisher->getExtJsStylesheets($cssFileName);
            $body   = $oHeadPublisher->getExtJsScripts();

            $templateFile = G::ExpandPath( "skinEngine" ).'base'.PATH_SEP .'extJsInitLoad.html';
        }
        else {
            $header = $oHeadPublisher->getExtJsStylesheets($cssFileName);
            $header .= $oHeadPublisher->includeExtJs();
            $styles = "";
            $body   = $oHeadPublisher->renderExtJs();

            $templateFile = $layoutFile['dirname'].PATH_SEP.$layoutFileExtjs['basename'];
        }
        $template = new TemplatePower(  $templateFile );
        $template->prepare();
        $template->assign( 'header', $header );
        $template->assign( 'styles', $styles );
        $template->assign( 'bodyTemplate', $body);
        $content = $template->getOutputContent();

        print $content;
        break;
    case "tracker"://This is a special template but need main skin styles
        G::verifyPath ( PATH_SMARTY_C,     true );
        G::verifyPath ( PATH_SMARTY_CACHE, true );

        // put full path to Smarty.class.php
        require_once(PATH_THIRDPARTY . 'smarty/libs/Smarty.class.php');


        $smarty = new Smarty();

        $smarty->template_dir = PATH_SKINS;
        $smarty->compile_dir  = PATH_SMARTY_C;
        $smarty->cache_dir    = PATH_SMARTY_CACHE;
        $smarty->config_dir   = PATH_THIRDPARTY . 'smarty/configs';

        $oHeadPublisher =& headPublisher::getSingleton();
        global $G_ENABLE_BLANK_SKIN;

        if ( isset($G_ENABLE_BLANK_SKIN) && $G_ENABLE_BLANK_SKIN ) {
            $smarty->force_compile=$forceTemplateCompile;
            $smarty->display($layoutFileBlank['basename']);
        }
        else {
             
            $header = '';
            if (isset($oHeadPublisher)) {
                $oHeadPublisher->title = isset($_SESSION['USR_USERNAME']) ? '(' . $_SESSION['USR_USERNAME'] . ' ' . G::LoadTranslation('ID_IN') . ' ' . SYS_SYS . ')' : '';
                $header = $oHeadPublisher->printHeader();
            }
            $footer = '';
            if (strpos($_SERVER['REQUEST_URI'], '/login/login') !== false) {
                if ( defined('SYS_SYS') ) {
                    $footer = "<a href=\"#\" onclick=\"openInfoPanel();return false;\" class=\"FooterLink\">| System Information |</a><br />";
                }
                $footer .= "<br />Copyright � 2003-2008 Colosa, Inc. All rights reserved.";
            }

            //menu
            global $G_MAIN_MENU;
            global $G_SUB_MENU;
            global $G_MENU_SELECTED;
            global $G_SUB_MENU_SELECTED;
            global $G_ID_MENU_SELECTED;
            global $G_ID_SUB_MENU_SELECTED;

            $oMenu = new Menu();
            $menus = $oMenu->generateArrayForTemplate ( $G_MAIN_MENU,'SelectedMenu', 'mainMenu',$G_MENU_SELECTED, $G_ID_MENU_SELECTED );
            $smarty->assign('menus', $menus  );

            $oSubMenu = new Menu();
            $subMenus = $oSubMenu->generateArrayForTemplate ( $G_SUB_MENU,'selectedSubMenu', 'subMenu',$G_SUB_MENU_SELECTED, $G_ID_SUB_MENU_SELECTED );
            $smarty->assign('subMenus', $subMenus  );

            $smarty->assign('user',   isset($_SESSION['USR_USERNAME']) ? $_SESSION['USR_USERNAME'] : '');
            $smarty->assign('pipe',   isset($_SESSION['USR_USERNAME']) ? ' | ' : '');
            $smarty->assign('logout', G::LoadTranslation('ID_LOGOUT'));
            $smarty->assign('header', $header );
            $smarty->assign('tpl_menu', PATH_TEMPLATE . 'menu.html' );
            $smarty->assign('tpl_submenu', PATH_TEMPLATE . 'submenu.html' );

            if (class_exists('PMPluginRegistry')) {
                $oPluginRegistry = &PMPluginRegistry::getSingleton();
                $sCompanyLogo = $oPluginRegistry->getCompanyLogo ( '/images/processmaker.logo.jpg' );
            }
            else
            $sCompanyLogo = '/images/processmaker.logo.jpg';

            $smarty->assign('logo_company', $sCompanyLogo );
            $smarty->force_compile=$forceTemplateCompile;
            $smarty->display($layoutFileTracker['basename']);
        }
        break;
    default://Render a common page
        if (! defined('DB_SYSTEM_INFORMATION'))
        define('DB_SYSTEM_INFORMATION', 1);

        G::verifyPath(PATH_SMARTY_C, true);
        G::verifyPath(PATH_SMARTY_CACHE, true);

        // put full path to Smarty.class.php
        require_once (PATH_THIRDPARTY . 'smarty/libs/Smarty.class.php');

        $smarty = new Smarty();


        $smarty->compile_dir = PATH_SMARTY_C;
        $smarty->cache_dir = PATH_SMARTY_CACHE;
        $smarty->config_dir = PATH_THIRDPARTY . 'smarty/configs';

        $oHeadPublisher = & headPublisher::getSingleton();

        global $G_ENABLE_BLANK_SKIN;

        //To setup en extJS Theme for this Skin
        G::LoadClass('serverConfiguration');
        $oServerConf =& serverConf::getSingleton();
        $extSkin=$oServerConf->getProperty("extSkin");
        if(!$extSkin) $extSkin=array();
        $extSkin[SYS_SKIN]="xtheme-gray";
        $oServerConf->setProperty("extSkin",$extSkin);
        //End of extJS Theme setup

        //G::pr($oHeadPublisher);
        if (isset($G_ENABLE_BLANK_SKIN) && $G_ENABLE_BLANK_SKIN) {
            $smarty->template_dir = $layoutFileBlank['dirname'];
            $smarty->force_compile=$forceTemplateCompile;
            $smarty->display($layoutFileBlank['basename']);
        }
        else {
            $smarty->template_dir = $layoutFile['dirname'];

            $header = '';
            if (isset($oHeadPublisher)) {
                $oHeadPublisher->title = isset($_SESSION['USR_USERNAME']) ? '(' . $_SESSION['USR_USERNAME'] . ' ' . G::LoadTranslation('ID_IN') . ' ' . SYS_SYS . ')' : '';
                $header = $oHeadPublisher->printHeader();
                $header .= $oHeadPublisher->getExtJsStylesheets($cssFileName);

            }
            $footer = '';
            if (strpos($_SERVER['REQUEST_URI'], '/login/login') !== false) {
                if (DB_SYSTEM_INFORMATION == 1) {
                    $footer = "<a href=\"#\" onclick=\"openInfoPanel();return false;\" class=\"FooterLink\">| System Information |</a><br />";
                }

                $freeOfChargeText = "";
                if (! defined('SKIP_FREE_OF_CHARGE_TEXT'))
                $freeOfChargeText = "Supplied free of charge with no support, certification, warranty, <br>maintenance nor indemnity by Colosa and its Certified Partners.";
                if(class_exists('pmLicenseManager')) $freeOfChargeText="";
                $footer .= "<br />Copyright &copy; 2003-" . date('Y') . " <a href=\"http://www.colosa.com\" alt=\"Colosa, Inc.\" target=\"_blank\">Colosa, Inc.</a> All rights reserved.<br /> $freeOfChargeText " . "<br><br/><a href=\"http://www.processmaker.com\" alt=\"Powered by ProcessMaker - Open Source Workflow & Business Process Management (BPM) Management Software\" title=\"Powered by ProcessMaker\" target=\"_blank\"><img src=\"/images/PowerdbyProcessMaker.png\" border=\"0\" /></a>";
            }

            //menu
            global $G_MAIN_MENU;
            global $G_SUB_MENU;
            global $G_MENU_SELECTED;
            global $G_SUB_MENU_SELECTED;
            global $G_ID_MENU_SELECTED;
            global $G_ID_SUB_MENU_SELECTED;

            $oMenu = new Menu();
            $menus = $oMenu->generateArrayForTemplate($G_MAIN_MENU, 'SelectedMenu', 'mainMenu', $G_MENU_SELECTED, $G_ID_MENU_SELECTED);
            $smarty->assign('menus', $menus);

            $oSubMenu = new Menu();
            $subMenus = $oSubMenu->generateArrayForTemplate($G_SUB_MENU, 'selectedSubMenu', 'subMenu', $G_SUB_MENU_SELECTED, $G_ID_SUB_MENU_SELECTED);
            $smarty->assign('subMenus', $subMenus);

            if (! defined('NO_DISPLAY_USERNAME')) {
                define('NO_DISPLAY_USERNAME', 0);
            }
            if (NO_DISPLAY_USERNAME == 0) {
                $smarty->assign('userfullname', isset($_SESSION['USR_FULLNAME']) ? $_SESSION['USR_FULLNAME'] : '');
                $smarty->assign('user', isset($_SESSION['USR_USERNAME']) ? '(' . $_SESSION['USR_USERNAME'] . ')' : '');
                $smarty->assign('rolename', isset($_SESSION['USR_ROLENAME']) ? $_SESSION['USR_ROLENAME'] . '' : '');
                $smarty->assign('pipe', isset($_SESSION['USR_USERNAME']) ? ' | ' : '');
                $smarty->assign('logout', G::LoadTranslation('ID_LOGOUT'));
                $smarty->assign('workspace', defined('SYS_SYS')?SYS_SYS: '');
                $uws = (isset($_SESSION['USR_ROLENAME']) && $_SESSION['USR_ROLENAME'] != '')? strtolower(G::LoadTranslation('ID_WORKSPACE_USING')): G::LoadTranslation('ID_WORKSPACE_USING');
                $smarty->assign('workspace_label', $uws);
                $smarty->assign('udate', G::getformatedDate(date('Y-m-d'), 'M d, yyyy', SYS_LANG));

            }
            if(class_exists('pmLicenseManager')){
                $pmLicenseManagerO =& pmLicenseManager::getSingleton();
                $expireIn=$pmLicenseManagerO->getExpireIn();
                $expireInLabel=$pmLicenseManagerO->getExpireInLabel();
                //if($expireIn<=30){
                $smarty->assign('msgVer', '<br><label class="textBlack">'.$expireInLabel.'</label>&nbsp;&nbsp;');
                //}
            }

            if (defined('SYS_SYS'))
            $logout = '/sys' . SYS_SYS . '/' . SYS_LANG . '/' . SYS_SKIN . '/login/login';
            else
            $logout = '/sys/' . SYS_LANG . '/' . SYS_SKIN . '/login/login';

            $smarty->assign('linklogout', $logout);
            $smarty->assign('header', $header);
            $smarty->assign('footer', $footer);
            $smarty->assign('tpl_menu', PATH_TEMPLATE . 'menu.html');
            $smarty->assign('tpl_submenu', PATH_TEMPLATE . 'submenu.html');
            G::LoadClass( 'replacementLogo' );
            $oLogoR = new replacementLogo();
            if(defined("SYS_SYS")){
                $aFotoSelect   = $oLogoR->getNameLogo((isset($_SESSION['USER_LOGGED']))?$_SESSION['USER_LOGGED']:'');
                if ( is_array ( $aFotoSelect ) ) {
                    $sFotoSelect   = trim($aFotoSelect['DEFAULT_LOGO_NAME']);
                    $sWspaceSelect = trim($aFotoSelect['WORKSPACE_LOGO_NAME']);
                }
            }

            if (class_exists('PMPluginRegistry')) {
                $oPluginRegistry = &PMPluginRegistry::getSingleton();
                if ( isset($sFotoSelect) && $sFotoSelect!='' && !(strcmp($sWspaceSelect,SYS_SYS)) ){
                    $sCompanyLogo = $oPluginRegistry->getCompanyLogo($sFotoSelect);
                    $sCompanyLogo= "/sys".SYS_SYS."/".SYS_LANG."/".SYS_SKIN."/setup/showLogoFile.php?id=".G::encrypt($sCompanyLogo,"imagen");
                }
                else {
                    $sCompanyLogo = $oPluginRegistry->getCompanyLogo('/images/processmaker.logo.jpg');
                }
            }
            else {
                $sCompanyLogo = '/images/processmaker.logo.jpg';
            }

            $smarty->assign('logo_company', $sCompanyLogo);

            $smarty->force_compile=$forceTemplateCompile;
            $smarty->display($layoutFile['basename']);
        }
        break;
}
?>
