var main = function(){
  Ext.state.Manager.setProvider(new Ext.state.CookieProvider());

  var tabSettings = new Ext.tree.TreePanel({
    title:'Settings',
    id: 'menu-settings',
    animate:true,
    autoScroll:true,
    loader: new Ext.tree.TreeLoader({
      dataUrl:'mainAjax?request=loadMenu&menu=setting&r='+Math.random()
    }),
    enableDD:true,
    containerScroll: true,
    border: false,
    width: 250,
    height: 120,
    dropConfig: {appendOnly:true},
    margins: '0 2 2 2',
    cmargins: '2 2 2 2',
    rootVisible: false,
    root: new Ext.tree.AsyncTreeNode(),
    /*tbar: [{
      text: 'reload',
      handler: function(){}
    }],*/
    listeners: {
      'click': function(tp) {
        if( tp.attributes.url ){
          document.getElementById('setup-frame').src = tp.attributes.url;
        }
      }
    }
  });

var tabMaintenance = new Ext.tree.TreePanel({
    title:'Maintenance',
    id: 'menu-maintenance',
    animate:true,
    autoScroll:true,
    loader: new Ext.tree.TreeLoader({
      dataUrl:'mainAjax?request=loadMenu&menu=maintenance&r='+Math.random()
    }),
    enableDD:true,
    containerScroll: true,
    border: false,
    width: 250,
    height: 120,
    dropConfig: {appendOnly:true},
    margins: '0 2 2 2',
    cmargins: '2 2 2 2',
    rootVisible: false,
    root: new Ext.tree.AsyncTreeNode(),
    listeners: {
      'click': function(tp) {
        if( tp.attributes.url ){
          document.getElementById('setup-frame').src = tp.attributes.url;
        }
      }
    }
  });

  var tabTools = new Ext.tree.TreePanel({
    title:'Tools',
    id: 'menu-tools',
    animate:true,
    autoScroll:true,
    loader: new Ext.tree.TreeLoader({
      dataUrl:'mainAjax?request=loadMenu&menu=tool&r='+Math.random()
    }),
    enableDD:true,
    containerScroll: true,
    border: false,
    width: 250,
    height: 120,
    dropConfig: {appendOnly:true},
    margins: '0 2 2 2',
    cmargins: '2 2 2 2',
    rootVisible: false,
    root: new Ext.tree.AsyncTreeNode(),
    listeners: {
      'click': function(tp) {
        if( tp.attributes.url ){
          document.getElementById('setup-frame').src = tp.attributes.url;
        }
      }
    }
  });

  //tabItems = [{id:'settings', title:'Settings'}, {id:'maintenance', title:'Maintenance'}, {id:'tools', title:'Tools'}];

  items = Array();
  
  for(i=0; i<tabItems.length; i++){
    items[i] = new Ext.tree.TreePanel({
      title: tabItems[i].title,
      id: tabItems[i].id,
      animate:true,
      autoScroll:true,
      loader: new Ext.tree.TreeLoader({
        dataUrl:'mainAjax?request=loadMenu&menu='+tabItems[i].id+'&r='+Math.random()
      }),
      enableDD:true,
      containerScroll: true,
      border: false,
      width: 250,
      height: 120,
      dropConfig: {appendOnly:true},
      margins: '0 2 2 2',
      cmargins: '2 2 2 2',
      rootVisible: false,
      root: new Ext.tree.AsyncTreeNode(),
      listeners: {
        'click': function(tp) {
          if( tp.attributes.url ){
            document.getElementById('setup-frame').src = tp.attributes.url;
          }
        }
      }
    });
  }

  var viewport = new Ext.Viewport({
    layout: 'border',
    items: [
    // create instance immediately
    new Ext.TabPanel({
      region: 'west',
      id: 'west-panel', // see Ext.getCmp() below
      title: 'West',
      split: true,
      width: 240,
      minSize: 175,
      maxSize: 400,
      collapsible: true,
      animCollapse: true,
      
      margins: '0 0 0 5',
      activeTab: 0,
      enableTabScroll: true,
      items: items
    }),
    {
       region: 'center', // a center region is ALWAYS required for border
       contentEl: 'setup-frame',
    }]
  });
  //oClientWinSize = parent.getClientWindowSize();
  //parent.document.getElementById('adminFrame').style.height = oClientWinSize.height-105;  
}

Ext.onReady(main);

function getPosition(obj){
    var topValue= 0,leftValue= 0;
    while(obj){
	leftValue+= obj.offsetLeft;
	topValue+= obj.offsetTop;
	obj= obj.offsetParent;
    }
    finalvalue = leftValue + "," + topValue;
    return finalvalue;
}
