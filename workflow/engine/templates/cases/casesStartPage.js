var conn = new Ext.data.Connection();


var caseStatusByProcess = new Ext.data.JsonStore({
	fields : [ 'process','inbox', 'draft', 'unassigned' ],
    root : 'caseStatusByProcess'
});

var caseDelayed = new Ext.data.JsonStore({
	fields : [ 'delayed','total' ],
    root : 'caseDelayed'
});

// function that loads store when it is called
function getDashboardData() {

    var parameters = {       
       action : 'getSimpleDashboardData'
    }
	conn.request( {
		url : 'casesStartPage_Ajax.php',
		method : 'POST',
		params : {
			"action" : 'getSimpleDashboardData'
		},
		success : function(responseObject) {
			// showHistoryDialog(responseObject.responseText);
		// grid.getGridEl().unmask(true);
		// Ext.Msg.alert('Status', responseObject.responseText);
		var responseData = Ext.decode(responseObject.responseText);		
		// Load store from here
        caseStatusByProcess.loadData(responseData);
        caseDelayed.loadData(responseData);
	},
	failure : function() {
		// grid.getGridEl().unmask(true);
		Ext.Msg.alert('Status', 'Unable to get list of Process');
	}
	});
   

}


function getOtherDashboards(dashboardTabPanels) {

	conn.request( {
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
					dashboardTabPanels.add( {
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
		},
		failure : function() {
			// grid.getGridEl().unmask(true);
		Ext.Msg.alert('Status', 'Unable to get Dashboards');
	}
	});
}



function getDashboardItems() {	
	

	dashboardItems = [
			{
				xtype : 'window',
				title : 'Start Case',
				closable : true,
				collapsible : true,
				autoshow : true,
				expandOnShow : true,
				hidden : false,
				boxMaxHeight : 350,
				maxHeight : 300,
				autoScroll : true,
				x : 0,
				y : 50,
				items : {
					xtype : 'treepanel',
					width : 450,
					autoHeight : true,
					useArrows : true,
					autoScroll : true,
					animate : true,
					enableDD : true,
					containerScroll : true,
					constrain : true,
					// border : true,
					// split : true,
					draggable : true,
					itemId : 'startCaseTreePanel',
					id : 'startCaseTreePanel',
					rootVisible : false,
					loader : new Ext.tree.TreeLoader( {
						dataUrl : 'casesStartPage_Ajax.php',
						baseParams : {
							action : 'getProcessList'
						}
					// custom http params
							}),
					listeners : {
						click : function(n) {
							if (n.attributes.optionType == "startProcess") {
								// Ext.Msg.alert('Start Case', 'Starting... "' +
								// n.attributes.text + '"');
								Ext.Msg
										.show( {
											title : 'Start Case',
											msg : 'Starting new case<br><br> "' + n.attributes.text + '"',
											icon : Ext.MessageBox.INFO
										});
								Ext.Ajax
										.request( {
											url : 'casesStartPage_Ajax.php',
											params : {
												action : 'startCase',
												processId : n.attributes.pro_uid,
												taskId : n.attributes.tas_uid
											},
											success : function(responseObject) {
												// showHistoryDialog(responseObject.responseText);
											// grid.getGridEl().unmask(true);
											var response = Ext.util.JSON
													.decode(responseObject.responseText);
											Ext.Msg
													.show( {
														title : 'Open Case',
														msg : 'Case number <b>' + response.CASE_NUMBER + '</b>',
														icon : Ext.MessageBox.INFO
													});
											window.location = response.openCase.PAGE;
											// Ext.Msg.alert('Status',
											// responseObject.responseText);
										},
										failure : function() {
											// grid.getGridEl().unmask(true);
											Ext.Msg.alert('Error',
													'Unable to start a case');
										}
										});
							}
						}
					},
					root : {
						nodeType : 'async',
						draggable : false,
						// allowDrop:false,
						id : 'root',
						expanded : true
					},
					tbar : [ {
						xtype : 'textfield',
						name : 'field1',
						emptyText : 'enter search term',
						enableKeyEvents : true,
						listeners : {
							keyup : function(filterText) {
								// console.log(filterText.getValue());
					}
				}
					}, {
						xtype : 'tbfill'
					}, {
						text : 'Refresh',

						handler : function() {
							tree = Ext.getCmp('startCaseTreePanel');
							tree.getLoader().load(tree.root);
						}
					} ]

				}

			}

			, {
				xtype : 'window',
				title : 'Cases',
				closable : true,
				collapsible : true,
				autoshow : true,
				expandOnShow : true,
				hidden : false,
				bodyBorder : false,
				border : false,
				hideBorders : true,
				stateful : true,

				x: 480,
				y : 50,
				items : {
					store : caseDelayed,
					xtype : 'piechart',
					dataField : 'total',
					categoryField : 'delayed',
					width : 250,
					height : 250,
					draggable : true,
					disableCaching : true,
					expressInstall : true,
					// extra styles get applied to the chart defaults
					extraStyle : {
						legend : {
							display : 'bottom',
							padding : 5,
							font : {
								family : 'Tahoma',
								size : 13
							}
						}
					}

				}
			},
			
			 {
				xtype : 'window',
				title : 'Status by Process',
				closable : false,
				collapsible : false,
				autoshow : true,
				expandOnShow : true,
				hidden : false,
				x:480,
				y:330,
				items : {
					xtype : 'columnchart',
					store : caseStatusByProcess,
					width : 450,
					height : 400,
					series : [{yField:'inbox',displayName:'Inbox'},{yField:'draft',displayName:'Draft'},{yField:'unassigned',displayName:'Unassigned'}],
					xField : 'process',
					draggable : true,
					xAxis : new Ext.chart.CategoryAxis( {
						// title : 'Case Status',
						labelRenderer:  this.customFormat
						 
					}),
					customFormat:function(value){  
					    return 'Year: ';  
					},
					yAxis : new Ext.chart.NumericAxis( {
						title : 'Total'
					}),
					extraStyle : {
						xAxis : {
							labelRotation : -45,							
						},
					legend:{        
				             display: 'right'  
				         } 
					}
				}
			}

	];
	getDashboardData();
	return dashboardItems;
}
function getDefaultDashboard() {
	defaultDashboard = "mainDashboard";
	// Get Server Dashboard default
	// defaultDashboard="pentaho";
	// Get User Dashbaord Default if allowed
	return defaultDashboard;
}
Ext.chart.Chart.CHART_URL = '/images/charts.swf';
Ext.FlashComponent.EXPRESS_INSTALL_URL = '/images/expressinstall.swf';

Ext.onReady(function() {

	var dashboardTabPanels = new Ext.TabPanel( {
		renderTo : 'containerStartpage',
		activeTab : '',
		// plain:true,
		autoHeight : true,
		autoWidth : true,
		enableTabScroll : true,
		plugins : new Ext.ux.TabCloseMenu(),

		defaults : {
			autoScroll : true
		},
		listeners : {
			tabchange : function(tabPanel, newTab) {
				// alert(tabPanel);
		// alert(newTab);
		if (newTab.getUpdater) {
			var thisObj = newTab.getUpdater();
			if (thisObj)
				thisObj.refresh();
		}
	}
},
items : [ {
	title : 'Dashboard',
	id : 'mainDashboard',
	iconCls : 'ICON_CASES_START_PAGE',
	xtype : 'container',
	// layout:'border',
	autoHeight : true,
	enableDD : true,
	items : getDashboardItems()
} ]
	});

	getOtherDashboards(dashboardTabPanels);

	// Set default Dashboard
		dashboardName = getDefaultDashboard();
		if (!dashboardTabPanels.getItem(dashboardName))
			dashboardName = "mainDashboard";
		dashboardTabPanels.setActiveTab(dashboardName);

	});