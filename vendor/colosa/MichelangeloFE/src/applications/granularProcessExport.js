(function () {
    PMDesigner.granularProcessExport = function () {
        var totalObjects = 0,
            objectValues,
            buttonSave,
            objectsGroup,
            loadObjects,
            domSettings,
            granularProcessExportForm,
            granularProcessExportWindow,
            buttonCancel = new PMUI.ui.Button({
                id: 'cancelButton',
                text: "Cancel".translate(),
                buttonType: 'error',
                handler: function (event) {
                    granularProcessExportWindow.close();
                }
            });

        buttonSave = new PMUI.ui.Button({
            id: 'saveButton',
            text: "Export".translate(),
            handler: function (event) {
                var selectedObjects,
                    locationOrigin,
                    ws = enviromentVariables('WORKSPACE'),
                    formData = granularProcessExportForm.getData();
                selectedObjects = JSON.parse(formData.objectsGroup).map(function (n) {
                    return n;
                });
                if (selectedObjects.length === 0) {
                    PMDesigner.msgFlash('At least one object should be selected in order to execute the action.'.translate(), document.body, 'error', 3000, 5);
                    return;
                }
                if (!window.location.origin) {
                    locationOrigin = window.location.protocol + "//" + window.location.hostname +
                        (window.location.port ? ':' + window.location.port : '');
                } else if (typeof HTTP_SERVER_HOSTNAME !== 'undefined') {
                    locationOrigin = HTTP_SERVER_HOSTNAME;
                } else {
                    locationOrigin = window.location.origin;
                }
                location.href = locationOrigin + "/api/1.0/" + ws + "/project/" + PMDesigner.project.id +
                    "/export-granular?access_token=" + PMDesigner.project.keys.access_token + '&objects=' +
                    encodeURIComponent(JSON.stringify(selectedObjects));
                granularProcessExportWindow.close();
            },
            buttonType: 'success'
        });

        granularProcessExportWindow = new PMUI.ui.Window({
            id: "granularProcessExportWindow",
            title: "Export Process Objects".translate(),
            width: 350,
            height: DEFAULT_WINDOW_HEIGHT,
            footerItems: [
                buttonCancel,
                buttonSave
            ],
            buttonPanelPosition: "bottom",
            footerAling: "right",
            onBeforeClose: function () {
                granularProcessExportWindow.close();
            }
        });

        objectsGroup = new PMUI.field.CheckBoxGroupField({
            labelVisible: false,
            id: 'objectsGroup',
            controlPositioning: 'horizontal',
            maxDirectionOptions: 1,
            required: true,
            options: [],
            onChange: function (newVal, oldVal) {
                if (totalObjects > JSON.parse(newVal).length) {
                    granularProcessExportForm.getField('checkAllId').setValue(0);
                }
            }
        });

        loadObjects = function () {
            var objectsList,
                restClient,
                i;
            restClient = new PMRestClient({
                endpoint: 'export/listObjects',
                typeRequest: 'get',
                functionSuccess: function (xhr, response) {
                    var arrayIds = [],
                        i;
                    objectsList = JSON.parse(response);
                    for (i in objectsList.data) {
                        objectsGroup.addOption({
                            label: objectsList.data[i].OBJECT_NAME,
                            name: objectsList.data[i].OBJECT_NAME,
                            selected: false,
                            value: objectsList.data[i].OBJECT_ID
                        });
                        arrayIds.push(objectsList.data[i].OBJECT_ID.toString());
                    }
                    totalObjects = objectsList.data.length;
                    objectValues = objectsGroup.getValueFromControls();
                    objectValues = JSON.parse(objectValues).length ? objectValues : JSON.stringify(arrayIds);
                },
                functionFailure: function (xhr, response) {
                    PMDesigner.msgWinError(response.error.message);
                    PMDesigner.msgFlash('There are problems loading the process objects.'.translate(), document.body, 'error', 3000, 5);
                }
            });
            restClient.executeRestClient();
        };
        loadObjects();

        granularProcessExportForm = new PMUI.form.Form({
            id: "granularProcessExportForm",
            border: true,
            visibleHeader: false,
            width: '340px',
            name: "granularProcessExportForm",
            title: "",
            items: [
                {
                    id: "panelDetailsCustom",
                    pmType: "panel",
                    layout: 'vbox',
                    fieldset: false,
                    height: '380px',
                    legend: "DETAILS".translate(),
                    items: [
                        {
                            pmType: "checkbox",
                            id: "checkAllId",
                            labelVisible: false,
                            controlPositioning: 'vertical',
                            maxDirectionOptions: 2,
                            value: '',
                            options: [
                                {
                                    label: "Check All".translate(),
                                    disabled: false,
                                    value: '1',
                                    selected: false
                                }
                            ],
                            onChange: function (newVal, oldVal) {
                                if (newVal[2] === "1") {
                                    objectsGroup.setValueToControls(objectValues);
                                } else {
                                    objectsGroup.setValueToControls();
                                }
                            }
                        },
                        objectsGroup
                    ]
                }
            ]
        });

        domSettings = function () {
            $('#objectsGroup').find('label:eq(0)').remove();
        };

        granularProcessExportWindow.addItem(granularProcessExportForm);
        granularProcessExportWindow.open();
        granularProcessExportWindow.showFooter();
        domSettings();
    };
}());

