<?php
/**
 * showFieldAjax.php
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
global $HTTP_SESSION_VARS;
global $G_FORM;

G::LoadInclude ( 'ajax' );
/*
if(isset($HTTP_SESSION_VARS['CURRENT_APPLICATION'])){
if ($HTTP_GET_VARS['__dynaform__'] == '')
  $filename = $HTTP_SESSION_VARS['CURRENT_REQ_DYNAFORM_FILENAME'];
if ($HTTP_GET_VARS['__filename__'] != '')
  $filename = $HTTP_GET_VARS['__filename__'];

$Connection = new DBConnection;
$ses = new DBSession($Connection);

if ($HTTP_GET_VARS['__dynaform__'] == '') {
  $Dataset = $ses->Execute("SELECT UID FROM REQ_DYNAFORM WHERE REQ_FILENAME = '$filename' AND UID_PROCESS = '" . $HTTP_SESSION_VARS['PROCESS_GUID']."'");
  $Row     = $Dataset->Read();
}
else {
	$Dataset = $ses->Execute('SELECT REQ_FILENAME FROM REQ_DYNAFORM WHERE UID = "' . $HTTP_GET_VARS['__dynaform__'].'"');
  $Row      = $Dataset->Read();

  $filename = $Row['REQ_FILENAME'];
}

if ($HTTP_GET_VARS['__dynaform__'] == '')
  $HTTP_SESSION_VARS['CURRENT_REQ_DYNAFORM'] = $Row['UID'];

}
*/
  $filename = 'rbac/userAssignRole';


$fieldName  	= get_ajax_value( 'field' );
$fieldValue  	= get_ajax_value( 'value' );
$fieldParent 	= get_ajax_value( 'parent' );
$function    	= get_ajax_value( 'function' );
$appid    		= get_ajax_value( 'application' );
$Dynaform		  = get_ajax_value( 'Dynaform' );
$InitValue	  = get_ajax_value( 'InitValue' );

switch ( $function ) {
  case 'text' : fillText( $fieldName, $fieldParent, $fieldValue, $appid, $filename ); break;
  case 'dropdown' : fillDropdown( $fieldName, $fieldParent, $fieldValue, $appid, $filename,$InitValue ); break;
  default : echo'<small>none</small>';
}
function fillCaption ( $fieldName,$fieldParent, $valueRecived, $appid, $filename) {
		$options = reload( $fieldName,$fieldParent, $valueRecived, $appid,$filename );
		header("Content-Type: text/xml");
		print '<?xml version="1.0" encoding="UTF-8"?>
					 <data>
		 			 	<value>' . $value . '</value>
					 </data>';
//		 			 	<value>' . utf8_encode($value) . '</value>
}
function fillText ( $fieldName,$fieldParent, $valueRecived, $appid, $filename) {
		$options = reload( $fieldName,$fieldParent, $valueRecived, $appid,$filename );
		$value = "_vacio";
		if(is_array($options))
 		foreach($options as $key => $val){
 			$value = $val;
 		}
		header("Content-Type: text/xml");
		print '<?xml version="1.0" encoding="UTF-8"?>
					 <data>
		 			 	<value>' . $value . '</value>
					 </data>';

//		 			 	<value>'.utf8_encode($value).'</value>

}

function fillDropdown ( $fieldName,$fieldParent, $valueRecived, $appid,$filename, $InitValue ) {

	global $HTTP_SESSION_VARS;
		$options = reload( $fieldName,$fieldParent, $valueRecived, $appid,$filename);
		if(is_array($options))
 		foreach($options as $key => $val){
 			$value .= '<item value="'.$key.'">' . $val .'</item> ';
// 			$value .= '<item value="'.$key.'">'.utf8_encode($val) .'</item> ';
 		}
 		if($value == ""){
 			$value = "_vacio";
 		}else{
 			if($InitValue == 'true'){
 				if(isset($HTTP_SESSION_VARS['INIT_VALUES'][$fieldName])){
//						$value .= '<item value="Init_Values">' . utf8_encode($HTTP_SESSION_VARS['INIT_VALUES'][$fieldName]) .'</item> ';
						$value .= '<item value="Init_Values">' . $HTTP_SESSION_VARS['INIT_VALUES'][$fieldName]  .'</item> ';
				}
			}
 		}
 		//$value = '<item value="1">'.$HTTP_SESSION_VARS['INIT_VALUES'][$fieldName].'</item> ';
 		//$value = '<item value="1">HOLA</item> ';
		//header("Content-Type: text/xml");
		//header("Content-type: text/xml;charset=UTF-8");
		header('Content-Type: text/xml; charset=UTF-8');
		print '<?xml version="1.0" encoding="UTF-8"?>';
		print '<data>'.$value.'</data>';



}

  function LoadOptions( $stQry , $dbc )
  {

  	$stQry = str_replace("''''", "''", $stQry);

    //cuando la conexion es normal... se intenta realizar el query
    $dses = new DBSession;
    $dses->SetTo( $dbc );
    $dses->UseDB( DB_NAME );
    $dset = $dses->Execute($stQry, false, 3 );
    $data = $dset->ReadAbsolute();

    while( $data )
    {
      $key = $data[0];
      $val = $data[1];
      $result[$key] = $val;
      $data = $dset->ReadAbsolute();
    }
    return $result;
  }



function reload( $fieldName,$fieldParent, $valueRecived, $appid, $filename){
		global $HTTP_SESSION_VARS;
		$G_FORM = new Form;
		G::LoadSystem("xmlform");
		G::LoadClass("dynaform");
		$fieldNew[$fieldParent] = $valueRecived;
		$Connection = new DBConnection(DB_HOST, DB_RBAC_USER, DB_RBAC_PASS, DB_RBAC_NAME );;
		$ses = new DBSession($Connection);
		$xml = new Xmlform;
		$vars = explode('][',$fieldName);
		if(is_array($vars)){
			$cant = count($vars);
			$fieldName = $vars[$cant-1];
			if ($cant > 1)
			$filename = $vars[0];
		}
		$vars2 = explode('][',$fieldParent);
		if(is_array($vars2)){
			$cant2 = count($vars2);
			$fieldParent = $vars2[$cant2-1];

		}
		$v = $filename;
    $xml->home = PATH_XMLFORM;

		$fieldXmlform 	= $xml->parseXmlformToArray ($v);

		$fieldNew[$fieldParent] = $fieldXmlform[$fieldName][defaultvalue];
		if($valueRecived != '')	$fieldNew[$fieldParent] = $valueRecived;
		$qry = stripslashes($fieldXmlform[$fieldName][Sql]);
		$fieldNew[$fieldParent] = $fieldXmlform[$fieldName][defaultvalue];
		if($valueRecived != '')	$fieldNew[$fieldParent] = $valueRecived;

		if($qry != ''){
			$conexion = $fieldXmlform[$fieldName][sqlconnection];
		  G::LoadClass('dynaform');
		  $myDyna = new Dynaform($Connection);
		  $fieldsBase = array();

		  if ($HTTP_SESSION_VARS['CURRENT_APPLICATION'] != "0")
		  	$fieldsBase = $myDyna->getFieldsDefaultDynaform( $appid, 0 );
		  if ( $appid != "" ) {
		  	$Fields = G::array_merges ( $fieldsBase, $fieldNew );
		    $qry = $myDyna->replaceTextWithFields($qry, $Fields);
		  }
		  else
		    $qry = "Select '1', 'dynamically filled' ";


		 	eval ( '$Result = "$qry "; ' );
		 	$options = LoadOptions ( $Result ,  $Connection );
		}
		return $options;
}
?>