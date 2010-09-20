var PANEL_EAST_OPEN = false;
var eastPanel;
var eastPanelNortSubPanel;
var eastPanelCenterSubPanel;
var eastPanel;
var westPanel;
var centerPanel;

var menuTree;

var winSize = parent.getClientWindowSize(); 

var detailsMenuTreePanelHeight = winSize.height - 420;
var detailsdebugVariablesHeight = winSize.height - 200;

var debugVarTpl = new Ext.Template(
  '<span style="font-size:11">',
  '{value}',
  '</span>'
);
debugVarTpl.compile();

var detailsText = '<i>...</i>';
var menuTreeDetailsTpl = new Ext.Template(
  '<span style="font-size:10">',
  '<h2 class="title">{title}</h2>',
  'Related Processes</b>: {processes_count}<br/>',
  '<span style="font-size:9">',
  '{innerText}',
  '</span>',
  '</span>'
);
menuTreeDetailsTpl.compile();

var debugTriggersDetailTpl = new Ext.Template(
  '<pre style="font-size:10px"><code>{code}</code></pre>'
);
debugTriggersDetailTpl.compile();
  
Ext.onReady(function(){

  Ext.state.Manager.setProvider(new Ext.state.CookieProvider());

  var resetGrid = function() {
    propStore.load();
  };
  
  var resetTriggers = function(){
    store.load();
  }
  
  var propStore = new Ext.data.Store({
    proxy: new Ext.data.HttpProxy({url: 'debug_vars'}),
    reader: new Ext.data.DynamicJsonReader({root: 'data'})
  });
    
  propStore.on('load', function(){
    propStore.fields = propStore.recordType.prototype.fields;
    debugVariables.setSource(propStore.getAt(0).data);
  });
  
  var debugVariables = new Ext.grid.PropertyGrid({
    title:'Variables',
    autoHeight: false,
    height: 300,
    width: 400,
    region: 'center',
    margins: '2 2 0 2',
    
    border: true,
    stripeRows: true,
    listeners: {
      beforeedit: function(event) { //Cancel editing - read only
        event.cancel = true;
      }
    }, 
    tbar: [
      {text: 'Update', handler: resetGrid}
    ],
    sm: new Ext.grid.RowSelectionModel({singleSelect: true}),
    viewConfig: {
      forceFit: true
    }
  });
  
  //set debug variable details
  debugVariables.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
    var detailPanel = Ext.getCmp('debug-details-panel');
    debugVarTpl.overwrite(detailPanel.body, r.data);
    detailPanel.setTitle(r.data.name);
  });
  
  eastPanelNortSubPanel = new Ext.TabPanel({
    border: false, // already wrapped so don't add another border
    activeTab: 0, // second tab initially active
    tabPosition: 'top',
    region:'north',
    split: true,
    height:detailsdebugVariablesHeight,
    items: [
      debugVariables,
      {
        html: '<p>trigger 1.',
        title: 'Trigers',
        autoScroll: true
      }
    ]
  });

  eastPanelCenterSubPanel = {
    id: 'debug-details-panel',
    title: '',
    id: 'debug-details',
    height:10,
    split: true,
    collapseMode: 'mini',
    margins: '5 0 0 0',
    region: 'center',
    autoScroll: true,
    xtype:'panel',
    html: ''
  }
  //bodyStyle: 'padding-bottom:15px;background:#eee;',

  eastPanel = {
    layout: 'border',
    id: 'east-panel',
    region:'east',
    border: false,
    split:true,
    margins: '2 0 5 5',
    width: 250,
    minSize: 100,
    maxSize: 500,
    
    collapsible: true,
    collapseMode: 'mini',
    items: [eastPanelNortSubPanel, eastPanelCenterSubPanel]
  };
  
  westPanel = {
      region: 'west',
      id: 'west-panel', // see Ext.getCmp() below
      title: 'User Cases',
      contentEl:'west',
      split: true,
      width: 200,
      minSize: 175,
      maxSize: 400,
      collapsible: true,
      collapseMode: 'mini',
      margins: '0 0 0 5',
      items: []
    }

  centerPanel = {
    region: 'center', // a center region is ALWAYS required for border layout
    xtype:'panel',
    deferredRender: false,
    contentEl:'casesSubFrame'
  }
  
  //tree
  menuTree = new Ext.Panel({
    id:'menuTreePanel',
    title: '',
    region: 'west',
    /*renderTo: 'tree',*/
    layout: 'border',
    width: 200,
    height: 500,
    minSize: 175,
    maxSize: 400,
    split: true,
    collapsible: true,
    collapseMode: 'mini',
    margins: '0 0 0 5',
    items: [{
      xtype: 'treepanel',
      height: 350,
      id: 'tree-panel',
      region: 'center',
      margins: '2 2 0 2',
      autoScroll: true,
      rootVisible: false,
      root: new Ext.tree.AsyncTreeNode(),

      // Our custom TreeLoader:
      loader: new Ext.app.menuLoader({
        dataUrl:'cases_menuLoader'
      }),

      listeners: {
        'render': function(tp){
          tp.getSelectionModel().on('selectionchange', function(tree, node){
            //alert(node.attributes.node.attributes.url);
        
            if( node.attributes.url )
              document.getElementById('casesSubFrame').src = node.attributes.url;
            
            var el = Ext.getCmp('details-panel').body;
            
            if(node && node.leaf){
              //alert(node.attributes.title);
              Ext.getCmp('details-panel').title = node.attributes.title;
              
              menuTreeDetailsTpl.overwrite(el, node.attributes);
              
            } else {
              el.update(detailsText);
            }
          })
        }
      }
    },{
        region: 'south',
        title: '',
        id: 'details-panel',
        autoScroll: true,
        collapsible: true,
        split: true,
        margins: '0 2 2 2',
        cmargins: '2 2 2 2',
        height: detailsMenuTreePanelHeight,
        html: detailsText
    }]  
  });
  
  Ext.QuickTips.init();

  var xg = Ext.grid;
  
  var reader = new Ext.data.JsonReader(
    {
      root: 'data',
      totalProperty: 'total',
      id: 'name'
    }, 
    [
      {name: 'name'},
      {name: 'execution_time'},
      {name: 'code'}
    ]
  );

  var store = new Ext.data.GroupingStore({
    reader: reader,
    sortInfo:{field: 'name', direction: "ASC"},
    groupField:'execution_time',
    proxy: new Ext.data.HttpProxy({url: 'debug_triggers'})
  });

  var debugTriggers = new xg.GridPanel({
      store: store,
      
      columns: [
          {id:'name',header: "Name", width: 60, sortable: true, dataIndex: 'name'},
          {header: "Execution", width: 30, sortable: true, dataIndex: 'execution_time'},
          {header: "Code", width: 30, sortable: false, dataIndex: 'code', hidden: true}
      ],

      view: new Ext.grid.GroupingView({
          forceFit:true,
          groupTextTpl: '{text} ({[values.rs.length]} {[ values.rs[0].data.execution_time=="error" || values.rs[0].data.execution_time=="Fatal error"? "<font color=red>"+values.rs[0].data.execution_time+"</font>":  values.rs.length > 1 ? "Triggers" : "Trigger"]})'
      }),

      width: 700,
      height: 450,
      title: 'Triggers',
      iconCls: 'icon-grid',
      tbar: [
       {text: 'Update', handler: resetTriggers},
       {text: 'show code', handler: show1}
      ],
      sm: new Ext.grid.RowSelectionModel({singleSelect: true}),
      viewConfig: {
        forceFit: true
      }
  });
  
  debugTriggers.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
    var detailPanel = Ext.getCmp('debug-details-panel');
    debugTriggersDetailTpl.overwrite(detailPanel.body, r.data);
    
    
  });
  
  function show1() {
    var r = debugTriggers.getSelectionModel().getSelected();
    if(r){
    var w = new Ext.Window({
      title: r.data.name,
      width: 500,
      height: 400,
      modal: false,
      items: []
    });
    w.show();

    debugTriggersDetailTpl.overwrite(w.body, r.data);
    }
  };


//////////////////////////

  
  
  debugPanel = new Ext.Panel({
    id:'debugPanel',
    title: '',
    region: 'east',
    /*renderTo: 'tree',*/
    layout: 'border',
    width: 300,
    height: 500,
    minSize: 175,
    maxSize: 400,
    split: true,
    collapsible: true,
    collapseMode: 'mini',
    margins: '0 0 0 5',
    items: [
      new Ext.TabPanel({
        border: true, // already wrapped so don't add another border
        activeTab: 0, // second tab initially active
        tabPosition: 'top',
        region:'north',
        split: true,
        height:detailsdebugVariablesHeight,
        items: [
          debugVariables,
          debugTriggers
        ]
      }),
      {
        region: 'center',
        title: '',
        id: 'debug-details-panel',
        autoScroll: true,
        collapsible: false,
        split: true,
        margins: '0 2 2 2',
        cmargins: '2 2 2 2',
        height: detailsMenuTreePanelHeight,
        html: detailsText
    }]  
  });
  
  
  var viewport = new Ext.Viewport({
    layout: 'border',
    items: [ menuTree, centerPanel, debugPanel]
  });

  
  /** after panel creation routines */ 
  var menuPanelC = Ext.getCmp('debugPanel');
  //w.collapse();
  
  /**hide*/
  //menuPanelC.hide(); 
  //menuPanelC.ownerCt.doLayout(); 
  
  /**show*/
  //w.show();
  //w.ownerCt.doLayout();
  //w.expand();       
  
 
  setDefaultOption();
});



  Ext.grid.dummyData = [
      ['3m Co','after'],
      ['Alcoa Inc','before'],
   
      
  ];

  // add in some dummy descriptions
  for(var i = 0; i < Ext.grid.dummyData.length; i++){
      Ext.grid.dummyData[i].push('Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Sed metus nibh, sodales a, porta at, vulputate eget, dui. Pellentesque ut nisl. Maecenas tortor turpis, interdum non, sodales non, iaculis ac, lacus. Vestibulum auctor, tortor quis iaculis malesuada, libero lectus bibendum purus, sit amet tincidunt quam turpis vel lacus. In pellentesque nisl non sem. Suspendisse nunc sem, pretium eget, cursus a, fringilla vel, urna.<br/><br/>Aliquam commodo ullamcorper erat. Nullam vel justo in neque porttitor laoreet. Aenean lacus dui, consequat eu, adipiscing eget, nonummy non, nisi. Morbi nunc est, dignissim non, ornare sed, luctus eu, massa. Vivamus eget quam. Vivamus tincidunt diam nec urna. Curabitur velit.');
  }

Ext.data.DynamicJsonReader = function(config){
  Ext.data.DynamicJsonReader.superclass.constructor.call(this, config, []);
};

Ext.extend(Ext.data.DynamicJsonReader, Ext.data.JsonReader, {
  getRecordType : function(data) {
  var i = 0, arr = [];

  for (var name in data[0]) { arr[i++] = name; } // is there a built-in to do this?
    this.recordType = Ext.data.Record.create(arr);
    return this.recordType;
  },
  readRecords : function(o){ // this is just the same as base class, with call to getRecordType injected

    this.jsonData = o;
    var s = this.meta;
    var sid = s.id;
    var totalRecords = 0;
    if(s.totalProperty){
      var v = parseInt(eval("o." + s.totalProperty), 10);
      if(!isNaN(v)){
        totalRecords = v;
      }
    }

    var root = s.root ? eval("o." + s.root) : o;
    var recordType = this.getRecordType(root);
    var fields = recordType.prototype.fields;
    var records = [];
    
    for(var i = 0; i < root.length; i++){
      var n = root[i];
        var values = {};
        var id = (n[sid] !== undefined && n[sid] !== "" ? n[sid] : null);
        for(var j = 0, jlen = fields.length; j < jlen; j++){
            var f = fields.items[j];
            var map = f.mapping || f.name;
            var v = n[map] !== undefined ? n[map] : f.defaultValue;
            v = f.convert(v);
            values[f.name] = v;
        }
        var record = new recordType(values, id);
        record.json = n;
        records[records.length] = record;
    }

    return {
        records : records,
        totalRecords : totalRecords || records.length
    };
  }
});

Ext.app.menuLoader = Ext.extend(Ext.ux.tree.XmlTreeLoader, {
  processAttributes : function(attr){
    if(attr.blockTitle){
      attr.text = attr.blockTitle;
      attr.iconCls = 'ICON_' + attr.id;
      attr.loaded = true;
      attr.expanded = true;
    }
    else if(attr.title){ 
      attr.text = attr.title;
      if( attr.cases_count )
        attr.text += ' (' + attr.cases_count + ')';
      
      attr.iconCls = 'ICON_' + attr.id;
      attr.leaf = true;
    }
  }
});

function setDefaultOption(){
  document.getElementById('casesSubFrame').src = "casesStartPage";
}

