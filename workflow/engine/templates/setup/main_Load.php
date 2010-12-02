<body onresize="autoResizeScreen()" onload="autoResizeScreen()">
<iframe name="adminFrame" id="adminFrame" src ="main_init" width="99%" height="200" frameborder="0">
  <p>Your browser does not support iframes.</p>
</iframe>
</body>
<script>
  oClientWinSize = getClientWindowSize();
  if ( document.getElementById('pm_submenu') ) 
    document.getElementById('pm_submenu').style.display = 'none';
  document.documentElement.style.overflowY = 'hidden';
  
  function autoResizeScreen() {
    oCasesFrame    = document.getElementById('adminFrame');
    oClientWinSize = getClientWindowSize();
    height = oClientWinSize.height-105;
    oCasesFrame.style.height = height;
    oCasesSubFrame = oCasesFrame.contentWindow.document.getElementById('setup-frame');

    if(oCasesSubFrame)
      oCasesSubFrame.style.height = height-10;
    else
      setTimeout('autoResizeScreen()', 2000);
  }
</script>
</html>