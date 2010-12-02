<?php
switch ($_GET['MAIN_DIRECTORY']) {
  case 'mailTemplates':
    $sDirectory = PATH_DATA_MAILTEMPLATES . $_GET['PRO_UID'] . PATH_SEP . ($_GET['CURRENT_DIRECTORY'] != '' ? $_GET['CURRENT_DIRECTORY'] . PATH_SEP : '');
  break;
  case 'public':
    $sDirectory = PATH_DATA_PUBLIC . $_GET['PRO_UID'] . PATH_SEP . ($_GET['CURRENT_DIRECTORY'] != '' ? $_GET['CURRENT_DIRECTORY'] . PATH_SEP : '');
  break;
  default:
    die;
  break;
}
if (file_exists($sDirectory . $_GET['FILE'])) {
  G::streamFile($sDirectory . $_GET['FILE'], true);
}