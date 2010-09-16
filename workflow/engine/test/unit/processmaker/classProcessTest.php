<?php
/**
 * classProcessTest.php
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

  require_once( PATH_CORE.'/classes/model/Process.php');

  $obj = new Process (); 
  $t   = new lime_test( 40, new lime_output_color() );

  $t->diag('class Process' );
  $t->isa_ok( $obj  , 'Process',  'class Process created');

  //method load
  //#2
  $t->can_ok( $obj,      'getProTitle',   'getProTitle() is callable' );
  //#3
  $t->can_ok( $obj,      'setProTitle',   'setProTitle() is callable' );
  //#4
  $t->can_ok( $obj,      'getProDescription',   'getProDescription() is callable' );
  //#5
  $t->can_ok( $obj,      'setProDescription',   'setProDescription() is callable' );
  //#6
  $t->can_ok( $obj,      'create',   'create() is callable' );
  //#7
  $t->can_ok( $obj,      'update',   'update() is callable' );
  //#8
  $t->can_ok( $obj,      'load',   'load() is callable' );
  //#9
  $t->can_ok( $obj,      'remove',   'remove() is callable' );

  //getProUid
  //#10
  $t->is( $obj->getProUid(),      '',   'getProUid() return empty, when the instance doesnt have any row' );
  
  //getProTitle
  try {
    $obj = new Process (); 
    $res = $obj->getProTitle();
  } 
  catch ( Exception $e ) {
  //#11
    $t->isa_ok( $e,      'Exception',   'getProTitle() return error when PRO_UID is not defined' );
  //#12
    $t->is ( $e->getMessage(),      "Error in getProTitle, the PRO_UID can't be blank",   'getProTitle() return Error in getProTitle, the PRO_UID cant be blank' );
  }

  //setProDescription
  try {
    $obj = new Process (); 
    $obj->setProDescription('x');
  } 
  catch ( Exception $e ) {
  //#13
    $t->isa_ok( $e,      'Exception',   'setProDescription() return error when PRO_UID is not defined' );
  //#14
    $t->is ( $e->getMessage(),      "Error in setProDescription, the PRO_UID can't be blank",   'setProDescription() return Error in getAppTitle, the APP_UID cant be blank' );
  }

  //getProDescription
  try {
    $obj = new Process (); 
    $res = $obj->getProDescription();
  } 
  catch ( Exception $e ) {
  //#15
    $t->isa_ok( $e,      'Exception',   'getProDescription() return error when PRO_UID is not defined' );
  //#16
    $t->is ( $e->getMessage(),      "Error in getProDescription, the PRO_UID can't be blank",   'getProDescription() return Error in getProDescription, the PRO_UID cant be blank' );
  }

  //setAppDescription
  try {
    $obj = new Process (); 
    $obj->setProDescription('x');
  } 
  catch ( Exception $e ) {
  //#17
    $t->isa_ok( $e,      'Exception',   'setAppDescription() return error when PRO_UID is not defined' );
  //#18
    $t->is ( $e->getMessage(),      "Error in setProDescription, the PRO_UID can't be blank",   'setAppDescription() return Error in getAppDescription, the APP_UID cant be blank' );
  }
  
  
  
  //create new row
  try {
    $obj = new Process (); 
    $res = $obj->create();
  } 
  catch ( Exception $e ) {
  //#19
    $t->isa_ok( $e,      'PropelException',   'create() return error when PRO_UID is not defined' );
  //#20
    $t->like ( $e->getMessage(),      "%The process cannot be created. The USR_UID is empty.%",   'create() return The process cannot be created. The USR_UID is empty.' );
  }

  //create
  try {
    $Fields['USR_UID'] = '1';  // we need a valid user
    $obj = new Process (); 
    $proUid = $obj->create( $Fields );
  //#21
    $t->isa_ok( $proUid,      'string',   'create(), creates a new Process' );
  //#22
    //$t->is ( strlen($proUid),      14,   'create(), creates a new Process, Guid lenth=14 chars' );
    $t->diag ( "strlen($proUid),      14,   'create(), creates a new Process, Guid lenth=14 chars' " );
    $t->is ( strlen($proUid),      32,   'create(), creates a new Process, Guid lenth=32 chars' );
    $res = $obj->load( $proUid );
  //#23
    $t->isa_ok( $res,      'array',   'load(), loads a new Process' );
  //#24
    $t->is ( $res['PRO_UID'],      $proUid,   'load(), loads a new Process, valid PRO_UID' );
  //#25
    $t->is ( strlen($res['PRO_CREATE_DATE']) ,19,   'load(), loads a new Process, valid CREATE_DATE' );
  //#26
    $t->like ( $res['PRO_TITLE'],      '%Default Process%',   'load(), loads a new Process, valid PRO_TITLE' );
  //#27
    $t->is ( $res['PRO_DESCRIPTION'],      'Default Process Description',   'load(), loads a new Process, valid PRO_DESCRIPTION' );

  }
  catch ( Exception $e ) {
    $t->like ( $e->getMessage(),      "%Unable to execute INSERT statement%",   'create() return Error in getAppTitle, the APP_UID cant be blank' );
  }

  //update with empty
  try {
    $obj = new Process ();
    $res = $obj->update( NULL );
  }
  catch ( Exception $e ) {
  //#28
    $t->isa_ok( $e,      'Exception',   'update() returns error when PRO_UID is not defined' );
  //#29
  //  $t->is ( $e->getMessage(),   "This row doesn't exists!",   "update() This row doesn't exists!" );
     $t->todo ( " $e->getMessage() This row doesn't exists!  <>  The row '' in table Process doesn't exists! "  . " line 171");
  }


  //update with $fields
  $newTitle = 'new title ' . rand( 1000, 5000);
  $newDescription = 'new Description '. rand( 1000, 5000);
  $Fields['PRO_UID'] = $proUid;
  $Fields['PRO_TITLE'] = $newTitle;
  $Fields['PRO_PARENT'] = rand( 1000, 5000);
  $Fields['PRO_CREATE_DATE'] = 'now';
  try {
    $obj = new Process ();
    $res = $obj->update( $Fields);
  //#30
    $t->is ( $res,   1,   "update() update 1 row" );
    $Fields = $obj->Load ( $proUid );
  //#26
    $t->is ( $obj->getproUid(),   $proUid,   "update() APP_UID = ". $proUid );
  //#27
    $t->is ( $obj->getProTitle(),   $newTitle,   "update() getAppTitle" );
  //#28
    $t->is ( $Fields['PRO_TITLE'],   $newTitle,   "update() PRO_TITLE= ". $newTitle );
  //#29
    $t->is ( $Fields['PRO_CREATE_DATE'],   date('Y-m-d H:i:s'),   "update() PRO_CREATE_DATE= ". date('Y-m-d H:i:s') );
  }
  catch ( Exception $e ) {
  //#14
    $t->isa_ok( $e,      'PropelException',   'update() return error ' . $e->getMessage() );
    print $e->getMessage();
  }

//remove with empty
  try {
    $obj = new Process ();
    $res = $obj->remove( NULL );
  }
  catch ( Exception $e ) {
  //#30
    $t->isa_ok( $e,      'Exception',   'remove() returns error when UID is not defined' );
  //#31
  //$t->is ( $e->getMessage(),   "This row doesn't exists!",   "remove() This row doesn't exists!" );
    $t->todo ( $e->getMessage() . "  <> The row ''in table Process doesn't exists! " . "     line 213" );
  }

  //remove with $fields
  $Fields['PRO_UID'] = $proUid;
  try {
    $obj = new Process ();
    $res = $obj->remove( $Fields );
  //#32
    $t->is ( $res,   NULL,   "remove() remove row $proUid" );
  }
  catch ( Exception $e ) {
  //#14
    $t->isa_ok( $e,      'PropelException',   'remove() return error ' . $e->getMessage() );
  }

  //remove with $proUid
  $obj = new Process ();
  $proUid = $obj->create( '1' );
  try {
    $obj = new Process ();
    $res = $obj->remove ($proUid );
  //#33
    $t->is ( $res,   NULL,   "remove() remove row $proUid" );
  }
  catch ( Exception $e ) {
  //#14
    $t->isa_ok( $e,      'PropelException',   'remove() return error ' . $e->getMessage() );
  }


  $t->todo(  'Test to verify if delete works correctly :p ...');
  $t->todo(  'how can I change dynamically the Case Title based in a definition, right now the case title is the same as the process title.  We need another field in process to have the case title definition');
  
?>