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

  G::LoadClass ( 'insert');


  $obj = new Insert ($dbc); 
  $t   = new lime_test( 9, new lime_output_color() );

  $className = Insert;
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

  $t->is( count($methods) , 3,  "class $className have " . 3 . ' methods.' );

   //checking method '__construct'
  $t->can_ok( $obj,      '__construct',   '__construct() is callable' );

  //$result = $obj->__construct ( $db_spool);
  //$t->isa_ok( $result,      'NULL',   'call to method __construct ');
  $t->todo( "call to method __construct using $db_spool ");


  //checking method 'returnStatus'
  $t->can_ok( $obj,      'returnStatus',   'returnStatus() is callable' );

  //$result = $obj->returnStatus ( );
  //$t->isa_ok( $result,      'NULL',   'call to method returnStatus ');
  $t->todo( "call to method returnStatus using  ");


  //checking method 'db_insert'
  $t->can_ok( $obj,      'db_insert',   'db_insert() is callable' );

  //$result = $obj->db_insert ( $db_spool);
  //$t->isa_ok( $result,      'NULL',   'call to method db_insert ');
  $t->todo( "call to method db_insert using $db_spool ");



  $t->todo (  'review all pendings methods in this class');
