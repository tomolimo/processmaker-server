MyWorkflow=function(id){
  Workflow.call(this,id);
  this.html.style.backgroundImage="url(/skins/ext/images/gray/shapes/grid_10.png)";
  //enable the snapToGrid 
  this.setGridWidth(2,2);
  this.setSnapToGrid(true);
};
MyWorkflow.prototype=new Workflow;
MyWorkflow.prototype.type="MyWorkflow";

/**
 * Undo Redo Functionality
 * @Author Girish Joshi
 */
commandListener=function(){
  CommandStackEventListener.call(this);
};
commandListener.prototype=new CommandStackEventListener;
commandListener.prototype.type="commandListener";

/**
 * Add SubProcess
 * @Author Safan Maredia
 */
MyWorkflow.prototype.subProcess= function(_6767)
{
  _6767.subProcessName = 'Sub Process' ;
  var subProcess  = eval("new bpmnSubProcess(_6767) ");
  var xPos = this.workflow.contextX;
  var yPos = this.workflow.contextY;
  _6767.scope.workflow.addFigure(subProcess, xPos, yPos);
  subProcess.actiontype = 'addSubProcess';
  this.workflow.saveShape(subProcess);
}

/**
 * Will add horizontal and verticle scroll bars on DragEnd and OpenProcess
 * @Param Shape Object
 * @Author Girish Joshi
 */
MyWorkflow.prototype.setBoundary = function (oShape) {
  //Left Border
  if (oShape.x < 5) {
    oShape.x = 10;
  }
  //Right Border
  if (oShape.x > 1300 - oShape.width) {
    workflow.main.setWidth(oShape.x+150);
  }
  //Top Border
  if (oShape.y < 20) {
    oShape.y = 20;
  }
  //Bottom Border
  if (oShape.y > 1000 - oShape.height) {
    workflow.main.setHeight(oShape.y+75);
  }
}

/**
 * ExtJS Form on Right Click of Task
 * @Param  Shape Object
 * @Author Safan Maredia
 */
MyWorkflow.prototype.AddTaskContextMenu= function(oShape)
{
  var taskExtObj = new TaskContext();
  if (oShape.id != null) {
    this.canvasTask = Ext.get(oShape.id);
    this.contextTaskmenu = new Ext.menu.Menu({
        items: [
/*
        {
            text: 'Steps',
            iconCls: 'button_menu_ext ss_sprite ss_shape_move_forwards',
            handler: taskExtObj.editTaskSteps,
            scope: oShape
        },
        {
            text: 'Users & Users Group',
            iconCls: 'button_menu_ext ss_sprite ss_group',
            handler: taskExtObj.editUsers,
            scope: oShape
        },
        {
            text: 'Users & Users Groups (ad-hoc)',
            iconCls: 'button_menu_ext ss_sprite ss_group',
            handler: taskExtObj.editUsersAdHoc,
            scope: oShape
        },
*/
        {
            text: 'Transform To',
            iconCls: 'button_menu_ext ss_sprite ss_page_refresh',
            menu: {        // <-- submenu by nested config object
                items: [
                    // stick any markup in a menu
                    {
                        text: 'Sub Process',
                        iconCls: 'button_menu_ext ss_sprite ss_layout_link',
                        type:'bpmnSubProcess',
                        scope:oShape,
                        handler: MyWorkflow.prototype.toggleShapes
                    }
                ]
            },
            scope: this
        },
        {
            text: 'Attach Event',
            iconCls: 'button_menu_ext ss_sprite ss_link',
            menu: {        // <-- submenu by nested config object
                items: [
                    // stick any markup in a menu
                    {
                        text: 'Timer Boundary Event',
                        iconCls: 'button_menu_ext ss_sprite ss_clock',
                        type:'bpmnEventBoundaryTimerInter',
                        scope:oShape,
                        handler: MyWorkflow.prototype.toggleShapes
                    }
                ]
            },
            scope: this
        }
/*        ,
        {
            text: 'Properties',
            handler: taskExtObj.editTaskProperties,
            scope: oShape
        }
*/
        ]
    });
    }

    this.canvasTask.on('contextmenu', function (e) {
        e.stopEvent();
        this.contextTaskmenu.showAt(e.getXY());
    }, this);

}

/**
 * ExtJS Menu on Right Click of Connection
 * @Param  Shape Object
 * @Author Girish Joshi
 */
MyWorkflow.prototype.connectionContextMenu=function(oShape)
{
  this.canvasEvent = Ext.get(oShape.id);
  this.contextEventmenu = new Ext.menu.Menu({
    items: [{
      text: 'Straight Line',
      iconCls: 'button_menu_ext ss_sprite ss_bullet_white ',
      scope: this,
      handler: MyWorkflow.prototype.toggleConnection
    }, {
      text: 'Curvy Line',
      scope: this,
      iconCls: 'button_menu_ext ss_sprite ss_vector',
      handler: MyWorkflow.prototype.toggleConnection
    }, {
      text: 'Angled Line',
      scope: this,
      iconCls: 'button_menu_ext ss_sprite ss_bullet_white ',
      handler: MyWorkflow.prototype.toggleConnection
    }, {
      text: 'Delete Line',
      iconCls: 'button_menu_ext ss_sprite ss_delete',
      scope: this,
      handler:function()
      {
          MyWorkflow.prototype.deleteRoute(oShape.workflow.currentSelection,0)
      }
    }]
  });

  this.canvasEvent.on('contextmenu', function(e) {
    e.stopEvent();
    this.contextEventmenu.showAt(e.getXY());
  }, this);
}

/**
 * Draw2d Functionality of Changing the routers
 * @Param  Shape Object
 * @Author Girish Joshi
 */
MyWorkflow.prototype.toggleConnection=function(oShape)
{
  this.currentSelection.workflow.contextClicked = false;
  switch (oShape.text) {
    case 'NULL Router':
        this.currentSelection.setRouter(null);
    break;
    case 'Angled Line':
        this.currentSelection.setRouter(new ManhattanConnectionRouter());
    break;
    case 'Curvy Line':
        this.currentSelection.setRouter(new BezierConnectionRouter());
    break;
    case 'Straight Line':
        this.currentSelection.setRouter(new FanConnectionRouter());
    break;
    case 'Delete Line':
        this.currentSelection.workflow.getCommandStack().execute(new CommandDelete(this.currentSelection.workflow.getCurrentSelection()));
        ToolGeneric.prototype.execute.call(this);
    break;
  }
}

/**
 * Draw2d Functionality of Adding Port to Gateways
 * @Param  Shape Object
 * @Author Girish Joshi
 */
MyWorkflow.prototype.AddGatewayPorts = function(_40c5)
{
  var TaskPortName = ['inputPort1','inputPort2','outputPort1','outputPort2','outputPort3'];
  var TaskPortType = ['InputPort','InputPort','OutputPort','OutputPort','OutputPort'];
  var TaskPositionX= [0,_40c5.width/2,_40c5.width,_40c5.width/2, 0];
  var TaskPositionY= [_40c5.height/2,0,_40c5.height/2,_40c5.Height, _40c5.height/2 + 10];

  for(var i=0; i< TaskPortName.length ; i++){
    eval('_40c5.'+TaskPortName[i]+' = new '+TaskPortType[i]+'()');                               //Create New Port
    eval('_40c5.'+TaskPortName[i]+'.setWorkflow(_40c5)');                                        //Add port to the workflow
    eval('_40c5.'+TaskPortName[i]+'.setName("'+TaskPortName[i]+'")');                            //Set PortName
    eval('_40c5.'+TaskPortName[i]+'.setZOrder(-1)');                                             //Set Z-Order of the port to -1. It will be below all the figure
    eval('_40c5.'+TaskPortName[i]+'.setBackgroundColor(new Color(255, 255, 255))');              //Setting Background of the port to white
    eval('_40c5.'+TaskPortName[i]+'.setColor(new Color(255, 255, 255))');                        //Setting Border of the port to white
    this.workflow = _40c5.workflow;
    this.workflow.currentSelection  =_40c5;
    eval('_40c5.addPort(_40c5.'+TaskPortName[i]+','+TaskPositionX[i]+', '+TaskPositionY[i]+')');  //Setting Position of the port
  }
}
/**
 * ExtJs Form on right Click of Gateways
 * @Param  Shape Object
 * @Author Girish Joshi
 */
MyWorkflow.prototype.AddGatewayContextMenu=function(_4092)
{
    this.canvasGateway = Ext.get(_4092.id);
    this.contextGatewaymenu = new Ext.menu.Menu({
        items: [{
            text: 'Gateway Type',
            menu: {        // <-- submenu by nested config object
                    items: [
                        // stick any markup in a menu
                        {
                            text: 'Exclusive Gateway',
                            type:'bpmnGatewayExclusiveData',
                            scope:_4092,
                            handler: MyWorkflow.prototype.toggleShapes
                        }, {
                            text: 'Inclusive Gateway',
                            type:'bpmnGatewayInclusive',
                            scope:_4092,
                            handler: MyWorkflow.prototype.toggleShapes
                        }, {
                            text: 'Parallel Gateway',
                            type:'bpmnGatewayParallel',
                            scope:_4092,
                            handler: MyWorkflow.prototype.toggleShapes
                        }
/*                        , {
                            text: 'Complex Gateway',
                            type:'bpmnGatewayComplex',
                            scope:_4092,
                            handler: MyWorkflow.prototype.toggleShapes
                        }, {
                            text: 'Event Based Gateway',
                            type:'bpmnGatewayExclusiveEvent',
                            scope:_4092,
                            handler: MyWorkflow.prototype.toggleShapes
                        }
*/
                    ]
                },
            scope: this
        },{
            text: 'Properties',
            handler: this.editGatewayProperties,
            scope: this
        }]
    });

this.canvasGateway.on('contextmenu', function(e) {
    e.stopEvent();
    this.contextGatewaymenu.showAt(e.getXY());
}, this);
}

/**
 * ExtJs Menu on right Click of SubProcess
 * @Param  Shape Object
 * @Author Safan Maredia
 */
MyWorkflow.prototype.AddSubProcessContextMenu=function(_4092)
{
    var taskExtObj = new TaskContext();
    this.canvasSubProcess = Ext.get(_4092.id);
    this.contextSubProcessmenu = new Ext.menu.Menu({
        items: [
            {
                text: 'Transform To',
                menu: {        // <-- submenu by nested config object
                    items: [
                        // stick any markup in a menu
                        {
                            text: 'Task',
                            type:'bpmnTask',
                            scope:_4092,
                            handler: MyWorkflow.prototype.toggleShapes
                        }
                    ]
                },
                scope: this
            },
            {
            text: 'Properties',
            handler: taskExtObj.editSubProcessProperties,
            scope: this
        }]
    });

this.canvasSubProcess.on('contextmenu', function(e) {
    e.stopEvent();
    this.contextSubProcessmenu.showAt(e.getXY());
}, this);
}


//Window pop up function when user clicks on Gateways properties

MyWorkflow.prototype.editGatewayProperties= function()
{
    
}
/**
 * Changing the Shape and Maintaining the Connections
 * @Param  Shape Object
 * @Author Girish Joshi
 */
MyWorkflow.prototype.toggleShapes=function(item)
{
    
    //Set the context Clicked Flag to false because context click action is performed
    if(item.scope.workflow != null){
    item.scope.workflow.contextClicked = false;
    if(item.scope.workflow.currentSelection != null){

        //Get all the ports of the shapes
        var ports = item.scope.workflow.currentSelection.getPorts();
        var len =ports.data.length;

        //Get all the connection of the shape
        var conn = new Array();
        for(var i=0; i<=len; i++){
            if(typeof ports.data[i] === 'object')
                conn[i] = ports.data[i].getConnections();
        }

        //Initializing Arrays and variables
        var connLength = conn.length;
        var sourceNode = new Array();
        var targetNode = new Array();
        var countConn  = 0;
        var sourcePortName = new Array();
        var targetPortName = new Array();
        var sourcePortId   = new Array();
        var targetPortId   = new Array();

        //Get the pre-selected id into new variable to compare in future code
        var shapeId = this.workflow.currentSelection.id;

        //Get the current pre-selected figure object in the new object, because not accessible after adding new shapes
        var oldWorkflow = this.workflow.currentSelection;

        //Get the source and Target object of all the connections in an array
        for(i = 0; i< connLength ; i++)
            {
                for(var j = 0; j < conn[i].data.length ; j++)
                   {
                    if(typeof conn[i].data[j] != 'undefined') {
                            sourceNode[countConn] = conn[i].data[j].sourcePort.parentNode;
                            targetNode[countConn] = conn[i].data[j].targetPort.parentNode;
                            sourcePortName[countConn] = conn[i].data[j].sourcePort.properties.name;
                            targetPortName[countConn] = conn[i].data[j].targetPort.properties.name;
                            sourcePortId[countConn] = conn[i].data[j].sourcePort.parentNode.id;
                            targetPortId[countConn] = conn[i].data[j].targetPort.parentNode.id;
                            countConn++;
                        }
                    }
            }

        //Add new selected Figure
        var x =item.scope.workflow.currentSelection.getX();  //Get x co-ordinate from figure
        var y =item.scope.workflow.currentSelection.getY();  //Get y co-ordinate from figure
        
        if(item.type == 'bpmnEventBoundaryTimerInter') {
            workflow.currentSelection.boundaryEvent = true;
            workflow.taskName = oldWorkflow.taskName;
            var newShape = workflow.currentSelection;
            newShape.setDimension(newShape.getWidth(),newShape.getHeight());
        }
        else if(item.type == 'bpmnSubProcess') {
                workflow.subProcessName = 'Sub Process';
                newShape = eval("new "+item.type+"(this.workflow)");
        }
        else
            newShape = eval("new "+item.type+"(this.workflow)");

        if(item.type != 'bpmnEventBoundaryTimerInter') {
            this.workflow.addFigure(newShape,x,y); //Add New Selected Shape First
            //Delete Old Shape
            item.scope.workflow.getCommandStack().execute(new CommandDelete(oldWorkflow));
            ToolGeneric.prototype.execute.call(item.scope);
           //to create all the new connections again
           var connObj;
           for(i=0 ; i < countConn ; i++){
                if(sourcePortId[i] == shapeId)  //If shapeId is equal to sourceId the , replace the oldShape object by new shape Object
                  sourceNode[i] = newShape;
                else
                  targetNode[i] = newShape;
                  connObj = new DecoratedConnection();
                  connObj.setTarget(eval('targetNode[i].getPort(targetPortName[i])'));
                  connObj.setSource(eval('sourceNode[i].getPort(sourcePortName[i])'));
                  newShape.workflow.addFigure(connObj);
              }
        }
        //Saving Asynchronously deleted shape and new created shape into DB
        if(item.type.match(/Boundary/)) {
            newShape.actiontype = 'updateTask';
            workflow.saveShape(newShape);
        }
        if(newShape.type.match(/Event/) && !item.type.match(/Boundary/)) {
           newShape.mode = 'ddEvent';
           newShape.actiontype = 'addEvent';
           //Set the Old Id to the Newly created Event
           newShape.html.id = oldWorkflow.id;
           newShape.id = oldWorkflow.id;
           newShape.workflow.saveShape(newShape);
         }
         if(newShape.type.match(/Gateway/)) {
           newShape.mode = 'ddGateway';
           newShape.actiontype = 'addGateway';
           //Set the Old Id to the Newly created Gateway
           newShape.html.id = oldWorkflow.id;
           newShape.id = oldWorkflow.id;
           newShape.workflow.saveShape(newShape);
         }
         //Swapping from Task to subprocess and vice -versa
         if((newShape.type == 'bpmnSubProcess' || newShape.type == 'bpmnTask') && !item.type.match(/Boundary/)) {
           newShape.actiontype = 'addSubProcess';
           if(newShape.type == 'bpmnTask')
             newShape.actiontype = 'addTask';
           newShape.workflow.saveShape(newShape);
         }
         if((this.type == 'bpmnTask' || this.type == 'bpmnSubProcess') && !item.type.match(/Boundary/)) {
           this.actiontype = 'deleteTask';
           this.noAlert    = true;
           if(this.type == 'bpmnSubProcess')
             this.actiontype = 'deleteSubProcess';
           newShape.workflow.deleteSilently(this);
         }
       }
    }
}

/**
 * Toggling Between Task and SubProcess
 * @Param  Shape Object
 * @Author Safan Maredia
 */
MyWorkflow.prototype.swapTaskSubprocess=function(itemObj)
{
         if(itemObj.type == 'bpmnSubProcess')
            {
                workflow.subProcessName = 'Sub Process';
                var newShape = eval("new "+itemObj.type+"(this.workflow)");
            }
         else
            newShape = eval("new "+itemObj.type+"(this.workflow)");

         //Swapping from Task to subprocess and vice -versa
         if((newShape.type == 'bpmnSubProcess' || newShape.type == 'bpmnTask') && !itemObj.type.match(/Boundary/)) {
             newShape.actiontype = 'addSubProcess';
             if(newShape.type == 'bpmnTask')
                 newShape.actiontype = 'addTask';
              newShape.workflow.saveShape(newShape);
         }
         if((this.type == 'bpmnTask' || this.type == 'bpmnSubProcess') && !itemObj.type.match(/Boundary/)) {
             this.actiontype = 'deleteTask';
             this.noAlert    = true;
             if(this.type == 'bpmnSubProcess')
                 this.actiontype = 'deleteSubProcess';
             newShape.workflow.deleteShape(this);
         }
}

/**
 * Validating Connection on Input/Output onDrop Event
 * @Param  port
 * @Param  portType
 * @Param  portTypeName
 * @Author Girish Joshi
 */
MyWorkflow.prototype.checkConnectionsExist=function(port,portType,portTypeName)
{
       //Get all the ports of the shapes
        var ports = port.workflow.currentSelection.getPorts();
        var len =ports.data.length;

        //Get all the connection of the shape
        var conn = new Array();
        for(var i=0; i<=len; i++){
            if(typeof ports.data[i] === 'object')
                if(ports.data[i].type == portTypeName)
                    conn[i] = ports.data[i].getConnections();
        }
        //Initializing Arrays and variables
        var countConn = 0;
        var portParentId= new Array();
        var portName = new Array();

        //Get ALL the connections for the specified PORT
        for(i = 0; i< conn.length ; i++)
            {
                if(typeof conn[i] != 'undefined')
                for(var j = 0; j < conn[i].data.length ; j++)
                   {
                     if(typeof conn[i].data[j] != 'undefined') {
                            portParentId[countConn] = eval('conn[i].data[j].'+portType+'.parentNode.id');
                            portName[countConn] = eval('conn[i].data[j].'+portType+'.properties.name');
                            countConn++;
                        }
                    }
            }
        var conx = 0;
        var parentid;
        for(i=0 ; i < countConn ; i++)
            {
                if(portParentId[i] == port.parentNode.id)
                        conx++;
            }
            return conx;

}
/**
 * ExtJs Menu on right Click of Start Event
 * @Param  Shape Object
 * @Author Girish Joshi
 */
MyWorkflow.prototype.AddEventStartContextMenu=function(oShape)
{
    this.canvasEvent = Ext.get(oShape.id);
    this.contextEventmenu = new Ext.menu.Menu({
        items: [{
            text: 'Event Type',
            menu: {        // <-- submenu by nested config object
                    items: [
                        // stick any markup in a menu
                        {
                            text: 'Empty',
                            type:'bpmnEventEmptyStart',
                            scope:oShape,
                            handler: MyWorkflow.prototype.toggleShapes
                        },
                        {
                            text: 'Message',
                            type:'bpmnEventMessageStart',
                            scope:oShape,
                            handler: MyWorkflow.prototype.toggleShapes
                        }, {
                            text: 'Timer',
                            type:'bpmnEventTimerStart',
                            scope:oShape,
                            handler: MyWorkflow.prototype.toggleShapes
                        }/*, {
                            text: 'Conditional',
                            type:'bpmnEventRuleStart',
                            scope:oShape,
                            handler: MyWorkflow.prototype.toggleShapes
                        }/*, {
                            text: 'Signal',
                            type:'bpmnEventSignalStart',
                            scope:oShape,
                            handler: MyWorkflow.prototype.toggleShapes
                        }, {
                            text: 'Multiple',
                            type:'bpmnEventMulStart',
                            scope:oShape,
                            handler: MyWorkflow.prototype.toggleShapes
                        }*/
                    ]
                },
            scope: this
        },{
            text: 'Properties',
            scope: this,
            handler: MyWorkflow.prototype.editEventProperties
        }]
    });

this.canvasEvent.on('contextmenu', function(e) {
    e.stopEvent();
    this.contextEventmenu.showAt(e.getXY());
}, this);
}
/**
 * ExtJs Menu on right Click of Intermediate Event
 * @Param  Shape Object
 * @Author Girish Joshi
 */
MyWorkflow.prototype.AddEventInterContextMenu=function(_4093)
{
    this.canvasEvent = Ext.get(_4093.id);
    this.contextEventmenu = new Ext.menu.Menu({
        items: [{
            text: 'Event Type',
            menu: {        // <-- submenu by nested config object
                    items: [
                        // stick any markup in a menu
                        /*{
                            text: 'Empty',
                            type:'bpmnEventEmptyInter',
                            scope:_4093,
                            handler: MyWorkflow.prototype.toggleShapes
                        },*/
                        {
                            text: 'Message : Throw',
                            type:'bpmnEventMessageSendInter',
                            scope:_4093,
                            handler: MyWorkflow.prototype.toggleShapes
                        },
                        {
                            text: 'Timer',
                            type:'bpmnEventTimerInter',
                            scope:_4093,
                            handler: MyWorkflow.prototype.toggleShapes
                        }
                        /*
                        {
                            text: 'Intermediate Boundary Timer',
                            type:'bpmnEventBoundaryInter',
                            scope:_4093,
                            handler: MyWorkflow.prototype.toggleShapes
                        },{
                            text: 'Message :  Catch',
                            type:'bpmnEventMessageRecInter',
                            scope:_4093,
                            handler: MyWorkflow.prototype.toggleShapes
                        }, {
                            text: 'Compensate',
                            type:'bpmnEventCompInter',
                            scope:_4093,
                            handler: MyWorkflow.prototype.toggleShapes
                        }, {
                            text: 'Conditional',
                            type:'bpmnEventRuleInter',
                            scope:_4093,
                            handler: MyWorkflow.prototype.toggleShapes
                        }, {
                            text: 'Link',
                            type:'bpmnEventLinkInter',
                            scope:_4093,
                            handler: MyWorkflow.prototype.toggleShapes
                        }, {
                            text: 'Signal',
                            type:'bpmnEventInterSignal',
                            scope:_4093,
                            handler: MyWorkflow.prototype.toggleShapes
                        }, {
                            text: 'Multiple',
                            type:'bpmnEventMultipleInter',
                            scope:_4093,
                            handler: MyWorkflow.prototype.toggleShapes
                        }*/
                    ]
                },
            scope: this
        },{
            text: 'Properties',
            handler: MyWorkflow.prototype.editEventProperties,
            scope: this
        }]
    });

this.canvasEvent.on('contextmenu', function(e) {
    e.stopEvent();
    this.contextEventmenu.showAt(e.getXY());
}, this);
}
/**
 * ExtJs Menu on right Click of End Event
 * @Param  Shape Object
 * @Author Girish Joshi
 */
MyWorkflow.prototype.AddEventEndContextMenu=function(_4093)
{
    this.canvasEvent = Ext.get(_4093.id);
    this.contextEventmenu = new Ext.menu.Menu({
        items: [{
            text: 'Event Type',
            menu: {        // <-- submenu by nested config object
                    items: [
                        // stick any markup in a menu
                        {
                            text: 'Empty',
                            type:'bpmnEventEmptyEnd',
                            scope:_4093,
                            handler: MyWorkflow.prototype.toggleShapes
                        },
                        {
                            text: 'Message',
                            type:'bpmnEventMessageEnd',
                            scope:_4093,
                            handler: MyWorkflow.prototype.toggleShapes
                        }/*,
                        {
                            text: 'Error',
                            type:'bpmnEventErrorEnd',
                            scope:_4093,
                            handler: MyWorkflow.prototype.toggleShapes
                        }, {
                            text: 'Cancel',
                            type:'bpmnEventCancelEnd',
                            scope:_4093,
                            handler: MyWorkflow.prototype.toggleShapes
                        }, {
                            text: 'Compensate',
                            type:'bpmnEventCompEnd',
                            scope:_4093,
                            handler: MyWorkflow.prototype.toggleShapes
                        }, {
                            text: 'Signal',
                            type:'bpmnEventEndSignal',
                            scope:_4093,
                            handler: MyWorkflow.prototype.toggleShapes
                        }, {
                            text: 'Multiple',
                            type:'bpmnEventMultipleEnd',
                            scope:_4093,
                            handler: MyWorkflow.prototype.toggleShapes
                        }, {
                            text: 'Terminate',
                            type:'bpmnEventTerminate',
                            scope:_4093,
                            handler: MyWorkflow.prototype.toggleShapes
                        }*/
                    ]
                },
            scope: this
        },{
            text: 'Properties',
            handler: MyWorkflow.prototype.editEventProperties,
            scope: this
        }]
    });

this.canvasEvent.on('contextmenu', function(e) {
    e.stopEvent();
    this.contextEventmenu.showAt(e.getXY());
}, this);
}

/**
 * Hiding Ports according to the Shape Selected
 * @Param  Shape Object
 * @Author Girish Joshi
 */
MyWorkflow.prototype.disablePorts=function(oShape)
{
  if(oShape.type != ''){
    var ports ='';
    if(oShape.type.match(/Gateway/)) {
      ports = ['output1','input1','output2','input2', 'output3' ];
    }
    else if(oShape.type.match(/Task/) || oShape.type.match(/Gateway/) || oShape.type.match(/Inter/) || oShape.type.match(/SubProcess/)) {
      ports = ['output1','input1','output2','input2' ];
    }
    else if(oShape.type.match(/End/)) {
      ports = ['input1','input2'];
    }
    else if(oShape.type.match(/Start/)) {
      ports = ['output1','output2'];
    }
    else if(oShape.type.match(/Annotation/)) {
      ports = ['input1'];
    }
    for(var i=0; i< ports.length ; i++) {
      eval('oShape.'+ports[i]+'.setZOrder(-1)');
      eval('oShape.'+ports[i]+'.setBackgroundColor(new Color(255, 255, 255))');
      eval('oShape.'+ports[i]+'.setColor(new Color(255, 255, 255))');
    }
  }
}
/**
 * Show Ports according to the Shape Selected
 * @Param  Shape Object
 * @Param  aPort Array
 * @Author Girish Joshi
 */
MyWorkflow.prototype.enablePorts=function(oShape,aPort)
{
  /*Setting Background ,border and Z-order of the flow menu back to original when clicked
   *on the shape
  **/
  for(var i=0; i< aPort.length ; i++)
  {
    if(aPort[i].match(/input/)) {
      eval('oShape.workflow.currentSelection.'+aPort[i]+'.setBackgroundColor(new Color(245, 115, 115))');
      eval('oShape.workflow.currentSelection.'+aPort[i]+'.setZOrder(49000)');
    }
    else {
      eval('oShape.workflow.currentSelection.'+aPort[i]+'.setBackgroundColor(new Color(115, 115, 245))');
      eval('oShape.workflow.currentSelection.'+aPort[i]+'.setZOrder(50000)');
    }
    eval('oShape.workflow.currentSelection.'+aPort[i]+'.setColor(new Color(90, 150, 90))');
  }
}

/**
 * Hide Flow menu according to the Shape Selected
 * @Param  Shape Object
 * @Param  aPort Array
 * @Author Girish Joshi
 */
MyWorkflow.prototype.disableFlowMenu =function(oShape,aPort)
{
  /*Setting Background ,border and Z-order of the flow menu back to original when clicked
   *on the shape
   */
  for(var i=0; i< aPort.length ; i++)
  {
    if(aPort[i].match(/input/))
        eval('oShape.workflow.currentSelection.'+aPort[i]+'.setBackgroundColor(new Color(245, 115, 115))');
    else
        eval('oShape.workflow.currentSelection.'+aPort[i]+'.setBackgroundColor(new Color(115, 115, 245))');
        eval('oShape.workflow.currentSelection.'+aPort[i]+'.setColor(new Color(90, 150, 90))');
        eval('oShape.workflow.currentSelection.'+aPort[i]+'.setZOrder(50000)');
  }
}

/**
 * This function is called on Right Click of All Shapes
 * and menu is shown according to the shape selected
 * @Param  Shape Object
 * @Author Girish Joshi
 */
MyWorkflow.prototype.handleContextMenu=function(oShape)
{
    workflow.hideResizeHandles();
    //Enable the contextClicked Flag
    workflow.contextClicked = true;

    //Set the current Selection to the selected Figure
    workflow.setCurrentSelection(oShape);

    //Disable Resize of all the figures
    //oShape.workflow.hideResizeHandles();
//    oShape.setSelectable(false);
//    oShape.setResizeable(false);
    //Handle the Right click menu
    var pmosExtObj = new pmosExt();
    //Load all the process Data
    pmosExtObj.loadProcess(oShape);
    
    //Load Dynaform List
   // pmosExtObj.loadDynaforms(oShape);

     if(oShape.type != ''){
        if(oShape.type.match(/Task/)) {
            oShape.workflow.taskid = new Array();
            oShape.workflow.taskid.value = oShape.id;
             pmosExtObj.loadTask(oShape);
            oShape.workflow.AddTaskContextMenu(oShape);
        }
        else if(oShape.type.match(/Start/)) {
            oShape.workflow.taskUid = workflow.getStartEventConn(oShape,'targetPort','OutputPort');
            pmosExtObj.loadDynaforms(oShape);
            oShape.workflow.AddEventStartContextMenu(oShape);
        }
        else if(oShape.type.match(/Inter/)) {
            oShape.workflow.taskUidFrom = workflow.getStartEventConn(oShape,'sourcePort','InputPort');
            //oShape.workflow.taskid =  oShape.workflow.taskUid[0];
            oShape.workflow.taskUidTo = workflow.getStartEventConn(oShape,'targetPort','OutputPort');
            oShape.workflow.taskid =  oShape.workflow.taskUidFrom[0];
            pmosExtObj.loadTask(oShape);
            pmosExtObj.getTriggerList(oShape);
            oShape.workflow.AddEventInterContextMenu(oShape);
        }
        else if(oShape.type.match(/End/)) {
            oShape.workflow.taskUid = workflow.getStartEventConn(oShape,'sourcePort','InputPort');
            oShape.workflow.AddEventEndContextMenu(oShape);
        }
        else if(oShape.type.match(/Gateway/)) {
            oShape.workflow.AddGatewayContextMenu(oShape);
        }
        else if(oShape.type.match(/SubProcess/)) {
            oShape.workflow.AddSubProcessContextMenu(oShape);
        }
    }
    //this.workflow.AddEventStartContextMenu(oShape);
        

}

/**
 * This function is called in Save Process
 * and menu is shown according to the shape selected
 * @Param  Shape Object
 * @Author Javed Aman
 */
MyWorkflow.prototype.getCommonConnections = function(oShape)
{
    var routes = new Array();
    var counter = 0
    for(var p=0; p < oShape.workflow.commonPorts.data.length; p++)
    {
        if(typeof oShape.workflow.commonPorts.data[p] === "object" && oShape.workflow.commonPorts.data[p] != null)
            {
                counter++;
            }
    }
    for(var j=0; j< counter; j++)
    {
         //var temp1 = eval("this.workflow.commonPorts.data["+i+"].parentNode.output"+count+".getConnections()");
         var tester = oShape.workflow.commonPorts.data;
         var temp1 = eval("oShape.workflow.commonPorts.data["+j+"].getConnections()");
            if(temp1.data[0]) {
              if(routes[j]) {
                if(routes[j][1] != temp1.data[0].sourcePort.parentNode.id) {
                  routes[j] = new Array(3);
                  routes[j][0] = temp1.data[0].id;
                  routes[j][1] = temp1.data[0].sourcePort.parentNode.id;
                  routes[j][2] = temp1.data[0].targetPort.parentNode.id;
                  routes[j][3] = temp1.data[0].targetPort.properties.name;
                  routes[j][4] = temp1.data[0].sourcePort.properties.name;
                }
              }
              else {
                     routes[j] = new Array(3);
                     routes[j][0] = temp1.data[0].id;
                     routes[j][1] = temp1.data[0].sourcePort.parentNode.id;
                     routes[j][2] = temp1.data[0].targetPort.parentNode.id;
                     routes[j][3] = temp1.data[0].targetPort.properties.name;
                     routes[j][4] = temp1.data[0].sourcePort.properties.name;
              }
            }
    }
    var j = 0;
    var serial = new Array();
    for(key in routes)
    {
        if(typeof routes[key] === 'object') {
          serial[j] = routes[key];
          j++;
        }
    }
    var routes = serial.getUniqueValues();
    for(var i=0;i< routes.length ; i++)
     {
        routes[i] = routes[i].split(',');
     }

     return routes;
}

Array.prototype.getUniqueValues = function () {
var hash = new Object();
for (j = 0; j < this.length; j++) {hash[this[j]] = true}
var array = new Array();
for (value in hash) {array.push(value)};
return array;
}
/**
 * Get Process UID
 * @Param  Shape Object
 * @Author Safan maredia
 */
MyWorkflow.prototype.getUrlVars = function()
{
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');

    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    var pro_uid = vars["PRO_UID"];
    return pro_uid;
}


MyWorkflow.prototype.savePosition= function(oShape)
{
    var shapeId = oShape.id;
    var actiontype = oShape.actiontype;
    var xpos = oShape.x;
    var ypos = oShape.y;
    var pos = '{"x":'+xpos+',"y":'+ypos+'}';

    var width = oShape.width;
    var height = oShape.height;
    var cordinates = '{"x":'+width+',"y":'+height+'}';
    var urlparams = '';
    switch(actiontype)
    {
        case 'saveTaskPosition':
            urlparams = '?action='+actiontype+'&data={"uid":"'+ shapeId +'","position":'+pos+'}';
            break;
        case 'saveEventPosition':
            urlparams = '?action='+actiontype+'&data={"uid":"'+ shapeId +'","position":'+pos+'}';
        break;
        case 'saveTextPosition':
            urlparams = '?action='+actiontype+'&data={"uid":"'+ shapeId +'","position":'+pos+'}';
            break;
        case 'saveGatewayPosition':
            urlparams = '?action='+actiontype+'&data={"uid":"'+ shapeId +'","position":'+pos+'}';
            break;
        case 'saveTaskCordinates':
            urlparams = '?action='+actiontype+'&data={"uid":"'+ shapeId +'","position":'+cordinates+'}';
            break;
        case 'saveAnnotationCordinates':
            urlparams = '?action='+actiontype+'&data={"uid":"'+ shapeId +'","position":'+cordinates+'}';
        break;
    }
    if(urlparams != ''){
      Ext.Ajax.request({
        url: "bpmn/processes_Ajax.php"+ urlparams,
        success: function(response) {
          //Ext.Msg.alert (response.responseText);
        },
        failure: function(){
         //Ext.Msg.alert ('Failure');
        }
      });
    }
}
/**
 * Saving Shape Asychronously
 * @Param  oNewShape Object
 * @Author Safan maredia
 */
MyWorkflow.prototype.saveShape= function(oNewShape)
{
    //Initializing variables
    var shapeId    = oNewShape.id;
    var shapetype  = oNewShape.type;
    var actiontype = oNewShape.actiontype;
    var xpos       = oNewShape.x;
    var ypos       = oNewShape.y;
    var pos        = '{"x":'+xpos+',"y":'+ypos+'}';
    var width      = oNewShape.width;
    var height     = oNewShape.height;
    var cordinates = '{"x":'+width+',"y":'+height+'}';

    if(shapetype == 'bpmnTask'){
        var newlabel = oNewShape.taskName;
    }
    if(shapetype == 'bpmnAnnotation'){
        newlabel = oNewShape.annotationName;
    }
    
    //var urlparams = "action=addTask&data={"uid":"4708462724ca1d281210739068208635","position":{"x":707,"y":247}}";
    var urlparams = '';
    switch(actiontype)
    {
        case 'addTask':
            urlparams = '?action='+actiontype+'&data={"uid":"'+ pro_uid +'","position":'+pos+',"cordinate":'+cordinates+'}';
            break;
        case 'updateTask':
            urlparams = '?action='+actiontype+'&data={"uid":"'+ shapeId +'","boundary":"TIMER"}';
            break;
        case 'updateTaskName':
            urlparams = '?action='+actiontype+'&data={"uid":"'+ shapeId +'","label":"'+newlabel+'"}';
            break;
        case 'addSubProcess':
            urlparams = '?action='+actiontype+'&data={"uid":"'+ pro_uid +'","position":'+pos+'}';
            break;
        case 'addText':
            var next_uid = '';
            var taskUidFrom = workflow.getStartEventConn(oNewShape,'sourcePort','OutputPort');
            if(taskUidFrom.length > 0)
                next_uid = taskUidFrom[0].value;
            urlparams = '?action='+actiontype+'&data={"uid":"'+ pro_uid +'","label":"'+newlabel+'","task_uid":"'+ next_uid +'","position":'+pos+'}';
            break;
        case 'updateText':
            next_uid = '';
            if(workflow.currentSelection.type == 'bpmnTask')
                taskUidFrom = workflow.getStartEventConn(oNewShape,'sourcePort','OutputPort');
            else
                taskUidFrom = workflow.getStartEventConn(oNewShape,'sourcePort','InputPort');
            if(taskUidFrom.length > 0)
                next_uid = taskUidFrom[0].value;
            
            urlparams = '?action='+actiontype+'&data={"uid":"'+ shapeId +'","label":"'+newlabel+'","next_uid":"'+ next_uid +'"}';
            break;
        case 'saveStartEvent':
            //If we change Event to start from Message/Timer then Delete the record from Events Table
            this.deleteEvent(oNewShape);
            var tas_start = 'TRUE';
            var tas_uid = oNewShape.task_uid;
            urlparams = '?action='+actiontype+'&data={"tas_uid":"'+tas_uid+'","tas_start":"'+tas_start+'"}';
            break;
        case 'addEvent':
            urlparams = '?action='+actiontype+'&data={"uid":"'+ pro_uid +'","evn_type":"'+shapetype+'","position":'+pos+',"evn_uid":"'+shapeId+'"}';
            break;
        case 'updateEvent':
            urlparams = '?action='+actiontype+'&data={"evn_uid":"'+shapeId +'","evn_type":"'+shapetype+'"}';
            break;
       case 'addGateway':
            urlparams = '?action='+actiontype+'&data={"pro_uid":"'+ pro_uid +'","gat_uid":"'+ shapeId +'","gat_type":"'+ shapetype +'","position":'+ pos +'}';
            break;
    }
    //var urlparams = '?action='+actiontype+'&data={"uid":"'+ pro_uid +'","position":'+pos+'}';

        if(urlparams != ''){
        Ext.Ajax.request({
            url: "bpmn/processes_Ajax.php"+ urlparams,
            success: function(response) {
                //Ext.Msg.alert (response.responseText);
                  if(response.responseText != 1 && response.responseText != ""){
                    this.workflow.newTaskInfo = Ext.util.JSON.decode(response.responseText);
                    oNewShape.html.id = this.workflow.newTaskInfo.uid;
                    oNewShape.id      = this.workflow.newTaskInfo.uid;
                    if(oNewShape.type == 'bpmnTask' && oNewShape.boundaryEvent != true){
                      oNewShape.taskName = this.workflow.newTaskInfo.label;
                      workflow.redrawTaskText(oNewShape);
                      //After Figure is added, Update Start Event connected to Task
                      if(typeof this.workflow.preSelectedObj != 'undefined' ){
                        var preSelectedFigure = this.workflow.preSelectedObj;
                        if(preSelectedFigure.type.match(/Start/) && preSelectedFigure.type.match(/Event/))
                          this.workflow.saveEvents(preSelectedFigure,oNewShape.id);
                        else if(preSelectedFigure.type.match(/Task/))
                          this.workflow.saveRoute(preSelectedFigure,oNewShape);
                        else if (preSelectedFigure.type.match(/Gateway/))
                        //preSelectedFigure.rou_type = 'SEQUENTIAL';
                          this.workflow.saveRoute(preSelectedFigure,oNewShape);
                        else if (preSelectedFigure.type.match(/Inter/)) {
                          //preSelectedFigure.rou_type = 'SEQUENTIAL';
                          this.workflow.saveEvents(preSelectedFigure,oNewShape);
                        }
                      }
                      
                      /**
                       * erik: Setting Drop targets from users & groups grids to assignment to tasks
                       * for new tasks created recently
                       */
                      //var dropEls = Ext.get('paintarea').query('.x-task');
                      //for(var i = 0; i < dropEls.length; i++)
                        //new Ext.dd.DropTarget(dropEls[i], {ddGroup:'task-assignment', notifyDrop  : Ext.getCmp('usersPanel')._onDrop});
                      
                    }
                    else if(oNewShape.type == 'bpmnSubProcess'){
                      oNewShape.subProcessName = this.workflow.newTaskInfo.label;
                    }
                    /*else if(oNewShape.type.match(/Inter/) && oNewShape.type.match(/Start/)) {
                      workflow.saveEvents(oNewShape);
                    }
                    else if(oNewShape.type.match(/Start/) && oNewShape.type.match(/Event/)) {
                      workflow.saveEvents(oNewShape);
                    }*/
                    else if(oNewShape.type.match(/End/) && oNewShape.type.match(/Event/)) {
                      if(workflow.currentSelection != null && workflow.currentSelection != '') //will check for standalone event
                        workflow.saveRoute(workflow.currentSelection,oNewShape);
                    }
                    else if(oNewShape.type.match(/Gateway/)) {
                      workflow.saveGateways(oNewShape);
                    }
                    else if(oNewShape.type.match(/Inter/) && oNewShape.type.match(/Event/)) {
                      preSelectedFigure = this.workflow.preSelectedFigure;
                      workflow.saveEvents(oNewShape,preSelectedFigure.id);
                    }
               }
            },
            failure: function(){
                //Ext.Msg.alert ('Failure');
            }
            });
        }
}

MyWorkflow.prototype.saveTask= function(actiontype,xpos,ypos)
{
    if(actiontype != '') {
            var pro_uid = this.getUrlVars();
            var actiontype = actiontype;
            var pos = '{"x":'+xpos+',"y":'+ypos+'}';
            switch(actiontype) {
                case 'addTask':
                    urlparams = '?action='+actiontype+'&data={"uid":"'+ pro_uid +'","position":'+pos+'}';
                    break;
            }
             Ext.Ajax.request({
                    url: "bpmn/processes_Ajax.php"+ urlparams,
                    success: function(response) {
                        //Ext.Msg.alert (response.responseText);
                          if(response.responseText != 1 && response.responseText != "") {
                            workflow.newTaskInfo = Ext.util.JSON.decode(response.responseText);
                            workflow.taskName = this.workflow.newTaskInfo.label;
                            workflow.task  = eval("new bpmnTask(workflow) ");
                            workflow.addFigure(workflow.task, xpos, ypos);
                            workflow.task.html.id = workflow.newTaskInfo.uid;
                            workflow.task.id = workflow.newTaskInfo.uid;
                          }
                       }
                    })
        }
}
//Deleting shapes silently on swapping task to sub process and vice-versa
MyWorkflow.prototype.deleteSilently= function(oShape)
{
    //Initializing variables
    var pro_uid = this.getUrlVars();
    var shapeId = oShape.id;
    var actiontype = oShape.actiontype;
    //var shapeName = '';

     switch(actiontype)
    {
       case 'deleteTask':
            var urlparams = '?action='+actiontype+'&data={"pro_uid":"'+ pro_uid +'","tas_uid":"'+shapeId+'"}';
            this.urlparameter = urlparams;
            //shapeName = 'Task :'+ oShape.taskName;
            break;
       case 'deleteSubProcess':
           urlparams = '?action='+actiontype+'&data={"pro_uid":"'+ pro_uid +'","tas_uid":"'+shapeId+'"}';
           this.urlparameter = urlparams;
           break;
    }

    Ext.Ajax.request({
                        url: "bpmn/processes_Ajax.php"+ urlparams,
                        success: function(response) {
                                //Ext.Msg.alert (response.responseText);
                        },
                        failure: function(){
                                Ext.Msg.alert ('Failure');
                        }
                        });
   //workflow.getCommandStack().execute(new CommandDelete(workflow.getCurrentSelection()));
}
/**
 * Deleting Shape Asychronously
 * @Param  oShape Object
 * @Author Safan maredia
 */
MyWorkflow.prototype.deleteShape= function(oShape)
{
    var shapeId = oShape.id;
    var actiontype = oShape.actiontype;
    var shapeName = '';
    switch(actiontype)
    {
       case 'deleteTask':
            var urlparams = '?action='+actiontype+'&data={"pro_uid":"'+ pro_uid +'","tas_uid":"'+shapeId+'"}';
            this.urlparameter = urlparams;
            shapeName = 'Task :'+ oShape.taskName;
            break;
       case 'deleteSubProcess':
           urlparams = '?action='+actiontype+'&data={"pro_uid":"'+ pro_uid +'","tas_uid":"'+shapeId+'"}';
           this.urlparameter = urlparams;
           shapeName = oShape.subProcessName;
           break;
       case 'deleteText':
           urlparams = '?action='+actiontype+'&data={"uid":"'+shapeId+'"}';
           this.urlparameter = urlparams;
           shapeName = 'Annotation';
           break;
       case 'deleteStartEvent':
           var task_detail = this.getStartEventConn(this.currentSelection,'targetPort','OutputPort');
           var evn_uid = this.currentSelection.id;
           if(task_detail.length > 0){
               var tas_uid = task_detail[0].value;
               var tas_start = 'FALSE';
               this.urlparameter = '?action=deleteStartEvent&data={"tas_uid":"'+tas_uid+'","tas_start":"'+tas_start+'","evn_uid":"'+evn_uid+'"}';
           }
           else
               this.urlparameter = '?action=deleteEvent&data={"uid":"'+evn_uid+'"}';
           shapeName = 'Start Event';
           break;
       case 'deleteEndEvent':
            shapeName = 'End Event';
            var evn_uid = this.currentSelection.id;
            this.urlparameter = '?action=deleteEvent&data={"uid":"'+evn_uid+'"}';
            break;
       case 'deleteInterEvent':
            shapeName = 'Intermediate Event';
            var evn_uid = this.currentSelection.id;
            this.urlparameter = '?action=deleteEvent&data={"uid":"'+evn_uid+'"}';
            break;
       case 'deleteGateway':
           shapeName = 'Gateway';
           urlparams = '?action='+actiontype+'&data={"pro_uid":"'+ pro_uid +'","gat_uid":"'+shapeId+'"}';
           this.urlparameter = urlparams;
           break;
    }
if(typeof oShape.noAlert == 'undefined' || oShape.noAlert == null){
  Ext.MessageBox.confirm('Confirm', 'Are you sure you want to delete the '+ shapeName,this.showAjaxDialog);
}
else{
  Ext.MessageBox.confirm('Confirm', 'Are you sure you want to delete the '+ shapeName,this.showDeleteDialog);
}
}

MyWorkflow.prototype.showAjaxDialog = function(btn){
        //this.workflow.confirm = btn;
        if(typeof workflow.urlparameter != 'undefined'){
          var url = workflow.urlparameter;
          if(btn == 'yes'){
           var currentObj = workflow.currentSelection;
           
           //Check for End Event and delete from route table
           if(workflow.currentSelection.type.match(/End/) && workflow.currentSelection.type.match(/Event/)){
             var ports = currentObj.getPorts();
             var len =ports.data.length;
             var conn = new Array();
             for(var i=0; i<=len; i++){
                if(typeof ports.data[i] === 'object')
                   conn[i] = ports.data[i].getConnections();
             }
             for(i = 0; i< conn.length ; i++)
             {
                if(typeof conn[i] != 'undefined')
                for(var j = 0; j < conn[i].data.length ; j++)
                {
                  if(typeof conn[i].data[j] != 'undefined'){
                     if(conn[i].data[j].targetPort.parentNode.id == currentObj.id){
                        route = conn[i].data[j];
                     break;
                   }
                  }
                }
              }
             if(typeof route != 'undefined'){
               workflow.deleteRoute(route,1);
             }
           }
           Ext.Ajax.request({
              url: "bpmn/processes_Ajax.php"+ url,
                success: function(response) {
                    workflow.getCommandStack().execute(new CommandDelete(currentObj));
              },
              failure: function(){
                Ext.Msg.alert ('Failure');
              }
            });
          
         }
      }
    };

MyWorkflow.prototype.showDeleteDialog = function(btn){
          if(btn == 'yes'){
           workflow.getCommandStack().execute(new CommandDelete(workflow.getCurrentSelection()));
         }
    };


/**
 * ExtJs Menu on right click on Event Shape
 * @Param  oShape Object
 * @Author Girish joshi
 */
MyWorkflow.prototype.editEventProperties = function(oShape)
{
    var currentSelection = oShape.scope.currentSelection;
    var pmosExtObj = new pmosExt();
        
    switch (currentSelection.type){
        case 'bpmnEventMessageStart':
            pmosExtObj.popWebEntry(currentSelection);
            break;
        case 'bpmnEventTimerStart':
            pmosExtObj.popCaseSchedular(currentSelection);
            break;
        case 'bpmnEventMessageSendInter':
            pmosExtObj.popTaskNotification(currentSelection);
            break;
        case 'bpmnEventTimerInter':
            pmosExtObj.popMultipleEvent(currentSelection);
            break;
        case 'bpmnEventMessageEnd':
            pmosExtObj.popMessageEvent(currentSelection);
            break;
    }
}

/**
 * Get the Source / Target of the shape
 * @Param  oShape     Object
 * @Param  sPort      string
 * @Param  sPortType  string
 * @return aStartTask array
 * @Author Girish joshi
 */
MyWorkflow.prototype.getStartEventConn = function(oShape,sPort,sPortType)
{
  var aStartTask= new Array();

  //Get all the ports of the shapes
  if( workflow.currentSelection != null && typeof workflow.currentSelection != 'undefined') {
    var ports = workflow.currentSelection.getPorts();
    //var ports = oShape.getPorts();
    var len   = ports.data.length;
    
    //Get all the connection of the shape
    var conn = new Array();
    for(var i=0; i<=len; i++){
      if(typeof ports.data[i] === 'object')
        if(ports.data[i].type == sPortType)
          conn[i] = ports.data[i].getConnections();
    }
    //Initializing Arrays and variables
    var countConn = 0;
    
    var type;
    //Get ALL the connections for the specified PORT
    for(i = 0; i< conn.length ; i++)
    {
      if(typeof conn[i] != 'undefined')
      for(var j = 0; j < conn[i].data.length ; j++)
      {
        if(typeof conn[i].data[j] != 'undefined')
        {
          type = eval('conn[i].data[j].'+sPort+'.parentNode.type')
          if(type == 'bpmnTask')
          {
            aStartTask[countConn] = new Array();
            aStartTask[countConn].value = eval('conn[i].data[j].'+sPort+'.parentNode.id');
            aStartTask[countConn].name  = eval('conn[i].data[j].'+sPort+'.parentNode.taskName');
            countConn++;
          }
        }
      }
    }
  }
  return aStartTask;
}

/**
 * save Gateway depending on the Shape Type
 * @Param  oGateway     Object
 * @Param  sTaskUID      string
 * @Author Safan Maredia
 */
MyWorkflow.prototype.saveGateways = function(oGateway){
    var task_uid       = '';
    var next_task_uid  = '';
    var next_task_type = '';
    var urlparams      = '';
    var xpos = oGateway.x;
    var ypos = oGateway.y;
    var pos = '{"x":'+xpos+',"y":'+ypos+'}';

    var ports = oGateway.getPorts();
        var len =ports.data.length;
        //Get all the connection of the shape
        var conn = new Array();
        var count1 = 0;
        var count2 = 0;
        for(var i=0; i<=len; i++){
            if(typeof ports.data[i] === 'object')
                conn[i] = ports.data[i].getConnections();
        }
        //Get ALL the connections for the specified PORT
        for(i = 0; i< conn.length ; i++){
          if(typeof conn[i] != 'undefined')
            for(var j = 0; j < conn[i].data.length ; j++){
              if(typeof conn[i].data[j] != 'undefined'){
                if(conn[i].data[j].sourcePort.parentNode.id != oGateway.id){
                  // task_uid[count1] = new Array();
                   task_uid = conn[i].data[j].sourcePort.parentNode.id;
                   count1++;
                }
                if(conn[i].data[j].targetPort.parentNode.id != oGateway.id){
                  // task_uid[count2] = new Array();
                  next_task_uid = conn[i].data[j].targetPort.parentNode.id;
                  next_task_type = conn[i].data[j].targetPort.parentNode.type;
                  //count2++;
                }
             }
          }
        }
    // var staskUid     = 	Ext.util.JSON.encode(task_uid);
    // var sNextTaskUid = 	Ext.util.JSON.encode(next_task_uid);
     urlparams = '?action=addGateway&data={"pro_uid":"'+ pro_uid +'","tas_from":"'+task_uid+'","tas_to":"'+next_task_uid+'","gat_type":"'+oGateway.type+'","gat_uid":"'+oGateway.id+'","gat_next_type":"'+next_task_type+'","position":'+pos+'}';
     if(urlparams != ''){
        Ext.Ajax.request({
                url: "bpmn/processes_Ajax.php"+ urlparams,
                success: function(response) {
                    if(response.responseText != '')
                      {
                         // workflow.currentSelection.id = response.responseText;
                        /*if(workflow.currentSelection.type.match(/Inter/) && workflow.currentSelection.type.match(/Event/)){
                           workflow.currentSelection.id = response.responseText;
                           var newObj = workflow.currentSelection;
                           var preObj = new Array();
                           preObj.type = 'bpmnTask';
                           preObj.id = task_uid[0];
                           newObj.evn_uid = workflow.currentSelection.id;
                           newObj.task_to = next_task_uid[0];
                           this.workflow.saveRoute(preObj,newObj);
                         }*/
                      }
                },
                failure: function(){
                    Ext.Msg.alert ('Failure');
                }
            });
    }
}

/**
 * save Event depending on the Shape Type
 * @Param  oShape     Object
 * @Param  sPort      string
 * @Param  sPortType  string
 * @Author Girish joshi
 */
MyWorkflow.prototype.saveEvents = function(oEvent,sTaskUID)
{
  var task_uid      = new Array();
  var next_task_uid = new Array();
  var urlparams     = '';

  if(typeof sTaskUID == 'undefined')  //Will be undefined for standalone events
    sTaskUID = '';
  if(oEvent.type.match(/Start/))
  {
    var tas_start = 'TRUE';
    urlparams = '?action=saveEvents&data={"tas_uid":"'+sTaskUID+'","tas_start":"'+tas_start+'","evn_type":"'+oEvent.type+'","evn_uid":"'+oEvent.id+'"}';
  }
  else if(oEvent.type.match(/Inter/))
  {
    var ports = oEvent.getPorts();
    var len =ports.data.length;

    //Get all the connection of the shape
    var conn = new Array();
    var count1 = 0;
    var count2 = 0;
    for(var i=0; i<=len; i++) {
      if(typeof ports.data[i] === 'object')
        conn[i] = ports.data[i].getConnections();
    }
    
    //Get ALL the connections for the specified PORT
    for(i = 0; i< conn.length ; i++){
      if(typeof conn[i] != 'undefined')
        for(var j = 0; j < conn[i].data.length ; j++) {
          if(typeof conn[i].data[j] != 'undefined'){
            if(conn[i].data[j].sourcePort.parentNode.type != oEvent.type){
              // task_uid[count1] = new Array();
               task_uid = conn[i].data[j].sourcePort.parentNode.id;
               count1++;
            }
            if(conn[i].data[j].targetPort.parentNode.type != oEvent.type){
              // task_uid[count2] = new Array();
              next_task_uid = conn[i].data[j].targetPort.parentNode.id;
              //count2++;
            }
          }
        }
    }
    // var staskUid     = 	Ext.util.JSON.encode(task_uid);
    // var sNextTaskUid = 	Ext.util.JSON.encode(next_task_uid);
    if(typeof task_uid == 'undefined')
      task_uid = '';
    if(typeof next_task_uid == 'undefined')
      next_task_uid = '';
    
    urlparams = '?action=saveEvents&data={"tas_from":"'+task_uid+'","tas_to":"'+next_task_uid+'","evn_type":"'+oEvent.type+'","evn_uid":"'+oEvent.id+'"}';
  }
  
  if(urlparams != '') {
    Ext.Ajax.request({
      url: "bpmn/processes_Ajax.php"+ urlparams,
      success: function(response) {
        if(response.responseText != '')
        {
          //Save Route
          //disabled by Fernando, because the workflow.currentSelection arrives null and throwing an error in javascript
//          if(workflow.currentSelection.type.match(/Inter/) && workflow.currentSelection.type.match(/Event/)){
//            workflow.currentSelection.id = response.responseText;
//            var newObj = workflow.currentSelection;
//            var preObj = new Array();
//            preObj.type = 'bpmnTask';
//            preObj.id = task_uid[0];
//            newObj.evn_uid = workflow.currentSelection.id;
//            newObj.task_to = next_task_uid[0];
//            this.workflow.saveRoute(preObj,newObj);
//          }
        }
      },
      failure: function(){
          Ext.Msg.alert ('Failure');
      }
    });
  }
}

/**
 * save Route on Changing of route Ports depending on the Shape Type
 * @Param  preObj     Object
 * @Param  newObj     Object
 * @Author Girish joshi
 */
MyWorkflow.prototype.saveRoute =    function(preObj,newObj)
{
    var task_uid      = new Array();
    var next_task_uid = new Array();
    var rou_type      ='';
    var rou_evn_uid   = '';
    var port_numberIP   = '';
    var port_numberOP   = '';
    var sGatUid   = '';
    var sGatType   = '';

    if(typeof newObj.sPortType != 'undefined')
      {
        sPortTypeIP          = newObj.sPortType;
        sPortTypeOP          = preObj.sPortType;
        var sPortType_lenIP  = sPortTypeIP.length;
        var sPortType_lenOP  = sPortTypeOP.length;
        port_numberIP        = sPortTypeIP.charAt(sPortType_lenIP-1);
        port_numberOP        = sPortTypeOP.charAt(sPortType_lenOP-1);
      }
     if(preObj.type.match(/Task/) && newObj.type.match(/Event/) && newObj.type.match(/Inter/))
      {
         task_uid[0]      = preObj.id;
         next_task_uid[0] = newObj.task_to;
         rou_type         = 'SEQUENTIAL';
         rou_evn_uid      = newObj.id;        
      }
      //If both the Object are Task
      else if(preObj.type.match(/Task/) && newObj.type.match(/Task/))
      {
        task_uid[0]          = preObj.id;
        next_task_uid[0]     = newObj.id;
        rou_type             = 'SEQUENTIAL';
      }
      else if(preObj.type.match(/Task/) && newObj.type.match(/End/) && newObj.type.match(/Event/) || newObj.reverse == 1)
      {
        //this.deleteRoute(newObj.conn,1);
        if(newObj.reverse == 1)      //Reverse Routing
            task_uid[0]  = newObj.id;
        else
            task_uid[0]  = preObj.id;

        next_task_uid[0] = '-1';

        rou_type         = 'SEQUENTIAL';
        rou_evn_uid      = newObj.id;
      }
      else if(preObj.type.match(/Gateway/))
      {
         switch(preObj.type){
            case  'bpmnGatewayParallel':
                    rou_type ='PARALLEL';
                    break;
            case  'bpmnGatewayExclusiveData':
                    rou_type = 'EVALUATE';
                    break;
            case  'bpmnGatewayInclusive':
                    rou_type = 'PARALLEL-BY-EVALUATION';
                    break;
            case  'bpmnGatewayComplex':
                    rou_type = 'DISCRIMINATOR';
                    break;
        }
        var ports = preObj.getPorts();
        var len =ports.data.length;

        //Get all the connection of the shape
        var conn = new Array();
        var count1 = 0;
        var count2 = 0;
        for(var i=0; i<=len; i++){
            if(typeof ports.data[i] === 'object')
                conn[i] = ports.data[i].getConnections();
        }

        //Get ALL the connections for the specified PORT
        for(i = 0; i< conn.length ; i++)
            {
                if(typeof conn[i] != 'undefined')
                for(var j = 0; j < conn[i].data.length ; j++)
                   {
                     if(typeof conn[i].data[j] != 'undefined')
                        {
                            if(conn[i].data[j].sourcePort.parentNode.type != preObj.type){
                                   // task_uid[count1] = new Array();
                                    task_uid[count1] = conn[i].data[j].sourcePort.parentNode.id;
                                    count1++;
                            }
                            if(conn[i].data[j].targetPort.parentNode.type != preObj.type){
                                   // task_uid[count2] = new Array();
                                    next_task_uid[count2] = conn[i].data[j].targetPort.parentNode.id;
                                    count2++;
                            }
                            
                        }
                    }
            }
    }

    var staskUid     = 	Ext.util.JSON.encode(task_uid);
    var sNextTaskUid = 	Ext.util.JSON.encode(next_task_uid);
    if(preObj.type.match(/Gateway/)){
        sGatUid      =  preObj.id;
        sGatType     =  preObj.type;
    }
    if(task_uid.length > 0 && next_task_uid.length > 0)
        {
            Ext.Ajax.request({
                    url: "bpmn/patterns_Ajax.php",
                    success: function(response) {
                        if(response.responseText != 0) {
                            if(typeof newObj.conn != 'undefined') {
                                //var resp = response.responseText.split("|");     //resp[0] => gateway UID OR event_UID , resp[1] => route UID
                                var resp = response.responseText;     //resp[0] => gateway UID OR event_UID , resp[1] => route UID
                                newObj.conn.html.id = resp;
                                newObj.conn.id = resp;
                            }
                        }
                    },
                    failure: function(){
                        Ext.Msg.alert ('Failure');
                    },
                    params: {
                            action        :'savePattern',
                            PROCESS       : pro_uid,
                            TASK          : staskUid,
                            ROU_NEXT_TASK : sNextTaskUid,
                            ROU_TYPE      : rou_type,
                            ROU_EVN_UID   : rou_evn_uid,
                            PORT_NUMBER_IP: port_numberIP,
                            PORT_NUMBER_OP: port_numberOP,
                            GAT_UID       : sGatUid,
                            GAT_TYPE      : sGatType,
                            mode:'Ext'
                        }
                });
        }
    else
        workflow.saveGateways(preObj);
}

MyWorkflow.prototype.deleteRoute = function(oConn,iVal){
     workflow.oConn = oConn;
     var sourceObjType = oConn.sourcePort.parentNode.type;
     var targetObjType = oConn.targetPort.parentNode.type;
     var rou_uid       = oConn.id;
     //Setting Condition for VALID ROUTE_UID present in Route Table
     //For start and gateway event, we dont have entry in ROUTE table
     if(rou_uid != '' && !sourceObjType.match(/Gateway/) && !sourceObjType.match(/Start/) && !targetObjType.match(/Gateway/)){
            workflow.urlDeleteparameter = '?action=deleteRoute&data={"uid":"'+ rou_uid +'"}';
     }
     //Deleting route for Start event and also deleting start event
     else if(sourceObjType.match(/Start/)){
            var targetObj = oConn.targetPort.parentNode;  //Task
            var tas_uid   = targetObj.id;
            var tas_start = 'FALSE';
            workflow.urlDeleteparameter = '?action=saveStartEvent&data={"tas_uid":"'+tas_uid+'","tas_start":"'+tas_start+'"}';
     }
     if(iVal == 0)
        Ext.MessageBox.confirm('Confirm', 'Are you sure you want to delete the Route',this.showEventResult);
     else
         this.showEventResult('yes');
}

MyWorkflow.prototype.showEventResult = function(btn){
        //this.workflow.confirm = btn;
        if(typeof workflow.urlDeleteparameter != 'undefined')
        {
           var url = workflow.urlDeleteparameter;
           if(btn == 'yes')
            {
                Ext.Ajax.request({
                    url: "bpmn/processes_Ajax.php"+ url,
                    success: function(response) {
                           workflow.getCommandStack().execute(new CommandDelete(workflow.oConn));
                    },
                    failure: function(){
                        Ext.Msg.alert ('Failure');
                    }
                });
            }
        }

    };
/**
 * Deleting Event
 * @Param  eventObj     Object
 * @Author Girish joshi
 */
MyWorkflow.prototype.deleteEvent = function(eventObj){

     var event_uid = eventObj.id;
     if(event_uid != '') {
            var urlparams = '?action=deleteEvent&data={"uid":"'+ event_uid +'"}';
            Ext.Ajax.request({
                    url: "bpmn/processes_Ajax.php"+ urlparams,
                    success: function(response) {
                    },
                    failure: function(){
                        Ext.Msg.alert ('Failure');
                    }
                });
     }
}

MyWorkflow.prototype.getDeleteCriteria = function()
{
  var shape = workflow.currentSelection.type;
  var currentObj = workflow.currentSelection;
  if(shape.match(/Task/)){
    workflow.currentSelection.actiontype = 'deleteTask';
  }
  else if(shape.match(/SubProcess/)){
    workflow.currentSelection.actiontype = 'deleteSubProcess';
  }
  else if(shape.match(/Annotation/)){
    workflow.currentSelection.actiontype = 'deleteText';
  }
  else if(shape.match(/Event/) && shape.match(/Start/)){
    workflow.currentSelection.actiontype = 'deleteStartEvent';
  }
  else if(shape.match(/Event/) && shape.match(/End/)){
    workflow.currentSelection.actiontype = 'deleteEndEvent';
  }
  else if(shape.match(/Event/) && shape.match(/Inter/)){
    workflow.currentSelection.actiontype = 'deleteInterEvent';
  }
  else if(shape.match(/Gateway/)){
    workflow.currentSelection.actiontype = 'deleteGateway';
    workflow.deleteShape(workflow.currentSelection);
  }
  if(workflow.currentSelection.actiontype != '')
    workflow.deleteShape(workflow.currentSelection);
}

/**
 * Zoom Function
 * @Param  sType  string(in/out)
 * @Author Girish joshi
 */
MyWorkflow.prototype.zoom = function(sType)
{
   //workflow.zoomFactor = 1;
   var loadMask = new Ext.LoadMask(document.body, {msg:'Zooming..'});
   var figures = workflow.getDocument().getFigures();

   var lines=workflow.getLines();
   var size=lines.getSize();
  
   sType =sType/100;
   workflow.zoomfactor = sType;
   var figSize = figures.getSize();
  // loadMask.show();
   for(f = 0;f<figures.getSize();f++){
   var fig = figures.get(f);
   if(typeof fig.limitFlag == 'undefined'){
     if(typeof fig.orgXPos == 'undefined') {
       fig.orgXPos = fig.getX();
       fig.orgYPos = fig.getY();
     }
     fig.orgFontSize =fig.fontSize;
     if(fig.boundaryEvent == true){
       fig.orgx3Pos = fig.x3;
       fig.orgy4Pos = fig.y4;
       fig.orgy5Pos = fig.y5;
     }
     fig.limitFlag = true;
   }
   if(fig.limitFlag == false){
     fig.originalWidth = fig.getWidth();
     fig.originalHeight = fig.getHeight();
     fig.limitFlag = true;
   }
   
   var width  = fig.originalWidth*sType;
   var height = fig.originalHeight*sType;
   if(fig.boundaryEvent == true) {
     fig.x3 = fig.orgx3Pos *sType;
     fig.y4 = fig.orgy4Pos *sType;
     fig.y5 = fig.orgy5Pos *sType;
   }
   
   var xPos =  fig.orgXPos * sType;
   var yPos =  fig.orgYPos * sType;
   
   if(fig.type == 'bpmnTask') {
      fig.fontSize = parseInt(fig.orgFontSize) * sType;
      //fig.bpmnText.drawStringRect(fig.taskName, fig.padleft, fig.padtop, fig.rectWidth, fig.rectheight, 'center');
      fig.bpmnText.paint();
    }
    else if(fig.type == 'bpmnAnnotation') {
      fig.fontSize = parseInt(fig.orgFontSize) * sType;
      fig.bpmnText.paint();
    }
    fig.setPosition(xPos,yPos);
    fig.setDimension(width,height);
   }

   //If zooming is 100% disable resizing of shapes again
   if(sType == '1'){
     fig.orgXPos = fig.getX();
     fig.orgYPos = fig.getY();
     fig.orgFontSize =fig.fontSize;
     if(fig.boundaryEvent == true){
       fig.orgx3Pos = fig.x3;
       fig.orgy4Pos = fig.y4;
       fig.orgy5Pos = fig.y5;
     }
   }
  // loadMask.hide();
}

MyWorkflow.prototype.redrawTaskText = function(fig){
  //Setting font minimum limit
  if(this.fontSize < 11)
     this.fontSize = 11;
 this.limitFlag = true;
  fig.paint();
}
MyWorkflow.prototype.redrawAnnotationText = function(fig,sType){
  if(sType == 'in')
    fig.fontSize = parseInt(fig.fontSize) + 4;
  else
    fig.fontSize = parseInt(fig.fontSize) - 4;

  //Setting font minimum limit i.e. 11px
  if(fig.fontSize < 11)
    fig.fontSize = 11;
  fig.paint();
}


 MyWorkflow.prototype.createUIDButton = function (value) {
         Ext.MessageBox.alert ('Info','UID: '+value);
    }

MyWorkflow.prototype.ExtVariables = function(fieldName,rowData)
{
  var pro_uid = workflow.getUrlVars();
  var varFields = Ext.data.Record.create([
            {
                name: 'variable',
                type: 'string'
            },
            {
                name: 'type',
                type: 'string'
            },
            {
                name: 'label',
                type: 'string'
            }
       ]);
  var varStore = '';
  varStore = new Ext.data.JsonStore({
            root         : 'data',
            totalProperty: 'totalCount',
            idProperty   : 'gridIndex',
            remoteSort   : true,
            fields       : varFields,
            proxy        : new Ext.data.HttpProxy({
                   url   : 'proxyVariable?pid='+pro_uid+'&sFieldName=form[CTO_CONDITION]&sSymbol=@@'
            })
          });
  //varStore.load();

  var varColumns = new Ext.grid.ColumnModel({
            columns: [
                new Ext.grid.RowNumberer(),
                    {
                        id: 'FLD_NAME',
                        header: 'Variable',
                        dataIndex: 'variable',
                        width: 170,
                        editable: false,
                        sortable: true
                    },{
                        id: 'PRO_VARIABLE',
                        header: 'Label',
                        dataIndex: 'label',
                        width: 150,
                        sortable: true
                    }
                ]
        });

  var varForm = new Ext.FormPanel({
        labelWidth: 100,
        monitorValid : true,
        width     : 400,
        height    : 350,
        renderer: function(val){return '<table border=1> <tr> <td> @@ </td> <td> Replace the value in quotes </td> </tr> </table>';},
        items:
            {
                xtype:'tabpanel',
                activeTab: 0,
                defaults:{
                    autoHeight:true
                },
                items:[{
                        title:'All Variables',
                        id   :'allVar',
                        layout:'form',
                        listeners: {
                            activate: function(tabPanel){
                                                        // use {@link Ext.data.HttpProxy#setUrl setUrl} to change the URL for *just* this request.
                                                        var link = 'proxyVariable?pid='+pro_uid+'&type='+tabPanel.id+'&sFieldName=form[CTO_CONDITION]&sSymbol=@@';
                                                        varStore.proxy.setUrl(link, true);
                                                        varStore.load();
                            }
                        },
                        items:[{
                                xtype: 'grid',
                                ds: varStore,
                                cm: varColumns,
                                width: 380,
                                autoHeight: true,
                                //plugins: [editor],
                                //loadMask    : true,
                                loadingText : 'Loading...',
                                border: false,
                                listeners: {
                                 //rowdblclick: alert("ok"),
                                 rowdblclick: function(){
                                           var getObjectGridRow = workflow.gridObjectRowSelected;
                                           var FieldSelected    = workflow.gridField;

                                           //getting selected row of variables
                                           var rowSelected      = this.getSelectionModel().getSelected();
                                           var rowLabel         = rowSelected.data.variable;

                                           //Assigned new object with condition
                                           if(typeof rowData.colModel != 'undefined')
                                               rowData.colModel.config[3].editor.setValue(rowLabel);
                                           //Assigning / updating Condition for a row
                                           else
                                               rowData[0].set(fieldName,rowLabel);
                                      }
                                }
                                 }]
                }]
            }
});
  var window = new Ext.Window({
        title: 'Variables',
        collapsible: false,
        maximizable: false,
        scrollable: true,
        width: 400,
        height: 350,
        minWidth: 200,
        minHeight: 150,
        autoScroll: true,
        layout: 'fit',
        plain: true,
        buttonAlign: 'center',
        items: [varForm]
  });
    window.show();

}
