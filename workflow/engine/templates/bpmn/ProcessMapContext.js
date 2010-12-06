ProcessMapContext=function(id){
Workflow.call(this,id);
};
ProcessMapContext.prototype=new Workflow;
ProcessMapContext.prototype.type="ProcessMap";

ProcessMapContext.prototype.editProcess= function(_5678)
{
        var editProcessData     = _5678.scope.workflow.processEdit;
        var processCategoryData = _5678.scope.workflow.processCategory;
        var debug               =editProcessData.PRO_DEBUG;
        var pro_category        =editProcessData.PRO_CATEGORY;
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
  Ext.MessageBox.alert('Status','Process Permission');
}

ProcessMapContext.prototype.caseTracker= function()
{
  Ext.MessageBox.alert('Status','Case Tracker');
}
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