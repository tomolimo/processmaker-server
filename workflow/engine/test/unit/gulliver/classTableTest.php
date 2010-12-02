<?php
/**
 * classTableTest.php
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
G::LoadSystem ( 'table');

$t = new lime_test(24, new lime_output_color());
$obj = new Table();
$method = get_class_methods('Table');
$t->diag('class Table' );
$t->is(  count($method) , 23,  "class Table " . count($method). " methods." );
$t->isa_ok( $obj,  'Table',  'class Table created');
$t->can_ok( $obj,  'SetTo',   'SetTo()');
$t->can_ok( $obj,  'SetSource',   'SetSource()');
$t->can_ok( $obj,  'GetSource',   'GetSource()');
$t->can_ok( $obj,  'TotalCount',   'TotalCount()');
$t->can_ok( $obj,  'Count',   'Count()');
$t->can_ok( $obj,  'CurRow',   'CurRow()');
$t->can_ok( $obj,  'ColumnCount',   'ColumnCount()');
$t->can_ok( $obj,  'Read',   'Read()');
$t->can_ok( $obj,  'Seek',   'Seek()');
$t->can_ok( $obj,  'MoveFirst',   'MoveFirst()');
$t->can_ok( $obj,  'EOF',   'EOF()');
$t->can_ok( $obj,  'AddColumn',   'AddColumn()');
$t->can_ok( $obj,  'AddRawColumn',   'AddRawColumn()');
$t->can_ok( $obj,  'RenderTitle_ajax',   'RenderTitle_ajax()');
$t->can_ok( $obj,  'RenderTitle2',   'RenderTitle2()');
$t->can_ok( $obj,  'RenderColumn',   'RenderColumn()');
$t->can_ok( $obj,  'SetAction',   'SetAction()');
$t->can_ok( $obj,  'setTranslate',   'setTranslate()');
$t->can_ok( $obj,  'translateValue',   'translateValue()');
$t->can_ok( $obj,  'setContext',   'setContext()');
$t->can_ok( $obj,  'ParsingFromHtml',   'ParsingFromHtml()');
                   
$t->todo(  'review all pendings in this class');
