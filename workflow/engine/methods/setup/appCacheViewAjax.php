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
      $res = $appCache->triggerAppDelegationInsert($lang);
      $result->info[] = array ('name' => 'Trigger APP_DELEGATION INSERT',           'value'=> $res);

      //APP_DELEGATION Update 
      $res = $appCache->triggerAppDelegationUpdate($lang);
      $result->info[] = array ('name' => 'Trigger APP_DELEGATION UPDATE',           'value'=> $res);

      //APPLICATION UPDATE 
      $res = $appCache->triggerApplicationUpdate($lang);
      $result->info[] = array ('name' => 'Trigger APPLICATION UPDATE',              'value'=> $res);

      //APPLICATION DELETE
      $res = $appCache->triggerApplicationDelete($lang);
      $result->info[] = array ('name' => 'Trigger APPLICATION DELETE',              'value'=> $res);

      //show language
      $result->info[] = array ('name' => 'Language',         'value'=> $lang );

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
      G::LoadClass('configuration');
      $conf = new Configurations;
      
      $lang = $_POST['lang'];

      try {        
        //build using the method in AppCacheView Class
        //setup the appcacheview object, and the path for the sql files
        $appCache = new AppCacheView();
        $appCache->setPathToAppCacheFiles ( PATH_METHODS . 'setup' . PATH_SEP .'setupSchemas'. PATH_SEP );
        $res = $appCache->fillAppCacheView($lang);
        $result->info[] = array ('name' => 'build APP_CACHE_VIEW',              'value'=> $res);
        
        //set status in config table
        $confParams = Array(
          'LANG' => $lang,
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

  