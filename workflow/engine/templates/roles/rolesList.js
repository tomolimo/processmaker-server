/*
 * @author: Qennix
 * Jan 18th, 2011
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
			CanDeleteRole();
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
var usersButton;
var permissionsButton;
var searchButton;

var serachText;

var newForm;
var comboStatusStore;
var editForm;

var contextMenu;

var w;
 

Ext.onReady(function(){
    Ext.QuickTips.init();
    
    newButton = new Ext.Action({
    	text: TRANSLATIONS.ID_NEW,
    	iconCls: 'button_menu_ext ss_sprite  ss_add',
    	handler: NewRoleWindow
    });
    
    editButton = new Ext.Action({
    	text: TRANSLATIONS.ID_EDIT,
    	iconCls: 'button_menu_ext ss_sprite  ss_pencil',
    	handler: EditRole,
    	disabled: true	
    });
    
    deleteButton = new Ext.Action({
    	text: TRANSLATIONS.ID_DELETE,
    	iconCls: 'button_menu_ext ss_sprite  ss_delete',
    	handler: CanDeleteRole,
    	disabled: true
    });
    
    usersButton = new Ext.Action({
    	text: TRANSLATIONS.ID_USERS,
    	iconCls: 'button_menu_ext ss_sprite  ss_user_add',
    	handler: RolesUserPage,
    	disabled: true
    });
    permissionsButton = new Ext.Action({
    	text: TRANSLATIONS.ID_PERMISSIONS,
    	iconCls: 'button_menu_ext ss_sprite  ss_key_add',
    	handler: RolesPermissionPage,
    	disabled: true
    });
    
    searchButton = new Ext.Action({
    	text: TRANSLATIONS.ID_SEARCH,
    	handler: DoSearch
    });
    
    contextMenu = new Ext.menu.Menu({
    	items: [editButton, deleteButton,'-',usersButton, permissionsButton]
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
    
    comboStatusStore = new Ext.data.SimpleStore({
    	fields: ['id','value'],
    	data: [['1',TRANSLATIONS.ID_ACTIVE],['0',TRANSLATIONS.ID_INACTIVE]]
    });
    
    newForm = new Ext.FormPanel({
    	url: 'roles_Ajax?request=saveNewRole',
    	frame: true,
    	title: 'Create a new role',
    	items:[
    	       {xtype: 'textfield', fieldLabel: TRANSLATIONS.ID_CODE, name: 'code', width: 250, allowBlank: false},
    	       {xtype: 'textfield', fieldLabel: TRANSLATIONS.ID_NAME, name: 'name', width: 200, allowBlank: false},
    	       {
    	    	   xtype: 'combo', 
    	    	   fieldLabel: TRANSLATIONS.ID_STATUS, 
    	    	   hiddenName: 'status',
    	    	   typeAhead: true,
    	    	   mode: 'local', 
    	    	   store: comboStatusStore, 
    	    	   displayField: 'value', 
    	    	   valueField:'id',
    	    	   allowBlank: false, 
    	    	   triggerAction: 'all',
                   emptyText: TRANSLATIONS.ID_SELECT_STATUS,
                   selectOnFocus:true
    	    	   }
    	       ],
    	 buttons: [
    	       {text: TRANSLATIONS.ID_CLOSE, handler: CloseWindow},
    	       {text: TRANSLATIONS.ID_SAVE, handler: SaveNewRole}
    	 ]
    });
    
    editForm = new Ext.FormPanel({
    	url: 'roles_Ajax?request=updateRole',
    	frame: true,
    	title: 'Updating role',
    	items:[
    	       {xtype: 'textfield', name: 'rol_uid', hidden: true },
    	       {xtype: 'textfield', fieldLabel: TRANSLATIONS.ID_CODE, name: 'code', width: 250, allowBlank: false},
    	       {xtype: 'textfield', fieldLabel: TRANSLATIONS.ID_NAME, name: 'name', width: 200, allowBlank: false},
    	       {
    	    	   xtype: 'combo', 
    	    	   fieldLabel: TRANSLATIONS.ID_STATUS, 
    	    	   hiddenName: 'status',
    	    	   typeAhead: true,
    	    	   mode: 'local', 
    	    	   store: comboStatusStore, 
    	    	   displayField: 'value', 
    	    	   valueField:'id',
    	    	   allowBlank: false, 
    	    	   triggerAction: 'all',
                   emptyText: TRANSLATIONS.ID_SELECT_STATUS,
                   selectOnFocus:true
    	    	   }
    	       ],
    	 buttons: [
    	       {text: TRANSLATIONS.ID_CLOSE, handler: CloseWindow},
    	       {text: TRANSLATIONS.ID_SAVE, handler: UpdateRole}
    	 ]
    });
    
    smodel = new Ext.grid.RowSelectionModel({
    	singleSelect: true,
    	listeners:{
    		rowselect: function(sm){
    			editButton.enable();
    			deleteButton.enable();
                usersButton.enable();
                permissionsButton.enable();
    		},
    		rowdeselect: function(sm){
    			editButton.disable();
    			deleteButton.disable();
    			usersButton.disable();
                permissionsButton.disable();
    		}
    	}
    });

    store = new Ext.data.GroupingStore( {
        proxy : new Ext.data.HttpProxy({
            url: 'data_rolesList'
          }),
    	reader : new Ext.data.JsonReader( {
    		root: 'roles',
    		fields : [
    		    {name : 'ROL_UID'},
    		    {name : 'ROL_CODE'},
    		    {name : 'ROL_NAME'},
    		    {name : 'ROL_CREATE_DATE'},
    		    {name : 'ROL_UPDATE_DATE'},
    		    {name : 'ROL_STATUS'}
    		    ]
    	})
    });
    
    cmodel = new Ext.grid.ColumnModel({
        defaults: {
            width: 50,
            sortable: true
        },
        columns: [
            {id:'ROL_UID', dataIndex: 'ROL_UID', hidden:true, hideable:false},
            {header: TRANSLATIONS.ID_CODE, dataIndex: 'ROL_CODE', width: 60, align:'left'},
            {header: TRANSLATIONS.ID_NAME, dataIndex: 'ROL_NAME', width: 60, hidden:false, align:'left'},
            {header: TRANSLATIONS.ID_STATUS, dataIndex: 'ROL_STATUS', width: 20, hidden: false, align: 'center', renderer: status_role},
            {header: TRANSLATIONS.ID_PRO_CREATE_DATE, dataIndex: 'ROL_CREATE_DATE', width: 40, hidden:false, align:'center'},
            {header: TRANSLATIONS.ID_LAN_UPDATE_DATE, dataIndex: 'ROL_UPDATE_DATE', width: 40, hidden:false, align:'center'}
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
    	title : TRANSLATIONS.ID_ROLES,
    	store: store,
    	cm: cmodel,
    	sm: smodel,
    	tbar: [newButton, '-', editButton, deleteButton,'-',usersButton, permissionsButton, {xtype: 'tbfill'}, searchText,clearTextButton,searchButton],
    	listeners: {
    		rowdblclick: EditRole
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

//Open New Role Form
NewRoleWindow = function(){
	w = new Ext.Window({
		height: 190,
		width: 420,
		title: TRANSLATIONS.ID_ROLES,
		items: [newForm]
	});
	w.show();
}

//Close Popup Window
CloseWindow = function(){
	w.hide();
}

//Save New Role
SaveNewRole = function(){
	newForm.getForm().submit({
		success: function(f,a){
			w.hide(); //Hide popup widow
			newForm.getForm().reset(); //Set empty form to next use
			textSearch.reset();
			infoGrid.store.load(); //Reload store grid
			Ext.Msg.alert(TRANSLATIONS.ID_ROLES,TRANSLATIONS.ID_ROLES_SUCCESS_NEW);
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

//Update Selected Role
UpdateRole = function(){
	editForm.getForm().submit({
		success: function(f,a){
			w.hide(); //Hide popup widow
			DoSearch(); //Reload store grid
			editButton.disable();  //Disable Edit Button
			deleteButton.disable(); //Disable Delete Button
			Ext.Msg.alert(TRANSLATIONS.ID_ROLES,TRANSLATIONS.ID_ROLES_SUCCESS_UPDATE);
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

//Edit Selected Role
EditRole = function(){
	iGrid = Ext.getCmp('infoGrid');
	rowSelected = iGrid.getSelectionModel().getSelected();
	if (rowSelected){
		if (rowSelected.data.ROL_UID == '00000000000000000000000000000002'){
			Ext.Msg.alert(TRANSLATIONS.ID_ROLES,TRANSLATIONS.ID_ROLES_MSG);
		}else{
			editForm.getForm().findField('rol_uid').setValue(rowSelected.data.ROL_UID);
			editForm.getForm().findField('code').setValue(rowSelected.data.ROL_CODE);
			editForm.getForm().findField('name').setValue(rowSelected.data.ROL_NAME);
			editForm.getForm().findField('status').setValue(rowSelected.data.ROL_STATUS);
			w = new Ext.Window({
				height: 190,
				width: 420,
				title: TRANSLATIONS.ID_ROLES,
				items: [editForm]
			});
			w.show();
		}
			
	}
}

//Check Can Delete Role
CanDeleteRole = function(){
	iGrid = Ext.getCmp('infoGrid');
	rowSelected = iGrid.getSelectionModel().getSelected();
	if (rowSelected){
		var swDelete = false;
		Ext.Ajax.request({
			url: 'roles_Ajax',
			success: function(response, opts){
				swDelete = (response.responseText=='true') ? true : false;
				if (swDelete){
					Ext.Msg.confirm(TRANSLATIONS.ID_CONFIRM, TRANSLATIONS.ID_REMOVE_ROLE,
					        function(btn, text){
					            if (btn=="yes"){
					            	Ext.Ajax.request({
					            		url: 'roles_Ajax',
					            		params: {request: 'deleteRole', ROL_UID: rowSelected.data.ROL_UID},
					            		success: function(r,o){
					            			infoGrid.store.load(); //Reload store grid
					            			editButton.disable();  //Disable Edit Button
					            			deleteButton.disable(); //Disable Delete Button
					            			Ext.Msg.alert(TRANSLATIONS.ID_ROLES,TRANSLATIONS.ID_ROLES_SUCCESS_DELETE);
					            		},
					            		failure: DoNothing
					            	});
					            }
					});
				}else{
					Ext.Msg.alert(TRANSLATIONS.ID_ROLES,TRANSLATIONS.ID_ROLES_CAN_NOT_DELETE);
				}
			},
			failure: DoNothing,
			params: {request: 'canDeleteRole', ROL_UID: rowSelected.data.ROL_UID}
		});
	}
}


//Open User-Roles Manager
RolesUserPage = function(value){
	iGrid = Ext.getCmp('infoGrid');
	  rowSelected = iGrid.getSelectionModel().getSelected();
	  if (rowSelected){
	    value = rowSelected.data.ROL_UID;
	    location.href = 'rolesUsersPermission?rUID=' + value + '&tab=users';
	  }
}


//Open Permission-Roles Manager
RolesPermissionPage = function(value){
  iGrid = Ext.getCmp('infoGrid');
  rowSelected = iGrid.getSelectionModel().getSelected();
  if (rowSelected){
    value = rowSelected.data.ROL_UID;
    location.href = 'rolesUsersPermission?rUID=' + value + '&tab=permissions';
  }
}

//Renderer Active/Inactive Role
status_role = function(value){
	return (value==1) ? TRANSLATIONS.ID_ACTIVE : TRANSLATIONS.ID_INACTIVE;
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