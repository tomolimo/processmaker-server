<?php
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

  G::LoadClass ( 'reportTables');


  $obj = new ReportTables ($dbc); 
  $t   = new lime_test( 19, new lime_output_color() );

  $className = ReportTables;
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
  $className = ucwords($className);
  $t->diag("class $className" );

  $t->isa_ok( $obj  , $className,  "class $className created");

  $t->is( count($methods) , 11,  "class $className have " . 11 . ' methods.' );

   //checking method 'deleteAllReportVars'
  $t->can_ok( $obj,      'deleteAllReportVars',   'deleteAllReportVars() is callable' );

  //$result = $obj->deleteAllReportVars ( $sRepTabUid);
  //$t->isa_ok( $result,      'NULL',   'call to method deleteAllReportVars ');
  $t->todo( "call to method deleteAllReportVars using $sRepTabUid ");


  //checking method 'dropTable'
  $t->can_ok( $obj,      'dropTable',   'dropTable() is callable' );

  //$result = $obj->dropTable ( $sTableName, $sConnection);
  //$t->isa_ok( $result,      'NULL',   'call to method dropTable ');
  $t->todo( "call to method dropTable using $sTableName, $sConnection ");


  //checking method 'createTable'
  $t->can_ok( $obj,      'createTable',   'createTable() is callable' );

  //$result = $obj->createTable ( $sTableName, $sConnection, $sType, $aFields, $bDefaultFields);
  //$t->isa_ok( $result,      'NULL',   'call to method createTable ');
  $t->todo( "call to method createTable using $sTableName, $sConnection, $sType, $aFields, $bDefaultFields ");


  //checking method 'populateTable'
  $t->can_ok( $obj,      'populateTable',   'populateTable() is callable' );

  //$result = $obj->populateTable ( $sTableName, $sConnection, $sType, $aFields, $sProcessUid, $sGrid);
  //$t->isa_ok( $result,      'NULL',   'call to method populateTable ');
  $t->todo( "call to method populateTable using $sTableName, $sConnection, $sType, $aFields, $sProcessUid, $sGrid ");


  //checking method 'getTableVars'
  $t->can_ok( $obj,      'getTableVars',   'getTableVars() is callable' );

  //$result = $obj->getTableVars ( $sRepTabUid, $bWhitType);
  //$t->isa_ok( $result,      'NULL',   'call to method getTableVars ');
  $t->todo( "call to method getTableVars using $sRepTabUid, $bWhitType ");


  //checking method 'deleteReportTable'
  $t->can_ok( $obj,      'deleteReportTable',   'deleteReportTable() is callable' );

  //$result = $obj->deleteReportTable ( $sRepTabUid);
  //$t->isa_ok( $result,      'NULL',   'call to method deleteReportTable ');
  $t->todo( "call to method deleteReportTable using $sRepTabUid ");


  //checking method 'updateTables'
  $t->can_ok( $obj,      'updateTables',   'updateTables() is callable' );

  //$result = $obj->updateTables ( $sProcessUid, $sApplicationUid, $iApplicationNumber, $aFields);
  //$t->isa_ok( $result,      'NULL',   'call to method updateTables ');
  $t->todo( "call to method updateTables using $sProcessUid, $sApplicationUid, $iApplicationNumber, $aFields ");


  //checking method 'tableExist'
  $t->can_ok( $obj,      'tableExist',   'tableExist() is callable' );

  //$result = $obj->tableExist ( );
  //$t->isa_ok( $result,      'NULL',   'call to method tableExist ');
  $t->todo( "call to method tableExist using  ");



  $t->todo (  'review all pendings methods in this class');