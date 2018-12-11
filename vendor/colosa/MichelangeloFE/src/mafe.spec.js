global.dom = require('jsdom');
global.$ = null;
global.PMDesigner = {};
global.dom.env({
    html: '<body></body>',
    scripts: ['../lib/jQuery/jquery-1.10.2.min.js',
        '../lib/jQueryUI/jquery-ui-1.10.3.custom.min.js',
        '../lib/underscore/underscore-min.js',
        '../lib/restclient/restclient.js',
        '../lib/pmUI/pmui-1.0.0.js',
        '../src/commandreconnect.js',
        '../src/utils.js',
        '../src/segmentdrag.js',
        '../src/shape.js',
        '../src/flow.js',
        '../src/connectiondrop.js',
        '../src/toolbarpanel.js',
        '../src/process.js',
        '../src/snapper.js',
        '../src/canvas.js',
        '../src/customLayer.js',
        '../src/activity.js',
        '../src/artifact.js',
        '../src/event.js',
        '../src/flow.js',
        '../src/gateway.js',
        '../src/pmLine.js'
    ],
    done: function (errors, _window) {
        if (errors) {
        }
        global.window = _window;
        global.document = _window.document;
        global.$ = global.jQuery = window.$;
        global.PMUI = window.PMUI;
        global.PMCommandReconnect = window.PMCommandReconnect;
        global.IncrementNameCanvas = window.IncrementNameCanvas;
        global.PMSegmentDragBehavior = window.PMSegmentDragBehavior;
        global.PMShape = window.PMShape;
        global.PMFlow = window.PMFlow;
        global.PMConnectionDropBehavior = window.PMConnectionDropBehavior;
        global.ToolbarPanel = window.ToolbarPanel;
        global.PMProcess = window.PMProcess;
        global.PMSnapper = window.PMSnapper;
        global.PMCanvas = window.PMCanvas;
        global.CustomLayer = window.CustomLayer;
        global.PMActivity = window.PMActivity;
        global.PMArtifact = window.PMArtifact;
        global.PMEvent = window.PMEvent;
        global.PMFlow = window.PMFlow;
        global.PMGateway = window.PMGateway;
        global.PMLine = window.PMLine;


        return global.$;
    }
});

if (!$) {
    beforeEach(function () {
        return waitsFor(function () {
            return $;
        });
    });
}

//**CODE**//