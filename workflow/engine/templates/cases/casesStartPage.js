var store2 = new Ext.data.ArrayStore({
        fields: ['month', 'hits'],
        data: generateData()
    });

	var store = new Ext.data.JsonStore({
        fields: ['caseStatus', 'total'],
        data: [{
            caseStatus: 'Inbox',
            total: 150
        },{
            caseStatus: 'Draft',
            total: 245
        },{
            caseStatus: 'Unassigned',
            total: 117
        }]
    });
function generateData(){
    var data = [];
    for(var i = 0; i < 12; ++i){
        data.push([Date.monthNames[i], (Math.floor(Math.random() *  11) + 1) * 100]);
    }
    return data;
}

var conn = new Ext.data.Connection();
function getOtherDashboards(dashboardTabPanels){
	dashboardTabPanels.add({
                title: 'Pentaho',
                id: 'pentaho',
                autoLoad:{url: '../../blank/pentahoreports/dashboard', scripts:true}
                //disabled:true,
            });
            dashboardTabPanels.add({
                title: 'Old',
                id: 'old',
                autoLoad:{url: '../dashboard/dashboard', scripts:true},
                disabled:true
            });
}

 var reloadTree = function() {
  console.log(Ext.getCmp('startCaseTreePanel'));
        tree=Ext.getCmp('startCaseTreePanel');
        tree.enable();
        tree.getLoader().dataUrl = 'get-nodes2.php';
        tree.getLoader().load(tree.root);
    };

function getProcesses() {

            //metaID = record.get("MetaID");
            //grid.getGridEl().mask('Loading history...');
            conn.request({
                url: 'casesStartPage_Ajax.php',
                method: 'POST',
                params: {"action": 'getProcessList'},
                success: function(responseObject) {
                    //showHistoryDialog(responseObject.responseText);
                    //grid.getGridEl().unmask(true);
                    Ext.Msg.alert('Status', responseObject.responseText);
                },
                failure: function() {
                    //grid.getGridEl().unmask(true);
                    Ext.Msg.alert('Status', 'Unable to get list of Process');
                }
            });

    }

function getDashboardItems(){
	//getProcesses();


	dashboardItems=[
	{
	  xtype:'window',
	  title:'Start Case',
	  closable:true,
    collapsible:true,
    autoshow:true,
    expandOnShow:true,
    hidden:false,
    x:0,
    y:50,
    items:
    {
		xtype:'treepanel',
		width:450,
		autoHeight:true,
		boxMaxHeight:300,
		maxHeight:300,		
		useArrows: true,
    autoScroll: true,
    animate: true,
    enableDD: true,
    containerScroll: true,
    
    border: true,
    split:true,
    draggable:true,
    itemId: 'startCaseTreePanel',
    id: 'startCaseTreePanel',
    rootVisible: false,
    loader: new Ext.tree.TreeLoader({
      dataUrl:'casesStartPage_Ajax.php',
      baseParams: {action:'getProcessList'} // custom http params
    }),
    listeners:{
      click: function(n) {
        if(n.attributes.optionType=="startProcess"){
            //Ext.Msg.alert('Start Case', 'Starting... "' + n.attributes.text + '"');
            Ext.Msg.show({
              title:'Start Case',
              msg:'Starting new case<br><br> "' + n.attributes.text + '"',
              icon:Ext.MessageBox.INFO
            });
            Ext.Ajax.request({
              url:'casesStartPage_Ajax.php',
              params:{
                action:'startCase',
                processId:n.attributes.pro_uid,
                taskId:n.attributes.tas_uid
                },
                success: function(responseObject) {
                    //showHistoryDialog(responseObject.responseText);
                    //grid.getGridEl().unmask(true);
                    var response=Ext.util.JSON.decode(responseObject.responseText);
                    Ext.Msg.show({
                      title:'Open Case',
                      msg:'Case number <b>' +response.CASE_NUMBER + '</b>',
                      icon:Ext.MessageBox.INFO
                    });                   
                    window.location=response.openCase.PAGE;
                    //Ext.Msg.alert('Status', responseObject.responseText);
                },
                failure: function() {
                    //grid.getGridEl().unmask(true);
                    Ext.Msg.alert('Error', 'Unable to start a case');
                }
            });
          }
        }
    },
    root: {
        nodeType: 'async',
        draggable: false,
        //allowDrop:false,
        id: 'root',
        expanded: true
    },
    tbar: [
     {
            xtype: 'textfield',
            name: 'field1',
            emptyText: 'enter search term',
            enableKeyEvents:true,
            listeners:{
              keyup: function(filterText){
                //console.log(filterText.getValue());
              }
            }
        },
 {xtype: 'tbfill'},
    {
            text: 'Refresh',
            
            handler: function(){
              tree=Ext.getCmp('startCaseTreePanel');                            
              tree.getLoader().load(tree.root);
            }
        }]


	}
	
	}

	,
	{
	  xtype:'window',
	  title:'Graph 1',
	  closable:true,
    collapsible:true,
    autoshow:true,
    expandOnShow:true,
    hidden:false,
    bodyBorder:false,
    border:false,
    hideBorders:true,
    stateful:true,
    
    y:50,
    items:
          {
				            store: store,
				            xtype: 'piechart',
				            dataField: 'total',
				            categoryField: 'caseStatus',				            
				            width : 250,
				            height : 250,
				            draggable:true,
				            disableCaching:true,
				            expressInstall:true,
				            //extra styles get applied to the chart defaults
				            extraStyle:
				            {
				                legend:
				                {
				                    display: 'bottom',
				                    padding: 5,
				                    font:
				                    {
				                        family: 'Tahoma',
				                        size: 13
				                    }
				                }
				            }

				        }
	},
	
     {
      xtype:'window',
	  title:'Graph 1',
	  closable:true,
    collapsible:true,
    autoshow:true,
    expandOnShow:true,
    hidden:false,
    x:0,
    items:
    {
				        	xtype: 'columnchart',
			            store: store2,
			            width : 350,
				          height : 250,
			            yField: 'hits',
			            xField: 'month',
			            draggable:true,
			            xAxis: new Ext.chart.CategoryAxis({
			                title: 'Month'
			            }),
			            yAxis: new Ext.chart.NumericAxis({
			                title: 'Hits'
			            }),
			            extraStyle: {
			               xAxis: {
			                    labelRotation: -90
			                }
			            }
				        }
    }     
				        
				        
				        ,
				        {
				          xtype:'window',
	  title:'Graph 1',
	  closable:true,
    collapsible:true,
    autoshow:true,
    expandOnShow:true,
    hidden:false,
    items:
     {
				        	xtype: 'columnchart',
			            store: store,
			            width : 350,
				          height : 250,
			            yField: 'total',
			            xField: 'caseStatus',
			            draggable:true,
			            xAxis: new Ext.chart.CategoryAxis({
			                title: 'Case Status'
			            }),
			            yAxis: new Ext.chart.NumericAxis({
			                title: 'Total'
			            }),
			            extraStyle: {
			               xAxis: {
			                    labelRotation: -90
			                }
			            }
				        }
				        }
				        
				        
				       
				        
				        ];
	 return dashboardItems;
}
function getDefaultDashboard(){
	defaultDashboard="mainDashboard";
	//Get Server Dashboard default
	//defaultDashboard="pentaho";
	//Get User Dashbaord Default if allowed
	return defaultDashboard;
}
Ext.chart.Chart.CHART_URL = '/images/charts.swf';
Ext.FlashComponent.EXPRESS_INSTALL_URL = '/images/expressinstall.swf';

Ext.onReady(function(){







    var dashboardTabPanels = new Ext.TabPanel({
        renderTo: 'containerStartpage',
        activeTab: '',
        //plain:true,
        autoHeight:true,
        autoWidth:true,
        enableTabScroll:true,
				plugins: new Ext.ux.TabCloseMenu(),

        defaults:{autoScroll: true},
				listeners: {
					tabchange: function(tabPanel,newTab){
						//alert(tabPanel);
						//alert(newTab);
						if(newTab.getUpdater){
							var thisObj = newTab.getUpdater();
						 	if(thisObj) thisObj.refresh();
						}
				  }
			  },
        items:[{
                title: 'Dashboard',
                id: 'mainDashboard',
                iconCls: 'ICON_CASES_START_PAGE',
                xtype: 'container',
                layout:'border',
                autoHeight:true,
                enableDD: true,              
                items: getDashboardItems()
            }
        ]
    });

getOtherDashboards(dashboardTabPanels);

//Set default Dashboard
dashboardName=getDefaultDashboard();
if(!dashboardTabPanels.getItem(dashboardName)) dashboardName="mainDashboard";
dashboardTabPanels.setActiveTab(dashboardName);


});