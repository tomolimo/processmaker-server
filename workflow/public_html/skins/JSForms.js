/*if (window.attachEvent)
  window.attachEvent('onload', _OnLoad_);
else
  window.addEventListener('load', _OnLoad_, true);*/

//function _OnLoad_() {


onload=function(){

	if (self.setNewDates)
    self.setNewDates();

  if (self.setReloadFields)
    self.setReloadFields();

  if (self.enableHtmlEdit)
    self.enableHtmlEdit();

  if (self.dynaformOnloadUsers)
    self.dynaformOnloadUsers();

  if (self.dynaformOnload)
    self.dynaformOnload();


}



function refillText( fldName, ajax_server, values ) {
	var objetus;
    objetus = get_xmlhttp();
    objetus.open ("GET", ajax_server + "?" + values, false);
    objetus.onreadystatechange=function() {
        if ( objetus.readyState == 1 )
        {
          var textfield = document.getElementById( 'form[' + fldName + ']' );
          if ( ! isdefined( textfield ))
            var textfield = document.getElementById( fldName );
          textfield.value = '';

        }
        else if ( objetus.readyState==4)
        {
            if( objetus.status==200)
            {
//              alert ( objetus.responseText );
              var xmlDoc = objetus.responseXML;
              if ( xmlDoc ) {
                 var textfield = document.getElementById( 'form[' + fldName + ']' );
                 if ( ! isdefined( textfield ))
                   var textfield = document.getElementById( fldName );
                 var dataArray = xmlDoc.getElementsByTagName('value');
                 if (dataArray[0].firstChild)
                 	 if((dataArray[0].firstChild.xml)!='_vacio'){
                 		 textfield.value = dataArray[0].firstChild.xml;
                 		 if(textfield.type != 'hidden')
                 		   if ( textfield.onchange )
                 			   textfield.onchange();
                 	 }
              }
            }
            else
            {
                window.alert('error-['+ objetus.status +']-' + objetus.responseText );
            }
        }
    }
    objetus.send(null);
}

function refillCaption( fldName, ajax_server, values ){
	var objetus;
    objetus = get_xmlhttp();
    objetus.open ("GET", ajax_server + "?" + values, false);
    objetus.onreadystatechange=function() {
        if ( objetus.readyState == 1 )
        {
          var textfield = document.getElementById( 'FLD_' + fldName );
          textfield.innerHTML = '';

        }
        else if ( objetus.readyState==4)
        {
            if( objetus.status==200)
            {
              var xmlDoc = objetus.responseXML;
              if ( xmlDoc ) {
                 var textfield = document.getElementById( 'FLD_' + fldName );
                 var dataArray = xmlDoc.getElementsByTagName('value');
                 if (dataArray[0].firstChild)
                 	  if((dataArray[0].firstChild.xml)!='_vacio')
                 		  //textfield.innerHTML = '<font size="1">' + dataArray[0].firstChild.xml + '</font>';
                 		  textfield.innerHTML = dataArray[0].firstChild.xml;
              }
            }
            else
            {
                window.alert('error-['+ objetus.status +']-' + objetus.responseText );
            }
        }
    }
    objetus.send(null);
}


function refillDropdown( fldName, ajax_server, values , InitValue)
{

	var objetus;
    objetus = get_xmlhttp();
    objetus.open ("GET", ajax_server + "?" + values, false);
    objetus.onreadystatechange=function() {
        if ( objetus.readyState == 1 )
        {
          var dropdown = document.getElementById( 'form[' + fldName + ']' );

          while ( dropdown.hasChildNodes() )
            dropdown.removeChild(dropdown.childNodes[0]);

        }
        else if ( objetus.readyState==4)
        {
            if( objetus.status==200)
            {
              var xmlDoc = objetus.responseXML;

              if ( xmlDoc ) {
                 var dropdown = document.getElementById( 'form[' + fldName + ']' );
                 var dataArray = xmlDoc.getElementsByTagName('item');
                 itemsNumber = dataArray.length;

                 if(InitValue == true) itemsNumber = dataArray.length-1;
                 for (var i=0; i<itemsNumber; i++){
                    dropdown.options[ dropdown.length] = new Option(dataArray[i].firstChild.xml, dataArray[i].attributes[0].value );
                    if(InitValue == true) {
                    	if(dropdown.options[ dropdown.length-1].value == dataArray[dataArray.length-1].firstChild.xml)
                    		dropdown.options[i].selected = true;
                    }
                 }
                 dropdown.onchange();
              }
            }
            else
            {
                window.alert('error-['+ objetus.status +']-' + objetus.responseText );
            }
        }
    }
    objetus.send(null);
}

function iframe_get_xmlhttp() {
  try {
    xmlhttp = new ActiveXObject('Msxml2.XMLHTTP');
  } catch (e) {
    try {
      xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
    } catch (E) {
      xmlhttp = false;
    }
  }
  if (!xmlhttp && typeof XMLHttpRequest != 'undefined') {
    xmlhttp = new XMLHttpRequest();
  }
  return xmlhttp;
}

function get_xmlhttp() {
        try {
                xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
                try {
                        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (E) {
                        xmlhttp = false;
                }
        }
        if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
                xmlhttp = new XMLHttpRequest();
        }
        return xmlhttp;
}



function refillTextError( div_container, fldName, ajax_server, values )
{
	var objetus;
    objetus = get_xmlhttp();
    objetus.open ("GET", ajax_server + "?" + values, false);
    objetus.onreadystatechange=function() {
        if ( objetus.readyState == 1 )
        {
          var textfield = document.getElementById( 'form[' + fldName + ']' );
          textfield.value = '';
          document.getElementById(div_container).innerHTML = '';

        }
        else if ( objetus.readyState==4)
        {
            if( objetus.status==200)
            {
              var xmlDoc = objetus.responseXML;
              if ( xmlDoc ) {
                 var textfield = document.getElementById( 'form[' + fldName + ']' );
                 var dataArray = xmlDoc.getElementsByTagName('value');
                 textfield.value = dataArray[0].firstChild.xml;
                 var dataArray = xmlDoc.getElementsByTagName('message');
                 if ( dataArray[0].firstChild )
                   document.getElementById(div_container).innerHTML = '<b>' + dataArray[0].firstChild.xml + '</b>';
              }
            }
            else
            {
                window.alert('error-['+ objetus.status +']-' + objetus.responseText );
            }
        }
    }
    objetus.send(null);
}



function iframe_get_xmlhttp() {
  try {
    xmlhttp = new ActiveXObject('Msxml2.XMLHTTP');
  } catch (e) {
    try {
      xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
    } catch (E) {
      xmlhttp = false;
    }
  }
  if (!xmlhttp && typeof XMLHttpRequest != 'undefined') {
    xmlhttp = new XMLHttpRequest();
  }
  return xmlhttp;
}

function iframe_ajax_init(ajax_server, div_container, values, callback) {
	var objetus;
  objetus = iframe_get_xmlhttp();
  objetus.open ('GET', ajax_server + '?' + values, true);
  objetus.onreadystatechange = function() {
    if ( objetus.readyState == 1 ) {
      document.getElementById(div_container).style.display = '';
      document.getElementById(div_container).innerHTML = '...';
    }
    else if (objetus.readyState==4) {
      if (objetus.status==200) {
        document.getElementById(div_container).innerHTML = objetus.responseText;
        if (callback != '')
          callback();
      }
      else {
        window.alert('error-['+ objetus.status +']-' + objetus.responseText );
      }
    }
  }
  objetus.send(null);
}

function iframe_ajax_init_2(ajax_server, div_container, values, callback) {
	var objetus;
  objetus = iframe_get_xmlhttp();
  objetus.open ('GET', ajax_server + '?' + values, true);
  objetus.onreadystatechange = function() {
    if ( objetus.readyState == 1 ) {
      div_container.style.display = '';
      div_container.innerHTML = '...';
    }
    else if (objetus.readyState==4) {
      if (objetus.status==200) {
        div_container.innerHTML = objetus.responseText;
        if (callback != '')
          callback();
      }
      else {
        window.alert('error-['+ objetus.status +']-' + objetus.responseText );
      }
    }
  }
  objetus.send(null);
}

function myEmptyCallback() {
}

function disable (obj) {
  obj.disabled = true;
  return;
}

function enable (obj) {
  obj.disabled = false;
  return;
}

function disableById (id) {
  obj = getField(id);
  obj.disabled = true;
  return;
}

function enableById (id) {
  obj = getField(id);
  obj.disabled = false;
  return;
}

function visible (obj) {
  obj.style.visibility = 'visible';
  return;
}

function hidden (obj) {
  obj.style.visibility = 'hidden';
  return;
}

function visibleById (id) {
  obj = getField(id);
  obj.style.visibility = 'visible';
  return;
}

function hiddenById (id) {
  obj = getField(id);
  obj.style.visibility = 'hidden';
  return;
}

function hiddenRowById (id) {
	row = 'DIV_'+ id +'.style.visibility = \'hidden\';';
	hiden = 'DIV_'+ id +'.style.display = \'none\';';
	eval(row);
	eval(hiden);
  return;
}
function visibleRowById (id) {
	row = 'DIV_'+ id +'.style.visibility = \'visible\';';
	block = 'DIV_'+ id +'.style.display = \'block\';';
	eval(row);
	eval(block);
  return;
}

function setFocus (obj) {
  obj.focus();
  return;
}

function setFocusById (id) {
  obj = getField (id);
  setFocus(obj);
  return;
}

function submitForm () {
  document.webform.submit();
  return;
}

function changeValue(id, newValue) {
  obj = getField(id);
  obj.value = newValue;
  return ;
}

function getValue(obj) {
  return obj.value;
}

function getValueById (id) {
  obj = getField(id);
  return obj.value;
}

function removeCurrencySign (snumber) {
   var aux = '';
   var num = new String (snumber);
   var len = num.length;
   var i = 0;
   for (i=0; !(i>=len); i++)
     if (num.charAt(i) != ',' && num.charAt(i) != '$' && num.charAt(i) != ' ') aux = aux + num.charAt(i);
   return aux;
 }

 function removePercentageSign (snumber) {
   var aux = '';
   var num = new String (snumber);
   var len = num.length;
   var i = 0;
   for (i=0; !(i>=len); i++)
     if (num.charAt(i) != ',' && num.charAt(i) != '%' && num.charAt(i) != ' ') aux = aux + num.charAt(i);
   return aux;
 }

 function toReadOnly(obj) {
 	 if (obj) {
     obj.readOnly = 'readOnly';
     obj.style.background = '#CCCCCC';
   }
   return;
 }

 function toReadOnlyById(id) {
   obj = getField(id);
   if (obj) {
     obj.readOnly = 'readOnly';
     obj.style.background = '#CCCCCC';
   }
   return ;
 }

function getField (id) {
  obj = document.getElementById('form[' + id + ']');
  if (!obj)
    obj = document.getElementById(id);
  return obj;
 }

function getGridField(Grid, Row, Field) {
	obj = document.getElementById('form[' + Grid + ']' + '[' + Row + ']' + '[' + Field + ']');
  return obj;
}

function getGridValueById(Grid, Row, Field) {
  obj = getGridField(Grid, Row, Field);
  if (obj)
    return obj.value;
  else
    return '';
}

function Number_Rows_Grid(Grid, Field) {
	Number_Rows = 1;
	if (getGridField(Grid, Number_Rows, Field)) {
		Number_Rows = 0;
	  while (getGridField(Grid, (Number_Rows + 1), Field))
	    Number_Rows++;
	  return Number_Rows;
	}
	else
	  return 0;
}

function attachFunctionEventOnChange(Obj, TheFunction) {
	Obj.oncustomize = TheFunction;
}

function attachFunctionEventOnChangeById(Id, TheFunction) {
	Obj = getField(Id);
	Obj.oncustomize = TheFunction;
}

function attachFunctionEventOnKeypress(Obj, TheFunction) {
	Obj.attachEvent('onkeypress', TheFunction);
}

function attachFunctionEventOnKeypressById(Id, TheFunction) {
	Obj = getField(Id);
	Obj.attachEvent('onkeypress', TheFunction);
}

function unselectOptions ( field ) {
var radios = document.getElementById('form[' + field + ']');
	if (radios) {
	  var inputs = radios.getElementsByTagName ('input');
	  if (inputs) {
		  for(var i = 0; i < inputs.length; ++i) {
		  	inputs[i].checked = false;
			}
	  }
	}
}

function validDate(TheField, Required) {
	TheYear  = getField(TheField + '][YEAR');
	TheMonth = getField(TheField + '][MONTH');
	TheDay   = getField(TheField + '][DAY');
	if (!TheYear || !TheMonth || !TheDay)
	  return false;
	if (Required)
	  if ((TheYear.value == 0) || (TheMonth.value == 0) || (TheDay.value == 0))
	    return false;
	if (TheMonth.value == 2)
	  if (TheDay.value > 29)
	    return false;
	if ((TheMonth.value == 4) || (TheMonth.value == 6) || (TheMonth.value == 9) || (TheMonth.value == 11))
	  if (TheDay.value > 30)
	    return false;
	return true;
}