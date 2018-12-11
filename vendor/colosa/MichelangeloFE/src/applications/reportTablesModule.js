(
    function () {
        var iframeRT,
            reportWindow,
            pathSrc;

        PMDesigner.reporttable = function (event) {
            reportWindow = new PMUI.ui.Window({
                id: 'reportTableWindow',
                title: "Report Tables".translate(),
                width: DEFAULT_WINDOW_WIDTH,
                height: DEFAULT_WINDOW_HEIGHT
            });

            pathSrc = window.parent.location;

            iframeRT = new PMIframe({
                id: 'reporTableIframe',
                src: window.location.href.split("/")[0] + "//" + pathSrc.host + "/sys" + WORKSPACE + "/" + LANG + "/" + SKIN + "/pmTables?PRO_UID=" + PMDesigner.project.id + "&flagProcessmap=1",
                width: DEFAULT_WINDOW_WIDTH,
                height: DEFAULT_WINDOW_HEIGHT - 36,
                scrolling: 'no',
                frameborder: '0'
            });

            reportWindow.addItem(iframeRT);
            reportWindow.open();
            reportWindow.setBodyPadding(0);
        };

        PMDesigner.reporttable.create = function () {
            pathSrc = window.parent.location;
            reportWindow.clearItems();
            iframeRT = new PMIframe({
                id: 'reporTableIframe',
                src: window.location.href.split("/")[0] + "//" + pathSrc.host + "/sys" + WORKSPACE + "/" + LANG + "/" + SKIN + "/pmTables/edit?PRO_UID=" + PMDesigner.project.id + "&tableType=report&flagProcessmap=1",
                width: DEFAULT_WINDOW_WIDTH,
                height: DEFAULT_WINDOW_HEIGHT - 36,
                scrolling: 'no',
                frameborder: '0'
            });
            reportWindow.addItem(iframeRT);
            reportWindow.open();
            reportWindow.setBodyPadding(0);
        }

    }()
);