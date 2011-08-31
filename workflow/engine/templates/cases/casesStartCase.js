//Ext.BLANK_IMAGE_URL = 'resources/s.gif';
var startCaseFilter;

Ext.chart.Chart.CHART_URL = '/images/charts.swf';
Ext.FlashComponent.EXPRESS_INSTALL_URL = '/images/expressinstall.swf';
// The Quicktips are used for the toolbar and Tree mouseover tooltips!


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

var processNumbersData = [[0,0,0,0,0]];
//processNumbers.loadData(processNumbersData);
Docs = {};


Ext.onReady(function() {
  var newCaseTree = new Ext.ux.MaskTree({
    id: 'startCaseTreePanel',
    region: 'center',
    //autoWidth: true,
    useArrows: true,
    animate: true,
    split : true,
    autoScroll: true,
    dataUrl: 'casesStartPage_Ajax?action=getProcessList',
    rootVisible: false,
    containerScroll: true,
    border: false,
    root: {
      nodeType: 'async',
      expanded : true
    },
    tbar : [
      {
        xtype : 'textfield',
        name : 'processesFilter',
        id : 'processesFilter',
        emptyText : _('ID_FIND_A_PROCESS'),  // 'Find a Process',
        enableKeyEvents : true,
        listeners : {
          render : function(f) {
            startCaseFilter = new Ext.ux.tree.TreeFilterX(Ext.getCmp('startCaseTreePanel'));
          },
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
        tooltip :  _('ID_EXPAND_ALL'), //'Expand All',
        handler : function() {
          Ext.getCmp("startCaseTreePanel").root.expand(true);
        },
        scope : this
      }, '-', {
        iconCls : 'icon-collapse-all',
        tooltip : _('ID_COLLAPSE_ALL'), //'Collapse All',
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
      }
    ],
    listeners : {
      dblclick : function(n) {
        //mainPanel.openCase(n);
        openCaseA(n);

      },
      click : function(n) {
        //mainPanel.showDetails(n);
        showDetailsA(n);
      }
    }
  });

  var details = {
      xtype:'form',
      id : 'process-detail-panel',
      region : 'east',
      // autoHeight : true,
      split : true,
      style : {
        width : '450'
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
              xtype : 'button',
              id : 'starCaseButton',
              disabled : true,
              // cls :
              // 'x-btn-icon',
              // icon :
              // '/images/refresh.gif',
              iconCls : "ICON_CASES_START_CASE",
              text : TRANSLATIONS.ID_TITLE_START_CASE, // "Start
                                    // Case",
              // margins:"5 5
              // 5 5",
              autoWidth : true,
              handler : function() {
                tree = Ext
                    .getCmp('startCaseTreePanel');
                var selectedNode = tree
                    .getSelectionModel()
                    .getSelectedNode();
                if (selectedNode) {
                  //mainPanel.openCase(selectedNode);
                  openCaseA(selectedNode);
                }
              }
            },
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
            }

          ]},
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
              fieldLabel: ' ',
              labelSeparator : '',
              labelStyle: 'font-color:white;',
          //    height: 15,
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
              height: 49,
              width: 355,
              //title: TRANSLATIONS.ID_GENERAL_PROCESS_NUMBERS,  // 'General Process Numbers',
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
        },/*{
          xtype:'textarea',
          fieldLabel: TRANSLATIONS.ID_CALENDAR_DESCRIPTION,  // 'Calendar Description',
          name: 'calendarDescription',
          value: '',
          labelStyle: 'font-weight:bold;',
          // disabled: true,
          readOnly: true,
          id:"calendarDescription"
      },*/{
        xtype:'checkboxgroup',
            fieldLabel: TRANSLATIONS.ID_WORKING_DAYS, // 'Working days',
            name: 'calendarWorkDays',
            disabled: true,
            readOnly: true,
            disabledClass:"",
            labelStyle: 'font-weight:bold',
            id:"calendarWorkDays",
              columns: 7,
                items: [
                    {boxLabel: TRANSLATIONS.ID_SUN, name: '0',disabledClass:""},
                    {boxLabel: TRANSLATIONS.ID_MON, name: '1',disabledClass:""},
                    {boxLabel: TRANSLATIONS.ID_TUE, name: '2',disabledClass:""},
                    {boxLabel: TRANSLATIONS.ID_WEN, name: '3',disabledClass:""},
                    {boxLabel: TRANSLATIONS.ID_THU, name: '4',disabledClass:""},
                    {boxLabel: TRANSLATIONS.ID_FRI, name: '5',disabledClass:""},
                    {boxLabel: TRANSLATIONS.ID_SAT, name: '6',disabledClass:""}
                ]
       }, {
        xtype:'checkbox',
          fieldLabel: TRANSLATIONS.ID_DEBUG_MODE, // 'Debug Mode',
          name: 'processDebug',
          labelStyle: 'font-weight:bold;',
          disabled: true,
          readOnly: true,
          id:"processDebug",
          disabledClass:""

    }]
  }
  

  Ext.QuickTips.init();
  
  var viewport = new Ext.Viewport({
    layout : 'border',
    items : [newCaseTree, details]
  });
  //PMExt.info('x', 'ddd');
	//viewport.doLayout();

  //routine to hide the debug panel if it is open
  if( typeof parent != 'undefined' ){
    if( parent.PANEL_EAST_OPEN ){
      parent.PANEL_EAST_OPEN = false;
      parent.Ext.getCmp('debugPanel').hide();
      parent.Ext.getCmp('debugPanel').ownerCt.doLayout();
    }
  }

});

function openCaseA(n){
	if (n.attributes.optionType == "startProcess") {
		Ext.Msg.show({
			title : '', //TRANSLATIONS.ID_TITLE_START_CASE, //'Start Case',
			msg : TRANSLATIONS.ID_STARTING_NEW_CASE  // 'Starting new case'
				  + '<br><br><b>' + n.attributes.text + '</b>',
			//icon : Ext.MessageBox.INFO,
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

    processNumbersData = [[
      otherAttributes.CASES_COUNT_TO_DO,
      otherAttributes.CASES_COUNT_DRAFT,
      otherAttributes.CASES_COUNT_COMPLETED,
      otherAttributes.CASES_COUNT_CANCELLED,
      otherAttributes.CASES_COUNT
    ]];
    processNumbers.loadData(processNumbersData);

	} else {
		//detailEl.update('');
	}

	return;
};


Ext.ux.MaskTree = Ext.extend(Ext.tree.TreePanel, {
    /**
     * @cfg {Boolean} mask Indicates if the tree panel should have a loadmask applied when loading nodes
     */
    mask: true,
    /**
     * @cfg {Object} maskConfig A configuration object that can be applied to the loadmask.
     */
    maskConfig: { msg: _('ID_LOADING') },

    //init
    initComponent:function() {
        // call parent
        Ext.ux.MaskTree.superclass.initComponent.apply(this, arguments);

        if (this.mask) { this.on('render', this.createMask, this); }
    }, //end initComponent

    /**
     * @private
     */
    createMask: function() {
        var mask = new Ext.LoadMask(Ext.getBody(), this.maskConfig);
        this.getLoader().on('beforeload', mask.show, mask);
        this.getLoader().on('load', mask.hide, mask);
    }
}); // end of extend

Ext.reg('masktree', Ext.ux.MaskTree);