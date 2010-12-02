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

  G::LoadClass ( 'languages');


  $obj = new Languages ($dbc); 
  $t   = new lime_test( 5, new lime_output_color() );

  $className = Languages;
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

  $t->isa_ok( $obj  , "languages",  "class $className created");

  $t->is( count($methods) , 1,  "class $className have " . 1 . ' methods.' );

   //checking method 'importLanguage'
  $t->can_ok( $obj,      'importLanguage',   'importLanguage() is callable' );

  //$result = $obj->importLanguage ( $sLanguageFile, $bXml);
  //$t->isa_ok( $result,      'NULL',   'call to method importLanguage ');
  $t->todo( "call to method importLanguage using $sLanguageFile, $bXml ");



  $t->todo (  'review all pendings methods in this class');
