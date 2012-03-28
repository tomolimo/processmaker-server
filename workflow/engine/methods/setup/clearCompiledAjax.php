<?php
try {
  if ( (isset($_POST['javascriptCache'])) || (isset($_POST['metadataCache'])) || (isset($_POST['htmlCache']))) {
    if ((isset($_POST['javascriptCache'])) && (@chdir(PATH_C.'/ExtJs/'))) {
      G::rm_dir(PATH_C.'/ExtJs/');
      $response->javascript = true;
    }
    if ((isset($_POST['metadataCache'])) && (@chdir(PATH_C.'/xmlform/'))) {
      G::rm_dir(PATH_C.'/xmlform/');
      $response->xmlform = true;
    }
    if((isset($_POST['htmlCache'])) && (@chdir(PATH_C.'/smarty/'))) {
      G::rm_dir(PATH_C.'/smarty/');
      $response->smarty = true;
    }
    
    $response->success = true;
    //$response->path = $path;
  }else{
    $response->success = false;
  } 
}
catch ( Exception $e ) {
  $response->success = false;
}
echo G::json_encode($response);
