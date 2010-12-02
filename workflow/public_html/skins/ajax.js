// Popup code
var gPopupMask = null;
var gPopupContainer = null;
var gPopFrame = null;
var gReturnFunc;
var gPopupIsShown = false;

var gHideSelects = false;


var gTabIndexes = new Array();
// Pre-defined list of tags we want to disable/enable tabbing into
var gTabbableTags = new Array("A","BUTTON","TEXTAREA","INPUT","IFRAME");

// If using Mozilla or Firefox, use Tab-key trap.
if (!document.all) {
	document.onkeypress = keyDownHandler;
}

function myEmptyCallback() {
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

function isdefined( variable) {
    return (typeof(window[variable]) == "undefined")?  false: true;
}

//DAN Nov 24, 2006
//parameter asyncronuos was added to work in syncronous mode
// usage: ajax_init(_,_,_,_,false);
function ajax_init( ajax_server,div_container, values, callback,asyncronuos)
{   // DAN Nov 24, 2006. variable asyncronuos was added
	asyncronuos = (asyncronuos!=false)?true:false;

	var objetus;
    objetus = get_xmlhttp();

    try{  // DAN Nov 24, 2006. variable asyncronuos instead of true
    objetus.open("GET", ajax_server + "?" + values, asyncronuos);

  }catch(ss)
  {
  	alert("error"+ss.message);
  }
    objetus.onreadystatechange=function() {
        if ( objetus.readyState == 1 )
        {
            document.getElementById(div_container).style.display = "";
            document.getElementById(div_container).innerHTML = "...";

        }
        else if ( objetus.readyState==4)
        {
            if( objetus.status==200)
            {
                document.getElementById(div_container).innerHTML = objetus.responseText;
                if ( callback != '' )
                  callback();
            }
            else
            {
                window.alert('error-['+ objetus.status +']-' + objetus.responseText );
            }
        }
    }
    objetus.send(null);
}

function ajax_init_2( ajax_server, div, values, callback )
{
	var objetus;
    objetus = get_xmlhttp();
    objetus.open ("GET", ajax_server + "?" + values, true);
    objetus.onreadystatechange=function() {
        if ( objetus.readyState == 1 )
        {
            //document.getElementById(div_container).style.display = "";
            //document.getElementById(div_container).innerHTML = "...";
            div.style.display = "";
            div.innerHTML = "...";
        }
        else if ( objetus.readyState==4)
        {
            if( objetus.status==200)
            {
                //document.getElementById(div_container).innerHTML = objetus.responseText;
                div.innerHTML = objetus.responseText;
                if ( callback != '' )
                  callback();
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