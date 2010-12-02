<?
/**
 * inc.JSForms.php
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
$path = '';
$showFieldAjax = 'showFieldAjax.php';

$serverAjax = G::encryptLink($path.$showFieldAjax);

?>
<script language="JavaScript">
function RefreshDependentFields(ObjectName, Fields, InitValue) { 

<?  
  //global $G_FORM;
  $HTTP_SESSION_VARS['INIT_VALUES'] = $G_FORM->Values;
  global $HTTP_GET_VARS;
  if ($HTTP_SESSION_VARS['CURRENT_APPLICATION'] == '') $HTTP_SESSION_VARS['CURRENT_APPLICATION'] = '0';
  	$appid = $HTTP_SESSION_VARS['CURRENT_APPLICATION'];
  	if ($HTTP_GET_VARS['dynaform'] != '')
  	  $Dynaform = '&__dynaform__=' . $HTTP_GET_VARS['dynaform'];
  	if ($HTTP_GET_VARS['filename'] != '')
  	  $Dynaform = '&__filename__=' . $HTTP_GET_VARS['filename'];

?>

    if (getField)
      TheObject = getField(ObjectName);

    if (TheObject) {
	    Fields = Fields.split(',');
	    for (i=0; i<Fields.length; i++) {
        DivObj = document.getElementById('FLD_' + Fields[i]);
        FldObj = document.getElementById('form[' + Fields[i] + ']');

        if(FldObj){
        	if(FldObj.type == 'text'){
        		refillText( Fields[i],'<?=$serverAjax?>', 'function=text&field=' + Fields[i] + '&parent=' + ObjectName + '&value=' + TheObject.value + '<?=$Dynaform?>'+ '&application=' + '<?=$appid?>'+ '&Dynaform=' + '<?=$Dynaform?>' );
        	}
        	if(FldObj.type == 'hidden'){
        		refillText( Fields[i],'<?=$serverAjax?>', 'function=text&field=' + Fields[i] + '&parent=' + ObjectName + '&value=' + TheObject.value + '<?=$Dynaform?>'+ '&application=' + '<?=$appid?>'+ '&Dynaform=' + '<?=$Dynaform?>' );
        	}
        	
        	if(FldObj.type == 'select-one') {
        		refillDropdown( Fields[i],'<?=$serverAjax?>', 'function=dropdown&field=' + Fields[i] + '&parent=' + ObjectName + '&value=' + TheObject.value + '<?=$Dynaform?>'+ '&application=' + '<?=$appid?>'+ '&Dynaform=' + '<?=$Dynaform?>'+ '&InitValue=' + InitValue , InitValue);
        	}
        }else{
        	if(DivObj)
        		refillCaption( Fields[i],'<?=$serverAjax?>', 'function=text&field=' + Fields[i] + '&parent=' + ObjectName + '&value=' + TheObject.value + '<?=$Dynaform?>'+ '&application=' + '<?=$appid?>'+ '&Dynaform=' + '<?=$Dynaform?>' );
        }
      }
    }
<?
 // }
?>
}

function registerDate ( field, options ) {
	var opts = options.split(',');
  var fieldName = 'form['+field+']';
  var divName   = 'DIV['+field+']';
	Obj = getField( divName);
	value = Obj.value;
  myDatePicker = new Bs_DatePicker();
<?
global $G_DATE_FORMAT;
global $HTTP_SESSION_VARS;

$classfile = PATH_CORE  . 'classes/class.user.php';
if(file_exists($classfile))
{
  G::LoadClass('user');
  $DateFormat = User::Get_User_Date_Format($HTTP_SESSION_VARS['USER_LOGGED']);
}

if ($DateFormat == '')
	if (defined('DATE_FORMAT'))
	  $TheDateFormat = DATE_FORMAT;
	else
    $TheDateFormat = $G_DATE_FORMAT;
else
  switch ($DateFormat) {
    case 'en':
      $TheDateFormat = 'us';
    break;
    case 'es':
      $TheDateFormat = 'es';
    break;
  }
?>
  myDatePicker.displayDateFormat = '<?=$TheDateFormat?>';
  myDatePicker.fieldName  = fieldName;
	myDatePicker.loadSkin('win2k');
	myDatePicker.daysNumChars = 2;
	myDatePicker.setDateByIso( value);
  myDatePicker.drawInto(divName);
}
</script>
<script language="JavaScript" src="/skins/JSForms.js"></script>