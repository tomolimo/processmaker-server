<?php
  $unitFilename = $_SERVER['PWD'] . '/test/bootstrap/unit.php' ;
  require_once( $unitFilename );

  require_once( PATH_THIRDPARTY . '/lime/lime.php');
  require_once( PATH_THIRDPARTY.'lime/yaml.class.php');
  require_once( 'propel/Propel.php' );
  require_once ( "creole/Creole.php" );
  Propel::init(  PATH_CORE . "config/databases.php");


  G::LoadThirdParty('smarty/libs','Smarty.class');
  G::LoadSystem ( 'error');
  G::LoadSystem ( 'xmlform');
  G::LoadSystem ( 'xmlDocument');
  G::LoadSystem ( 'form');

  require_once (  PATH_CORE . "config/databases.php");  

  G::LoadClass ( 'net');

  $obj = new Net ($dbc); 
  $t   = new lime_test( 31, new lime_output_color() );

  $className = Net;
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
 
  $t->diag('class $className' );
  $t->isa_ok( $obj  , 'NET',  'class $className created');

  $t->is( count($methods) , 14,  "class $className have " . 14 . ' methods.' );

  //checking method '__construct'
  $t->can_ok( $obj,      '__construct',   '__construct() is callable' );

  //$result = $obj->__construct ( $pHost);
  //$t->isa_ok( $result,      'NULL',   'call to method __construct ');
  $t->todo( "call to method __construct using $pHost ");


  //checking method 'resolv'
  $t->can_ok( $obj,      'resolv',   'resolv() is callable' );

  //$result = $obj->resolv ( $pHost);
  //$t->isa_ok( $result,      'NULL',   'call to method resolv ');
  $t->todo( "call to method resolv using $pHost ");


  //checking method 'scannPort'
  $t->can_ok( $obj,      'scannPort',   'scannPort() is callable' );

  //$result = $obj->scannPort ( $pPort);
  //$t->isa_ok( $result,      'NULL',   'call to method scannPort ');
  $t->todo( "call to method scannPort using $pPort ");


  //checking method 'is_ipaddress'
  $t->can_ok( $obj,      'is_ipaddress',   'is_ipaddress() is callable' );

  //$result = $obj->is_ipaddress ( $pHost);
  //$t->isa_ok( $result,      'NULL',   'call to method is_ipaddress ');
  $t->todo( "call to method is_ipaddress using $pHost ");


  //checking method 'ping'
  $t->can_ok( $obj,      'ping',   'ping() is callable' );

  //$result = $obj->ping ( $pTTL);
  //$t->isa_ok( $result,      'NULL',   'call to method ping ');
  $t->todo( "call to method ping using $pTTL ");


  //checking method 'loginDbServer'
  $t->can_ok( $obj,      'loginDbServer',   'loginDbServer() is callable' );

  //$result = $obj->loginDbServer ( $pUser, $pPasswd);
  //$t->isa_ok( $result,      'NULL',   'call to method loginDbServer ');
  $t->todo( "call to method loginDbServer using $pUser, $pPasswd ");


  //checking method 'setDataBase'
  $t->can_ok( $obj,      'setDataBase',   'setDataBase() is callable' );

  //$result = $obj->setDataBase ( $pDb, $pPort);
  //$t->isa_ok( $result,      'NULL',   'call to method setDataBase ');
  $t->todo( "call to method setDataBase using $pDb, $pPort ");


  //checking method 'tryConnectServer'
  $t->can_ok( $obj,      'tryConnectServer',   'tryConnectServer() is callable' );

  //$result = $obj->tryConnectServer ( $pDbDriver);
  //$t->isa_ok( $result,      'NULL',   'call to method tryConnectServer ');
  $t->todo( "call to method tryConnectServer using $pDbDriver ");


  //checking method 'tryOpenDataBase'
  $t->can_ok( $obj,      'tryOpenDataBase',   'tryOpenDataBase() is callable' );

  //$result = $obj->tryOpenDataBase ( $pDbDriver);
  //$t->isa_ok( $result,      'NULL',   'call to method tryOpenDataBase ');
  $t->todo( "call to method tryOpenDataBase using $pDbDriver ");


  //checking method 'getDbServerVersion'
  $t->can_ok( $obj,      'getDbServerVersion',   'getDbServerVersion() is callable' );

  //$result = $obj->getDbServerVersion ( $driver);
  //$t->isa_ok( $result,      'NULL',   'call to method getDbServerVersion ');
  $t->todo( "call to method getDbServerVersion using $driver ");


  //checking method 'dbName'
  $t->can_ok( $obj,      'dbName',   'dbName() is callable' );

  //$result = $obj->dbName ( $pAdapter);
  //$t->isa_ok( $result,      'NULL',   'call to method dbName ');
  $t->todo( "call to method dbName using $pAdapter ");


  //checking method 'showMsg'
  $t->can_ok( $obj,      'showMsg',   'showMsg() is callable' );

  //$result = $obj->showMsg ( );
  //$t->isa_ok( $result,      'NULL',   'call to method showMsg ');
  $t->todo( "call to method showMsg using  ");


  //checking method 'getErrno'
  $t->can_ok( $obj,      'getErrno',   'getErrno() is callable' );

  //$result = $obj->getErrno ( );
  //$t->isa_ok( $result,      'NULL',   'call to method getErrno ');
  $t->todo( "call to method getErrno using  ");


  //checking method 'getErrmsg'
  $t->can_ok( $obj,      'getErrmsg',   'getErrmsg() is callable' );

  //$result = $obj->getErrmsg ( );
  //$t->isa_ok( $result,      'NULL',   'call to method getErrmsg ');
  $t->todo( "call to method getErrmsg using  ");



  $t->todo (  'review all pendings methods in this class');
