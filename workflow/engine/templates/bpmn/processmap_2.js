Ext.onReady(function () {



    /**********************************************************************************
     *
     * Do the Ext (Yahoo UI) Stuff
     *
     **********************************************************************************/


    var west = {
        xtype: "panel",
        title: 'Palette',
        region: 'west',
        split: true,
        width: 75,
        collapsible: true,
        margins: '3 0 3 3',
        cmargins: '3 3 3 3',
        items: {
            html: '<ul id="x-shapes"><li id="x-shapes-task" title="Task" >&nbsp;</li>\n\
                                             <li id="x-shapes-startEvent" title="Start">&nbsp;</li>\n\
                                             <li id="x-shapes-interEvent" title="Intermediate Event">&nbsp;</li>\n\
                                             <li id="x-shapes-endEvent" title="End Event">&nbsp;</li>\n\
                                             <li id="x-shapes-gateways" title="Gateway">&nbsp;</li></ul>'
        }
    };

    var north = {
        xtype: "panel",
        initialSize: 60,
        split: false,
        titlebar: false,
        collapsible: false,
        animate: false,
        region: "north"

    };
    var south = {
        xtype: "panel",
        initialSize: 120,
        height: 100,
        split: true,
        titlebar: false,
        collapsible: true,
        autoScroll: true,
        animate: true,
        region: "south",
        items: {
            region: 'center',
            xtype: 'tabpanel',
            items: [{
                title: 'Properties',
                html: 'Properties'
            },
            {
                title: 'Debug Console',
                html: 'Debug Console'
            }]
        }
    };
    var center = {
        width: 100,
        height: 200,
        xtype: "panel",
        titlebar: true,
        title: "center region",
        autoScroll: true,
        fitToFrame: true,
        region: "center"

    };

    main = function () {
        var layout;
        return {
            init: function () {
                layout = new Ext.Panel({
                    tbar: [{
                        text: 'Save'
                    },
                    {
                        text: 'Save as'
                    },
                    {
                        text: 'Undo'
                    },
                    {
                        text: 'Redo'
                    }

                    ],
                    renderTo: "center1",
                    layout: "border",
                    height: 1000,
                    width: 1000,
                    scope: menu,
                    items: [west, north, south, center]
                });


                workflow = new MyWorkflow("paintarea");
                workflow.setEnableSmoothFigureHandling(true);
                workflow.scrollArea.width = 2000;
                
                var simpleToolbar = new Ext.Toolbar('toolbar');
                simpleToolbar.addButton({
                    text: 'Save',
                    cls: 'x-btn-text-icon scroll-bottom'
                });
                simpleToolbar.addButton({
                    text: 'Save As',
                    cls: 'x-btn-text-icon scroll-bottom'
                });
                simpleToolbar.addButton({
                    text: 'Undo',
                    cls: 'x-btn-text-icon'
                });
                simpleToolbar.addButton({
                    text: 'Redo',
                    cls: 'x-btn-text-icon'
                });

                var dragsource = new Ext.dd.DragSource("x-shapes-task", {
                    ddGroup: 'TreeDD',
                    dragData: {
                        name: "bpmnTask"
                    }
                });
                var dragsource = new Ext.dd.DragSource("x-shapes-startEvent", {
                    ddGroup: 'TreeDD',
                    dragData: {
                        name: "bpmnEventEmptyStart"
                    }
                });
                var dragsource = new Ext.dd.DragSource("x-shapes-interEvent", {
                    ddGroup: 'TreeDD',
                    dragData: {
                        name: "bpmnEventEmptyInter"
                    }
                });
                var dragsource = new Ext.dd.DragSource("x-shapes-endEvent", {
                    ddGroup: 'TreeDD',
                    dragData: {
                        name: "bpmnEventEndSignal"
                    }
                });
                var dragsource = new Ext.dd.DragSource("x-shapes-gateways", {
                    ddGroup: 'TreeDD',
                    dragData: {
                        name: "bpmnGatewayExclusiveData"
                    }
                });

                var droptarget = new Ext.dd.DropTarget("ext-gen51", {
                    ddGroup: 'TreeDD'
                });
                workflow.taskNo = 0; //Initializing Count for the bpmnTask
                var count = 0;
                this.taskName = '';
                droptarget.notifyDrop = function (dd, e, data) {
                    
                    

                    if (data.name) {
                        if (data.name == 'bpmnTask') {
                            count = ++workflow.taskNo; //Incrementing Task No and assigning it to a local variable
                        }
                        var xOffset = workflow.getAbsoluteX();
                        var yOffset = workflow.getAbsoluteY();
                        var scrollLeft = workflow.getScrollLeft();
                        var scrollTop = workflow.getScrollTop();
                        workflow.addFigure(eval("new " + data.name + "(workflow)"), e.xy[0] - xOffset + scrollLeft, e.xy[1] - yOffset + scrollTop);
                        return true;
                    }
                }

            }
        }
    }();

    Ext.EventManager.onDocumentReady(main.init, main, true);

    var menu = new FlowMenu(workflow);
    workflow.addSelectionListener(menu);
    workflow.flow = menu;

    canvas = Ext.get('ext-gen51');

    contextCanvasMenu = new Ext.menu.Menu({
        items: [{
            text: 'Edit Process',
            handler: workflow.editProcess,
            icon: '/skins/ext/images/gray/shapes/more.gif',
            scope: this
        },
        {
            text: 'Export Process',
            handler: workflow.exportProcess,
            scope: this
        },
        {
            text: 'Add Task',
            handler: workflow.addTask,
            scope: this
        },
        {
            text: 'Add Subprocess',
            handler: workflow.subProcess,
            scope: this
        },
        {
            text: 'Horizontal Line',
            handler: workflow.horiLine,
            scope: this
        },
        {
            text: 'Vertical Line',
            handler: workflow.vertiLine,
            scope: this
        },
        {
            text: 'Delete All Lines',
            handler: workflow.delLines,
            scope: this
        },
        {
            text: 'Process Permission',
            handler: workflow.processPermission,
            scope: this
        },
        {
            text: 'Web Entry',
            handler: workflow.webEntry,
            scope: this
        },
        {
            text: 'Case Tracker',
            handler: workflow.caseTracker,
            scope: this
        },
        {
            text: 'Process File Manager',
            handler: workflow.processFileManager,
            scope: this
        },
        {
            text: 'Events',
            handler: workflow.events,
            scope: this
        }]
    });

    canvas.on('contextmenu', function (e) {
        e.stopEvent();
        this.contextCanvasMenu.showAt(e.getXY());
    }, this);

    canvas.on('click', function (e) {
        e.stopEvent();
        if (this.workflow.flow != null) {
/*this.currentFigure = this.workflow.currentSelection;
        this.workflow.flow.myworkflow.removeFigure(this.workflow.flow);
        this.added=false;
      //  this.currentFigure.detachMoveListener(this.workflow.flow);*/
        }

    }, this);

});


ContextmenuConnection = function () {
    Connection.call(this);
    this.sourcePort = null;
    this.targetPort = null;
    this.lineSegments = new Array();
    this.setColor(new Color(128, 128, 255));
    this.setLineWidth(1);
};
ContextmenuConnection.prototype = new Connection();
ContextmenuConnection.prototype.getContextMenu = function () {
    var menu = new Menu();
    var oThis = this;
    menu.appendMenuItem(new MenuItem("NULL Router", null, function () {
        oThis.setRouter(null);
    }));
    menu.appendMenuItem(new MenuItem("Manhatten Router", null, function () {
        oThis.setRouter(new ManhattanConnectionRouter());
    }));
    menu.appendMenuItem(new MenuItem("Bezier Router", null, function () {
        oThis.setRouter(new BezierConnectionRouter());
    }));
    menu.appendMenuItem(new MenuItem("Fan Router", null, function () {
        oThis.setRouter(new FanConnectionRouter());
    }));
    return menu;
};

function debug(msg) {
    var console = document.getElementById("debug");
    console.innerHTML = console.innerHTML + "<br>" + msg;
}