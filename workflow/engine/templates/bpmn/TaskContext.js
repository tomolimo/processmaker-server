TaskContext=function(id){
    Workflow.call(this,id);
};
TaskContext.prototype=new Workflow;
TaskContext.prototype.type="TaskContext";


TaskContext.prototype.editTaskSteps = function(_3252){
    var taskExtObj = new TaskContext();
    var ProcMapObj= new ProcessMapContext();
    var taskId  = _3252.scope.workflow.currentSelection.id;
    
    var stepsFields = Ext.data.Record.create([
        {
            name: 'STEP_TITLE',
            type: 'string'
        },
        {
            name: 'STEP_UID',
            type: 'string'
        },
        {
            name: 'STEP_TYPE_OBJ',
            type: 'string'
        },
        {
            name: 'STEP_CONDITION',
            type: 'string'
        },
        {
            name: 'STEP_POSITION',
            type: 'string'
        },
        {
            name: 'STEP_MODE',
            type: 'string'
        },
        {
            name: 'STEP_UID_OBJ',
            type: 'string'
        }
   ]);

    var editor = new Ext.ux.grid.RowEditor({
        saveText: _('ID_UPDATE')
        });

    var btnAdd = new Ext.Button({
        id: 'btnAdd',
        text: _('ID_ASSIGN'),
        iconCls: 'button_menu_ext ss_sprite ss_add',
        handler: function(){
            var User = grid.getStore();
            var e = new stepsFields({
                 //STEP_TITLE: User.data.items[0].data.STEP_TITLE,
                 STEP_UID       : '',
                 STEP_TYPE_OBJ  : '',
                 STEP_CONDITION : '',
                 STEP_POSITION  : '',
                 STEP_MODE      : '',
                 STEP_UID_OBJ   : ''
            });

            if(availableSteps.data.items.length == 0)
                PMExt.notify( _('ID_STATUS') , _('ID_STEPS_UNAVAILABLE') );
            else
            {
                editor.stopEditing();
                taskSteps.insert(0, e);
                grid.getView().refresh();
                //grid.getSelectionModel().selectRow(0);
                editor.startEditing(0, 0);
            }
        }
    });

    var btnRemove = new Ext.Button({
        id: 'btnRemove',
        text: _('ID_REMOVE'),
        iconCls: 'button_menu_ext ss_sprite ss_pencil',
        handler: function (s) {
            editor.stopEditing();
            var s = grid.getSelectionModel().getSelections();
            for(var i = 0, r; r = s[i]; i++){

                //First Deleting step from Database using Ajax
                var stepUID      = r.data.STEP_UID;
                var stepPosition = r.data.STEP_POSITION;

                //if STEP_UID is properly defined (i.e. set to valid value) then only delete the row
                //else its a BLANK ROW for which Ajax should not be called.
                if(r.data.STEP_UID != "")
                {
                    Ext.Ajax.request({
                      url   : '../steps/steps_Delete.php',
                      method: 'POST',
                      params: {
                            TASK            : taskId,
                            STEP_UID        : stepUID,
                            STEP_POSITION   : stepPosition
                      },
                      success: function(response) {
                          PMExt.notify( _('ID_STATUS') , _('ID_STEP_REMOVED') );
                        //Secondly deleting from Grid
                        taskSteps.remove(r);
                        //Reloading store after removing steps
                        taskSteps.reload();
                      }
                    });
                }
                else
                   taskSteps.remove(r);
            }
        }
    });

    var tb = new Ext.Toolbar({
        items: [btnAdd, btnRemove]
        });

        // create the Data Store of all Steps that are already been assigned to a task
    var taskSteps = new Ext.data.JsonStore({
        root         : 'data',
        totalProperty: 'totalCount',
        idProperty   : 'gridIndex',
        remoteSort   : true,
        fields       : stepsFields,
        proxy        : new Ext.data.HttpProxy({
        url   : 'bpmn/proxyExtjs?tid='+taskId+'&action=getAssignedSteps'
        })
      });
    taskSteps.load({params:{start : 0 , limit : 10 }});

    // create the Data Store of all Steps that are not been assigned to a task i.e available steps
    var availableSteps = new Ext.data.JsonStore({
         root            : 'data',
         url             : 'bpmn/proxyExtjs?pid='+pro_uid+'&tid='+taskId+'&action=getAvailableSteps',
         totalProperty   : 'totalCount',
         idProperty      : 'gridIndex',
         remoteSort      : false,
         autoLoad        : true,
         fields          : stepsFields

     });
    availableSteps.load();

    var btnStepsCondition = new Ext.Button({
        id: 'btnCondition',
        text: _('ID_CONDITION'),
        handler: function (s) {
                    workflow.taskUID         = taskId
                    workflow.variablesAction = 'grid';
                    workflow.variable        = '@@',
                    workflow.gridField       = 'STEP_CONDITION';
                    var rowSelected = conditionGrid.getSelectionModel().getSelections();
                    if(rowSelected == '')
                        workflow.gridObjectRowSelected = conditionGrid;
                    else
                        workflow.gridObjectRowSelected = rowSelected;

                    var rowData = ProcMapObj.ExtVariables();
                    console.log(rowData);
                }
        })
     

    var toolbar = new Ext.Toolbar({
        items: [btnStepsCondition]
        });
         //availableSteps.load();
    var conditionGrid =  new Ext.grid.GridPanel({
        store           : taskSteps,
        id              : 'conditiongrid',
        loadMask        : true,
        loadingText     : 'Loading...',
        frame           : false,
        autoHeight      : false,
        //enableDragDrop  : true,
        layout          : 'form',
        tbar            : toolbar,
        //ddGroup         : 'firstGridDDGroup',
        clicksToEdit    : 1,
        minHeight       :400,
        height          :400,
        plugins         : [editor],
        columns         : [{
                            id: 'STEP_TITLE',
                            header: _('ID_STEPS'),
                            dataIndex: 'STEP_TITLE',
                            width: 280,
                            editor: new Ext.form.TextField({
                            })
                            },
                            {
                            id: 'STEP_CONDITION',
                            header: _('ID_CONDITION'),
                            dataIndex: 'STEP_CONDITION',
                            width: 250,
                            //editable: true,
                            editor: new Ext.form.TextField({
                                    editable  : true
                                })
                            }
                           ]
                        });

        
                 
    var grid =  new Ext.grid.GridPanel({
        store       : taskSteps,
        id          : 'mygrid',
        loadMask    : true,
        loadingText : 'Loading...',
        //renderTo    : 'cases-grid',
        frame       : false,
        autoHeight  : false,
        //enableDragDrop   : true,
        //ddGroup     : 'firstGridDDGroup',
        clicksToEdit: 1,
        minHeight   :400,
        height      :340,
        layout      : 'form',
        plugins     : [editor],
        columns     : [{
                        id: 'STEP_TITLE',
                        header: _('ID_STEPS'),
                        dataIndex: 'STEP_TITLE',
                        width: 200,
                        sortable: true,
                        editor: new Ext.form.ComboBox({
                                xtype        : 'combo',
                                fieldLabel   : 'Users_groups',
                                store        :  availableSteps,
                                displayField : 'STEP_TITLE'  ,
                                valueField   : 'STEP_TITLE',
                                scope        :  this,
                                triggerAction: 'all',
                                emptyText    : 'Select Step',
                                allowBlank   : false,
                                onSelect: function(record, index){
                                    var User = grid.getStore();
                                    
                                    if(typeof _3252.scope.workflow.currentrowIndex == 'undefined')
                                        var selectedrowIndex = '0';
                                    else
                                        selectedrowIndex     = _3252.scope.workflow.currentrowIndex;    //getting Index of the row that has been edited

                                     //User.data.items[0].data.STEP_TITLE= record.data.STEP_TITLE;
                                     User.data.items[selectedrowIndex].data.STEP_UID        = record.data.STEP_UID;
                                     User.data.items[selectedrowIndex].data.STEP_TYPE_OBJ   =record.data.STEP_TYPE_OBJ;
                                     User.data.items[selectedrowIndex].data.STEP_CONDITION  =record.data.STEP_CONDITION;
                                     User.data.items[selectedrowIndex].data.STEP_POSITION   =record.data.STEP_POSITION;
                                     User.data.items[selectedrowIndex].data.STEP_UID_OBJ    =record.data.STEP_UID_OBJ;
                                     User.data.items[selectedrowIndex].data.STEP_MODE       =record.data.STEP_MODE;

                                     this.setValue(record.data[this.valueField || this.displayField]);
                                     this.collapse();
                                  }
                            })
                    },
                     {
                        id: 'STEP_MODE',
                        header: _('STEP_MODE'),
                        dataIndex: 'STEP_MODE',
                        width: 100,
                        sortable: true,
                        editor: new Ext.form.ComboBox ({
                            editable : false,
                            triggerAction: 'all',
                            lazyRender:true,
                            allowBlank   : false,
                            emptyText    : 'Select Mode',
                            mode: 'local',
                            scope: this,
                            store: new Ext.data.ArrayStore({
                                id: 0,
                                fields: [
                                    'STEP_MODE',
                                    'STEP_MODE'
                                ],
                                data: [['EDIT', 'Edit'], ['VIEW', 'View']]
                            }),
                            valueField: 'STEP_MODE',
                            defaultValue: 'EDIT',
                            displayField: 'STEP_MODE',
                            onSelect: function(record, index){
                                    var User = grid.getStore();
                                    User.data.items[0].data.STEP_MODE=record.data.STEP_MODE;
                                    this.setValue(record.data[this.valueField || this.displayField]);
                                    this.collapse();
                                  }
                          })
                     },
                     {
                         sortable: false,
                         renderer: function()
                            {
                                return String.format("<a href='../dynaforms/dynaforms_Editor?PRO_UID={0}&DYN_UID={1}'>Edit</a>",pro_uid,taskId);
                            }
                     }
                    ],
            sm: new Ext.grid.RowSelectionModel({
                singleSelect: true,
                listeners: {
                     rowselect: function(smObj, rowIndex, record) {
                        _3252.scope.workflow.currentrowIndex = rowIndex;
                    }
               }
            }),
            stripeRows: true,
            viewConfig: {forceFit: true},
            tbar: tb,
            bbar: new Ext.PagingToolbar({
            pageSize: 10,
            store: taskSteps,
            displayInfo: true,
            displayMsg: 'Displaying Steps {0} - {1} of {2}',
            emptyMsg: "No Steps to display",
            items:[]
            })
         });

      editor.on({
          scope: this,
          afteredit: function(roweditor, changes, record, rowIndex) {

             var stepUIDObj    = record.data.STEP_UID_OBJ;
             var stepTypeObj   = record.data.STEP_TYPE_OBJ;
             var stepMode      = record.data.STEP_MODE;

             Ext.Ajax.request({
              url   : '../steps/steps_Save.php',
              method: 'POST',
              params: {
                    sProcess    : pro_uid,
                    sTask       : taskId,
                    sType       : stepTypeObj,
                    sUID        : stepUIDObj,
                    sMode       : stepMode
              },
              success: function(response) {
                  PMExt.notify( _('ID_STATUS') , _('ID_STEP_ASSIGNED') );
                  }
            });
            //availableSteps.reload();
            //Deleting previously assigned step on updating/replacing with new step.
            if(changes != '' && typeof record.json != 'undefined')
            {
             var stepUID       = record.json.STEP_UID;
             var stepPosition  = record.json.STEP_POSITION;

             Ext.Ajax.request({
              url   : '../steps/steps_Delete.php',
              method: 'POST',
              params: {
                    TASK            : taskId,
                    STEP_UID        : stepUID,
                    STEP_POSITION   : stepPosition
              },
              success: function(response) {
                //Ext.MessageBox.alert ('Status','Step has been updated successfully.');
               }
             });
            }
            
          }
        });



    //Getting triggers data using stepTriggers function
    var treeGrid = taskExtObj.stepTriggers(_3252);
    treeGrid.render(document.body);

    var taskStepsTabs = new Ext.FormPanel({
        labelWidth: 100,
        monitorValid : true,
        width     : 850,
        height    : 400,
        items:
            {
            xtype:'tabpanel',
            activeTab: 0,
            defaults:{
                autoHeight:true
            },
            items:[{
                title:_('ID_STEPS'),
                layout:'fit',
                defaults: {
                    width: 400
                },
                listeners: {
                    tabchange: function(tabPanel,newTab){
                            taskSteps.reload();
                      }
                },
                items:[grid]
            },{
                title:_('ID_CONDITION'),
                layout:'fit',
                defaults: {
                    width: 400
                },
                listeners: {
                    tabchange: function(tabPanel,newTab){
                            taskSteps.reload();
                      }
                },
                items:[conditionGrid]
            },{
                title:_('ID_TRIGGERS'),
                layout:'form',
                defaults: {
                    width: 400
                },
                items:[treeGrid]
            }]
        }
    });

    taskStepsTabs.render(document.body);
    _3252.scope.workflow.taskStepsTabs = taskStepsTabs;

    var window = new Ext.Window({
        title: _('ID_STEPS_OF'),
        collapsible: false,
        maximizable: false,
        width: 770,
        height: 380,
        minWidth: 200,
        minHeight: 150,
        layout: 'fit',
        plain: true,
        buttonAlign: 'center',
        items: taskStepsTabs
        
    });
    window.show();
}

TaskContext.prototype.editUsers= function()
{
    var taskExtObj = new TaskContext();
    var taskId     = workflow.currentSelection.id;
    var userFields = Ext.data.Record.create([
            {
                name: 'LABEL',
                type: 'string'
            },
            {
                name: 'TU_TYPE',
                type: 'string'
            },
            {
                name: 'TU_RELATION',
                type: 'string'
            },
            {
                name: 'TAS_UID',
                type: 'string'
            },
            {
                name: 'USR_UID',
                type: 'string'
            }
            ]);
    var editor = new Ext.ux.grid.RowEditor({
            saveText: _('ID_UPDATE')
        });

       
    var btnAdd = new Ext.Button({
        id: 'btnAdd',
        text: _('ID_ASSIGN'),
        iconCls: 'button_menu_ext ss_sprite ss_add',
        handler: function(){
            var User = grid.getStore();
            var e = new userFields({
                 TAS_UID    : '',
                 TU_TYPE    : '',
                 USR_UID    : '',
                 TU_RELATION: ''
            });
            //storeUsers.reload();
            if(storeUsers.data.items.length == 0)
                PMExt.notify( _('ID_STATUS') , _('ID_USERS_UNAVAILABLE') );
            else
            {
                editor.stopEditing();
                taskUsers.insert(0, e);
                grid.getView().refresh();
                editor.startEditing(0, 0);
            }
        }
    });

    var btnRemove = new Ext.Button({
        id: 'btnRemove',
        text: _('ID_REMOVE'),
        iconCls: 'application_delete',
        handler: function (s) {
            editor.stopEditing();
            var s = grid.getSelectionModel().getSelections();
            for(var i = 0, r; r = s[i]; i++){

                //First Deleting assigned users from Database
                var user_TURel      = r.data.TU_RELATION;
                var userUID         = r.data.USR_UID;
                var user_TUtype     = r.data.TU_TYPE;
                var urlparams       = '?action=ofToAssign&data={"TAS_UID":"'+taskId+'","TU_RELATION":"'+user_TURel+'","USR_UID":"'+userUID+'","TU_TYPE":"'+user_TUtype+'"}';

                //if USR_UID is properly defined (i.e. set to valid value) then only delete the row
                //else its a BLANK ROW for which Ajax should not be called.
                 if(r.data.USR_UID != "")
                     {
                        Ext.Ajax.request({
                        url   : 'bpmn/processes_Ajax.php' +urlparams ,
                        success: function(response) {
                             PMExt.notify( _('ID_STATUS') , _('ID_USERS_REMOVED') );
                         //Secondly deleting from Grid
                          taskUsers.remove(r);

                          //Reloading available user store
                          taskUsers.reload();
                      }
                        });
                     }
                 else
                     taskUsers.remove(r);
            }
        }
    });

    var tb = new Ext.Toolbar({
            items: [btnAdd, btnRemove]
        });

        // create the Data Store of users that are already assigned to a task
    var taskUsers = new Ext.data.JsonStore({
            root         : 'data',
            totalProperty: 'totalCount',
            idProperty   : 'gridIndex',
            remoteSort   : true,
            fields       : userFields,
            proxy: new Ext.data.HttpProxy({
              url: 'bpmn/proxyExtjs?pid='+pro_uid+'&tid='+taskId+'&action=getAssignedUsersList'
            })
          });
   taskUsers.setDefaultSort('LABEL', 'asc');
   

   // create the Data Store of users that are not assigned to a task
    var storeUsers = new Ext.data.JsonStore({
                 root            : 'data',
                 url             : 'bpmn/proxyExtjs?tid='+taskId+'&action=getAvailableUsersList',
                 totalProperty   : 'totalCount',
                 idProperty      : 'gridIndex',
                 remoteSort      : false, //true,
                 autoLoad        : true,
                 fields          : userFields
              });
     storeUsers.load();

    var grid = new Ext.grid.GridPanel({
        store: taskUsers,
        id : 'mygrid',
        loadMask: true,
        loadingText: 'Loading...',
        //renderTo: 'cases-grid',
        frame: false,
        autoHeight:false,
        clicksToEdit: 1,
        minHeight:400,
        height   :320,
        layout: 'fit',
        plugins: [editor],
        cm: new Ext.grid.ColumnModel({
              defaults: {
                  width: 200,
                  sortable: true
              },
              columns: [
                new Ext.grid.RowNumberer(),
                {
                    id: 'LABEL',
                    header:_('ID_GROUP_USER'),
                    dataIndex: 'LABEL',
                    width: 100,
                    editor: new Ext.form.ComboBox({
                            xtype: 'combo',
                            fieldLabel: 'Users_groups',
                            hiddenName: 'number',
                            store        : storeUsers,
                            displayField : 'LABEL'  ,
                            valueField   : 'LABEL',
                            name         : 'LABEL',
                            triggerAction: 'all',
                            emptyText: 'Select User or Group',
                            allowBlank: false,
                             onSelect: function(record, index){
                                var User = grid.getStore();

                                if(typeof workflow.currentrowIndex == 'undefined')
                                        var selectedrowIndex = '0';
                                else
                                        selectedrowIndex     = workflow.currentrowIndex;    //getting Index of the row that has been edited

                                 //User.data.items[0].data.LABEL= record.data.LABEL;
                                 User.data.items[selectedrowIndex].data.TAS_UID      = record.data.TAS_UID;
                                 User.data.items[selectedrowIndex].data.TU_TYPE      = record.data.TU_TYPE;
                                 User.data.items[selectedrowIndex].data.USR_UID      = record.data.USR_UID;
                                 User.data.items[selectedrowIndex].data.TU_RELATION  = record.data.TU_RELATION;

                                 this.setValue(record.data[this.valueField || this.displayField]);
                                 this.collapse();
                              }
                        })
                },
                ]
        }),
        sm: new Ext.grid.RowSelectionModel({
                singleSelect: true,
                listeners: {
                     rowselect: function(smObj, rowIndex, record) {
                         workflow.currentrowIndex = rowIndex;
                    }
               }
            }),
         
        stripeRows: true,
        viewConfig: {forceFit: true},
        bbar: new Ext.PagingToolbar({
            pageSize: 10,
            store: taskUsers,
            displayInfo: true,
            displayMsg: 'Displaying Users {0} - {1} of {2}',
            emptyMsg: "No Users to display",
            items:[]
        }),
        tbar: tb
        });

        taskUsers.load({params:{start : 0 , limit : 10 }});

        editor.on({
          scope: this,
          afteredit: function(roweditor, changes, record, rowIndex) {
            var taskId      = record.data.TAS_UID;
            var userId      = record.data.USR_UID;
            var tu_Type     = record.data.TU_TYPE;
            var tu_Relation = record.data.TU_RELATION;
            var urlparams   = '?action=assign&data={"TAS_UID":"'+taskId+'","USR_UID":"'+userId+'","TU_TYPE":"'+tu_Type+'","TU_RELATION":"'+tu_Relation+'"}';

            Ext.Ajax.request({
                    url: 'bpmn/processes_Ajax.php' +urlparams ,
                    success: function (response) {      // When saving data success
                        PMExt.notify( _('ID_STATUS') , _('ID_USER_ASSIGNED') );
                        },
                    failure: function () {      // when saving data failed
                        PMExt.notify( _('ID_STATUS') , _('ID_USER_SAVE_FAIL') );
                        }
                 });

            //Updating the user incase if already assigned user has been replaced by other user
            if(changes != '' && typeof record.json != 'undefined')
            {
                var user_TURel      = record.json.TU_RELATION;
                var userUID         = record.json.USR_UID;
                var user_TUtype     = record.json.TU_TYPE;
                urlparams           = '?action=ofToAssign&data={"TAS_UID":"'+taskId+'","TU_RELATION":"'+user_TURel+'","USR_UID":"'+userUID+'","TU_TYPE":"'+user_TUtype+'"}';
                Ext.Ajax.request({
                      url   : 'bpmn/processes_Ajax.php' +urlparams ,
                      success: function(response) {
                          //Ext.MessageBox.alert ('Status','User has been updated successfully.');
                      }
                    });
            }
            storeUsers.reload();
          }
        });

    var panel = new Ext.Panel({
        id: 'panel',
        //renderTo: Ext.getBody(),
        items: [grid]
    });

    var window = new Ext.Window({
        title: _('ID_USERS_GROUPS'),
        collapsible: false,
        maximizable: false,
        width: 400,
        height: 350,
        minWidth: 200,
        minHeight: 150,
        //layout: 'fit',
        plain: true,
        buttonAlign: 'center',
        items: panel
     });
    window.show();
}


TaskContext.prototype.editTaskProperties= function()
{
    var ProcMapObj = new ProcessMapContext();
    var taskExtObj = new TaskContext();
    var taskId  = workflow.currentSelection.id;
    var oPmosExt = new pmosExt();
    
    var fieldsToToggle = new Array();

    var taskPropertiesTabs = new Ext.FormPanel({
        labelWidth  : 140,
        //border      : false,
        monitorValid : true,
        // store       : taskDetails,
        //url         : 'proxyTaskPropertiesDetails.php',
        width       : 600,
        items: [{
            xtype:'tabpanel',
            activeTab: 0,
            bodyStyle   : 'padding:5px 0 0 5px;',
            defaults:{
              labelWidth : 140,
              height : 300
            },
            items:[
                {
              title:_('ID_DEFINITION'),
              layout:'form',
              defaults: {
                width: 230
              },
              defaultType: 'textfield',
              items: [{
                fieldLabel: _('ID_TITLE'),
                name: 'TAS_TITLE',
                width: 350
              },{
                xtype: 'textarea',
                fieldLabel: _('ID_DESCRIPTION'),
                name: 'TAS_DESCRIPTION',
                allowBlank: true,
                width: 350,
                height : 150
              },{
                xtype: 'fieldset',
                layout:'column',
                border : false,
                width: 550,
                items:[{
                  columnWidth:.7,
                  layout: 'form',
                  border : false,
                  items: [{
                    xtype: 'textfield',
                    labelWidth : 130,
                    fieldLabel: _('ID_VARIABLES_CASE_PRIORITY'),
                    name: 'TAS_PRIORITY_VARIABLE',
                    anchor:'100%'                            
                  }]
                },{
                  columnWidth:.2,
                  layout: 'form',
                  border:false,
                  items: [{
                    xtype:'button',
                    title: ' ',
                    text: '@@',
                    name: 'selectorigin',
                    handler: function (s) {
                      workflow.variablesAction = 'form';
                      workflow.fieldName         = 'TAS_PRIORITY_VARIABLE' ;
                      workflow.formSelected    = taskPropertiesTabs;
                      var rowData = ProcMapObj.ExtVariables();
                      console.log(rowData);
                    }
                  }]
                }]
               },{
                xtype: 'checkbox',
                fieldLabel: _('ID_START_TASK'),
                name: 'TAS_START',
                 checked:workflow.checkStartingTask
              }]
            },{
              title:_('ID_ASSIGNMENT_RULES'),
              layout     : 'form',
              defaults: {
                width: 260
              },
              items: [{
                xtype: 'radiogroup',
                //id:    'assignType',
                fieldLabel: _('ID_CASES_ASSIGNED_BY'),
                itemCls: 'x-check-group-alt',
                columns: 1,
                items: [{
                  boxLabel: _('ID_CYCLIC_ASSIGNMENT'),
                  //id: 'BALANCED',
                  name: 'TAS_ASSIGN_TYPE',
                  inputValue: 'BALANCED',
                  checked: false
//                  listeners: {
//                      'check':{
//                          fn: function(){
//                              Ext.getCmp("staticMI").hide();
//                              Ext.getCmp("cancelMI").hide();
//                              Ext.getCmp("evaluate").hide();
//                          }
//                      }
//                  }
                },{
                  boxLabel: _('ID_MANUAL_ASSIGNMENT'),
                 // id: 'MANUAL',
                  name: 'TAS_ASSIGN_TYPE',
                  inputValue: 'MANUAL',
                  checked:false
//                  listeners: {
//                      'check':{
//                          fn: function(){
//                              Ext.getCmp("staticMI").hide();
//                              Ext.getCmp("cancelMI").hide();
//                              Ext.getCmp("evaluate").hide();
//                          }
//                      }
//                  }
                },{
                  boxLabel: _('ID_VALUE_BASED'),
                  //id:'EVALUATE',
                  name: 'TAS_ASSIGN_TYPE',
                  inputValue: 'EVALUATE',
                  checked:false
//                listeners: {
//                    'check':{
//                        fn: function(){
                           
                      
//                                       var fields = workflow.taskPropertiesTabs.items.items[0].items.items[1].items.items;
//                          var fieldsToToggle = new Array();
//                          fieldsToToggle = [fields[1].items.items[0].items.items[0]];
//                          oPmosExt.toggleFields(fieldsToToggle,true);
//                      }
//                    }
//                  }
                },{
                  boxLabel: _('ID_REPORTS_TO'),
                  //id:'REPORT_TO',
                  name: 'TAS_ASSIGN_TYPE',
                  inputValue: 'REPORT_TO',
                  checked:false
//                  listeners: {
//                      'check':{
//                          fn: function(){
//                              Ext.getCmp("staticMI").hide();
//                              Ext.getCmp("cancelMI").hide();
//                              Ext.getCmp("evaluate").hide();
//                          }
//                      }
//                  }
                },{
                  boxLabel: _('ID_SELF_SERVICE'),
                  //id:'SELF_SERVICE',
                  name: 'TAS_ASSIGN_TYPE',
                  inputValue: 'SELF_SERVICE',
                  checked:false
//                  listeners: {
//                      'check':
//                          {fn: function(){
//                              //fieldsToToggle = [fields[0],fields[1],fields[2],fields[3],fields[4],fields[5],fields[6],fields[7],fields[8]]
//                              Ext.getCmp("staticMI").hide();
//                              Ext.getCmp("cancelMI").hide();
//                              Ext.getCmp("evaluate").hide();
//                          }
//                      }
//                  }
                },{
                  boxLabel: _('ID_STATIC_PARTIAL_JOIN_MULTIPLE_INSTANCES'),
                  //id:'STATIC_MI',
                  name: 'TAS_ASSIGN_TYPE',
                  inputValue: 'STATIC_MI',
                  checked:false
//                  listeners: {
//                    'check':{
//                      fn: function(){
//                        Ext.getCmp("staticMI").show();
//                        Ext.getCmp("cancelMI").show();
//                        Ext.getCmp("evaluate").hide();
//                      }
//                    }
//                  }
                },{
                  boxLabel: _('ID_CANCEL_PARTIAL_JOIN_MULTIPLE_INSTANCE'),
                  //id   : 'CANCEL_MI',
                  name : 'TAS_ASSIGN_TYPE',
                  inputValue: 'CANCEL_MI',
                  checked:false
//                  listeners: {
//                    'check':{
//                      fn: function(){
//                        Ext.getCmp("staticMI").show();
//                        Ext.getCmp("cancelMI").show();
//                        Ext.getCmp("evaluate").hide();
//                      }
//                    }
//                  }
                }]


                },{
                  xtype: 'fieldset',
                  layout:'column',
                  border:false,
                  width: 550,
                  hidden: true,
                  id: 'evaluate',
                  items:[{
                    columnWidth:.8,
                    layout: 'form',
                    border:false,
                    items: [{
                      xtype: 'textfield',
                      fieldLabel: _('ID_VARIABLES_VALUE_ASSIGNMENT'),
                      name: 'TAS_ASSIGN_VARIABLE',
                      anchor:'100%'
                    }]
                    },{
                      columnWidth:.2,
                      layout: 'form',
                      border:false,
                      items: [{
                        xtype:'button',
                        title: ' ',
                        text: '@@',
                        name: 'selectorigin'
                      }]
                    }
                  ]
                },{
                  xtype: 'fieldset',
                  layout:'column',
                  border:false,
                  width: 550,
                  hidden: true,
                  id: 'staticMI',
                  items:[{
                    columnWidth:.8,
                    layout: 'form',
                    border:false,
                    items: [{
                      xtype: 'textfield',
                      fieldLabel: _('ID_VARIABLES_NO_INSTANCES'),
                      name: 'TAS_MI_INSTANCE_VARIABLE',
                      anchor:'100%'
                    }]
                  },{
                    columnWidth:.2,
                    layout: 'form',
                    border:false,
                    items: [{
                      xtype:'button',
                      title: ' ',
                      text: '@@',
                      name: 'selectorigin'
                        }]
                    }]
                },{
                    xtype: 'fieldset',
                    layout:'column',
                    border:false,
                    width: 550,
                    hidden: true,
                    id: 'cancelMI',
                    items:[{
                      columnWidth:.8,
                      layout: 'form',
                      border:false,
                      items: [{
                        xtype: 'textfield',
                        fieldLabel: _('ID_VARIABLES_INSTANCES_TO _COMPLETE'),
                        name: 'TAS_MI_COMPLETE_VARIABLE',
                        anchor:'100%'
                      }]
                    },{
                      columnWidth:.2,
                      layout: 'form',
                      border:false,
                      items: [{
                        xtype:'button',
                        title: ' ',
                        text: '@@',
                        name: 'selectorigin'
                      }]
                    }]
                }]
            },{
              title:'Timing Control Rules',
              layout:'form',
              defaults: {
                width: 260
              },
              defaultType: 'textfield',
              items: [{
                xtype: 'checkbox',
                boxLabel: _('ID_USER_DEFINED_TIMING_CONTROL'),
                name: 'TAS_TRANSFER_FLY',
                checked: 'TAS_TRANSFER_FLY',
                labelWidth: 100,
                listeners: {
                  /**Listeners for hiding all the fields
                  * under "Timing Control Rules" tabs
                  * when user clicks on 'Allow user defined timing control' checkbox
                  **/
                  check : function(a,checked,c) {
                    if(checked == true)
                      Ext.getCmp("userDefinedTiming").hide();
                    else
                      Ext.getCmp("userDefinedTiming").show();
                  }
                }
              },{
                xtype: 'fieldset',
                layout:'form',
                border:false,
                width: 550,
                hidden: false,
                id: 'userDefinedTiming',

                items:[{
                  xtype: 'textfield',
                  fieldLabel: _('ID_TASK_DURATION'),
                  name: 'TAS_DURATION',
                  width : 100,
                  allowBlank:false
                },{
                  width:          100,
                  xtype:          'combo',
                  mode:           'local',
                  triggerAction:  'all',
                  forceSelection: true,
                  editable:       false,
                  fieldLabel:     _('ID_TIME_UNIT'),
                  name:           'TAS_TIMEUNIT',
                  hiddenName:     'TAS_TIMEUNIT',
                  displayField:   'name',
                    valueField:     'value',
                    store:          new Ext.data.JsonStore({
                    fields : ['name', 'value'],
                    data   : [
                      {
                        name : 'Days',
                        value: 'Days'
                      },{
                        name : 'Hours',
                        value: 'Hours'
                      }]
                    })
                },{
                  width:          120,
                  xtype:          'combo',
                  mode:           'local',
                  //value:          '- None -',
                  triggerAction:  'all',
                  forceSelection: true,
                  editable:       false,
                  fieldLabel:     _('ID_COUNT_DAYS'),
                  name:           'TAS_TYPE_DAY',
                  hiddenName:     'TAS_TYPE_DAY',
                  displayField:   'name',
                  //value: 'TAS_TYPE_DAY',
                  valueField:     'value',
                  store:          new Ext.data.JsonStore({
                    fields : ['name', 'value'],
                    data   : [
                    {
                        name : 'Work Days',
                        value: '1'
                    },

                    {
                        name : 'Calendar Days',
                        value: '2'
                    },
                    ]
                  })
                },{
                  width:          100,
                  xtype:          'combo',
                  mode:           'local',
                  value:          'Default',
                  forceSelection: true,
                  triggerAction:  'all',
                  editable:       false,
                  fieldLabel:     _('ID_CALENDAR'),
                  name:           'TAS_CALENDAR',
                  hiddenName:     'TAS_CALENDAR',
                  displayField:   'name',
                  valueField:     'value',
                  store:          new Ext.data.JsonStore({
                    fields : ['name', 'value'],
                    data   : [
                    {
                      name : '- None-',
                      value: '- None-'
                    },

                    {
                      name : 'Default',
                      value: 'Default'
                    },
                    ]
                  })
                }]
              }]
            },{
              title:_('ID_PERMISSION'),
              layout:'form',
              defaults: {
                  width: 260
              },
              defaultType: 'textfield',
              labelWidth: 200,
              items: [{
                  xtype: 'checkbox',
                  //id: 'ADHOC',
                  fieldLabel: _('ID_ALLOW_ARBITARY_TRANSFER'),
                  inputValue:'ADHOC',
                  checked: false,
                  name: 'TAS_TYPE'
              }]
            },{
              title:_('ID_CASE_LABELS'),
              layout:'form',
              defaults: {
                width: 600
              },
              defaultType: 'textfield',
              labelWidth: 70,
              items: [{
                xtype: 'fieldset',
                layout:'column',
                border:false,
                width: 600,
                items:[{
                  columnWidth:.8,
                  layout: 'form',
                  border:false,
                  items: [{
                      xtype: 'textarea',
                      fieldLabel: _('ID_CASE_TITLE'),
                      //id: 'caseTitle',
                      name: 'TAS_DEF_TITLE',
                      height : 120,
                      //value: _5625.scope.workflow.taskDetails.TAS_ASSIGN_VARIABLE
                      anchor:'100%'
                  }]
                },{
                    columnWidth:.2,
                    layout: 'form',
                    border:false,
                    items: [{
                        xtype:'button',
                        title: ' ',
                        text: '@#',
                        name: 'selectCaseTitle',
                        handler: function (s) {
                                workflow.variablesAction = 'form';
                                workflow.variable        = '@%23',
                                workflow.fieldName         = 'TAS_DEF_TITLE' ;
                                workflow.formSelected    = taskPropertiesTabs;
                                var rowData = ProcMapObj.ExtVariables();
                                console.log(rowData);
                               }
                       }]
                }]
              },{
                  xtype: 'fieldset',
                  layout:'column',
                  border:false,
                  width: 600,
                  items:[{
                      columnWidth:.8,
                      layout: 'form',
                      border:false,
                      items: [{
                          xtype: 'textarea',
                          //id: 'caseDescription',
                          fieldLabel: _('ID_CASE_DESCRIPTION'),
                          name: 'TAS_DEF_DESCRIPTION',
                          height : 120,
                          anchor:'100%'

                      }]
                  },{
                      columnWidth:.2,
                      layout: 'form',
                      border:false,
                      items: [{
                          xtype:'button',
                          title: ' ',
                          text: '@#',
                          name: 'selectCaseDesc',
                          handler: function (s) {
                            workflow.variablesAction = 'form';
                            workflow.variable = '@%23',
                            workflow.fieldName= 'TAS_DEF_DESCRIPTION' ;
                            workflow.formSelected = taskPropertiesTabs;
                            var rowData = ProcMapObj.ExtVariables();
                            console.log(rowData);
                          }
                      }]
                  }]
              }]
              },{
              title:_('ID_NOTIFICATION'),
              layout:'form',
              defaultType: 'textfield',
              labelWidth: 170,
              items: [{
                xtype: 'checkbox',
                boxLabel: _('ID_NOTIFY_USERS_AFTER_ASSIGN'),
                labelWidth: 100,
                name:   'SEND_EMAIL',
                checked:   'TAS_DEF_MESSAGE_CHECKBOX',
                listeners: {
                  /**Listeners for showing "TAS_DEF_MESSAGE" textarea field
                  * under "Notification" tab
                  * when user clicks on 'After routing notify the next assigned user(s).' checkbox
                  **/
                  check : function(a,checked,c) {
                    if(checked == true)
                      Ext.getCmp("notifyUser").show();
                    else
                      Ext.getCmp("notifyUser").hide();
                  }
               }
              },{
                xtype: 'fieldset',
                id:    'notifyUser',
                border: false,
                defaults: {
                    width: 400
                },
                labelWidth: 50,
                hidden: true,
                items :[{
                  xtype: 'textarea',
                  name: 'TAS_DEF_MESSAGE',
                  width: 400,
                  height : 180
                }]
              }]
            }
            ]
        }]
    });

  workflow.taskPropertiesTabs = taskPropertiesTabs;
    
    
  //Loading Task Details into the form
  taskPropertiesTabs.form.load({
        url:'bpmn/proxyExtjs.php?tid='+taskId+'&action=getTaskPropertiesList',
        method:'GET',
        waitMsg:'Loading',
        success:function(form, action) {
                alert(action.result.data.TAS_START);
                if(action.result.data.TAS_START== true)
                           workflow.checkStartingTask = 'on';
                       else
                           workflow.checkStartingTask = 'off';

  //To load the values of the selecte radio button in Assignment Rules
                       if(action.result.data.TAS_ASSIGN_TYPE=='BALANCED')
                           form.items.items[4].items[0].checked=true;

                       else  if(action.result.data.TAS_ASSIGN_TYPE=='MANUAL')
                        {
                            form.items.items[4].items[1].checked=true;
                            //Ext.getCmp(ID).setValue(true);
                            //taskPropertiesTabs.getForm().findField('TAS_ASSIGN_TYPE').setValue(true);
                        }
                       else if(action.result.data.TAS_ASSIGN_TYPE=='EVALUATE')
                            {
                                form.items.items[4].items[2].checked=true;
                                taskPropertiesTabs.getForm().findField('TAS_ASSIGN_VARIABLE').show();
                            }

                       else if(action.result.data.TAS_ASSIGN_TYPE=='REPORT_TO')
                           form.items.items[4].items[3].checked=true;

                       else  if(action.result.data.TAS_ASSIGN_TYPE=='SELF_SERVICE')
                           {form.items.items[4].items[4].checked=true;
                           Ext.getCmp("staticMI").hide();
                           Ext.getCmp("cancelMI").hide();}

                       else if(action.result.data.TAS_ASSIGN_TYPE=='STATIC_MI')
                            {
                                form.items.items[4].items[5].checked=true;
                                Ext.getCmp("staticMI").show();
                                Ext.getCmp("cancelMI").show();
                                Ext.getCmp("evaluate").hide();
                            }

                       else  if(action.result.data.TAS_ASSIGN_TYPE=='CANCEL_MI')
                           {
                               form.items.items[4].items[6].checked=true;
                               Ext.getCmp("staticMI").show();
                               Ext.getCmp("cancelMI").show();
                               Ext.getCmp("evaluate").hide();
                           }

                      if(action.result.data.TAS_TYPE == 'ADHOC')
                           form.items.items[13].checked=false;
                       else
                            form.items.items[13].checked=true;


                         if(action.result.data.TAS_ASSIGN_TYPE == 'EVALUATE')
                            form.findField('TAS_ASSIGN_VARIABLE').show();

                        
                     
       },
        failure:function(form, action) {
           PMExt.notify( _('ID_STATUS') , _('ID_FAILURE') );
        }
    });

    taskPropertiesTabs.render(document.body);
    workflow.taskPropertiesTabs = taskPropertiesTabs;

    var window = new Ext.Window({
        title: _('ID_TASK'),
        collapsible: false,
        maximizable: false,
        width: 600,
        height: 370,
        minWidth: 300,
        minHeight: 150,
        layout: 'fit',
        plain: true,
        buttonAlign: 'center',
        items: taskPropertiesTabs,
        buttons: [{
            text: _('ID_SAVE'),
            formBind    :true,
            handler: function(){
                //var getstore = taskPropertiesTabs.getStore();
                //var getData = getstore.data.items;
                taskExtObj.saveTaskProperties();
                 //window.hide();

            }
        },{
            text: _('ID_CANCEL'),
            handler: function(){
                // when this button clicked,
                window.hide();
            }
        }]
        });
    window.show();

}

TaskContext.prototype.saveTaskProperties= function()
{
                 var saveTaskform = workflow.taskPropertiesTabs.getForm().getValues();
                 var taskId = workflow.currentSelection.id;
                 var tas_start = saveTaskform['TAS_START'];
                 var tas_type = saveTaskform['TAS_TYPE'];
                 var tas_transfer_fly = saveTaskform['TAS_TRANSFER_FLY'];
                 var send_email = saveTaskform['SEND_EMAIL'];
                 saveTaskform['TAS_UID'] = taskId;
                 alert(tas_start);

                 //Checking checkbox fields
                 if(typeof tas_start != 'undefined' && tas_start != ''){
                     if(tas_start == 'on')
                         saveTaskform['TAS_START'] = 'TRUE';
                 }
                 else
                         saveTaskform['TAS_START'] = 'FALSE';

                 if(typeof tas_transfer_fly != 'undefined' && tas_transfer_fly != ''){
                     if(tas_transfer_fly == 'on')
                         saveTaskform['TAS_TRANSFER_FLY'] = 'TRUE';
                 }
                 else
                         saveTaskform['TAS_TRANSFER_FLY'] = 'FALSE';

                 if(typeof send_email != 'undefined' && send_email != ''){
                     if(send_email == 'on')
                         saveTaskform['SEND_EMAIL'] = 'TRUE';
                 }
                 else
                         saveTaskform['SEND_EMAIL'] = 'FALSE';

                 if(typeof tas_type != 'undefined' && tas_type != ''){
                     if(tas_type == 'on')
                         saveTaskform['TAS_TYPE'] = 'ADHOC';
                 }
                 else
                         saveTaskform['TAS_TYPE'] = 'NORMAL';

                 var object_data = Ext.util.JSON.encode(saveTaskform);

                 Ext.Ajax.request({
                        url: '../tasks/tasks_Ajax.php' ,
                    success: function (response) {      // When saving data success
                        PMExt.notify( _('ID_STATUS') , _('ID_TASK_PROPERTIES_SAVE') );
                        },
                    failure: function () {      // when saving data failed
                        PMExt.notify( _('ID_STATUS') , _('ID_ERROR_TASK_SAVE') );
                        },
                    params: {
                        functions:'saveTaskData',
                        oData:object_data
                    }
                 });
}

TaskContext.prototype.stepTriggers = function()
    {
     var taskId  = workflow.currentSelection.id;
     var ProcMapObj= new ProcessMapContext();
     var triggersFields = Ext.data.Record.create([
                {
                    name: 'CON_VALUE',
                    type: 'string'
                },
                {
                    name: 'ST_TYPE',
                    type: 'string'
                },
                {
                    name: 'STEP_UID',
                    type: 'string'
                },
                {
                    name: 'TRI_UID',
                    type: 'string'
                },
                {
                    name: 'ST_POSITION',
                    type: 'string'
                },
                {
                    name: 'TRI_TITLE',
                    type: 'string'
                },
                {
                    name: 'ST_CONDITION',
                    type: 'string'
                }
        ]);

    var triggerEditor = new Ext.ux.grid.RowEditor({
        saveText: _('ID_UPDATE')
    });

    var root = new Ext.tree.AsyncTreeNode({text: 'treeRoot',id:'0'});
    var tree = new Ext.tree.TreePanel({
            //renderTo    : 'cases-grid',
            dataUrl     : 'get-triggers-tree.php?tid='+taskId,
            border      : false,
            rootVisible : false,
            height      : 320,
            width       : 230,
            useArrows   : false,
            autoScroll  : true,
            animate     : true
         });
    tree.setRootNode(root);
    root.expand(true);

    tree.on('click', function (node){
         if(node.isLeaf()){
             var sStepUID = node.attributes.id;
             workflow.selectedStepUID = sStepUID;

             stepsTriggers.on({
                beforeload: {
                        fn: function (store, options) {
                                // use {@link Ext.data.HttpProxy#setUrl setUrl} to change the URL for *just* this request.
                                var link = 'bpmn/proxyExtjs?tid='+taskId+'&stepid='+sStepUID+'&action=getAssignedStepTriggers';
                                store.proxy.setUrl(link, true);
                        }
                }
             });

            availableTriggers.on({
                beforeload: {
                        fn: function (store, options) {
                                // use {@link Ext.data.HttpProxy#setUrl setUrl} to change the URL for *just* this request.
                                var link = 'bpmn/proxyExtjs?pid='+pro_uid+'&tid='+taskId+'&stepid='+sStepUID+'&action=getAvailableStepTriggers';
                                store.proxy.setUrl(link, true);
                        }
                }
             });

             triggerGrid.store.load();
             availableTriggers.load();
             //availableTriggers.reload();
             triggerGrid.show();
         }
         else
             triggerGrid.hide();
    });


    var addBtn = new Ext.Button({
        id: 'addBtn',
        text: _('ID_ADD'),
        iconCls: 'button_menu_ext ss_sprite ss_add',
        handler: function(){
            //var User = triggerGrid.getStore();
            var e1 = new triggersFields({
                 //STEP_TITLE: User.data.items[0].data.STEP_TITLE,
                 CON_VALUE       : '',
                 ST_TYPE         : '',
                 STEP_UID        : '',
                 TRI_UID         : '',
                 ST_POSITION     : '',
                 ST_CONDITION    : '',
                 TRI_TITLE       : ''
            });

            if(availableTriggers.data.items.length == 0)
                PMExt.notify( _('ID_STATUS') , _('ID_TRIGGERS_UNAVAILABLE') );
            else
            {
                triggerEditor.stopEditing();
                stepsTriggers.insert(0, e1);
                triggerGrid.getView().refresh();
                //triggerGrid.getSelectionModel().selectRow(0);
                triggerEditor.startEditing(0, 0);
            }

        }
    });

    var removeBtn = new Ext.Button({
        id: 'removeBtn',
        text: _('ID_REMOVE'),
        iconCls: 'button_menu_ext ss_sprite ss_delete',
        handler: function (s) {
            triggerEditor.stopEditing();
            var s = triggerGrid.getSelectionModel().getSelections();
            for(var i = 0, r; r = s[i]; i++){

                //First Deleting step from Database using Ajax
                var stepUID      = r.data.STEP_UID;
                var sTrigger     = r.data.TRI_UID;
                var sType        = r.data.ST_TYPE;
                var iPosition    = r.data.ST_POSITION;
                var urlparams    = '?action=ofToAssignTrigger&sStep=' + stepUID + '&sTrigger=' + sTrigger + '&sType=' + sType + '&iPosition=' + iPosition

                //if STEP_UID is properly defined (i.e. set to valid value) then only delete the row
                //else its a BLANK ROW for which Ajax should not be called.
                if(r.data.STEP_UID != "")
                {
                    Ext.Ajax.request({
                      url   : '../steps/steps_Ajax.php' + urlparams,
                      success: function(response) {
                          PMExt.notify( _('ID_STATUS') , _('ID_TRIGGER_REMOVE') );
                       //Secondly deleting from Grid
                        stepsTriggers.remove(r);

                        availableTriggers.reload();
                      }
                    });
                }
                else
                    stepsTriggers.remove(r);
            }
        }
    });


    var btnTriggerCondition = new Ext.Button({
      //id: 'btnCondition',
      text: _('ID_CONDITION'),
      handler: function (s) {
                workflow.variablesAction = 'grid';
                workflow.variable = '@@',
                workflow.gridField = 'ST_CONDITION';
                var rowSelected = triggerGrid.getSelectionModel().getSelections();
                if(rowSelected == '')
                    workflow.gridObjectRowSelected = triggerGrid;
                else
                    workflow.gridObjectRowSelected = rowSelected;
                //var rowSelected = Objectsgrid;
                //workflow.gridObject = Objectsgrid;
                var rowData = ProcMapObj.ExtVariables();
            }
    });

    var toolBar = new Ext.Toolbar({
        items: [addBtn, removeBtn, btnTriggerCondition]
    });


    // create the Data Store of users that are already assigned to a task
    var stepsTriggers = new Ext.data.JsonStore({
        root            : 'data',
        url             : 'bpmn/proxyExtjs?tid='+taskId+'&action=',//+'&stepid='+workflow.selectedStepUID,
        totalProperty   : 'totalCount',
        idProperty      : 'gridIndex',
        remoteSort      : true,
        fields          : triggersFields
      });
      //taskUsers.setDefaultSort('LABEL', 'asc');
      stepsTriggers.load({params:{start : 0 , limit : 5 }});

     var availableTriggers = new Ext.data.JsonStore({
         root            : 'data',
         url             : 'bpmn/proxyExtjs?pid='+pro_uid+'&tid='+taskId+'&action=',//+'&stepid='+workflow.selectedStepUID,
         totalProperty   : 'totalCount',
         idProperty      : 'gridIndex',
         remoteSort      : false, //true,
         autoLoad        : true,
         fields          : triggersFields
      });

      //availableTriggers.load();

      var triggerGrid = new Ext.grid.GridPanel({
        store: stepsTriggers,
        id : 'triggerGrid',
        //cm: cm,
        loadMask: true,
        loadingText: 'Loading...',
        //renderTo: 'cases-grid',
        frame: false,
        autoHeight:false,
        hidden    :true,
        clicksToEdit: 1,
        width       : 450,
        minHeight:400,
        height   :320,
        layout: 'fit',
        plugins: [triggerEditor],
        columns: [
                new Ext.grid.RowNumberer(),
                {
                    id: 'blank',
                    hidden : true,
                    //width:0,
                    editable: true,
                    editor: new Ext.form.TextField({
                        allowBlank: true
                    })
                },
                {
                    id: 'CON_VALUE',
                    header: _('ID_TRIGGERS'),
                    dataIndex: 'CON_VALUE',
                    //width: 200,
                    sortable: true,
                    editor: new Ext.form.ComboBox({
                            id           : 'available',
                            xtype        : 'combo',
                            fieldLabel   : 'Users_groups',
                            hiddenName   : 'number',
                            store        : availableTriggers,
                            displayField : 'CON_VALUE',
                            valueField   : 'CON_VALUE',
                            name         : 'CON_VALUE',
                            scope        :  this,
                            triggerAction: 'all',
                            emptyText    : 'Select Triggers',
                            allowBlank   : false,
                            onSelect     : function(record, index){
                                var triggerStore  = triggerGrid.getStore();

                                if(typeof workflow.currentRowTrigger == 'undefined')
                                        var selectedrowIndex = '0';
                                else
                                        selectedrowIndex     = workflow.currentRowTrigger;    //getting Index of the row that has been edited

                                 //User.data.items[0].data.CON_VALUE                 = record.data.CON_VALUE;
                                 triggerStore.data.items[selectedrowIndex].data.ST_TYPE      = record.data.ST_TYPE;
                                 triggerStore.data.items[selectedrowIndex].data.STEP_UID     = record.data.STEP_UID;
                                 triggerStore.data.items[selectedrowIndex].data.TRI_UID      = record.data.TRI_UID;
                                 triggerStore.data.items[selectedrowIndex].data.ST_POSITION  = record.data.ST_POSITION;
                                 triggerStore.data.items[selectedrowIndex].data.TRI_TITLE    = record.data.TRI_TITLE;

                                 workflow.currentrowIndex = '0';
                                 this.setValue(record.data[this.valueField || this.displayField]);
                                 this.collapse();
                              }
                        })
                },
                {
                    //id: 'STEP_TITLE',
                    header: _('ID_CONDITION'),
                    dataIndex: 'ST_CONDITION',
                    //width: 200,
                    editable: true,
                    editor: new Ext.form.TextField({
                        allowBlank: true
                    })
                }
                
                ],
        sm: new Ext.grid.RowSelectionModel({
                singleSelect: true,
                listeners: {
                     rowselect: function(smObj, rowIndex, record) {
                        workflow.currentRowTrigger = rowIndex;
                    }
               }
            }),
        stripeRows: true,
        viewConfig: {forceFit: true},
        bbar: new Ext.PagingToolbar({
            pageSize: 5,
            store: stepsTriggers,
            displayInfo: true,
            displayMsg: 'Displaying Step Tiggers {0} - {1} of {2}',
            emptyMsg: "No Step Tiggers to display",
            items:[]
        }),
        tbar: toolBar
        });

        triggerEditor.on({
          scope: this,
          afteredit: function(roweditor, changes, record, rowIndex) {

             var stepUID     = record.data.STEP_UID;
             var triUID      = record.data.TRI_UID;
             var sType       = record.data.ST_TYPE;
             var sCondition  = record.data.ST_CONDITION;
             //var urlparams   = '?action=assignTrigger&data={"STEP_UID":"'+stepUID+'","TRI_UID":"'+triUID+'","ST_TYPE":"'+sType+'","ST_CONDITION":""}';
             Ext.Ajax.request({
              url   : '../steps/steps_Ajax.php',
              method: 'POST',
              params:{
                  action       : 'assignTrigger',
                  STEP_UID     : stepUID,
                  TRI_UID      : triUID,
                  ST_TYPE      : sType,
                  ST_CONDITION : sCondition
              },
              success: function(response) {
                  PMExt.notify( _('ID_STATUS') , _('ID_TRIGGER_ASSIGN') );
                  tree.getLoader().dataUrl = 'get-triggers-tree.php?tid='+taskId;
                  tree.getLoader().load(tree.root);
              }
            });

          //Deleting previously assigned trigger on updating/replacing with new trigger.
          if(changes != '' && typeof record.json != 'undefined' )
            {
                stepUID          = record.json.STEP_UID;
                var sTrigger     = record.json.TRI_UID;
                sType            = record.json.ST_TYPE;
                var iPosition    = record.json.ST_POSITION;
                var condition    = record.json.ST_CONDITION;

                var urlparams    = '?action=ofToAssignTrigger&sStep=' + stepUID + '&sTrigger=' + sTrigger + '&sType=' + sType + '&iPosition=' + iPosition

                Ext.Ajax.request({
                  url   : '../steps/steps_Ajax.php' + urlparams,
                  success: function(response) {
                    //Ext.MessageBox.alert ('Status','Trigger has been updated successfully.');
                  }
                });
            }
            availableTriggers.reload();
          }
        });

    var treeGrid = new Ext.FormPanel({
        frame: false,
        monitorValid : true,
        labelAlign: 'left',
        width:  750,
        height: 500,
        layout: 'column',    
        items: [{
            columnWidth: 0.4,
            layout: 'fit',
            items: [tree]
        },{
            columnWidth: 0.6,
            xtype: 'fieldset',
            //labelWidth: 120,
            title:_('ID_ASSIGN_TRIGGERS'),
            //defaults: {width: 140, border:false},
            autoHeight: true,
            border: false,
            items: [triggerGrid]
        }]
    });
    
    return treeGrid;
}

TaskContext.prototype.editUsersAdHoc= function()
{
    var taskExtObj = new TaskContext();
    var taskId  = workflow.currentSelection.id;
    var userFields = Ext.data.Record.create([
            {
                name: 'LABEL',
                type: 'string'
            },{
                name: 'TU_TYPE',
                type: 'string'
            },{
                name: 'TU_RELATION',
                type: 'string'
            },{
                name: 'TAS_UID',
                type: 'string'
            },{
                name: 'USR_UID',
                type: 'string'
            }
            ]);
    var editor = new Ext.ux.grid.RowEditor({
            saveText: _('ID_UPDATE')
        });
    var taskUsers = new Ext.data.JsonStore({
            root         : 'data',
            totalProperty: 'totalCount',
            idProperty   : 'gridIndex',
            remoteSort   : true,
            fields       : userFields,
            proxy: new Ext.data.HttpProxy({
              url: 'bpmn/proxyExtjs?pid='+pro_uid+'&tid='+taskId+'&action=assignedUsers'
            })
          });
          //taskUsers.setDefaultSort('LABEL', 'asc');
          taskUsers.load({params:{start : 0 , limit : 10 }});

         // create the Data Store of users that are not assigned to a task
         var storeUsers = new Ext.data.JsonStore({
                 root            : 'data',
                 url             : 'bpmn/proxyExtjs?tid='+taskId+'&action=availableUsers',
                 totalProperty   : 'totalCount',
                 idProperty      : 'gridIndex',
                 remoteSort      : false, //true,
                 autoLoad        : true,
                 fields          : userFields
              });


        var btnAdd = new Ext.Button({
            id: 'btnAdd',
            text: _('ID_ASSIGN'),
            iconCls: 'button_menu_ext ss_sprite ss_add',
            handler: function(){
                var User = grid.getStore();
                var e = new userFields({
                     //LABEL: 'Select User or Group',
                     //LABEL: User.data.items[0].data.LABEL,
                     TAS_UID: '',
                     TU_TYPE: '',
                     USR_UID: '',
                     TU_RELATION: ''
                });

                //storeUsers.reload();
                if(storeUsers.data.items.length == 0)
                    PMExt.notify( _('ID_STATUS') , _('ID_USERS_UNAVAILABLE') );
                else
                {
                    editor.stopEditing();
                    taskUsers.insert(0, e);
                    grid.getView().refresh();
                    //grid.getSelectionModel().selectRow(0);
                    editor.startEditing(0, 0);
                    
                }
            
        }

        });

        var btnRemove = new Ext.Button({
            id: 'btnRemove',
            text: _('ID_REMOVE'),
            iconCls: 'button_menu_ext ss_sprite ss_delete',
            handler: function (s) {
                editor.stopEditing();
                var s = grid.getSelectionModel().getSelections();
                for(var i = 0, r; r = s[i]; i++){

                    //First Deleting assigned users from Database
                    var user_TURel      = r.data.TU_RELATION;
                    var userUID         = r.data.USR_UID;
                    var user_TUtype     = r.data.TU_TYPE;
                    var urlparams       = '?action=ofToAssign&data={"TAS_UID":"'+taskId+'","TU_RELATION":"'+user_TURel+'","USR_UID":"'+userUID+'","TU_TYPE":"'+user_TUtype+'"}';

                    //if USR_UID is properly defined (i.e. set to valid value) then only delete the row
                    //else its a BLANK ROW for which Ajax should not be called.
                     if(r.data.USR_UID != "")
                     {
                        Ext.Ajax.request({
                          url   : 'bpmn/processes_Ajax.php' +urlparams ,
                          /*method: 'POST',
                          params: {
                                functions       : 'ofToAssign',
                                TAS_UID         : taskId,
                                TU_RELATION     : user_TURel,
                                USR_UID         : userUID,
                                TU_TYPE         : user_TUtype

                          },*/
                          success: function(response) {
                              PMExt.notify( _('ID_STATUS') , _('ID_USERS_REMOVED') );
                              //Secondly deleting from Grid
                              taskUsers.remove(r);
                              //Reloading available user store
                              taskUsers.reload();
                          }
                        });
                     }
                     else
                         taskUsers.remove(r);
                }
            }
        });

        var tb = new Ext.Toolbar({
            items: [btnAdd, btnRemove]
        });

        // create the Data Store of users that are already assigned to a task
        var grid = new Ext.grid.GridPanel({
        store: taskUsers,
        id : 'mygrid',
        //cm: cm,
        loadMask: true,
        loadingText: 'Loading...',
        //renderTo: 'cases-grid',
        frame: false,
        autoHeight:false,
        clicksToEdit: 1,
        minHeight:400,
        height   :330,
        layout: 'fit',
        plugins: [editor],
        columns: [
                new Ext.grid.RowNumberer(),
                {
                    id: 'LABEL',
                    header: _('ID_GROUP_USER'),
                    dataIndex: 'LABEL',
                    width: 100,
                    sortable: true,
                    editor: new Ext.form.ComboBox({
                            xtype: 'combo',
                            fieldLabel: 'Users_groups',
                            hiddenName: 'number',
                            store        : storeUsers,
                            displayField : 'LABEL',
                            valueField   : 'LABEL',
                            name         : 'LABEL',
                            triggerAction: 'all',
                            emptyText: 'Select User or Group',
                            allowBlank: false,
                             onSelect: function(record, index){
                                var User = grid.getStore();

                                if(typeof workflow.currentrowIndex == 'undefined')
                                        var selectedrowIndex = '0';
                                else
                                        selectedrowIndex     = workflow.currentrowIndex;    //getting Index of the row that has been edited

                                 //User.data.items[0].data.LABEL= record.data.LABEL;
                                 User.data.items[selectedrowIndex].data.TAS_UID      = record.data.TAS_UID;
                                 User.data.items[selectedrowIndex].data.TU_TYPE      = record.data.TU_TYPE;
                                 User.data.items[selectedrowIndex].data.USR_UID      = record.data.USR_UID;
                                 User.data.items[selectedrowIndex].data.TU_RELATION  = record.data.TU_RELATION;

                                 this.setValue(record.data[this.valueField || this.displayField]);
                                 this.collapse();
                              }
                        })
                }
                ],
        sm: new Ext.grid.RowSelectionModel({
                singleSelect: true,
                listeners: {
                     rowselect: function(smObj, rowIndex, record) {
                         workflow.currentrowIndex = rowIndex;
                    }
               }
            }),
        stripeRows: true,
        viewConfig: {forceFit: true},
        bbar: new Ext.PagingToolbar({
            pageSize: 10,
            store: taskUsers,
            displayInfo: true,
            displayMsg: 'Displaying Users {0} - {1} of {2}',
            emptyMsg: "No Users to display",
            items:[]
        }),
        tbar: tb
        });

        storeUsers.load();

        editor.on({
          scope: this,
          afteredit: function(roweditor, changes, record, rowIndex) {

            var taskId      = record.data.TAS_UID;
            var userId      = record.data.USR_UID;
            var tu_Type     = record.data.TU_TYPE;
            var tu_Relation = record.data.TU_RELATION;
            ///var urlparams   = '?action=assign&data={"TAS_UID":"'+taskId+'","USR_UID":"'+userId+'","TU_TYPE":"'+tu_Type+'","TU_RELATION":"'+tu_Relation+'"}';

            Ext.Ajax.request({
                    url: '../users/users_Ajax.php',
                    METHOD:'post',
                    success: function (response) {      // When saving data success
                        PMExt.notify( _('ID_STATUS') , _('ID_USER_ASSIGNED') );
                    },
                    params:{
                        functions : 'assign',
                        TAS_UID  :  taskId,
                        USR_UID : userId,
                        TU_TYPE : tu_Type,
                        TU_RELATION:tu_Relation

                    },
                    failure: function () {      // when saving data failed
                        PMExt.notify( _('ID_STATUS') , _('ID_USER_SAVE_FAIL') );
                        }
                 });

            //Updating the user incase if already assigned user has been replaced by other user
            if(changes != '' && typeof record.json != 'undefined')
            {
                var user_TURel      = record.json.TU_RELATION;
                var userUID         = record.json.USR_UID;
                var user_TUtype     = record.json.TU_TYPE;
                //urlparams           = '?action=ofToAssign&data={"TAS_UID":"'+taskId+'","TU_RELATION":"'+user_TURel+'","USR_UID":"'+userUID+'","TU_TYPE":"'+user_TUtype+'"}';
                Ext.Ajax.request({
                      url   : '../users/users_Ajax.php',
                      method: 'POST',
                      success: function(response) {
                          PMExt.notify( _('ID_STATUS') , _('ID_USER_ASSIGNED') );
                          //Ext.MessageBox.alert ('Status','User has been updated successfully.');
                      },
                      params:{
                        functions  : 'ofToAssign',
                        TAS_UID    :  taskId,
                        USR_UID    : userId,
                        TU_TYPE    : tu_Type,
                        TU_RELATION:tu_Relation

                    }
                    });
            }
            //storeUsers.reload();
          }
        });

        var panel = new Ext.Panel({
            id: 'panel',
            //renderTo: Ext.getBody(),
            items: [grid]
        });

        var window = new Ext.Window({
        title: _('ID_USER_GROUPS_ADHOC'),
        collapsible: false,
        maximizable: false,
        width: 400,
        height: 360,
        minWidth: 200,
        minHeight: 150,
        layout: 'fit',
        plain: true,
        buttonAlign: 'center',
        items: panel
        });
    window.show();
}

/**
 * ExtJs Form of SubProcess Properties
 * @Param  Shape Object
 * @Author Safan Maredia
 */
TaskContext.prototype.editSubProcessProperties= function(_3525)
{
        var taskId  = workflow.currentSelection.id;
        //Variables Out Grid
        var subProcessFields = Ext.data.Record.create([
            {name: 'SP_UID',type: 'string'},
            {name: 'TAS_UID',type: 'string'},
            {name: 'PRO_PARENT',type: 'string'},
            {name: 'PRO_UID',type: 'string'},
            {name: 'PRO_TITLE',type: 'string'},
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
            saveText: _('ID_UPDATE')
        });
    var editorIn = new Ext.ux.grid.RowEditor({
            saveText: _('ID_UPDATE')
        });

    //Variable out grid configuration starts here
    var btnAddOut = new Ext.Button({
            id: 'btnAddOut',
            text: _('ID_ASSIGN_VARIABLES_OUT'),
            iconCls: 'button_menu_ext ss_sprite ss_add',
            handler: function(){
                var storeData = variableOutGrid.getStore();
                 //STEP_TITLE: storeData.data.items[0].data.STEP_TITLE,
                var e = new subProcessFields({
                     SP_UID         : '',
                     PRO_PARENT     : '',
                     SP_SYNCHRONOUS : '',
                     TAS_PARENT     : '',
                     TASKS          : '',
                     VAR_OUT1       : '',
                     VAR_OUT2     : ''
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
        text: _('ID_REMOVE_VARIABLES_OUT'),
        iconCls: 'button_menu_ext ss_sprite ss_delete',
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
        text: 'ID_ASSIGN_VARIABLES_IN',
        iconCls: 'button_menu_ext ss_sprite ss_add',
        handler: function(){
            var e = new subProcessFields({
                 SP_UID         : '',
                 PRO_PARENT     : '',
                 SP_SYNCHRONOUS : '',
                 TAS_PARENT     : '',
                 VAR_IN1        : '',
                 VAR_IN2	: ''
            });

                editorIn.stopEditing();
                variablesInStore.insert(0, e);
                variableInGrid.getView().refresh();
                editorIn.startEditing(0, 0);
        }
    });

    var btnRemoveIn = new Ext.Button({
        id: 'btnRemoveIn',
        text: 'ID_REMOVE_VARIABLES_IN',
        iconCls: 'button_menu_ext ss_sprite ss_delete',
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
               url   : 'bpmn/proxyExtjs?pid='+pro_uid+'&action=getSubProcessProperties&tid='+taskId+'&type=0' //type=0 specifies Variables Out (Asynchronous)
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
               url   : 'bpmn/proxyExtjs?pid='+pro_uid+'&action=getSubProcessProperties&tid='+taskId+'&type=1'  //type=1 specifies Variables In (Synchronous)
        })
      });
      //taskUsers.setDefaultSort('LABEL', 'asc');
    variablesInStore.load();

    var processListStore = new Ext.data.JsonStore({
        root         : 'data',
        totalProperty: 'totalCount',
        idProperty   : 'gridIndex',
        remoteSort   : true,
        fields       : subProcessFields,
        proxy        : new Ext.data.HttpProxy({
               url   : '../processes/processesList'
        })
      });
    processListStore.load();

    var variableOutGrid =  new Ext.grid.GridPanel({
        store       : variablesOutStore,
        id          : 'mygrid',
        loadMask    : true,
        loadingText : 'Loading...',
        //renderTo    : 'cases-grid',
        frame       : false,
        autoHeight  : true,
        autoScroll  : true,
        clicksToEdit: 1,
        layout      : 'form',
        plugins     : [editorOut],
        columns     : [{
                        id       : 'VAR_OUT1',
                        name     : 'VAR_OUT1',
                        header   : _('ID_ORIGIN'),
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
                        name      : 'VAR_OUT2',
                        header    : _('ID_TARGET'),
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
        //renderTo    : 'cases-grid',
        frame       : false,
        autoHeight  : true,
        autoScroll  : true,
        clicksToEdit: 1,
        layout      : 'form',
        plugins     : [editorIn],
        columns     : [{
                        id       : 'VAR_IN1',
                        name     : 'VAR_IN1',
                        header   : _('ID_ORIGIN'),
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
                        name      : 'VAR_IN2',
                        header    : _('ID_TARGET'),
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

    var subProcessProperties = new Ext.FormPanel({
    labelWidth  : 110, // label settings here cascade unless overridden
    //frame:true,
    width: 500,
    bodyStyle: 'padding:5px 0 0 5px;',
    autoScroll: true,
    items: [
            {
            xtype:'fieldset',
            title: _('ID_SUBPROCESS'),
            collapsible: false,
            autoHeight:true,
            //width: 600,
            defaultType: 'textfield',
             items:[
                   {
                    id:    'subProcessName',
                    xtype: 'textfield',
                    width:  350,
                    fieldLabel: _('ID_SUBPROCESS_NAME'),
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
                        fieldLabel:     _('ID_PROCESS'),
                        name:           'PRO_TITLE',
                        emptyText    : 'Select Process',
                        displayField:   'PRO_TITLE',
                        valueField:     'PRO_TITLE',
                        store:          processListStore,
                        onSelect: function(record, index){
                                 //processListStore.data.items[0].data.PRO_UID      = record.data.PRO_UID;
                                 Ext.getCmp("SEL_PROCESS").setValue(record.data.PRO_UID);
                                 this.setValue(record.data[this.valueField || this.displayField]);
                                 this.collapse();
                              }
                    },{
                           xtype :'hidden',
                           name :'SEL_PROCESS',
                           id :'SEL_PROCESS'
                       },
                    {
                        width:          150,
                        id   :          'spType',
                        xtype:          'combo',
                        mode:           'local',
                        triggerAction:  'all',
                        forceSelection: true,
                        editable:       false,
                        fieldLabel:     _('ID_TYPE'),
                        name:           'SP_SYNCHRONOUS',
                        hiddenName:     'SP_SYNCHRONOUS',
                        displayField:   'name',
                        valueField:     'value',
                        emptyText    : 'Select Type',
                        store:          new Ext.data.JsonStore({
                                    fields : ['name', 'value'],
                                    data   : [
                                        {name : 'Asynchronous',   value: '0'},
                                        {name : 'Synchronous',   value: '1'},
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
            name :'VAR_OUT1',
            xtype:'fieldset',
            title: _('ID_VARIABLES_OUT'),
            collapsible: false,
            labelAlign: 'top',
             items:[variableOutGrid]
            },
            {
            id   :'variablein',
            name :'VAR_IN1',
            xtype:'fieldset',
            title: _('ID_VARIABLES_IN'),
            //hidden: true,
            collapsible: false,
            labelAlign: 'top',
            items:[variableInGrid]
            }]
    });

    //Loading Task Details into the form
    subProcessProperties.form.load({
        url:'bpmn/proxyExtjs?pid='+pro_uid+'&action=getSubProcessProperties&tid='+taskId+'&type=2',
        method:'GET',
        waitMsg:'Loading....',
        success:function(form, action) {
               var response = action.response.responseText;
               var aData = Ext.util.JSON.decode(response);
               spUID        = aData.data[0].SP_UID;
               proUID        = aData.data[0].PRO_UID;
               proParent    = aData.data[0].PRO_PARENT;
               spSync       = aData.data[0].SP_SYNCHRONOUS;
               tasParent    = aData.data[0].TAS_PARENT;
               tasks        = aData.data[0].TASKS;
           var processName  = aData.data[0].SPROCESS_NAME;
           if(action.result.data[0].SP_SYNCHRONOUS == 0)
               {
                   Ext.getCmp("variablein").hide();
                   form.findField('SP_SYNCHRONOUS').setValue('Asynchronous');
               }
           else
               {
                   Ext.getCmp("variablein").show();
                   form.findField('SP_SYNCHRONOUS').setValue('Synchronous');
               }
          form.findField('PRO_TITLE').setValue(action.result.data[0].PRO_TITLE);
          form.findField('SPROCESS_NAME').setValue(processName);
        },
        failure:function(form, action) {
            PMExt.notify( _('ID_STATUS') , _('ID_LOAD_FAILED') );
            }
     });

    //subProcessProperties.render(document.body);

    var window = new Ext.Window({
    title: _('ID_PROPERTIES'),
    collapsible: false,
    maximizable: false,
    width: 800,
    height: 400,
    layout: 'fit',
    plain: true,
    buttonAlign: 'center',
    items: subProcessProperties,
    buttons: [{
        text: _('ID_SAVE'),
        handler: function(){
            var getForm      = subProcessProperties.getForm().getValues();

            //Getting data from Grid (Variables In and Out)
            var storeOutData    = variableOutGrid.getStore();
            var storeInData    = variableInGrid.getStore();
            var storeOutLength = storeOutData.data.items.length;
            var storeInLength = storeInData.data.items.length;
            var varOut1 = new Array();
            var varOut2 = new Array();
            for(var i=0;i<storeOutLength;i++){
                    varOut1[i] =  storeOutData.data.items[i].data['VAR_OUT1'];
                    varOut2[i] =  storeOutData.data.items[i].data['VAR_OUT2'];
             }
            var varOut = Ext.util.JSON.encode(varOut1)+'|'+Ext.util.JSON.encode(varOut2);

            var varIn1 = new Array();
            var varIn2 = new Array();
            for(var j=0;j<storeInLength;j++){
                    varIn1[j] =  storeInData.data.items[j].data['VAR_IN1'];
                    varIn2[j] =  storeInData.data.items[j].data['VAR_IN2'];
             }
            
            var sProcessUID = getForm.SEL_PROCESS;
            if(sProcessUID == '')
                sProcessUID = proUID;
            
            var sSPNAME      = getForm.SPROCESS_NAME;
            var sSync        = getForm.SP_SYNCHRONOUS;
            if(sSync == 1 || sSync == 'Synchronous')
                {
                    var varIn = Ext.util.JSON.encode(varIn1)+'|'+Ext.util.JSON.encode(varIn2);
                    sSync = 1;
                }
            else
                {
                    sSync = 0;
                    varIn = new Array();
                    varIn[0] = '';
                }
                
            
           Ext.Ajax.request({
              url   : 'bpmn/processes_Ajax.php',
              method: 'POST',
              params: {
                    action          : 'saveSubprocessDetails',
                    SP_UID          : spUID,
                    //TASKS           : tasks,  
                    sProcessUID     : sProcessUID,
                    PRO_UID         : pro_uid,
                    SPROCESS_NAME   : sSPNAME,
                    PRO_PARENT      : proParent,
                    TAS_PARENT	    : tasParent,
                    VAR_OUT         : varOut,
                    VAR_IN          : varIn,
                    SP_SYNCHRONOUS  : sSync
                  },
              success: function(response) {
                  PMExt.notify( _('ID_STATUS') , _('ID_SUBPROCESS_SAVE') );
                  window.close();
                   workflow.currentSelection.bpmnNewText.clear();
                   workflow.currentSelection.bpmnNewText.drawStringRect(sSPNAME,20,5,150,'center');
                   workflow.currentSelection.bpmnNewText.paint();
                   workflow.currentSelection.subProcessName = sSPNAME;
              }
            });
        }
    },{
        text: _('ID_CANCEL'),
        handler: function(){
            // when this button clicked,
            window.close();
        }
    }]
    });
    window.show();

}
