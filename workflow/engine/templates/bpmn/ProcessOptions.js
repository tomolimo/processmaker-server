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
                formWindow.show();
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

 var dynaformGrid = new Ext.grid.GridPanel({
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
   });

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
                            //name:           'ADD_TABLE',
                            displayField:   'ADD_TAB_NAME',
                            valueField:     'ADD_TAB_UID',
                            value        : '---------------------------',
                            store        : additionalTables,
                            onSelect: function(record, index){
                                var link = 'proxyDynaform?tabId='+record.data.ADD_TAB_UID;
                                tablesFieldsStore.proxy.setUrl(link, true);
                                tablesFieldsStore.load();
                                
                                Ext.getCmp("fieldsGrid").show();
                                Ext.getCmp("pmTable").setValue(record.data.ADD_TAB_UID);

                                this.setValue(record.data[this.valueField || this.displayField]);
                                this.collapse();
                             }
                         },{
                            xtype:'hidden',//<--hidden field
                            name:'ADD_TABLE',
                            id  :'pmTable'
                         },
                         {
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
        height: 450,
        layout: 'fit',
        plain: true,
        bodyStyle: 'padding:5px;',
        buttonAlign: 'center',
        items: dynaformGrid
    });

 var formWindow = new Ext.Window({
        title: 'Dynaform',
        collapsible: false,
        maximizable: true,
        width: 550,
        //autoHeight: true,
        height: 400,
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
                        var sAddTab     = getForm.ADD_TABLE;
                        var aStoreFields  = tablesFieldsStore.data.items;
                        var aData = '';
                        //Creating string in JSON format
                        for(var i=0;i<aStoreFields.length;i++)
                            {
                                var fName = aStoreFields[i].data.FLD_NAME;
                                var pVar  = aStoreFields[i].data.PRO_VARIABLE;
                                aData += '"FLD_NAME":"'+fName+'","PRO_VARIABLE":"'+pVar+'",';
                            }
                        var sData = '{'+aData.slice(0,aData.length-1)+'}';
                        sTitle    = getForm.DYN_TITLE[1];
                        sDesc     = getForm.DYN_DESCRIPTION[1];
                    }


                Ext.Ajax.request({
                  url   : '../dynaforms/dynaforms_Save.php',
                  method: 'POST',
                  params:{
                      ACTION          : sAction,
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
                formWindow.close();
                taskDynaform.reload();
            }
        },{
            text: 'Cancel',
            handler: function(){
                // when this button clicked,
                formWindow.close();
            }
        }]
    });
   gridWindow.show();
}

ProcessOptions.prototype.addInputDoc= function(_5625)
{
  var pro_uid = workflow.getUrlVars();
  //var taskId  = workflow.currentSelection.id;

  var dynaFields = Ext.data.Record.create([
            {
                name: 'INP_DOC_UID',
                type: 'string'
            },
            {
                name: 'PRO_UID',
                type: 'string'
            },
            {
                name: 'INP_DOC_TITLE',
                type: 'string'
            },
            {
                name: 'INP_DOC_DESCRIPTION',
                type: 'string'
            }
            ]);

    var editor = new Ext.ux.grid.RowEditor({
    saveText: 'Update'
    });

            
  
    var inputDocStore = new Ext.data.JsonStore({
            root         : 'data',
            totalProperty: 'totalCount',
            idProperty   : 'gridIndex',
            remoteSort   : true,
            fields       : dynaFields,
            proxy: new Ext.data.HttpProxy({
              url: 'proxyInputDocument?pid='+pro_uid
            })
          });
    inputDocStore.load();

    var btnRemove = new Ext.Button({
            id: 'btnRemove',
            text: 'Delete Input Document',
            iconCls: 'application_delete',
            handler: function (s) {
                editor.stopEditing();
                var s = inputDocGrid.getSelectionModel().getSelections();
                for(var i = 0, r; r = s[i]; i++){

                    //First Deleting dynaform from Database using Ajax
                    var inputDocUID      = r.data.INP_DOC_UID;

                    //if STEP_UID is properly defined (i.e. set to valid value) then only delete the row
                    //else its a BLANK ROW for which Ajax should not be called.
                    if(r.data.INP_DOC_UID != "")
                    {
                        Ext.Ajax.request({
                          url   : '../inputdocs/inputdocs_Delete.php',
                          method: 'POST',
                          params: {
                                INP_DOC_UID        : inputDocUID
                          },
                          success: function(response) {
                            Ext.MessageBox.alert ('Status','Input document has been removed successfully.');
                          }
                        });
                    }

                    //Secondly deleting from Grid
                    inputDocStore.remove(r);
                }
            }
        });

        var btnAdd = new Ext.Button({
            id: 'btnAdd',
            text: 'New Input Document',
            iconCls: 'application_add',
            handler: function () {
            newIOWindow.show();
            }
        });

    var inputDocForm = new Ext.FormPanel({
        
        labelWidth: 100,
        bodyStyle :'padding:5px 5px 0',
        width     : 500,
        items:
                [{
                    xtype: 'fieldset',
                    layout: 'form',
                    border:true,
                    title: 'Input Document Information',
                    width: 500,
                    collapsible: false,
                    labelAlign: '',
                    items:[{
                            xtype     : 'textfield',
                            fieldLabel: 'Title',
                            name      : 'INP_DOC_TITLE'
                         },{
                            width:          150,
                            xtype:          'combo',
                            mode:           'local',
                            editable:       false,
                            fieldLabel:     'Type',
                            triggerAction:  'all',
                            forceSelection: true,
                            name:           'INP_DOC_FORM_NEEDED',
                            displayField:   'name',
                            value       :   'Digital',
                            valueField:     'value',
                            store:          new Ext.data.JsonStore({
                                        fields : ['name', 'value'],
                                        data   : [
                                            {name : 'Digital',   value: 'VIRTUAL'},
                                            {name : 'Printed',   value: 'REAL'},
                                            {name : 'Digital/Printed',   value: 'VREAL'}]}),

                           onSelect: function(record, index) {
                                //Show-Hide Format Type Field
                                if(record.data.value != 'VIRTUAL')
                                        Ext.getCmp("formType").show();
                                else
                                        Ext.getCmp("formType").hide();

                                this.setValue(record.data[this.valueField || this.displayField]);
                                this.collapse();
                             }
                         },{
                            xtype: 'fieldset',
                            layout: 'form',
                            id:'formType',
                            border: false,
                            hidden:true,
                            labelAlign: '',
                            items:[{
                                xtype:          'combo',
                                width:          150,
                                mode:           'local',
                                editable:       false,
                                fieldLabel:     'Format',
                                triggerAction:  'all',
                                forceSelection: true,
                                name:           'INP_DOC_ORIGINAL',
                                displayField:   'name',
                                //emptyText    : 'Select Format',
                                valueField:     'value',
                                value        : 'ORIGINAL',
                                store:          new Ext.data.JsonStore({
                                            fields : ['name', 'value'],
                                            data   : [
                                                {name : 'ORIGINAL',   value: 'ORIGINAL'},
                                                {name : 'LEGAL COPY',   value: 'COPYLEGAL'},
                                                {name : 'COPY',   value: 'COPY'}
                                                ]})
                                }]
                        },{
                            xtype     : 'textarea',
                            fieldLabel: 'Description',
                            name      : 'INP_DOC_DESCRIPTION',
                            height    : 120,
                            width     : 350
                         },{
                            width:          150,
                            xtype:          'combo',
                            mode:           'local',
                            editable:       false,
                            fieldLabel:     'Enable Versioning',
                            triggerAction:  'all',
                            forceSelection: true,
                            name:           'INP_DOC_VERSIONING',
                            displayField:   'name',
                            valueField:     'value',
                            value         : 'No',
                            store:          new Ext.data.JsonStore({
                                        fields : ['name', 'value'],
                                        data   : [
                                            {name : 'No',   value: ''},
                                            {name : 'Yes',   value: '1'},
                                            ]})
                         }, {
                    xtype: 'fieldset',
                    layout:'column',
                    border:false,
                    width: 550,
                    items:[{
                        columnWidth:.6,
                        layout: 'form',
                        border:false,
                        items: [{
                            xtype: 'textfield',
                            fieldLabel: 'Destination Path',
                            name: 'INP_DOC_DESTINATION_PATH',
                            anchor:'100%'
                         }]
                    },{
                        columnWidth:.3,
                        layout: 'form',
                        border:false,
                        items: [{
                            xtype:'button',
                            title: ' ',
                            text: '@@',
                            name: 'selectorigin'
                            //anchor:'15%'
                        }]
                    }]
                },{
                    xtype: 'fieldset',
                    layout:'column',
                    border:false,
                    width: 550,
                    items:[{
                        columnWidth:.6,
                        layout: 'form',
                        border:false,
                        items: [{
                            xtype: 'textfield',
                            fieldLabel: 'Tags',
                            name: 'INP_DOC_TAGS',
                            anchor:'100%'
                         }]
                    },{
                        columnWidth:.3,
                        layout: 'form',
                        border:false,
                        items: [{
                            xtype:'button',
                            title: ' ',
                            text: '@@',
                            name: 'selectorigin'
                            //anchor:'15%'
                        }]
                      }]
                    }]
                  }]
                });


                         

       var inputDocColumns = new Ext.grid.ColumnModel({
            columns: [
                {
                    id: 'INP_DOC_TITLE',
                    header: 'Title',
                    dataIndex: 'INP_DOC_TITLE',
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
                            return String.format("<a href='../dynaforms/dynaforms_Editor?PRO_UID={0}&DYN_UID={1}'>Edit</a>",pro_uid,pro_uid);
                       }
                }
                ]
        });


  var tb = new Ext.Toolbar({
            items: [btnAdd, btnRemove]
            });

  var inputDocGrid = new Ext.grid.GridPanel({
        store: inputDocStore,
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
        cm: inputDocColumns,
        stripeRows: true,
        tbar: tb,
        viewConfig: {forceFit: true}
   });

 
 var gridWindow = new Ext.Window({
        title: 'Input Document',
        collapsible: false,
        maximizable: false,
        width: 550,
        height: 450,
        minWidth: 200,
        minHeight: 150,
        layout: 'fit',
        plain: true,
        bodyStyle: 'padding:5px;',
        items: inputDocGrid,
        buttonAlign: 'center'
          });

    var newIOWindow = new Ext.Window({
        title: 'Input Document',
        collapsible: false,
        maximizable: false,
        width: 550,
        height: 550,
        minWidth: 200,
        minHeight: 150,
        layout: 'fit',
        plain: true,
        bodyStyle: 'padding:5px;',
        items: inputDocForm,
        buttonAlign: 'center',
        buttons: [{
            text: 'Save',
            handler: function(){
                var getForm   = inputDocForm.getForm().getValues();
                
                var sDocType        = getForm.INP_DOC_TITLE
                var sFormNeeded     = getForm.INP_DOC_FORM_NEEDED;
                var sOrig           = getForm.INP_DOC_ORIGINAL;
                if(sOrig == 'LEGAL COPY')
                    sOrig           = 'COPYLEGAL';

                if(sFormNeeded == 'Digital')
                     sFormNeeded     = 'VIRTUAL';
                else if(sFormNeeded == 'Printed')
                    sFormNeeded     = 'REAL';
                else
                    sFormNeeded     = 'VREAL';

                
                var sDesc = getForm.INP_DOC_DESCRIPTION;
                var sVers           = getForm.INP_DOC_VERSIONING;
                if(sVers == 'Yes')
                    sVers = '1';
                else
                    sVers = '';
                
                var sDestPath       = getForm.INP_DOC_DESTINATION_PATH;
                var sTags           = getForm.INP_DOC_TAGS;

               Ext.Ajax.request({
                  url   : '../inputdocs/inputdocs_Save.php',
                  method: 'POST',
                  params:{
                      INP_DOC_TITLE                 :sDocType,
                      INP_DOC_UID                   : '',
                      PRO_UID                       : pro_uid,
                      INP_DOC_FORM_NEEDED           : sFormNeeded,
                      INP_DOC_ORIGINAL              : sOrig,
                      INP_DOC_VERSIONING            : sVers,
                      INP_DOC_TAGS                  : 'INPUT',    //By Default
                      INP_DOC_DESCRIPTION           : sDesc
                  },
                  success: function(response) {
                      Ext.MessageBox.alert ('Status','Input document has been created successfully.');
                  }
                });

                //var getData = getstore.data.items;
                //taskExtObj.saveTaskUsers(getData);

            newIOWindow.close();
            inputDocStore.reload();
          }
        },{
            text: 'Cancel',
            handler: function(){
                // when this button clicked,
                newIOWindow.close();
            }
        }]
    });
   gridWindow.show();
}



ProcessOptions.prototype.addOutputDoc= function(_5625)
{
  var pro_uid = workflow.getUrlVars();
  //var taskId  = workflow.currentSelection.id;

  var dynaFields = Ext.data.Record.create([
            {
                name: 'OUT_DOC_UID',
                type: 'string'
            },
            {
                name: 'OUT_DOC_TYPE',
                type: 'string'
            },
            {
                name: 'OUT_DOC_TITLE',
                type: 'string'
            },
            {
                name: 'OUT_DOC_DESCRIPTION',
                type: 'string'
            }
            ]);

            var editor = new Ext.ux.grid.RowEditor({
            saveText: 'Update'
            });



    var outputDocStore = new Ext.data.JsonStore({
            root         : 'data',
            totalProperty: 'totalCount',
            idProperty   : 'gridIndex',
            remoteSort   : true,
            fields       : dynaFields,
            proxy: new Ext.data.HttpProxy({
              url: 'proxyOutputDocument?pid='+pro_uid
            })
          });
 outputDocStore.load();

 var btnRemove = new Ext.Button({
            id: 'btnRemove',
            text: 'Delete Output Document',
            iconCls: 'application_delete',
            handler: function (s) {
                editor.stopEditing();
                var s = outputDocGrid.getSelectionModel().getSelections();
                for(var i = 0, r; r = s[i]; i++){

                    //First Deleting dynaform from Database using Ajax
                    var outputDocUID      = r.data.OUT_DOC_UID;

                    //if STEP_UID is properly defined (i.e. set to valid value) then only delete the row
                    //else its a BLANK ROW for which Ajax should not be called.
                    if(r.data.OUT_DOC_UID != "")
                    {
                        Ext.Ajax.request({
                          url   : '../outputdocs/outputdocs_Delete.php',
                          method: 'POST',
                          params: {
                                OUT_DOC_UID        : outputDocUID
                          },
                          success: function(response) {
                            Ext.MessageBox.alert ('Status','Output document has been removed successfully.');
                          }
                        });
                    }

                    //Secondly deleting from Grid
                    outputDocStore.remove(r);
                }
            }
        });


         var btnAdd = new Ext.Button({
            id: 'btnAdd',
            text: 'New Output Document',
            iconCls: 'application_add',
            handler: function () {
            newOPWindow.show();
            }
        });

var tb = new Ext.Toolbar({
            items: [btnAdd, btnRemove]
       });


 var outputDocColumns = new Ext.grid.ColumnModel({
            columns: [
                {
                    id: 'OUT_DOC_TITLE',
                    header: 'Title',
                    dataIndex: 'OUT_DOC_TITLE',
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
                            return String.format("<a href='../dynaforms/dynaforms_Editor?PRO_UID={0}&DYN_UID={1}'>Edit</a>",pro_uid,pro_uid);
                       }
                }
                ]
        });


 var outputDocGrid = new Ext.grid.GridPanel({
        store       : outputDocStore,
        id          : 'mygrid',
        loadMask    : true,
        loadingText : 'Loading...',
        renderTo    : 'cases-grid',
        frame       : false,
        autoHeight  :false,
        clicksToEdit: 1,
        minHeight   :400,
        height      :400,
        layout      : 'fit',
        cm          : outputDocColumns,
        stripeRows  : true,
        tbar        : tb,
        viewConfig  : {forceFit: true}
   });

   var outputDocForm = new Ext.FormPanel({

        labelWidth      : 100,
        bodyStyle       :'padding:5px 5px 0',
        defaults        :{ autoScroll:true },
        width           : 500,
        items           :[{
                    xtype       : 'fieldset',
                    layout      : 'form',
                    border      :true,
                    title       : 'Output Document Information',
                    width       : 500,
                    collapsible : false,
                    labelAlign  : '',
                    items       :[{
                    xtype       : 'textfield',
                    fieldLabel  : 'Title',
                    allowBlank  : true,
                    name        : 'OUT_DOC_TITLE'
               },{
                    xtype: 'fieldset',
                    layout:'column',
                    border:false,
                    allowBlank: true,
                    width: 550,
                    items:[{
                    columnWidth:.6,
                    layout: 'form',
                    border:false,
                    items: [{
                    xtype: 'textfield',
                    fieldLabel: 'Filename generated',
                    name: 'OUT_DOC_FILENAME',
                    anchor:'100%'
                         }]
                },{
                    columnWidth:.3,
                    layout: 'form',
                    border:false,
                    items: [{
                    xtype:'button',
                    title: ' ',
                    text: '@@',
                    name: 'selectorigin'
                    //anchor:'15%'
                        }]
                    }]
                },{
                    xtype           : 'textarea',
                    fieldLabel      : 'Description',
                    name            : 'OUT_DOC_DESCRIPTION',
                    height          : 120,
                    width           : 350
                },{
                    width           :150,
                    xtype           :'combo',
                    mode            :'local',
                    editable        :false,
                    fieldLabel      :'Orientation',
                    triggerAction   :'all',
                    forceSelection  : true,
                    name            :'OUT_DOC_LANDSCAPE',
                    displayField    :'name',
                    value           :'Digital',
                    valueField      :'value',
                    store           :new Ext.data.JsonStore({
                                                        fields : ['name', 'value'],
                                                        data   : [
                                                        {name : 'Portrait',   value: '0'},
                                                        {name : 'Landscape',   value: '1'}]})
                           
                },{
                    width           :150,
                    xtype           :'combo',
                    mode            :'local',
                    editable        :false,
                    fieldLabel      :'Media',
                    triggerAction   :'all',
                    forceSelection  : true,
                    name            :'OUT_DOC_MEDIA',
                    displayField    :'name',
                    value           :'Digital',
                    valueField      :'value',
                    store           :new Ext.data.JsonStore({
                                                            fields : ['name', 'value'],
                                                            data   : [
                                                            {name : 'Letter',   value: 'Letter'},
                                                            {name : 'Legal',   value: 'Legal'},
                                                            {name : 'Executive',   value: 'Executive'},
                                                            {name : 'B5',   value: 'B5'},
                                                            {name : 'Folio',   value: 'Folio'},
                                                            {name : 'A0Oversize',   value: 'A0Oversize'},
                                                            {name : 'A0',   value: 'A0'},
                                                            {name : 'A1',   value: 'A1'},
                                                            {name : 'A2',   value: 'A2'},
                                                            {name : 'A3',   value: 'A3'},
                                                            {name : 'A4',   value: 'A4'},
                                                            {name : 'A5',   value: 'A5'},
                                                            {name : 'A6',   value: 'A6'},
                                                            {name : 'A7',   value: 'A7'},
                                                            {name : 'A8',   value: 'A8'},
                                                            {name : 'A9',   value: 'A9'},
                                                            {name : 'A10',   value: 'A10'},
                                                            {name : 'Screenshot640',   value: 'Screenshot640'},
                                                            {name : 'Screenshot800',   value: 'Screenshot800'},
                                                            {name : 'Screenshot1024',   value: 'Screenshot1024'}]})
                           
                },{
                    xtype       : 'textfield',
                    fieldLabel  : 'Left Margin',
                    // allowBlank: true,
                    name        : 'OUT_DOC_LEFT_MARGIN'
                },{
                    xtype       : 'textfield',
                    fieldLabel  : 'Right Margin',
                    // allowBlank: true,
                    name        : 'OUT_DOC_RIGHT_MARGIN'
                },{
                    xtype       : 'textfield',
                    fieldLabel  : 'Top Margin',
                    // allowBlank: true,
                    name        : 'OUT_DOC_TOP_MARGIN'
                },{
                            xtype     : 'textfield',
                            fieldLabel: 'Bottom Margin',
                           // allowBlank: true,
                            name      : 'OUT_DOC_BOTTOM_MARGIN'
                },{
                    width           :150,
                    xtype           :'combo',
                    mode            :'local',
                    editable        :false,
                    fieldLabel      :'Output Document to Generate',
                    triggerAction   :'all',
                    forceSelection  :true,
                    name            :'OUT_DOC_GENERATE',
                    displayField    :'name',
                    value           :'Digital',
                    valueField      :'value',
                    store           :new Ext.data.JsonStore({
                    fields          :['name', 'value'],
                    data            :[
                                    {name : 'BOTH',   value: 'BOTH'},
                                    {name : 'DOC',   value: 'DOC'},
                                    {name : 'PDF',   value: 'PDF'}]})
                },{
                    width           : 150,
                    xtype           :'combo',
                    mode            :'local',
                    editable        :false,
                    fieldLabel      :'Enable Versioning',
                    triggerAction   :'all',
                    forceSelection  :true,
                    name            :'OUT_DOC_VERSIONING',
                    displayField    :'name',
                    value           :'Digital',
                    valueField      :'value',
                    store           :new Ext.data.JsonStore({
                    fields          : ['name', 'value'],
                    data            : [
                                    {name : 'NO',   value: '0'},
                                    {name : 'YES',   value: '1'}]})
                },{
                    xtype       : 'fieldset',
                    layout      :'column',
                    border      :false,
                    allowBlank  : true,
                    width       : 550,
                    items       :[{
                    columnWidth :.6,
                    layout      : 'form',
                    border      :false,
                    items       : [{
                    xtype       : 'textfield',
                    fieldLabel  : 'Destination Path',
                    name        : 'OUT_DOC_DESTINATION_PATH',
                    anchor      :'100%'
                    }]
                },{
                    columnWidth     :.3,
                    layout          : 'form',
                    border          :false,
                    items           : [{
                    xtype           :'button',
                    title           : ' ',
                    text            : '@@',
                    name            : 'selectorigin'
                  //anchor          :'15%'
                        }]
                    }]
                },{
                    xtype       : 'fieldset',
                    layout      :'column',
                    border      :false,
                    allowBlank  : true,
                    width       : 550,
                    items       :[{
                    columnWidth :.6,
                    layout      : 'form',
                    border      :false,
                    items       : [{
                    xtype       : 'textfield',
                    fieldLabel  : 'Tags',
                    name        : 'OUT_DOC_TAGS',
                    anchor      :'100%'
                    }]
                    },{
                        columnWidth :.3,
                        layout      : 'form',
                        border      :false,
                        items       : [{
                        xtype       :'button',
                        title       : ' ',
                        text        : '@@',
                        name        : 'selectorigin'
                      //anchor      :'15%'
                            }]
                        }]
                     }]
                  }]
                });




    var newOPWindow = new Ext.Window({
        title       : 'Output Document',
        collapsible : false,
        maximizable : false,
        width       : 550,
        defaults    :{ autoScroll:true },
        height      : 550,
        minWidth    : 200,
        minHeight   : 150,
        layout      : 'fit',
        plain       : true,
        bodyStyle   : 'padding:5px;',
        items       : outputDocForm,
        buttonAlign : 'center',
        buttons     : [{
        text        : 'Save',
        handler     : function(){
                var getForm   = outputDocForm.getForm().getValues();

                var sDocTitle     = getForm.OUT_DOC_TITLE;
                var sFilename     = getForm.OUT_DOC_FILENAME;
                var sDesc         = getForm.OUT_DOC_DESCRIPTION;
                var sLandscape    = getForm.OUT_DOC_LANDSCAPE;
                    if(getForm.OUT_DOC_LANDSCAPE == 'Portrait')
                        getForm.OUT_DOC_LANDSCAPE=0;
                    if(getForm.OUT_DOC_LANDSCAPE == 'Landscape')
                        getForm.OUT_DOC_LANDSCAPE=1;
                var sMedia        = getForm.OUT_DOC_MEDIA;
                var sLeftMargin   = getForm.OUT_DOC_LEFT_MARGIN;
                var sRightMargin  = getForm.OUT_DOC_RIGHT_MARGIN;
                var sTopMargin    = getForm.OUT_DOC_TOP_MARGIN;
                var sBottomMargin = getForm.OUT_DOC_BOTTOM_MARGIN;
                var sGenerated                   = getForm.OUT_DOC_GENERATE;
                var sVersioning              = getForm.OUT_DOC_VERSIONING;
                    if(getForm.OUT_DOC_VERSIONING == 'No')
                        getForm.OUT_DOC_VERSIONING=0;
                    if(getForm.OUT_DOC_VERSIONING == 'Yes')
                        getForm.OUT_DOC_VERSIONING=1;
                var sDestPath     = getForm.OUT_DOC_DESTINATION_PATH;
                var sTags         = getForm.OUT_DOC_TAGS;

               Ext.Ajax.request({
                  url   : '../outputdocs/outputdocs_Save.php',
                  method: 'POST',
                  params:{
                      OUT_DOC_TITLE            :sDocTitle,
                      OUT_DOC_FILENAME         : sFilename,
                      OUT_DOC_DESCRIPTION      : sDesc,
                      OUT_DOC_LANDSCAPE        : sLandscape,
                      OUT_DOC_MEDIA            : sMedia,
                      OUT_DOC_LEFT_MARGIN      : sLeftMargin,
                      OUT_DOC_RIGHT_MARGIN     : sRightMargin,
                      OUT_DOC_TOP_MARGIN       : sTopMargin,
                      OUT_DOC_BOTTOM_MARGIN    : sBottomMargin,
                      OUT_DOC_GENERATE         : sGenerated,
                      OUT_DOC_VERSIONING       : sVersioning,
                      OUT_DOC_DESTINATION_PATH : sDestPath,
                      OUT_DOC_TAGS             : sTags
                  },
                  success: function(response) {
                      Ext.MessageBox.alert ('Status','Input document has been created successfully.');
                  }
                });

                //var getData = getstore.data.items;
                //taskExtObj.saveTaskUsers(getData);

            newOPWindow.close();
            outputDocStore.reload();
          }
        },{
            text: 'Cancel',
            handler: function(){
                // when this button clicked,
                newOPWindow.close();
            }
        }]
    });
   
 var gridWindow = new Ext.Window({
        title       : 'Output Document',
        collapsible : false,
        maximizable : false,
        width       : 550,
        defaults    :{ autoScroll:true },
        height      : 450,
        minWidth    : 200,
        minHeight   : 150,
        layout      : 'fit',
        plain       : true,
        bodyStyle   : 'padding:5px;',
        items       : outputDocGrid,
        buttonAlign : 'center'
          });

        gridWindow.show();
   
}

