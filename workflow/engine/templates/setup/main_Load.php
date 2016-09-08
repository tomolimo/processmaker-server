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
    if (document.getElementById('pm_submenu'))
        document.getElementById('pm_submenu').style.display = 'none';
    document.documentElement.style.overflowY = 'hidden';

    function autoResizeScreen() {
        var pmHeader, container, adminFrame, height, setupFrame, factor;
        adminFrame = document.getElementById('adminFrame');
        pmHeader = document.getElementById("pm_header");
        if (document.getElementById("mainMenuBG") &&
                document.getElementById("mainMenuBG").parentNode &&
                document.getElementById("mainMenuBG").parentNode.parentNode &&
                document.getElementById("mainMenuBG").parentNode.parentNode.parentNode &&
                document.getElementById("mainMenuBG").parentNode.parentNode.parentNode.parentNode) {
            container = document.getElementById("mainMenuBG").parentNode.parentNode.parentNode.parentNode;
        }
        if (pmHeader === container) {
            factor = pmHeader.clientHeight;
        } else {
            factor = 90;
        }
        height = getClientWindowSize().height - factor;
        adminFrame.style.height = height + 'px';
        if (adminFrame.height) {
            adminFrame.height = height + 'px';
        }
        setupFrame = adminFrame.contentWindow.document.getElementById('setup-frame');
        if (setupFrame) {
            setupFrame.style.height = (height - 5) + 'px';
        }
    }
</script>
</html>