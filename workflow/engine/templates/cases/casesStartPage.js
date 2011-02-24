//Ext.BLANK_IMAGE_URL = 'resources/s.gif';

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
var MainPanel = function() {
	MainPanel.superclass.constructor.call(this, {
		id : 'doc-body',
		region : 'center',
		resizeTabs : true,
		minTabWidth : 135,
		tabWidth : 135,
		plugins : new Ext.ux.TabCloseMenu(),
		enableTabScroll : true,
		activeTab : 0,
		items : []
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
						 //this.body.on('click', this.onClick, this);
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

  //routine to hide the debug panel if it is open
  if( typeof parent != 'undefined' ){
    if( typeof parent.parent != 'undefined' ){
      if( parent.parent.PANEL_EAST_OPEN ){
        parent.parent.PANEL_EAST_OPEN = false;
        var debugPanel = parent.parent.Ext.getCmp('debugPanel');
        debugPanel.hide();
        debugPanel.ownerCt.doLayout();
      }
    }
  }

});