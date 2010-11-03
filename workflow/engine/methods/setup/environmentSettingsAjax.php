<?php
 /**
  * @author Erik A.O. <erik@colosa.com>
  * @date Sept 13th, 2010
  */
  
  $request = isset($_POST['request'])? $_POST['request']: (isset($_GET['request'])? $_GET['request']: null);
  
  switch($request){
    case 'getUserMaskList':
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

    case 'getDateMaskList':
      $formats[] = Array(
        'id'=>'Y-m-d H:i:s',
        'name'=>'Y-m-d H:i:s'
      );
      $formats[] = Array(
        'id'=>'d/m/Y',
        'name'=>'d/m/Y'
      );
      $formats[] = Array(
        'id'=>'m/d/Y',
        'name'=>'m/d/Y'
      );
      $formats[] = Array(
        'id'=>'Y/d/m',
        'name'=>'Y/d/m'
      );
      $formats[] = Array(
        'id'=>'Y/m/d',
        'name'=>'Y/m/d'
      );
      $formats[] = Array(
        'id'=>'F j, Y, g:i a',
        'name'=>'F j, Y, g:i a'
      );
      $formats[] = Array(
        'id'=>'m.d.y',
        'name'=>'m.d.y'
      );
      $formats[] = Array(
        'id'=>'j, n, Y',
        'name'=>'j, n, Y'
      );
      
      $formats[] = Array(
        'id'=>'D M j G:i:s T Y',
        'name'=>'D M j G:i:s T Y'
      );
      $formats[] = Array(
        'id'=>'D M, Y',
        'name'=>'D M, Y'
      );
      $formats[] = Array(
        'id'=>'d M, Y',
        'name'=>'d M, Y'
      );
      $formats[] = Array(
        'id'=>'d m, Y',
        'name'=>'d m, Y'
      );

      $result->rows = $formats;
      print(G::json_encode($result));
      break;

    case 'save':
      
      G::LoadClass('configuration');
      $conf = new Configurations;
      $conf->aConfig = Array(
        'format'=>$_POST['userFormat'],
        'dateFormat'=>$_POST['dateFormat']
      );
      $conf->saveConfig('ENVIRONMENT_SETTINGS', '');

      $response = new StdClass();
      $response->success = true;
      $response->msg     = G::LoadTranslation('ID_SAVED_SUCCESSFULLY');
      
      echo G::json_encode($response);
    break;
  }

  