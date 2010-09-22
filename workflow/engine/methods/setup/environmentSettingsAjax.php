<?php
 /**
  * @author Erik A.O. <erik@colosa.com>
  * @date Sept 13th, 2010
  */
  
  $request = isset($_POST['request'])? $_POST['request']: (isset($_GET['request'])? $_GET['request']: null);
  
  switch($request){
    case 'getList':
      $formats[] = Array(
        'id'=>'@firstName @lastName', //the id , don't translate
        'name'=>'@firstName @lastName' //label displayed, can be translated
      );
      $formats[] = Array(
        'id'=>'@firstName @lastName (@userName)',
        'name'=>'@firstName @lastName (@userName)'
      );
      $formats[] = Array(
        'id'=>'@userName',
        'name'=>'@userName'
      );
      $formats[] = Array(
        'id'=>'@userName (@firstName @lastName)',
        'name'=>'@userName (@firstName @lastName)'
      );
      $formats[] = Array(
        'id'=>'@lastName @firstName',
        'name'=>'@lastName @firstName'
      );
      $formats[] = Array(
        'id'=>'@lastName, @firstName',
        'name'=>'@lastName, @firstName'
      );
      $formats[] = Array(
        'id'=>'@lastName, @firstName (@userName)',
        'name'=>'@lastName, @firstName (@userName)'
      );
      
      $result->rows = $formats;
      print(G::json_encode($result));
      break;

    case 'save':
      
      G::LoadClass('configuration');
      $conf = new Configurations;
      $conf->aConfig = Array(
        'format'=>$_POST['format']
      );
      $conf->saveConfig('ENVIRONMENT_SETTINGS', '');

      $response = new StdClass();
      $response->success = true;
      $response->msg     = "Saved Successfully";
      
      echo G::json_encode($response);
    break;
  }

  