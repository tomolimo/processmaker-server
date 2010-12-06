ProcessOptions=function(id){
Workflow.call(this,id);
};
ProcessOptions.prototype=new Workflow;
ProcessOptions.prototype.type="ProcessOptions";


/**
   * 'addDynaform' function that will allow adding new dynaforms and showing list of
   * dynaforms available
*/
ProcessOptions.prototype.addDynaform= function(_5625)
{
  var pro_uid = workflow.getUrlVars();
  //var taskId  = workflow.currentSelection.id;

  var dynaFields = Ext.data.Record.create([
            { name: 'DYN_UID', type: 'string'},
            { name: 'DYN_TYPE', type: 'string'},
            { name: 'DYN_TITLE', type: 'string'},
            { name: 'DYN_DISCRIPTION',type: 'string'}
            ]);

  var editor = new Ext.ux.grid.RowEditor({
            saveText: 'Update'
        });
  var btnAdd = new Ext.Button({
            id: 'btnAdd',
            text: 'New Dynaform',
            iconCls: 'application_add',
            handler: function () {
                
            }
  });

  var btnRemove = new Ext.Button({
            id: 'btnRemove',
            text: 'Delete Dynaform',
            iconCls: 'application_delete',
            handler: function (s) {
                editor.stopEditing();
                var s = dynaformGrid.getSelectionModel().getSelections();
                for(var i = 0, r; r = s[i]; i++){

                    //First Deleting dynaform from Database using Ajax
                    var dynUID      = r.data.DYN_UID;

                    //if STEP_UID is properly defined (i.e. set to valid value) then only delete the row
                    //else its a BLANK ROW for which Ajax should not be called.
                    if(r.data.DYN_UID != "")
                    {
                        Ext.Ajax.request({
                          url   : '../dynaforms/dynaforms_Delete.php',
                          method: 'POST',
                          params: {
                                DYN_UID        : dynUID
                          },
                          success: function(response) {
                            Ext.MessageBox.alert ('Status','Dynaform has been removed successfully.');
                          }
                        });
                    }

                    //Secondly deleting from Grid
                    taskDynaform.remove(r);
                }
            }
        });

        var tb = new Ext.Toolbar({
            items: [btnAdd, btnRemove]
        });

  var taskDynaform = new Ext.data.JsonStore({
            root         : 'data',
            totalProperty: 'totalCount',
            idProperty   : 'gridIndex',
            remoteSort   : true,
            fields       : dynaFields,
            proxy: new Ext.data.HttpProxy({
              url: 'proxyDynaform?pid='+pro_uid
            })
          });
 taskDynaform.load();

 //Creating store for getting list of additional PM tables
 var additionalTablesFields = Ext.data.Record.create([
            { name: 'ADD_TAB_UID', type: 'string'},
            { name: 'ADD_TAB_NAME', type: 'string'},
            { name: 'ADD_TAB_DESCRIPTION',type: 'string'}
            ]);

 var additionalTables = new Ext.data.JsonStore({
            root         : 'data',
            totalProperty: 'totalCount',
            idProperty   : 'gridIndex',
            remoteSort   : true,
            fields       : additionalTablesFields,
            proxy: new Ext.data.HttpProxy({
              url: 'proxyDynaform'
            })
          });
 additionalTables.load();

 //Creating store for getting list of Fields of additional PM tables
  var TablesFields = Ext.data.Record.create([
            { name: 'FLD_UID',type: 'string'},
            { name: 'FLD_NAME',type: 'string'},
            { name: 'FLD_DESCRIPTION',type: 'string'},
            { name: 'FLD_TYPE',type: 'string'}
            ]);

 var tablesFieldsStore = new Ext.data.JsonStore({
            root         : 'data',
            totalProperty: 'totalCount',
            idProperty   : 'gridIndex',
            remoteSort   : true,
            fields       : TablesFields,
            proxy: new Ext.data.HttpProxy({
              url: 'proxyDynaform'
            })
          });
 //tablesFieldsStore.load();

 var dynaformColumns = new Ext.grid.ColumnModel({
            columns: [
                new Ext.grid.RowNumberer(),
                    {
                        id: 'DYN_TITLE',
                        header: 'Title',
                        dataIndex: 'DYN_TITLE',
                        width: 280,
                        editable: false,
                        editor: new Ext.form.TextField({
                        //allowBlank: false
                        })
                    },
                    {
                        sortable: false,
                        renderer: function(val, meta, record)
                           {
                                return String.format("<a href='../dynaforms/dynaforms_Editor?PRO_UID={0}&DYN_UID={1}'>Edit</a>",pro_uid,record.data.DYN_UID);
                           }
                    }
                ]
        });

 var addTableColumns = new Ext.grid.ColumnModel({
            columns: [
                new Ext.grid.RowNumberer(),
                    {
                        id: 'FLD_NAME',
                        header: 'Primary Key',
                        dataIndex: 'FLD_NAME',
                        width: 200,
                        editable: false,
                        sortable: true,
                        editor: {
                            xtype: 'textfield',
                            allowBlank: false,
                            name      : 'FLD_NAME'
                        }
                    },{
                        id: 'PRO_VARIABLE',
                        header: 'Variables',
                        dataIndex: 'PRO_VARIABLE',
                        width: 200,
                        sortable: true,
                        editor: {
                            xtype: 'textfield',
                            allowBlank: false,
                            name      : 'PRO_VARIABLE'
                        }
                    },{
                        sortable: false,
                        renderer: function(val){return '<input type="button" value="@@" id="'+val+'"/>';}
                    }
                ]
        });

 /*var dynaformGrid = new Ext.grid.GridPanel({
        store: taskDynaform,
        id : 'mygrid',
        loadMask: true,
        loadingText: 'Loading...',
        renderTo: 'cases-grid',
        frame: false,
        autoHeight:false,
        clicksToEdit: 1,
        minHeight:400,
        height   :400,
        layout: 'fit',
        cm: dynaformColumns,
        stripeRows: true,
        tbar: tb,
        viewConfig: {forceFit: true}
   });*/

 var dynaformDetails = new Ext.FormPanel({
        labelWidth: 100,
        bodyStyle :'padding:5px 5px 0',
        width     : 550,
        items:
                [{
                    xtype: 'fieldset',
                    layout: 'fit',
                    border:true,
                    title: 'Please select the Dynaform Type',
                    width: 550,
                    collapsible: false,
                    labelAlign: 'top',
                    items:[{
                            xtype: 'radiogroup',
                            id:    'dynaformType',
                            layout: 'fit',
                            fieldLabel: 'Type',
                            itemCls: 'x-check-group-alt',
                            columns: 1,
                            items: [
                                {
                                    boxLabel: 'Blank Dynaform',
                                    name: 'DYN_SOURCE',
                                    inputValue: 'blankDyna',
                                    checked: true
                                },

                                {
                                    boxLabel: 'PM Table Dynaform',
                                    name: 'DYN_SOURCE',
                                    inputValue: 'pmTableDyna'
                                }],
                            listeners: {
                            change: function(radiogroup, radio) {
                            if(radio.inputValue == 'blankDyna')
                                {
                                    Ext.getCmp("blankDynaform").show();
                                    Ext.getCmp("pmTableDynaform").hide();
                                }
                            else
                                {
                                    Ext.getCmp("blankDynaform").hide();
                                    Ext.getCmp("pmTableDynaform").show();
                                }
                        }
                         }
                     }]
                },
                {
                    xtype: 'fieldset',
                    id:    'blankDynaform',
                    border:true,
                    hidden: false,
                    title: 'Dynaform Information',
                    width: 550,
                    items:[{
                            xtype     : 'textfield',
                            fieldLabel: 'Title',
                            name      : 'DYN_TITLE',
                            allowBlank: false
                         },{
                            width:          150,
                            xtype:          'combo',
                            mode:           'local',
                            editable:       false,
                            fieldLabel:     'Type',
                            triggerAction:  'all',
                            forceSelection: true,
                            name:           'ACTION',
                            displayField:   'name',
                            valueField:     'value',
                            value        : 'Normal',
                            store:          new Ext.data.JsonStore({
                                        fields : ['name', 'value'],
                                        data   : [
                                            {name : 'Normal',   value: 'Normal'},
                                            {name : 'Grid',   value: 'Grid'},
                                        ]
                                    })
                         },{
                            xtype     : 'textarea',
                            fieldLabel: 'Description',
                            name      : 'DYN_DESCRIPTION',
                            height    : 120,
                            width     : 350
                         }
                     ]
                },{
                    xtype: 'fieldset',
                    id:    'pmTableDynaform',
                    border:true,
                    hidden: true,
                    title: 'Dynaform Information',
                    width: 550,
                    items:[{
                            width:          150,
                            xtype:          'combo',
                            mode:           'local',
                            editable:       false,
                            triggerAction:  'all',
                            forceSelection: true,
                            fieldLabel:     'Create from a PM Table',
                            name:           'ADD_TABLE',
                            displayField:   'ADD_TAB_NAME',
                            valueField:     'ADD_TAB_UID',
                            value        : '---------------------------',
                            store        : additionalTables,
                            onSelect: function(record, index){
                                var link = 'proxyDynaform?tabId='+record.data.ADD_TAB_UID;
                                tablesFieldsStore.proxy.setUrl(link, true);
                                tablesFieldsStore.load();
                                /*Ext.Ajax.request({
                                  url   : 'proxyDynaform?tabId='+record.data.ADD_TAB_UID,
                                  method: 'GET',
                                  success: function(response) {
                                      var fields = Ext.util.JSON.decode(response.responseText);
                                      Ext.getCmp("FLD_NAME").setValue(fields[0].FLD_NAME);
                                  }
                                });*/
                                Ext.getCmp("fieldsGrid").show();
                                 
                                this.setValue(record.data[this.valueField || this.displayField]);
                                this.collapse();
                             }
                         },{
                            xtype     : 'textfield',
                            fieldLabel: 'Title',
                            name      : 'DYN_TITLE',
                            allowBlank: false
                         },{
                            xtype     : 'textarea',
                            fieldLabel: 'Description',
                            name      : 'DYN_DESCRIPTION',
                            height    : 120,
                            width     : 350
                         },
                         {
                            xtype: 'grid',
                            id:'fieldsGrid',
                            hidden: true,
                            ds: tablesFieldsStore,
                            cm: addTableColumns,
                            width: 550,
                            height: 300,
                            //autoHeight: true,
                            plugins: [editor],
                            //loadMask    : true,
                            loadingText : 'Loading...',
                            border: true,
                            renderTo : Ext.getBody()
                         }
                     ]
                }
            ]

    });
    
 var gridWindow = new Ext.Window({
        title: 'Dynaform',
        collapsible: false,
        maximizable: true,
        width: 600,
        //autoHeight: true,
        height: 500,
        layout: 'fit',
        plain: true,
        bodyStyle: 'padding:5px;',
        buttonAlign: 'center',
        items: dynaformDetails,
        buttons: [{
            text: 'Save',
            handler: function(){
                var getForm   = dynaformDetails.getForm().getValues();
                //var sDynaType = getForm.DYN_SOURCE;
                if(getForm.DYN_SOURCE == 'blankDyna')
                    {
                        var sAction   = getForm.ACTION;
                        var sTitle    = getForm.DYN_TITLE[0];
                        var sDesc     = getForm.DYN_DESCRIPTION[0];
                    }
                else
                    {
                        //sAction   = getForm.ADD_TABLE;
                        var aFields = new Array();
                        var aStoreFields  = tablesFieldsStore.data.items;
                        var aData = '';
                        for(var i=0;i<aStoreFields.length;i++)
                            {
                                /*aFields[i] = new Array();
                                aFields[i] = aStoreFields[i].data.FLD_NAME;
                                aFields[i] = aStoreFields[i].data.PRO_VARIABLE;*/
                                var fName = aStoreFields[i].data.FLD_NAME;
                                var pVar  = aStoreFields[i].data.PRO_VARIABLE;
                                aData = '"FLD_NAME":"'+fName+'","PRO_VARIABLE":"'+pVar+'"';
                            }
                        var sData = '[' +aData+ ']';
                        //var sFields = Ext.util.JSON.encode(aData);
                        sTitle    = getForm.DYN_TITLE[1];
                        sDesc     = getForm.DYN_DESCRIPTION[1];
                        var sAddTab     = getForm.ADD_TABLE;
                    }
                
                
                Ext.Ajax.request({
                  url   : '../dynaforms/dynaforms_Save.php',
                  method: 'POST',
                  params:{
                      //ACTION        : sAction,
                      FIELDS          : sData,
                      ADD_TABLE       : sAddTab,
                      PRO_UID         : pro_uid,
                      DYN_TITLE       : sTitle,
                      DYN_TYPE        : 'xmlform',
                      DYN_DESCRIPTION : sDesc
                  },
                  success: function(response) {
                      Ext.MessageBox.alert ('Status','Dynaform has been created successfully.');
                  }
                });
            }
        },{
            text: 'Cancel',
            handler: function(){
                // when this button clicked,
                gridWindow.close();
            }
        }]
    });
   gridWindow.show();
}

