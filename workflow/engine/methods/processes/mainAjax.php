<?

$request = $_POST['request'];

switch($request){
  case 'categoriesList':
    $r = Array(Array('CAT_ID'=>1, 'CAT_NAME'=>'uno'), Array('CAT_ID'=>2, 'CAT_NAME'=>'dos'), Array('CAT_ID'=>3, 'CAT_NAME'=>'tres'));
    require_once "classes/model/ProcessCategory.php";
    $processCategory = new ProcessCategory;
    $response->rows = $processCategory->getAll('array');
    echo G::json_encode($response); 
  break;
}