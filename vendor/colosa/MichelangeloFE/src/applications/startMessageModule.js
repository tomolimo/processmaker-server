(function () {
    var winHtmlShow, loadValuesStartMessage;

    PMDesigner.startMessage = function (element) {
        var winGrdpnlStartMessage,
            btnNew,
            cboUser,
            cboDynaForm,
            btnGenerateStartMessagePage,
            frmDataStartMessage,
            frmHtml,
            winFrmDataStartMessage,
            restProxy,
            disableAllItems,
            winFrmStartMessageShow,
            updateStartMessage,
            loadDataDynaform,
            loadDataUsers,
            loadDataForm,
            listUsers = [],
            cboMethod,
            listDynaforms = [],
            dataForm = [],
            enableGenerateWebEntry,
            btnSaveStartMessagePage,
            btnStartMessageCancel,
            btnClose,
            startMessage = element;

        disableAllItems = function () {
            winGrdpnlStartMessage.getItems()[0].setVisible(false);
            winGrdpnlStartMessage.getItems()[1].setVisible(false);
            btnGenerateStartMessagePage.setVisible(false);
            btnStartMessageCancel.setVisible(false);
            btnClose.setVisible(false);
            winGrdpnlStartMessage.hideFooter();
        };

        winFrmStartMessageShow = function () {
            disableAllItems();
            frmDataStartMessage.reset();
            winGrdpnlStartMessage.showFooter();
            winGrdpnlStartMessage.getItems()[0].setVisible(true);
            btnGenerateStartMessagePage.setVisible(true);
            btnStartMessageCancel.setVisible(true);
            loadDataUsers();
            loadDataDynaform();
            loadDataForm();
            frmDataStartMessage.setFocus();
        };

        winHtmlShow = function (msgHtml) {
            if (typeof msgHtml == 'undefined') {
                msgHtml = dataForm.we_data;
                if (dataForm.we_method == "WS") {
                    window.open(msgHtml);
                    winGrdpnlStartMessage.close();
                    return false;
                }
            }
            disableAllItems();
            winGrdpnlStartMessage.showFooter();
            winGrdpnlStartMessage.footer.getItems()[1].setVisible(false);
            btnClose.setVisible(true);
            winGrdpnlStartMessage.getItems()[1].setVisible(true);
            winGrdpnlStartMessage.getItems()[1].setWidth(925);
            winGrdpnlStartMessage.getItems()[1].setHeight(440);
            winGrdpnlStartMessage.getItems()[1].hideHeader();
            frmHtml.getFields()[0].setHeight(396);
            frmHtml.getItems()[0].setValue(msgHtml);
            frmHtml.panel.style.addProperties({'box-sizing': 'initial'});
            frmHtml.style.addProperties({marginLeft: '16px'});
            frmHtml.setFocus();
            return true;
        };

        loadValuesStartMessage = function (flag) {
            var viewFlagForm,
                restClient,
                response;
            listUsers = [];
            listDynaforms = [];
            dataForm = [];
            restClient = new PMRestClient({
                typeRequest: 'post',
                multipart: true,
                data: {
                    calls: [
                        {
                            url: 'activity/' + startMessage.ports.get(0).connection.flo_element_dest + '/assignee/all',
                            method: 'GET'
                        },
                        {
                            url: "activity/" + startMessage.ports.get(0).connection.flo_element_dest + "/steps",
                            method: 'GET'
                        }, {
                            url: 'web-entry/' + startMessage.evn_uid,
                            method: 'GET'
                        }
                    ]
                },
                functionSuccess: function (xhr, response) {
                    listUsers = response[0].response;
                    listDynaforms = response[1].response;
                    dataForm = response[2].response;
                },
                functionFailure: function (xhr, response) {
                    PMDesigner.msgWinError(response.error.message);
                }
            });
            restClient.executeRestClient();
            if (flag) {
                viewFlagForm = false;
                if (typeof dataForm == 'object' && dataForm.we_data != null) {
                    viewFlagForm = true;
                }
                response = [listUsers.length, listDynaforms.length, viewFlagForm];
                return response;
            }
            return true;
        };

        updateStartMessage = function (data) {
            restClient = new PMRestClient({
                typeRequest: 'post',
                multipart: true,
                data: {
                    calls: [
                        {
                            url: "web-entry/" + startMessage.evn_uid,
                            method: 'PUT',
                            data: data
                        },
                        {
                            url: 'web-entry/' + startMessage.evn_uid,
                            method: 'GET'
                        }
                    ]
                },
                functionSuccess: function (xhr, response) {
                    dataForm = response[1].response;
                    if (data.we_method == "HTML") {
                        winHtmlShow(response.we_data);
                    }
                    btnGenerateStartMessagePage.setVisible(false);
                    btnStartMessageCancel.setVisible(false);
                    btnClose.setVisible(true);
                    winGrdpnlStartMessage.footer.getItems()[1].setVisible(false);
                },
                functionFailure: function (xhr, response) {
                    PMDesigner.msgWinError(response.error.message);
                },
                messageError: ["There are problems updating the Web Entry, please try again.".translate()],
                messageSuccess: ['Web Entry updated successfully'.translate()],
                flashContainer: frmDataStartMessage
            });
            restClient.executeRestClient();
        };

        loadDataDynaform = function () {
            var i;
            cboDynaForm.clearOptions();
            for (i = 0; i <= listDynaforms.length - 1; i++) {
                if (listDynaforms[i].step_type_obj == "DYNAFORM") {
                    cboDynaForm.addOption({
                        value: listDynaforms[i].step_uid_obj,
                        label: listDynaforms[i].obj_title
                    });
                }
            }
        };

        loadDataUsers = function () {
            var i;
            cboUser.clearOptions();
            for (i = 0; i <= listUsers.length - 1; i++) {
                cboUser.addOption({
                    value: listUsers[i].aas_uid,
                    label: listUsers[i].aas_name + ' ' + listUsers[i].aas_lastname
                });
            }
        };

        loadDataForm = function () {
            cboUser.setValue(dataForm.usr_uid);
            cboDynaForm.setValue(dataForm.dyn_uid);
            cboMethod.setValue(dataForm.we_method);
        };

        enableGenerateWebEntry = function () {
            btnGenerateStartMessagePage.setVisible(true);
            btnStartMessageCancel.setVisible(true);
            btnClose.setVisible(false);
            winGrdpnlStartMessage.footer.getItems()[1].setVisible(true);
        };
        cboUser = new PMUI.field.DropDownListField({
            id: "cboUser",
            name: "cboUser",
            label: "User".translate(),
            required: true,
            options: null,
            onChange: function (newVal, oldVal) {
                enableGenerateWebEntry();
            },
            controlsWidth: 350
        });

        cboDynaForm = new PMUI.field.DropDownListField({
            id: "cboDynaForm",
            name: "cboDynaForm",
            label: "Initial Dynaform".translate(),
            options: null,
            required: true,
            onChange: function (newVal, oldVal) {
                enableGenerateWebEntry();
            },
            controlsWidth: 350
        });

        cboMethod = new PMUI.field.DropDownListField({
            id: "cboMethod",
            name: "cboMethod",
            label: "Method".translate(),
            value: "WS",
            options: [
                {
                    value: "WS",
                    label: "PHP pages with Web Services".translate()
                },
                {
                    value: "HTML",
                    label: "Single HTML".translate()
                }
            ],
            onChange: function (newVal, oldVal) {
                enableGenerateWebEntry();
            },
            controlsWidth: 350
        });

        btnGenerateStartMessagePage = new PMUI.ui.Button({
            id: "btnGenerateStartMessagePage",
            text: "Generate Web Entry Page".translate(),
            handler: function () {
                var flagGenerateStartMessage = 0, data;
                data = {
                    tas_uid: startMessage.ports.get(0).connection.flo_element_dest,
                    dyn_uid: cboDynaForm.getValue(),
                    usr_uid: cboUser.getValue(),
                    we_title: startMessage.evn_name,
                    we_description: '',
                    we_method: cboMethod.getValue(),
                    we_input_document_access: 1
                };

                if (frmDataStartMessage.isValid()) {
                    updateStartMessage(data);
                }
            },
            buttonType: 'success',
            height: 31,
            visible: true
        });
        btnSaveStartMessagePage = new PMUI.ui.Button({
            id: "btnGenerateStartMessagePage",
            text: "Save".translate(),
            handler: function () {
                var flagGenerateStartMessage = 0, data;
                data = {
                    tas_uid: startMessage.ports.get(0).connection.flo_element_dest,
                    dyn_uid: cboDynaForm.getValue(),
                    usr_uid: cboUser.getValue(),
                    we_title: startMessage.evn_name,
                    we_description: '',
                    we_method: cboMethod.getValue(),
                    we_input_document_access: 1
                };

                if (frmDataStartMessage.isValid()) {
                    updateStartMessage(data);
                }
            },
            buttonType: 'success',
            height: 31,
            visible: true
        });

        frmDataStartMessage = new PMUI.form.Form({
            id: "frmDataStartMessage",
            title: "",
            width: DEFAULT_WINDOW_WIDTH - 70,
            items: [
                cboUser,
                cboDynaForm,
                cboMethod
            ],
            visibleHeader: false
        });

        btnClose = new PMUI.ui.Button({
            id: "btnClose",
            text: "Close".translate(),
            handler: function () {
                winGrdpnlStartMessage.close();
            },
            buttonType: 'success',
            height: 31
        });

        btnStartMessageCancel = new PMUI.ui.Button({
            id: "btnStartMessageCancel",
            text: "Cancel".translate(),
            handler: function () {
                var message_window;
                if (frmDataStartMessage.isDirty()) {
                    message_window = new PMUI.ui.MessageWindow({
                        windowMessageType: 'warning',
                        id: "cancelMessageStartTimer",
                        title: "Start Message Event".translate(),
                        message: 'Are you sure you want to discard your changes?'.translate(),
                        bodyHeight: 'auto',
                        width: 490,
                        footerItems: [
                            {
                                text: 'No'.translate(),
                                handler: function () {
                                    message_window.close();
                                },
                                buttonType: "error"
                            },
                            {
                                text: 'Yes'.translate(),
                                handler: function () {
                                    message_window.close();
                                    winGrdpnlStartMessage.close();
                                },
                                buttonType: "success"
                            }
                        ]
                    });
                    message_window.open();
                    message_window.showFooter();
                } else {
                    frmDataStartMessage.reset();
                    winGrdpnlStartMessage.close();
                }
            }
        });

        frmHtml = new PMUI.form.Form({
            id: "frmHtml",
            title: "",
            width: DEFAULT_WINDOW_WIDTH - 43,
            items: [
                {
                    id: "txtHtml",
                    name: "txtHtml",
                    pmType: "textarea",
                    valueType: "string",
                    rows: 400,
                    value: '',
                    controlsWidth: DEFAULT_WINDOW_WIDTH - 50,
                    labelVisible: false,
                    style: {cssClasses: ['mafe-textarea-resize']}
                }
            ]
        });

        winGrdpnlStartMessage = new PMUI.ui.Window({
            id: "winGrdpnlStartMessage",
            title: "Start Message Event".translate(),
            height: DEFAULT_WINDOW_HEIGHT,
            width: DEFAULT_WINDOW_WIDTH,
            buttonPanelPosition: "top",
            buttons: [btnSaveStartMessagePage, {pmType: 'label', text: 'or'}, btnStartMessageCancel, btnClose]
        });

        winGrdpnlStartMessage.addItem(frmDataStartMessage);

        openForm = function () {
            winGrdpnlStartMessage.open();
            winGrdpnlStartMessage.defineEvents();
            applyStyleWindowForm(winGrdpnlStartMessage);
            winGrdpnlStartMessage.footer.html.style.textAlign = 'right';
            winFrmStartMessageShow();
        }
    };

    PMDesigner.startMessage.openForm = function (element) {
        openForm();
    };

    PMDesigner.startMessage.viewForm = function (element) {
        openForm();
    };

    PMDesigner.startMessage.validate = function (starMessageEvent) {
        if (starMessageEvent.ports.isEmpty()) {
            PMDesigner.msgFlash('Must connect to a Task'.translate(), document.body, 'error', 4000, 5);
            return [false, false];
        }
        PMDesigner.startMessage(starMessageEvent);
        response = loadValuesStartMessage(true);
        if (response[0] == 0) {
            PMDesigner.msgFlash('The task doesn\'t have assigned users'.translate(), document.body, 'info', 4000);
            return [false, false];
        }
        if (response[1] == 0) {
            PMDesigner.msgFlash('The task doesn\'t have assigned Dynaforms'.translate(), document.body, 'info', 4000);
            return [false, false];
        }
        return [true, response[2]];
    }
}());
