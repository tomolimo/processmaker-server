<?php
    $_ICON_SIZE = '18';
    global $RBAC;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="language" content="en" />
<meta name="author"   content="Erik Amaru Ortiz"/>

<link type="text/css" href="/js/jquery/css/smoothness/jquery-ui-1.7.2.custom.css" rel="stylesheet" />
<link type="text/css" href="/skins/<?php echo SYS_SKIN;?>/style.css" rel="stylesheet" />

<script type="text/javascript" src="/js/jquery/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="/js/jquery/jquery-ui-1.7.2.custom.min.js"></script>
<script type="text/javascript" src="/js/jquery/jquery.layout-latest.js"></script>

<style>
    .submenu{
      text-align: left;
      font-weight: normal;
    }
    .submenu table{
      width: 100%;
      border-spacing: 0px;
    }
    .submenu tr{
      cursor: pointer;
    }
    .menuRow{
        background-color:#FFF;
    }
    .menuRowSelected{
        background-color:#FFF;
    }
    .menuRowPointer{
        background-color:#E0EAEF;
    }
    .submenu a{
        color:#2078A8;
        text-decoration:none;
        font-size: 11px;
    }

    #mainMenu {
        padding: 4px;
        padding-left: 1px;
        font-size: 12px;
        overflow: hidden;
    }

    #mainMenu a {
        padding: 4px;
        padding-left: 1px;
        font-size: 12px;
    }
    .xpadding{
       padding:5px;
       overflow: hidden;
    }

    #paneEastNorthContent,
    #paneEastSouthContent,
    #paneEastCenterContent,
    #paneCenterContent{
        overflow: auto;
    }

    #loadPage{
        position: absolute;
        top: 200px;
        left: 200px;
    }
    .content {
        padding:	0px;
        position:	relative;
        overflow:	none;
    }
</style>


<script type="text/javascript">
    var IS_FIREFOX
	var outerLayout, innerLayout;

	$(document).ready( function() {
        var layoutSettings_Outer = {
            name: "outerLayout" // NO FUNCTIONAL USE, but could be used by custom code to 'identify' a layout
            // options.defaults apply to ALL PANES - but overridden by pane-specific settings
        ,	defaults: {
                size:					"auto"
            ,	minSize:				50
            ,	paneClass:				"pane" 		// default = 'ui-layout-pane'
            ,	resizerClass:			"resizer"	// default = 'ui-layout-resizer'
            ,	togglerClass:			"toggler"	// default = 'ui-layout-toggler'
            ,	buttonClass:			"button"	// default = 'ui-layout-button'
            ,	contentSelector:		".content"	// inner div to auto-size so only it scrolls, not the entire pane!
            ,	contentIgnoreSelector:	"span"		// 'paneSelector' for content to 'ignore' when measuring room for content
            ,	togglerLength_open:		35			// WIDTH of toggler on north/south edges - HEIGHT on east/west edges
            ,	togglerLength_closed:	35			// "100%" OR -1 = full height
            ,	hideTogglerOnSlide:		true		// hide the toggler when pane is 'slid open'
            ,	togglerTip_open:		"Close This Pane"
            ,	togglerTip_closed:		"Open This Pane"
            ,	resizerTip:				"Resize This Pane"
            //	effect defaults - overridden on some panes
            ,	fxName:					"slide"		// none, slide, drop, scale
            ,	fxSpeed_open:			750
            ,	fxSpeed_close:			1500
            ,	fxSettings_open:		{ easing: "easeInQuint" }
            ,	fxSettings_close:		{ easing: "easeOutQuint" }
        }
        ,	north: {
                spacing_open:			1			// cosmetic spacing
            ,	togglerLength_open:		0			// HIDE the toggler button
            ,	togglerLength_closed:	-1			// "100%" OR -1 = full width of pane
            ,	resizable: 				false
            ,	slidable:				false
            //	override default effect
            ,	fxName:					"none"
            }
        ,	south: {
                maxSize:				200
            ,	spacing_closed:			0			// HIDE resizer & toggler when 'closed'
            ,	slidable:				false		// REFERENCE - cannot slide if spacing_closed = 0
            ,	initClosed:				true
           
            }
        ,	west: {
                size:					270
            ,	spacing_closed:			12			// wider space when closed
            ,	togglerLength_closed:	12			// make toggler 'square' - 21x21
            ,	togglerAlign_closed:	"top"		// align to top of resizer
            ,	togglerLength_open:		0			// NONE - using custom togglers INSIDE west-pane
            ,	togglerTip_open:		"Close West Pane"
            ,	togglerTip_closed:		"Open West Pane"
            ,	resizerTip_open:		"Resize West Pane"
            ,	slideTrigger_open:		"slide" 	// default
            ,	initClosed:				false
            //	add 'bounce' option to default 'slide' effect
            ,	fxSettings_open:		{ easing: "easeOutBounce" }
            }
        ,	east: {
                size:					320
            ,	spacing_closed:			21			// wider space when closed
            ,	togglerLength_closed:	21			// make toggler 'square' - 21x21
            ,	togglerAlign_closed:	"top"		// align to top of resizer
            ,	togglerLength_open:		0 			// NONE - using custom togglers INSIDE east-pane
            ,	togglerTip_open:		"Close East Pane"
            ,	togglerTip_closed:		"Open East Pane"
            ,	resizerTip_open:		"Resize East Pane"
            ,	slideTrigger_open:		"click" //slide
            ,	initClosed:				true
            //	override default effect, speed, and settings
            ,	fxName:					"drop"
            ,	fxSpeed:				"normal"
            ,	fxSettings:				{ easing: "easeOutBounce" } // nullify default easing
            ,   onshow_start:			showUpdatedTriggers
            ,   onopen_start:			function () {  }


            }
        ,	center: {
                paneSelector:			".ui-layout-center" 			// sample: use an ID to select pane instead of a class
            ,	onresize:				"innerLayout.resizeAll"	// resize INNER LAYOUT when center pane resizes
            ,	minWidth:				200
            ,	minHeight:				200
            }
        };
		// create the OUTER LAYOUT
		outerLayout = $("#mainPane").layout( layoutSettings_Outer );

        innerLayout = $('#paneEastContent').layout({
			center__paneSelector:	".inner-center"
		,	west__paneSelector:		".inner-west"
		,	east__paneSelector:		".inner-east"
		,	north__size:				170
		,	south__size:				170
		,	spacing_open:			4  // ALL panes
		,	spacing_closed:			4  // ALL panes
		,	west__spacing_closed:	6
		,	east__spacing_closed:	6
		});

        //erik 1
        var westSelector = "#mainPane > .ui-layout-west"; // outer-west pane
		var eastSelector = "#mainPane > .ui-layout-east"; // outer-east pane

		 // CREATE SPANs for pin-buttons - using a generic class as identifiers
		$("<span></span>").addClass("pin-button").prependTo( westSelector );
		$("<span></span>").addClass("pin-button").prependTo( eastSelector );
		// BIND events to pin-buttons to make them functional
		outerLayout.addPinBtn( westSelector +" .pin-button", "west");
		outerLayout.addPinBtn( eastSelector +" .pin-button", "east" );

		 // CREATE SPANs for close-buttons - using unique IDs as identifiers
		$("<span></span>").attr("id", "west-closer" ).prependTo( westSelector );
		$("<span></span>").attr("id", "east-closer").prependTo( eastSelector );
		// BIND layout events to close-buttons to make them functional
		outerLayout.addCloseBtn("#west-closer", "west");
		outerLayout.addCloseBtn("#east-closer", "east");

        //***//
        var sClientbrowser = navigator.userAgent.toLowerCase();
        IS_FIREFOX = false;

        if (sClientbrowser.indexOf('firefox') != -1){
            IS_FIREFOX = true;
        }
        /*$("#mainMenu").accordion({
            fillSpace:!IS_FIREFOX, autoHeight: false
        });*/

        outerLayout.hide('east');

	});

    function showUpdatedTriggers(){
       // alert('showing triggers');
    }

    var PANEL_EAST_OPEN = false;


    function loading(){
        //alert('inicio la carga');
    }
    /**
     * Comment
     */
    function unloading() {
        parent.document.getElementById('light').style.display='none';
        parent.document.getElementById('fade').style.display='none';
    }
    
</script>

</head>
<body onload="unloading()" onunload="loading()">
 
<div id="mainPane" style="width:100%; height:650px" class="panel_modal___processmaker">
    <div class="ui-layout-west">
       <div class="ui-accordion-header ui-helper-reset ui-state-active ui-corner-all">
           <table class="" cellspacing="0" cellpadding="3" border="0" width="100%">
             <tr>
                <td width="35" align="right"><a href="cases_New" target="casesSubFrame" class="submenu"><img src="/images/mail-message-new.png" width="<?=$_ICON_SIZE?>" height="<?=$_ICON_SIZE?>" border="0"/></a></td>
                <td align="left"><a href="cases_New" target="casesSubFrame" class="submenu">&nbsp;New Case</a></td>
             </tr>
          </table>
       </div>
       <div id="mainMenu">
            <?php foreach($_POST['PM_CASES_MENU'] as $menu => $aMenuBlock){?>
            <?php if( isset($aMenuBlock['blockItems']) && sizeof($aMenuBlock['blockItems']) > 0 ){?>
            <h3 class="ui-accordion-header ui-helper-reset ui-state-default ui-corner-all"><?php echo $aMenuBlock['blockTitle']?></h3>
            <div class="ui-accordion-header ui-helper-reset ui-state-active ui-corner-all xpadding">
                <div class="submenu" >
                    <table class="submenu" cellspacing="0" cellpadding="0">
                      <?php foreach($aMenuBlock['blockItems'] as $id => $aMenu){?>
                      <tr onmouseout="setRowClass(this, 'menuRow')" onmouseover="setRowClass(this, 'menuRowPointer')" onclick="setSelected(this)" id="MENU_<?php echo $id?>">
                          <td width="10">
                              <img src="/images/<?php echo isset($aMenu['icon'])? $aMenu['icon']: 'kcmdf.png'?>"  width="18" height="18" border="0"/>
                          </td>
                          <td>
                              <a href="<?php echo isset($aMenu['link'])? $aMenu['link']: ''?>" target="casesSubFrame" class="tableOption">
                                  &nbsp;<?php echo $aMenu['label']?>
                                  <span id="notifier_<?php echo $id?>">
                                    <?php echo isset($aMenu['notifier'])? $aMenu['notifier']: ''?>
                                  </span>
                              </a>
                          </td>
                      </tr>
                      <?}?>
                    </table>
                </div>
            </div>
            <?php }?>
            <?php }?>
        </div>
    </div>

    <div class="ui-layout-east" id="paneEastContent">
        <div class="inner-center"    id="paneEastCenterContent"></div>
        <div class="ui-layout-north" id="paneEastNorthContent"></div>
        <div class="ui-layout-south" id="paneEastSouthContent"></div>
    </div>

    <div class="ui-layout-south"></div>

    <div class="ui-layout-center">
        <iframe name="casesSubFrame" id="casesSubFrame" src ="main?stage=load" width="99%" height="100" frameborder="0">
            <p>Your browser does not support iframes.</p>
        </iframe>
    </div>
</div>
</body>
<script>
var sClientbrowser = parent.getBrowserClient();

function setRowClass (theRow, thePointerClass){
    if (thePointerClass == '' || typeof(theRow.className) == 'undefined') {
        return false;
    }

    if( theRow.className != 'ui-accordion-header ui-helper-reset ui-state-default ui-corner-all' )
        theRow.className = thePointerClass;

    return true;
}


function setSelected(o){
    

    oTables = document.getElementsByTagName('table');

    for(i=0; i<oTables.length; i++){
        if(oTables[i].className == 'submenu'){
            var oTrs = oTables[i].getElementsByTagName('tr');

            for(j=0; j<oTrs.length; j++){
                oTrs[j].className = '';
            }
        }
    }
    setRowClass(o, 'ui-accordion-header ui-helper-reset ui-state-default ui-corner-all');

    A = o.getElementsByTagName('a');
    document.getElementById("casesSubFrame").contentWindow.location.href = A[0].href;
    
}

//MSIE adapting the interface
if(sClientbrowser.browser == 'msie'){
    oDivsSubMenusIE = document.getElementsByTagName('div');
    for(i=0; i<oDivsSubMenusIE.length; i++){
        if(oDivsSubMenusIE[i].className == 'ui-accordion-header ui-helper-reset ui-state-active ui-corner-all xpadding'){
            oDivsSubMenusIE[i].style.width = '260px';
        }
    }

}

//resize the div like arent frame
function autoResizeScreen(){
    oParentFrame = parent.document.getElementById('casesFrame');
    oCasesSubFrame = document.getElementById('casesSubFrame');
    oCasesFrame = document.getElementById('mainPane');
    height = oParentFrame.style.height.substring(0, oParentFrame.style.height.length-2);
    oCasesFrame.style.height = height-10;
    oCasesSubFrame.style.height = height-20;
}


oMainMenuDiv = document.getElementById('mainMenu');
oMenuBlocks = oMainMenuDiv.getElementsByTagName('table');
oFirstRowMenuItem = oMenuBlocks[0].getElementsByTagName('tr');

setSelected(document.getElementById(oFirstRowMenuItem[0].id));

setTimeout('autoResizeScreen()', 500);

</script>
</html> 