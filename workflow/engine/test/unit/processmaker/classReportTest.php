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

  G::LoadClass ( 'report');


  $obj = new Report ($dbc); 
  $t   = new lime_test( 31, new lime_output_color() );

  $className = Report;
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
  $t->isa_ok( $obj  , 'Report',  'class $className created');

  $t->is( count($methods) , 14,  "class $className have " . 14 . ' methods.' );

  //checking method 'generatedReport1'
  $t->can_ok( $obj,      'generatedReport1',   'generatedReport1() is callable' );

  //$result = $obj->generatedReport1 ( );
  //$t->isa_ok( $result,      'NULL',   'call to method generatedReport1 ');
  $t->todo( "call to method generatedReport1 using  ");


  //checking method 'generatedReport1_filter'
  $t->can_ok( $obj,      'generatedReport1_filter',   'generatedReport1_filter() is callable' );

  //$result = $obj->generatedReport1_filter ( $from, $to, $startedby);
  //$t->isa_ok( $result,      'NULL',   'call to method generatedReport1_filter ');
  $t->todo( "call to method generatedReport1_filter using $from, $to, $startedby ");


  //checking method 'descriptionReport1'
  $t->can_ok( $obj,      'descriptionReport1',   'descriptionReport1() is callable' );

  //$result = $obj->descriptionReport1 ( $PRO_UID);
  //$t->isa_ok( $result,      'NULL',   'call to method descriptionReport1 ');
  $t->todo( "call to method descriptionReport1 using $PRO_UID ");


  //checking method 'generatedReport2'
  $t->can_ok( $obj,      'generatedReport2',   'generatedReport2() is callable' );

  //$result = $obj->generatedReport2 ( );
  //$t->isa_ok( $result,      'NULL',   'call to method generatedReport2 ');
  $t->todo( "call to method generatedReport2 using  ");


  //checking method 'reports_Description_filter'
  $t->can_ok( $obj,      'reports_Description_filter',   'reports_Description_filter() is callable' );

  //$result = $obj->reports_Description_filter ( $from, $to, $startedby, $PRO_UID);
  //$t->isa_ok( $result,      'NULL',   'call to method reports_Description_filter ');
  $t->todo( "call to method reports_Description_filter using $from, $to, $startedby, $PRO_UID ");


  //checking method 'generatedReport2_filter'
  $t->can_ok( $obj,      'generatedReport2_filter',   'generatedReport2_filter() is callable' );

  //$result = $obj->generatedReport2_filter ( $from, $to, $startedby);
  //$t->isa_ok( $result,      'NULL',   'call to method generatedReport2_filter ');
  $t->todo( "call to method generatedReport2_filter using $from, $to, $startedby ");


  //checking method 'generatedReport3'
  $t->can_ok( $obj,      'generatedReport3',   'generatedReport3() is callable' );

  //$result = $obj->generatedReport3 ( );
  //$t->isa_ok( $result,      'NULL',   'call to method generatedReport3 ');
  $t->todo( "call to method generatedReport3 using  ");


  //checking method 'generatedReport3_filter'
  $t->can_ok( $obj,      'generatedReport3_filter',   'generatedReport3_filter() is callable' );

  //$result = $obj->generatedReport3_filter ( $process, $task);
  //$t->isa_ok( $result,      'NULL',   'call to method generatedReport3_filter ');
  $t->todo( "call to method generatedReport3_filter using $process, $task ");


  //checking method 'generatedReport4'
  $t->can_ok( $obj,      'generatedReport4',   'generatedReport4() is callable' );

  //$result = $obj->generatedReport4 ( );
  //$t->isa_ok( $result,      'NULL',   'call to method generatedReport4 ');
  $t->todo( "call to method generatedReport4 using  ");


  //checking method 'generatedReport4_filter'
  $t->can_ok( $obj,      'generatedReport4_filter',   'generatedReport4_filter() is callable' );

  //$result = $obj->generatedReport4_filter ( $process, $task);
  //$t->isa_ok( $result,      'NULL',   'call to method generatedReport4_filter ');
  $t->todo( "call to method generatedReport4_filter using $process, $task ");


  //checking method 'generatedReport5'
  $t->can_ok( $obj,      'generatedReport5',   'generatedReport5() is callable' );

  //$result = $obj->generatedReport5 ( );
  //$t->isa_ok( $result,      'NULL',   'call to method generatedReport5 ');
  $t->todo( "call to method generatedReport5 using  ");


  //checking method 'generatedReport5_filter'
  $t->can_ok( $obj,      'generatedReport5_filter',   'generatedReport5_filter() is callable' );

  //$result = $obj->generatedReport5_filter ( $process, $task);
  //$t->isa_ok( $result,      'NULL',   'call to method generatedReport5_filter ');
  $t->todo( "call to method generatedReport5_filter using $process, $task ");


  //checking method 'getAvailableReports'
  $t->can_ok( $obj,      'getAvailableReports',   'getAvailableReports() is callable' );

  //$result = $obj->getAvailableReports ( );
  //$t->isa_ok( $result,      'NULL',   'call to method getAvailableReports ');
  $t->todo( "call to method getAvailableReports using  ");


  //checking method 'reportsPatch'
  $t->can_ok( $obj,      'reportsPatch',   'reportsPatch() is callable' );

  //$result = $obj->reportsPatch ( );
  //$t->isa_ok( $result,      'NULL',   'call to method reportsPatch ');
  $t->todo( "call to method reportsPatch using  ");



  $t->todo (  'review all pendings methods in this class');
