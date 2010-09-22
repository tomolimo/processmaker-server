//Ext.BLANK_IMAGE_URL = 'resources/s.gif';

Ext.chart.Chart.CHART_URL = '/images/charts.swf';
Ext.FlashComponent.EXPRESS_INSTALL_URL = '/images/expressinstall.swf';


var conn = new Ext.data.Connection();

var caseStatusByProcess = new Ext.data.JsonStore({
	fields : [ 'process','inbox', 'draft', 'unassigned' ],
    root : 'caseStatusByProcess'
});

var caseDelayed = new Ext.data.JsonStore({
	fields : [ 'delayed','total' ],
    root : 'caseDelayed'
});

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
	

	dashboardItems = [
			{
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
			,
			
			 {
				title : 'Status by Process',
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
			 

	];
	getDashboardData();
	return dashboardItems;
}
function getDefaultDashboard(dashboardTabPanels) {
	defaultDashboard = "mainDashboard";
	dashboardTabPanels.setActiveTab(defaultDashboard);
	Ext.getCmp("dashboardTabPanels").getEl().mask("Please wait, retrieving data...",  "ext-el-mask-msg x-mask-loading");
	

	//console.info("Get Default Dashboard");
	//console.log(defaultDashboard);
	// Get Server Dashboard default
	// defaultDashboard="pentaho";
	// defaultDashboard="startCase";
	
	var parameters = {       
		       action : 'getDefaultDashboard'
		    }
			conn.request( {
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
				//console.log(responseData);
				//console.log(responseData.defaultTab);
				if(responseData.defaultTab){
					defaultDashboard=responseData.defaultTab;
				}
				//console.info("New default dashboard");
				//console.log(defaultDashboard);
				//console.log(dashboardTabPanels);
				//console.log(dashboardTabPanels.getItem(defaultDashboard));
				if (dashboardTabPanels.getItem(defaultDashboard)){
					//console.info("Setting the new default dashboard: "+defaultDashboard);
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
//console.info("Doc Panel - Start");


DocPanel = Ext.extend(Ext.Panel, {
    closable: true,
    autoScroll:true,
    
    initComponent : function(){
        // var ps = this.cclass.split('.');
        // this.title = ps[ps.length-1];
        Ext.apply(this,{
            tbar: ['->',{
                text: 'Config Options',
                handler: this.scrollToMember.createDelegate(this, ['configs']),
                iconCls: 'icon-config'
            },'-',{
                text: 'Properties',
                handler: this.scrollToMember.createDelegate(this, ['props']),
                iconCls: 'icon-prop'
            }, '-',{
                text: 'Methods',
                handler: this.scrollToMember.createDelegate(this, ['methods']),
                iconCls: 'icon-method'
            }, '-',{
                text: 'Events',
                handler: this.scrollToMember.createDelegate(this, ['events']),
                iconCls: 'icon-event'
            }, '-',{
                text: 'Direct Link',
                handler: this.directLink,
                scope: this,
                iconCls: 'icon-fav'
            }, '-',{
                tooltip:'Hide Inherited Members',
                iconCls: 'icon-hide-inherited',
                enableToggle: true,
                scope: this,
                toggleHandler : function(b, pressed){
                     this.body[pressed ? 'addClass' : 'removeClass']('hide-inherited');
                }
            }, '-', {
                tooltip:'Expand All Members',
                iconCls: 'icon-expand-members',
                enableToggle: true,
                scope: this,
                toggleHandler : function(b, pressed){
                    this.body[pressed ? 'addClass' : 'removeClass']('full-details');
                }
            }]
        });
        DocPanel.superclass.initComponent.call(this);
    },

    directLink : function(){
        var link = String.format(
            "<a href=\"{0}\" target=\"_blank\">{0}</a>",
            document.location.href+'?class='+this.cclass
        );
        Ext.Msg.alert('Direct Link to ' + this.cclass,link);
    },
    
    scrollToMember : function(member){
        var el = Ext.fly(this.cclass + '-' + member);
        if(el){
            var top = (el.getOffsetsTo(this.body)[1]) + this.body.dom.scrollTop;
            this.body.scrollTo('top', top-25, {duration:0.75, callback: this.hlMember.createDelegate(this, [member])});
        }
    },

	scrollToSection : function(id){
		var el = Ext.getDom(id);
		if(el){
			var top = (Ext.fly(el).getOffsetsTo(this.body)[1]) + this.body.dom.scrollTop;
			this.body.scrollTo('top', top-25, {duration:0.5, callback: function(){
                Ext.fly(el).next('h2').pause(0.2).highlight('#8DB2E3', {attr:'color'});
            }});
        }
	},

    hlMember : function(member){
        var el = Ext.fly(this.cclass + '-' + member);
        if(el){
            if (tr = el.up('tr')) {
                tr.highlight('#cadaf9');
            }
        }
    }
});

//console.info("Doc Panel - End");
//console.info("Main Panel - Start");
MainPanel = function(){
	

	
    MainPanel.superclass.constructor.call(this, {
        id:'doc-body',
        region:'center',
        //margins:'0 5 5 0',
        resizeTabs: true,
        minTabWidth: 135,
        tabWidth: 135,
        plugins: new Ext.ux.TabCloseMenu(),
        enableTabScroll: true,
        activeTab: 0,
        

        items:[{
            id:'startCase',
            title: 'Start Case',            
            iconCls:'ICON_CASES_START_CASE',
            //autoScroll: true,
            layout:'border',
            items:[{
					id: 'img-chooser-view',					
					region: 'center',					
					//autoScroll: true,
					items: 
					[
    		       {
					xtype : 'treepanel',
					id:'processTree',
					style: {
            //width: '50%',
            height: '100%',
            //marginBottom: '10px',
            overflow:'auto'
        },

					// autoWidth : true,
					//autoHeight : true,
					//useArrows : true,
					//autoScroll : true,
					animate : true,
					//enableDD : true,
					//containerScroll : true,
					//ddScroll:true,
					//singleExpand:true,
					
					// constrain : true,
					border : false,
					split : true,
					//draggable : true,
					itemId : 'startCaseTreePanel',
					id : 'startCaseTreePanel',
					rootVisible : false,
					treePanel:this,
					loader : new Ext.tree.TreeLoader( {
						dataUrl : 'casesStartPage_Ajax.php',
						baseParams : {
							action : 'getProcessList'
						}
					// custom http params
							}),
					listeners : {
					  'dblclick':function(n) {
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
						},
						click : function(n) {
						  this.treePanel.showDetails(n);
						  return;						  
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
					

				}
		        
    		       ]
                 
				},{
					id: 'process-detail-panel',
					region: 'east',
					split: true,
					style: {
           width: '50%',
           
        },
					minWidth: 150,
					//maxWidth: 250,
					html:"jhl was here<br><br>Need to have a brief description here when nothing is selected..."
				}],
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
					},{
        
        xtype: 'tbbutton',
        cls: 'x-btn-icon',
        icon: '/images/refresh.gif',
        
        handler: function(){
          tree = Ext.getCmp('startCaseTreePanel');
							tree.getLoader().load(tree.root);
        }
      } ]
			
        },
        {
        	title : 'Dashboard',
        	id : 'mainDashboard',
        	iconCls : 'ICON_CASES_START_PAGE',
        	xtype : 'container',
        	// layout:'hbox',
        	autoHeight : true,
        	enableDD : false,        	
        	items :getDashboardItems()
             
        }
        ]
    });
};
//console.info("Main Panel - End");
Ext.extend(MainPanel, Ext.TabPanel, {

    initEvents : function(){
        MainPanel.superclass.initEvents.call(this);
        //this.body.on('click', this.onClick, this);
    },

    onClick: function(e, target,elementselected){
      return;
        if(target = e.getTarget('a:not(.exi)', 3)){
            var cls = Ext.fly(target).getAttributeNS('ext', 'cls');
            e.stopEvent();
            if(cls){
                var member = Ext.fly(target).getAttributeNS('ext', 'member');
                this.loadClass(target.href, cls, member);
            }else if(target.className == 'inner-link'){
                this.getActiveTab().scrollToSection(target.href.split('#')[1]);
            }else{
                window.open(target.href);
            }
        }else if(target = e.getTarget('.micon', 2)){
            e.stopEvent();
            var tr = Ext.fly(target.parentNode);
            if(tr.hasClass('expandable')){
                tr.toggleClass('expanded');
            }
        }
    },

    loadClass : function(href, cls, member){
        var id = 'docs-' + cls;
        var tab = this.getComponent(id);
        if(tab){
            this.setActiveTab(tab);
            if(member){
                tab.scrollToMember(member);
            }
        }else{
            var autoLoad = {url: href};
            if(member){
                autoLoad.callback = function(){
                    Ext.getCmp(id).scrollToMember(member);
                }
            }
            var p = this.add(new DocPanel({
                id: id,
                cclass : cls,
                autoLoad: autoLoad
            }));
            this.setActiveTab(p);
        }
    },
    initTemplates : function(){


		this.detailsTemplate = new Ext.XTemplate(
			'<div  class=" x-panel ">',
				'<tpl for=".">',
					'<img src="{url}"><div class="x-panel-bwrap">',
					'<b>Process Name:</b>',
					'<span>{name}</span><br>',
					'<b>Description:</b>',
					'<span>{sizeString}</span><br>',
					'<input type="button" value="Start Case" onclick="alert(\'JHL was here: This should start the following Process/Task{name}. \');">',
					
					
				'</tpl>',
			'</div>'
		);
		this.detailsTemplate.compile();
	},
    showDetails : function(selectedNode){
      
      //console.log(selectedNode);
      var detailEl = Ext.getCmp('process-detail-panel').body;
      if(selectedNode){
        this.initTemplates();
         detailEl.hide();
         data={name:selectedNode.attributes.text};
            this.detailsTemplate.overwrite(detailEl, data);
            detailEl.slideIn('l', {stopFx:true,duration:.2});
            detailEl.highlight('#c3daf9', {block:true});
      }else{
        detailEl.update('');
      }
       
      return;
	    //var selNode = this.getSelectedNodes();
	    
		
	},
    loadOtherDashboards:function(){
    	//console.info("Getting other Dashboards");
    	dashboardTabPanels=this;
    	//console.log(dashboardTabPanels);
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
    			// getDefaultDashboard(dashboardTabPanels);
    			dashboardTabPanels.activateDefaultTab();
    		},
    		failure : function() {
    			// grid.getGridEl().unmask(true);
    			// getDefaultDashboard(dashboardTabPanels);
    			dashboardTabPanels.activateDefaultTab();
    		Ext.Msg.alert('Status', 'Unable to get Dashboards');
    	}
    	});
    },
    activateDefaultTab:function(){
      // api.expandPath('/root/apidocs');
      // allow for link in
      var page = window.location.href.split('?')[1];
      //console.info("page : "+page);
      if(page){
          var ps = Ext.urlDecode(page);
          //console.log(ps.action);
          if(ps.action){
          	defaultDashboard=ps.action;
          	if (this.getItem(defaultDashboard)){
  				//console.info("Setting the new default dashboard: "+defaultDashboard);
  				this.setActiveTab(defaultDashboard);
  			}
          	
          }
          // var cls = ps['class'];
          // mainPanel.loadClass('output/' + cls + '.html', cls, ps.member);
      }
    }
	

	

});


Ext.onReady(function(){
	var Cookies = {};
	Cookies.set = function(name, value){
	     var argv = arguments;
	     var argc = arguments.length;
	     var expires = (argc > 2) ? argv[2] : null;
	     var path = (argc > 3) ? argv[3] : '/';
	     var domain = (argc > 4) ? argv[4] : null;
	     var secure = (argc > 5) ? argv[5] : false;
	     document.cookie = name + "=" + escape (value) +
	       ((expires == null) ? "" : ("; expires=" + expires.toGMTString())) +
	       ((path == null) ? "" : ("; path=" + path)) +
	       ((domain == null) ? "" : ("; domain=" + domain)) +
	       ((secure == true) ? "; secure" : "");
	};

	Cookies.get = function(name){
		var arg = name + "=";
		var alen = arg.length;
		var clen = document.cookie.length;
		var i = 0;
		var j = 0;
		while(i < clen){
			j = i + alen;
			if (document.cookie.substring(i, j) == arg)
				return Cookies.getCookieVal(j);
			i = document.cookie.indexOf(" ", i) + 1;
			if(i == 0)
				break;
		}
		return null;
	};

	Cookies.clear = function(name) {
	  if(Cookies.get(name)){
	    document.cookie = name + "=" +
	    "; expires=Thu, 01-Jan-70 00:00:01 GMT";
	  }
	};

	Cookies.getCookieVal = function(offset){
	   var endstr = document.cookie.indexOf(";", offset);
	   if(endstr == -1){
	       endstr = document.cookie.length;
	   }
	   return unescape(document.cookie.substring(offset, endstr));
	};
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
	//Ext.getCmp("dashboardTabPanels").getEl().mask("Please wait, retrieving data...",  "ext-el-mask-msg x-mask-loading");
	//Ext.getCmp("dashboardTabPanels").getEl().unmask();
	/*
    var t = Ext.get('exttheme');
    if(!t){ // run locally?
        return;
    }
    var theme = Cookies.get('exttheme') || 'aero';
    if(theme){
        t.dom.value = theme;
        Ext.getBody().addClass('x-'+theme);
    }
    t.on('change', function(){
        Cookies.set('exttheme', t.getValue());
        setTimeout(function(){
            window.location.reload();
        }, 250);
    });*/

    Ext.QuickTips.init();


    var mainPanel = new MainPanel();
/*
 * api.on('click', function(node, e){ if(node.isLeaf()){ e.stopEvent();
 * mainPanel.loadClass(node.attributes.href, node.id); } });
 */
    //console.info("mai panel tabchnge -start");
    mainPanel.on('tabchange', function(tp, tab){
        // api.selectClass(tab.cclass);
        //console.info("main tabchange");
        
        if (tab.getUpdater) {
			var thisObj = tab.getUpdater();
			if (thisObj){
				thisObj.refresh();
			}
		}
        
        
    });
    //console.info("mai panel tabchnge -end");
    //console.info("viewport -start");
    var viewport = new Ext.Viewport({
        layout:'border',
        items:[ {
            // cls: 'docs-header',
            // height: 36,
            region:'north',
            xtype:'box',
            el:'header',
            border:false// ,
            // margins: '0 0 5 0'
        }, /* api, */ mainPanel ]
    });
    
    
    mainPanel.loadOtherDashboards();
    
    //console.info("viewport -end");
    
   
    viewport.doLayout();
	
	
	
});