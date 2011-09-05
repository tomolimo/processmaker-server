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

  G::LoadClass ( 'jrml');

  $obj = new Jrml ($dbc); 
  $t   = new lime_test( 19, new lime_output_color() );

  $className = Jrml;
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
  $t->isa_ok( $obj  , 'Jrml',  'class $className created');

  $t->is( count($methods) , 8,  "class $className have " . 8 . ' methods.' );

  //checking method '__construct'
  $t->can_ok( $obj,      '__construct',   '__construct() is callable' );

  //$result = $obj->__construct ( $data);
  //$t->isa_ok( $result,      'NULL',   'call to method __construct ');
  $t->todo( "call to method __construct using $data ");


  //checking method 'get_rows'
  $t->can_ok( $obj,      'get_rows',   'get_rows() is callable' );

  //$result = $obj->get_rows ( $a);
  //$t->isa_ok( $result,      'NULL',   'call to method get_rows ');
  $t->todo( "call to method get_rows using $a ");


  //checking method 'get_md'
  $t->can_ok( $obj,      'get_md',   'get_md() is callable' );

  //$result = $obj->get_md ( );
  //$t->isa_ok( $result,      'NULL',   'call to method get_md ');
  $t->todo( "call to method get_md using  ");


  //checking method 'get_header'
  $t->can_ok( $obj,      'get_header',   'get_header() is callable' );

  //$result = $obj->get_header ( );
  //$t->isa_ok( $result,      'NULL',   'call to method get_header ');
  $t->todo( "call to method get_header using  ");


  //checking method 'get_column_header'
  $t->can_ok( $obj,      'get_column_header',   'get_column_header() is callable' );

  //$result = $obj->get_column_header ( );
  //$t->isa_ok( $result,      'NULL',   'call to method get_column_header ');
  $t->todo( "call to method get_column_header using  ");


  //checking method 'get_detail'
  $t->can_ok( $obj,      'get_detail',   'get_detail() is callable' );

  //$result = $obj->get_detail ( );
  //$t->isa_ok( $result,      'NULL',   'call to method get_detail ');
  $t->todo( "call to method get_detail using  ");


  //checking method 'get_footer'
  $t->can_ok( $obj,      'get_footer',   'get_footer() is callable' );

  //$result = $obj->get_footer ( );
  //$t->isa_ok( $result,      'NULL',   'call to method get_footer ');
  $t->todo( "call to method get_footer using  ");


  //checking method 'export'
  $t->can_ok( $obj,      'export',   'export() is callable' );

  //$result = $obj->export ( );
  //$t->isa_ok( $result,      'NULL',   'call to method export ');
  $t->todo( "call to method export using  ");



  $t->todo (  'review all pendings methods in this class');
