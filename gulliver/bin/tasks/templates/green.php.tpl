<?php
/**
 * green.php
 *
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

  global $G_ENABLE_BLANK_SKIN;

  if ( isset($G_ENABLE_BLANK_SKIN) && $G_ENABLE_BLANK_SKIN ) {
    $smarty->display('blank.html');
  }
  else {
    $oHeadPublisher = headPublisher::getSingleton();
    if (isset($oHeadPublisher)) $header = $oHeadPublisher->printHeader();
	  $smarty->assign('username', (isset($_SESSION['USR_USERNAME']) ? '(' . $_SESSION['USR_USERNAME'] . ' ' . G::LoadTranslation('ID_IN') . ' ' . SYS_SYS . ')' : '') );
  	$smarty->assign('header', $header );
	  $smarty->assign('tpl_menu', PATH_TEMPLATE . 'menu.html' );
	  $smarty->assign('tpl_submenu', PATH_TEMPLATE . 'submenu.html' );
    $smarty->display('green.html');
  }
