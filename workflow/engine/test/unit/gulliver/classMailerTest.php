<?php
/**
 * classMailerTest.php
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
	G::LoadSystem ( 'mailer');
	$obj = new mailer ();
	$testItems = 0;
	$method = get_class_methods('mailer');
	$t = new lime_test(11, new lime_output_color());
	$t->diag('class mailer' );
	$t->is(  count($method) , 7,  "class mailer " . count($method) . " methods." );
	$t->isa_ok( $obj  , 'mailer',  'class mailer created');
	$t->can_ok( $obj,      'instanceMailer',   'instanceMailer()');
	$t->can_ok( $obj,      'arpaEMAIL',   'arpaEMAIL()');
	$t->can_ok( $obj,      'sendTemplate',   'sendTemplate()');
	$t->can_ok( $obj,      'sendHtml',   'sendHtml()');
	$t->can_ok( $obj,      'sendText',   'sendText()');
	$t->can_ok( $obj,      'replaceFields',   'replaceFields()');
	$t->can_ok( $obj,      'html2text',   'html2text()');
	$t->todo(  'delete function html2text !!!');
	$t->todo(  'review all pendings in this class');
