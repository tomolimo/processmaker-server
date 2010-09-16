<?php
/**
 * classApplicationTest.php
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
  $unitFilename = $_SERVER['PWD'] . '/test/bootstrap/unit.php' ;
  require_once( $unitFilename );

  require_once( PATH_THIRDPARTY . '/lime/lime.php');
  require_once( PATH_THIRDPARTY.'lime/yaml.class.php');
  require_once( 'propel/Propel.php' );
  Propel::init(  PATH_CORE . "config/databases.php");


  G::LoadThirdParty('smarty/libs','Smarty.class');
  G::LoadSystem ( 'error');
  G::LoadSystem ( 'xmlform');
  G::LoadSystem ( 'xmlDocument');
  G::LoadSystem ( 'form');
  G::LoadSystem ( 'testTools');

  require_once( PATH_CORE.'/classes/model/Application.php');

  $obj = new Application ();  
  $t   = new lime_test( 23, new lime_output_color() );

  $t->diag('class Application' );
  $t->isa_ok( $obj  , 'Application',  'class Application created');

  //method load
  //#2
  $t->can_ok( $obj,      'getAppTitle',   'getAppTitle() is callable' );
  //#3
  $t->can_ok( $obj,      'setAppTitle',   'setAppTitle() is callable' );
  //#4
  $t->can_ok( $obj,      'create',   'create() is callable' );
  //#5
  $t->can_ok( $obj,      'update',   'update() is callable' );
  //#6
  $t->can_ok( $obj,      'load',   'load() is callable' );
  //#7
  $t->can_ok( $obj,      'remove',   'remove() is callable' );
  //#8
  $t->can_ok( $obj,      'createApplication',   'createApplication() is callable' );

  //getAppUid
  //#9
  $t->is( $obj->getAppUid(),      '',   'getAppUid() return empty, when the instance doesnt have any row' );

  //getAppTitle
  try {
    $obj = new Application ();
    $res = $obj->getAppTitle();
  }
  catch ( Exception $e ) {
  //#10
    $t->isa_ok( $e,      'Exception',   'getAppTitle() return error when APP_UID is not defined' );
  //#11
    $t->is ( $e->getMessage(),      "Error in getAppTitle, the APP_UID can't be blank",   'getAppTitle() return Error in getAppTitle, the APP_UID cant be blank' );
  }

  //setAppTitle
  try {
    $obj = new Application ();
    $obj->setAppTitle('x');
  }
  catch ( Exception $e ) {
  //#12
    $t->isa_ok( $e,      'Exception',   'setAppTitle() return error when APP_UID is not defined' );
  //#13
    $t->is ( $e->getMessage(), "Error in setAppTitle, the APP_UID can't be blank",   'setAppTitle() return Error in getAppTitle, the APP_UID cant be blank' );
  }
   //create
  try {
    $obj = new Application ();
    $res = $obj->create();
  }
  catch ( Exception $e ) {
  //#14
    $t->isa_ok( $e,      'PropelException',   'create() return error when APP_UID is not defined' );
  //#15
    $t->like ( $e->getMessage(),      "%Unable to execute INSERT statement%",   'getAppTitle() return Error in getAppTitle, the APP_UID cant be blank' );
  }

  //create
  try {
    $obj = new Application ();
    $appUid = $obj->create( '1' );
  //#16
    $t->isa_ok( $appUid,      'string',   'create(), creates a new application' );
  //#17
    $t->is ( strlen($appUid),      14,   'create(), creates a new application, Guid lenth=14 chars' );
    $res = $obj->load( $appUid );
  //#18
    $t->isa_ok( $res,      'array',   'load(), loads a new application' );
  //#19
    $t->is ( $res['APP_UID'],      $appUid,   'load(), loads a new application, valid APP_UID' );
  //#20
    $t->is ( $res['APP_FINISH_DATE'],'1902-01-01 00:00:00',   'load(), loads a new application, valid FINISH_DATE' );
  //#21
    $t->like ( $res['APP_TITLE'],      '%#%',   'load(), loads a new application, valid APP_TITLE' );
  //#22
    $t->is ( $res['APP_PARENT'],      '',   'load(), loads a new application, valid APP_PARENT' );

  }
  catch ( Exception $e ) {
    $t->like ( $e->getMessage(),      "%Unable to execute INSERT statement%",   'create() return Error in getAppTitle, the APP_UID cant be blank' );
  }

  //update with empty
  try {
    $obj = new Application ();
    $res = $obj->update( NULL );
  }
  catch ( Exception $e ) {
  //#23
    $t->isa_ok( $e,      'Exception',   'update() returns error when APP_UID is not defined' );
  //#24 
  
  $t->is ( $e->getMessage(),   "The row '' in table APPLICATION doesn't exists!",   "update() This row doesn't exists!" );

  }

  //update with $fields
  $newTitle = 'new title';
  $Fields['APP_UID'] = $appUid;
  $Fields['APP_TITLE'] = $newTitle;
  $Fields['APP_PARENT'] = rand( 1000, 5000);
  $Fields['APP_INIT_DATE'] = 'now';
  try {
    $obj = new Application ();
    $res = $obj->update( $Fields);
  //#25
    $t->is ( $res,   1,   "update() update 1 row" );
    $Fields = $obj->Load ( $appUid );
  //#26
    $t->is ( $obj->getAppUid(),   $appUid,   "update() APP_UID = ". $appUid );
  //#27
    $t->is ( $obj->getAppTitle(),   $newTitle,   "update() getAppTitle" );
  //#28
    $t->is ( $Fields['APP_TITLE'],   $newTitle,   "update() APP_TITLE= ". $newTitle );
  //#29
    $t->is ( $Fields['APP_INIT_DATE'],   date('Y-m-d H:i:s'),   "update() APP_INIT_DATE= ". date('Y-m-d H:i:s') );
  }
  catch ( Exception $e ) {
  //#14
   // $t->isa_ok( $e,      'PropelException',   'update() return error ' . $e->getMessage() );
      $t->isa_ok( $e,      'Exception',   'update() return error ' . $e->getMessage() );
  }

//remove with empty
  try {
    $obj = new Application ();
    $res = $obj->remove( NULL );
  }
  catch ( Exception $e ) {
  //#30
    $t->isa_ok( $e,      'Exception',   'remove() returns error when APP_UID is not defined' );
  //#31
    $t->is ( $e->getMessage(),   "The row '' in table Application doesn't exists!",   "remove() This row doesn't exists!" );
  }
/*
  //remove with $fields
  $Fields['APP_UID'] = $appUid;
  try {
    $obj = new Application ();
    //$res = $obj->remove( $Fields ); 
    $t->todo ( "check why this sentence is not working : $res = $obj->remove( $Fields ); " );

  //#32
    $t->is ( $res,   NULL,   "remove() remove row $appUid" );
  }
  catch ( Exception $e ) {
  //#14
 //   $t->isa_ok( $e,      'PropelException',   'remove() return error ' . $e->getMessage() );
      $t->isa_ok( $e,      'Exception',   'remove() return error ' . $e->getMessage() );
  }

  //remove with $appUid
  $obj = new Application ();
  $appUid = $obj->create( '1' );
  try {
    $obj = new Application ();
    //$res = $obj->remove ($appUid );
  //#33
    //$t->is ( $res,   NULL,   "remove() remove row $appUid" );
  }
  catch ( Exception $e ) {
  //#14
    $t->isa_ok( $e,      'PropelException',   'remove() return error ' . $e->getMessage() );
  }
*/

  $t->todo(  'Test to verify if delete works correctly :p ...');
  $t->todo(  'how can I change dynamically the Case Title based in a definition, right now the case title is the same as the process title.  We need another field in process to have the case title definition');

?>

