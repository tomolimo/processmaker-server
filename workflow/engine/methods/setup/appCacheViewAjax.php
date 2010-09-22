<?php

  $request = isset($_POST['request'])? $_POST['request']: (isset($_GET['request'])? $_GET['request']: null);
  
  switch($request){
    case 'info':
      $result = new stdClass();
      
      G::LoadClass('serverConfiguration');
      $oServerConf =& serverConf::getSingleton();
      $appCacheViewEngine = $oServerConf->getProperty('APP_CACHE_VIEW_ENGINE');
      
      if( isset($appCacheViewEngine['lang']) )
        $lang = $appCacheViewEngine['lang'];
      else
        $lang = '-';
      
      $sql = "SELECT table_name
      FROM information_schema.tables
      WHERE table_schema = 'wf_".SYS_SYS."'
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
          
       // $o2->query('DROP TRIGGER IF EXISTS APP_DELEGATION_INSERT;');
        //$o2->query('DROP TRIGGER IF EXISTS APP_DELEGATION_UPDATE;');
        //$o2->query('DROP TRIGGER IF EXISTS APPLICATION_UPDATE;');
      } else {
        $tableExists  = 'NOT FOUND';
        $count = '-';
      }
      
      $result->info = Array(
        Array('name'=>'Cache table', 'value'=>"[$tableExists]"),
        Array('name'=>'Records in Cache table', 'value'=>"[$count]"),
        Array('name'=>'Cache Table Triggers', 'value'=>"[]"),
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
      
      G::LoadClass('serverConfiguration');
      $oServerConf =& serverConf::getSingleton();
      
      $lang = $_POST['lang'];
      
      try {
        
        $con = Propel::getConnection("workflow");
        G::LoadSystem('dbMaintenance');
        $o2 = new DataBaseMaintenance('localhost', 'root', 'atopml2005');
        
        $o2->setDbName('wf_'.SYS_SYS);
        $o2->connect();
        foreach ($sqlToExe as $i=>$sqlFile) {
          
          if($i == 0){
            $s = "SELECT table_name
            FROM information_schema.tables
            WHERE table_schema = 'wf_".SYS_SYS."'
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
          'lang'=>$lang,
          'status'=> 'active'
        );
        $appCacheViewEngine = $oServerConf->setProperty('APP_CACHE_VIEW_ENGINE', $confParams);
        echo '{success: true, msg:"Completed successlly"}';
      
      } catch (SQLException $sqle) {
        $confParams = Array(
          'lang'=>$lang,
          'status'=> 'failed'
        );
        $appCacheViewEngine = $oServerConf->setProperty('APP_CACHE_VIEW_ENGINE', $confParams);
        $con->rollback();
        throw $sqle;
      }
     
      break;
  }

  