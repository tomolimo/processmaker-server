//Ext.BLANK_IMAGE_URL = 'resources/s.gif';

var startCaseFilter;

Ext.chart.Chart.CHART_URL = '/images/charts.swf';
Ext.FlashComponent.EXPRESS_INSTALL_URL = '/images/expressinstall.swf';
// The Quicktips are used for the toolbar and Tree mouseover tooltips!
Ext.QuickTips.init();

var conn = new Ext.data.Connection();


function getOtherDashboards(dashboardTabPanels) {
	conn.request({
		url : 'casesStartPage_Ajax.php',
		method : 'POST',
		params : {
			"action" : 'getRegisteredDashboards'
		},
		success : function(responseObject) {
			var response = Ext.util.JSON.decode(responseObject.responseText);
			for ( var i = 0; i < response.length; i++) {
				tabInfo = response[i];
				if (tabInfo.sName) {
					dashboardTabPanels.add({
						title : tabInfo.sName,
						id : tabInfo.sNamespace + "-" + tabInfo.sName,
						iconCls : tabInfo.sIcon,// 'ICON_CASES_START_PAGE',
						autoLoad : {
							url : tabInfo.sPage,
							scripts : true
						}
					// disabled:true,
					});
				}
			}
			getDefaultDashboard(dashboardTabPanels);
		},
		failure : function() {
			// grid.getGridEl().unmask(true);
			getDefaultDashboard(dashboardTabPanels);
			Ext.Msg.alert('Status', 'Unable to get Dashboards');
		}
	});
}

function getDefaultDashboard(dashboardTabPanels) {
	defaultDashboard = "mainDashboard";
	dashboardTabPanels.setActiveTab(defaultDashboard);
	Ext.getCmp("dashboardTabPanels").getEl()
			.mask("Please wait, retrieving data...",
					"ext-el-mask-msg x-mask-loading");

	var parameters = {
		action : 'getDefaultDashboard'
	};
	conn.request({
		url : 'casesStartPage_Ajax.php',
		method : 'POST',
		params : {
			"action" : 'getDefaultDashboard'
		},
		success : function(responseObject) {
			// showHistoryDialog(responseObject.responseText);
			// grid.getGridEl().unmask(true);
			// Ext.Msg.alert('Status', responseObject.responseText);
			var responseData = Ext.decode(responseObject.responseText);
			// console.log(responseData);
			// console.log(responseData.defaultTab);
			if (responseData.defaultTab) {
				defaultDashboard = responseData.defaultTab;
			}
			if (dashboardTabPanels.getItem(defaultDashboard)) {
				dashboardTabPanels.setActiveTab(defaultDashboard);
			}
			Ext.getCmp("dashboardTabPanels").getEl().unmask();

		},
		failure : function() {
			Ext.getCmp("dashboardTabPanels").getEl().unmask();
			// grid.getGridEl().unmask(true);
			// Ext.Msg.alert('Status', 'Unable to get list of Process');
		}
	});

	// Get User Dashbaord Default if allowed
	return defaultDashboard;
}

Docs = {};

var newCaseTree = {
	xtype : 'treepanel',
	id : 'processTree',
	style : {
		height : '100%',
		overflow : 'auto'
	},
	useArrows : true,
	border : false,
	split : true,
	itemId : 'startCaseTreePanel',
	id : 'startCaseTreePanel',
	rootVisible : false,
	treePanel : this,
	clearOnReLoad : false,
	loader : new Ext.tree.TreeLoader({
	  preloadChildren : true,
		dataUrl : 'casesStartPage_Ajax.php',
		baseParams : {
			action : 'getProcessList'
		}
	}),
	listeners : {
		dblclick : function(n) {
			if (n.attributes.optionType == "startProcess") {
				Ext.Msg.show({
					title : 'Start Case',
					msg : 'Starting new case<br><br><b>' + n.attributes.text
							+ '</b>',
					icon : Ext.MessageBox.INFO,
					//width:300,
					wait:true,
			        waitConfig: {interval:500}
				});
				Ext.Ajax.request({
					url : 'casesStartPage_Ajax.php',
					params : {
						action : 'startCase',
						processId : n.attributes.pro_uid,
						taskId : n.attributes.tas_uid
					},
					success : function(response) {
						
						try{ 
							var res = Ext.util.JSON.decode(response.responseText);
							
							if (res.openCase) {
								window.location = res.openCase.PAGE;
							} else {
								Ext.Msg.show({
									title : 'Error creating a new Case',
									msg : '<textarea cols="50" rows="10">'
											+ res.message + '</textarea>',
									icon : Ext.MessageBox.ERROR,
									buttons : Ext.Msg.OK
								});
							}						
					} catch(e) {						
						Ext.Msg.show({
							title : 'Error creating a new Case',
							msg : 'JSON Decode Error:<br /><textarea cols="50" rows="2">'
									+ e.message + '</textarea><br />Server Response<br /><textarea cols="50" rows="5">'+response.responseText+'</textarea>',
							icon : Ext.MessageBox.ERROR,
							buttons : Ext.Msg.OK
						});
						 
					}
						
						
						
						
					},
					failure : function() {
						// grid.getGridEl().unmask(true);
						Ext.Msg.alert('Error', 'Unable to start a case');
					}
				});
			}
		},
		click : function(n) {
			mainPanel.showDetails(n);
		}
	},
	root : {
		nodeType : 'async',
		draggable : false,
		id : 'root',
		expanded : true
	}
};

var startCaseTab = {
	id : 'startCase',
	title : 'Start Case',
	iconCls : 'ICON_CASES_START_CASE',
	layout : 'border',
	items : [ {
		id : 'img-chooser-view',
		region : 'center',
        style : {
            width : '50%'
          },
		// autoScroll: true,
		items : [ newCaseTree ]
	}, {
    	  xtype:'form',
		id : 'process-detail-panel',
		region : 'east',
        // autoHeight : true,
		split : true,
		style : {
			width : '50%'
          	
          },
        
// minWidth : 150,
        //frame: true,
        labelAlign: 'right',
        labelWidth: 85,
  // width:340,
        waitMsgTarget: true,
        title:'Process Information',
        layout:'form',
        defaults: {width: 350},
        defaultType: 'displayfield',
        items: [{
            fieldLabel: 'Process',
            name: 'processName',
            allowBlank:false,
            value: '',
            //disabled: true,
            // readonly:true,
            id:"processName"
        },{
            fieldLabel: 'Task',
            name: 'taskName',
            allowBlank:false,
            value: '',
            //disabled: true,
            id:"taskName"
        },{
        	xtype:'textarea',
            fieldLabel: 'Description',
            name: 'processDescription',
            value: '',
            readOnly: true,
            //disabled: true,
            id:"processDescription"
        },{
            fieldLabel: 'Category',
            name: 'processCategory',
            value: '',
            readOnly: true,
            //disabled: true,
            id:"processCategory"
        }, {
            fieldLabel: 'Calendar',
            name: 'calendarName',            
            //disabled: true,
            id:"calendarName"
      },{
      	xtype:'textarea',
        fieldLabel: 'Calendar Description',
        name: 'calendarDescription',
        value: '',
        //disabled: true,
        readOnly: true,
        id:"calendarDescription"
    },{
    	xtype:'checkboxgroup',
          fieldLabel: 'Working days',
          name: 'calendarWorkDays',
          //disabled: true,
          readOnly: true,
          id:"calendarWorkDays",
        	  columns: 7,
        	    items: [
        	        {boxLabel: 'Sun', name: '0'},
        	        {boxLabel: 'Mon', name: '1'},
        	        {boxLabel: 'Tue', name: '2'},
        	        {boxLabel: 'Wen', name: '3'},
        	        {boxLabel: 'Thu', name: '4'},
        	        {boxLabel: 'Fri', name: '5'},
        	        {boxLabel: 'Sat', name: '6'}
        	    ]
     }, {
    	xtype:'checkbox',
        fieldLabel: 'Debug Mode',
        name: 'processDebug',        
        //disabled: true,
        readOnly: true,
        id:"processDebug"
  }]
      }
  ],

	tbar : [
			{
				xtype : 'textfield',
				name : 'processesFilter',
				id : 'processesFilter',
				emptyText : 'Find a Process',
				enableKeyEvents : true,
				listeners : {
					render : function(f) {
						/*
						 * Ext.getCmp("startCaseTreePanel").filter = new
						 * Ext.tree.TreeFilter( this, { clearBlank : true,
						 * autoClear : true } );
						 */

						startCaseFilter = new Ext.ux.tree.TreeFilterX(Ext
								.getCmp('startCaseTreePanel'));
					}, /*
						 * keydown : function(t, e) { treeFiltered =
						 * Ext.getCmp("startCaseTreePanel");
						 * //console.log(treeFiltered);
						 * 
						 * var text = t.getValue();
						 * 
						 * //console.log(text); if (!text) {
						 * treeFiltered.filter.clear(); return; }
						 * treeFiltered.expandAll();
						 * 
						 * var re = new RegExp('^'+ Ext.escapeRe(text), 'i');
						 * console.log(re);
						 * treeFiltered.filter.filterBy(function(n) { return
						 * !n.attributes.isClass || re.test(n.text); }); },
						 */
					specialkey : function(f, e) {
						if (e.getKey() == e.ENTER) {
							txt = Ext.getCmp('processesFilter').getValue();
							startCaseFilter.clear();
							var re = new RegExp('.*' + txt + '.*', 'i');
							startCaseFilter.filter(re, 'text');
						}
					},
					scope : this
				}
			}, {
				text : 'X',
				ctCls : 'pm_search_x_button',
				handler : function() {
					Ext.getCmp('processesFilter').setValue('');
					startCaseFilter.clear();
				}
			}, ' ', ' ', {
				iconCls : 'icon-expand-all',
				tooltip : 'Expand All',
				handler : function() {
					Ext.getCmp("startCaseTreePanel").root.expand(true);
				},
				scope : this
			}, '-', {
				iconCls : 'icon-collapse-all',
				tooltip : 'Collapse All',
				handler : function() {
					Ext.getCmp("startCaseTreePanel").root.collapse(true);
				},
				scope : this
			}, ' ', ' ', {
				xtype : 'tbbutton',
				cls : 'x-btn-icon',
				icon : '/images/refresh.gif',

				handler : function() {
					tree = Ext.getCmp('startCaseTreePanel');
					tree.getLoader().load(tree.root);
				}
			} ]
};


		    function chDir( directory, loadGridOnly ) {
		   		//console.info("**** Changing Directory: "+directory+" -- "+loadGridOnly);
		    	if( datastore.directory.replace( /\//g, '' ) == directory.replace( /\//g, '' )
		    		&& datastore.getTotalCount() > 0 && directory != '') {
		    		// Prevent double loading
		    		return;
		    	}
		    	datastore.directory = directory;
		    	var conn = datastore.proxy.getConnection();
		    	if( directory == '' || conn && !conn.isLoading()) {
		    		datastore.load({params:{start:0, limit:150, dir: directory, node: directory, option:'gridDocuments', action:'expandNode', sendWhat: datastore.sendWhat }});
		    	}
	
			    if( !loadGridOnly ) {
					expandTreeToDir( null, directory );
		    	}
		    }
			
			function expandTreeToDir( node, dir ) {
				//console.info("Expanding Tree to Dir "+node+" - "+dir);
				dir = dir ? dir : new String('');
				var dirs = dir.split('/');
				if( dirs[0] == '') { dirs.shift(); }
				if( dirs.length > 0 ) {
					//console.log("Dir to expand... "+dirs[0]);
					node = dirTree.getNodeById( dirs[0] );
					if( !node ) return;
					if( node.isExpanded() ) {
						expandNode( node, dir );
						return;
					}
					node.on('load', function() { expandNode( node, dir ); } );
					node.expand();
				}
			}
			function expandNode( node, dir ) {
				//console.info("Expanding Node "+node+" - "+dir);
				var fulldirpath, dirpath;
			
				var dirs = dir.split('/');
				if( dirs[0] == '') { dirs.shift(); }
				if( dirs.length > 0 ) {
					fulldirpath = '';
					for( i=0; i < dirs.length; i++ ) {
						fulldirpath += dirs[i];
					}
					if( node.id.substr( 0, 5 ) != '_RRR_' ) {
						fulldirpath = fulldirpath.substr( 5 );
					}
				
					if( node.id != fulldirpath ) {
						dirpath = '';
				
						var nodedirs = node.id.split('_RRR_');
						if( nodedirs[0] == '' ) nodedirs.shift();
						for( i=0; i < dirs.length; i++ ) {
							if( nodedirs[i] ) {
								dirpath += '_RRR_'+ dirs[i];
							} else {
								dirpath += '_RRR_'+ dirs[i];
								// dirpath = dirpath.substr( 5 );
								var nextnode = dirTree.getNodeById( dirpath );
								if( !nextnode ) { return; }
								if( nextnode.isExpanded() ) { expandNode( nextnode, dir ); return;}
								nextnode.on( 'load', function() { expandNode( nextnode, dir ); } );	

								nextnode.expand();
								break;
							}
						}
					}
					else {
						node.select();
					}
					
				}
			}
		    function handleNodeClick( sm, node ) {		    	
		    	if( node && node.id ) {
		    		//console.log("Node Clicked: "+node);
		    		chDir( node.id );
		    	}
		    } 
		   
		function showLoadingIndicator( el, replaceContent ) {
			//console.info("showLoadingIndicator");
			if( !el ) return;
			var loadingimg = '/images/documents/_indicator.gif';
			var imgtag = '<' + 'img src="'+ loadingimg + '" alt="Loading..." border="0" name="Loading" align="absmiddle" />';

			if( replaceContent ) {
				el.innerHTML = imgtag;
			}
			else {
				el.innerHTML += imgtag;
			}
		}
		function getURLParam( strParamName, myWindow){
			//console.info("getURLParam");
			//console.trace();
			if( !myWindow ){
				myWindow = window;
			}
		  var strReturn = "";
		  var strHref = myWindow.location.href;
		  if ( strHref.indexOf("?") > -1 ){
		    var strQueryString = strHref.substr(strHref.indexOf("?")).toLowerCase();
		    var aQueryString = strQueryString.split("&");
		    for ( var iParam = 0; iParam < aQueryString.length; iParam++ ){
		      if ( aQueryString[iParam].indexOf(strParamName + "=") > -1 ){
		        var aParam = aQueryString[iParam].split("=");
		        strReturn = aParam[1];
		        break;
		      }
		    }
		  }
		  return strReturn;
		}

		function openActionDialog( caller, action ) {
			//console.log("Dialog open: "+caller+" ->"+action);
			var dialog;
			var selectedRows = ext_itemgrid.getSelectionModel().getSelections();
			if( selectedRows.length < 1 ) {
				var selectedNode = dirTree.getSelectionModel().getSelectedNode();
				if( selectedNode ) {
					selectedRows = Array( dirTree.getSelectionModel().getSelectedNode().id.replace( /_RRR_/g, '/' ) );
				}
			}
			var dontNeedSelection = { newFolder:1, uploadDocument:1, search:1 };
			if( dontNeedSelection[action] == null  && selectedRows.length < 1 ) {
				Ext.Msg.alert( 'error','No items Selected');
				return false;
			}

			switch( action ) {
				case 'copy':
				case 'edit':
				case 'newFolder':
				case 'move':
				case 'rename':
				case 'search':
				case 'uploadDocument':
				case 'move':
					requestParams = getRequestParams();
					requestParams.action = action;
					if( action != "edit" ) {
		            	dialog = new Ext.Window( {
		            		id: "dialog",
		                    autoCreate: true,
		                    modal:true,
		                    width:600,
		                    height:400,
		                    shadow:true,
		                    minWidth:300,
		                    minHeight:200,
		                    proxyDrag: true,
		                    resizable: true,
		                    //renderTo: Ext.getBody(),
		                    keys: {
							    key: 27,
							    fn  : function(){
			                        dialog.hide();
			                    }
							}//,
		                    // animateTarget: typeof caller.getEl == 'function'
							// ? caller.getEl() : caller,
							//title: 'dialog_title'
		                   
		            	});			
					}
					Ext.Ajax.request( { url: '../appFolder/appFolderAjax.php',
										params: Ext.urlEncode( requestParams ),
										scripts: true,
										callback: function(oElement, bSuccess, oResponse) {
													if( !bSuccess ) {
														msgbox = Ext.Msg.alert( "Ajax communication failure!");
														msgbox.setIcon( Ext.MessageBox.ERROR );
													}
													if( oResponse && oResponse.responseText ) {
														
														// Ext.Msg.alert("Debug",
														// oResponse.responseText
														// );
														try{ json = Ext.decode( oResponse.responseText );
															if( json.error && typeof json.error != 'xml' ) {																
																Ext.Msg.alert( "error", json.error );
																dialog.destroy();
																return false;
															}
														} catch(e) {
															msgbox = Ext.Msg.alert( "error", "JSON Decode Error: " + e.message );
															msgbox.setIcon( Ext.MessageBox.ERROR );
															return false; 
														}
														if( action == "edit" ) {
															Ext.getCmp("mainpanel").add(json);
															Ext.getCmp("mainpanel").activate(json.id);
														}
														else {
															// we expect the
															// returned JSON to
															// be an object that
															// contains an
															// "Ext.Component"
															// or derivative in
															// xtype notation
															// so we can simply
															// add it to the
															// Window
															//console.log(json);
															dialog.add(json);
															if( json.dialogtitle ) {
																// if the
																// component
																// delivers a
																// title for our
																// dialog we can
																// set the title
																// of the window
																dialog.setTitle(json.dialogtitle);
															}

															try {
																// recalculate
																// layout
																dialog.doLayout();
																// recalculate
																// dimensions,
																// based on
																// those of the
																// newly added
																// child
																// component
																firstComponent = dialog.getComponent(0);
																newWidth = firstComponent.getWidth() + dialog.getFrameWidth();
																newHeight = firstComponent.getHeight() + dialog.getFrameHeight();
																dialog.setSize( newWidth, newHeight );
																
															} catch(e) {}
															// alert( "Before:
															// Dialog.width: " +
															// dialog.getWidth()
															// + ", Client
															// Width: "+
															// Ext.getBody().getWidth());
															if( dialog.getWidth() >= Ext.getBody().getWidth() ) {
																dialog.setWidth( Ext.getBody().getWidth() * 0.8 );
															}
															// alert( "After:
															// Dialog.width: " +
															// dialog.getWidth()
															// + ", Client
															// Width: "+
															// Ext.getBody().getWidth());
															if( dialog.getHeight() >= Ext.getBody().getHeight() ) {
																dialog.setHeight( Ext.getBody().getHeight() * 0.7 );
															} else if( dialog.getHeight() < Ext.getBody().getHeight() * 0.3 ) {
																dialog.setHeight( Ext.getBody().getHeight() * 0.5 );
															}

															// recalculate
															// Window size
															dialog.syncSize();
															// center the window
															dialog.center();
														}
													} else if( !response || !oResponse.responseText) {
														msgbox = Ext.Msg.alert( "error", "Received an empty response");
														msgbox.setIcon( Ext.MessageBox.ERROR );

													}
												}
									});
		            
					if( action != "edit" ) {
		            	dialog.on( 'hide', function() { dialog.destroy(true); } );
		            	dialog.show();
		            }
		            break;

				case 'delete':
					var num = selectedRows.length;
					Ext.Msg.confirm('dellink?', String.format("miscdelitems", num ), deleteFiles);
					break;
				case 'download':
					document.location = '?option=com_extplorer&action=download&item='+ encodeURIComponent(ext_itemgrid.getSelectionModel().getSelected().get('name')) + '&dir=' + encodeURIComponent( datastore.directory );
					break;
			}
		}
		function handleCallback(requestParams, node) {
			//console.log("handleCallback "+requestParams +" -- "+node);
			//console.trace();
			var conn = new Ext.data.Connection();

			conn.request({
				url: '../appFolder/appFolderAjax.php',
				params: requestParams,
				callback: function(options, success, response ) {
					if( success ) {
						json = Ext.decode( response.responseText );
						if( json.success ) {
							statusBarMessage( json.message, false, true );
							try {
								if( dropEvent) {
									dropEvent.target.parentNode.reload();
									dropEvent = null;
								}
								if( node ) {
									if( options.params.action == 'delete' || options.params.action == 'rename' ) {
										node.parentNode.select();
									}
									node.parentNode.reload();
								} else {
									datastore.reload();
								}
							} catch(e) { datastore.reload(); }
						} else {
							Ext.Msg.alert( 'Failure', json.error );
						}
					}
					else {
						Ext.Msg.alert( 'Error', 'Failed to connect to the server.');
					}

				}
			});
		}
		function getRequestParams() {
			//console.info("Get Request params ");
			var selitems, dir, node;
			var selectedRows = ext_itemgrid.getSelectionModel().getSelections();
			if( selectedRows.length < 1 ) {
				node = dirTree.getSelectionModel().getSelectedNode();
				if( node ) {
					var dir = dirTree.getSelectionModel().getSelectedNode().id.replace( /_RRR_/g, '/' );
					var lastSlash = dir.lastIndexOf( '/' );
					if( lastSlash > 0 ) {
						selitems = Array( dir.substring(lastSlash+1) );
					} else {
						selitems = Array( dir );
					}
				} else {
					selitems = {};
				}
				dir = datastore.directory.substring( 0, datastore.directory.lastIndexOf('/'));
			}
			else {
				selitems = Array(selectedRows.length);

				if( selectedRows.length > 0 ) {
					for( i=0; i < selectedRows.length;i++) {
						selitems[i] = selectedRows[i].get('name');
					}
				}
				dir = datastore.directory;
			}
			//Ext.Msg.alert("Debug", datastore.directory );
			var requestParams = {
				option: 'new',
				dir: datastore.directory,
				item: selitems.length > 0 ? selitems[0]:'',
				'selitems[]': selitems
			};
			return requestParams;
		}
		/**
		 * Function for actions, which don't require a form like download,
		 * extraction, deletion etc.
		 */
		function deleteFiles(btn) {
			if( btn != 'yes') {
				return;
			}
			requestParams = getRequestParams();
			requestParams.action = 'delete';
			handleCallback(requestParams);
		}
		function extractArchive(btn) {
			if( btn != 'yes') {
				return;
			}
			requestParams = getRequestParams();
			requestParams.action = 'extract';
			handleCallback(requestParams);
		}
		function deleteDir( btn, node ) {
			if( btn != 'yes') {
				return;
			}
			requestParams = getRequestParams();
			requestParams.dir = datastore.directory.substring( 0, datastore.directory.lastIndexOf('/'));
			requestParams.selitems = Array( node.id.replace( /_RRR_/g, '/' ) );
			requestParams.action = 'delete';
			handleCallback(requestParams, node);
		}

		Ext.msgBoxSlider = function(){
		    var msgCt;

		    function createBox(t, s){
		        return ['<div class="msg">',
		                '<div class="x-box-tl"><div class="x-box-tr"><div class="x-box-tc"></div></div></div>',
		                '<div class="x-box-ml"><div class="x-box-mr"><div id="x-box-mc-inner" class="x-box-mc"><h3>', t, '</h3>', s, '</div></div></div>',
		                '<div class="x-box-bl"><div class="x-box-br"><div class="x-box-bc"></div></div></div>',
		                '</div>'].join('');
		    }
		    return {
		        msg : function(title, format){
		            if(!msgCt){
		                msgCt = Ext.DomHelper.insertFirst(document.body, {id:'msg-div'}, true);
		            }
		            msgCt.alignTo(document, 't-t');
		            var s = String.format.apply(String, Array.prototype.slice.call(arguments, 1));
		            var m = Ext.DomHelper.append(msgCt, {html:createBox(title, s)}, true);
		            m.setWidth(400 );
		            m.position(null, 5000 );
		           m.alignTo(document, 't-t');
		           Ext.get('x-box-mc-inner' ).setStyle('background-image', 'url("/images/documents/_accept.png")');
		           Ext.get('x-box-mc-inner' ).setStyle('background-position', '5px 10px');
		           Ext.get('x-box-mc-inner' ).setStyle('background-repeat', 'no-repeat');
		           Ext.get('x-box-mc-inner' ).setStyle('padding-left', '35px');
		            m.slideIn('t').pause(3).ghost("t", {remove:true});
		        }
		    };
		}();


		function statusBarMessage( msg, isLoading, success ) {
			//console.log("Status Bar needed");
			var statusBar = Ext.getCmp('statusPanel');
			if( !statusBar ) return;
			//console.log("Status bar acceced: "+msg);
			if( isLoading ) {
				statusBar.showBusy();
			}
			else {
				statusBar.setStatus("Done.");
			}
			if( success ) {
				statusBar.setStatus({
				    text: 'success: ' + msg,
				    iconCls: 'success',
				    clear: true
				});
				Ext.msgBoxSlider.msg('success', msg );
			} else if( success != null ) {
				statusBar.setStatus({
				    text: 'error: ' + msg,
				    iconCls: 'error',
				    clear: true
				});
				
			}
			

		}

		function selectFile( dir, file ) {
			//console.log("file selected: "+dir+" - "+file);
			chDir( dir );
			var conn = datastore.proxy.getConnection();
		   	if( conn.isLoading() ) {
		   		setTimeout( "selectFile(\"" + dir + "\", \""+ file + "\")", 1000 );
		   	}
			idx  = datastore.find( "name", file );
			if( idx >= 0 ) {
				ext_itemgrid.getSelectionModel().selectRow( idx );
			}
		}

		/**
		 * Debug Function, that works like print_r for Objects in Javascript
		 */
		function var_dump(obj) {
			var vartext = "";
			for (var prop in obj) {
				if( isNaN( prop.toString() )) {
					vartext += "\t->"+prop+" = "+ eval( "obj."+prop.toString()) +"\n";
				}
		    }
		   	if(typeof obj == "object") {
		    	return "Type: "+typeof(obj)+((obj.constructor) ? "\nConstructor: "+obj.constructor : "") + "\n" + vartext;
		   	} else {
		      	return "Type: "+typeof(obj)+"\n" + vartext;
			}
		}// end function var_dump


datastore = new Ext.data.Store({
	proxy : new Ext.data.HttpProxy({
		url : "../appFolder/appFolderAjax.php",
		directory : "/",
		params : {
			start : 0,
			limit : 150,
			dir : this.directory,
			node : this.directory,
			option : "gridDocuments",
			action : "expandNode"
		}
	}),
	directory : "/",
	sendWhat : "both",
	// create reader that reads the File records
	reader : new Ext.data.JsonReader({
		root : "items",
		totalProperty : "totalCount"
	}, Ext.data.Record.create([ {
		name : "name"
	}, {
		name : "size"
	}, {
		name : "type"
	}, {
		name : "modified"
	}, {
		name : "perms"
	}, {
		name : "icon"
	}, {
		name : "owner"
	}, {
		name : "is_deletable"
	}, {
		name : "is_file"
	}, {
		name : "is_archive"
	}, {
		name : "is_writable"
	}, {
		name : "is_chmodable"
	}, {
		name : "is_readable"
	}, {
		name : "is_deletable"
	}, {
		name : "is_editable"
	}, {
		name : "id"
	}, {
		name : "docVersion"
	}, {
		name : "appDocType"
	}, {
		name : "appDocCreateDate"
	}, {
		name : "appLabel"
	}, {
		name : "proTitle"
	}, {
		name : "appDocVersionable"
	}
	])),

	// turn on remote sorting
	remoteSort : true
});
datastore.paramNames["dir"] = "direction";
datastore.paramNames["sort"] = "order";

datastore.on("beforeload",
		function(ds, options) {
			options.params.dir = options.params.dir ? options.params.dir
					: ds.directory;
			node = options.params.dir ? options.params.dir : ds.directory;
			options.params.option = "gridDocuments";
			options.params.action = "expandNode";
			options.params.sendWhat = datastore.sendWhat;
		});
// pluggable renders
function renderFileName(value, p, record) {
	return String.format(
			'<img src="{0}" alt="* " align="absmiddle" />&nbsp;<b>{1}</b>',
			record.get('icon'), value);
}
function renderType(value, p, record) {	
	if(record.get('appDocType')!=""){
		return String.format('{1} - <i>{0}</i>', value,record.get('appDocType'));
	}else{
		return String.format('<i>{0}</i>', value);
	}
}
function renderVersion(value, p, record) {
	// addcc.png
	// system-search.png
	if(record.get("appDocVersionable")=="1"){
		if(value>1){
		return String.format(
				'<a href="#">{1}</a>&nbsp;&nbsp;<a href="#"><img src="{0}" alt="* " align="absmiddle" width="12" border="0"/></a>',
				"/images/addc.png", value);
		}else{
			return String.format(
					'{1}&nbsp;&nbsp;<a href="#"><img src="{0}" alt="* " align="absmiddle" width="12" border="0"/></a>',
					"/images/addc.png", value);
		}
	}else{
	
	return String.format(
			'<b>-</b>',
			value);
	}
}

var gridtb = new Ext.Toolbar(
		[
				{
					xtype : "tbbutton",
					id : 'tb_home',
					icon : '/images/documents/_home.png',
					//text : 'Root',
					tooltip : 'Root Folder',
					//cls : 'x-btn-text-icon',
					cls : 'x-btn-icon',
					handler : function() {
						chDir('');
					}
				},
				{
					xtype : "tbbutton",
					id : 'tb_reload',
					icon : '/images/documents/_reload.png',
					//text : 'Reload',
					tooltip : 'Reload',
					//cls : 'x-btn-text-icon',
					cls : 'x-btn-icon',
					handler : loadDir
				},

				{
					xtype : "tbbutton",
					id : 'tb_search',
					icon : '/images/documents/_filefind.png',
					//text : 'Search',
					tooltip : 'Search',
					//cls : 'x-btn-text-icon',
					cls : 'x-btn-icon',
					disabled : true,
					handler : function() {
						openActionDialog(this, 'search');
					}
				},
				'-',
				{
					xtype : "tbbutton",
					id : 'tb_new',
					icon : '/images/documents/_filenew.png',
					tooltip : 'New Folder',
					cls : 'x-btn-icon',
					disabled : false,
					handler : function() {
						openActionDialog(this, 'newFolder');
					}
				},
				{
					xtype : "tbbutton",
					id : 'tb_copy',
					icon : '/images/documents/_editcopy.png',
					tooltip : 'Copy',
					cls : 'x-btn-icon',
					disabled : true,
					handler : function() {
						openActionDialog(this, 'copy');
					}
				},
				{
					xtype : "tbbutton",
					id : 'tb_move',
					icon : '/images/documents/_move.png',
					tooltip : 'Move',
					cls : 'x-btn-icon',
					disabled : true,
					handler : function() {
						openActionDialog(this, 'move');
					}
				},
				{
					xtype : "tbbutton",
					id : 'tb_delete',
					icon : '/images/documents/_editdelete.png',
					tooltip : 'dellink',
					cls : 'x-btn-icon',
					disabled : true,
					handler : function() {
						openActionDialog(this, 'delete');
					}
				},
				{
					xtype : "tbbutton",
					id : 'tb_rename',
					icon : '/images/documents/_fonts.png',
					tooltip : 'renamelink',
					cls : 'x-btn-icon',
					disabled : true,
					handler : function() {
						openActionDialog(this, 'rename');
					}
				},
				'-',				
				{
					xtype : "tbbutton",
					id : 'tb_download',
					icon : '/images/documents/_down.png',
					tooltip : 'downlink',
					cls : 'x-btn-icon',
					disabled : true,
					handler : function() {
						openActionDialog(this, 'download');
					}
				},				
				{
					xtype : "tbbutton",
					id : 'tb_upload',
					icon : '/images/documents/_up.png',
					tooltip : 'Upload',
					cls : 'x-btn-icon',
					disabled : false,
					handler : function() {
						/*
						Ext.ux.OnDemandLoad
								.load("/scripts/extjs3-ext/ux.swfupload/SwfUploadPanel.css");
						Ext.ux.OnDemandLoad
								.load("/scripts/extjs3-ext/ux.swfupload/SwfUpload.js");
						Ext.ux.OnDemandLoad
								.load(
										"/scripts/extjs3-ext/ux.swfupload/SwfUploadPanel.js",
										function(options) {
											openActionDialog(this, 'upload');
										});*/
						openActionDialog(this, 'uploadDocument');
					}
				},
				'-',				
				new Ext.Toolbar.Button({
					text : 'Show Dirs',
					enableToggle : true,
					pressed : true,
					handler : function(btn, e) {
						if (btn.pressed) {
							datastore.sendWhat = 'both';
							loadDir();
						} else {
							datastore.sendWhat = 'files';
							loadDir();
						}
					}
				}), '-', new Ext.form.TextField({
					name : "filterValue",
					id : "filterField",
					enableKeyEvents : true,
					title : "Filter current view",
					emptyText : 'Filter current view',
					listeners : {
						"keypress" : {
							fn : function(textfield, e) {
								if (e.getKey() == Ext.EventObject.ENTER) {
									filterDataStore();
								}
							}
						}
					}
				}), new Ext.Toolbar.Button({
					text : '&nbsp;X&nbsp;',
					handler : function() {
						datastore.clearFilter();
						Ext.getCmp("filterField").setValue("");
					}
				})

		]);
function filterDataStore(btn, e) {
	var filterVal = Ext.getCmp("filterField").getValue();
	if (filterVal.length > 1) {
		datastore.filter('name', eval('/' + filterVal + '/gi'));
	} else {
		datastore.clearFilter();
	}
}
// add a paging toolbar to the grid's footer
var gridbb = new Ext.PagingToolbar({
	store : datastore,
	pageSize : 25 ,
	displayInfo : true,
	//displayMsg : '% % %',
	emptyMsg : 'No items to display',
	beforePageText : 'Page',
	//afterPageText : 'of %',
	firstText : 'First',
	lastText : 'Last',
	nextText : 'Next',
	prevText : 'Previous',
	refreshText : 'Reload',
	items : [ '-', ' ', ' ', ' ', ' ', ' ', new Ext.ux.StatusBar({
		defaultText : 'Done',
		id : 'statusPanel'
	}) ]
});

// the column model has information about grid columns
// dataIndex maps the column to the specific data field in
// the data store
var cm = new Ext.grid.ColumnModel([ {
	id : 'gridcm', // id assigned so we can apply custom css (e.g.
					// .x-grid-col-topic b { color:#333 })
	header : "Name",
	dataIndex : 'name',
	width : 200,
	renderer : renderFileName,
	editor : new Ext.form.TextField({
		allowBlank : false
	}),
	css : 'white-space:normal;'
}, {
	header : "Version",
	dataIndex : 'docVersion',
	width : 50,
	align : 'center',
	renderer : renderVersion
}, {
	header : "Modified",
	dataIndex : 'appDocCreateDate',
	width : 65
}, {
	header : "Owner",
	dataIndex : 'owner',
	width : 100
	// sortable : false
}, {
	header : "PM Type",
	dataIndex : 'appDocType',
	width : 70,
	hidden:true
	// align : 'right'
	// renderer : renderType
}, {
	header : "Type",
	dataIndex : 'type',
	width : 100,
	// align : 'right',
	renderer : renderType
}, {
	header : "Process",
	dataIndex : 'proTitle',
	width : 150// ,
	// align : 'right'
	// renderer : renderType
}, {
	header : "Case",
	dataIndex : 'appLabel',
	width : 150// ,
	// align : 'right'
	// renderer : renderType
},{
	header : "Size",
	dataIndex : 'size',
	width : 50,
	hidden:true
}, {
	header : "Permissions",
	dataIndex : 'perms',
	width : 100,
	hidden:true
}, {
	dataIndex : 'is_deletable',
	header : "is_deletable",
	hidden : true,
	hideable : false
}, {
	dataIndex : 'is_file',
	hidden : true,
	hideable : false
}, {
	dataIndex : 'is_archive',
	hidden : true,
	hideable : false
}, {
	dataIndex : 'is_writable',
	hidden : true,
	hideable : false
}, {
	dataIndex : 'is_chmodable',
	hidden : true,
	hideable : false
}, {
	dataIndex : 'is_readable',
	hidden : true,
	hideable : false
}, {
	dataIndex : 'is_deletable',
	hidden : true,
	hideable : false
}, {
	dataIndex : 'is_editable',
	hidden : true,
	hideable : false
}, {
	dataIndex : 'id',
	hidden : true,
	hideable : false
} ]);

// by default columns are sortable
cm.defaultSortable = true;

function handleRowClick(sm, rowIndex) {
	//console.log("Row Clicked: "+rowIndex);
	var selections = sm.getSelections();
	tb = ext_itemgrid.getTopToolbar();
	if (selections.length > 1) {
		tb.items.get('tb_delete').enable();
		tb.items.get('tb_rename').disable();
		tb.items.get('tb_download').disable();
	} else if (selections.length == 1) {
		tb.items.get('tb_delete')[selections[0].get('is_deletable') ? 'enable'
				: 'disable']();
		tb.items.get('tb_rename')[selections[0].get('is_deletable') ? 'enable'
				: 'disable']();
		tb.items.get('tb_download')[selections[0].get('is_readable')
				&& selections[0].get('is_file') ? 'enable' : 'disable']();
	} else {		
		tb.items.get('tb_delete').disable();
		tb.items.get('tb_rename').disable();
		tb.items.get('tb_download').disable();
	}
	return true;
}


// trigger the data store load
function loadDir() {
	//console.info("loadDir");
	//console.trace();
	datastore.load({
		params : {
			start : 0,
			limit : 150,
			dir : datastore.directory,
			node : datastore.directory,
			option : 'gridDocuments',
			action : 'expandNode',
			sendWhat : datastore.sendWhat
		}
	});
}

function rowContextMenu(grid, rowIndex, e, f) {
	//console.log("Context menu: "+grid+" - "+rowIndex);
	if (typeof e == 'object') {
		e.preventDefault();
	} else {
		e = f;
	}
	gsm = ext_itemgrid.getSelectionModel();
	gsm.clickedRow = rowIndex;
	var selections = gsm.getSelections();
	if (selections.length > 1) {
		gridCtxMenu.items.get('gc_delete').enable();
		gridCtxMenu.items.get('gc_rename').disable();
		gridCtxMenu.items.get('gc_download').disable();
	} else if (selections.length == 1) {
		gridCtxMenu.items.get('gc_delete')[selections[0].get('is_deletable') ? 'enable'
				: 'disable']();
		gridCtxMenu.items.get('gc_rename')[selections[0].get('is_deletable') ? 'enable'
				: 'disable']();
		gridCtxMenu.items.get('gc_download')[selections[0].get('is_readable')
				&& selections[0].get('is_file') ? 'enable' : 'disable']();
	}
	gridCtxMenu.show(e.getTarget(), 'tr-br?');

}
gridCtxMenu = new Ext.menu.Menu({
	id : 'gridCtxMenu',

	items : [ {
		id : 'gc_rename',
		icon : '/images/documents/_fonts.png',
		text : 'renamelink',
		handler : function() {
			ext_itemgrid.onCellDblClick(ext_itemgrid, gsm.clickedRow, 0);
			gsm.clickedRow = null;
		}
	}, {
		id : 'gc_copy',
		icon : '/images/documents/_editcopy.png',
		text : 'copylink',
		handler : function() {
			openActionDialog(this, 'copy');
		}
	}, {
		id : 'gc_move',
		icon : '/images/documents/_move.png',
		text : 'movelink',
		handler : function() {
			openActionDialog(this, 'move');
		}
	}, {
		id : 'gc_delete',
		icon : '/images/documents/_editdelete.png',
		text : 'dellink',
		handler : function() {
			openActionDialog(this, 'delete');
		}
	}, '-', {
		id : 'gc_download',
		icon : '/images/documents/_down.png',
		text : 'downlink',
		handler : function() {
			openActionDialog(this, 'download');
		}
	},

	'-', {
		id : 'cancel',
		icon : '/images/documents/_cancel.png',
		text : 'btncancel',
		handler : function() {
			gridCtxMenu.hide();
		}
	} ]
});

function dirContext(node, e) {
	//console.log("Dir context menu: "+node);
	// Select the node that was right clicked
	node.select();
	// Unselect all files in the grid
	ext_itemgrid.getSelectionModel().clearSelections();

	dirCtxMenu.items.get('dirCtxMenu_rename')[node.attributes.is_deletable ? 'enable'
			: 'disable']();
	dirCtxMenu.items.get('dirCtxMenu_remove')[node.attributes.is_deletable ? 'enable'
			: 'disable']();
	
	dirCtxMenu.node = node;
	dirCtxMenu.show(e.getTarget(), 't-b?');

}

function copymove(action) {
	var s = dropEvent.data.selections, r = [];
	if (s) {
		// Dragged from the Grid
		requestParams = getRequestParams();
		requestParams.new_dir = dropEvent.target.id.replace(/_RRR_/g, '/');
		requestParams.new_dir = requestParams.new_dir.replace(/ext_root/g, '');
		requestParams.confirm = 'true';
		requestParams.action = action;
		handleCallback(requestParams);
	} else {
		// Dragged from inside the tree
		// alert('Move ' + dropEvent.data.node.id.replace( /_RRR_/g, '/' )+' to
		// '+ dropEvent.target.id.replace( /_RRR_/g, '/' ));
		requestParams = getRequestParams();
		requestParams.dir = datastore.directory.substring(0,
				datastore.directory.lastIndexOf('/'));
		requestParams.new_dir = dropEvent.target.id.replace(/_RRR_/g, '/');
		requestParams.new_dir = requestParams.new_dir.replace(/ext_root/g, '');
		requestParams.selitems = Array(dropEvent.data.node.id.replace(/_RRR_/g,
				'/'));
		requestParams.confirm = 'true';
		requestParams.action = action;
		handleCallback(requestParams);
	}
}
// context menus
var dirCtxMenu = new Ext.menu.Menu(
		{
			id : 'dirCtxMenu',
			items : [
					{
						id : 'dirCtxMenu_new',
						icon : '/images/documents/_folder_new.png',
						text : 'New',
						handler : function() {
							dirCtxMenu.hide();
							openActionDialog(this, 'newFolder');
						}
					},
					{
						id : 'dirCtxMenu_rename',
						icon : '/images/documents/_fonts.png',
						text : 'Rename',
						handler : function() {
							dirCtxMenu.hide();
							openActionDialog(this, 'rename');
						}
					},
					{
						id : 'dirCtxMenu_copy',
						icon : '/images/documents/_editcopy.png',
						text : 'Copy',
						handler : function() {
							dirCtxMenu.hide();
							openActionDialog(this, 'copy');
						}
					},
					{
						id : 'dirCtxMenu_move',
						icon : '/images/documents/_move.png',
						text : 'Move',
						handler : function() {
							dirCtxMenu.hide();
							openActionDialog(this, 'move');
						}
					},					
					{
						id : 'dirCtxMenu_remove',
						icon : '/images/documents/_editdelete.png',
						text : 'Remove',
						handler : function() {
							dirCtxMenu.hide();
							var num = 1;
							Ext.Msg
									.confirm(
											'Confirm',
											String
													.format(
															"Delete?",
															num),
											function(btn) {
												deleteDir(btn, dirCtxMenu.node);
											});
						}
					}, '-', {
						id : 'dirCtxMenu_reload',
						icon : '/images/documents/_reload.png',
						text : 'Refresh',
						handler : function() {
							dirCtxMenu.hide();
							dirCtxMenu.node.reload();
						}
					}, '-', {
						id : 'dirCtxMenu_cancel',
						icon : '/images/documents/_cancel.png',
						text : 'Cancel',
						handler : function() {
							dirCtxMenu.hide();
						}
					} ]
		});
var copymoveCtxMenu = new Ext.menu.Menu({
	id : 'copyCtx',
	items : [ {
		id : 'copymoveCtxMenu_copy',
		icon : '/images/documents/_editcopy.png',
		text : 'copylink',
		handler : function() {
			copymoveCtxMenu.hide();
			copymove('copy');
		}
	}, {
		id : 'copymoveCtxMenu_move',
		icon : '/images/documents/_move.png',
		text : 'movelink',
		handler : function() {
			copymoveCtxMenu.hide();
			copymove('move');
		}
	}, '-', {
		id : 'copymoveCtxMenu_cancel',
		icon : '/images/documents/_cancel.png',
		text : 'btncancel',
		handler : function() {
			copymoveCtxMenu.hide();
		}
	} ]
});

function copymoveCtx(e) {
	// ctxMenu.items.get('remove')[node.attributes.allowDelete ? 'enable' :
	// 'disable']();
	copymoveCtxMenu.showAt(e.rawEvent.getXY());
}

var documentsTab = {
	id : 'documents',
	title : 'Documents',
	iconCls : 'ICON_FOLDERS',
	layout : 'border',
	defaults : {
		split : true
	},
	items : [
			{
				xtype : "treepanel",
				id : "dirTree",
				region : "west",
				title : 'Directory<img src="/images/refresh.gif" hspace="20" style="cursor:pointer;" title="reload" onclick="Ext.getCmp(\'dirTree\').getRootNode().reload();" alt="Reload" align="middle" />',
				closable : false,
				collapsible: true,
				collapseMode: 'mini',
				// collapsed:true,
				width : 180,
				titlebar : true,
				autoScroll : true,
				animate : true,
				
				// rootVisible: false,
				loader : new Ext.tree.TreeLoader({
					preloadChildren : true,
					dataUrl : '../appFolder/appFolderAjax.php',
					baseParams : {
						action : 'expandNode',
						sendWhat : 'dirs'
					}
				}),
				containerScroll : true,
				enableDD : true,
				ddGroup : 'TreeDD',
				listeners : {
					// "load": { fn: function(node) { chDir( node.id.replace(
					// /_RRR_/g, '/' ), true ); } },
					'contextmenu' : {
						fn : dirContext
					},
					'textchange' : {
						fn : function(node, text, oldText) {
							if (text == oldText)
								return true;
							var requestParams = getRequestParams();
							var dir = node.parentNode.id.replace(/_RRR_/g, '/');
							if (dir == 'root')
								dir = '';
							requestParams.dir = dir;
							requestParams.newitemname = text;
							requestParams.item = oldText;

							requestParams.confirm = 'true';
							requestParams.action = 'rename';
							handleCallback(requestParams);
							ext_itemgrid.stopEditing();
							return true;
						}
					},
					'beforenodedrop' : {
						fn : function(e) {
							dropEvent = e;
							copymoveCtx(e);
						}
					},
					'beforemove' : {
						fn : function() {
							return false;
						}
					}
				},

				root : new Ext.tree.AsyncTreeNode({
					text : '/',
					draggable : false,
					expanded : true,
					id : 'root'
				})
			},
			{
				layout : "border",
				region : "center",
				items : [
						{
							region : "north",
							xtype : "locationbar",
							id : "locationbarcmp",
							height : 28,
							tree : Ext.getCmp("dirTree")
						},
						{
							// region : "center",
							// layout:'fit',
							// items : [ {
								region : "center",
								// xtype : "tabpanel",
								layout:'fit',
								id : "mainpanel",
								// autoHeight : true,
								// enableTabScroll : true,
								// activeTab : 0,
								// hideTabStripItem:0,
								items : [ {
									xtype : "editorgrid",
									layout:'fit',
									region : "center",
									// title : "Documents",
									// autoHeight : true,
									// autoScroll : true,
									// collapsible : false,
									// closeOnTab : true,
									id : "gridpanel",
									ds : datastore,
									cm : cm,
									tbar : gridtb,
									bbar : gridbb,
									ddGroup : 'TreeDD',
									selModel : new Ext.grid.RowSelectionModel({
										listeners : {
											'rowselect' : {
												fn : handleRowClick
											},
											'selectionchange' : {
												fn : handleRowClick
											}
										}
									}),
									loadMask : true,
									keys : [
											{
												key : 'c',
												ctrl : true,
												stopEvent : true,
												handler : function() {
													openActionDialog(this,
															'copy');
												}

											},
											{
												key : 'x',
												ctrl : true,
												stopEvent : true,
												handler : function() {
													openActionDialog(this,
															'move');
												}

											},
											{
												key : 'a',
												ctrl : true,
												stopEvent : true,
												handler : function() {
													ext_itemgrid
															.getSelectionModel()
															.selectAll();
												}
											},
											{
												key : Ext.EventObject.DELETE,
												handler : function() {
													openActionDialog(this,
															'delete');
												}
											} ],
									listeners : {
										'rowcontextmenu' : {
											fn : rowContextMenu
										},
										'celldblclick' : {
											fn : function(grid, rowIndex,
													columnIndex, e) {
												if (Ext.isOpera) {
													// because Opera <= 9
													// doesn't support the
													// right-mouse-button-clicked
													// event (contextmenu)
													// we need to simulate it
													// using the ondblclick
													// event
													rowContextMenu(grid,
															rowIndex, e);
												} else {
													gsm = ext_itemgrid
															.getSelectionModel();
													gsm.clickedRow = rowIndex;
													var selections = gsm
															.getSelections();
													if (!selections[0]
															.get('is_file')) {
														//console.log(datastore.directory);
														chDir(/*datastore.directory																
																+ "/"+*/selections[0]
																		.get('id'));
													} else if (selections[0]
															.get('is_editable')) {
														openActionDialog(this,
																'edit');
													} else if (selections[0]
															.get('is_readable')) {
														openActionDialog(this,
																'view');
													}
												}
											}
										},
										'validateedit' : {
											fn : function(e) {
												if (e.value == e.originalValue)
													return true;
												var requestParams = getRequestParams();
												requestParams.newitemname = e.value;
												requestParams.item = e.originalValue;

												requestParams.confirm = 'true';
												requestParams.action = 'rename';
												handleCallback(requestParams);
												return true;
											}
										}
									}

								} ]// another level
							
							// } /* jj */]
						} 
				  ]
			} ],
			
			listeners : {
				"afterlayout" : {
					fn : function() {
						// alert(Ext.getCmp("locationbarcmp"));
						// Ext.getCmp("documents").
						/*
						if(typeof(sw_afterlayout)!="undefined"){
							//console.log("starting locatiobar");
							Ext.getCmp("locationbarcmp").tree = Ext.getCmp("dirTree");
							Ext.getCmp("locationbarcmp").initComponent();
							//console.log("location abr started");
							return;
						} 
						*/	
						//console.log(typeof(sw_afterlayout));
						sw_afterlayout=true;
						ext_itemgrid = Ext.getCmp("gridpanel");
						//console.log("variable ext_itemgrid created");
						//console.trace();
						ext_itemgrid.un('celldblclick', ext_itemgrid.onCellDblClick);
						//console.log("celldoublde click removed");
						dirTree = Ext.getCmp("dirTree");
						//console.log("dirtree created");
						
						/*
						 * dirTree.loader.on('load', function(loader, o,
						 * response ) { if( response && response.responseText ) {
						 * var json = Ext.decode( response.responseText ); if(
						 * json && json.error ) { Ext.Msg.alert('Error',
						 * json.error +'onLoad'); } } });
						 */

						var tsm = dirTree.getSelectionModel();
						//console.log("tried to gtet selection model");
						tsm.on('selectionchange',
								handleNodeClick);

						// create the editor for the directory
						// tree
						var dirTreeEd = new Ext.tree.TreeEditor(
								dirTree,
								{
									allowBlank : false,
									blankText : 'A name is required',
									selectOnFocus : true
								});
						//console.log("tree editor created");

						//console.log("before the first chdir");
						chDir('');
						//console.log("starting locatiobar first time");
						Ext.getCmp("locationbarcmp").tree = Ext.getCmp("dirTree");
						Ext.getCmp("locationbarcmp").initComponent();
						//console.log("location abr started first time");
						
					}
	
				}
			}

};
/*
 * var dashboardTab = { title : 'Dashboard', id : 'mainDashboard', iconCls :
 * 'ICON_CASES_START_PAGE', xtype : 'container', autoHeight : true, enableDD :
 * false, items : getDashboardItems() };
 */
var MainPanel = function() {
	MainPanel.superclass.constructor.call(this, {
		id : 'doc-body',
		region : 'center',
		resizeTabs : true,
		minTabWidth : 135,
		tabWidth : 135,
		plugins : new Ext.ux.TabCloseMenu(),
		enableTabScroll : true,
		activeTab : -1,
		items : [ startCaseTab, documentsTab /* , dashboardTab */]
	});
};

// console.info("Main Panel - End");
Ext
		.extend(
				MainPanel,
				Ext.TabPanel,
				{
					initEvents : function() {
						MainPanel.superclass.initEvents.call(this);
						 this.body.on('click', this.onClick, this);
					},

					onClick : function(e, target, elementselected) {
						return;
						if (target = e.getTarget('a:not(.exi)', 3)) {
							var cls = Ext.fly(target).getAttributeNS('ext',
									'cls');
							e.stopEvent();
							if (cls) {
								var member = Ext.fly(target).getAttributeNS(
										'ext', 'member');
								this.loadClass(target.href, cls, member);
							} else if (target.className == 'inner-link') {
								this.getActiveTab().scrollToSection(
										target.href.split('#')[1]);
							} else {
								window.open(target.href);
							}
						} else if (target = e.getTarget('.micon', 2)) {
							e.stopEvent();
							var tr = Ext.fly(target.parentNode);
							if (tr.hasClass('expandable')) {
								tr.toggleClass('expanded');
							}
						}
					},
					
					showDetails : function(selectedNode) {

						// console.log(selectedNode);
						var detailEl = Ext.getCmp('process-detail-panel').body;
						if (selectedNode) {
    								otherAttributes = selectedNode.attributes.otherAttributes;
        
        calendarDays=(otherAttributes.CALENDAR_WORK_DAYS).split("|");
        calendarObj={};        
        for(i=0;i<calendarDays.length;i++){
        	calendarObj[calendarDays[i]]=true;
        }
        //console.log(otherAttributes);
        Ext.getCmp('process-detail-panel').getForm().setValues({
        	processName : otherAttributes.PRO_TITLE,
        	taskName : selectedNode.attributes.text,
        	calendarName : otherAttributes.CALENDAR_NAME,
        	calendarDescription : otherAttributes.CALENDAR_DESCRIPTION,
        	processCalendar:otherAttributes.CALENDAR_NAME+" "+otherAttributes.CALENDAR_DESCRIPTION,
        	calendarWorkDays : calendarObj,/* (otherAttributes.CALENDAR_WORK_DAYS).split("|"), */
        	processCategory : otherAttributes.PRO_CATEGORY_LABEL,
        	processDebug : otherAttributes.PRO_DEBUG,
        	processDescription : otherAttributes.PRO_DESCRIPTION,
        	myInbox : otherAttributes.myInbox,
            totalInbox : otherAttributes.totalInbox

        });

        
        
        
        
        // this.detailsTemplate.overwrite(detailEl, data);
							// detailEl.slideIn('t', {stopFx:true,duration:.0});
							detailEl.highlight('#c3daf9', {
								block : true
							});
						} else {
							detailEl.update('');
						}

						return;
						// var selNode = this.getSelectedNodes();

					},
					/*
					 * startNewCase:function(){ alert("asdasdasd"); },
					 */
					loadOtherDashboards : function() {
						// console.info("Getting other Dashboards");
						dashboardTabPanels = this;
						// console.log(dashboardTabPanels);
						conn.request({
							url : 'casesStartPage_Ajax.php',
							method : 'POST',
							params : {
								"action" : 'getRegisteredDashboards'
							},
							success : function(responseObject) {
								var response = Ext.util.JSON
										.decode(responseObject.responseText);
								for (var i in response) {
									tabInfo = response[i];
									if (tabInfo.sName) {
										dashboardTabPanels.add({
											title : tabInfo.sName,
											id : tabInfo.sNamespace + "-"
													+ tabInfo.sName,
											iconCls : tabInfo.sIcon,// 'ICON_CASES_START_PAGE',
											autoLoad : {
												url : tabInfo.sPage,
												scripts : true
											}
										// disabled:true,
										});
									}
								}
								// getDefaultDashboard(dashboardTabPanels);
								dashboardTabPanels.activateDefaultTab();
							},
							failure : function() {
								// grid.getGridEl().unmask(true);
								// getDefaultDashboard(dashboardTabPanels);
								dashboardTabPanels.activateDefaultTab();
								Ext.Msg.alert('Status',
										'Unable to get Dashboards');
							}
						});
					},
					activateDefaultTab : function() {
						// api.expandPath('/root/apidocs');
						// allow for link in
						var page = window.location.href.split('?')[1];
						// console.info("page : "+page);
						if (page) {
							var ps = Ext.urlDecode(page);
							// console.log(ps.action);
							if (ps.action) {
								defaultDashboard = ps.action;
								if (this.getItem(defaultDashboard)) {
									// console.info("Setting the new default
									// dashboard:
									// "+defaultDashboard);
									this.setActiveTab(defaultDashboard);
								}

							}
							// var cls = ps['class'];
							// mainPanel.loadClass('output/' + cls + '.html',
							// cls, ps.member);
						}
					}

				});

var mainPanel = new MainPanel();

Ext.onReady(function() {
	var Cookies = {};
	Cookies.set = function(name, value) {
		var argv = arguments;
		var argc = arguments.length;
		var expires = (argc > 2) ? argv[2] : null;
		var path = (argc > 3) ? argv[3] : '/';
		var domain = (argc > 4) ? argv[4] : null;
		var secure = (argc > 5) ? argv[5] : false;
		document.cookie = name
				+ "="
				+ escape(value)
				+ ((expires == null) ? "" : ("; expires=" + expires
						.toGMTString()))
				+ ((path == null) ? "" : ("; path=" + path))
				+ ((domain == null) ? "" : ("; domain=" + domain))
				+ ((secure == true) ? "; secure" : "");
	};

	Cookies.get = function(name) {
		var arg = name + "=";
		var alen = arg.length;
		var clen = document.cookie.length;
		var i = 0;
		var j = 0;
		while (i < clen) {
			j = i + alen;
			if (document.cookie.substring(i, j) == arg)
				return Cookies.getCookieVal(j);
			i = document.cookie.indexOf(" ", i) + 1;
			if (i == 0)
				break;
		}
		return null;
	};

	Cookies.clear = function(name) {
		if (Cookies.get(name)) {
			document.cookie = name + "="
					+ "; expires=Thu, 01-Jan-70 00:00:01 GMT";
		}
	};

	Cookies.getCookieVal = function(offset) {
		var endstr = document.cookie.indexOf(";", offset);
		if (endstr == -1) {
			endstr = document.cookie.length;
		}
		return unescape(document.cookie.substring(offset, endstr));
	};

	

	mainPanel.on('tabchange', function(tp, tab) {
		if (tab.getUpdater) {
			var thisObj = tab.getUpdater();
			if (thisObj) {
				thisObj.refresh();
			}
		}
	});

	var viewport = new Ext.Viewport({
		layout : 'border',
		items : [
		mainPanel ]
	});

	mainPanel.loadOtherDashboards();

	// console.info("viewport -end");

	viewport.doLayout();

	// routine to hide the debug panel if it is open
	if (parent.PANEL_EAST_OPEN) {
		parent.PANEL_EAST_OPEN = false;
		var debugPanel = parent.Ext.getCmp('debugPanel');
		debugPanel.hide();
		debugPanel.ownerCt.doLayout();
	}

});