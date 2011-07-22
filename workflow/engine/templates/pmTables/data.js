/**
 * Handle PMtables Data
 * @author Erik A. O. <erik@colosa.com>
 */

var newButton;
var editButton;
var deleteButton;
var importButton;
var backButton;

var store;
var cmodel;
var smodel;
var infoGrid;
var _fields;
var isReport;

Ext.onReady(function(){
  
  pageSize = 20; //parseInt(CONFIG.pageSize);
  
  isReport = tableDef.PRO_UID ? true : false;
  
  newButton = new Ext.Action({
    text: _('ID_ADD_ROW'),
    iconCls: 'button_menu_ext ss_sprite ss_add',
    handler: NewPMTableRow
  });
  
  editButton = new Ext.Action({
    text: _('ID_EDIT'),
    iconCls: 'button_menu_ext ss_sprite  ss_pencil',
    handler: EditPMTableRow,
    disabled: true
  });

  deleteButton = new Ext.Action({
    text: _('ID_DELETE'),
    iconCls: 'button_menu_ext ss_sprite  ss_delete',
    handler: DeletePMTableRow,
    disabled: true
  });

  importButton = new Ext.Action({
    text: _('ID_IMPORT'),
    iconCls: 'silk-add',
    icon: '/images/import.gif',
    handler: ImportPMTableCSV
  });
  
  backButton = new Ext.Action({
    text: _('ID_BACK'),
    icon: '/images/back-icon.png',
    handler: BackPMList
  });
  
  contextMenu = new Ext.menu.Menu({
      items: [editButton, deleteButton]
  });
  
  //This loop loads columns and fields to store and column model
  _columns = new Array();
  _fields  = new Array();
  _idProperty = '__index__';

 //default generated id
  _columns.push({
    id: _idProperty,
    hidden: true
  });

  _fields.push({name: _idProperty});
  
  if (tableDef.FIELDS.length !== 0) {
    for (i in tableDef.FIELDS) {
      _columns.push({
        id: tableDef.FIELDS[i].FLD_NAME,
        header: tableDef.FIELDS[i].FLD_DESCRIPTION,
        dataIndex: tableDef.FIELDS[i].FLD_NAME,
        width: 40,
        editor:{
          xtype: 'textfield',
          allowBlank: true
        }
      });
      
      _fields.push({name: tableDef.FIELDS[i].FLD_NAME});

      if(_idProperty == '' && tableDef.FIELDS[i].FLD_KEY) {
        _idProperty = tableDef.FIELDS[i].FLD_NAME;
      }
    }
  }
  
 smodel = new Ext.grid.CheckboxSelectionModel({
     listeners:{
       selectionchange: function(sm){
         var count_rows = sm.getCount();
         switch(count_rows){
         case 0:
           editButton.disable();
           deleteButton.disable();
           break;
         case 1:
           if (!isReport) {
             editButton.enable();
           }
           deleteButton.enable();
           break;
         default:
           editButton.disable();
           //deleteButton.disable();
           break;
         }
       }
     }
   });

  //row editor for table columns grid
  if (!isReport) {
    editor = new Ext.ux.grid.RowEditor({
      saveText: _("ID_UPDATE"),
      listeners: {
  	    afteredit: {
  	      fn:function(rowEditor, obj, data, rowIndex ){            	  
    		    if (data.phantom === true) {
    			  store.reload(); // only if it is an insert 
    	    	}
  	      }
  	    }
      }
    });
  }

  var proxy = new Ext.data.HttpProxy({
    //url: '../pmTablesProxy/getData?id=' + tableDef.ADD_TAB_UID
    api: {
      read   : '../pmTablesProxy/dataView?id=' + tableDef.ADD_TAB_UID,
      create : '../pmTablesProxy/dataCreate?id=' + tableDef.ADD_TAB_UID,
      update : '../pmTablesProxy/dataUpdate?id=' + tableDef.ADD_TAB_UID,
      destroy: '../pmTablesProxy/dataDestroy?id=' + tableDef.ADD_TAB_UID
    },
    baseParams : {id: tableDef.ADD_TAB_UID}
  })

  // The new DataWriter component.
  var writer = new Ext.data.JsonWriter({
      encode: true,
      writeAllFields: false
  });

  var reader = new Ext.data.JsonReader({
    root       : 'rows',
    idProperty : 'id',
    fields     : _fields,
    idProperty : _idProperty,
    totalProperty: 'count'
  })
  
  store = new Ext.data.Store({
    proxy : proxy,
    reader : reader,
    writer : writer, // <-- plug a DataWriter into the store just as you would a Reader
    autoSave: true // <-- false would delay executing create, update, destroy requests until specifically told to do so with some [save] buton.
  });

  cmodel = new Ext.grid.ColumnModel({
    defaults: {
        width: 50,
        sortable: true
    },
    columns: _columns
  });
  
  storePageSize = new Ext.data.SimpleStore({
    fields: ['size'],
     data: [['20'],['30'],['40'],['50'],['100']],
     autoLoad: true
  });
    
  comboPageSize = new Ext.form.ComboBox({
    typeAhead     : false,
    mode          : 'local',
    triggerAction : 'all',
    store: storePageSize,
    valueField: 'size',
    displayField: 'size',
    width: 50,
    editable: false,
    listeners:{
      select: function(c,d,i){
        UpdatePageConfig(d.data['size']);
        bbarpaging.pageSize = parseInt(d.data['size']);
        bbarpaging.moveFirst();
      }
    }
  });
    
  comboPageSize.setValue(pageSize);
  
  bbarpaging = new Ext.PagingToolbar({
    pageSize: pageSize,
    store: store,
    displayInfo: true,
    displayMsg: _('ID_GRID_PAGE_DISPLAYING_ROWS_MESSAGE') + '&nbsp; &nbsp; ',
    emptyMsg: _('ID_GRID_PAGE_NO_ROWS_MESSAGE'),
    items: ['-',_('ID_PAGE_SIZE')+':',comboPageSize]
  });
  
  infoGridConfig = {
    region: 'center',
    layout: 'fit',
    id: 'infoGrid',
    height:1000,
    autoWidth : true,
    title : _('ID_PM_TABLE') + " : " + tableDef.ADD_TAB_NAME,
    stateful : true,
    stateId : 'grid',
    enableColumnResize: true,
    enableHdMenu: true,
    frame:false,
    columnLines: false,
    viewConfig: {
      forceFit:true
    },
    store: store,
    cm: cmodel,
    sm: smodel,
    tbar:[newButton,'-',editButton, deleteButton,'-',importButton,{xtype: 'tbfill' }, backButton],
    bbar: bbarpaging
  }
  
  if (!isReport) {
    infoGridConfig.plugins = new Array();
    infoGridConfig.plugins.push(editor);
  }
  
  infoGrid = new Ext.grid.GridPanel(infoGridConfig);
  
  infoGrid.on('rowcontextmenu', 
        function (grid, rowIndex, evt) {
            var sm = grid.getSelectionModel();
            sm.selectRow(rowIndex, sm.isSelected(rowIndex));
        },
        this
  );
    
  infoGrid.on('contextmenu', function(evt){evt.preventDefault();}, this);
  infoGrid.addListener('rowcontextmenu',onMessageContextMenu, this);

  infoGrid.store.load();

  viewport = new Ext.Viewport({
    layout: 'fit',
    autoScroll: false,
    items: [infoGrid]
  }); 
});

//Funtion Handles Context Menu Opening
onMessageContextMenu = function (grid, rowIndex, e) {
    e.stopEvent();
    var coords = e.getXY();
    contextMenu.showAt([coords[0], coords[1]]);
};



/////JS FUNCTIONS

//Capitalize String Function
capitalize = function(s){
  s = s.toLowerCase();
  return s.replace( /(^|\s)([a-z])/g , function(m,p1,p2){ return p1+p2.toUpperCase(); } );
};

//Do Nothing Function
DoNothing = function(){};

//Load New PM Table Row Forms
var props = function(){};

NewPMTableRow = function(){
  var PMRow = infoGrid.getStore().recordType;
  //var meta = mapPMFieldType(records[i].data['FIELD_UID']);
  
  for(i=0; i<_fields.length; i++) {
    props.prototype[_fields[i].name] = '';
  }

  var row = new PMRow(new props);
  length = infoGrid.getStore().data.length;
  
  editor.stopEditing();
  store.insert(length, row);
  infoGrid.getView().refresh();
  infoGrid.getSelectionModel().selectRow(length);
  editor.startEditing(length);
};

//Load PM Table Edition Row Form
EditPMTableRow = function(){
  iGrid = Ext.getCmp('infoGrid');
  rowsSelected = iGrid.getSelectionModel().getSelections();
  var aRowsSeleted = (RetrieveRowsID(rowsSelected)).split(",") ;
  var aTablesPKF   = (TABLES.PKF).split(","); ;
  var sParam = '';
  for(var i=0;i<aTablesPKF.length; i++){
    sParam += '&' + aTablesPKF[i] + '=' + aRowsSeleted[i];
  }
  location.href = 'additionalTablesDataEdit?sUID='+TABLES.UID+sParam;
};

//Confirm PM Table Row Deletion Tasks
DeletePMTableRow = function(){
  
  PMExt.confirm(_('ID_CONFIRM'), _('ID_CONFIRM_REMOVE_FIELD'), function(){
    var records = Ext.getCmp('infoGrid').getSelectionModel().getSelections();
    Ext.each(records, Ext.getCmp('infoGrid').store.remove, Ext.getCmp('infoGrid').store);
  });

};

//Load Import PM Table From CSV Source
ImportPMTableCSV = function(){
  location.href = 'additionalTablesDataImportForm?sUID=' + TABLES.UID;
};

//Load PM Table List
BackPMList = function(){
  //location.href = 'additionalTablesList';
  history.back();
};

//Gets UIDs from a array of rows
RetrieveRowsID = function(rows){
  var arrAux = new Array();
  var arrPKF = new Array();
  arrPKF = TABLES.PKF.split(',');
  if(rows.length>0){
    var c = 0;
    for(var i=0; i<arrPKF.length; i++){
      arrAux[i] = rows[c].get(arrPKF[i]);
    }
  }
  return arrAux.join(',');
};

//Update Page Size Configuration
UpdatePageConfig = function(pageSize){
  Ext.Ajax.request({
  url: 'additionalTablesAjax',
  params: {action:'updatePageSizeData', size: pageSize}
  });
};