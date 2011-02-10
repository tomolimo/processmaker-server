<?

$request = isset($_GET['request']) ? $_GET['request'] : $_POST['request'];

switch($request)
{
  case 'categoriesList':
    require_once "classes/model/ProcessCategory.php";
    
    $processCategory = new ProcessCategory;
    $defaultOption = Array();
    $defaultOption[] = Array('CATEGORY_UID'=>'<reset>', 'CATEGORY_NAME'=>G::LoadTranslation('ID_ALL'));
    $defaultOption[] = Array('CATEGORY_UID'=>'', 'CATEGORY_NAME'=>G::LoadTranslation('ID_PROCESS_NO_CATEGORY'));
    
    $response->rows = array_merge($defaultOption, $processCategory->getAll('array'));
    
    echo G::json_encode($response);
  break;
  
  case 'processCategories':
    require_once "classes/model/ProcessCategory.php";
    
    $processCategory = new ProcessCategory;
    $defaultOption = Array();
    $defaultOption[] = Array('CATEGORY_UID'=>'', 'CATEGORY_NAME'=>G::LoadTranslation('ID_PROCESS_NO_CATEGORY'));
    
    $response->rows = array_merge($defaultOption, $processCategory->getAll('array'));
    
    echo G::json_encode($response); 
  break;
  
  case 'saveProcess':
    try{
      require_once 'classes/model/Task.php';
      G::LoadClass('processMap');
      $oProcessMap = new ProcessMap();
      
      if( ! isset($_POST['PRO_UID']) ) {
      
        if( Process::existsByProTitle($_POST['PRO_TITLE']) ) {
          $result = array(
            'success' => false,  
            'msg' => 'Process Save Error',  
            'errors' => array(
              'PRO_TITLE' => G::LoadTranslation('ID_PROCESSTITLE_ALREADY_EXISTS', SYS_LANG, $_POST)
            )
          );
          print G::json_encode($result);
          exit(0);
        }
      
        $processData['USR_UID']         = $_SESSION['USER_LOGGED'];
        $processData['PRO_TITLE']       = $_POST['PRO_TITLE'];
        $processData['PRO_DESCRIPTION'] = $_POST['PRO_DESCRIPTION'];
        $processData['PRO_CATEGORY']    = $_POST['PRO_CATEGORY'];
        
        $sProUid = $oProcessMap->createProcess($processData);
        
        //call plugins
        $oData['PRO_UID']      = $sProUid;
        $oData['PRO_TEMPLATE'] = ( isset($_POST['PRO_TEMPLATE']) && $_POST['PRO_TEMPLATE'] != '' ) ? $_POST['form']['PRO_TEMPLATE'] : '';
        $oData['PROCESSMAP']   = $oProcessMap;
    
        $oPluginRegistry =& PMPluginRegistry::getSingleton();
        $oPluginRegistry->executeTriggers ( PM_NEW_PROCESS_SAVE , $oData );
        
      } else {
        //$oProcessMap->updateProcess($_POST['form']);
        $sProUid = $_POST['PRO_UID'];
      }
      
      //Save Calendar ID for this process
      if( isset($_POST['PRO_CALENDAR']) ){
        G::LoadClass("calendar");
        $calendarObj=new Calendar();
        $calendarObj->assignCalendarTo($sProUid, $_POST['PRO_CALENDAR'], 'PROCESS');
      }
      
      $result->success = true;
      $result->PRO_UID = $sProUid;
      $result->msg = G::LoadTranslation('ID_CREATE_PROCESS_SUCCESS');
    } catch(Exception $e){
      $result->success = false;
      $result->msg = $e->getMessage();
    }
    
    print G::json_encode($result);
  break;
}


