/*new Ext.KeyMap(document, {
  key: Ext.EventObject.F5,
  fn: function(keycode, e) {
    if (! e.ctrlKey) {
      if (Ext.isIE) {
        e.browserEvent.keyCode = 8;
      }
      e.stopEvent();
      updateCasesTree();
    } 
    else 
      Ext.Msg.alert('Refresh', 'You clicked: CTRL-F5');
  }
});*/

var Actions = {};
var showCaseNavigatorPanel;
var hideCaseNavigatorPanel;
var informationMenu;
var caseMenuOpen = false;
var menuSelectedTitle = Array();

var _ENV_CURRENT_DATE;


Ext.onReady(function(){
  Ext.QuickTips.init();

  showCaseNavigatorPanel = function(steps, information, action) {
    
    if( caseMenuOpen ) 
      return false;
    else
      caseMenuOpen = true;

    //alert(steps+' '+information+' '+action);
    
    //getting the case Information availables options
    Ext.Ajax.request({
      url : 'ajaxListener' , 
      params : { action : 'getInformationOptions' },
      success: function ( result, request ) { 
        var data = Ext.util.JSON.decode(result.responseText); 
        var informationMenu = Ext.getCmp('informationMenu');
        //tb = Ext.getCmp('navPanelNorth').getTopToolbar();
        informationMenu.menu.removeAll();
        for(i=0; i<data.length; i++) {
          informationMenu.menu.add({
            text: data[i].text,
            handler: data[i].fn != '' ? Actions[data[i].fn] : function(){}
          });
          menuSelectedTitle[data[i].fn] = data[i].text;
        }
      },
      failure: function ( result, request) { 
        Ext.MessageBox.alert('Failed', result.responseText); 
      }
    });

    //getting the case action availables options
    Ext.Ajax.request({
      url : 'ajaxListener' , 
      params : { action : 'getActionOptions' },
      success: function ( result, request ) { 
        var data = Ext.util.JSON.decode(result.responseText); 
        var actionMenu = Ext.getCmp('actionMenu');
        
        actionMenu.menu.removeAll();
        for(i=0; i<data.length; i++) {
          actionMenu.menu.add({
              text: data[i].text,
            handler: data[i].fn != '' ? Actions[data[i].fn] : function(){}
          });
          menuSelectedTitle[data[i].fn] = data[i].text;
        }
      },
      failure: function ( result, request) { 
        Ext.MessageBox.alert('Failed', result.responseText); 
      }
    });

    Ext.getCmp('casesStepTree').root.reload();
    
    if( steps)
      Ext.getCmp('stepsMenu').show();
    
    if( information )
      Ext.getCmp('informationMenu').show();
    
    if( action )
      Ext.getCmp('actionMenu').show();
    
  }
  
  
  hideCaseNavigatorPanel = function(){
    Ext.getCmp('stepsMenu').pressed = false;
    caseMenuOpen = false;
    
    Ext.getCmp('navPanelWest').hide();
    Ext.getCmp('navPanelWest').ownerCt.doLayout(); 
    
  }

 
  function togglePreview(btn, pressed){
    var preview = Ext.getCmp('navPanelWest');
    preview[pressed ? 'show' : 'hide']();
    //preview.ownerCt.doLayout();
    Ext.getCmp('navPanel').ownerCt.doLayout();
  }
  
  var casesStepTree = new Ext.tree.TreePanel({
    id: 'casesStepTree',
    //title: 'Reporting Project',
    autoWidth: true,
    userArrows: true,
    animate: true,
    autoScroll: true,
    dataUrl: 'ajaxListener?action=steps',
    rootVisible: false,
    containerScroll: true,
    border: false,
    root: {
      nodeType: 'async'
    },
    listeners: {
      render: function() {
        this.getRootNode().expand();
      },
      click: function(tp) {
        if( tp.attributes.url ){
         
          document.getElementById('openCaseFrame').src = tp.attributes.url;
          
        }
      }
    }
  })
  
  var navPanelWest = {
    id: 'navPanelWest',
    region: 'west',
    xtype:'panel',
    //deferredRender: false,
    //contentEl:'casesSubFrame'
     //layout: 'border',
    width: 250,
    height: 500,
    //minSize: 175,
    maxSize: 400,
    split: true,
    collapsible: false,
//    collapseMode: 'mini',
    margins: '0 0 0 0',
    items:[casesStepTree]
  }
  
  var fnChangeStatus  =function(){
    alert('loaded');
  }

  var navPanelCenter = {
    id: 'navPanelCenter',
    region: 'center',
    xtype:'panel',
    //html:'addd'
    //deferredRender: true
    //contentEl:'openCaseFrame'
    items:[{
        xtype:"tabpanel",
        id: 'caseTabPanel',
        deferredRender:false,
        defaults:{autoScroll: true},
        defaultType:"iframepanel",
        activeTab: 0,
        
        //defaults: Ext.apply({}, Ext.isGecko? {style:{position:'absolute'},hideMode:'visibility'}:false),
        
        items:[{
          title: _('ID_CASE') +' ' + parent._CASE_TITLE,
          frameConfig:{name:'openCaseFrame', id:'openCaseFrame'},
          defaultSrc : uri,
          loadMask:{msg:'Loading...'},
          bodyStyle:{height: (PMExt.getBrowser().screen.height-55) + 'px', overflow:'scroll'},
          width:'1024px'
          
          }/*{
            xtype:"panel",
            title: parent.CASE_TITLE,
            items:[new Ext.ux.IFrameComponent({ id: 'openCaseFrame', url: uri }) ],
            loadMask:{msg:'Loading Quote...'}
          }*/
        ],
        listeners: {
          /*tabchange: function(tp,newTab){
             um = newTab.getUpdater();
             if(um) um.abort();
             //return false;
             
          }*/
          render : function(panel){
         Ext.each([this.el, this[this.collapseEl]] , 
          function( elm ) {
            elm.setVisibilityMode(Ext.Element.VISIBILITY).originalDisplay ='visible';
            });
          }
        }

    }]
  };

  var navPanel = {
    id: 'navPanel',
    region: 'center',
    //xtype:'border',
    //deferredRender: false,
    //contentEl:'casesSubFrame'
    layout: 'border',
 
    items:[navPanelWest, navPanelCenter],
    tbar:[{
      id: 'stepsMenu',
      text: '&nbsp;&nbsp;Steps',
      pressed: false,
      enableToggle:true,
      tooltip: {
        title:'Preview Pane',
        text:'Show or hide the Preview Pane'
      },
      iconCls: 'ICON_STEPS',
      toggleHandler: togglePreview
    }, {
      id: 'informationMenu',
      text: 'Information',
      menu: []//,
      //disabled: false
    }, {
      id: 'actionMenu',
      text: 'Actions',
      menu: []
    }]
    //html:'sds'
  }
  
  var viewport = new Ext.Viewport({
      layout: 'border',
      items: [navPanel]
  }); 


  Ext.getCmp('stepsMenu').hide();
  Ext.getCmp('informationMenu').hide();
  Ext.getCmp('actionMenu').hide();
    
  hideCaseNavigatorPanel();

  // Actions methods
  
  Actions.processMap = function()
  {
    Actions.tabFrame('processMap');
  }
  
  Actions.processInformation = function()
  {
    Ext.Ajax.request({
      url : 'ajaxListener' , 
      params : { action : 'getProcessInformation' },
      success: function ( result, request ) { 
        var data = Ext.util.JSON.decode(result.responseText); 
    
        fieldset = {
          xtype : 'fieldset',
          autoHeight  : true,
          defaults    : { 
            width : 170, 
            xtype:'label',
            labelStyle : 'padding: 0px;',
            style: 'font-weight: bold'
          },
          items       : [
            {fieldLabel: 'Title', text: data.PRO_TITLE}, 
            {fieldLabel: 'Description', text: data.PRO_DESCRIPTION},
            {fieldLabel: 'Category', text: data.PRO_CATEGORY_LABEL},
            {fieldLabel: 'Author', text: data.PRO_AUTHOR},
            {fieldLabel: 'Create date', text: data.PRO_CREATE_DATE},
          ]
        }
    
        var frm = new Ext.FormPanel( {
          labelAlign : 'right',
          bodyStyle : 'padding:5px 5px 0',
          width : 400,
          items : [fieldset],
          buttons : [{
            text : 'OK',
            handler : function() {
              win.close();
            }
          }]   
        });
    
        var win = new Ext.Window({
          title: '',
          width: 450,
          height: 280,
          layout:'fit',
          autoScroll:true,
          modal: true,
          maximizable: false,
          items: [frm]
        });
        win.show();
      },
      failure: function ( result, request) { 
        Ext.MessageBox.alert('Failed', result.responseText); 
      }
    });
  }

  Actions.taskInformation = function()
  {
    Ext.Ajax.request({
      url : 'ajaxListener' , 
      params : { action : 'getTaskInformation' },
      success: function ( result, request ) { 
        var data = Ext.util.JSON.decode(result.responseText); 
    
        fieldset = {
          xtype : 'fieldset',
          autoHeight  : true,
          defaults    : { 
            width : 170, 
            xtype:'label',
            labelStyle : 'padding: 0px;',
            style: 'font-weight: bold'
          },
          items       : [
            {fieldLabel: 'Title', text: data.TAS_TITLE}, 
            {fieldLabel: 'Description', text: data.TAS_DESCRIPTION},
            {fieldLabel: 'Init Date', text: data.INIT_DATE},
            {fieldLabel: 'Due Date', text: data.DUE_DATE},
            {fieldLabel: 'Finish Date', text: data.FINISH},
            {fieldLabel: 'Duration', text: data.DURATION},
          ]
        }
    
        var frm = new Ext.FormPanel( {
          labelAlign : 'right',
          bodyStyle : 'padding:5px 5px 0',
          width : 400,
          items : [fieldset],
          buttons : [{
            text : 'OK',
            handler : function() {
              win.close();
            }
          }]   
        });
    
        var win = new Ext.Window({
          title: '',
          width: 450,
          height: 280,
          layout:'fit',
          autoScroll:true,
          modal: true,
          maximizable: false,
          items: [frm]
        });
        win.show();
      },
      failure: function ( result, request) { 
        Ext.MessageBox.alert('Failed', result.responseText); 
      }
    });
  }
  
  Actions.caseHistory = function()
  {
    Actions.tabFrame('caseHistory');
  }
  
  Actions.messageHistory = function()
  {
    Actions.tabFrame('messageHistory');
  }
  
  Actions.dynaformHistory = function()
  {
    Actions.tabFrame('dynaformHistory');
  }
  
  Actions.uploadedDocuments = function()
  {
    Actions.tabFrame('uploadedDocuments');
  }
  
  Actions.generatedDocuments = function()
  {
    Actions.tabFrame('generatedDocuments');
  }
  
  Actions.cancelCase = function()
  {
    PMExt.confirm(_('ID_CONFIRM'), _('ID_CONFIRM_CANCEL_CASE'), function(){
      Ext.Ajax.request({
        url : 'ajaxListener' , 
        params : { action : 'cancelCase' },
        success: function ( result, request ) {
          parent.notify('', 'The case ' + parent._CASE_TITLE + ' was cancelled!');
          location.href = 'casesListExtJs';
        },
        failure: function ( result, request) {
          Ext.MessageBox.alert('Failed', result.responseText); 
        }
      });
    });
  }
  
  Actions.getUsersToReassign = function()
  {
    var store = new Ext.data.Store( {
      autoLoad: true,
      proxy : new Ext.data.HttpProxy({
        url: 'ajaxListener?action=getUsersToReassign'
      }),
      reader : new Ext.data.JsonReader( {
        root: 'data',
        fields : [
          {name : 'USR_UID'},
          {name : 'PRO_USERNAME'},
          {name : 'USR_FIRSTNAME'},
          {name : 'PRO_LASTNAME'}
        ]
      })
    });
    
    var grid = new Ext.grid.GridPanel( {
      id: 'reassignGrid',
      height:300,
      width:'300',
      title : '',
      stateful : true,
      stateId : 'grid',
      enableColumnResize: true,
      enableHdMenu: true,
      frame:false,
      cls : 'grid_with_checkbox',
      columnLines: true,

      viewConfig: {
        forceFit:true
      },
      
      cm: new Ext.grid.ColumnModel({
        defaults: {
            width: 200,
            sortable: true
        },    
        columns: [
          {id:'USR_UID', dataIndex: 'USR_UID', hidden:true, hideable:false},
          {header: _('ID_FIRSTNAME'), dataIndex: 'USR_FIRSTNAME', width: 300},
          {header: _('ID_LASTNAME'), dataIndex: 'USR_LASTNAME', width: 300}
        ]
      }),

      store: store,

      tbar:[
        {
          text:_('ID_REASSIGN'),
          iconCls: 'ICON_CASES_TO_REASSIGN',
          handler: Actions.reassignCase
        }
      ],
      listeners: {
        //rowdblclick: openCase,
        render: function(){
          this.loadMask = new Ext.LoadMask(this.body, {msg:_('ID_LOADING')});
          this.ownerCt.doLayout();
        }
      }
    });
    
    var win = new Ext.Window({
      title: '',
      width: 450,
      height: 280,
      layout:'fit',
      autoScroll:true,
      modal: true,
      maximizable: false,
      items: [grid]
    });
    win.show();
  }
  
  Actions.reassignCase = function()
  {
    var rowSelected = Ext.getCmp('reassignGrid').getSelectionModel().getSelected();
    if( rowSelected ) {
      PMExt.confirm(_('ID_CONFIRM'), _('ID_REASSIGN_CONFIRM'), function(){
        Ext.Ajax.request({
          url : 'ajaxListener' , 
          params : { action : 'reassignCase', USR_UID: rowSelected.data.USR_UID},
          success: function ( result, request ) {
            var data = Ext.util.JSON.decode(result.responseText); 
            if( data.status == 0 ) {
              parent.notify('', data.msg);
              location.href = 'casesListExtJs';
            } else {
              alert(data.msg);
            }
          },
          failure: function ( result, request) {
            Ext.MessageBox.alert('Failed', result.responseText); 
          }
        });
      });
    }          
  }
  
  Actions.setUnpauseCaseDate = function()
  {
    var fieldset = {
      xtype : 'fieldset',
      autoHeight  : true,
      defaults    : { 
        width : 170,
        xtype:'label',
        labelStyle : 'padding: 0px;',
        style: 'font-weight: bold'
      },
      items : [
        {fieldLabel: 'Case', text: parent._CASE_TITLE}, 
        {fieldLabel: 'Pause Date', text: _ENV_CURRENT_DATE},
        new Ext.form.DateField({
          id: 'unpauseDate',
          format: 'Y-m-d',
          fieldLabel: 'Unpause Date',
          name: 'unpauseDate',
          allowBlank:false
        })
      ],
      buttons : [
        {
          id: 'submitPauseCase',
          text : 'Pause Case',
          handler : Actions.pauseCase,
          disabled:false
        },{
          text : 'Cancel',
          handler : function() {
            win.close();
          }
        }
      ]   
    }

    var frm = new Ext.FormPanel( {
      id: 'unpauseFrm',
      labelAlign : 'right',
      bodyStyle : 'padding:5px 5px 0',
      width : 250,
      items : [fieldset]
    });
    
    
    var win = new Ext.Window({
      title: 'Pause Case',
      width: 340,
      height: 170,
      layout:'fit',
      autoScroll:true,
      modal: true,
      maximizable: false,
      items: [frm]
    });
    win.show();
  }
  
  Actions.pauseCase = function()
  {
    
    var unpauseDate = Ext.getCmp('unpauseDate').getValue();
    if( unpauseDate == '') {
      //Ext.getCmp('submitPauseCase').setDisabled(true);
      return;
    } else 
      //Ext.getCmp('submitPauseCase').enable();

    unpauseDate = unpauseDate.format('Y-m-d');
    
    Ext.getCmp('unpauseFrm').getForm().submit({
      url:'ajaxListener?action=pauseCase&unpauseDate=' + unpauseDate, 
      waitMsg:'Pausing Case '+parent._CASE_TITLE+'...',
      timeout : 36000,
      success : function(res, req) {
        if(req.result.success) {
          parent.notify('PAUSE CASE', req.result.msg);
          location.href = 'casesListExtJs';
        } else {
          PMExt.error(_('ID_ERROR'), req.result.msg);
        }
      } 
    });
  }

  Actions.unpauseCase = function()
  {
    PMExt.confirm(_('ID_CONFIRM'), _('ID_CONFIRM_UNPAUSE_CASE'), function(){
      var loadMask = new Ext.LoadMask(document.body, {msg:'Unpausing case...'});
      loadMask.show();
      
      Ext.Ajax.request({
        url : 'ajaxListener' ,
        params : { action : 'unpauseCase' },
        success: function ( result, request ) {
          loadMask.hide();
          var data = Ext.util.JSON.decode(result.responseText); 
          if( data.success ) {
            parent.PMExt.notify(_('ID_UNPAUSE_ACTION'), data.msg);
            location.href = 'casesListExtJs';
          } else {
            PMExt.error(_('ID_ERROR'), data.msg);
          }
        },
        failure: function ( result, request) {
          Ext.MessageBox.alert('Failed', result.responseText);
        }
      });
    });
  }

  Actions.deleteCase = function()
  {
    PMExt.confirm(_('ID_CONFIRM'), _('ID_CONFIRM_DELETE_CASE'), function(){
      var loadMask = new Ext.LoadMask(document.body, {msg:'Deleting case...'});
      loadMask.show();
      Ext.Ajax.request({
        url : 'ajaxListener' ,
        params : { action : 'deleteCase' },
        success: function ( result, request ) {
          loadMask.hide();
          var data = Ext.util.JSON.decode(result.responseText); 
          if( data.success ) {
            parent.PMExt.notify(_('ID_DELETE_ACTION'), data.msg);
            location.href = 'casesListExtJs';
          } else {
            PMExt.error(_('ID_ERROR'), data.msg);
          }
        },
        failure: function ( result, request) {
          Ext.MessageBox.alert('Failed', result.responseText);
        }
      });
    });
  }

  Actions.reactivateCase = function()
  {
    PMExt.confirm(_('ID_CONFIRM'), _('ID_CONFIRM_REACTIVATE_CASE'), function(){
      var loadMask = new Ext.LoadMask(document.body, {msg:'Reactivating case...'});
      loadMask.show();
      Ext.Ajax.request({
        url : 'ajaxListener' ,
        params : { action : 'reactivateCase' },
        success: function ( result, request ) {
          loadMask.hide();
          var data = Ext.util.JSON.decode(result.responseText); 
          if( data.success ) {
            parent.PMExt.notify(_('ID_REACTIVATE_ACTION'), data.msg);
            location.href = 'casesListExtJs';
          } else {
            PMExt.error(_('ID_ERROR'), data.msg);
          }
        },
        failure: function ( result, request) {
          Ext.MessageBox.alert('Failed', result.responseText);
        }
      });
    });
  }
  
  //
  Actions.tabFrame = function(name)
  {
    tabId = name + 'MenuOption';
    var uri = 'ajaxListener?action=' + name;
    var TabPanel = Ext.getCmp('caseTabPanel');
    var tab = TabPanel.getItem(tabId);
    
    if( tab ) {
      TabPanel.setActiveTab(tabId);
    } else {
      TabPanel.add({
        id: tabId,
        title: menuSelectedTitle[name],
        frameConfig:{name: name + 'Frame', id: name + 'Frame'},
        defaultSrc : uri,
        loadMask:{msg:'Loading...'},
        bodyStyle:{height:'600px'},
        width:'1024px',
        closable:true
      }).show();
      
      TabPanel.doLayout();
    }
  }
  
  
});


Ext.ux.IFrameComponent = Ext.extend(Ext.BoxComponent, {
  onRender : function(ct, position){
    this.el = ct.createChild({tag: 'iframe', id: this.id, name: this.id, frameBorder: 0, src: this.url,  width:'100%', height:"768"});
  }
});




