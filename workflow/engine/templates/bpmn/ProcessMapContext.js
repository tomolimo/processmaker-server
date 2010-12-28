ProcessMapContext=function(id){
Workflow.call(this,id);
};
ProcessMapContext.prototype=new Workflow;
ProcessMapContext.prototype.type="ProcessMap";

ProcessMapContext.prototype.editProcess= function(_5678)
{
        var editProcessData     = _5678.scope.workflow.processEdit;
        var processCategoryData = _5678.scope.workflow.processCategory;
        var debug               = editProcessData.PRO_DEBUG;
        var pro_category        = editProcessData.PRO_CATEGORY;
        var pro_category_label  = editProcessData.PRO_CATEGORY_LABEL;
        var checkDebug = true;
        if(debug  == '0')
            checkDebug = false;

            var processCalendar = new Array();
            processCalendar[0]  = new Array();
            processCalendar[1]  = new Array();

            processCalendar[0].name  = 'None';
            processCalendar[0].value = '';
            processCalendar[1].name  = 'Default';
            processCalendar[1].value = '00000000000000000000000000000001';

        //var processName = processInfo.title.label
        var editProcess = new Ext.FormPanel({
        labelWidth: 75, // label settings here cascade unless overridden
        frame:true,
        bodyStyle:'padding:5px 5px 0',
        width: 500,
        height: 400,
        defaults: {width: 350},
        defaultType: 'textfield',
        items: [{
                xtype:'fieldset',
                title: 'Process Information',
                collapsible: false,
                autoHeight:true,
                buttonAlign : 'center',
                width: 450,
                defaultType: 'textfield',
                items: [{
                        fieldLabel: 'Title',
                        name: 'title',
                        value: editProcessData.PRO_TITLE,
                        allowBlank:false
                    },{
                        xtype: 'textarea',
                        fieldLabel: 'Description',
                        name: 'description',
                        value: editProcessData.PRO_DESCRIPTION,
                        width: 300,
                        height : 150

                    },{
                        width:          100,
                        xtype:          'combo',
                        mode:           'local',
                        value:          editProcessData.PRO_CALENDAR,
                        forceSelection: true,
                        triggerAction:  'all',
                        editable:       false,
                        fieldLabel:     'Calendar',
                        name:           'calendar',
                        hiddenName:     'calendar',
                        displayField:   'name',
                        valueField:     'value',
                        store:          new Ext.data.JsonStore({
                                                fields : ['name', 'value'],
                                                data   : processCalendar
                                            })
                    }, {
                        width:          100,
                        xtype:          'combo',
                        mode:           'local',
                        value:          editProcessData.PRO_CATEGORY,
                        triggerAction:  'all',
                        forceSelection: true,
                        editable:       false,
                        fieldLabel:     'Category',
                        name:           'category',
                        hiddenName:     'category',
                         displayField:   'CATEGORY_NAME',
                        valueField:     'CATEGORY_UID',
                        store:          new Ext.data.JsonStore({
                                                fields : ['CATEGORY_NAME', 'CATEGORY_UID'],
                                                data   :processCategoryData
                                            })
                    },{
                        xtype: 'checkbox',
                        fieldLabel: 'Debug',
                        name: 'debug',
                        checked:checkDebug
                    }
                ]
        }]
    });

    editProcess.render(document.body);
    _5678.scope.workflow.editProcessForm = editProcess;

     var window = new Ext.Window({
        title: 'Edit Process',
        collapsible: false,
        maximizable: false,
        width: 500,
        height: 400,
        minWidth: 300,
        minHeight: 200,
        layout: 'fit',
        plain: true,
        bodyStyle: 'padding:5px;',
        buttonAlign: 'center',
        items: editProcess,
        buttons: [{
            text: 'Save',
            handler: function(){
                //waitMsg: 'Saving...',       // Wait Message
                  var fields          = editProcess.items.items;
                  var pro_title       = fields[0].items.items[0].getValue();
                  var pro_description = fields[0].items.items[1].getValue();
                  var pro_calendar    = fields[0].items.items[2].getValue();
                  var pro_category    = fields[0].items.items[3].getValue();
                  var pro_debug       = fields[0].items.items[4].getValue();
                  if(pro_debug == true)
                     pro_debug = '1';
                 else
                     pro_debug = '0';

                  var pro_uid = _5678.scope.workflow.getUrlVars();

                  var urlparams = '?action=saveProcess&data={"PRO_UID":"'+ pro_uid +'","PRO_CALENDAR":"'+ pro_calendar +'","PRO_CATEGORY":"'+ pro_category +'","PRO_DEBUG":"'+ pro_debug +'","PRO_DESCRIPTION":"'+ pro_description +'","PRO_TITLE":"'+ pro_title +'",}';
                  Ext.Ajax.request({
                    url: "processes_Ajax.php"+ urlparams,
                    success: function(response) {
                        window.close();
                    },
                    failure: function(){
                        Ext.Msg.alert ('Failure');
                    }
                });
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

ProcessMapContext.prototype.exportProcess= function()
{
  workflow.FILENAME_LINK = '';
  workflow.FILENAME_LINKXPDL = '';

  /*var exportProcessFields = Ext.data.Record.create([
    {
       name: 'PRO_TITLE',
       type: 'string'
    },{
       name: 'PRO_DESCRIPTION',
       type: 'string'
    },{
       name: 'SIZE',
       type: 'string'
    },{
       name: 'File',
       type: 'string'
    }]);

  var pro_uid = workflow.getUrlVars();
  exportProcess = new Ext.data.JsonStore
  ({
      root         : 'data',
      totalProperty: 'totalCount',
      idProperty   : 'gridIndex',
      remoteSort   : true,
      fields       : exportProcessFields,
      proxy        : new Ext.data.HttpProxy({
      url          : 'proxyProcesses_Export?pro_uid='+pro_uid
     })
  });
          //taskUsers.setDefaultSort('LABEL', 'asc');
  exportProcess.load();*/


  var exportProcessForm = new Ext.FormPanel({
  labelWidth    : 120, // label settings here cascade unless overridden
  frame         : true,
  title         : '',
  bodyStyle     : 'padding:5px 5px 0',
  width         : 500,
  height        : 400,
  defaultType   : 'textfield',
  buttonAlign   : 'center',
  items: [
           {
             xtype      :'fieldset',
             title      : 'Process Info',
             collapsible: false,
             autoHeight :true,
             buttonAlign: 'center',
             defaults   : {width: 210},
             //defaultType: 'textfield',
             items: [
                      {
                        xtype       : 'textfield',
                        fieldLabel  : 'Process Title',
                        name        : 'PRO_TITLE',
                        readOnly  :true
                      },{
                        xtype       : 'textfield',
                        fieldLabel  : 'Description',
                        name        : 'PRO_DESCRIPTION',
                        readOnly  :true
                      },{
                        xtype       : 'textfield',
                        fieldLabel  : 'Size in bytes',
                        name        : 'SIZE',
                        readOnly  :true
                      },{
                        xtype       : 'textfield',
                        fieldLabel  : 'File',
                        name        : 'FILENAME_LINK',
                        readOnly  :true
                      },{
                        xtype       : 'textfield',
                        fieldLabel  : 'File XPDL',
                        name        : 'FILENAME_LINKXPDL',
                        dataIndex   : 'FILENAME_LINKXPDL',
                        readOnly  :true
                      },{
                        xtype   : 'button',
                        name    : 'FILENAME_LINK',
                        html    : '<a href=\"http:\/\/www.google.at\">Link<\/a>',
                        width   :100
                      },{
                         sortable: false,
                            renderer: function()
                            {
                                return String.format("<a href=\"http:\/\/www.google.at\">x<\/a>");
                            }
                      }
                  ]
           }]

            });

  exportProcessForm.render(document.body);
  var pro_uid = workflow.getUrlVars();
  workflow.exportProcessForm = exportProcessForm;
  exportProcessForm.form.load({
    url:'proxyProcesses_Export?pro_uid='+pro_uid,
    method:'GET',
    waitMsg:'Loading',
    success:function(form, action) {
      var aData = action.result.data;
      var fieldSet = workflow.exportProcessForm.items.items[0];
      var fields = fieldSet.items.items;

      var link = new Ext.form.TextField({
          xtype   : 'button',
          name    : 'FILENAME_LINK',
          html    : '<a href=\"http:\/\/www.google.at\">Link<\/a>',
          width   :100
      });
      workflow.exportProcessForm.add(link);
     //this.add(form);
     //this.doLayout();

      fields[5].render = '<a href=\"http:\/\/www.google.com\">x<\/a>';
      workflow.FILENAME_LINK = aData.FILENAME_LINK;
      //workflow.FILENAME_LINKXPDL = aData.FILENAME_LINKXPDL;
    },
    failure:function(form, action) {
    //  Ext.MessageBox.alert('Message', 'Load failed');
    }
  });
  var exportProcesswindow = new Ext.Window({
    title      : 'Export Process',
    collapsible: false,
    maximizable: false,
    width      : 450,
    height     : 450,
    minWidth   : 300,
    minHeight  : 200,
    layout     : 'fit',
    plain      : true,
    bodyStyle  : 'padding:5px;',
    buttonAlign: 'center',
    items      : exportProcessForm
  });
   workflow.exportProcesswindow = exportProcesswindow;
   exportProcesswindow.show();
}

ProcessMapContext.prototype.addTask= function()
{
  Ext.MessageBox.alert('Status','Add Task');
}

ProcessMapContext.prototype.horiLine= function()
{
  Ext.MessageBox.alert('Status','Horizontal Line');
}

ProcessMapContext.prototype.vertiLine= function()
{
  Ext.MessageBox.alert('Status','Vertical Line');
}

ProcessMapContext.prototype.delLines= function()
{
  Ext.MessageBox.alert('Status','Delete All Lines');
}

ProcessMapContext.prototype.processPermission= function()
{
  var pro_uid = workflow.getUrlVars();
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
            { name: 'OP_CASE_STATUS',type: 'string'}
        ]);

var PermissionStore = new Ext.data.JsonStore({
            root         : 'data',
            totalProperty: 'totalCount',
            idProperty   : 'gridIndex',
            remoteSort   : true,
            fields       : dbConnFields,
            proxy: new Ext.data.HttpProxy({
              url: 'proxyObjectPermissions.php?pid='+pro_uid
            })
          });
 PermissionStore.load();

var PermissionGridColumn =  new Ext.grid.ColumnModel({
      columns: [
                new Ext.grid.RowNumberer(),
                    {
                        id: 'TASK_TARGET',
                        header: 'Target Task',
                        dataIndex: 'TASK_TARGET',
                        autoWidth: true,
                        editable: false,
                        sortable: true,
                        editor: new Ext.form.TextField({
                            //allowBlank: false
                            })
                    },{
                        id: 'GROUP_USER',
                        header: 'Group or Users',
                        dataIndex: 'GROUP_USER',
                        //width: 100,
                        sortable: true,
                        editor: new Ext.form.TextField({
                            //allowBlank: false
                            })
                    },{
                        id: 'TASK_SOURCE',
                        header: 'Origin Task',
                        dataIndex: 'TASK_SOURCE',
                        //width: 100,
                        sortable: true,
                        editor: new Ext.form.TextField({
                            //allowBlank: false
                            })
                    },{
                        id: 'PARTICIPATED',
                        header: 'Participation',
                        dataIndex: 'PARTICIPATED',
                        width: 100,
                        sortable: true,
                        editor: new Ext.form.TextField({
                            //allowBlank: false
                            })
                    },{
                        id: 'OBJECT_TYPE',
                        header: 'Type',
                        dataIndex: 'OBJECT_TYPE',
                        //width: 100,
                        editable: false,
                        sortable: true,
                        editor: new Ext.form.TextField({
                            //allowBlank: false
                            })
                    },{
                        id: 'OBJECT',
                        header: 'Object',
                        dataIndex: 'OBJECT',
                        //width: 100,
                        sortable: true,
                        editor: new Ext.form.TextField({
                            //allowBlank: false
                            })
                    },{
                        id: 'ACTION',
                        header: 'Permission',
                        dataIndex: 'ACTION',
                        //width: 100,
                        sortable: true,
                        editor: new Ext.form.TextField({
                            //allowBlank: false
                            })
                    },{
                        id: 'OP_CASE_STATUS',
                        header: 'Status',
                        dataIndex: 'OP_CASE_STATUS',
                        width: 100,
                        sortable: true,
                        editor: new Ext.form.TextField({
                            //allowBlank: false
                            })
                    }
                ]
     });
  var btnCreate = new Ext.Button({
            id: 'btnCreate',
            text: 'New',
            iconCls: 'application_add',
            handler: function () {
                formWindow.show();
            }
  })
var tb = new Ext.Toolbar({
            items: [btnCreate]
        });

  var btnAdd = new Ext.Button({
            id: 'btnAdd',
            text: 'New',
            iconCls: 'application_add',
            handler: function () {
                formWindow.show();
            }
  })

  var PermissionGrid = new Ext.grid.GridPanel({
        store: PermissionStore,
        id : 'mygrid',
        loadMask: true,
        loadingText: 'Loading...',
        //renderTo: 'cases-grid',
        frame: false,
        autoHeight:false,
        clicksToEdit: 1,
        width:450,
        minHeight:400,
        height   :400,
        layout: 'fit',
        cm: PermissionGridColumn,
        stripeRows: true,
        tbar: tb,
        viewConfig: {forceFit: true}
   });

 var gridWindow = new Ext.Window({
        title: 'Process Permissions',
        collapsible: false,
        maximizable: true,
        width: 600,
        //autoHeight: true,
        height: 450,
        layout: 'fit',
        plain: true,
        bodyStyle: 'padding:5px;',
        buttonAlign: 'center',
        items: PermissionGrid

 });

 //Creating different stores required for fields in form
 var selectField = Ext.data.Record.create([
                        { name: 'LABEL',type: 'string'},
                        { name: 'UID',type: 'string'}
                    ]);
 var selectTaskStore = new Ext.data.JsonStore({
                    root         : 'data',
                    totalProperty: 'totalCount',
                    idProperty   : 'gridIndex',
                    remoteSort   : true,
                    fields       : selectField,
                    proxy: new Ext.data.HttpProxy({
                      url: 'proxyObjectPermissions.php?pid='+pro_uid+'&action=tasks'
                    })
                  });

 var usersStore = new Ext.data.JsonStore({
                    root         : 'data',
                    totalProperty: 'totalCount',
                    idProperty   : 'gridIndex',
                    remoteSort   : true,
                    fields       : selectField,
                    proxy: new Ext.data.HttpProxy({
                      url: 'proxyObjectPermissions.php?pid='+pro_uid+'&action=users'
                    })
                  });

 var dynaformStore = new Ext.data.JsonStore({
                    root         : 'data',
                    totalProperty: 'totalCount',
                    idProperty   : 'gridIndex',
                    remoteSort   : true,
                    fields       : selectField,
                    proxy: new Ext.data.HttpProxy({
                      url: 'proxyObjectPermissions.php?pid='+pro_uid+'&action=dynaform'
                    })
                  });

 var inputDocStore = new Ext.data.JsonStore({
                    root         : 'data',
                    totalProperty: 'totalCount',
                    idProperty   : 'gridIndex',
                    remoteSort   : true,
                    fields       : selectField,
                    proxy: new Ext.data.HttpProxy({
                      url: 'proxyObjectPermissions.php?pid='+pro_uid+'&action=input'
                    })
                  });

 var outputDocStore = new Ext.data.JsonStore({
                    root         : 'data',
                    totalProperty: 'totalCount',
                    idProperty   : 'gridIndex',
                    remoteSort   : true,
                    fields       : selectField,
                    proxy: new Ext.data.HttpProxy({
                      url: 'proxyObjectPermissions.php?pid='+pro_uid+'&action=output'
                    })
                  });

 var PermissionForm =new Ext.FormPanel({
   //   title:"Add new Database Source",
      collapsible: false,
      maximizable: true,
      width:450,
      frame:true,
      plain: true,
      bodyStyle: 'padding:5px;',
      buttonAlign: 'center',
      items:[{
                    width           :150,
                    xtype           :'combo',
                    mode            :'local',
                    editable        :false,
                    fieldLabel      :'Status Case',
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
                    fieldLabel: 'Target Task',
                    //hiddenName:'popType',
                    autoload: true,
                    name: 'TARGET TASK',
                    store: selectTaskStore,
                    valueField:'LABEL',
                    displayField:'LABEL',
                    triggerAction: 'all',
                    emptyText:'Select',
                    editable: true,
                    onSelect: function(record,index)
                    {
                        //var taskUID = record.data.UID;
                        Ext.getCmp("TAS_UID").setValue(record.data.UID);
                       this.setValue(record.data[this.valueField || this.displayField]);
                       this.collapse();
                    }
                    }),

                 new Ext.form.ComboBox({
                    fieldLabel: 'Group or Users',
                    //hiddenName:'popType',
                    autoload: true,
                    store: usersStore,
                    valueField:'LABEL',
                    displayField:'LABEL',
                    triggerAction: 'all',
                    emptyText:'Select',
                    editable: true,
                    onSelect: function(record,index)
                    {
                        //var taskUID = record.data.UID;

                       Ext.getCmp("GROUP_USER").setValue(record.data.UID);
                        this.setValue(record.data[this.valueField || this.displayField]);
                        this.collapse();
                    }
                    })
                ,
                new Ext.form.ComboBox({
                    fieldLabel: 'Origin Task',
                    //hiddenName:'popType',
                    autoload: true,
                    store: selectTaskStore,
                    valueField:'LABEL',
                    displayField:'LABEL',
                    triggerAction: 'all',
                    emptyText:'Select',
                    editable: true,
                    onSelect: function(record,index)
                    {
                        //var taskUID = record.data.UID;
                        Ext.getCmp("OP_TASK_SOURCE").setValue(record.data.UID);
                        this.setValue(record.data[this.valueField || this.displayField]);
                        this.collapse();
                    }
                    }),
                  {
                    width           :150,
                    xtype           :'combo',
                    mode            :'local',
                    editable        :false,
                    fieldLabel      :'Participation Required?',
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
                    fieldLabel      :'Type',
                    triggerAction   :'all',
                    forceSelection  : true,
                    name            :'OP_OBJ_TYPE',
                    displayField    :'name',
                    value           :'All',
                    valueField      :'value',
                    store           :new Ext.data.JsonStore({
                                                        fields : ['name', 'value'],
                                                        data   : [
                                                        {name : 'All',   value: '0'},
                                                        {name : 'DYNAFORM',   value: '1'},
                                                        {name : 'INPUT',   value: '2'},
                                                        {name : 'OUTPUT',   value: '3'}]})
           },
           {
               xtype: 'fieldset',
               id   : 'dynaform',
               hidden: false,
               items: [{
                    xtype: 'combo',
                    fieldLabel: 'Dynaform',
                    //hiddenName:'UID',
                    autoload: true,
                    store: dynaformStore,
                    valueField:'LABEL',
                    displayField:'LABEL',
                    triggerAction: 'all',
                    emptyText:'Select',
                    editable: true,
                    onSelect: function(record,index)
                    {
                        //var taskUID = record.data.UID;
                        Ext.getCmp("DYNAFORMS").setValue(record.data.UID);
                        this.setValue(record.data[this.valueField || this.displayField]);
                        this.collapse();
                    }
               }]
           },{
               xtype: 'fieldset',
               id   : 'inputdoc',
               hidden: false,
               items: [{
                    xtype: 'combo',
                    fieldLabel: 'Input Document',
                    //hiddenName:'UID',
                    autoload: true,
                    store: inputDocStore,
                    valueField:'UID',
                    displayField:'LABEL',
                    triggerAction: 'all',
                    emptyText:'Select',
                    editable: true,
                    onSelect: function(record,index)
                    {
                        //var taskUID = record.data.UID;
                        Ext.getCmp("INPUTS").setValue(record.data.UID);
                        this.setValue(record.data[this.valueField || this.displayField]);
                        this.collapse();
                    }
               }]
           },{
               xtype: 'fieldset',
               id   : 'outputdoc',
               hidden: false,
               items: [{
                    xtype: 'combo',
                    fieldLabel: 'Output Document',
                    //hiddenName:'popType',
                    autoload: true,
                    store: outputDocStore,
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
           },
           {
                    width           :150,
                    xtype           :'combo',
                    mode            :'local',
                    editable        :false,
                    fieldLabel      :'Permission',
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
               name :'TAS_UID',
               id :'TAS_UID'
           },{
               xtype:'hidden',
               name:'GROUP_USER',
               id:'GROUP_USER'
           },{
               xtype:'hidden',
               name:'OP_TASK_SOURCE',
               id:'OP_TASK_SOURCE'
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
           }


       ]
  })



var formWindow = new Ext.Window({
        title: 'New specific Permission',
        collapsible: false,
        maximizable: true,
        width: 450,
        //autoHeight: true,
        height: 420,
        //layout: 'fit',
        plain: true,
        bodyStyle: 'padding:5px;',
        buttonAlign: 'center',
        items: PermissionForm,
        buttons: [{
            text: 'Create',
            handler: function(){
                var getForm         = PermissionForm.getForm().getValues();
                var TargetTask      = getForm.TAS_UID;
                var GroupUser       = getForm.GROUP_USER;
                var OriginTask      = getForm.OP_TASK_SOURCE;
                var Dynaforms       = getForm.DYNAFORMS;
                var Inputs          = getForm.INPUTS;
                var Outputs         = getForm.OUTPUTS;
                var Status          = getForm.OP_CASE_STATUS;
                var Participation   = getForm.OP_PARTICIPATE;
                var Type            = getForm.OP_OBJ_TYPE;
                var Permission      = getForm.OP_ACTION;
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
                      Ext.MessageBox.alert ('Status','Process Permission created successfully.');
                      formWindow.close();
                      PermissionStore.reload();
                      formWindow.close();
                      PermissionStore.reload();
                  }
                });
          }
        },{
            text: 'Cancel',
            handler: function(){
                // when this button clicked,
                formWindow.close();
          }
        }]
    });
  gridWindow.show();
}

ProcessMapContext.prototype.processSupervisors= function()
{
  var pro_uid = workflow.getUrlVars();

  var processUserFields = Ext.data.Record.create([
            {name: 'PU_UID',type: 'string'},
            {name: 'USR_UID',type: 'string'},
            {name: 'PU_TYPE',type: 'string'},
            {name: 'USR_FIRSTNAME',type: 'string'},
            {name: 'USR_LASTNAME',type: 'string'},
            {name: 'USR_EMAIL',type: 'string'}
            ]);
  var editor = new Ext.ux.grid.RowEditor({
            saveText: 'Update'
        });

  var btnAdd = new Ext.Button({
            id: 'btnAdd',
            text: 'Assign Supervisor',
            iconCls: 'application_add',
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
                     Ext.MessageBox.alert ('Status','No supervisors are available. All supervisors have been already assigned.');
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
            text: 'Remove Supervisor',
            iconCls: 'application_delete',
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
                          url   : 'processes_Ajax.php'+urlparams,
                          method: 'GET',
                          success: function(response) {
                              Ext.MessageBox.alert ('Status','Supervisor  has been removed successfully from Process.');
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
              url: 'proxyProcessSupervisors?pid='+pro_uid+'&action=process_User'
            })
          });
  processUser.load();

  // create the Data Store of users that are not assigned to a process supervisor
  var availableProcessesUser = new Ext.data.JsonStore({
                 root            : 'data',
                 url             : 'proxyProcessSupervisors?pid='+pro_uid+'&action=availableProcessesUser',
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
                    id: 'USR_FIRSTNAME',
                    header: 'First Name',
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
                                //User.data.items[selectedrowIndex].data.USR_FIRSTNAME  = record.data.USR_FIRSTNAME;
                                User.data.items[selectedrowIndex].data.USR_LASTNAME  = record.data.USR_LASTNAME;
                                User.data.items[selectedrowIndex].data.USR_EMAIL  = record.data.USR_EMAIL;

                                Ext.getCmp("lastname").setValue(record.data.USR_LASTNAME);
                                this.setValue(record.data[this.valueField || this.displayField]);
                                this.collapse();
                              }
                        })
                },{
                    //id: 'USR_LASTNAME',
                    header: 'Last Name',
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
        tbar: tb
        });

        availableProcessesUser.load();

        editor.on({
          scope: this,
          afteredit: function(roweditor, changes, record, rowIndex) {

            var userID      = record.data.USR_UID;
            var urlparams   = '?action=assignProcessUser&data={"PRO_UID":"'+pro_uid+'","USR_UID":"'+userID+'"}';

            Ext.Ajax.request({
                    url: 'processes_Ajax.php'+urlparams,
                    method: 'GET',
                    success: function (response) {      // When saving data success
                        Ext.MessageBox.alert ('Status','Supervisor has been successfully assigned to a Process');
                        processUser.reload();
                        availableProcessesUser.reload();
                    },
                    failure: function () {      // when saving data failed
                        Ext.MessageBox.alert ('Status','Failed saving Supervisor Assigned to process');
                    }
                 });
          }
        });

        var window = new Ext.Window({
        title: 'Assign Process Supervisor',
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
        items: grid
    });
    window.show();
}

ProcessMapContext.prototype.processDynaform= function()
{
  var pro_uid = workflow.getUrlVars();

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
            text: 'Assign Dynaform',
            iconCls: 'application_add',
            handler: function(){
                var User = grid.getStore();
                var e = new supervisorDynaformsFields({
                     DYN_UID: '',
                     STEP_UID: '',
                     STEP_UID_OBJ: '',
                     STEP_TYPE_OBJ: '',
                     STEP_POSITION: ''
                });

                //storeUsers.reload();
                if(availableSupervisorDynaforms.data.items.length == 0)
                     Ext.MessageBox.alert ('Status','No dynaform are available. All dynaforms have been already assigned.');
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
            text: 'Remove Dynaform',
            iconCls: 'application_delete',
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
                              Ext.MessageBox.alert ('Status','Dynaform  has been removed successfully from Process.');
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
              url: 'proxyProcessSupervisors?pid='+pro_uid+'&action=supervisorDynaforms'
            })
          });
  supervisorDynaforms.load();

  // create the Data Store of users that are not assigned to a process supervisor
  var availableSupervisorDynaforms = new Ext.data.JsonStore({
                 root            : 'data',
                 url             : 'proxyProcessSupervisors?pid='+pro_uid+'&action=availableSupervisorDynaforms',
                 totalProperty   : 'totalCount',
                 idProperty      : 'gridIndex',
                 remoteSort      : false, //true,
                 autoLoad        : true,
                 fields          : supervisorDynaformsFields
              });


  var grid = new Ext.grid.GridPanel({
        store: supervisorDynaforms,
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
                    id: 'DYN_TITLE',
                    header: 'Title',
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

                                User.data.items[selectedrowIndex].data.STEP_UID      = record.data.STEP_UID;
                                User.data.items[selectedrowIndex].data.STEP_UID_OBJ      = record.data.STEP_UID_OBJ;
                                User.data.items[selectedrowIndex].data.STEP_TYPE_OBJ      = record.data.STEP_TYPE_OBJ;
                                User.data.items[selectedrowIndex].data.STEP_POSITION      = record.data.STEP_POSITION;
                                User.data.items[selectedrowIndex].data.DYN_UID  = record.data.DYN_UID;

                                this.setValue(record.data[this.valueField || this.displayField]);
                                this.collapse();
                              }
                        })
                }
                ],
        stripeRows: true,
        viewConfig: {forceFit: true},
        tbar: tb
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
                        Ext.MessageBox.alert ('Status','Dynaform has been successfully assigned to a Process');
                        supervisorDynaforms.reload();
                        availableSupervisorDynaforms.reload();
                    },
                    failure: function () {      // when saving data failed
                        Ext.MessageBox.alert ('Status','Failed saving Dynaform Assigned to process');
                    }
                 });
          }
        });

        var window = new Ext.Window({
        title: 'Assign Dynaform',
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
        items: grid
    });
    window.show();
}

ProcessMapContext.prototype.processIODoc= function()
{
  var pro_uid = workflow.getUrlVars();

  var supervisorInputDocFields = Ext.data.Record.create([
            {name: 'INP_DOC_TITLE',type: 'string'},
            {name: 'STEP_UID',type: 'string'},
            {name: 'STEP_UID_OBJ',type: 'string'},
            {name: 'STEP_TYPE_OBJ',type: 'string'},
            {name: 'STEP_POSITION',type: 'string'},
            {name: 'INP_DOC_UID',type: 'string'}
            ]);
  var editor = new Ext.ux.grid.RowEditor({
            saveText: 'Update'
        });

  var btnAdd = new Ext.Button({
            id: 'btnAdd',
            text: 'Assign Input Document',
            iconCls: 'application_add',
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
                     Ext.MessageBox.alert ('Status','No Input Document are available. All Input Document have been already assigned.');
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
            text: 'Remove Input Document',
            iconCls: 'application_delete',
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
                              Ext.MessageBox.alert ('Status','Input Document  has been removed successfully from Process.');
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
              url: 'proxyProcessSupervisors?pid='+pro_uid+'&action=supervisorInputDoc'
            })
          });
  supervisorInputDoc.load();

  // create the Data Store of users that are not assigned to a process supervisor
  var availableSupervisorInputDoc = new Ext.data.JsonStore({
                 root            : 'data',
                 url             : 'proxyProcessSupervisors?pid='+pro_uid+'&action=availableSupervisorInputDoc',
                 totalProperty   : 'totalCount',
                 idProperty      : 'gridIndex',
                 remoteSort      : false, //true,
                 autoLoad        : true,
                 fields          : supervisorInputDocFields
              });


  var grid = new Ext.grid.GridPanel({
        store: supervisorInputDoc,
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
                    id: 'INP_DOC_TITLE',
                    header: 'Title',
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
                                User.data.items[selectedrowIndex].data.STEP_UID_OBJ      = record.data.STEP_UID_OBJ;
                                User.data.items[selectedrowIndex].data.STEP_TYPE_OBJ      = record.data.STEP_TYPE_OBJ;
                                User.data.items[selectedrowIndex].data.STEP_POSITION      = record.data.STEP_POSITION;
                                User.data.items[selectedrowIndex].data.INP_DOC_UID  = record.data.INP_DOC_UID;

                                this.setValue(record.data[this.valueField || this.displayField]);
                                this.collapse();
                              }
                        })
                }
                ],
        stripeRows: true,
        viewConfig: {forceFit: true},
        tbar: tb
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
                        Ext.MessageBox.alert ('Status','Input Document has been successfully assigned to a Process');
                        supervisorInputDoc.reload();
                        availableSupervisorInputDoc.reload();
                    },
                    failure: function () {      // when saving data failed
                        Ext.MessageBox.alert ('Status','Failed saving Input Document Assigned to process');
                    }
                 });
          }
        });

        var window = new Ext.Window({
        title: 'Assign Dynaform',
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
  var AwesomeUploaderInstance = new AwesomeUploader({
		title:'Ext JS Super Uploader'
		,renderTo:'paintarea'
		,frame:true
		,width:500
		,height:300
	});

         var window = new Ext.Window({
        title: 'Edit Process',
        collapsible: false,
        maximizable: false,
        width: 500,
        height: 400,
        minWidth: 300,
        minHeight: 200,
        layout: 'fit',
        plain: true,
        bodyStyle: 'padding:5px;',
        buttonAlign: 'center',
        items: AwesomeUploaderInstance,
        buttons: [{
            text: 'Save',
            handler: function(){
                //waitMsg: 'Saving...',       // Wait Message
                  var fields          = editProcess.items.items;
                  var pro_title       = fields[0].items.items[0].getValue();
                  var pro_description = fields[0].items.items[1].getValue();
                  var pro_calendar    = fields[0].items.items[2].getValue();
                  var pro_category    = fields[0].items.items[3].getValue();
                  var pro_debug       = fields[0].items.items[4].getValue();
                  if(pro_debug == true)
                     pro_debug = '1';
                 else
                     pro_debug = '0';

                  var pro_uid = _5678.scope.workflow.getUrlVars();

                  var urlparams = '?action=saveProcess&data={"PRO_UID":"'+ pro_uid +'","PRO_CALENDAR":"'+ pro_calendar +'","PRO_CATEGORY":"'+ pro_category +'","PRO_DEBUG":"'+ pro_debug +'","PRO_DESCRIPTION":"'+ pro_description +'","PRO_TITLE":"'+ pro_title +'",}';
                  Ext.Ajax.request({
                    url: "processes_Ajax.php"+ urlparams,
                    success: function(response) {
                        window.close();
                    },
                    failure: function(){
                        Ext.Msg.alert ('Failure');
                    }
                });
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

ProcessMapContext.prototype.caseTrackerProperties= function()
{
  var pro_uid = workflow.getUrlVars();

   var PropertiesForm = new Ext.FormPanel({
        labelWidth: 75, // label settings here cascade unless overridden
        frame:true,
        bodyStyle:'padding:5px 5px 0',
        width: 500,
        height: 400,
        defaults: {width: 350},
        defaultType: 'textfield',
                items: [{
                         width:          100,
                        xtype:          'combo',
                        mode:           'local',
                        triggerAction:  'all',
                        forceSelection: true,
                        editable:       false,
                        fieldLabel:     'Map Type',
                        name:           'CT_MAP_TYPE',
                        displayField:   'name',
                        value: 'Process Map',
                        valueField:     'value',
                        store:          new Ext.data.JsonStore({
                                                fields : ['name', 'value'],
                                                data   :[{name: 'None', value:'0'},
                                                        {name: 'Process Map', value: '1'},
                                                        {name: 'Stages Map', value:'2'}]

                                                              })
                    },{
                        xtype: 'checkbox',
                        fieldLabel: 'Derivation History',
                        name: 'CT_DERIVATION_HISTORY'
                        //checked:checkDerivation
                    },{
                        xtype: 'checkbox',
                        fieldLabel: 'Messages History',
                        name: 'CT_MESSAGE_HISTORY'
                       // checked:checkMessages
                    }
                ]

   });
   var Propertieswindow = new Ext.Window({
        title: 'Edit Process',
        collapsible: false,
        maximizable: false,
        width: 500,
        height: 400,
        minWidth: 300,
        minHeight: 200,
        layout: 'fit',
        plain: true,
        bodyStyle: 'padding:5px;',
        buttonAlign: 'center',
        items: PropertiesForm,
        buttons: [{
                text: 'Save',
            handler: function(){
                var getForm             = PropertiesForm.getForm().getValues();
                var pro_uid             = getForm.PRO_UID;
                var MapType             = getForm.CT_MAP_TYPE;
                var DerivationHistory   = getForm.CT_DERIVATION_HISTORY;
                var MessageHistory      = getForm.CT_MESSAGE_HISTORY;

                   Ext.Ajax.request({
                   url   : '../tracker/tracker_save.php',
                   method: 'POST',
                   params:{
                      PRO_UID  :pro_uid,
                      CT_MAP_TYPE     :MapType,
                      CT_DERIVATION_HISTORY   :DerivationHistory,
                      CT_MESSAGE_HISTORY  :MessageHistory
                          },
                   success: function(response) {
                      Ext.MessageBox.alert ('Status','Connection Saved Successfully.');
                                               }
                   });
            }
                 },{
                     text: 'Cancel',
                     handler: function(){
                     Propertieswindow.close();
                     }
                   }
        ]
   });
  Propertieswindow.show();
}

ProcessMapContext.prototype.caseTrackerObjects= function()
  {
    var pro_uid = workflow.getUrlVars();
     //var taskId  = workflow.currentSelection.id;

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
            name:'CTO_UID_OBJ',
            type:'string'
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
        }
    ]);
    var editor = new Ext.ux.grid.RowEditor({
            saveText: 'Update'
    });

    var assignedStore = new Ext.data.JsonStore({
      root         : 'data',
      totalProperty: 'totalCount',
      idProperty   : 'gridIndex',
      remoteSort   : true,
      fields       : ObjectFields,
      proxy: new Ext.data.HttpProxy({
        url: 'proxyCaseTrackerObjects?pid='+pro_uid
        })
    });
          //taskUsers.setDefaultSort('LABEL', 'asc');
    assignedStore.load();

         // create the Data Store of users that are not assigned to a task
    var availableStore = new Ext.data.JsonStore({
      root            : 'data',
      url             : 'proxyCaseTrackerObjects?tid='+pro_uid,
      totalProperty   : 'totalCount',
      idProperty      : 'gridIndex',
      remoteSort      : false, //true,
      autoLoad        : true,
      fields          : ObjectFields
    });

    var btnAdd = new Ext.Button({
      id: 'btnAdd',
      text: 'Assign',
      iconCls: 'application_add',
      handler: function(){
         var User = Objectsgrid.getStore();
         var e = new ObjectFields({
           OBJECT_TITLE:'',
           OBJECT_TYPE:'',
           OBJECT_UID: ''
      });

                //storeUsers.reload();
      if(availableStore.data.items.length == 0)
      Ext.MessageBox.alert ('Status','No users are available. All users have been already assigned.');
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
      text: 'Remove',
      iconCls: 'application_delete',
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
                Ext.MessageBox.alert ('Status','Object has been removed successfully.');
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

    var tb = new Ext.Toolbar({
      items: [btnAdd, btnRemove]
    });

        // create the Data Store of objects that are already assigned
    var Objectsgrid = new Ext.grid.GridPanel({
      store: assignedStore,
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
          id: 'CTO_TITLE',
          header: 'Title',
          dataIndex: 'CTO_TITLE',
          width: 100,
          sortable: true,
          editor: new Ext.form.ComboBox({
            xtype: 'combo',
            store:availableStore,
            fieldLabel: 'Title',
            hiddenName: 'number',
            displayField : 'OBJECT_TITLE'  ,
            valueField   : 'OBJECT_TITLE',
            name         : 'OBJECT_TITLE',
            triggerAction: 'all',
            emptyText: 'Select User or Group',
            allowBlank: false,
            onSelect: function(record, index){
              var User = Objectsgrid.getStore();
              var selectedrowIndex = '0';
              User.data.items[selectedrowIndex].data.OBJECT_UID   = record.data.OBJECT_UID;
              User.data.items[selectedrowIndex].data.OBJECT_TYPE  = record.data.OBJECT_TYPE;
              User.data.items[selectedrowIndex].data.OBJECT_TITLE = record.data.OBJECT_TITLE;
              this.setValue(record.data[this.valueField || this.displayField]);
              this.collapse();
            }
          })
        },
        {
          header: 'Type',
          dataIndex: 'CTO_TYPE_OBJ',
          //width: 200,
          editable: false
        },
        {
          header: 'Condition',
          dataIndex: 'CTO_CONDITION',
          name: 'CTO_CONDITION',
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
            workflow.currentrowIndex = rowIndex;
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
                        Ext.MessageBox.alert ('Status','Objects has been successfully assigned');
                        availableStore.reload();
                        assignedStore.reload();
                      }
                    })
            },
          failure: function () {      // when saving data failed
            Ext.MessageBox.alert ('Status','Failed to assign Objects');
            }
        })
    
      }

    });
    //Updating the user incase if already assigned user has been replaced by other user
    /*if(changes != '' && typeof record.json != 'undefined')
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
    }*/
    assignedStore.reload();
    availableStore.reload();


    var gridObjectWindow = new Ext.Window({
      title       : 'Objects',
      collapsible : false,
      maximizable : false,
      width       : 550,
      defaults    :{ autoScroll:true },
      height      : 450,
      minWidth    : 200,
      minHeight   : 150,
      layout      : 'fit',
      plain       : true,
      bodyStyle   : 'padding:5px;',
      items       : Objectsgrid,
      buttonAlign : 'center'
    });
  gridObjectWindow.show()

  }

ProcessMapContext.prototype.ExtVariables = function()
  {
    var pro_uid = workflow.getUrlVars();
     //var taskId  = workflow.currentSelection.id;
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

  var varStore = new Ext.data.JsonStore({
            root         : 'data',
            totalProperty: 'totalCount',
            idProperty   : 'gridIndex',
            remoteSort   : true,
            fields       : varFields,
            proxy        : new Ext.data.HttpProxy({
                   url   : 'proxyVariable?pid='+pro_uid+'&sFieldName=form[CTO_CONDITION]&sSymbol=@@'
            })
          });
          //taskUsers.setDefaultSort('LABEL', 'asc');
          varStore.load();

var allVarGrid =  new Ext.grid.GridPanel({
            store       : varStore,
            id          : 'mygrid',
            loadMask    : true,
            loadingText : 'Loading...',
            //renderTo    : 'cases-grid',
            frame       : false,
            autoHeight  : false,
            enableDragDrop   : true,
            ddGroup     : 'firstGridDDGroup',
            clicksToEdit: 1,
            minHeight   :400,
            height      :400,
            layout      : 'form',
            //plugins     : [editor],
            columns     : [{
                        id: 'variable',
                        header: 'Variable',
                        dataIndex: 'variable',
                        width: 200,
                        sortable: true
            },{
                id: 'label',
                header: 'Label',
                dataIndex: 'label',
                width: 200,
                sortable: true
              }]
            });

var sysStore = new Ext.data.JsonStore({
            root         : 'data',
            totalProperty: 'totalCount',
            idProperty   : 'gridIndex',
            remoteSort   : true,
            fields       : varFields,
            proxy        : new Ext.data.HttpProxy({
            url   : 'proxyVariable?pid='+pro_uid+'&sFieldName=form[CTO_CONDITION]&sSymbol=@@'
            })
          });
          //taskUsers.setDefaultSort('LABEL', 'asc');
          sysStore.load();

var sysGrid =  new Ext.grid.GridPanel({
            store       : sysStore,
            id          : 'mygrid',
            loadMask    : true,
            loadingText : 'Loading...',
            //renderTo    : 'cases-grid',
            frame       : false,
            autoHeight  : false,
            enableDragDrop   : true,
            ddGroup     : 'firstGridDDGroup',
            clicksToEdit: 1,
            minHeight   :400,
            height      :400,
            layout      : 'form',
            //plugins     : [editor],
            columns     : [{
                        id: 'variable',
                        header: 'Variable',
                        dataIndex: 'variable',
                        width: 200,
                        sortable: true
            },{
                id: 'Label',
                header: 'Label',
                dataIndex: 'label',
                width: 200,
                sortable: true
              }]
            });
var varForm = new Ext.FormPanel({
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
                title:'All Variables',
                layout:'fit',
                defaults: {
                    width: 400
                },
                items:[allVarGrid]
            },{
                title:'System',
                layout:'fit',
                defaults: {
                    width: 400
                },
                items:[sysGrid]
            },{
                title:'Process',
                layout:'form',
                defaults: {
                    width: 400
                },
                items:[]
            }]
        }
    });

    //varForm.render(document.body);
  //workflow.taskStepsTabs = varForm;

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
        items: varForm
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
