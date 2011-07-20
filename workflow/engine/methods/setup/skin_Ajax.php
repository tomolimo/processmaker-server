<?php

if (!isset($_REQUEST ['action'])) {
  $res ['success'] = 'failure';
  $res ['message'] = 'You may request an action';
  print G::json_encode($res);
  die ();
}
if (!function_exists($_REQUEST ['action'])) {
  $res ['success'] = 'failure';
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