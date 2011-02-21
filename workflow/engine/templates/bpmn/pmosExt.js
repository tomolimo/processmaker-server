pmosExt=function(id){
Workflow.call(this,id);
};
pmosExt.prototype=new Workflow;
pmosExt.prototype.type="pmosExt";

pmosExt.prototype.addExtJsWindow = function(items,width,height,title)
{
         var window = new Ext.Window({
         title: title,
         collapsible: false,
         maximizable: false,
         width: width,
         height: height,
         minWidth: 300,
         minHeight: 200,
         layout: 'fit',
         plain: true,
         bodyStyle: 'padding:5px;',
         buttonAlign: 'center',
         items: items,
         buttons: [{
             text: _('ID_SAVE'),
             handler: function(){
	                // when this button clicked, sumbit this form
                          items.getForm().submit({
	                    //waitMsg: 'Saving...',       // Wait Message
	                    success: function () {      // When saving data success
	                        //Ext.MessageBox.alert (response.responseText);
	                        // clear the form
	                        //simpleForm.getForm().reset();
	                    },
	                    failure: function () {      // when saving data failed
                                PMExt.notify( _('ID_STATUS') , _('ID_AUTHENTICATION_FAILED') );
	                    }
	                });
//                           var test = webForm.getForm().submit({url:'../cases/cases_SchedulerValidateUser.php', submitEmptyText: false});
                        }
             },{
             text: _('ID_CANCEL'),
             handler: function(){
	                // when this button clicked,
                            window.close();
                        }
             }]
         });
         window.show();
}
pmosExt.prototype.popWebEntry= function(_5678)
{
    var oTask           = workflow.taskUid;
    var oDyna           = workflow.dynaList;
    var webEntryDetails = workflow.webEntryList;
    var webEntry = '';
    if(typeof webEntryDetails != 'undefined'){ //Web Entry Present
        if(webEntryDetails.length > 0 && webEntryDetails[0].W_LINK != '') //Web Entry Present
            webEntry = webEntryDetails[0].W_LINK;
    }
    var webForm = new Ext.FormPanel({
    labelWidth: 120, // label settings here cascade unless overridden
    //url:'save-form.php',
    frame:true,
    title: '',
    scope:workflow,
    bodyStyle:'padding:5px 5px 0',
    width: 500,
    height: 400,
    defaultType: 'textfield',
    buttonAlign : 'center',
    items: [
           {
             xtype:'fieldset',
             title: _('ID_WEBENTRY_LINK'),
             collapsible: false,
             autoHeight:true,
             buttonAlign : 'center',
             defaults: {width: 210},
             //defaultType: 'textfield',
             items: [
                    {
                        html: webEntry,
                        xtype: "panel",
                        width:400
                    },{
                        xtype: 'fieldset',
                        layout:'column',
                        border:false,
                        align:'center',
                        //width: 700,
                        items:[{
                            columnWidth:.3,
                            layout: 'form',
                            border:false,
                            items: [{
                                    xtype: 'button',
                                    id: 'edit',
                                    text: _('ID_EDIT'),
                                    bodyStyle: 'padding-left:35px;',
                                   // width:50,
                                    handler: function(){
                                        var properties = workflow.webForm.items.items[1];
                                        var credential = workflow.webForm.items.items[2];
                                        properties.show();
                                        credential.show();
                                        workflow.webEntrywindow.buttons[0].enable();
                                        workflow.webEntrywindow.setHeight(450);
                                    }
                            }]
                        },{
                            columnWidth:.3,
                            layout: 'form',
                            border:false,
                            //bodyStyle: 'padding:35px;',
                            items: [{
                                xtype: 'button',
                                id: 'cancel',
                                //width:50,
                                text: _('ID_CANCEL'),
                                handler: function(){
                                    var properties = workflow.webForm.items.items[1];
                                    var credential = workflow.webForm.items.items[2];
                                    properties.hide();
                                    credential.hide();
                                    workflow.webEntrywindow.buttons[0].disable();
                                    workflow.webEntrywindow.setHeight(200);
                                }
                            }]
                        }]
                        }
                    /*{
                        xtype: 'button',
                        id: 'edit',
                        text: 'Edit',
                        width:50,
                        handler: function(){
                            var properties = _5678.workflow.webForm.items.items[1];
                            var credential = _5678.workflow.webForm.items.items[2];
                            properties.show();
                            credential.show();
                            _5678.workflow.webEntrywindow.buttons[0].enable();
                            _5678.workflow.webEntrywindow.setHeight(450);
                        }
                     },{
                        xtype: 'button',
                        id: 'cancel',
                        width:50,
                        text: 'Cancel',
                        handler: function(){
                            var properties = _5678.workflow.webForm.items.items[1];
                            var credential = _5678.workflow.webForm.items.items[2];
                            properties.hide();
                            credential.hide();
                            _5678.workflow.webEntrywindow.buttons[0].disable();
                            _5678.workflow.webEntrywindow.setHeight(200);
                        }*/
                    // }
                 ]
           },
           {
             xtype:'fieldset',
            title: _('ID_PROPERTIES'),
            collapsible: false,
            autoHeight:true,
            defaults: {width: 210},
            defaultType: 'textfield',
            items: [{
                width:          200,
                xtype:          'combo',
                mode:           'local',
                forceSelection: true,
                allowBlank:false,
                triggerAction:  'all',
                editable:       false,
                fieldLabel:     _('ID_INITIAL_TASK'),
                name:           'initTask',
                hiddenName:     'initTask',
                displayField:   'name',
                valueField:     'value',
                store:          new Ext.data.JsonStore({
                                    fields : ['name', 'value'],
                                    data   :oTask
                               })
            },{
                width:          200,
                xtype:          'combo',
                mode:           'local',
                forceSelection: true,
                allowBlank:false,
                triggerAction:  'all',
                editable:       false,
                fieldLabel:     _('ID_INITIAL_DYNAFORM'),
                name:           'initDyna',
                hiddenName:     'initDyna',
                displayField:   'name',
                valueField:     'value',
                store:          new Ext.data.JsonStore({
                                    fields : ['name', 'value'],
                                    data   :oDyna
                               })
            },{
               width:          200,
               xtype:          'combo',
               mode:           'local',
               forceSelection: true,
               allowBlank:false,
               triggerAction:  'all',
               editable:       false,
               fieldLabel:     _('ID_METHODS'),
               name:           'methods',
               hiddenName:     'methods',
               displayField:   'name',
               valueField:     'value',
               scope:workflow,
               store:          new Ext.data.JsonStore({
               fields : ['name', 'value'],
               data   : [
                            {name : 'PHP Pages with Web Services',   value: 'WS'},
                            {name : 'Single HTML',   value: 'SINGLE'},
                        ]
                       }),
               onSelect: function(record, index){
                   var fields = workflow.webForm.items.items;

                   if(index == 1)
                   { //Select
                        fields[2].collapse();
                        workflow.webEntrywindow.buttons[0].disable();
                        workflow.webEntrywindow.buttons[1].enable();
                   }
                   else
                   {
                        fields[2].expand();
                        workflow.webEntrywindow.buttons[0].enable();
                        workflow.webEntrywindow.buttons[1].disable();
                   }
                   this.setValue(record.data[this.valueField || this.displayField]);
                   this.collapse();
               }
            }, {
               width:          200,
               xtype:          'combo',
               mode:           'local',
               forceSelection: true,
               triggerAction:  'all',
               editable:       false,
               allowBlank:false,
               fieldLabel:     _('ID_INPUT_DOC_ACCESS'),
               name:           'inputDocAccess',
               hiddenName:     'inputDocAccess',
               displayField:   'name',
               valueField:     'value',
               store:          new Ext.data.JsonStore({
                            fields : ['name', 'value'],
                            data   : [
                                        {name : 'No Restriction',   value: '1'},
                                        {name : 'Restricted to Process Permission',   value: '2'},
                                     ]
                                })
            }]
           },{
             xtype:'fieldset',
             title: _('ID_PHP_WEB_SERVICE'),
             collapsible: false,
             autoHeight:true,
             defaults: {width: 210},
             defaultType: 'textfield',
             items: [{
                       fieldLabel: _('ID_WEB_SERVICE_USER'),
                       name: 'webserviceUser',
                       allowBlank:true
                     },{
                       fieldLabel: _('ID_WEB_SERVICE_PASSWORD'),
                       name: 'webservicePassword',
                       allowBlank:true,
                       inputType:'password'
                     }]
             }]

            });

            webForm.render(document.body);
            workflow.webForm = webForm;
            var fields = webForm.items.items;
            var oPmosExt = new pmosExt();
            //oPmosExt.addExtJsWindow(webForm,600,500,'Add New webentry');

             var webEntrywindow = new Ext.Window({
             title: _('ID_START_MESSAGE_EVENT_WEB_ENTRY'),
             collapsible: false,
             maximizable: false,
             width: 450,
             height: 350,
             minWidth: 300,
             minHeight: 200,
             layout: 'fit',
             plain: true,
             bodyStyle: 'padding:5px;',
             buttonAlign: 'center',
             items: webForm,
             scope:workflow,
             buttons: [{
                 text: _('ID_TEST_CONFIGURATION'),
                 handler: function(){
                            var propertiesfields = workflow.webForm.items.items[1].items.items;
                            var credentialFields = workflow.webForm.items.items[2].items.items;
                            var task_uid         = propertiesfields[0].getValue();
                            var dyna_uid         = propertiesfields[1].getValue();
                            var we_type          = propertiesfields[2].getValue();
                            var we_usr           = propertiesfields[3].getValue();
                            var tasName          = 'test';
                            var username         = credentialFields[0].getValue();
                            var password         = credentialFields[1].getValue();
                            var args  = '?action=webEntry_validate&data={"PRO_UID":"'+pro_uid +'", "TASKS":"'+task_uid+'", "DYNAFORM":"'+dyna_uid+'", "WE_TYPE":"'+we_type+'", "WS_USER":"'+username+'", "WS_PASS":"'+password+'", "WS_ROUNDROBIN":"", "WE_USR":"'+we_usr+'", "TASKS_NAME":"'+tasName+'"}';
                            Ext.Ajax.request({
                            url: 'processes_Ajax.php'+ args,
                            success: function(response) {
                                if(response.responseText == "1")
                                    workflow.webEntrywindow.buttons[1].enable();
                                else
                                    {
                                        Ext.Msg.alert (response.responseText);
                                        workflow.webEntrywindow.buttons[1].disable();
                                    }
                             },
                            failure: function(){
                                PMExt.notify( _('ID_STATUS') , _('ID_FAILURE') );
                                }
                        });
                        }
                 },{
                 text: _('ID_GENERATE_WEB_ENTRY_PAGE'),
                 disabled:true,
                 handler: function(){
                            var webEntryLink     = workflow.webForm.items.items[0].items.items;
                            var propertiesfields = workflow.webForm.items.items[1].items.items;
                            var credentialFields = workflow.webForm.items.items[2].items.items;
                            var evn_uid          = workflow.currentSelection.id;
                            var task_uid         = propertiesfields[0].getValue();
                            var dyna_uid         = propertiesfields[1].getValue();
                            var we_type          = propertiesfields[2].getValue();
                            var we_usr           = propertiesfields[3].getValue();
                            var username         = credentialFields[0].getValue();
                            var password         = credentialFields[1].getValue();
                            var args  = '?action=webEntry_generate&data={"PRO_UID":"'+pro_uid +'", "TASKS":"'+task_uid+'", "DYNAFORM":"'+dyna_uid+'", "WE_TYPE":"'+we_type+'", "WS_USER":"'+username+'", "WS_PASS":"'+password+'", "WS_ROUNDROBIN":"", "WE_USR":"'+we_usr+'", "WE_EVN_UID":"'+evn_uid+'"}';
                            Ext.Ajax.request({
                            url: 'processes_Ajax.php'+ args,
                            success: function(response) {
                                 webEntryLink[0].initialConfig.html = response.responseText;
                               // webEntryLink[0].setValue(response.responseText);
                                workflow.webForm.items.items[0].show();
                                workflow.webForm.items.items[1].hide();
                                workflow.webForm.items.items[2].hide();
                                workflow.webEntrywindow.buttons[1].disable();
                                Ext.Msg.alert(response.responseText);
                             },
                            failure: function(){
                                PMExt.notify( _('ID_STATUS') , _('ID_FAILURE') );
                            }
                        });
                        }
                 }]
             });
             workflow.webEntrywindow = webEntrywindow;
             webEntrywindow.show();
             var webEntryDetails    = workflow.webEntryList;
             var webEntryLink       = workflow.webForm.items.items[0];
             var propertiesfields   = workflow.webForm.items.items[1];
             var credentialFields   = workflow.webForm.items.items[2];
             if(typeof webEntryDetails != 'undefined')
             if(webEntryDetails.length > 0 && webEntryDetails[0].W_LINK != ''){ //Web Entry Present
                  // webEntryLink.items.items[0].setValue(webEntryDetails[0].W_LINK);
                   workflow.webEntrywindow.setHeight(200);
                   webEntryLink.show();
                   propertiesfields.hide();
                   credentialFields.hide();
             }
             else
                 {
                  propertiesfields.show();
                  credentialFields.show();
                  webEntryLink.hide();
                 }
}
pmosExt.prototype.popCaseSchedular= function(_5678){
        Ext.QuickTips.init();
        var oPmosExt = new pmosExt();
         //Get the Task Data
        var oTask = workflow.taskUid;
        if(typeof oTask != 'undefined')
            {
                taskName = oTask[0].name;
                task_uid = oTask[0].value;
            }
        var caseSchedularForm = new Ext.FormPanel({
        labelWidth: 120, // label settings here cascade unless overridden
        url:'cases_Scheduler_Save.php',
        frame:true,
        title: _('ID_GENERATE_INFO'),
        bodyStyle:'padding:5px 5px 0',
        width: 500,
        height: 400,
        buttonAlign : 'center',
        defaultType: 'textfield',
        items: [{
                xtype:'fieldset',
                title: 'ID_PROCESSMAKER_VALIDATION',
                collapsible: false,
                autoHeight:true,
                buttonAlign : 'center',
                defaults: {width: 210},
                defaultType: 'textfield',
                items: [
                        {
                            fieldLabel: _('ID_USERNAME'),
                            name: 'SCH_DEL_USER_NAME',
                            allowBlank:false,
                            blankText:'Enter username'
                        },{
                            fieldLabel: _('ID_CACHE_PASSWORD'),
                            inputType:'password',
                            name: 'SCH_USER_PASSWORD',
                            allowBlank:false,
                            blankText:'Enter Password'
                        },{
                            xtype: 'button',
                            id: 'testUser',
                            width:75,
                            text: _('ID_TEST_USER'),
                            arrowAlign: 'center',
                            scope:workflow,
                            align:'center',
                            margins:'5 5 5 5',
                            handler: function(){
                            var credentialFieldset  = workflow.caseSchedularForm.items.items[0];
                            var propertiesFieldset  = workflow.caseSchedularForm.items.items[1];
                            var timeFieldset        = workflow.caseSchedularForm.items.items[2];
                            var username            = credentialFieldset.items.items[0].getValue();
                            var password            = credentialFieldset.items.items[1].getValue();
                            if(username == '' || password == ''){
                                PMExt.notify( _('ID_ERROR') , _('ID_VALID_CREDENTIALS') );
                                }
                            else
                            {
                                Ext.Ajax.request({
                                url: '../cases/cases_SchedulerValidateUser.php?USERNAME=' + username+'&PASSWORD='+password,
                                    success: function(response) {
                                        var result = Ext.util.JSON.decode(response.responseText);
                                        if(result.status == 0)
                                        {
                                          credentialFieldset.items.items[4].setValue(response.responseText);
                                          propertiesFieldset.show();
                                          timeFieldset.show();
                                          timeFieldset.collapse();
                                          credentialFieldset.items.items[2].hide(); //Hide Test User
                                          credentialFieldset.items.items[3].show(); //Show Edit User
                                        }
                                        Ext.Msg.alert(result.message);
                                 },
                                    failure: function(){
                                        PMExt.notify( _('ID_STATUS') , _('ID_LOAD_FAILED') );
                                    }
                                });
                           }
                            }
                        },{
                            xtype: 'button',
                            id: 'editUser',
                            width:75,
                            text: _('ID_EDIT_USER'),
                            arrowAlign: 'center',
                            scope:_5678,
                            align:'center',
                            hidden:true,
                            margins:'5 5 5 5',
                            handler: function(){
                            var credentialFieldset = workflow.caseSchedularForm.items.items[0];
                            var propertiesFieldset = workflow.caseSchedularForm.items.items[1];
                            var timeFieldset       = workflow.caseSchedularForm.items.items[2];
                            propertiesFieldset.hide();
                            timeFieldset.hide();
                            credentialFieldset.items.items[3].hide(); //Hide Edit User
                            credentialFieldset.items.items[2].show(); //Show Test User
                            }
                        },{
                            name: 'SCH_DEL_USER_UID',
                            hidden:true
                        },{
                            name: 'PRO_UID',
                            hidden:true
                        },{
                            name: 'SCH_DAYS_PERFORM_TASK',
                            hidden:true,
                            value:1
                        },{
                            name: 'TAS_UID',
                            hidden:true,
                            value:1
                        },{
                            name: 'SCH_WEEK_DAYS',
                            hidden:true
                        },{
                            name: 'SCH_MONTHS',
                            hidden:true
                        },{
                            name: 'EVN_UID',
                            hidden:true
                        },{
                            name: 'SCH_UID',
                            hidden:true
                        }]
                },{
                    xtype:'fieldset',
                    title: _('ID_PROPERTIES'),
                    collapsible: false,
                    autoHeight:true,
                    buttonAlign : 'center',
                    defaults: {width: 210},
                    defaultType: 'textfield',
                    items: [
                        {
                           fieldLabel: _('ID_TASK'),
                           name: 'TAS_NAME',
                           value: taskName,
                           readOnly:true,
                           allowBlank:false
                        }
                        /*{
                            width:          160,
                            xtype:          'combo',
                            mode:           'local',
                            forceSelection: true,
                            triggerAction:  'all',
                            editable:       false,
                            allowBlank:false,
                            fieldLabel:     'Task',
                            name:           'TAS_UID',
                            hiddenName:     'task',
                            displayField:   'name',
                            valueField:     'value',
                            store:          new Ext.data.JsonStore({
                                        fields : ['name', 'value'],
                                        data   :oTask
                                    })
                        }*/,{
                            fieldLabel: _('ID_DESCRIPTION'),
                            allowBlank:false,
                            name: 'SCH_NAME'
                            //allowBlank:false
                        },{
                            width:          120,
                            xtype:          'combo',
                            mode:           'local',
                            triggerAction:  'all',
                            forceSelection: true,
                            allowBlank:false,
                            value: '--select--',
                            editable:       false,
                            fieldLabel:     _('ID_PERFORM_TASK'),
                            name:           'SCH_OPTION',
                            displayField:   'name',
                            valueField:     'value',
                            scope:_5678,
                            store:          new Ext.data.JsonStore({
                                        fields : ['name', 'value'],
                                        data   :[
                                            {name : '--select--',   value: '0',selected: true},
                                            {name : 'Daily',   value: '1'},
                                            {name : 'Weekly',  value: '2'},
                                            {name : 'Monthly', value: '3'},
                                            {name : 'One time only', value: '4'}
                                        ]
                                    }),
                            onSelect: function(record, index){
                                    oPmosExt.hideSchOptions(caseSchedularForm,index);
                                    this.setValue(record.data[this.valueField || this.displayField]);
                                    this.collapse();
                                }
                        }
              ]},{
                    xtype:'fieldset',
                    title: _('ID_SELECT_DATE_TIME'),
                    collapsible: false,
                    autoHeight:true,
                    buttonAlign : 'center',
                    defaults: {width: 210},
                    defaultType: 'textfield',
                    items: [
                        {
                            xtype: 'datefield',
                            name:'SCH_START_DATE',
                            format: 'Y-m-d',
                            fieldLabel: _('ID_START_DATE')
                        },{
                            xtype: 'datefield',
                            name:'SCH_END_DATE',
                            format: 'Y-m-d',
                            fieldLabel: _('ID_END_DATE')
                        },{
                            fieldLabel: _('ID_EXECUTION_TIME'),
                            name: 'SCH_START_TIME'
                            //allowBlank:false
                        },{
                            xtype: 'checkboxgroup',
                            fieldLabel: _('ID_SELECT_DAY_OF_WEEK'),
                            name:'SCH_WEEK_DAY',
                            hidden: true,
                            // Put all controls in a single column with width 100%
                            columns: 2,
                            items: [
                                    {boxLabel: 'Monday', name: '1', checked: true},
                                    {boxLabel: 'Tuesday', name: '2'},
                                    {boxLabel: 'Wednesday', name: '3'},
                                    {boxLabel: 'Thursday', name: '4'},
                                    {boxLabel: 'Friday', name: '5'},
                                    {boxLabel: 'Saturday', name: '6'},
                                    {boxLabel: 'Sunday', name: '7'}
                            ]
                        },{
                            width:          100,
                            labelWidth:     0,
                            xtype:          'combo',
                            mode:           'local',
                            triggerAction:  'all',
                            forceSelection: true,
                            hidden:         true,
                            editable:       false,
                            scope:_5678,
                            name:           'SCH_START_DAY',
                            displayField:   'name',
                            valueField:     'value',
                            store:          new Ext.data.JsonStore({
                                        fields : ['name', 'value'],
                                        data   : [
                                            {name : 'Day of Month',   value: '1'},
                                            {name : 'The Day',   value: '2'},
                                        ]
                                    }),
                         onSelect: function(record, index){
                              var timefieldset = workflow.caseSchedularForm.items.items[2];
                              var fields = timefieldset.items.items;
                                    var fieldsToToggle = new Array();
                                    if(index == 0){ //Select
                                       fieldsToToggle = [fields[5],fields[6]];
                                       oPmosExt.toggleFields(fieldsToToggle,false);

                                       fieldsToToggle = [fields[7]];
                                       oPmosExt.toggleFields(fieldsToToggle,true);
                                    }
                                    else
                                    {
                                       fieldsToToggle = [fields[5],fields[6]];
                                       oPmosExt.toggleFields(fieldsToToggle,true);

                                       fieldsToToggle = [fields[7]];
                                       oPmosExt.toggleFields(fieldsToToggle,false);
                                    }
                                   this.setValue(record.data[this.valueField || this.displayField]);
                                   this.collapse();
                         }
                        },{
                            width:          100,
                            labelWidth:     0,
                            xtype:          'combo',
                            mode:           'local',
                            triggerAction:  'all',
                            forceSelection: true,
                            hidden:         true,
                            editable:       false,
                            name:           'SCH_START_DAY_OPT_2_WEEKS',
                            displayField:   'name',
                            valueField:     'value',
                            store:          new Ext.data.JsonStore({
                                        fields : ['name', 'value'],
                                        data   : [
                                            {name : 'First',   value: '1'},
                                            {name : 'Second',   value: '2'},
                                            {name : 'Third',   value: '3'},
                                            {name : 'Fourth',   value: '4'},
                                            {name : 'Last',   value: '5'},
                                        ]
                                    })
                        },{
                            width:          100,
                            labelWidth:     0,
                            xtype:          'combo',
                            mode:           'local',
                            triggerAction:  'all',
                            forceSelection: true,
                            hidden:         true,
                            editable:       false,
                            name:           'SCH_START_DAY_OPT_2_DAYS_WEEK',
                            displayField:   'name',
                            valueField:     'value',
                            store:          new Ext.data.JsonStore({
                                        fields : ['name', 'value'],
                                        data   : [
                                            {name : 'Monday',   value: '1'},
                                            {name : 'Tuesday',   value: '2'},
                                            {name : 'Wednesday',   value: '3'},
                                            {name : 'Thursday',   value: '4'},
                                            {name : 'Friday',   value: '5'},
                                            {name : 'Saturday',   value: '6'},
                                            {name : 'Sunday',   value: '7'},
                                        ]
                                    })
                        },{
                            name: 'SCH_START_DAY_OPT_1',
                            hidden:         true,
                            value:1
                        },{
                            xtype: 'checkboxgroup',
                            fieldLabel: _('ID_OF_THE_MONTH'),
                            name:'SCH_MONTH',
                            hidden:true,
                            // Put all controls in a single column with width 100%
                            columns: 3,
                            items: [
                                    {boxLabel : 'Jan',   name: '1'},
                                    {boxLabel : 'Feb',   name: '2'},
                                    {boxLabel : 'Mar',   name: '3'},
                                    {boxLabel : 'Apr',   name: '4'},
                                    {boxLabel : 'May',   name: '5'},
                                    {boxLabel : 'Jun',   name: '6'},
                                    {boxLabel : 'Jul',   name: '7'},
                                    {boxLabel : 'Aug',   name: '8'},
                                    {boxLabel : 'Sep',   name: '9'},
                                    {boxLabel : 'Oct',   name: '10'},
                                    {boxLabel : 'Nov',   name: '11'},
                                    {boxLabel : 'Dec',   name: '12'},
                            ]
                        }]}
        ]
    });
          caseSchedularForm.render(document.body);

          var credentialFieldset = caseSchedularForm.items.items[0];
          var propertiesFieldset = caseSchedularForm.items.items[1];
          var timeFieldset = caseSchedularForm.items.items[2];

          var evn_uid = workflow.currentSelection.id;
          //Loading Details into the form
          caseSchedularForm.form.load({
                url:'proxyCaseSchLoad?eid='+evn_uid,
                method:'GET',
                waitMsg:'Loading',
                success:function(form,action) {
                  propertiesFieldset.show();
                  timeFieldset.show();
                  timeFieldset.collapse();
                  credentialFieldset.items.items[2].hide(); //Hide Test User
                  credentialFieldset.items.items[3].show(); //Show Edit User

                  var index = propertiesFieldset.items.items[2].value;
                  timeFieldset.expand();
                  var sch_month = credentialFieldset.items.items[9].getValue();
                  var aSchMonth = new Array();
                  var sSchMonth = '';
                  aSchMonth = sch_month.substr(0,sch_month.length-1).split("|");
                  for(var i=0;i<aSchMonth.length;i++)
                      {
                          var index1 = aSchMonth[i];
                          timeFieldset.items.items[8].items.items[index1].checked = true;
                      }
//                  timeFieldset.items.items[8].setValue(sSchMonth);
                  oPmosExt.hideSchOptions(caseSchedularForm,index);
                },
                failure:function(form, action) {
                  //  Ext.MessageBox.alert('Message', 'Load failed');
                }
            });

            workflow.caseSchedularForm = caseSchedularForm;
            
            //hide Usr_uid and pro_uid labels field
            credentialFieldset.items.items[4].getEl().up('.x-form-item').setDisplayed(false);
            credentialFieldset.items.items[5].getEl().up('.x-form-item').setDisplayed(false);
            credentialFieldset.items.items[6].getEl().up('.x-form-item').setDisplayed(false);
            credentialFieldset.items.items[7].getEl().up('.x-form-item').setDisplayed(false);
            credentialFieldset.items.items[8].getEl().up('.x-form-item').setDisplayed(false);
            credentialFieldset.items.items[9].getEl().up('.x-form-item').setDisplayed(false);
            credentialFieldset.items.items[10].getEl().up('.x-form-item').setDisplayed(false);
            credentialFieldset.items.items[11].getEl().up('.x-form-item').setDisplayed(false);
            //Set pro_uid field
            credentialFieldset.items.items[5].setValue(pro_uid);
            //Set pro_uid field
            credentialFieldset.items.items[7].setValue(task_uid);
            //Set Event UID
            credentialFieldset.items.items[10].setValue(workflow.currentSelection.id);

            propertiesFieldset.hide();
            timeFieldset.hide();
           // oPmosExt.addExtJsWindow(caseSchedularForm,600,550,'Add New Case scheduler');
           var window = new Ext.Window({
             title: _('ID_START_TIME_EVENT'),
             collapsible: false,
             maximizable: false,
             width: 600,
             height: 640,
             minWidth: 300,
             minHeight: 200,
             layout: 'fit',
             plain: true,
             bodyStyle: 'padding:5px;',
             buttonAlign: 'center',
             items: caseSchedularForm,
             buttons: [{
                 text: _('ID_SAVE'),
                 handler: function(){
                              //Set SCH_WEEK_DAYS field
                              var sch_week_days = timeFieldset.items.items[3].getValue();

                              var sch_week_day = new Array();
                              for(i=0;i< sch_week_days.length;i++)
                                  {
                                      sch_week_day[i] = sch_week_days[i].name;
                                  }
                              credentialFieldset.items.items[8].setValue(sch_week_day);

                              var sch_months = timeFieldset.items.items[8].getValue();
                              var sch_month = new Array();
                              for(i=0;i< sch_months.length;i++)
                                  {
                                      sch_month[i] = sch_months[i].name;
                                  }
                              credentialFieldset.items.items[9].setValue(sch_month);

                              caseSchedularForm.getForm().submit({
                               waitMsg: 'Saving...',        // Wait Message
                                success: function () {      // When saving data success
                                    PMExt.notify( _('ID_STATUS') , _('ID_CASE_SCHEDULER_SAVED') );
                                    },
                                failure: function () {      // when saving data failed
                                    PMExt.notify( _('ID_STATUS') , _('ID_AUTHENTICATION_FAILED') );
                                    }
                            });
                            }
                 },{
                 text: _('ID_CANCEL'),
                 handler: function(){
                            // when this button clicked,
                                window.close();
                            }
                 }]
             });
             window.show();


      }
pmosExt.prototype.hideSchOptions = function(formObj,index)
{
    var credentialFieldset = formObj.items.items[0];
    var propertiesFieldset = formObj.items.items[1];
    var timeFieldset       = formObj.items.items[2];

    var fields = timeFieldset.items.items;
    var fieldsToToggle = new Array();
    if(index == 0){ //Select
       fieldsToToggle = [fields[0],fields[1],fields[2],fields[3],fields[4],fields[5],fields[6],fields[7],fields[8]];
       this.toggleFields(fieldsToToggle,false);
    }
    if(index == 1){ //Daily
        fieldsToToggle = [fields[0],fields[1],fields[2]];
        this.toggleFields(fieldsToToggle,true);

        fieldsToToggle = [fields[3],fields[4],fields[5],fields[6],fields[7],fields[8]];
        this.toggleFields(fieldsToToggle,false);
    }
    if(index == 2){//Weekly
        fieldsToToggle = [fields[0],fields[1],fields[2],fields[3]];
        this.toggleFields(fieldsToToggle,true);

        fieldsToToggle = [fields[4],fields[5],fields[6],fields[7],fields[8]];
        this.toggleFields(fieldsToToggle,false);
    }
    if(index == 3){//Monthly
        fieldsToToggle = [fields[0],fields[1],fields[2],fields[4],fields[8]];
        this.toggleFields(fieldsToToggle,true);

        fieldsToToggle = [fields[3],fields[5],fields[6],fields[7]];
        this.toggleFields(fieldsToToggle,false);

        var sSchValue = fields[3].getValue();
        if(sSchValue[0].name == '1'){
            fieldsToToggle = [fields[5],fields[6]];
            this.toggleFields(fieldsToToggle,true);

            fieldsToToggle = [fields[7]];
            this.toggleFields(fieldsToToggle,false);
        }
        else
            {
                fieldsToToggle = [fields[4],fields[5]];
                this.toggleFields(fieldsToToggle,false);

                fieldsToToggle = [fields[6]];
                this.toggleFields(fieldsToToggle,true);
            }

        //if(sSchValue == 3)
    }
    if(index == 4){//One-Time Only
        fieldsToToggle = [fields[0],fields[2]];
        this.toggleFields(fieldsToToggle,true);

        fieldsToToggle = [fields[1],fields[3],fields[4],fields[5],fields[6],fields[7],fields[8]];
        this.toggleFields(fieldsToToggle,false);
    }

}
pmosExt.prototype.popTaskNotification= function(_5678){
        var oPmosExt = new pmosExt();
         //Get the Task Data
        var oTask = workflow.taskUid;
        var oTaskData = workflow.taskDetails;
        var toggle = true;
        var message = '';
        if(typeof oTaskData != 'undefined')
            {
                if(oTaskData.TAS_SEND_LAST_EMAIL == 'TRUE')
                    {
                        toggle = false;
                        message = oTaskData.TAS_DEF_MESSAGE;
                    }
                
            }
        var taskNotificationForm = new Ext.FormPanel({
        labelWidth: 120, // label settings here cascade unless overridden
        frame:true,
        bodyStyle:'padding:5px 5px 0',
        buttonAlign : 'center',
        defaultType: 'textfield',
        
        items: [{
            xtype:'fieldset',
            checkboxToggle:true,
            title: _('ID_AFTER_ROUTING_NOTIFY'),
            autoHeight:true,
            labelWidth: 5,
            defaults: {width: 400},
            defaultType: 'textfield',
            collapsed: toggle,
            items :[{
                    xtype: 'textarea',
                    name: 'description',
                    value:message,
                    height : 250
                }
            ]
        }]
        });
         var window = new Ext.Window({
         title: _('ID_INTERMEDIATE_MESSAGE_EVENTS'),
         collapsible: false,
         maximizable: false,
         width: 500,
         height: 400,
         minWidth: 300,
         minHeight: 200,
         layout: 'fit',
         plain: true,
         bodyStyle: 'padding:5px;',
         buttonAlign: 'center',
         items: taskNotificationForm,
         buttons: [{
             text: _('ID_SAVE'),
             handler: function(){
                            //waitMsg: 'Saving...',       // Wait Message
                                var fields      = taskNotificationForm.items.items;
                                var tas_send    = fields[0].collapsed;
                                if(tas_send == true)
                                    tas_send = false;
                                else
                                    tas_send = true;
                                var data = fields[0].items.items[0].getValue();
                                var taskUid = workflow.taskUid;
                                if(typeof taskUid[0] != 'undefined')
                                {
                                 var urlparams = '?action=saveInterMessageEvent&data={"uid":"'+ taskUid[0].value +'","tas_send":"'+tas_send+'","data":"'+data+'"}';
                                    Ext.Ajax.request({
                                            url: "processes_Ajax.php"+ urlparams,
                                            success: function(response) {
                                                window.close();
                                            },
                                            failure: function(){
                                                Ext.Msg.alert ('Failure');
                                            }
                                        });
                                }
                        }
             },{
             text: _('ID_CANCEL'),
             handler: function(){
                            window.close();
                        }
             }]
         });
         window.show();
}
pmosExt.prototype.loadTask = function(_5678){
        var taskUid = workflow.taskid;
        if(typeof taskUid != 'undefined')
        {
            var urlparams = '?action=loadTask&data={"uid":"'+ taskUid.value +'"}';
            Ext.Ajax.request({
                    url: "processes_Ajax.php"+ urlparams,
                    success: function(response) {
                        workflow.taskDetails = Ext.util.JSON.decode(response.responseText);
                    },
                    failure: function(){
                       PMExt.notify( _('ID_STATUS') , _('ID_FAILURE') );
                    }
                });
        }

}

pmosExt.prototype.getTriggerList = function(_5678){
        if(typeof pro_uid != 'undefined')
        {
            var urlparams = '?action=triggersList&data={"pro_uid":"'+ pro_uid +'"}';
            Ext.Ajax.request({
                    url: "processes_Ajax.php"+ urlparams,
                    success: function(response) {
                        workflow.triggerList = Ext.util.JSON.decode(response.responseText);
                    },
                    failure: function(){
                        PMExt.notify( _('ID_STATUS') , _('ID_FAILURE') );
                    }
                });
        }

}
pmosExt.prototype.toggleFields = function(field,bool){

    for(var i=0;i<field.length;i++){
        if(typeof field[i] != 'undefined'){
            if(bool)
                field[i].show();
            else
                field[i].hide();

            //Hide-Show label
            //field[i].getEl().up('.x-form-item').setDisplayed(bool);
        }
    }
     
}
pmosExt.prototype.popMessageEvent= function(_5678){
         var oTask = workflow.taskUid;
         var oPmosExt = new pmosExt();
         var messageEvent = new Ext.FormPanel({
         labelWidth: 150, // label settings here cascade unless overridden
         frame:true,
         bodyStyle:'padding:5px 5px 0',
         width: 500,
         height: 400,
         defaultType: 'textfield',
         items: [
                 {
                    xtype:'fieldset',
                    title: _('ID_EVENT_MESSAGE'),
                    collapsible: false,
                    autoHeight:true,
                    buttonAlign : 'center',
                    defaults: {width: 210},
                    defaultType: 'textfield',
                    items:[
                             {
                                width     : 200,
                                fieldLabel: _('ID_DESCRIPTION'),
                                name      : 'description',
                                allowBlank: false
                             },{
                                width:          100,
                                xtype:          'combo',
                                mode:           'local',
                                triggerAction:  'all',
                                forceSelection: true,
                                editable:       false,
                                fieldLabel:     _('ID_STATUS'),
                                name:           'status',
                                displayField:   'name',
                                valueField:     'value',
                                store:          new Ext.data.JsonStore({
                                            fields : ['name', 'value'],
                                            data   : [
                                                {name : 'Active',   value: 'Active'},
                                                {name : 'InActive',   value: 'InActive'},
                                            ]
                                        })
                            }
                        ]
              },{
                xtype:'fieldset',
                title: _('ID_BEHAVIOUR'),
                collapsible: false,
                autoHeight:true,
                buttonAlign : 'center',
                defaults: {width: 210},
                defaultType: 'textfield',
                items:[{
                        width:          100,
                        xtype:          'combo',
                        mode:           'local',
                        triggerAction:  'all',
                        forceSelection: true,
                        editable:       false,
                        fieldLabel:     _('ID_TYPE'),
                        name:           'type',
                        displayField:   'name',
                        valueField:     'value',
                        store:          new Ext.data.JsonStore({
                                    fields : ['name', 'value'],
                                    data   : [
                                        {name : 'Single Task',   value: 'Single Task'},
                                        {name : 'Multiple Task',   value: 'Multiple Task'},
                                    ]
                                })
                      },{
                        width:          150,
                        xtype:          'combo',
                        mode:           'local',
                        triggerAction:  'all',
                        forceSelection: true,
                        editable:       false,
                        fieldLabel:     _('ID_TIME_START_WITH_TASK'),
                        name:           'type',
                        displayField:   'name',
                        valueField:     'value',
                        store:          new Ext.data.JsonStore({
                                    fields : ['name', 'value'],
                                    data   :oTask
                               })
                    },{
                        fieldLabel: _('ID_ESTIMATED_TASK_DURATION'),
                        width:          50,
                        name: 'estimatedTask',
                        allowBlank:false
                    },{
                        fieldLabel: _('ID_EXECUTION_TIME'),
                        width:          50,
                        name: 'executionTime',
                        allowBlank:false
                    },{
                        width:          150,
                        xtype:          'combo',
                        mode:           'local',
                        triggerAction:  'all',
                        forceSelection: true,
                        editable:       false,
                        fieldLabel:     _('ID_EXECUTION_TIME_INTERVAL'),
                        name:           'executionTimeInterval',
                        displayField:   'name',
                        valueField:     'value',
                        store:          new Ext.data.JsonStore({
                                    fields : ['name', 'value'],
                                    data   : [
                                        {name : 'After Interval Ends',   value: 'After Interval Ends'},
                                        {name : 'After Interval Starts',   value: 'After Interval Starts'},
                                    ]
                                })
                    }]
        }]

    });
    messageEvent.render(document.body);
    workflow.messageEventForm = messageEvent;

     var window = new Ext.Window({
     title: _('ID_END_MESSAGE_EVENT'),
     collapsible: false,
     maximizable: false,
     width: 500,
     height: 400,
     minWidth: 300,
     minHeight: 200,
     layout: 'fit',
     plain: true,
     bodyStyle: 'padding:5px;',
     buttonAlign: 'center',
     items: messageEvent,
     buttons: [{
         text: _('ID_CONTINUE'),
         handler: function(){
                        //waitMsg: 'Saving...',       // Wait Message
                            var fields   = messageEvent.items.items;
                            var tas_send = fields[0].collapsed;
                            if(tas_send == true)
                                tas_send = false;
                            else
                                tas_send = true;
                            var data = fields[0].items.items[0].getValue();
                            var taskUid = workflow.taskUid;
                            if(typeof taskUid[0] != 'undefined')
                            {
                             var urlparams = '?action=saveInterMessageEvent&data={"uid":"'+ taskUid[0].value +'","tas_send":"'+tas_send+'","data":"'+data+'"}';
                                Ext.Ajax.request({
                                        url: "processes_Ajax.php"+ urlparams,
                                        success: function(response) {
                                            window.close();
                                        },
                                        failure: function(){
                                            PMExt.notify( _('ID_STATUS') , _('ID_FAILURE') );
                                        }
                                    });
                            }
                    }
         },{
         text: _('ID_CANCEL'),
         handler: function(){
                        window.close();
                    }
         }]
     });
     window.show();
}
pmosExt.prototype.popMultipleEvent= function(_5678){
        Ext.QuickTips.init();
        var oTaskFrom = workflow.taskUidFrom;
        var oTaskTo = workflow.taskUidTo;
        var oTriggers = workflow.triggerList;
        var oPmosExt = new pmosExt();
        var multipleEvent = new Ext.FormPanel({
        url:'eventsSave.php',
        labelWidth: 150, // label settings here cascade unless overridden
        frame:true,
        bodyStyle:'padding:5px 5px 0',
        width: 500,
        height: 400,
        scope:_5678,
        defaultType: 'textfield',
        items: [
            {
            xtype:'fieldset',
            title: _('ID_EVENT_MULTIPLE'),
            collapsible: false,
            autoHeight:true,
            buttonAlign : 'center',
            defaults: {width: 210},
            defaultType: 'textfield',
            items:[
                    {
                        fieldLabel: '_(ID_DESCRIPTION)',
                        blankText:'Enter Event Description',
                        name: 'EVN_DESCRIPTION',
                        allowBlank:false
                    },{
                        width:          100,
                        xtype:          'combo',
                        mode:           'local',
                        triggerAction:  'all',
                        allowBlank:false,
                        editable:       false,
                        fieldLabel:     _('ID_STATUS'),
                        name:           'EVN_STATUS',
                        displayField:   'name',
                        valueField:     'value',
                        store:          new Ext.data.JsonStore({
                                    fields : ['name', 'value'],
                                    data   : [
                                        {name : 'Active',   value: 'ACTIVE'},
                                        {name : 'InActive',   value: 'INACTIVE'},
                                    ]
                                })
                    },{
                        fieldLabel: 'EVN_UID',
                        name: 'EVN_UID',
                        hidden:true
                    },{
                        fieldLabel: 'PRO_UID',
                        name: 'PRO_UID',
                        hidden:true
                    },{
                        fieldLabel: 'EVN_TYPE',
                        name: 'EVN_TYPE',
                        hidden:true
                    }
                ]
            },{
                xtype:'fieldset',
                title: _('ID_BEHAVIOUR'),
                collapsible: false,
                autoHeight:true,
                buttonAlign : 'center',
                defaults: {width: 210},
                defaultType: 'textfield',
                items:[
                      /* {
                            width        :  100,
                            xtype        :  'combo',
                            mode         :  'local',
                            triggerAction:  'all',
                            allowBlank   :  false,
                            editable     :  false,
                            fieldLabel   :  'Type',
                            name         :  'EVN_RELATED_TO',
                            displayField :  'name',
                            valueField   :  'value',
                            store        :  new Ext.data.JsonStore({
                                        fields : ['name', 'value'],
                                        data   : [
                                            {name : 'Single Task',   value: 'SINGLE'},
                                            {name : 'Multiple Task',   value: 'MULTIPLE'},
                                        ]
                                   }),
                           onSelect: function(record, index){
                                   var fields = workflow.multipleEventForm.items.items[1];
                                   var fields = fields.items.items;
                                   if(index == 0)
                                   {
                                     var fieldsToToggle = [fields[1]];
                                     oPmosExt.toggleFields(fieldsToToggle,true);
                                     fieldsToToggle = [fields[2],fields[3]];
                                     oPmosExt.toggleFields(fieldsToToggle,false);
                                   }
                                   else
                                   {
                                     var fieldsToToggle = [fields[1]];
                                     oPmosExt.toggleFields(fieldsToToggle,false);
                                     fieldsToToggle = [fields[2],fields[3]];
                                     oPmosExt.toggleFields(fieldsToToggle,true);
                                   }
                                   this.setValue(record.data[this.valueField || this.displayField]);
                                   this.collapse();
                            }
                    }*/
                       {
                            width        :  100,
                            xtype        :  'combo',
                            mode         :  'local',
                            triggerAction:  'all',
                            allowBlank   :  false,
                            editable     :  false,
                            fieldLabel   :  _('ID_TYPE'),
                            name         :  'EVN_RELATED_TO',
                            displayField :  'name',
                            valueField   :  'value',
                            store        :  new Ext.data.JsonStore({
                                        fields : ['name', 'value'],
                                        data   : [
                                            {name : 'Multiple Task',   value: 'MULTIPLE'},
                                        ]
                                   })
                    },
                   /* {
                            width:          150,
                            xtype:          'combo',
                            mode:           'local',
                            triggerAction:  'all',
                            allowBlank   :  true,
                            editable:       false,
                            fieldLabel:     'The time starts with task',
                            name:           'TAS_UID',
                            displayField:   'name',
                            valueField:     'value',
                            store:          new Ext.data.JsonStore({
                                    fields : ['name', 'value'],
                                    data   :oTask
                               })
                        }*/{
                            width:          150,
                            xtype:          'combo',
                            mode:           'local',
                            triggerAction:  'all',
                            allowBlank   :  false,
                            editable:       false,
                            fieldLabel:     _('ID_TIME_START_WITH_TASK'),
                            name:           'EVN_TAS_UID_FROM',
                            displayField:   'name',
                            valueField:     'value',
                            store:          new Ext.data.JsonStore({
                                    fields : ['name', 'value'],
                                    data   :oTaskFrom
                               })
                        },{
                            width:          150,
                            xtype:          'combo',
                            mode:           'local',
                            triggerAction:  'all',
                            allowBlank   :  false,
                            editable:       false,
                            fieldLabel:     _('ID_TO'),
                            name:           'EVN_TAS_UID_TO',
                            displayField:   'name',
                            valueField:     'value',
                            store:          new Ext.data.JsonStore({
                                    fields : ['name', 'value'],
                                    data   :oTaskTo
                               })
                        },{
                            width:50,
                            fieldLabel: _('ID_ESTIMATED_TASK_DURATION_DAYS'),
                            name: 'EVN_TAS_ESTIMATED_DURATION',
                            allowBlank:false
                        },{
                            width:50,
                            fieldLabel: _('ID_EXECUTION_TIME_DAYS'),
                            name: 'EVN_WHEN',
                            allowBlank:false
                        },{
                            width:          150,
                            xtype:          'combo',
                            mode:           'local',
                            triggerAction:  'all',
                            allowBlank   :  false,
                            editable:       false,
                            fieldLabel:     '',
                            name:           'EVN_WHEN_OCCURS',
                            displayField:   'name',
                            valueField:     'value',
                            store:          new Ext.data.JsonStore({
                                        fields : ['name', 'value'],
                                        data   : [
                                            {name : 'After Interval Ends',   value: 'AFTER_TIME'},
                                            {name : 'After Interval Starts',   value: 'TASK_STARTED'},
                                        ]
                                    })
                        },{
                            name  :  'EVN_ACTION',
                            value :  'EXECUTE_TRIGGER',
                            hidden:  true
                        },{
                            width:          120,
                            xtype:          'combo',
                            mode:           'local',
                            triggerAction:  'all',
                            forceSelection: true,
                            editable:       false,
                            fieldLabel:     _('ID_EXECUTE_TRIGGER'),
                            name:           'TRI_UID',
                            displayField:   'TRI_TITLE',
                            valueField:     'TRI_UID',
                            store:          new Ext.data.JsonStore({
                                    fields : ['TRI_TITLE', 'TRI_UID'],
                                    data   :oTriggers
                               })
                        }]
                }]

    });
    multipleEvent.render(document.body);

    workflow.multipleEventForm = multipleEvent;
     var evn_uid = workflow.currentSelection.id;
     multipleEvent.form.load({
      url:'proxyEventsLoad?startInterId='+evn_uid,
      method:'GET',
      waitMsg:'Loading',
      success:function(form,action) {
        },
        failure:function(form, action) {
          //  Ext.MessageBox.alert('Message', 'Load failed');
        }
      });


    // Hide/Show Fields
    var eventTitle = multipleEvent.items.items[0];
    var eventDesc = multipleEvent.items.items[1];

    var titleFields = eventTitle.items.items;
    var descFields = eventDesc.items.items;
    var fieldsToToggle = [titleFields[2],titleFields[3],titleFields[4],descFields[6]];
    oPmosExt.toggleFields(fieldsToToggle,false);

    var window = new Ext.Window({
    title: _('ID_INTERMEDIATE_TIMER_EVENTS'),
    collapsible: false,
    maximizable: false,
    width: 500,
    height: 450,
    minWidth: 300,
    minHeight: 200,
    layout: 'fit',
    plain: true,
    bodyStyle: 'padding:5px;',
    buttonAlign: 'center',
    items: multipleEvent,
    buttons: [{
        text: _('ID_SAVE'),
        handler: function(){
                         oPmosExt.saveInterTimer();
                   }
        },{
        text: _('ID_CANCEL'),
        handler: function(){
                       window.close();
                   }
        }]
    });
    window.show();    
}
pmosExt.prototype.saveInterTimer=function()
{
  var mulEventform = workflow.multipleEventForm.getForm().getValues();
  var fieldSet1 = workflow.multipleEventForm.items.items[0];
  var fieldSet2 = workflow.multipleEventForm.items.items[1];
  var eventId = workflow.currentSelection.id;
  var newFormValues = new Array();
  newFormValues = new Array();
  var sData = null;
  for (var key in mulEventform )
  {
      newFormValues[key] = new Array();
    switch(key){
      case 'EVN_TAS_UID_FROM':
          if(typeof fieldSet2.items.items[2].value != 'undefined')
            newFormValues[key]= fieldSet2.items.items[1].value;
           else
            newFormValues[key] = '';
        break;
      case 'EVN_TAS_UID_TO':
          if(typeof fieldSet2.items.items[3].value != 'undefined')
            newFormValues[key]= fieldSet2.items.items[2].value;
           else
            newFormValues[key]= '';
        break;
      case 'EVN_STATUS':
          newFormValues[key] = fieldSet1.items.items[1].value;
        break;
      case 'EVN_WHEN_OCCURS':
          newFormValues[key] = fieldSet2.items.items[5].value;
        break;
        case 'TRI_UID':
          if(typeof fieldSet2.items.items[7].value != 'undefined')
            newFormValues[key] = fieldSet2.items.items[7].value;
        break;
      case 'EVN_TYPE':
          newFormValues[key] = workflow.currentSelection.type;
        break;
      case 'EVN_RELATED_TO':
          newFormValues[key] = fieldSet2.items.items[0].value;
        break;
      case 'PRO_UID':
          newFormValues[key] = pro_uid;
        break;
      default:
          newFormValues[key] = mulEventform[key];
        break;
    }
   if(sData != null)
        sData = sData + '"'+key+'":"'+newFormValues[key]+'"'+',';
    else
        sData = '"'+key+'":"'+newFormValues[key]+'",';
  }
  //sData = Ext.util.JSON.encode(newFormValues);
  
  sData = '{'+sData.slice(0,sData.length-1)+'}';

  Ext.Ajax.request({
    url: 'eventsSave.php' ,
      success: function (response) {      // When saving data success
          /*_39fd.workflow = workflow;
          var preObj = workflow.getStartEventConn(_39fd,'sourcePort','InputPort');
            //_39fd.workflow.taskid =  _39fd.workflow.taskUid[0];
          var newObj =  workflow.getStartEventConn(_39fd,'targetPort','OutputPort');
          
          workflow.saveRoute(preObj,newObj);*/

        Ext.MessageBox.alert (response.responseText);
        },
      failure: function () {      // when saving data failed
        PMExt.notify( _('ID_STATUS') , _('ID_FAILURE') );
      },
      params: {
        sData:sData
      }
    });
}

pmosExt.prototype.loadProcess=function(_5678)
{
    var urlparams = '?action=load&data={"uid":"'+ pro_uid +'"}';
        Ext.Ajax.request({
                url: "processes_Ajax.php"+ urlparams,
                success: function(response) {
                    workflow.processInfo = Ext.util.JSON.decode(response.responseText);
                },
                failure: function(){
                     PMExt.notify( _('ID_STATUS') , _('ID_FAILURE') );
                }
            });
}
pmosExt.prototype.loadDynaforms=function()
{
        var taskUid = workflow.taskUid;
        if(typeof taskUid[0] != 'undefined')
        {
            var urlparams = '?action=dynaforms&data={"uid":"'+ taskUid[0].value +'"}';
            Ext.Ajax.request({
                    url: "processes_Ajax.php"+ urlparams,
                    success: function(response) {
                        workflow.dynaList = Ext.util.JSON.decode(response.responseText);
                    },
                    failure: function(){
                         PMExt.notify( _('ID_STATUS') , _('ID_FAILURE') );
                    }
                });
        }
}
pmosExt.prototype.loadConnectedTask=function()
{
      var urlparams = '?action=load&data={"uid":"'+ pro_uid +'"}';
        Ext.Ajax.request({
                url: "processes_Ajax.php"+ urlparams,
                success: function(response) {
                    workflow.processInfo = Ext.util.JSON.decode(response.responseText);

                },
                failure: function(){
                     PMExt.notify( _('ID_STATUS') , _('ID_FAILURE') );
                }
            });
}
pmosExt.prototype.loadWebEntry=function()
{
       var evn_uid = workflow.currentSelection.id;
       var urlparams = '?action=webEntry&data={"uid":"'+ pro_uid +'","evn_uid":"'+evn_uid+'"}';
        Ext.Ajax.request({
                url: "processes_Ajax.php"+ urlparams,
                success: function(response) {
                    workflow.webEntryList = Ext.util.JSON.decode(response.responseText);
                },
                failure: function(){
                    PMExt.notify( _('ID_STATUS') , _('ID_FAILURE') );
                }
            });
}
pmosExt.prototype.loadEditProcess=function()
{
      var urlparams = '?action=process_Edit&data={"pro_uid":"'+ pro_uid +'"}';
        Ext.Ajax.request({
                url: "processes_Ajax.php"+ urlparams,
                success: function(response) {
                    workflow.processEdit = Ext.util.JSON.decode(response.responseText);
                },
                failure: function(){
                    PMExt.notify( _('ID_STATUS') , _('ID_FAILURE') );
                }
            });
}
pmosExt.prototype.loadProcessCategory =function()
{
       var urlparams = '?action=loadCategory';
        Ext.Ajax.request({
                url: "processes_Ajax.php"+ urlparams,
                success: function(response) {
                    workflow.processCategory = Ext.util.JSON.decode(response.responseText);
                },
                failure: function(){
                     PMExt.notify( _('ID_STATUS') , _('ID_FAILURE') );
                }
            });
}
pmosExt.prototype.saveEvent =function(urlparams)
{
        Ext.Ajax.request({
                url: "processes_Ajax.php"+ urlparams,
                success: function(response) {

                },
                failure: function(){
                    PMExt.notify( _('ID_STATUS') , _('ID_FAILURE') );
                }
            });
}
