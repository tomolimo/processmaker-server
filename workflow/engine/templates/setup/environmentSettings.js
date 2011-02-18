var formSettings;
var fsSamples;
var fsNames;
var fsDates;
var fsCases;
var _firstName, _lastName, _userName, _dateSample;

Ext.onReady(function() {
  Ext.QuickTips.init();
  
  _firstName = 'John';
  _lastName = 'Deere';
  _userName = 'johndeere';
  _dateSample = '2011-02-17 19:15:38';
  
  fsSample = new Ext.form.FieldSet({
	  title: _('ID_SAMPLES'),
	  labelWidth: 250,
	  autoHeight: true,
	  items: [
	          {xtype: 'label', fieldLabel: _('IS_USER_NAME_DISPLAY_FORMAT'), id: 'lblFullName', width: 400},
	          {xtype: 'label', fieldLabel: _('ID_GLOBAL_DATE_FORMAT'), id: 'lblDateFormat', width: 400},
	          {xtype: 'label', fieldLabel: _('ID_CASE_LIST') +': '+_('ID_CASES_DATE_MASK'), id: 'lblCasesDateFormat', width: 400},
	          {xtype: 'label', fieldLabel: _('ID_CASE_LIST') +': '+_('ID_CASES_ROW_NUMBER'), id: 'lblCasesRowsList', width: 400}
	          ]
  });
  
  storeUsernameFormat = new Ext.data.GroupingStore({
	proxy : new Ext.data.HttpProxy({
  	  url: 'environmentSettingsAjax?request=getUserMaskList'
  	}),
  	reader : new Ext.data.JsonReader({
  	  root: 'rows',
  	  fields : [
  		{name : 'id'},
  		{name : 'name'}
  	  ]
  	}),
    listeners:{
      load: function(){
    	default_format = FORMATS.FullNameFormat;
        i = cmbUsernameFormats.store.findExact('id', default_format, 0);
        cmbUsernameFormats.setValue(cmbUsernameFormats.store.getAt(i).data.id);
        cmbUsernameFormats.setRawValue(cmbUsernameFormats.store.getAt(i).data.name);
      }
    }
  });
  
  cmbUsernameFormats = new Ext.form.ComboBox({
    fieldLabel : _('IS_USER_NAME_DISPLAY_FORMAT'),
    hiddenName : 'userFormat',
    store : storeUsernameFormat,
    valueField : 'id',
    displayField : 'name',
    triggerAction : 'all',
    emptyText : _('ID_SELECT'),
    //selectOnFocus : true,
    editable : false,
    allowBlank : false,
    allowBlankText : _('ID_ENVIRONMENT_SETTINGS_MSG_1'),
    mode:'local',
    width: 400,
    listeners:{
      afterrender:function(){
        cmbUsernameFormats.store.load();
      },
      select: function(){ChangeSettings('1');}
    }
  });
  
  storeDateFormat = new Ext.data.Store( {
    proxy : new Ext.data.HttpProxy( {
          url : 'environmentSettingsAjax?request=getDateFormats',
          method : 'POST'
    }),
    reader: new Ext.data.JsonReader( {
      root: 'rows',
      fields: [ 
        {name : 'id'},
        {name : 'name'}
      ]
    }),
    listeners:{
      load: function(){
    	default_date_format = FORMATS.DateFormat,
        i = cmbDateFormats.store.findExact('id', default_date_format, 0);
        cmbDateFormats.setValue(cmbDateFormats.store.getAt(i).data.id);
        cmbDateFormats.setRawValue(cmbDateFormats.store.getAt(i).data.name);
      }
    }
  });
  
  cmbDateFormats = new Ext.form.ComboBox({
    fieldLabel : _('ID_GLOBAL_DATE_FORMAT'),
    hiddenName : 'dateFormat',
    store : storeDateFormat,
    mode: 'remote',
    valueField : 'id',
    displayField : 'name',
    triggerAction : 'all',
    emptyText : _('ID_SELECT'),
    selectOnFocus : true,
    editable : false,
    allowBlank : false,
    width: 300,
    allowBlankText : _('ID_ENVIRONMENT_SETTINGS_MSG_1'),
    mode:'local',
    listeners:{
      afterrender:function(){
        cmbDateFormats.store.load();
      },
      select: function(){ChangeSettings('2');}  
    }
  });
  
  storeCaseUserNameFormat = new Ext.data.Store( {
    proxy : new Ext.data.HttpProxy({
	  url : 'environmentSettingsAjax?request=getCasesListDateFormat',
	  method : 'POST'
    }),
	reader: new Ext.data.JsonReader({
	  root: 'rows',
		fields: [ 
		  {name: 'id'},
		  {name : 'name'} 
		]
	}),
	listeners:{
	  load: function(){
        default_caseslist_date_format = FORMATS.CasesListDateFormat;
	    i = cmbCasesDateFormats.store.findExact('id', default_caseslist_date_format, 0);
	    cmbCasesDateFormats.setValue(cmbCasesDateFormats.store.getAt(i).data.id);
	    cmbCasesDateFormats.setRawValue(cmbCasesDateFormats.store.getAt(i).data.name);
	  }
	}
  });
  
  cmbCasesDateFormats = new Ext.form.ComboBox({
    fieldLabel : _('ID_CASES_DATE_MASK'),
    hiddenName : 'casesListDateFormat',
    store : storeCaseUserNameFormat,
    valueField : 'id',
    displayField : 'name',
    triggerAction : 'all',
    emptyText : _('ID_SELECT'),
    //selectOnFocus : true,
    editable : false,
    allowBlank : false,
    width: 300,
    allowBlankText : _('ID_ENVIRONMENT_SETTINGS_MSG_1'),
    mode:'local',
    listeners:{
      afterrender:function(){
        cmbCasesDateFormats.store.load();
      },
      select: function(){ChangeSettings('3');}
    }
  });
  
  storeCaseListNumber = new Ext.data.Store({
	proxy : new Ext.data.HttpProxy( {
	  url : 'environmentSettingsAjax?request=getCasesListRowNumber',
	  method : 'POST'
	}),
	reader: new Ext.data.JsonReader( {
	  root: 'rows',
	  fields :[
	    {name : 'id'},
	    {name : 'name'}
	  ]
	}),
	listeners:{
	  load: function(){
		default_caseslist_row_number = FORMATS.CasesListRowNumber;
	    i = cmbCasesRowsPerPage.store.findExact('id', default_caseslist_row_number, 0);
	    if( i != -1 ){
	       cmbCasesRowsPerPage.setValue(cmbCasesRowsPerPage.store.getAt(i).data.id);
	       cmbCasesRowsPerPage.setRawValue(cmbCasesRowsPerPage.store.getAt(i).data.name);
	    }
	  }
	}
  });

  cmbCasesRowsPerPage = new Ext.form.ComboBox({
    fieldLabel : _('ID_CASES_ROW_NUMBER'),
    hiddenName : 'casesListRowNumber',
    store : storeCaseListNumber,
    valueField : 'id',
    displayField : 'name',
    triggerAction : 'all',
    emptyText : _('ID_SELECT'),
    //selectOnFocus : true,
    editable : false,
    allowBlank : false,
    width: 100,
    allowBlankText : _('ID_ENVIRONMENT_SETTINGS_MSG_1'),
    mode:'local',
    listeners:{
      afterrender:function(){
        cmbCasesRowsPerPage.store.load();
      },
      select: function(){ChangeSettings('4');}
    }
  });  
  
  fsNames = new Ext.form.FieldSet({
	  title: _('ID_PM_ENV_SETTINGS_USERFIELDSET_TITLE'),
	  labelAlign: 'right',
	  items: [cmbUsernameFormats]
  });
  
  fsDates = new Ext.form.FieldSet({
	  title: _('ID_PM_ENV_SETTINGS_REGIONFIELDSET_TITLE'),
	  labelAlign: 'right',
	  items: [cmbDateFormats]
  });
  
  fsCases = new Ext.form.FieldSet({
	  title: _('ID_PM_ENV_SETTINGS_CASESLIST_TITLE'),
	  labelAlign: 'right',
	  items: [cmbCasesDateFormats,cmbCasesRowsPerPage]
  });
  
  saveButton = new Ext.Action({
    text : _('ID_SAVE_SETTINGS'),
    disabled : true,
    handler : function() {
      formSettings.getForm().submit({
        url : 'environmentSettingsAjax?request=save&r=' + Math.random(),
        waitMsg : _('ID_SAVING_ENVIRONMENT_SETTINGS')+'...',
        timeout : 36000,
        success : function(res, req) {
    	  PMExt.notify(_('ID_PM_ENV_SETTINGS_TITLE'), req.result.msg);
    	  saveButton.disable();
        }
      });
    }
  });
  
  formSettings = new Ext.FormPanel( {
	region: 'center',
    labelWidth : 170, // label settings here cascade unless overridden
    //labelAlign: 'right',
    frame : true,
    title : _('ID_PM_ENV_SETTINGS_TITLE'),
    //bodyStyle : 'padding:5px 5px 0',
    autoWidth : true,
    autoScroll: true,

    items : [fsSample,fsNames,fsDates, fsCases],
    buttons : [saveButton]
  });

  LoadSamples();
  
  viewport = new Ext.Viewport({
  	layout: 'fit',
  	autoScroll: false,
  	items: [
  	   formSettings
  	]
  });
});

//Load Samples Label
LoadSamples = function(){
  Ext.getCmp('lblFullName').setText(_FNF(_userName,_firstName,_lastName));
  Ext.getCmp('lblDateFormat').setText(_DF(_dateSample));
  Ext.getCmp('lblCasesDateFormat').setText(_DF(_dateSample,FORMATS.CasesListDateFormat));
  Ext.getCmp('lblCasesRowsList').setText(FORMATS.CasesListRowNumber);
};

//Change Some Setting
ChangeSettings = function(iType){
  saveButton.enable();
  switch (iType){
    case '1': 
    	_format = cmbUsernameFormats.getValue();
    	Ext.getCmp('lblFullName').setText(_FNF(_userName,_firstName,_lastName, _format));
    	break;
    case '2':
    	_format = cmbDateFormats.getValue();
    	Ext.getCmp('lblDateFormat').setText(_DF(_dateSample,_format));
    	break;
    case '3':
    	_format = cmbCasesDateFormats.getValue();
    	Ext.getCmp('lblCasesDateFormat').setText(_DF(_dateSample,_format));
    	break;
    case '4':
    	_format = cmbCasesRowsPerPage.getValue();
    	Ext.getCmp('lblCasesRowsList').setText(_format);
    	break;
  }
};
