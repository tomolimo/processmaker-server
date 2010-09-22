var main = function(){
  Ext.state.Manager.setProvider(new Ext.state.CookieProvider());
    
  var viewport = new Ext.Viewport({
    layout: 'border',
    items: [
    // create instance immediately
    new Ext.TabPanel({
      region: 'west',
      id: 'west-panel', // see Ext.getCmp() below
      title: 'West',
      split: true,
      width: 200,
      minSize: 175,
      maxSize: 400,
      collapsible: true,
      margins: '0 0 0 5',
      activeTab: 0,
      items: [ 
        new Ext.tree.TreePanel({
          title:'Settings',
          id: 'menu-settings',
          animate:true,
          autoScroll:true,
          loader: new Ext.tree.TreeLoader({
            dataUrl:'mainAjax?request=settingsMenu'
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
        })
      ,new Ext.tree.TreePanel({
        title:'Tools',
        id: 'menu-tools',
        animate:true,
        autoScroll:true,
        loader: new Ext.tree.TreeLoader({
          dataUrl:'mainAjax?request=toolsMenu'
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
      })]
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
