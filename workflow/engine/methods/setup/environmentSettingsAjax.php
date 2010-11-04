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
        'id'=>'D d M, Y',
        'name'=>'D d M, Y'
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

    case 'getCasesListDateFormat':
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
        'id'=>'D d M, Y',
        'name'=>'D d M, Y'
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
    case 'getCasesListRowNumber':
      $formats[] = Array(
        'id'=>'5',
        'name'=>'5'
      );
      $formats[] = Array(
        'id'=>'6',
        'name'=>'6'
      );
      $formats[] = Array(
        'id'=>'7',
        'name'=>'7'
      );
      $formats[] = Array(
        'id'=>'8',
        'name'=>'8'
      );
      $formats[] = Array(
        'id'=>'9',
        'name'=>'9'
      );
      $formats[] = Array(
        'id'=>'10',
        'name'=>'10'
      );
      $formats[] = Array(
        'id'=>'12',
        'name'=>'12'
      );
      $formats[] = Array(
        'id'=>'15',
        'name'=>'15'
      );

      $formats[] = Array(
        'id'=>'18',
        'name'=>'18'
      );
      $formats[] = Array(
        'id'=>'20',
        'name'=>'20'
      );
      $formats[] = Array(
        'id'=>'25',
        'name'=>'25'
      );
      $formats[] = Array(
        'id'=>'30',
        'name'=>'30'
      );
      $formats[] = Array(
        'id'=>'50',
        'name'=>'50'
      );
      $formats[] = Array(
        'id'=>'100',
        'name'=>'100'
      );

      $result->rows = $formats;
      print(G::json_encode($result));
      break;

    case 'save':
      
      G::LoadClass('configuration');
      $conf = new Configurations;
      $conf->aConfig = Array(
        'format'=>$_POST['userFormat'],
        'dateFormat'=>$_POST['dateFormat'],
        'casesListDateFormat' =>$_POST['casesListDateFormat'],
        'casesListRowNumber'  =>$_POST['casesListRowNumber']
      );
      $conf->saveConfig('ENVIRONMENT_SETTINGS', '');

      $response = new StdClass();
      $response->success = true;
      $response->msg     = G::LoadTranslation('ID_SAVED_SUCCESSFULLY');
      
      echo G::json_encode($response);
    break;
  }

  