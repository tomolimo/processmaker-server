<?php

  $request = isset($_POST['request'])? $_POST['request']: (isset($_GET['request'])? $_GET['request']: null);
  
  switch($request){
  
    case 'info':
      $result = new stdClass();
      
      G::loadClass('configuration');
      $oConf = new Configurations; 
      $oConf->loadConfig($x, 'APP_CACHE_VIEW_ENGINE','','','','');
      $appCacheViewEngine = $oConf->aConfig;
      
      if( isset($appCacheViewEngine['LANG']) ){
        $lang   = $appCacheViewEngine['LANG'];
        $status = strtoupper($appCacheViewEngine['STATUS']);
      } else {
        $lang = '-';
        $status = 'MISSING';
      }
      $sql = "SELECT table_name
      FROM information_schema.tables
      WHERE table_schema = '".DB_NAME."'
      AND table_name = 'APP_CACHE_VIEW'";
      
      $con = Propel::getConnection("workflow");
      $rs = $con->executeQuery($sql);
      $rs->next();
      $res = $rs->getRow();
      if( isset($res['table_name']) ){
        $tableExists  = 'PASSED';
        $sql = "SELECT COUNT(APP_UID) AS NUM FROM APP_CACHE_VIEW";
        $rs = $con->executeQuery($sql);
        $rs->next();
        $res = $rs->getRow();
        //print_r($res);
        if( isset($res['NUM']) )
          $count = $res['NUM'];
        else 
          $count = '-';

      } else {
        $tableExists  = 'NOT FOUND';
        $count = '-';
      }
      $result->status = 'ok';
      $result->info = Array(
        Array('name'=>'Status', 'value'=>"[$status]"),
        Array('name'=>'Cache table', 'value'=>"[$tableExists]"),
        Array('name'=>'Records in Cache table', 'value'=>"[$count]"),
       /* Array('name'=>'Cache Table Triggers', 'value'=>"[]"),*/
        Array('name'=>'Language', 'value'=>"[$lang]")
      );
      
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
        
      } catch (Exception $e) {
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

  