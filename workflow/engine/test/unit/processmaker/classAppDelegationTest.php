<?php
/**
 * classAppDelegationTest.php
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
  G::LoadSystem ( 'testTools');
  G::LoadClass ( 'appDelegation');
  require_once(PATH_CORE.'/classes/model/AppDelegation.php');
  $dbc = new DBConnection(); 
  $ses = new DBSession( $dbc);
  $obj = new AppDelegation ($dbc); 
  $t   = new lime_test( 1, new lime_output_color() );
  $t->diag('class AppDelegation' );
  $t->isa_ok( $obj  , 'AppDelegation',  'class AppDelegation created');
  class AppDel extends unitTest
  {
    function CreateEmptyAppDelegation($data,$fields)
    {
      $obj=new AppDelegation();
      $res=$obj->createAppDelegation($fields);
      return $res;
    }
    function CreateDuplicated($data,$fields)
    {
      $obj1=new AppDelegation();
      $res=$obj1->createAppDelegation($fields);
      $this->domain->addDomainValue('createdAppDel',serialize($fields));
      $obj2=new AppDelegation();
      $res=$obj2->createAppDelegation($fields);
      $this->domain->addDomainValue(serialize($fields));
      return $res;
    }
    function CreateNewAppDelegation($data,$fields)
    {
      $obj=new AppDelegation();
      $res=$obj->createAppDelegation($fields);
      $this->domain->addDomainValue('createdAppDel',serialize($fields));
      return $res;
    }
    function DeleteAppDelegation($data,$fields)
    {
      $obj=new AppDelegation();
      $fields=unserialize($fields['Fields']);
      $obj->setAppUid($fields['APP_UID']);
      $obj->setDelIndex($fields['DEL_INDEX']);
      $res=$obj->delete();
      return $res;
    }
  }
  $tt=new AppDel('appDelegation.yml',$t,$domain);
  $domain->addDomain("createdAppDel");
  $tt->load('CreateDelApplication');
  $tt->runAll();
  $tt->load('DeleteCretedAppDelegations');
  $tt->runAll();

?>