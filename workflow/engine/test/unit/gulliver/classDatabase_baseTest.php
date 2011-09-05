<?php
/**
	* classDatabase_baseTest.php
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
  G::LoadSystem ( 'database_base');
  $counter=1; 
  $t = new lime_test( 12, new lime_output_color());
  $obj = new database_base();
  $methods = get_class_methods('database_base');

  $t->diag('class database_base' );
  
  /* Listing Method */
  
  $t->isa_ok( $obj  , 'database_base',  'class database_base created');
  $t->todo ( 'Listing Method' );
  $t->is(  count ( $methods )  , 8,  "class database_base " . count ( $methods )  . " methods." );
  /* checking methods */ 
  
	$t->can_ok( $obj,      '__construct'						,   $counter++.' __construct()');
	$t->can_ok( $obj,      'generateDropTableSQL'		,   $counter++.' generateDropTableSQL()');
	$t->can_ok( $obj,      'generateCreateTableSQL' ,   $counter++.' generateCreateTableSQL()');
	$t->can_ok( $obj,      'generateDropColumnSQL'	,   $counter++.' generateDropColumnSQL()');
	$t->can_ok( $obj,      'generateAddColumnSQL'		,   $counter++.' generateAddColumnSQL()');
	$t->can_ok( $obj,      'generateChangeColumnSQL',   $counter++.' generateChangeColumnSQL()');
	$t->can_ok( $obj,      'executeQuery'	 					,   $counter++.' executeQuery()');
	$t->can_ok( $obj,      'close'	 								,   $counter++.' close()');
	
  $t->todo (  'Review, specific examples.');	