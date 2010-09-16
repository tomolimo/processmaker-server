<?php
/**
 * classDatesTest.php
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

  G::LoadClass ( 'dates');


  $obj = new Dates ($dbc); 
  $t   = new lime_test( 37, new lime_output_color() );

  $className = Dates;
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
  //To change the case only the first letter of each word, TIA
  //$className = ucwords($className);
  $t->diag("class $className" );

  $t->isa_ok( $obj  , $className,  "class $className created");

  $t->is( count($methods) , 17,  "class $className have " . 17 . ' methods.' );

   //checking method 'calculateDate'
  $t->can_ok( $obj,      'calculateDate',   'calculateDate() is callable' );

  //$result = $obj->calculateDate ( $sInitDate, $iDuration, $sTimeUnit, $iTypeDay, $UsrUid, $ProUid, $TasUid);
  //$t->isa_ok( $result,      'NULL',   'call to method calculateDate ');
  $t->todo( "call to method calculateDate using $sInitDate, $iDuration, $sTimeUnit, $iTypeDay, $UsrUid, $ProUid, $TasUid ");


  //checking method 'calculateDuration'
  $t->can_ok( $obj,      'calculateDuration',   'calculateDuration() is callable' );

  //$result = $obj->calculateDuration ( $sInitDate, $sEndDate, $UsrUid, $ProUid, $TasUid);
  //$t->isa_ok( $result,      'NULL',   'call to method calculateDuration ');
  $t->todo( "call to method calculateDuration using $sInitDate, $sEndDate, $UsrUid, $ProUid, $TasUid ");


  //checking method 'prepareInformation'
  $t->can_ok( $obj,      'prepareInformation',   'prepareInformation() is callable' );

  //$result = $obj->prepareInformation ( $UsrUid, $ProUid, $TasUid);
  //$t->isa_ok( $result,      'NULL',   'call to method prepareInformation ');
  $t->todo( "call to method prepareInformation using $UsrUid, $ProUid, $TasUid ");


  //checking method 'setSkipEveryYear'
  $t->can_ok( $obj,      'setSkipEveryYear',   'setSkipEveryYear() is callable' );

  //$result = $obj->setSkipEveryYear ( $bSkipEveryYear);
  //$t->isa_ok( $result,      'NULL',   'call to method setSkipEveryYear ');
  $t->todo( "call to method setSkipEveryYear using $bSkipEveryYear ");


  //checking method 'addHoliday'
  $t->can_ok( $obj,      'addHoliday',   'addHoliday() is callable' );

  //$result = $obj->addHoliday ( $sDate);
  //$t->isa_ok( $result,      'NULL',   'call to method addHoliday ');
  $t->todo( "call to method addHoliday using $sDate ");


  //checking method 'setHolidays'
  $t->can_ok( $obj,      'setHolidays',   'setHolidays() is callable' );

  //$result = $obj->setHolidays ( $aDates);
  //$t->isa_ok( $result,      'NULL',   'call to method setHolidays ');
  $t->todo( "call to method setHolidays using $aDates ");


  //checking method 'setWeekends'
  $t->can_ok( $obj,      'setWeekends',   'setWeekends() is callable' );

  //$result = $obj->setWeekends ( $aWeekends);
  //$t->isa_ok( $result,      'NULL',   'call to method setWeekends ');
  $t->todo( "call to method setWeekends using $aWeekends ");


  //checking method 'skipDayOfWeek'
  $t->can_ok( $obj,      'skipDayOfWeek',   'skipDayOfWeek() is callable' );

  //$result = $obj->skipDayOfWeek ( $iDayNumber);
  //$t->isa_ok( $result,      'NULL',   'call to method skipDayOfWeek ');
  $t->todo( "call to method skipDayOfWeek using $iDayNumber ");


  //checking method 'addNonWorkingRange'
  $t->can_ok( $obj,      'addNonWorkingRange',   'addNonWorkingRange() is callable' );

  //$result = $obj->addNonWorkingRange ( $sDateA, $sDateB);
  //$t->isa_ok( $result,      'NULL',   'call to method addNonWorkingRange ');
  $t->todo( "call to method addNonWorkingRange using $sDateA, $sDateB ");


  //checking method 'addDays'
  $t->can_ok( $obj,      'addDays',   'addDays() is callable' );

  //$result = $obj->addDays ( $iInitDate, $iDaysCount, $addSign);
  //$t->isa_ok( $result,      'NULL',   'call to method addDays ');
  $t->todo( "call to method addDays using $iInitDate, $iDaysCount, $addSign ");


  //checking method 'addHours'
  $t->can_ok( $obj,      'addHours',   'addHours() is callable' );

  //$result = $obj->addHours ( $sInitDate, $iHoursCount, $addSign);
  //$t->isa_ok( $result,      'NULL',   'call to method addHours ');
  $t->todo( "call to method addHours using $sInitDate, $iHoursCount, $addSign ");


  //checking method 'inRange'
  $t->can_ok( $obj,      'inRange',   'inRange() is callable' );

  //$result = $obj->inRange ( $iDate);
  //$t->isa_ok( $result,      'NULL',   'call to method inRange ');
  $t->todo( "call to method inRange using $iDate ");


  //checking method 'truncateTime'
  $t->can_ok( $obj,      'truncateTime',   'truncateTime() is callable' );

  //$result = $obj->truncateTime ( $iDate);
  //$t->isa_ok( $result,      'NULL',   'call to method truncateTime ');
  $t->todo( "call to method truncateTime using $iDate ");


  //checking method 'getTime'
  $t->can_ok( $obj,      'getTime',   'getTime() is callable' );

  //$result = $obj->getTime ( $iDate);
  //$t->isa_ok( $result,      'NULL',   'call to method getTime ');
  $t->todo( "call to method getTime using $iDate ");


  //checking method 'setTime'
  $t->can_ok( $obj,      'setTime',   'setTime() is callable' );

  //$result = $obj->setTime ( $iDate, $aTime);
  //$t->isa_ok( $result,      'NULL',   'call to method setTime ');
  $t->todo( "call to method setTime using $iDate, $aTime ");


  //checking method 'listForYear'
  $t->can_ok( $obj,      'listForYear',   'listForYear() is callable' );

  //$result = $obj->listForYear ( $iYear);
  //$t->isa_ok( $result,      'NULL',   'call to method listForYear ');
  $t->todo( "call to method listForYear using $iYear ");


  //checking method 'changeYear'
  $t->can_ok( $obj,      'changeYear',   'changeYear() is callable' );

  //$result = $obj->changeYear ( $iDate, $iYear);
  //$t->isa_ok( $result,      'NULL',   'call to method changeYear ');
  $t->todo( "call to method changeYear using $iDate, $iYear ");



  $t->todo (  'review all pendings methods in this class');
