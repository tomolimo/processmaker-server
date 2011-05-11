var Actions = {};
var showCaseNavigatorPanel;
var hideCaseNavigatorPanel;
var informationMenu;
var caseMenuOpen = false;
var toReviseTreeOpen = false;
var menuSelectedTitle = Array();
var _ENV_CURRENT_DATE;

Ext.onReady(function(){
  Ext.QuickTips.init();
  
  showCaseNavigatorPanel = function(app_status) {
    
    if (typeof(treeToReviseTitle) != 'undefined'){
      var treeToRevise = new Ext.tree.TreePanel({
        title: treeToReviseTitle,
        width: 250,
        height: 250,
        userArrows: true,
        animate: true,
        autoScroll: true,
        rootVisible: false,
        dataUrl: casesPanelUrl,
        root: {
            nodeType : 'async',
            text     : 'To Revise',
            id       : 'node-root'
        },
        listeners: {
            render: function() {
                this.expandAll();
            }
        }
      });

      if(typeof(winTree)=='undefined'){
        var winTree = new Ext.Window({
          id:'toReviseWindow',
          width:220,
          height:300,
          el:'toReviseTree',
          collapsible: true,
          plain: true,
          x:100,
          y:100,
          closeAction:'hide',
          items: [treeToRevise]
        });
      }

      if (!toReviseTreeOpen){
        winTree.show(this);
        toReviseTreeOpen = true;
      }
    }

    if( caseMenuOpen )
      return false;
    else
      caseMenuOpen = true;
    
    //get the menu 
    Ext.Ajax.request({
      url : 'ajaxListener', 
      params : {action : 'getCaseMenu', app_status:app_status},
      success: function ( result, request ) { 
        var data = Ext.util.JSON.decode(result.responseText); 
        for(i=0; i<data.length; i++) {
          switch(data[i].id) {
            case 'STEPS':
              Ext.getCmp('casesStepTree').root.reload();
              Ext.getCmp('stepsMenu').show();
              break;
              
            case 'INFO':
              //filling information menu
              var informationMenu = Ext.getCmp('informationMenu');
              informationMenu.menu.removeAll();
              for(j=0; j<data[i].options.length; j++) {
                informationMenu.menu.add({
                  text: data[i].options[j].text,
                  handler: data[i].options[j].fn != '' ? Actions[data[i].options[j].fn] : function(){}
                });
                menuSelectedTitle[data[i].options[j].fn] = data[i].options[j].text;
              }
              informationMenu.show();
              break; 
              
            case 'ACTIONS':
              var actionMenu = Ext.getCmp('actionMenu');
              actionMenu.menu.removeAll();
              for(j=0; j<data[i].options.length; j++) {
                actionMenu.menu.add({
                  text: data[i].options[j].text,
                  handler: data[i].options[j].fn != '' ? Actions[data[i].options[j].fn] : function(){}
                });
                menuSelectedTitle[data[i].options[j].fn] = data[i].options[j].text;
              }
              actionMenu.show();
              break;
          }
        }
      },
      failure: function ( result, request) { 
        Ext.MessageBox.alert('Failed', result.responseText); 
      }
    });
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
    Ext.getCmp('navPanel').ownerCt.doLayout();
  }
  
  var casesStepTree = new Ext.tree.TreePanel({
    id: 'casesStepTree',
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
    width: 250,
    height: 500,
    maxSize: 400,
    split: true,
    collapsible: false,
    margins: '0 0 0 0',
    items:[casesStepTree]
  }
  
  var fnChangeStatus  =function(){
    alert('loaded');
  }

  var screenWidth = (PMExt.getBrowser().screen.width-140).toString() + 'px';

  var navPanelCenter = {
    id: 'navPanelCenter',
    region: 'center', layout:'fit',forceLayout: true,
    xtype:'panel',
    items:[{
        xtype:"tabpanel",
        id: 'caseTabPanel',
        deferredRender:false,
        defaults:{autoScroll: true},
        defaultType:"iframepanel",
        activeTab: 0,
        
        //defaults: Ext.apply({}, Ext.isGecko? {style:{position:'absolute'},hideMode:'visibility'}:false),
        
        items:[{
          title: _('ID_CASE') +' ' + _APP_NUM,
          frameConfig:{name:'openCaseFrame', id:'openCaseFrame'},
          defaultSrc : uri,
          loadMask:{msg:'Loading...'},
          bodyStyle:{height: (PMExt.getBrowser().screen.height-60) + 'px', overflow:'auto'},
          width:screenWidth
          
          }
        ],
        listeners: {
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
    layout: 'border',
    items:[navPanelWest, navPanelCenter],
    tbar:[{
      id: 'stepsMenu',
      text: '&nbsp;&nbsp;'+_('ID_STEPS'),
      pressed: false,
      enableToggle:true,
      tooltip: {
        title: _('ID_CASES_STEPS'),
        text:_('ID_SHOW_HIDE_CASES_STEPS')
      },
      iconCls: 'ICON_STEPS',
      toggleHandler: togglePreview
    }, {
      id: 'informationMenu',
      text: _('ID_INFORMATION'),
      menu: []
    }, {
      id: 'actionMenu',
      text: _('ID_ACTIONS'),
      menu: []
    }]    
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
      params : {action : 'getProcessInformation'},
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
            {fieldLabel: 'Create date', text: data.PRO_CREATE_DATE}
          ]
        }

        var frm = new Ext.FormPanel( {
          labelAlign : 'right',
          bodyStyle : 'padding:5px 5px 0',
          width : 400,
          autoScroll:true,
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
      params : {action : 'getTaskInformation'},
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
            {fieldLabel: 'Duration', text: data.DURATION}
          ]
        }
    
        var frm = new Ext.FormPanel( {
          labelAlign : 'right',
          bodyStyle : 'padding:5px 5px 0',
          width : 400,
          autoScroll:true,
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
        params : {action : 'cancelCase'},
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
          params : {action : 'reassignCase', USR_UID: rowSelected.data.USR_UID},
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
    curDate = _ENV_CURRENT_DATE.split('-');
    filterDate = curDate[0]+'-'+curDate[1]+'-';
    nDay = '' + (parseInt(curDate[2])+1);
    nDay = nDay.length == 1 ? '0' + nDay : nday;
    filterDate += nDay;
    
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
          id: 	'unpauseDate',
          format: 'Y-m-d',
          fieldLabel: 'Unpause Date',
          name: 'unpauseDate',
          allowBlank: false,
          minValue: new Date(filterDate)
        })
      ],
      buttons : [
        {
          id: 'submitPauseCase',
          text : _('ID_PAUSE_CASE'),
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
        params : {action : 'unpauseCase'},
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
        url : '../adhocUserProxy/deleteCase',
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
        params : {action : 'reactivateCase'},
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
        autoWidth: true,
        closable:true,
        autoScroll: true,
        bodyStyle:{height: (PMExt.getBrowser().screen.height-60) + 'px', overflow:'auto'}
      }).show();
      
      TabPanel.doLayout();
    }
  }
  
  
});

	/*-----added by krlos------------*/
  Actions.adhocAssignmentUsers = function()
  {
    Ext.state.Manager.setProvider(new Ext.state.CookieProvider());
    Ext.QuickTips.init();
    store = new Ext.data.GroupingStore( {
    proxy : new Ext.data.HttpProxy({
      url   : '../adhocUserProxy/adhocAssignUsersk'
     }),
    reader : new Ext.data.JsonReader( {
     root: 'data',
     fields : [
      {name : 'USR_UID'},
      {name : 'USR_FIRSTNAME'},
      {name : 'USR_LASTNAME'}
      ]
    })
    });
		
    cmk = new Ext.grid.ColumnModel({
     defaults: {
      width: 40,
      sortable: true
      },    
     columns: [
      { id:'USR_UID', dataIndex: 'USR_UID', hidden:true, hideable:false},
      { header : "First Name", dataIndex : 'USR_FIRSTNAME', sortable : true, width: 130, align:'center'},
      { header : "Last Name", dataIndex : 'USR_LASTNAME', sortable : true,width: 130, align:'center' }		      
     ]
    });

    pbark = new Ext.PagingToolbar({
     pageSize: 8,
     store: store,
     displayInfo: true,
     displayMsg: 'Displaying Users {0} - {1} of {2}',
     emptyMsg: "",
     items:[]
    });

		var adHocUserGrid = new Ext.grid.GridPanel( {
     region: 'center',
     layout: 'fit',
     id: 'adHocUserGrid',
     height:210,
     //autoWidth : true,
     width:'',
     title : '',
     stateful : true,
     stateId : 'grid',
     enableColumnResize: true,
     enableHdMenu: true,
     frame:false,          
     columnLines: true,      
     viewConfig: {
      forceFit:true
     },
     cm: cmk,
     store: store,
     tbar:[{text:_('ID_ASSIGN'), iconCls: 'silk-add', icon: '/images/cases-selfservice.png', handler: assignAdHocUser}	],
     bbar: '',
     listeners:{
      rowdblclick: assignAdHocUser
     }
    });

    var w = new Ext.Window({
     title: _('ID_ADHOC_ASSIGNMENT'),
     width: 500,
     height: 240,       
     resizable: false,
     items: [ adHocUserGrid ],
     id: 'w'
    });    
    adHocUserGrid.store.load();
		w.show();

    function assignAdHocUser(){
     rowSelected = adHocUserGrid.getSelectionModel().getSelected();
     PMExt.confirm(_('ID_CONFIRM'), _('ID_CONFIRM_ADHOCUSER_CASE'), function(){
      var loadMask = new Ext.LoadMask(document.body, {msg:'Assignment case...'});
      loadMask.show();
      Ext.Ajax.request({
       url : '../adhocUserProxy/reassignCase' ,
       method: 'POST',
       params : {USR_UID: rowSelected.data.USR_UID, THETYPE: 'ADHOC'},
       success: function ( result, request ) {
        loadMask.hide();
        var data = Ext.util.JSON.decode(result.responseText);
        if( data.success ) {
         CloseWindow();		          
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
  }
  CloseWindow = function(){
   Ext.getCmp('w').hide();
  };
	/*-----added by krlos end------------*/
/*Date.prototype.dateFormat = function(format) {
    var result = "";
    for (var i = 0; i < format.length; ++i) {
        result += this.dateToString(format.charAt(i));
    }
    return result;
}

Date.prototype.dateToString = function(character) {
    switch (character) {
    case "Y":
        return this.getFullYear();
    
    case "d":
        return this.getDate();
        
    case "m":
        return this.getMonth();
    // snip a bunch of lines
    default:
        return character;
    }
}*/
