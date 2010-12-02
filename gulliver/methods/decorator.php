<?php

G::LoadSystem('helper');

$helper = new Helper();
$type = $_GET['t'];

switch($type){
  case 'extjs-cssExtended':
    $helper->setContenttype('text/css');
    
    $extJsSkin = $_GET['s'];
    
    //adding the extend css for extjs-pmos 
    $cssThemeExtensions = glob(PATH_TPL . "*/css/extjs-extend/{$extJsSkin}.css");
    foreach($cssThemeExtensions as $cssThemeExtensionFile)
      $helper->addFile($cssThemeExtensionFile);
    
    $helper->serve();
  break;
}


