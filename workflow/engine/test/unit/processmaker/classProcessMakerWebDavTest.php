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

  G::LoadClass ( 'webdav');


  //$obj = new ProcessMakerWebDav ($dbc);
  $t   = new lime_test( 106, new lime_output_color() );

  $className = ProcessMakerWebDav;
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

  $t->is( count($methods) , 52,  "class $className have " . 52 . ' methods.' );
   // Methods
  $aMethods = array_keys ( $methods );
   //checking method 'ServeRequest'
  $t->is ( $aMethods[0],      'ServeRequest',   'ServeRequest() is callable' );

  //$result = $obj->ServeRequest ( $base);
  //$t->isa_ok( $result,      'NULL',   'call to method ServeRequest ');
  $t->todo( "call to method ServeRequest using $base ");


  //checking method 'check_auth'
  $t->is ( $aMethods[1],      'check_auth',   'check_auth() is callable' );

  //$result = $obj->check_auth ( $type, $user, $pass);
  //$t->isa_ok( $result,      'NULL',   'call to method check_auth ');
  $t->todo( "call to method check_auth using $type, $user, $pass ");


  //checking method 'PROPFIND'
  $t->is ( $aMethods[2],      'PROPFIND',   'PROPFIND() is callable' );

  //$result = $obj->PROPFIND ( $options, $files);
  //$t->isa_ok( $result,      'NULL',   'call to method PROPFIND ');
  $t->todo( "call to method PROPFIND using $options, $files ");


  //checking method '_can_execute'
  $t->is ( $aMethods[3],      '_can_execute',   '_can_execute() is callable' );

  //$result = $obj->_can_execute ( $name, $path);
  //$t->isa_ok( $result,      'NULL',   'call to method _can_execute ');
  $t->todo( "call to method _can_execute using $name, $path ");


  //checking method '_mimetype'
  $t->is ( $aMethods[4],      '_mimetype',   '_mimetype() is callable' );

  //$result = $obj->_mimetype ( $fspath);
  //$t->isa_ok( $result,      'NULL',   'call to method _mimetype ');
  $t->todo( "call to method _mimetype using $fspath ");


  //checking method 'GET'
  $t->is ( $aMethods[5],      'GET',   'GET() is callable' );

  //$result = $obj->GET ( $options);
  //$t->isa_ok( $result,      'NULL',   'call to method GET ');
  $t->todo( "call to method GET using $options ");


  //checking method 'getRoot'
  $t->is ( $aMethods[6],      'getRoot',   'getRoot() is callable' );

  //$result = $obj->getRoot ( $options);
  //$t->isa_ok( $result,      'NULL',   'call to method getRoot ');
  $t->todo( "call to method getRoot using $options ");


  //checking method 'GetDir'
  $t->is ( $aMethods[7],      'GetDir',   'GetDir() is callable' );

  //$result = $obj->GetDir ( $fspath, $options);
  //$t->isa_ok( $result,      'NULL',   'call to method GetDir ');
  $t->todo( "call to method GetDir using $fspath, $options ");


  //checking method 'PUT'
  $t->is ( $aMethods[8],      'PUT',   'PUT() is callable' );

  //$result = $obj->PUT ( $options);
  //$t->isa_ok( $result,      'NULL',   'call to method PUT ');
  $t->todo( "call to method PUT using $options ");


  //checking method 'MKCOL'
  $t->is ( $aMethods[9],      'MKCOL',   'MKCOL() is callable' );

  //$result = $obj->MKCOL ( $options);
  //$t->isa_ok( $result,      'NULL',   'call to method MKCOL ');
  $t->todo( "call to method MKCOL using $options ");


  //checking method 'DELETE'
  $t->is ( $aMethods[10],      'DELETE',   'DELETE() is callable' );

  //$result = $obj->DELETE ( $options);
  //$t->isa_ok( $result,      'NULL',   'call to method DELETE ');
  $t->todo( "call to method DELETE using $options ");


  //checking method 'MOVE'
  $t->is ( $aMethods[11],      'MOVE',   'MOVE() is callable' );

  //$result = $obj->MOVE ( $options);
  //$t->isa_ok( $result,      'NULL',   'call to method MOVE ');
  $t->todo( "call to method MOVE using $options ");


  //checking method 'COPY'
  $t->is ( $aMethods[12],      'COPY',   'COPY() is callable' );

  //$result = $obj->COPY ( $options, $del);
  //$t->isa_ok( $result,      'NULL',   'call to method COPY ');
  $t->todo( "call to method COPY using $options, $del ");


  //checking method 'PROPPATCH'
  $t->is ( $aMethods[13],      'PROPPATCH',   'PROPPATCH() is callable' );

  //$result = $obj->PROPPATCH ( $options);
  //$t->isa_ok( $result,      'NULL',   'call to method PROPPATCH ');
  $t->todo( "call to method PROPPATCH using $options ");


  //checking method 'LOCK'
  $t->is ( $aMethods[14],      'LOCK',   'LOCK() is callable' );

  //$result = $obj->LOCK ( $options);
  //$t->isa_ok( $result,      'NULL',   'call to method LOCK ');
  $t->todo( "call to method LOCK using $options ");


  //checking method 'UNLOCK'
  $t->is ( $aMethods[15],      'UNLOCK',   'UNLOCK() is callable' );

  //$result = $obj->UNLOCK ( $options);
  //$t->isa_ok( $result,      'NULL',   'call to method UNLOCK ');
  $t->todo( "call to method UNLOCK using $options ");


  //checking method 'checkLock'
  $t->is ( $aMethods[16],      'checkLock',   'checkLock() is callable' );

  //$result = $obj->checkLock ( $path);
  //$t->isa_ok( $result,      'NULL',   'call to method checkLock ');
  $t->todo( "call to method checkLock using $path ");


  //checking method 'create_database'
  $t->is ( $aMethods[17],      'create_database',   'create_database() is callable' );

  //$result = $obj->create_database ( );
  //$t->isa_ok( $result,      'NULL',   'call to method create_database ');
  $t->todo( "call to method create_database using  ");


  //checking method 'HTTP_WebDAV_Server'
  $t->is ( $aMethods[18],      'HTTP_WebDAV_Server',   'HTTP_WebDAV_Server() is callable' );

  //$result = $obj->HTTP_WebDAV_Server ( );
  //$t->isa_ok( $result,      'NULL',   'call to method HTTP_WebDAV_Server ');
  $t->todo( "call to method HTTP_WebDAV_Server using  ");


  //checking method 'http_OPTIONS'
  $t->is ( $aMethods[19],      'http_OPTIONS',   'http_OPTIONS() is callable' );

  //$result = $obj->http_OPTIONS ( );
  //$t->isa_ok( $result,      'NULL',   'call to method http_OPTIONS ');
  $t->todo( "call to method http_OPTIONS using  ");


  //checking method 'http_PROPFIND'
  $t->is ( $aMethods[20],      'http_PROPFIND',   'http_PROPFIND() is callable' );

  //$result = $obj->http_PROPFIND ( );
  //$t->isa_ok( $result,      'NULL',   'call to method http_PROPFIND ');
  $t->todo( "call to method http_PROPFIND using  ");


  //checking method 'http_PROPPATCH'
  $t->is ( $aMethods[21],      'http_PROPPATCH',   'http_PROPPATCH() is callable' );

  //$result = $obj->http_PROPPATCH ( );
  //$t->isa_ok( $result,      'NULL',   'call to method http_PROPPATCH ');
  $t->todo( "call to method http_PROPPATCH using  ");


  //checking method 'http_MKCOL'
  $t->is ( $aMethods[22],      'http_MKCOL',   'http_MKCOL() is callable' );

  //$result = $obj->http_MKCOL ( );
  //$t->isa_ok( $result,      'NULL',   'call to method http_MKCOL ');
  $t->todo( "call to method http_MKCOL using  ");


  //checking method 'http_GET'
  $t->is ( $aMethods[23],      'http_GET',   'http_GET() is callable' );

  //$result = $obj->http_GET ( );
  //$t->isa_ok( $result,      'NULL',   'call to method http_GET ');
  $t->todo( "call to method http_GET using  ");


  //checking method '_get_ranges'
  $t->is ( $aMethods[24],      '_get_ranges',   '_get_ranges() is callable' );

  //$result = $obj->_get_ranges ( $options);
  //$t->isa_ok( $result,      'NULL',   'call to method _get_ranges ');
  $t->todo( "call to method _get_ranges using $options ");


  //checking method '_multipart_byterange_header'
  $t->is ( $aMethods[25],      '_multipart_byterange_header',   '_multipart_byterange_header() is callable' );

  //$result = $obj->_multipart_byterange_header ( $mimetype, $from, $to, $total);
  //$t->isa_ok( $result,      'NULL',   'call to method _multipart_byterange_header ');
  $t->todo( "call to method _multipart_byterange_header using $mimetype, $from, $to, $total ");


  //checking method 'http_HEAD'
  $t->is ( $aMethods[26],      'http_HEAD',   'http_HEAD() is callable' );

  //$result = $obj->http_HEAD ( );
  //$t->isa_ok( $result,      'NULL',   'call to method http_HEAD ');
  $t->todo( "call to method http_HEAD using  ");


  //checking method 'http_PUT'
  $t->is ( $aMethods[27],      'http_PUT',   'http_PUT() is callable' );

  //$result = $obj->http_PUT ( );
  //$t->isa_ok( $result,      'NULL',   'call to method http_PUT ');
  $t->todo( "call to method http_PUT using  ");


  //checking method 'http_DELETE'
  $t->is ( $aMethods[28],      'http_DELETE',   'http_DELETE() is callable' );

  //$result = $obj->http_DELETE ( );
  //$t->isa_ok( $result,      'NULL',   'call to method http_DELETE ');
  $t->todo( "call to method http_DELETE using  ");


  //checking method 'http_COPY'
  $t->is ( $aMethods[29],      'http_COPY',   'http_COPY() is callable' );

  //$result = $obj->http_COPY ( );
  //$t->isa_ok( $result,      'NULL',   'call to method http_COPY ');
  $t->todo( "call to method http_COPY using  ");


  //checking method 'http_MOVE'
  $t->is ( $aMethods[30],      'http_MOVE',   'http_MOVE() is callable' );

  //$result = $obj->http_MOVE ( );
  //$t->isa_ok( $result,      'NULL',   'call to method http_MOVE ');
  $t->todo( "call to method http_MOVE using  ");


  //checking method 'http_LOCK'
  $t->is ( $aMethods[31],      'http_LOCK',   'http_LOCK() is callable' );

  //$result = $obj->http_LOCK ( );
  //$t->isa_ok( $result,      'NULL',   'call to method http_LOCK ');
  $t->todo( "call to method http_LOCK using  ");


  //checking method 'http_UNLOCK'
  $t->is ( $aMethods[32],      'http_UNLOCK',   'http_UNLOCK() is callable' );

  //$result = $obj->http_UNLOCK ( );
  //$t->isa_ok( $result,      'NULL',   'call to method http_UNLOCK ');
  $t->todo( "call to method http_UNLOCK using  ");


  //checking method '_copymove'
  $t->is ( $aMethods[33],      '_copymove',   '_copymove() is callable' );

  //$result = $obj->_copymove ( $what);
  //$t->isa_ok( $result,      'NULL',   'call to method _copymove ');
  $t->todo( "call to method _copymove using $what ");


  //checking method '_allow'
  $t->is ( $aMethods[34],      '_allow',   '_allow() is callable' );

  //$result = $obj->_allow ( );
  //$t->isa_ok( $result,      'NULL',   'call to method _allow ');
  $t->todo( "call to method _allow using  ");


  //checking method 'mkprop'
  $t->is ( $aMethods[35],      'mkprop',   'mkprop() is callable' );

  //$result = $obj->mkprop ( );
  //$t->isa_ok( $result,      'NULL',   'call to method mkprop ');
  $t->todo( "call to method mkprop using  ");


  //checking method '_check_auth'
  $t->is ( $aMethods[36],      '_check_auth',   '_check_auth() is callable' );

  //$result = $obj->_check_auth ( );
  //$t->isa_ok( $result,      'NULL',   'call to method _check_auth ');
  $t->todo( "call to method _check_auth using  ");


  //checking method '_new_uuid'
  $t->is ( $aMethods[37],      '_new_uuid',   '_new_uuid() is callable' );

  //$result = $obj->_new_uuid ( );
  //$t->isa_ok( $result,      'NULL',   'call to method _new_uuid ');
  $t->todo( "call to method _new_uuid using  ");


  //checking method '_new_locktoken'
  $t->is ( $aMethods[38],      '_new_locktoken',   '_new_locktoken() is callable' );

  //$result = $obj->_new_locktoken ( );
  //$t->isa_ok( $result,      'NULL',   'call to method _new_locktoken ');
  $t->todo( "call to method _new_locktoken using  ");


  //checking method '_if_header_lexer'
  $t->is ( $aMethods[39],      '_if_header_lexer',   '_if_header_lexer() is callable' );

  //$result = $obj->_if_header_lexer ( $string, $pos);
  //$t->isa_ok( $result,      'NULL',   'call to method _if_header_lexer ');
  $t->todo( "call to method _if_header_lexer using $string, $pos ");


  //checking method '_if_header_parser'
  $t->is ( $aMethods[40],      '_if_header_parser',   '_if_header_parser() is callable' );

  //$result = $obj->_if_header_parser ( $str);
  //$t->isa_ok( $result,      'NULL',   'call to method _if_header_parser ');
  $t->todo( "call to method _if_header_parser using $str ");


  //checking method '_check_if_header_conditions'
  $t->is ( $aMethods[41],      '_check_if_header_conditions',   '_check_if_header_conditions() is callable' );

  //$result = $obj->_check_if_header_conditions ( );
  //$t->isa_ok( $result,      'NULL',   'call to method _check_if_header_conditions ');
  $t->todo( "call to method _check_if_header_conditions using  ");


  //checking method '_check_uri_condition'
  $t->is ( $aMethods[42],      '_check_uri_condition',   '_check_uri_condition() is callable' );

  //$result = $obj->_check_uri_condition ( $uri, $condition);
  //$t->isa_ok( $result,      'NULL',   'call to method _check_uri_condition ');
  $t->todo( "call to method _check_uri_condition using $uri, $condition ");


  //checking method '_check_lock_status'
  $t->is ( $aMethods[43],      '_check_lock_status',   '_check_lock_status() is callable' );

  //$result = $obj->_check_lock_status ( $path, $exclusive_only);
  //$t->isa_ok( $result,      'NULL',   'call to method _check_lock_status ');
  $t->todo( "call to method _check_lock_status using $path, $exclusive_only ");


  //checking method 'lockdiscovery'
  $t->is ( $aMethods[44],      'lockdiscovery',   'lockdiscovery() is callable' );

  //$result = $obj->lockdiscovery ( $path);
  //$t->isa_ok( $result,      'NULL',   'call to method lockdiscovery ');
  $t->todo( "call to method lockdiscovery using $path ");


  //checking method 'http_status'
  $t->is ( $aMethods[45],      'http_status',   'http_status() is callable' );

  //$result = $obj->http_status ( $status);
  //$t->isa_ok( $result,      'NULL',   'call to method http_status ');
  $t->todo( "call to method http_status using $status ");


  //checking method '_urlencode'
  $t->is ( $aMethods[46],      '_urlencode',   '_urlencode() is callable' );

  //$result = $obj->_urlencode ( $url);
  //$t->isa_ok( $result,      'NULL',   'call to method _urlencode ');
  $t->todo( "call to method _urlencode using $url ");


  //checking method '_urldecode'
  $t->is ( $aMethods[47],      '_urldecode',   '_urldecode() is callable' );

  //$result = $obj->_urldecode ( $path);
  //$t->isa_ok( $result,      'NULL',   'call to method _urldecode ');
  $t->todo( "call to method _urldecode using $path ");


  //checking method '_prop_encode'
  $t->is ( $aMethods[48],      '_prop_encode',   '_prop_encode() is callable' );

  //$result = $obj->_prop_encode ( $text);
  //$t->isa_ok( $result,      'NULL',   'call to method _prop_encode ');
  $t->todo( "call to method _prop_encode using $text ");


  //checking method '_slashify'
  $t->is ( $aMethods[49],      '_slashify',   '_slashify() is callable' );

  //$result = $obj->_slashify ( $path);
  //$t->isa_ok( $result,      'NULL',   'call to method _slashify ');
  $t->todo( "call to method _slashify using $path ");


  //checking method '_unslashify'
  $t->is ( $aMethods[50],      '_unslashify',   '_unslashify() is callable' );

  //$result = $obj->_unslashify ( $path);
  //$t->isa_ok( $result,      'NULL',   'call to method _unslashify ');
  $t->todo( "call to method _unslashify using $path ");


  //checking method '_mergePathes'
  $t->is ( $aMethods[51],      '_mergePathes',   '_mergePathes() is callable' );

  //$result = $obj->_mergePathes ( $parent, $child);
  //$t->isa_ok( $result,      'NULL',   'call to method _mergePathes ');
  $t->todo( "call to method _mergePathes using $parent, $child ");



  $t->todo (  'review all pendings methods in this class');
