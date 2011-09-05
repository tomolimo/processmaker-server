<?php
/**
 * classDynaFormFieldTest.php
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
 
  G::LoadThirdParty('smarty/libs','Smarty.class');
  G::LoadSystem ( 'error');
  G::LoadSystem ( 'xmlform');
  G::LoadSystem ( 'xmlDocument');
  G::LoadSystem ( 'form');
  G::LoadSystem ( 'dbconnection');
  G::LoadSystem ( 'dbsession');
  G::LoadSystem ( 'dbrecordset');
  G::LoadSystem ( 'dbtable');
  G::LoadClass ( 'dynaFormField');

  require_once (  PATH_CORE . "config/databases.php");  

  $dbc = new DBConnection(); 
  $ses = new DBSession( $dbc);
 
  $obj = new DynaFormField ($dbc); 
  $t   = new lime_test(  6, new lime_output_color() );
 
  $t->diag('class DynaFormField' );
  $t->isa_ok( $obj  , 'DynaFormField',  'class DynaFormField created');

  //method Load
  $t->can_ok( $obj,      'Load',   'Load() is callable' );

  //  $result = $obj->Load ( $sUID);
  //  $t->isa_ok( $result,      'NULL',   'call to method Load ');


  //method Delete
  $t->can_ok( $obj,      'Delete',   'Delete() is callable' );

  //  $result = $obj->Delete ( $uid);
  //  $t->isa_ok( $result,      'NULL',   'call to method Delete ');


  //method Save
  $t->can_ok( $obj,      'Save',   'Save() is callable' );

  //  $result = $obj->Save ( $Fields, $labels, $options);
  //  $t->isa_ok( $result,      'NULL',   'call to method Save ');


  //method isNew
  $t->can_ok( $obj,      'isNew',   'isNew() is callable' );

  //  $result = $obj->isNew ( );
  //  $t->isa_ok( $result,      'NULL',   'call to method isNew ');


  //$t->fail(  'review all pendings methods in this class');
    $t->todo(  "review all pendings methods in this class" );