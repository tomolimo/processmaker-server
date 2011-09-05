<?php
/**
 * classPmScriptTest.php
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
  G::LoadClass ( 'pmScript');

  require_once (  PATH_CORE . "config/databases.php");  

  $dbc = new DBConnection(); 
  $ses = new DBSession( $dbc);
 
  $obj = new PmScript ($dbc); 
  $t   = new lime_test( 7, new lime_output_color() );
 
  $t->diag('class PmScript' );
  $t->isa_ok( $obj  , 'PMScript',  'class PmScript created');

  //method setFields
  $t->can_ok( $obj,      'setFields',   'setFields() is callable' );

//  $result = $obj->setFields ( $aFields);
//  $t->isa_ok( $result,      'NULL',   'call to method setFields ');


  //method setScript
  $t->can_ok( $obj,      'setScript',   'setScript() is callable' );

//  $result = $obj->setScript ( $sScript);
//  $t->isa_ok( $result,      'NULL',   'call to method setScript ');


  //method validSyntax
  $t->can_ok( $obj,      'validSyntax',   'validSyntax() is callable' );

//  $result = $obj->validSyntax ( $sScript);
//  $t->isa_ok( $result,      'NULL',   'call to method validSyntax ');


  //method execute
  $t->can_ok( $obj,      'execute',   'execute() is callable' );

//  $result = $obj->execute ( );
//  $t->isa_ok( $result,      'NULL',   'call to method execute ');


  //method evaluate
  $t->can_ok( $obj,      'evaluate',   'evaluate() is callable' );

//  $result = $obj->evaluate ( );
//  $t->isa_ok( $result,      'NULL',   'call to method evaluate ');


 // $t->fail(  'review all pendings methods in this class');
$t->todo ( "review all pendings methods in this class" );