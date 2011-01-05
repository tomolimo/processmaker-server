TaskContext=function(id){
    Workflow.call(this,id);
};
TaskContext.prototype=new Workflow;
TaskContext.prototype.type="TaskContext";


TaskContext.prototype.editTaskSteps = function(_3252){
    var taskExtObj = new TaskContext();
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
            text: 'Assign Step',
            iconCls: 'application_add',
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
            text: 'Remove Step',
            iconCls: 'application_delete',
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
         //availableSteps.load();


         var conditionsColumns = new Ext.grid.ColumnModel({
            columns: [
                {
                    id: 'STEP_TITLE',
                    header: 'Title',
                    dataIndex: 'STEP_TITLE',
                    width: 280,
                    editor: new Ext.form.TextField({
                    //allowBlank: false
                    })
                },
                {
                    //id: 'STEP_TITLE',
                    header: 'Condition',
                    dataIndex: 'STEP_CONDITION',
                    width: 250,
                    editable: true,
                    editor: new Ext.form.TextField({
                    })
                },
                  {
                    header: 'Assign Condition',
                    width: 200,
                    renderer: function(val){return '<input type="button" value="@@" id="'+val+'"/>';}
                  }
                ]
        });

       
                 
      var grid =  new Ext.grid.GridPanel({
            store       : taskSteps,
            id          : 'mygrid',
            loadMask    : true,
            loadingText : 'Loading...',
            renderTo    : 'cases-grid',
            frame       : false,
            autoHeight  : false,
            enableDragDrop   : true,
            ddGroup     : 'firstGridDDGroup',
            clicksToEdit: 1,
            minHeight   :400,
            height      :400,
            layout      : 'form',
            plugins     : [editor],
            columns     : [{
                        id: 'STEP_TITLE',
                        header: 'Title',
                        dataIndex: 'STEP_TITLE',
                        width: 200,
                        sortable: true,
                        editor: new Ext.form.ComboBox({
                                xtype        : 'combo',
                                fieldLabel   : 'Users_groups',
                                //hiddenName   : 'number',
                                store        :  availableSteps,
                                displayField : 'STEP_TITLE'  ,
                                valueField   : 'STEP_TITLE',
                                //name         : 'STEP_TITLE',
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
                                     User.data.items[selectedrowIndex].data.STEP_UID= record.data.STEP_UID;
                                     User.data.items[selectedrowIndex].data.STEP_TYPE_OBJ=record.data.STEP_TYPE_OBJ;
                                     User.data.items[selectedrowIndex].data.STEP_CONDITION=record.data.STEP_CONDITION;
                                     User.data.items[selectedrowIndex].data.STEP_POSITION=record.data.STEP_POSITION;
                                     User.data.items[selectedrowIndex].data.STEP_UID_OBJ=record.data.STEP_UID_OBJ;
                                     User.data.items[selectedrowIndex].data.STEP_MODE=record.data.STEP_MODE;

                                     this.setValue(record.data[this.valueField || this.displayField]);
                                     this.collapse();
                                  }
                            })
                    },
                     {
                        id: 'STEP_MODE',
                        header: 'Mode',
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
            availableSteps.reload();
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
      var firstGridDropTargetEl =  grid.getView().scroller.dom;
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
      firstGridDropTarget.addToGroup('firstGridDDGroup');

    //Getting triggers data using stepTriggers function
    var treeGrid = taskExtObj.stepTriggers(_3252);
    treeGrid.render(document.body);

    var taskStepsTabs = new Ext.FormPanel({
        labelWidth: 100,
        bodyStyle :'padding:5px 5px 0',
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
                title:'Steps',
                layout:'fit',
                defaults: {
                    width: 400
                },
                items:[grid]
            },{
                title:'Condition',
                layout:'fit',
                defaults: {
                    width: 400
                },
                items:[{
                        xtype: 'grid',
                        ds: taskSteps,
                        cm: conditionsColumns,
                        height: 350,
                        loadMask    : true,
                        loadingText : 'Loading...',
                        border: false
                }]
            },{
                title:'Triggers',
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
        title: 'Steps Of',
        collapsible: false,
        maximizable: false,
        width: 800,
        height: 470,
        minWidth: 200,
        minHeight: 150,
        layout: 'fit',
        plain: true,
        bodyStyle: 'padding:5px;',
        buttonAlign: 'center',
        items: taskStepsTabs
        /*buttons: [{
            text: 'Save',
            handler: function(){
                //var getstore = grid.getStore();
                //var getData = getstore.data.items;
                //taskExtObj.saveTaskSteps(getData);
            }
        },{
            text: 'Cancel',
            handler: function(){
                // when this button clicked,
                window.close();
            }
        }]*/
    });
    window.show();

}


TaskContext.prototype.editUsers= function(_5625)
{
        var taskExtObj = new TaskContext();
        var pro_uid = workflow.getUrlVars();
        var taskId  = workflow.currentSelection.id;

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
            text: 'Assign User',
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
            text: 'Remove User',
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
          //taskUsers.setDefaultSort('LABEL', 'asc');
          taskUsers.load();

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
                            displayField : 'LABEL'  ,
                            valueField   : 'LABEL',
                            name         : 'LABEL',
                            scope        : _5625,
                            triggerAction: 'all',
                            emptyText: 'Select User or Group',
                            allowBlank: false,
                             onSelect: function(record, index){
                                var User = grid.getStore();

                                if(typeof _5625.scope.workflow.currentrowIndex == 'undefined')
                                        var selectedrowIndex = '0';
                                else
                                        selectedrowIndex     = _5625.scope.workflow.currentrowIndex;    //getting Index of the row that has been edited

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
                         _5625.scope.workflow.currentrowIndex = rowIndex;
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
        title: 'Assign User',
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
        /*buttons: [{
            text: 'Save',
            handler: function(){
                Ext.MessageBox.alert ("User has been successfully assigned");
                //var getstore = grid.getStore();
                //var getData = getstore.data.items;
                //taskExtObj.saveTaskUsers(getData);
                
            }
        },{
            text: 'Cancel',
            handler: function(){
                // when this button clicked,
                window.close();
            }
        }]*/
    });
    window.show();
}


TaskContext.prototype.editTaskProperties= function(_5625)
{
    
    var taskExtObj = new TaskContext();
    var taskId  = _5625.scope.workflow.currentSelection.id;

    /*function setTaskAssignType(formData){
       for(var i=0;i<formData.items.items.length;i++)
           {
                if(formData.items.items[i].xtype == 'radiogroup')
                    {
                        //var taskAssignType = formData.items.items[i].getValue();
                        //var taskAssignType1 = taskPropertiesTabs.getForm().findField('TAS_ASSIGN_TYPE').getGroupValue();
                        //var taskAssignType1 = Ext.getCmp("assignType").items.get(0).getGroupValue();
                         //var checkedItem = Ext.getCmp('assignType').items.first().getGroupValue();

                        switch(checkedItem)
                        {
                            case 'SELF_SERVICE':
                                this.workflow.selfServiceChecked = true;
                            break;
                            case 'REPORT_TO':
                                this.workflow.reportToChecked = true;
                            break;
                            case 'BALANCED':
                                this.workflow.balancedChecked = true;
                            break;
                            case 'MANUAL':
                                this.workflow.manualChecked = true;
                            break;
                            case 'EVALUATE':
                                this.workflow.evaluateChecked      = true;
                                this.workflow.hideEvaluateField    = false;
                            break;
                            case 'STATIC_MI':
                                this.workflow.staticMIChecked      = true;
                                this.workflow.hidePartialJoinField = false;
                            break;
                            case 'CANCEL_MI':
                                this.workflow.cancelMIChecked      = true;
                                this.workflow.hidePartialJoinField = false;
                            break;
                        }
                    }
           }
      }*/
    
     // create the Data Store for processes
      var taskDetails = new Ext.data.JsonStore({
        root         : 'data',
        totalProperty: 'totalCount',
        idProperty   : 'gridIndex',
        remoteSort   : true,
        //fields       : taskFields,
        proxy: new Ext.data.HttpProxy({
          url: 'proxyTaskPropertiesDetails?tid='+taskId
        })
      });
      //taskUsers.setDefaultSort('LABEL', 'asc');
      taskDetails.load();


    var taskPropertiesTabs = new Ext.FormPanel({
        labelWidth  : 100,
       // store       : taskDetails,
        //url         : 'proxyTaskPropertiesDetails.php',
        bodyStyle   : 'padding:5px 5px 0',
        width       : 600,
        scope       : _5625,
        items: {
            xtype:'tabpanel',
            activeTab: 0,
            defaults:{
                autoHeight:true,
                bodyStyle:'padding:10px'
            },
            items:[{
                title:'Defination',
                layout:'form',
                defaults: {
                    width: 230
                },
                defaultType: 'textfield',
                items: [{
                    fieldLabel: 'Title',
                    name: 'TAS_TITLE',
                    //dataIndex: 'TAS_TITLE',
                    //value:  _5625.scope.workflow.taskDetails.TAS_TITLE,
                    allowBlank: false,
                    width: 300
                },{
                    xtype: 'textarea',
                    fieldLabel: 'Description',
                    name: 'TAS_DESCRIPTION',
                    //dataIndex: 'TAS_DESCRIPTION',
                    //value: _5625.scope.workflow.taskDetails.TAS_DESCRIPTION,
                    allowBlank: false,
                    width: 300,
                    height : 150
                },
                {
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
                            fieldLabel: 'Variable for Case priority',
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
                            name: 'selectorigin'
                            //anchor:'15%'
                        }]
                    }]
                },
                {
                    xtype: 'checkbox',
                    fieldLabel: 'Starting Task',
                    name: 'TAS_START',
                    //dataIndex: 'TAS_START'
                    checked: 'TAS_START'
                }]
            },{
                title:'Assignment Rules',
                layout:'form',
                defaults: {
                    width: 260
                },
                //defaultType: 'textfield',
                items: [{
                    xtype: 'radiogroup',
                    id:    'assignType',
                    fieldLabel: 'Cases to be Assigned by',
                    itemCls: 'x-check-group-alt',
                    columns: 1,
                    items: [
                    {
                        boxLabel: 'Cyclic Assignment',
                        name: 'TAS_ASSIGN_TYPE',
                        inputValue: 'BALANCED',
                        checked:    'BALANCED'
                    },

                    {
                        boxLabel: 'Manual Assignment',
                        name: 'TAS_ASSIGN_TYPE',
                        inputValue: 'MANUAL',
                        checked:    'MANUAL'
                    },

                    {
                        boxLabel: 'Value Based',
                        name: 'TAS_ASSIGN_TYPE',
                        inputValue: 'EVALUATE',
                        //checked:    'EVALUATE',
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
                        boxLabel: 'Reports to',
                        name: 'TAS_ASSIGN_TYPE',
                        inputValue: 'REPORT_TO',
                        checked:    'REPORT_TO'
                    },
                    {
                        boxLabel: 'Self Service',
                        name: 'TAS_ASSIGN_TYPE',
                        inputValue: 'SELF_SERVICE',
                        checked:    'SELF_SERVICE'
                    },

                    {
                        boxLabel: 'Static Partial Join for Multiple Instance',
                        name: 'TAS_ASSIGN_TYPE',
                        inputValue: 'STATIC_MI',
                        //checked:    'STATIC_MI',
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
                        boxLabel: 'Cancelling Partial Join for Multiple Instance',
                        name: 'TAS_ASSIGN_TYPE',
                        inputValue: 'CANCEL_MI',
                        //checked:    'CANCEL_MI',
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
                    /*{boxLabel: 'Item 2', name: 'rb-auto', inputValue: 2,
                        listeners: {
                            'check':{fn: function(){Ext.getCmp("txt-test4").hide();Ext.getCmp("toggler").collapse();(function(){Ext.getCmp("toggler").hide();}).defer(1000);},scope: this}}
                    }*/
                    ]
                },{
                    xtype: 'fieldset',
                    layout:'column',
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
                            fieldLabel: 'Variable for Value Based Assignment',
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
                            name: 'selectorigin'
                        //anchor:'35%'
                        }]
                    }]
                },{
                    xtype: 'fieldset',
                    layout:'column',
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
                            fieldLabel: 'Variable for No of Instances',
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
                            name: 'selectorigin'
                        //anchor:'35%'
                        }]
                    }]
                },{
                    xtype: 'fieldset',
                    layout:'column',
                    border:false,
                    width: 550,
                    //title: 'MI2',
                    hidden: true,
                    id: 'cancelMI',
                    items:[{
                        columnWidth:.6,
                        layout: 'form',
                        border:false,
                        items: [{
                            xtype: 'textfield',
                            fieldLabel: 'Variable for No of Instances to complete',
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
                            name: 'selectorigin'
                        //anchor:'35%'
                        }]
                    }]
                }

                ]
            },{
                title:'Timing Control Rules',
                layout:'form',
                defaults: {
                    width: 260
                },
                bodyStyle:'padding:5px 5px 0 30px',
                defaultType: 'textfield',
                items: [{
                    xtype: 'checkbox',
                    boxLabel: 'Allow user defined timing control',
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

                },
                  
                {
                    xtype: 'fieldset',
                    layout:'form',
                    border:false,
                    width: 550,
                    //title: 'MI',
                    hidden: false,
                    id: 'userDefinedTiming',

                    items:[{
                        xtype: 'textfield',
                        fieldLabel: 'Task Duration',
                        name: 'TAS_DURATION',
                        //value: _5625.scope.workflow.taskDetails.TAS_DURATION,
                        width : 100,
                        allowBlank:false
                    },
                    {
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
                        //value: _5625.scope.workflow.taskDetails.TAS_TIMEUNIT,
                        valueField:     'value',
                        store:          new Ext.data.JsonStore({
                            fields : ['name', 'value'],
                            data   : [
                            {
                                name : 'Days',
                                value: 'Days'
                            },

                            {
                                name : 'Hours',
                                value: 'Hours'
                            },
                            ]
                        })
                    },
                    {
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
                                value: 'Work Days'
                            },

                            {
                                name : 'Calendar Days',
                                value: 'Calendar Days'
                            },
                            ]
                        })
                    },
                    {
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
                        //value: _5625.scope.workflow.taskDetails.TAS_CALENDAR,
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
            },
            {
                title:'Permission',
                layout:'form',
                defaults: {
                    width: 260
                },
                defaultType: 'textfield',
                labelWidth: 170,
                items: [{
                    xtype: 'checkbox',
                    fieldLabel: 'Allow arbitary transfer (Ad hoc)',
                    checked: 'TAS_TYPE',
                    name: 'TAS_TYPE'
                }]
            },

            {
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
                            name: 'selectCaseTitle'
                            //anchor:'10%'
                        }]
                    }]
                },
                {
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
                            name: 'selectCaseDesc'
                            //anchor:'10%'
                        }]
                    }]
                }]
                },
           {
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
                        //value: _5625.scope.workflow.taskDetails.TAS_DEF_MESSAGE,
                        width: 400,
                        height : 180
                    }]
                }]
              
            }
            ]
        }
    });
    
  //Loading Task Details into the form
  taskPropertiesTabs.form.load({
        url:'proxyTaskPropertiesDetails.php?tid=' +taskId,
        method:'GET',
        waitMsg:'Loading',
        success:function(form, action) {
           //Ext.MessageBox.alert('Message', 'Loaded OK');
          //  setTaskAssignType(form);
        },
        failure:function(form, action) {
            Ext.MessageBox.alert('Message', 'Load failed');
        }
    });

    taskPropertiesTabs.render(document.body);
    _5625.scope.workflow.taskPropertiesTabs = taskPropertiesTabs;


    var window = new Ext.Window({
        title: 'Task: ',
        collapsible: false,
        maximizable: false,
        width: 600,
        height: 450,
        minWidth: 300,
        minHeight: 150,
        layout: 'fit',
        plain: true,
        bodyStyle: 'padding:5px;',
        buttonAlign: 'center',
        items: taskPropertiesTabs,
        buttons: [{
            text: 'Save',
            handler: function(){
                //var getstore = taskPropertiesTabs.getStore();
                //var getData = getstore.data.items;
                taskExtObj.saveTaskProperties(_5625);
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

TaskContext.prototype.saveTaskProperties= function(_5625)
{
                 var saveTaskform = _5625.scope.workflow.taskPropertiesTabs.getForm().getValues();
                 var taskId = _5625.scope.workflow.currentSelection.id;
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

TaskContext.prototype.stepTriggers = function(_5625)
{
    var pro_uid = _5625.scope.workflow.getUrlVars();
    var taskId  = _5625.scope.workflow.currentSelection.id;

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
        text: 'Add Triggers',
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
        text: 'Remove Triggers',
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

    var toolBar = new Ext.Toolbar({
        items: [addBtn, removeBtn]
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
                             onSelect: function(record, index){
                                var triggerStore  = triggerGrid.getStore();

                                if(typeof _5625.scope.workflow.currentRowTrigger == 'undefined')
                                        var selectedrowIndex = '0';
                                else
                                        selectedrowIndex     = _5625.scope.workflow.currentRowTrigger;    //getting Index of the row that has been edited

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
                },
                  {
                    header: 'Assign Condition',
                    //width: 50,
                    renderer: function(val){return '<input type="button" value="@@" id="'+val+'"/>';}
                  }
                ],
        sm: new Ext.grid.RowSelectionModel({
                singleSelect: true,
                listeners: {
                     rowselect: function(smObj, rowIndex, record) {
                        _5625.scope.workflow.currentRowTrigger = rowIndex;
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
    /*var window = new Ext.Window({
        title: 'Steps Of',
        collapsible: false,
        maximizable: false,
        width: 700,
        height: 500,
        //minWidth: 200,
        //autoHeight: true,
        minHeight : 150,
        layout    : 'fit',
        plain: true,
        bodyStyle: 'padding:5px;',
        buttonAlign: 'center',
        items: [treeGrid],
        buttons: [{
            text: 'Save',
            handler: function(){

            }
        },{
            text: 'Cancel',
            handler: function(){
                // when this button clicked,
                window.close();
            }
        }]
    });
    window.show();*/
}