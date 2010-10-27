<?php 

  $oHeadPublisher =& headPublisher::getSingleton();
  //$oHeadPublisher->setExtSkin( 'xtheme-blue');   
  //$oHeadPublisher->usingExtJs('ux/Ext.ux.codepress');
  
  $oHeadPublisher->addExtJsScript('setup/appCacheViewConf', true);    //adding a javascript file .js
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
  
  /*
  //get user Root from hash 
  
      PROPEL::Init ( PATH_METHODS.'dbConnections/rootDbConnections.php' ); 
      $con = Propel::getConnection("root");

      $appCache = new AppCacheView();
      $appCache->setPathToAppCacheFiles ( PATH_METHODS . 'setup' . PATH_SEP .'setupSchemas'. PATH_SEP );
      $appCache->checkGrantsForNormalUser();
      $appCache->checkAppCacheView();
      //$appCache->fillAppCacheView();
      $appCache->triggerAppDelegationInsert();
die;*/
  $oHeadPublisher->assign('appCacheViewEnabled', $appCacheViewEnabled);

  //$oHeadPublisher->assign('appCacheViewEngine', $appCacheViewEngine);
  
  G::RenderPage('publish', 'extJs');