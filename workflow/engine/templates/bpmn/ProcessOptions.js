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
 var dynaFields = Ext.data.Record.create([
    {name: 'DYN_UID'},
    {name: 'DYN_TYPE'},
    {name: 'DYN_TITLE'},
    {name: 'DYN_DESCRIPTION'},
    {name: 'TAS_EDIT'},
    {name: 'TAS_VIEW'},
    {name: 'ACTION'}
    ]);

  var editor = new Ext.ux.grid.RowEditor({
    saveText: 'Update'
  });
  var btnAdd = new Ext.Button({
    id: 'btnAdd',
    text: _('ID_NEW'),
    iconCls: 'button_menu_ext ss_sprite ss_add',
    //iconCls: 'application_add',
    handler: function () {
      dynaformDetails.getForm().reset();
      formWindow.show();
    }
  });

  var btnRemove = new Ext.Button({
    id: 'btnRemove',
    text: _('ID_DELETE'),
    iconCls: 'button_menu_ext ss_sprite ss_delete',
    handler: function (s) {
      editor.stopEditing();
      var s = dynaformGrid.getSelectionModel().getSelections();
      for(var i = 0, r; r = s[i]; i++){
        //First Deleting dynaform from Database using Ajax
        var dynUID      = r.data.DYN_UID;
      
        //if STEP_UID is properly defined (i.e. set to valid value) then only delete the row
        //else its a BLANK ROW for which Ajax should not be called.
        if( r.data.DYN_UID != "")
        {
          Ext.Ajax.request({
            url   : '../dynaforms/dynaforms_Delete.php',
            method: 'POST',
            params: {
              functions      : 'getDynaformAssign',
              PRO_UID        : pro_uid,
              DYN_UID        : dynUID
            },
            success: function(response) {
              //First check whether selected Dynaform is assigned to a task steps or not.
              //If response.responseText == 1 i.e it is assigned, => it cannot be deleted
              if(response.responseText == "")
              {
                Ext.Ajax.request({
                  url   : '../dynaforms/dynaforms_Delete.php',
                  method: 'POST',
                  params: {
                    functions      : 'getRelationInfDynaform',
                    DYN_UID        : dynUID
                  },
                  success: function(response) {
                    //Second check whether selected Dynaform is assigned to a processes supervisors or not.
                    //If response.responseText == 1 i.e it is assigned, => it cannot be deleted
                    if(response.responseText == "")
                    {
                      Ext.Ajax.request({
                        url   : '../dynaforms/dynaforms_Delete.php',
                        method: 'POST',
                        params: {
                          functions      : 'deleteDynaform',
                          DYN_UID        : dynUID
                        },
                        success: function(response) {
                        	//PMExt.notify( _('ID_TITLE_JS') , _('ID_TITLE') );
                          PMExt.notify( _('ID_STATUS') , _('ID_DYNAFORM_REMOVED') );
                          //Secondly deleting from Grid
                          taskDynaform.remove(r);
                          //Reloading store after deleting dynaform
                          taskDynaform.reload();
                        }
                      });
                    }
                    else
                      PMExt.warning(_('ID_STATUS'), _('ID_CONFIRM_CANCEL_CASE'));
                  	PMExt.notify( _('ID_TITLE_JS') , _('ID_TITLE') );
                     //  Ext.MessageBox.alert ('Status','Dynaform assigned to a process supervisors cannot be deleted.');
                  }
                });
              }
              else
                PMExt.notify( _('ID_TITLE_JS') , _('ID_TITLE_JS') );
                //Ext.MessageBox.alert ('Status','Dynaform assigned to a task steps cannot be deleted.');
            }
          });
        }      
      }
    }
  });

  var tb = new Ext.Toolbar({
    items: [btnAdd, btnRemove]
  });

  //taskDynaform? and groupingStore?
  //var taskDynaform = new Ext.data.GroupingStore({
  var taskDynaform = new Ext.data.GroupingStore({
    idProperty   : 'gridIndex',
    reader : new Ext.data.JsonReader( {
      totalProperty: 'totalCount',
      root: 'data',
      fields : dynaFields
    }),
    proxy        : new Ext.data.HttpProxy({
      url: 'proxyExtjs?pid='+pro_uid+'&action=getDynaformList'
    })
    //sortInfo:{field: 'DYN_TITLE', direction: "ASC"}
  });
  taskDynaform.load({params:{start:0, limit:10}});

  //Creating store for getting list of additional PM tables
  var additionalTablesFields = Ext.data.Record.create([
    {name: 'ADD_TAB_UID', type: 'string'},
    {name: 'ADD_TAB_NAME', type: 'string'},
    {name: 'ADD_TAB_DESCRIPTION',type: 'string'}
  ]);

  var additionalTables = new Ext.data.JsonStore({
    root         : 'data',
    totalProperty: 'totalCount',
    idProperty   : 'gridIndex',
    remoteSort   : true,
    fields       : additionalTablesFields,
    proxy: new Ext.data.HttpProxy({
      url: 'proxyExtjs?action=getAdditionalTables'
    })
  });
  additionalTables.load();

 //Creating store for getting list of Fields of additional PM tables
  var TablesFields = Ext.data.Record.create([
    {name: 'FLD_UID',type: 'string'},
    {name: 'FLD_NAME',type: 'string'},
    {name: 'FLD_DESCRIPTION',type: 'string'},
    {name: 'FLD_TYPE',type: 'string'}
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

  var expander = new Ext.ux.grid.RowExpander({
    tpl : new Ext.Template(
        "<p><b>"+TRANSLATIONS.ID_DESCRIPTION+":</b> {DYN_DESCRIPTION} </p><br><input type='button' value='UID' onclick=workflow.createUIDButton('{DYN_UID}');> </p>"
    )
  });


  var dynaformColumns = new Ext.grid.ColumnModel({
    defaults: {
      width: 90,
      sortable: true
    },
    columns: [
      expander,
      {
        header: _('ID_TITLE_FIELD'),
        dataIndex: 'DYN_TITLE',
        width: 280
      },{
        header: _('ID_TYPE'),
        dataIndex: 'DYN_TYPE',
        width: 90
      },{
        sortable: false,
        header: _('ID_TAS_EDIT'),
        dataIndex: 'TAS_EDIT',
        width: 110
      },{
        sortable: false,
        header: _('ID_TAS_VIEW'),
        dataIndex: 'TAS_VIEW',
        width: 110
      },{
        sortable: false,
        width: 50,
        renderer: function(val, meta, record)
        {
          return String.format("<a href='../dynaforms/dynaforms_Editor?PRO_UID={0}&DYN_UID={1}' >Edit</a>",pro_uid,record.data.DYN_UID);
        }
      }/*,{
        sortable: false,
        width: 60,
        renderer: function(val, meta, record)
        {
          return String.format("<input type='button' value='UID' onclick=workflow.createUIDButton('{0}');>",record.data.DYN_UID);
        }
      }*/
    ]
  });


  var addTableColumns = new Ext.grid.ColumnModel({
    columns: [
      new Ext.grid.RowNumberer(),
      {
        id: 'FLD_NAME',
        header: _('ID_PRIMARY_KEY'),
        dataIndex: 'FLD_NAME',
        width: 200,
        editable: false,
        sortable: true,
        editor: new Ext.form.TextField({
          allowBlank: false
        })
      },{
        id: 'PRO_VARIABLE',
        header: _('ID_VARIABLES'),
        dataIndex: 'PRO_VARIABLE',
        width: 200,
        sortable: true,
        editor: new Ext.form.TextField({
          allowBlank: false
        })
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
    //renderTo: 'cases-grid',
    frame: false,
    autoHeight:false,
    minHeight:400,
    height   :400,
    width: '',
    layout: 'fit',
    cm: dynaformColumns,
    stateful : true,
    stateId : 'grid',
    plugins: expander,
    stripeRows: true,
    tbar: tb,
    bbar: new Ext.PagingToolbar({
      pageSize: 10,
      store: taskDynaform,
      displayInfo: true,
      displayMsg: 'Displaying dynaforms {0} - {1} of {2}',
      emptyMsg: "No users to display",
      items:[]
    }),
    viewConfig: {forceFit: true}
   });

  var dynaformDetails = new Ext.FormPanel({
    labelWidth  : 100,
    buttonAlign : 'center',
    width       : 490,
    height      : 420,
    bodyStyle : 'padding:10px 0 0 10px;',
    autoHeight: true,
    items:
      [{
        xtype: 'fieldset',
        layout: 'fit',
        border:true,
        title: _('ID_SELECT_DYNAFORM'),
        width: 500,
        collapsible: false,
        labelAlign: 'top',
        items:[{
          xtype: 'radiogroup',
          //id:    'dynaformType',
          layout: 'fit',
          fieldLabel: _('ID_TYPE'),
          itemCls: 'x-check-group-alt',
          columns: 1,
          items: [
            {
              boxLabel: _('ID_BLANK_DYNAFORM'),
              name: 'DYN_SOURCE',
              inputValue: 'blankDyna',
              checked: true
            },
            {
              boxLabel: _('ID_PM_DYNAFORM'),
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
        title: _('ID_DYNAFORM_INFORMATION'),
        width: 500,
        items:[{
              xtype     : 'textfield',
              fieldLabel: _('ID_TITLE'),
              name      : 'DYN_TITLE1',
              width     : 350,
              allowBlank: false
           },{
              width     : 350,
              xtype:          'combo',
              allowBlank: false,
              mode:           'local',
              editable:       false,
              fieldLabel:     _('ID_TYPE'),
              triggerAction:  'all',
              forceSelection: true,
              //name:           'ACTION',
              name:           'DYN_TYPE',
              displayField:   'name',
              valueField:     'value',
              value        : 'Normal',
              store:          new Ext.data.JsonStore({
                  fields : ['name', 'value'],
                  data   : [
                      {name : _('ID_NORMAL'),   value: 'normal'},
                      {name : _('ID_GRID')  ,   value: 'grid'},
                  ]
              })
           },{
              xtype     : 'textarea',
              fieldLabel: _('ID_DESCRIPTION'),
              name      : 'DYN_DESCRIPTION1',
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
          width: 500,
          items:[{
                  width:          350,
                  xtype:          'combo',
                  mode:           'local',
                  editable:       true,
                  triggerAction:  'all',
                  forceSelection: true,
                  fieldLabel:     _('ID_CREATE_PM_TABLE'),
                  emptyText    : 'Select Table',
                  displayField:   'ADD_TAB_NAME',
                  valueField:     'ADD_TAB_UID',
                  value        : '---------------------------',
                  store        : additionalTables,
                  onSelect: function(record, index){
                      var link = 'proxyExtjs?tabId='+record.data.ADD_TAB_UID+'&action=getPMTableDynaform';
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
                  fieldLabel: _('ID_TITLE'),
                  name      : 'DYN_TITLE2',
                  allowBlank: false,
                  width     : 350
               },{
                  xtype     : 'textarea',
                  fieldLabel: _('ID_DESCRIPTION'),
                  name      : 'DYN_DESCRIPTION2',
                  height    : 120,
                  width     : 350
               },
               {
                  xtype: 'grid',
                  id:'fieldsGrid',
                  hidden: true,
                  store: tablesFieldsStore,
                  cm: addTableColumns,
                  width: 500,
                  //height: 300,
                  autoHeight: true,
                  clicksToEdit: 1,
                  plugins: [editor],
                  //loadMask    : true,
                  loadingText : 'Loading...',
                  border: false
                  //renderTo : Ext.getBody()
               }
           ]
      }
        ], buttons: [{
        text: _('ID_SAVE'),
        //formBind    :true,
        handler: function(){
            var getForm   = dynaformDetails.getForm().getValues();
            //var sDynaType = getForm.DYN_SOURCE;
            if(getForm.DYN_SOURCE == 'blankDyna')
                {
                    //var sAction   = getForm.ACTION;
                    var sTitle    = getForm.DYN_TITLE1;
                    var sDesc     = getForm.DYN_DESCRIPTION1;
                    var sDynaformType     = getForm.DYN_TYPE;
                    if(sDynaformType == 'normal')
                        sDynaformType = 'xmlform';
                }
            else
                {
                    var sAddTab     = getForm.ADD_TABLE;
                    var aStoreFields  = tablesFieldsStore.data.items;
                    var fName = new Array();
                    var pVar = new Array();
                    for(var i=0;i<aStoreFields.length;i++)
                    {
                        fName[i] = aStoreFields[i].data.FLD_NAME;
                        pVar[i]  = aStoreFields[i].data.PRO_VARIABLE;
                    }
                    var fieldname = Ext.util.JSON.encode(fName);
                    var variable = Ext.util.JSON.encode(pVar);
                    sTitle    = getForm.DYN_TITLE2;
                    sDesc     = getForm.DYN_DESCRIPTION2;
                }

                if(sTitle == '')
                    PMExt.notify( _('ID_ERROR') , _('ID_DYNAFORM_TITLE_REQUIRED') );
                else
                    {
                      Ext.Ajax.request({
                      url   : '../dynaforms/dynaforms_Save.php',
                      method: 'POST',
                      params:{
                          functions       : 'saveDynaform',
                          ACTION          : 'normal',
                          FIELDS          : fieldname,
                          VARIABLES       : variable,
                          ADD_TABLE       : sAddTab,
                          PRO_UID         : pro_uid,
                          DYN_TITLE       : sTitle,
                          DYN_TYPE        : sDynaformType,
                          DYN_DESCRIPTION : sDesc
                      },
                      success: function(response) {
                          PMExt.notify( _('ID_STATUS') , _('ID_DYANFORM_CREATED') );
                          taskDynaform.reload();
                          formWindow.hide()
                      }
                    });
                    }
        }
    },{
            text: _('ID_CANCEL'),
            handler: function(){
                // when this button clicked,
                formWindow.hide();
            }
        }]

    });

 var gridWindow = new Ext.Window({
        title: _('ID_DYNAFORMS'),
        autoScroll: true,
        collapsible: false,
        maximizable: true,
        width: 600,
        //autoHeight: true,
        height: 420,
        layout: 'fit',
        plain: true,
        buttonAlign: 'center',
        items: dynaformGrid
    });

 var formWindow = new Ext.Window({
        title: _('ID_NEW_DYNAFORM'),
        autoScroll: true,
        collapsible: false,
        maximizable: true,
        width: 550,
        height: 420,
        defaults    :{autoScroll:true},
        //autoHeight: true,
        //height: 500,
        layout: 'fit',
        plain: true,
        buttonAlign: 'center',
        items: dynaformDetails
       
    });
   gridWindow.show();
}


ProcessOptions.prototype.dbConnection = function()
{
  //Database store code starts here

  var dbConnFields = Ext.data.Record.create([
            {name: 'DBS_UID',type: 'string'},
            {name: 'DBS_TYPE',type: 'string'},
            {name: 'DBS_SERVER',type: 'string'},
            {name: 'DBS_DATABASE_NAME',type: 'string'},
            {name: 'DBS_USERNAME',type: 'string'},
            {name: 'DBS_PASSWORD',type: 'string'},
            {name: 'DBS_PORT',type: 'string'},
            {name: 'DBS_DESCRIPTION',type: 'string'},
            {name: 'DBS_ENCODE', type:'string'}
        ]);

  var btnNew = new Ext.Button({
            id: 'btnNew',
            text: _('ID_NEW'),
            iconCls: 'button_menu_ext ss_sprite ss_add',
            handler: function () {
                dbconnForm.getForm().reset();
                formWindow.show();
                
            }
  });

  var btnEdit = new Ext.Button({
            id: 'btnEdit',
            text: _('ID_EDIT'),
            iconCls: 'button_menu_ext ss_sprite ss_pencil',
            handler: function (s) {
                var selectedRow = dbGrid.getSelectionModel().getSelections();
                var dbConnUID   = selectedRow[0].data.DBS_UID;
                dbconnForm.form.load({
                url:'proxyExtjs.php?pid='+pro_uid+'&dbs_uid='+dbConnUID+'&action=editDatabaseConnection',
                    method:'GET',
                    waitMsg:'Loading',
                    success:function(form, action) {
                        formWindow.show();
                      //Ext.MessageBox.alert('Message', 'Loaded OK');
                     // setTaskAssignType(form);
                    },
                    failure:function(form, action) {
                        PMExt.notify( _('ID_STATUS') , _('ID_LOAD_FAILED') );
                        }
                });
            }
  });

   var btnRemove = new Ext.Button({
            id: 'btnRemove',
            text: _('ID_DELETE'),
            iconCls: 'button_menu_ext ss_sprite ss_delete',
            handler: function (s) {
                editor.stopEditing();
                var s = dbGrid.getSelectionModel().getSelections();
                for(var i = 0, r; r = s[i]; i++){

                    //First Deleting step from Database using Ajax
                    var dbUID      = r.data.DBS_UID;
                    //if STEP_UID is properly defined (i.e. set to valid value) then only delete the row
                    //else its a BLANK ROW for which Ajax should not be called.
                    if(r.data.DBS_UID != "")
                    {
                        Ext.Ajax.request({
                          url   : '../dbConnections/dbConnectionsAjax.php',
                          method: 'POST',
                          params: {
                                dbs_uid         : dbUID,
                                action          :'deleteDbConnection'
                          },

                          success: function(response) {
                            PMExt.notify (_('ID_STATUS'),_('DBS_REMOVE'));
                            //Secondly deleting from Grid
                            dbStore.remove(r);
                            //Reloading store after removing steps
                            dbStore.reload();
                          }
                        });
                    }
                    else
                       dbStore.remove(r);
                }
            }
        });

        var tb = new Ext.Toolbar({
            items: [btnNew,btnEdit, btnRemove]
        });

  var editor = new Ext.ux.grid.RowEditor({
            saveText: _('ID_UPDATE')
        });

  var dbStore = new Ext.data.JsonStore({
            root         : 'data',
            totalProperty: 'totalCount',
            idProperty   : 'gridIndex',
            remoteSort   : true,
            fields       : dbConnFields,
            proxy: new Ext.data.HttpProxy({
              url: 'proxyExtjs.php?pid='+pro_uid+'&action=getDatabaseConnectionList'
            })
          });
  dbStore.load({params:{start : 0 , limit : 10 }});

  var expander = new Ext.ux.grid.RowExpander({
    tpl : new Ext.Template(
        "<p><b>"+TRANSLATIONS.ID_DESCRIPTION+":</b> {DBS_DESCRIPTION} </p><br><input type='button' value='UID' onclick=workflow.createUIDButton('{DBS_UID}');> </p>"
    )
  });


  var dbGridColumn =  new Ext.grid.ColumnModel({
      columns: [
               expander,
                    {
                        id: 'DBS_TYPE',
                        header: _('ID_TYPE'),
                        dataIndex: 'DBS_TYPE',
                        //width: 100,
                        editable: false,
                        sortable: true,
                        editor: new Ext.form.TextField({
                        //allowBlank: false
                            })
                    },{
                        id: 'DBS_SERVER',
                        header: _('ID_SERVER'),
                        dataIndex: 'DBS_SERVER',
                        //width: 100,
                        sortable: true,
                        editor: new Ext.form.TextField({
                            //allowBlank: false
                            })
                    },{
                        id: 'DBS_DATABASE_NAME',
                        header: _('ID_DATABASE_NAME'),
                        dataIndex: 'DBS_DATABASE_NAME',
                        width: 150,
                        sortable: true,
                        editor: new Ext.form.TextField({
                       // allowBlank: false
                            })
                    },{
                        id: 'DBS_DESCRIPTION',
                        header: _('ID_DESCRIPTION'),
                        dataIndex: 'DBS_DESCRIPTION',
                        width: 100,
                        sortable: true,
                        editor: new Ext.form.TextField({
                            //allowBlank: false
                            })
                    }/*,{
                            sortable: false,
                            renderer: function(val, meta, record)
                               {
                                    return String.format("<input type='button' value='UID' onclick=workflow.createUIDButton('{0}');>",record.data.DBS_UID);
                               }
                      }*/
                ]
     });

  var dbGrid = new Ext.grid.GridPanel({
        store: dbStore,
        id : 'mygrid',
        loadMask: true,
        loadingText: 'Loading...',
        //renderTo: 'cases-grid',
        frame: false,
        autoHeight:false,
        clicksToEdit: 1,
        width:480,
        minHeight:400,
        height   :380,
        layout: 'fit',
        cm: dbGridColumn,
        plugins: expander,
        stripeRows: true,
        tbar: tb,
        bbar: new Ext.PagingToolbar({
            pageSize: 10,
            store: dbStore,
            displayInfo: true,
            displayMsg: 'Displaying DB Connection {0} - {1} of {2}',
            emptyMsg: "No DB Connection to display",
            items:[]
        }),
        viewConfig: {forceFit: true}
   });

  var dbconnForm =new Ext.FormPanel({
   //   title:"Add new Database Source",
      collapsible: false,
      maximizable: true,
      //allowBlank:false,
      width:400,
      frame:false,
      autoDestroy : true,
      monitorValid : true,
      plain: true,
      bodyStyle : 'padding:10px 0 0 10px;',
      buttonAlign: 'center',
        items:[{
                        xtype: 'combo',
                        width:  200,
                        mode: 'local',
                        editable:       false,
                        fieldLabel: _('ID_ENGINE'),
                        triggerAction: 'all',
                        forceSelection: true,
                        name: 'DBS_TYPE',
                        displayField:  'name',
                        emptyText    : 'Select Format',
                        valueField   : 'value',
                        allowBlank: false,
                        //value        : 'Select',
                        store: new Ext.data.JsonStore({
                                 fields : ['name', 'value'],
                                 data   : [
                                          {name : 'Select', value: 'select'},
                                          {name : 'MySql',   value: 'MySql'},
                                          {name : 'PostGreSql',   value: 'PostGreSql'},
                                          {name : 'Microsoft SQL server',   value: 'Microsoft SQL server'}
                                          ]}),
                        onSelect: function(record, index) {
                                 //Show-Hide Format Type Field
                                if(record.data.value == 'MySql')
                                        {Ext.getCmp("encode").show();
                                         Ext.getCmp("postgre").hide();
                                         Ext.getCmp("port").setValue('3306')}
                                else if(record.data.value == 'PostGreSql')
                                        {Ext.getCmp("postgre").show();
                                         Ext.getCmp("encode").hide();
                                         Ext.getCmp("port").setValue('5432')}
                                else
                                        {Ext.getCmp("sqlserver").show();
                                         Ext.getCmp("postgre").hide();
                                         Ext.getCmp("port").setValue('1433')}
                                         this.setValue(record.data[this.valueField || this.displayField]);
                                         this.collapse();
                        }
                      },{
                              xtype: 'fieldset',
                              id:    'encode',
                              border:false,
                              hidden: true,
                              items: [{
                                      xtype: 'combo',
                                      width:  220,
                                      mode: 'local',
                                   //   hidden: true,
                                      editable:       false,
                                      fieldLabel: _('ID_ENCODE'),
                                      triggerAction: 'all',
                                      forceSelection: true,
                                      //dataIndex : 'ENGINE',
                                      displayField:   'value',
                                      valueField:     'name',
                                      name: 'DBS_ENCODE',
                                      store: new Ext.data.JsonStore({
                                             fields : ['name', 'value'],
                                             data   :  [
                                      {name:'armscii8', value:'armscii8 - ARMSCII-8 Armenian'},
                                      {name:'ascii',    value:'ascii - US ASCII'},
                                      {name:'big5',        value:'big5 - Big5 Traditional Chinese'},
                                      {name:'binary',  value: 'binary  - Binary pseudo charset'},
                                      {name:'cp850',    value:'cp850 - DOS West European'},
                                      {name:'cp852',   value: 'cp852 - DOS Central European'},
                                      {name:'cp866',    value:'cp866 - DOS Russian'},
                                      {name:'cp932',   value: 'cp932] - SJIS for Windows Japanese'},
                                      {name:'cp1250',  value: 'cp1250 - Windows Central European'},
                                      {name:'cp1251',  value: 'cp1251 - Windows Cyrillic'},
                                      {name:'cp1256',  value: 'cp1256  - Windows Arabic'},
                                      {name:'cp1257',  value: 'cp1257  - Windows Baltic'},
                                      {name:'dec8',        value:'dec8 - DEC West European'},
                                      {name:'eucjpms', value: 'eucjpms - UJIS for Windows Japanese'},
                                      {name:'euckr',   value: 'euckr - EUC-KR Korean'},
                                      {name:'gb2312',  value: 'gb2312 - GB2312 Simplified Chinese'},
                                      {name:'gbk',        value: 'gbk - GBK Simplified Chinese'},
                                      {name:'geostd8', value: 'geostd8 - GEOSTD8 Georgian'},
                                      {name:'greek',   value: 'greek - ISO 8859-7 Greek'},
                                      {name:'hebrew',  value: 'hebrew - ISO 8859-8 Hebrew'},
                                      {name:'hp8',        value: 'hp8 - HP West European'},
                                      {name:'keybcs2', value: 'keybcs2 - DOS Kamenicky Czech-Slovak'},
                                      {name:'koi8r',    value:'koi8r - KOI8-R Relcom Russian'},
                                       {name:'koi8u',   value: 'koi8u - KOI8-U Ukrainian'},
                                      {name:'latin1',   value:'latin1 - cp1252 West European'},
                                      {name:'latin2',   value:'latin2 - ISO 8859-2 Central European'},
                                      {name:'latin5',   value:'latin5 - ISO 8859-9 Turkish'},
                                      {name:'latin7',  value: 'atin7 - ISO 8859-13 Baltic'},
                                      {name:'macce',   value: 'macce - Mac Central European'},
                                      {name:'macroman', value:'macroman - Mac West European'},
                                      {name:'sjis',        value:'sjis - Shift-JIS Japanese'},
                                      {name:'swe7',        value:'swe7 - 7bit Swedish'},
                                      {name:'tis620',  value: 'tis620 - TIS620 Thai'},
                                      {name:'ucs2',        value:'ucs2 - UCS-2 Unicode'},
                                      {name:'ujis',        value:'ujis - EUC-JP Japanese'},
                                      {name:'utf8',        value:'utf8 - UTF-8 Unicode'}
                                    ]}),
                                onSelect: function(record, index){
                                dbconnForm.getForm().findField('DBS_ENCODE').setValue(record.data.value);
                                this.setValue(record.data[this.valueField || this.displayField]);
                                     this.collapse();
                                    }
                             }]
          
                      },{
                         xtype: 'fieldset',
                         id: 'postgre',
                         border:false,
                         hidden: true,
                         items:[{
                                  xtype: 'combo',
                                   width:  220,
                                  mode: 'local',
                                 // hidden: true,
                                  editable:false,
                                  fieldLabel:_('ID_ENCODE'),
                                  triggerAction: 'all',
                                  forceSelection: true,
                                  //dataIndex : 'ENGINE',
                                  displayField:   'name',
                                  valueField:     'value',
                                  name: 'DBS_ENCODE',
                                  store: new Ext.data.JsonStore({
                                         fields : ['name', 'value'],
                                         data   :  [
                                                      {name:"BIG5",        value:"BIG5"},
                                                      {name:"EUC_CN",      value:"EUC_CN"},
                                                      {name:"EUC_JP",      value:"EUC_JP"},
                                                      {name:"EUC_KR",      value:"EUC_KR"},
                                                      {name:"EUC_TW",      value:"EUC_TW"},
                                                      {name:"GB18030",     value:"GB18030"},
                                                      {name:"GBK",         value:"GBK"},
                                                      {name:"ISO_8859_5",  value:"ISO_8859_5"},
                                                      {name:"ISO_8859_6",  value:"ISO_8859_6"},
                                                      {name:"ISO_8859_7",  value:"ISO_8859_7"},
                                                      {name:"ISO_8859_8",  value: "ISO_8859_8"},
                                                      {name:"JOHAB",       value:"JOHAB"},
                                                      {name:"KOI8",        value: "KOI8"},
                                                      {name:"selected",    value:  "LATIN1"},
                                                      {name:"LATIN2",      value:"LATIN2"},
                                                      {name:"LATIN3",      value:"LATIN3"},
                                                      {name:"LATIN4",      value: "LATIN4"},
                                                      {name:"LATIN5",      value:"LATIN5"},
                                                      {name:"LATIN6",      value: "LATIN6"},
                                                      {name:"LATIN7",      value:"LATIN7"},
                                                      {name:"LATIN8",      value:"LATIN8"},
                                                      {name:"LATIN9",      value:"LATIN9"},
                                                      {name:"LATIN10",     value:"LATIN10"},
                                                      {name:"SJIS",        value:"SJIS"},
                                                      {name:"SQL_ASCII",   value:"SQL_ASCII"},
                                                      {name:"UHC",         value: "UHC"},
                                                      {name:"UTF8",        value: "UTF8"},
                                                      {name:"WIN866",      value: "WIN866"},
                                                      {name:"WIN874",      value:"WIN874"},
                                                      {name:"WIN1250",     value:"WIN1250"},
                                                      {name:"WIN1251",     value:"WIN1251"},
                                                      {name:"WIN1252",     value:"WIN1252"},
                                                      {name:"WIN1256",     value:"WIN1256"},
                                                      {name:"WIN1258",     value:"WIN1258"}
                                                   ]}),
                                               onSelect: function(record, index){
                                dbconnForm.getForm().findField('DBS_ENCODE').setValue(record.data.value);
                                this.setValue(record.data[this.valueField || this.displayField]);
                                     this.collapse();
                                    }
                               }]
                      },{
                         xtype: 'fieldset',
                         id: 'sqlserver',
                         border:false,
                         hidden: true,
                         items:[{
                                  xtype: 'combo',
                                  width:  220,
                                  mode: 'local',
                                  editable:       false,
                                  fieldLabel: _('ID_ENCODE'),
                                  triggerAction: 'all',
                                  forceSelection: true,
                                  //dataIndex : 'ENGINE',
                                  displayField:   'name',
                                  valueField:     'value',
                                  name: 'DBS_ENCODE',
                                  store: new Ext.data.JsonStore({
                                         fields : ['name', 'value'],
                                  data   :  [
                                            {name:'utf8',    value: 'utf8'}
                                  ]}),
                              onSelect: function(record, index){
                                dbconnForm.getForm().findField('DBS_ENCODE').setValue(record.data.value);
                                this.setValue(record.data[this.valueField || this.displayField]);
                                     this.collapse();
                                    }
                               }]
                  
                      },{
                         xtype: 'textfield',
                         fieldLabel: _('ID_SERVER'),
                         name: 'DBS_SERVER',
                         width:  200,
                         allowBlank: false
                      },{
                         xtype: 'textfield',
                         fieldLabel: _('ID_DATABASE_NAME'),
                         name: 'DBS_DATABASE_NAME',
                         width:  200,
                         allowBlank: false
                      },{
                        xtype: 'textfield',
                        fieldLabel: _('ID_USERNAME'),
                        name: 'DBS_USERNAME',
                        width:  200,
                        allowBlank: false
                      },{
                        xtype: 'textfield',
                        fieldLabel: _('ID_CACHE_PASSWORD'),
                        inputType:'password',
                        width:  200,
                        name: 'DBS_PASSWORD',
                        allowBlank: true
                      },{
                        xtype: 'textfield',
                        fieldLabel: _('ID_PORT'),
                        name: 'DBS_PORT',
                        width:  200,
                        id:'port',
                        //allowBlank: false,
                        editable:false
                      },{
                        xtype: 'textarea',
                        fieldLabel: _('ID_DESCRIPTION'),
                        name: 'DBS_DESCRIPTION',
                        allowBlank: true,
                        width: 220,
                        height:100
                      },{
                        id : 'DBS_UID',
                        xtype: 'hidden',
                        name : 'DBS_UID'
                      },{
                        id : 'DBS_ENCODE',
                        xtype: 'hidden',
                        name : 'DBS_ENCODE'
                      }],
         buttons: [{text:_('ID_TEST_CONNECTION'),
                                   id: 'test',
                                    //formbind: true,
                                    handler: function(){
                                       // testConnWindow.show();

                                       Ext.fly('p3text').update('Working');
                                        mybar.wait({
                                            interval:200,
                                            duration:5000,
                                            increment:15,
                                            fn:function(){
                                                btn3.dom.disabled = false;
                                                Ext.fly('p3text').update('Done');
                                            }
                                        });

                                       var getForm         = dbconnForm.getForm().getValues();
                                        //var dbConnUID       = getForm.DBS_UID;
                                        var Type            = getForm.DBS_TYPE;
                                        var Server          = getForm.DBS_SERVER;
                                        var DatabaseName    = getForm.DBS_DATABASE_NAME;
                                        var Username        = getForm.DBS_USERNAME;
                                        var Password        = getForm.DBS_PASSWORD;
                                        var Port            = getForm.DBS_PORT;
                                       
                                       //var Description     = getForm.DBS_DESCRIPTION;
                                       for(var Step=1;Step<=4;Step++)
                                          // {
                                               {
                                               Ext.Ajax.request({
                                                   url   : '../dbConnections/dbConnectionsAjax.php',
                                                   method: 'POST',
                                                   params:{
                                                           step     :Step,
                                                           type     :Type,
                                                           server   :Server,
                                                           db_name  :DatabaseName,
                                                           user     :Username ,
                                                           passwd   :Password,
                                                           port     :Port,
                                                           //desc     :Description,
                                                           action   :'testConnection'
                                                          },
                                                    success: function(response) {
                                                    PMExt.notify( _('ID_STATUS') , _('ID_DBS_CONNECTION_TEST') );
                                                    }
                                    });
                            }
                        }
        },{
        text: _('ID_SAVE'),
        formBind    :true,
        handler: function(){
            var getForm         = dbconnForm.getForm().getValues();
            var dbConnUID       = getForm.DBS_UID;
            var Type            = getForm.DBS_TYPE;
            var Server          = getForm.DBS_SERVER;
            var DatabaseName    = getForm.DBS_DATABASE_NAME;
            var Username        = getForm.DBS_USERNAME;
            var Password        = getForm.DBS_PASSWORD;
            var Port            = getForm.DBS_PORT;
            var Description     = getForm.DBS_DESCRIPTION;
            var encode          = getForm.DBS_ENCODE;


            if(dbConnUID=='')
                {
                   Ext.Ajax.request({
                       url   : '../dbConnections/dbConnectionsAjax.php',
                       method: 'POST',
                       params:{
                               dbs_uid  :dbConnUID,
                               type     :Type,
                               server   :Server,
                               db_name  :DatabaseName,
                               user     :Username ,
                               passwd   :Password,
                               port     :Port,
                               desc     :Description,
                               PROCESS  :pro_uid,
                               enc      :encode,
                               action   :'saveConnection'
                              },
                        success: function(response) {
                        PMExt.notify( _('ID_STATUS') , _('ID_DBS_CONNECTION_SAVE') );
                        }
                  });
                }
            else
                {
                 Ext.Ajax.request({
                     url   : '../dbConnections/dbConnectionsAjax.php',
                     method: 'POST',
                     params:{
                              dbs_uid  :dbConnUID,
                              type     :Type,
                              server   :Server,
                              db_name  :DatabaseName,
                              user     :Username ,
                              passwd   :Password,
                              port     :Port,
                              PROCESS  :pro_uid,
                              desc     :Description,
                              enc      :encode,
                              action   :'saveEditConnection'
                            },
                    success: function(response) {
                         PMExt.notify( _('ID_STATUS') , _('ID_DBS_CONNECTION_EDIT') );
                    }
                    });
            }
           formWindow.hide();
           dbStore.reload();
        }
    },{
        text: _('ID_CANCEL'),
        handler: function(){
            // when this button clicked,
            formWindow.hide();
        }
    }]
  })

   /*var testConnBar = new Ext.ProgressBar({
    id: 'loadBar',
    text: 'Loading...'
});

var testConnWindow = new Ext.Window({
    closable: false,
    collapsible: false,
    draggable: false,
    resizable: false,
    el: 'gridDiv',
    layout:'fit',
    width:500,
    height:40,
    plain: true,
    modal: true,
    items: testConnBar
});*/

  var formWindow = new Ext.Window({
    title: _('ID_DBS_SOURCE'),
    collapsible: false,
    maximizable: true,
    width: 400,
    //autoHeight: true,
    //height: 400,
    //layout: 'fit',
    plain: true,
    buttonAlign: 'center',
    items: dbconnForm
  });

  var gridWindow = new Ext.Window({
    title: _('ID_DBS_LIST'),
    collapsible: false,
    maximizable: true,
    width: 480,
    //autoHeight: true,
    height: 420,
    //layout: 'fit',
    plain: true,
    buttonAlign: 'center',
    items: dbGrid
  });
  gridWindow.show();
}


ProcessOptions.prototype.addInputDoc= function(_5625)
{
  var ProcMapObj= new ProcessMapContext();
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
            },{
                name: 'INP_DOC_VERSIONING',
                type: 'string'
            },{
                name: 'INP_DOC_DESTINATION_PATH',
                type: 'string'
            }
            ]);

    var editor = new Ext.ux.grid.RowEditor({
    saveText: _('ID_UPDATE')
    });

  var inputDocStore = new Ext.data.JsonStore({
            root         : 'data',
            totalProperty: 'totalCount',
            idProperty   : 'gridIndex',
            remoteSort   : true,
            fields       : dynaFields,
            proxy: new Ext.data.HttpProxy({
              url: 'proxyExtjs?pid='+pro_uid+'&action=getInputDocumentList'
            })
          });
  inputDocStore.load({params:{start : 0 , limit : 10 }});

  var btnRemove = new Ext.Button({
            id: 'btnRemove',
            text: _('ID_DELETE'),
            iconCls: 'button_menu_ext ss_sprite ss_delete',
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
                                functions          : 'getRelationInfDoc',
                                INP_DOC_UID        : inputDocUID
                          },
                          success: function(response) {
                            //First check whether selected input document is assigned to a process supervisor or not.
                            //If response.responseText == 1 i.e it is assigned, => it cannot be deleted
                            if(response.responseText == "")
                             {
                                Ext.Ajax.request({
                                  url   : '../inputdocs/inputdocs_Delete.php',
                                  method: 'POST',
                                  params: {
                                        functions          : 'deleteInputDocument',
                                        INP_DOC_UID        : inputDocUID
                                  },
                                  success: function(response) {
                                    PMExt.notify( _('ID_STATUS') , _('ID_INPUT_REMOVED') );
                                    //Secondly deleting from Grid
                                    inputDocStore.remove(r);
                                    //reloading store after deleting input document
                                    inputDocStore.reload();
                                  }
                                });
                             }
                             else
                                  PMExt.notify( _('ID_STATUS') , _('ID_INPUT_WARNING') );
                              }
                        });
                    }
                    else
                        inputDocStore.remove(r);
                }
            }
        });

  var btnAdd = new Ext.Button({
            id: 'btnAdd',
            text: _('ID_NEW'),
           iconCls: 'button_menu_ext ss_sprite ss_add',
            handler: function () {
               newIOWindow.show();
               inputDocForm.getForm().reset();
            }
        });
 

 var btnEdit = new Ext.Button({
            id: 'btnEdit',
            text: _('ID_EDIT'),
            iconCls: 'button_menu_ext ss_sprite ss_pencil',
            handler: function (s) {
                var selectedRow = inputDocGrid.getSelectionModel().getSelections();
                var inputDocUID   = selectedRow[0].data.INP_DOC_UID;

                 //Loading Task Details into the form
                  inputDocForm.form.load({
                        url:'proxyExtjs.php?INP_DOC_UID=' +inputDocUID+'&action=editInputDocument',
                        method:'GET',
                        waitMsg:'Loading',
                        success:function(form, action) {
                           //Ext.MessageBox.alert('Message', 'Loaded OK');
                           newIOWindow.show();
                           Ext.getCmp("INP_DOC_UID").setValue(inputDocUID);
                        },
                        failure:function(form, action) {
                            PMExt.notify( _('ID_STATUS') , _('ID_LOAD_FAILED') );
                        }
                    });
            }
        });

  var inputDocForm = new Ext.FormPanel({
        labelWidth: 100,
        width     : 500,
        height    : 380,
        monitorValid : true,
        autoHeight: true,
        bodyStyle : 'padding:10px 0 0 10px;',
        items:[{
                    xtype: 'fieldset',
                    layout: 'form',
                    border:true,
                    title: _('ID_INPUT_INFO'),
                    width: 500,
                    //height:500,
                    collapsible: false,
                    labelAlign: '',
                    items:[{
                            xtype     : 'textfield',
                            fieldLabel: _('ID_TITLE'),
                            width     : 200,
                            name      : 'INP_DOC_TITLE',
                            allowBlank: false
                         },{
                            width     : 200,
                            xtype:          'combo',
                            mode:           'local',
                            editable:       false,
                            fieldLabel:     _('ID_TYPE'),
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
                            width     : 300,
                            hidden:true,
                            labelAlign: '',
                            items:[{
                                xtype:          'combo',
                                width:          150,
                                mode:           'local',
                                editable:       false,
                                fieldLabel:     _('ID_FORMAT'),
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
                                                {name : 'Original',   value: 'ORIGINAL'},
                                                {name : 'Legal Copy',   value: 'COPYLEGAL'},
                                                {name : 'Copy',   value: 'COPY'}
                                                ]})
                                }]
                        },{
                            xtype     : 'textarea',
                            fieldLabel: _('ID_DESCRIPTION'),
                            name      : 'INP_DOC_DESCRIPTION',
                            height    : 120,
                            width     : 300
                         },{
                            width     : 200,
                            xtype:          'combo',
                            mode:           'local',
                            editable:       false,
                            fieldLabel:     _('ID_ENABLE_VERSIONING'),
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
                         },{
                    layout      :'column',
                    border      :false,
                    items       :[{
                        //columnWidth :.6,
                        layout      : 'form',
                        border      :false,
                        items       : [{
                                xtype       : 'textfield',
                                width     : 200,
                                //id          : 'DestPath',
                                fieldLabel  : _('ID_DESTINATION_PATH'),
                                name        : 'INP_DOC_DESTINATION_PATH',
                                anchor      :'100%'
                        }]
                    },{
                        //columnWidth     :.4,
                        layout          : 'form',
                        border          :false,
                        items           : [{
                                xtype           :'button',
                                title           : ' ',
                                width:50,
                                text            : '@@',
                               name            : 'selectorigin',
                                 handler: function (s) {
                                                    workflow.variablesAction = 'form';
                                                    workflow.fieldName         = 'INP_DOC_DESTINATION_PATH' ;
                                                    workflow.variable        = '@@',
                                                    workflow.formSelected    = inputDocForm;
                                                    var rowData = ProcMapObj.ExtVariables();
                                            }
                            }]
                    }]
                },{
                    layout      :'column',
                    border      :false,
                    items       :[{
                        //columnWidth :.6,
                        layout      : 'form',
                        border      :false,
                        items       : [{
                        xtype       : 'textfield',
                        width     : 200,
                        //id          :'tags',
                        fieldLabel  : _('ID_TAGS'),
                        name        : 'INP_DOC_TAGS',
                        anchor      :'100%'
                        }]
                    },{
                        columnWidth :.4,
                        layout      : 'form',
                        border      :false,
                        items       : [{
                                xtype       :'button',
                                title       : ' ',
                                width:50,
                                text        : '@@',
                                name        : 'selectorigin',
                                handler: function (s) {
                                                workflow.variablesAction = 'form';
                                                workflow.fieldName         = 'INP_DOC_TAGS' ;
                                                workflow.variable        = '@@',
                                                workflow.formSelected    = inputDocForm;
                                                var rowData = ProcMapObj.ExtVariables();
                                   }
                              }]
                        }]
                     },{
                      id : 'INP_DOC_UID',
                      xtype: 'hidden',
                      name : 'INP_DOC_UID'
               }]
        }],
        buttons: [{
            text: _('ID_SAVE'),
            formBind    :true,
            handler: function(){
                var getForm         = inputDocForm.getForm().getValues();
                var sDocUID        = getForm.INP_DOC_UID;
                var sDocTitle        = getForm.INP_DOC_TITLE;
                var sFormNeeded     = getForm.INP_DOC_FORM_NEEDED;
                var sOrig           = getForm.INP_DOC_ORIGINAL;
                if(sOrig == "" || sOrig == "Original")
                    sOrig           = 'ORIGINAL';
                
                if(sOrig == 'Legal Copy')
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

               if(sDocUID == "")
               {
                    Ext.Ajax.request({
                      url   : '../inputdocs/inputdocs_Save.php',
                      method: 'POST',
                      params:{
                          functions                : 'lookForNameInput',
                          NAMEINPUT                : sDocTitle,
                          proUid                   : pro_uid
                      },
                      success: function(response) {
                        if(response.responseText == "1")
                        {
                           Ext.Ajax.request({
                              url   : '../inputdocs/inputdocs_Save.php',
                              method: 'POST',
                              params:{
                                  functions                     : '',
                                  INP_DOC_TITLE                 : sDocTitle,
                                  INP_DOC_UID                   : sDocUID,
                                  PRO_UID                       : pro_uid,
                                  INP_DOC_FORM_NEEDED           : sFormNeeded,
                                  INP_DOC_ORIGINAL              : sOrig,
                                  INP_DOC_VERSIONING            : sVers,
                                  INP_DOC_TAGS                  : sTags,
                                  INP_DOC_DESCRIPTION           : sDesc,
                                  INP_DOC_DESTINATION_PATH      : sDestPath
                              },
                              success: function(response) {
                                  PMExt.notify( _('ID_STATUS') , _('ID_INPUT_CREATE') );
                                  newIOWindow.hide();
                                  inputDocStore.reload();
                              }
                            });
                        }
                        else
                             PMExt.notify( _('ID_STATUS') , _('ID_INPUT_NOT_SAVE') );
                             }
                 })
                }
                else
                {
                    Ext.Ajax.request({
                          url   : '../inputdocs/inputdocs_Save.php',
                          method: 'POST',
                          params:{
                              functions                     : '',
                              INP_DOC_TITLE                 : sDocTitle,
                              INP_DOC_UID                   : sDocUID,
                              PRO_UID                       : pro_uid,
                              INP_DOC_FORM_NEEDED           : sFormNeeded,
                              INP_DOC_ORIGINAL              : sOrig,
                              INP_DOC_VERSIONING            : sVers,
                              INP_DOC_TAGS                  : sTags,
                              INP_DOC_DESCRIPTION           : sDesc,
                              INP_DOC_DESTINATION_PATH      : sDestPath
                          },
                          success: function(response) {
                               PMExt.notify( _('ID_STATUS') , _('ID_INPUT_UPDATE') );
                              newIOWindow.hide();
                              inputDocStore.reload();
                          }
                        });
                }
           }
        },{
            text: _('ID_CANCEL'),
            handler: function(){
                // when this button clicked,
                newIOWindow.hide();
            }
        }],
       buttonAlign : 'center'
    });

 var expander = new Ext.ux.grid.RowExpander({
    tpl : new Ext.Template(
        "<p><b>"+TRANSLATIONS.ID_DESCRIPTION+":</b> {INP_DOC_DESCRIPTION} </p><br><input type='button' value='UID' onclick=workflow.createUIDButton('{INP_DOC_UID}');> </p>"
    )
  });

var inputDocColumns = new Ext.grid.ColumnModel({
            columns: [
                expander,
                {
                    id: 'INP_DOC_TITLE',
                    header: _('ID_TITLE'),
                    dataIndex: 'INP_DOC_TITLE',
                    width: 280,
                    editable: false,
                    editor: new Ext.form.TextField({
                    //allowBlank: false
                    })
                },{
                    id: 'INP_DOC_VERSIONING',
                    header: _('ID_VERSIONING'),
                    dataIndex: 'INP_DOC_VERSIONING',
                    width: 280,
                    editable: false,
                    editor: new Ext.form.TextField({
                    //allowBlank: false
                    })
                },{
                    id: 'INP_DOC_DESTINATION_PATH',
                    header: _('ID_DESTINATION_PATH'),
                    dataIndex: 'INP_DOC_DESTINATION_PATH',
                    width: 280,
                    editable: false,
                    editor: new Ext.form.TextField({
                    //allowBlank: false
                    })
                }
                ]
        });


  var tb = new Ext.Toolbar({
            items: [btnAdd, btnRemove, btnEdit]
            });

  var inputDocGrid = new Ext.grid.GridPanel({
        store: inputDocStore,
        id : 'mygrid',
        loadMask: true,
        //loadingText: 'Loading...',
        //renderTo: 'cases-grid',
        frame: false,
        autoHeight:false,
        clicksToEdit: 1,
        minHeight:400,
        height   :350,
        layout: 'fit',
        plugins: expander,
        cm: inputDocColumns,
        stripeRows: true,
        tbar: tb,
        bbar: new Ext.PagingToolbar({
            pageSize: 10,
            store: inputDocStore,
            displayInfo: true,
            displayMsg: 'Displaying Input Document {0} - {1} of {2}',
            emptyMsg: "No Input Document to display",
            items:[]
        }),
        viewConfig: {forceFit: true}
   });


  var gridWindow = new Ext.Window({
        title: _('ID_REQUEST_DOCUMENTS'),
        width: 550,
        height: 420,
        minWidth: 200,
        minHeight: 150,
        layout: 'fit',
        plain: true,
        items: inputDocGrid,
        autoScroll: true
 });

 var newIOWindow = new Ext.Window({
        title: _('ID_NEW_INPUTDOCS'),
        width: 550,
        height: 400,
        minWidth: 200,
        minHeight: 150,
        autoScroll: true,
        layout: 'fit',
        plain: true,
        items: inputDocForm
    });
   gridWindow.show();
}



ProcessOptions.prototype.addOutputDoc= function(_5625)
{
 var ProcMapObj= new ProcessMapContext();

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
            saveText: _('ID_UPDATE')
            });



  var outputDocStore = new Ext.data.JsonStore({
            root         : 'data',
            totalProperty: 'totalCount',
            idProperty   : 'gridIndex',
            remoteSort   : true,
            fields       : dynaFields,
            proxy        : new Ext.data.HttpProxy({
                           url: 'proxyExtjs?pid='+pro_uid+'&action=getOutputDocument'
                           })
  });
  outputDocStore.load({params:{start : 0 , limit : 10 }});

  var btnRemove = new Ext.Button({
            id: 'btnRemove',
            text: _('ID_DELETE'),
            iconCls: 'button_menu_ext ss_sprite ss_delete',
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
                              PMExt.notify( _('ID_STATUS') , _('ID_OUTPUT_REMOVE') );
                            //Secondly deleting from Grid
                            outputDocStore.remove(r);
                            //reloading store after deleting output document
                            outputDocStore.reload();
                          }
                        });
                    }
                }
            }
        });


  var btnAdd = new Ext.Button({
            id: 'btnAdd',
            text: _('ID_NEW'),
            iconCls: 'button_menu_ext ss_sprite ss_add',
            handler: function () {
            outputDocForm.getForm().reset();
            newOPWindow.show();
            }
        });

  var btnEdit = new Ext.Button({
            id: 'btnEdit',
            text: _('ID_EDIT'),
            iconCls: 'button_menu_ext ss_sprite ss_pencil',
            handler: function (s) {
                var s = outputDocGrid.getSelectionModel().getSelections();
                var outputDocUID = s[0].data.OUT_DOC_UID;
                outputDocForm.form.load({
                    url:'proxyExtjs.php?tid='+outputDocUID+'&action=editOutputDocument',
                    method:'GET',
                    waitMsg:'Loading',
                    success:function(form, action) {
                        newOPWindow.show();
                     //  Ext.MessageBox.alert('Message', 'Loaded OK');
                      //  setTaskAssignType(form);
                    },
                    failure:function(form, action) {
                        PMExt.notify( _('ID_STATUS') , _('ID_LOAD_FAILED') );
                    }
                });
            }

        });

  var btnProperties = new Ext.Button({
            id: 'btnProperties',
            text: _('ID_PROPERTIES'),
            iconCls: 'application_add',
            handler: function (s) {
                outputDocGrid.stopEditing();
                var selectedRow = outputDocGrid.getSelectionModel().getSelections();
                var outDocUID   = selectedRow.data[0].OUT_DOC_UID;
            }
        });

  var tb = new Ext.Toolbar({
            items: [btnAdd, btnRemove,btnEdit,btnProperties]
       });

 var expander = new Ext.ux.grid.RowExpander({
    tpl : new Ext.Template(
        "<p><b>"+TRANSLATIONS.ID_DESCRIPTION+":</b> {OUT_DOC_DESCRIPTION} </p><br><input type='button' value='UID' onclick=workflow.createUIDButton('{OUT_DOC_UID}');> </p>"
    )
  });
  var outputDocColumns = new Ext.grid.ColumnModel({
            columns: [
                expander,
                {
                    id: 'OUT_DOC_TITLE',
                    header: _('ID_TITLE'),
                    dataIndex: 'OUT_DOC_TITLE',
                    width: 280,
                    editable: false,
                    editor: new Ext.form.TextField({
                    //allowBlank: false
                    })
                },
                {
                    id: 'OUT_DOC_TYPE',
                    header: _('ID_TYPE'),
                    dataIndex: 'OUT_DOC_TYPE',
                    editable: false,
                    editor: new Ext.form.TextField({
                    //allowBlank: false
                    })
                }/*,{
                    sortable: false,
                    renderer: function(val, meta, record)
                       {
                            return String.format("<input type='button' value='UID' onclick=workflow.createUIDButton('{0}');>",record.data.OUT_DOC_UID);
                       }
                }*/
            ]
        });


  var outputDocGrid = new Ext.grid.GridPanel({
        store       : outputDocStore,
        id          : 'mygrid',
        loadMask    : true,
        loadingText : 'Loading...',
        //renderTo    : 'cases-grid',
        frame       : false,
        autoHeight  :false,
        clicksToEdit: 1,
        minHeight   :400,
        height      :400,
        layout      : 'fit',
        cm          : outputDocColumns,
        stripeRows  : true,
        plugins: expander,
        tbar        : tb,
        bbar: new Ext.PagingToolbar({
            pageSize: 10,
            store: outputDocStore,
            displayInfo: true,
            displayMsg: 'Displaying Output Document {0} - {1} of {2}',
            emptyMsg: "No Output Document to display",
            items:[]
        }),
        viewConfig  : {forceFit: true}
   });

  var outputDocForm = new Ext.FormPanel({
        monitorValid    :true,
        labelWidth      : 100,
        defaults        :{autoScroll:true},
        width           : 450,
        bodyStyle : 'padding:10px 0 0 10px;',
        items           :[{
                    xtype       : 'fieldset',
                    layout      : 'form',
                    border      :true,
                    title       : _('ID_OUTPUT_INFO'),
                    width       : 450,
                    collapsible : false,
                    labelAlign  : '',
                    items       :[{
                                    xtype       : 'textfield',
                                    fieldLabel  : _('ID_TITLE'),
                                    allowBlank  : false,
                                    width       : 300,
                                    blankText   : 'Enter Title of Output Document',
                                    name        : 'OUT_DOC_TITLE'
                                },{
                                    //xtype: 'fieldset',
                                    layout:'column',
                                    border:false,
                                    items:[{
                                        columnWidth:.6,
                                        layout: 'form',
                                        border:false,
                                        items: [{
                                            xtype       : 'textfield',
                                            //id          : 'filenameGenerated',
                                            fieldLabel  : _('ID_FILENAME_GENERATED'),
                                            name        : 'OUT_DOC_FILENAME',
                                            allowBlank  : false,
                                            width       : 250,
                                            blankText   : 'Select Filename generated',
                                            anchor      : '100%'
                                         }]
                                },{
                                    columnWidth:.4,
                                    layout: 'form',
                                    width       : 200,
                                    border:false,
                                    items: [{
                                            xtype:'button',
                                            title: ' ',
                                            text: '@@',
                                            name: 'selectorigin',
                                             handler: function (s) {
                                                workflow.variablesAction = 'form';
                                                workflow.fieldName         = 'OUT_DOC_FILENAME' ;
                                                workflow.variable        = '@#',
                                                workflow.formSelected    = outputDocForm;
                                                var rowData = ProcMapObj.ExtVariables();
                                                console.log(rowData);
                                        }
                                    }]
                                 }]
                                },{
                                    xtype           : 'textarea',
                                    fieldLabel      : _('ID_DESCRIPTION'),
                                    name            : 'OUT_DOC_DESCRIPTION',
                                    height          : 120,
                                    width           : 300
                                },{
                                    width           :150,
                                    xtype           :'combo',
                                    mode            :'local',
                                    editable        :false,
                                    fieldLabel      :_('ID_ORIENTATION'),
                                    triggerAction   :'all',
                                    forceSelection  : true,
                                    name            :'OUT_DOC_LANDSCAPE',
                                    displayField    :'name',
                                    value           :'Portrait',
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
                                    fieldLabel      :_('ID_MEDIA'),
                                    triggerAction   :'all',
                                    forceSelection  : true,
                                    name            :'OUT_DOC_MEDIA',
                                    displayField    :'name',
                                    value           :'Letter',
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
                                                                            {name : 'Screenshot1024',   value: 'Screenshot1024'}
                                                                           ]
                                                                       })
                                },{
                                                    xtype       : 'numberfield',
                                                    fieldLabel  : _('ID_LEFT_MARGIN'),
                                                    name        : 'OUT_DOC_LEFT_MARGIN',
                                                    width       : 50
                                                },{
                                                    xtype       : 'numberfield',
                                                    fieldLabel  : _('ID_RIGHT_MARGIN'),
                                                    name        : 'OUT_DOC_RIGHT_MARGIN',
                                                    width       : 50
                                                },{
                                                    xtype       : 'numberfield',
                                                    fieldLabel  : _('ID_TOP_MARGIN'),
                                                    name        : 'OUT_DOC_TOP_MARGIN',
                                                    width       : 50
                                                },{
                                                    xtype       : 'numberfield',
                                                    fieldLabel  : _('ID_BOTTOM_MARGIN'),
                                                    name        : 'OUT_DOC_BOTTOM_MARGIN',
                                                    width       : 50
                                                },{
                                                    width           :150,
                                                    xtype           :'combo',
                                                    mode            :'local',
                                                    editable        :false,
                                                    fieldLabel      :_('ID_OUTPUT_GENERATE'),
                                                    triggerAction   :'all',
                                                    forceSelection  :true,
                                                    name            :'OUT_DOC_GENERATE',
                                                    displayField    :'name',
                                                    value           :'Doc',
                                                    valueField      :'value',
                                                    store           :new Ext.data.JsonStore({
                                                    fields          :['name', 'value'],
                                                    data            :[
                                                                    {name : 'BOTH',   value: 'BOTH'},
                                                                    {name : 'DOC',   value: 'DOC'},
                                                                    {name : 'PDF',   value: 'PDF'}]})
                },{
                    width           : 50,
                    xtype           :'combo',
                    mode            :'local',
                    editable        :false,
                    fieldLabel      :_('ID_ENABLE_VERSIONING'),
                    triggerAction   :'all',
                    forceSelection  :true,
                    name            :'OUT_DOC_VERSIONING',
                    displayField    :'name',
                    value           :'NO',
                    valueField      :'value',
                    store           :new Ext.data.JsonStore({
                    fields          : ['name', 'value'],
                    data            : [
                                    {name : 'NO',   value: '0'},
                                    {name : 'YES',   value: '1'}]})
                },{
                    layout      :'column',
                    border      :false,
                    items       :[{
                        columnWidth :.6,
                        layout      : 'form',
                        border      :false,
                        items       : [{
                                xtype       : 'textfield',
                                //id          : 'DestPath',
                                fieldLabel  : _('ID_DESTINATION_PATH'),
                                name        : 'OUT_DOC_DESTINATION_PATH',
                                anchor      :'100%',
                                width       : 250
                        }]
                    },{
                        columnWidth     :.4,
                        layout          : 'form',
                        border          :false,
                        items           : [{
                                xtype           :'button',
                                title           : ' ',
                                text            : '@@',
                                name            : 'selectorigin',
                                 handler: function (s) {
                                                    workflow.variablesAction = 'form';
                                                    workflow.fieldName         = 'OUT_DOC_DESTINATION_PATH' ;
                                                    workflow.variable        = '@@',
                                                    workflow.formSelected    = outputDocForm;
                                                    var rowData = ProcMapObj.ExtVariables();
                                            }
                            }]
                    }]
                },{
                    layout      :'column',
                    border      :false,
                    items       :[{
                        columnWidth :.6,
                        layout      : 'form',
                        border      :false,
                        items       : [{
                        xtype       : 'textfield',
                        //id          :'tags',
                        fieldLabel  : _('ID_TAGS'),
                        name        : 'OUT_DOC_TAGS',
                        anchor      :'100%',
                        width       : 250
                        }]
                    },{
                        columnWidth :.4,
                        layout      : 'form',
                        border      :false,
                        items       : [{
                                xtype       :'button',
                                title       : ' ',
                                text        : '@@',
                                name        : 'selectorigin',
                                handler: function (s) {
                                                workflow.variablesAction = 'form';
                                                workflow.fieldName         = 'OUT_DOC_TAGS' ;
                                                workflow.variable        = '@@',
                                                workflow.formSelected    = outputDocForm;
                                                var rowData = ProcMapObj.ExtVariables();
                                   }
                              }]
                        }]
                     },{
                      id : 'OUT_DOC_UID',
                      xtype: 'hidden',
                      name : 'OUT_DOC_UID'
                  }
                 ]
                  }],
     buttons     : [{
        text        : _('ID_SAVE'),
        formBind    :true,
        handler     : function(){
                var getForm       = outputDocForm.getForm().getValues();
                var sDocUID       = getForm.OUT_DOC_UID;
                var sDocTitle     = getForm.OUT_DOC_TITLE;
                var sFilename     = getForm.OUT_DOC_FILENAME;
                var sDesc         = getForm.OUT_DOC_DESCRIPTION;
                var sLandscape    = getForm.OUT_DOC_LANDSCAPE;
                if(getForm.OUT_DOC_LANDSCAPE == 'Portrait')
                        sLandscape=0;
                if(getForm.OUT_DOC_LANDSCAPE == 'Landscape')
                        sLandscape=1;
                var sMedia        = getForm.OUT_DOC_MEDIA;
                var sLeftMargin   = getForm.OUT_DOC_LEFT_MARGIN;
                var sRightMargin  = getForm.OUT_DOC_RIGHT_MARGIN;
                var sTopMargin    = getForm.OUT_DOC_TOP_MARGIN;
                var sBottomMargin = getForm.OUT_DOC_BOTTOM_MARGIN;
                var sGenerated    = getForm.OUT_DOC_GENERATE;
                var sVersioning   = getForm.OUT_DOC_VERSIONING;
                if(getForm.OUT_DOC_VERSIONING == 'NO')
                        sVersioning=0;
                if(getForm.OUT_DOC_VERSIONING == 'YES')
                        sVersioning=1;
                var sDestPath     = getForm.OUT_DOC_DESTINATION_PATH;
                var sTags         = getForm.OUT_DOC_TAGS;
            if(sDocUID == "")
               {
              Ext.Ajax.request({
                  url   : '../outputdocs/outputdocs_Save.php',
                  method: 'POST',
                  params:{
                      functions                : 'lookForNameOutput',
                      NAMEOUTPUT               : sDocTitle,
                      proUid                   : pro_uid
                  },
                  success: function(response) {
                    if(response.responseText == "1")
                    {
                      Ext.Ajax.request({
                          url   : '../outputdocs/outputdocs_Save.php',
                          method: 'POST',
                          params:{
                              functions                : '',
                              OUT_DOC_UID              : sDocUID,
                              OUT_DOC_TITLE            : sDocTitle,
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
                              OUT_DOC_TAGS             : sTags,
                              PRO_UID                  : pro_uid
                          },
                          success: function(response) {
                              PMExt.notify( _('ID_STATUS') , _('OUTPUT_CREATE') );
                            outputDocStore.reload();
                            newOPWindow.hide();
                          }
                      });

                  }


                  else
                      PMExt.notify( _('ID_STATUS') , _('ID_OUTPUT_NOT_SAVE') );
                      }
        });
               }
         else
               {
             Ext.Ajax.request({
                          url   : '../outputdocs/outputdocs_Save.php',
                          method: 'POST',
                          params:{
                              functions                : '',
                              OUT_DOC_UID              : sDocUID,
                              OUT_DOC_TITLE            : sDocTitle,
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
                              OUT_DOC_TAGS             : sTags,
                              PRO_UID                  : pro_uid
                          },
                          success: function(response) {
                              PMExt.notify( _('ID_STATUS') , _('ID_OUTPUT_UPDATE') );
                              outputDocStore.reload();
                            newOPWindow.hide();
                          }
                      });

               }
        }
       },{
            text: _('ID_CANCEL'),
            handler: function(){
                // when this button clicked,
                newOPWindow.hide();
            }
        }],
     buttonAlign : 'center'
       });

  var newOPWindow = new Ext.Window({
        title       : _('ID_OUTPUT_DOCUMENTS'),
        width       : 500,
        defaults    :{autoScroll:true},
        height      : 420,
        minWidth    : 200,
        minHeight   : 150,
        layout      : 'fit',
        plain       : true,
        items       : outputDocForm,
        buttonAlign : 'center'
    });

 var gridWindow = new Ext.Window({
        title       : _('ID_OUTPUT_DOCUMENTS'),
        collapsible : false,
        maximizable : false,
        width       : 550,
        defaults    :{autoScroll:true},
        height      : 420,
        minWidth    : 200,
        minHeight   : 150,
        layout      : 'fit',
        plain       : true,
        items       : outputDocGrid,
        buttonAlign : 'center'
     });
 gridWindow.show();
}

ProcessOptions.prototype.addReportTable= function(_5625)
{
  var reportFields = Ext.data.Record.create([
            {
                name:'REP_TAB_UID',
                type: 'string'
            },
            {
                name: 'REP_TAB_TITLE',
                type: 'string'
            },
            {
                name: 'FIELD_NAME',
                type: 'string'
            },
            {
                name: 'FIELD_UID',
                type: 'string'
            }
       ]);

 var editor = new Ext.ux.grid.RowEditor({
            saveText: _('ID_UPDATE')
        });
        
 var reportStore = new Ext.data.JsonStore({
            root         : 'data',
            totalProperty: 'totalCount',
            idProperty   : 'gridIndex',
            remoteSort   : true,
            fields       : reportFields,
            proxy        : new Ext.data.HttpProxy({
                           url : 'proxyExtjs?pid='+pro_uid+'&action=getReportTables'
                           })
 });
  reportStore.load({params:{start : 0 , limit : 10 }});

 var reportTableTypeStore = new Ext.data.JsonStore({
            root         : 'data',
            totalProperty: 'totalCount',
            idProperty   : 'gridIndex',
            remoteSort   : true,
            fields       : reportFields,
            proxy        : new Ext.data.HttpProxy({
                           url : 'proxyExtjs?pid='+pro_uid+'&type=NORMAL&action=getReportTableType'
                           })
 });
  reportTableTypeStore.load();

 var expander = new Ext.ux.grid.RowExpander({
    tpl : new Ext.Template(
        " <p><input type='button' value='UID' onclick=workflow.createUIDButton('{REP_TAB_UID}');> </p>"
    )
  });

  var reportColumns = new Ext.grid.ColumnModel({
            columns: [
                expander,
                {
                    id: 'REP_TAB_TITLE',
                    header: _('ID_TITLE'),
                    dataIndex: 'REP_TAB_TITLE',
                    width: 380,
                    editable: false,
                    editor: new Ext.form.TextField({
                        //allowBlank: false
                    })
                }
            ]
  });

  var btnAdd = new Ext.Button({
            id: 'btnAdd',
            text: _('ID_NEW'),
            iconCls: 'button_menu_ext ss_sprite ss_add',
            handler: function () {
                formWindow.show();
                reportForm.getForm().reset();
            }
  });

   var btnEdit = new Ext.Button({
            id: 'btnEdit',
            text: _('ID_EDIT'),
            iconCls: 'button_menu_ext ss_sprite ss_pencil',
            handler: function (s) {
                var s = reportGrid.getSelectionModel().getSelections();
                var repTabUID = s[0].data.REP_TAB_UID;
                reportForm.form.load({
                url:'proxyExtjs.php?REP_TAB_UID='+repTabUID+'&action=editReportTables',
                    method:'GET',
                    waitMsg:'Loading',
                    success:function(form, action) {
                        formWindow.show();
                      //Ext.MessageBox.alert('Message', 'Loaded OK');
                     // setTaskAssignType(form);
                    },
                    failure:function(form, action) {
                        PMExt.notify( _('ID_STATUS') , _('ID_LOAD_FAILED') );
                    }
                });
            }
  });


  var btnRemove = new Ext.Button({
            id: 'btnRemove',
            text: _('ID_DELETE'),
            iconCls: 'button_menu_ext ss_sprite ss_delete',
            handler: function () {
                editor.stopEditing();
                var s = reportGrid.getSelectionModel().getSelections();
                for(var i = 0, r; r = s[i]; i++){

                    //if REP_TAB_UID is properly defined (i.e. set to valid value) then only delete the row
                    //else its a BLANK ROW for which Ajax should not be called.
                    if(r.data.REP_TAB_UID != "")
                    {
                        Ext.Ajax.request({
                          url   : '../reportTables/reportTables_Delete.php',
                          method: 'POST',
                          params: {
                                REP_TAB_UID    : r.data.REP_TAB_UID
                          },
                          success: function(response) {
                                PMExt.notify( _('ID_STATUS') , _('ID_REPORT_REMOVED') );
                                //Secondly deleting from Grid
                                reportGrid.remove(r);
                                //Reloading store after deleting report table
                                reportStore.reload();
                              }
                     });
                    }
                }
            }
  });


  var tb = new Ext.Toolbar({
            items: [btnAdd,btnRemove, btnEdit]
        });
       

  var reportGrid = new Ext.grid.GridPanel({
        store       : reportStore,
        id          : 'mygrid',
        loadMask    : true,
        //loadingText : 'Loading...',
        //renderTo    : 'cases-grid',
        frame       : false,
        autoHeight  :false,
        clicksToEdit: 1,
        width       :420,
        height      :400,
        layout      : 'fit',
        plugins: expander,
        cm          : reportColumns,
        stripeRows: true,
        tbar: tb,
        bbar: new Ext.PagingToolbar({
            pageSize: 10,
            store: reportStore,
            displayInfo: true,
            displayMsg: 'Displaying Report Tables {0} - {1} of {2}',
            emptyMsg: "No Report Tables to display",
            items:[]
        }),
        viewConfig: {forceFit: true}
   });

  var gridWindow = new Ext.Window({
        title       : _('ID_REPORT_TABLES'),
        collapsible : false,
        maximizable : false,
        width       : 420,
        defaults    :{autoScroll:true},
        height      : 450,
        minWidth    : 200,
        minHeight   : 150,
        layout      : 'fit',
        plain       : true,
        items       : reportGrid,
        buttonAlign : 'center'
     });
 gridWindow.show();

var reportForm =new Ext.FormPanel({
      collapsible: false,
      maximizable: true,
      width:450,
      height:325,
      frame:false,
      monitorValid : true,
      plain: true,
      bodyStyle : 'padding:10px 0 0 10px;',
      buttonAlign: 'center',
      items:[{
                              xtype: 'textfield',
                              fieldLabel: _('ID_TITLE'),
                              width: 250,
                              name: 'REP_TAB_TITLE',
                               allowBlank: false
                          },{

                              xtype: 'textfield',
                              fieldLabel: _('ID_TABLE_NAME'),
                              width: 250,
                              name: 'REP_TAB_NAME',
                               allowBlank: false
                          },
                          {
                              xtype: 'combo',
                              width:  250,
                              mode: 'local',
                              editable:false,
                              fieldLabel: _('ID_TYPE'),
                              triggerAction: 'all',
                              forceSelection: true,
                              name: 'REP_TAB_TYPE',
                              displayField:  'name',
                              valueField   : 'value',
                              value        : 'Global',
                              store: new Ext.data.JsonStore({
                                     fields : ['name', 'value'],
                                     data   : [
                                                {name : 'Global', value: 'NORMAL'},
                                                {name : 'Grid',   value: 'GRID'}
                                             ]}),
                              onSelect: function(record, index) {
                                        //Show-Hide Format Type Field
                                        if(record.data.value == 'NORMAL')
                                                {
                                                    Ext.getCmp("fields").show();
                                                    Ext.getCmp("gridfields").hide();
                                                }
                                        else
                                             {
                                                Ext.getCmp("gridfields").show();
                                                Ext.getCmp("fields").hide();
                                             }
                                        var link = 'proxyExtjs?pid='+pro_uid+'&type='+record.data.value+'&action=getReportTableType';
                                        reportTableTypeStore.proxy.setUrl(link, true);
                                        reportTableTypeStore.load();

                                        this.setValue(record.data[this.valueField || this.displayField]);
                                        this.collapse();
                      }
                      },
                      {
                          xtype: 'fieldset',
                          id:    'fields',
                          border:false,
                          hidden: false,
                          items: [{
                                  xtype: 'multiselect',
                                  width:  240,
                                  height: 150,
                                  mode: 'local',
                                  style : 'margin-bottom:10px',
                                  editable:true,
                                  fieldLabel: _('ID_FIELDS'),
                                  triggerAction: 'all',
                                  allowblank: true,
                                  forceSelection: false,
                                  dataIndex : 'FIELD_NAME',
                                  name: 'FIELDS',
                                  valueField: 'FIELD_UID',
                                  displayField: 'FIELD_NAME',
                                  store: reportTableTypeStore
                                 }]
          }, {
                 xtype: 'fieldset',
                 id: 'gridfields',
                 border:false,
                 hidden: true,
                 align:'left',
                  items:[{
                                  xtype: 'combo',
                                  width:  200,
                                  mode: 'local',
                                  editable:false,
                                  fieldLabel: _('ID_GRID_FIELDS'),
                                  triggerAction: 'all',
                                  forceSelection: true,
                                  displayField:   'name',
                                  valueField:     'value',
                                  name: 'REP_TAB_GRID',
                                  store: new Ext.data.JsonStore({
                                         fields : ['name', 'value'],
                                         data   :  []
                                     })
                       }]
          }
      ], buttons: [{
            text: _('ID_SAVE'),
            formBind    :true,
            handler: function(){
                var getForm         = reportForm.getForm().getValues();
                //var pro_uid         = getForm.PRO_UID;
                var tableUID        = getForm.REP_TAB_UID;
                var Title           = getForm.REP_TAB_TITLE;
                var Name            = getForm.REP_TAB_NAME;
                var Type            = getForm.REP_TAB_TYPE;
                if(Type == 'Global')
                    Type = 'NORMAL';
                else
                    Type = 'GRID';

                var Grid            = getForm.REP_TAB_GRID;
                var Fields          = getForm.FIELDS;

            if(typeof tableUID=='undefined')
               {
                Ext.Ajax.request({
                  url   : '../reportTables/reportTables_Save.php',
                  method: 'POST',
                  params:{
                      PRO_UID         :pro_uid,
                      REP_TAB_UID     :'',
                      REP_TAB_TITLE   :Title,
                      REP_TAB_NAME    :Name,
                      REP_TAB_TYPE    :Type ,
                      REP_TAB_GRID    :Grid,
                      FIELDS          :Fields
                  },
                  success: function(response) {
                       PMExt.notify( _('ID_STATUS') , _('ID_REPORT_SAVE') );
                      }
                });
               }
                    else
                         {
                Ext.Ajax.request({
                  url   : '../reportTables/reportTables_Edit.php',
                  method: 'POST',
                  params:{
                      PRO_UID         :pro_uid,
                      REP_TAB_UID     :tableUID,
                      REP_TAB_TITLE   :Title,
                      REP_TAB_NAME    :Name,
                      REP_TAB_TYPE    :Type ,
                      REP_TAB_GRID    :Grid,
                      FIELDS          :Fields,
                      //REP_VAR_NAME    : VariableName,
                      //REP_VAR_TYPE    : VariableType,
                      REP_TAB_CONNECTION: Connection
                  },
                  success: function(response) {
                      PMExt.notify( _('ID_STATUS') , _('ID_REPORT_EDITED') );
                     }


                });
                    }
            formWindow.hide();
            reportStore.reload();

          }
        },{
            text: _('ID_CANCEL'),
            handler: function(){
                // when this button clicked,
                formWindow.hide();
          }
        }]
  })

var formWindow = new Ext.Window({
        title: _('ID_NEW_REPORT_TABLE'),
        collapsible: false,
        maximizable: true,
        width: 400,
        //autoHeight: true,
        height: 330,
        layout: 'fit',
        plain: true,
        buttonAlign: 'center',
        items: reportForm
    });
   //gridWindow.show();
}

ProcessOptions.prototype.addTriggers= function()
{
  
  

}
