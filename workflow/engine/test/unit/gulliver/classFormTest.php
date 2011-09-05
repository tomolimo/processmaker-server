<?php
/**
 * classFormTest.php
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
  G::LoadSystem ( 'xmlform');
  G::LoadSystem ( 'xmlDocument');
  G::LoadSystem ( 'form');
  require_once( 'propel/Propel.php' );
  require_once ( "creole/Creole.php" );
  require_once (  PATH_CORE . "config/databases.php");  
	$counter=1;
	$obj = new Form ( 'login/login');
	$method = array ( );
	$testItems = 0;
	$methods = get_class_methods('Form');
	$t = new lime_test(15, new lime_output_color());
	$t->diag('class Form' );
	$t->is(  count($methods) , 12,  "class Form " . count($methods) . " methods." );
	$t->can_ok( $obj,      'Form',$counter++.' Form()');
	$t->can_ok( $obj,      'setDefaultValues',$counter++.' setDefaultValues()');
	$t->can_ok( $obj,      'printTemplate',   $counter++.' printTemplate()');
	$t->can_ok( $obj,      'render',   				$counter++.' render()');
	$t->can_ok( $obj,      'setValues',   		$counter++.' setValues()');
	$t->can_ok( $obj,      'getFields',       $counter++.' getFields()');
	$t->can_ok( $obj,      'validatePost',    $counter++.' validatePost()');
	$t->can_ok( $obj,      'validateArray',   $counter++.' validateArray()');
	$t->can_ok( $obj,      'getVars',   		  $counter++.' getVars()');
	$t->can_ok( $obj,      'validateRequiredFields',   $counter++.' validateRequiredFields()');
	$t->can_ok( $obj,      'parseFile',   		$counter++.' parseFile()');
	$t->can_ok( $obj,      'cloneObject',   	$counter++.' cloneObject()');
	$t->is(  count($methods) , --$counter ,  "ok");
	$t->todo(  'review all pendings in this class');
