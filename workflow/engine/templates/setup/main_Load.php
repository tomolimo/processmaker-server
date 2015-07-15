<body onresize="autoResizeScreen()" onload="autoResizeScreen()">
  <style type="text/css">
   *html body {
      overflow-y: hidden;
   }
  </style>
<iframe name="adminFrame" id="adminFrame" src ="main_init" width="99%" height="768" frameborder="0">
  <p>Your browser does not support iframes.</p>
</iframe>
</body>
<script>
  oClientWinSize = getClientWindowSize();
  if ( document.getElementById('pm_submenu') ) 
    document.getElementById('pm_submenu').style.display = 'none';
  document.documentElement.style.overflowY = 'hidden';
  
  function autoResizeScreen() {
   	var containerList1, containerList2;
    oCasesFrame = document.getElementById('adminFrame');
	containerList1 = document.getElementById("pm_header");
	if (document.getElementById("mainMenuBG") &&
		document.getElementById("mainMenuBG").parentNode &&
		document.getElementById("mainMenuBG").parentNode.parentNode &&
		document.getElementById("mainMenuBG").parentNode.parentNode.parentNode &&
		document.getElementById("mainMenuBG").parentNode.parentNode.parentNode.parentNode){
			containerList2 = document.getElementById("mainMenuBG").parentNode.parentNode.parentNode.parentNode;
		}
	if (containerList1 === containerList2) {
		height = oClientWinSize.height - containerList1.clientHeight;
		oCasesFrame.style.height = height;
		if (oCasesFrame.height ) {
			oCasesFrame.height = height;
		}
	} else {
		oClientWinSize = getClientWindowSize();
		height = oClientWinSize.height-90;
		oCasesFrame.style.height = height;
		oCasesSubFrame = oCasesFrame.contentWindow.document.getElementById('setup-frame');

		if(oCasesSubFrame)
		  oCasesSubFrame.style.height = height-5;
		else
		  setTimeout('autoResizeScreen()', 2000);
	}
  }
</script>
</html>