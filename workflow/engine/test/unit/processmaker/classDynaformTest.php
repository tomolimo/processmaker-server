<?php
/**
 * classDynaformTest.php
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

  require_once( PATH_CORE.'/classes/model/Dynaform.php');

  $obj = new Dynaform ();
  $t   = new lime_test( 18, new lime_output_color() );

  $t->diag('class Dynaform' );
  $t->isa_ok( $obj  , 'Dynaform',  'class Dynaform created');

  //method load
  //#2
  $t->can_ok( $obj,      'getDynTitle',   'getDynTitle() is callable' );
  //#3
  $t->can_ok( $obj,      'setDynTitle',   'setDynTitle() is callable' );
  //#4
  $t->can_ok( $obj,      'getDynDescription',   'getDynDescription() is callable' );
  //#5
  $t->can_ok( $obj,      'setDynDescription',   'setDynDescription() is callable' );
  //#6
  $t->can_ok( $obj,      'create',   'create() is callable' );
  //#7
  $t->can_ok( $obj,      'update',   'update() is callable' );
  //#8
  $t->can_ok( $obj,      'load',   'load() is callable' );
  //#9
  $t->can_ok( $obj,      'remove',   'remove() is callable' );

  //getDynUid
  //#10
  $t->is( $obj->getDynUid(),      '',   'getDynUid() return empty, when the instance doesnt have any row' );
  
  //getDynTitle
  try {
    $obj = new Dynaform (); 
    $res = $obj->getDynTitle();
  } 
  catch ( Exception $e ) {
  //#11
    $t->isa_ok( $e,      'Exception',   'getDynTitle() return error when DYN_UID is not defined' );
  //#12
    $t->is ( $e->getMessage(),      "Error in getDynTitle, the DYN_UID can't be blank",   'getDynTitle() return Error in getDynTitle, the DYN_UID cant be blank' );
  }

  //setDynDescription
  try {
    $obj = new Dynaform (); 
    $obj->setDynDescription('x');
  } 
  catch ( Exception $e ) {
  //#13
    $t->isa_ok( $e,      'Exception',   'setDynDescription() return error when DYN_UID is not defined' );
  //#14
    $t->is ( $e->getMessage(),      "Error in setDynDescription, the DYN_UID can't be blank",   'setDynDescription() return Error in getAppTitle, the APP_UID cant be blank' );
  }

  //getDynDescription
  try {
    $obj = new Dynaform (); 
    $res = $obj->getDynDescription();
  } 
  catch ( Exception $e ) {
  //#15
    $t->isa_ok( $e,      'Exception',   'getDynDescription() return error when DYN_UID is not defined' );
  //#16
    $t->is ( $e->getMessage(),      "Error in getDynDescription, the DYN_UID can't be blank",   'getDynDescription() return Error in getDynDescription, the DYN_UID cant be blank' );
  }

  //setAppDescription
  try {
    $obj = new Dynaform (); 
    $obj->setDynDescription('x');
  } 
  catch ( Exception $e ) {
  //#17
    $t->isa_ok( $e,      'Exception',   'setAppDescription() return error when DYN_UID is not defined' );
  //#18
    $t->is ( $e->getMessage(),      "Error in setDynDescription, the DYN_UID can't be blank",   'setAppDescription() return Error in getAppDescription, the APP_UID cant be blank' );
  }
  


