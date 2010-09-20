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
		xtype:'treepanel',
		useArrows: true,
        autoScroll: true,
        animate: true,
        enableDD: true,
        containerScroll: true,
        border: false,
        // auto create TreeLoader
 				loader: new Ext.tree.TreeLoader({
            dataUrl:'casesStartPage_Ajax.php',
            baseParams: {action:'getProcessList'} // custom http params
          }),

      

        root: {
            nodeType: 'async',
            text: 'Start Case',
            draggable: false,
            id: 'root',
            expanded: true
        }

	},
/*
{
	xtype:'grid',
	autoWidth: true,
        autoHeight: true,
        store: new Ext.data.ArrayStore({
            fields: fields,
            data: data
        }),
        columns: columns,
        viewConfig: {
            forceFit: true
        },
        plugins: group

},

{


	xtype:'panel',
	title:'Start case',
	autoWidth: true,
autoScroll:true,
closable:true,

collapsible:true,


layout:'fit',

titleCollapse:true,
        autoHeight:false,
        tbar: [{
            xtype: 'buttongroup',
            title: 'Finance',
            columns: 1,
            defaults: {
                scale: 'small'
            },
            items: [{

                autoWidth:true,
                text: 'Process 1 (task a)'

            },{

                autoWidth:true,
                text: 'Process 1 (task b)'
            },{
                text: 'Process 2 with a long name of process (and a long name of task)',
                autoWidth:true
            },{
                text: 'Process 2 with a long name of process (and other task)',
                autoWidth:true
            },{
                text: 'Process 3  (with long task name)',
                autoWidth:true
            }]
        },{
            xtype: 'buttongroup',
            autoWidth:true,
            title: 'Human Resources',
            columns: 1,
            defaults: {
                scale: 'small'
            },
            items: [{

                text: 'Process 4 (task)'

            },{

                text: 'Process 5 (task)'

            },{
                text: 'Process 6 (task)'

            },{
                text: 'Process 7 (task)'

            },{
                text: 'Process 8 (task)'

            }]
        }]
    },

*/

                {
				            store: store,
				            xtype: 'piechart',
				            dataField: 'total',
				            categoryField: 'caseStatus',
				            width : 250,
				            height : 250,
				            draggable:true,
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

				        },
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
				        },
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
	 var structure = {
        Asia: ['Beijing', 'Tokyo'],
        Europe: ['Berlin', 'London', 'Paris']
    },
    products = ['ProductX', 'ProductY'],
    fields = [],
    columns = [],
    data = [],
    continentGroupRow = [],
    cityGroupRow = [];

      var group = new Ext.ux.grid.ColumnHeaderGroup({
        rows: [continentGroupRow, cityGroupRow]
    });
Ext.onReady(function(){




    /*
     * Example method that generates:
     * 1) The column configuration for the grid
     * 2) The column grouping configuration
     * 3) The data for the store
     * 4) The fields for the store
     */
    function generateConfig(){
        var arr,
            numProducts = products.length;

        Ext.iterate(structure, function(continent, cities){
            continentGroupRow.push({
                header: continent,
                align: 'center',
                colspan: cities.length * numProducts
            });
            Ext.each(cities, function(city){
                cityGroupRow.push({
                    header: city,
                    colspan: numProducts,
                    align: 'center'
                });
                Ext.each(products, function(product){
                    fields.push({
                        type: 'int',
                        name: city + product
                    });
                    columns.push({
                        dataIndex: city + product,
                        header: product,
                        renderer: Ext.util.Format.usMoney
                    });
                });
                arr = [];
                for(var i = 0; i < 20; ++i){
                    arr.push((Math.floor(Math.random()*11) + 1) * 100000);
                }
                data.push(arr);
            });
        })
    }

    // Run method to generate columns, fields, row grouping
    generateConfig();


    /*
     * continentGroupRow at this point is:
     * [
     *     {header: 'Asia', colspan: 4, align: 'center'},
     *     {header: 'Europe', colspan: 6, align: 'center'}
     * ]
     *
     * cityGroupRow at this point is:
     * [
     *     {header: 'Beijing', colspan: 2, align: 'center'},
     *     {header: 'Tokyo', colspan: 2, align: 'center'},
     *     {header: 'Berlin', colspan: 2, align: 'center'},
     *     {header: 'London', colspan: 2, align: 'center'},
     *     {header: 'Paris', colspan: 2, align: 'center'}
     * ]
     */





    var dashboardTabPanels = new Ext.TabPanel({
        renderTo: 'containerStartpage',
        activeTab: '',
        plain:true,
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
                xtype: 'panel',
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