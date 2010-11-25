<?
$action = isset($POST['action'])? $POST['action']: isset($_GET['action'])? $_GET['action']: '';

switch($action){
  case 'setTemplateFile':
    //print_r($_FILES);
    $_SESSION['outpudocs_tmpFile'] = PATH_DATA . $_FILES['templateFile']['name'];
//    file_put_contents($_FILES['templateFile']['name'], file_get_contents($_FILES['templateFile']['tmp_name']));
    copy($_FILES['templateFile']['tmp_name'],     $_SESSION['outpudocs_tmpFile']);
    $result = new stdClass();
    
    $result->success = true;
    $result->msg = 'success - saved '.    $_SESSION['outpudocs_tmpFile'];
    echo G::json_encode($result);
  break;
  
  case 'getTemplateFile':
    $aExtensions = array ("html", "htm", "txt", "xml");
    $sFileName = strtolower($_SESSION['outpudocs_tmpFile']);
    $sExtension = substr($sFileName, strpos($sFileName,'.') + 1, strlen($sFileName));
    if(in_array($sExtension, $aExtensions))
      echo $content = file_get_contents($_SESSION['outpudocs_tmpFile']);
  break;
  
  case 'loadTemplateContent':
    require_once 'classes/model/OutputDocument.php';
    $ooutputDocument = new OutputDocument();
    if (isset($_POST['OUT_DOC_UID'])) {
      $aFields = $ooutputDocument->load($_POST['OUT_DOC_UID']);
      
      echo $aFields['OUT_DOC_TEMPLATE'];
    }
  break;
}
