pmosExt=function(id){
Workflow.call(this,id);
};
pmosExt.prototype=new Workflow;
pmosExt.prototype.type="pmosExt";

pmosExt.prototype.addExtJsWindow = function(items,width,height,title)
{
         var window = new Ext.Window({
         title: title,
         collapsible: false,
         maximizable: false,
         width: width,
         height: height,
         minWidth: 300,
         minHeight: 200,
         layout: 'fit',
         plain: true,
         bodyStyle: 'padding:5px;',
         buttonAlign: 'center',
         items: items,
         buttons: [{
             text: _('ID_SAVE'),
             handler: function(){
	                // when this button clicked, sumbit this form
                          items.getForm().submit({
	                    //waitMsg: 'Saving...',       // Wait Message
	                    success: function () {      // When saving data success
	                        //Ext.MessageBox.alert (response.responseText);
	                        // clear the form
	                        //simpleForm.getForm().reset();
	                    },
	                    failure: function () {      // when saving data failed
                                PMExt.notify( _('ID_STATUS') , _('ID_AUTHENTICATION_FAILED') );
	                    }
	                });
//                           var test = webForm.getForm().submit({url:'../cases/cases_SchedulerValidateUser.php', submitEmptyText: false});
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
pmosExt.prototype.popWebEntry= function(_5678)
{
  var oTask           = workflow.taskUid;
  var oDyna           = workflow.dynaList;
  
  var newButton = new Ext.Action({
    text: _('ID_NEW_WEB_ENTRY'),
    iconCls: 'button_menu_ext ss_sprite ss_add',
    hidden: true,
    handler: function(){
      webForm.hide();
      editForm.getForm().reset();
      Ext.getCmp('frameEdit').setTitle = _('ID_NEW_WEB_ENTRY');
      editForm.getForm().findField('pro_uid').setValue(pro_uid);
      editForm.getForm().findField('evn_uid').setValue(evn_uid);
      editForm.getForm().findField('dynaform').setValue('');
      editForm.getForm().items.items[3].focus('',500);
      editForm.show();
      newButton.disable();
    }
  });
  
  var editButton = new Ext.Action({
    text: _('ID_EDIT'),
    iconCls: 'button_menu_ext ss_sprite ss_pencil',
    hidden: true,
    handler: function(){
      webForm.hide();
      editForm.getForm().reset();
      Ext.getCmp('frameEdit').setTitle = _('ID_EDIT_WEB_ENTRY');
      editForm.getForm().findField('pro_uid').setValue(pro_uid);
      editForm.getForm().findField('evn_uid').setValue(evn_uid);
      editForm.getForm().findField('dynaform').setValue(webEntryList.data.DYN_TITLE);
      editForm.getForm().findField('username').setValue(webEntryList.data.USR_UID);
      editForm.getForm().findField('initDyna').setValue(webEntryList.data.DYN_UID);
      editForm.show();
      editButton.disable();
      deleteButton.disable();
    }
  });
  
  var deleteButton = new Ext.Action({
    text: _('ID_DELETE'),
    iconCls: 'button_menu_ext ss_sprite  ss_delete',
    hidden: true,
    handler: function(){
      Ext.Msg.confirm(_('ID_CONFIRM'),_('ID_CONFIRM_DELETE_WEB_ENTRY'), function(btn, text){
        if (btn=='yes'){
          var file_name = webForm.getForm().findField('dynaform').getValue();
          Ext.Ajax.request({
            url: 'webEntryProxy/delete',
            params: {PRO_UID: pro_uid, EVN_UID: evn_uid, FILE_NAME: file_name},
            success: function (r,o){
              response = Ext.util.JSON.decode(r.responseText);
              if (response.success){
                PMExt.notify(_('ID_WEB_ENTRY'),response.msg);
                newButton.show();
                editButton.hide();
                deleteButton.hide();
                webForm.getForm().findField('link').setValue(_('ID_NOT_DEFINED'));
                webForm.getForm().findField('task').setValue(_('ID_NOT_DEFINED'));
                webForm.getForm().findField('dynaform').setValue(_('ID_NOT_DEFINED'));
                webForm.getForm().findField('user').setValue(_('ID_NOT_DEFINED'));
                goToWebEntry.disable();
                webForm.show();
                editForm.hide();
              }else{
                PMExt.error(_('ID_ERROR'),response.msg);
              }
            }, 
            failure: function (r,o){
              PMExt.notify( _('ID_STATUS') , _('ID_LOAD_FAILED') );
            }
          });
        }
      });
    }
  });
  
  var goToWebEntry = new Ext.Action({
    text: _('ID_TEST_WEB_ENTRY'),
    disabled: true,
    handler: function(){
      var url = webForm.getForm().findField('link').getValue();
      window.open(url,'','');
    }
  });
  
  var saveButton = new Ext.Action({
    text: _('ID_SAVE'),
    disabled: false,
    handler: function(){
      var user = editForm.getForm().findField('username').getValue();
      var pass = editForm.getForm().findField('password').getValue();
      
      Ext.Ajax.request({
        url: 'webEntryProxy/checkCredentials',
        params: {PRO_UID: pro_uid, EVN_UID: evn_uid, WS_USER: user, WS_PASS: pass},
        success: function(r,o){
          var resp = Ext.util.JSON.decode(r.responseText);
          if (resp.success){
            editForm.getForm().submit({
              success: function(f,a){
                var rs = Ext.util.JSON.decode(a.response.responseText);
                if (rs.success){
                  newButton.hide();
                  editButton.show();
                  deleteButton.show();
                  webForm.getForm().findField('link').setValue(rs.W_LINK);
                  webForm.getForm().findField('task').setValue(rs.TAS_TITLE);
                  webForm.getForm().findField('dynaform').setValue(rs.DYN_TITLE);
                  webForm.getForm().findField('user').setValue(rs.USR_UID);
                  goToWebEntry.enable();
                  webForm.show();
                  editForm.hide();
                  newButton.enable();
                  editButton.enable();
                  deleteButton.enable();
                  PMExt.notify(_('ID_WEB_ENTRY'),rs.msg);
                }else{
                  PMExt.error(_('ID_WEB_ENTRY'),rs.msg);
                }
              },
              failure: function(f,r){
                PMExt.notify( _('ID_STATUS') , _('ID_LOAD_FAILED') );
              }
            });
          }else{
            PMExt.error(_('ID_CREDENTIAL_ERROR'),resp.msg);
          }
        },
        failure: function(r,o){
          PMExt.notify( _('ID_STATUS') , _('ID_LOAD_FAILED') );
        }
      });
    }
  });
  
  var cancelButton = new Ext.Action({
    text: _('ID_CANCEL'),
    disabled: false,
    handler: function(){
      editForm.hide();
      webForm.show();
      newButton.enable();
      editButton.enable();
      deleteButton.enable();
    }
  });
  
  var webEntryList;
  
  
  var webForm = new Ext.FormPanel({
    labelWidth    : 120, // label settings here cascade unless overridden
    frame         : true,
    width         : 585,
    autoHeight    : true,
    defaultType   : 'textfield',
    buttonAlign   : 'center',
    items: [
            {
              xtype      :'fieldset',
              title      : _('ID_WEB_ENTRY_SUMMARY'),
              collapsible: false,
              autoHeight :true,
              buttonAlign: 'center',
              defaults   : {width: 210},
              items: [
                      {xtype: 'textfield', fieldLabel: _('ID_WEB_ENTRY_LINK'), name: 'link', width: 400, readOnly: true, selectOnFocus: true},
                      {xtype: 'textfield', fieldLabel: _('ID_TASK'), name: 'task', width: 400, readOnly: true},
                      {xtype: 'textfield', fieldLabel: _('ID_INITIAL_DYNAFORM'), name: 'dynaform', width: 400, readOnly: true},
                      {xtype: 'textfield', fieldLabel: _('ID_USER'), name: 'user', width: 400, readOnly: true}
                      ]
            }
            ],
            buttons: [goToWebEntry]
  , hidden: true
  });
  
  var editForm = new Ext.FormPanel({
    labelWidth    : 120, // label settings here cascade unless overridden
    frame         : true,
    autoWidth     : true,
    autoHeight    : true,
    defaultType   : 'textfield',
    buttonAlign   : 'center',
    url           : 'webEntryProxy/save',
    items: [
            {xtype: 'hidden', name: 'pro_uid', hidden: true},
            {xtype: 'hidden', name: 'evn_uid', hidden: true},
            {xtype: 'hidden', name: 'dynaform', hidden: true},
            {
              xtype      :'fieldset',
              title      : _('ID_NEW_WEB_ENTRY'),
              collapsible: false,
              autoHeight :true,
              id : 'frameEdit',
              buttonAlign: 'center',
              defaults   : {width: 210},
              items: [
                      {
                        width          : 400,
                        xtype          : 'combo',
                        mode           : 'local',
                        forceSelection : true,
                        allowBlank     : false,
                        triggerAction  : 'all',
                        fieldLabel      : _('ID_INITIAL_DYNAFORM'),
                        name            : 'initDyna',
                        hiddenName      : 'initDyna',
                        displayField    : 'name',
                        valueField      : 'value',
                        store           : new Ext.data.JsonStore({
                          fields : ['name', 'value'],
                          data   :oDyna
                        })
                      },
                      {xtype: 'textfield', fieldLabel: _('ID_USER'), name: 'username', width: 200, allowBlank: false},
                      {xtype: 'textfield', fieldLabel: _('ID_PASSWORD'), name: 'password', width: 200, inputType: 'password', allowBlank: false}
                      ]
            }
            ],
            hidden: true,
            buttons: [saveButton, cancelButton]
  });
  
  
  var webEntryWindow = new Ext.Window({
    title:_('ID_START_MESSAGE_EVENT_WEB_ENTRY'),
    collapsible: false,
    width: 600,
    autoHeight: true,
    layout: 'fit',
    plain: true,
    buttonAlign: 'center',
    scope : workflow,
    modal: true,
    items: [webForm, editForm],
    tbar: [newButton, editButton, deleteButton]
  });
  workflow.webEntryWindow = webEntryWindow;
  
  
  var evn_uid = workflow.currentSelection.id;
  Ext.Ajax.request({
    url: 'webEntryProxy/load',
    params:{PRO_UID: pro_uid, EVN_UID: evn_uid},
    success: function(r,o){
      webEntryList = Ext.util.JSON.decode(r.responseText);
      if (webEntryList.success){
        if (webEntryList.data.W_LINK !=''){
          newButton.hide();
          editButton.show();
          deleteButton.show();
          webForm.getForm().findField('link').setValue(webEntryList.data.W_LINK);
          webForm.getForm().findField('task').setValue(webEntryList.data.TAS_TITLE);
          webForm.getForm().findField('dynaform').setValue(webEntryList.data.DYN_TITLE);
          webForm.getForm().findField('user').setValue(webEntryList.data.USR_UID);
          goToWebEntry.enable();
          webForm.show();
        }else{
          newButton.show();
          editButton.hide();
          deleteButton.hide();
          webForm.getForm().findField('link').setValue(_('ID_NOT_DEFINED'));
          webForm.getForm().findField('task').setValue(_('ID_NOT_DEFINED'));
          webForm.getForm().findField('dynaform').setValue(_('ID_NOT_DEFINED'));
          webForm.getForm().findField('user').setValue(_('ID_NOT_DEFINED'));
          goToWebEntry.disable();
          webForm.show();
        }
      }else{
        PMExt.notify( _('ID_STATUS') , _('ID_LOAD_FAILED') );
      }
    }, 
    failure: function(r,o){
      PMExt.notify( _('ID_STATUS') , _('ID_LOAD_FAILED') );
    }
  });
  
  webEntryWindow.show();
}

pmosExt.prototype.popCaseSchedular= function(_5678){
  Ext.QuickTips.init();
  var oPmosExt = new pmosExt();
  
  var oTask = workflow.taskUid;
  if(typeof oTask != 'undefined')
  {
    taskName = oTask[0].name;
    task_uid = oTask[0].value;
  }
  var evn_uid = workflow.currentSelection.id;
  var case_SCH_UID;
  var caseSchedulerData;
  
  var newButton = new Ext.Action({
    text: _('ID_NEW_CASE_SCHEDULER'),
    iconCls: 'button_menu_ext ss_sprite ss_add',
    hidden: true,
    handler: function(){
      editForm.getForm().reset();
      oPmosExt.hideSchOptions(performFields,0);
      Ext.getCmp('fTask').setText(taskName);
      editForm.getForm().findField('pro_uid').setValue(pro_uid);
      editForm.getForm().findField('evn_uid').setValue(evn_uid);
      editForm.getForm().findField('tas_uid').setValue(task_uid);
      editForm.show();
      summaryForm.hide();
      newButton.disable();
    }
  });
  
  var editButton = new Ext.Action({
    text: _('ID_EDIT'),
    iconCls: 'button_menu_ext ss_sprite ss_pencil',
    hidden: true,
    handler: function(){
      var sSCH_UID;
      if (typeof caseSchedulerData != 'undefined')
        sSCH_UID = caseSchedulerData.SCH_UID;
      else
        sSCH_UID = case_SCH_UID;
      Ext.Ajax.request({
        url: 'caseSchedulerProxy/loadCS',
        params: {SCH_UID: sSCH_UID},
        success: function(r,o){
          var res = Ext.decode(r.responseText);
          if (res.success){
            editForm.getForm().reset();
            oPmosExt.hideSchOptions(performFields,(res.data.SCH_OPTION-1));
            Ext.getCmp('fTask').setText(taskName);
            editForm.getForm().findField('pro_uid').setValue(pro_uid);
            editForm.getForm().findField('evn_uid').setValue(evn_uid);
            editForm.getForm().findField('tas_uid').setValue(task_uid);
            editForm.getForm().findField('sch_uid').setValue(res.data.SCH_UID);
            editForm.getForm().findField('fUser').setValue(res.data.SCH_DEL_USER_NAME);
            editForm.getForm().findField('fDescription').setValue(res.data.SCH_NAME);
            editForm.getForm().findField('fType').setValue(res.data.SCH_OPTION);
            editForm.getForm().findField('SCH_START_DATE').setValue(res.data.START_DATE);
            editForm.getForm().findField('SCH_END_DATE').setValue(res.data.END_DATE);
            editForm.getForm().findField('SCH_START_TIME').setValue(res.data.EXEC_TIME);
            for (var i=0; i<7; i++){
              var name = editForm.getForm().findField('SCH_WEEK_DAY').items.items[i].getName();
              switch(name){
                case 'W1': editForm.getForm().findField('SCH_WEEK_DAY').items.items[i].setValue(res.data.W1); break;
                case 'W2': editForm.getForm().findField('SCH_WEEK_DAY').items.items[i].setValue(res.data.W2); break;
                case 'W3': editForm.getForm().findField('SCH_WEEK_DAY').items.items[i].setValue(res.data.W3); break;
                case 'W4': editForm.getForm().findField('SCH_WEEK_DAY').items.items[i].setValue(res.data.W4); break;
                case 'W5': editForm.getForm().findField('SCH_WEEK_DAY').items.items[i].setValue(res.data.W5); break;
                case 'W6': editForm.getForm().findField('SCH_WEEK_DAY').items.items[i].setValue(res.data.W6); break;
                case 'W7': editForm.getForm().findField('SCH_WEEK_DAY').items.items[i].setValue(res.data.W7); break;
              }  
            }
            editForm.getForm().findField('SCH_START_DAY').setValue(res.data.TYPE_CMB);
            editForm.getForm().findField('SCH_START_DAY_OPT_1').setValue(res.data.EACH_DAY);
            editForm.getForm().findField('SCH_START_DAY_OPT_2_WEEKS').setValue(res.data.CMB_1);
            editForm.getForm().findField('SCH_START_DAY_OPT_2_DAYS_WEEK').setValue(res.data.CMB_2);
            if (res.data.TYPE_CMB === 1){
              editForm.getForm().findField('SCH_START_DAY_OPT_1').show();
              editForm.getForm().findField('SCH_START_DAY_OPT_2_WEEKS').hide();
              editForm.getForm().findField('SCH_START_DAY_OPT_2_DAYS_WEEK').hide();
              editForm.getForm().findField('SCH_START_DAY_OPT_1').enable();
              editForm.getForm().findField('SCH_START_DAY_OPT_2_WEEKS').disable();
              editForm.getForm().findField('SCH_START_DAY_OPT_2_DAYS_WEEK').disable();
            }
            if (res.data.TYPE_CMB == 2){
              editForm.getForm().findField('SCH_START_DAY_OPT_1').disable();
              editForm.getForm().findField('SCH_START_DAY_OPT_2_WEEKS').enable();
              editForm.getForm().findField('SCH_START_DAY_OPT_2_DAYS_WEEK').enable();
              editForm.getForm().findField('SCH_START_DAY_OPT_1').hide();
              editForm.getForm().findField('SCH_START_DAY_OPT_2_WEEKS').show();
              editForm.getForm().findField('SCH_START_DAY_OPT_2_DAYS_WEEK').show();
            }
            for (i=0; i<12; i++){
              var name = editForm.getForm().findField('SCH_MONTH').items.items[i].getName();
              switch(name){
                case 'M1': editForm.getForm().findField('SCH_MONTH').items.items[i].setValue(res.data.M1); break;
                case 'M2': editForm.getForm().findField('SCH_MONTH').items.items[i].setValue(res.data.M2); break;
                case 'M3': editForm.getForm().findField('SCH_MONTH').items.items[i].setValue(res.data.M3); break;
                case 'M4': editForm.getForm().findField('SCH_MONTH').items.items[i].setValue(res.data.M4); break;
                case 'M5': editForm.getForm().findField('SCH_MONTH').items.items[i].setValue(res.data.M5); break;
                case 'M6': editForm.getForm().findField('SCH_MONTH').items.items[i].setValue(res.data.M6); break;
                case 'M7': editForm.getForm().findField('SCH_MONTH').items.items[i].setValue(res.data.M7); break;
                case 'M8': editForm.getForm().findField('SCH_MONTH').items.items[i].setValue(res.data.M8); break;
                case 'M9': editForm.getForm().findField('SCH_MONTH').items.items[i].setValue(res.data.M9); break;
                case 'M10': editForm.getForm().findField('SCH_MONTH').items.items[i].setValue(res.data.M10); break;
                case 'M11': editForm.getForm().findField('SCH_MONTH').items.items[i].setValue(res.data.M11); break;
                case 'M12': editForm.getForm().findField('SCH_MONTH').items.items[i].setValue(res.data.M12); break;
              }  
            }
            
            
            editForm.show();
            summaryForm.hide();
            editButton.disable();
            deleteButton.disable();
            changeButton.disable();
          }else{
            PMEXt.error(_('ID_CASE_SCHEDULER'), res.msg);
          }
        },
        failure: function(r,o){
          PMExt.notify( _('ID_STATUS') , _('ID_LOAD_FAILED'));
        }
      });
    }
  });
  
  var changeButton = new Ext.Action({
    text: _('ID_CHANGE_STATUS'),
    iconCls: 'button_menu_ext ss_sprite ss_arrow_switch',
    hidden: true,
    handler: function(){
      var sSCH_UID;
      if (typeof caseSchedulerData != 'undefined')
        sSCH_UID = caseSchedulerData.SCH_UID;
      else
        sSCH_UID = case_SCH_UID;
      Ext.Ajax.request({
        url: 'caseSchedulerProxy/changeStatus',
        params: {SCH_UID: sSCH_UID},
        success: function(r,o){
          var respuesta = Ext.decode(r.responseText);
          if (respuesta.success){
            PMExt.notify(_('ID_CASE_SCHEDULER'), respuesta.msg);
            Ext.getCmp('status').setText(respuesta.SCH_STATUS);
          }else{
            PMExt.error(_('ID_STATUS'),respuesta.msg);
          }
        },
        failure: function(r,o){
          PMExt.notify( _('ID_STATUS') , _('ID_LOAD_FAILED'));
        }
      });
    }
  });
  
  var deleteButton = new Ext.Action({
    text: _('ID_DELETE'),
    iconCls: 'button_menu_ext ss_sprite  ss_delete',
    hidden: true,
    handler: function(){
      Ext.Msg.confirm(_('ID_CONFIRM'),_('ID_CONFIRM_DELETE_CASE_SCHEDULER'),
          function(btn, text){
        if (btn=='yes'){
          var sSCH_UID;
          if (typeof caseSchedulerData != 'undefined')
            sSCH_UID = caseSchedulerData.SCH_UID;
          else
            sSCH_UID = case_SCH_UID;
          Ext.Ajax.request({
            url: 'caseSchedulerProxy/delete',
            params: {SCH_UID: sSCH_UID, EVN_UID: evn_uid},
            success: function(r,o){
              var rs = Ext.decode(r.responseText);
              if (rs.success){
                PMExt.notify(_('ID_CASE_SCHEDULER'), rs.msg);
                editButton.hide();
                deleteButton.hide();
                newButton.show();
                changeButton.hide();
                summaryForm.getForm().reset();
                Ext.getCmp('description').setText(_('ID_NOT_DEFINED'));
                Ext.getCmp('task').setText(_('ID_NOT_DEFINED'));
                Ext.getCmp('status').setText(_('ID_NOT_DEFINED'));
                Ext.getCmp('next').setText(_('ID_NOT_DEFINED'));
                Ext.getCmp('last').setText(_('ID_NOT_DEFINED'));
              }else{
                PMExt.error(_('ID_STATUS'),rs.msg);
              }
            },
            failure: function(r, o){
              PMExt.notify( _('ID_STATUS') , _('ID_LOAD_FAILED'));
            }
          });
        }
      }
      );
    }
  });
  
  var saveButton = new Ext.Action({
    text: _('ID_SAVE'),
    hidden: false,
    handler: function(){
      var user = editForm.getForm().findField('fUser').getValue();
      var pass = editForm.getForm().findField('fPassword').getValue();
      if (((user == '')&&(pass=='')) || (user=='')) {
        PMExt.warning(_('ID_ERROR'), _('ID_USER_CREDENTIALS_REQUIRED'));
        return;
      }
      //Ext.getCmp('paintarea').getEl().mask(_('ID_PROCESSING'));
      Ext.Ajax.request({
        url: 'caseSchedulerProxy/checkCredentials',
        params: {PRO_UID: pro_uid, EVN_UID: evn_uid, WS_USER: user, WS_PASS: pass},
        success: function (r,o){
          //Ext.getCmp('paintarea').getEl().unmask();
          var resp = Ext.util.JSON.decode(r.responseText);
          if (resp.success){
            editForm.getForm().findField('usr_uid').setValue(resp.msg);
            editForm.getForm().submit({
              success: function(f,a){
                var res = Ext.decode(a.response.responseText);
                if (res.success){
                  editForm.getForm().reset();
                  editForm.hide();
                  Ext.getCmp('description').setText(res.DESCRIPTION);
                  Ext.getCmp('task').setText(res.TAS_NAME);
                  Ext.getCmp('status').setText('ACTIVE');
                  Ext.getCmp('next').setText(res.NEXT);
                  Ext.getCmp('last').setText('');
                  if (typeof caseSchedulerData != 'undefined')
                    caseSchedulerData.SCH_UID = res.SCH_UID;
                  else
                    case_SCH_UID = res.SCH_UID;
                  summaryForm.show();
                  newButton.enable();
                  editButton.enable();
                  deleteButton.enable();
                  changeButton.enable();
                  newButton.hide();
                  editButton.show();
                  deleteButton.show();
                  changeButton.show();
                  PMExt.notify(_('ID_CASE_SCHEDULER'),res.msg);
                }else{
                  PMExt.error(_('ID_ERROR'),res.msg);
                }
              },
              failure: function(f,a){
                PMExt.error(_('ID_ERROR'),res.msg);
              }
            });
          }else{
            PMExt.error(_('ID_CREDENTIAL_ERROR'),resp.msg);
          }
        },
        failure: function (r,o){
          //Ext.getCmp('paintarea').getEl().unmask();
          PMExt.notify( _('ID_STATUS') , _('ID_LOAD_FAILED'));
        }
      });
    }
  });
  
  var cancelButton = new Ext.Action({
    text: _('ID_CANCEL'),
    hidden: false,
    handler: function(){
      editForm.getForm().reset();
      editForm.hide();
      summaryForm.show();
      newButton.enable();
      editButton.enable();
      deleteButton.enable();
      changeButton.enable();
    }
  });
  
  
  
  
  var summaryForm = new Ext.FormPanel({
    labelWidth    : 120,
    frame         : true,
    autoHeight    : true,
    defaultType   : 'textfield',
    buttonAlign   : 'center',
    items: [
            {
              xtype      :'fieldset',
              title      : _('ID_CASE_SCHEDULER_SUMMARY'),
              collapsible: false,
              autoHeight :true,
              buttonAlign: 'center',
              defaults   : {width: 210},
              items: [
                      {xtype: 'label', fieldLabel: _('ID_DESCRIPTION'), id: 'description', width: 300, readOnly: true, selectOnFocus: true},
                      {xtype: 'label', fieldLabel: _('ID_TASK'), id: 'task', width: 300, readOnly: true},
                      {xtype: 'label', fieldLabel: _('ID_STATUS'), id: 'status', width: 300, readOnly: true},
                      {xtype: 'label', fieldLabel: _('ID_LAST_RUN_TIME'), id: 'last', width: 300, readOnly: true},
                      {xtype: 'label', fieldLabel: _('ID_TIME_NEXT_RUN'), id: 'next', width: 300, readOnly: true}
                      
                      ]
            }
            ],
            hidden : true
  });
  
  var generalFields = new Ext.form.FieldSet({
    title: 'Properties',
    labelWidth: 100,
    items: [
            {xtype: 'label', fieldLabel: _('ID_TASK'), width: 150, id: 'fTask'},
            {xtype: 'textfield', fieldLabel: _('ID_DESCRIPTION'), width: 150, name: 'fDescription', allowBlank: false},
            {xtype: 'textfield', fieldLabel: _('ID_USER'), width: 150, name: 'fUser', allowBlank: false},
            {xtype: 'textfield', fieldLabel: _('ID_PASSWORD'), width: 150, inputType: 'password', name: 'fPassword', allowBlank: false},
            {
              xtype: 'combo',
              fieldLabel      : _('ID_PERFORM_TASK'),
              name: 'fType',
              mode            : 'local',
              triggerAction   : 'all',
              forceSelection  : true,
              allowBlank      : false,
              value           : 'Daily',
              editable        : false,
              displayField    : 'name',
              valueField      : 'value',
              submitValue     : true,
              scope           : _5678,
              store           : new Ext.data.JsonStore({
                fields : ['name', 'value'],
                data   :[
                         {name : 'Daily',        value: '1', selected: true},
                         {name : 'Weekly',       value: '2'},
                         {name : 'Monthly',      value: '3'},
                         {name : 'One time only',value: '4'}
                         ]
              }),
              width: 150,
              onSelect: function(record, index){
                oPmosExt.hideSchOptions(performFields,index);
                this.setValue(record.data[this.valueField || this.displayField]);
                this.collapse();
                
              }
            },
            {xtype: 'hidden', name: 'pro_uid'},
            {xtype: 'hidden', name: 'evn_uid'},
            {xtype: 'hidden', name: 'tas_uid'},
            {xtype: 'hidden', name: 'usr_uid'},
            {xtype: 'hidden', name: 'sch_uid'},
            {name: 'SCH_DAYS_PERFORM_TASK', hidden: true, value: 1},
            {name: 'SCH_WEEK_DAYS', hidden: true},
            {name: 'SCH_MONTHS', hidden: true}
            ]
  });
  
  var performFields = new Ext.form.FieldSet({
    title: 'Scheduler Details',
    labelWidth: 70,
    items: [
            {xtype: 'datefield', name: 'SCH_START_DATE',format: 'Y-m-d',fieldLabel: _('ID_START_DATE'), allowBlank: false, width: 150},
            {xtype: 'datefield', name: 'SCH_END_DATE',format: 'Y-m-d', fieldLabel  : _('ID_END_DATE'), allowBlank: true, width: 150},
            {xtype: 'textfield', fieldLabel  : _('ID_EXECUTION_TIME'), name: 'SCH_START_TIME', width: 80, allowBlank: false},
            { //3
              xtype       : 'checkboxgroup',
              fieldLabel  : _('ID_SELECT_DAY_OF_WEEK'),
              name        : 'SCH_WEEK_DAY',
              hidden      : true,
              columns     : 2,
              items       : [
                             {boxLabel: 'Monday',    name: 'W1', checked: true},
                             {boxLabel: 'Tuesday',   name: 'W2'},
                             {boxLabel: 'Wednesday', name: 'W3'},
                             {boxLabel: 'Thursday',  name: 'W4'},
                             {boxLabel: 'Friday',    name: 'W5'},
                             {boxLabel: 'Saturday',  name: 'W6'},
                             {boxLabel: 'Sunday',    name: 'W7'}
                             ],
                             allowBlank: false
            },
            { //4          
              labelWidth      : 0,
              xtype           : 'combo',
              mode            : 'local',
              triggerAction   : 'all',
              forceSelection  : true,
              hidden          : true,
              editable        : false,
              allowBlank: false,
              name            : 'SCH_START_DAY',
              displayField    : 'name',
              valueField      : 'value',
              value           : 'Day of Month',
              store           : new Ext.data.JsonStore({
                fields : ['name', 'value'],
                data   : [
                          {name : 'Day of Month',   value: '1'},
                          {name : 'The Day',        value: '2', selected: true},
                          ]
              }),
              onSelect: function(record, index){
                var fieldsToToggle = new Array();
                var fields = performFields.items.items;
                if (index==0){
                  fieldsToToggle = [fields[5],fields[6]];
                  oPmosExt.toggleFields(fieldsToToggle, false);
                  fieldsToToggle = [fields[7]];
                  oPmosExt.toggleFields(fieldsToToggle, true);
                }else{
                  fieldsToToggle = [fields[5],fields[6]];
                  oPmosExt.toggleFields(fieldsToToggle, true);
                  fieldsToToggle = [fields[7]];
                  oPmosExt.toggleFields(fieldsToToggle, false);
                }
                this.setValue(record.data[this.valueField || this.displayField]);
                this.collapse();
              },
              width           : 100
            },
            { //5
              width           : 100,
              labelWidth      : 0,
              xtype           : 'combo',
              mode            : 'local',
              triggerAction   : 'all',
              forceSelection  : true,
              hidden          : true,
              editable        : false,
              allowBlank: false,
              name            : 'SCH_START_DAY_OPT_2_WEEKS',
              displayField    : 'name',
              valueField      : 'value',
              value           : 'First',
              store           : new Ext.data.JsonStore({
                fields : ['name', 'value'],
                data   : [
                          {name : 'First',    value: '1', selected: true},
                          {name : 'Second',   value: '2'},
                          {name : 'Third',    value: '3'},
                          {name : 'Fourth',   value: '4'},
                          {name : 'Last',     value: '5'},
                          ]
              })
            },
            { //6
              width         : 100,
              labelWidth    : 0,
              xtype         : 'combo',
              mode          : 'local',
              triggerAction : 'all',
              forceSelection: true,
              hidden        : true,
              editable      : false,
              allowBlank: false,
              name          : 'SCH_START_DAY_OPT_2_DAYS_WEEK',
              displayField  : 'name',
              valueField    : 'value',
              value         : 'Monday',
              store         : new Ext.data.JsonStore({
                fields : ['name', 'value'],
                data   : [
                          {name : 'Monday',     value: '1', selected: true},
                          {name : 'Tuesday',    value: '2'},
                          {name : 'Wednesday',  value: '3'},
                          {name : 'Thursday',   value: '4'},
                          {name : 'Friday',     value: '5'},
                          {name : 'Saturday',   value: '6'},
                          {name : 'Sunday',     value: '7'},
                          ]
              })
            },
            {xtype : 'textfield', name: 'SCH_START_DAY_OPT_1', hidden: true, value: 1, width: 40, allowBlank: false},//7
            {//8
              xtype     : 'checkboxgroup',
              fieldLabel: _('ID_OF_THE_MONTH'),
              name      : 'SCH_MONTH',
              hidden: true,
              allowBlank: false,
              columns: 3,
              items: [
                      {boxLabel : 'Jan',   name: 'M1'},
                      {boxLabel : 'Feb',   name: 'M2'},
                      {boxLabel : 'Mar',   name: 'M3'},
                      {boxLabel : 'Apr',   name: 'M4'},
                      {boxLabel : 'May',   name: 'M5'},
                      {boxLabel : 'Jun',   name: 'M6'},
                      {boxLabel : 'Jul',   name: 'M7'},
                      {boxLabel : 'Aug',   name: 'M8'},
                      {boxLabel : 'Sep',   name: 'M9'},
                      {boxLabel : 'Oct',   name: 'M10'},
                      {boxLabel : 'Nov',   name: 'M11'},
                      {boxLabel : 'Dec',   name: 'M12'},
                      ]
            }
            ]
  });
  
  var editForm = new Ext.FormPanel({
    frame         : true,
    url: 'caseSchedulerProxy/save',
    id : 'editForm',
    buttonAlign   : 'center',
    layout        : 'fit',
    autoHeight    : true,
    items: [{
      layout: 'column',
      autoScroll: true,
      autoHeight: true,
      items:[
             {columnWidth:.5, padding: 2, layout: 'form', items: [generalFields]},
             {columnWidth:.5, padding: 2, layout: 'form', items: [performFields]}
             ]
    }],
    buttons: [saveButton, cancelButton],
    hidden: true
  });
  
  var caseSchedulerWindow = new Ext.Window({
    title: _('ID_START_TIME_EVENT'),
    layout         : 'fit',
    plain          : true,
    buttonAlign    : 'center',
    autoHeight     : true,
    width          : 600,
    modal          : true,
    items: [summaryForm, editForm],
    tbar: [newButton, editButton, deleteButton, changeButton]
  });
  
  workflow.caseSchedulerWindow = caseSchedulerWindow;
  
  Ext.Ajax.request({
    url: 'caseSchedulerProxy/load',
    params: {PRO_UID: pro_uid, EVN_UID: evn_uid},
    success: function (r,o){
      var resp = Ext.util.JSON.decode(r.responseText);
      if (resp.success){
        caseSchedulerData = resp.data;
        editButton.show();
        deleteButton.show();
        newButton.hide();
        changeButton.show();
        summaryForm.getForm().reset();
        Ext.getCmp('description').setText(caseSchedulerData.SCH_NAME);
        Ext.getCmp('task').setText(taskName);
        Ext.getCmp('status').setText(caseSchedulerData.SCH_STATE);
        Ext.getCmp('next').setText(caseSchedulerData.SCH_TIME_NEXT_RUN);
        Ext.getCmp('last').setText(caseSchedulerData.SCH_LAST_RUN_TIME);
        summaryForm.show();
      }else{
        editButton.hide();
        deleteButton.hide();
        newButton.show();
        changeButton.hide();
        summaryForm.getForm().reset();
        Ext.getCmp('description').setText(_('ID_NOT_DEFINED'));
        Ext.getCmp('task').setText(_('ID_NOT_DEFINED'));
        Ext.getCmp('status').setText(_('ID_NOT_DEFINED'));
        Ext.getCmp('next').setText(_('ID_NOT_DEFINED'));
        Ext.getCmp('last').setText(_('ID_NOT_DEFINED'));
        summaryForm.show();
      }
    },
    failure: function(r,o){
      PMExt.notify( _('ID_STATUS') , _('ID_LOAD_FAILED'));
    }
  });
  
  caseSchedulerWindow.show();
}

pmosExt.prototype.hideSchOptions = function(formObj,index){
  var fields = formObj.items.items;
  var fieldsToToggle = new Array();
  switch (index){
    case 0:
      fieldsToToggle = [fields[1]];
      this.toggleFields(fieldsToToggle, true);
      fieldsToToggle = [fields[3],fields[4],fields[5],fields[6],fields[7],fields[8]];
      this.toggleFields(fieldsToToggle, false);
      break;
    case 1:
      fieldsToToggle = [fields[1], fields[3]];
      this.toggleFields(fieldsToToggle, true);
      fieldsToToggle = [fields[4],fields[5],fields[6],fields[7],fields[8]];
      this.toggleFields(fieldsToToggle, false);
      break;
    case 2:
      fieldsToToggle = [fields[1],fields[4],fields[8]];
      this.toggleFields(fieldsToToggle, true);
      fieldsToToggle = [fields[3],fields[5],fields[6],fields[7]];
      this.toggleFields(fieldsToToggle, false);
      var sw = fields[4].getValue();
      if (sw == '1'){
        fieldsToToggle = [fields[5],fields[6]];
        this.toggleFields(fieldsToToggle, true);
      }else{
        fieldsToToggle = [fields[7]];
        this.toggleFields(fieldsToToggle, true);
      }
      break;
    case 3:
      fieldsToToggle = [fields[1],fields[3],fields[4],fields[5],fields[6],fields[7],fields[8]];
      this.toggleFields(fieldsToToggle, false);
      break;
  }
}                  

pmosExt.prototype.popTaskNotification= function(_5678){
        var oPmosExt = new pmosExt();
         //Get the Task Data
        var oTask = workflow.taskUid;
        var oTaskData = workflow.taskDetails;
        var toggle = true;
        var message = '';
        if(typeof oTaskData != 'undefined')
            {
                if(oTaskData.TAS_SEND_LAST_EMAIL == 'TRUE')
                    {
                        toggle = false;
                        message = oTaskData.TAS_DEF_MESSAGE;
                    }
                
            }
       var taskNotificationForm = new Ext.FormPanel({
         labelWidth: 120, // label settings here cascade unless overridden
         frame:true,
         bodyStyle:'padding:5px 5px 5px 5px',
         buttonAlign : 'center',
         defaultType: 'textfield',
       
         items: [{
           xtype:'fieldset',
           checkboxToggle:true,
           title: _('ID_AFTER_ROUTING_NOTIFY'),
           autoHeight:true,
           width : 460,
           labelWidth: 5,
           defaults: {width: 425},
           defaultType: 'textfield',
           collapsed: toggle,
           items :[{
             xtype: 'textarea',
             name: 'description',
             value:message,
             height : 250
           }]
         }]
       });
       
       var window = new Ext.Window({
         title: _('ID_INTERMEDIATE_MESSAGE_EVENTS'),
         collapsible: false,
         maximizable: false,
         width: 500,
         height: 380,
         minWidth: 490,
         minHeight: 370,
         layout: 'fit',
         plain: true,
         bodyStyle: 'padding:5px;',
         buttonAlign: 'center',
         items: taskNotificationForm,
         buttons: [{
           text: _('ID_SAVE'),
           handler: function(){
             //waitMsg: 'Saving...',       // Wait Message
             var fields      = taskNotificationForm.items.items;
             var tas_send    = fields[0].collapsed;
             if(tas_send == true)
               tas_send = false;
             else
               tas_send = true;
             var data = fields[0].items.items[0].getValue();
             var taskUid = _5678.workflow.taskid;
             if( typeof taskUid != 'undefined' && typeof taskUid.value != 'undefined')
             {
               var urlparams = '?action=saveInterMessageEvent&data={"uid":"'+ taskUid.value +'","tas_send":"'+tas_send+'","data":"'+data+'"}';
               Ext.Ajax.request({
                 url: "bpmn/processes_Ajax.php"+ urlparams,
                 success: function(response) {
                   window.close();
                 },
                 failure: function(){
                   Ext.Msg.alert ('Failure');
                 }
               });
             }
             else
             	 window.close(); //catching an error, because the taskUid was null, and was showing a fatal error in Javascript.
           }
         },{
           text: _('ID_CANCEL'),
           handler: function(){
           window.close();
         }
         }]
       });
     window.show();
  }
  
  pmosExt.prototype.loadTask = function(_5678){
    var taskUid = workflow.taskid;
    if(typeof taskUid != 'undefined')
    {
      var urlparams = '?action=loadTask&data={"uid":"'+ taskUid.value +'"}';
      Ext.Ajax.request({
        url: "bpmn/processes_Ajax.php"+ urlparams,
        success: function(response) {
            workflow.taskDetails = Ext.util.JSON.decode(response.responseText);
        },
        failure: function(){
           PMExt.notify( _('ID_STATUS') , _('ID_FAILURE') );
        }
      });
    }
  }

  pmosExt.prototype.getTriggerList = function(_5678){
    if(typeof pro_uid != 'undefined')
    {
      var urlparams = '?action=triggersList&data={"pro_uid":"'+ pro_uid +'"}';
      Ext.Ajax.request({
        url: "bpmn/processes_Ajax.php"+ urlparams,
        success: function(response) {
          workflow.triggerList = Ext.util.JSON.decode(response.responseText);
        },
        failure: function(){
          PMExt.notify( _('ID_STATUS') , _('ID_FAILURE') );
        }
      });
    }
  }

  pmosExt.prototype.toggleFields = function(field,bool){
    for(var i=0;i<field.length;i++){
      if(typeof field[i] != 'undefined'){
        if(bool){
          field[i].show();
          field[i].enable();
        }else{
          field[i].hide();
          field[i].disable();
        }
        //Hide-Show label
        field[i].getEl().up('.x-form-item').setDisplayed(bool);
      }
    }
  }

  pmosExt.prototype.popMessageEvent= function(_5678){
    var oTask = workflow.taskUid;
    var oPmosExt = new pmosExt();
    var messageEvent = new Ext.FormPanel({
    labelWidth: 150, // label settings here cascade unless overridden
    frame:true,
    bodyStyle:'padding:5px 5px 0',
    width: 500,
    height: 400,
    defaultType: 'textfield',
    items: [
      {
         xtype:'fieldset',
         title: _('ID_EVENT_MESSAGE'),
         collapsible: false,
         autoHeight:true,
         buttonAlign : 'center',
         defaults: {width: 210},
         defaultType: 'textfield',
         items:[
                  {
                     width     : 200,
                     fieldLabel: _('ID_DESCRIPTION'),
                     name      : 'description',
                     allowBlank: false
                  },{
                     width:          100,
                     xtype:          'combo',
                     mode:           'local',
                     triggerAction:  'all',
                     forceSelection: true,
                     editable:       false,
                     fieldLabel:     _('ID_STATUS'),
                     name:           'status',
                     displayField:   'name',
                     valueField:     'value',
                     store:          new Ext.data.JsonStore({
                                 fields : ['name', 'value'],
                                 data   : [
                                     {name : 'Active',   value: 'Active'},
                                     {name : 'InActive',   value: 'InActive'},
                                 ]
                             })
                 }
             ]
         },{
           xtype:'fieldset',
           title: _('ID_BEHAVIOUR'),
           collapsible: false,
           autoHeight:true,
           buttonAlign : 'center',
           defaults: {width: 210},
           defaultType: 'textfield',
           items:[{
                   width:          100,
                   xtype:          'combo',
                   mode:           'local',
                   triggerAction:  'all',
                   forceSelection: true,
                   editable:       false,
                   fieldLabel:     _('ID_TYPE'),
                   name:           'type',
                   displayField:   'name',
                   valueField:     'value',
                   store:          new Ext.data.JsonStore({
                               fields : ['name', 'value'],
                               data   : [
                                   {name : 'Single Task',   value: 'Single Task'},
                                   {name : 'Multiple Task',   value: 'Multiple Task'},
                               ]
                           })
                 },{
                   width:          150,
                   xtype:          'combo',
                   mode:           'local',
                   triggerAction:  'all',
                   forceSelection: true,
                   editable:       false,
                   fieldLabel:     _('ID_TIME_START_WITH_TASK'),
                   name:           'type',
                   displayField:   'name',
                   valueField:     'value',
                   store:          new Ext.data.JsonStore({
                               fields : ['name', 'value'],
                               data   :oTask
                          })
               },{
                   fieldLabel: _('ID_ESTIMATED_TASK_DURATION'),
                   width:          50,
                   name: 'estimatedTask',
                   allowBlank:false
               },{
                   fieldLabel: _('ID_EXECUTION_TIME'),
                   width:          50,
                   name: 'executionTime',
                   allowBlank:false
               },{
                   width:          150,
                   xtype:          'combo',
                   mode:           'local',
                   triggerAction:  'all',
                   forceSelection: true,
                   editable:       false,
                   fieldLabel:     _('ID_EXECUTION_TIME_INTERVAL'),
                   name:           'executionTimeInterval',
                   displayField:   'name',
                   valueField:     'value',
                   store:          new Ext.data.JsonStore({
                               fields : ['name', 'value'],
                               data   : [
                                   {name : 'After Interval Ends',   value: 'After Interval Ends'},
                                   {name : 'After Interval Starts',   value: 'After Interval Starts'},
                               ]
                           })
               }]
    }]

    });
    messageEvent.render(document.body);
    workflow.messageEventForm = messageEvent;

     var window = new Ext.Window({
     title: _('ID_END_MESSAGE_EVENT'),
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
     items: messageEvent,
     buttons: [{
         text: _('ID_CONTINUE'),
         handler: function(){
                        //waitMsg: 'Saving...',       // Wait Message
                            var fields   = messageEvent.items.items;
                            var tas_send = fields[0].collapsed;
                            if(tas_send == true)
                                tas_send = false;
                            else
                                tas_send = true;
                            var data = fields[0].items.items[0].getValue();
                            var taskUid = workflow.taskUid;
                            if(typeof taskUid[0] != 'undefined')
                            {
                             var urlparams = '?action=saveInterMessageEvent&data={"uid":"'+ taskUid[0].value +'","tas_send":"'+tas_send+'","data":"'+data+'"}';
                                Ext.Ajax.request({
                                        url: "bpmn/processes_Ajax.php"+ urlparams,
                                        success: function(response) {
                                            window.close();
                                        },
                                        failure: function(){
                                            PMExt.notify( _('ID_STATUS') , _('ID_FAILURE') );
                                        }
                                    });
                            }
                    }
         },{
         text: _('ID_CANCEL'),
         handler: function(){
                        window.close();
                    }
         }]
     });
     window.show();
}

  pmosExt.prototype.popMultipleEvent= function(_5678){
    Ext.QuickTips.init();
    var oTaskFrom = workflow.taskUidFrom;
    var oTaskTo   = workflow.taskUidTo;
    var oTriggers = workflow.triggerList;
    var oPmosExt  = new pmosExt();
    
    var multipleEvent = new Ext.FormPanel({
      url:'eventsSave.php',
      labelWidth: 160, 
      frame:true,
      bodyStyle:'padding:5px 5px 5px 5px',
      width : 490,
      height: 380,
      scope : _5678,
      defaultType: 'textfield',
      items: [
        {
          xtype:'fieldset',
          title: _('ID_BEHAVIOUR'),
          buttonAlign : 'center',
          width  : 470,
          defaults: {width: 220},
          defaultType: 'textfield',
          items:[
            {
              fieldLabel: _('ID_DESCRIPTION'),
              blankText:'Enter Event Description',
              name: 'EVN_DESCRIPTION',
              allowBlank:false
            },{
              width:          100,
              xtype:          'combo',
              mode:           'local',
              triggerAction:  'all',
              allowBlank:false,
              editable:       false,
              fieldLabel:     _('ID_STATUS'),
              name:           'EVN_STATUS',
              displayField:   'name',
              valueField:     'value',
              store:          new Ext.data.JsonStore({
                fields : ['name', 'value'],
                data   : [{name : 'Active',   value: 'ACTIVE'},{name : 'InActive',   value: 'INACTIVE'} ] 
              })
            },{
              fieldLabel: 'EVN_UID',
              name: 'EVN_UID',
              hidden:true
            },{
              fieldLabel: 'PRO_UID',
              name: 'PRO_UID',
              hidden:true
            },{
              fieldLabel: 'EVN_TYPE',
              name: 'EVN_TYPE',
              hidden:true
            },
/*
            {
              width:          150,
              xtype:          'combo',
              mode:           'local',
              triggerAction:  'all',
              allowBlank   :  false,
              editable:       false,
              fieldLabel:     _('ID_TIME_START_WITH_TASK'),
              name:           'EVN_TAS_UID_FROM',
              displayField:   'name',
              valueField:     'value',
              store:          new Ext.data.JsonStore({
                fields : ['name', 'value'],
                data   :oTaskFrom
              })
            },{
              width:          150,
              xtype:          'combo',
              mode:           'local',
              triggerAction:  'all',
              allowBlank   :  false,
              editable:       false,
              fieldLabel:     _('ID_TO'),
              name:           'EVN_TAS_UID_TO',
              displayField:   'name',
              valueField:     'value',
              store:          new Ext.data.JsonStore({
                fields : ['name', 'value'],
                data   :oTaskTo
              })
            },
*/
            {
              width:50,
              fieldLabel: _('ID_ESTIMATED_TASK_DURATION_DAYS'),
              name: 'EVN_TAS_ESTIMATED_DURATION',
              allowBlank:false
            },{
              width:50,
              fieldLabel: _('ID_EXECUTION_TIME_DAYS'),
              name: 'EVN_WHEN',
              allowBlank:false
            },{
              width:          150,
              fieldLabel: _('ID_WHEN_OCCURS'),
              xtype:          'combo',
              mode:           'local',
              triggerAction:  'all',
              allowBlank   :  false,
              editable:       false,
              fieldLabel:     '',
              name:           'EVN_WHEN_OCCURS',
              displayField:   'name',
              valueField:     'value',
              store:          new Ext.data.JsonStore({
                fields : ['name', 'value'],
                data   : [
                  {name : 'After Interval Ends',   value: 'AFTER_TIME'},
                  {name : 'After Interval Starts',   value: 'TASK_STARTED'},
                ]
              })
            },{
                name  :  'EVN_ACTION',
                value :  'EXECUTE_TRIGGER',
                hidden:  true
            },{
                width:          120,
                xtype:          'combo',
                mode:           'local',
                triggerAction:  'all',
                forceSelection: true,
                editable:       false,
                fieldLabel:     _('ID_EXECUTE_TRIGGER'),
                name:           'TRI_UID',
                displayField:   'TRI_TITLE',
                valueField:     'TRI_UID',
                store:          new Ext.data.JsonStore({
                  fields : ['TRI_TITLE', 'TRI_UID'],
                  data   :oTriggers
                })
            }]
          }]
    });
    multipleEvent.render(document.body);

    workflow.multipleEventForm = multipleEvent;
      var evn_uid = workflow.currentSelection.id;
      multipleEvent.form.load({
        url:'proxyEventsLoad?startInterId='+evn_uid,
        method:'GET',
        waitMsg:'Loading',
        success:function(form,action) {
        },
        failure:function(form, action) {
          PMExt.notify( _('ID_STATUS') , _('ID_FAILURE') );
        }
      });


    // Hide/Show Fields
    var eventTitle = multipleEvent.items.items[0];
    //var eventDesc = multipleEvent.items.items[1];

    var titleFields = eventTitle.items.items;
    //var descFields = eventDesc.items.items;
    var fieldsToToggle = [titleFields[2],titleFields[3],titleFields[4]];
    oPmosExt.toggleFields(fieldsToToggle,false);

    var window = new Ext.Window({
    title: _('ID_INTERMEDIATE_TIMER_EVENTS'),
    collapsible: false,
    maximizable: false,
    width: 500,
    height: 360,
    minWidth: 300,
    minHeight: 200,
    layout: 'fit',
    plain: true,
    bodyStyle: 'padding:5px;',
    buttonAlign: 'center',
    items: multipleEvent,
    buttons: [
      {
        text: _('ID_SAVE'),
        handler: function(){
          oPmosExt.saveInterTimer();
        }
      },{
        text: _('ID_CANCEL'),
        handler: function(){
          window.close();
        }
      }]
    });
    window.show();    
}
pmosExt.prototype.saveInterTimer=function()
{
  var mulEventform = workflow.multipleEventForm.getForm().getValues();
  var fieldSet1 = workflow.multipleEventForm.items.items[0];
  //var fieldSet2 = workflow.multipleEventForm.items.items[1];
  var eventId = workflow.currentSelection.id;
  var newFormValues = new Array();
  newFormValues = new Array();
  var sData = null;
  for (var key in mulEventform )
  {
    newFormValues[key] = new Array();
    switch(key){
      //case 'EVN_TAS_UID_FROM':
      //    if(typeof fieldSet2.items.items[2].value != 'undefined')
      //      newFormValues[key]= fieldSet2.items.items[1].value;
      //     else
      //      newFormValues[key] = '';
      //  break;
      //case 'EVN_TAS_UID_TO':
      //    if(typeof fieldSet2.items.items[3].value != 'undefined')
      //      newFormValues[key]= fieldSet2.items.items[2].value;
      //     else
      //      newFormValues[key]= '';
      //  break;
      case 'EVN_STATUS':
          newFormValues[key] = fieldSet1.items.items[1].value;
        break;
      case 'EVN_WHEN_OCCURS':
          newFormValues[key] = fieldSet1.items.items[7].value;
        break;
        case 'TRI_UID':
          if(typeof fieldSet1.items.items[8].value != 'undefined')
            newFormValues[key] = fieldSet1.items.items[8].value;
        break;
      case 'EVN_TYPE':
          newFormValues[key] = workflow.currentSelection.type;
        break;
      case 'EVN_RELATED_TO':
          newFormValues[key] = fieldSet2.items.items[0].value;
        break;
      case 'PRO_UID':
          newFormValues[key] = pro_uid;
        break;
      default:
          newFormValues[key] = mulEventform[key];
        break;
    }
   if(sData != null)
        sData = sData + '"'+key+'":"'+newFormValues[key]+'"'+',';
    else
        sData = '"'+key+'":"'+newFormValues[key]+'",';
  }
  //sData = Ext.util.JSON.encode(newFormValues);
  
  sData = '{'+sData.slice(0,sData.length-1)+'}';

  Ext.Ajax.request({
    url: 'eventsSave.php' ,
      success: function (response) {      // When saving data success
      	// :P  we are not saving nothing at all
          /*_39fd.workflow = workflow;
          var preObj = workflow.getStartEventConn(_39fd,'sourcePort','InputPort');
            //_39fd.workflow.taskid =  _39fd.workflow.taskUid[0];
          var newObj =  workflow.getStartEventConn(_39fd,'targetPort','OutputPort');
          
          workflow.saveRoute(preObj,newObj);*/

        Ext.MessageBox.alert (response.responseText);
        },
      failure: function () {      // when saving data failed
        PMExt.notify( _('ID_STATUS') , _('ID_FAILURE') );
      },
      params: {
        sData:sData
      }
    });
}

pmosExt.prototype.loadProcess=function(_5678)
{
    var urlparams = '?action=load&data={"uid":"'+ pro_uid +'"}';
        Ext.Ajax.request({
                url: "bpmn/processes_Ajax.php"+ urlparams,
                success: function(response) {
                    workflow.processInfo = Ext.util.JSON.decode(response.responseText);
                },
                failure: function(){
                     PMExt.notify( _('ID_STATUS') , _('ID_FAILURE') );
                }
            });
}
pmosExt.prototype.loadDynaforms=function()
{
        var taskUid = workflow.taskUid;
        if(typeof taskUid[0] != 'undefined')
        {
            var urlparams = '?action=dynaforms&data={"uid":"'+ taskUid[0].value +'"}';
            Ext.Ajax.request({
                    url: "bpmn/processes_Ajax.php"+ urlparams,
                    success: function(response) {
                        workflow.dynaList = Ext.util.JSON.decode(response.responseText);
                    },
                    failure: function(){
                         PMExt.notify( _('ID_STATUS') , _('ID_FAILURE') );
                    }
                });
        }
}
pmosExt.prototype.loadConnectedTask=function()
{
      var urlparams = '?action=load&data={"uid":"'+ pro_uid +'"}';
        Ext.Ajax.request({
                url: "bpmn/processes_Ajax.php"+ urlparams,
                success: function(response) {
                    workflow.processInfo = Ext.util.JSON.decode(response.responseText);

                },
                failure: function(){
                     PMExt.notify( _('ID_STATUS') , _('ID_FAILURE') );
                }
            });
}
pmosExt.prototype.loadWebEntry=function()
{
       var evn_uid = workflow.currentSelection.id;
       var urlparams = '?action=webEntry&data={"uid":"'+ pro_uid +'","evn_uid":"'+evn_uid+'"}';
        Ext.Ajax.request({
                url: "processes/processes_Ajax.php"+ urlparams,
                success: function(response) {
                    workflow.webEntryList = Ext.util.JSON.decode(response.responseText);
                },
                failure: function(){
                    PMExt.notify( _('ID_STATUS') , _('ID_FAILURE') );
                }
            });
}
pmosExt.prototype.loadEditProcess=function()
{
      var urlparams = '?action=process_Edit&data={"pro_uid":"'+ pro_uid +'"}';
        Ext.Ajax.request({
                url: "bpmn/processes_Ajax.php"+ urlparams,
                success: function(response) {
                    workflow.processEdit = Ext.util.JSON.decode(response.responseText);
                },
                failure: function(){
                    PMExt.notify( _('ID_STATUS') , _('ID_FAILURE') );
                }
            });
}
pmosExt.prototype.loadProcessCategory =function()
{
       var urlparams = '?action=loadCategory';
        Ext.Ajax.request({
                url: "bpmn/processes_Ajax.php"+ urlparams,
                success: function(response) {
                    workflow.processCategory = Ext.util.JSON.decode(response.responseText);
                },
                failure: function(){
                     PMExt.notify( _('ID_STATUS') , _('ID_FAILURE') );
                }
            });
}
pmosExt.prototype.saveEvent =function(urlparams)
{
        Ext.Ajax.request({
                url: "bpmn/processes_Ajax.php"+ urlparams,
                success: function(response) {

                },
                failure: function(){
                    PMExt.notify( _('ID_STATUS') , _('ID_FAILURE') );
                }
            });
}
