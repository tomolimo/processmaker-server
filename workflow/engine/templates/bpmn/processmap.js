Ext.onReady ( function() {

  workflow  = new MyWorkflow("paintarea");
  workflow.setEnableSmoothFigureHandling(false);
  workflow.scrollArea.width = 2000;
  //For Undo and Redo Options
  // workflow.getCommandStack().addCommandStackEventListener(new commandListener());
  //Getting process id from the URL using getUrlvars function
  

  if(typeof pro_uid !== 'undefined') {
    Ext.Ajax.request({
      url: 'openProcess.php?PRO_UID=' + pro_uid,
      success: function(response) {
        //shapesData =  createShapes(response.responseText,this);
        //createConnection(shapesData);
      },
      failure: function(){
        Ext.Msg.alert ('Failure');
      }
    });
  }

  

  /**********************************************************************************
  *
  * Do the Ext (Yahoo UI) Stuff
  *
  **********************************************************************************/
  var west= {
    id         : 'palette',
    title      : 'Palette',
    region     : 'west',
    width      : 65,
    border     : false,
    autoScroll : true,
    collapsible :true,
    split       :true,
    //collapseMode:'mini',
    hideCollapseTool: false,

    items:{
      html:'<div id="x-shapes">\n\
              <p id="x-shapes-task" title="Task" ><img src= "/skins/ext/images/gray/shapes/pallete/task.png"/></p>\n\
              <p id="x-shapes-startEvent" title="Start"><img src= "/skins/ext/images/gray/shapes/pallete/startevent.png"/></p>\n\
              <p id="x-shapes-interEvent" title="Intermediate Event"><img src= "/skins/ext/images/gray/shapes/pallete/interevent.png"/></p>\n\
              <p id="x-shapes-endEvent" title="End Event"><img src= "/skins/ext/images/gray/shapes/pallete/endevent.png"/></p>\n\
              <p id="x-shapes-gateways" title="Gateway"><img src= "/skins/ext/images/gray/shapes/pallete/gateway.png"/></p>\n\
              <p id="x-shapes-annotation" title="Annotation"><img src= "/skins/ext/images/gray/shapes/pallete/annotation.png"/></p>\n\
              <!--<p id="x-shapes-group" title="Group"><img src= "/skins/ext/images/gray/shapes/pallete/group.png"/></p>\n\
              <p id="x-shapes-dataobject" title="Data Object"><img src= "/skins/ext/images/gray/shapes/pallete/dataobject.png"/></p>\n\
              <p id="x-shapes-pool" title="Pool"><img src= "/skins/ext/images/gray/shapes/pallete/pool.png"/></p>\n\
              <p id="x-shapes-lane" title="Lane"><img src= "/skins/ext/images/gray/shapes/pallete/lane.png"/></p>\n\
              <p id="x-shapes-milestone" title="Milestone"><img src= "/skins/ext/images/gray/shapes/pallete/milestone.png"/></p>-->\n\
            </div>'
    }
  };
  
  var east= {
    id         : 'eastPanel',
    title      : '',
    region     : 'east',
    width      : 150,
    border     : false,
    autoScroll : true,
    collapsible :true,
    split       :true,
    collapseMode:'mini',
    hideCollapseTool: false,

    items:{
      html:'east panel'
    }
  };

  var north= {
    xtype	:	"panel",
    initialSize: 60,
    split:false,
    titlebar: false,
    collapsible: false,
    animate: false,
    region	:	"north"
  };
  
  var south= {
    xtype	:	"panel",
    initialSize: 120,
    height: 100,
    split:true,
    titlebar: false,
    collapsible: true,
    autoScroll:true,
    animate: true,
    region	:	"south",
    items: {
        region: 'center',
        xtype: 'tabpanel',
        items: [{
            title: 'Properties',
            html: 'Properties'
        },
        {
            title: 'Debug Console',
            html: 'Debug Console'
        }]
    }
  };

  var center= {
    region: 'center',
    width:100,
    height:2000,
    xtype	:	"iframepanel",
    title   : "BPMN Processmap - " + pro_title,

    frameConfig:{name:'designerFrame', id:'designerFrame'},
    defaultSrc : 'designer?PRO_UID=' + pro_uid,
    loadMask:{msg:'Loading...'},
    bodyStyle:{height: (_BROWSER.screen.height-55) + 'px', overflow:'scroll'},
    width:'1024px'
  };

  var processObj = new ProcessOptions();

  var main = new Ext.Panel({
    renderTo  : "center1",
    region    : "center",
    layout    : "border",
    autoScroll: true,
    height    : 1000,
    width     : 1300,    
    items   : [north, center],
    tbar: [
      {
        text: 'Save',
        cls: 'x-btn-text-icon',
        iconCls: 'button_menu_ext ss_sprite ss_disk',
        handler: function() {
          saveProcess();
        }
      }, {
          text:'Save as'
      }, {
        text:'Undo',
        iconCls: 'button_menu_ext ss_sprite ss_arrow_undo',
        handler: function() {
          document.getElementById('designerFrame').contentWindow.workflow.getCommandStack().undo();
        }
      }, {
        text:'Redo',
        iconCls: 'button_menu_ext ss_sprite ss_arrow_redo',
        handler: function() {
          document.getElementById('designerFrame').contentWindow.workflow.getCommandStack().redo();
        }
      }, {
        xtype: 'tbsplit',
        iconCls: 'button_menu_ext ss_sprite ss_application',
        text: 'Process',
        menu: new Ext.menu.Menu({
          items: [{
              text    : 'Dynaform',
              iconCls: 'button_menu_ext ss_sprite ss_application_form',
              handler : function() {
                processObj.addDynaform();
              }
            }, {
              text: 'Input Document',
              iconCls: 'button_menu_ext ss_sprite ss_page_white_put',
              handler : function() {
                processObj.addInputDoc();
              }
            }, {
              text: 'Output Document',
              iconCls: 'button_menu_ext ss_sprite ss_page_white_get',

              handler : function() {
                processObj.addOutputDoc();
              }
            }, {
              text: 'Trigger',
              iconCls: 'button_menu_ext ss_sprite ss_cog',
              handler : function() {
                processObj.addTriggers();
              }
            },
            {
              text: 'Report Table',
              iconCls: 'button_menu_ext ss_sprite ss_table',
              handler : function() {
                processObj.addReportTable();
              }
            },
            {
              text: 'Database Connection',
              iconCls: 'button_menu_ext ss_sprite ss_database_connect',
              handler : function() {
                processObj.dbConnection();
              }
            }
          ]
        })

      }, {
        text:'Zoom In',
        iconCls: 'button_menu_ext ss_sprite ss_zoom_in',
        handler: function() {
          document.getElementById('designerFrame').contentWindow.workflow.zoom('in');
        }
      }, {
        text:'Zoom Out',
        iconCls: 'button_menu_ext ss_sprite ss_zoom_out',
        handler: function() {
          document.getElementById('designerFrame').contentWindow.workflow.zoom('out');
        }
      }
    ]
  });

  
  var viewport = new Ext.Viewport({
    id:'viewPort1'
    ,layout:'border'
    ,border:false,
    items:[main]
  });
  
});
