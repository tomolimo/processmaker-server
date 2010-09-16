<?php
/**
 * classTriggerTest.php
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
  require_once (  PATH_CORE . "config/databases.php");
  require_once ( "propel/Propel.php" );
  Propel::init(  PATH_CORE . "config/databases.php");

  G::LoadThirdParty('smarty/libs','Smarty.class');
  G::LoadSystem ( 'error');
  G::LoadSystem ( 'xmlform');
  G::LoadSystem ( 'xmlDocument');
  G::LoadSystem ( 'form');
  G::LoadSystem ( 'dbconnection');
  G::LoadSystem ( 'dbsession');
  G::LoadSystem ( 'dbrecordset');
  G::LoadSystem ( 'dbtable');
  //G::LoadClass ( 'user');
  G::LoadSystem ( 'testTools');
  require_once(PATH_CORE.'/classes/model/Triggers.php');

  require_once (  PATH_CORE . "config/databases.php");

  $dbc = new DBConnection();
  $ses = new DBSession( $dbc);

  $obj = new Triggers ($dbc);
  $t   = new lime_test( 25, new lime_output_color() );

  $t->diag('class Trigger' );
  $t->isa_ok( $obj  , 'Triggers',  'class Trigger created');

  //method load
  $t->can_ok( $obj,      'load',   'load() is callable' );
  //method save
  $t->can_ok( $obj,      'update',   'update() is callable' );
  //method delete
  $t->can_ok( $obj,      'delete',   'delete() is callable' );
  //method create
  $t->can_ok( $obj,      'create',   'create() is callable' );


  class TriggerTest extends UnitTest
  {
  	function CreateTrigger($data,$fields)
  	{
  	  try
  	  {
  	    $Trigger=new Triggers();
  	    $result=$Trigger->create($fields);
  	    $this->domain->addDomainValue('CREATED',$Trigger->getTriUid());
  	    return $result;
  	  }
  	  catch(Exception $e)
  	  {
  	    return array('Exception!! '=> $e->getMessage());
  	  }
  	}
  	function UpdateTrigger($data,$fields)
  	{
  	  try
  	  {
  	    $Trigger=new Triggers();
  	    $result=$Trigger->update($fields);
  	    return $result;
  	  }
  	  catch(Exception $e)
  	  {
  	    return array('Exception!! '=> $e->getMessage());
  	  }
  	}
  	function LoadTrigger($data,$fields)
  	{
  	  try
  	  {
  	    $Trigger=new Triggers();
  	    $result=$Trigger->load($fields['TRI_UID']);
  	    return $result;
  	  }
  	  catch(Exception $e)
  	  {
  	    return array('Exception!! '=> $e->getMessage());
  	  }
  	}
  	function RemoveTrigger($data,$fields)
  	{
  	  try
  	  {
  	    $Trigger=new Triggers();
  	    $result=$Trigger->remove($fields['TRI_UID']);
  	    return $result;
  	  }
  	  catch(Exception $e)
  	  {
  	    return array('Exception!! '=> $e->getMessage());
  	  }
  	}
  }
  $test=new TriggerTest('trigger.yml',$t);
  $test->domain->addDomain('CREATED');
  $test->load('CreateTestTriggers');
  $test->runAll();
  $test->load('TriggerUnitTest');
  $test->runAll();
?>