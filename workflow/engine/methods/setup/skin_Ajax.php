<?php

if (!isset($_REQUEST ['action'])) {
  $res ['success'] = false;
  $res ['message'] = 'You may request an action';
  print G::json_encode($res);
  die ();
}
if (!function_exists($_REQUEST ['action'])) {
  $res ['success'] = false;
  $res ['message'] = 'The requested action doesn\'t exists';
  print G::json_encode($res);
  die ();
}

$functionName = $_REQUEST ['action'];
$functionParams = isset($_REQUEST ['params']) ? $_REQUEST ['params'] : array();

$functionName($functionParams);

function updatePageSize() {
  G::LoadClass('configuration');
  $c = new Configurations();
  $arr['pageSize'] = $_REQUEST['size'];
  $arr['dateSave'] = date('Y-m-d H:i:s');
  $config = Array();
  $config[] = $arr;
  $c->aConfig = $config;
  $c->saveConfig('skinsList', 'pageSize', '', $_SESSION['USER_LOGGED']);
  echo '{success: true}';
}

function skinList() {
  //Get Skin Config files
  $skinListArray = array();
  $customSkins = glob(PATH_CUSTOM_SKINS . "*/config.xml");
  $configurationFile = G::ExpandPath("skinEngine") . 'base' . PATH_SEP . 'config.xml';
  array_unshift($customSkins, $configurationFile);

  //Read and parse each Configuration File
  foreach ($customSkins as $key => $configInformation) {

    $folderId = str_replace(G::ExpandPath("skinEngine") . 'base', "", str_replace(PATH_CUSTOM_SKINS, "", str_replace("/config.xml", "", $configInformation)));
    if ($folderId == "")
      $folderId = "classic";
    $xmlConfiguration = file_get_contents($configInformation);
    $xmlConfigurationObj = G::xmlParser($xmlConfiguration);
    $skinInformationArray = $skinFilesArray = $xmlConfigurationObj->result['skinConfiguration']['__CONTENT__']['information']['__CONTENT__'];

    $res = array();
    $res['SKIN_FOLDER_ID'] = strtolower($folderId);
    foreach ($skinInformationArray as $keyInfo => $infoValue) {
      $res['SKIN_' . strtoupper($keyInfo)] = $infoValue['__VALUE__'];
    }
    $skinListArray['skins'][] = $res;
  }
  print_r(G::json_encode($skinListArray));
}

function createSkin($baseSkin='classic') {

}

function importSkin() {
  try {
    if (!isset($_FILES['uploadedFile'])) {
      throw ( new Exception(G::LoadTranslation('ID_SKIN_FILE_REQUIRED')) );
    }
    $uploadedInstances = count($_FILES['uploadedFile']['name']);
    $sw_error = false;
    $sw_error_exists = isset($_FILES['uploadedFile']['error']);
    $emptyInstances = 0;
    $quequeUpload = array();
    // upload files & check for errors

    $tmp = $_FILES['uploadedFile']['tmp_name'];
    $items = stripslashes($_FILES['uploadedFile']['name']);
    if ($sw_error_exists)
      $up_err = $_FILES['uploadedFile']['error'];
    else
      $up_err= ( file_exists($tmp) ? 0 : 4);


    if ($items == "" || $up_err == 4) {
      throw ( new Exception(G::LoadTranslation('ID_SKIN_FILE_REQUIRED')) );
    }
    if ($up_err == 1 || $up_err == 2) {
      throw ( new Exception(G::LoadTranslation('ID_SKIN_FILE_SIZE_ERROR')) );
      //$errors[$i]='miscfilesize';
    }
    if ($up_err == 3) {
      throw ( new Exception(G::LoadTranslation('ID_SKIN_FILE_PART_ERROR')) );
      //$errors[$i]='miscfilepart';
    }
    if (!@is_uploaded_file($tmp)) {
      throw ( new Exception(G::LoadTranslation('ID_SKIN_FILE_NOT_UPLOADED')) );
      //$errors[$i]='uploadfile';
    }
    $fileInfo = pathinfo($items);
    $validType = array('tar', 'gz');

    if (!in_array($fileInfo['extension'], $validType)) {
      throw ( new Exception(G::LoadTranslation('ID_SKIN_FILE_TYPE_ERROR')) );
//$errors[$i]='wrongtype';
    }


    $filename = $items;
    $tempPath = PATH_CUSTOM_SKINS . '.tmp' . PATH_SEP;
    G::verifyPath($tempPath, true);
    $tempName = $tmp;
    G::uploadFile($tempName, $tempPath, $filename);
    G::LoadThirdParty('pear/Archive', 'Tar');
    $tar = new Archive_Tar($tempPath . $filename);
    $aFiles = $tar->listContent();
    $swConfigFile = false;

    foreach ($aFiles as $key => $val) {
      if (basename($val['filename']) == 'config.xml') {
        $skinName = dirname($val['filename']);
        $skinArray = explode("/", $skinName);
        if (count($skinArray) == 1) {
          $swConfigFile = true;
        }
      }
    }

    if (!$swConfigFile) {
      @unlink(PATH_CUSTOM_SKINS . '.tmp' . PATH_SEP . $filename);
      throw ( new Exception(G::LoadTranslation('ID_SKIN_CONFIGURATION_MISSING')) );
    }

    if (is_dir(PATH_CUSTOM_SKINS . $skinName)) {
      if ((isset($_REQUEST['overwrite_files'])) && ($_REQUEST['overwrite_files'] == 'on')) {
        G::rm_dir(PATH_CUSTOM_SKINS . $skinName, false);
      } else {
        throw ( new Exception(G::LoadTranslation('ID_SKIN_ALREADY_EXISTS')) );
      }
    }
    $res = $tar->extract(PATH_CUSTOM_SKINS);
    if (!$res) {
      throw ( new Exception(G::LoadTranslation('ID_SKIN_ERROR_EXTRACTING')) );
    }
//Delete Temporal
    @unlink(PATH_CUSTOM_SKINS . '.tmp' . PATH_SEP . $filename);

    $response['success'] = true;
    $response['message'] = G::LoadTranslation('ID_SKIN_SUCCESSFUL_IMPORTED');
    print_r(G::json_encode($response));
  } catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = $e->getMessage();
    $response['error'] = $e->getMessage();
    print_r(G::json_encode($response));
  }
}

function exportSkin($skinToExport) {
  try {
    if (!isset($_REQUEST['SKIN_FOLDER_ID'])) {
      throw ( new Exception(G::LoadTranslation('ID_SKIN_NAME_REUIRED')) );
    }

    $skinName = $_REQUEST['SKIN_FOLDER_ID'];

    $customSkins = glob(PATH_CUSTOM_SKINS . "*/config.xml");

    $skinFolderBase = PATH_CUSTOM_SKINS . $skinName;
    $skinFolder = $skinFolderBase . PATH_SEP;
    $skinTar = PATH_CUSTOM_SKINS . $skinName . '.tar';
    if (!is_dir($skinFolder)) {
      throw ( new Exception(G::LoadTranslation('ID_SKIN_DOESNT_EXIST')) );
    }
    if (!file_exists($skinFolder . "config.xml")) {
      throw ( new Exception(G::LoadTranslation('ID_SKIN_CONFIGFILE_DOESNT_EXIST')) );
    }

    if (file_exists($skinTar)) {
      //try to delete
      if (!unlink($skinTar)) {
        throw ( new Exception(G::LoadTranslation('ID_SKIN_FOLDER_PERMISSIONS')) );
      }
    }

    //Try to generate tar file

    G::LoadThirdParty('pear/Archive', 'Tar');
    $tar = new Archive_Tar($skinTar);
    $tar->_compress = false;

    addTarFolder($tar, $skinFolder, PATH_CUSTOM_SKINS);

    $response['success'] = true;
    $response['message'] = $skinTar;


    print_r(G::json_encode($response));
  } catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = $e->getMessage();
    print_r(G::json_encode($response));
  }
}

function streamSkin() {
  $skinTar = $_REQUEST['file'];
  $bDownload = true;
  G::streamFile($skinTar, $bDownload, basename($skinTar));
  @unlink($fileTar);
}

function addTarFolder($tar, $pathBase, $pluginHome) {
  $aux = explode(PATH_SEP, $pathBase);
  if ($aux[count($aux) - 2] == '.svn')
    return;

  if ($handle = opendir($pathBase)) {
    while (false !== ($file = readdir($handle))) {
      if (is_file($pathBase . $file)) {
        //print "file $file \n";
        $tar->addModify($pathBase . $file, '', $pluginHome);
      }
      if (is_dir($pathBase . $file) && $file != '..' && $file != '.') {
        //print "dir $pathBase$file \n";
        addTarFolder($tar, $pathBase . $file . PATH_SEP, $pluginHome);
      }
    }
    closedir($handle);
  }
}