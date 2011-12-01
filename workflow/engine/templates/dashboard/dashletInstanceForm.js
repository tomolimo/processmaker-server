Ext.namespace("dashletInstance");

dashletInstance.form = {
  init: function () {
    dashletInstanceSaveProcessAjax = function () {
      var myMask = new Ext.LoadMask(Ext.getBody(), {msg: "Saving. Please wait..."});
      myMask.show();

      Ext.Ajax.request({
        url: "saveDashletInstance",
        method: "POST",
        params: dashletInstanceFrm.getForm().getFieldValues(),

        success:function (result, request) {
                  myMask.hide();

                  var dataResponse = Ext.util.JSON.decode(result.responseText)

                  switch (dataResponse.status) {
                    case "OK": window.location.href = "dashletsList";
                               break;
                    default: Ext.MessageBox.alert("Alert", "Dashboard Instance registered failed");
                             break;
                  }
                },
        failure:function (result, request) {
                  myMask.hide();
                  Ext.MessageBox.alert("Alert", "Ajax communication failed");
                }
      });
    }

    //------------------------------------------------------------------------------------------------------------------
    var storeDasUID = new Ext.data.Store({
      proxy: new Ext.data.HttpProxy({
        url: "getDashlets",
        method: "POST"
      }),

      baseParams: {"option": "DASHLST"},

      reader: new Ext.data.JsonReader({
        totalProperty: "total",
        root:          "dashlets",
        fields:[{name: "DAS_UID",   type: "string"},
                {name: "DAS_TITLE", type: "string"}
               ]
      }),

      autoLoad: true, //First call

      listeners: {
        load: function (store, record, option) {
          cboDasUID.setValue(store.getAt(0).get(cboDasUID.valueField));
        }
      }
    });

    var storeDasInsType = new Ext.data.ArrayStore({
      idIndex: 0,
      fields: ["id", "value"],
      data:   [["OPEN_CASES", "Open Cases"]
              ]
    });

    var storeDasInsContextTime = new Ext.data.ArrayStore({
      idIndex: 0,
      fields: ["id", "value"],
      data:   [//["RANGE", "Date Ranges"],
               ["TODAY",            "Today"],
               ["YESTERDAY",        "Yesterday"],
               ["THIS_WEEK",        "This Week"],
               ["PREVIOUS_WEEK",    "Previous Week"],
               ["THIS_MONTH",       "This Month"],
               ["PREVIOUS_MONTH",   "Previous Month"],
               //["THIS_QUARTER",     "This Quarter"],
               //["PREVIOUS_QUARTER", "Previous Quarter"],
               ["THIS_YEAR",        "This Year"],
               ["PREVIOUS_YEAR",    "Previous Year"]
              ]
    });

    var storeDasInsOwnerType = new Ext.data.ArrayStore({
      idIndex: 0,
      fields: ["id", "value"],
      data:   [["USER",       "User"],
               ["DEPARTMENT", "Department"],
               ["GROUP", "Group"]
              ]
    });

    var storeDasInsOwnerUID = new Ext.data.Store({
      proxy: new Ext.data.HttpProxy({
        url: "getOwnersByType",
        method: "POST"
      }),

      reader: new Ext.data.JsonReader({
        totalProperty: "total",
        root:          "owners",
        fields:[{name: "OWNER_UID",  type: "string"},
                {name: "OWNER_NAME", type: "string"}
              ]
      }),

      autoLoad: true, //First call

      listeners: {
        beforeload: function (store) {
          storeDasInsOwnerUID.baseParams = {"option": "OWNERTYPE",
                                            "type": cboDasInsOwnerType.getValue()
                                           };
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
        }
      }
    });

    var storeProcess = new Ext.data.ArrayStore({
      idIndex: 0,
      fields: ["id", "value"],
      data:   [["ALL", "All"]
               //,
               //["SEL", "Selection"]
              ]
    });

    var storeTask = new Ext.data.ArrayStore({
      idIndex: 0,
      fields: ["id", "value"],
      data:   [["ALL", "All"]
               //,
               //["SEL", "Selection"]
              ]
    });

    //------------------------------------------------------------------------------------------------------------------
    var hiddenDasInsUID = new Ext.form.Hidden({
      id: "hiddenDasInsUID",
      name: "DAS_INS_UID"
    });

    var cboDasUID = new Ext.form.ComboBox({
      id: "cboDasUID",
      name: "DAS_UID",

      valueField:   "DAS_UID",
      displayField: "DAS_TITLE",
      store:        storeDasUID,

      triggerAction: "all",
      mode:     "local",
      editable: false,

      width: 325,
      fieldLabel: "Dashboard"
    });

    var cboDasInsType = new Ext.form.ComboBox({
      id: "cboDasInsType",
      name: "DAS_INS_TYPE",

      valueField:   "id",
      displayField: "value",
      value:        "OPEN_CASES",
      store:        storeDasInsType,

      triggerAction: "all",
      mode:     "local",
      editable: false,

      width: 325,
      fieldLabel: "Type"
    });

    var cboDasInsContextTime = new Ext.form.ComboBox({
      id: "cboDasInsContextTime",
      name: "DAS_INS_CONTEXT_TIME",

      valueField:   "id",
      displayField: "value",
      value:        "TODAY",
      store:        storeDasInsContextTime,

      triggerAction: "all",
      mode:     "local",
      editable: false,

      width: 325,
      fieldLabel: "Period"
    });

    var txtDasInsStartDate = new Ext.form.DateField({
      id: "txtDasInsStartDate",
      name: "DAS_INS_START_DATE",

      value: new Date(2011, 0, 1), //january=0, february=1, etc
      width: 100,
      format: "Y/m/d",
      editable: false,
      fieldLabel: "Start Date"
    });

    var txtDasInsEndDate = new Ext.form.DateField({
      id: "txtDasInsEndDate",
      name: "DAS_INS_END_DATE",

      value: new Date(2011, 0, 1),
      width: 100,
      format: "Y/m/d",
      editable: false,
      fieldLabel: "Finish Date"
    });

    var cboDasInsOwnerType = new Ext.form.ComboBox({
      id: "cboDasInsOwnerType",
      name: "DAS_INS_OWNER_TYPE",

      valueField:   "id",
      displayField: "value",
      value:        "DEPARTMENT",
      store:        storeDasInsOwnerType,

      triggerAction: "all",
      mode:     "local",
      editable: false,

      width: 325,
      fieldLabel: "Assign To",

      listeners: {
        select: function (combo, record, index) {
          storeDasInsOwnerUID.baseParams = {"option": "OWNERTYPE",
                                            "type": combo.getValue()
                                           };
          dashletInstance.DAS_INS_OWNER_UID = '';
          cboDasInsOwnerUID.store.removeAll();
          cboDasInsOwnerUID.clearValue();
          cboDasInsOwnerUID.store.reload();
        }
      }
    });

    var cboDasInsOwnerUID = new Ext.form.ComboBox({
      id: "cboDasInsOwnerUID",
      name: "DAS_INS_OWNER_UID",

      valueField:   "OWNER_UID",
      displayField: "OWNER_NAME",
      store:        storeDasInsOwnerUID,

      triggerAction: "all",
      mode:     "local",
      editable: false,

      width: 325,
      fieldLabel: "Name",
      allowBlank: false
    });

    var cboProcess = new Ext.form.ComboBox({
      id: "cboProcess",
      name: "DAS_INS_PROCESSES",

      valueField:   "id",
      displayField: "value",
      value:        "ALL",
      store:        storeProcess,

      triggerAction: "all",
      mode:     "local",
      editable: false,

      width: 325,
      fieldLabel: "Process"
    });

    var cboTask = new Ext.form.ComboBox({
      id: "cboTask",
      name: "DAS_INS_TASKS",

      valueField:   "id",
      displayField: "value",
      value:        "ALL",
      store:        storeTask,

      triggerAction: "all",
      mode:     "local",
      editable: false,

      width: 325,
      fieldLabel: "Task"
    });

    var sliderMaxValue = 100;
    var sliderValue = [];
    
    var additionalFieldName = [];
    var additionalFieldN    = additionaFields.length;
    
    for (var i = 0; i <= additionalFieldN - 1; i++) {
      additionalFieldName[i] = additionaFields[i].name;
    }
    
    for (var i = 0; i <= (additionalFieldN / 2) - 1; i++) {
      sliderValue[i] = 0;
    }
    
    var sliderRangeColor = new Ext.slider.MultiSlider({
      //renderTo: "id",
        
      fieldLabel: "Range Of Colors",
        
      width: 325,
      minValue: 0,
      maxValue: sliderMaxValue,
      values: sliderValue,
      plugins: new Ext.slider.Tip(),
        
      listeners:{
        //changecomplete(Ext.slider.MultiSlider slider, Number newValue, Ext.slider.Thumb thumb)
        changecomplete: function (slider, newValue, thumb) {
          var sliderAux = slider.getValues();
          var index = 0;
          var sw = 0;
          
          for (var i = 0; i <= sliderAux.length - 1 && sw == 0; i++) {
            if (sliderAux[i] == newValue) {
              index = i;
              sw = 1;
            }
          }
          
          dashletInstanceFrm.getForm().findField(additionalFieldName[(index * 2) + 1]).setValue(newValue);
          
          if (index < (additionalFieldN / 2) - 1) {
            dashletInstanceFrm.getForm().findField(additionalFieldName[(index * 2) + 1 + 1]).setValue(newValue);
          }
        }
      }
    })

    var formFields = [
      new Ext.form.FieldSet({
        title: "Setting",
        items:[hiddenDasInsUID,
               cboDasUID,
               cboDasInsType,
               cboDasInsContextTime,
               //txtDasInsStartDate,
               //txtDasInsEndDate,
               cboDasInsOwnerType,
               cboDasInsOwnerUID
               //,
               //cboProcess,
               //cboTask,
              ]
      })
    ];
    
    formFields = formFields.concat([
      new Ext.form.FieldSet({
        title: "Configuration",
        items:[sliderRangeColor,
               additionaFields
              ]
      })
    ]);

    //------------------------------------------------------------------------------------------------------------------
    var dashletInstanceFrm = new Ext.form.FormPanel({
      id:  "dashletInstanceFrm",
      labelWidth: 100,
      border: true,
      width: 465,
      frame: true,
      title: "Dashlet Instance Configuration",
      items: formFields,
      buttonAlign: "right",
      buttons: [new Ext.Action({
                  id:   "btnSubmit",
                  text: "Save",
                  handler: function () {
                    if (dashletInstanceFrm.getForm().isValid()) {
                      dashletInstanceSaveProcessAjax();
                    }
                    else {
                      Ext.MessageBox.alert('Invalid data', 'Please check the fields mark in red.');
                    }
                  }
                }),
                {xtype: "button",
                 id:    "btnCancel",
                 text:  "Cancel",
                 handler: function () {
                   window.location.href = "dashletsList";
                 }
                }
               ]
    });

    dashletInstanceFrm.getForm().setValues(dashletInstance);
    
    ///////
    var frm = dashletInstanceFrm.getForm();
    var n = additionalFieldN / 2;

    if (dashletInstance.DAS_INS_UID) {
      for (var i = 0; i <= n - 1; i++) {
        //setValue(Number index, Number value, Boolean animate)
        sliderRangeColor.setValue(i, frm.findField(additionalFieldName[(i * 2) + 1]).getValue(), true);
      }
    }
    else {
      var range = parseInt(sliderMaxValue / n);
      var r = range;

      for (var i = 0; i <= n - 1; i++) {
        sliderRangeColor.setValue(i, r, true);
        
        frm.findField(additionalFieldName[(i * 2) + 1 - 1]).setValue(r - range);
        frm.findField(additionalFieldName[(i * 2) + 1]).setValue(r);
        
        r = r + range;
      }
      
      sliderRangeColor.setValue(n - 1, sliderMaxValue, true);
      
      frm.findField(additionalFieldName[((n - 1) * 2) + 1]).setValue(sliderMaxValue);
    }
    ///////
    
    dashletInstanceFrm.render(document.body);
  }
}

Ext.onReady(dashletInstance.form.init, dashletInstance.form);