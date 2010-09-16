<html>
<head>
<style>
body {
 overflow:hidden;
}

#loadPage{
  position: absolute;
  top: 200px;
  left: 200px;
}

.overlay{
  display: block;
  position: absolute;
  top: 0;
  left: 0;
  width: 101%;
  height: 100%;
  background: #ECECEC;
  z-index:1001;   
  padding: 0px;
}

.modal {
  display: block;
  position: absolute;
  top: 25%;
  left: 42%;
  background: #000;
  padding: 0px;
  z-index:1002;
  overflow: hidden;
  border: solid 1px #808080;
  border-width: 1px 0px;
}

.progress {
    display: block;
    position: absolute;
    padding: 2px 3px;
}

.container
{
  
}
.header
{
  background: url(/images/onmouseSilver.jpg) #ECECEC repeat-x 0px 0px;
  border-color: #808080 #808080 #ccc;
  border-style: solid;
  border-width: 0px 1px 1px;
  padding: 0px 10px;
  color: #000000;
  font-size: 9pt;
  font-weight: bold;
  line-height: 1.9;
  font-family: arial,helvetica,clean,sans-serif;
} 

.body
{
  background-color: #f2f2f2;
  border-color: #808080;
  border-style: solid;
  border-width: 0px 1px;
  padding: 10px;
}
</style>
</head>
<body onresize="autoResizeScreen()" onload="autoResizeScreen()">
<div id="fade" class="overlay"></div>
<div class="modal" id="light">
  <div class="header"><?=G::LoadTranslation('ID_LOADING')?></div>
  <div class="body">
    <img src="/images/activity.gif" />
  </div>
</div>
<iframe name="casesFrame" id="casesFrame" src ="../cases/main_init" width="99%" height="200" frameborder="0">
  <p>Your browser does not support iframes.</p>
</iframe>
</body>
<script>
  oClientWinSize = getClientWindowSize();
  h = getStyle(document.getElementById('pm_menu'),'top');
  h = h.replace("px", "");
  h = parseInt(h) + 18;

  document.getElementById('fade').style.top = h+"px";
  document.getElementById('fade').style.height = oClientWinSize.height;
  
  document.getElementById('pm_submenu').style.display = 'none';
  document.documentElement.style.overflowY = 'hidden';
  
  function autoResizeScreen(){
    oCasesFrame    = document.getElementById('casesFrame');
    oClientWinSize = getClientWindowSize();
    height = oClientWinSize.height-105;
    oCasesFrame.style.height = height;
    oCasesSubFrame = oCasesFrame.contentWindow.document.getElementById('casesSubFrame');
    oCasesFrame = oCasesFrame.contentWindow.document.getElementById('mainPane');
    oCasesFrame.style.height = height-10;
    oCasesSubFrame.style.height = height-10;
  }

  function getStyle(targetElement,styleProp)
  {
    if (targetElement) {
      if (targetElement.currentStyle) return targetElement.currentStyle[styleProp];
      else if (window.getComputedStyle) return document.defaultView.getComputedStyle(targetElement,null).getPropertyValue(styleProp);
    }
  }
  
  
</script>
</html>
