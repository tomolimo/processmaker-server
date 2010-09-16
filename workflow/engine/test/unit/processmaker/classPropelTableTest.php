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

  G::LoadClass ( 'propelTable');


  $obj = new PropelTable ($dbc); 
  $t   = new lime_test( 19, new lime_output_color() );

  $className = PropelTable;
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

  $t->is( count($methods) , 8,  "class $className have " . 8 . ' methods.' );

   //checking method 'prepareQuery'
  $t->can_ok( $obj,      'prepareQuery',   'prepareQuery() is callable' );

  //$result = $obj->prepareQuery ( $limitPage);
  //$t->isa_ok( $result,      'NULL',   'call to method prepareQuery ');
  $t->todo( "call to method prepareQuery using $limitPage ");


  //checking method 'setupFromXmlform'
  $t->can_ok( $obj,      'setupFromXmlform',   'setupFromXmlform() is callable' );

  //$result = $obj->setupFromXmlform ( $xmlForm);
  //$t->isa_ok( $result,      'NULL',   'call to method setupFromXmlform ');
  $t->todo( "call to method setupFromXmlform using $xmlForm ");


  //checking method 'count'
  $t->can_ok( $obj,      'count',   'count() is callable' );

  //$result = $obj->count ( );
  //$t->isa_ok( $result,      'NULL',   'call to method count ');
  $t->todo( "call to method count using  ");


  //checking method 'renderTitle'
  $t->can_ok( $obj,      'renderTitle',   'renderTitle() is callable' );

  //$result = $obj->renderTitle ( );
  //$t->isa_ok( $result,      'NULL',   'call to method renderTitle ');
  $t->todo( "call to method renderTitle using  ");


  //checking method 'renderField'
  $t->can_ok( $obj,      'renderField',   'renderField() is callable' );

  //$result = $obj->renderField ( $row, $r, $result);
  //$t->isa_ok( $result,      'NULL',   'call to method renderField ');
  $t->todo( "call to method renderField using $row, $r, $result ");


  //checking method 'defaultStyle'
  $t->can_ok( $obj,      'defaultStyle',   'defaultStyle() is callable' );

  //$result = $obj->defaultStyle ( );
  //$t->isa_ok( $result,      'NULL',   'call to method defaultStyle ');
  $t->todo( "call to method defaultStyle using  ");


  //checking method 'renderTable'
  $t->can_ok( $obj,      'renderTable',   'renderTable() is callable' );

  //$result = $obj->renderTable ( $block, $fields);
  //$t->isa_ok( $result,      'NULL',   'call to method renderTable ');
  $t->todo( "call to method renderTable using $block, $fields ");


  //checking method 'printForm'
  $t->can_ok( $obj,      'printForm',   'printForm() is callable' );

  //$result = $obj->printForm ( $filename, $data);
  //$t->isa_ok( $result,      'NULL',   'call to method printForm ');
  $t->todo( "call to method printForm using $filename, $data ");



  $t->todo (  'review all pendings methods in this class');
