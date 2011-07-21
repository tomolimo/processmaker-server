
var checkColumn;

var Export = function() {
  return {
    //config objects
    windowConfig : {},
    targetGridConfig : {},

    // defining components
    targetGrid : {},
    window : {},
    // init
    init : function() {
      Ext.form.Field.prototype.msgTarget = 'side';
      Ext.QuickTips.init();
      
      this.configure();

      this.targetGrid = new Ext.grid.EditorGridPanel(this.targetGridConfig);
      this.window = new Ext.Window(this.windowConfig);
      this.window.add(this.targetGrid);
    }
  }
}();


Export.configure = function()
{
  /**
   * TARGET GRID CONFIG
   */
  this.targetGridConfig = {
    id    : 'targetGrid',
    title : 'To Export tables',
    region: 'east',
    width : 450,
    split : true,
    clicksToEdit: 2,
    columnLines: true
  };

  this.targetGridConfig.store = new Ext.data.ArrayStore({
      fields: [
        {name : 'ADD_TAB_UID'},
        {name : 'ADD_TAB_NAME'},
        {name : '_TYPE'},
        {name : '_SCHEMA'},
        {name : '_DATA'}
      ]
  });


  schemaColumn = new Ext.grid.CheckColumn({
    header: 'Schema',
    dataIndex: '_SCHEMA',
    width: 55,
    checked: true
  });

  dataColumn = new Ext.grid.CheckColumn({
    header: 'Data',
    dataIndex: '_DATA',
    width: 55
  });


  this.targetGridConfig.cm = new Ext.grid.ColumnModel({
    defaults: {
      sortable: true
    },
    columns: [
      new Ext.grid.RowNumberer(),
      {id:'ADD_TAB_UID', dataIndex: 'ADD_TAB_UID', hidden:true, hideable:false},
      {header: _('ID_TABLE'), dataIndex: 'ADD_TAB_NAME', width: 300},
      {header: _('ID_TYPE'), dataIndex: '_TYPE', width:70},
      schemaColumn,
      dataColumn
    ]
  });

  this.targetGridConfig.plugins = [schemaColumn, dataColumn];

  /** 
   * WINDOW CONFIG
   */
  this.windowConfig = {
    title: '',
    layout: 'fit',
    width: 550,
    height: 400,
    modal: true,
    autoScroll: true,
    maximizable: true,
    closeAction: 'hide',
    maximizable : false,
    items: [],
    listeners:{
      show:function() {
        this.loadMask = new Ext.LoadMask(this.body, { msg:'Loading. Please wait...' });
      }
    }
  }

  this.windowConfig.buttons = [{
    text: 'Export',
    handler: Export.submit
  },{
    text: 'Cancel',
    handler: function(){
      Export.window.hide();
    }
  }]

} //end configure

Export.submit = function()
{

  var rows = Export.targetGrid.getStore();
  var rowsData = new Array();

  for (i=0; i < rows.getCount(); i++) {
    row = rows.getAt(i);
    if ( row.data._SCHEMA == false && row.data._DATA == false) {
      PMExt.info('INFO', 'From each table you should select Schema/Data to export at least one.');
      return false;
    }
    rowsData.push(row.data);
  }
  
  Ext.Msg.show({
    title : '', //TRANSLATIONS.ID_TITLE_START_CASE, //'Start Case',
    msg : 'Processing...',
    wait: true,
    waitConfig: {interval:500}
  });
  

  Ext.Ajax.request({
    url: 'pmTablesProxy/export',
    params: {
      rows : Ext.util.JSON.encode(rowsData)
    },
    success: function(resp){
      Ext.Msg.hide();
      Export.window.hide();
      result = Ext.util.JSON.decode(resp.responseText);

      if (result.success) {
        location.href = result.link;
      } else {
        PMExt.error(_('ID_ERROR', result.message));
      }
    },
    failure: function(obj, resp){
      Ext.Msg.alert( _('ID_ERROR'), resp.result.msg);
    }
  });

}

Ext.onReady(Export.init, Export, true);


