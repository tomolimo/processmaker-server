//Ext.BLANK_IMAGE_URL = 'resources/s.gif';
var startCaseFilter;

Ext.chart.Chart.CHART_URL = '/images/charts.swf';
Ext.FlashComponent.EXPRESS_INSTALL_URL = '/images/expressinstall.swf';
// The Quicktips are used for the toolbar and Tree mouseover tooltips!
Ext.QuickTips.init();

var conn = new Ext.data.Connection();

var processNumbers = new Ext.data.ArrayStore({
    fields: [
       {name: 'CASES_COUNT_TO_DO', type: 'integer'},
       {name: 'CASES_COUNT_DRAFT', type: 'integer'},
       {name: 'CASES_COUNT_COMPLETED', type: 'integer'},
       {name: 'CASES_COUNT_CANCELLED', type: 'integer'},
       {name: 'CASES_COUNT', type: 'integer'}
    ]
});

var processNumbersData = [
                          [0,0,0,0,0]
                          ];
processNumbers.loadData(processNumbersData);


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
			//mainPanel.openCase(n);
			openCaseA(n);
			
		},
		click : function(n) {
			//mainPanel.showDetails(n);
			showDetailsA(n);
		}
	},
	root : {
		nodeType : 'async',
		draggable : false,
		id : 'root',
		expanded : true
	}
};

Ext.onReady(function() {
		
	var viewport = new Ext.Viewport({
		layout : 'border',
		items : [{
		id : 'startCase',
		//title : 'Start Case',
		//iconCls : 'ICON_CASES_START_CASE',
		layout : 'border',
		region : 'center',
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
			// frame: true,
				labelAlign: 'right',
				labelWidth: 85,
        // width:340,
				waitMsgTarget: true,
				title: TRANSLATIONS.ID_PROCESS_INFORMATION, // 'Process Information', // 
				layout:'form',
				defaults: {width: 350},
				defaultType: 'displayfield',
				items: [{
					fieldLabel: TRANSLATIONS.ID_PROCESS, // 'Process',
					name: 'processName',
					allowBlank:false,
					value: '',
					labelStyle: 'font-weight:bold;',
					// disabled: true,
					// readonly:true,
					id:"processName"
				},
        {
          xtype: 'compositefield',
					fieldLabel: TRANSLATIONS.ID_TASK, // 'Task',
					labelStyle: 'font-weight:bold;',
							
					items: [
									{
										xtype     : 'displayfield',
												 
										//fieldLabel: 'Task',
										//labelStyle: 'font-weight:bold;',
										name: 'taskName',
										allowBlank:false,
										value: '',
										//autoWitdh:true,
										width:200,
										// disabled: true,
										id:"taskName"
									},
									{
										xtype : 'button',
										id : 'starCaseButton',
										disabled : true,
										//cls : 'x-btn-icon',
										//icon : '/images/refresh.gif',
										iconCls: "ICON_CASES_START_CASE",
										text: TRANSLATIONS.ID_TITLE_START_CASE, // "Start Case",
										//margins:"5 5 5 5",
										autoWidth:true,
										handler : function() {				
											tree = Ext.getCmp('startCaseTreePanel');
											var selectedNode = tree.getSelectionModel().getSelectedNode();
											if(selectedNode){
												//mainPanel.openCase(selectedNode);
												openCaseA(selectedNode);
											}
										}
									}
							]
					},
					
					{
						xtype:'textarea',
						fieldLabel: TRANSLATIONS.ID_DESCRIPTION, // 'Description',
						name: 'processDescription',
						value: '',
						readOnly: true,
						labelStyle: 'font-weight:bold;',
						// disabled: true,
						id:"processDescription"
					},{
						fieldLabel: TRANSLATIONS.ID_CATEGORY, // 'Category',
						name: 'processCategory',
						value: '',
						readOnly: true,
						labelStyle: 'font-weight:bold;',
						// disabled: true,
						id:"processCategory"
					}, 
					{
							xtype: 'grid',
						 
							ds: processNumbers,
							cm: new Ext.grid.ColumnModel([
																						{id:'inbox',header: TRANSLATIONS.ID_INBOX, width:70, sortable: false, locked:true, dataIndex: 'CASES_COUNT_TO_DO'},
																						{id:'draft',header: TRANSLATIONS.ID_DRAFT, width:70,  sortable: false, locked:true,  dataIndex: 'CASES_COUNT_DRAFT'},
																						{id:'completed',header: TRANSLATIONS.ID_COMPLETED, width:70,  sortable: false, locked:true, dataIndex: 'CASES_COUNT_COMPLETED'},
																						{id:'canceled',header: TRANSLATIONS.ID_CANCELLED, width:70,  sortable: false, locked:true,  dataIndex: 'CASES_COUNT_CANCELLED'},
																						{id:'totalCases',header: TRANSLATIONS.ID_TOTAL_CASES, width:70,  sortable: false, locked:true , dataIndex: 'CASES_COUNT'}
																						
																				])
	,
							
							//autoExpandColumn: 'company',
							//height: 350,
							width: 355,
							title: TRANSLATIONS.ID_GENERAL_PROCESS_NUMBERS,  // 'General Process Numbers',
							border: true,
							listeners: {
									viewready: function(g) {
											//g.getSelectionModel().selectRow(0);
									} // Allow rows to be rendered.
							}
					},
					
					{
							fieldLabel: TRANSLATIONS.ID_CALENDAR, // 'Calendar',
							name: 'calendarName',
							labelStyle: 'font-weight:bold;',
							// disabled: true,
							id:"calendarName"
				},{
					xtype:'textarea',
					fieldLabel: TRANSLATIONS.ID_CALENDAR_DESCRIPTION,  // 'Calendar Description',
					name: 'calendarDescription',
					value: '',
					labelStyle: 'font-weight:bold;',
					// disabled: true,
					readOnly: true,
					id:"calendarDescription"
			},{
				xtype:'checkboxgroup',
						fieldLabel: TRANSLATIONS.ID_WORKING_DAYS, // 'Working days',
						name: 'calendarWorkDays',
						// disabled: true,
						readOnly: true,
						labelStyle: 'font-weight:bold;',
						id:"calendarWorkDays",
							columns: 7,
								items: [
										{boxLabel: TRANSLATIONS.ID_SUN, name: '0'},
										{boxLabel: TRANSLATIONS.ID_MON, name: '1'},
										{boxLabel: TRANSLATIONS.ID_TUE, name: '2'},
										{boxLabel: TRANSLATIONS.ID_WEN, name: '3'},
										{boxLabel: TRANSLATIONS.ID_THU, name: '4'},
										{boxLabel: TRANSLATIONS.ID_FRI, name: '5'},
										{boxLabel: TRANSLATIONS.ID_SAT, name: '6'}
								]
			 }, {
				xtype:'checkbox',
					fieldLabel: TRANSLATIONS.ID_DEBUG_MODE, // 'Debug Mode',
					name: 'processDebug',
					labelStyle: 'font-weight:bold;',
					// disabled: true,
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
				emptyText : TRANSLATIONS.ID_FIND_A_PROCESS,  // 'Find a Process',
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
    }
    ]
    
	});
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

function openCaseA(n){
	if (n.attributes.optionType == "startProcess") {
		Ext.Msg.show({
			title : TRANSLATIONS.ID_TITLE_START_CASE, //'Start Case',
			msg : TRANSLATIONS.ID_STARTING_NEW_CASE  // 'Starting new case'
				  + '<br><br><b>' + n.attributes.text
					+ '</b>',
			icon : Ext.MessageBox.INFO,
			// width:300,
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
							title : TRANSLATIONS.ID_ERROR_CREATING_NEW_CASE, // 'Error creating a new Case',
							msg : '<textarea cols="50" rows="10">'
									+ res.message + '</textarea>',
							icon : Ext.MessageBox.ERROR,
							buttons : Ext.Msg.OK
						});
					}						
			} catch(e) {						
				Ext.Msg.show({
					title : TRANSLATIONS.ID_ERROR_CREATING_NEW_CASE, // 'Error creating a new Case',
					msg : 'JSON Decode Error:<br /><textarea cols="50" rows="2">'
							+ e.message + '</textarea><br />Server Response<br /><textarea cols="50" rows="5">'+response.responseText+'</textarea>',
					icon : Ext.MessageBox.ERROR,
					buttons : Ext.Msg.OK
				});
				 
			}
				
			},
			failure : function() {
				// grid.getGridEl().unmask(true); UNABLE_START_CASE
				// Ext.Msg.alert('Error', 'Unable to start a case');
        Ext.Msg.alert(TRANSLATIONS.ID_ERROR, TRANSLATIONS.ID_UNABLE_START_CASE); 
			}
		});
	}
};

function showDetailsA(selectedNode) {

	// console.log(selectedNode);
	var detailEl = Ext.getCmp('process-detail-panel').body;
	if ((selectedNode)&&(selectedNode.attributes.otherAttributes)) {
				otherAttributes = selectedNode.attributes.otherAttributes;

calendarDays=(otherAttributes.CALENDAR_WORK_DAYS).split("|");
calendarObj={};

for(i=0;i<calendarDays.length;i++){
calendarObj[calendarDays[i]]=true;
}
//console.log(otherAttributes);
//starCaseButton
Ext.ComponentMgr.get("starCaseButton").enable();
Ext.getCmp('process-detail-panel').getForm().setValues({
processName : otherAttributes.PRO_TITLE,
taskName : otherAttributes.PRO_TAS_TITLE,
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

processNumbersData = [
              [otherAttributes.CASES_COUNT_TO_DO,otherAttributes.CASES_COUNT_DRAFT,otherAttributes.CASES_COUNT_COMPLETED,otherAttributes.CASES_COUNT_CANCELLED,otherAttributes.CASES_COUNT]
              ];
processNumbers.loadData(processNumbersData);





//this.detailsTemplate.overwrite(detailEl, data);
	//	detailEl.slideIn('t', {stopFx:true,duration:.0});
/*					
detailEl.highlight('#c3daf9', {
			block : true
		});*/
	} else {
		//detailEl.update('');
	}

	return;
	// var selNode = this.getSelectedNodes();

};
