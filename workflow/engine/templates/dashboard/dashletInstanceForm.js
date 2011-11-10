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
                    case "OK": //Ext.MessageBox.alert("Message", "Dashboard Instance registered correctly");
                               window.location.href = "dashletsList";
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
      idIndex: 0, //definimos la posicion del ID de cada registro
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
            cboDasInsOwnerUID.setValue(store.getAt(0).get(cboDasInsOwnerUID.valueField));
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

      width: 200,
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

      width: 200,
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

      width: 200,
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

      width: 200,
      fieldLabel: "Assign To",

      listeners: {
        select: function (combo, record, index) {
          storeDasInsOwnerUID.baseParams = {"option": "OWNERTYPE",
                                            "type": combo.getValue()
                                           };
          cboDasInsOwnerUID.store.removeAll();
          cboDasInsOwnerUID.store.load();
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

      width: 200,
      fieldLabel: "Name"
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

      width: 200,
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

      width: 200,
      fieldLabel: "Task"
    });

    var formFields = [hiddenDasInsUID,
              cboDasUID,
              cboDasInsType,
              cboDasInsContextTime,
              //txtDasInsStartDate,
              //txtDasInsEndDate,
              cboDasInsOwnerType,
              cboDasInsOwnerUID
              //cboProcess,
              //cboTask
              ];
    formFields = formFields.concat(additionaFields);

    //------------------------------------------------------------------------------------------------------------------
    var dashletInstanceFrm = new Ext.form.FormPanel({
      id:  "dashletInstanceFrm",

      style: "margin: 0 auto 0 auto;",
      //labelAlign: "top",
      labelWidth: 115, //The width of labels in pixels
      bodyStyle: "padding:0.5em;",
      border: true,
      //cls: "class1",
      width: 400,
      //height: 400,

      title: "New Dashboard Instance",

      items: formFields,

      buttonAlign: "right",
      buttons: [new Ext.Action({
                  id:   "btnSubmit",

                  text: "Save",
                  //scope: this,
                  handler: function () {
                    dashletInstanceSaveProcessAjax();
                  }
                }),

                //{xtype: "button",
                // id:    "btnReset",
                // text:  "Reset",
                // handler: function () {
                //   //Ext.getCmp("dashletInstanceFrm").getForm().reset();
                //   dashletInstanceFrm.getForm().reset();
                //   //cboProcess.store.load();
                // }
                //},

                {xtype: "button",
                 id:    "btnCancel",
                 text:  "Cancel",
                 handler: function () {
                   window.location.href = "dashletsList";
                 }
                }
               ]
    });

    //------------------------------------------------------------------------------------------------------------------
    dashletInstanceFrm.getForm().setValues(dashletInstance);

    //------------------------------------------------------------------------------------------------------------------
    var pnlMain = new Ext.Panel({
      id: "pnlMain",

      region: "center",
      margins: {top:3, right:3, bottom:3, left:0},
      //bodyStyle: "padding:0.5em;", //propiedades ... //no aceptaba para la derecha
      bodyStyle: "padding: 25px 25px 25px 25px;", //propiedades ...
      border: false,

      items: [dashletInstanceFrm]
    });

    //------------------------------------------------------------------------------------------------------------------
    //LOAD ALL PANELS
    var viewport = new Ext.Viewport({
      layout:"fit",
      items:[pnlMain]
    });
  }
}

Ext.onReady(dashletInstance.form.init, dashletInstance.form);