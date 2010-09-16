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

  G::LoadClass ( 'xmlDb');


  $obj = new XmlDb ($dbc); 
  $t   = new lime_test( 7, new lime_output_color() );

  $className = XmlDb;
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

  $t->isa_ok( $obj  , "XMLDB",  "class $className created");

  $t->is( count($methods) , 2,  "class $className have " . 2 . ' methods.' );

   //checking method 'connect'
  $t->can_ok( $obj,      'connect',   'connect() is callable' );

  //$result = $obj->connect ( $dsn, $options);
  //$t->isa_ok( $result,      'NULL',   'call to method connect ');
  $t->todo( "call to method connect using $dsn, $options ");


  //checking method 'isError'
  $t->can_ok( $obj,      'isError',   'isError() is callable' );

  //$result = $obj->isError ( $result);
  //$t->isa_ok( $result,      'NULL',   'call to method isError ');
  $t->todo( "call to method isError using $result ");



  $t->todo (  'review all pendings methods in this class');
