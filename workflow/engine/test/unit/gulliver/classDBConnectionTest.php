<?php
	/**
	 * classDBConnectionTest.php
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
		$counter   =1;
		$testItems = 0;
		if ( isset ( $G_ENVIRONMENTS ) ) {
			  $dbfile = $G_ENVIRONMENTS[ G_TEST_ENV ][ 'dbfile']; 
			  if ( !file_exists ( $dbfile ) ) {
			   printf("%s \n", pakeColor::colorize( "dbfile $dbfile doesn't exist for environment " . G_ENVIRONMENT  , 'ERROR'));
			   exit (200);
			  }else
			       include ( $dbfile );
	 	}else
		exit (201);
   	require_once( PATH_GULLIVER . 'class.dbconnection.php');
    $obj = new DBConnection();
		$method = array ( );
		$methods = get_class_methods('DBConnection'); 
		 		
		$t = new lime_test( 20 , new lime_output_color());
		$t->diag('class DBConnection' );
	  
	  /* Listing Method */
	  $t->is(  count( $methods ) , 8,  "class DBConnection " . 8 . " methods." );    
	  $t->todo ( 'Listing Method' );
	  $t->is( $methods[0]  , 'DBConnection',  $counter++.' DBConnection');
	  $t->is( $methods[1]  , 'Reset'			 ,  $counter++.' Reset');
	  $t->is( $methods[2]  , 'Free'				 ,  $counter++.' Free');
	  $t->is( $methods[3]  , 'Close'			 ,  $counter++.' Close');
	  $t->is( $methods[4]  , 'logError'	   ,  $counter++.' logError');
	  $t->is( $methods[5]  , 'traceError'	 ,  $counter++.' traceError');
	  $t->is( $methods[6]  , 'printArgs'	 ,  $counter++.' printArgs');
	  $t->is( $methods[7]  , 'GetLastID'	 ,  $counter++.' GetLastID');
	  
	  /* checking methods */ 
	  $t->todo( 'checking methods' );
		$t->can_ok( $obj,      'DBConnection',   'DBConnection()');
		$t->can_ok( $obj,      'Reset'			 ,   'Reset()');
		$t->can_ok( $obj,      'Free'				 ,   'Free()');
		$t->can_ok( $obj,      'Close'			 ,   'Close()');
		$t->can_ok( $obj,      'logError'		 ,   'logError()');
		$t->can_ok( $obj,      'traceError'	 ,   'traceError()');
		$t->can_ok( $obj,      'printArgs'	 ,   'printArgs()');
		$t->can_ok( $obj,      'GetLastID'	 ,   'GetLastID()');
    $t->todo (  'Review, specific examples.');	