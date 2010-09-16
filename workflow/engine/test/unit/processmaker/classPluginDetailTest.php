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

  G::LoadClass ( 'pluginRegistry');


  //$obj = new PluginDetail ($dbc);
  $t   = new lime_test( 4, new lime_output_color() );

  $className = PluginDetail;
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

  //$t->isa_ok( $obj  , $className,  "class $className created");

  $t->is( count($methods) , 1,  "class $className have " . 1 . ' methods.' );
  // Methods
  $aMethods = array_keys ( $methods );
   //checking method '__construct'
  $t->is ( $aMethods[0],      '__construct',   '__construct() is callable' );

  //$result = $obj->__construct ( $sNamespace, $sClassName, $sFilename, $sFriendlyName, $sPluginFolder, $sDescription, $sSetupPage, $iVersion);
  //$t->isa_ok( $result,      'NULL',   'call to method __construct ');
  $t->todo( "call to method __construct using $sNamespace, $sClassName, $sFilename, $sFriendlyName, $sPluginFolder, $sDescription, $sSetupPage, $iVersion ");



  $t->todo (  'review all pendings methods in this class');
