var caseData = '';
var appTitle = new Ext.form.Label({
    fieldLabel: "Case Title",
    labelStyle: 'font-weight:bold;padding-right:30px;'
});

var process = new Ext.form.Label({
    fieldLabel: "Process Uid",
    labelStyle: 'font-weight:bold;padding-right:35px;'
});

var processTitle = new Ext.form.Label({
    fieldLabel: "Process",
    labelStyle: 'font-weight:bold;padding-right:35px;'
});

var appUid = new Ext.form.Label({
    fieldLabel: "App Uid",
    labelStyle: 'font-weight:bold;padding-right:35px;'
});

var caseNumber = new Ext.form.Label({
    fieldLabel: "Case number",
    labelStyle: 'font-weight:bold;padding-right:35px;'
});

var initUser = new Ext.form.Label({
    fieldLabel: "Init user",
    labelStyle: 'font-weight:bold;padding-right:35px;'
});

var finishUser = new Ext.form.Label({
    fieldLabel: "Finish user",
    labelStyle: 'font-weight:bold;padding-right:35px;'
});

var createDate = new Ext.form.Label({
    fieldLabel: "Create date",
    labelStyle: 'font-weight:bold;padding-right:35px;'
});

var finishDate = new Ext.form.Label({
    fieldLabel: "Finish date",
    labelStyle: 'font-weight:bold;padding-right:35px;'
});

var fileName = new Ext.form.Label({
    fieldLabel: "File Name",
    labelStyle: 'font-weight:bold;padding-right:35px;'
});

var statusCaseWin = new Ext.form.Label({
    fieldLabel: "Status",
    labelStyle: 'font-weight:bold;padding-right:35px;'
});

var formCase = new Ext.FormPanel({
    labelWidth : 120,
    labelAlign : 'right',
    autoScroll: true,
    frame: true,
    bodyStyle   : 'padding-top:20px;padding-left:20px;',
    items:[
        appTitle,
        caseNumber,
        processTitle,
        initUser,
        finishUser,
        createDate,
        finishDate,
        fileName,
        statusCaseWin
    ],
    buttons:[{
        text : "Restore case",
        id: 'BUTTON_UNARCHIVE_CASE',
        iconCls: 'button_menu_ext ss_sprite ss_folder_go',
        formBind : true,
        handler : function(){
            if (caseData!='') {
                    Ext.MessageBox.confirm("Confirm", "Are you sure you want to restore the case?", function (val) {
                        if (val == 'yes') {
                            dataCase = caseData;
                            Ext.MessageBox.show({
                                msg: "Restoring case" + ' ' + dataCase.CASE_NUMBER + ' ...',
                                progressText: 'Saving...',
                                width:300,
                                wait:true,
                                waitConfig: {interval:200},
                                animEl: 'mb7'
                            });
                            Ext.Ajax.request({
                                params: {
                                    'APP_UID': dataCase.APP_UID,
                                    'FILENAME_TAR': dataCase.FILENAME_TAR,
                                    'functionExecute': 'unarchiveCase'
                                },
                                url : 'controllers/searchListProxy.php',
                                success: function (returnData) {
                                    Ext.MessageBox.hide();

                                    var resp = Ext.decode(returnData.responseText);
                                    if (resp.success) {
                                        Ext.MessageBox.show({
                                            title: 'Case Unarhive',
                                            msg: "Case" + ' ' + dataCase.CASE_NUMBER + ' ' + "Restored sucessfully",
                                            buttons: Ext.MessageBox.OK,
                                            animEl: 'mb9',
                                            icon: Ext.MessageBox.INFO
                                        });
                                    } else {
                                        Ext.MessageBox.show({
                                            title: "Error",
                                            msg: resp.message,
                                            buttons: Ext.MessageBox.OK,
                                            animEl: 'mb9',
                                            icon: Ext.MessageBox.ERROR
                                        });
                                    }

                                    storeGridSearch.load();
                                },
                                failure: function () {
                                    Ext.MessageBox.alert("Error", "Error in server");
                                }
                            });
                        }
                    });
            }
        }
    },
    {
        text : _('ID_CLOSE'),
        iconCls: 'button_menu_ext ss_sprite ss_folder_delete',
        formBind : true,
        handler : function(){
            summaryWindow.hide();
        }
    }]
});

var summaryWindow = new Ext.Window({
    title: "Detail Case",
    layout: 'fit',
    width: 500,
    height: 320,
    resizable: true,
    closable: true,
    closeAction : 'hide',
    modal: true,
    autoScroll:true,
    constrain: true,
    items: [formCase]
});

function showCaseSummary(dataCase) {
    if (dataCase) {
        caseData = dataCase;

        if(dataCase.STATUS == 'RESTORED'){
            Ext.getCmp('BUTTON_UNARCHIVE_CASE').disable();
        } else {
            Ext.getCmp('BUTTON_UNARCHIVE_CASE').enable();
        }

        appTitle.setText(dataCase.APP_TITLE, false);
        process.setText(dataCase.PRO_UID, false);
        processTitle.setText(dataCase.PRO_TITLE, false);
        appUid.setText(dataCase.APP_UID, false);
        caseNumber.setText(dataCase.CASE_NUMBER, false);
        initUser.setText(dataCase.INIT_USER_NAME, false);
        finishUser.setText(dataCase.FINISH_USER_NAME, false);
        createDate.setText(renderDate(dataCase.CREATE_DATE, false));
        finishDate.setText(renderDate(dataCase.FINISH_DATE, false));
        fileName.setText(dataCase.FILENAME_TAR+'.tar', false);
        statusCaseWin.setText(dataCase.STATUS, false);

        summaryWindow.show();
    }
}