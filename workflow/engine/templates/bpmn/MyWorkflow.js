MyWorkflow=function(id){
Workflow.call(this,id);
this.html.style.backgroundImage="url(/skins/ext/images/gray/shapes/grid_10.png)";
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
  if (oShape.x < 75) {
        oShape.x = 85;
  }
  //Right Border
  if (oShape.x > 1300 - oShape.width) {
    workflow.main.setWidth(oShape.x+150);
  }
  //Top Border
  if (oShape.y < 55) {
    oShape.y = 60;
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
            items: [{
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
            },
            {
                text: 'Properties',
                handler: taskExtObj.editTaskProperties,
                scope: oShape
            }]
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
            text: 'NULL Router',
            scope: this,
            handler: MyWorkflow.prototype.toggleConnection
        }, {
            text: 'Manhatten Router',
            scope: this,
            handler: MyWorkflow.prototype.toggleConnection
        }, {
            text: 'Bezier Router',
            scope: this,
            handler: MyWorkflow.prototype.toggleConnection
        }, {
            text: 'Fan Router',
            scope: this,
            handler: MyWorkflow.prototype.toggleConnection
        }, {
            text: 'Delete Router',
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
            case 'Manhatten Router':
                this.currentSelection.setRouter(new ManhattanConnectionRouter());
            break;
            case 'Bezier Router':
                this.currentSelection.setRouter(new BezierConnectionRouter());
            break;
            case 'Fan Router':
                this.currentSelection.setRouter(new FanConnectionRouter());
            break;
            case 'Delete Router':
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
        var TaskPortName = ['inputPort1','inputPort2','outputPort1','outputPort2'];
        var TaskPortType = ['InputPort','InputPort','OutputPort','OutputPort'];
        var TaskPositionX= [0,_40c5.width/2,_40c5.width,_40c5.width/2];
        var TaskPositionY= [_40c5.height/2,0,_40c5.height/2,_40c5.height];

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
                        }, {
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
            handler: this.editSubProcessProperties,
            scope: this
        }]
    });

this.canvasSubProcess.on('contextmenu', function(e) {
    e.stopEvent();
    this.contextSubProcessmenu.showAt(e.getXY());
}, this);
}

/**
 * ExtJs Form of SubProcess Properties
 * @Param  Shape Object
 * @Author Safan Maredia
 */
MyWorkflow.prototype.editSubProcessProperties= function(_3525)
{
        var pro_uid = workflow.getUrlVars();
        var taskId  = workflow.currentSelection.id;
        //Variables Out Grid
        var subProcessFields = Ext.data.Record.create([
            {name: 'SP_UID',type: 'string'},
            {name: 'TAS_UID',type: 'string'},
            {name: 'PRO_PARENT',type: 'string'},
            {name: 'TAS_PARENT',type: 'string'},
            {name: 'SP_SYNCHRONOUS',type: 'string'},
            {name: 'SPROCESS_NAME',type: 'string'},
            {name: 'TASKS',type: 'string'},
            {name: 'TAS_TITLE',type: 'string'},
            {name: 'CON_VALUE',type: 'string'},
            {name: 'VAR_OUT1',type: 'string'},
            {name: 'VAR_OUT2',type: 'string'},
            {name: 'VAR_IN1',type: 'string'},
            {name: 'VAR_IN2',type: 'string'}
       ]);

    var editorOut = new Ext.ux.grid.RowEditor({
            saveText: 'Update'
        });
    var editorIn = new Ext.ux.grid.RowEditor({
            saveText: 'Update'
        });

    //Variable out grid configuration starts here
    var btnAddOut = new Ext.Button({
            id: 'btnAddOut',
            text: 'Assign Variables Out',
            iconCls: 'application_add',
            handler: function(){
                var e = new subProcessFields({
                     SP_UID         : '',
                     PRO_PARENT     : '',
                     SP_SYNCHRONOUS : '',
                     TAS_PARENT     : '',
                     VAR_OUT1       : '',
                     VAR_OUT2	    : ''
                });

                    editorOut.stopEditing();
                    variablesOutStore.insert(0, e);
                    variableOutGrid.getView().refresh();
                    //grid.getSelectionModel().selectRow(0);
                    editorOut.startEditing(0, 0);
            }
        });

    var btnRemoveOut = new Ext.Button({
        id: 'btnRemoveOut',
        text: 'Remove Variables Out',
        iconCls: 'application_delete',
        handler: function (s) {
            editorOut.stopEditing();
            var s = variableOutGrid.getSelectionModel().getSelections();
            for(var i = 0, r; r = s[i]; i++){
                variablesOutStore.remove(r);
            }
        }
    });

    var tbOut = new Ext.Toolbar({
        items: [btnAddOut, btnRemoveOut]
    });


    //Variable out grid configuration starts here
  var btnAddIn = new Ext.Button({
        id: 'btnAddIn',
        text: 'Assign Variables In',
        iconCls: 'application_add',
        handler: function(){
            var e = new subProcessFields({
                 SP_UID         : '',
                 PRO_PARENT     : '',
                 SP_SYNCHRONOUS : '',
                 TAS_PARENT     : '',
                 VAR_IN1        : '',
                 VAR_IN2	    : ''
            });

                editorIn.stopEditing();
                variablesInStore.insert(0, e);
                variableInGrid.getView().refresh();
                editorIn.startEditing(0, 0);
        }
    });

    var btnRemoveIn = new Ext.Button({
        id: 'btnRemoveIn',
        text: 'Remove Variables In',
        iconCls: 'application_delete',
        handler: function (s) {
            editorIn.stopEditing();
            var s = variableInGrid.getSelectionModel().getSelections();
            for(var i = 0, r; r = s[i]; i++){


                //Secondly deleting from Grid
                variablesInStore.remove(r);
            }
        }
    });

    var tbIn = new Ext.Toolbar({
        items: [btnAddIn, btnRemoveIn]
    });

    // create the Data Store of all Variables Out
    var variablesOutStore = new Ext.data.JsonStore({
        root         : 'data',
        totalProperty: 'totalCount',
        idProperty   : 'gridIndex',
        remoteSort   : true,
        fields       : subProcessFields,
        proxy        : new Ext.data.HttpProxy({
               url   : 'proxySubProcessProperties?pid='+pro_uid+'&tid='+taskId+'&type=0' //type=0 specifies Variables Out (Asynchronous)
        })
      });
      variablesOutStore.load();

    // create the Data Store of all Variables In
    var variablesInStore = new Ext.data.JsonStore({
        root         : 'data',
        totalProperty: 'totalCount',
        idProperty   : 'gridIndex',
        remoteSort   : true,
        fields       : subProcessFields,
        proxy        : new Ext.data.HttpProxy({
               url   : 'proxySubProcessProperties?pid='+pro_uid+'&tid='+taskId+'&type=1'  //type=1 specifies Variables In (Synchronous)
        })
      });
      //taskUsers.setDefaultSort('LABEL', 'asc');
      variablesInStore.load();

    var variableOutGrid =  new Ext.grid.GridPanel({
        store       : variablesOutStore,
        id          : 'mygrid',
        loadMask    : true,
        loadingText : 'Loading...',
        renderTo    : 'cases-grid',
        frame       : true,
        autoHeight  : true,
        autoScroll  : true,
        clicksToEdit: 1,
        layout      : 'form',
        plugins     : [editorOut],
        columns     : [{
                        id       : 'VAR_OUT1',
                        header   : 'Origin',
                        dataIndex: 'VAR_OUT1',
                        width    : 200,
                        sortable : true,
                        editor   : new Ext.form.TextField({
                                allowBlank: true
                            })
                        },
                        {
                         sortable: false,
                         renderer: function()
                            {
                                return '<input type="button" value="@@" />';
                            }
                        },
                        {
                        id        : 'VAR_OUT2',
                        header    : 'Target',
                        dataIndex : 'VAR_OUT2',
                        width     : 200,
                        sortable  : true,
                        editor    : new Ext.form.TextField({
                                allowBlank: true
                            })
                        },
                        {
                         sortable: false,
                         renderer: function()
                            {
                                return '<input type="button" value="@@" />';
                            }
                        }
                      ],
        viewConfig: {forceFit: true},
        stripeRows: true,
        tbar: tbOut
     });

    var variableInGrid =  new Ext.grid.GridPanel({
        store       : variablesInStore,
        id          : 'mygrid1',
        loadMask    : true,
        loadingText : 'Loading...',
        renderTo    : 'cases-grid',
        frame       : true,
        autoHeight  : true,
        autoScroll  : true,
        clicksToEdit: 1,
        layout      : 'form',
        plugins     : [editorIn],
        columns     : [{
                        id       : 'VAR_IN1',
                        header   : 'Origin',
                        dataIndex: 'VAR_IN1',
                        width    : 200,
                        sortable : true,
                        editor   : new Ext.form.TextField({
                                allowBlank: true
                            })
                        },
                        {
                         sortable: false,
                         renderer: function()
                            {
                                return '<input type="button" value="@@" />';
                            }
                        },
                        {
                        id        : 'VAR_IN2',
                        header    : 'Target',
                        dataIndex : 'VAR_IN2',
                        width     : 200,
                        sortable  : true,
                        editor    : new Ext.form.TextField({
                                allowBlank: true
                            })
                        },
                        {
                         sortable: false,
                         renderer: function()
                            {
                                return '<input type="button" value="@@" />';
                            }
                        }
                      ],
        viewConfig: {forceFit: true},
        stripeRows: true,
        tbar: tbIn
     });



     editorOut.on({
          scope: this,
          afteredit: function(roweditor, changes, record, rowIndex) {


             var sTasks            = record.data.TASKS;
             var sSync             = record.data.SP_SYNCHRONOUS;
             var sSP_UID           = record.data.SP_UID;
             var sProcess_Parent   = record.data.PRO_PARENT;
             var sTask_Parent      = record.data.TAS_PARENT;
             var sVar_Out1         = record.data.VAR_OUT1;
             var sVar_Out2         = record.data.VAR_OUT2;

             Ext.Ajax.request({
              url   : 'processes_Ajax.php',
              method: 'POST',
              params: {
                    action          : 'saveSubProcessDetails',
                    TASKS           : sTasks,
                    SP_SYNCHRONOUS  : sSync,
                    SP_UID          : sSP_UID,
                    PRO_PARENT      : sProcess_Parent,
                    TAS_PARENT      : sTask_Parent,
                    VAR_OUT1        : sVar_Out1,
                    VAR_OUT2        : sVar_Out2
              },
              success: function(response) {
                  Ext.MessageBox.alert ('Status','Variable Out has been saved successfully.');
              }
            });

          }
        });

        editorIn.on({
          scope: this,
          afteredit: function(roweditor, changes, record, rowIndex) {


             var sTasks            = record.data.TASKS;
             var sSync             = record.data.SP_SYNCHRONOUS;
             var sSP_UID           = record.data.SP_UID;
             var sProcess_Parent   = record.data.PRO_PARENT;
             var sTask_Parent      = record.data.TAS_PARENT;
             var sVar_In1          = record.data.VAR_IN1;
             var sVar_In2          = record.data.VAR_IN2;

             Ext.Ajax.request({
              url   : 'processes_Ajax.php',
              method: 'POST',
              params: {
                    action          : 'saveSubProcessDetails',
                    TASKS           : sTasks,
                    SP_SYNCHRONOUS  : sSync,
                    SP_UID          : sSP_UID,
                    PRO_PARENT      : sProcess_Parent,
                    TAS_PARENT      : sTask_Parent,
                    VAR_IN1         : sVar_In1,
                    VAR_IN2         : sVar_In2
              },
              success: function(response) {
                  Ext.MessageBox.alert ('Status','Variable In has been saved successfully.');
              }
            });

          }
        });

    var subProcessProperties = new Ext.FormPanel({
    labelWidth  : 110, // label settings here cascade unless overridden
    //frame:true,
    bodyStyle:'padding:5px 5px 0',
    scope: _3525,
    items: [
            {
            xtype:'fieldset',
            title: 'Sub-Process',
            collapsible: false,
            autoHeight:true,
            //width: 600,
            defaultType: 'textfield',
             items:[
                   {
                    id:    'subProcessName',
                    xtype: 'textfield',
                    width:  350,
                    fieldLabel: 'SubProcess name',
                    name      : 'SPROCESS_NAME',
                    allowBlank: false
                   },
                   {
                        width:          300,
                        xtype:          'combo',
                        mode:           'local',
                        triggerAction:  'all',
                        forceSelection: true,
                        editable:       false,
                        fieldLabel:     'Process',
                        name:           'process',
                        emptyText    : 'Select Process',
                        displayField:   'PROCESSES',
                        valueField:     'PROCESSES',
                        store:          variablesOutStore
                    },
                    {
                        width:          150,
                        id   :          'spType',
                        xtype:          'combo',
                        mode:           'local',
                        triggerAction:  'all',
                        forceSelection: true,
                        editable:       false,
                        fieldLabel:     'Type',
                        name:           'SP_SYNCHRONOUS',
                        hiddenName:     'SP_SYNCHRONOUS',
                        displayField:   'name',
                        valueField:     'value',
                        emptyText    : 'Select Type',
                        store:          new Ext.data.JsonStore({
                                    fields : ['name', 'value'],
                                    data   : [
                                        {name : 'Asynchronous',   value: 'Asynchronous'},
                                        {name : 'Synchronous',   value: 'Synchronous'},
                                    ]
                                }),
                       onSelect: function(record, index){
                           if(record.data.name == 'Synchronous')
                                Ext.getCmp("variablein").show();
                           else
                                Ext.getCmp("variablein").hide();

                           this.setValue(record.data[this.valueField || this.displayField]);
                           this.collapse();
                       }
                    }]
            },
            {
            id   :'variableout',
            xtype:'fieldset',
            title: 'Variables Out',
            collapsible: false,
            labelAlign: 'top',
             items:[variableOutGrid]
            },
            {
            id   :'variablein',
            xtype:'fieldset',
            title: 'Variables In',
            //hidden: true,
            collapsible: false,
            labelAlign: 'top',
            items:[variableInGrid]
            }]
    });

    //Loading Task Details into the form
    subProcessProperties.form.load({
        url:'proxySubProcessProperties?pid='+pro_uid+'&tid='+taskId+'&type=2',
        method:'GET',
        //waitMsg:'Loading',
        success:function(form, action) {
           Ext.getCmp("subProcessName").setValue(action.result.data[0].TAS_TITLE);
           if(action.result.data[0].SP_SYNCHRONOUS == 0)
               {
                   Ext.getCmp("variablein").hide();
                   Ext.getCmp("spType").setValue("Asynchronous");
               }
           else
               {
                   Ext.getCmp("variablein").show();
                   Ext.getCmp("spType").setValue("Synchronous");
               }
          workflow.subProcessProperties = action.result.data[0];
        },
        failure:function(form, action) {
            Ext.MessageBox.alert('Message', 'Load failed');
        }
     });

    subProcessProperties.render(document.body);
   // workflow.subProcessProperties = subProcessProperties;

    var window = new Ext.Window({
    title: 'Task: ',
    collapsible: false,
    maximizable: false,
    width: 800,
    height: 500,
    minWidth: 300,
    minHeight: 150,
    layout: 'fit',
    plain: true,
    bodyStyle: 'padding:5px;',
    buttonAlign: 'center',
    items: subProcessProperties,
    buttons: [{
        text: 'Save',
        handler: function(){
            var getForm    = subProcessProperties.getForm().getValues();
            var sTask_Parent = workflow.subProcessProperties.TAS_PARENT;
            var sSPNAME = getForm.SPROCESS_NAME;
           Ext.Ajax.request({
              url   : 'processes_Ajax.php',
              method: 'POST',
              params: {
                    action          : 'subprocessProperties',
                    TAS_PARENT      : sTask_Parent,
                    SPROCESS_NAME   : sSPNAME
                  },
              success: function(response) {
                  Ext.MessageBox.alert ('Status','Sub Process Properties has been saved successfully.');
              }
            });

            workflow.currentSelection.bpmnNewText.clear();
            workflow.currentSelection.bpmnNewText.drawStringRect(sSPNAME,20,20,100,'left');
            workflow.currentSelection.bpmnNewText.paint();
            workflow.currentSelection.subProcessName = sSPNAME;
            //var getstore = taskPropertiesTabs.getStore();
            //var getData = getstore.data.items;
            //taskExtObj.saveTaskProperties(_5625);
        }
    },{
        text: 'Cancel',
        handler: function(){
            // when this button clicked,
            window.close();
        }
    }]
    });
    window.show();

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
        var targetNode= new Array();
        var countConn = 0;
        var sourcePortName= new Array();
        var targetPortName= new Array();
        var sourcePortId= new Array();
        var targetPortId= new Array();

        //Get the pre-selected id into new variable to compare in future code
        var shapeId = this.workflow.currentSelection.id;

        //Get the current pre-selected figure object in the new object, because not accessible after adding new shapes
        var oldWorkflow = this.workflow.currentSelection;

        //Get the source and Target object of all the connections in an array
        for(i = 0; i< connLength ; i++)
            {
                for(var j = 0; j < conn[i].data.length ; j++)
                   {
                    if(typeof conn[i].data[j] != 'undefined')
                        {
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
        
        if(item.type == 'bpmnEventBoundaryTimerInter')
        {
          workflow.boundaryEvent = true;
          workflow.taskName = oldWorkflow.taskName;
          var newShape = eval("new bpmnTask(workflow)");
          //workflow.boundaryEvent = false;
          
        }
        else if(item.type == 'bpmnSubProcess')
            {
                workflow.subProcessName = 'Sub Process';
                newShape = eval("new "+item.type+"(this.workflow)");
            }
        else
            newShape = eval("new "+item.type+"(this.workflow)");
        
        this.workflow.addFigure(newShape,x,y); //Add New Selected Gateway First

        //Delete Old Shape
        item.scope.workflow.getCommandStack().execute(new CommandDelete(oldWorkflow));
        ToolGeneric.prototype.execute.call(item.scope);

        //to create all the new connections again
        var connObj;
        for(i=0 ; i < countConn ; i++)
            {
               if(sourcePortId[i] == shapeId)  //If shapeId is equal to sourceId the , replace the oldShape object by new shape Object
                   sourceNode[i] = newShape;
               else
                   targetNode[i] = newShape;

               connObj = new DecoratedConnection();
               connObj.setTarget(eval('targetNode[i].getPort(targetPortName[i])'));
               connObj.setSource(eval('sourceNode[i].getPort(sourcePortName[i])'));
               newShape.workflow.addFigure(connObj);
            }

         //Saving Asynchronously deleted shape and new created shape into DB
         if(item.type.match(/Boundary/))
         {
            newShape.id = oldWorkflow.id;
            newShape.html.id = oldWorkflow.id;
            newShape.actiontype = 'updateTask';
            workflow.saveShape(newShape);
         }
         if(newShape.type.match(/Event/)  && newShape.type.match(/Inter/) && !item.type.match(/Boundary/))
         {
              newShape.actiontype = 'updateEvent';
              //Set the Old Id to the Newly created Event
              newShape.html.id = oldWorkflow.id;
              newShape.id = oldWorkflow.id;
              newShape.workflow.saveShape(newShape);
         }
         if(newShape.type  == 'bpmnEventMessageStart' || newShape.type  == 'bpmnEventTimerStart')
         {
             newShape.workflow.currentSelection = newShape;
             var task_details = workflow.getStartEventConn(newShape,'targetPort','OutputPort');
             if(task_details.length > 0 )
                 {
                    var task_uid = task_details[0].value;
                    newShape.task_uid = task_uid;
                    newShape.actiontype = 'addEvent';
                    newShape.workflow.saveShape(newShape);
                 }
         }
         else if(newShape.type  == 'bpmnEventEmptyStart')
         {
             workflow.deleteEvent(oldWorkflow);
             newShape.workflow.currentSelection = newShape;
             var task_details = workflow.getStartEventConn(newShape,'targetPort','OutputPort');
             if(task_details.length > 0 )
                 {
                    var task_uid = task_details[0].value;
                    newShape.task_uid = task_uid;
                    newShape.actiontype = 'saveStartEvent';
                    newShape.workflow.saveShape(newShape);
                 }
         }
         else if(newShape.type.match(/Gateway/))
             {
                 var shape = new Array();
                 shape.type = '';
                 newShape.workflow.saveRoute(newShape,shape);
             }

         //Swapping from Task to subprocess and vice -versa
         if((newShape.type == 'bpmnSubProcess' || newShape.type == 'bpmnTask') && !item.type.match(/Boundary/))
         {
             newShape.actiontype = 'addSubProcess';
             if(newShape.type == 'bpmnTask')
                 newShape.actiontype = 'addTask';
              newShape.workflow.saveShape(newShape);
         }
         if((this.type == 'bpmnTask' || this.type == 'bpmnSubProcess') && !item.type.match(/Boundary/) )
         {
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
         if((newShape.type == 'bpmnSubProcess' || newShape.type == 'bpmnTask') && !itemObj.type.match(/Boundary/))
         {
             newShape.actiontype = 'addSubProcess';
             if(newShape.type == 'bpmnTask')
                 newShape.actiontype = 'addTask';
              newShape.workflow.saveShape(newShape);
         }
         if((this.type == 'bpmnTask' || this.type == 'bpmnSubProcess') && !itemObj.type.match(/Boundary/) )
         {
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
                     if(typeof conn[i].data[j] != 'undefined')
                        {
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
                        }, {
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
                            text: 'Message :  Catch',
                            type:'bpmnEventMessageRecInter',
                            scope:_4093,
                            handler: MyWorkflow.prototype.toggleShapes
                        },
                        {
                            text: 'Timer',
                            type:'bpmnEventTimerInter',
                            scope:_4093,
                            handler: MyWorkflow.prototype.toggleShapes
                        },
                        {
                            text: 'Intermediate Boundary Timer',
                            type:'bpmnEventBoundaryInter',
                            scope:_4093,
                            handler: MyWorkflow.prototype.toggleShapes
                        }
                        /*, {
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
    if(oShape.type.match(/Task/) || oShape.type.match(/Gateway/) || oShape.type.match(/Inter/) || oShape.type.match(/SubProcess/)){
      ports = ['output1','input1','output2','input2'];
    }
    else if(oShape.type.match(/End/)){
      ports = ['input1','input2'];
    }
    else if(oShape.type.match(/Start/)){
      ports = ['output1','output2'];
    }
    else if(oShape.type.match(/Annotation/)){
      ports = ['input1'];
    }
    for(var i=0; i< ports.length ; i++){
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
  for(var i=0; i< aPort.length ; i++){
    if(aPort[i].match(/input/))
        eval('oShape.workflow.currentSelection.'+aPort[i]+'.setBackgroundColor(new Color(245, 115, 115))');
    else
        eval('oShape.workflow.currentSelection.'+aPort[i]+'.setBackgroundColor(new Color(115, 115, 245))');

        eval('oShape.workflow.currentSelection.'+aPort[i]+'.setColor(new Color(90, 150, 90))');
        eval('oShape.workflow.currentSelection.'+aPort[i]+'.setZOrder(50000)');
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
  for(var i=0; i< aPort.length ; i++){
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
        if(oShape.type.match(/Task/)){
            oShape.workflow.taskid = new Array();
            oShape.workflow.taskid.value = oShape.id;
             pmosExtObj.loadTask(oShape);
            oShape.workflow.AddTaskContextMenu(oShape);
        }
        else if(oShape.type.match(/Start/)){
            oShape.workflow.taskUid = oShape.workflow.getStartEventConn(oShape,'targetPort','OutputPort');
            pmosExtObj.loadDynaforms(oShape);
            if(oShape.type.match(/Message/))
                pmosExtObj.loadWebEntry(oShape);
            oShape.workflow.AddEventStartContextMenu(oShape);
        }
        else if(oShape.type.match(/Inter/)){
            oShape.workflow.taskUidFrom = oShape.workflow.getStartEventConn(oShape,'sourcePort','InputPort');
            //oShape.workflow.taskid =  oShape.workflow.taskUid[0];
            oShape.workflow.taskUidTo = oShape.workflow.getStartEventConn(oShape,'targetPort','OutputPort');
            oShape.workflow.taskid =  oShape.workflow.taskUidFrom[0];
            pmosExtObj.loadTask(oShape);
            pmosExtObj.getTriggerList(oShape);
            oShape.workflow.AddEventInterContextMenu(oShape);
        }
        else if(oShape.type.match(/End/)){
            oShape.workflow.taskUid = oShape.workflow.getStartEventConn(oShape,'sourcePort','InputPort');
            oShape.workflow.AddEventEndContextMenu(oShape);
        }
        else if(oShape.type.match(/Gateway/)){
            oShape.workflow.AddGatewayContextMenu(oShape);
        }
        else if(oShape.type.match(/SubProcess/)){
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
            if(temp1.data[0]){
                if(routes[j])
                    {
                       if(routes[j][1] != temp1.data[0].sourcePort.parentNode.id)
                       {
                           routes[j] = new Array(3);
                           routes[j][0] = temp1.data[0].id;
                           routes[j][1] = temp1.data[0].sourcePort.parentNode.id;
                           routes[j][2] = temp1.data[0].targetPort.parentNode.id;
                           routes[j][3] = temp1.data[0].targetPort.properties.name;
                           routes[j][4] = temp1.data[0].sourcePort.properties.name;
                       }
                    }
                 else
                    {
                        routes[j] = new Array(3);
                        routes[j][0] = temp1.data[0].id;
                        routes[j][1] = temp1.data[0].sourcePort.parentNode.id;
                        routes[j][2] = temp1.data[0].targetPort.parentNode.id;
                        routes[j][3] = temp1.data[0].targetPort.properties.name;
                        routes[j][4] = temp1.data[0].sourcePort.properties.name;
                     }
             }
	//j++;
//            while(routes[j])
//            {
//                j++
//            };
//            j--;
    }
    var j = 0;
    var serial = new Array();
    for(key in routes)
    {
        if(typeof routes[key] === 'object'){
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
/**
 * Saving Shape Asychronously
 * @Param  oNewShape Object
 * @Author Safan maredia
 */
MyWorkflow.prototype.saveShape= function(oNewShape)
{
    //Initializing variables

    var pro_uid = this.getUrlVars();
    var shapeId = oNewShape.id;
    var actiontype = oNewShape.actiontype;
    var xpos = oNewShape.x;
    var ypos = oNewShape.y;
    var pos = '{"x":'+xpos+',"y":'+ypos+'}';

    var width = oNewShape.width;
    var height = oNewShape.height;
    var cordinates = '{"x":'+width+',"y":'+height+'}';

    if(oNewShape.type == 'bpmnTask'){
        var newlabel = oNewShape.taskName;
    }
    if(oNewShape.type == 'bpmnAnnotation'){
        newlabel = oNewShape.annotationName;
    }

    //var urlparams = "action=addTask&data={"uid":"4708462724ca1d281210739068208635","position":{"x":707,"y":247}}";
    var urlparams;
    switch(actiontype)
    {
        case 'addTask':
            urlparams = '?action='+actiontype+'&data={"uid":"'+ pro_uid +'","position":'+pos+'}';
            break;
        case 'updateTask':
            urlparams = '?action='+actiontype+'&data={"uid":"'+ shapeId +'","boundary":"TIMER"}';
            break;
        case 'saveTaskPosition':
            urlparams = '?action='+actiontype+'&data={"uid":"'+ shapeId +'","position":'+pos+'}';
            break;
        case 'saveTaskCordinates':
            urlparams = '?action='+actiontype+'&data={"uid":"'+ shapeId +'","position":'+cordinates+'}';
            break;
        case 'saveAnnotationCordinates':
            urlparams = '?action='+actiontype+'&data={"uid":"'+ shapeId +'","position":'+cordinates+'}';
        break;
        case 'updateTaskName':
            urlparams = '?action='+actiontype+'&data={"uid":"'+ shapeId +'","label":"'+newlabel+'"}';
            break;
        case 'addSubProcess':
            urlparams = '?action='+actiontype+'&data={"uid":"'+ pro_uid +'","position":'+pos+'}';
            break;
        case 'addText':
            urlparams = '?action='+actiontype+'&data={"uid":"'+ pro_uid +'","label":"'+newlabel+'","position":'+pos+'}';
            break;
        case 'updateText':
            urlparams = '?action='+actiontype+'&data={"uid":"'+ shapeId +'","label":"'+newlabel+'"}';
            break;
        case 'saveTextPosition':
            urlparams = '?action='+actiontype+'&data={"uid":"'+ shapeId +'","position":'+pos+'}';
            break;
        case 'saveStartEvent':
            //If we change Event to start from Message/Timer then Delete the record from Events Table
            this.deleteEvent(oNewShape);
            var tas_start = 'TRUE';
            var tas_uid = oNewShape.task_uid;
            urlparams = '?action='+actiontype+'&data={"tas_uid":"'+tas_uid+'","tas_start":"'+tas_start+'"}';
            break;
        case 'addEvent':
            var tas_uid = oNewShape.workflow.taskUid[0].value;
            var tas_type = oNewShape.type;
            urlparams = '?action='+actiontype+'&data={"uid":"'+ pro_uid +'","tas_uid":"'+tas_uid+'","tas_type":"'+tas_type+'"}';
            break;
        case 'updateEvent':
            var evn_uid = oNewShape.id
            var evn_type = oNewShape.type;
            urlparams = '?action='+actiontype+'&data={"evn_uid":"'+evn_uid +'","evn_type":"'+evn_type+'"}';
            break;
        case 'saveGatewayPosition':
            urlparams = '?action='+actiontype+'&data={"uid":"'+ shapeId +'","position":'+pos+'}';
            break;
    }
    //var urlparams = '?action='+actiontype+'&data={"uid":"'+ pro_uid +'","position":'+pos+'}';


        Ext.Ajax.request({
            url: "processes_Ajax.php"+ urlparams,
            success: function(response) {
                //Ext.Msg.alert (response.responseText);
                  if(response.responseText != 1 && response.responseText != "")
                   {
                        this.workflow.newTaskInfo = Ext.util.JSON.decode(response.responseText);
                        oNewShape.html.id = this.workflow.newTaskInfo.uid;
                        oNewShape.id = this.workflow.newTaskInfo.uid;
                            if(oNewShape.type == 'bpmnTask'){
                                oNewShape.taskName = this.workflow.newTaskInfo.label;
                                workflow.redrawTaskText(oNewShape,'');
                                //After Figure is added, Update Start Event connected to Task
                                if(typeof this.workflow.preSelectedObj != 'undefined' )
                                  {
                                      var preSelectedFigure = this.workflow.preSelectedObj;
                                      if(preSelectedFigure.type.match(/Start/) && preSelectedFigure.type.match(/Event/))
                                        this.workflow.saveEvents(preSelectedFigure,oNewShape);

                                      if(preSelectedFigure.type.match(/Task/))
                                         this.workflow.saveRoute(preSelectedFigure,oNewShape);

                                      if (preSelectedFigure.type.match(/Gateway/)) 
                                         //preSelectedFigure.rou_type = 'SEQUENTIAL';
                                        this.workflow.saveRoute(preSelectedFigure,oNewShape);

                                      
                                      if (preSelectedFigure.type.match(/Inter/)) {
                                         //preSelectedFigure.rou_type = 'SEQUENTIAL';
                                        this.workflow.saveEvents(preSelectedFigure,oNewShape);
                                      }
                                  }
                            else if(oNewShape.type == 'bpmnSubProcess'){
                                oNewShape.subProcessName = this.workflow.newTaskInfo.label;
                        }
                   }
               }
            },
            failure: function(){
                //Ext.Msg.alert ('Failure');
            }
            });
}

MyWorkflow.prototype.saveTask= function(actiontype,xpos,ypos)
{
    if(actiontype != '')
        {
            var pro_uid = this.getUrlVars();
            var actiontype = actiontype;
            var pos = '{"x":'+xpos+',"y":'+ypos+'}';
            switch(actiontype)
            {
                case 'addTask':
                    urlparams = '?action='+actiontype+'&data={"uid":"'+ pro_uid +'","position":'+pos+'}';
                    break;

            }
             Ext.Ajax.request({
                    url: "processes_Ajax.php"+ urlparams,
                    success: function(response) {
                        //Ext.Msg.alert (response.responseText);
                          if(response.responseText != 1 && response.responseText != "")
                           {
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
                        url: "processes_Ajax.php"+ urlparams,
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
    //Initializing variables

    var pro_uid = this.getUrlVars();
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
       case 'saveStartEvent':
           var task_detail = this.getStartEventConn(this.currentSelection,'targetPort','OutputPort');
           if(task_detail.length > 0){
               var tas_uid = task_detail[0].value;
               var tas_start = 'FALSE';
               urlparams = '?action=saveStartEvent&data={"tas_uid":"'+tas_uid+'","tas_start":"'+tas_start+'"}';
               this.urlparameter = urlparams;
               shapeName = 'Start Event';
           }
           break;
       case 'deleteEndEvent':
            shapeName = 'End Event';
            oShape.workflow.getCommandStack().execute(new CommandDelete(oShape.workflow.getCurrentSelection()));
            break;
       case 'deleteGateway':
           shapeName = 'Gateway';
           urlparams = '?action='+actiontype+'&data={"pro_uid":"'+ pro_uid +'","gat_uid":"'+shapeId+'"}';
           this.urlparameter = urlparams;
           break;
    }

    //Ext.MessageBox.confirm('Confirm', 'Are you sure you want to delete the '+ shapeName,this.showResult);

if(oShape.noAlert == null)
    Ext.MessageBox.confirm('Confirm', 'Are you sure you want to delete the '+ shapeName,this.showResult);
else
    workflow.showResult('yes');
}

MyWorkflow.prototype.showResult = function(btn){
        //this.workflow.confirm = btn;
        if(typeof workflow.urlparameter != 'undefined')
        {
           var url = workflow.urlparameter;
           if(btn == 'yes')
            {
                Ext.Ajax.request({
                        url: "processes_Ajax.php"+ url,
                        success: function(response) {
                                //Ext.Msg.alert (response.responseText);
                        },
                        failure: function(){
                            Ext.Msg.alert ('Failure');
                        }
                        });
                workflow.getCommandStack().execute(new CommandDelete(workflow.getCurrentSelection()));
            }
        }
        //Ext.example.msg('Button Click', 'You clicked the {0} button', btn);
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
     //Get all the ports of the shapes
        var ports = oShape.workflow.currentSelection.getPorts();
        var len =ports.data.length;

        //Get all the connection of the shape
        var conn = new Array();
        for(var i=0; i<=len; i++){
            if(typeof ports.data[i] === 'object')
                if(ports.data[i].type == sPortType)
                    conn[i] = ports.data[i].getConnections();
        }
        //Initializing Arrays and variables
        var countConn = 0;
        var aStartTask= new Array();
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
                                aStartTask[countConn].name = eval('conn[i].data[j].'+sPort+'.parentNode.taskName');
                                countConn++;
                            }
                        }
                    }
            }
            return aStartTask;
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
    var pro_uid = this.getUrlVars();
    var task_uid      = new Array();
    var next_task_uid = new Array();

    if(oEvent.type.match(/Start/) && oEvent.type.match(/Empty/))
    {
        var tas_start = 'TRUE';
        var urlparams = '?action=saveStartEvent&data={"tas_uid":"'+sTaskUID+'","tas_start":"'+tas_start+'"}';
    }
    else if(oEvent.type.match(/Start/) && (oEvent.type.match(/Message/) || oEvent.type.match(/Timer/)) )
    {
        urlparams = '?action=addEvent&data={"uid":"'+ pro_uid +'","tas_uid":"'+sTaskUID+'","tas_type":"'+oEvent.type+'"}';
    }
    else if(oEvent.type.match(/Inter/))
    {
        var ports = oEvent.getPorts();
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
                            if(conn[i].data[j].sourcePort.parentNode.type != oEvent.type){
                                   // task_uid[count1] = new Array();
                                    task_uid[count1] = conn[i].data[j].sourcePort.parentNode.id;
                                    count1++;
                            }
                            if(conn[i].data[j].targetPort.parentNode.type != oEvent.type){
                                   // task_uid[count2] = new Array();
                                    next_task_uid[count2] = conn[i].data[j].targetPort.parentNode.id;
                                    count2++;
                            }

                        }
                    }
    }

    var staskUid     = 	Ext.util.JSON.encode(task_uid);
    var sNextTaskUid = 	Ext.util.JSON.encode(next_task_uid);
        urlparams = '?action=addEvent&data={"uid":"'+ pro_uid +'","tas_from":"'+staskUid+'","tas_to":"'+sNextTaskUid+'","tas_type":"'+oEvent.type+'"}';
    }

    if(urlparams != ''){
        Ext.Ajax.request({
                url: "processes_Ajax.php"+ urlparams,
                success: function(response) {
                    if(response.responseText != '')
                      {
                         //Save Route
                         if(workflow.currentSelection.type.match(/Inter/) && workflow.currentSelection.type.match(/Event/)){
                           workflow.currentSelection.id = response.responseText;
                           var newObj = workflow.currentSelection;
                           var preObj = new Array();
                           preObj.type = 'bpmnTask';
                           preObj.id = task_uid[0];
                           newObj.evn_uid = workflow.currentSelection.id;
                           newObj.task_to = next_task_uid[0];
                           this.workflow.saveRoute(preObj,newObj);
                         }
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
    var pro_uid = this.getUrlVars();
    
    var task_uid      = new Array();
    var next_task_uid = new Array();
    var rou_type      ='';
    var rou_evn_uid   = '';
    var port_numberIP   = '';
    var port_numberOP   = '';

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
         rou_evn_uid      = newObj.evn_uid;
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
        this.deleteRoute(newObj.conn,1);
        if(newObj.reverse == 1)      //Reverse Routing
            task_uid[0]      = newObj.id;
        else
            task_uid[0]      = preObj.id;
        next_task_uid[0] = '-1';
        rou_type         = 'SEQUENTIAL';
      }
      /*else if(preObj.type.match(/Event/) && preObj.type.match(/End/) && newObj.type.match(/Task/))
      {
        this.deleteRoute(newObj.conn,1);
        task_uid[0]      = newObj.id;
        next_task_uid[0] = '-1';
        rou_type         = 'SEQUENTIAL';
      }*/
      else if(preObj.type.match(/Gateway/))
      {
//         var task_uid = new Array();
//         var next_task_uid = new Array();
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
    if(staskUid != '')
        {
            Ext.Ajax.request({
                    url: "patterns_Ajax.php",
                    success: function(response) {
                        if(response.responseText != 0){
                            if(typeof newObj.conn != 'undefined'){
                                var resp = response.responseText.split("|");     //resp[0] => gateway UID OR event_UID , resp[1] => route UID
                                newObj.conn.html.id = resp[1];
                                newObj.conn.id = resp[1];

                                //replacing old gateway UID with response UID
                                if(! preObj.type.match(/Task/))
                                    {
                                        preObj.html.id = resp[0];
                                        preObj.id = resp[0];
                                    }
                            }
                        }
                    },
                    failure: function(){
                        Ext.Msg.alert ('Failure');
                    },
                    params: {
                            action:'savePattern',
                            PROCESS: pro_uid,
                            TASK:staskUid,
                            ROU_NEXT_TASK:sNextTaskUid,
                            ROU_TYPE:rou_type,
                            ROU_EVN_UID:rou_evn_uid,
                            PORT_NUMBER_IP:port_numberIP,
                            PORT_NUMBER_OP:port_numberOP,
                            GAT_UID       : '',
                            mode:'Ext'
                        }
                });
        }
}

/**
 * Deleting Route Silently
 * @Param  oConn     Object
 * @Param  iVal    Integer
 * @Author Girish joshi
 */
MyWorkflow.prototype.deleteRoute = function(oConn,iVal){

     var sourceObjType = oConn.sourcePort.parentNode.type;
     var targetObjType = oConn.targetPort.parentNode.type;
     var rou_uid       = oConn.id;

     //Setting Condition for VALID ROUTE_UID present in Route Table
     //For start and gateway event, we dont have entry in ROUTE table
     if(rou_uid != '' && !sourceObjType.match(/Gateway/) && !sourceObjType.match(/Start/) && !targetObjType.match(/Gateway/))
            var urlparams = '?action=deleteRoute&data={"uid":"'+ rou_uid +'"}';

    //Deleting route for Start event and also deleting start event
    else if(sourceObjType.match(/Start/)){
        var targetObj = oConn.targetPort.parentNode;  //Task
        var tas_uid   = targetObj.id;
        var tas_start = 'FALSE';
        urlparams = '?action=saveStartEvent&data={"tas_uid":"'+tas_uid+'","tas_start":"'+tas_start+'"}';
    }
      Ext.Ajax.request({
                    url: "processes_Ajax.php"+ urlparams,
                    success: function(response) {
                        if(iVal == 0)
                           oConn.workflow.getCommandStack().execute(new CommandDelete(oConn));
                    },
                    failure: function(){
                        Ext.Msg.alert ('Failure');
                    }
                });
}

/**
 * Deleting Event
 * @Param  eventObj     Object
 * @Author Girish joshi
 */
MyWorkflow.prototype.deleteEvent = function(eventObj){

     var event_uid = eventObj.id;
     if(event_uid != '')
        {
            var urlparams = '?action=deleteEvent&data={"uid":"'+ event_uid +'"}';
            Ext.Ajax.request({
                    url: "processes_Ajax.php"+ urlparams,
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
    switch (shape) {
    case 'bpmnTask':
        workflow.currentSelection.actiontype = 'deleteTask';
        break;
    case 'bpmnSubProcess':
        workflow.currentSelection.actiontype = 'deleteSubProcess';
        break;
    case 'bpmnAnnotation':
        workflow.currentSelection.actiontype = 'deleteText';
        break;
    case 'bpmnEventEmptyStart':
        workflow.currentSelection.actiontype = 'saveStartEvent';
        break;
    case 'bpmnEventEmptyEnd':
        workflow.currentSelection.actiontype = 'deleteEndEvent';
        var currentObj = workflow.currentSelection;
        var ports = currentObj.getPorts();
        var len =ports.data.length;
    

        //Get all the connection of the shape
        var conn = new Array();
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
                            if(conn[i].data[j].targetPort.parentNode.id == currentObj.id){
                                    route = conn[i].data[j];
                                    break;
                            }
                        }
                    }
             }
        if(typeof route != 'undefined')
             workflow.deleteRoute(route,1);
        break;
  }
  if(shape.match(/Gateway/))
        {
            workflow.currentSelection.actiontype = 'deleteGateway';
            workflow.deleteShape(workflow.currentSelection);
        }
  else
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
   var figures = workflow.getDocument().getFigures();

   var lines=workflow.getLines();
   var size=lines.getSize();

   if(typeof workflow.limitFlag == 'undefined')
       workflow.limitFlag = 0;

   var zoomFactor = 0.2;
   var figSize = figures.getSize();
   for(f = 0;f<figures.getSize();f++){
   var fig = figures.get(f);
   var width = fig.getWidth();
   var height = fig.getHeight();
   var xPos = fig.getX();
   var yPos = fig.getY();
  
   if(sType == 'in')
     {
        if(fig.type.match(/Event/) || fig.type.match(/Gateway/))
          {
              width  += zoomFactor*25;
              height += zoomFactor*25;
              workflow.zoomWidth  = width;
              workflow.zoomHeight = height;
          }
        else if(fig.type.match(/Annotation/)) {
             width  += zoomFactor*100;
             height += zoomFactor*100;
             workflow.zoomAnnotationWidth  = width;
             workflow.zoomAnnotationHeight = height;
          }
        else
          {
             width  += zoomFactor*100;
             height += zoomFactor*100;
             workflow.zoomTaskWidth  = width;
             workflow.zoomTaskHeight = height;
          }
          ++workflow.limitFlag;
       fig.setPosition(xPos + zoomFactor*xPos,yPos + zoomFactor*yPos);
     }
    else if(sType == 'out' && workflow.limitFlag > 0)
     {
       if(fig.type.match(/Event/) || fig.type.match(/Gateway/) )
          {
              width  -= zoomFactor*25;
              height -= zoomFactor*25;
              workflow.zoomWidth  = width;
              workflow.zoomHeight = height;
          }
        else if(fig.type.match(/Annotation/)) {
             width  -= zoomFactor*100;
             height -= zoomFactor*100;
             workflow.zoomAnnotationWidth  = width;
             workflow.zoomAnnotationHeight = height;
          }
        else
          {
             width  -= zoomFactor*100;
             height -= zoomFactor*100;
             workflow.zoomTaskWidth  = width;
             workflow.zoomTaskHeight = height;
          }
          --workflow.limitFlag;
       fig.setPosition(xPos - zoomFactor*xPos,yPos - zoomFactor*yPos);
     }
   fig.setDimension(width,height);
   if(fig.type == 'bpmnTask')
      {
        workflow.redrawTaskText(fig,sType);
      }
      else if(fig.type == 'bpmnAnnotation')
      {
        workflow.redrawAnnotationText(fig,sType);
      }
    }
}


MyWorkflow.prototype.redrawTaskText = function(fig,sType)
{
  fig.bpmnText.clear();
  //len = Math.ceil(this.input.value.length/16);
  var len = fig.getWidth() / 18;
  if (len >= 6) {
      //len = 1.5;
      var padleft = 0.12 * fig.getWidth();
      var padtop = 0.40 * fig.getHeight() -3;
      fig.rectWidth = fig.getWidth() - 2 * padleft;
    }
    else {
      padleft = 0.1 * fig.getWidth();
      padtop = 0.09 * fig.getHeight() -3;
      fig.rectWidth = fig.getWidth() - 2 * padleft;
    }
  var rectheight = fig.getHeight() - padtop -7;

  if(typeof fig.size == 'undefined')
    fig.size  = fig.bpmnText.ftSz.substr(0,fig.bpmnText.ftSz.length-2);
  else
    fig.size = fig.size;

  if(sType == 'in' && sType != '')
    fig.size = parseInt(fig.size) + 4;
  else if(sType == 'out' && sType != '')
    fig.size = parseInt(fig.size) - 4;

   //Setting font minimum limit
   if(fig.size < 11)
      fig.size = 11;
   eval("fig.bpmnText.setFont('verdana','"+fig.size+"px', Font.PLAIN)");
   fig.bpmnText.drawStringRect(fig.taskName, padleft, padtop, fig.rectWidth, rectheight, 'center');
   fig.bpmnText.paint();
}

MyWorkflow.prototype.redrawAnnotationText = function(fig,sType)
{
  fig.bpmnText.clear();
  var text = fig.annotationName;
  len = Math.ceil(text.length/16);
  if(text.length < 19)
  {
    len = 1.5;
    if(text.length > 9)
      fig.rectWidth = text.length*8;
    else
      fig.rectWidth = 48;
  }
  else
    fig.rectWidth = 150;
  if(typeof fig.size == 'undefined')
    fig.size  = fig.bpmnText.ftSz.substr(0,fig.bpmnText.ftSz.length-2);
  else
    fig.size = fig.size;

  if(sType == 'in')
      fig.size = parseInt(fig.size) + 4;
  else
      fig.size = parseInt(fig.size) - 4;

  //Setting font minimum limit i.e. 11px
  if(fig.size < 11)
    fig.size = 11;

  //workflow.zoomAnnotationTextSize = fig.size;
  eval("fig.bpmnText.setFont('verdana','"+fig.size+"px', Font.PLAIN)");
  fig.bpmnText.drawStringRect(text,20,20,fig.rectWidth,'left');
  fig.bpmnText.paint();
}
