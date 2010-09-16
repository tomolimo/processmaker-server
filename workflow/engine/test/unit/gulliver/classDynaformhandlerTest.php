<?php
  if ( !defined ('PATH_THIRDPARTY') ) {
    require_once(  $_SERVER['PWD']. '/test/bootstrap/unit.php');
  }
  require_once( PATH_THIRDPARTY . 'lime/lime.php');

  define ( 'G_ENVIRONMENT', G_TEST_ENV);
  G::LoadThirdParty('smarty/libs','Smarty.class');
  G::LoadSystem ( 'xmlform');
  G::LoadSystem ( 'xmlDocument');
  G::LoadSystem ( 'form');
  G::LoadSystem ( 'dynaformhandler');

  $t = new lime_test( 20, new lime_output_color());
  $obj = "dynaFormHandler";
  $method = array ( );
  $testItems = 0;
  $class_methods = get_class_methods('dynaFormHandler');
  foreach ($class_methods as $method_name) {
    $methods[ $testItems ] = $method_name;
    $testItems++;
  }

  $t->diag('class dynaFormHandler' );
  $t->is(  $testItems , 18,  "class database " . $testItems . " methods." ); 
  $t->is( $methods[0]  , '__construct'					  , '__construct');
  $t->is( $methods[1]  , '__cloneEmpty'    			  , '__cloneEmpty');
  $t->is( $methods[2]  , 'toString'      				  , 'toString');
  $t->is( $methods[3]  , 'getNode'     					  , 'getNode');
  $t->is( $methods[4]  , 'setNode'     					  , 'setNode');
  $t->is( $methods[5]  , 'add'      						  , 'add');
  $t->is( $methods[6]  , 'replace'   						  , 'replace');
  $t->is( $methods[7]  , 'save' 								  , 'save');
  $t->is( $methods[8]  , 'fixXmlFile'						  , 'fixXmlFile');
  $t->is( $methods[9]  , 'setHeaderAttribute' 		, 'setHeaderAttribute');
  $t->is( $methods[10] , 'modifyHeaderAttribute'  , 'modifyHeaderAttribute');
  $t->is( $methods[11] , 'updateAttribute'				, 'updateAttribute');
  $t->is( $methods[12] , 'remove'     						, 'remove');
  $t->is( $methods[13] , 'nodeExists' 						, 'nodeExists');
  $t->is( $methods[14] , 'moveUp'      						, 'moveUp');
  $t->is( $methods[15] , 'moveDown'  	 						, 'moveDown');
  $t->is( $methods[16] , 'getFields'              , 'getFields');
  $t->is( $methods[17] , 'getFieldNames'     			, 'getFieldNames');
  $t->todo(  'review all pendings in this class');