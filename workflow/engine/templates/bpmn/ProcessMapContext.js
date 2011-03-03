ProcessMapContext=function(id){
Workflow.call(this,id);
};
ProcessMapContext.prototype=new Workflow;
ProcessMapContext.prototype.type="ProcessMap";

ProcessMapContext.prototype.editProcess= function()
   {
      var editProcess = new Ext.FormPanel({
        labelWidth  : 75, // label settings here cascade unless overridden
        frame       :false,
        buttonAlign : 'center',
        bodyStyle   : 'padding:5px 0 0 5px;',
        monitorValid: true,
        width       : 450,
        height      : 400,
        defaultType : 'textfield',
        items       : [{
                        xtype       :'fieldset',
                        title       : 'Process Information',
                        collapsible : false,
                        autoHeight  :true,
                        buttonAlign : 'center',
                        width       : 450,
                        defaultType : 'textfield',
                        items       : [{
                                        fieldLabel  : _('ID_TITLE'),
                                        name        : 'PRO_TITLE',
                                        width       : 300,
                                        allowBlank  : false
                                    },{
                                        xtype       : 'textarea',
                                        fieldLabel  : _('ID_DESCRIPTION'),
                                        name        : 'PRO_DESCRIPTION',
                                        width       : 300,
                                        height      : 150

                                    },{
                                        width           : 300,
                                        xtype           : 'combo',
                                        mode            : 'local',
                                        forceSelection  : true,
                                        triggerAction   : 'all',
                                        editable        : false,
                                        fieldLabel      : _('ID_CALENDAR'),
                                        name            : 'PRO_CALENDAR',
                                        hiddenName      : 'calendar',
                                        displayField    : 'name',
                                        valueField      : 'value',
                                        store           : new Ext.data.JsonStore({
                                                                fields : ['name', 'value'],
                                                                data   : [
                                                                         {name:'none',    value: ''},
                                                                         {name:'default',    value: '00000000000000000000000000000001'}
                                                                         ]
                                        })
                                    },{
                                        width           : 300,
                                        xtype           : 'combo',
                                        mode            : 'local',
                                        triggerAction   : 'all',
                                        forceSelection  : true,
                                        editable        : false,
                                        fieldLabel      : _('ID_CATEGORY'),
                                        name            : 'PRO_CATEGORY',
                                        hiddenName      : 'category',
                                        displayField    : 'CATEGORY_NAME',
                                        valueField      : 'CATEGORY_UID',
                                        value           : '--None--',
                                        store           :new Ext.data.JsonStore({
                                                                    fields : ['CATEGORY_NAME', 'CATEGORY_UID']
                                        })
                                    },{
                                        xtype       : 'checkbox',
                                        fieldLabel  : _('ID_PRO_DEBUG'),
                                        name        :'PRO_DEBUG',
                                        checked     : workflow.checkdebug
                                    }
                        ]
        }],buttons: [{
                text : _('ID_SAVE'),
                formBind : true,
                handler : function(form, action){
                              var getForm         = editProcess.getForm().getValues();
                              var pro_title       = getForm.PRO_TITLE;
                              var pro_description = getForm.PRO_DESCRIPTION;
                              var pro_calendar    = getForm.calendar;
                              var pro_category    = getForm.category;
                              var pro_debug       = getForm.PRO_DEBUG;
                  
                              if(pro_debug == 'on')
                                 pro_debug = 1;
                              else
                                 pro_debug = 0;
                                 var urlparams = '?action=saveProcess&data={"PRO_UID":"'+ pro_uid +'","PRO_CALENDAR":"'+ pro_calendar +'","PRO_CATEGORY":"'+ pro_category +'","PRO_DEBUG":"'+ pro_debug +'","PRO_DESCRIPTION":"'+ pro_description +'","PRO_TITLE":"'+ pro_title +'"}';
                                 Ext.Ajax.request({
                                     url: "bpmn/processes_Ajax.php"+ urlparams,
                                     success: function(response) {
                                         PMExt.notify( _('ID_STATUS') , _('ID_PROCESS_SAVE') );
                                         window.hide();
                                     }

                                 });
                }

        },{
            text: _('ID_CANCEL'),
            handler: function(){
                // when this button clicked,
                window.hide();
            }
        }]
      });

      editProcess.form.load({
            url:'bpmn/proxyExtjs.php?pid='+pro_uid+'&action=process_Edit',
            method:'GET',
            waitMsg:'Loading',
            success:function(form, action) {
                    if(action.result.data.PRO_DEBUG== 0)
                        workflow.checkdebug = false;
                    else
                        workflow.checkdebug = true;
                        window.show();
            },
            failure:function(form, action) {
                    PMExt.notify( _('ID_STATUS') , _('ID_LOAD_FAILED') );
            }
      });

      editProcess.render(document.body);
      
      var window = new Ext.Window({
        title: _('ID_EDIT_PROCESS'),
        collapsible: false,
        maximizable: false,
        width: 480,
        height: 380,
        minWidth: 300,
        minHeight: 200,
        layout: 'fit',
        autoScroll: true,
        plain: true,
        buttonAlign: 'center',
        items: editProcess
        });
      window.show();
   }

ProcessMapContext.prototype.exportProcess= function()
  {
   var exportProcessForm = new Ext.FormPanel({
    labelWidth    : 160, 
    frame         : false,
    height        : 200,
    defaultType   : 'textfield',
    width         : 400,
    buttonAlign   : 'center',
    items: [
            {
            xtype      : 'fieldset',
            bodyStyle     :'padding:10px 10px 10px 20px;',
            title      : 'Process Info',
            //width      : 370,
            collapsible: false,
            autoHeight : true,
            buttonAlign: 'center',
            items:[
                    {
                    xtype       : 'displayfield',
                    fieldLabel  : _('ID_PRO_TITLE'),
                    name        : 'PRO_TITLE'
                  },{
                    xtype       : 'displayfield',
                    fieldLabel  : _('ID_DESCRIPTION'),
                    name        : 'PRO_DESCRIPTION'
/*
                  },{
                    xtype       : 'displayfield',
                    fieldLabel  : _('ID_SIZE_IN_BYTES'),
                    name        : 'SIZE'
*/                    
                  },{
                    xtype       : 'displayfield',
                    fieldLabel  : _('ID_PM_FILENAME'),
                    //id          : 'PM_FILENAME',
                    name        : 'PM_FILENAME'
                  },{
                    xtype       : 'displayfield',
                    fieldLabel  : _('ID_XPDL_FILENAME'),
                    //id          : 'XPDL_FILENAME',
                    name        : 'XPDL_FILENAME'
                  }
                ]
    }],
    buttons: [{
    text: _('ID_CANCEL'),
    handler: function(){
            exportProcesswindow.hide();
            }
    }]
});

   exportProcessForm.render(document.body);
   workflow.exportProcessForm = exportProcessForm;
   exportProcessForm.form.load({
        url:'proxyProcesses_Export?pro_uid='+pro_uid,
        method:'GET',
        waitMsg:'Loading',
        success:function(form, action) {
                  var aData = action.result.data;
                  var fieldSet = workflow.exportProcessForm.items.items[0];
                  var fields = fieldSet.items.items;
                  form.findField('PM_FILENAME').setValue("<a href=\"" + aData.FILENAME_LINK + "\">" + aData.FILENAME + "<\/a>");
                  form.findField('XPDL_FILENAME').setValue("<a href=\"" + aData.FILENAME_LINKXPDL + "\">" + aData.FILENAMEXPDL + "<\/a>");
        },
        failure:function(form, action) {
        }
   });

   var exportProcesswindow = new Ext.Window({
        title      : _('ID_EXPORT_PROCESS'),
        collapsible: false,
        maximizable: false,
        sizeable   : false,
        width      : 420,
        height     : 210,
        resizable  : false,
        layout     : 'fit',
        plain      : true,
        buttonAlign: 'center',
        items      : exportProcessForm
        });
  
   workflow.exportProcesswindow = exportProcesswindow;
   exportProcesswindow.show();
}

ProcessMapContext.prototype.addTask= function()
    {
        var newShape = eval("new bpmnTask(workflow)");
        var xPos = workflow.contextX;
        var yPos = workflow.contextY;
        workflow.addFigure(newShape, xPos, yPos);
        newShape.actiontype = 'addTask';
        workflow.saveShape(newShape);      //Saving Annotations when user drags and drops it
    }

ProcessMapContext.prototype.horiLine= function()
    {
        PMExt.notify( _('ID_STATUS') , _('ID_HORIZONTAL_LINE') );
    }

ProcessMapContext.prototype.vertiLine= function()
    {
        PMExt.notify( _('ID_STATUS') , _('ID_VERTICAL_LINE') );
    }

ProcessMapContext.prototype.delLines= function()
    {
        PMExt.notify( _('ID_STATUS') , _('ID_DELETE_LINES') );
    }

ProcessMapContext.prototype.processPermission= function()
  {
  //Process Permission store code starts here
    var dbConnFields = Ext.data.Record.create([
            { name: 'OP_UID',type: 'string'},
            { name: 'LABEL',type: 'string'},
            { name: 'TASK_TARGET',type: 'string'},
            { name: 'GROUP_USER',type: 'string'},
            { name: 'TASK_SOURCE',type: 'string'},
            { name: 'PARTICIPATED',type: 'string'},
            { name: 'OBJECT_TYPE',type: 'string'},
            { name: 'OBJECT',type: 'string'},
            { name: 'ACTION',type: 'string'},
            { name: 'OP_CASE_STATUS',type: 'string'},
            { name: 'DYNAFORM',type: 'string'},
            { name: 'INPUT',type: 'string'},
            { name: 'OUTPUT',type: 'string'},
            { name: 'TAS_UID',type: 'string'},
            { name: 'OP_TASK_SOURCE',type: 'string'},
            { name: 'OP_PARTICIPATE',type: 'string'},
            { name: 'OP_OBJ_TYPE',type: 'string'},
            { name: 'OP_GROUP_USER',type: 'string'},
            { name: 'OBJ_NAME',type: 'string'},
            { name: 'OP_ACTION',type: 'string'},
            { name: 'USR_FULLNAME',type: 'string'},
            { name: 'DYNAFORM_NAME',type: 'string'},
            { name: 'INPUT_NAME',type: 'string'},
            { name: 'OUTPUT_NAME',type: 'string'}
    ]);

    //Creating different stores required for fields in form
    var selectField = Ext.data.Record.create([
                            { name: 'LABEL',type: 'string'},
                            { name: 'UID',type: 'string'}
    ]);

    var editor = new Ext.ux.grid.RowEditor({
        saveText: _('ID_UPDATE')
        });

    var btnCreate = new Ext.Button({
        id: 'btnCreate',
        text: _('ID_NEW'),
        iconCls: 'button_menu_ext ss_sprite ss_add',
        handler: function () {
            PermissionForm.getForm().reset();
            formWindow.show();
            
        }
    });

    var editProPermission = function() {
        editor.stopEditing();
        var rowSelected  = Ext.getCmp('permissiongrid').getSelectionModel().getSelections();
         if( rowSelected.length == 0 ) {
           PMExt.error('', _('ID_NO_SELECTION_WARNING'));
           return false;
       }
       var opUID = rowSelected[0].get('OP_UID');
       PermissionForm.form.load({
                url:'bpmn/proxyExtjs.php?pid='+pro_uid+'&op_uid=' +opUID+'&action=editObjectPermission',
                method:'GET',
                waitMsg:'Loading',
                success:function(form, action) {
                   formWindow.show();
                   if(action.result.data.OP_PARTICIPATE == 1)
                       form.findField('OP_PARTICIPATE').setValue('Yes');
                   else
                       form.findField('OP_PARTICIPATE').setValue('No');

                   if(action.result.data.OP_OBJ_TYPE == 'DYNAFORM')
                       Ext.getCmp('dynaform').show();
                   if(action.result.data.OP_OBJ_TYPE == 'INPUT')
                       Ext.getCmp('inputdoc').show();
                   if(action.result.data.OP_OBJ_TYPE == 'OUTPUT')
                       Ext.getCmp('outputdoc').show();
                },
                failure:function(form, action) {
                    PMExt.notify( _('ID_STATUS') , _('ID_LOAD_FAILED') );
                }
       });
    }

    var deleteProPermission = function(){
    ids = Array();

    editor.stopEditing();
    var rowsSelected = Ext.getCmp('permissiongrid').getSelectionModel().getSelections();

    if( rowsSelected.length == 0 ) {
      PMExt.error('', _('ID_NO_SELECTION_WARNING'));
      return false;
    }

    for(i=0; i<rowsSelected.length; i++)
      ids[i] = rowsSelected[i].get('OP_UID');

    ids = ids.join(',');
    PMExt.confirm(_('ID_CONFIRM'), _('ID_DELETE_INPUTDOCUMENT_CONFIRM'), function(){
         Ext.Ajax.request({
                        url   : '../processes/processes_DeleteObjectPermission.php?OP_UID='+ids,
                                  method: 'GET',
                        success: function(response) {
                          var result = Ext.util.JSON.decode(response.responseText);
                          if( result.success ){
                            PMExt.notify( _('ID_STATUS') , result.msg);
                            //Reloading store after deleting input document
                            PermissionStore.reload();
                          } else {
                            PMExt.error(_('ID_ERROR'), result.msg);
                          }
                        }
                      });
                    });
      }

  var btnEdit = new Ext.Button({
    id: 'btnEdit',
    text: _('ID_EDIT'),
    iconCls: 'button_menu_ext ss_sprite  ss_pencil',
    handler: editProPermission
  });

  var btnRemove = new Ext.Button({
    id: 'btnRemove',
    text: _('ID_DELETE'),
    iconCls: 'button_menu_ext ss_sprite ss_delete',
    handler: deleteProPermission
  });

  var tb = new Ext.Toolbar({
    items: [btnCreate, btnRemove, btnEdit]
  });

  var PermissionStore = new Ext.data.JsonStore({
    root         : 'data',
    totalProperty: 'totalCount',
    idProperty   : 'gridIndex',
    remoteSort   : true,
    fields       : dbConnFields,
    proxy        : new Ext.data.HttpProxy({
                        url: 'bpmn/proxyExtjs.php?pid='+pro_uid+'&action=getObjectPermission'
    })
  });
  PermissionStore.load({params:{start:0, limit:10}});

  var selectTaskStore = new Ext.data.JsonStore({
    root         : 'data',
    totalProperty: 'totalCount',
    idProperty   : 'gridIndex',
    remoteSort   : true,
    fields       : selectField,
    proxy: new Ext.data.HttpProxy({
      url: 'bpmn/proxyExtjs.php?pid='+pro_uid+'&action=getObjectPermissionType&objectType=tasks'
    })
  });

 var usersStore = new Ext.data.JsonStore({
    root         : 'data',
    totalProperty: 'totalCount',
    idProperty   : 'gridIndex',
    remoteSort   : true,
    fields       : selectField,
    proxy: new Ext.data.HttpProxy({
      url: 'bpmn/proxyExtjs.php?pid='+pro_uid+'&action=getObjectPermissionType&objectType=users'
    })
  });

 var dynaformStore = new Ext.data.JsonStore({
    root         : 'data',
    totalProperty: 'totalCount',
    idProperty   : 'gridIndex',
    remoteSort   : true,
    fields       : selectField,
    proxy: new Ext.data.HttpProxy({
      url: 'bpmn/proxyExtjs.php?pid='+pro_uid+'&action=getObjectPermissionType&objectType=dynaform'
    })
  });

 var inputDocStore = new Ext.data.JsonStore({
    root         : 'data',
    totalProperty: 'totalCount',
    idProperty   : 'gridIndex',
    remoteSort   : true,
    fields       : selectField,
    proxy: new Ext.data.HttpProxy({
      url: 'bpmn/proxyExtjs.php?pid='+pro_uid+'&action=getObjectPermissionType&objectType=input'
    })
  });

 var outputDocStore = new Ext.data.JsonStore({
    root         : 'data',
    totalProperty: 'totalCount',
    idProperty   : 'gridIndex',
    remoteSort   : true,
    fields       : selectField,
    proxy: new Ext.data.HttpProxy({
      url: 'bpmn/proxyExtjs.php?pid='+pro_uid+'&action=getObjectPermissionType&objectType=output'
    })
  });

  var PermissionForm =new Ext.FormPanel({
    collapsible: false,
    maximizable: true,
    width:360,
    //height: 30,
    monitorValid : true,
    bodyStyle : 'padding:10px 0 0 10px;',
    frame:false,
    plain: true,
    buttonAlign: 'center',
    items:[{
            width           :150,
            xtype           :'combo',
            mode            :'local',
            editable        :false,
            fieldLabel      :_('ID_STATUS_CASE'),
            triggerAction   :'all',
            forceSelection  : true,
            name            :'OP_CASE_STATUS',
            displayField    :'name',
            value           :'ALL',
            valueField      :'value',
            store           :new Ext.data.JsonStore({
                                                    fields : ['name', 'value'],
                                                    data   : [
                                                    {name : 'ALL',   value: '0'},
                                                    {name : 'DRAFT',   value: '1'},
                                                    {name : 'TO DO',   value: '2'},
                                                    {name : 'PAUSED',   value: '3'},
                                                    {name : 'COMPLETED',   value: '4'}]})
            },
            new Ext.form.ComboBox({
                fieldLabel: _('ID_TARGET_TASK'),
                name: 'TASK_TARGET_NAME',
                store: selectTaskStore,
                valueField:'LABEL',
                displayField:'LABEL',
                triggerAction: 'all',
                emptyText:'Select Target Task',
                editable: true,
                onSelect: function(record,index)
                  {
                   Ext.getCmp("TASK_TARGET").setValue(record.data.UID);
                   this.setValue(record.data[this.valueField || this.displayField]);
                   this.collapse();
                  }
            }),
            new Ext.form.ComboBox({
                fieldLabel: _('ID_GROUP_USERS'),
                //hiddenName:'popType',
                name: 'USR_FULLNAME',
                //autoload: true,
                store: usersStore,
                valueField:'LABEL',
                displayField:'LABEL',
                triggerAction: 'all',
                emptyText:'Select Group or Users',
                editable: true,
                onSelect: function(record,index)
                {
                   Ext.getCmp("GROUP_USER").setValue(record.data.UID);
                    this.setValue(record.data[this.valueField || this.displayField]);
                    this.collapse();
                }
            }),
            new Ext.form.ComboBox({
                fieldLabel: _('ID_ORIGIN_TASK'),
                name    : 'TASK_SOURCE_NAME',
                store: selectTaskStore,
                valueField:'LABEL',
                displayField:'LABEL',
                triggerAction: 'all',
                emptyText:'Select Source Task',
                editable: true,
                onSelect: function(record,index)
                {
                    //var taskUID = record.data.UID;
                    Ext.getCmp("TASK_SOURCE").setValue(record.data.UID);
                    this.setValue(record.data[this.valueField || this.displayField]);
                    this.collapse();
                }
            }),
            {
            width           :150,
            xtype           :'combo',
            mode            :'local',
            editable        :false,
            fieldLabel      :_('ID_PARTICIPATION_REQUIRED'),
            triggerAction   :'all',
            forceSelection  : true,
            name            :'OP_PARTICIPATE',
            displayField    :'name',
            value           :'Yes',
            valueField      :'value',
            store           :new Ext.data.JsonStore({
                                                    fields : ['name', 'value'],
                                                    data   : [
                                                    {name : 'Yes',   value: '0'},
                                                    {name : 'No',   value: '1'}]})
            },{
            width           :150,
            xtype           :'combo',
            mode            :'local',
            editable        :false,
            fieldLabel      :_('ID_TYPE'),
            triggerAction   :'all',
            forceSelection  : true,
            name            :'OP_OBJ_TYPE',
            displayField    :'name',
            value           :'ALL',
            valueField      :'value',
            store           :new Ext.data.JsonStore({
                                                    fields : ['name', 'value'],
                                                    data   : [
                                                    {name : 'ALL',   value: '0'},
                                                    {name : 'DYNAFORM',   value: '1'},
                                                    {name : 'INPUT',   value: '2'},
                                                    {name : 'OUTPUT',   value: '3'}]}),
            onSelect: function(record, index) {
                                 //Show-Hide Format Type Field
                                                if(record.data.value == '1')
                                                        {
                                                            Ext.getCmp("dynaform").show();
                                                            Ext.getCmp("inputdoc").hide();
                                                            Ext.getCmp("outputdoc").hide();
                                                        }
                                                else if(record.data.value == '2')
                                                        {
                                                            Ext.getCmp("inputdoc").show();
                                                            Ext.getCmp("dynaform").hide();
                                                            Ext.getCmp("outputdoc").hide();
                                                        }
                                                else if(record.data.value == '3')
                                                        {
                                                            Ext.getCmp("outputdoc").show();
                                                            Ext.getCmp("inputdoc").hide();
                                                            Ext.getCmp("dynaform").hide();
                                                        }
                                                else
                                                    {
                                                        Ext.getCmp("outputdoc").hide();
                                                        Ext.getCmp("inputdoc").hide();
                                                        Ext.getCmp("dynaform").hide();
                                                    }
                                                this.setValue(record.data[this.valueField || this.displayField]);
                                                this.collapse();
            }
            },{
            xtype: 'fieldset',
            id   : 'dynaform',
            hidden: true,
            border: false,
            items: [{
                    xtype: 'combo',
                    fieldLabel: _('ID_DYNAFORM'),
                    autoload: true,
                    width:200,
                    store: dynaformStore,
                    name: 'DYNAFORM_NAME',
                    valueField:'LABEL',
                    displayField:'LABEL',
                    triggerAction: 'all',
                    emptyText:'Select',
                    editable: true,
                    onSelect: function(record,index)
                        {
                        Ext.getCmp("DYNAFORMS").setValue(record.data.UID);
                        this.setValue(record.data[this.valueField || this.displayField]);
                        this.collapse();
                    }
            }]
            },{
            xtype: 'fieldset',
            id   : 'inputdoc',
            hidden: true,
            border: false,
            items: [{
                    xtype: 'combo',
                    fieldLabel: _('ID_INPUT_DOCUMENT'),
                    name: 'INPUT_NAME',
                    width:200,
                    autoload: true,
                    store: inputDocStore,
                    valueField:'UID',
                    displayField:'LABEL',
                    triggerAction: 'all',
                    emptyText:'Select',
                    editable: true,
                    onSelect: function(record,index)
                      {
                        Ext.getCmp("INPUTS").setValue(record.data.UID);
                        this.setValue(record.data[this.valueField || this.displayField]);
                        this.collapse();
                    }
               }]
            },{
               xtype: 'fieldset',
               id   : 'outputdoc',
               hidden: true,
               border: false,
               items: [{
                    xtype: 'combo',
                    fieldLabel: _('ID_OUTPUT_DOCUMENT'),
                    //hiddenName:'popType',
                    width:200,
                    autoload: true,
                    store: outputDocStore,
                    name: 'OUTPUT_NAME',
                    valueField:'LABEL',
                    displayField:'LABEL',
                    triggerAction: 'all',
                    emptyText:'Select',
                    editable: true,
                    onSelect: function(record,index)
                    {
                        //var taskUID = record.data.UID;
                        Ext.getCmp("OUTPUTS").setValue(record.data.UID);
                        this.setValue(record.data[this.valueField || this.displayField]);
                        this.collapse();
                    }

               }]
           },{
               width           :150,
               xtype           :'combo',
               mode            :'local',
               editable        :false,
               fieldLabel      :_('ID_PERMISSION'),
               triggerAction   :'all',
               forceSelection  : true,
               name            :'OP_ACTION',
               displayField    :'name',
               value           :'VIEW',
               valueField      :'value',
               store           :new Ext.data.JsonStore({
                                                        fields : ['name', 'value'],
                                                        data   : [
                                                        {name : 'VIEW',   value: 'VIEW'},
                                                        {name : 'BLOCK',   value: 'BLOCK'}]})
           },{
               xtype :'hidden',
               name :'TASK_TARGET',
               id :'TASK_TARGET'
           },{
               xtype:'hidden',
               name:'GROUP_USER',
               id:'GROUP_USER'
           },{
               xtype:'hidden',
               name:'TASK_SOURCE',
               id:'TASK_SOURCE'
           },{
               xtype:'hidden',
               name:'DYNAFORMS',
               id:'DYNAFORMS'
           },{
               xtype:'hidden',
               name:'INPUTS',
               id:'INPUTS'
           },{
               xtype:'hidden',
               name:'OUTPUTS',
               id:'OUTPUTS'
           },{
               id : 'OP_UID',
               xtype: 'hidden',
               name : 'OP_UID'
           }],
           buttons: [{
             text: _('ID_CREATE'),
             formBind    :true,
             handler: function(){
                var getForm         = PermissionForm.getForm().getValues();
                var TargetTask      = getForm.TASK_TARGET;
                var GroupUser       = getForm.GROUP_USER;
                var OriginTask      = getForm.TASK_SOURCE;
                var Dynaforms       = getForm.DYNAFORMS;
                var Inputs          = getForm.INPUTS;
                var Outputs         = getForm.OUTPUTS;
                var Status          = getForm.OP_CASE_STATUS;
                var Participation   = getForm.OP_PARTICIPATE;
                if(Participation == 'Yes')
                    Participation = 1;
                else
                    Participation = 0;

                var Type            = getForm.OP_OBJ_TYPE;
                var Permission      = getForm.OP_ACTION;
                var OP_UID          = getForm.OP_UID;
                if(OP_UID == "")
                    {
                Ext.Ajax.request({
                  url   : '../processes/processes_SaveObjectPermission.php',
                  method: 'POST',
                  params:{
                      PRO_UID         :pro_uid,
                      OP_OBJ_TYPE     :Type,
                      TAS_UID         :TargetTask,
                      OP_CASE_STATUS  :Status,
                      GROUP_USER      :GroupUser,
                      OP_TASK_SOURCE  :OriginTask,
                      OP_PARTICIPATE  :Participation,
                      OP_ACTION       :Permission,
                      DYNAFORMS       :Dynaforms,
                      INPUTS          :Inputs,
                      OUTPUTS         :Outputs
                  },
                  success: function(response) {
                      PMExt.notify( _('ID_STATUS') , _('ID_PROCESS_PERMISSIONS_CREATE') );
                      formWindow.hide();
                      PermissionStore.reload();
                      formWindow.hide();
                      PermissionStore.reload();
                  }
                });
           }
                else
                  {
                    Ext.Ajax.request({
                    url   : '../processes/processes_SaveEditObjectPermission.php',
                    method: 'POST',
                    params:{
                          PRO_UID         :pro_uid,
                          OP_UID          :OP_UID,
                          OP_OBJ_TYPE     :Type,
                          TAS_UID         :TargetTask,
                          OP_CASE_STATUS  :Status,
                          GROUP_USER      :GroupUser,
                          OP_TASK_SOURCE  :OriginTask,
                          OP_PARTICIPATE  :Participation,
                          OP_ACTION       :Permission,
                          DYNAFORMS       :Dynaforms,
                          INPUTS          :Inputs,
                          OUTPUTS         :Outputs
                    },
                    success: function(response) {
                      PMExt.notify( _('ID_STATUS') , _('ID_PROCESS_PERMISSIONS_EDIT') );
                      formWindow.hide();
                      PermissionStore.reload();
                      formWindow.hide();
                      PermissionStore.reload();
                    }
                   });
                }
             }
            },{
            text: _('ID_CANCEL'),
            handler: function(){
                formWindow.hide();
            }
        }]
  })

  var PermissionGridColumn =  new Ext.grid.ColumnModel({
     columns: [
              new Ext.grid.RowNumberer(),
                {
                id       : 'TASK_TARGET',
                header   : _('ID_TARGET_TASK'),
                dataIndex: 'TASK_TARGET',
                autoWidth: true,
                editable : false,
                width    :120,
                sortable : true,
                editor   : new Ext.form.TextField({
                })
              },{
                id       : 'GROUP_USER',
                header   : _('ID_GROUP_USER'),
                dataIndex: 'GROUP_USER',
                width    : 150,
                sortable : true,
                editor   : new Ext.form.TextField({
                })
              },{
                id       : 'TASK_SOURCE',
                header   : _('ID_ORIGIN_TASK'),
                dataIndex: 'TASK_SOURCE',
                width    : 120,
                sortable : true,
                editor   : new Ext.form.TextField({
                })
              },{
                id       : 'PARTICIPATED',
                header   : _('ID_PARTICIPATION'),
                dataIndex: 'PARTICIPATED',
                width    : 120,
                sortable : true,
                editor   : new Ext.form.TextField({
                })
              },{
                id       : 'OBJECT_TYPE',
                header   : _('ID_TYPE'),
                dataIndex: 'OBJECT_TYPE',
                width    : 100,
                editable : false,
                sortable : true,
                editor   : new Ext.form.TextField({
                })
              },{
                id       : 'OBJECT',
                header   : _('ID_OBJECT'),
                name     :'OBJECT',
                dataIndex: 'OBJECT',
                width    : 100,
                sortable : true,
                editor   : new Ext.form.TextField({
                })
              },{
                id       : 'ACTION',
                header   : _('ID_PERMISSION'),
                dataIndex: 'ACTION',
                width    : 120,
                sortable : true,
                editor   : new Ext.form.TextField({
                })
              },{
                id       : 'OP_CASE_STATUS',
                header   : _('ID_STATUS'),
                dataIndex: 'OP_CASE_STATUS',
                width    : 120,
                sortable : true,
                editor   : new Ext.form.TextField({
                })
              }]
  });
  
  var PermissionGrid = new Ext.grid.GridPanel({
    store       : PermissionStore,
    id          : 'permissiongrid',
    loadMask    : true,
    loadingText : 'Loading...',
    frame       : false,
    autoHeight  :false,
    clicksToEdit: 1,
    width       :450,
    minHeight   :400,
    height      :400,
    layout      : 'fit',
    cm          : PermissionGridColumn,
    stripeRows  : true,
    tbar        : tb,
    bbar        : new Ext.PagingToolbar({
                    pageSize    : 10,
                    store       : PermissionStore,
                    displayInfo : true,
                    displayMsg  : 'Displaying Process Permission {0} - {1} of {2}',
                    emptyMsg    : "No Process Permission to display",
                    items       :[]
    }),
    viewConfig  : {forceFit: true}
  });

//connecting context menu  to grid
  PermissionGrid.addListener('rowcontextmenu', onProcessPermissionMenu,this);

  //by default the right click is not selecting the grid row over the mouse
  //we need to set this four lines
  PermissionGrid.on('rowcontextmenu', function (grid, rowIndex, evt) {
    var sm = grid.getSelectionModel();
    sm.selectRow(rowIndex, sm.isSelected(rowIndex));
  }, this);

  //prevent default
  PermissionGrid.on('contextmenu', function (evt) {
      evt.preventDefault();
  }, this);

  function onProcessPermissionMenu(grid, rowIndex, e) {
    e.stopEvent();
    var coords = e.getXY();
    processPermisssionMenu.showAt([coords[0], coords[1]]);
  }

  var processPermisssionMenu = new Ext.menu.Menu({
    id: 'messageContextMenu',
    items: [{
        text: _('ID_EDIT'),
        iconCls: 'button_menu_ext ss_sprite  ss_pencil',
        handler: editProPermission
      },{
        text: _('ID_DELETE'),
        icon: '/images/delete.png',
        handler: deleteProPermission
      },{
        text: _('ID_UID'),
        handler: function(){
          var rowSelected = Ext.getCmp('permissiongrid').getSelectionModel().getSelected();
          workflow.createUIDButton(rowSelected.data.OP_UID);
        }
      }
    ]
  });
 var gridWindow = new Ext.Window({
        title       : _('ID_PROCESS_PERMISSIONS'),
        collapsible : false,
        maximizable : true,
        width       : 800,
        autoScroll  : true,
        height      : 380,
        layout      : 'fit',
        plain       : true,
        buttonAlign : 'center',
        items       : PermissionGrid
 });

 var formWindow = new Ext.Window({
        title       : _('ID_PERMISSION_NEW'),
        collapsible : false,
        maximizable : true,
        width       : 400,
        autoScroll  : true,
        height      : 320,
        plain       : true,
        buttonAlign : 'center',
        items       : PermissionForm
       });
  gridWindow.show();
}

ProcessMapContext.prototype.processSupervisors= function()
  {
   var processUserFields = Ext.data.Record.create([
    {name: 'PU_UID',type: 'string'},
    {name: 'USR_UID',type: 'string'},
    {name: 'PU_TYPE',type: 'string'},
    {name: 'USR_FIRSTNAME',type: 'string'},
    {name: 'USR_LASTNAME',type: 'string'},
    {name: 'USR_EMAIL',type: 'string'}
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
            var e = new processUserFields({
                 PU_UID: '',
                 USR_UID: '',
                 PU_TYPE: '',
                 //USR_FIRSTNAME: '',
                 USR_LASTNAME: '',
                 USR_EMAIL: ''
            });

            //storeUsers.reload();
            if(availableProcessesUser.data.items.length == 0)
                PMExt.notify( _('ID_STATUS') , _('ID_SUPERVISOR_UNAVAILABLE') );
            else
            {
                editor.stopEditing();
                processUser.insert(0, e);
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
                    var puID            = r.data.PU_UID;
                    var urlparams       = '?action=removeProcessUser&data={"PU_UID":"'+puID+'"}';

                    //if USR_UID is properly defined (i.e. set to valid value) then only delete the row
                    //else its a BLANK ROW for which Ajax should not be called.
                     if(r.data.PU_UID != "")
                     {
                        Ext.Ajax.request({
                          url   : 'bpmn/processes_Ajax.php'+urlparams,
                          method: 'GET',
                          success: function(response) {
                              PMExt.notify( _('ID_STATUS') , _('ID_SUPERVISOR_REMOVED') );
                              //Secondly deleting from Grid
                              processUser.remove(r);
                              //Reloading available user store
                              processUser.reload();
                              availableProcessesUser.reload();
                          }
                        });
                     }
                     else
                         processUser.remove(r);
                }
            }
        });

  var tb = new Ext.Toolbar({
            items: [btnAdd, btnRemove]
        });

  // create the Data Store of users that are already assigned to a process supervisor
  var processUser = new Ext.data.JsonStore({
            root         : 'data',
            totalProperty: 'totalCount',
            idProperty   : 'gridIndex',
            remoteSort   : true,
            fields       : processUserFields,
            proxy: new Ext.data.HttpProxy({
              url: 'bpmn/proxyExtjs?pid='+pro_uid+'&action=process_Supervisors'
            })
          });
  processUser.load({params:{start:0, limit:10}});

  // create the Data Store of users that are not assigned to a process supervisor
  var availableProcessesUser = new Ext.data.JsonStore({
                 root            : 'data',
                 url             : 'bpmn/proxyExtjs?pid='+pro_uid+'&action=availableProcessesSupervisors',
                 totalProperty   : 'totalCount',
                 idProperty      : 'gridIndex',
                 remoteSort      : false, //true,
                 autoLoad        : true,
                 fields          : processUserFields
              });


  var grid = new Ext.grid.GridPanel({
        store: processUser,
        id : 'mygrid',
        //cm: cm,
        loadMask: true,
        loadingText: 'Loading...',
        //renderTo: 'cases-grid',
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
                    id: 'USR_FIRSTNAME',
                    header: _('ID_FIRST_NAME'),
                    dataIndex: 'USR_FIRSTNAME',
                    width: 200,
                    sortable: true,
                    editor: new Ext.form.ComboBox({
                            xtype: 'combo',
                            fieldLabel: 'Users_groups',
                            hiddenName: 'number',
                            store        : availableProcessesUser,
                            displayField : 'USR_FIRSTNAME'  ,
                            valueField   : 'USR_FIRSTNAME',
                            name         : 'USR_FIRSTNAME',
                            triggerAction: 'all',
                            emptyText: 'Select Supervisor',
                            allowBlank: false,
                             onSelect: function(record, index){
                                var User = grid.getStore();

                                var selectedrowIndex = '0';

                                User.data.items[selectedrowIndex].data.PU_UID      = record.data.PU_UID;
                                User.data.items[selectedrowIndex].data.USR_UID      = record.data.USR_UID;
                                User.data.items[selectedrowIndex].data.PU_TYPE      = record.data.PU_TYPE;
                                User.data.items[selectedrowIndex].data.USR_LASTNAME  = record.data.USR_LASTNAME;
                                User.data.items[selectedrowIndex].data.USR_EMAIL  = record.data.USR_EMAIL;

                                Ext.getCmp("lastname").setValue(record.data.USR_LASTNAME);
                                this.setValue(record.data[this.valueField || this.displayField]);
                                this.collapse();
                              }
                        })
                },{
                    header: _('ID_LAST_NAME'),
                    dataIndex: 'USR_LASTNAME',
                    width: 200,
                    editable: false,
                    editor: new Ext.form.TextField({
                        id: 'lastname',
                        allowBlank : true
                    })
                }
                ],
        stripeRows: true,
        viewConfig: {forceFit: true},
        bbar: new Ext.PagingToolbar({
            pageSize: 10,
            store: processUser,
            displayInfo: true,
            displayMsg: 'Displaying Process Supervisor {0} - {1} of {2}',
            emptyMsg: "No Process Supervisor to display",
            items:[]
        }),
        tbar: tb
        });

        availableProcessesUser.load();

        editor.on({
          scope: this,
          afteredit: function(roweditor, changes, record, rowIndex) {

            var userID      = record.data.USR_UID;
            var urlparams   = '?action=assignProcessUser&data={"PRO_UID":"'+pro_uid+'","USR_UID":"'+userID+'"}';

            Ext.Ajax.request({
                    url: 'bpmn/processes_Ajax.php'+urlparams,
                    method: 'GET',
                    success: function (response) {      // When saving data success
                        PMExt.notify( _('ID_STATUS') , _('ID_SUPERVISOR_ASSIGNED') );
                        processUser.reload();
                        availableProcessesUser.reload();
                    },
                    failure: function () {      // when saving data failed
                        PMExt.notify( _('ID_STATUS') , _('ID_SUPERVISOR_FAILED') );
                        }
                 });
          }
        });

  var window = new Ext.Window({
        title: _('ID_SUPERVISOR'),
        collapsible: false,
        maximizable: false,
        width: 450,
        height: 350,
        minWidth: 200,
        minHeight: 150,
        autoScroll: true,
        layout: 'fit',
        plain: true,
        buttonAlign: 'center',
        items: grid
    });
    window.show();
}

ProcessMapContext.prototype.processDynaform= function()
{
  var supervisorDynaformsFields = Ext.data.Record.create([
    {name: 'DYN_TITLE',type: 'string'},
    {name: 'STEP_UID',type: 'string'},
    {name: 'STEP_UID_OBJ',type: 'string'},
    {name: 'STEP_TYPE_OBJ',type: 'string'},
    {name: 'STEP_POSITION',type: 'string'},
    {name: 'DYN_UID',type: 'string'}
  ]);
  var editor = new Ext.ux.grid.RowEditor({
    saveText: 'Update'
  });

  var btnAdd = new Ext.Button({
            id: 'btnAdd',
            text: _('ID_ASSIGN'),
            iconCls: 'button_menu_ext ss_sprite ss_add',
            handler: function(){
                var User = grid.getStore();
                var e = new supervisorDynaformsFields({
                     DYN_UID: '',
                     STEP_UID: '',
                     STEP_UID_OBJ: '',
                     STEP_TYPE_OBJ: '',
                     STEP_POSITION: ''
                });

                if(availableSupervisorDynaforms.data.items.length == 0)
                 Ext.MessageBox.alert ('Status','No Dynaforms are available. All Dynaforms have been already assigned.');
                else
                {
                    editor.stopEditing();
                    supervisorDynaforms.insert(0, e);
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
                    var dynUID          = r.data.DYN_UID;
                    var stepUID         = r.data.STEP_UID;
                    var sPos            = r.data.STEP_POSITION;

                    //if DYN_UID is properly defined (i.e. set to valid value) then only delete the row
                    //else its a BLANK ROW for which Ajax should not be called.
                     if(r.data.DYN_UID != "")
                     {
                        Ext.Ajax.request({
                          url   : '../steps/steps_SupervisorAjax.php',
                          method: 'POST',
                          params: {
                              STEP_UID           : stepUID,
                              PRO_UID            : pro_uid,
                              DYN_UID            : dynUID,
                              STEP_POSITION      : sPos,
                              action             : 'removeSupervisorDynaform'
                          },
                          success: function(response) {
                              PMExt.notify( _('ID_STATUS') , _('ID_DYANFORM_REMOVE') );
                              //Secondly deleting from Grid
                              supervisorDynaforms.remove(r);
                              //Reloading available user store
                              supervisorDynaforms.reload();
                              availableSupervisorDynaforms.reload();
                          }
                        });
                     }
                     else
                         supervisorDynaforms.remove(r);
                }
            }
        });

  var tb = new Ext.Toolbar({
            items: [btnAdd, btnRemove]
        });

  // create the Data Store of users that are already assigned to a process supervisor
  var supervisorDynaforms = new Ext.data.JsonStore({
            root         : 'data',
            totalProperty: 'totalCount',
            idProperty   : 'gridIndex',
            remoteSort   : true,
            fields       : supervisorDynaformsFields,
            proxy: new Ext.data.HttpProxy({
              url: 'bpmn/proxyExtjs?pid='+pro_uid+'&action=supervisorDynaforms'
            })
          });
  supervisorDynaforms.load({params:{start : 0 , limit : 10 }});

  // create the Data Store of users that are not assigned to a process supervisor
  var availableSupervisorDynaforms = new Ext.data.JsonStore({
             root            : 'data',
             url             : 'bpmn/proxyExtjs?pid='+pro_uid+'&action=availableSupervisorDynaforms',
             totalProperty   : 'totalCount',
             idProperty      : 'gridIndex',
             remoteSort      : false, //true,
             autoLoad        : true,
             fields          : supervisorDynaformsFields
          });
  availableSupervisorDynaforms.load();

  var grid = new Ext.grid.GridPanel({
        store: supervisorDynaforms,
        id : 'mygrid',
        //cm: cm,
        loadMask: true,
        loadingText: 'Loading...',
        //renderTo: 'cases-grid',
        frame: false,
        autoHeight:false,
        clicksToEdit: 1,
        minHeight:400,
        height   :400,
        width   :435,
        layout: 'fit',
        plugins: [editor],
        columns: [
                new Ext.grid.RowNumberer(),
                {
                    id: 'DYN_TITLE',
                    header: _('ID_TITLE'),
                    dataIndex: 'DYN_TITLE',
                    width: 200,
                    sortable: true,
                    editor: new Ext.form.ComboBox({
                            xtype: 'combo',
                            fieldLabel: 'Users_groups',
                            hiddenName: 'number',
                            store        : availableSupervisorDynaforms,
                            displayField : 'DYN_TITLE'  ,
                            valueField   : 'DYN_TITLE',
                            name         : 'DYN_TITLE',
                            triggerAction: 'all',
                            emptyText: 'Select Dynaform',
                            allowBlank: false,
                             onSelect: function(record, index){
                                var User = grid.getStore();
                                var selectedrowIndex = '0';

                                User.data.items[selectedrowIndex].data.STEP_UID         = record.data.STEP_UID;
                                User.data.items[selectedrowIndex].data.STEP_UID_OBJ     = record.data.STEP_UID_OBJ;
                                User.data.items[selectedrowIndex].data.STEP_TYPE_OBJ    = record.data.STEP_TYPE_OBJ;
                                User.data.items[selectedrowIndex].data.STEP_POSITION    = record.data.STEP_POSITION;
                                User.data.items[selectedrowIndex].data.DYN_UID          = record.data.DYN_UID;

                                this.setValue(record.data[this.valueField || this.displayField]);
                                this.collapse();
                              }
                        })
                }
                ],
        stripeRows: true,
        viewConfig: {forceFit: true},
        tbar: tb,
        bbar: new Ext.PagingToolbar({
            pageSize: 10,
            store: supervisorDynaforms,
            displayInfo: true,
            displayMsg: 'Displaying Supervisor Dynaform {0} - {1} of {2}',
            emptyMsg: "No Supervisor Dynaform to display",
            items:[]
            })
        });

        //availableSupervisorDynaforms.load();

        editor.on({
          scope: this,
          afteredit: function(roweditor, changes, record, rowIndex) {

            var dynUID      = record.data.DYN_UID;
            //var urlparams   = '?action=assignsupervisorDynaforms&data={"PRO_UID":"'+pro_uid+'","USR_UID":"'+userID+'"}';

            Ext.Ajax.request({
                    url   : '../steps/steps_SupervisorAjax.php',
                    method: 'POST',
                    params: {
                        action      : 'assignSupervisorDynaform',
                        PRO_UID     : pro_uid,
                        DYN_UID     : dynUID
                    },
                    success: function (response) {      // When saving data success
                        PMExt.notify( _('ID_STATUS') , _('ID_DYNAFORM_ASSIGN') );
                        supervisorDynaforms.reload();
                        availableSupervisorDynaforms.reload();
                    },
                    failure: function () {      // when saving data failed
                        PMExt.notify( _('ID_STATUS') , _('ID_DYNAFORM_ASSIGN_FAILED') );
                        }
                 });
          }
        });

        var window = new Ext.Window({
        title: _('ID_DYNAFORMS'),
        collapsible: false,
        maximizable: false,
        width: 480,
        height: 350,
        minWidth: 200,
        minHeight: 150,
        layout: 'fit',
        autoScroll: true,
        plain: true,
        buttonAlign: 'center',
        items: grid
    });
    window.show();
}

ProcessMapContext.prototype.processIODoc = function()
{
  var supervisorInputDocFields = Ext.data.Record.create([
            {name: 'INP_DOC_TITLE',type: 'string'},
            {name: 'STEP_UID',type: 'string'},
            {name: 'STEP_UID_OBJ',type: 'string'},
            {name: 'STEP_TYPE_OBJ',type: 'string'},
            {name: 'STEP_POSITION',type: 'string'},
            {name: 'INP_DOC_UID',type: 'string'}
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
                var e = new supervisorInputDocFields({
                     INP_DOC_UID: '',
                     STEP_UID: '',
                     STEP_UID_OBJ: '',
                     STEP_TYPE_OBJ: '',
                     STEP_POSITION: ''
                });

                //storeUsers.reload();
                if(availableSupervisorInputDoc.data.items.length == 0)
                    PMExt.notify( _('ID_STATUS') , _('ID_INPUT_UNAVAILABLE') );
                else
                {
                    editor.stopEditing();
                    supervisorInputDoc.insert(0, e);
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
                    var inputDocUID     = r.data.INP_DOC_UID;
                    var stepUID         = r.data.STEP_UID;
                    var sPos            = r.data.STEP_POSITION;

                    //if DYN_UID is properly defined (i.e. set to valid value) then only delete the row
                    //else its a BLANK ROW for which Ajax should not be called.
                     if(r.data.DYN_UID != "")
                     {
                        Ext.Ajax.request({
                          url   : '../steps/steps_SupervisorAjax.php',
                          method: 'POST',
                          params: {
                              STEP_UID           : stepUID,
                              PRO_UID            : pro_uid,
                              INP_DOC_UID        : inputDocUID,
                              STEP_POSITION      : sPos,
                              action             : 'removeSupervisorInput'
                          },
                          success: function(response) {
                              PMExt.notify( _('ID_STATUS') , _('ID_INPUT_REMOVE') );
                              //Secondly deleting from Grid
                              supervisorInputDoc.remove(r);
                              //Reloading available user store
                              supervisorInputDoc.reload();
                              availableSupervisorInputDoc.reload();
                          }
                        });
                     }
                     else
                         supervisorInputDoc.remove(r);
                }
            }
        });

  var tb = new Ext.Toolbar({
            items: [btnAdd, btnRemove]
        });

  // create the Data Store of users that are already assigned to a process supervisor
  var supervisorInputDoc = new Ext.data.JsonStore({
            root         : 'data',
            totalProperty: 'totalCount',
            idProperty   : 'gridIndex',
            remoteSort   : true,
            fields       : supervisorInputDocFields,
            proxy: new Ext.data.HttpProxy({
              url: 'bpmn/proxyExtjs?pid='+pro_uid+'&action=supervisorInputDoc'
            })
          });
  supervisorInputDoc.load({params:{start : 0 , limit : 10 }});

  // create the Data Store of users that are not assigned to a process supervisor
  var availableSupervisorInputDoc = new Ext.data.JsonStore({
                 root            : 'data',
                 url             : 'bpmn/proxyExtjs?pid='+pro_uid+'&action=availableSupervisorInputDoc',
                 totalProperty   : 'totalCount',
                 idProperty      : 'gridIndex',
                 remoteSort      : false, //true,
                 autoLoad        : true,
                 fields          : supervisorInputDocFields
              });
  availableSupervisorInputDoc.load();

  var grid = new Ext.grid.GridPanel({
        store: supervisorInputDoc,
        id : 'mygrid',
        //cm: cm,
        loadMask: true,
        loadingText: 'Loading...',
        //renderTo: 'cases-grid',
        frame: false,
        autoHeight:false,
        clicksToEdit: 1,
        minHeight:400,
        height   :400,
        width    :420,
        layout: 'fit',
        plugins: [editor],
        columns: [
                new Ext.grid.RowNumberer(),
                {
                    id: 'INP_DOC_TITLE',
                    header: _('ID_TITLE'),
                    dataIndex: 'INP_DOC_TITLE',
                    width: 200,
                    sortable: true,
                    editor: new Ext.form.ComboBox({
                            xtype: 'combo',
                            fieldLabel: 'Users_groups',
                            hiddenName: 'number',
                            store        : availableSupervisorInputDoc,
                            displayField : 'INP_DOC_TITLE'  ,
                            valueField   : 'INP_DOC_TITLE',
                            name         : 'INP_DOC_TITLE',
                            triggerAction: 'all',
                            emptyText: 'Select Input Document',
                            allowBlank: false,
                             onSelect: function(record, index){
                                var User = grid.getStore();
                                var selectedrowIndex = '0';

                                User.data.items[selectedrowIndex].data.STEP_UID      = record.data.STEP_UID;
                                User.data.items[selectedrowIndex].data.STEP_UID_OBJ  = record.data.STEP_UID_OBJ;
                                User.data.items[selectedrowIndex].data.STEP_TYPE_OBJ = record.data.STEP_TYPE_OBJ;
                                User.data.items[selectedrowIndex].data.STEP_POSITION = record.data.STEP_POSITION;
                                User.data.items[selectedrowIndex].data.INP_DOC_UID   = record.data.INP_DOC_UID;

                                this.setValue(record.data[this.valueField || this.displayField]);
                                this.collapse();
                              }
                        })
                }
                ],
        stripeRows: true,
        viewConfig: {forceFit: true},
        tbar: tb,
        bbar: new Ext.PagingToolbar({
            pageSize: 10,
            store: supervisorInputDoc,
            displayInfo: true,
            displayMsg: 'Displaying Supervisor Input Doc {0} - {1} of {2}',
            emptyMsg: "No Supervisor Input Doc to display",
            items:[]
            })
        });

        //availableSupervisorInputDoc.load();

        editor.on({
          scope: this,
          afteredit: function(roweditor, changes, record, rowIndex) {

            var inputDocUID      = record.data.INP_DOC_UID;
            //var urlparams   = '?action=assignsupervisorInputDoc&data={"PRO_UID":"'+pro_uid+'","USR_UID":"'+userID+'"}';

            Ext.Ajax.request({
                    url   : '../steps/steps_SupervisorAjax.php',
                    method: 'POST',
                    params: {
                        action      : 'assignSupervisorInput',
                        PRO_UID     : pro_uid,
                        INP_DOC_UID : inputDocUID
                    },
                    success: function (response) {      // When saving data success
                        PMExt.notify( _('ID_STATUS') , _('ID_INPUT_ASSIGN') );
                        supervisorInputDoc.reload();
                        availableSupervisorInputDoc.reload();
                    },
                    failure: function () {      // when saving data failed
                        PMExt.notify( _('ID_STATUS') , _('ID_INPUT_FAILED') );
                        }
                 });
          }
        });

  var window = new Ext.Window({
        title: _('ID_REQUEST_DOCUMENTS'),
        collapsible: false,
        maximizable: false,
        width: 430,
        height: 350,
        minWidth: 200,
        minHeight: 150,
        autoScroll: true,
        layout: 'fit',
        plain: true,
        buttonAlign: 'center',
        items: grid
    });
    window.show();
}

/*ProcessMapContext.prototype.caseTracker= function()
{
  Ext.MessageBox.alert('Status','Case Tracker');
}*/
ProcessMapContext.prototype.processFileManager= function()
{
}

ProcessMapContext.prototype.caseTrackerProperties= function()
{
  var PropertiesForm = new Ext.FormPanel({
        labelWidth: 75, // label settings here cascade unless overridden
        frame:false,
        monitorValid : true,
        width: 300,
        height: 300,
        bodyStyle : 'padding:10px 0 0 10px;',
        //defaults: {width: 350},
        defaultType: 'textfield',
                items: [{
                   xtype: 'fieldset',
                   layout:'column',
                   border:false,
                   width: 400,
                   //title: 'valueBased',
                   hidden: false,
                   id: 'evaluate',
                   items:[{
                       columnWidth:.6,
                       layout: 'form',
                       border:false,
                       items: [{
                       width           :120,
                       xtype           :'combo',
                       mode            :'local',
                       triggerAction   :'all',
                       forceSelection  :true,
                       editable        :false,
                       fieldLabel      :_('ID_MAP_TYPE'),
                       name            :'CT_MAP_TYPE',
                       displayField    :'name',
                       value           :'PROCESSMAP',
                       valueField      :'value',
                       store           :new Ext.data.JsonStore({
                                        fields : ['name', 'value'],
                                        data   :[{name: 'None', value:'NONE'},
                                                 {name: 'PROCESSMAP', value: 'PROCESSMAP'},
                                                 {name: 'STAGES', value:'STAGES'}]
                                        }),
                                        onSelect: function(record, index) {
                                 //Show-Hide Format Type Field
                                if(record.data.value == 'NONE')
                                        {Ext.getCmp("edit").hide();}
                                else if(record.data.value == 'PROCESSMAP')
                                        {Ext.getCmp("edit").hide();}
                                else
                                        {Ext.getCmp("edit").show();}
                                       this.setValue(record.data[this.valueField || this.displayField]);
                                         this.collapse();}
                       }]
                   },{
                       columnWidth:.3,
                       id:'edit',
                       hidden: true,
                       layout: 'form',
                       border:false,
                       items: [{
                             xtype: 'box',
                             autoEl: {tag: 'a', href: '../tracker/tracker_Ajax?PRO_UID=pro_uid&action=editStagesMap', children: [{tag: 'div', html: 'Edit'}]},
                             style: 'cursor:pointer;'
                       }]
                   }]
               },{
                        xtype: 'checkbox',
                        fieldLabel: _('ID_DERIVATION_HISTORY'),
                        name: 'CT_DERIVATION_HISTORY',
                        checked     : workflow.checkdebug
                },{
                        xtype: 'checkbox',
                        fieldLabel: _('ID_MESSAGES_HISTORY'),
                        name: 'CT_MESSAGE_HISTORY',
                       checked     : workflow.checkdebug
               }], buttons: [{
                text: _('ID_SAVE'),
                formBind    :true,
                handler: function(){
                var getForm             = PropertiesForm.getForm().getValues();
                //var pro_uid             = getForm.PRO_UID;
                var MapType             = getForm.CT_MAP_TYPE;
                var DerivationHistory   = getForm.CT_DERIVATION_HISTORY;
                var MessageHistory      = getForm.CT_MESSAGE_HISTORY;
                if(DerivationHistory == 'on')
                    DerivationHistory = 1;
                else
                    DerivationHistory = 0;

                if(MessageHistory == 'on')
                    MessageHistory = 1;
                else
                    MessageHistory = 0;

                   Ext.Ajax.request({
                       url   : '../tracker/tracker_Save.php',
                       method: 'POST',
                       params:{
                          PRO_UID               :pro_uid,
                          CT_MAP_TYPE           :MapType,
                          CT_DERIVATION_HISTORY :DerivationHistory,
                          CT_MESSAGE_HISTORY    :MessageHistory
                       },

                       success: function(response) {
                           PMExt.notify( _('ID_STATUS') , _('ID_CASE_PROPERTIES_SAVE') );
                           Propertieswindow.hide();
                          }
                   });
                }
        },{
           text: _('ID_CANCEL'),
           handler: function(){
           Propertieswindow.hide();
          }
        }]

   });

    PropertiesForm.form.load({
            url:'bpmn/proxyExtjs.php?pid='+pro_uid+'&action=getCaseTracker',
            method:'GET',
            waitMsg:'Loading',
            success:function(form, action) {
                    if(action.result.data.CT_DERIVATION_HISTORY == 0)
                        workflow.checkdebug = false;
                    else
                        workflow.checkdebug = true;
                        Propertieswindow.show();
                    if(action.result.data.CT_MESSAGE_HISTORY == 0)
                        workflow.checkdebug = false;
                    else
                        workflow.checkdebug = true;
                        Propertieswindow.show();

            },
            failure:function(form, action) {
                    PMExt.notify( _('ID_STATUS') , _('ID_LOAD_FAILED') );
            }
      });

      PropertiesForm.render(document.body);

   var Propertieswindow = new Ext.Window({
        title: _('ID_CASE_TRACKERS'),
        collapsible: false,
        maximizable: false,
        width: 300,
        height: 300,
        //minWidth: 300,
        //minHeight: 200,
        layout: 'fit',
        autoScroll: true,
        plain: true,
        buttonAlign: 'center',
        items: PropertiesForm
       
   });
  Propertieswindow.show();
}

ProcessMapContext.prototype.caseTrackerObjects= function()
  {
    var ProcMapObj= new ProcessMapContext();
    var ObjectFields = Ext.data.Record.create([
        {
            name: 'CTO_TITLE',
            type: 'string'
        },{
            name: 'CTO_UID',
            type: 'string'
        },{
            name: 'CTO_TYPE_OBJ',
            type: 'string'
        },{
            name:'CTO_CONDITION',
            type:'string'
        },{
            name:'CTO_POSITION',
            type:'string'
        },{
            name:'OBJECT_UID',
            type:'string'
        },{
            name:'OBJECT_TITLE',
            type:'string'
        },{
            name:'OBJECT_TYPE',
            type:'string'
        },{
            name:'CTO_UID_OBJ',
            type:'string'
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
         var User = Objectsgrid.getStore();
         var e = new ObjectFields({
           OBJECT_TITLE :'',
           OBJECT_TYPE  :'',
           OBJECT_UID   : '',
           CTO_CONDITION: ''
         });

         if(availableStore.data.items.length == 0)
             PMExt.notify( _('ID_STATUS') , _('ID_OBJECTS_UNAVAILABLE') );
            else
            {
                editor.stopEditing();
                assignedStore.insert(0, e);
                Objectsgrid.getView().refresh();
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
        var s = Objectsgrid.getSelectionModel().getSelections();
        for(var i = 0, r; r = s[i]; i++){
          //First Deleting assigned objects from Database
          var title       = r.data.CTO_TITLE;
          var UID         = r.data.CTO_UID;
          var type        = r.data.CTO_TYPE_OBJ;
          var objUID      = r.data.CTO_UID_OBJ;
          var condition   = r.data.CTO_CONDITION;
          var position    = r.data.CTO_POSITION
            //if UID is properly defined (i.e. set to valid value) then only delete the row
            //else its a BLANK ROW for which Ajax should not be called.
            if(r.data.USR_UID != "")
              {
              Ext.Ajax.request({
              url   : '../tracker/tracker_Ajax.php',
              method: 'POST',
              params: {
                      action          :'removeCaseTrackerObject',
                      CTO_UID         : UID,
                      PRO_UID         : pro_uid,
                      STEP_POSITION   : position
                      },

              success: function(response) {
                  PMExt.notify( _('ID_STATUS') , _('ID_OBJECT_REMOVE') );
                //Secondly deleting from Grid
                assignedStore.remove(r);
                //Reloading available user store
                assignedStore.reload();
                }
              });
            }
            else
             assignedStore.remove(r);
        }
      }
    });

    var btnObjectsCondition = new Ext.Button({
      id: 'btnCondition',
      text: _('ID_CONDITION'),
      handler: function (s) {
                workflow.variablesAction = 'grid';
                workflow.gridField       = 'CTO_CONDITION';
                var rowSelected          = Objectsgrid.getSelectionModel().getSelections();
                if(rowSelected == '')
                    workflow.gridObjectRowSelected = Objectsgrid;
                else
                    workflow.gridObjectRowSelected = rowSelected;
                var rowData = ProcMapObj.ExtVariables();
        }
    });

    var tb = new Ext.Toolbar({
      items: [btnAdd, btnRemove,btnObjectsCondition]
    });

    var assignedStore = new Ext.data.JsonStore({
      root          : 'data',
      totalProperty : 'totalCount',
      idProperty    : 'gridIndex',
      remoteSort    : true,
      fields        : ObjectFields,
      proxy         : new Ext.data.HttpProxy({
      url           : 'bpmn/proxyExtjs?pid='+pro_uid+'&action=getAssignedCaseTrackerObjects'
      })
    });
   assignedStore.load({params:{start : 0 , limit : 10 }});

    var availableStore = new Ext.data.JsonStore({
      root            : 'data',
      url             : 'bpmn/proxyExtjs?pid='+pro_uid+'&action=getAvailableCaseTrackerObjects',
      totalProperty   : 'totalCount',
      idProperty      : 'gridIndex',
      remoteSort      : false, //true,
      fields          : ObjectFields
    });
    availableStore.load();

    // create the Data Store of objects that are already assigned
    var Objectsgrid = new Ext.grid.GridPanel({
      store: assignedStore,
      id : 'mygrid',
      //cm: cm,
      loadMask: true,
      loadingText: 'Loading...',
      //renderTo: 'cases-grid',
      frame: false,
      autoHeight:false,
      clicksToEdit: 1,
      minHeight:400,
      height   :350,
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
              id: 'CTO_TITLE',
              header: _('ID_TITLE'),
              dataIndex: 'CTO_TITLE',
              width: 100,
              sortable: true,
              editor: new Ext.form.ComboBox({
                xtype: 'combo',
                store:availableStore,
                fieldLabel   : _('ID_TITLE'),
                hiddenName   : 'number',
                displayField : 'OBJECT_TITLE'  ,
                valueField   : 'OBJECT_TITLE',
                name         : 'OBJECT_TITLE',
                triggerAction: 'all',
                emptyText    : 'Select User or Group',
                allowBlank   : false,
                onSelect     : function(record, index){
                  var User = Objectsgrid.getStore();
                  var selectedrowIndex = '0';
                  User.data.items[selectedrowIndex].data.OBJECT_UID   = record.data.OBJECT_UID;
                  User.data.items[selectedrowIndex].data.OBJECT_TYPE  = record.data.OBJECT_TYPE;
                  User.data.items[selectedrowIndex].data.OBJECT_TITLE = record.data.OBJECT_TITLE;
                  this.setValue(record.data[this.valueField || this.displayField]);
                  this.collapse();
                }
              })
            },{
              header    : _('ID_TYPE'),
              dataIndex : 'CTO_TYPE_OBJ',
              editable  : false
            },{
                header : _('ID_CONDITION'),
                dataindex: 'CTO_CONDITION',
                name : 'CTO_CONDITION',
                editor: new Ext.form.TextField({
                    editable  : true
                })
            }/*,{
                sortable: false,
                renderer: function(val, meta, record)
                   {
                       //var recordData = Ext.util.JSON.encode(record);
                       return String.format("<input type='button' value='@@' onclick=workflow.ExtVariables('{0}');>",record.data.CTO_UID);
                  }
            }*/]
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
      tbar: tb,
      bbar: new Ext.PagingToolbar({
            pageSize: 10,
            store: assignedStore,
            displayInfo: true,
            displayMsg: 'Displaying Case Tracker Object {0} - {1} of {2}',
            emptyMsg: "No Case Tracker Object to display",
            items:[]
            })
    });

    editor.on({
      scope: this,
      afteredit: function(roweditor, changes, record, rowIndex) {
        var objType         = record.data.OBJECT_TYPE;
        var objUID          = record.data.OBJECT_UID;
        var objTitle        = record.data.OBJECT_TITLE;
        var cto_uid         = record.data.CTO_UID;
        var condition       = record.data.CTO_CONDITION;

        Ext.Ajax.request({
          url   : '../tracker/tracker_Ajax.php',
          method: 'POST',
          params:{
                PRO_UID     : pro_uid,
                OBJECT_TYPE : objType,
                OBJECT_UID  : objUID,
                action      :'assignCaseTrackerObject'
            },
          success: function (response)
            {
                cto_uid = response.responseText;
                    Ext.Ajax.request({
                      url   : '../tracker/tracker_ConditionsSave.php',
                      method: 'POST',
                      params:
                        {
                            PRO_UID         : pro_uid,
                            CTO_UID         : cto_uid,
                            CTO_CONDITION   : condition
                        },
                      success: function (response){
                          PMExt.notify( _('ID_STATUS') , _('ID_OBJECT_ASSIGNED') );
                          availableStore.reload();
                          assignedStore.reload();
                      }
                    })
            },
          failure: function () {      // when saving data failed
              PMExt.notify( _('ID_STATUS') , _('ID_OBJECT_FAILED') );
            }
        })
        //Updating the user incase if already assigned user has been replaced by other user
            if(changes != '' && typeof record.json != 'undefined')
            {
                var obj_type      = record.json.CTO_TYPE_OBJ;
                var obj_UID       = record.json.CTO_UID;
                var obj_title     = record.json.CTO_TITLE;
                var obj_uid       = record.json.CTO_UID;
                var obj_condition = record.json.CTO_CONDITION;
                var obj_position = record.json.CTO_POSITION;

                Ext.Ajax.request({
                      url: '../tracker/tracker_Ajax.php',
                      method: 'POST',
                      params: {
                      action          :'removeCaseTrackerObject',
                      CTO_UID         : obj_UID,
                      PRO_UID         : pro_uid,
                      STEP_POSITION   : obj_position
                      },
                      success: function(response) {
                          PMExt.notify( _('ID_STATUS') , _('ID_OBJECT_UPDATE') );
                          }
                    });
            }
             availableStore.reload();
             assignedStore.reload();
      }
    });

    var gridObjectWindow = new Ext.Window({
      title       : 'Objects',
      collapsible : false,
      maximizable : false,
      width       : 550,
      defaults    :{ autoScroll:true },
      height      : 380,
      minWidth    : 200,
      minHeight   : 150,
      plain       : true,
      items       : Objectsgrid,
      buttonAlign : 'center'
    });
    gridObjectWindow.show()
}

ProcessMapContext.prototype.ExtVariables = function()
{
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
                   url   : 'bpmn/proxyExtjs?pid='+pro_uid+'&action=getVariables&sFieldName=form[CTO_CONDITION]&sSymbol=@@'
            })
          });
  //varStore.load();

  var varColumns = new Ext.grid.ColumnModel({
            columns: [
                new Ext.grid.RowNumberer(),
                    {
                        id: 'FLD_NAME',
                        header: _('ID_VARIABLES'),
                        dataIndex: 'variable',
                        width: 170,
                        editable: false,
                        sortable: true
                    },{
                        id: 'PRO_VARIABLE',
                        header: _('ID_LABEL'),
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
        bodyStyle : 'padding:10px 0 0 10px;',
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
                        title:_('ID_ALL_VARIABLES'),
                        id   :'allVar',
                        layout:'form',
                        listeners: {
                            activate: function(tabPanel){
                                                        // use {@link Ext.data.HttpProxy#setUrl setUrl} to change the URL for *just* this request.
                                                        var link = 'bpmn/proxyExtjs?pid='+pro_uid+'&action=getVariables&type='+tabPanel.id+'&sFieldName=form[CTO_CONDITION]&sSymbol=@@';
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
                                   var objectSelected = workflow.variablesAction;
                                   switch(objectSelected)
                                        {
                                           case 'grid':
                                               var getObjectGridRow = workflow.gridObjectRowSelected;
                                               var FieldSelected    = workflow.gridField;
                                               //getting selected row of variables
                                               var rowSelected      = this.getSelectionModel().getSelected();
                                               var rowLabel         = rowSelected.data.variable;

                                               //Assigned new object with condition
                                               if(typeof getObjectGridRow.colModel != 'undefined')
                                                   getObjectGridRow.colModel.config[3].editor.setValue(rowLabel);
                                               //Assigning / updating Condition for a row
                                               else
                                                       getObjectGridRow[0].set(FieldSelected,rowLabel);

                                                   if(FieldSelected=='CTO_CONDITION')
                                                       {
                                                   Ext.Ajax.request({
                                                                      url   : '../tracker/tracker_ConditionsSave.php',
                                                                      method: 'POST',
                                                                      params:
                                                                        {
                                                                            PRO_UID         : pro_uid,
                                                                            CTO_UID         : getObjectGridRow[0].data.CTO_UID,
                                                                            CTO_CONDITION   : getObjectGridRow[0].data.CTO_CONDITION
                                                                        },
                                                                      success: function (response){
                                                                        Ext.MessageBox.alert ('Status','Objects has been edited successfully ');
                                                                       }
                                                                    })
                                                       }
                                                     else if (FieldSelected=='STEP_CONDITION')
                                                         {
                                                   Ext.Ajax.request({
                                                                      url   : '../steps/conditions_Save.php',
                                                                      method: 'POST',
                                                                      params:
                                                                        {
                                                                            PRO_UID         : pro_uid,
                                                                            STEP_UID         : getObjectGridRow[0].data.STEP_UID,
                                                                            STEP_CONDITION   : getObjectGridRow[0].data.STEP_CONDITION
                                                                        },
                                                                      success: function (response){
                                                                        Ext.MessageBox.alert ('Status','Objects has been edited successfully ');
                                                                       }
                                                                    })
                                                         }
                                                    else if (FieldSelected=='ST_CONDITION')
                                                         {
                                                   Ext.Ajax.request({
                                                                      url   : '../steps/steps_Ajax.php',
                                                                      method: 'POST',
                                                                      params:
                                                                        {
                                                                            action          : 'saveTriggerCondition',
                                                                            PRO_UID         : pro_uid,
                                                                            STEP_UID        : getObjectGridRow[0].data.STEP_UID,
                                                                            ST_CONDITION    : getObjectGridRow[0].data.STEP_CONDITION,
                                                                            TAS_UID         : taskId,
                                                                            TRI_UID         : getObjectGridRow[0].data.TRI_UID,
                                                                            ST_TYPE         : getObjectGridRow[0].data.ST_TYPE

                                                                        },
                                                                      success: function (response){
                                                                        Ext.MessageBox.alert ('Status','Objects has been edited successfully ');
                                                                       }
                                                                    })
                                                         }

                                               window.hide();


                                               break;
                                           case 'form':
                                                FormSelected     = workflow.formSelected;
                                                rowSelected      = this.getSelectionModel().getSelected();
                                                FieldSelected    =  workflow.fieldName;
                                                rowLabel         = rowSelected.data.variable;
                                               var value = FormSelected.getForm().findField(FieldSelected).setValue(rowLabel);
                                               window.hide();
                                               break;

                                        }

                                   }
                              }
                        }]
                },{
                title:_('ID_SYSTEM'),
                id:'system',
                layout:'form',
                listeners:{
                    activate: function(tabPanel){
                                                // use {@link Ext.data.HttpProxy#setUrl setUrl} to change the URL for *just* this request.
                                                var link = 'bpmn/proxyExtjs?pid='+pro_uid+'&action=getVariables&type='+tabPanel.id+'&sFieldName=form[CTO_CONDITION]&sSymbol=@@';
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
                                   var objectSelected = workflow.variablesAction;
                                   switch(objectSelected)
                                        {
                                           case 'grid':
                                               var getObjectGridRow = workflow.gridObjectRowSelected;
                                               var FieldSelected    = workflow.gridField;
                                               //getting selected row of variables
                                               var rowSelected      = this.getSelectionModel().getSelected();
                                               var rowLabel         = rowSelected.data.variable;
                                               //Assigned new object with condition
                                               if(typeof getObjectGridRow.colModel != 'undefined')
                                                   getObjectGridRow.colModel.config[3].editor.setValue(rowLabel);
                                               //Assigning / updating Condition for a row
                                               else
                                                   getObjectGridRow[0].set(FieldSelected,rowLabel);
                                                   if(CTO_UID!='')
                                                       {
                                                   Ext.Ajax.request({
                                                                      url   : '../tracker/tracker_ConditionsSave.php',
                                                                      method: 'POST',
                                                                      params:
                                                                        {
                                                                            PRO_UID         : pro_uid,
                                                                            CTO_UID         : getObjectGridRow[0].data.CTO_UID,
                                                                            CTO_CONDITION   : getObjectGridRow[0].data.CTO_CONDITION
                                                                        },
                                                                      success: function (response){
                                                                        Ext.MessageBox.alert ('Status','Objects has been edited successfully ');
                                                                       }
                                                                    })
                                               window.hide();
                                                       }

                                               break;
                                           case 'form':
                                                FormSelected     = workflow.formSelected;
                                                rowSelected      = this.getSelectionModel().getSelected();
                                                FieldSelected    =  workflow.fieldName;
                                                rowLabel         = rowSelected.data.variable;
                                                var value = FormSelected.getForm().findField(FieldSelected).setValue(rowLabel);
                                                window.hide();
                                               break;

                                        }

                                   }
                              }
                }]
                },{
                title:_('ID_CASESLIST_APP_PRO_TITLE'),
                id   :'process',
                layout:'form',
                listeners: {
                    activate: function(tabPanel){
                                                // use {@link Ext.data.HttpProxy#setUrl setUrl} to change the URL for *just* this request.
                                                var link = 'bpmn/proxyExtjs?pid='+pro_uid+'&action=getVariables&type='+tabPanel.id+'&sFieldName=form[CTO_CONDITION]&sSymbol=@@';
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
                                   var objectSelected = workflow.variablesAction;
                                   switch(objectSelected)
                                        {
                                           case 'grid':
                                               var getObjectGridRow = workflow.gridObjectRowSelected;
                                               var FieldSelected    = workflow.gridField;
                                               //getting selected row of variables
                                               var rowSelected      = this.getSelectionModel().getSelected();
                                               var rowLabel         = rowSelected.data.variable;
                                               //Assigned new object with condition
                                               if(typeof getObjectGridRow.colModel != 'undefined')
                                                   getObjectGridRow.colModel.config[3].editor.setValue(rowLabel);
                                               //Assigning / updating Condition for a row
                                               else
                                                   getObjectGridRow[0].set(FieldSelected,rowLabel);
                                                   Ext.Ajax.request({
                                                                      url   : '../tracker/tracker_ConditionsSave.php',
                                                                      method: 'POST',
                                                                      params:
                                                                        {
                                                                            PRO_UID         : pro_uid,
                                                                            CTO_UID         : getObjectGridRow[0].data.CTO_UID,
                                                                            CTO_CONDITION   : getObjectGridRow[0].data.CTO_CONDITION
                                                                        },
                                                                      success: function (response){
                                                                        Ext.MessageBox.alert ('Status','Objects has been edited successfully ');
                                                                       }
                                                                    })
                                               window.hide();
                                               break;
                                           case 'form':
                                                FormSelected     = workflow.formSelected;
                                                rowSelected      = this.getSelectionModel().getSelected();
                                                FieldSelected    =  workflow.fieldName;
                                                rowLabel         = rowSelected.data.variable;
                                               var value = FormSelected.getForm().findField(FieldSelected).setValue(rowLabel);
                                               window.hide();
                                               break;

                                        }

                                   }
                              }
                }]
                }]
            }

  });

  var window = new Ext.Window({
        title: _('ID_VARIABLES'),
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
