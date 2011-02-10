ProcessMapContext=function(id){
Workflow.call(this,id);
};
ProcessMapContext.prototype=new Workflow;
ProcessMapContext.prototype.type="ProcessMap";

ProcessMapContext.prototype.editProcess= function()
{
         var pro_uid = workflow.getUrlVars();
        //var editProcessData     = workflow.processEdit;
       // var processCategoryData = workflow.processCategory;
        //var debug               = editProcessData.PRO_DEBUG;
       // var pro_category        = editProcessData.PRO_CATEGORY;
        //var pro_category_label  = editProcessData.PRO_CATEGORY_LABEL;
            //var checkdebug = true;
      //if(debug  == '0')
         // checkDebug = false;
//
//            var processCalendar = new Array();
//            processCalendar[0]  = new Array();
//            processCalendar[1]  = new Array();
//
//            processCalendar[0].name  = 'None';
//            processCalendar[0].value = '';
//            processCalendar[1].name  = 'Default';
//            processCalendar[1].value = '00000000000000000000000000000001';

        //var processName = processInfo.title.label
        var editProcess = new Ext.FormPanel({
        labelWidth: 75, // label settings here cascade unless overridden
        frame:false,
        buttonAlign: 'center',
        //monitorValid : true,
        width: 450,
        height: 400,
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
                        name: 'PRO_TITLE',
                        width: 300,
                        //value: editProcessData.PRO_TITLE,
                        allowBlank:false
                    },{
                        xtype: 'textarea',
                        fieldLabel: 'Description',
                        name: 'PRO_DESCRIPTION',
                        //value: editProcessData.PRO_DESCRIPTION,
                        width: 300,
                        height : 150

                    },{
                        width: 300,
                        xtype:          'combo',
                        mode:           'local',
                        //value:          editProcessData.PRO_CALENDAR,
                        forceSelection: true,
                        triggerAction:  'all',
                        editable:       false,
                        fieldLabel:     'Calendar',
                        name:           'PRO_CALENDAR',
                        hiddenName:     'calendar',
                        displayField:   'name',
                        valueField:     'value',
                        store:          new Ext.data.JsonStore({
                                                fields : ['name', 'value'],
                                                data   :  [
                                            {name:'none',    value: 'none'},
                                             {name:'default',    value: 'default'}
                                  ]
                                            })
                    }, {
                        width:          300,
                        xtype:          'combo',
                        mode:           'local',
                        //value:          editProcessData.PRO_CATEGORY,
                        triggerAction:  'all',
                        forceSelection: true,
                        editable:       false,
                        fieldLabel:     'Category',
                        name:           'PRO_CATEGORY',
                        hiddenName:     'category',
                         displayField:   'CATEGORY_NAME',
                        valueField:     'CATEGORY_UID',
                        store:          new Ext.data.JsonStore({
                                                fields : ['CATEGORY_NAME', 'CATEGORY_UID']
                                                //data   :processCategoryData
                                            })
                    },{
                        xtype: 'checkbox',
                        fieldLabel: 'Debug',
                        name: 'PRO_DEBUG',
                        checked:workflow.checkdebug
                    }
                ]
        }],buttons: [{
            text: 'Save',
            formBind    :true,
            handler: function(form, action){
                //waitMsg: 'Saving...',       // Wait Message
                  //var fields          = editProcess.items.items;
                  var getForm         = editProcess.getForm().getValues();
                  var pro_title       = getForm.PRO_TITLE;
                  var pro_description = getForm.PRO_DESCRIPTION;
                  var pro_calendar    = getForm.PRO_CALENDAR;
                  var pro_category    = getForm.PRO_CATEGORY;
                  var pro_debug       = getForm.PRO_DEBUG;
                  
                 if(pro_debug == 'on')
                     pro_debug = 1;
                 else
                    pro_debug = 0;
                  var urlparams = '?action=saveProcess&data={"PRO_UID":"'+ pro_uid +'","PRO_CALENDAR":"'+ pro_calendar +'","PRO_CATEGORY":"'+ pro_category +'","PRO_DEBUG":"'+ pro_debug +'","PRO_DESCRIPTION":"'+ pro_description +'","PRO_TITLE":"'+ pro_title +'",}';
                  Ext.Ajax.request({
                        url: "processes_Ajax.php"+ urlparams,
                        success: function(response) {
                            Ext.MessageBox.alert ('Status','Process Information Saved Successfully.');
                            //window.hide();
                        }
                        
                    });
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
console.log(workflow.checkdebug);
     editProcess.form.load({
        url:'proxyExtjs.php?pid='+pro_uid+'&action=process_Edit',
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
            Ext.MessageBox.alert('Message', 'Load failed');
        }
    });

    editProcess.render(document.body);
    //workflow.editProcessForm = editProcess;

     var window = new Ext.Window({
        title: 'Edit Process',
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
  workflow.FILENAME_LINK = '';
  workflow.FILENAME_LINKXPDL = '';


  var exportProcessForm = new Ext.FormPanel({
  labelWidth    : 120, // label settings here cascade unless overridden
  frame         : false,
  monitorValid : true,
  title         : '',
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
                      //},{
                       // xtype       : 'button',
                       // fieldLabel  : 'File XPDL',
                       // name        : 'FILENAME_LINKXPDL',
                        //dataIndex   : 'FILENAME_LINKXPDL',
                       // html        : '<a href="javascript: Ext.ux.classobj.method(' + Ext.util.JSON.encode(obj) + ')" ></a>'
                        //readOnly  :true
                      },{
                        xtype: "panel",
                        html: new Ext.XTemplate("<a href='#'>{value}").apply({
                        value:'FILENAME_LINKXPDL'
                        })
                      },{
                        xtype: 'box',
                        autoEl: {
                            tag: 'a',

                            html: '3. Dom element created by a DomHelper and wrapped as Component',
                            href: '#'
            }},

                      {
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
    height     : 300,
    minWidth   : 300,
    minHeight  : 200,
    layout     : 'fit',
    plain      : true,
    autoScroll: true,
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

  var PermissionStore = new Ext.data.JsonStore({
            root         : 'data',
            totalProperty: 'totalCount',
            idProperty   : 'gridIndex',
            remoteSort   : true,
            fields       : dbConnFields,
            proxy: new Ext.data.HttpProxy({
            url: 'proxyExtjs.php?pid='+pro_uid+'&action=getObjectPermission'
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
                        width:120,
                        sortable: true,
                        editor: new Ext.form.TextField({
                            //allowBlank: false
                            })
                    },{
                        id: 'GROUP_USER',
                        header: 'Group or Users',
                        dataIndex: 'GROUP_USER',
                        width: 150,
                        sortable: true,
                        editor: new Ext.form.TextField({
                            //allowBlank: false
                            })
                    },{
                        id: 'TASK_SOURCE',
                        header: 'Origin Task',
                        dataIndex: 'TASK_SOURCE',
                        width: 120,
                        sortable: true,
                        editor: new Ext.form.TextField({
                            //allowBlank: false
                            })
                    },{
                        id: 'PARTICIPATED',
                        header: 'Participation',
                        dataIndex: 'PARTICIPATED',
                        width: 120,
                        sortable: true,
                        editor: new Ext.form.TextField({
                            //allowBlank: false
                            })
                    },{
                        id: 'OBJECT_TYPE',
                        header: 'Type',
                        dataIndex: 'OBJECT_TYPE',
                        width: 100,
                        editable: false,
                        sortable: true,
                        editor: new Ext.form.TextField({
                            //allowBlank: false
                            })
                    },{
                        id: 'OBJECT',
                        header: 'Object',
                        name:'OBJECT',
                        dataIndex: 'OBJECT',
                        width: 100,
                        sortable: true,
                        editor: new Ext.form.TextField({
                            //allowBlank: false
                            })
                    },{
                        id: 'ACTION',
                        header: 'Permission',
                        dataIndex: 'ACTION',
                        width: 120,
                        sortable: true,
                        editor: new Ext.form.TextField({
                            //allowBlank: false
                            })
                    },{
                        id: 'OP_CASE_STATUS',
                        header: 'Status',
                        dataIndex: 'OP_CASE_STATUS',
                        width: 120,
                        sortable: true,
                        editor: new Ext.form.TextField({
                            //allowBlank: false
                            })
                    }
                ]
     });
  var editor = new Ext.ux.grid.RowEditor({
    saveText: 'Update'
    });


  var btnCreate = new Ext.Button({
            id: 'btnCreate',
            text: 'New',
            iconCls: 'button_menu_ext ss_sprite ss_add',
            handler: function () {
                formWindow.show();
                PermissionForm.getForm().reset();
            }
  });
  var btnEdit = new Ext.Button({
            id: 'btnEdit',
            text: 'Edit',
            iconCls: 'button_menu_ext ss_sprite ss_pencil',
            handler: function (s) {
                var selectedRow = PermissionGrid.getSelectionModel().getSelections();
                var opUID   = selectedRow[0].data.OP_UID;
                 //Loading Task Details into the form
                  PermissionForm.form.load({
                        url:'proxyExtjs.php?pid='+pro_uid+'&op_uid=' +opUID+'&action=editObjectPermission',
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
                            Ext.MessageBox.alert('Message', 'Load failed');
                        }
                    });
            }
        });

  var btnRemove = new Ext.Button({
            id: 'btnRemove',
            text: 'Delete',
            iconCls: 'button_menu_ext ss_sprite ss_delete',
            handler: function (s) {
                editor.stopEditing();
                var s = PermissionGrid.getSelectionModel().getSelections();
                for(var i = 0, r; r = s[i]; i++){

                    //First Deleting process permission from Database using Ajax
                    var opUID      = r.data.OP_UID;

                    //if OP_UID is properly defined (i.e. set to valid value) then only delete the row
                    //else its a BLANK ROW for which Ajax should not be called.
                    if(r.data.OP_UID != "")
                    {
                          Ext.Ajax.request({
                                  url   : '../processes/processes_DeleteObjectPermission.php?opUID='+OP_UID,
                                  method: 'GET',
                                  
                                  success: function(response) {
                                    Ext.MessageBox.alert ('Status','Process Permission has been removed successfully.');
                                    //Secondly deleting from Grid
                                    PermissionStore.remove(r);
                                    //reloading store after deleting input document
                                    PermissionStore.reload();
                                  }
                                });
                    }
                    else
                        PermissionStore.remove(r);
                }
            }
        });
        
  var tb = new Ext.Toolbar({
            items: [btnCreate,btnRemove,btnEdit,SearchText,btnSearch]
      
  });
      
  var SearchText = new Ext.TextField ({
        id: 'searchTxt',
        ctCls:'pm_search_text_field',
        allowBlank: true,
        width: 150,
        emptyText: TRANSLATIONS.ID_ENTER_SEARCH_TERM,//'enter search term',
        listeners: {
          specialkey: function(f,e){
            if (e.getKey() == e.ENTER) {
              doSearch();
            }
          }
        }
      });


  var btnSearch = new Ext.Button({
        text:'X',
        ctCls:'pm_search_x_button',
        handler: function(){
          //store.setBaseParam( 'category', '<reset>');
          //store.setBaseParam( 'processName', '');
          //store.load({params:{start : 0 , limit : '' }});
          Ext.getCmp('searchTxt').setValue('');
          //comboCategory.setValue('');
          //store.reload();
        }
      });
       

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
        width: 800,
        autoScroll: true,
        height: 380,
        layout: 'fit',
        plain: true,
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
                      url: 'proxyExtjs.php?pid='+pro_uid+'&action=getObjectPermissionType&objectType=tasks'
                    })
                  });

 var usersStore = new Ext.data.JsonStore({
                    root         : 'data',
                    totalProperty: 'totalCount',
                    idProperty   : 'gridIndex',
                    remoteSort   : true,
                    fields       : selectField,
                    proxy: new Ext.data.HttpProxy({
                      url: 'proxyExtjs.php?pid='+pro_uid+'&action=getObjectPermissionType&objectType=users'
                    })
                  });

 var dynaformStore = new Ext.data.JsonStore({
                    root         : 'data',
                    totalProperty: 'totalCount',
                    idProperty   : 'gridIndex',
                    remoteSort   : true,
                    fields       : selectField,
                    proxy: new Ext.data.HttpProxy({
                      url: 'proxyExtjs.php?pid='+pro_uid+'&action=getObjectPermissionType&objectType=dynaform'
                    })
                  });

 var inputDocStore = new Ext.data.JsonStore({
                    root         : 'data',
                    totalProperty: 'totalCount',
                    idProperty   : 'gridIndex',
                    remoteSort   : true,
                    fields       : selectField,
                    proxy: new Ext.data.HttpProxy({
                      url: 'proxyExtjs.php?pid='+pro_uid+'&action=getObjectPermissionType&objectType=input'
                    })
                  });

 var outputDocStore = new Ext.data.JsonStore({
                    root         : 'data',
                    totalProperty: 'totalCount',
                    idProperty   : 'gridIndex',
                    remoteSort   : true,
                    fields       : selectField,
                    proxy: new Ext.data.HttpProxy({
                      url: 'proxyExtjs.php?pid='+pro_uid+'&action=getObjectPermissionType&objectType=output'
                    })
                  });

 var PermissionForm =new Ext.FormPanel({
   //   title:"Add new Database Source",
      collapsible: false,
      maximizable: true,
      width:360,
      //height: 30,
      monitorValid : true,
      frame:false,
      plain: true,
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
                    //autoload: true,
                    name: 'TASK_TARGET_NAME',
                    store: selectTaskStore,
                    valueField:'LABEL',
                    displayField:'LABEL',
                    triggerAction: 'all',
                    emptyText:'Select Target Task',
                    editable: true,
                    onSelect: function(record,index)
                    {
                        //var taskUID = record.data.UID;
                        Ext.getCmp("TASK_TARGET").setValue(record.data.UID);
                       this.setValue(record.data[this.valueField || this.displayField]);
                       this.collapse();
                    }
                    }),

                 new Ext.form.ComboBox({
                    fieldLabel: 'Group or Users',
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
                    })
                ,
                new Ext.form.ComboBox({
                    fieldLabel: 'Origin Task',
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
                                                                {Ext.getCmp("dynaform").show();
                                                                 Ext.getCmp("inputdoc").hide();
                                                                 Ext.getCmp("outputdoc").hide()}
                                                        else if(record.data.value == '2')
                                                                {Ext.getCmp("inputdoc").show();
                                                                 Ext.getCmp("dynaform").hide();
                                                                    Ext.getCmp("outputdoc").hide()}
                                                        else
                                                                {Ext.getCmp("outputdoc").show();
                                                                 Ext.getCmp("inputdoc").hide();
                                                                 Ext.getCmp("dynaform").hide()}
                                                                 this.setValue(record.data[this.valueField || this.displayField]);
                                                                 this.collapse();
                                     }

           },
           {
               xtype: 'fieldset',
               id   : 'dynaform',
               hidden: true,
               border: false,
               items: [{
                    xtype: 'combo',
                    fieldLabel: 'Dynaform',
                    //hiddenName:'UID',
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
                        //var taskUID = record.data.UID;
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
                    fieldLabel: 'Input Document',
                    //hiddenName:'UID',
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
                        //var taskUID = record.data.UID;
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
                    fieldLabel: 'Output Document',
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
           }


       ],
       buttons: [{
            text: 'Create',
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
                var Participation   = getForm.PARTICIPATED;
                var Type            = getForm.OP_OBJ_TYPE;
                var Permission      = getForm.OP_ACTION;
                if(TASK_TARGET == "")
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
                      Ext.MessageBox.alert ('Status','Process Permission created successfully.');
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
                      Ext.MessageBox.alert ('Status','Process Permission edited successfully.');
                      formWindow.hide();
                      PermissionStore.reload();
                      formWindow.hide();
                      PermissionStore.reload();
                  }
                });
                }
          }
        },{
            text: 'Cancel',
            handler: function(){
                // when this button clicked,
                formWindow.hide();
          }
        }]
  })



var formWindow = new Ext.Window({
        title: 'New specific Permission',
        collapsible: false,
        maximizable: true,
        width: 400,
        autoScroll: true,
        //autoHeight: true,
        height: 320,
        //layout: 'fit',
        plain: true,
        buttonAlign: 'center',
        items: PermissionForm
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
            text: 'Assign',
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
            text: 'Remove',
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
                                User.data.items[selectedrowIndex].data.USR_LASTNAME  = record.data.USR_LASTNAME;
                                User.data.items[selectedrowIndex].data.USR_EMAIL  = record.data.USR_EMAIL;

                                Ext.getCmp("lastname").setValue(record.data.USR_LASTNAME);
                                this.setValue(record.data[this.valueField || this.displayField]);
                                this.collapse();
                              }
                        })
                },{
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
        title: 'Supervisor',
        collapsible: false,
        maximizable: false,
        width: 400,
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
            text: 'Assign',
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
            }
  });

  var btnRemove = new Ext.Button({
            id: 'btnRemove',
            text: 'Remove',
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
        title: 'Dynaform',
        collapsible: false,
        maximizable: false,
        width: 400,
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
            text: 'Assign',
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
            text: 'Remove',
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
        title: 'Input Documents',
        collapsible: false,
        maximizable: false,
        width: 400,
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
  var AwesomeUploaderInstance = new AwesomeUploader({
		title:'Ext JS Super Uploader'
		,renderTo:'paintarea'
		,frame:true
		,width:500
		,height:300
	});

  var window = new Ext.Window({
        title: 'Process File Manager',
        collapsible: false,
        maximizable: false,
        width: 500,
        height: 400,
        minWidth: 300,
        minHeight: 200,
        autoScroll: true,
        layout: 'fit',
        plain: true,
        buttonAlign: 'center',
        items: AwesomeUploaderInstance,
        buttons: [{
            text: 'Save',
            formBind    :true,
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
                        window.hide();
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
                window.hide();
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
        frame:false,
        monitorValid : true,
        width: 300,
        height: 300,
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
                       fieldLabel      :'Map Type',
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
                        fieldLabel: 'Derivation History',
                        name: 'CT_DERIVATION_HISTORY'
                        //checked:checkDerivation
                },{
                        xtype: 'checkbox',
                        fieldLabel: 'Messages History',
                        name: 'CT_MESSAGE_HISTORY'
                       // checked:checkMessages
               }], buttons: [{
                text: 'Save',
                formBind    :true,
                handler: function(){
                var getForm             = PropertiesForm.getForm().getValues();
                //var pro_uid             = getForm.PRO_UID;
                var MapType             = getForm.CT_MAP_TYPE;
                var DerivationHistory   = getForm.CT_DERIVATION_HISTORY;
                var MessageHistory      = getForm.CT_MESSAGE_HISTORY;
                if(DerivationHistory == 'on')
                    DerivationHistory = '1';
                else
                    DerivationHistory = '0';

                if(MessageHistory == 'on')
                    MessageHistory = '1';
                else
                    MessageHistory = '0';

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
                          Ext.MessageBox.alert ('Status','Connection Saved Successfully.');
                                                                            }
                   });
                }
        },{
           text: 'Cancel',
           handler: function(){
           Propertieswindow.hide();
          }
        }]

   });
   var Propertieswindow = new Ext.Window({
        title: 'Case tracker',
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
      root          : 'data',
      totalProperty : 'totalCount',
      idProperty    : 'gridIndex',
      remoteSort    : true,
      fields        : ObjectFields,
      proxy         : new Ext.data.HttpProxy({
      url           : 'proxyExtjs?pid='+pro_uid+'&action=getAssignedCaseTrackerObjects'
      })
    });
    assignedStore.load();

    var availableStore = new Ext.data.JsonStore({
      root            : 'data',
      url             : 'proxyExtjs?tid='+pro_uid+'&action=getAvailableCaseTrackerObjects',
      totalProperty   : 'totalCount',
      idProperty      : 'gridIndex',
      remoteSort      : false, //true,
      autoLoad        : true,
      fields          : ObjectFields
    });

    var btnAdd = new Ext.Button({
      id: 'btnAdd',
      text: 'Assign',
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



    var btnObjectsCondition = new Ext.Button({
      id: 'btnCondition',
      text: 'Condition',
      handler: function (s) {
                workflow.variablesAction = 'grid';
                workflow.gridField       = 'CTO_CONDITION';
                var rowSelected          = Objectsgrid.getSelectionModel().getSelections();
                if(rowSelected == '')
                    workflow.gridObjectRowSelected = Objectsgrid;
                else
                    workflow.gridObjectRowSelected = rowSelected;
                var rowData = ProcMapObj.ExtVariables();
                console.log(rowData);

        }
   })

    var tb = new Ext.Toolbar({
      items: [btnAdd, btnRemove,btnObjectsCondition]
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
            fieldLabel   : 'Title',
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
          header    : 'Type',
          dataIndex : 'CTO_TYPE_OBJ',
          editable  : false
        },{
            header : 'Condition',
            dataindex: 'CTO_CONDITION',
            name : 'CTO_CONDITION',
            //xtype: 'textfield',
            editable  : true
        },{
            sortable: false,
            renderer: function(val, meta, record)
               {
                   var recordData = Ext.util.JSON.encode(record);
                    return String.format("<input type='button' value='@@' onclick=workflow.ExtVariables('CTO_CONDITION','{0}');>",recordData);
              }
        }],
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
                          Ext.MessageBox.alert ('Status','User has been updated successfully.');
                      }
                    });
            }
             availableStore.reload();
             assignedStore.reload();
      }

    });

   // assignedStore.reload();
   // availableStore.reload();


    var gridObjectWindow = new Ext.Window({
      title       : 'Objects',
      collapsible : false,
      maximizable : false,
      width       : 550,
      defaults    :{ autoScroll:true },
      height      : 400,
      minWidth    : 200,
      minHeight   : 150,
      layout      : 'fit',
      plain       : true,
      autoScroll  : true,
      items       : Objectsgrid,
      buttonAlign : 'center'
    });
  gridObjectWindow.show()

  }

ProcessMapContext.prototype.ExtVariables = function()
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
                title:'System',
                id:'system',
                layout:'form',
                listeners:{
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
                title:'Process',
                id   :'process',
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
        title: 'Steps Of',
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
