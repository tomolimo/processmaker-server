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
        text : 'three columns',
        handler : function(a) {
          var vp = Ext.getCmp('viewportDashboard');
          var pd = Ext.getCmp('portalDashboard');
          pd.items.items[0].columnWidth = 0.33;
          pd.items.items[1].columnWidth = 0.33;
          pd.items.items[2].columnWidth = 0.33;
          pd.doLayout();        
          }
      },
      {
        xtype: 'tbbutton',
        text : 'two columns',
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
      },
      {
        xtype: 'tbbutton',
        text : 'blog',
        handler : function(a) {
          var vp = Ext.getCmp('viewportDashboard');
          var pd = Ext.getCmp('portalDashboard');
          pd.items.items[0].columnWidth = 0.40;
          pd.items.items[1].columnWidth = 0.40;
          pd.items.items[2].columnWidth = 0.20;
          pd.doLayout();        
          //vp.doLayout();        
          }
      },
      {
        xtype: 'tbbutton',
        text : 'new gauge',
        handler : function(a) {
          var np = new Ext.ux.Portlet ( {
            //title: 'Panel nuevo',
            //tools: tools,
            html: 'gauge placeholder',
            listeners: {
              'render': function(p){
                p.html = 'hello ' + p.getWidth();
              },
              'move' : function(p){
                Ext.Msg.alert('Portlet ', 'move ' + p.getWidth() );
                p.html = 'show ' + p.getWidth();
              },
              'resize' : function(p,w,h){
                var randomnumber=Math.floor(Math.random()*1000000)
                var img = new Ext.XTemplate("<img src='{page}?w={width}&r={random}&DAS_INS_UID={id}'>").apply({
                page: 'dashboard/renderDashletInstance', width:w, random: randomnumber, id:'123456ABCDEF' })

                p.update(img );
              }
            }
          });
          
          var vp = Ext.getCmp('viewportDashboard');
          var pd = Ext.getCmp('portalDashboard');
          pd.items.items[0].add( np );
          pd.doLayout();        
          //vp.doLayout();        
          }
      },
      {
        xtype: 'tbbutton',
        text : 'new trend graph',
        handler : function(a) {
          var np = new Ext.ux.Portlet ( {
            //title: 'Panel nuevo',
            tools: tools,
            html: 'hello world',
            listeners: {
              'render': function(p){
                p.html = 'hello ' + p.getWidth();
              },
              'move' : function(p){
                Ext.Msg.alert('Portlet ', 'move ' + p.getWidth() );
                p.html = 'show ' + p.getWidth();
              },
              'resize' : function(p,w,h){
                var randomnumber=Math.floor(Math.random()*1000000)
                var img = new Ext.XTemplate("<img src='{page}?w={width}&r={random}'>").apply({
                page: 'http://javaserver.colosa.net/ext/examples/portal/history.php', width:w, random: randomnumber })

                p.update(img );
              }
            }
          });
          
          var vp = Ext.getCmp('viewportDashboard');
          var pd = Ext.getCmp('portalDashboard');
          pd.items.items[0].add( np );
          pd.doLayout();        
          //vp.doLayout();        
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
});

