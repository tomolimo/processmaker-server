/*
 * @author: Qennix
 * Jan 24th, 2011
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
},
{
	key: Ext.EventObject.DELETE,
	fn: function(k,e){
		iGrid = Ext.getCmp('infoGrid');
		rowSelected = iGrid.getSelectionModel().getSelected();
		if (rowSelected){
			DeleteUserAction();
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
var groupsButton;
//var reassignButton;
var authenticationButton;
var searchButton;

var searchText;

var contextMenu;

var user_admin = '00000000000000000000000000000001';

Ext.onReady(function(){
    Ext.QuickTips.init();
    
    newButton = new Ext.Action({
    	text: TRANSLATIONS.ID_NEW,
    	iconCls: 'button_menu_ext ss_sprite  ss_add',
    	handler: NewUserAction
    });
    
    editButton = new Ext.Action({
    	text: TRANSLATIONS.ID_EDIT,
    	iconCls: 'button_menu_ext ss_sprite  ss_pencil',
    	handler: EditUserAction,
    	disabled: true	
    });
    
    deleteButton = new Ext.Action({
    	text: TRANSLATIONS.ID_DELETE,
    	iconCls: 'button_menu_ext ss_sprite  ss_delete',
    	handler: DeleteUserAction,
    	disabled: true
    });
    
    groupsButton = new Ext.Action({
    	text: TRANSLATIONS.ID_GROUPS,
    	iconCls: 'button_menu_ext ss_sprite ss_group_add',
    	handler: UsersGroupPage,
    	disabled: true
    });
    
//    reassignButton = new Ext.Action({
//    	text: TRANSLATIONS.ID_REASSIGN_CASES,
//    	iconCls: 'button_menu_ext ss_sprite ss_arrow_rotate_clockwise',
//    	handler: DoNothing,
//    	disabled: true
//    });
    
    authenticationButton = new Ext.Action({
    	text: TRANSLATIONS.ID_AUTHENTICATION,
    	iconCls: 'button_menu_ext ss_sprite ss_key',
    	handler: AuthUserPage,
    	disabled: true
    });
    
    
    searchButton = new Ext.Action({
    	text: TRANSLATIONS.ID_SEARCH,
    	handler: DoSearch
    });
    
    contextMenu = new Ext.menu.Menu({
    	items: [editButton, deleteButton,'-',groupsButton,'-',authenticationButton]
    });
    
    searchText = new Ext.form.TextField ({
        id: 'searchTxt',
        ctCls:'pm_search_text_field',
        allowBlank: true,
        width: 150,
        emptyText: TRANSLATIONS.ID_ENTER_SEARCH_TERM,//'enter search term',
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
                groupsButton.enable();
                //reassignButton.enable();
                authenticationButton.enable();
    		},
    		rowdeselect: function(sm){
    			editButton.disable();
    			deleteButton.disable();
    			groupsButton.disable();
    			//reassignButton.disable();
    			authenticationButton.disable();
    		}
    	}
    });

    store = new Ext.data.GroupingStore( {
        proxy : new Ext.data.HttpProxy({
            url: 'data_usersList'
          }),
    	reader : new Ext.data.JsonReader( {
    		root: 'users',
    		fields : [
    		    {name : 'USR_UID'},
    		    {name : 'USR_USERNAME'},
    		    {name : 'USR_COMPLETENAME'},
    		    {name : 'USR_EMAIL'},
    		    {name : 'USR_ROLE'},
    		    {name : 'USR_DUE_DATE'}
    		    ]
    	})
    });
    
    cmodel = new Ext.grid.ColumnModel({
        defaults: {
            width: 50,
            sortable: true
        },
        columns: [
            {id:'USR_UID', dataIndex: 'USR_UID', hidden:true, hideable:false},
            {header: TRANSLATIONS.ID_PHOTO, dataIndex: 'USR_UID', width: 14, align:'center', sortable: false, renderer: photo_user},
            {header: TRANSLATIONS.ID_FULL_NAME, dataIndex: 'USR_COMPLETENAME', width: 80, align:'left'},
            {header: TRANSLATIONS.ID_USER_NAME, dataIndex: 'USR_USERNAME', width: 60, hidden:false, align:'left'},
            {header: TRANSLATIONS.ID_EMAIL, dataIndex: 'USR_EMAIL', width: 60, hidden: false, align: 'left'},
            {header: TRANSLATIONS.ID_ROLE, dataIndex: 'USR_ROLE', width: 70, hidden:false, align:'left'},
            {header: TRANSLATIONS.ID_DUE_DATE, dataIndex: 'USR_DUE_DATE', width: 30, hidden:false, align:'center'}
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
    	title : TRANSLATIONS.ID_USERS,
    	store: store,
    	cm: cmodel,
    	sm: smodel,
    	tbar: [newButton, '-', editButton, deleteButton,'-',groupsButton,'-',authenticationButton,  {xtype: 'tbfill'}, searchText,clearTextButton,searchButton],
    	listeners: {
    		rowdblclick: EditUserAction
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
    
    infoGrid.on('contextmenu', 
    		function (evt) {
        		evt.preventDefault();
    		}, 
    		this
    );
    
    infoGrid.addListener('rowcontextmenu',onMessageContextMenu,this);

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

//Open New User Form
NewUserAction = function(){
	location.href = 'users_New';
}

//Edit User Action
EditUserAction = function(){
	var uid = infoGrid.getSelectionModel().getSelected();
	if (uid){
		location.href = 'users_Edit?USR_UID=' + uid.data.USR_UID;
	}
}

//Delete User Action
DeleteUserAction = function(){
	var uid = infoGrid.getSelectionModel().getSelected();
	if (uid){
		if (uid.data.USR_UID==user_admin){
			Ext.Msg.alert(TRANSLATIONS.ID_USERS, TRANSLATIONS.ID_CANNOT_DELETE_ADMIN_USER);
		}else{
			Ext.Ajax.request({
				url: 'users_Ajax',
				params: {'function': 'canDeleteUser', uUID: uid.data.USR_UID},
				success: function(res, opt){
					response = Ext.util.JSON.decode(res.responseText);
					if (response.candelete){
						if (response.hashistory){
							Ext.Msg.confirm(TRANSLATIONS.ID_CONFIRM, TRANSLATIONS.ID_USERS_DELETE_WITH_HISTORY, 
								    function(btn){
										if (btn=='yes') DeleteUser(uid.data.USR_UID);
									}
								);
						}else{
							Ext.Msg.confirm(TRANSLATIONS.ID_CONFIRM, TRANSLATIONS.ID_MSG_CONFIRM_DELETE_USER, 
							    function(btn){
									if (btn=='yes') DeleteUser(uid.data.USR_UID);
								}
							);
						}
					}else{
					   Ext.Msg.alert(TRANSLATIONS.ID_USERS, TRANSLATIONS.ID_MSG_CANNOT_DELETE_USER);	
					}
				},
				failure: DoNothing
			});
		}
	}
}

//Open User-Groups Manager
UsersGroupPage = function(value){
	rowSelected = infoGrid.getSelectionModel().getSelected();
	if (rowSelected){
		value = rowSelected.data.USR_UID;
	    location.href = 'usersGroups?uUID=' + value + '&type=group';
	}
}

//Open Authentication-User Manager
AuthUserPage = function(value){
	rowSelected = infoGrid.getSelectionModel().getSelected();
	if (rowSelected){
		value = rowSelected.data.USR_UID;
	    location.href = 'usersGroups?uUID=' + value + '&type=auth';;
	}
}

//Renderer Active/Inactive Role
photo_user = function(value){
	return '<img border="0" src="users_ViewPhotoGrid?pUID=' + value + '" width="20" />';
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

//Delete User Function
DeleteUser = function(uid){
	Ext.Ajax.request({
		url: 'users_Ajax',
		params: {'function': 'deleteUser', USR_UID: uid},
		success: function(res, opt){
			DoSearch();
			Ext.Msg.alert(TRANSLATIONS.ID_USERS,TRANSLATIONS.ID_USERS_SUCCESS_DELETE);
		},
		failure: DoNothing
	});
}