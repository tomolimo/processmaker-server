<?php
/**
 * unit.php
 *  
 * ProcessMaker Open Source Edition
 * Copyright (C) 2004 - 2008 Colosa Inc.23
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 * 
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * For more information, contact Colosa Inc, 2566 Le Jeune Rd., 
 * Coral Gables, FL, 33134, USA, or email info@colosa.com.
 * 
 */
  ini_set('short_open_tag', 'on');
  ini_set('asp_tags', 'on');
  ini_set('memory_limit', '80M');

    if ( PHP_OS == 'WINNT' ) 
      define('PATH_SEP', '\\');
    else
      define('PATH_SEP', '/');

      
    //***************** Defining the Home Directory *********************************
    $docuroot =  explode ( PATH_SEP , $_SERVER['PWD'] );
    array_pop($docuroot);
    $pathhome = implode( PATH_SEP, $docuroot );
    define('PATH_HOME', $pathhome . PATH_SEP );
    $gulliverConfig =  PATH_HOME . 'engine' . PATH_SEP . 'config' . PATH_SEP . 'paths.php';  
    $definesConfig =  PATH_HOME . 'engine' . PATH_SEP . 'config' . PATH_SEP . 'defines.php';  

    //try to find automatically the trunk directory where are placed the RBAC and Gulliver directories
    //in a normal installation you don't need to change it.
    array_pop($docuroot);
    $pathTrunk = implode( PATH_SEP, $docuroot ) . PATH_SEP ;
    array_pop($docuroot);
    $pathOutTrunk = implode( PATH_SEP, $docuroot ) . PATH_SEP ;
    // to do: check previous algorith for Windows  $pathTrunk = "c:/home/"; 

    define('PATH_TRUNK', $pathTrunk );
    define('PATH_OUTTRUNK', $pathOutTrunk );
       
    if (file_exists( $gulliverConfig ))   {
      include ( $gulliverConfig );
    }
 
    if (file_exists( $definesConfig ))   {
      include ( $definesConfig );
    }
 
  //$_test_dir = realpath(dirname(__FILE__).'/..');
  //require_once( 'lime/lime.php');

  require_once( PATH_THIRDPARTY . 'pake' . PATH_SEP . 'pakeFunction.php');
  require_once( PATH_THIRDPARTY . 'pake' . PATH_SEP . 'pakeGetopt.class.php');
  require_once( PATH_CORE . 'config' . PATH_SEP . 'environments.php');

  if ( !defined ( 'G_ENVIRONMENT') ) 
    define ( 'G_ENVIRONMENT', G_TEST_ENV );

