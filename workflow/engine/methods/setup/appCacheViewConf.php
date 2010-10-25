<?php 

  $oHeadPublisher =& headPublisher::getSingleton();
  //$oHeadPublisher->setExtSkin( 'xtheme-blue');   
  //$oHeadPublisher->usingExtJs('ux/Ext.ux.codepress');
  
  $oHeadPublisher->addExtJsScript('setup/appCacheViewConf', true);    //adding a javascript file .js
  $oHeadPublisher->addContent('setup/appCacheViewConf'); //adding a html file  .html.
  
  //G::LoadClass('serverConfiguration');
  //$oServerConf =& serverConf::getSingleton();
  G::loadClass('configuration');
  $oConf = new Configurations; 
  $oConf->loadConfig($x, 'APP_CACHE_VIEW_ENGINE','','','','');
  
  //g::dump($oConf->aConfig); die;
  
  if( isset($oConf->aConfig['LANG']) && isset($oConf->aConfig['STATUS']) && $oConf->aConfig['STATUS'] == 'active'){
    $appCacheViewEnabled = true;
  } else {
    $appCacheViewEnabled = false;
  }
  
  //verifying the grants for the db user
  $enoughGrants = false;
  
  //normal user
  G::LoadSystem('dbMaintenance');
  $randTable = "RAND_" . md5(rand());
  
  $dbMaintenance = new DataBaseMaintenance(DB_HOST, DB_USER, DB_PASS);
  $dbMaintenance->setDbName(DB_NAME);
  $dbMaintenance->connect();
  
  $dbUserType = 0;
  
  if( $dbMaintenance->query("CREATE TRIGGER $randTable AFTER UPDATE ON ".DB_NAME." FOR EACH ROW BEGIN INSERT INTO APPLICATION(APP_UID VALUES ('111' ); END;") !== false ){
    $dbMaintenance->query("DROP TRIGGER $randTable;");
    $enoughGrants = true;
    $dbUserType = 1;
  } else {
    $dbHash = @explode(SYSTEM_HASH, G::decrypt(HASH_INSTALLATION, SYSTEM_HASH));
    
    $dbMaintenance = new DataBaseMaintenance($dbHash[0], $dbHash[1], $dbHash[2]);
    $dbMaintenance->setDbName(DB_NAME);
    $dbMaintenance->connect();
    if( $dbMaintenance->query("CREATE TABLE $randTable (ID VARCHAR(32));") !== false ){
      $dbMaintenance->query("DROP TABLE $randTable;");
      $enoughGrants = true;
      $dbUserType = 2;
    }
  }
  //echo $dbMaintenance->error();
  //g::dump($enoughGrants);
  $oHeadPublisher->assign('appCacheViewEnabled', $appCacheViewEnabled);
  $oHeadPublisher->assign('enoughGrants', $enoughGrants);
  $oHeadPublisher->assign('dbUserType', $dbUserType);
  //$oHeadPublisher->assign('appCacheViewEngine', $appCacheViewEngine);
  
  G::RenderPage('publish', 'extJs');