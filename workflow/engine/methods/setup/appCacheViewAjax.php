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
      $result->info = array ();
      $result->error = false;

      //setup the appcacheview object, and the path for the sql files
      $appCache = new AppCacheView();
      $appCache->setPathToAppCacheFiles ( PATH_METHODS . 'setup' . PATH_SEP .'setupSchemas'. PATH_SEP );
      
      $res = $appCache->getMySQLVersion();
      $result->info[] = array ('name' => 'MySQL Version',   'value'=> $res );
      
      $res = $appCache->checkGrantsForUser( false );
      $currentUser        = $res['user'];
      $currentUserIsSuper = $res['super'];
      $result->info[] = array ('name' => 'Current User',    'value'=> $currentUser );
      $result->info[] = array ('name' => 'Current User has SUPER privilege', 'value'=> $currentUserIsSuper );
      
      try {
        PROPEL::Init ( PATH_METHODS.'dbConnections/rootDbConnections.php' ); 
        $con = Propel::getConnection("root");
      }
      catch ( Exception $e ) {
        $result->info[] = array ('name' => 'Checking MySql Root user',    'value'=> 'failed' );
        $result->error = true;
        $result->errorMsg = $e->getMessage();
      }
      
      //if user does not have the SUPER privilege we need to use the root user and grant the SUPER priv. to normal user.
      if ( ! $currentUserIsSuper && !$result->error ) {
        $res = $appCache->checkGrantsForUser( true );
        if ( !isset( $res['error'] ) ) {
          $result->info[] = array ('name' => 'Root User',       'value'=> $res['user'] );
          $result->info[] = array ('name' => 'Root User has SUPER privilege', 'value'=> $res['super'] );
        }
        else {
          $result->info[] = array ('name' => 'Error', 'value'=> $res['msg'] );
        }

        $res = $appCache->setSuperForUser( $currentUser );
        if ( !isset( $res['error'] ) ) {
          $result->info[] = array ('name' => 'Setting SUPER privilege',       'value'=> 'Successfully' );
        }
        else {
          $result->error = true;
          $result->errorMsg = $res['msg'];
        }
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
      $res = $appCache->triggerAppDelegationInsert($lang, false);
      $result->info[] = array ('name' => 'Trigger APP_DELEGATION INSERT',           'value'=> $res);

      //APP_DELEGATION Update 
      $res = $appCache->triggerAppDelegationUpdate($lang, false);
      $result->info[] = array ('name' => 'Trigger APP_DELEGATION UPDATE',           'value'=> $res);

      //APPLICATION UPDATE 
      $res = $appCache->triggerApplicationUpdate($lang, false);
      $result->info[] = array ('name' => 'Trigger APPLICATION UPDATE',              'value'=> $res);

      //APPLICATION DELETE
      $res = $appCache->triggerApplicationDelete($lang, false);
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
        //setup the appcacheview object, and the path for the sql files
        $appCache = new AppCacheView();
        $appCache->setPathToAppCacheFiles ( PATH_METHODS . 'setup' . PATH_SEP .'setupSchemas'. PATH_SEP );

        //APP_DELEGATION INSERT 
        $res = $appCache->triggerAppDelegationInsert($lang, true);
        //$result->info[] = array ('name' => 'Trigger APP_DELEGATION INSERT',           'value'=> $res);
  
        //APP_DELEGATION Update 
        $res = $appCache->triggerAppDelegationUpdate($lang, true);
        //$result->info[] = array ('name' => 'Trigger APP_DELEGATION UPDATE',           'value'=> $res);
  
        //APPLICATION UPDATE 
        $res = $appCache->triggerApplicationUpdate($lang, true);
        //$result->info[] = array ('name' => 'Trigger APPLICATION UPDATE',              'value'=> $res);
  
        //APPLICATION DELETE
        $res = $appCache->triggerApplicationDelete($lang, true);
        //$result->info[] = array ('name' => 'Trigger APPLICATION DELETE',              'value'=> $res);


        //build using the method in AppCacheView Class
        $res = $appCache->fillAppCacheView($lang);
        //$result->info[] = array ('name' => 'build APP_CACHE_VIEW',              'value'=> $res);
        
        //set status in config table
        $confParams = Array(
          'LANG' => $lang,
          'STATUS'=> 'active'
        );        
        $conf->aConfig = $confParams;
        $conf->saveConfig('APP_CACHE_VIEW_ENGINE', '', '', '');
      
        $response = new StdClass();
        $result->success = true;
        $result->msg     = "Completed successfully";
        
        echo G::json_encode($result);
        
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

  