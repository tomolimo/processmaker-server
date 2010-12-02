<?
/**
 * green.php
 *
 */
if (! defined('DB_SYSTEM_INFORMATION'))
    define('DB_SYSTEM_INFORMATION', 1);

G::verifyPath(PATH_SMARTY_C, true);
G::verifyPath(PATH_SMARTY_CACHE, true);

// put full path to Smarty.class.php
require_once (PATH_THIRDPARTY . 'smarty/libs/Smarty.class.php');

$smarty = new Smarty();

$smarty->template_dir = PATH_SKINS;
$smarty->compile_dir = PATH_SMARTY_C;
$smarty->cache_dir = PATH_SMARTY_CACHE;
$smarty->config_dir = PATH_THIRDPARTY . 'smarty/configs';

$oHeadPublisher = & headPublisher::getSingleton();
global $G_ENABLE_BLANK_SKIN;

if (isset($G_ENABLE_BLANK_SKIN) && $G_ENABLE_BLANK_SKIN) {
    $smarty->display('blank.html');
} else {
    
    $header = '';
    if (isset($oHeadPublisher)) {
        $oHeadPublisher->title = isset($_SESSION['USR_USERNAME']) ? '(' . $_SESSION['USR_USERNAME'] . ' ' . G::LoadTranslation('ID_IN') . ' ' . SYS_SYS . ')' : '';
        $header = $oHeadPublisher->printHeader();
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
    $smarty->display('green-submenu.html');
}
