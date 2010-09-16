<?php
/**
	* classDatabase_mysqlTest.php
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
  G::LoadThirdParty('smarty/libs','Smarty.class');
  G::LoadSystem ( 'xmlform');
  G::LoadSystem ( 'xmlDocument');
  G::LoadSystem ( 'form');
  G::LoadSystem ( 'database_mysql');
  $t = new lime_test( 24, new lime_output_color());
  $obj = new database_base();
  $method = array (); 
  $testItems = 0;
  $methods = get_class_methods('database');
  $t->diag('class database' );
  $t->is(  count($methods) , 21,  "class database " . count($methods) . " methods." );
  $t->is( $methods[0]  , '__construct',  '__construct');
  $t->is( $methods[1]  , 'generateCreateTableSQL'    ,  'generateCreateTableSQL');
  $t->is( $methods[2]  , 'generateDropTableSQL'      ,  'generateDropTableSQL');
  $t->is( $methods[3]  , 'generateDropColumnSQL'     ,  'generateDropColumnSQL');
  $t->is( $methods[4]  , 'generateAddColumnSQL'      ,  'generateAddColumnSQL');
  $t->is( $methods[5]  , 'generateChangeColumnSQL'   ,  'generateChangeColumnSQL');
  $t->is( $methods[6]  , 'generateGetPrimaryKeysSQL' ,  'generateGetPrimaryKeysSQL');
  $t->is( $methods[7]  , 'generateDropPrimaryKeysSQL',  'generateDropPrimaryKeysSQL');
  $t->is( $methods[8]  , 'generateAddPrimaryKeysSQL' ,  'generateAddPrimaryKeysSQL');
  $t->is( $methods[9]  , 'generateDropKeySQL'        ,  'generateDropKeySQL');
  $t->is( $methods[10] , 'generateAddKeysSQL'				 ,  'generateAddKeysSQL');
  $t->is( $methods[11] , 'generateShowTablesSQL'     ,  'generateShowTablesSQL');
  $t->is( $methods[12] , 'generateShowTablesLikeSQL' ,  'generateShowTablesLikeSQL');
  $t->is( $methods[13] , 'generateDescTableSQL'      ,  'generateDescTableSQL');
  $t->is( $methods[14] , 'generateTableIndexSQL'  	 ,  'generateTableIndexSQL');
  $t->is( $methods[15] , 'isConnected'               ,  'isConnected');
  $t->is( $methods[16] , 'logQuery'     						 ,  'logQuery');
  $t->is( $methods[17] , 'executeQuery'              ,  'executeQuery');
  $t->is( $methods[18] , 'countResults'              ,  'countResults');
  $t->is( $methods[19] , 'getRegistry'							 ,  'getRegistry');
  $t->is( $methods[20] , 'close'     								 ,  'close');
  $t->isa_ok( $obj  , 'database_base',  'class database_base created');
  $t->todo(  'Examples');