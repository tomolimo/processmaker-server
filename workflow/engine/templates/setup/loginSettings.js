Ext.onReady(function(){
  
  var cmbLanguages = new Ext.form.ComboBox({
      fieldLabel : TRANSLATIONS.ID_CACHE_LANGUAGE, // 'Language'
      hiddenName : 'lang',
      store : new Ext.data.Store( {
        proxy : new Ext.data.HttpProxy( {
          url : 'loginSettingsAjax',
          method : 'POST'
        }),
        baseParams : {request : 'getLangList'},
        reader : new Ext.data.JsonReader( {
          root : 'rows',
          fields : [ {name : 'LAN_ID'}, {name : 'LAN_NAME'} ]
        })
      }),
      valueField     : 'LAN_ID',
      displayField   : 'LAN_NAME', 
      emptyText      : 'Select',
      selectOnFocus  : true,
      editable       : false,
      allowBlank     : false,
      listeners:{
        select: function(){ChangeSettings('1');}
      }
    });
  
  cmbLanguages.store.on('load',function(){ cmbLanguages.setValue ( currentLang ) });
  cmbLanguages.store.load();
  saveButton = new Ext.Action({
    text : _('ID_SAVE_SETTINGS'),
    disabled : true,
    handler : saveSettings
  });
  
  loginFields = new Ext.form.FieldSet({   
    title: _('ID_LOGIN_SETTINGS'),
    items : [
      cmbLanguages,    
      {        
        xtype: 'checkbox',
        checked: currentOption,
        name: 'acceptRP',
        fieldLabel: _('ID_ENABLE_FOTGOT_PASSWORD'),
        id: 'ch_ii',
        listeners:{
          check:function(){ChangeSettings('2');}
        }
       
      },
    ],
    buttons : [saveButton]   
  });

  
  var frm = new Ext.FormPanel( {
    title: '&nbsp',
    id:'frm',
    labelWidth: 150,
    width:400,
    labelAlign:'right',
    autoScroll: true,
    bodyStyle:'padding:2px',
    waitMsgTarget : true,
    frame: true,
    
    defaults: {
      allowBlank: false,
      msgTarget: 'side',
      align:'center'
    },
    items:[ loginFields ]
   
  });
  //render to process-panel
  frm.render(document.body);
});

function saveSettings() {  
  Ext.getCmp('frm').getForm().submit( {  
    url : 'loginSettingsAjax?request=saveSettings',
    waitMsg : _('ID_SAVING_PROCESS'),
    timeout : 36000,
    success : function(obj, resp) {
      //nothing to do
      response = Ext.decode(resp.response.responseText);
      if (response.enable)     
        parent.PMExt.notify(_('ID_LOGIN_SETTINGS'),_('ID_ENABLE_FORGOT_PASSWORD'));
      else
        parent.PMExt.notify(_('ID_LOGIN_SETTINGS'),_('ID_DISABLE_FORGOT_PASSWORD'));
      saveButton.disable();
    },
    failure: function(obj, resp) {
      Ext.Msg.alert( _('ID_ERROR'), resp.result.msg);
    }    
  });
}

ChangeSettings = function(iType){ 
  saveButton.enable();
}
