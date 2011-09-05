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

  G::LoadClass ( 'wsResponse');

  $obj = new WsResponse ($dbc); 
  $t   = new lime_test( 9, new lime_output_color() );

  $className = WsResponse;
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
  $t->isa_ok( $obj  , $className,  'class $className created');

  $t->is( count($methods) , 3,  "class $className have " . 3 . ' methods.' );

  //checking method '__construct'
  $t->can_ok( $obj,      '__construct',   '__construct() is callable' );

  //$result = $obj->__construct ( $status, $message);
  //$t->isa_ok( $result,      'NULL',   'call to method __construct ');
  $t->todo( "call to method __construct using $status, $message ");


  //checking method 'getPayloadString'
  $t->can_ok( $obj,      'getPayloadString',   'getPayloadString() is callable' );

  //$result = $obj->getPayloadString ( $operation);
  //$t->isa_ok( $result,      'NULL',   'call to method getPayloadString ');
  $t->todo( "call to method getPayloadString using $operation ");


  //checking method 'getPayloadArray'
  $t->can_ok( $obj,      'getPayloadArray',   'getPayloadArray() is callable' );

  //$result = $obj->getPayloadArray ( );
  //$t->isa_ok( $result,      'NULL',   'call to method getPayloadArray ');
  $t->todo( "call to method getPayloadArray using  ");



  $t->todo (  'review all pendings methods in this class');