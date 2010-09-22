Ext.onReady(function() {

  Ext.QuickTips.init();
  Ext.form.Field.prototype.msgTarget = 'side';
  
  var fsf = new Ext.FormPanel( {
    labelWidth : 170, // label settings here cascade unless overridden
    labelAlign: 'right',
    url : '',
    frame : true,
    title : TRANSLATIONS.ID_PM_ENV_SETTINGS_TITLE,
    bodyStyle : 'padding:5px 5px 0',
    width : 500,

    items : [],
    buttons : [
      {
        text : TRANSLATIONS.ID_SAVE_SETTINGS,
        disabled : false,
        handler : function() {
          fsf.getForm().submit( {
            url : 'environmentSettingsAjax?request=save&r=' + Math.random(),
            waitMsg : TRANSLATIONS.ID_SAVING_ENVIRONMENT_SETTINGS+'...',
            timeout : 36000,
            success : function(res, req) {
                Ext.MessageBox.show({ title: '', msg: req.result.msg, buttons:
                Ext.MessageBox.OK, animEl: 'mb9', fn: function(){}, icon:
                Ext.MessageBox.INFO }); setTimeout(function(){
                Ext.MessageBox.hide(); }, 2000);
            }
          });
        }
      }
    ]
  });
  var fieldset;
  
  var cmbFormats = new Ext.form.ComboBox({
    fieldLabel : TRANSLATIONS.IS_USER_NAME_DISPLAY_FORMAT,
    hiddenName : 'format',
    store : new Ext.data.Store( {
      proxy : new Ext.data.HttpProxy( {
        url : 'environmentSettingsAjax',
        method : 'POST'
      }),
      baseParams : {
        request : 'getList'
      },
      reader : new Ext.data.JsonReader( {
        root : 'rows',
        fields : [ {
          name : 'id'
        }, {
          name : 'name'
        } ]
      })
    }),
    
    valueField : 'id',
    displayField : 'name',
    triggerAction : 'all',
    emptyText : 'Select',
    selectOnFocus : true,
    editable : false,
    allowBlank : false,
    allowBlankText : TRANSLATIONS.ID_ENVIRONMENT_SETTINGS_MSG_1
  })

  fieldset = {
      xtype : 'fieldset',
      title : TRANSLATIONS.ID_PM_ENV_SETTINGS_USERFIELDSET_TITLE,
      collapsible : false,
      autoHeight : true,
      defaults : {
        width : 250
      },
      defaultType : 'textfield',
      items : [cmbFormats]
  }
  
  
  fsf.add(fieldset);
  cmbFormats.setValue(default_format);

  fsf.render(document.body);
});



