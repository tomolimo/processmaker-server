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

  G::LoadClass ( 'javaBridgePM');

  $obj = new JavaBridgePM ($dbc); 
  $t   = new lime_test( 9, new lime_output_color() );

  $className = JavaBridgePM;
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
  $t->isa_ok( $obj  , 'JavaBridgePM',  'class $className created');

  $t->is( count($methods) , 3,  "class $className have " . 3 . ' methods.' );

  //checking method 'checkJavaExtension'
  $t->can_ok( $obj,      'checkJavaExtension',   'checkJavaExtension() is callable' );

  //$result = $obj->checkJavaExtension ( );
  //$t->isa_ok( $result,      'NULL',   'call to method checkJavaExtension ');
  $t->todo( "call to method checkJavaExtension using  ");


  //checking method 'convertValue'
  $t->can_ok( $obj,      'convertValue',   'convertValue() is callable' );

  //$result = $obj->convertValue ( $value, $className);
  //$t->isa_ok( $result,      'NULL',   'call to method convertValue ');
  $t->todo( "call to method convertValue using $value, $className ");


  //checking method 'generateJrxmlFromDynaform'
  $t->can_ok( $obj,      'generateJrxmlFromDynaform',   'generateJrxmlFromDynaform() is callable' );

  //$result = $obj->generateJrxmlFromDynaform ( $outDocUid, $dynaformUid, $template);
  //$t->isa_ok( $result,      'NULL',   'call to method generateJrxmlFromDynaform ');
  $t->todo( "call to method generateJrxmlFromDynaform using $outDocUid, $dynaformUid, $template ");



  $t->todo (  'review all pendings methods in this class');
