<?
/**
 * tracker.php
 *
 */

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
    $smarty->display('blank.html');
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
      $footer .= "<br />Copyright © 2003-2008 Colosa, Inc. All rights reserved.";
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
    $smarty->display('tracker.html');
  }
  
