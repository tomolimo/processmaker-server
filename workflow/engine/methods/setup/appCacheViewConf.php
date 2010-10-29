<?php 
//  header('Pragma: no-cache');
//  header('Cache-Control: no-store, no-cache, must-revalidate');
  
  $oHeadPublisher =& headPublisher::getSingleton();
  //$oHeadPublisher->setExtSkin( 'xtheme-blue');   
  //$oHeadPublisher->usingExtJs('ux/Ext.ux.codepress');
  
  $oHeadPublisher->addExtJsScript('setup/appCacheViewConf', false);    //adding a javascript file .js
  $oHeadPublisher->addContent('setup/appCacheViewConf'); //adding a html file  .html.
  
  require_once('classes/model/AppCacheView.php');
  G::loadClass('configuration');
  $oConf = new Configurations; 
  $oConf->loadConfig($x, 'APP_CACHE_VIEW_ENGINE','','','','');

  //first check about APP_CACHE_VIEW is enabled or not,   
  if ( isset($oConf->aConfig['LANG']) && isset($oConf->aConfig['STATUS']) && $oConf->aConfig['STATUS'] == 'active'){
    $appCacheViewEnabled = true;
  } 
  else {
    $appCacheViewEnabled = false;
  }
  $lang = isset($oConf->aConfig['LANG']) ? $oConf->aConfig['LANG'] : 'en';
  
  //$oHeadPublisher->assign('appCacheViewEnabled', $appCacheViewEnabled);
  $TRANSLATIONS->ID_PROCESSING               = G::LoadTranslation('ID_PROCESSING'); 		 			
  $oHeadPublisher->assign( 'TRANSLATIONS',   $TRANSLATIONS); //translations
  $oHeadPublisher->assign( 'currentLang',    $lang);  //current language  
  
  G::RenderPage('publish', 'extJs');