/*
 * @author: Qennix
 * Jan 25th, 2011
 */

//Keyboard Events
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
    		document.location = document.location;
    	}else{
    		Ext.Msg.alert('Refresh', 'You clicked: CTRL-F5');
    	}
    }
});

var storeP;
var storeA;
var cmodelP;
var smodelA;
var smodelP;

var availableGrid;
var assignedGrid;

var GroupsPanel;
var AuthenticationPanel;
var northPanel;
var tabsPanel;
var viewport;

var assignButton;
var assignAllButton;
var removeButton;
var removeAllButton;
var backButton;
var discardChangesButton;
var saveChangesButton;

var sw_func_groups;
//var sw_func_reassign;
var sw_func_auth;
var sw_form_changed;

Ext.onReady(function(){
	
	sw_func_groups = false;
	//sw_func_reassign = false;
	sw_func_auth = false;
	
	assignButton = new Ext.Action({
    	text: TRANSLATIONS.ID_ASSIGN,
    	iconCls: 'button_menu_ext ss_sprite  ss_add',
    	handler: AssignGroupsAction,
    	disabled: true
    });
	
	assignAllButton = new Ext.Action({
    	text: TRANSLATIONS.ID_ASSIGN_ALL_GROUPS,
    	iconCls: 'button_menu_ext ss_sprite  ss_add',
    	handler: AssignAllGroupsAction
    });
	
	removeButton = new Ext.Action({
    	text: TRANSLATIONS.ID_REMOVE,
    	iconCls: 'button_menu_ext ss_sprite  ss_delete',
    	handler: RemoveGroupsAction,
    	disabled: true
    });
	
	removeAllButton = new Ext.Action({
    	text: TRANSLATIONS.ID_REMOVE_ALL_GROUPS,
    	iconCls: 'button_menu_ext ss_sprite  ss_delete',
    	handler: RemoveAllGroupsAction
    });
	
	backButton = new Ext.Action({
		text: TRANSLATIONS.ID_BACK,
		iconCls: 'button_menu_ext ss_sprite ss_arrow_redo',
		handler: BackToUsers
	});
	
	saveChangesButton = new Ext.Action({
		text: TRANSLATIONS.ID_SAVE_CHANGES,
		//iconCls: 'button_menu_ext ss_sprite ss_arrow_redo',
		handler: SaveChangesAuthForm,
		disabled: true
	});
	
	discardChangesButton = new Ext.Action({
		text: TRANSLATIONS.ID_DISCARD_CHANGES,
		//iconCls: 'button_menu_ext ss_sprite ss_arrow_redo',
		handler: LoadAuthForm,
		disabled: true
	});
    
	storeP = new Ext.data.GroupingStore( {
        proxy : new Ext.data.HttpProxy({
            url: 'users_Ajax?function=assignedGroups&uUID=' + USERS.USR_UID
          }),
    	reader : new Ext.data.JsonReader( {
    		root: 'groups',
    		fields : [
    		    {name : 'GRP_UID'},
    		    {name : 'GRP_STATUS'},
    		    {name : 'CON_VALUE'}
    		]
    	})
    });
	
	storeA = new Ext.data.GroupingStore( {
        proxy : new Ext.data.HttpProxy({
            url: 'users_Ajax?function=availableGroups&uUID=' + USERS.USR_UID
          }),
    	reader : new Ext.data.JsonReader( {
    		root: 'groups',
    		fields : [
    		    {name : 'GRP_UID'},
    		    {name : 'GRP_STATUS'},
    		    {name : 'CON_VALUE'}
    		    ]
    	})
    });
	
	cmodelP = new Ext.grid.ColumnModel({
        defaults: {
            width: 50,
            sortable: true
        },
        columns: [
            {id:'GRP_UID', dataIndex: 'GRP_UID', hidden:true, hideable:false},
            {header: TRANSLATIONS.ID_GROUP_NAME, dataIndex: 'CON_VALUE', width: 60, align:'left'}
        ]
    });
	
	smodelA = new Ext.grid.RowSelectionModel({
		selectSingle: false,
		listeners:{
			selectionchange: function(sm){
    			switch(sm.getCount()){
    			case 0: assignButton.disable(); break;
    			default: assignButton.enable(); break;	
    			}
    		}
		}
	});
	
	smodelP = new Ext.grid.RowSelectionModel({
		selectSingle: false,
		listeners:{
			selectionchange: function(sm){
    			switch(sm.getCount()){
    			case 0: removeButton.disable(); break;
    			default: removeButton.enable(); break;	
    			}
    		}
		}
	});
	
  	availableGrid = new Ext.grid.GridPanel({
  		    layout			: 'fit',
  		    region          : 'center',
        	ddGroup         : 'assignedGridDDGroup',
            store           : storeA,
            cm          	: cmodelP,
            sm				: smodelA,
            enableDragDrop  : true,
            stripeRows      : true,
            autoExpandColumn: 'CON_VALUE',
            iconCls			: 'icon-grid',
            id				: 'availableGrid',
        	height			: 100,
        	autoWidth 		: true,
        	stateful 		: true,
        	stateId 		: 'grid',
        	enableColumnResize : true,
        	enableHdMenu	: true,
        	frame			: false,
        	columnLines		: false,
        	viewConfig		: {forceFit:true},
            tbar: [TRANSLATIONS.ID_AVAILABLE_GROUPS,{xtype: 'tbfill'},'-',assignButton],
            bbar: [{xtype: 'tbfill'}, assignAllButton],
            listeners: {rowdblclick: AssignGroupsAction} 
    });

  	assignedGrid = new Ext.grid.GridPanel({
  		    layout			: 'fit',
  			ddGroup         : 'availableGridDDGroup',
            store           : storeP,
            cm          	: cmodelP,
            sm				: smodelP,
            enableDragDrop  : true,
            stripeRows      : true,
            autoExpandColumn: 'CON_VALUE',
            iconCls			: 'icon-grid',
            id				: 'assignedGrid',
        	height			: 100,
        	autoWidth 		: true,
        	stateful 		: true,
        	stateId 		: 'grid',
        	enableColumnResize : true,
        	enableHdMenu	: true,
        	frame			: false,
        	columnLines		: false,
        	viewConfig		: {forceFit:true},
            tbar: [TRANSLATIONS.ID_ASSIGNED_GROUPS,{xtype: 'tbfill'},'-',removeButton],
            bbar: [{xtype: 'tbfill'},removeAllButton],
        	listeners: {rowdblclick: RemoveGroupsAction} 
    });
  	
  	RefreshGroups();

  	//GROUPS DRAG AND DROP PANEL
    GroupsPanel = new Ext.Panel({
    	    title		 : TRANSLATIONS.ID_GROUPS,
    		autoWidth	 : true,
    		layout       : 'hbox',
   		    defaults     : { flex : 1 }, //auto stretch
    		layoutConfig : { align : 'stretch' },
    		items        : [availableGrid,{xtype: '', width: 10},assignedGrid],
    		viewConfig	 : {forceFit:true}

    });
    
    comboAuthSourcesStore = new Ext.data.GroupingStore({
    	proxy : new Ext.data.HttpProxy({
            url: 'users_Ajax?function=authSources'
          }),
    	reader : new Ext.data.JsonReader( {
    		root: 'sources',
    		fields : [
    		    {id: 'AUTH_SOURCE_UID'},
    		    {name : 'AUTH_SOURCE_UID'},
    		    {name : 'AUTH_SOURCE_NAME'},
    		    {name : 'AUTH_SOURCE_PROVIDER'}
    		    ]
    	}),
    	autoLoad: true
    });
    
    //AUTHENTICATION FORM
    authForm = new Ext.FormPanel({
    	url: 'users_Ajax?function=updateAuthServices',
    	frame: true,
    	title: TRANSLATIONS.ID_AUTHENTICATION_FORM_TITLE,
    	items:[
    	       {xtype: 'textfield', name: 'usr_uid', hidden: true },
    	       {
    	    	   xtype: 'combo', 
    	    	   fieldLabel: TRANSLATIONS.ID_AUTHENTICATION_SOURCE, 
    	    	   hiddenName: 'auth_source',
    	    	   name: 'auth_source_uid',
    	    	   typeAhead: true,
    	    	   mode: 'local', 
    	    	   store: comboAuthSourcesStore, 
    	    	   displayField: 'AUTH_SOURCE_NAME', 
    	    	   valueField:'AUTH_SOURCE_UID',
    	    	   allowBlank: false,
    	    	   submitValue: true,
    	    	   //hiddenValue: 'AUTH_SOURCE_UID',
    	    	   triggerAction: 'all',
                   emptyText: TRANSLATIONS.ID_SELECT_AUTH_SOURCE,
                   selectOnFocus:true,
                   listeners:{select: function(c,r,i){ 
                	   ReportChanges();
                	   if (i==0){
                		   authForm.getForm().findField('auth_dn').disable();
                	   }else{
                		   authForm.getForm().findField('auth_dn').enable();
                	   } 
                   }}
    	       },
    	       {
    	    	   xtype: 'textfield', 
    	    	   fieldLabel: TRANSLATIONS.ID_AUTHENTICATION_DN, 
    	    	   name: 'auth_dn', 
    	    	   width: 350, 
    	    	   allowBlank: true,
    	    	   enableKeyEvents: true,
    	    	   listeners: {keyup: function(f,e){ ReportChanges(); }}
    	       }
    	       
    	       ],
    	 buttons: [discardChangesButton,saveChangesButton]
    });
    
    LoadAuthForm();
    
    
    //AUTHENTICATION EDITING PANEL
    AuthenticationPanel = new Ext.Panel({
	    title		 : TRANSLATIONS.ID_AUTHENTICATION,
		autoWidth	 : true,
		layout       : 'hbox',
		defaults     : { flex : 1 }, //auto stretch
		layoutConfig : { align : 'stretch' },
		items: [authForm],
		viewConfig	 : {forceFit:true},
		hidden: true,
		hideLabel: true
    });
    
        
    //NORTH PANEL WITH TITLE AND ROLE DETAILS
    northPanel = new Ext.Panel({
    	region: 'north',
    	xtype: 'panel',
    	tbar: [TRANSLATIONS.ID_USERS + ' : ' + USERS.USR_COMPLETENAME + ' (' + USERS.USR_USERNAME + ')',{xtype: 'tbfill'},backButton]
    });
    
    //TABS PANEL
    tabsPanel = new Ext.TabPanel({
       	region: 'center',
       	activeTab: USERS.CURRENT_TAB,
    	items:[GroupsPanel,AuthenticationPanel],
    	listeners:{
    		beforetabchange: function(p,t,c){
    			switch(t.title){
    			case TRANSLATIONS.ID_GROUPS:
    				if (sw_form_changed){
    					Ext.Msg.confirm(TRANSLATIONS.ID_USERS, 'Do you want discard changes?',
    					  function(btn, text){
				            if (btn=="no"){
				               p.setActiveTab(c);
				            }else{
				            	LoadAuthForm();
				            }
    					});
    				}
    				break;
    			}
    		},
    		tabchange: function(p,t){
    			switch(t.title){
    			case TRANSLATIONS.ID_GROUPS:
    				sw_func_groups ? DoNothing() : DDLoadGroups();
    				break;
    			case TRANSLATIONS.ID_AUTHENTICATION:
    				sw_func_auth ? DoNothing() : LoadAuthForm();
    				break;
    			}
    		}
    	}
    });
    
    //LOAD ALL PANELS
    viewport = new Ext.Viewport({
    	layout: 'border',
    	items: [northPanel, tabsPanel]
    });
    
});

//Do Nothing Function
DoNothing = function(){}

//Return to Roles Main Page
BackToUsers = function(){
	location.href = 'users_List';
}

//Loads Drag N Drop Functionality for Permissions
DDLoadGroups = function(){
	//GROUPS DRAG N DROP AVAILABLE
	var availableGridDropTargetEl =  availableGrid.getView().scroller.dom;
    var availableGridDropTarget = new Ext.dd.DropTarget(availableGridDropTargetEl, {
                    ddGroup    : 'availableGridDDGroup',
                    notifyDrop : function(ddSource, e, data){
                            var records =  ddSource.dragData.selections;
                            var arrAux = new Array();
                            for (var r=0; r < records.length; r++){
                            	arrAux[r] = records[r].data['GRP_UID'];
                            }
                            DeleteGroupsUser(arrAux,RefreshGroups,FailureProcess);
                            return true;
                    }
    });
	
    //GROUPS DRAG N DROP ASSIGNED
    var assignedGridDropTargetEl = assignedGrid.getView().scroller.dom;
    var assignedGridDropTarget = new Ext.dd.DropTarget(assignedGridDropTargetEl, {
                    ddGroup    : 'assignedGridDDGroup',
                    notifyDrop : function(ddSource, e, data){
                            var records =  ddSource.dragData.selections;
                            var arrAux = new Array();
                            for (var r=0; r < records.length; r++){
                            	arrAux[r] = records[r].data['GRP_UID'];
                            }
                            SaveGroupsUser(arrAux,RefreshGroups,FailureProcess);
                            return true;
                    }
     });
    sw_func_groups = true;
}

//LOAD AUTHTENTICATION FORM DATA
LoadAuthForm = function(){
	Ext.Ajax.request({
		url: 'users_Ajax',
		params: {'function': 'loadAuthSourceByUID', uUID: USERS.USR_UID},
		success: function(resp, opt){
		   var user = Ext.util.JSON.decode(resp.responseText);
		   authForm.getForm().findField('usr_uid').setValue(user.data.USR_UID);
		   authForm.getForm().findField('auth_source').setValue(user.auth.AUTH_SOURCE_NAME);
		   authForm.getForm().findField('auth_dn').setValue(user.data.USR_AUTH_USER_DN);
		   if (user.auth.AUTH_SOURCE_NAME=='ProcessMaker'){
			   authForm.getForm().findField('auth_dn').disable();
		   }else{
			   authForm.getForm().findField('auth_dn').enable();
		   }
		},
		failure: DoNothing
	});
	
	sw_func_auth = true;
	sw_form_changed = false;
	saveChangesButton.disable();
	discardChangesButton.disable();
}

//ReportChanges
ReportChanges = function(){
	saveChangesButton.enable();
	discardChangesButton.enable();
	sw_form_changed = true;
}


//REFRESH GROUPS GRIDS
RefreshGroups = function(){
	availableGrid.store.load();
	assignedGrid.store.load();
}

//SAVE AUTHENTICATION CHANGES

SaveChangesAuthForm = function(){
	viewport.getEl().mask(TRANSLATIONS.ID_PROCESSING);
	authForm.getForm().submit({
		success: function(f,a){
			LoadAuthForm();
			viewport.getEl().unmask();
		},
		failure: function(f,a){
			FailureProcess();
			viewport.getEl().unmask();
		}
	});
}

//FAILURE AJAX FUNCTION
FailureProcess = function(){
	Ext.Msg.alert(TRANSLATIONS.ID_USERS, TRANSLATIONS.ID_MSG_AJAX_FAILURE);
}

//ASSIGN GROUPS TO A USER
SaveGroupsUser = function(arr_grp, function_success, function_failure){
	var sw_response;
	viewport.getEl().mask(TRANSLATIONS.ID_PROCESSING);
	Ext.Ajax.request({
		url: 'users_Ajax',
		params: {'function': 'assignGroupsToUserMultiple', USR_UID: USERS.USR_UID, GRP_UID: arr_grp.join(',')},
		success: function(){
					function_success();
					viewport.getEl().unmask();
				  },
		failure: function(){
					function_failure();
					viewport.getEl().unmask();
		}
	});
}

//REMOVE GROUPS FROM A USER
DeleteGroupsUser = function(arr_grp, function_success, function_failure){
	var sw_response;
	viewport.getEl().mask(TRANSLATIONS.ID_PROCESSING);
	Ext.Ajax.request({
		url: 'users_Ajax',
		params: {'function': 'deleteGroupsToUserMultiple', USR_UID: USERS.USR_UID, GRP_UID: arr_grp.join(',')},
		success: function(){
			        function_success();
					viewport.getEl().unmask();			        
		},
		failure: function(){
					function_failure();
					viewport.getEl().unmask();
		}
	});
}

//AssignButton Functionality
AssignGroupsAction = function(){
	rowsSelected = availableGrid.getSelectionModel().getSelections();
	var arrAux = new Array();
	for(var a=0; a < rowsSelected.length; a++){
		arrAux[a] = rowsSelected[a].get('GRP_UID');
	}
	SaveGroupsUser(arrAux,RefreshGroups,FailureProcess);
}

//RemoveButton Functionality
RemoveGroupsAction = function(){
	rowsSelected = assignedGrid.getSelectionModel().getSelections();
	var arrAux = new Array();
	for(var a=0; a < rowsSelected.length; a++){
		arrAux[a] = rowsSelected[a].get('GRP_UID');
	}
	DeleteGroupsUser(arrAux,RefreshGroups,FailureProcess);
}

//AssignALLButton Functionality
AssignAllGroupsAction = function(){
	var allRows = availableGrid.getStore();
	var arrAux = new Array();
	if (allRows.getCount()>0){
		for (var r=0; r < allRows.getCount(); r++){
			row = allRows.getAt(r);
			arrAux[r] = row.data['GRP_UID'];
		}
		SaveGroupsUser(arrAux,RefreshGroups,FailureProcess);
	}
}

//RevomeALLButton Functionality
RemoveAllGroupsAction = function(){
	var allRows = assignedGrid.getStore();
	var arrAux = new Array();
	if (allRows.getCount()>0){
		for (var r=0; r < allRows.getCount(); r++){
			row = allRows.getAt(r);
			arrAux[r] = row.data['GRP_UID'];
		}
		DeleteGroupsUser(arrAux,RefreshGroups,FailureProcess);
	}
}