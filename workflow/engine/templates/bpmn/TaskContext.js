TaskContext=function(id){
    Workflow.call(this,id);
};
TaskContext.prototype=new Workflow;
TaskContext.prototype.type="TaskContext";


TaskContext.prototype.editTaskSteps = function(_3252){
    var taskExtObj = new TaskContext();
    var ProcMapObj= new ProcessMapContext();
    var pro_uid = _3252.scope.workflow.getUrlVars();
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
        saveText: 'Update'
        });

    var btnAdd = new Ext.Button({
        id: 'btnAdd',
        text: 'Assign',
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
                 Ext.MessageBox.alert ('Status','No steps are available. All Steps have been already assigned.');
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
        text: 'Remove',
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
                        Ext.MessageBox.alert ('Status','Step has been removed successfully.');
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
        url   : 'proxyExtjs?tid='+taskId+'&action=getAssignedSteps'
        })
      });
      //taskUsers.setDefaultSort('LABEL', 'asc');
      taskSteps.load();

        // create the Data Store of all Steps that are not been assigned to a task i.e available steps
    var availableSteps = new Ext.data.JsonStore({
         root            : 'data',
         url             : 'proxyExtjs?pid='+pro_uid+'&tid='+taskId+'&action=getAvailableSteps',
         totalProperty   : 'totalCount',
         idProperty      : 'gridIndex',
         remoteSort      : false,
         autoLoad        : true,
         fields          : stepsFields

     });

    var btnStepsCondition = new Ext.Button({
        id: 'btnCondition',
        text: 'Condition',
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
                            editable: true,
                            editor: new Ext.form.TextField({
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
        height      :400,
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
            tbar: tb
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
                  Ext.MessageBox.alert ('Status','Step has been assigned successfully.');
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

       // Setup Drop Targets
      // This will make sure we only drop to the  view scroller element
   /* var firstGridDropTargetEl =  grid.getView().scroller.dom;
    var firstGridDropTarget = new Ext.dd.DropTarget(firstGridDropTargetEl, {
                ddGroup    : 'firstGridDDGroup',
                notifyDrop : function(ddSource, e, data){
                                     var records =  ddSource.dragData.selections;
                                     Ext.each(records, ddSource.grid.store.remove, ddSource.grid.store);
                                     grid.store.add(records);
                                     grid.store.commitChanges();
                                     //firstGrid.store.sort('gridIndex', 'ASC');
                                     return true
                                   }
      });
      firstGridDropTarget.addToGroup('firstGridDDGroup');*/

    //Getting triggers data using stepTriggers function
    var treeGrid = taskExtObj.stepTriggers(_3252);
    treeGrid.render(document.body);

    var taskStepsTabs = new Ext.FormPanel({
        labelWidth: 100,
        bodyStyle :'padding:5px 5px 0',
        monitorValid : true,
        width     : 850,
        height    : 500,
        items:
            {
            xtype:'tabpanel',
            activeTab: 0,
            defaults:{
                autoHeight:true,
                bodyStyle:'padding:10px'
            },
            items:[{
                title:_('ID_STEPS'),
                layout:'fit',
                defaults: {
                    width: 400
                },
                items:[grid]
            },{
                title:_('ID_CONDITION'),
                layout:'fit',
                defaults: {
                    width: 400
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
        width: 750,
        height: 380,
        minWidth: 200,
        minHeight: 150,
        layout: 'fit',
        plain: true,
        bodyStyle: 'padding:5px;',
        buttonAlign: 'center',
        items: taskStepsTabs
        
    });
    window.show();
}


TaskContext.prototype.editUsers= function()
{
    var taskExtObj = new TaskContext();
    var pro_uid    = workflow.getUrlVars();
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
            saveText: 'Update'
        });

       
    var btnAdd = new Ext.Button({
        id: 'btnAdd',
        text: _('ID_ASSIGN'),
        iconCls: 'application_add',
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
                 Ext.MessageBox.alert ('Status','No users are available. All users have been already assigned.');
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
        text: _('ID_DE_ASSIGN'),
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
                        url   : 'processes_Ajax.php' +urlparams ,
                        success: function(response) {
                          Ext.MessageBox.alert ('Status','User has been removed successfully.');
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
              url: 'proxyExtjs?pid='+pro_uid+'&tid='+taskId+'&action=getAssignedUsersList'
            })
          });
   taskUsers.setDefaultSort('LABEL', 'asc');
   

   // create the Data Store of users that are not assigned to a task
    var storeUsers = new Ext.data.JsonStore({
                 root            : 'data',
                 url             : 'proxyExtjs?tid='+taskId+'&action=getAvailableUsersList',
                 totalProperty   : 'totalCount',
                 idProperty      : 'gridIndex',
                 remoteSort      : false, //true,
                 autoLoad        : true,
                 fields          : userFields
              });
     //storeUsers.load();
       // paging bar on the bottom
     var paging = new Ext.PagingToolbar({
            pageSize: 10,
            store: taskUsers,
            displayInfo: true,
            displayMsg: 'Displaying users {0} - {1} of {2}',
            emptyMsg: "No users to display"
        });

    var grid = new Ext.grid.GridPanel({
        store: taskUsers,
        id : 'mygrid',
        loadMask: true,
        loadingText: 'Loading...',
        renderTo: 'cases-grid',
        frame: false,
        autoHeight:false,
        clicksToEdit: 1,
        minHeight:400,
        height   :300,
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
                            //store        : storeUsers,
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
        bbar:paging,
        tbar: tb
        });

        taskUsers.load({params:{start:0, limit:10}});

        editor.on({
          scope: this,
          afteredit: function(roweditor, changes, record, rowIndex) {
            var taskId      = record.data.TAS_UID;
            var userId      = record.data.USR_UID;
            var tu_Type     = record.data.TU_TYPE;
            var tu_Relation = record.data.TU_RELATION;
            var urlparams   = '?action=assign&data={"TAS_UID":"'+taskId+'","USR_UID":"'+userId+'","TU_TYPE":"'+tu_Type+'","TU_RELATION":"'+tu_Relation+'"}';

            Ext.Ajax.request({
                    url: 'processes_Ajax.php' +urlparams ,
                    success: function (response) {      // When saving data success
                        Ext.MessageBox.alert ('Status','User has been successfully assigned');
                    },
                    failure: function () {      // when saving data failed
                        Ext.MessageBox.alert ('Status','Failed saving User Assigned to Task');
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
                      url   : 'processes_Ajax.php' +urlparams ,
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
        renderTo: Ext.getBody(),
        items: [grid]
    });

    var window = new Ext.Window({
        title: _('ID_USERS_GROUPS'),
        collapsible: false,
        maximizable: true,
        width: 400,
        height: 350,
        minWidth: 200,
        minHeight: 150,
        //layout: 'fit',
        plain: true,
        bodyStyle: 'padding:5px;',
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
            bodyStyle   : 'padding:10 10 0 ',
            defaults:{
              labelWidth : 140,
              height : 300
            },
            items:[{
                title:_('ID_DEFINITION'),
                layout:'form',
                defaults: {
                    width: 230
                },
                defaultType: 'textfield',
                items: [{
                    fieldLabel: _('ID_TITLE_FIELD'),
                    name: 'TAS_TITLE',
                    //dataIndex: 'TAS_TITLE',
                    //value:  _5625.scope.workflow.taskDetails.TAS_TITLE,
                    allowBlank: false,
                    width: 300
                },{
                    xtype: 'textarea',
                    fieldLabel: _('ID_DESCRIPTION'),
                    name: 'TAS_DESCRIPTION',
                    //dataIndex: 'TAS_DESCRIPTION',
                    //value: _5625.scope.workflow.taskDetails.TAS_DESCRIPTION,
                    allowBlank: true,
                    width: 300,
                    height : 150
                },{
                    xtype: 'fieldset',
                    layout:'column',
                    border:false,
                    width: 550,
                    items:[{
                        columnWidth:.6,
                        layout: 'form',
                        border:false,
                        items: [{
                            xtype: 'textfield',
                            //id: 'priorityVariable',
                            fieldLabel: _('ID_VARIABLES_CASE_PRIORITY'),
                            name: 'TAS_PRIORITY_VARIABLE',
                            anchor:'100%'
                            
                         }]
                    },{
                        columnWidth:.3,
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
                fieldLabel:_('ID_START_TASK'),
                name: 'TAS_START'
              }]
            },{
                title:_('ID_ASSIGNMENT_RULES'),
                layout:'form',
                defaults: {
                    width: 260
                },
               items: [{
                    xtype: 'radiogroup',
                    id:    'assignType',
                    fieldLabel: _('ID_CASES_ASSIGNED_BY'),
                    itemCls: 'x-check-group-alt',
                    columns: 1,
                    items: [
                    {
                        boxLabel: _('ID_CYCLIC_ASSIGNMENT'),
                        id: 'BALANCED',
                        name: 'TAS_ASSIGN_TYPE',
                        inputValue: 'BALANCED',
                        checked: false,
                        listeners: {'check':{fn: function(){Ext.getCmp("staticMI").hide();Ext.getCmp("cancelMI").hide();Ext.getCmp("evaluate").hide();}}}
                    },

                    {
                        boxLabel: _('ID_MANUAL_ASSIGNMENT'),
                        id: 'MANUAL',
                        name: 'TAS_ASSIGN_TYPE',
                        inputValue: 'MANUAL',
                        checked:false,
                        listeners: {'check':{fn: function(){Ext.getCmp("staticMI").hide();Ext.getCmp("cancelMI").hide();Ext.getCmp("evaluate").hide();}}}
                    },

                    {
                        boxLabel: _('ID_VALUE_BASED'),
                        id:'EVALUATE',
                        name: 'TAS_ASSIGN_TYPE',
                        inputValue: 'EVALUATE',
                        checked:false,
                        listeners: {
                            'check':{
                                fn: function(){
                                    Ext.getCmp("evaluate").show();
                                    Ext.getCmp("staticMI").hide();
                                    Ext.getCmp("cancelMI").hide();
                                }
                            }
                        }
                    },

                    {
                        boxLabel: _('ID_REPORTS_TO'),
                        id:'REPORT_TO',
                        name: 'TAS_ASSIGN_TYPE',
                        inputValue: 'REPORT_TO',
                        checked:false,
                        listeners: {'check':{fn: function(){Ext.getCmp("staticMI").hide();Ext.getCmp("cancelMI").hide();Ext.getCmp("evaluate").hide();}}}
                    },
                    {
                        boxLabel: _('ID_SELF_SERVICE'),
                        id:'SELF_SERVICE',
                        name: 'TAS_ASSIGN_TYPE',
                        inputValue: 'SELF_SERVICE',
                        checked:false,
                        listeners: {'check':{fn: function(){Ext.getCmp("staticMI").hide();Ext.getCmp("cancelMI").hide();Ext.getCmp("evaluate").hide();}}}
                    },

                    {
                        boxLabel: _('ID_STATIC_PARTIAL_JOIN_MULTIPLE_INSTANCES'),
                        id:'STATIC_MI',
                        name: 'TAS_ASSIGN_TYPE',
                        inputValue: 'STATIC_MI',
                        checked:false,
                        listeners: {
                            'check':{
                                fn: function(){
                                    Ext.getCmp("staticMI").show();
                                    Ext.getCmp("cancelMI").show();
                                    Ext.getCmp("evaluate").hide();
                                }
                            }
                        }
                    },

                    {
                        boxLabel: _('ID_CANCEL_PARTIAL_JOIN_MULTIPLE_INSTANCE'),
                        id:'CANCEL_MI',
                        name: 'TAS_ASSIGN_TYPE',
                        inputValue: 'CANCEL_MI',
                        checked:false,
                        listeners: {
                            'check':{
                                fn: function(){
                                    Ext.getCmp("staticMI").show();
                                    Ext.getCmp("cancelMI").show();
                                    Ext.getCmp("evaluate").hide();
                                }
                            }
                        }
                    }
                  
                ,{
                  boxLabel: 'Reports to',
                  id:'REPORT_TO',
                  name: 'TAS_ASSIGN_TYPE',
                  inputValue: 'REPORT_TO',
                  checked:false,
                  listeners: {'check':{fn: function(){Ext.getCmp("staticMI").hide();Ext.getCmp("cancelMI").hide();Ext.getCmp("evaluate").hide();}}}
                },{
                  boxLabel: 'Self Service',
                  id:'SELF_SERVICE',
                  name: 'TAS_ASSIGN_TYPE',
                  inputValue: 'SELF_SERVICE',
                  checked:false,
                  listeners: {'check':{fn: function(){Ext.getCmp("staticMI").hide();Ext.getCmp("cancelMI").hide();Ext.getCmp("evaluate").hide();}}}
                },{
                  boxLabel: 'Static Partial Join for Multiple Instance',
                  id:'STATIC_MI',
                  name: 'TAS_ASSIGN_TYPE',
                  inputValue: 'STATIC_MI',
                  checked:false,
                  listeners: {
                    'check':{
                      fn: function(){
                        Ext.getCmp("staticMI").show();
                        Ext.getCmp("cancelMI").show();
                        Ext.getCmp("evaluate").hide();
                      }
                    }
                  }
                },{
                  boxLabel: 'Cancelling Partial Join for Multiple Instance',
                  id   : 'CANCEL_MI',
                  name : 'TAS_ASSIGN_TYPE',
                  inputValue: 'CANCEL_MI',
                  checked:false,
                  listeners: {
                    'check':{
                      fn: function(){
                        Ext.getCmp("staticMI").show();
                        Ext.getCmp("cancelMI").show();
                        Ext.getCmp("evaluate").hide();
                      }
                    }
                  }
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
                    width: 550,
                    //title: 'valueBased',
                    hidden: true,
                    id: 'evaluate',
                    items:[{
                        columnWidth:.6,
                        layout: 'form',
                        border:false,
                        items: [{
                            xtype: 'textfield',
                            fieldLabel: _('ID_VARIABLES_VALUE_ASSIGNMENT'),
                            name: 'TAS_ASSIGN_VARIABLE',
                            //value: _5625.scope.workflow.taskDetails.TAS_ASSIGN_VARIABLE
                            anchor:'100%'
                        }]
                    },{
                        columnWidth:.3,
                        layout: 'form',
                        border:false,
                        items: [{
                            xtype:'button',
                            title: ' ',
                            text: '@@',
                            name: 'selectorigin',
                             handler: function (s) {
                                    workflow.variablesAction = 'form';
                                    workflow.variable        = '@%23',
                                    workflow.fieldName         = 'TAS_ASSIGN_VARIABLE' ;
                                    workflow.formSelected    = taskPropertiesTabs;
                                    var rowData = ProcMapObj.ExtVariables();
                                    console.log(rowData);
                                   }
                        //anchor:'35%'
                        }]
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
                  bodyStyle   : 'padding:0 0 0 ',
                  hidden: true,
                  id: 'staticMI',
                  items:[{
                    columnWidth:.8,
                    layout: 'form',
                    border:false,
                    width: 550,
                    //title: 'MI',
                    hidden: true,
                    id: 'staticMI',
                    items:[{
                        columnWidth:.6,
                        layout: 'form',
                        border:false,
                        items: [{
                            xtype: 'textfield',
                            fieldLabel: _('ID_VARIABLES_NO_INSTANCES'),
                            name: 'TAS_MI_INSTANCE_VARIABLE',
                            //value: _5625.scope.workflow.taskDetails.TAS_MI_INSTANCE_VARIABLE
                            anchor:'100%'
                        }]
                },{
                        columnWidth:.3,
                        layout: 'form',
                        border:false,
                        items: [{
                            xtype:'button',
                            title: ' ',
                            text: '@@',
                            name: 'selectorigin',
                            handler: function (s) {
                                    workflow.variablesAction = 'form';
                                    workflow.variable        = '@%23',
                                    workflow.fieldName         = 'TAS_MI_INSTANCE_VARIABLE' ;
                                    workflow.formSelected    = taskPropertiesTabs;
                                    var rowData = ProcMapObj.ExtVariables();
                                    console.log(rowData);
                                   }
                        //anchor:'35%'
                        }]
                    }]
                },{
                    xtype: 'fieldset',
                    layout:'column',
                    border:false,
                    width: 550,
                    bodyStyle   : 'padding:0 0 0 ',
                    hidden: true,
                    id: 'cancelMI',
                    items:[{
                        columnWidth:.6,
                        layout: 'form',
                        border:false,
                        items: [{
                            xtype: 'textfield',
                            fieldLabel: _('ID_VARIABLES_INSTANCES_TO _COMPLETE'),
                            name: 'TAS_MI_COMPLETE_VARIABLE',
                            //value: _5625.scope.workflow.taskDetails.TAS_MI_COMPLETE_VARIABLE
                            anchor:'100%'
                        }]
                },{
                    columnWidth:.3,
                    layout: 'form',
                    border:false,
                    items: [{
                        xtype:'button',
                        title: ' ',
                        text: '@@',
                        name: 'selectorigin',
                        handler: function (s) {
                                    workflow.variablesAction = 'form';
                                    workflow.variable        = '@%23',
                                    workflow.fieldName         = 'TAS_MI_COMPLETE_VARIABLE' ;
                                    workflow.formSelected    = taskPropertiesTabs;
                                    var rowData = ProcMapObj.ExtVariables();
                                    console.log(rowData);
                                   }
                      }]
                    }]
                }]
            },{
                title:_('ID_TASK_PROPERTIES_TIMING T'),
                layout:'form',
                border:false,
                width: 550,
                hidden: false,
                id: 'userDefinedTiming',

                items:[{
                  xtype: 'textfield',
                  fieldLabel: 'Task Duration',
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
                  fieldLabel:     'Time Unit',
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
                  fieldLabel:     'Count Days by',
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
                  fieldLabel:     'Calendar',
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
              title:'Permission',
              layout:'form',
              defaults: {
                  width: 260
              },
              defaultType: 'textfield',
              labelWidth: 200,
              items: [{
                  xtype: 'checkbox',
                  id: 'ADHOC',
                  fieldLabel: 'Allow arbitary transfer (Ad hoc)',
                  inputValue:'ADHOC',
                  checked: false,
                  name: 'TAS_TYPE'
              }]
            },{
                title:'Case Labels',
                layout:'form',
                defaults: {
                    width: 800
                },
                defaultType: 'textfield',
                labelWidth: 70,
                items: [{
                    xtype: 'fieldset',
                    layout:'column',
                    border:false,
                    width: 700,
                    items:[{
                        columnWidth:.5,
                        layout: 'form',
                        border:false,
                        items: [{
                            xtype: 'textarea',
                            fieldLabel: 'Case Title',
                            //id: 'caseTitle',
                            name: 'TAS_DEF_TITLE',
                            height : 100,
                            //value: _5625.scope.workflow.taskDetails.TAS_ASSIGN_VARIABLE
                            anchor:'100%'
                        }]
                    },{
                        columnWidth:.3,
                        layout: 'form',
                        border:false,
                        bodyStyle: 'padding:35px;',
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
                    columnWidth:.2,
                    layout: 'form',
                    border:false,
                    width: 700,
                    items:[{
                        columnWidth:.5,
                        layout: 'form',
                        border:false,
                        items: [{
                            xtype: 'textarea',
                            //id: 'caseDescription',
                            fieldLabel: 'Case Description',
                            name: 'TAS_DEF_DESCRIPTION',
                            height : 100,
                            anchor:'100%'

                        }]
                    },{
                        columnWidth:.3,
                        layout: 'form',
                        border:false,
                        bodyStyle: 'padding:35px;',
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
                            //anchor:'10%'
                        }]
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
                          id: 'caseDescription',
                          fieldLabel: 'Case Description',
                          name: 'TAS_DEF_DESCRIPTION',
                          height : 120,
                          anchor:'100%'

                      }]
                  },{
                      columnWidth:.2,
                      layout: 'form',
                      border:false,
                      bodyStyle: 'padding:35px;',
                      items: [{
                          xtype:'button',
                          title: ' ',
                          text: '@#',
                          name: 'selectCaseDesc',
                          handler: function (s) {
                            workflow.variablesAction = 'form';
                            workflow.variable = '@%23',
                            workflow.fieldId= 'caseDescription' ;
                            workflow.formSelected = taskPropertiesTabs;
                            var rowData = ProcMapObj.ExtVariables();
                            console.log(rowData);
                          }
                      }]
                  }]
              }]
              },{
              title:'Notification',
              layout:'form',
              defaultType: 'textfield',
              labelWidth: 170,
              items: [{
                xtype: 'checkbox',
                boxLabel: 'After routing notify the next assigned user(s).',
                labelWidth: 100,
                name:   'TAS_DEF_MESSAGE_CHECKBOX',
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
        
    });
    
  //Loading Task Details into the form
  taskPropertiesTabs.form.load({
        url:'proxyExtjs.php?tid='+taskId+'&action=getTaskPropertiesList',
        method:'GET',
        waitMsg:'Loading',
        success:function(form, action) {
        //To load the values of the selecte radio button in Assignment Rules
                       if(action.result.data.TAS_ASSIGN_TYPE=='BALANCED')
                           form.items.items[4].items[0].checked=true;

                       else  if(action.result.data.TAS_ASSIGN_TYPE=='MANUAL')
                        {
                            form.items.items[4].items[1].checked=true;
                            Ext.getCmp(ID).setValue(true);
                            Ext.getCmp('BALANCED').setValue(true);
                        }
                       else if(action.result.data.TAS_ASSIGN_TYPE=='EVALUATE')
                            {
                                form.items.items[4].items[2].checked=true;
                                Ext.getCmp("evaluate").show();
                            }

                       else if(action.result.data.TAS_ASSIGN_TYPE=='REPORT_TO')
                           form.items.items[4].items[3].checked=true;

                       else  if(action.result.data.TAS_ASSIGN_TYPE=='SELF_SERVICE')
                           form.items.items[4].items[4].checked=true;

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
       },
        failure:function(form, action) {
            Ext.MessageBox.alert('Message', 'Load failed');
        }
    });

    taskPropertiesTabs.render(document.body);
    workflow.taskPropertiesTabs = taskPropertiesTabs;

    var window = new Ext.Window({
        title: 'Task:',
        collapsible: false,
        maximizable: false,
        width: 600,
        height: 400,
        minWidth: 300,
        minHeight: 150,
        layout: 'fit',
        plain: true,
        //bodyStyle: 'padding:5px;',
        buttonAlign: 'center',
        items: taskPropertiesTabs,
        buttons: [{
            text: 'Save',
            formBind    :true,
            handler: function(){
                //var getstore = taskPropertiesTabs.getStore();
                //var getData = getstore.data.items;
                taskExtObj.saveTaskProperties();
                 window.hide();

            }
        },{
            text: 'Cancel',
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
                 var newTaskValues = new Array();
                 var oData = null;
                 for (var key in saveTaskform )
                    {
                            newTaskValues[key] = new Array();

                            if(saveTaskform[key] == 'on')
                                {
                                    if(key == 'TAS_TYPE')
                                        saveTaskform[key] = 'ADHOC';
                                    else
                                        saveTaskform[key] = 'TRUE';
                                }
                            else if(saveTaskform[key] == 'off')
                                saveTaskform[key] = 'FALSE';

                            newTaskValues[key] = saveTaskform[key];        //Creating an array on all updated fields by user

                            if(key != 'TAS_CALENDAR' && key != 'TAS_DEF_MESSAGE_CHECKBOX')
                            {
                                if(oData != null)
                                    oData = oData + '"'+key+'":"'+saveTaskform[key]+'"'+',';
                                else
                                    oData = '"'+key+'":"'+saveTaskform[key]+'",' + '"TAS_UID":"'+taskId+'",';
                            }
                    }
                 oData = '{'+oData.slice(0,oData.length-1)+'}';

                 Ext.Ajax.request({
                        url: '../tasks/tasks_Ajax.php' ,
                    success: function (response) {      // When saving data success
                        Ext.MessageBox.alert ('Status','Task properties has been saved successfully');
                    },
                    failure: function () {      // when saving data failed
                        Ext.MessageBox.alert ('Status','Error in saving Task Properties');
                    },
                    params: {
                        functions:'saveTaskData',
                        oData:oData
                    }
                 });
}

TaskContext.prototype.stepTriggers = function()
    {
     var pro_uid = workflow.getUrlVars();
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
        saveText: 'Update'
    });


    var tree = new Ext.tree.TreePanel({
            //renderTo    : 'cases-grid',
            dataUrl     : 'get-triggers-tree.php?tid='+taskId,
            border      : false,
            rootVisible : false,
            height      : 320,
            width       : 200,
            useArrows   : false,
            autoScroll  : true,
            animate     : true,
            root        : new Ext.tree.AsyncTreeNode({text: 'treeRoot',id:'0'})
         });

    //tree.render('tree');
    tree.on('click', function (node){
         if(node.isLeaf()){
             var sStepUID = node.attributes.id;
             workflow.selectedStepUID = sStepUID;

             stepsTriggers.on({
                beforeload: {
                        fn: function (store, options) {
                                // use {@link Ext.data.HttpProxy#setUrl setUrl} to change the URL for *just* this request.
                                var link = 'proxyExtjs?tid='+taskId+'&stepid='+sStepUID+'&action=getAssignedStepTriggers';
                                store.proxy.setUrl(link, true);
                        }
                }
             });

            availableTriggers.on({
                beforeload: {
                        fn: function (store, options) {
                                // use {@link Ext.data.HttpProxy#setUrl setUrl} to change the URL for *just* this request.
                                var link = 'proxyExtjs?pid='+pro_uid+'&tid='+taskId+'&stepid='+sStepUID+'&action=getAvailableStepTriggers';
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
        text: 'Add',
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
                 Ext.MessageBox.alert ('Status','No triggers are available. All triggers have been already assigned.');
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
        text: 'Remove',
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
                        Ext.MessageBox.alert ('Status','Trigger has been removed successfully.');

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
      text: 'Condition',
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
                console.log(rowData);
                //var a = Ext.getCmp('btnCondition');
                //alert (a);

                //console.log(rowData);
            }


    });

    var toolBar = new Ext.Toolbar({
        items: [addBtn, removeBtn, btnTriggerCondition]
    });


    // create the Data Store of users that are already assigned to a task
    var stepsTriggers = new Ext.data.JsonStore({
        root            : 'data',
        url             : 'proxyExtjs?tid='+taskId+'&action=',//+'&stepid='+workflow.selectedStepUID,
        totalProperty   : 'totalCount',
        idProperty      : 'gridIndex',
        remoteSort      : true,
        fields          : triggersFields
      });
      //taskUsers.setDefaultSort('LABEL', 'asc');
      //stepsTriggers.load();

     var availableTriggers = new Ext.data.JsonStore({
         root            : 'data',
         url             : 'proxyExtjs?pid='+pro_uid+'&tid='+taskId+'&action=',//+'&stepid='+workflow.selectedStepUID,
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
        renderTo: 'cases-grid',
        frame: false,
        autoHeight:false,
        hidden    :true,
        clicksToEdit: 1,
        width       : 450,
        minHeight:400,
        height   :400,
        layout: 'fit',
        plugins: [triggerEditor],
        columns: [
                new Ext.grid.RowNumberer(),
                {
                    id: 'blank',
                    hidden : true,
                    width:0,
                    editable: true,
                    editor: new Ext.form.TextField({
                        allowBlank: true
                    })
                },
                {
                    id: 'CON_VALUE',
                    header: 'Triggers',
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
                    header: 'Condition',
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
                  Ext.MessageBox.alert ('Status','Triggers has been assigned successfully.');
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
        frame: true,
        monitorValid : true,
        labelAlign: 'left',
        bodyStyle:'padding:5px',
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
            title:'Assign Triggers',
            defaults: {width: 140, border:false}, 
            autoHeight: true,
            bodyStyle: Ext.isIE ? 'padding:0 0 5px 15px;' : 'padding:10px 15px;',
            border: false,
            items: [triggerGrid]
        }]
    });
    
    return treeGrid;
}

TaskContext.prototype.editUsersAdHoc= function()
{
    var taskExtObj = new TaskContext();
    var pro_uid = workflow.getUrlVars();
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
            saveText: 'Update'
        });
    var taskUsers = new Ext.data.JsonStore({
            root         : 'data',
            totalProperty: 'totalCount',
            idProperty   : 'gridIndex',
            remoteSort   : true,
            fields       : userFields,
            proxy: new Ext.data.HttpProxy({
              url: 'proxyExtjs?pid='+pro_uid+'&tid='+taskId+'&action=assignedUsers'
            })
          });
          //taskUsers.setDefaultSort('LABEL', 'asc');
          taskUsers.load();

         // create the Data Store of users that are not assigned to a task
         var storeUsers = new Ext.data.JsonStore({
                 root            : 'data',
                 url             : 'proxyExtjs?tid='+taskId+'&action=availableUsers',
                 totalProperty   : 'totalCount',
                 idProperty      : 'gridIndex',
                 remoteSort      : false, //true,
                 autoLoad        : true,
                 fields          : userFields
              });


        var btnAdd = new Ext.Button({
            id: 'btnAdd',
            text: 'Assign',
            iconCls: 'application_add',
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
                     Ext.MessageBox.alert ('Status','No users are available. All users have been already assigned.');
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
            text: 'Remove',
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
                          url   : 'processes_Ajax.php' +urlparams ,
                          /*method: 'POST',
                          params: {
                                functions       : 'ofToAssign',
                                TAS_UID         : taskId,
                                TU_RELATION     : user_TURel,
                                USR_UID         : userUID,
                                TU_TYPE         : user_TUtype

                          },*/
                          success: function(response) {
                              Ext.MessageBox.alert ('Status','User has been removed successfully.');
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
        renderTo: 'cases-grid',
        frame: false,
        autoHeight:false,
        clicksToEdit: 1,
        minHeight:400,
        height   :400,
        layout: 'fit',
        plugins: [editor],
        columns: [
                new Ext.grid.RowNumberer(),
                {
                    id: 'LABEL',
                    header: 'Group or User',
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
                        Ext.MessageBox.alert ('Status','User has been successfully assigned');
                    },
                    params:{
                        functions : 'assign',
                        TAS_UID  :  taskId,
                        USR_UID : userId,
                        TU_TYPE : tu_Type,
                        TU_RELATION:tu_Relation

                    },
                    failure: function () {      // when saving data failed
                        Ext.MessageBox.alert ('Status','Failed saving User Assigned to Task');
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
                          Ext.MessageBox.alert ('Status','User has been updated successfully.');
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
            renderTo: Ext.getBody(),
            items: [grid]
        });

        var window = new Ext.Window({
        title: 'Users and User Groups(Ad Hoc)',
        collapsible: false,
        maximizable: false,
        width: 400,
        height: 350,
        minWidth: 200,
        minHeight: 150,
        layout: 'fit',
        plain: true,
        bodyStyle: 'padding:5px;',
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
        var pro_uid = workflow.getUrlVars();
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
            saveText: 'Update'
        });
    var editorIn = new Ext.ux.grid.RowEditor({
            saveText: 'Update'
        });

    //Variable out grid configuration starts here
    var btnAddOut = new Ext.Button({
            id: 'btnAddOut',
            text: 'Assign Variables Out',
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
        text: 'Remove Variables Out',
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
        text: 'Assign Variables In',
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
        text: 'Remove Variables In',
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
        renderTo    : 'cases-grid',
        frame       : true,
        autoHeight  : true,
        autoScroll  : true,
        clicksToEdit: 1,
        layout      : 'form',
        plugins     : [editorOut],
        columns     : [{
                        id       : 'VAR_OUT1',
                        name     : 'VAR_OUT1',
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
                        name      : 'VAR_OUT2',
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
                        name     : 'VAR_IN1',
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
                        name      : 'VAR_IN2',
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



    /* editorOut.on({
          scope: this,
          afteredit: function(roweditor, changes, record, rowIndex) {
             var storeData = variableOutGrid.getStore();
             var sSP_UID           =  spUID;
             var sProcess_Parent   =  proParent;
             var sSync             =  spSync;
             var sTask_Parent      =  tasParent;
             var sTasks            =  tasks;
             var sVar_Out1         =  record.data.VAR_OUT1;
             var sVar_Out2         =  record.data.VAR_OUT2;

             Ext.Ajax.request({
              url   : 'processes_Ajax.php',
              method: 'POST',
              params: {
                    action          : 'saveSubProcessVariables',
                    PRO_UID         : pro_uid,
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
              var storeData = variableInGrid.getStore();
             var sSP_UID           =  spUID;
             var sProcess_Parent   =  proParent;
             var sSync             =  spSync;
             var sTask_Parent      =  tasParent;
             var sTasks            =  tasks;
             var sVar_In1          =  record.data.VAR_IN1;
             var sVar_In2          =  record.data.VAR_IN2;

             Ext.Ajax.request({
              url   : 'processes_Ajax.php',
              method: 'POST',
              params: {
                    action          : 'saveSubProcessVariables',
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
        });*/

    var subProcessProperties = new Ext.FormPanel({
    labelWidth  : 110, // label settings here cascade unless overridden
    //frame:true,
    bodyStyle:'padding:5px 5px 0',
    width: 800,
    autoScroll: true,
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
                        fieldLabel:     'Type',
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
            title: 'Variables Out',
            collapsible: false,
            labelAlign: 'top',
             items:[variableOutGrid]
            },
            {
            id   :'variablein',
            name :'VAR_IN1',
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
            Ext.MessageBox.alert('Message', 'Load failed');
        }
     });

    subProcessProperties.render(document.body);

    var window = new Ext.Window({
    title: 'Task: ',
    collapsible: false,
    maximizable: false,
    width: 900,
    height: 400,
    layout: 'fit',
    plain: true,
    bodyStyle: 'padding:5px;',
    buttonAlign: 'center',
    items: subProcessProperties,
    buttons: [{
        text: 'Save',
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
            if(sSync == 1)
                    var varIn = Ext.util.JSON.encode(varIn1)+'|'+Ext.util.JSON.encode(varIn2);
            else
                {
                    varIn = new Array();
                    varIn[0] = '';
                }
                
            
           Ext.Ajax.request({
              url   : 'processes_Ajax.php',
              method: 'POST',
              params: {
                    action          : 'saveSubprocessDetails',
                    SP_UID          : spUID,
                    TASKS           : tasks,  //problem
                    PRO_UID         : sProcessUID,
                    SPROCESS_NAME   : sSPNAME,
                    PRO_PARENT      : proParent,
                    TAS_PARENT	    : tasParent,
                    VAR_OUT         : varOut,
                    VAR_IN          : varIn,
                    SP_SYNCHRONOUS  : sSync
                  },
              success: function(response) {
                  Ext.MessageBox.alert ('Status','Sub Process Properties has been saved successfully.');
                  window.close();
              }
            });

            workflow.currentSelection.bpmnNewText.clear();
            workflow.currentSelection.bpmnNewText.drawStringRect(sSPNAME,20,20,100,'left');
            workflow.currentSelection.bpmnNewText.paint();
            workflow.currentSelection.subProcessName = sSPNAME;
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
