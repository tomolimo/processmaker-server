<?php
/**
 * classGroupTest.php
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

  require_once( PATH_CORE.'/classes/model/Groupwf.php');

  $obj = new Groupwf ();
  $t   = new lime_test( 10, new lime_output_color() );

  $t->diag('class Groupwf' );
  $t->isa_ok( $obj  , 'Groupwf',  'class Groupwf created');

  //method load
  //#2
  $t->can_ok( $obj,      'getGrpTitle',   'getGrpTitle() is callable' );
  //#3
  $t->can_ok( $obj,      'setGrpTitle',   'setGrpTitle() is callable' );
  //#4
  $t->can_ok( $obj,      'create',   'create() is callable' );
  //#5
  $t->can_ok( $obj,      'update',   'update() is callable' );
  //#6
  $t->can_ok( $obj,      'load',   'load() is callable' );
  //#7
  $t->can_ok( $obj,      'remove',   'remove() is callable' );

  //getGrpUid
  //#8
  $t->is( $obj->getGrpUid(),      '',   'getGrpUid() return empty, when the instance doesnt have any row' );

  //getGrpTitle
  try {
    $obj = new Groupwf ();
    $res = $obj->getGrpTitle();
  }
  catch ( Exception $e ) {
  //#9
    $t->isa_ok( $e,      'Exception',   'getGrpTitle() return error when GRP_UID is not defined' );
  //#10
    $t->is ( $e->getMessage(),      "Error in getGrpTitle, the GRP_UID can't be blank",   'getGrpTitle() return Error in getGrpTitle, the GRP_UID cant be blank' );
  }
