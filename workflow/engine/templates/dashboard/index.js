Ext.onReady(function(){

  Ext.state.Manager.setProvider(new Ext.state.CookieProvider());

  // create some portlet tools using built in Ext tool ids
  var tools = [{
    id:'gear',
    handler: function(){
      Ext.Msg.alert('Message', 'The Settings tool was clicked.');
    }
  },{
    id:'close',
    handler: function(e, target, panel){
        panel.ownerCt.remove(panel, true);
    }
  }];

  var tbDashboard = new Ext.Toolbar({
    height: 30,
    items: [
      {
        xtype: 'tbbutton',
        text : 'Three Columns',
        handler : function(a) {
          var vp = Ext.getCmp('viewportDashboard');
          var pd = Ext.getCmp('portalDashboard');
          while (pd.items.items[0].items.items[1]) {
            pd.items.items[2].add(pd.items.items[0].items.items[1]);
          }
          pd.items.items[0].columnWidth = 0.33;
          pd.items.items[1].columnWidth = 0.33;
          pd.items.items[2].columnWidth = 0.33;
          pd.doLayout();
          }
      },
      {
        xtype: 'tbbutton',
        text : 'Two Columns',
        handler : function(a) {
          var vp = Ext.getCmp('viewportDashboard');
          var pd = Ext.getCmp('portalDashboard');
          pd.items.items[0].columnWidth = 0.49;
          pd.items.items[1].columnWidth = 0.49;
          while ( pd.items.items[2].items.items[0] ) {
            pd.items.items[0].add( pd.items.items[2].items.items[0] );
          }
          pd.items.items[2].columnWidth = 0.01;
          pd.doLayout();
          }
      }
    ]
  });

  var viewport = new Ext.Viewport({
    layout:'fit',
    name : 'viewportDashboard',
    id   : 'viewportDashboard',
    items:[{
      xtype:'portal',
      region:'center',
      margins:'35 5 5 0',
      tbar: tbDashboard,
      name : 'portalDashboard',
      id   : 'portalDashboard',
      items:[{
        columnWidth:.33,
        style:'padding:10px 0 10px 10px',
        items:[]
      },{
          columnWidth:.33,
          style:'padding:10px 0 10px 10px',
          items:[]
      },{
          columnWidth:.33,
          style:'padding:10px',
          items:[]
      }]

      /*
       * Uncomment this block to test handling of the drop event. You could use this
       * to save portlet position state for example. The event arg e is the custom
       * event defined in Ext.ux.Portal.DropZone.
       */
//      ,listeners: {
//          'drop': function(e){
//              Ext.Msg.alert('Portlet Dropped', e.panel.title + '<br />Column: ' +
//                  e.columnIndex + '<br />Position: ' + e.position);
//          }
//      }
    }]
  });

  var pd = Ext.getCmp('portalDashboard');
  for (var i = 0; i < dashletsInstances.length; i++) {
    var np = new Ext.ux.Portlet({
      title: dashletsInstances[i].DAS_TITLE,
      index: i,
      dasInsUid : dashletsInstances[i].DAS_INS_UID,
      html: 'Gauge Placeholder',
      listeners: {
        'resize': function(p, w, h) {
          var template = new Ext.XTemplate(dashletsInstances[p.index].DAS_XTEMPLATE).apply({
            id: p.dasInsUid,
            page: 'dashboard/renderDashletInstance',
            width: w - 12,
            random: Math.floor(Math.random() * 1000000)
          })
          p.update(template);
        }
      }
    });
    pd.items.items[i % 3].add(np);
  }
  pd.doLayout();
});
