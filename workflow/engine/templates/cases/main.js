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

            // Tell the tree this is a leaf node.  This could also be passed as an attribute in the original XML,
            // but this example demonstrates that you can control this even when you cannot dictate the format of
            // the incoming source XML:
            attr.leaf = true;
        }
    }
});

Ext.onReady(function(){

  Ext.state.Manager.setProvider(new Ext.state.CookieProvider());

  var eastPanel;
  var eastPanelNortSubPanel;
  var eastPanelCenterSubPanel;
  var eastPanel;
  var westPanel;
  var centerPanel;
  
  var menuTree;
  
  var winSize = parent.getClientWindowSize(); 
  
  //alert(o.name);
  eastPanelNortSubPanel = new Ext.TabPanel({
    border: false, // already wrapped so don't add another border
    activeTab: 0, // second tab initially active
    tabPosition: 'top',
    region:'north',
    split: true,
    items: [
      new Ext.grid.PropertyGrid({
        title: 'Variables',
        width: 300,
        height: 320,
        
        startEditing: Ext.emptyFn,
        autoScroll: true,
        propertyNames: {
          tested: 'QA',
          borderWidth: 'Border Width'
        },
        source: {
          '(name)': 'Properties Grid',
          grouping: false,
          autoFitColumns: true,
          productionQuality: false,
          created: new Date(Date.parse('10/15/2006')),
          tested: false,
          version: 0.01,
          grouping2: false,
          autoFitColumns2: true,
          productionQuality2: false,
          created2: new Date(Date.parse('10/15/2006')),
          tested2: false,
          version2: 0.01,
          borderWidth: 1
        }
      }),
      {
        html: '<p>trigger 1.',
        title: 'Trigers',
        autoScroll: true
      }
    ]
  });

  eastPanelCenterSubPanel = {
    id: 'debug-details-panel',
    title: 'Details',
    size:100,
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
    id: 'layout-browser',
    region:'east',
    border: false,
    split:true,
    margins: '2 0 5 5',
    width: 275,
    minSize: 100,
    maxSize: 500,
    
    collapsible: true,
    collapseMode: 'mini',
    items: [eastPanelNortSubPanel, eastPanelCenterSubPanel]
  };

  /*westPanel = {
    region: 'west',
    id: 'west-panel', // see Ext.getCmp() below
    title: 'User Cases',
    split: true,
    width: 200,
    minSize: 175,
    maxSize: 400,
    collapsible: true,
    collapseMode: 'mini',
    margins: '0 0 0 5',
    layout: {
      type: 'accordion',
      animate: true
    },
    items: [{
      contentEl: 'west',
      title: 'Inbox',
      border: false,
      iconCls: 'nav' // see the HEAD section for style used
    }, {
      title: 'Drafts',
      html: '<p>...</p>',
      border: false,
      iconCls: 'settings'
    }]
  }*/
  
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
  var detailsText = '<i>...</i>';

  var tpl = new Ext.Template(
    '<span style="font-size:10">',
    '<h2 class="title">{title}</h2>',
    'Related Processes</b>: {processes_count}<br/>',
    '<span style="font-size:9">',
    '{innerText}',
    '</span>',
    '</span>'
  );
  
  tpl.compile();

  detailsMenuTreePanelHeight = winSize.height - 420;
  
  menuTree = new Ext.Panel({
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
              
              tpl.overwrite(el, node.attributes);
              
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
  

  var viewport = new Ext.Viewport({
    layout: 'border',
    items: [ eastPanel, menuTree, centerPanel]
  });

  
  /** after panel creation routines */ 
  var w = Ext.getCmp('east-panel');
  //w.collapse();
  
 
  setDefaultOption();
});

function setDefaultOption(){
  document.getElementById('casesSubFrame').src = "casesStartPage";
}