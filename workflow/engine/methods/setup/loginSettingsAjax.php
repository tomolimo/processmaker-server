<?php
 $request = isset($_POST['request'])? $_POST['request']: (isset($_GET['request'])? $_GET['request']: null);
  
  switch($request){
    case 'getLangList': 

      $Translations = G::getModel('Translation');
      $result = new stdClass();
      $result->rows = Array();
      
      $langs = $Translations->getTranslationEnvironments();
      foreach($langs as $lang){
        $result->rows[] = Array('LAN_ID'=>$lang['LOCALE'], 'LAN_NAME'=>$lang['LANGUAGE']);
      }
      
      print(G::json_encode($result));
      break;
    case 'saveSettings':
      
      G::LoadClass('configuration');
      $conf = new Configurations;
      $conf->loadConfig($obj, 'ENVIRONMENT_SETTINGS','');
      
      $conf->aConfig['login_enableForgotPassword'] = isset($_POST['acceptRP']) ? $_POST['acceptRP'] : 'off';
      $conf->aConfig['login_defaultLanguage'] = $_POST['lang'];      
      $conf->saveConfig('ENVIRONMENT_SETTINGS', '');
      
      $response->success = true;
      if (isset($_POST['acceptRP']) && $_POST['acceptRP'])
        $response->enable = true;
      else
        $response->enable = false;     

      echo G::json_encode($response);
      
      break;
  }