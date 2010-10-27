<?php
  require_once('classes/model/AppCacheView.php');

  $request = isset($_POST['request'])? $_POST['request']: (isset($_GET['request'])? $_GET['request']: null);
  
  switch($request){
    //check if the APP_CACHE VIEW table and their triggers are installed
    case 'info':
      $result = new stdClass();
      $result->info = Array();
      
      //check the language, if no info in config about language, the default is 'en'
      G::loadClass('configuration');
      $oConf = new Configurations; 
      $oConf->loadConfig($x, 'APP_CACHE_VIEW_ENGINE','','','','');
      $appCacheViewEngine = $oConf->aConfig;
      
      if( isset($appCacheViewEngine['LANG']) ) {
        $lang   = $appCacheViewEngine['LANG'];
        $status = strtoupper($appCacheViewEngine['STATUS']);
      } 
      else {
        $confParams = Array(
          'LANG' => 'en',
          'STATUS'=> ''
        );
        $oConf->aConfig = $confParams;
        $oConf->saveConfig('APP_CACHE_VIEW_ENGINE', '', '', '');      	
        $lang = 'en';
        $status = '';
      }

      //get user Root from hash 
      PROPEL::Init ( PATH_METHODS.'dbConnections/rootDbConnections.php' ); 
      $con = Propel::getConnection("root");

      //setup the appcacheview object, and the path for the sql files
      $appCache = new AppCacheView();
      $appCache->setPathToAppCacheFiles ( PATH_METHODS . 'setup' . PATH_SEP .'setupSchemas'. PATH_SEP );
      
      $res = $appCache->getMySQLVersion();
      $result->info[] = array ('name' => 'MySQL Version',   'value'=> $res );
      
      $res = $appCache->checkGrantsForUser( false );
      $currentUser        = $res['user'];
      $currentUserIsSuper = $res['super'];
      $result->info[] = array ('name' => 'Current User',    'value'=> $currentUser );
      $result->info[] = array ('name' => 'SUPER privilege', 'value'=> $currentUserIsSuper );
      
      //if user does not have the SUPER privilege we need to use the root user and grant the SUPER priv. to normal user.
      if ( ! $currentUserIsSuper ) {
        $res = $appCache->checkGrantsForUser( true );
        $result->info[] = array ('name' => 'Root User',       'value'=> $res['user'] );
        $result->info[] = array ('name' => 'Has SUPER privilege', 'value'=> $res['super'] );

        $res = $appCache->setSuperForUser( $currentUser );
        $result->info[] = array ('name' => 'setting SUPER privilege',       'value'=> $res);
        $currentUserIsSuper = true;
      }

      //now check if table APPCACHEVIEW exists, and it have correct number of fields, etc.      
      $res = $appCache->checkAppCacheView();
      $result->info[] = array ('name' => 'Table APP_CACHE_VIEW',           'value'=> $res['found']);
      if ( $res['recreated'] ) 
        $result->info[] = array ('name' => 'Table APP_CACHE_VIEW recreated', 'value'=> $res['recreated']);
        
      $result->info[] = array ('name' => 'Rows in APP_CACHE_VIEW',       'value'=> $res['count']);
      
      //now check if we have the triggers installed
      //APP_DELEGATION INSERT 
      $res = $appCache->triggerAppDelegationInsert();
      $result->info[] = array ('name' => 'Trigger APP_DELEGATION INSERT',           'value'=> $res);

      //APP_DELEGATION Update 
      $res = $appCache->triggerAppDelegationUpdate();
      $result->info[] = array ('name' => 'Trigger APP_DELEGATION UPDATE',           'value'=> $res);

      //APPLICATION UPDATE 
      $res = $appCache->triggerApplicationUpdate();
      $result->info[] = array ('name' => 'Trigger APPLICATION UPDATE',              'value'=> $res);

      //build?
      $res = $appCache->fillAppCacheView();
      $result->info[] = array ('name' => 'build APP_CACHE_VIEW',              'value'=> $res);

      //show language
      $result->info[] = array ('name' => 'Language',         'value'=> $lang );

/*
      $result->info = Array(
        Array('name'=>'Cache Table', 'value'=>"[$tableExists]"),
        Array('name'=>'Records in Cache Table', 'value'=>"[$count]"),
        Array('name'=>'Cache Table Triggers', 'value'=>"[]"),
        Array('name'=>'Language', 'value'=>"[$lang]"),
        Array('name'=>'Status', 'value'=>"[$status]")
      );
*/      
      echo G::json_encode($result);
      break;
    
    case 'getLangList': 

      require_once 'classes/model/Language.php';
      $result = new stdClass();
      $result->rows = Array();
      $lang = new Language();
      $result->rows = $lang->getActiveLanguages();
     
      print(G::json_encode($result));
      break;
    
    case 'build':
      $sqlToExe = Array();
      $schemasPath = PATH_METHODS . 'setup' . PATH_SEP .'setupSchemas'. PATH_SEP;
      $sqlToExe[] = $schemasPath . 'app_cache_view.sql';
      $sqlToExe[] = $schemasPath . 'app_cache_view_insert.sql';
      $sqlToExe[] = $schemasPath . 'triggerAppDelegationInsert.sql';
      $sqlToExe[] = $schemasPath . 'triggerAppDelegationUpdate.sql';
      $sqlToExe[] = $schemasPath . 'triggerApplicationUpdate.sql';
      
      //G::LoadClass('serverConfiguration');
      //$oServerConf =& serverConf::getSingleton();
      
      G::LoadClass('configuration');
      $conf = new Configurations;
      
      $lang = $_POST['lang'];
      $dbUserType = $_GET['dbUserType'];
      try {
        
        $con = Propel::getConnection("workflow");
        G::LoadSystem('dbMaintenance');
        
        switch($dbUserType){
          case '1': 
            $o2 = new DataBaseMaintenance(DB_HOST, DB_USER, DB_PASS); 
            break;
          case '2': 
          
            $dbHash = @explode(SYSTEM_HASH, G::decrypt(HASH_INSTALLATION, SYSTEM_HASH));
            $o2 = new DataBaseMaintenance($dbHash[0], $dbHash[1], $dbHash[2]);
            break;
          case '0': 
            $o2 = new DataBaseMaintenance(DB_HOST, $_POST['user'], $_POST['password']); 
            break;
          default:
            die('fatal error!!'); break;
        }
        
        
        
        $o2->setDbName(DB_NAME);
        $o2->connect();
        foreach ($sqlToExe as $i=>$sqlFile) {
          
          if($i == 0){
            $s = "SELECT table_name
            FROM information_schema.tables
            WHERE table_schema = '".DB_NAME."'
            AND table_name = 'APP_CACHE_VIEW'";
            
            $con = Propel::getConnection("workflow");
            $con->begin();
            
            $rs = $con->executeQuery($s);
            $con->commit();
            $rs->next();
            $res = $rs->getRow();
            //print_r($res);
            if( isset($res['table_name']) ){
              $appCacheViewTableExists = true;
              $o2->query('DROP TABLE IF EXISTS APP_CACHE_VIEW;');
              $o2->query('DROP TRIGGER IF EXISTS APP_DELEGATION_INSERT;');
              $o2->query('DROP TRIGGER IF EXISTS APP_DELEGATION_UPDATE;');
              $o2->query('DROP TRIGGER IF EXISTS APPLICATION_UPDATE;');
            } else {
              $appCacheViewTableExists = false;
            }
          }
          $sqlString = file_get_contents($sqlFile);
          $sqlString = str_replace('{lang}', $lang, $sqlString);
          
          $o2->restoreFromSql($sqlString, 'string');
        }
        
        $confParams = Array(
          'LANG'=>$lang,
          'STATUS'=> 'active'
        );
        
        $conf->aConfig = $confParams;
        $conf->saveConfig('APP_CACHE_VIEW_ENGINE', '', '', '');
      
        $response = new StdClass();
        $response->success = true;
        $response->msg     = "Completed successfully";
        
        echo G::json_encode($response);
        
      } 
      catch (Exception $e) {
        $confParams = Array(
          'lang'=>$lang,
          'status'=> 'failed'
        );
        $appCacheViewEngine = $oServerConf->setProperty('APP_CACHE_VIEW_ENGINE', $confParams);
        $con->rollback();
        
        echo '{success: false, msg:"'.$e->getMessage().'"}';
      }
     
      break;
  }

  