var actionsByEmailGrid;
var store;
var win ;

new Ext.KeyMap(document, {
  key: Ext.EventObject.F5,
  fn: function(keycode, e) {
      if (! e.ctrlKey) {
        if (Ext.isIE) {
          e.browserEvent.keyCode = 8;
        }
        e.stopEvent();
        document.location = document.location;
      } else {
        Ext.Msg.alert(_('ID_REFRESH_LABEL'), _('ID_REFRESH_MESSAGE'));
      }
  }
});


Ext.onReady(function(){
  Ext.state.Manager.setProvider(new Ext.state.CookieProvider());
  Ext.QuickTips.init();

store = new Ext.data.GroupingStore( {
    proxy : new Ext.data.HttpProxy({
      url: '../actionsByEmail/actionsByEmailAjax',
      method: 'POST'
    }),

    reader : new Ext.data.JsonReader( {
      totalProperty: 'totalCount',
      root: 'data',
      fields : [
        {name : 'ABE_UID'},
        {name : 'ABE_REQ_UID'},
        {name : 'APP_UID'},
        {name : 'TAS_UID'},
        {name : 'ABE_REQ_DATE'},
        {name : 'ABE_REQ_SUBJECT'},
        {name : 'APP_NUMBER'},
        {name : 'USER'},
        {name : 'ABE_REQ_SENT_TO'},
        {name : 'ABE_REQ_STATUS'},
        {name : 'ABE_REQ_ANSWERED'},
        {name : 'ABE_RES_MESSAGE'}
      ]
    })
  });
  store.setBaseParam( 'action', 'loadActionByEmail' );

  actionsByEmailGrid = new Ext.grid.GridPanel( {
    region: 'center',
    layout: 'fit',
    id: 'actionsByEmailGrid',
    title : '',
    stateful : true,
    stateId : 'grid',
    enableColumnResize: true,
    enableHdMenu: true,
    frame:false,
    columnLines: true,

    cm: new Ext.grid.ColumnModel({
      defaults: {
          sortable: true
      },
      columns: [
        {id:     "ABE_UID", dataIndex: "ABE_UID", hidden:true, hideable:false},
        {header: _("DATE_LABEL"), width: 100, dataIndex: "ABE_REQ_DATE", sortable: true},
        {header: _("ID_CASE_NUMBER_CAPITALIZED"), width: 70, dataIndex: "APP_NUMBER", sortable: true},
        {header: _("ID_SUBJECT"), width: 150, dataIndex: "ABE_REQ_SUBJECT", sortable: true},
        {header: _("ID_FROM"), width: 110, dataIndex: "USER", sortable: true},
        {header: _("ID_TO"), width:  110, dataIndex: "ABE_REQ_SENT_TO", sortable: true},
        {header: _("ID_STATUS"), width: 40, dataIndex: "ABE_REQ_STATUS", sortable: true},
        {header: _("ID_ANSWERED"), width:  60, dataIndex: "ABE_REQ_ANSWERED"},
        {header: _("ID_VIEW_RESPONSE"), width: 80, sortable: false, align: 'center', renderer: function(val){ return '<img src="/images/ext/default/s.gif" class="x-tree-node-icon ss_layout_header" unselectable="off" id="extdd-17" onclick="openForm()" '; }, dataIndex: 'somefieldofyourstore'},
        {header: _("ID_ERROR_MESSAGE"), width: 130,dataIndex: "ABE_RES_MESSAGE",sortable: false}
      ]
    }),
    store: store,
    tbar:[
      {
        text: _("ID_RESEND"),
        iconCls: 'button_menu_ext ss_sprite  ss_world',
        handler:ForwardEmail
      }
    ],
    // paging bar on the bottom
    bbar: new Ext.PagingToolbar({
        pageSize: 25,
        store: store,
        displayInfo: true,
        displayMsg: _('ID_DISPLATING_ACTIONSBYEMAIL') + ' {0} - {1} ' + _('ID_DISPLAY_OF') + ' {2}'//,
    }),
    viewConfig: {
      forceFit: true
    },
      listeners: {
      render: function(){
        this.loadMask = new Ext.LoadMask(this.body, {msg:_('ID_LOADING_GRID')});
      }
    }
  });

  store.load({params:{ start : 0 , limit : 25 }});
  actionsByEmailGrid.addListener('rowcontextmenu', onMessageContextMenu,this);
  actionsByEmailGrid.on('rowcontextmenu', function (grid, rowIndex, evt) {
    var sm = grid.getSelectionModel();
    sm.selectRow(rowIndex, sm.isSelected(rowIndex));

    var rowSelected = Ext.getCmp('actionsByEmailGrid').getSelectionModel().getSelected();

  }, this);
  actionsByEmailGrid.on('contextmenu', function (evt) {
      evt.preventDefault();
  }, this);

  function onMessageContextMenu(grid, rowIndex, e) {
    e.stopEvent();
    var coords = e.getXY();
    messageContextMenu.showAt([coords[0], coords[1]]);
  }

  var viewport = new Ext.Viewport({
    layout: 'border',
    autoScroll: true,
    items: [
      actionsByEmailGrid
    ]
  });
});

function openForm(){
  var rows = actionsByEmailGrid.getSelectionModel().getSelections();
  var REQ_UID = '';
  var ids = '';
  for (i=0; i<rows.length; i++) {
    if (i != 0 ) {
      ids += ',';
    }
    ids += rows[i].get('APP_NUMBER') + ', ';
    ids += rows[i].get('ABE_REQ_SUBJECT');
    REQ_UID += rows[i].get('ABE_REQ_UID');
  }
  if ( REQ_UID != '' ) {
    win = new Ext.Window({
          id: 'win',
          title: ids,
          pageX: 100 ,
          pageY: 100 ,
          width: 500,
          floatable: true,
          autoHeight:true,
          modal: true,
          layout: 'fit',
          autoLoad : {
                url : '../actionsByEmail/actionsByEmailAjax',
                params : { action:'viewForm',REQ_UID : REQ_UID },
                scripts: true
          },
          plain: true,
          buttons: [{
            id: 'btn',
            text: _('ID_CLOSE'),
            handler: function() {
              win.hide();
            }
          }]}).show();
  } else {
     Ext.Msg.show({
      title:'',
      msg: TRANSLATIONS.ID_NO_SELECTION_WARNING,
      buttons: Ext.Msg.INFO,
      fn: function(){},
      animEl: 'elId',
      icon: Ext.MessageBox.INFO,
      buttons: Ext.MessageBox.OK
    });
  }
}
function ForwardEmail(){
  var rows = actionsByEmailGrid.getSelectionModel().getSelections();
  var REQ_UID = '';
  var ids = '';
  for (i=0; i<rows.length; i++) {
    if (i != 0 ) {
      ids += ',';
    }
    REQ_UID += rows[i].get('ABE_REQ_UID');
    ids += rows[i].get('APP_NUMBER') + ', ';
    ids += rows[i].get('ABE_REQ_SUBJECT');
  }
  if ( REQ_UID != '' ) {
    win = new Ext.Window({
            id: 'win',
            title: ids,
            pageX: 100 ,
            pageY: 100 ,
            width: 500,
            floatable: true,
            autoHeight:true,
            modal: true,
            layout: 'fit',
            autoLoad : {
                  url : '../actionsByEmail/actionsByEmailAjax',
                  params : { action:'forwardMail',REQ_UID :REQ_UID},
                  scripts: true
            },
            plain: true,
            buttons: [{
              id: 'btn',
              text: _('ID_CLOSE'),
              handler: function() {
                win.hide();
              }
            }]}).show();
  } else {
     Ext.Msg.show({
      title:'',
      msg:  _("ID_NO_SELECTION_WARNING"),
      buttons: Ext.Msg.INFO,
      fn: function(){},
      animEl: 'elId',
      icon: Ext.MessageBox.INFO,
      buttons: Ext.MessageBox.OK
    });
  }
}
