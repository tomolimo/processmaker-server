/*
 * @author: Qennix
 * Jan 27th, 2011
 */

//Keyboard Events
new Ext.KeyMap(document, [
   {
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
}
   ,
{
	key: Ext.EventObject.DELETE,
	fn: function(k,e){
		iGrid = Ext.getCmp('infoGrid');
		rowSelected = iGrid.getSelectionModel().getSelected();
		if (rowSelected){
			DeleteButtonAction();
		}
	}
},
{
	key: Ext.EventObject.F2,
	fn: function(k,e){
		iGrid = Ext.getCmp('infoGrid');
		rowSelected = iGrid.getSelectionModel().getSelected();
		if (rowSelected){
			EditGroupWindow();
		}
	}
}
]);

var store;
var cmodel;
var infoGrid;
var viewport;
var smodel;

var newButton;
var editButton;
var deleteButton;
var searchButton;

var searchText;
var contextMenu;

Ext.onReady(function(){
    Ext.QuickTips.init();
    
    newButton = new Ext.Action({
    	text: TRANSLATIONS.ID_NEW,
    	iconCls: 'button_menu_ext ss_sprite  ss_add',
    	handler: NewGroupWindow
    });
    
    editButton = new Ext.Action({
    	text: TRANSLATIONS.ID_EDIT,
    	iconCls: 'button_menu_ext ss_sprite  ss_pencil',
    	handler: EditGroupWindow,
    	disabled: true	
    });
    
    deleteButton = new Ext.Action({
    	text: TRANSLATIONS.ID_DELETE,
    	iconCls: 'button_menu_ext ss_sprite  ss_delete',
    	handler: DeleteButtonAction,
    	disabled: true
    });
    
    membersButton = new Ext.Action({
    	text: TRANSLATIONS.ID_MEMBERS,
    	iconCls: 'button_menu_ext ss_sprite ss_user_add',
    	handler: MembersAction,
    	disabled: true
    });
    
    searchButton = new Ext.Action({
    	text: TRANSLATIONS.ID_SEARCH,
    	handler: DoSearch
    });
    
    contextMenu = new Ext.menu.Menu({
    	items: [editButton, deleteButton,'-',membersButton]
    });
    
    searchText = new Ext.form.TextField ({
        id: 'searchTxt',
        ctCls:'pm_search_text_field',
        allowBlank: true,
        width: 150,
        emptyText: TRANSLATIONS.ID_ENTER_SEARCH_TERM,
        listeners: {
          specialkey: function(f,e){
            if (e.getKey() == e.ENTER) {
            	DoSearch();
            }
          },
          focus: function(f,e) {
       	   			var row = infoGrid.getSelectionModel().getSelected();
       	   			infoGrid.getSelectionModel().deselectRow(infoGrid.getStore().indexOf(row));
          		 }
        }
    });
    
    clearTextButton = new Ext.Action({
    	text: 'X',
    	ctCls:'pm_search_x_button',
    	handler: GridByDefault
    });
    
   
    smodel = new Ext.grid.RowSelectionModel({
    	singleSelect: true,
    	listeners:{
    		rowselect: function(sm){
    			editButton.enable();
    			deleteButton.enable();
    			membersButton.enable();
    		},
    		rowdeselect: function(sm){
    			editButton.disable();
    			deleteButton.disable();
    			membersButton.disable();
    		}
    	}
    });
    
    comboStatusStore = new Ext.data.SimpleStore({
    	fields: ['id','value'],
    	data: [['1','ACTIVE'],['0','INACTIVE']]
    });
    
    newForm = new Ext.FormPanel({
    	url: 'groups_Ajax?action=saveNewGroup',
    	frame: true,
    	items:[
    	       {xtype: 'textfield', fieldLabel: TRANSLATIONS.ID_GROUP_NAME, name: 'name', width: 200, allowBlank: false},
    	       {
    	    	   xtype: 'combo', 
    	    	   fieldLabel: TRANSLATIONS.ID_STATUS, 
    	    	   hiddenName: 'status',
    	    	   typeAhead: true,
    	    	   mode: 'local', 
    	    	   store: comboStatusStore, 
    	    	   displayField: 'value', 
    	    	   valueField:'value',
    	    	   allowBlank: false, 
    	    	   triggerAction: 'all',
                   emptyText: TRANSLATIONS.ID_SELECT_STATUS,
                   selectOnFocus:true
    	    	   }
    	       ],
    	 buttons: [
    	       {text: TRANSLATIONS.ID_CLOSE, handler: CloseWindow},
    	       {text: TRANSLATIONS.ID_SAVE, handler: SaveNewGroupAction}
    	 ]
    });
    
    editForm = new Ext.FormPanel({
    	url: 'groups_Ajax?action=saveEditGroup',
    	frame: true,
    	items:[
    	       {xtype: 'textfield', name: 'grp_uid', hidden: true},
    	       {xtype: 'textfield', fieldLabel: TRANSLATIONS.ID_GROUP_NAME, name: 'name', width: 200, allowBlank: false},
    	       {
    	    	   xtype: 'combo', 
    	    	   fieldLabel: TRANSLATIONS.ID_STATUS, 
    	    	   hiddenName: 'status',
    	    	   typeAhead: true,
    	    	   mode: 'local', 
    	    	   store: comboStatusStore, 
    	    	   displayField: 'value', 
    	    	   valueField:'value',
    	    	   allowBlank: false, 
    	    	   triggerAction: 'all',
                   emptyText: TRANSLATIONS.ID_SELECT_STATUS,
                   selectOnFocus:true
    	    	   }
    	       ],
    	 buttons: [
    	       {text: TRANSLATIONS.ID_CLOSE, handler: CloseWindow},
    	       {text: TRANSLATIONS.ID_SAVE, handler: SaveEditGroupAction}
    	 ]
    });

    store = new Ext.data.GroupingStore( {
        proxy : new Ext.data.HttpProxy({
            url: 'groups_Ajax?action=groupsList'
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
    
    cmodel = new Ext.grid.ColumnModel({
        defaults: {
            width: 50,
            sortable: true
        },
        columns: [
            {id:'GRP_UID', dataIndex: 'USR_UID', hidden:true, hideable:false},
            {header: 'Group Name', dataIndex: 'CON_VALUE', width: 60, align:'left'},
            {header: 'Status', dataIndex: 'GRP_STATUS', width: 30, align:'center'}
            ]
    });
    
    
    infoGrid = new Ext.grid.GridPanel({
    	region: 'center',
    	layout: 'fit',
    	id: 'infoGrid',
    	height:100,
    	autoWidth : true,
    	stateful : true,
    	stateId : 'grid',
    	enableColumnResize: true,
    	enableHdMenu: true,
    	frame:false,
    	iconCls:'icon-grid',
    	columnLines: false,
    	viewConfig: {
    		forceFit:true
    	},
    	title : TRANSLATIONS.ID_GROUPS,
    	store: store,
    	cm: cmodel,
    	sm: smodel,
    	tbar: [newButton, '-', editButton, deleteButton,'-',membersButton, {xtype: 'tbfill'}, searchText,clearTextButton,searchButton],
    	listeners: {
    		rowdblclick: EditGroupWindow
    	},
    	view: new Ext.grid.GroupingView({
    		forceFit:true,
    		groupTextTpl: '{text}'
    	})
    });
    
    infoGrid.on('rowcontextmenu', 
    		function (grid, rowIndex, evt) {
        		var sm = grid.getSelectionModel();
        		sm.selectRow(rowIndex, sm.isSelected(rowIndex));
    		},
    		this
    );
    
    infoGrid.on('contextmenu', function(evt){evt.preventDefault();}, this);
    infoGrid.addListener('rowcontextmenu',onMessageContextMenu, this);
    infoGrid.store.load();

    viewport = new Ext.Viewport({
    	layout: 'fit',
    	autoScroll: false,
    	items: [
    	   infoGrid
    	]
    });
});

//Funtion Handles Context Menu Opening
onMessageContextMenu = function (grid, rowIndex, e) {
    e.stopEvent();
    var coords = e.getXY();
    contextMenu.showAt([coords[0], coords[1]]);
}

//Do Nothing Function
DoNothing = function(){}

//Open New Group Form
NewGroupWindow = function(){
	w = new Ext.Window({
		height: 130,
		width: 400,
		title: TRANSLATIONS.ID_CREATE_GROUP_TITLE,
		closable: false,
		modal: true,
		items: [newForm]
	});
	w.show();
}

//Load Grid By Default
GridByDefault = function(){
	searchText.reset();
	infoGrid.store.load();
}

//Do Search Function
DoSearch = function(){
   infoGrid.store.load({params: {textFilter: searchText.getValue()}});	
}

//Close Popup Window
CloseWindow = function(){
	w.hide();
}

//Check Group Name Availability
CheckGroupName = function(grp_name, function_success, function_failure){
	Ext.Ajax.request({
		url: 'groups_Ajax',
		params: {action: 'exitsGroupName', GRP_NAME: grp_name},
		success: function(resp, opt){
			var checked = eval(resp.responseText);
			(!checked) ? function_success() : function_failure();
		},
		failure: function(r,o) {
			function_failure();
		}
	});
}

//Save Group Button
SaveNewGroupAction = function(){
	var group = newForm.getForm().findField('name').getValue();
	if (group != '') CheckGroupName(group, SaveNewGroup, DuplicateGroupName);
}

//Show Duplicate Group Name Message
DuplicateGroupName = function(){
	Ext.Msg.alert(TRANSLATIONS.ID_GROUPS, TRANSLATIONS.ID_MSG_GROUP_NAME_EXISTS);
}

//Save New Group
SaveNewGroup = function(){
	newForm.getForm().submit({
		success: function(f,a){
			w.hide(); //Hide popup widow
			newForm.getForm().reset(); //Set empty form to next use
			searchText.reset();
			infoGrid.store.load(); //Reload store grid
			Ext.Msg.alert(TRANSLATIONS.ID_GROUPS,TRANSLATIONS.ID_GROUPS_SUCCESS_NEW);
		},
		failure: function(f,a){
			switch(a.failureType){
			case Ext.form.Action.CLIENT_INVALID:
				//Ext.Msg.alert('New Role Form','Invalid Data');
				break;
			}
			
		}
	});
}

//Open Edit Group Form
EditGroupWindow = function(){
	rowSelected = infoGrid.getSelectionModel().getSelected();
	editForm.getForm().findField('grp_uid').setValue(rowSelected.data.GRP_UID);
	editForm.getForm().findField('name').setValue(rowSelected.data.CON_VALUE);
	editForm.getForm().findField('status').setValue(rowSelected.data.GRP_STATUS);
	w = new Ext.Window({
		height: 130,
		width: 440,
		title: TRANSLATIONS.ID_EDIT_GROUP_TITLE,
		closable: false,
		modal: true,
		items: [editForm]
	});
	w.show();
}

//Save Edit Group Button
SaveEditGroupAction = function(){
	var group = editForm.getForm().findField('name').getValue();
	rowSelected = infoGrid.getSelectionModel().getSelected();
	if (group != ''){
		if (rowSelected.data.CON_VALUE.toUpperCase() == group.toUpperCase()){
			SaveEditGroup();
		}else{
			CheckGroupName(group, SaveEditGroup, DuplicateGroupName);	
		}
	}
}

//Save Edit Group
SaveEditGroup = function(){
	editForm.getForm().submit({
		success: function(f,a){
			w.hide(); //Hide popup widow
			DoSearch(); //Reload store grid
			editButton.disable();  //Disable Edit Button
			deleteButton.disable(); //Disable Delete Button
			membersButton.disable(); //Disable Members Button
			Ext.Msg.alert(TRANSLATIONS.ID_ROLES,TRANSLATIONS.ID_GROUPS_SUCCESS_UPDATE);
		},
		failure: function(f,a){
			switch(a.failureType){
			case Ext.form.Action.CLIENT_INVALID:
				//Ext.Msg.alert('New Role Form','Invalid Data');
				break;
			}
			
		}
	});
}

//Delete Button Action
DeleteButtonAction = function(){
	Ext.Msg.confirm(TRANSLATIONS.ID_CONFIRM, TRANSLATIONS.ID_MSG_CONFIRM_DELETE_GROUP,
			function(btn, text){
        		if (btn=="yes"){
        			rowSelected = infoGrid.getSelectionModel().getSelected();
        			Ext.Ajax.request({
        				url: 'groups_Ajax',
        				params: {action: 'deleteGroup', GRP_UID: rowSelected.data.GRP_UID},
        				success: function(r,o){
        				   DoSearch();
        				   editButton.disable();  //Disable Edit Button
        				   deleteButton.disable(); //Disable Delete Button
        				   membersButton.disable(); //Disable Members Button
        				   Ext.Msg.alert(TRANSLATIONS.ID_GROUPS, TRANSLATIONS.ID_GROUPS_SUCCESS_DELETE);
        				}
        			});
        			
        		}
	       	}
	);
}

//Members Button Action
MembersAction = function(){
	rowSelected = infoGrid.getSelectionModel().getSelected();
	location.href = 'groupsMembers?GRP_UID=' + rowSelected.data.GRP_UID;
}