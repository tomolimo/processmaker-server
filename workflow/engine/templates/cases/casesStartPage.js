//Ext.BLANK_IMAGE_URL = 'resources/s.gif';

var startCaseFilter;

Ext.chart.Chart.CHART_URL = '/images/charts.swf';
Ext.FlashComponent.EXPRESS_INSTALL_URL = '/images/expressinstall.swf';

var conn = new Ext.data.Connection();

var caseStatusByProcess = new Ext.data.JsonStore({
	fields : [ 'process', 'inbox', 'draft', 'unassigned' ],
	root : 'caseStatusByProcess'
});

var caseDelayed = new Ext.data.JsonStore({
	fields : [ 'delayed', 'total' ],
	root : 'caseDelayed'
});

function getDashboardData() {

	var parameters = {
		action : 'getSimpleDashboardData'
	};
	conn.request({
		url : 'casesStartPage_Ajax.php',
		method : 'POST',
		params : {
			"action" : 'getSimpleDashboardData'
		},
		success : function(responseObject) {
			var responseData = Ext.decode(responseObject.responseText);

			// Load store from here
			caseStatusByProcess.loadData(responseData);
			caseDelayed.loadData(responseData);
		},
		failure : function() {
			Ext.Msg.alert('Status', 'Unable to get list of Process');
		}
	});
}

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

function getDashboardItems() {
	dashboardItems = [ {
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

	}, {
		title : 'Status by Process',
		xtype : 'columnchart',
		store : caseStatusByProcess,
		width : 450,
		height : 400,
		series : [ {
			yField : 'inbox',
			displayName : 'Inbox'
		}, {
			yField : 'draft',
			displayName : 'Draft'
		}, {
			yField : 'unassigned',
			displayName : 'Unassigned'
		} ],
		xField : 'process',
		draggable : true,
		xAxis : new Ext.chart.CategoryAxis({
			// title : 'Case Status',
			labelRenderer : this.customFormat
		}),
		customFormat : function(value) {
			return 'Year: ';
		},
		yAxis : new Ext.chart.NumericAxis({
			title : 'Total'
		}),
		extraStyle : {
			xAxis : {
				labelRotation : -45
			},
			legend : {
				display : 'right'
			}
		}
	} ];
	getDashboardData();
	return dashboardItems;
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
// console.info("Doc Panel - Start");

DocPanel = Ext.extend(Ext.Panel, {
  closable : true,
  autoScroll : true,

  initComponent : function() {
    // var ps = this.cclass.split('.');
    // this.title = ps[ps.length-1];
    Ext.apply(this, {
      tbar : [
          '->',
          {
            text : 'Config Options',
            handler : this.scrollToMember.createDelegate(
                this, [ 'configs' ]),
            iconCls : 'icon-config'
          },
          '-',
          {
            text : 'Properties',
            handler : this.scrollToMember.createDelegate(
                this, [ 'props' ]),
            iconCls : 'icon-prop'
          },
          '-',
          {
            text : 'Methods',
            handler : this.scrollToMember.createDelegate(
                this, [ 'methods' ]),
            iconCls : 'icon-method'
          },
          '-',
          {
            text : 'Events',
            handler : this.scrollToMember.createDelegate(
                this, [ 'events' ]),
            iconCls : 'icon-event'
          },
          '-',
          {
            text : 'Direct Link',
            handler : this.directLink,
            scope : this,
            iconCls : 'icon-fav'
          },
          '-',
          {
            tooltip : 'Hide Inherited Members',
            iconCls : 'icon-hide-inherited',
            enableToggle : true,
            scope : this,
            toggleHandler : function(b, pressed) {
              this.body[pressed ? 'addClass'
                  : 'removeClass']('hide-inherited');
            }
          },
          '-',
          {
            tooltip : 'Expand All Members',
            iconCls : 'icon-expand-members',
            enableToggle : true,
            scope : this,
            toggleHandler : function(b, pressed) {
              this.body[pressed ? 'addClass'
                  : 'removeClass']('full-details');
            }
          } ]
    });
    DocPanel.superclass.initComponent.call(this);
  },

  directLink : function() {
    var link = String.format(
        "<a href=\"{0}\" target=\"_blank\">{0}</a>",
        document.location.href + '?class=' + this.cclass);
    Ext.Msg.alert('Direct Link to ' + this.cclass, link);
  },

  scrollToMember : function(member) {
    var el = Ext.fly(this.cclass + '-' + member);
    if (el) {
      var top = (el.getOffsetsTo(this.body)[1])
          + this.body.dom.scrollTop;
      this.body.scrollTo('top', top - 25, {
        duration : 0.75,
        callback : this.hlMember.createDelegate(this,
            [ member ])
      });
    }
  },

  scrollToSection : function(id) {
    var el = Ext.getDom(id);
    if (el) {
      var top = (Ext.fly(el).getOffsetsTo(this.body)[1])
          + this.body.dom.scrollTop;
      this.body.scrollTo('top', top - 25, {
        duration : 0.5,
        callback : function() {
          Ext.fly(el).next('h2').pause(0.2).highlight(
              '#8DB2E3', {
                attr : 'color'
              });
        }
      });
    }
  },

  hlMember : function(member) {
    var el = Ext.fly(this.cclass + '-' + member);
    if (el) {
      if (tr = el.up('tr')) {
        tr.highlight('#cadaf9');
      }
    }
  }
});


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
          msg : 'Starting new case<br><br><b>'
              + n.attributes.text
              + '</b>',
          icon : Ext.MessageBox.INFO
        });
        Ext.Ajax.request({
          url : 'casesStartPage_Ajax.php',
          params : {
            action : 'startCase',
            processId : n.attributes.pro_uid,
            taskId : n.attributes.tas_uid
          },
          success : function(response) {
            var res = Ext.util.JSON.decode(response.responseText);
            if (res.openCase) {
              window.location = res.openCase.PAGE;
            } else {
              Ext.Msg.show({
                title : 'Error creating a new Case',
                msg : '<textarea cols="50" rows="10">'
                    + res.message
                    + '</textarea>',
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
  items : [
      {
        id : 'img-chooser-view',
        region : 'center',
        // autoScroll: true,
        items : [ newCaseTree ]
      }, {
        id : 'process-detail-panel',
        region : 'east',
        autoHeight : true,
        split : true,
        style : {
          width : '50%'
        },
        minWidth : 150,
        html : ""
      }
  ],

  tbar : [
      {
        xtype : 'textfield',
        name : 'processesFilter',
        id: 'processesFilter',
        emptyText : 'Find a Process',
        enableKeyEvents : true,
        listeners : {
          render : function(f) {
            /*Ext.getCmp("startCaseTreePanel").filter = new Ext.tree.TreeFilter(
              this,
              {
                clearBlank : true,
                autoClear : true
              }
            );*/

            startCaseFilter = new Ext.ux.tree.TreeFilterX(Ext.getCmp('startCaseTreePanel'));
          }, /*
          keydown : function(t, e) {
            treeFiltered = Ext.getCmp("startCaseTreePanel");
            //console.log(treeFiltered);

            var text = t.getValue();

            //console.log(text);
            if (!text) {
              treeFiltered.filter.clear();
              return;
            }
            treeFiltered.expandAll();

            var re = new RegExp('^'+ Ext.escapeRe(text), 'i');
            console.log(re);
            treeFiltered.filter.filterBy(function(n) {
              return !n.attributes.isClass || re.test(n.text);
            });
          },*/
          specialkey: function(f,e){
            if (e.getKey() == e.ENTER) {
              txt = Ext.getCmp('processesFilter').getValue();
              startCaseFilter.clear();
              var re = new RegExp('.*' + txt + '.*', 'i');
              startCaseFilter.filter(re, 'text');
            }
          },
          scope : this
        }
      },
      {
        text:'X',
        ctCls:'pm_search_x_button',
        handler: function(){
          Ext.getCmp('processesFilter').setValue('');
          startCaseFilter.clear();
        }
      },
      ' ',
      ' ',
      {
        iconCls : 'icon-expand-all',
        tooltip : 'Expand All',
        handler : function() {
          Ext.getCmp("startCaseTreePanel").root.expand(true);
        },
        scope : this
      },
      '-',
      {
        iconCls : 'icon-collapse-all',
        tooltip : 'Collapse All',
        handler : function() {
          Ext.getCmp("startCaseTreePanel").root.collapse(true);
        },
        scope : this
      },
      ' ',
      ' ',
      {
        xtype : 'tbbutton',
        cls : 'x-btn-icon',
        icon : '/images/refresh.gif',

        handler : function() {
          tree = Ext
              .getCmp('startCaseTreePanel');
          tree.getLoader().load(
              tree.root);
        }
      }
   ]
};

var documentsTab = {
  id : 'documents',
  title : 'Documents',
  iconCls : 'ICON_FOLDERS',
  layout : 'border',
  items : [ {
    id : 'documentsView',
    region : 'center',
    // autoScroll: true,
    items : [ {
      xtype : 'treepanel',
      id : 'documentsTree',
      style : {
        // width: '50%',
        height : '100%',
        overflow : 'auto'
      },
      useArrows : true,
      border : false,
      split : true,
      itemId : 'documentsTreePanel',
      id : 'documentsTreePanel',
      rootVisible : true,
      treePanel : this,
      clearOnReLoad : false,
      loader : new Ext.tree.TreeLoader(
          {
            dataUrl : '../appFolder/appFolderAjax.php',
            baseParams : {
              action : 'expandNode'
            }
          }
      ),
      listeners : {
        dblclick : function(n) {
          if (n.attributes.optionType == "startProcess") {
            Ext.Msg.show({
              title : 'Start Case',
              msg : 'Starting new case<br><br><b>'
                  + n.attributes.text
                  + '</b>',
              icon : Ext.MessageBox.INFO
            });
            Ext.Ajax.request({
                  url : 'casesStartPage_Ajax.php',
                  params : {
                    action : 'startCase',
                    processId : n.attributes.pro_uid,
                    taskId : n.attributes.tas_uid
                  },
                  success : function(
                      response) {
                    var res = Ext.util.JSON
                        .decode(response.responseText);
                    if (res.openCase) {
                      window.location = res.openCase.PAGE;
                    } else {
                      Ext.Msg.show({
                        title : 'Error creating a new Case',
                        msg : '<textarea cols="50" rows="10">'
                            + res.message
                            + '</textarea>',
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
    } ]
  // items
  } ],

  tbar : [
      {
        xtype : 'textfield',
        name : 'field1',
        emptyText : 'Filter',
        enableKeyEvents : true,
        listeners : {
          render : function(f) {
            Ext.getCmp("documentsTreePanel").filter = new Ext.tree.TreeFilter(
              this,
              {
                clearBlank : true,
                autoClear : true
              }
            );
          },
          keydown : function(t, e) {
            treeFiltered = Ext.getCmp("documentsTreePanel");
            var text = t.getValue();
            if (!text) {
              treeFiltered.filter
                  .clear();
              return;
            }
            treeFiltered
                .expandAll();

            var re = new RegExp(
                '^'
                    + Ext
                        .escapeRe(text),
                'i');
            treeFiltered.filter
                .filterBy(function(
                    n) {
                  // return
                  // !n.attributes.isClass
                  // ||
                  // re.test(n.text);
                });
            /*
             * // hide empty
             * packages that weren't
             * filtered
             * this.hiddenPkgs = [];
             * var me = this;
             * this.root.cascade(function(n){
             * if(!n.attributes.isClass &&
             * n.ui.ctNode.offsetHeight <
             * 3){ n.ui.hide();
             * me.hiddenPkgs.push(n); }
             * });
             */
          },
          scope : this
        }
      },
      ' ',
      ' ',
      {
        iconCls : 'icon-expand-all',
        tooltip : 'Expand All',
        handler : function() {
          Ext
              .getCmp("documentsTreePanel").root
              .expand(true);
        },
        scope : this
      },
      '-',
      {
        iconCls : 'icon-collapse-all',
        tooltip : 'Collapse All',
        handler : function() {
          Ext
              .getCmp("documentsTreePanel").root
              .collapse(true);
        },
        scope : this
      },
      ' ',
      ' ',
      {
        xtype : 'tbbutton',
        cls : 'x-btn-icon',
        icon : '/images/refresh.gif',

        handler : function() {
          tree = Ext
              .getCmp('documentsTreePanel');
          tree.getLoader().load(
              tree.root);
        }
      }
   ]
};
/*
var dashboardTab = {
  title : 'Dashboard',
  id : 'mainDashboard',
  iconCls : 'ICON_CASES_START_PAGE',
  xtype : 'container',
  autoHeight : true,
  enableDD : false,
  items : getDashboardItems()
};
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
    activeTab : 0,
    items : [startCaseTab/*, documentsTab, dashboardTab*/]
  });
};

// console.info("Main Panel - End");
Ext.extend(
  MainPanel,
  Ext.TabPanel,
  {
    initEvents : function() {
      MainPanel.superclass.initEvents.call(this);
      // this.body.on('click', this.onClick, this);
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

    loadClass : function(href, cls, member) {
      var id = 'docs-' + cls;
      var tab = this.getComponent(id);
      if (tab) {
        this.setActiveTab(tab);
        if (member) {
          tab.scrollToMember(member);
        }
      } else {
        var autoLoad = {
          url : href
        };
        if (member) {
          autoLoad.callback = function() {
            Ext.getCmp(id).scrollToMember(member);
          };
        }
        var p = this.add(new DocPanel({
          id : id,
          cclass : cls,
          autoLoad : autoLoad
        }));
        this.setActiveTab(p);
      }
    },

    initTemplates : function() {

      this.detailsTemplate = new Ext.XTemplate(
          '<div  class="details" style="background-color:Gainsboro">',
          '<tpl for=".">',
          '<!--<img src="{url}">-->',
          '<div class="details-info" style="background-color:#ffffff;/* for IE */ filter:alpha(opacity=90); /* CSS3 standard */ opacity:0.9;">',

          '<!-- <div style="width:90%;height:30%;padding:0px;overflow:auto;border:5px dashed black;margin:10px" id="pm_target" ></div>-->',
          '<h1><center><u>{taskName}</u></center></h1><br>',
          '<b>Process Name:</b>',
          '<span>{processName}</span>',
          '<b>Description: </b>',
          '<span>{processDescription}</span>',
          '<table>',
          '<tr>',
          '<td>',
          '<b>Category: </b>',

          '<span>{processCategory}</span>',

          '</td>',
          '<td>',
          '<b>Calendar: </b>',
          '<span>{calendarName} ({calendarDescription})</span>',
          '</td>',
          '<td>',
          '<b>Working Days: </b>',
          '<span>{calendarWorkDays}</span>',
          '</td>',
          '<td>',
          '<b>Debug mode: </b>',
          '<span>{processDebug}</span>',
          '</tr>',
          '</table>',
          '<b>Statistics: </b>',
          '<span><b>Active Cases (Mine/Total):</b>{myInbox} / {totalInbox}</span>',

          '<hr><span><i>Double click to start any Process/Case</i></span>',
          '<!-- <input type="button" value="Start Case" onclick="alert(\'JHL was here: This should start the following Process/Task{name}. \');alert(mainPanel);alert(mainPanel.startNewCase);"> -->',

          '</tpl>', '</div>');
      this.detailsTemplate.compile();
    },

    showDetails : function(selectedNode) {

      // console.log(selectedNode);
      var detailEl = Ext.getCmp('process-detail-panel').body;
      if (selectedNode) {
        this.initTemplates();
        // detailEl.hide();
        // detailEl.sequenceFx();
        // detailEl.slideOut('l',
        // {stopFx:true,duration:.9});

        otherAttributes = selectedNode.attributes.otherAttributes;
        taskName = selectedNode.attributes.text;
        processName = otherAttributes.PRO_TITLE;
        calendarName = otherAttributes.CALENDAR_NAME;
        calendarDescription = otherAttributes.CALENDAR_DESCRIPTION;
        calendarWorkDays = otherAttributes.CALENDAR_WORK_DAYS;
        processCategory = otherAttributes.PRO_CATEGORY_LABEL;
        if (otherAttributes.PRO_DEBUG == 0) {
          processDebug = "False";
        } else {
          processDebug = "True";
        }
        processDescription = otherAttributes.PRO_DESCRIPTION;
        myInbox = otherAttributes.myInbox;
        totalInbox = otherAttributes.totalInbox;

        // data={name:selectedNode.attributes.text};
        data = {
          taskName : taskName,
          processName : processName,
          calendarName : calendarName,
          calendarDescription : calendarDescription,
          calendarWorkDays : calendarWorkDays,
          processCategory : processCategory,
          processDebug : processDebug,
          processDescription : processDescription,
          myInbox : myInbox,
          totalInbox : totalInbox
        };
        this.detailsTemplate.overwrite(detailEl, data);
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
          for ( var i = 0; i < response.length; i++) {
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

	// function that loads store when it is called
	function getDashboardData() {
		var parameters = {
			action : 'getSimpleDashboardData'
		};
		conn.request({
			url : 'casesStartPage_Ajax.php',
			method : 'POST',
			params : {
				"action" : 'getSimpleDashboardData'
			},
			success : function(responseObject) {
				var responseData = Ext.decode(responseObject.responseText);

				// Load store from here
				caseStatusByProcess.loadData(responseData);
				caseDelayed.loadData(responseData);
			},
			failure : function() {
				Ext.Msg.alert('Status', 'Unable to get list of Process');
			}
		});
	}

	Ext.QuickTips.init();

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
		/*
		 * { // cls: 'docs-header', // height: 36, region:'north', xtype:'box',
		 * el:'header', border:false// , // margins: '0 0 5 0' },
		 */
		mainPanel ]
	});

	mainPanel.loadOtherDashboards();

	// console.info("viewport -end");

	viewport.doLayout();


  //routine to hide the debug panel if it is open
  if( parent.PANEL_EAST_OPEN ){
    parent.PANEL_EAST_OPEN = false;
    var debugPanel = parent.Ext.getCmp('debugPanel');
    debugPanel.hide();
    debugPanel.ownerCt.doLayout();
  }
  
});






// vim: ts=4:sw=4:nu:fdc=4:nospell
/*global Ext */
/**
 * @class   Ext.ux.tree.TreeFilterX
 * @extends Ext.tree.TreeFilter
 *
 * <p>
 * Shows also parents of matching nodes as opposed to default TreeFilter. In other words
 * this filter works "deep way".
 * </p>
 *
 * @author   Ing. Jozef Sakáloš
 * @version  1.0
 * @date     17. December 2008
 * @revision $Id: Ext.ux.tree.TreeFilterX.js 589 2009-02-21 23:30:18Z jozo $
 * @see      <a href="http://extjs.com/forum/showthread.php?p=252709">http://extjs.com/forum/showthread.php?p=252709</a>
 *
 * @license Ext.ux.tree.CheckTreePanel is licensed under the terms of
 * the Open Source LGPL 3.0 license.  Commercial use is permitted to the extent
 * that the code/component(s) do NOT become part of another Open Source or Commercially
 * licensed development library or toolkit without explicit permission.
 *
 * <p>License details: <a href="http://www.gnu.org/licenses/lgpl.html"
 * target="_blank">http://www.gnu.org/licenses/lgpl.html</a></p>
 *
 * @forum     55489
 * @demo      http://remotetree.extjs.eu
 *
 * @donate
 * <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
 * <input type="hidden" name="cmd" value="_s-xclick">
 * <input type="hidden" name="hosted_button_id" value="3430419">
 * <input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-butcc-donate.gif"
 * border="0" name="submit" alt="PayPal - The safer, easier way to pay online.">
 * <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
 * </form>
 */

Ext.ns('Ext.ux.tree');

/**
 * Creates new TreeFilterX
 * @constructor
 * @param {Ext.tree.TreePanel} tree The tree panel to attach this filter to
 * @param {Object} config A config object of this filter
 */
Ext.ux.tree.TreeFilterX = Ext.extend(Ext.tree.TreeFilter, {
	/**
	 * @cfg {Boolean} expandOnFilter Deeply expands startNode before filtering (defaults to true)
	 */
	 expandOnFilter:true

	// {{{
    /**
     * Filter the data by a specific attribute.
	 *
     * @param {String/RegExp} value Either string that the attribute value
     * should start with or a RegExp to test against the attribute
     * @param {String} attr (optional) The attribute passed in your node's attributes collection. Defaults to "text".
     * @param {TreeNode} startNode (optional) The node to start the filter at.
     */
	,filter:function(value, attr, startNode) {

		// expand start node
		if(false !== this.expandOnFilter) {
			startNode = startNode || this.tree.root;
			var animate = this.tree.animate;
			this.tree.animate = false;
			startNode.expand(true, false, function() {

				// call parent after expand
				Ext.ux.tree.TreeFilterX.superclass.filter.call(this, value, attr, startNode);

			}.createDelegate(this));
			this.tree.animate = animate;
		}
		else {
			// call parent
			Ext.ux.tree.TreeFilterX.superclass.filter.apply(this, arguments);
		}

	} // eo function filter
	// }}}
	// {{{
    /**
     * Filter by a function. The passed function will be called with each
     * node in the tree (or from the startNode). If the function returns true, the node is kept
     * otherwise it is filtered. If a node is filtered, its children are also filtered.
	 * Shows parents of matching nodes.
	 *
     * @param {Function} fn The filter function
     * @param {Object} scope (optional) The scope of the function (defaults to the current node)
     */
	,filterBy:function(fn, scope, startNode) {
		startNode = startNode || this.tree.root;
		if(this.autoClear) {
			this.clear();
		}
		var af = this.filtered, rv = this.reverse;

		var f = function(n) {
			if(n === startNode) {
				return true;
			}
			if(af[n.id]) {
				return false;
			}
			var m = fn.call(scope || n, n);
			if(!m || rv) {
				af[n.id] = n;
				n.ui.hide();
				return true;
			}
			else {
				n.ui.show();
				var p = n.parentNode;
				while(p && p !== this.root) {
					p.ui.show();
					p = p.parentNode;
				}
				return true;
			}
			return true;
		};
		startNode.cascade(f);

        if(this.remove){
           for(var id in af) {
               if(typeof id != "function") {
                   var n = af[id];
                   if(n && n.parentNode) {
                       n.parentNode.removeChild(n);
                   }
               }
           }
        }
	} // eo function filterBy
	// }}}

}); // eo extend

// eof


