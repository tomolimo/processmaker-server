<?
/**
 * raw.php
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
if (isset($oHeadPublisher)) $header = $oHeadPublisher->printRawHeader();
$smarty->assign('header', $header );
$smarty->display('raw.html');
