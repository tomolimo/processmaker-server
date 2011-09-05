
var Labels = new Array(6);
Labels['ID_THE_CHARACTER'] = new Array(2)
Labels['ID_THE_CHARACTER']['es'] = 'El caracter';
Labels['ID_THE_CHARACTER']['en'] = 'The character';
Labels['ID_NOT_VALID'] = new Array(2)
Labels['ID_NOT_VALID']['es'] = 'no es v·lido para este campo';
Labels['ID_NOT_VALID']['en'] = 'is not valid for this field';
Labels['ID_ERROR_CALLING_CONTROL'] = new Array(2)
Labels['ID_ERROR_CALLING_CONTROL']['es'] = 'Error al llamado de control: no hay control especificado';
Labels['ID_ERROR_CALLING_CONTROL']['en'] = 'Error calling the control: no target control specified';
Labels['ID_ERROR_CALLING_CALENDAR'] = new Array(2)
Labels['ID_ERROR_CALLING_CALENDAR']['es'] = 'Error al llamado del calendario: el parametro especificado no es un control v·lido';
Labels['ID_ERROR_CALLING_CALENDAR']['en'] = 'Error calling the calendar: parameter specified is not valid target control';
Labels['ID_NO_ROOM_FIELDS'] = new Array(2)
Labels['ID_NO_ROOM_FIELDS']['es'] = 'No hay sitio para m·s campos';
Labels['ID_NO_ROOM_FIELDS']['en'] = 'There is no room for more fields';
Labels['ID_NO_ROOM_FORMS'] = new Array(2)
Labels['ID_NO_ROOM_FORMS']['es'] = 'No hay sitio para m·s formularios';
Labels['ID_NO_ROOM_FORMS']['en'] = 'There is no room for more forms';
Labels['ID_PARAMETERS_NOT_EXISTS'] = new Array(2)
Labels['ID_PARAMETERS_NOT_EXISTS']['es'] = 'No existen par·metros';
Labels['ID_PARAMETERS_NOT_EXISTS']['en'] = 'Parameters doesn\'t exist';
Labels['ID_PARAMETER_NOT_FOUND'] = new Array(2)
Labels['ID_PARAMETER_NOT_FOUND']['es'] = 'Par·metro requerido no encontrado';
Labels['ID_PARAMETER_NOT_FOUND']['en'] = 'Required parameter not found';
Labels['ID_FIELD_NOT_EXISTS'] = new Array(2)
Labels['ID_FIELD_NOT_EXISTS']['es'] = 'No existe el campo';
Labels['ID_FIELD_NOT_EXISTS']['en'] = 'Doesn\'t exist the field';
Labels['ID_INVALID_EMAIL']= new Array(2)
Labels['ID_INVALID_EMAIL']['es']="Email invalido";
Labels['ID_INVALID_EMAIL']['en']="Invalid Email";

var keyAlfa = "abcdefghijklmnopqrstuvwxyz ABCDEFGHIJKLMNOPQRSTUVWXYZ·ÈÌÛ˙Ò¡…Õ”⁄— ";
var keyAlfaUS = "abcdefghijklmnopqrstuvwxyz_ABCDEFGHIJKLMNOPQRSTUVWXYZ";
var keyAlfaUS2 = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
var keyDigit = "01234567890";
var keyReal = keyDigit+"-.";
var keyAlfa9 = keyAlfa+keyDigit + ".,@";
var keyAlfa8 = keyAlfaUS2+keyDigit ;
var keyAlfa10 = keyAlfaUS+keyDigit+keyReal+"@";
var keyAny = keyAlfa9+"!#$%&/()=ø?°+*{}[]-_.:,;'|\"\\@";
var keyField = keyAlfaUS + keyDigit;
var ALFA = 1, INTEGER = 2, REAL=3, ANY = 4, ALFANUM = 5; FIELD=6; ALFANUMAOUTSPAC = 7; EMAIL=10; LOGIN=11;

function Upcase(t)
{
	aux = t.value; t.value  = aux.toUpperCase();

}

function isIn ( cadena, car ) {
  var i = 0; sw = 0==1;
  while ( i < cadena.length && !sw ) {
    sw= (cadena.charAt(i) == car);
    i ++;
  }
  return sw;
}


function IsValueOk(objRecieved)
{
	if(!echeck(objRecieved.value))
	{
		alert(Labels['ID_INVALID_EMAIL'][GetCurrentLanguage()]);
		objRecieved.value='';
		return false;
	}
	return true;
}

function echeck(str) {

		var at="@"
		var dot="."
		var lat=str.indexOf(at)
		var lstr=str.length
		var ldot=str.indexOf(dot)
		if (str.indexOf(at)==-1){

		   return false;
		}

		if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr){

		   return false;
		}

		if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr){

		    return false;
		}

		 if (str.indexOf(at,(lat+1))!=-1){

		    return false;
		 }

		 if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot){

		    return false;
		 }

		 if (str.indexOf(dot,(lat+2))==-1){

		    return false;
		 }

		 if (str.indexOf(" ")!=-1){

		    return false;
		 }

 		 return true;
	}

function IsKeyValid(mode,event) {
  var msj;
  evvent=(window.event)?window.event:event;
  if(evvent.keyCode==9)
  {
  return;
  }
  if ( evvent.keyCode  == 13 ) {
  	if (top.document.webform) {
      frm = top.document.webform;
      if ( frm.onsubmit )
        frm.onsubmit();
    }
  	return ;
  }
  //alert(evvent.keyCode);
  car = String.fromCharCode( (evvent.keyCode)?evvent.keyCode:evvent.which );
  //alert(car);
  bOk=false;
  if (mode==ALFA   ) bOk = isIn ( keyAlfa, car );
  if (mode==INTEGER) bOk = isIn ( keyDigit,car );
  if (mode==REAL   ) bOk = isIn ( keyReal, car );
  if (mode==ANY    ) bOk = isIn ( keyAny,  car );
  if (mode==ALFANUM) bOk = isIn ( keyAlfa9,car );
  if (mode==FIELD)   bOk = isIn ( keyField,car );
  if (mode==ALFANUMAOUTSPAC) bOk = isIn ( keyAlfa8,car );
  if (mode==LOGIN) 	 bOk = isIn ( keyAlfa10,car );

  //evvent.returnValue = true;


  if (bOk==false) {

   //alert("asdasd");
   if(browser.isIE)
   {
   alert(Labels['ID_THE_CHARACTER'][GetCurrentLanguage()] + ' ' + String (car) + ' ' + Labels['ID_NOT_VALID'][GetCurrentLanguage()]);
   //evvent.returnValue = false;
   return false;
   }
   else
   {
  		return false;
   }
   return true;
  }
}

function IsAlfa (f) {
  return 1;
}

function IsInteger (f) {
  return 1;
}

function IsReal (f) {
  return 1;
}

function IsAny (f) {
  return 1;
}

function IsAlfaNum (f) {
}


/* modal function*/
  function getValue ( nameField ) {
    val = top.document.webform.elements ["form[" + nameField + "]"].value;
    return val;
  }

  function setModalValue ( nameField, display, value ) {
    var cacheobj=document.webform.elements ["form[" + nameField + "]"];
    largo = cacheobj.options.length;
    for (m = largo - 1;m>=0;m--)  cacheobj.options[m]=null;
    cacheobj.options[0]= new Option( display, value);
    cacheobj.options[0].selected=true;
    return ;
  }

var controls ;

function control1 (nameField) {
	// assing methods
	//this.popup    = cal_popup1;

	// validate input parameters
	if (!nameField)
		return cal_error(Labels['ID_ERROR_CALLING_CONTROL'][GetCurrentLanguage()]);
	if (nameField == null)
		return cal_error(Labels['ID_ERROR_CALLING_CALENDAR'][GetCurrentLanguage()]);

	this.field = document.webform.elements ["form[" + nameField + "]"];
	// register in global collections
	//this.id = controls.length;
	controls = this;
}


  function show_dialog ( nameField, page, width, height ) {
    //var field = document.webform.elements ["form[" + nameField + "]"];

    if (crtl) crtl = null;
    var crtl = new control1(nameField);

    var url = '../controls/' + page;

    var left=300, top=60;
    if( window.screen && window.screen.availHeight )
      {left = (window.screen.availWidth  - width) /2;
        top = (window.screen.availHeight - height)/2 -20;}

    var options = 'top=' + top + ', left=' + left + ', width=' + width + ',height='+ height +
                  ',status=no,resizable=no,dependent=yes,alwaysRaised=yes';

    var obj_dialog = window.open( url, 'Controls', options );

  	obj_dialog.opener = window;
	  obj_dialog.focus();

    return ;
  }

  function open_dialog ( nameField, page, width, height ) {
    //if (crtl) crtl = null;
    //var crtl = new control1(nameField);
    var url = page + "?" + nameField;

    var left=300, top=60;
    if( window.screen && window.screen.availHeight )
      {left = (window.screen.availWidth  - width) /2;
        top = (window.screen.availHeight - height)/2 -20;}

    var options = 'top=' + top + ', left=' + left + ', width=' + width + ',height='+ height +
                  ',status=no,resizable=yes,dependent=yes,alwaysRaised=yes,scrollbars=yes';
    var obj_dialog = window.open( url, 'Controls', options );

    //window.changeValue  ( nameField, "" );
  	obj_dialog.mainWindow = window;
	  obj_dialog.focus();
    return ;
  }

  function showModal ( url, width, height ) {
    options = "dialogHeight:" + height +"px; dialogWidth:" + width+"px; center:yes; resizable:no; status:no;  ";
    res = window.showModalDialog( url, 0, options);
    return res;
  }

  function show_onDemandField ( nameField, times, maxl ) {
    var i = 1;
    var sw = 1;
    while ( i<= times && sw ) {
    	eval (" this.fieldDiv = document.all.div_" + nameField + "_" + i +"; ");
    	if ( this.fieldDiv.style.display == 'none' ) {
    		  sw = 0;
    		  this.fieldDiv.style.display = '';
    	}
    	i =i+1;
    } ;

    if ( sw )
      alert(Labels['ID_NO_ROOM_FIELDS'][GetCurrentLanguage()]);
    else
      if (parent.frames['frameXmlContainer'])
        if (parent.resizeFrame)
      	  parent.resizeFrame(parent.document.getElementById('frameXmlContainer'));
    return ;
  }

  function showOnDemandTab ( fieldName ) {
  	eval (" this.fieldDiv   = document.all.div_" + fieldName +"; ");
  	eval (" this.fieldArD = document.all.arrowD" + fieldName +"; ");
  	eval (" this.fieldArR = document.all.arrowR" + fieldName +"; ");
    this.fieldDiv.style.display = 'none';
    this.fieldArR.style.display = 'none';
    this.fieldArD.style.display = '';
    if (parent.frames['frameXmlContainer'])
      if (parent.resizeFrame)
    	  parent.resizeFrame(parent.document.getElementById('frameXmlContainer'));
  }

  function hideOnDemandTab ( fieldName ) {
  	eval (" this.fieldDiv = document.all.div_" + fieldName +"; ");
  	eval (" this.fieldArD = document.all.arrowD" + fieldName +"; ");
  	eval (" this.fieldArR = document.all.arrowR" + fieldName +"; ");
    this.fieldDiv.style.display = '';
    this.fieldArD.style.display = 'none';
    this.fieldArR.style.display = '';
    if (parent.frames['frameXmlContainer'])
      if (parent.resizeFrame)
    	  parent.resizeFrame(parent.document.getElementById('frameXmlContainer'));
  }

  function show_onDemandForm ( nameField, times ) {
    var i = 1;
    var sw = 1;
    hideOnDemandTab ( nameField );
    while ( i<= times && sw ) {
    	eval (" this.fieldDiv = document.all.div_" + nameField + "_" + i +"; ");
    	if ( this.fieldDiv.style.display == 'none' ) {
    		  sw = 0;
    		  this.fieldDiv.style.display = '';
    	}
    	i =i+1;
    };
    if ( sw )
      alert(Labels['ID_NO_ROOM_FORMS'][GetCurrentLanguage()]);
    else
      if (parent.frames['frameXmlContainer'])
        if (parent.resizeFrame)
      	  parent.resizeFrame(parent.document.getElementById('frameXmlContainer'));
    return ;
  }

  function hide_and_clear_onDemandForm ( formName , index) {
    var i = 1;
    var sw = 1;
  	eval (" this.fieldDiv = document.all.div_" + formName + '_' + index +"; ");
    this.fieldDiv.style.display = 'none';

    baseName = "form[" + formName + "][" + index + "]";
    for ( i = 0; i < document.webform.elements.length; i++ ) {
      qfield = document.webform.elements( i );
      qname = qfield.name;

      if ( qfield.name.slice(0, baseName.length) == baseName )
         qfield.value = '';
    }
    if (parent.frames['frameXmlContainer'])
      if (parent.resizeFrame)
      	parent.resizeFrame(parent.document.getElementById('frameXmlContainer'));
    return ;
  }


  function clear_field (  nameField, index ) {
    field = document.webform.elements ["form[" + nameField + "][" + index + "]" ];
  	field.value = '';
  	return;
  }

// Title: Tigra Calendar
// URL: http://www.softcomplex.com/products/tigra_calendar/
// Version: 3.2 (European date format)
// Date: 10/14/2002 (mm/dd/yyyy)
// Feedback: feedback@softcomplex.com (specify product title in the subject)
// Note: Permission given to use this script in ANY kind of applications if
//    header lines are left unchanged.
// Note: Script consists of two files: calendar?.js and calendar.html
// About us: Our company provides offshore IT consulting services.
//    Contact us at sales@softcomplex.com if you have any programming task you
//    want to be handled by professionals. Our typical hourly rate is $20.

// if two digit year input dates after this year considered 20 century.
var NUM_CENTYEAR = 30;
// is time input control required by default
var BUL_TIMECOMPONENT = false;
// are year scrolling buttons required by default
var BUL_YEARSCROLL = true;

var calendars = [];
var RE_NUM = /^\-?\d+$/;

function calendar1(obj_date1, obj_date2, obj_date3) {

	// assing methods
	this.gen_date = cal_gen_date1;
	this.gen_time = cal_gen_time1;
	this.gen_tsmp = cal_gen_tsmp1;
	this.prs_date = cal_prs_date1;
	this.prs_time = cal_prs_time1;
	this.prs_tsmp = cal_prs_tsmp1;
	this.popup    = cal_popup1;
	this.fecha    = "0";

	// validate input parameters
	if (!obj_date1)
		return cal_error("Error calling the calendar: no target control specified");
	if (obj_date1 == null)
		return cal_error("Error calling the calendar: parameter specified is not valid tardet control");
	this.date1 = obj_date1;
	this.date2 = obj_date2;
	this.date3 = obj_date3;
	this.time_comp = BUL_TIMECOMPONENT;
	this.year_scroll = BUL_YEARSCROLL;

	// register in global collections
	this.id = calendars.length;
	calendars[this.id] = this;
}

function cal_popup1 (str_datetime, pre_path) {
	this.dt_current = this.prs_tsmp(str_datetime );

	if (!this.dt_current) return;

  var left=300, top=60;
  if( window.screen && window.screen.availHeight )
    {left = (window.screen.availWidth  - 200) /2;
      top = (window.screen.availHeight - 215)/2 -20;}

 var Direction = new String(window.location);
 Direction = Direction.replace('http://', '');
 Direction = Direction.replace('https://', '');
 Direction = Direction.split('/');
 if (Direction[1].substr(0, 3) != 'sys') {
   var Key = new String('c0l0s40pt1mu59r1m3');
   var Controls = '';
   var Calendar = '';
   for (i=0; i<('controls'.length); i++) {
   	TheChar = 'controls'.charAt(i);
     AuxPos = (i % Key.length) - 1;
     if (AuxPos < 0)
       AuxPos = Key.length - (AuxPos * (-1));
   	KeyChar = Key.substr(AuxPos, 1);
   	TheChar = String.fromCharCode(TheChar.charCodeAt(0) + KeyChar.charCodeAt(0));
   	Controls += TheChar;
   }
   Controls = encode64(Controls);
   Controls = Controls.replace('∫', '/');
   Controls = Controls.replace('=', '');
   for (i=0; i<('calendar.php'.length); i++) {
   	 TheChar = 'calendar.php'.charAt(i);
      AuxPos = (i % Key.length) - 1;
      if (AuxPos < 0)
        AuxPos = Key.length - (AuxPos * (-1));
   	 KeyChar = Key.substr(AuxPos, 1);
   	 TheChar = String.fromCharCode(TheChar.charCodeAt(0) + KeyChar.charCodeAt(0));
   	 Calendar += TheChar;
   }
   Calendar = encode64(Calendar);
   Calendar = Calendar.replace('∫', '/');
   Calendar = Calendar.replace('=', '');
 }
 else {
 	 if (Direction.length > 6)
     Controls = '../controls';
   else
   	 Controls = 'controls';
   Calendar = 'calendar.php';
 }

 var obj_calwindow = window.open(
		'../' + Controls + '/' + Calendar + '?datetime=' + this.dt_current.valueOf()+ '&id=' + this.id,
		'Calendar', 'width=200,height='+(this.time_comp ? 215 : 190)+
		',status=no,resizable=no,top=' + top + ',left=' + left + ',dependent=yes,alwaysRaised=yes'
	);

	obj_calwindow.opener = window;
	obj_calwindow.focus();



}

// timestamp generating function
function cal_gen_tsmp1 (dt_datetime) {
	return(this.gen_date(dt_datetime) + ' ' + this.gen_time(dt_datetime));
}

// date generating function
function cal_gen_date1 (dt_datetime) {
	return (
		(dt_datetime.getDate() < 10 ? '0' : '') + dt_datetime.getDate() + "-"
		+ (dt_datetime.getMonth() < 9 ? '0' : '') + (dt_datetime.getMonth() + 1) + "-"
		+ dt_datetime.getFullYear()
	);
}
// time generating function
function cal_gen_time1 (dt_datetime) {
	return (
		(dt_datetime.getHours() < 10 ? '0' : '') + dt_datetime.getHours() + ":"
		+ (dt_datetime.getMinutes() < 10 ? '0' : '') + (dt_datetime.getMinutes()) + ":"
		+ (dt_datetime.getSeconds() < 10 ? '0' : '') + (dt_datetime.getSeconds())
	);
}

// timestamp parsing function
function cal_prs_tsmp1 (str_datetime) {
	// if no parameter specified return current timestamp
	if (!str_datetime)
		return (new Date());

	// if positive integer treat as milliseconds from epoch
	if (RE_NUM.exec(str_datetime))
		return new Date(str_datetime);

	// else treat as date in string format
	var arr_datetime = str_datetime.split(' ');
	return this.prs_time(arr_datetime[1], this.prs_date(arr_datetime[0]));
}

// date parsing function
function cal_prs_date1 (str_date) {

	var arr_date = str_date.split('-');

	if (arr_date.length != 3) return cal_error ("Invalid date format: '" + str_date + "'.\nFormat accepted is dd-mm-yyyy.");
	if (!arr_date[0]) return cal_error ("Invalid date format: '" + str_date + "'.\nNo day of month value can be found.");
	if (!RE_NUM.exec(arr_date[0])) return cal_error ("Invalid day of month value: '" + arr_date[0] + "'.\nAllowed values are unsigned integers.");
	if (!arr_date[1]) return cal_error ("Invalid date format: '" + str_date + "'.\nNo month value can be found.");
	if (!RE_NUM.exec(arr_date[1])) return cal_error ("Invalid month value: '" + arr_date[1] + "'.\nAllowed values are unsigned integers.");
	if (!arr_date[2]) return cal_error ("Invalid date format: '" + str_date + "'.\nNo year value can be found.");
	if (!RE_NUM.exec(arr_date[2])) return cal_error ("Invalid year value: '" + arr_date[2] + "'.\nAllowed values are unsigned integers.");

	var dt_date = new Date();
	dt_date.setDate(1);

	if (arr_date[1] < 1 || arr_date[1] > 12) return cal_error ("Invalid month value: '" + arr_date[1] + "'.\nAllowed range is 01-12.");
	dt_date.setMonth(arr_date[1]-1);

	if (arr_date[2] < 100) arr_date[2] = Number(arr_date[2]) + (arr_date[2] < NUM_CENTYEAR ? 2000 : 1900);
	dt_date.setFullYear(arr_date[2]);

	var dt_numdays = new Date(arr_date[2], arr_date[1], 0);
	dt_date.setDate(arr_date[0]);
	if (dt_date.getMonth() != (arr_date[1]-1)) return cal_error ("Invalid day of month value: '" + arr_date[0] + "'.\nAllowed range is 01-"+dt_numdays.getDate()+".");

	return (dt_date)
}

// time parsing function
function cal_prs_time1 (str_time, dt_date) {

	if (!dt_date) return null;
	var arr_time = String(str_time ? str_time : '').split(':');

	if (!arr_time[0]) dt_date.setHours(0);
	else if (RE_NUM.exec(arr_time[0]))
		if (arr_time[0] < 24) dt_date.setHours(arr_time[0]);
		else return cal_error ("Invalid hours value: '" + arr_time[0] + "'.\nAllowed range is 00-23.");
	else return cal_error ("Invalid hours value: '" + arr_time[0] + "'.\nAllowed values are unsigned integers.");

	if (!arr_time[1]) dt_date.setMinutes(0);
	else if (RE_NUM.exec(arr_time[1]))
		if (arr_time[1] < 60) dt_date.setMinutes(arr_time[1]);
		else return cal_error ("Invalid minutes value: '" + arr_time[1] + "'.\nAllowed range is 00-59.");
	else return cal_error ("Invalid minutes value: '" + arr_time[1] + "'.\nAllowed values are unsigned integers.");

	if (!arr_time[2]) dt_date.setSeconds(0);
	else if (RE_NUM.exec(arr_time[2]))
		if (arr_time[2] < 60) dt_date.setSeconds(arr_time[2]);
		else return cal_error ("Invalid seconds value: '" + arr_time[2] + "'.\nAllowed range is 00-59.");
	else return cal_error ("Invalid seconds value: '" + arr_time[2] + "'.\nAllowed values are unsigned integers.");

	dt_date.setMilliseconds(0);
	return dt_date;
}

function cal_error (str_message) {
	alert (str_message);
	return null;
}

  //to call picker date.... added byOnti
  function picker_date(nameField, NotOutOfForm) {
  	if (!NotOutOfForm) {
  	  if (document.webform) {
        var date1=document.webform.elements ["form[" + nameField + "][DAY]"];
        var date2=document.webform.elements ["form[" + nameField + "][MONTH]"];
        var date3=document.webform.elements ["form[" + nameField + "][YEAR]"];
      }
      else {
      	var date1=document.all["form[" + nameField + "][DAY]"];
        var date2=document.all["form[" + nameField + "][MONTH]"];
        var date3=document.all["form[" + nameField + "][YEAR]"];
      }
    }
    else {
    	if (document.webform) {
        var date1=document.webform.elements [nameField + "[DAY]"];
        var date2=document.webform.elements [nameField + "[MONTH]"];
        var date3=document.webform.elements [nameField + "[YEAR]"];
      }
      else {
      	var date1=document.all[nameField + "[DAY]"];
        var date2=document.all[nameField + "[MONTH]"];
        var date3=document.all[nameField + "[YEAR]"];
      }
    }
    var cal1 = new calendar1(date1, date2, date3);
    cal1.popup();
  }




   // Original JavaScript code by Duncan Crombie: dcrombie@chirp.com.au
   // Please acknowledge use of this code by including this header.

   // CONSTANTS
  var separator = ",";  // use comma as 000's separator
  var decpoint = ".";  // use period as decimal point
  var percent = "%";
  var currency = "$";  // use dollar sign for currency

  function formatNumber(number, format, print) {  // use: formatNumber(number, "format")
    if (print) document.write("formatNumber(" + number + ", \"" + format + "\")<br>");

    if (number - 0 != number) return null;  // if number is NaN return null
    if (number == 'Infinity') return null;  // if number is Infinite return null
    if (number == '-Infinity') return null;  // if number is Infinite return null
    var useSeparator = format.indexOf(separator) != -1;  // use separators in number
    var usePercent = format.indexOf(percent) != -1;  // convert output to percentage
    var useCurrency = format.indexOf(currency) != -1;  // use currency format
    var isNegative = (number < 0);
    number = Math.abs (number);
    if (usePercent) number *= 100;
    format = strip(format, separator + percent + currency);  // remove key characters
    number = "" + number;  // convert number input to string

     // split input value into LHS and RHS using decpoint as divider
    var dec = number.indexOf(decpoint) != -1;
    var nleftEnd = (dec) ? number.substring(0, number.indexOf(".")) : number;
    var nrightEnd = (dec) ? number.substring(number.indexOf(".") + 1) : "";

     // split format string into LHS and RHS using decpoint as divider
    dec = format.indexOf(decpoint) != -1;
    var sleftEnd = (dec) ? format.substring(0, format.indexOf(".")) : format;
    var srightEnd = (dec) ? format.substring(format.indexOf(".") + 1) : "";

     // adjust decimal places by cropping or adding zeros to LHS of number
    if (srightEnd.length < nrightEnd.length) {
      var nextChar = nrightEnd.charAt(srightEnd.length) - 0;
      nrightEnd = nrightEnd.substring(0, srightEnd.length);
      if (nextChar >= 5) nrightEnd = "" + ((nrightEnd - 0) + 1);  // round up

 // patch provided by Patti Marcoux 1999/08/06
      while (srightEnd.length > nrightEnd.length) {
        nrightEnd = "0" + nrightEnd;
      }

      if (srightEnd.length < nrightEnd.length) {
        nrightEnd = nrightEnd.substring(1);
        nleftEnd = (nleftEnd - 0) + 1;
      }
    } else {
      for (var i=nrightEnd.length; srightEnd.length > nrightEnd.length; i++) {
        if (srightEnd.charAt(i) == "0") nrightEnd += "0";  // append zero to RHS of number
        else break;
      }
    }

     // adjust leading zeros
    sleftEnd = strip(sleftEnd, "#");  // remove hashes from LHS of format
    while (sleftEnd.length > nleftEnd.length) {
      nleftEnd = "0" + nleftEnd;  // prepend zero to LHS of number
    }

    if (useSeparator) nleftEnd = separate(nleftEnd, separator);  // add separator
    var output = nleftEnd + ((nrightEnd != "") ? "." + nrightEnd : "");  // combine parts
    output = ((useCurrency) ? currency : "") + output + ((usePercent) ? percent : "");
    if (isNegative) {
      // patch suggested by Tom Denn 25/4/2001
      output = (useCurrency) ? "(" + output + ")" : "-" + output;
    }
    return output;
  }



  function strip(input, chars) {  // strip all characters in 'chars' from input
    var output = "";  // initialise output string
    for (var i=0; i < input.length; i++)
      if (chars.indexOf(input.charAt(i)) == -1)
        output += input.charAt(i);
    return output;
  }

  function separate(input, separator) {  // format input using 'separator' to mark 000's
    input = "" + input;
    var output = "";  // initialise output string
    for (var i=0; i < input.length; i++) {
      if (i != 0 && (input.length - i) % 3 == 0) output += separator;
      output += input.charAt(i);
    }
    return output;
  }

  function format_number_1000 (pnumber, decimals) {
    if (isNaN(pnumber)) { return 0};
    if (pnumber=='') { return 0};

    var snum = new String(pnumber);
    var sec = snum.split('.');
    var num = new String ( sec[0] );
    aux = '';
		if (sec[1] != null){
			var num2 = new String ( sec[1] );
		}
		else{
			var num2 = '';
			sec[1] = '';
		}
    var aux  = "";
    var aux2  = "";
    var len  = num.length;
    var len2 = num2.length;

    var i = 0;
    var c = 0;
    for (i = len -1; i >= 0; i-- ) {
      aux = num.charAt ( i) + aux;
      c ++;
      if (c % 3 == 0 && i > 0 && num.charAt (i-1) != '-') aux = "," + aux;
    }
    decimals2 = decimals - len2;

	  if(decimals2 > 0){
	  	sec[2]='';
	  	for (i = 0; i < decimals2; i++ ) {
	    			sec[2] = sec[2]  +  0;
	  	}
	  	sec[1] = sec[1]+sec[2];
	  }else if(decimals == 0) sec[1] = "";
	  else if((sec.length == 1) && (decimals == '')) sec[1] = "00";
	  else if(decimals2 < 0){
	    for (j = 0; j < decimals; j++ ) {
	      aux2 = aux2 + num2.charAt ( j);
	    }
	  	sec[1] = aux2;
	  }

    if (sec[1] && sec[1].length > 0)
    	aux = aux + "." + sec[1];

    //if(sec.length == 1) sec[1] = "00";
    //aux = aux + "." + sec[1];

    return aux ;
  }


  function quita_comas ( snumber ) {
    var aux = "";
    var num = new String (snumber);
    var len = num.length;
    var i = 0;
    for (i = 0; i < len; i++ ) {
      if (num.charAt ( i) != ',' && num.charAt (i) != '$' && num.charAt (i) != ' ' ) aux = aux + num.charAt ( i);
    }
    return aux;
  }


/**
 * Sets/unsets the pointer in browse mode
 *
 * @param   object   the table row
 * @param   object   the color to use for this row
 *
 * @return  boolean  whether pointer is set or not
 * the setPoninterForm is the generic an specific for forms...
 */
function setPointerForm(theRow, thePointerColor)
{
    if (thePointerColor == '' || typeof(theRow.style) == 'undefined') {
        return false;
    }
    if (typeof(document.getElementsByTagName) != 'undefined') {
        var theCells = theRow.getElementsByTagName('td');
    }
    else if (typeof(theRow.cells) != 'undefined') {
        var theCells = theRow.cells;
    }
    else {
        return false;
    }

    var rowCellsCnt  = theCells.length;
    for (var c = 0; c < rowCellsCnt; c++) {
        theCells[c].style.backgroundColor = thePointerColor;
    }

    return true;
} // end of the 'setPointer()' function

function SumarValoresColumna(Columna, NombreBase) {
	NombreBase = new String(NombreBase);
	Aux        = NombreBase.split('][');
	Total      = 0;
	i          = 1;
	if ((Aux[1] > 0) && (Aux[1] < 10))
	  Objeto = window.document.getElementById(NombreBase.replace( /\]\[(.)\]\[/g, "\]\[" + i + "\]\["));
	if ((Aux[1] > 9) && (Aux[1] < 100))
	  Objeto = window.document.getElementById(NombreBase.replace( /\]\[(..)\]\[/g, "\]\[" + i + "\]\["));
	if ((Aux[1] > 99) && (Aux[1] < 1000))
	  Objeto = window.document.getElementById(NombreBase.replace( /\]\[(...)\]\[/g, "\]\[" + i + "\]\["));
	while (Objeto) {
	  Total += Number(removePercentageSign(removeCurrencySign(Objeto.value)));
	  i++;
	  if ((Aux[1] > 0) && (Aux[1] < 10))
	    Objeto = window.document.getElementById(NombreBase.replace( /\]\[(.)\]\[/g, "\]\[" + i + "\]\["));
	  if ((Aux[1] > 9) && (Aux[1] < 100))
	    Objeto = window.document.getElementById(NombreBase.replace( /\]\[(..)\]\[/g, "\]\[" + i + "\]\["));
	  if ((Aux[1] > 99) && (Aux[1] < 1000))
	    Objeto = window.document.getElementById(NombreBase.replace( /\]\[(...)\]\[/g, "\]\[" + i + "\]\["));
	}
	Total = format_number_1000(Total, 2);
	NombreGrid = NombreBase.substring(NombreBase.indexOf('[') + 1, NombreBase.indexOf(']'));
	Objeto = window.document.getElementById('form[SYS_GRID_AGGREGATE_' + NombreGrid + '_' + Columna + ']');
	Objeto.value = Total;
	Objeto = window.document.getElementById('SYS_GRID_AGGREGATE_' + NombreGrid + '_' + Columna);
	Objeto.innerHTML = '= ' +  Total;
}

function GetCurrentLanguage() {
  var Key = new String('c0l0s40pt1mu59r1m3');
	var Direction = new String(window.location);
	var TheLanguage = '';
	Direction = Direction.replace('http://', '');
	Direction = Direction.replace('https://', '');
	Direction = Direction.split('/');
	if (Direction[1].substr(0, 3) == 'sys')
    TheLanguage = Direction[2];
  else {
  	Direction[2] = Direction[2].replace('∫', '/');
  	Direction[2] = decode64(Direction[2]);
  	for (i=0; i<(Direction[2].length-1); i++) {
  		TheChar = Direction[2].charAt(i);
  		if (TheChar != '') {
  		  AuxPos = (i % Key.length) - 1;
  		  if (AuxPos < 0)
  		    AuxPos = Key.length - (AuxPos * (-1));
  		  KeyChar = Key.substr(AuxPos, 1);
  		  TheChar = String.fromCharCode(TheChar.charCodeAt(0) - KeyChar.charCodeAt(0));
  		  TheLanguage = TheLanguage + TheChar;
  	  }
  	}
  }
  return TheLanguage;
}

function decode64(input) {
	 var keyStr = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';
   var output = '';
   var chr1, chr2, chr3;
   var enc1, enc2, enc3, enc4;
   var i = 0;

   input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");

   do {
      enc1 = keyStr.indexOf(input.charAt(i++));
      enc2 = keyStr.indexOf(input.charAt(i++));
      enc3 = keyStr.indexOf(input.charAt(i++));
      enc4 = keyStr.indexOf(input.charAt(i++));

      chr1 = (enc1 << 2) | (enc2 >> 4);
      chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
      chr3 = ((enc3 & 3) << 6) | enc4;

      output = output + String.fromCharCode(chr1);

      if (enc3 != 64) {
         output = output + String.fromCharCode(chr2);
      }
      if (enc4 != 64) {
         output = output + String.fromCharCode(chr3);
      }
   } while (i < input.length);

   return output;
}

function encode64(input) {
	 var keyStr = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';
   var output = "";
   var chr1, chr2, chr3;
   var enc1, enc2, enc3, enc4;
   var i = 0;

   do {
      chr1 = input.charCodeAt(i++);
      chr2 = input.charCodeAt(i++);
      chr3 = input.charCodeAt(i++);

      enc1 = chr1 >> 2;
      enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
      enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
      enc4 = chr3 & 63;

      if (isNaN(chr2)) {
         enc3 = enc4 = 64;
      } else if (isNaN(chr3)) {
         enc4 = 64;
      }

      output = output + keyStr.charAt(enc1) + keyStr.charAt(enc2) +
         keyStr.charAt(enc3) + keyStr.charAt(enc4);
   } while (i < input.length);

   return output;
}

function onChangeForAll(obj) {
  if (obj.onvalidation)      eval(obj.onvalidation);
  if (obj.onstrto)           eval(obj.onstrto);
  if (obj.ondependentfields) eval(obj.ondependentfields);
  if (obj.ontotalize)        eval(obj.ontotalize);
  if (obj.oncustomize)       obj.oncustomize();
}

function ValidateOnChange(ObjectName, Mode) {
	//agregar el cÛdigo para validar el contenido de un control
}

function RefreshWithExternalValue(TheWindow, ExternalField) {
  Parameters = TheWindow.location.search.substring(1).split('&');
  if (Parameters) {
    for (i=0; i<Parameters.length; i++) {
      Aux = Parameters[i].indexOf('=');
      ParameterName = Parameters[i].substring(0, Aux);
      ParametersValue = Parameters[i].substring(Aux + 1);
      if (ParameterName == '__FieldName__')
        Field = ParametersValue;
    }
    if (Field) {
      TheField = getField(Field);
      if (TheField) {
        TheField.value = TheWindow.document.all[ExternalField].value;
        TheField.onchange();
      }
      else {
      	alert(Labels['ID_FIELD_NOT_EXISTS'][GetCurrentLanguage()] + ': "' + Field + '"');
      	TheWindow.close();
      }
    }
    else {
    	alert(Labels['ID_PARAMETER_NOT_FOUND'][GetCurrentLanguage()]);
    	TheWindow.close();
    }
  }
  else {
  	alert(Labels['ID_PARAMETERS_NOT_EXISTS'][GetCurrentLanguage()]);
  	TheWindow.close();
  }
}

function RefreshFieldWithExternalValue(TheField, TheValue, Aux1, Aux2) {
	TheObject = getField(TheField);
	if (TheObject) {
		if (TheObject.type != 'select-one')
			TheObject.value = TheValue;
	  else {
	  	for (i=0; i<TheObject.length; i++) {
	  		if (TheObject[i].value == TheValue)
	  		  TheObject.selectedIndex = i;
	  	}
	  }
	  if (TheObject.onchange)
	    TheObject.onchange();
	}
  else {
  	TheObject = getField(TheField + '][DAY');
  	if (TheObject) {
  		for (i=0; i<TheObject.length; i++) {
	  		if (TheObject[i].value == TheValue)
	  		  TheObject.selectedIndex = i;
	  	}
  	}
  	TheObject = getField(TheField + '][MONTH');
  	if (TheObject) {
  		for (i=0; i<TheObject.length; i++) {
	  		if (TheObject[i].value == Aux1)
	  		  TheObject.selectedIndex = i;
	  	}
  	}
  	TheObject = getField(TheField + '][YEAR');
  	if (TheObject) {
  		for (i=0; i<TheObject.length; i++) {
	  		if (TheObject[i].value == Aux2)
	  		  TheObject.selectedIndex = i;
	  	}
  	}
  }
}



function FormatearCadena(TheType, FieldName) {
	TheField = document.getElementById(FieldName);
	switch (TheType) {
		case 1://To UpperCase
		  TheField.value = TheField.value.toUpperCase();
		break;
		case 2://To LowerCase
		  TheField.value = TheField.value.toLowerCase();
		break;
		case 3://To Capitalize
		  TheValue = TheField.value;
		  NewValue = '';
		  Aux = TheValue.split(' ');
		  for(i=0; i<Aux.length; i++)
        NewValue += Aux[i].substring(0, 1).toUpperCase() + Aux[i].substring(1, Aux[i].length) + ' ';
      TheField.value = NewValue.substring(0, (NewValue.length - 1));
		break;
	}
}



function applyMask(FieldName,mode, milSep, decSep, e)
{
    if(mode==0) return true;
    if(mode==1)
        currencyFormat(FieldName, milSep, decSep, e);

    return false;
}

function currencyFormat(FieldName, milSep, decSep, e) {
//e=catched_event;
fld=document.getElementById(FieldName);
var sep = 0;
var key = '';
var i = j = 0;
var len = len2 = 0;
var strCheck = '0123456789';
var aux = aux2 = '';
var whichCode = (window.Event) ? e.which : e.keyCode;
if (whichCode == 13) return true;
if (whichCode == 8) return true;
key = String.fromCharCode(whichCode);
if (strCheck.indexOf(key) == -1) return false;
len = fld.value.length;
for(i = 0; !(i >= len); i++)
if ((fld.value.charAt(i) != '0') && (fld.value.charAt(i) != decSep)) break;
aux = '';
for(; !(i >= len); i++)
{
    //alert(fld.value.charAt(i)+" -> "+strCheck.indexOf(fld.value.charAt(i)));
if (strCheck.indexOf(fld.value.charAt(i))!=-1)
{
    aux += fld.value.charAt(i);
    //alert(aux);
}
}

aux += key;
zero_found=false;
aux_temp=aux;
aux='';
for(i=0;i<aux_temp.length;i++)
{
    if(aux_temp.charAt(i)==0)
    {
        if(zero_found)
        {
            aux+=aux_temp.charAt(i);
        }
    }
    else
    {
        aux+=aux_temp.charAt(i);
        zero_found=true;
    }


}

len = aux.length;
result_value='';

if (len == 0) result_value = '';
if (len == 1) result_value = '$ 0'+ decSep + '0' + aux;
if (len == 2) result_value = '$ 0'+ decSep + aux;
if (len > 2) {
aux2 = '';
for (j = 0, i = len - 3; i >= 0; i--) {
if (j == 3) {
aux2 += milSep;
j = 0;
}
aux2 += aux.charAt(i);
j++;
}
result_value = '$ ';
len2 = aux2.length;
for (i = len2 - 1; i >= 0; i--)
result_value += aux2.charAt(i);
result_value += decSep + aux.substr(len - 2, len);
}
//alert(result_value);
fld.value=result_value;
return false;
}