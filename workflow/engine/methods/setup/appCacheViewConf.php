<?php 

  $oHeadPublisher =& headPublisher::getSingleton();
  //$oHeadPublisher->setExtSkin( 'xtheme-blue');   
  //$oHeadPublisher->usingExtJs('ux/Ext.ux.codepress');
  
  $oHeadPublisher->addExtJsScript('setup/appCacheViewConf', true);    //adding a javascript file .js
  $oHeadPublisher->addContent('setup/appCacheViewConf'); //adding a html file  .html.
  
  G::LoadClass('serverConfiguration');
  $oServerConf =& serverConf::getSingleton();
  
  $appCacheViewEngine = $oServerConf->getProperty('APP_CACHE_VIEW_ENGINE');
  
  if( isset($appCacheViewEngine['status']) && $appCacheViewEngine['status'] == 'active'){
    $appCacheViewEnabled = true;
  } else {
    $appCacheViewEnabled = false;
  }
  
  $appCacheViewEngine = Array('erik'=>123);
  $oHeadPublisher->assign('appCacheViewEnabled', $appCacheViewEnabled);
  $oHeadPublisher->assign('appCacheViewEngine', $appCacheViewEngine);
  
  G::RenderPage('publish', 'extJs');