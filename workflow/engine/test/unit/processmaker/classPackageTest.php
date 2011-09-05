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

  G::LoadClass ( 'package');

  $obj = new Package ($dbc); 
  $t   = new lime_test( 19, new lime_output_color() );

  $className = Package;
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

  $t->is( count($methods) , 8,  "class $className have " . 8 . ' methods.' );

  //checking method '__construct'
  $t->can_ok( $obj,      '__construct',   '__construct() is callable' );

  //$result = $obj->__construct ( $fileData);
  //$t->isa_ok( $result,      'NULL',   'call to method __construct ');
  $t->todo( "call to method __construct using $fileData ");


  //checking method 'returnHeader'
  $t->can_ok( $obj,      'returnHeader',   'returnHeader() is callable' );

  //$result = $obj->returnHeader ( );
  //$t->isa_ok( $result,      'NULL',   'call to method returnHeader ');
  $t->todo( "call to method returnHeader using  ");


  //checking method 'returnBody'
  $t->can_ok( $obj,      'returnBody',   'returnBody() is callable' );

  //$result = $obj->returnBody ( );
  //$t->isa_ok( $result,      'NULL',   'call to method returnBody ');
  $t->todo( "call to method returnBody using  ");


  //checking method 'returnErrors'
  $t->can_ok( $obj,      'returnErrors',   'returnErrors() is callable' );

  //$result = $obj->returnErrors ( $error);
  //$t->isa_ok( $result,      'NULL',   'call to method returnErrors ');
  $t->todo( "call to method returnErrors using $error ");


  //checking method 'addHeaders'
  $t->can_ok( $obj,      'addHeaders',   'addHeaders() is callable' );

  //$result = $obj->addHeaders ( );
  //$t->isa_ok( $result,      'NULL',   'call to method addHeaders ');
  $t->todo( "call to method addHeaders using  ");


  //checking method 'addAttachment'
  $t->can_ok( $obj,      'addAttachment',   'addAttachment() is callable' );

  //$result = $obj->addAttachment ( $data);
  //$t->isa_ok( $result,      'NULL',   'call to method addAttachment ');
  $t->todo( "call to method addAttachment using $data ");


  //checking method 'fixbody'
  $t->can_ok( $obj,      'fixbody',   'fixbody() is callable' );

  //$result = $obj->fixbody ( );
  //$t->isa_ok( $result,      'NULL',   'call to method fixbody ');
  $t->todo( "call to method fixbody using  ");


  //checking method 'compileBody'
  $t->can_ok( $obj,      'compileBody',   'compileBody() is callable' );

  //$result = $obj->compileBody ( );
  //$t->isa_ok( $result,      'NULL',   'call to method compileBody ');
  $t->todo( "call to method compileBody using  ");



  $t->todo (  'review all pendings methods in this class');