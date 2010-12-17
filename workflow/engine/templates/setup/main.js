/*
 * @author: Erik A. Ortiz
 * Aug 20th, 2010 
 */

var _NODE_SELECTED;
var main = function(){
  Ext.state.Manager.setProvider(new Ext.state.CookieProvider());
  
  var items = Array();
  var i;
 
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
            _NODE_SELECTED = tp.id;
            document.getElementById('setup-frame').src = tp.attributes.url;
          }
        },
        'render': function(tp){
        	
          var loader = tp.getLoader();
	    	loader.on("load", function(){
	        if( _item_selected != '' ){
	          node = tp.getNodeById(_item_selected);
	    	  document.getElementById('setup-frame').src = node.attributes.url;
	    	  if(node){
	    	    node.select();
	    	    _NODE_SELECTED = node.attributes.id;
	    	  }
	    	}
	      });
        }
      }
    });
  }

  var viewport = new Ext.Viewport({
    layout: 'border',
    items: [
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
        stateId:'admin-tabpanel',
        stateEvents:['tabchange'],
        getState:function() {
          return {
            activeTab:this.items.indexOf(this.getActiveTab())
          };
        },
        items: items
      }),
      {
        region: 'center', // a center region is ALWAYS required for border
        contentEl: 'setup-frame'
      }
    ]
  });
  //oClientWinSize = parent.getClientWindowSize();
  //parent.document.getElementById('adminFrame').style.height = oClientWinSize.height-105;  
}

new Ext.KeyMap(document, {
  key: Ext.EventObject.F5,
    fn: function(keycode, e) {
    	if (! e.ctrlKey) {
        if (Ext.isIE) {
            // IE6 doesn't allow cancellation of the F5 key, so trick it into
            // thinking some other key was pressed (backspace in this case)
            e.browserEvent.keyCode = 8;
        }
        e.stopEvent();
        //document.location = document.location;
        document.getElementById('setup-frame').src = document.getElementById('setup-frame').src;
      }
      else
    Ext.Msg.alert('Refresh', 'You clicked: CTRL-F5');
  }
});

Ext.onReady(main);


