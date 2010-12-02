<?

$request = $_POST['request'];

switch($request){
  case 'categoriesList':
    require_once "classes/model/ProcessCategory.php";
    
    $processCategory = new ProcessCategory;
    $defaultOption = Array();
    $defaultOption[] = Array('CATEGORY_UID'=>'<reset>', 'CATEGORY_NAME'=>G::LoadTranslation('ID_ALL'));
    $defaultOption[] = Array('CATEGORY_UID'=>'', 'CATEGORY_NAME'=>G::LoadTranslation('ID_PROCESS_NO_CATEGORY'));
    
    $response->rows = array_merge($defaultOption, $processCategory->getAll('array'));
    
    echo G::json_encode($response); 
    
  break;
}