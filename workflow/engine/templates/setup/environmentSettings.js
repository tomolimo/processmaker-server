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
  var fieldsetCasesList;

  var cmbUsernameFormats = new Ext.form.ComboBox({
    fieldLabel : TRANSLATIONS.IS_USER_NAME_DISPLAY_FORMAT,
    hiddenName : 'userFormat',
    store : new Ext.data.Store( {
      proxy : new Ext.data.HttpProxy( {
        url : 'environmentSettingsAjax',
        method : 'POST'
      }),
      baseParams : {
        request : 'getUserMaskList'
      },
      reader : new Ext.data.JsonReader( {
        root : 'rows',
        fields : [ {
          name : 'id'
        }, {
          name : 'name'
        } ]
      }),
      listeners:{
        load: function(){
          i = cmbUsernameFormats.store.findExact('id', default_format, 0);
          cmbUsernameFormats.setValue(cmbUsernameFormats.store.getAt(i).data.id);
          cmbUsernameFormats.setRawValue(cmbUsernameFormats.store.getAt(i).data.name);
        }
      }
    }),

    valueField : 'id',
    displayField : 'name',
    triggerAction : 'all',
    emptyText : 'Select',
    selectOnFocus : true,
    editable : false,
    allowBlank : false,
    allowBlankText : TRANSLATIONS.ID_ENVIRONMENT_SETTINGS_MSG_1,
    mode:'local',
    listeners:{
      afterrender:function(){
        cmbUsernameFormats.store.load();
      }
    }
  });

  var cmbDateFormats = new Ext.form.ComboBox({
    fieldLabel : TRANSLATIONS.ID_GLOBAL_DATE_FORMAT,
    hiddenName : 'dateFormat',
    store : new Ext.data.Store( {
      proxy : new Ext.data.HttpProxy( {
        url : 'environmentSettingsAjax',
        method : 'POST'
      }),
      baseParams : {
        request : 'getDateFormats'
      },
      reader : new Ext.data.JsonReader( {
        root : 'rows',
        fields : [ {
          name : 'id'
        }, {
          name : 'name'
        } ]
      }),
      listeners:{
        load: function(){
          i = cmbDateFormats.store.findExact('id', default_date_format, 0);
          cmbDateFormats.setValue(cmbDateFormats.store.getAt(i).data.id);
          cmbDateFormats.setRawValue(cmbDateFormats.store.getAt(i).data.name);
        }
      }
    }),
    mode: 'remote',
    valueField : 'id',
    displayField : 'name',
    triggerAction : 'all',
    emptyText : 'Select',
    selectOnFocus : true,
    editable : false,
    allowBlank : false,
    allowBlankText : TRANSLATIONS.ID_ENVIRONMENT_SETTINGS_MSG_1,
    mode:'local',
    listeners:{
      afterrender:function(){
        cmbDateFormats.store.load();
      }  
    }
  });

  var cmbCasesDateFormats = new Ext.form.ComboBox({
    fieldLabel : TRANSLATIONS.ID_CASES_DATE_MASK,
    hiddenName : 'casesListDateFormat',

    store : new Ext.data.Store( {
      proxy : new Ext.data.HttpProxy( {
        url : 'environmentSettingsAjax',
        method : 'POST'
      }),
      baseParams : {
        request : 'getCasesListDateFormat'
      },
      reader : new Ext.data.JsonReader( {
        root : 'rows',
        fields : [ {
          name : 'id'
        }, {
          name : 'name'
        } ]
      }),
      listeners:{
        load: function(){
          i = cmbCasesDateFormats.store.findExact('id', default_caseslist_date_format, 0);
          cmbCasesDateFormats.setValue(cmbCasesDateFormats.store.getAt(i).data.id);
          cmbCasesDateFormats.setRawValue(cmbCasesDateFormats.store.getAt(i).data.name);
        }
      }
    }),

    valueField : 'id',
    displayField : 'name',
    triggerAction : 'all',
    emptyText : 'Select',
    selectOnFocus : true,
    editable : false,
    allowBlank : false,

    allowBlankText : TRANSLATIONS.ID_ENVIRONMENT_SETTINGS_MSG_1,
    mode:'local',
    listeners:{
      afterrender:function(){
        cmbCasesDateFormats.store.load();
      }
    }
  });

  var cmbCasesRowsPerPage = new Ext.form.ComboBox({
    fieldLabel : TRANSLATIONS.ID_CASES_ROW_NUMBER,
    hiddenName : 'casesListRowNumber',
    store : new Ext.data.Store( {
      proxy : new Ext.data.HttpProxy( {
        url : 'environmentSettingsAjax',
        method : 'POST'
      }),
      baseParams : {
        request : 'getCasesListRowNumber'
      },
      reader : new Ext.data.JsonReader( {
        root : 'rows',
        fields : [ {
          name : 'id'
        }, {
          name : 'name'
        } ]
      }),
      listeners:{
        load: function(){
          i = cmbCasesRowsPerPage.store.findExact('id', default_caseslist_row_number, 0);
          if( i != -1 ){
            cmbCasesRowsPerPage.setValue(cmbCasesRowsPerPage.store.getAt(i).data.id);
            cmbCasesRowsPerPage.setRawValue(cmbCasesRowsPerPage.store.getAt(i).data.name);
          }
        }
      }
    }),

    valueField : 'id',
    displayField : 'name',
    triggerAction : 'all',
    emptyText : 'Select',
    selectOnFocus : true,
    editable : true,
    allowBlank : false,
    allowBlankText : TRANSLATIONS.ID_ENVIRONMENT_SETTINGS_MSG_1,
    mode:'local',
    vtype: 'numeric',
    listeners:{
      afterrender:function(){
        cmbCasesRowsPerPage.store.load();
      }
    }
  });

  
  fieldset = {
      xtype : 'fieldset',
      title : TRANSLATIONS.ID_PM_ENV_SETTINGS_USERFIELDSET_TITLE,
      collapsible : false,
      autoHeight : true,
      defaults : {
        width : 250
      },
      defaultType : 'textfield',
      items : [cmbUsernameFormats]
  }
  fieldset2 = {
      xtype : 'fieldset',
      title : TRANSLATIONS.ID_PM_ENV_SETTINGS_REGIONFIELDSET_TITLE,
      collapsible : false,
      autoHeight : true,
      defaults : {
        width : 250
      },
      defaultType : 'textfield',
      items : [cmbDateFormats]
  }

  fieldsetCasesList = {
      xtype : 'fieldset',
      title : TRANSLATIONS.ID_PM_ENV_SETTINGS_CASESLIST_TITLE,
      collapsible : false,
      autoHeight : true,
      defaults : {
        width : 250
      },
      defaultType : 'textfield',
      items : [cmbCasesDateFormats,cmbCasesRowsPerPage]
  }
  
  fsf.add(fieldset);
  fsf.add(fieldset2);
  fsf.add(fieldsetCasesList);
  cmbUsernameFormats.setValue(default_format);
  cmbDateFormats.selectByValue(default_date_format);
  cmbCasesDateFormats.setValue(default_caseslist_date_format);
  cmbCasesRowsPerPage.setValue(default_caseslist_row_number);

  fsf.render(document.body);
});



Ext.apply(Ext.form.VTypes, {
	   // Password Check
	   passwordText: 'The passwords entered do not match.',
	   password: function(value, field) {
	      var valid = false;
	      if (field.matches) {
	         var otherField = Ext.getCmp(field.matches);
	         if (value == otherField.getValue()) {
	            otherField.clearInvalid();
	            valid = true;
	         }
	      }
	      return valid;
	   },
    // Phone Number check
	   phoneText: "Not a valid phone number.  Must be in the following format: 123-4567 or 123-456-7890.",
	   phoneMask: /[d-]/,
	   phoneRe: /^(d{3}[-]?){1,2}(d{4})$/,
	   phone: function(v) {
	      return this.phoneRe.test(v);
	   },
	   // Email address check
	   emailText: "Not a valid email address. Must be in the following format: yourname@company.domain",
	    emailRe: /^(\s*[a-zA-Z0-9\._%-]+@[a-zA-Z0-9\.-]+\.[a-zA-Z]{2,4})\s*$/,
	    email: function(v) {
	        return this.emailRe.test(v);
	    },
	   // Numeric check
	   numericText: "Only numbers are allowed.",
	   numericMask: /[0-9]/,
	   numericRe: /[0-9]/,
	   numeric: function(v) {
	      return this.numericRe.test(v);
	   },
	   // Decimal Number check
	   decNumText: "Only decimal numbers are allowed.",
	   decNumMask: /(d|.)/,
	   decNumRe: /d+.d+|d+/,
	   decNum: function(v) {
	      return this.decNumRe.test(v);
	   }
	});