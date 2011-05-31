var workflow = {};

var ProcessOptions = function(id){
  //Workflow.call(this,id);
};

//ProcessOptions.prototype=new Workflow;
//ProcessOptions.prototype.type="ProcessOptions";

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
    id: 'btnEdit',
    text: _('ID_NEW'),
    iconCls: 'button_menu_ext ss_sprite ss_add',
    //iconCls: 'application_add',
    handler: function () {
      dynaformDetails.getForm().reset();
      dynaformDetails.getForm().items.items[0].focus('',200); 
      dynaformDetails.getForm().items.items[1].setValue('normal'); 
      formWindow.show();
    }
  });

  //edit dynaform Function 
  var editDynaform = function() {
    var rowSelected = Ext.getCmp('dynaformGrid').getSelectionModel().getSelected();

    if( rowSelected ) {
      //location.href = '../dynaforms/dynaforms_Editor?PRO_UID='+pro_uid+'&DYN_UID='+rowSelected.data.DYN_UID+'&bpmn=1'
      var url = 'dynaforms/dynaforms_Editor?PRO_UID='+pro_uid+'&DYN_UID='+rowSelected.data.DYN_UID+'&bpmn=1';
      Ext.getCmp('mainTabPanel')._addTabFrame(rowSelected.data.DYN_UID, rowSelected.data.DYN_TITLE, url);
    } else
      PMExt.error('', _('ID_NO_SELECTION_WARNING'));
  }

  var removeDynaform = function() {
    ids = Array();
    
    editor.stopEditing();
    var rowsSelected = Ext.getCmp('dynaformGrid').getSelectionModel().getSelections();

    if( rowsSelected.length == 0 ) {
      PMExt.error('', _('ID_NO_SELECTION_WARNING'));
      return false;
    }

    for(i=0; i<rowsSelected.length; i++)
      ids[i] = rowsSelected[i].get('DYN_UID');

    ids = ids.join(',');

    //First check whether selected Dynaform is assigned to a task steps or not.
    Ext.Ajax.request({
      url   : '../dynaforms/dynaforms_Delete',
      method: 'POST',
      params: {
        functions : 'getDynaformTaskRelations',
        PRO_UID   : pro_uid,
        DYN_UID   : ids
      },
      success: function(response) {
        var result = Ext.util.JSON.decode(response.responseText);
        if( result.success ) {
          if( result.passed ) {
            //Second check whether selected Dynaform is assigned to a processes supervisors or not.
            Ext.Ajax.request({
              url   : '../dynaforms/dynaforms_Delete.php',
              method: 'POST',
              params: {
                functions      : 'getDynaformSupervisorRelations',
                DYN_UID        : ids
              },
              success: function(response) {
                var result = Ext.util.JSON.decode(response.responseText);
                if( result.success ){
                  if( result.passed ) { //deleting the selected dyanoforms
                    PMExt.confirm(_('ID_CONFIRM'), _('ID_DELETE_DYNAFORM_CONFIRM'), function(){
                      Ext.Ajax.request({
                        url   : '../dynaforms/dynaforms_Delete.php',
                        method: 'POST',
                        params: {
                          functions      : 'removeDynaform',
                          DYN_UID        : ids
                        },
                        success: function(response) {
                          var result = Ext.util.JSON.decode(response.responseText);
                          if( result.success ){
                            PMExt.notify( _('ID_STATUS') , result.msg);

                            //Reloading store after deleting dynaform
                            taskDynaform.reload();
                          } else {
                            PMExt.error(_('ID_ERROR'), result.msg);
                          }
                        }
                      });
                    });
                  } else {
                    PMExt.error(_('ID_VALIDATION_ERROR'), result.msg);
                  }
                } else {
                  PMExt.error(_('ID_ERROR'), result.msg);
                }
              }
            });
          } else {
            PMExt.error(_('ID_VALIDATION_ERROR'), result.msg);
          }
        } else {
          PMExt.error(_('ID_ERROR'), result.msg);
        }
      }
    });
  }

  //edit dynaform button
  var btnEdit = new Ext.Button({
    id: 'btnAdd',
    text: _('ID_EDIT'),
    iconCls: 'button_menu_ext ss_sprite  ss_pencil',
    handler: editDynaform
  });

  var btnRemove = new Ext.Button({
    id: 'btnRemove',
    text: _('ID_DELETE'),
    iconCls: 'button_menu_ext ss_sprite ss_delete',
    handler: removeDynaform
  });

  var tb = new Ext.Toolbar({
    items: [btnAdd, btnEdit, btnRemove]
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
      url: 'bpmn/proxyExtjs?pid='+pro_uid+'&action=getDynaformList'
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
      url: 'bpmn/proxyExtjs?action=getAdditionalTables'
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
    tpl : new Ext.Template("<p><b>"+TRANSLATIONS.ID_DESCRIPTION+":</b> {DYN_DESCRIPTION}</p></p>")
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
      }
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
    id : 'dynaformGrid',
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

  //connecting context menu  to grid
  dynaformGrid.addListener('rowcontextmenu', onDynaformsContextMenu,this);
  dynaformGrid.addListener('rowdblclick', editDynaform,this);

  //by default the right click is not selecting the grid row over the mouse
  //we need to set this four lines
  dynaformGrid.on('rowcontextmenu', function (grid, rowIndex, evt) {
    var sm = grid.getSelectionModel();
    sm.selectRow(rowIndex, sm.isSelected(rowIndex));
  }, this);

  //prevent default
  dynaformGrid.on('contextmenu', function (evt) {
      evt.preventDefault();
  }, this);

  function onDynaformsContextMenu(grid, rowIndex, e) {
    e.stopEvent();
    var coords = e.getXY();
    dynaformsContextMenu.showAt([coords[0], coords[1]]);
  }

  var dynaformsContextMenu = new Ext.menu.Menu({
    id: 'messageContextMenu',
    items: [{
        text: _('ID_EDIT'),
        iconCls: 'button_menu_ext ss_sprite  ss_pencil',
        handler: editDynaform
      },{
        text: _('ID_DELETE'),
        icon: '/images/delete.png',
        handler: removeDynaform
      },{
        text: _('ID_UID'),
        handler: function(){
          var rowSelected = Ext.getCmp('dynaformGrid').getSelectionModel().getSelected();
          workflow.createUIDButton(rowSelected.data.DYN_UID);
        }
      } 
    ]
  });

  

  var dynaformDetails = new Ext.FormPanel({
    labelWidth  : 100,
    buttonAlign : 'center',
    width       : 490,
    height      : 420,
    bodyStyle : 'padding:10px 0 0 10px;',
    autoHeight: true,
    items:
      [
//      {
//        xtype: 'fieldset',
//        layout: 'fit',
//        border:true,
//        title: _('ID_SELECT_DYNAFORM'),
//        width: 500,
//        collapsible: false,
//        labelAlign: 'top',
//        items:[{
//          xtype: 'radiogroup',
//          //id:    'dynaformType',
//          layout: 'fit',
//          fieldLabel: _('ID_TYPE'),
//          itemCls: 'x-check-group-alt',
//          columns: 1,
//          items: [
//            {
//              boxLabel: _('ID_BLANK_DYNAFORM'),
//              name: 'DYN_SOURCE',
//              inputValue: 'blankDyna',
//              checked: true
//            },
//            {
//              boxLabel: _('ID_PM_DYNAFORM'),
//              name: 'DYN_SOURCE',
//              inputValue: 'pmTableDyna'
//            }],
//          listeners: {
//          change: function(radiogroup, radio) {
//          if(radio.inputValue == 'blankDyna')
//          {
//            Ext.getCmp("blankDynaform").show();
//            var f = form.findField('yourField');
//           f.container.up('div.x-form-item').hide();
//          }
//          else
//          {
//            Ext.getCmp("blankDynaform").hide();
//            Ext.getCmp("pmTableDynaform").show();
//          }
//        }
//      }
//      }]
//      },
      
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
              width          : 350,
              xtype          : 'combo',
              allowBlank     : false,
              mode           : 'local',
              editable       : false,
              fieldLabel     : _('ID_TYPE'),
              triggerAction  :  'all',
              forceSelection : true,
              name           : 'DYN_TYPE',
              valueField     : 'value',
              displayField   : 'name',
              value          : 'normal',
              store          : new Ext.data.JsonStore({
                fields : ['value', 'name'],
                data   : [
                  {value: 'normal', name : _('ID_NORMAL')},
                  {value: 'grid',   name : _('ID_GRID')}
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
      }
//      ,{
//          xtype: 'fieldset',
//          id:    'pmTableDynaform',
//          border:true,
//          hidden: true,
//          title: 'Dynaform Information',
//          width: 500,
//          items:[{
//                  width:          350,
//                  xtype:          'combo',
//                  mode:           'local',
//                  editable:       true,
//                  triggerAction:  'all',
//                  forceSelection: true,
//                  fieldLabel:     _('ID_CREATE_PM_TABLE'),
//                  emptyText    : 'Select Table',
//                  displayField:   'ADD_TAB_NAME',
//                  valueField:     'ADD_TAB_UID',
//                  value        : '---------------------------',
//                  store        : additionalTables,
//                  onSelect: function(record, index){
//                      var link = 'bpmn/proxyExtjs?tabId='+record.data.ADD_TAB_UID+'&action=getPMTableDynaform';
//                      tablesFieldsStore.proxy.setUrl(link, true);
//                      tablesFieldsStore.load();
//
//                      Ext.getCmp("fieldsGrid").show();
//                      Ext.getCmp("pmTable").setValue(record.data.ADD_TAB_UID);
//
//                      this.setValue(record.data[this.valueField || this.displayField]);
//                      this.collapse();
//                   }
//               },{
//                  xtype:'hidden',//<--hidden field
//                  name:'ADD_TABLE',
//                  id  :'pmTable'
//               },
//               {
//                  xtype     : 'textfield',
//                  fieldLabel: _('ID_TITLE'),
//                  name      : 'DYN_TITLE2',
//                  allowBlank: false,
//                  width     : 350
//               },{
//                  xtype     : 'textarea',
//                  fieldLabel: _('ID_DESCRIPTION'),
//                  name      : 'DYN_DESCRIPTION2',
//                  height    : 120,
//                  width     : 350
//               },
//               {
//                  xtype: 'grid',
//                  id:'fieldsGrid',
//                  hidden: true,
//                  store: tablesFieldsStore,
//                  cm: addTableColumns,
//                  width: 500,
//                  //height: 300,
//                  autoHeight: true,
//                  clicksToEdit: 1,
//                  plugins: [editor],
//                  //loadMask    : true,
//                  loadingText : 'Loading...',
//                  border: false
//                  //renderTo : Ext.getBody()
//               }
//           ]
//      }
        ], buttons: [{
        text: _('ID_SAVE'),
        handler: function(){
          var getForm       = dynaformDetails.getForm().getValues();
          //var sDynaformType = getForm.DYN_TYPE;
          var sDynaformType  = dynaformDetails.getForm().items.items[1].getValue(); 
          if ( sDynaformType == 'normal' || sDynaformType == '' )
            sDynaformType = 'xmlform';
          else
            sDynaformType = 'grid';

//          if ( getForm.DYN_SOURCE == 'blankDyna')
//          {
              var sTitle    = getForm.DYN_TITLE1;
              var sDesc     = getForm.DYN_DESCRIPTION1;
//          }
//          else 
//          {
//            var sAddTab     = getForm.ADD_TABLE;
//            var aStoreFields  = tablesFieldsStore.data.items;
//            var fName = new Array();
//            var pVar = new Array();
//            for(var i=0;i<aStoreFields.length;i++) {
//              fName[i] = aStoreFields[i].data.FLD_NAME;
//              pVar[i]  = aStoreFields[i].data.PRO_VARIABLE;
//            }
//            var fieldname = Ext.util.JSON.encode(fName);
//            var variable = Ext.util.JSON.encode(pVar);
//            sTitle    = getForm.DYN_TITLE2;
//            sDesc     = getForm.DYN_DESCRIPTION2;
//          }

          if(sTitle == '')
            PMExt.error( _('ID_ERROR') , _('ID_DYNAFORM_TITLE_REQUIRED') );
          else
          {
            Ext.Ajax.request({
              url   : 'dynaforms/dynaforms_Save.php',
              method: 'POST',
              params:{
                  functions       : 'saveDynaform',
                  ACTION          : 'normal',
//                  FIELDS          : fieldname,
//                  VARIABLES       : variable,
//                  ADD_TABLE       : sAddTab,
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
    height: 350,
    layout: 'fit',
    plain: true,
    buttonAlign: 'center',
    items: dynaformGrid
  });

  var formWindow = new Ext.Window({
    title: _('ID_NEW_DYNAFORM'),
    autoScroll  : true,
    collapsible : false,
    maximizable : true,
    width: 550,
    height: 310,
    defaults    :{autoScroll:true},
    //autoHeight: true,
    //height: 500,
    layout: 'fit',
    plain: true,
    buttonAlign : 'center',
    items: dynaformDetails       
   });
   gridWindow.show();
}

/*
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

  var editor = new Ext.ux.grid.RowEditor({
            saveText: _('ID_UPDATE')
        });

  var btnNew = new Ext.Button({
            id: 'btnNew',
            text: _('ID_NEW'),
            iconCls: 'button_menu_ext ss_sprite ss_add',
            handler: function () {
                dbconnForm.getForm().reset();
                formWindow.show();
            }
  });

  //edit report table Function
  var editDBConn = function() {
      editor.stopEditing();
      var rowSelected  = Ext.getCmp('dbConnGrid').getSelectionModel().getSelections();
      if( rowSelected.length == 0 ) {
           PMExt.error('', _('ID_NO_SELECTION_WARNING'));
           return false;
      }
      var dbConnUID = rowSelected[0].get('DBS_UID');
      dbconnForm.form.load({
                        url   :'bpmn/proxyExtjs.php?pid='+pro_uid+'&dbs_uid='+dbConnUID+'&action=editDatabaseConnection',
                        method: 'GET',
                        waitMsg:'Loading',
                        success:function(form, action) {
                           //Ext.MessageBox.alert('Message', 'Loaded OK');
                           Ext.getCmp("encode").show();
                           form.findField('DBS_ENCODE').setValue(action.result.data.DBS_ENCODE);
                           formWindow.show();
                           //Ext.getCmp("DBS_UID").setValue(dbConnUID);
                        },
                        failure:function(form, action) {
                            PMExt.notify( _('ID_STATUS') , _('ID_LOAD_FAILED') );
                        }
                     });
   }

   var removeDBConn = function(){
    ids = Array();

    editor.stopEditing();
    var rowsSelected = Ext.getCmp('dbConnGrid').getSelectionModel().getSelections();

    if( rowsSelected.length == 0 ) {
      PMExt.error('', _('ID_NO_SELECTION_WARNING'));
      return false;
    }

    for(i=0; i<rowsSelected.length; i++)
      ids[i] = rowsSelected[i].get('DBS_UID');

    ids = ids.join(',');

    PMExt.confirm(_('ID_CONFIRM'), _('ID_DELETE_DBCONNECTION_CONFIRM'), function(){
                      Ext.Ajax.request({
                          url   : '../dbConnections/dbConnectionsAjax.php',
                          method: 'POST',
                          params: {
                             action        :'deleteDbConnection',
                             dbs_uid   : ids
                          },
                        success: function(response) {
                          var result = Ext.util.JSON.decode(response.responseText);
                          if( result.success ){
                            PMExt.notify( _('ID_STATUS') , result.msg);

                            //Reloading store after deleting input document
                            dbStore.reload();
                          } else {
                            PMExt.error(_('ID_ERROR'), result.msg);
                          }
                        }
                      });
                    });
    }

    //edit report table button
  var btnEdit = new Ext.Button({
    id: 'btnEdit',
    text: _('ID_EDIT'),
    iconCls: 'button_menu_ext ss_sprite  ss_pencil',
    handler: editDBConn
  });

  var btnRemove = new Ext.Button({
    id: 'btnRemove',
    text: _('ID_DELETE'),
    iconCls: 'button_menu_ext ss_sprite ss_delete',
    handler: removeDBConn
  });

  var tb = new Ext.Toolbar({
        items: [btnNew,btnEdit, btnRemove]
    });

  var dbStore = new Ext.data.JsonStore({
            root         : 'data',
            totalProperty: 'totalCount',
            idProperty   : 'gridIndex',
            remoteSort   : true,
            fields       : dbConnFields,
            proxy: new Ext.data.HttpProxy({
              url: 'bpmn/proxyExtjs.php?pid='+pro_uid+'&action=getDatabaseConnectionList'
            })
          });
  dbStore.load({params:{start : 0 , limit : 10 }});

  var expander = new Ext.ux.grid.RowExpander({
    tpl : new Ext.Template(
        "<p><b>"+TRANSLATIONS.ID_DESCRIPTION+":</b> {DBS_DESCRIPTION} </p>"
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
                            })
                    }
                ]
     });

  var dbGrid = new Ext.grid.GridPanel({
        store: dbStore,
        id : 'dbConnGrid',
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

   //connecting context menu  to grid
  dbGrid.addListener('rowcontextmenu', ondbGridContextMenu,this);

  //by default the right click is not selecting the grid row over the mouse
  //we need to set this four lines
  dbGrid.on('rowcontextmenu', function (grid, rowIndex, evt) {
    var sm = grid.getSelectionModel();
    sm.selectRow(rowIndex, sm.isSelected(rowIndex));
  }, this);

  //prevent default
  dbGrid.on('contextmenu', function (evt) {
      evt.preventDefault();
  }, this);

  function ondbGridContextMenu(grid, rowIndex, e) {
    e.stopEvent();
    var coords = e.getXY();
    dbGridContextMenu.showAt([coords[0], coords[1]]);
  }

  var dbGridContextMenu = new Ext.menu.Menu({
    id: 'messageContextMenu',
    items: [{
        text: _('ID_EDIT'),
        iconCls: 'button_menu_ext ss_sprite  ss_pencil',
        handler: editDBConn
      },{
        text: _('ID_DELETE'),
        icon: '/images/delete.png',
        handler: removeDBConn
      },{
        text: _('ID_UID'),
        handler: function(){
          var rowSelected = Ext.getCmp('dbConnGrid').getSelectionModel().getSelected();
          workflow.createUIDButton(rowSelected.data.DBS_UID);
        }
      }
    ]
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
                                        {
                                         Ext.getCmp("encode").show();
                                         Ext.getCmp("postgre").hide();
                                         dbconnForm.getForm().findField('DBS_PORT').setValue('3306');
                                         }
                                else if(record.data.value == 'PostGreSql')
                                        {
                                         Ext.getCmp("postgre").show();
                                         Ext.getCmp("encode").hide();
                                         dbconnForm.getForm().findField('DBS_PORT').setValue('5432');
                                         }
                                else
                                        {
                                         Ext.getCmp("sqlserver").show();
                                         Ext.getCmp("postgre").hide();
                                         dbconnForm.getForm().findField('DBS_PORT').setValue('1433');
                                         }
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
                        //id:'port',
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
    height: 350,
    //layout: 'fit',
    plain: true,
    buttonAlign: 'center',
    items: dbGrid
  });
  gridWindow.show();
}
*/

ProcessOptions.prototype.addInputDoc= function(_5625)
{
  var gridWidow; 
  var inputDocGrid;
  var inputDocStore;
  var expander;
  var inputDocColumns;
  var render_version;
  var newButton;
  var editButton;
  var deleteButton;
  var saveButton;
  var cancelButton;
  var smodel;
  var bbarpaging;
  var idocsContextMenu;
  var newIDocWindow;
  var inputDocForm;
  
  //Renderer for Versioning Field
  render_version = function(value){
    var out = '';
    switch(value){
      case '0': out = 'No'; break;
      case '1': out = 'Yes'; break;
    }
    return out;
  }
  
  
  
  newButton = new Ext.Action({
    text : _('ID_NEW'),
    iconCls: 'button_menu_ext ss_sprite ss_add',
    handler: function(){
      inputDocForm.getForm().reset();
      Ext.getCmp('idoc_FORM_NEEDED').setValue('VIRTUAL');
      Ext.getCmp('idoc_VERSIONING').setValue('0');
      inputDocForm.getForm().findField('INP_DOC_TAGS').setValue('INPUT');
      inputDocForm.getForm().findField('PRO_UID').setValue(pro_uid);
      newIDocWindow.setTitle(_('ID_NEW_INPUTDOCS'));
      newIDocWindow.show();
    }
  });
  
  editButton = new Ext.Action({
    text : _('ID_EDIT'),
    iconCls: 'button_menu_ext ss_sprite ss_pencil',
    disabled: true,
    handler: function(){
      Ext.getCmp('designerTab').getEl().mask(_('ID_PROCESSING'));
      rowselected = inputDocGrid.getSelectionModel().getSelected();
      Ext.Ajax.request({
        url: 'processOptionsProxy/loadInputDoc',
        params: {IDOC_UID: rowselected.data.INP_DOC_UID},
        success: function(r,o){
          Ext.getCmp('designerTab').getEl().unmask();
          var res = Ext.decode(r.responseText);
          if (res.success){
            inputDocForm.getForm().reset();
            Ext.getCmp('idoc_FORM_NEEDED').setValue(res.data.INP_DOC_FORM_NEEDED);
            if (res.data.INP_DOC_FORM_NEEDED != 'VIRTUAL'){
              Ext.getCmp('formType').setValue(res.data.INP_DOC_ORIGINAL);
              Ext.getCmp("formType").enable();
            }
            Ext.getCmp('idoc_VERSIONING').setValue(res.data.INP_DOC_VERSIONING);
            inputDocForm.getForm().findField('INP_DOC_TITLE').setValue(res.data.INP_DOC_TITLE);
            inputDocForm.getForm().findField('INP_DOC_DESCRIPTION').setValue(res.data.INP_DOC_DESCRIPTION);
            inputDocForm.getForm().findField('INP_DOC_DESTINATION_PATH').setValue(res.data.INP_DOC_DESTINATION_PATH);
            inputDocForm.getForm().findField('INP_DOC_TAGS').setValue(res.data.INP_DOC_TAGS);
            inputDocForm.getForm().findField('INP_DOC_UID').setValue(res.data.INP_DOC_UID);
            inputDocForm.getForm().findField('PRO_UID').setValue(pro_uid);
            newIDocWindow.setTitle(_('ID_EDIT_INPUTDOCS'));
            newIDocWindow.show();
          }else{
            PMExt.notify(_('ID_REQUEST_DOCUMENTS'),res.msg);
          }
        },
        failure: function(r,o){
          Ext.getCmp('designerTab').getEl().unmask();
          PMExt.notify( _('ID_STATUS') , _('ID_LOAD_FAILED'));
        }
      });
    }
  });
  
  deleteButton = new Ext.Action({
    text : _('ID_DELETE'),
    iconCls: 'button_menu_ext ss_sprite ss_delete',
    disabled: true,
    handler : function(){
      Ext.getCmp('designerTab').getEl().mask(_('ID_PROCESSING'));
      rowselected = inputDocGrid.getSelectionModel().getSelected();
      Ext.Ajax.request({
        url: 'processOptionsProxy/canDeleteInputDoc',
        params: {PRO_UID: pro_uid, IDOC_UID: rowselected.data.INP_DOC_UID},
        success: function(r,o){
          Ext.getCmp('designerTab').getEl().unmask();
          var res = Ext.decode(r.responseText);
          if (res.success){
            Ext.Msg.confirm(_('ID_CONFIRM'),_('ID_CONFIRM_DELETE_INPUT_DOC'), function(btn, text){
              if (btn=='yes'){
                Ext.getCmp('designerTab').getEl().mask(_('ID_PROCESSING'));
                Ext.Ajax.request({
                  url: 'processOptionsProxy/deleteInputDoc',
                  params: {PRO_UID: pro_uid, IDOC_UID: rowselected.data.INP_DOC_UID},
                  success: function(r,o){
                    Ext.getCmp('designerTab').getEl().unmask();
                    var resp = Ext.decode(r.responseText);
                    if (resp.success){
                      editButton.disable();
                      deleteButton.disable();
                      inputDocGrid.store.load();
                      PMExt.notify(_('ID_REQUEST_DOCUMENTS'),resp.msg);
                    }else{
                      PMExt.error(_('ID_ERROR'), resp.msg);
                    }
                  },
                  failure: function(r,o){
                    Ext.getCmp('designerTab').getEl().unmask();
                    PMExt.notify( _('ID_STATUS') , _('ID_LOAD_FAILED'));
                  }
                  
                });
              }
            });
          }else{
            PMExt.warning(_('ID_REQUEST_DOCUMENTS'),_('ID_MSG_CANNOT_DELETE_INPUT_DOC'));
          }
        },
        failure: function(r,o){
          Ext.getCmp('designerTab').getEl().unmask();
          PMExt.notify( _('ID_STATUS') , _('ID_LOAD_FAILED'));
        }
      });
    }
  });
  
  saveButton = new Ext.Action({
    text : _('ID_SAVE'),
    disabled: false,
    handler: function(){
      Ext.getCmp('designerTab').getEl().mask(_('ID_PROCESSING'));
      inputDocForm.getForm().submit({
        success: function(f,a){
          Ext.getCmp('designerTab').getEl().unmask();
          var resp = Ext.decode(a.response.responseText);
          if (resp.success){
            editButton.disable();
            deleteButton.disable();
            inputDocGrid.store.load();
            Ext.getCmp('frmNewInputDoc').hide();
            PMExt.notify(_('ID_REQUEST_DOCUMENTS'),resp.msg);
          }else{
            PMExt.notify( _('ID_ERROR') , resp.msg);
          }
        },
        failure: function(f,a){
          Ext.getCmp('designerTab').getEl().unmask();
          PMExt.notify( _('ID_REQUEST_DOCUMENTS') , _('ID_SOME_FIELDS_REQUIRED'));
        }
      });
    }
  });
  
  cancelButton = new Ext.Action({
    text : _('ID_CANCEL'),
    disabled: false,
    handler: function(){
      Ext.getCmp('frmNewInputDoc').hide();
    }
  });
  
  inputDocForm = new Ext.FormPanel({
    labelWidth: 10,
    autoWidth : true,
    height    : 380,
    monitorValid : true,
    autoHeight: true,
    buttonAlign: 'center',
    url: 'processOptionsProxy/saveInputDoc',
    items: [{
      xtype  : 'fieldset',
      layout : 'form',
      border : true,
      title  : _('ID_INPUT_INFO'),
      autoWidth  : true,
      labelWidth : 150,
      collapsible : false,
      labelAlign  : '',
      plain: false,
      items : [
               {xtype: 'textfield', fieldLabel: _('ID_TITLE'),width: 300,name: 'INP_DOC_TITLE', allowBlank: false},
               {
                 width: 300,
                 xtype: 'combo',
                 mode: 'local',
                 editable: false,
                 fieldLabel: _('ID_TYPE'),
                 triggerAction: 'all',
                 name: 'INP_DOC_FORM_NEEDED',
                 displayField: 'name',
                 valueField    : 'value',
                 id: 'idoc_FORM_NEEDED',
                 autoSelect: true,
                 allowBlank: false,
                 submitValue : false,
                 hiddenName: 'INP_DOC_FORM_NEEDED',
                 store: new Ext.data.JsonStore({
                   fields : ['name', 'value'],
                   data   : [
                             {name : 'Digital',   value: 'VIRTUAL'},
                             {name : 'Printed',   value: 'REAL'},
                             {name : 'Digital/Printed',   value: 'VREAL'}
                             ]
                 }),
                 onSelect: function(record, index) {
                   if(record.data.value != 'VIRTUAL') {
                     Ext.getCmp("formType").enable();
                   }
                   else {
                     Ext.getCmp("formType").disable();
                   }
                   this.collapse();
                   this.setValue(record.data[this.valueField || this.displayField]);
                 }
               },
               {
                 xtype          : 'combo',
                 id             : 'formType',
                 width          : 150,
                 mode           : 'local',
                 editable       : false,
                 hiddenName     : 'INP_DOC_ORIGINAL',
                 disabled       : true,
                 submitValue : false,
                 fieldLabel     : _('ID_FORMAT'),
                 triggerAction  : 'all',
                 forceSelection : true,
                 displayField   : 'name',
                 valueField     : 'value',
                 allowBlank     : false,
                 value          : 'ORIGINAL',
                 store          : new Ext.data.JsonStore({
                   fields : ['name', 'value'],
                   data   : [
                             {name : 'Original',   value: 'ORIGINAL'},
                             {name : 'Legal Copy',   value: 'COPYLEGAL'},
                             {name : 'Copy',   value: 'COPY'}
                             ]}
                 )
               },
               {xtype: 'textarea', fieldLabel: _('ID_DESCRIPTION'), name: 'INP_DOC_DESCRIPTION', height: 120, width: 300},
               {
                 width     : 150,
                 xtype:          'combo',
                 mode:           'local',
                 editable:       false,
                 fieldLabel:     _('ID_ENABLE_VERSIONING'),
                 triggerAction:  'all',
                 forceSelection: true,
                 hiddenName: 'INP_DOC_VERSIONING',
                 id: 'idoc_VERSIONING',
                 submitValue: false,
                 displayField:   'name',
                 valueField:     'value',
                 value         : '0',
                 allowBlank: false,
                 store:          new Ext.data.JsonStore({
                   fields : ['name', 'value'],
                   data   : [
                             {name : 'No',   value: '0'},
                             {name : 'Yes',   value: '1'}
                             ]})
               },
               {
                 layout      :'column',
                 border      :false,
                 items       :[{
                   layout      : 'form',
                   border      :false,
                   items       : [{
                     xtype       : 'textfield',
                     width     : 250,
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
                     width :50,
                     text            : '@@',
                     name            : 'selectorigin',
                     handler: function (s) {
                       workflow.variablesAction = 'form';
                       workflow.fieldName         = 'INP_DOC_DESTINATION_PATH' ;
                       workflow.variable        = '@@',
                       workflow.formSelected    = inputDocForm;
                       var rowData = PMVariables();
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
                     width     : 250,
                     //id          :'tags',
                     fieldLabel  : _('ID_TAGS'),
                     name        : 'INP_DOC_TAGS',
                     anchor      :'100%' 
                   }]
                 },{
                   //columnWidth :.4,
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
                       var rowData = PMVariables();
                     }
                   }]
                 }]
               },
               {id : 'INP_DOC_UID', xtype: 'hidden', name : 'INP_DOC_UID'},
               {id : 'PRO_UID', xtype: 'hidden', name : 'PRO_UID'}
             ]
    }],
    buttons: [saveButton, cancelButton]
  });
  
  
  smodel = new Ext.grid.RowSelectionModel({
    singleSelect: true,
    listeners:{
      rowselect: function(sm){
        editButton.enable();
        deleteButton.enable();
      },
      rowdeselect: function(sm){
        editButton.disable();
        deleteButton.disable();
      }
    }
  });
  
  idocsContextMenu = new Ext.menu.Menu({
    items: [editButton, deleteButton]
  });
  
  
  inputDocStore = new Ext.data.GroupingStore( {
    proxy : new Ext.data.HttpProxy({
      url: 'processOptionsProxy/loadInputDocuments?PRO_UID='+pro_uid
      //params: {PRO_UID: pro_uid}
    }),
    reader : new Ext.data.JsonReader( {
      root: 'idocs',
      totalProperty: 'total_idocs',
      fields : [
                {name: 'INP_DOC_UID', type: 'string'},
                {name: 'PRO_UID',type: 'string'},
                {name: 'INP_DOC_TITLE', type: 'string'},
                {name: 'INP_DOC_DESCRIPTION', type: 'string'},
                {name: 'INP_DOC_VERSIONING',type: 'string'},
                {name: 'INP_DOC_DESTINATION_PATH',type: 'string'},
                {name: 'INP_DOC_TASKS', type: 'int'}
                ]
    })
  });
  
  bbarpaging = new Ext.PagingToolbar({
    pageSize: 10,
    store: inputDocStore,
    displayInfo: true,
    displayMsg: _('ID_GRID_PAGE_DISPLAYING_ROLES_MESSAGE') + '&nbsp; &nbsp; ',
    emptyMsg: _('ID_GRID_PAGE_NO_ROLES_MESSAGE'),
    items: []
  });
  
  expander = new Ext.ux.grid.RowExpander({
    tpl : new Ext.Template("<p><b>"+TRANSLATIONS.ID_DESCRIPTION+":</b> {INP_DOC_DESCRIPTION} </p>")
  });
  
  inputDocColumns = new Ext.grid.ColumnModel({
    defaults: {
      editable: false,
      sortable: true
    },
    columns: [
              expander,
              {id: 'INP_DOC_UID', dataIndex: 'INP_DOC_UID', hidden:true, hideable:false},
              {header: _('ID_TITLE'), dataIndex: 'INP_DOC_TITLE', width: 350},
              {header: _('ID_VERSIONING'), dataIndex: 'INP_DOC_VERSIONING', width: 100, renderer: render_version},
              {header: _('ID_DESTINATION_PATH'), dataIndex: 'INP_DOC_DESTINATION_PATH', width: 150},
              {header: _('ID_TASK'), dataIndex: 'INP_DOC_TASKS', width: 100, align: 'center'}
              ]
  });
  
  inputDocGrid = new Ext.grid.GridPanel({
    store: inputDocStore,
    cm: inputDocColumns,
    sm: smodel,
    id: 'inputdocGrid',
    loadMask: true,
    frame: false,
    autoWidth: true,
    clicksToEdit: 1,
    height:100,
    layout: 'fit',
    plugins: expander,
    stripeRows: true,
    tbar: [newButton, '-', editButton, deleteButton],
    bbar: bbarpaging,
    viewConfig: {forceFit: true},
    view: new Ext.grid.GroupingView({
      forceFit:true,
      groupTextTpl: '{text}'
    })
  });
  
  //connecting context menu to grid
  inputDocGrid.addListener('rowcontextmenu', onInputDocContextMenu,this);
  
  //by default the right click is not selecting the grid row over the mouse
  //we need to set this four lines
  inputDocGrid.on('rowcontextmenu', function (grid, rowIndex, evt) {
    var sm = grid.getSelectionModel();
    sm.selectRow(rowIndex, sm.isSelected(rowIndex));
  }, this);
  
  //prevent default
  inputDocGrid.on('contextmenu', function (evt) {
    evt.preventDefault();
  }, this);
  
  function onInputDocContextMenu(grid, rowIndex, e) {
    e.stopEvent();
    var coords = e.getXY();
    idocsContextMenu.showAt([coords[0], coords[1]]);
  }
  
  inputDocGrid.store.load();
  
  gridWindow = new Ext.Window({
    title: _('ID_REQUEST_DOCUMENTS'),
    width: 600,
    height: 350,
    minWidth: 200,
    minHeight: 350,
    layout: 'fit',
    plain: true,
    items: inputDocGrid,
    autoScroll: true, 
    modal: true
  });
  
  newIDocWindow = new Ext.Window({
    title: _('ID_NEW_INPUTDOCS'),
    width: 550,
    id: 'frmNewInputDoc',
    autoHeight: true,
    autoScroll: true,
    closable: false,
    layout: 'fit',
    plain: true,
    modal: true,
    items: inputDocForm
  });
  
  gridWindow.show();
}

ProcessOptions.prototype.addOutputDoc= function(_5625)
{
 

 var outputDocFields = Ext.data.Record.create([
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

  var btnAdd = new Ext.Button({
            id: 'btnAdd',
            text: _('ID_NEW'),
            iconCls: 'button_menu_ext ss_sprite ss_add',
            handler: function () {
            outputDocForm.getForm().reset();
            outputDocForm.getForm().items.items[3].setValue('Portrait'); 
            //outputDocForm.getForm().items.items[4].setValue('Letter'); 
            outputDocForm.getForm().items.items[9].setValue('BOTH'); 
            outputDocForm.getForm().items.items[10].setValue(0); 
            outputDocForm.getForm().items.items[0].focus('',500); 
            newOPWindow.show();
            }
        });

  //edit output document Function
  var editOutputDoc = function(){

   var rowSelected  = Ext.getCmp('outputdocGrid').getSelectionModel().getSelections();
              if( rowSelected.length == 0 ) {
                   PMExt.error('', _('ID_NO_SELECTION_WARNING'));
                   return false;
              }
      var outputDocUID = rowSelected[0].get('OUT_DOC_UID');

  

  Ext.QuickTips.init();

  // turn on validation errors beside the field globally
  Ext.form.Field.prototype.msgTarget = 'side';

  var bd = Ext.getBody();

  var importOption = new Ext.Action({
    text: _('ID_LOAD_FROM_FILE'),
    iconCls: 'silk-add',
    icon: '/images/import.gif',
    handler: function(){
      var w = new Ext.Window({
        title: '',
        width: 420,
        height: 140,
        modal: true,
        autoScroll: false,
        maximizable: false,
        resizable: false,

        items: [
          new Ext.FormPanel({
            /*renderTo: 'form-panel',*/
            id:'uploader',
            fileUpload: true,
            width: 400,
            frame: true,
            title: _('ID_OUT_PUT_DOC_UPLOAD_TITLE'),
            autoHeight: false,
            bodyStyle: 'padding: 10px 10px 0 10px;',
            labelWidth: 50,
            defaults: {
                anchor: '90%',
                allowBlank: false,
                msgTarget: 'side'
            },
            items: [{
                xtype: 'fileuploadfield',
                id: 'form-file',
                emptyText: _('ID_SELECT_TEMPLATE_FILE'),
                fieldLabel: _('ID_FILE'),
                name: 'templateFile',
                buttonText: '',
                buttonCfg: {
                    iconCls: 'upload-icon'
                }
            }],
            buttons: [{
                text: _('ID_UPLOAD'),
                handler: function(){
                  var uploader = Ext.getCmp('uploader');
                  if(uploader.getForm().isValid()){
                    uploader.getForm().submit({
                      url: '../outputdocs/outputdocs_Ajax?action=setTemplateFile',
                      waitMsg: _('ID_UPLOADING_FILE'),
                      success: function(o, resp){
                        w.close();

                        Ext.Ajax.request({
                          url: '../outputdocs/outputdocs_Ajax?action=getTemplateFile&r='+Math.random(),
                          success: function(response){
                            top.getForm().findField('OUT_DOC_TEMPLATE').setValue(response.responseText);
                            if(top.getForm().findFields('OUT_DOC_TEMPLATE').getValue(response.responseText)=='')
                              Ext.Msg.alert(_('ID_ALERT_MESSAGE'), _('ID_INVALID_FILE'));
                          },
                          failure: function(){},
                          params: {request: 'getRows'}
                        });

                      },
                      failure: function(o, resp){
                        w.close();
                        //alert('ERROR "'+resp.result.msg+'"');
                        Ext.MessageBox.show({title: '', msg: resp.result.msg, buttons:
                        Ext.MessageBox.OK, animEl: 'mb9', fn: function(){}, icon:
                        Ext.MessageBox.ERROR});
                        //setTimeout(function(){Ext.MessageBox.hide(); }, 2000);
                      }
                    });
                  }
                }
            },{
            text: _('ID_CANCEL'),
            handler: function(){
                // when this button clicked,
                w.hide();
            }
        }]
          })
        ]
      });
      w.show();
    }
  });


    var top = new Ext.FormPanel({
        labelAlign: 'top',
        frame:true,
        title: '',
        bodyStyle:'padding:5px 5px 0',
        width: 790,
        tbar:[importOption],
        items: [
        {
            xtype:'htmleditor',
            //id:'OUT_DOC_TEMPLATE',
            name:'OUT_DOC_TEMPLATE',
            fieldLabel:'Output Document Template',
            height:300,
            anchor:'98%'
        }],

        buttons: [{
          text: _('ID_SAVE'),
          handler: function(){
              editor.stopEditing();
             Ext.Ajax.request({
              url: 'outputdocs/outputdocs_Save.php',
              method: 'POST',
              params: {
                OUT_DOC_UID: outputDocUID,
                functions:'',
                OUT_DOC_TEMPLATE:top.getForm().findField('OUT_DOC_TEMPLATE').getValue()

              },
              success: function(response){
                Ext.Msg.show({
                  title: '',
                  msg: 'Saved Successfully',
                  fn: function(){
                      window.hide();
                                },
                  animEl: 'elId',
                  icon: Ext.MessageBox.INFO,
                  buttons: Ext.MessageBox.OK
                });
              },
              failure: function(){}
              
            });
          }
        },{
            text: _('ID_CANCEL'),
            handler: function(){
                // when this button clicked,
                window.hide();
            }
        }]
    });

    top.render(document.body);

    var window = new Ext.Window({
      title: _('ID_NEW_INPUTDOCS'),
      width: 650,
      height: 450,
      minWidth: 200,
      minHeight: 450,
      autoScroll: true,
      layout: 'fit',
      plain: true,
      items: top
    });
   window.show();

   top.form.load({
                    url   :'bpmn/processes_Ajax.php?OUT_DOC_UID='+outputDocUID+'&action=getOutputDocsTemplates',
                    method: 'GET',
                    waitMsg:'Loading',
                    success:function(form, action) {
                       //Ext.MessageBox.alert('Message', 'Loaded OK');
                       window.show();
                       //OUT_DOC_TEMPLATE:Ext.getCmp('OUT_DOC_TEMPLATE').setValue()
                    },
                    failure:function(form, action) {
                        PMExt.notify( _('ID_STATUS') , _('ID_LOAD_FAILED') );
                    }
                 });

   
}

   var removeOutputDoc = function(){
    ids = Array();

    editor.stopEditing();
    var rowsSelected = Ext.getCmp('outputdocGrid').getSelectionModel().getSelections();

    if( rowsSelected.length == 0 ) {
      PMExt.error('', _('ID_NO_SELECTION_WARNING'));
      return false;
    }

    for(i=0; i<rowsSelected.length; i++)
      ids[i] = rowsSelected[i].get('OUT_DOC_UID');

    ids = ids.join(',');

    //deleting the selected input document
    PMExt.confirm(_('ID_CONFIRM'), _('ID_DELETE_OUTPUTDOCUMENT_CONFIRM'), function(){
                      Ext.Ajax.request({
                        url   : 'outputdocs/outputdocs_Delete.php',
                        method: 'POST',
                        params: {
                          OUT_DOC_UID        : ids
                        },
                        success: function(response) {
                          var result = Ext.util.JSON.decode(response.responseText);
                          if( result.success ){
                            PMExt.notify( _('ID_STATUS') , result.msg);

                            //Reloading store after deleting input document
                            outputDocStore.reload();
                          } else {
                            PMExt.error(_('ID_ERROR'), result.msg);
                          }
                        }
                      });
                    });
  }

  //properties output document
  var propertiesOutputDoc = function(){
      editor.stopEditing();
      var rowSelected  = Ext.getCmp('outputdocGrid').getSelectionModel().getSelections();
      if( rowSelected.length == 0 ) {
           PMExt.error('', _('ID_NO_SELECTION_WARNING'));
           return false;
      }
      var outputDocUID = rowSelected[0].get('OUT_DOC_UID');
      outputDocForm.form.load({
         url   :'bpmn/proxyExtjs.php?tid='+outputDocUID+'&action=editOutputDocument',
         method: 'GET',
         waitMsg:'Loading',
         success:function(form, action) {
            //Ext.MessageBox.alert('Message', 'Loaded OK');
            newOPWindow.show();
            Ext.getCmp("OUT_DOC_UID").setValue(outputDocUID);
         },
         failure:function(form, action) {
             PMExt.notify( _('ID_STATUS') , _('ID_LOAD_FAILED') );
         }
      });

  }
  
  //edit output document button
  var btnEdit = new Ext.Button({
    id: 'btnEdit',
    text: _('ID_EDIT'),
    iconCls: 'button_menu_ext ss_sprite  ss_pencil',
    handler: editOutputDoc
  });

  var btnRemove = new Ext.Button({
    id: 'btnRemove',
    text: _('ID_DELETE'),
    iconCls: 'button_menu_ext ss_sprite ss_delete',
    handler: removeOutputDoc
  });

  var btnProperties = new Ext.Button({
            id: 'btnProperties',
            text: _('ID_PROPERTIES'),
            iconCls: 'button_menu_ext ss_sprite ss_application_edit',
            handler: propertiesOutputDoc
        });

  var tb = new Ext.Toolbar({
            items: [btnAdd, btnRemove, btnEdit, btnProperties]
            });

  var outputDocStore = new Ext.data.JsonStore({
            root         : 'data',
            totalProperty: 'totalCount',
            idProperty   : 'gridIndex',
            remoteSort   : true,
            fields       : outputDocFields,
            proxy        : new Ext.data.HttpProxy({
                           url: 'bpmn/proxyExtjs?pid='+pro_uid+'&action=getOutputDocument'
                           })
  });
  outputDocStore.load({params:{start : 0 , limit : 10 }});

 var expander = new Ext.ux.grid.RowExpander({
    tpl : new Ext.Template(
        "<p><b>"+TRANSLATIONS.ID_DESCRIPTION+":</b> {OUT_DOC_DESCRIPTION} </p>"
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
                }
            ]
        });

  var outputDocGrid = new Ext.grid.GridPanel({
        store       : outputDocStore,
        id          : 'outputdocGrid',
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
    labelWidth      : 140,
    defaults        : {width : 300, autoScroll:true},
    width           : 300,
    bodyStyle : 'padding:8px 0 0 8px;',
    items : [
      {
        xtype       : 'textfield',
        fieldLabel  : _('ID_TITLE'),
        allowBlank  : false,
        blankText   : 'Enter Title of Output Document',
        name        : 'OUT_DOC_TITLE'
      },{
        width       : 450,
        layout:'column',
        border:false,
        items:[{
          columnWidth:.8,
          layout : 'form',
          width  : 300,
          border:false,
          items: [{
            xtype       : 'textfield',
            fieldLabel  : _('ID_FILENAME_GENERATED'),
            name        : 'OUT_DOC_FILENAME',
            allowBlank  : false,
            blankText   : 'Select Filename generated',
            anchor      : '100%'
          }]
        },{
          columnWidth:.2,
          layout: 'form',
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
              var rowData = PMVariables();
              console.log(rowData);
            }
          }]
        }]
        },{
          xtype           : 'textarea',
          fieldLabel      : _('ID_DESCRIPTION'),
          name            : 'OUT_DOC_DESCRIPTION',
          height          : 50,
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
          forceSelection  : true,
          name            :'OUT_DOC_MEDIA',
          displayField    :'name',
          value           :'Letter',
          valueField      :'value',
          store           : new Ext.data.JsonStore({
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
                width       : 450,
                border      :false,
                items       :[{
                    columnWidth :.8,
                    layout      : 'form',
                    border      :false,
                    items       : [{
                      xtype       : 'textfield',
                      fieldLabel  : _('ID_DESTINATION_PATH'),
                      name        : 'OUT_DOC_DESTINATION_PATH',
                      anchor      :'100%',
                      width       : 300
                    }]
                },{
                    columnWidth     :.2,
                    layout          : 'form',
                    border          :false,
                    items           : [{
                      xtype           : 'button',
                      title           : ' ',
                      text            : '@@',
                      name            : 'selectorigin',
                      handler: function (s) {
                        workflow.variablesAction = 'form';
                        workflow.fieldName         = 'OUT_DOC_DESTINATION_PATH' ;
                        workflow.variable        = '@@',
                        workflow.formSelected    = outputDocForm;
                        var rowData = PMVariables();
                      }
                    }]
                }]
            },{
                layout      :'column',
                width       : 450,
                border      :false,
                items       :[{
                    columnWidth :.8,
                    layout      : 'form',
                    border      :false,
                    items       : [{
                    xtype       : 'textfield',
                    fieldLabel  : _('ID_TAGS'),
                    name        : 'OUT_DOC_TAGS',
                    anchor      :'100%',
                    width       : 300
                    }]
                },{
                    columnWidth :.2,
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
                        var rowData = PMVariables();
                      }
                    }]
                }]
              },{
                id : 'OUT_DOC_UID',
                xtype: 'hidden',
                name : 'OUT_DOC_UID'
              }
    ],
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
                url   : 'outputdocs/outputdocs_Save.php',
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
                        url   : 'outputdocs/outputdocs_Save.php',
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
                    PMExt.error( _('ID_ERROR') , _('ID_OUTPUT_NOT_SAVE') );
                    }
      });
             }
       else
             {
           Ext.Ajax.request({
                        url   : 'outputdocs/outputdocs_Save.php',
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
        width       : 520,
        closable    : false,
        defaults    :{autoScroll:true},
        height      : 470,
        minWidth    : 200,
        minHeight   : 350,
        layout      : 'fit',
        plain       : true,
        items       : outputDocForm,
        buttonAlign : 'center'
    });

  //connecting context menu  to grid
  outputDocGrid.addListener('rowcontextmenu', onOutputDocContextMenu,this);

  //by default the right click is not selecting the grid row over the mouse
  //we need to set this four lines
  outputDocGrid.on('rowcontextmenu', function (grid, rowIndex, evt) {
    var sm = grid.getSelectionModel();
    sm.selectRow(rowIndex, sm.isSelected(rowIndex));
  }, this);

  //prevent default
  outputDocGrid.on('contextmenu', function (evt) {
      evt.preventDefault();
  }, this);

  function onOutputDocContextMenu(grid, rowIndex, e) {
    e.stopEvent();
    var coords = e.getXY();
    outputdocContextMenu.showAt([coords[0], coords[1]]);
  }

  var outputdocContextMenu = new Ext.menu.Menu({
    id: 'messageContextMenu',
    items: [{
        text: _('ID_EDIT'),
        iconCls: 'button_menu_ext ss_sprite  ss_pencil',
        handler: editOutputDoc
      },{
        text: _('ID_PROPERTIES'),
        iconCls: 'button_menu_ext ss_sprite ss_application_edit',
        handler: propertiesOutputDoc
      },{
        text: _('ID_DELETE'),
        icon: '/images/delete.png',
        handler: removeOutputDoc
      },{
        text: _('ID_UID'),
        handler: function(){
          var rowSelected = Ext.getCmp('outputdocGrid').getSelectionModel().getSelected();
          workflow.createUIDButton(rowSelected.data.OUT_DOC_UID);
        }
      }
    ]
  });

 var gridWindow = new Ext.Window({
        title       : _('ID_OUTPUT_DOCUMENTS'),
        collapsible : false,
        maximizable : false,
        width       : 550,
        defaults    :{autoScroll:true},
        height      : 350,
        minWidth    : 200,
        minHeight   : 350,
        layout      : 'fit',
        plain       : true,
        items       : outputDocGrid,
        buttonAlign : 'center'
     });
 gridWindow.show();
}
 
/* 
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
 
 var btnAdd = new Ext.Button({
            id: 'btnAdd',
            text: _('ID_NEW'),
            iconCls: 'button_menu_ext ss_sprite ss_add',
            handler: function () {
                formWindow.show();
                reportForm.getForm().reset();
            }
  });
  
  //edit report table Function
  var editReportTable = function() {
      editor.stopEditing();
      var rowSelected  = Ext.getCmp('reportTableGrid').getSelectionModel().getSelections();
      if( rowSelected.length == 0 ) {
           PMExt.error('', _('ID_NO_SELECTION_WARNING'));
           return false;
      }
      var repTabUID = rowSelected[0].get('REP_TAB_UID');
      reportForm.form.load({
                        url   :'bpmn/proxyExtjs.php?pid='+pro_uid+'&REP_TAB_UID=' +repTabUID+'&action=editReportTables',
                        method: 'GET',
                        waitMsg:'Loading',
                        success:function(form, action) {
                           formWindow.show();
                        },
                        failure:function(form, action) {
                            PMExt.notify( _('ID_STATUS') , _('ID_LOAD_FAILED') );
                        }
                     });
   }
   
   var removeReportTable = function(){
    ids = Array();

    editor.stopEditing();
    var rowsSelected = Ext.getCmp('reportTableGrid').getSelectionModel().getSelections();

    if( rowsSelected.length == 0 ) {
      PMExt.error('', _('ID_NO_SELECTION_WARNING'));
      return false;
    }

    for(i=0; i<rowsSelected.length; i++)
      ids[i] = rowsSelected[i].get('REP_TAB_UID');

    ids = ids.join(',');

    PMExt.confirm(_('ID_CONFIRM'), _('ID_DELETE_INPUTDOCUMENT_CONFIRM'), function(){
                      Ext.Ajax.request({
                          url   : '../reportTables/reportTables_Delete.php',
                          method: 'POST',
                          params: {
                             REP_TAB_UID   : ids
                          },
                        success: function(response) {
                          var result = Ext.util.JSON.decode(response.responseText);
                          if( result.success ){
                            PMExt.notify( _('ID_STATUS') , result.msg);

                            //Reloading store after deleting input document
                            reportStore.reload();
                          } else {
                            PMExt.error(_('ID_ERROR'), result.msg);
                          }
                        }
                      });
                    });
    }
  
  //edit report table button
  var btnEdit = new Ext.Button({
    id: 'btnEdit',
    text: _('ID_EDIT'),
    iconCls: 'button_menu_ext ss_sprite  ss_pencil',
    handler: editReportTable
  });

  var btnRemove = new Ext.Button({
    id: 'btnRemove',
    text: _('ID_DELETE'),
    iconCls: 'button_menu_ext ss_sprite ss_delete',
    handler: removeReportTable
  });

  var tb = new Ext.Toolbar({
            items: [btnAdd, btnRemove, btnEdit]
            });

 var reportStore = new Ext.data.JsonStore({
            root         : 'data',
            totalProperty: 'totalCount',
            idProperty   : 'gridIndex',
            remoteSort   : true,
            fields       : reportFields,
            proxy        : new Ext.data.HttpProxy({
                           url : 'bpmn/proxyExtjs?pid='+pro_uid+'&action=getReportTables'
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
                           url : 'bpmn/proxyExtjs?pid='+pro_uid+'&type=NORMAL&action=getReportTableType'
                           })
 });
  reportTableTypeStore.load();

 var expander = new Ext.ux.grid.RowExpander({
    tpl : new Ext.Template(
        " <p></p>"
    )
  });

  var reportColumns = new Ext.grid.ColumnModel({
            columns: [
                new Ext.grid.RowNumberer(),
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

  var reportGrid = new Ext.grid.GridPanel({
        store       : reportStore,
        id          : 'reportTableGrid',
        loadMask    : true,
        loadingText : 'Loading...',
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
            emptyMsg: "No Report Tables to display"
        }),
        viewConfig: {forceFit: true}
   });

  //connecting context menu  to grid
  reportGrid.addListener('rowcontextmenu', onreportTableContextMenu,this);

  //by default the right click is not selecting the grid row over the mouse
  //we need to set this four lines
  reportGrid.on('rowcontextmenu', function (grid, rowIndex, evt) {
    var sm = grid.getSelectionModel();
    sm.selectRow(rowIndex, sm.isSelected(rowIndex));
  }, this);

  //prevent default
  reportGrid.on('contextmenu', function (evt) {
      evt.preventDefault();
  }, this);

  function onreportTableContextMenu(grid, rowIndex, e) {
    e.stopEvent();
    var coords = e.getXY();
    reportTableContextMenu.showAt([coords[0], coords[1]]);
  }

  var reportTableContextMenu = new Ext.menu.Menu({
    id: 'messageContextMenu',
    items: [{
        text: _('ID_EDIT'),
        iconCls: 'button_menu_ext ss_sprite  ss_pencil',
        handler: editReportTable
      },{
        text: _('ID_DELETE'),
        icon: '/images/delete.png',
        handler: removeReportTable
      },{
        text: _('ID_UID'),
        handler: function(){
          var rowSelected = Ext.getCmp('reportTableGrid').getSelectionModel().getSelected();
          workflow.createUIDButton(rowSelected.data.REP_TAB_UID);
        }
      }
    ]
  });

  var gridWindow = new Ext.Window({
        title       : _('ID_REPORT_TABLES'),
        collapsible : false,
        maximizable : false,
        width       : 420,
        defaults    :{autoScroll:true},
        height      : 350,
        minWidth    : 200,
        minHeight   : 350,
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
                                        var link = 'bpmn/proxyExtjs?pid='+pro_uid+'&type='+record.data.value+'&action=getReportTableType';
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
          },{xtype:'hidden', name:'REP_TAB_UID'}
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

            if(tableUID=='')
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
                  url   : '../reportTables/reportTables_Save.php',
                  method: 'POST',
                  params:{
                      PRO_UID         :pro_uid,
                      REP_TAB_UID     :tableUID,
                      REP_TAB_TITLE   :Title,
                      REP_TAB_NAME    :Name,
                      REP_TAB_TYPE    :Type ,
                      REP_TAB_GRID    :Grid,
                      FIELDS          :Fields
                      //REP_TAB_CONNECTION: Connection
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
*/


ProcessOptions.prototype.addTriggers = function()
{
   
   var triggerFields = Ext.data.Record.create([
    {name: 'TRI_UID'},
    {name: 'TRI_TITLE'},
    {name: 'TRI_DESCRIPTION'},
    {name: 'TRI_WEBBOT'}
    ]);

   var editor = new Ext.ux.grid.RowEditor({
            saveText: _('ID_UPDATE')
        });

   var btnAdd = new Ext.Button({
     id: 'btnAdd',
     text: _('ID_NEW'),
     iconCls: 'button_menu_ext ss_sprite ss_add',
     handler: function () {
       triggersForm.getForm().reset();
       triggersForm.getForm().items.items[0].focus('',200); 
       formWindow.show();
     }
   });

  //edit report table Function
   var editTriggers = function() {
          editor.stopEditing();
          var rowSelected  = Ext.getCmp('triggersGrid').getSelectionModel().getSelections();
          if( rowSelected.length == 0 ) {
               PMExt.error('', _('ID_NO_SELECTION_WARNING'));
               return false;
          }
          var triggerUID = rowSelected[0].get('TRI_UID');
          editTriggerForm.getForm().load({
                        url   :'bpmn/proxyExtjs.php?pid='+pro_uid+'&TRI_UID='+triggerUID+'&action=editTriggers',
                        method: 'GET',
                        waitMsg:'Loading',
                        success:function(form, action) {
                           Ext.getCmp('TRI_WEBBOT').setValue(action.result.data.TRI_WEBBOT);
                           editTriggerFormWindow.show();
                        },
                        failure:function(form, action) {
                           PMExt.notify( _('ID_STATUS') , _('ID_LOAD_FAILED') );
                        }
                     });
    }

    var editProperties = function(){
          editor.stopEditing();
          var rowSelected  = Ext.getCmp('triggersGrid').getSelectionModel().getSelections();
          if( rowSelected.length == 0 ) {
               PMExt.error('', _('ID_NO_SELECTION_WARNING'));
               return false;
          }
          var triggerUID = rowSelected[0].get('TRI_UID');
          //editPropertiesFormWindow.show();
          editPropertiesForm.form.load({
                        url   :'bpmn/proxyExtjs.php?pid='+pro_uid+'&TRI_UID='+triggerUID+'&action=editTriggers',
                        method: 'GET',
                        waitMsg:'Loading',
                        success:function(form, action) {
                           editPropertiesFormWindow.show();
                           //Ext.getCmp("TRI_UID").setValue(triggerUID);
                        },
                        failure:function(form, action) {
                            PMExt.notify( _('ID_STATUS') , _('ID_LOAD_FAILED') );
                        }
           });
    }
    
    var removeTriggers = function() {
    ids = Array();

    editor.stopEditing();
    var rowsSelected = Ext.getCmp('triggersGrid').getSelectionModel().getSelections();

    if( rowsSelected.length == 0 ) {
      PMExt.error('', _('ID_NO_SELECTION_WARNING'));
      return false;
    }

    for(i=0; i<rowsSelected.length; i++)
      ids[i] = rowsSelected[i].get('TRI_UID');

    ids = ids.join(',');
    //First check whether selected trigger has any dependencies or not
    Ext.Ajax.request({
      url   : '../triggers/triggers_Ajax',
      method: 'POST',
      params: {
        request   : 'verifyDependencies',
        PRO_UID   : pro_uid,
        TRI_UID   : ids
      },
      success: function(response) {
                var result = Ext.util.JSON.decode(response.responseText);
                if( result.success ){
                  if( result.passed ) { //deleting the selected triggers
                    PMExt.confirm(_('ID_CONFIRM'), _('ID_DELETE_TRIGGER_CONFIRM'), function(){
                    Ext.Ajax.request({
                        url   : 'bpmn/processes_Ajax.php',
                        method: 'POST',
                        params: {
                          action      : 'deleteTriggers',
                          TRI_UID     : ids
                        },
                        success: function(response) {
                          var result = Ext.util.JSON.decode(response.responseText);
                          if( result.success ){
                            PMExt.notify( _('ID_STATUS') , result.message);

                            //Reloading store after deleting dynaform
                            triggerStore.reload();
                          } else {
                            PMExt.error(_('ID_ERROR'), result.message);
                          }
                        }
                      });
                    });
                  } else {
                    PMExt.error(_('ID_VALIDATION_ERROR'), result.message);
                  }
                } else {
                  PMExt.error(_('ID_ERROR'), result.message);
                }
              }
    });
  }
  //edit triggers button
  var btnEdit = new Ext.Button({
    id: 'btnEdit',
    text: _('ID_EDIT'),
    iconCls: 'button_menu_ext ss_sprite  ss_pencil',
    handler: editTriggers
  });

  var btnRemove = new Ext.Button({
    id: 'btnRemove',
    text: _('ID_DELETE'),
    iconCls: 'button_menu_ext ss_sprite ss_delete',
    handler: removeTriggers
  });

  var btnProperties = new Ext.Button({
    id: 'btnProperty',
    text: _('ID_PROPERTIES'),
    iconCls: 'button_menu_ext ss_sprite ss_application_edit',
    handler: editProperties
  });

   var triggerStore = new Ext.data.GroupingStore({
    idProperty   : 'gridIndex',
    reader : new Ext.data.JsonReader( {
      totalProperty: 'totalCount',
      root: 'data',
      fields : triggerFields
    }),
    proxy        : new Ext.data.HttpProxy({
      url: 'bpmn/proxyExtjs?pid='+pro_uid+'&action=getTriggersList'
    })
  });
  triggerStore.load({params:{start:0, limit:10}});

   var expander = new Ext.ux.grid.RowExpander({
    tpl : new Ext.Template("<p><b>"+TRANSLATIONS.ID_DESCRIPTION+":</b> {TRI_DESCRIPTION}</p></p>")
   });

  var triggersColumns = new Ext.grid.ColumnModel({
    defaults: {
      width: 90,
      sortable: true
    },
    columns: [
      expander,
      {
        header: _('ID_TITLE_FIELD'),
        dataIndex: 'TRI_TITLE',
        width: 280
      }
    ]
  });

 var tb = new Ext.Toolbar({
    items: [btnAdd, btnEdit,btnProperties,btnRemove]
  });

  var triggersGrid = new Ext.grid.GridPanel({
    store: triggerStore,
    id : 'triggersGrid',
    loadMask: true,
    loadingText: 'Loading...',
    //renderTo: 'cases-grid',
    frame: false,
    autoHeight:false,
    minHeight:400,
    height   :400,
    width: '',
    layout: 'fit',
    cm: triggersColumns,
    stateful : true,
    stateId : 'grid',
    plugins: expander,
    stripeRows: true,
    tbar: tb,
    bbar: new Ext.PagingToolbar({
      pageSize: 10,
      store: triggerStore,
      displayInfo: true,
      displayMsg: 'Displaying Triggers {0} - {1} of {2}',
      emptyMsg: "No Triggers to display"
    }),
    viewConfig: {forceFit: true}
  });

  //connecting context menu  to grid
  triggersGrid.addListener('rowcontextmenu', ontriggersContextMenu,this);

  //by default the right click is not selecting the grid row over the mouse
  //we need to set this four lines
  triggersGrid.on('rowcontextmenu', function (grid, rowIndex, evt) {
    var sm = grid.getSelectionModel();
    sm.selectRow(rowIndex, sm.isSelected(rowIndex));
  }, this);

  //prevent default
  triggersGrid.on('contextmenu', function (evt) {
      evt.preventDefault();
  }, this);

  function ontriggersContextMenu(grid, rowIndex, e) {
    e.stopEvent();
    var coords = e.getXY();
    triggersContextMenu.showAt([coords[0], coords[1]]);
  }

  var triggersContextMenu = new Ext.menu.Menu({
    id: 'messageContextMenu',
    items: [{
        text: _('ID_EDIT'),
        iconCls: 'button_menu_ext ss_sprite  ss_pencil',
        handler: editTriggers
      },{
        text: _('ID_PROPERTIES'),
        iconCls: 'button_menu_ext ss_sprite ss_application_edit',
        handler: editProperties
      },{
        text: _('ID_DELETE'),
        icon: '/images/delete.png',
        handler: removeTriggers
      },{
        text: _('ID_UID'),
        handler: function(){
          var rowSelected = Ext.getCmp('triggersGrid').getSelectionModel().getSelected();
          workflow.createUIDButton(rowSelected.data.TRI_UID);
        }
      }
    ]
  });

var triggersForm = new Ext.FormPanel({
    labelWidth  : 100,
    buttonAlign : 'center',
    width       : 300,
    height      : 220,
    bodyStyle : 'padding:8px 0 0 8px;',
    autoHeight: true,
    items:
      [{
        xtype: 'textfield',
        layout: 'fit',
        border:true,
        name: 'TRI_TITLE',
        fieldLabel: _('ID_TITLE'),
        width: 300,
        collapsible: false,
        allowBlank: false,
        labelAlign: 'top'
      },
      {
        xtype: 'textarea',
        border:true,
        name: 'TRI_DESCRIPTION',
        hidden: false,
        fieldLabel: _('ID_DESCRIPTION'),
        width: 300,
        height: 120
      }],
     buttons: [{
        text: _('ID_SAVE'),
        //formBind    :true,
        handler: function(){
            var getForm   = triggersForm.getForm().getValues();
            var title = getForm.TRI_TITLE;
            var triggerUid = getForm.TRI_UID;
            var condition = getForm.TRI_WEBBOT;
            var desc = getForm.TRI_DESCRIPTION;

            if(title == '')
                    PMExt.notify( _('ID_ERROR') , _('ID_TRIGGER_TITLE_REQUIRED') );
            else
                    {
                    //First check whether trigger name already exist or not
                    Ext.Ajax.request({
                      url   : '../triggers/triggers_Save.php',
                          method: 'POST',
                          params: {
                            functions     : 'lookforNameTrigger',
                            proUid        : pro_uid,
                            NAMETRIGGER   : title
                          },
                      success: function(response) {
                        var result = response.responseText;
                        if(result) {
                                  //now save trigger
                                  Ext.Ajax.request({
                                  url   : '../triggers/triggers_Save.php',
                                  method: 'POST',
                                  params:{
                                          //functions       : 'lookforNameTrigger',
                                          TRI_TITLE     : title,
                                          PRO_UID       : pro_uid,
                                          TRI_UID       :'',
                                          TRI_PARAM     :'',
                                          TRI_TYPE      :'SCRIPT',
                                          TRI_DESCRIPTION :desc,
                                          TRI_WEBBOT    :condition,
                                          mode          :'ext'
                                  },
                                   success: function(response) {
                                          var result = Ext.util.JSON.decode(response.responseText);
                                          if( result.success ){
                                            PMExt.notify( _('ID_STATUS') , result.msg);

                                            //Reloading store after saving triggers
                                            triggerStore.reload();
                                            formWindow.hide();
                                          } else {
                                            PMExt.error(_('ID_ERROR'), result.msg);
                                          }
                                        }
                                      });
                                  } else {
                                    PMExt.error(_('ID_VALIDATION_ERROR'), 'There is a triggers with the same name in  this process.');
                                  }
                              }
                            });
                       formWindow.hide();
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

  var editTriggerForm = new Ext.FormPanel({
    buttonAlign : 'center',
    labelWidth  : 2,
    layout      : 'fit',
    width       : 570,
    height      : 350,
    items:
      [{
        layout      :'column',
        border      :false,
        items       :[{
            //columnWidth :.6,
            layout      : 'form',
            border      : false,
            items       : [{
//                xtype       : 'textarea',
                xtype       : 'codepress',
                language    : 'generic',
                id          : 'TRI_WEBBOT',
                width       : 420,
                height      : 310,
                name        : 'TRI_WEBBOT'
            }]
        },{
          //columnWidth     :.4,
          layout          : 'form',
          border          :false,
          items           : [{
            xtype   :'button',
            title   : ' ',
            width   : 50,
            text    : '@@',
            name    : 'selectorigin',
            handler: function (s) {
              workflow.variablesAction = 'form';
              workflow.fieldName       = 'TRI_WEBBOT' ;
              workflow.variable        = '@@',
              workflow.formSelected    = editTriggerForm;
              var rowData = PMVariables();
            }
          }]
        }]
      },{
        xtype: 'hidden',
        name: 'TRI_UID'
      }],
    buttons: [{
        text: _('ID_SAVE'),
        //formBind    :true,
        handler: function(){
            var getForm   = editTriggerForm.getForm().getValues();
            var triggerUid = getForm.TRI_UID;
//            var condition = getForm.TRI_WEBBOT;
            var condition = Ext.getCmp('TRI_WEBBOT').getCode();
            var desc = getForm.TRI_DESCRIPTION;
            Ext.Ajax.request({
                              url   : '../triggers/triggers_Save.php',
                              method: 'POST',
                              params:{
                                      PRO_UID       : pro_uid,
                                      TRI_UID       : triggerUid,
                                      TRI_TYPE      : 'SCRIPT',
                                      TRI_WEBBOT    : condition,
                                      mode          : 'ext'
                              },
                               success: function(response) {
                                      var result = Ext.util.JSON.decode(response.responseText);
                                      if( result.success ){
                                        PMExt.notify( _('ID_STATUS') , result.msg);

                                        //Reloading store after saving triggers
                                        triggerStore.reload();
                                        editTriggerFormWindow.hide();
                                      } else {
                                        PMExt.error(_('ID_ERROR'), result.msg);
                                      }
                                    }
                         });
        }
      },{
        text: _('ID_CANCEL'),
        handler: function(){
        // when this button clicked,
        editTriggerFormWindow.hide();
      }
      }]
 });

 var editPropertiesForm = new Ext.FormPanel({
    labelWidth  : 100,
    buttonAlign : 'center',
    width       : 400,
    height      : 300,
    bodyStyle : 'padding:10px 0 0 10px;',
    autoHeight: true,
    items:
      [{
       xtype: 'fieldset',
       title: 'Trigger Information',
       border:true,
       id: 'trigger',
       width: 400,
          items:[{
            xtype: 'textfield',
            layout: 'fit',
            border:true,
            name: 'TRI_TITLE',
            fieldLabel: _('ID_TITLE'),
            width: 250,
            allowBlank: false,
            labelAlign: 'top'
          },
          {
            xtype: 'textarea',
            border:true,
            name: 'TRI_DESCRIPTION',
            hidden: false,
            fieldLabel: _('ID_DESCRIPTION'),
            width: 250,
            height: 120
          }]
      },{
               xtype: 'hidden',
               name: 'TRI_UID'
        }],
    buttons: [{
        text: _('ID_SAVE'),
        //formBind    :true,
        handler: function(){
            var getForm   = editPropertiesForm.getForm().getValues();
            var triggerUid = getForm.TRI_UID;
            var title = getForm.TRI_TITLE;
            var desc = getForm.TRI_DESCRIPTION;
            //First check whether trigger name already exist or not
            Ext.Ajax.request({
              url   : '../triggers/triggers_Save.php',
                  method: 'POST',
                  params: {
                    functions     : 'lookforNameTrigger',
                    proUid        : pro_uid,
                    NAMETRIGGER   : title
                  },
              success: function(response) {
                var result = response.responseText;
                if(result) {
                          //now save trigger
                          Ext.Ajax.request({
                          url   : '../triggers/triggers_Save.php',
                          method: 'POST',
                          params:{
                                  TRI_TITLE     : title,
                                  PRO_UID       : pro_uid,
                                  TRI_UID       :triggerUid,
                                  TRI_PARAM     :'',
                                  TRI_TYPE      :'SCRIPT',
                                  TRI_DESCRIPTION :desc,
                                  mode          :'ext'
                          },
                           success: function(response) {
                                  var result = Ext.util.JSON.decode(response.responseText);
                                  if( result.success ){
                                    PMExt.notify( _('ID_STATUS') , result.msg);

                                    //Reloading store after saving triggers
                                    triggerStore.reload();
                                    editPropertiesFormWindow.hide();
                                  } else {
                                    PMExt.error(_('ID_ERROR'), result.msg);
                                  }
                                }
                              });
                          } else {
                            PMExt.error(_('ID_VALIDATION_ERROR'), 'There is a triggers with the same name in  this process.');
                          }
                      }
                    });
            editPropertiesFormWindow.hide();
        }
      },{
        text: _('ID_CANCEL'),
        handler: function(){
        // when this button clicked,
        editPropertiesFormWindow.hide();
      }
      }]
 }); 

  var editTriggerFormWindow = new Ext.Window({
    title:  _('ID_EDIT_TRIGGERS'),
    autoScroll: true,
    collapsible: false,
    width: 600,
    //autoHeight: true,
    height: 400,
    layout: 'fit',
    plain: true,
    buttonAlign: 'center',
    items: editTriggerForm
  });

  var editPropertiesFormWindow = new Ext.Window({
    title: _('ID_EDIT_TRIGGERS'),
    autoScroll: true,
    collapsible: false,
    width: 450,
    //autoHeight: true,
    height: 280,
    layout: 'fit',
    plain: true,
    buttonAlign: 'center',
    items: editPropertiesForm
    });


 var formWindow = new Ext.Window({
        title: _('ID_TRIGGERS'),
        autoScroll: true,
        collapsible: false,
        maximizable: true,
        width: 450,
        //autoHeight: true,
        height: 240,
        layout: 'fit',
        plain: true,
        buttonAlign: 'center',
        items: triggersForm
    });
    
  var gridWindow = new Ext.Window({
        title: _('ID_TRIGGERS'),
        autoScroll: true,
        collapsible: false,
        maximizable: true,
        width: 600,
        //autoHeight: true,
        height: 350,
        layout: 'fit',
        plain: true,
        buttonAlign: 'center',
        items: triggersGrid
    });
    gridWindow.show();
}


///*** from ProcessMapContext ***///
var PMVariables = function() {
  var varFields = Ext.data.Record.create( [ {
    name : 'variable',
    type : 'string'
  }, {
    name : 'type',
    type : 'string'
  }, {
    name : 'label',
    type : 'string'
  } ]);
  var varStore = '';
  varStore = new Ext.data.JsonStore( {
    root : 'data',
    totalProperty : 'totalCount',
    idProperty : 'gridIndex',
    remoteSort : true,
    fields : varFields,
    proxy : new Ext.data.HttpProxy( {
      url : 'bpmn/proxyExtjs?pid=' + pro_uid + '&action=getVariables&sFieldName=form[CTO_CONDITION]&sSymbol=@@'
    })
  });
  //varStore.load();

  var varColumns = new Ext.grid.ColumnModel( {
    columns : [ new Ext.grid.RowNumberer(), {
      id : 'FLD_NAME',
      header : _('ID_VARIABLES'),
      dataIndex : 'variable',
      width : 170,
      editable : false,
      sortable : true
    }, {
      id : 'PRO_VARIABLE',
      header : _('ID_LABEL'),
      dataIndex : 'label',
      width : 150,
      sortable : true
    } ]
  });

  var varForm = new Ext.FormPanel( {
    labelWidth : 100,
    monitorValid : true,
    width : 400,
    bodyStyle : 'padding:10px 0 0 10px;',
    height : 350,
    renderer : function(val) {
      return '<table border=1> <tr> <td> @@ </td> <td> Replace the value in quotes </td> </tr> </table>';
    },
    items : {
      xtype : 'tabpanel',
      activeTab : 0,
      defaults : {
        autoHeight : true
      },
      items : [ {
        title : _('ID_ALL_VARIABLES'),
        id : 'allVar',
        layout : 'form',
        listeners : {
          activate : function(tabPanel) {
            // use {@link Ext.data.HttpProxy#setUrl setUrl} to change the URL for *just* this request.
        var link = 'bpmn/proxyExtjs?pid=' + pro_uid + '&action=getVariables&type=' + tabPanel.id + '&sFieldName=form[CTO_CONDITION]&sSymbol=@@';
        varStore.proxy.setUrl(link, true);
        varStore.load();
      }
    },
    items : [ {
      xtype : 'grid',
      ds : varStore,
      cm : varColumns,
      width : 380,
      autoHeight : true,
      //plugins: [editor],
      //loadMask    : true,
      loadingText : 'Loading...',
      border : false,
      listeners : {
        //rowdblclick: alert("ok"),
      rowdblclick : function() {
        var objectSelected = workflow.variablesAction;
        switch (objectSelected) {
        case 'grid':
          var getObjectGridRow = workflow.gridObjectRowSelected;
          var FieldSelected = workflow.gridField;
          //getting selected row of variables
          var rowSelected = this.getSelectionModel().getSelected();
          var rowLabel = rowSelected.data.variable;
    
          //Assigned new object with condition
          if (typeof getObjectGridRow.colModel != 'undefined')
            getObjectGridRow.colModel.config[3].editor.setValue(rowLabel);
          //Assigning / updating Condition for a row
          else
            getObjectGridRow[0].set(FieldSelected, rowLabel);
    
          if (FieldSelected == 'CTO_CONDITION') {
            Ext.Ajax.request( {
              url : '../tracker/tracker_ConditionsSave.php',
              method : 'POST',
              params : {
                PRO_UID : pro_uid,
                CTO_UID : getObjectGridRow[0].data.CTO_UID,
                CTO_CONDITION : getObjectGridRow[0].data.CTO_CONDITION
              },
              success : function(response) {
                Ext.MessageBox.alert('Status', 'Objects has been edited successfully ');
              }
            })
          } else if (FieldSelected == 'STEP_CONDITION') {
            Ext.Ajax.request( {
              url : '../steps/conditions_Save.php',
              method : 'POST',
              params : {
                PRO_UID : pro_uid,
                STEP_UID : getObjectGridRow[0].data.STEP_UID,
                STEP_CONDITION : getObjectGridRow[0].data.STEP_CONDITION
              },
              success : function(response) {
                Ext.MessageBox.alert('Status', 'Objects has been edited successfully ');
              }
            })
          } else if (FieldSelected == 'ST_CONDITION') {
            Ext.Ajax.request( {
              url : '../steps/steps_Ajax.php',
              method : 'POST',
              params : {
                action : 'saveTriggerCondition',
                PRO_UID : pro_uid,
                STEP_UID : getObjectGridRow[0].data.STEP_UID,
                ST_CONDITION : getObjectGridRow[0].data.STEP_CONDITION,
                TAS_UID : taskId,
                TRI_UID : getObjectGridRow[0].data.TRI_UID,
                ST_TYPE : getObjectGridRow[0].data.ST_TYPE
    
              },
              success : function(response) {
                Ext.MessageBox.alert('Status', 'Objects has been edited successfully ');
              }
            })
          }
    
          window.hide();
    
          break;
        case 'form':
          FormSelected = workflow.formSelected;
          rowSelected = this.getSelectionModel().getSelected();
          FieldSelected = workflow.fieldName;
          rowLabel = rowSelected.data.variable;
          var prevContent = FormSelected.getForm().findField(FieldSelected).getValue();
          var newContent  = prevContent + ' ' + rowLabel;
          var value = FormSelected.getForm().findField(FieldSelected).setValue(newContent);
//          if (FormSelected.getForm().findField(FieldSelected).code){
//            FormSelected.getForm().findField(FieldSelected).code=value;
//          }
          window.hide();
          break;
    
        }
    
      }
    }
    } ]
      }, {
        title : _('ID_SYSTEM'),
        id : 'system',
        layout : 'form',
        listeners : {
          activate : function(tabPanel) {
            // use {@link Ext.data.HttpProxy#setUrl setUrl} to change the URL for *just* this request.
        var link = 'bpmn/proxyExtjs?pid=' + pro_uid + '&action=getVariables&type=' + tabPanel.id + '&sFieldName=form[CTO_CONDITION]&sSymbol=@@';
        varStore.proxy.setUrl(link, true);
        varStore.load();
      }
    },
    items : [ {
      xtype : 'grid',
      ds : varStore,
      cm : varColumns,
      width : 380,
      autoHeight : true,
      //plugins: [editor],
      //loadMask    : true,
      loadingText : 'Loading...',
      border : false,
      listeners : {
        //rowdblclick: alert("ok"),
      rowdblclick : function() {
        var objectSelected = workflow.variablesAction;
        switch (objectSelected) {
        case 'grid':
          var getObjectGridRow = workflow.gridObjectRowSelected;
          var FieldSelected = workflow.gridField;
          //getting selected row of variables
          var rowSelected = this.getSelectionModel().getSelected();
          var rowLabel = rowSelected.data.variable;
          //Assigned new object with condition
          if (typeof getObjectGridRow.colModel != 'undefined')
            getObjectGridRow.colModel.config[3].editor.setValue(rowLabel);
          //Assigning / updating Condition for a row
          else
            getObjectGridRow[0].set(FieldSelected, rowLabel);
          if (CTO_UID != '') {
            Ext.Ajax.request( {
              url : '../tracker/tracker_ConditionsSave.php',
              method : 'POST',
              params : {
                PRO_UID : pro_uid,
                CTO_UID : getObjectGridRow[0].data.CTO_UID,
                CTO_CONDITION : getObjectGridRow[0].data.CTO_CONDITION
              },
              success : function(response) {
                Ext.MessageBox.alert('Status', 'Objects has been edited successfully ');
              }
            })
            window.hide();
          }
    
          break;
        case 'form':
          FormSelected = workflow.formSelected;
          rowSelected = this.getSelectionModel().getSelected();
          FieldSelected = workflow.fieldName;
          rowLabel = rowSelected.data.variable;
          var value = FormSelected.getForm().findField(FieldSelected).setValue(rowLabel);
          window.hide();
          break;
    
        }
    
      }
    }
    } ]
      }, {
        title : _('ID_CASESLIST_APP_PRO_TITLE'),
        id : 'process',
        layout : 'form',
        listeners : {
          activate : function(tabPanel) {
            // use {@link Ext.data.HttpProxy#setUrl setUrl} to change the URL for *just* this request.
        var link = 'bpmn/proxyExtjs?pid=' + pro_uid + '&action=getVariables&type=' + tabPanel.id + '&sFieldName=form[CTO_CONDITION]&sSymbol=@@';
        varStore.proxy.setUrl(link, true);
        varStore.load();
      }
    },
    items : [ {
      xtype : 'grid',
      ds : varStore,
      cm : varColumns,
      width : 380,
      autoHeight : true,
      //plugins: [editor],
      //loadMask    : true,
      loadingText : 'Loading...',
      border : false,
      listeners : {
        //rowdblclick: alert("ok"),
        rowdblclick : function() {
          var objectSelected = workflow.variablesAction;
          switch (objectSelected) {
          case 'grid':
            var getObjectGridRow = workflow.gridObjectRowSelected;
            var FieldSelected = workflow.gridField;
            //getting selected row of variables
            var rowSelected = this.getSelectionModel().getSelected();
            var rowLabel = rowSelected.data.variable;
            //Assigned new object with condition
            if (typeof getObjectGridRow.colModel != 'undefined')
              getObjectGridRow.colModel.config[3].editor.setValue(rowLabel);
            //Assigning / updating Condition for a row
            else
              getObjectGridRow[0].set(FieldSelected, rowLabel);
            Ext.Ajax.request( {
              url : '../tracker/tracker_ConditionsSave.php',
              method : 'POST',
              params : {
                PRO_UID : pro_uid,
                CTO_UID : getObjectGridRow[0].data.CTO_UID,
                CTO_CONDITION : getObjectGridRow[0].data.CTO_CONDITION
              },
              success : function(response) {
                Ext.MessageBox.alert('Status', 'Objects has been edited successfully ');
              }
            })
            window.hide();
            break;
          case 'form':
            FormSelected = workflow.formSelected;
            rowSelected = this.getSelectionModel().getSelected();
            FieldSelected = workflow.fieldName;
            rowLabel = rowSelected.data.variable;
            var value = FormSelected.getForm().findField(FieldSelected).setValue(rowLabel);
            window.hide();
            break;
      
          }
      
        }
      }
      } ]
      } ]
    }
  });

  var window = new Ext.Window( {
    title : _('ID_VARIABLES'),
    collapsible : false,
    maximizable : false,
    scrollable : true,
    width : 400,
    height : 350,
    minWidth : 200,
    minHeight : 150,
    autoScroll : true,
    layout : 'fit',
    plain : true,
    buttonAlign : 'center',
    items : [ varForm ]
  });
  window.show();
}
