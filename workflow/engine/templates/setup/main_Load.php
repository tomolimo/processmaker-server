<body onresize="autoResizeScreen()" onload="autoResizeScreen()">
<iframe name="adminFrame" id="adminFrame" src ="main_init" width="99%" height="200" frameborder="0">
  <p>Your browser does not support iframes.</p>
</iframe>
</body>
<script>
  oClientWinSize = getClientWindowSize();
  h = getStyle(document.getElementById('pm_menu'),'top');
  h = h.replace("px", "");
  h = parseInt(h) + 18;
  if ( document.getElementById('pm_submenu') ) 
    document.getElementById('pm_submenu').style.display = 'none';
  document.documentElement.style.overflowY = 'hidden';
  function autoResizeScreen() {
    oCasesFrame    = document.getElementById('adminFrame');
    oClientWinSize = getClientWindowSize();
    height = oClientWinSize.height-105;
    oCasesFrame.style.height = height;
    oCasesSubFrame = oCasesFrame.contentWindow.document.getElementById('setup-frame');
    oCasesSubFrame.style.height = height-10;
  }
  function getStyle(targetElement,styleProp) {
    if (targetElement) {
      if (targetElement.currentStyle) return targetElement.currentStyle[styleProp];
      else if (window.getComputedStyle) return document.defaultView.getComputedStyle(targetElement,null).getPropertyValue(styleProp);
    }
  }
</script>
</html>