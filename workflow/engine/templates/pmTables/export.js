/**
 * Export PM Tables
 * developed on date 2011-07-20 
 * @author Erik Amaru Ortiz <erik@colosa.com>
 */

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

/**
 * CONFIGURE ROUTINES
 */
Export.configure = function()
{
  /**
   * TARGET GRID CONFIG
   */
  this.targetGridConfig = {
    id    : 'targetGrid',
    title : _('ID_TABLES_TO_EXPORT'),
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
    header: _('ID_SCHEMA'),
    dataIndex: '_SCHEMA',
    width: 55,
    checked: true
  });

  dataColumn = new Ext.grid.CheckColumn({
    header: _('ID_DATA'),
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
      {header: _('ID_PMTABLE'), dataIndex: 'ADD_TAB_NAME', width: 300},
      {header: _('ID_TYPE'), dataIndex: '_TYPE', width:100},
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
    width: 570,
    height: 400,
    modal: true,
    autoScroll: true,
    maximizable: true,
    closeAction: 'hide',
    maximizable : false,
    items: []
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

/**
 * EXPORT ROUTINE
 */
Export.submit = function()
{
  var rows = Export.targetGrid.getStore();
  var rowsData = new Array();

  for (i=0; i < rows.getCount(); i++) {
    row = rows.getAt(i);
    if ( row.data._SCHEMA == false && row.data._DATA == false) {
      PMExt.info(_('ID_INFO'), _('ID_PMTABLES_NOTICE_EXPORT'));
      return false;
    }
    rowsData.push(row.data);
  }
  
  Ext.Msg.show({
    title : '',
    msg : _('ID_PROCESSING'),
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


