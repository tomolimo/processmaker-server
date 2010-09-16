<?php
/**
 * classGTest.php
 *  
 * ProcessMaker Open Source Edition
 * Copyright (C) 2004 - 2008 Colosa Inc.23
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 * 
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * For more information, contact Colosa Inc, 2566 Le Jeune Rd., 
 * Coral Gables, FL, 33134, USA, or email info@colosa.com.
 * 
 */
		if ( !defined ('PATH_THIRDPARTY') ) {
		require_once(  $_SERVER['PWD']. '/test/bootstrap/unit.php');
		}
		require_once( PATH_THIRDPARTY . '/lime/lime.php');
		require_once( PATH_THIRDPARTY.'lime/yaml.class.php');
		require_once( PATH_GULLIVER .'class.g.php');
		$obj = new G();
		$methods = get_class_methods('G'); 
		$t = new lime_test( 223, new lime_output_color());
		$t->diag('class G' );
		$t->is(  count($methods) , 95,  "class G " . 95 . " methods." );
		$t->isa_ok( $obj  , 'G',  'class G created');
		$t->todo(  'review which PHP version is the minimum for Gulliver');
		$t->is( G::getVersion()  , '3.0-1',  'Gulliver version');  
		$t->todo(  'store the version in a file');
		$t->is( $obj->getIpAddress()  , false,   'getIpAddress()');
		$t->isnt( $obj->getMacAddress()  , '',  'getMacAddress()');
		$t->can_ok( $obj,      'microtime_float', 'microtime_float()');
		$t->can_ok( $obj,      'setFatalErrorHandler' ,  'setFatalErrorHandler()');
		$t->can_ok( $obj,      'setErrorHandler',   'setErrorHandler()');
		$t->is( $obj->fatalErrorHandler( 'Fatal error')  , 'Fatal error',  'fatalErrorHandler()');
		$like = '<table cellpadding=1 cellspacing=0 border=0 bgcolor=#808080 width=250><tr><td ><table cellpadding=2 cellspacing=0 border=0 bgcolor=white width=100%><tr bgcolor=#d04040><td colspan=2 nowrap><font color=#ffffaa><code> ERROR CAUGHT check log file</code></font></td></tr><tr ><td colspan=2 nowrap><font color=black><code>IP address: </code></font></td></tr> </table></td></tr></table>';
		$t->is( $obj->fatalErrorHandler( 'error</b>:abc<br>')  , $like,  'fatalErrorHandler()');
		$t->can_ok( $obj,      'customErrorHandler',   'customErrorHandler()');
	
		G::customErrorHandler ( G_DB_ERROR, "message error", "filename", 10, "context" ) ;
		$t->can_ok( $obj,      'showErrorSource',   'showErrorSource()');
		$t->can_ok( $obj,      'customErrorLog',   'customErrorLog()');
		$t->can_ok( $obj,      'verboseError',   'verboseError()');
		$t->can_ok( $obj,      'encrypt',   'encrypt()');
		$k = URL_KEY;
		$t->is( G::encrypt ("/sysOpenSource", $k),       'Ytap33°jmZ7D46bf2Jo',     'encrypt only workspace');
		$t->is( G::encrypt ("/sysOpenSource/", $k),      'Ytap33°jmZ7D46bf2Jpo',    'encrypt terminal slash');
		$t->is( G::encrypt ("/sysOpenSource/en", $k),    'Ytap33°jmZ7D46bf2Jpo158', 'encrypt two levels');
		$t->is( G::encrypt ("/sysOpenSource/en/test/login/login", $k),         'Ytap33°jmZ7D46bf2Jpo15+cp8ij4F°fo5fZ4mDZ5Jyi4A',            'encrypt normal page');
		$t->is( G::encrypt ("/sysOpenSource/en/test/login/login/demo", $k),    'Ytap33°jmZ7D46bf2Jpo15+cp8ij4F°fo5fZ4mDZ5Jyi4GDRmNCf',      'encrypt additional level');
		$t->is( G::encrypt ("/sysOpenSource/en/test/login/login?a=1&b=2", $k), 'Ytap33°jmZ7D46bf2Jpo15+cp8ij4F°fo5fZ4mDZ5Jyi4HDOcJRWzm2l',  'encrypt normal query string');
		$t->todo( 'encrypt query string plus pipe');
		$t->todo("encrypt query string plus pipe");
		$t->can_ok( $obj,      'decrypt',   'decrypt()');
		$t->is( G::decrypt ('Ytap33°jmZ7D46bf2Jo', $k),  "/sysOpenSource",          'decrypt only workspace');
		$t->is( G::decrypt ('Ytap33°jmZ7D46bf2Jpo', $k),   "/sysOpenSource/",       'decrypt terminal slash');
		$t->is( G::decrypt ('Ytap33°jmZ7D46bf2Jpo158', $k),  "/sysOpenSource/en",   'decrypt two levels');
		$t->is( G::decrypt ('Ytap33°jmZ7D46bf2Jpo15+cp8ij4F°fo5fZ4mDZ5Jyi4A', $k),             "/sysOpenSource/en/test/login/login",       'decrypt normal page');
		$t->is( G::decrypt ('Ytap33°jmZ7D46bf2Jpo15+cp8ij4F°fo5fZ4mDZ5Jyi4GDRmNCf', $k),       "/sysOpenSource/en/test/login/login/demo",  'decrypt additional level');
		$t->is( G::decrypt ('Ytap33°jmZ7D46bf2Jpo15+cp8ij4F°fo5fZ4mDZ5Jyi4HDOcJRWzm2l', $k) ,  "/sysOpenSource/en/test/login/login?a=1&b=2",'decrypt normal query string');
		$t->todo( 'decrypt query string plus pipe');
		$t->can_ok( $obj,      'lookup',   'lookup()');
		$t->is( G::lookup ('optimusprime.colosa.net'),  "192.168.1.22",          'lookup any address');
		$t->can_ok( $obj,      'mk_dir',   'mk_dir()');
		$newDir = '/tmp/test/directory';
		$r = G::verifyPath ( $newDir );
	
	 	if ( $r ) rmdir ( $newDir );
		$r = G::mk_dir ( $newDir );
		$r = G::verifyPath ( $newDir);
		$t->is( $r,      true,   "mk_dir() $newDir");
		$t->can_ok( $obj,      'verifyPath',   "verifyPath() $newDir");
		$t->isnt( PATH_CORE,      'PATH_CORE',   'Constant PATH_CORE');
		$t->isnt( PATH_GULLIVER,      'PATH_GULLIVER',   'Constant PATH_GULLIVER');
		$phatSitio     = "/home/arturo/processmaker/trunk/workflow/engine/class.x.php/";
		$phatBuscar = "/processmaker/trunk/workflow/engine/class.x.php/";
		$t->is(( ereg( $phatBuscar , $phatSitio ) ), 1 ,   'expandPath()');
		$t->is( G::LoadSystem("error"),      NULL,   'LoadSystem()');
		$t->can_ok( $obj,      'RenderPage',   'RenderPage()');
		$t->can_ok( $obj,      'LoadSkin',   'LoadSkin()');
		$t->can_ok( $obj,      'LoadInclude',   'LoadInclude()');
		$t->can_ok( $obj,      'LoadTemplate',   'LoadTemplate()');
		$t->can_ok( $obj,      'LoadClassRBAC',   'LoadClassRBAC()');
		$t->can_ok( $obj,      'LoadClass',   'LoadClass()');
		$t->can_ok( $obj,      'LoadThirdParty',   'LoadThirdParty()');
		$t->can_ok( $obj,      'encryptlink',   'encryptlink()');
		$t->is( G::encryptlink("normal url"),      "normal url",   'encryptlink() normal url');
		$t->todo(  'more tests with encryplink and remove ENABLE_ENCRYPT dependency');
		$t->can_ok( $obj,      'parseURI',   'parseURI()');
		
		G::parseURI("http:/192.168.0.9/sysos/en/wf5/login/login/abc?ab=123&bc=zy");
		$t->todo(  'more tests with parseURI');
		$t->can_ok( $obj,      'streamFile',   'streamFile()');
		$t->can_ok( $obj,      'sendHeaders',   'sendHeaders()');
		$t->todo(  'more tests with sendHeaders');
		$t->can_ok( $obj,      'virtualURI',   'virtualURI()');
		$t->can_ok( $obj,      'createUID',   'createUID()');
		$t->is( G::createUID('directory','filename'),      'bDh5aTBaUG5vNkxwMnByWjJxT2EzNVk___',   'createUID() normal');
		$t->can_ok( $obj,      'getUIDName',   'getUIDName()');
		$t->is( G::getUIDName('bDh5aTBaUG5vNkxwMnByWjJxT2EzNVk___','12345678901234567890'),      false,   'getUIDName() normal?');
		$t->can_ok( $obj,      'formatNumber',   'formatNumber()');
		$t->is( G::formatNumber('100000'),      '100000',   'formatNumber() normal');
		$t->todo(  'is useful the function formatNumber??');
		$t->can_ok( $obj,      'formatDate',   'formatDate()');
		$t->is( G::formatDate( '2001-02-29' ),      '2001-02-29',   'formatDate() ');
		$t->is( G::formatDate( '2001-02-29', 'F d, Y' ),      'Februar01 29, 2001',   'formatDate() '); //is not working
		$t->is( G::formatDate( '2001-02-29', 'd.m.Y' ),      '29.02.2001',   'formatDate() ');
		$t->todo( " the month literal text is defined here!! ");
		$t->todo(  'review all methods in class G');
		$i=1; 
		$t->diag('class G' );
		$t->is(  count($methods) , 95,  "class database " . count($methods) . " methods." ); 
		$t->is( $methods[0]  , 'is_https'					  ,$i++.' is_https');
		$t->is( $methods[1]  , 'array_fill_value'   ,$i++.' array_fill_value');
		$t->is( $methods[2]  , 'generate_password'  ,$i++.' generate_password');
		$t->is( $methods[3]  , 'array_concat'       ,$i++.' array_concat');
		$t->is( $methods[4]  , 'var_compare'     		,$i++.' var_compare');
		$t->is( $methods[5]  , 'var_probe'      		,$i++.' var_probe');
		$t->is( $methods[6]  , 'getVersion'   			,$i++.' getVersion'); 
		$t->is( G::getVersion()  , '3.0-1',  'Gulliver version 3.0-1');  
		$t->is( $methods[7]  , 'getIpAddress' 		  ,$i++.' getIpAddress'); 
		$t->is( $obj->getIpAddress()  , false,   'getIpAddress()');
		$t->is( $methods[8]  , 'getMacAddress'		  ,$i++.' getMacAddress');
		$t->isnt( $obj->getMacAddress()  , '',  'getMacAddress()');
		$t->is( $methods[9]  , 'microtime_float' 		,$i++.' microtime_float');
		$t->can_ok( $obj,      'microtime_float', 'microtime_float()');
		$t->is( $methods[10] , 'setFatalErrorHandler',$i++.' setFatalErrorHandler');
		$t->can_ok( $obj,      'setFatalErrorHandler' ,  'setFatalErrorHandler()');
		$t->is( $methods[11] , 'setErrorHandler'		 ,$i++.' setErrorHandler');
		$t->can_ok( $obj,      'setErrorHandler',   'setErrorHandler()');
		$t->is( $methods[12] , 'fatalErrorHandler'	 ,$i++.' fatalErrorHandler');
		$t->is( $methods[13] , 'customErrorHandler'  ,$i++.' customErrorHandler');
		$t->is( $methods[14] , 'showErrorSource'     ,$i++.' showErrorSource');
		$t->is( $methods[15] , 'customErrorLog'  	 	 ,$i++.' customErrorLog');
		$t->is( $methods[16] , 'verboseError'        ,$i++.' verboseError');
		$t->is( $methods[17] , 'encrypt'     				  ,$i++.' encrypt');
		$t->is( $methods[18] , 'decrypt'     			    ,$i++.' decrypt');
		$t->is( $methods[19] , 'lookup'     				  ,$i++.' lookup');
		$t->is( $methods[20] , 'mk_dir'					    ,$i++.' mk_dir');
		$t->is( $methods[21] , 'rm_dir'    			    ,$i++.' rm_dir');
		$t->is( $methods[22] , 'verifyPath'      	  ,$i++.' verifyPath');
		$t->is( $methods[23] , 'expandPath'    		  ,$i++.' expandPath');
		$t->is( $methods[24] , 'LoadSystem'    		  ,$i++.' LoadSystem');
		$t->is( $methods[25] , 'RenderPage'      	  ,$i++.' RenderPage');
		$t->is( $methods[26] , 'LoadSkin'   				,$i++.' LoadSkin');
		$t->is( $methods[27] , 'LoadInclude' 				,$i++. ' LoadInclude');
		$t->is( $methods[28] , 'LoadAllModelClasses',$i++. ' LoadAllModelClasses');
		$t->is( $methods[29] , 'LoadAllPluginModelClasses',$i++. ' LoadAllPluginModelClasses');
		$t->is( $methods[30] , 'LoadTemplate'       ,$i++. ' LoadTemplate');
		$t->is( $methods[31] , 'LoadClassRBAC'		  ,$i++. ' LoadClassRBAC');
		$t->is( $methods[32] , 'LoadClass'     			,$i++. ' LoadClass');
		$t->is( $methods[33] , 'LoadThirdParty' 		,$i++. ' LoadThirdParty');
		$t->is( $methods[34] , 'encryptlink'      	,$i++. ' encryptlink');
		$t->is( $methods[35] , 'parseURI'  	 				,$i++. ' parseURI');
		$t->is( $methods[36] , 'streamFile'         ,$i++. ' streamFile');
		$t->is( $methods[37] , 'trimSourceCodeFile' ,$i++. ' trimSourceCodeFile');
		$t->is( $methods[38] , 'sendHeaders'     		,$i++. ' sendHeaders');
		$t->is( $methods[39] , 'virtualURI'     		,$i++. ' virtualURI');
		$t->is( $methods[40] , 'createUID'					,$i++. ' createUID');
		$t->is( $methods[41] , 'getUIDName'    			,$i++. ' getUIDName');
		$t->is( $methods[42] , 'formatNumber'      	,$i++. ' formatNumber');
		$t->is( $methods[43] , 'formatDate'     		,$i++. ' formatDate');
		$t->is( $methods[44] , 'getformatedDate'    ,$i++. ' getformatedDate');
		$t->is( $methods[45] , 'arrayDiff'      		,$i++. ' arrayDiff');
		$t->is( $methods[46] , 'complete_field'   	,$i++. ' complete_field');
		$t->is( $methods[47] , 'sqlEscape' 					,$i++. ' sqlEscape');
		$t->is( $methods[48] , 'replaceDataField'		,$i++. ' replaceDataField');
		$t->can_ok( $obj,      'replaceDataField',   'replaceDataField()');
		$t->todo(  'improve the function replaceDataField !!');
		$t->is( $methods[49] , 'loadLanguageFile' 	,$i++. ' loadLanguageFile');
		$t->can_ok( $obj,      'loadLanguageFile',   'loadLanguageFile()');
		$t->todo(  'more tests with the function loadLanguageFile !!');
		$t->is( $methods[50] , 'registerLabel'		  ,$i++. ' registerLabel');
		$t->can_ok( $obj,      'registerLabel',   'registerLabel()');
		$t->todo(  'more tests with the function registerLabel !!');
		$t->is( $methods[51] , 'LoadMenuXml'			  ,$i++. ' LoadMenuXml');
		$t->can_ok( $obj,      'LoadMenuXml',   'LoadMenuXml()');
		$t->todo(  'more tests with the function LoadMenuXml !!');
		$t->is( $methods[52] , 'SendMessageXml'     ,$i++. ' SendMessageXml');
		$t->can_ok( $obj,      'SendMessageXml',   'SendMessageXml()');
		$t->todo(  'more tests with the function SendMessageXml !!');
		$t->is( $methods[53] , 'SendTemporalMessage',$i++. ' SendTemporalMessage');
		$t->is( $methods[54] , 'SendMessage'      	,$i++. ' SendMessage');
		$t->can_ok( $obj,      'SendTemporalMessage',   'SendTemporalMessage()');
		$t->todo(  'more tests with the function SendTemporalMessage !!');
		$t->can_ok( $obj,      'SendMessage',   'SendMessage()');
		$t->todo(  'more tests with the function SendMessage !!');
		$t->is( $methods[55] , 'SendMessageText'  	,$i++. ' SendMessageText');
		$t->is( $methods[56] , 'LoadMessage'        ,$i++. ' LoadMessage');
		$t->can_ok( $obj,      'LoadMessage',   'LoadMessage()');
		$t->todo(  'more tests with the function LoadMessage !!');
		$t->is( $methods[57] , 'LoadXmlLabel'     	,$i++. ' LoadXmlLabel');
		$t->can_ok( $obj,      'LoadXmlLabel',   'LoadXmlLabel()');
		$t->todo(  'is useful the function LoadXmlLabel ??? delete it!!');
		$t->is( $methods[58] , 'LoadMessageXml'     ,$i++. ' LoadMessageXml');
		$t->can_ok( $obj,      'LoadMessageXml',   'LoadMessageXml()');
		$t->todo(  'more tests with the function LoadMessageXml !!');
		$t->is( $methods[59] , 'LoadTranslationObject',$i++. ' LoadTranslationObject');
		$t->can_ok( $obj,      'LoadTranslation',   'LoadTranslation()');
		$t->todo(  'more tests with the function LoadTranslation !!');                      
		$t->is( $methods[60] , 'LoadTranslation'		,$i++. ' LoadTranslation');
		$t->is( $methods[61] , 'LoadArrayFile'    	,$i++. ' LoadArrayFile');
		$t->can_ok( $obj,      'LoadArrayFile',   'LoadArrayFile()');
		$t->todo(  'more tests with the function LoadArrayFile !!');
		$t->is( $methods[62] , 'expandUri'      		,$i++. ' expandUri');
		$t->can_ok( $obj,      'expandUri',   'expandUri()');
		$t->todo(  'more tests with the function expandUri !!');
		$t->is( $methods[63] , 'genericForceLogin'  ,$i++. ' genericForceLogin');
		$t->can_ok( $obj,      'genericForceLogin',   'genericForceLogin()');
		$t->todo(  'more tests with the function genericForceLogin !!');
		$t->is( $methods[64] , 'capitalize'     		,$i++. ' capitalize');
		$t->is( $methods[65] , 'toUpper'      			,$i++. ' toUpper');
		$t->is( $methods[66] , 'toLower'   					,$i++. ' toLower');
		$t->is( $methods[67] , 'http_build_query' 	,$i++. ' http_build_query');
		$t->is( $methods[68] , 'header'						  ,$i++. ' header');
		$t->can_ok( $obj,      'http_build_query',   'http_build_query()');
		$t->todo(  'more tests with the function http_build_query !!');
		$t->can_ok( $obj,      'header',   'header()');
		$t->todo(  'more tests with the function header !!');
		$t->is( $methods[69] , 'forceLogin' 	      ,$i++. ' forceLogin');
		$t->can_ok( $obj,      'forceLogin',   'forceLogin()');
		$t->todo(  'more tests with the function forceLogin , DELETE IT!!');
		$t->is( $methods[70] , 'add_slashes'				,$i++. ' add_slashes');
		$t->can_ok( $obj,      'add_slashes',   'add_slashes()');
		$t->todo(  'more tests with the function add_slashes !!');
		$t->is( $methods[71] , 'uploadFile'					,$i++. ' uploadFile');
		$t->can_ok( $obj,      'uploadFile',   'uploadFile()');
		$t->todo(  'more tests with the function uploadFile !!');
		$t->is( $methods[72] , 'resizeImage'     		,$i++. ' resizeImage');
		$t->is( $methods[73] , 'array_merges' 			,$i++. ' array_merges');
		$t->can_ok( $obj,      'array_merges',   'array_merges()');
		$t->todo(  'more tests with the function array_merges !!');
		$t->is( $methods[74] , 'array_merge_2'      ,$i++. ' array_merge_2');
		$t->can_ok( $obj,      'array_merge_2',   'array_merge_2()');
		$t->todo(  'more tests with the function array_merge_2 !!');
		$t->is( $methods[75] , 'generateUniqueID'  	,$i++. ' generateUniqueID');
		$t->can_ok( $obj,      'generateUniqueID',   'generateUniqueID()');
		$t->todo(  'more tests with the function sqlEscape !! is useful?  delete it !!');
		$t->can_ok( $obj,      'generateUniqueID',   'generateUniqueID()');
		$t->todo(  'more tests with the function sqlEscape !! is useful?  delete it !!');
		$t->is( $methods[76] , 'generateCode'       ,$i++. ' generateCode');
		$t->is( $methods[77] , 'verifyUniqueID'    	,$i++. ' verifyUniqueID');
		$t->is( $methods[78] , 'is_utf8'     				,$i++. ' is_utf8');
		$t->is( $methods[79] , 'CurDate'     				,$i++. ' CurDate');
		$t->can_ok( $obj,      'CurDate',   'CurDate()');
		$t->todo(  'more tests with the function sqlEscape !!');
		$t->is( $methods[80]  , 'getSystemConstants',$i++. ' getSystemConstants');
		$t->is( $methods[81]  , 'capitalizeWords'   ,$i++. ' capitalizeWords');
		$t->is( $methods[82]  , 'unhtmlentities'    ,$i++. ' unhtmlentities');
		$t->is( $methods[83]  , 'xmlParser'     		,$i++. ' xmlParser');
		$t->is( $methods[84]  , '_del_p'     				,$i++. ' _del_p');
		$t->is( $methods[85]  , 'ary2xml'      			,$i++. ' ary2xml');
		$t->is( $methods[86]  , 'ins2ary'   				,$i++. ' ins2ary');
		$t->is( $methods[87]  , 'evalJScript' 			,$i++. ' evalJScript');
		$t->is( $methods[88]  , 'inflect'						,$i++. ' inflect');
		$t->is( $methods[89]  , 'pr' 								,$i++. ' pr');
		$t->is( $methods[90] , 'dump'								,$i++. ' dump');
		$t->is( $methods[91] , 'stripCDATA'					,$i++. ' stripCDATA');
		$t->is( $methods[92] , 'getSysTemDir'     	,$i++. ' getSysTemDir');
		$t->is( $methods[93] , 'PMWSCompositeResponse'     	,$i++. ' PMWSCompositeResponse');          
		$t->is( $methods[94] , 'emailAddress'     					,$i++. ' emailAddress');
		$t->is( count( $methods )  , --$i		, count( $methods ).' = '.$i.' ok');
		$t->todo(  'review all pendings in this class');
		