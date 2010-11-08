<?php
ini_set('memory_limit', '300M'); // nore: this may need to be higher for many projects
$mem_limit = (int) ini_get('memory_limit');

if ( !defined('PATH_SEP') ) {
  define('PATH_SEP', ( substr(PHP_OS, 0, 3) == 'WIN' ) ? '\\' : '/');
}

$docuroot = explode(PATH_SEP, str_replace('engine' . PATH_SEP . 'methods' . PATH_SEP . 'services', '', dirname(__FILE__)));
array_pop($docuroot);
array_pop($docuroot);
$pathhome = implode(PATH_SEP, $docuroot) . PATH_SEP;
//try to find automatically the trunk directory where are placed the RBAC and Gulliver directories
//in a normal installation you don't need to change it.
array_pop($docuroot);
$pathTrunk = implode(PATH_SEP, $docuroot) . PATH_SEP ;
array_pop($docuroot);
$pathOutTrunk = implode( PATH_SEP, $docuroot) . PATH_SEP ;
// to do: check previous algorith for Windows  $pathTrunk = "c:/home/";
define('PATH_HOME',     $pathhome);
define('PATH_TRUNK',    $pathTrunk);
define('PATH_OUTTRUNK', $pathOutTrunk);

require_once (PATH_HOME . 'engine' . PATH_SEP . 'config' . PATH_SEP . 'paths.php');

//default values  
$bCronIsRunning = false;
$sLastExecution = '';
if ( file_exists(PATH_DATA . 'cron') ) {
  $aAux = unserialize( trim( @file_get_contents(PATH_DATA . 'cron')) );
  $bCronIsRunning = (boolean)$aAux['bCronIsRunning'];
  $sLastExecution = $aAux['sLastExecution'];
}
else {
  //if not exists the file, just create a new one with current date
  @file_put_contents(PATH_DATA . 'cron', serialize(array('bCronIsRunning' => '1', 'sLastExecution' => date('Y-m-d H:i:s'))));
}

$WS = '';
$argsx = '';
$sDate = '';
for($i=1; $i<count($argv); $i++){
  
  if( strpos($argv[$i], '+d') !== false){
    $sDate = substr($argv[$i],2);
  } else if( strpos($argv[$i], '+w') !== false){
    $WS = substr($argv[$i],2);
  } else {
    $argsx .= ' '.$argv[$i];
  }
}


//if $sDate is not set, so take the system time
if($sDate!=''){
  eprintln("[Aplying date filter: $sDate]");
} else {
  $sDate = date('Y-m-d H:i:s');
}


if( $WS=='' ){
  $oDirectory = dir(PATH_DB);
  $cws = 0;
  while($sObject = $oDirectory->read()) {
    if (($sObject != '.') && ($sObject != '..')) {
      if (is_dir(PATH_DB . $sObject)) {

        if (file_exists(PATH_DB . $sObject . PATH_SEP . 'db.php')) {
          $cws++;
          system("php -f ".dirname(__FILE__).PATH_SEP."cron_single.php $sObject \"$sDate\" $argsx", $retval);
        }
      }
    }
  }
} else {
  $cws = 1;
  system("php -f ".dirname(__FILE__).PATH_SEP."cron_single.php $WS \"$sDate\" $argsx", $retval);
}
eprintln("Finished $cws workspaces processed.");

