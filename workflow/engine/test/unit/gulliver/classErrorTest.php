<?php
/**
 * classErrorTest.php
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
  if ( !defined ('PATH_THIRDPARTY') ) {
    require_once(  $_SERVER['PWD']. '/test/bootstrap/unit.php');
  }

  require_once( PATH_THIRDPARTY . 'lime/lime.php');
  define ( 'G_ENVIRONMENT', G_TEST_ENV);
  require_once( PATH_CORE . 'config' . PATH_SEP . 'environments.php');

  global $G_ENVIRONMENTS;
  if ( isset ( $G_ENVIRONMENTS ) ) {
    $dbfile = $G_ENVIRONMENTS[ G_TEST_ENV ][ 'dbfile'];
    if ( !file_exists ( $dbfile ) ) {
      printf("%s \n", pakeColor::colorize( "dbfile $dbfile doesn't exist for environment " . G_ENVIRONMENT  , 'ERROR'));
      exit (200);
    }
    else
     include ( $dbfile );
  }
  else
   exit (201);

  require_once( PATH_GULLIVER . 'class.dbconnection.php');
  require_once( PATH_GULLIVER . 'class.error.php');

$obj = new G_Error(); 
$method = array ( );
$testItems = 0;
$class_methods = get_class_methods('G_Error');
foreach ($class_methods as $method_name) {
    $methods[ $testItems ] = $method_name;
    $testItems++;
}

$t = new lime_test(11, new lime_output_color());

$t->diag('class error' );
     //
     $t->is(  $testItems , 13,  "class G_Error " . 13 . " methods." );
$t->isa_ok( $obj  , 'G_Error',  'class G_Error created');
$t->is( G_ERROR , -100,         'G_ERROR constant defined');
$t->is( G_ERROR_ALREADY_ASSIGNED , -118,         'G_ERROR_ALREADY_ASSIGNED defined');

$obj = new G_Error( "string" ); 
$t->is( $obj->code, -1,    'default code error');
$t->is( $obj->message, "G Error: string",    'default message error');
$t->is( $obj->level, E_USER_NOTICE,    'default level error');

$obj = new G_Error( G_ERROR_SYSTEM_UID ); 
$t->is( $obj->code, -105,    'code error');
$t->is( $obj->message, "G Error: ",    'message error');

$t->can_ok( $obj, "errorMessage",  "exists method errorMessage");
$msg = $obj->errorMessage ( G_ERROR );
//$t->is( $msg->code, -100,    'fail in method errorMessage');
$t->todo(  'fail in method errorMessage');
