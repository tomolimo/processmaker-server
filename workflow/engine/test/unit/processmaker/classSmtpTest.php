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

  G::LoadClass ( 'smtp');

  $obj = new Smtp ($dbc); 
  $t   = new lime_test( 29, new lime_output_color() );

  $className = Smtp;
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

  $t->is( count($methods) , 13,  "class $className have " . 13 . ' methods.' );

  //checking method '__construct'
  $t->can_ok( $obj,      '__construct',   '__construct() is callable' );

  //$result = $obj->__construct ( );
  //$t->isa_ok( $result,      'NULL',   'call to method __construct ');
  $t->todo( "call to method __construct using  ");


  //checking method 'setServer'
  $t->can_ok( $obj,      'setServer',   'setServer() is callable' );

  //$result = $obj->setServer ( $sServer);
  //$t->isa_ok( $result,      'NULL',   'call to method setServer ');
  $t->todo( "call to method setServer using $sServer ");


  //checking method 'setPort'
  $t->can_ok( $obj,      'setPort',   'setPort() is callable' );

  //$result = $obj->setPort ( $iPort);
  //$t->isa_ok( $result,      'NULL',   'call to method setPort ');
  $t->todo( "call to method setPort using $iPort ");


  //checking method 'setReturnPath'
  $t->can_ok( $obj,      'setReturnPath',   'setReturnPath() is callable' );

  //$result = $obj->setReturnPath ( $sReturnPath);
  //$t->isa_ok( $result,      'NULL',   'call to method setReturnPath ');
  $t->todo( "call to method setReturnPath using $sReturnPath ");


  //checking method 'setHeaders'
  $t->can_ok( $obj,      'setHeaders',   'setHeaders() is callable' );

  //$result = $obj->setHeaders ( $sHeaders);
  //$t->isa_ok( $result,      'NULL',   'call to method setHeaders ');
  $t->todo( "call to method setHeaders using $sHeaders ");


  //checking method 'setBody'
  $t->can_ok( $obj,      'setBody',   'setBody() is callable' );

  //$result = $obj->setBody ( $sBody);
  //$t->isa_ok( $result,      'NULL',   'call to method setBody ');
  $t->todo( "call to method setBody using $sBody ");


  //checking method 'setSmtpAuthentication'
  $t->can_ok( $obj,      'setSmtpAuthentication',   'setSmtpAuthentication() is callable' );

  //$result = $obj->setSmtpAuthentication ( $sAuth);
  //$t->isa_ok( $result,      'NULL',   'call to method setSmtpAuthentication ');
  $t->todo( "call to method setSmtpAuthentication using $sAuth ");


  //checking method 'setUsername'
  $t->can_ok( $obj,      'setUsername',   'setUsername() is callable' );

  //$result = $obj->setUsername ( $sName);
  //$t->isa_ok( $result,      'NULL',   'call to method setUsername ');
  $t->todo( "call to method setUsername using $sName ");


  //checking method 'setPassword'
  $t->can_ok( $obj,      'setPassword',   'setPassword() is callable' );

  //$result = $obj->setPassword ( $sPass);
  //$t->isa_ok( $result,      'NULL',   'call to method setPassword ');
  $t->todo( "call to method setPassword using $sPass ");


  //checking method 'returnErrors'
  $t->can_ok( $obj,      'returnErrors',   'returnErrors() is callable' );

  //$result = $obj->returnErrors ( );
  //$t->isa_ok( $result,      'NULL',   'call to method returnErrors ');
  $t->todo( "call to method returnErrors using  ");


  //checking method 'returnStatus'
  $t->can_ok( $obj,      'returnStatus',   'returnStatus() is callable' );

  //$result = $obj->returnStatus ( );
  //$t->isa_ok( $result,      'NULL',   'call to method returnStatus ');
  $t->todo( "call to method returnStatus using  ");


  //checking method 'setEnvelopeTo'
  $t->can_ok( $obj,      'setEnvelopeTo',   'setEnvelopeTo() is callable' );

  //$result = $obj->setEnvelopeTo ( $env_to);
  //$t->isa_ok( $result,      'NULL',   'call to method setEnvelopeTo ');
  $t->todo( "call to method setEnvelopeTo using $env_to ");


  //checking method 'sendMessage'
  $t->can_ok( $obj,      'sendMessage',   'sendMessage() is callable' );

  //$result = $obj->sendMessage ( );
  //$t->isa_ok( $result,      'NULL',   'call to method sendMessage ');
  $t->todo( "call to method sendMessage using  ");



  $t->todo (  'review all pendings methods in this class');