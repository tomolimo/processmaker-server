<?php
/**
 * classArchiveTest.php
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
  require_once ( "creole/Creole.php" );
  Propel::init(  PATH_CORE . "config/databases.php");

  G::LoadThirdParty('smarty/libs','Smarty.class');
  G::LoadSystem ( 'error');
  G::LoadSystem ( 'xmlform');
  G::LoadSystem ( 'xmlDocument');
  G::LoadSystem ( 'form');

  require_once (  PATH_CORE . "config/databases.php");  

  G::LoadClass ( 'archive');
  
  $obj = new Archive ($dbc); 
  $t   = new lime_test( 27, new lime_output_color() );

  $className = Archive;
  $className = strtolower ( substr ($className, 0,1) ) . substr ($className, 1 );
  
  $reflect = new ReflectionClass( $className );
	$method = array ( );
	$testItems = 0;
 
  foreach ( $reflect->getMethods() as $reflectmethod )  {  
  	$params = '';
  	foreach ( $reflectmethod->getParameters() as $key => $row )   {  
  	  if ( $params != '' ) $params .= ', ';
  	  $params .= '$' . $row->name;  
  	}

 		$testItems++;
  	$methods[ $reflectmethod->getName() ] = $params;
  }
 
  $t->diag( "class $className " );
  $t->isa_ok( $obj  , $className,  "class $className created" );

  $t->is( count($methods) , 12,  "class $className have " . 12 . ' methods.' );

  //checking method 'archive'
  $t->can_ok( $obj,      'archive',   'archive() is callable' );

  //$result = $obj->archive ( $name);
  //$t->isa_ok( $result,      'NULL',   'call to method archive ');
  $t->todo( "call to method archive using $name ");


  //checking method 'set_options'
  $t->can_ok( $obj,      'set_options',   'set_options() is callable' );

  //$result = $obj->set_options ( $options);
  //$t->isa_ok( $result,      'NULL',   'call to method set_options ');
  $t->todo( "call to method set_options using $options ");


  //checking method 'create_archive'
  $t->can_ok( $obj,      'create_archive',   'create_archive() is callable' );

  //$result = $obj->create_archive ( );
  //$t->isa_ok( $result,      'NULL',   'call to method create_archive ');
  $t->todo( "call to method create_archive using  ");


  //checking method 'add_data'
  $t->can_ok( $obj,      'add_data',   'add_data() is callable' );

  //$result = $obj->add_data ( $data);
  //$t->isa_ok( $result,      'NULL',   'call to method add_data ');
  $t->todo( "call to method add_data using $data ");


  //checking method 'make_list'
  $t->can_ok( $obj,      'make_list',   'make_list() is callable' );

  //$result = $obj->make_list ( );
  //$t->isa_ok( $result,      'NULL',   'call to method make_list ');
  $t->todo( "call to method make_list using  ");


  //checking method 'add_files'
  $t->can_ok( $obj,      'add_files',   'add_files() is callable' );

  //$result = $obj->add_files ( $list);
  //$t->isa_ok( $result,      'NULL',   'call to method add_files ');
  $t->todo( "call to method add_files using $list ");


  //checking method 'exclude_files'
  $t->can_ok( $obj,      'exclude_files',   'exclude_files() is callable' );

  //$result = $obj->exclude_files ( $list);
  //$t->isa_ok( $result,      'NULL',   'call to method exclude_files ');
  $t->todo( "call to method exclude_files using $list ");


  //checking method 'store_files'
  $t->can_ok( $obj,      'store_files',   'store_files() is callable' );

  //$result = $obj->store_files ( $list);
  //$t->isa_ok( $result,      'NULL',   'call to method store_files ');
  $t->todo( "call to method store_files using $list ");


  //checking method 'list_files'
  $t->can_ok( $obj,      'list_files',   'list_files() is callable' );

  //$result = $obj->list_files ( $list);
  //$t->isa_ok( $result,      'NULL',   'call to method list_files ');
  $t->todo( "call to method list_files using $list ");


  //checking method 'parse_dir'
  $t->can_ok( $obj,      'parse_dir',   'parse_dir() is callable' );

  //$result = $obj->parse_dir ( $dirname);
  //$t->isa_ok( $result,      'NULL',   'call to method parse_dir ');
  $t->todo( "call to method parse_dir using $dirname ");


  //checking method 'sort_files'
  $t->can_ok( $obj,      'sort_files',   'sort_files() is callable' );

  //$result = $obj->sort_files ( $a, $b);
  //$t->isa_ok( $result,      'NULL',   'call to method sort_files ');
  $t->todo( "call to method sort_files using $a, $b ");


  //checking method 'download_file'
  $t->can_ok( $obj,      'download_file',   'download_file() is callable' );

  //$result = $obj->download_file ( );
  //$t->isa_ok( $result,      'NULL',   'call to method download_file ');
  $t->todo( "call to method download_file using  ");

  $t->todo (  'review all pendings methods in this class');
 
 ?>