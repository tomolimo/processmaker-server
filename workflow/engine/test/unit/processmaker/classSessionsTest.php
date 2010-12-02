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

  G::LoadClass ( 'sessions');


  $obj = new Sessions ($dbc); 
  $t   = new lime_test( 7, new lime_output_color() );

  $className = Sessions;
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

  //$t->is( count($methods) , 2,  "class $className have " . 2 . ' methods.' );
  $t->is( count($methods) , 7,  "class $className have " . 7 . ' methods.' );

   //checking method 'getSessionUser'
  $t->can_ok( $obj,      'getSessionUser',   'getSessionUser() is callable' );

  //$result = $obj->getSessionUser ( $sSessionId);
  //$t->isa_ok( $result,      'NULL',   'call to method getSessionUser ');
  $t->todo( "call to method getSessionUser using $sSessionId ");


  //checking method 'verifySession'
  $t->can_ok( $obj,      'verifySession',   'verifySession() is callable' );

  //$result = $obj->verifySession ( $sSessionId);
  //$t->isa_ok( $result,      'NULL',   'call to method verifySession ');
  $t->todo( "call to method verifySession using $sSessionId ");



  $t->todo (  'review all pendings methods in this class');