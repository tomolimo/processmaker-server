// Declare global variables
var storeDasInsOwnerType;
var storeDasInsOwnerUID;
var hiddenDasInsUID;
var txtDasInsTitle;
var cboDasUID;
var cboDasInsOwnerType;
var cboDasInsOwnerUID;
var formFields;
var additionalFields;
var dashletInstanceFrm;

// On ready
Ext.onReady(function() {
  // Stores
  storeDasInsOwnerType = new Ext.data.ArrayStore({
    idIndex: 0,
    fields: ['id', 'value'],
    data:   [['USER', 'User'], ['DEPARTMENT', 'Department'], ['GROUP', 'Group'], ['EVERYBODY', 'Everybody']]
  });

  storeDasInsOwnerUID = new Ext.data.Store({
    proxy: new Ext.data.HttpProxy({
      url:    'getOwnersByType',
      method: 'POST'
    }),
    reader: new Ext.data.JsonReader({
      totalProperty: 'total',
      root:          'owners',
      fields:        [{name: 'OWNER_UID',  type: 'string'}, {name: 'OWNER_NAME', type: 'string'}]
    }),
    autoLoad: true,
    listeners: {
      beforeload: function (store) {
        storeDasInsOwnerUID.baseParams = {'option': 'OWNERTYPE', 'type': cboDasInsOwnerType.getValue()};
      },
      load: function (store, record, option) {
        if (dashletInstance.DAS_INS_UID) {
          cboDasInsOwnerUID.setValue(dashletInstance.DAS_INS_OWNER_UID);
        }
        else {
          if (store.getAt(0)) {
            cboDasInsOwnerUID.setValue(store.getAt(0).get(cboDasInsOwnerUID.valueField));
          }
        }
        if (cboDasInsOwnerType.getValue() == 'EVERYBODY') {
          cboDasInsOwnerUID.hide();
          cboDasInsOwnerUID.container.up('div.x-form-item').setStyle('display', 'none');
        }
        else {
          cboDasInsOwnerUID.show();
          cboDasInsOwnerUID.container.up('div.x-form-item').setStyle('display', 'block');
        }
      }
    }
  });

  // Fields
  hiddenDasInsUID = new Ext.form.Hidden({
    id:   'hiddenDasInsUID',
    name: 'DAS_INS_UID'
  });

  txtDasInsTitle = new Ext.form.TextField({
    id:         'txtDasInsTitle',
    name:       'DAS_INS_TITLE',
    fieldLabel: 'Title',
    allowBlank: false,
    width:      320,
    listeners:  {
      blur: function() {
        this.setValue(this.getValue().trim());
      }
    }
  });

  cboDasUID = new Ext.form.ComboBox({
    id:             'cboDasUID',
    name:           'DAS_UID',
    fieldLabel:     'Dashlet',
    editable:       false,
    width:          320,
    store:          storeDasUID,
    triggerAction: 'all',
    mode:          'local',
    valueField:    'DAS_UID',
    displayField:  'DAS_TITLE',
    listeners:     {
      select: function (combo, record, index) {
        Ext.Ajax.request({
             url:      'getAdditionalFields',
             method:   'POST',
             params:   {DAS_UID: this.getValue()},
             success:  function (result, request) {
                         var response = Ext.util.JSON.decode(result.responseText)
                         additionalFields = response.additionalFields;
                         dashletInstanceFrm.remove('additional');
                         if (additionalFields.length > 0) {
                           for (var i = 0; i < additionalFields.length; i++) {
                             for (var listener in additionalFields[i].listeners) {
                               try {
                                 eval('additionalFields[i].listeners.' + listener + ' = ' + additionalFields[i].listeners[listener] + ';');
                               } catch (e) {}
                             }
                           }
                           dashletInstanceFrm.add(new Ext.form.FieldSet({
                             id:    'additional',
                             title: 'Other',
                             items: additionalFields
                           }));
                         }
                         dashletInstanceFrm.doLayout(false, true);
                         // Execute after render scripts
                         if (additionalFields.length > 0) {
                           for (var i = 0; i < additionalFields.length; i++) {
                             if (typeof(additionalFields[i]._afterRender) != 'undefined') {
                               try {
                                 eval(additionalFields[i]._afterRender);
                               } catch (e) {}
                             }
                           }
                         }
                      },
             failure: function (result, request) {
                        myMask.hide();
                        Ext.MessageBox.alert('Alert', 'Ajax communication failed');
                      }
           });
      }
    }
  });

  cboDasInsOwnerType = new Ext.form.ComboBox({
    id:            'cboDasInsOwnerType',
    name:          'DAS_INS_OWNER_TYPE',
    fieldLabel:    'Assign To',
    editable:      false,
    width:         320,
    store:         storeDasInsOwnerType,
    triggerAction: 'all',
    mode:          'local',
    value:         'USER',
    valueField:    'id',
    displayField:  'value',
    listeners:     {
      select: function (combo, record, index) {
        storeDasInsOwnerUID.baseParams = {'option': 'OWNERTYPE', 'type': combo.getValue()};
        dashletInstance.DAS_INS_OWNER_UID = '';
        cboDasInsOwnerUID.store.removeAll();
        cboDasInsOwnerUID.clearValue();
        cboDasInsOwnerUID.store.reload();
      }
    }
  });

  cboDasInsOwnerUID = new Ext.form.ComboBox({
    id:            'cboDasInsOwnerUID',
    name:          'DAS_INS_OWNER_UID',
    fieldLabel:    'Name',
    editable:      false,
    width:         320,
    store:         storeDasInsOwnerUID,
    triggerAction: 'all',
    mode:          'local',
    allowBlank:    true,
    valueField:    'OWNER_UID',
    displayField:  'OWNER_NAME'
  });

  formFields = [
    new Ext.form.FieldSet({
      id:    'general',
      title: 'General',
      items: [hiddenDasInsUID, txtDasInsTitle, cboDasUID, cboDasInsOwnerType, cboDasInsOwnerUID]
    })
  ];

  if (additionalFields.length > 0) {
    if (additionalFields.length > 0) {
      for (var i = 0; i < additionalFields.length; i++) {
        for (var listener in additionalFields[i].listeners) {
          try {
            eval('additionalFields[i].listeners.' + listener + ' = ' + additionalFields[i].listeners[listener] + ';');
          } catch (e) {alert('3->'+e);}
        }
      }
    }
    formFields.push(new Ext.form.FieldSet({
      id:    'additional',
      title: 'Other',
      items: additionalFields
    }));
  }

  // Form
  dashletInstanceFrm = new Ext.form.FormPanel({
    id:  'dashletInstanceFrm',
    labelWidth: 100,
    border: true,
    width: 465,
    frame: true,
    title: 'Dashlet Instance Configuration',
    items: formFields,
    buttonAlign: 'right',
    buttons: [
      new Ext.Action({
       id:      'btnSubmit',
       text:    'Save',
       handler: function () {
         if (dashletInstanceFrm.getForm().isValid()) {
           var myMask = new Ext.LoadMask(Ext.getBody(), {msg: 'Saving. Please wait...'});
           myMask.show();
           Ext.Ajax.request({
             url:      'saveDashletInstance',
             method:   'POST',
             params:   dashletInstanceFrm.getForm().getFieldValues(),
             success:  function (result, request) {
                         myMask.hide();
                         var dataResponse = Ext.util.JSON.decode(result.responseText)
                         switch (dataResponse.status) {
                           case 'OK':
                             window.location.href = 'dashletsList';
                           break;
                           default:
                             Ext.MessageBox.alert('Alert', 'Dashboard Instance registered failed');
                           break;
                        }
                      },
             failure: function (result, request) {
                        myMask.hide();
                        Ext.MessageBox.alert('Alert', 'Ajax communication failed');
                      }
           });
         }
         else {
           Ext.MessageBox.alert('Invalid data', 'Please check the fields mark in red.');
         }
       }
      }),
      {
        xtype:   'button',
        id:      'btnCancel',
        text:    'Cancel',
        handler: function () {
          window.location.href = 'dashletsList';
        }
     }
    ]
  });

  // Set initial values
  dashletInstanceFrm.getForm().setValues(dashletInstance);

  // Render
  dashletInstanceFrm.render(document.body);

  // Execute after render scripts
  if (additionalFields.length > 0) {
    for (var i = 0; i < additionalFields.length; i++) {
      if (typeof(additionalFields[i]._afterRender) != 'undefined') {
        try {
          eval(additionalFields[i]._afterRender);
        } catch (e) {}
      }
    }
  }
});
