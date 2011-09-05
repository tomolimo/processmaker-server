<?php

  $type      =  isset ( $_GET['type'])  ?  $_GET['type']  : '1' ;
  $chartType =  isset ( $_GET['chart']) ?  $_GET['chart']  : '1' ;
  $user      =  isset ( $_GET['user']) ?  $_GET['user']  : $_SESSION['USER_LOGGED'] ;

  include_once 'open_flash_chart_object.php';
  open_flash_chart_object( 450, 200, '../openFlash/chart-data.php?type=' . $type .'&chart='. $chartType, 
  false , '/plugin/openFlash/');
  
  
