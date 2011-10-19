<?php
 /**
  * @author Erik A.O. <erik@colosa.com>
  * @date Sept 13th, 2010
  */
  
  $request = isset($_POST['request'])? $_POST['request']: (isset($_GET['request'])? $_GET['request']: null);
  
  switch($request){
    case 'getUserMaskList':
      G::loadClass('configuration');
      $result->rows = Configurations::getUserNameFormats();
      print(G::json_encode($result));
      break;

    case 'getDateFormats':
      G::loadClass('configuration');
      $result->rows = Configurations::getDateFormats();
      print(G::json_encode($result));
      break;

    case 'getCasesListDateFormat':
      G::loadClass('configuration');
      $result->rows = Configurations::getDateFormats();;
      print(G::json_encode($result));
      break;
    
    case 'getCasesListRowNumber':
      for($i=10; $i<=50; $i+=5){
        $formats[] = Array('id'=>"$i", 'name'=>"$i");
      }
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
      $conf->aConfig['startCaseHideProcessInf'] =  isset($_POST['hideProcessInf']) ? true : false;

      $conf->saveConfig('ENVIRONMENT_SETTINGS', '');

      $response = new StdClass();
      $response->success = true;
      $response->msg     = G::LoadTranslation('ID_SAVED_SUCCESSFULLY');
      
      echo G::json_encode($response);
    break;
  }

  