<html>
  <style>.Footer .content{padding   :0px !important;}</style>
  <body onresize="autoResizeScreen()" onload="autoResizeScreen()">
  <iframe name="casesFrame" id="casesFrame" src ="../cases/main_init" width="99%" height="768" frameborder="0">
    <p>Your browser does not support iframes.</p>
  </iframe>
  </body>
  <script>
    if ( document.getElementById('pm_submenu') )
      document.getElementById('pm_submenu').style.display = 'none';
    document.documentElement.style.overflowY = 'hidden';

    var oClientWinSize = getClientWindowSize();
    
    function autoResizeScreen() {
      oCasesFrame    = document.getElementById('casesFrame');
      height = oClientWinSize.height-105;
      oCasesFrame.style.height = height;
      oCasesSubFrame = oCasesFrame.contentWindow.document.getElementById('casesSubFrame');

      if(oCasesSubFrame)
        oCasesSubFrame.style.height = height-10;
      else {
        setTimeout('autoResizeScreen()', 2000);
      }
    }
  </script>
</html>