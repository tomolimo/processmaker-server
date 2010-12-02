<?php
/**
 * classMenuTest.php
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
		
		G::LoadThirdParty('pear/json','class.json');
		G::LoadThirdParty('smarty/libs','Smarty.class');
		G::LoadSystem ( 'xmlform');
		G::LoadSystem ( 'xmlDocument');
		G::LoadSystem ( 'form');
		G::LoadSystem ( 'menu');
		 
		$obj = new Menu ();
		$counter=1; 
		$method = get_class_methods('Menu');print_r($class_methods);
		$t = new lime_test(15, new lime_output_color());
		$t->diag('class Menu' );
		$t->is(  count($method) , 11,  "class Menu " . count($method) . " methods." );
		$t->isa_ok( $obj  , 'Menu',  'class Menu created');
		$t->can_ok( $obj,      'SetClass'				 ,   $counter++.' SetClass()');
		$t->can_ok( $obj,      'Load'						 ,   $counter++.' Load()');
		$t->can_ok( $obj,      'OptionCount'		 ,   $counter++.' OptionCount()');
		$t->can_ok( $obj,      'AddOption'			 ,   $counter++.' AddOption()');
		$t->can_ok( $obj,      'AddIdOption'		 ,   $counter++.' AddIdOption()');
		$t->can_ok( $obj,      'AddRawOption'		 ,   $counter++.' AddRawOption()');
		$t->can_ok( $obj,      'AddIdRawOption'  ,   $counter++.' AddIdRawOption()');
		$t->can_ok( $obj,      'DisableOptionPos',   $counter++.' DisableOptionPos()');
		$t->can_ok( $obj,      'DisableOptionId' ,   $counter++.' DisableOptionId()');
		$t->can_ok( $obj,      'RenderOption'  	 ,   $counter++.' RenderOption()');
		$t->can_ok( $obj,      'generateArrayForTemplate',   $counter++.' generateArrayForTemplate()');
		$t->is(  count($method) , --$counter ,  " methods " );
		$t->todo(  'review all pendings in this class');
