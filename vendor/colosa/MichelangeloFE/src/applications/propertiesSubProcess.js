(function () {
    var windowPropSub;

    PMDesigner.propertiesSubProcess = function (activity) {
        var typeVariables = ['@@', '@#', '@=', '@&'],
            restClient,
            isDirtyFormSubProcess,
            getSubProcess,
            formVarTexOriginOut,
            updateSubProcess,
            formVarTexOriginIn,
            loadDropProcess,
            formVarTexTargetOut,
            formVarTexTargetIn,
            windowPropertiesSub,
            formProperties,
            sepInputs,
            gridVariablesOut,
            gridVariablesIn,
            formVariablesPanelOut,
            formVariablesPanelIn,
            validateVariable,
            formVarButtonAddOut,
            formVarButtonAddIn,
            labelVariablesOut,
            labelVariablesIn,
            loadDataServer,
            loadActivity,
            isDirty = false;

        restClient = new PMRestClient({
            endpoint: 'projects',
            typeRequest: 'get',
            functionSuccess: function (xhr, response) {
                for (var i = 0; i < response.length; i += 1) {
                    if (response[i].prj_uid != PMDesigner.project.projectId) {
                        formProperties.getField('out_doc_process').addOption({
                            label: response[i].prj_name,
                            value: response[i].prj_uid
                        });
                    }
                }
            },
            functionFailure: function (xhr, response) {
                PMDesigner.msgWinError(response.error.message);
            },
            messageError: "There are problems getting the output documents, please try again.".translate()
        });
        isDirtyFormSubProcess = function () {
            var message_window;
            if (formProperties.isDirty() || isDirty) {
                message_window = new PMUI.ui.MessageWindow({
                    windowMessageType: 'warning',
                    width: 490,
                    bodyHeight: 'auto',
                    id: "cancelSaveSubprocPropertiesWin",
                    title: "Sub-process Properties".translate(),
                    message: 'Are you sure you want to discard your changes?'.translate(),
                    footerItems: [
                        {
                            text: "No".translate(),
                            handler: function () {
                                message_window.close();
                            },
                            buttonType: "error"
                        },
                        {
                            text: "Yes".translate(),
                            handler: function () {
                                message_window.close();
                                formProperties.reset();
                                gridVariablesOut.setDataItems("");
                                gridVariablesIn.setDataItems("");
                                formProperties.setDirty(false);
                                windowPropertiesSub.close();
                            },
                            buttonType: "success"
                        }
                    ]
                });
                message_window.open();
                message_window.showFooter();
            } else {
                windowPropertiesSub.close();
            }
        };
        getSubProcess = function () {
            var restProxy = new PMRestClient({
                endpoint: "subprocess/" + activity.act_uid,
                typeRequest: "get",
                functionSuccess: function (xhr, response) {
                    var yout,
                        oout,
                        jout,
                        itemsOut,
                        i,
                        xin,
                        yin,
                        oin,
                        jin,
                        dout,
                        din,
                        itemsIn,
                        xout;
                    formProperties.getField('out_doc_title').setValue(response.spr_name);
                    formProperties.getField('out_doc_process').setValue(response.spr_pro);
                    formProperties.getField('out_doc_type').setValue(response.spr_synchronous);

                    if (response.spr_variables_out != false) {
                        xout = JSON.stringify(response.spr_variables_out);
                        yout = xout.substring(1, xout.length - 1);
                        oout = yout.replace(/"/g, '');
                        jout = oout.split(',');

                        itemsOut = [];
                        for (i = 0; i < jout.length; i += 1) {
                            dout = jout[i].split(':');
                            itemsOut.push({
                                origin: dout[0],
                                target: dout[1]
                            });
                        }
                        gridVariablesOut.setDataItems(itemsOut);

                        gridVariablesIn.setVisible(false);
                        formVariablesPanelIn.setVisible(false);
                    }

                    if (response.spr_variables_in != false && response.spr_variables_in != undefined) {
                        xin = JSON.stringify(response.spr_variables_in);
                        yin = xin.substring(1, xin.length - 1);
                        oin = yin.replace(/"/g, '');
                        jin = oin.split(',');

                        itemsIn = [];
                        for (i = 0; i < jin.length; i += 1) {
                            din = jin[i].split(':');
                            itemsIn.push({
                                origin: din[0],
                                target: din[1]
                            });
                        }
                        gridVariablesIn.setDataItems(itemsIn);
                    }

                    if (response.spr_synchronous == "1") {
                        gridVariablesIn.setVisible(true);
                        formVariablesPanelIn.setVisible(true);
                    }
                },
                functionFailure: function (xhr, response) {
                    PMDesigner.msgWinError(response.error.message);
                }
            });
            restProxy.executeRestClient();
        };

        updateSubProcess = function (data) {
            var restProxy = new PMRestClient({
                endpoint: "subprocess/" + activity.act_uid,
                typeRequest: "update",
                data: data,
                functionSuccess: function (xhr, response) {
                    var name = formProperties.getData().out_doc_title;
                    activity.setName(name);
                    activity.setActName(name);
                    PMDesigner.project.dirty = true;

                    formProperties.reset();
                    gridVariablesOut.setDataItems("");
                    gridVariablesIn.setDataItems("");
                    formProperties.setDirty(false);
                    windowPropertiesSub.close();
                },
                functionFailure: function (xhr, response) {
                    PMDesigner.msgWinError(response.error.message);
                }
            });
            restProxy.executeRestClient();
        };

        formVarTexOriginOut = new CriteriaField({
            id: 'idformVarTexOriginOut',
            pmType: "text",
            name: 'nmformVarTexOriginOut',
            placeholder: 'Origin'.translate(),
            labelVisible: false,
            controlsWidth: 150
        });

        formVarTexOriginIn = new CriteriaField({
            id: 'idformVarTexOriginIn',
            pmType: "text",
            name: 'nmformVarTexOriginIn',
            placeholder: 'Select Origin Process'.translate(),
            labelVisible: false,
            controlsWidth: 150
        });

        formVarTexTargetOut = new CriteriaField({
            id: 'idformVarTexTargetOut',
            pmType: "text",
            name: 'nmformVarTexTargetOut',
            placeholder: 'Select Target Process'.translate(),
            labelVisible: false,
            controlsWidth: 150,
            required: true
        });

        formVarTexTargetIn = new CriteriaField({
            id: 'idformVarTexTargetIn',
            pmType: "text",
            name: 'nmformVarTexTargetIn',
            placeholder: 'Target'.translate(),
            labelVisible: false,
            controlsWidth: 150
        });

        windowPropertiesSub = new PMUI.ui.Window({
            id: 'propSubWindow',
            title: "Sub-process Properties".translate(),
            height: DEFAULT_WINDOW_HEIGHT,
            width: DEFAULT_WINDOW_WIDTH,
            bodyHeight: '465px',
            bodyWidth: '900px',
            onBeforeClose: function () {
                isDirtyFormSubProcess();
            },
            buttons: [
                {
                    id: 'propSubButtonClose',
                    text: "Close".translate(),
                    buttonType: 'error',
                    handler: function () {
                        isDirtyFormSubProcess();
                    }
                },
                {
                    id: 'propSubButtonSave',
                    text: "Save".translate(),
                    buttonType: 'success',
                    handler: function () {
                        if (!formProperties.isValid()) {
                            return;
                        }
                        var propertiesData = formProperties.getData();
                        var variablesOutData = gridVariablesOut.getData();
                        var variablesOut = {};
                        if (variablesOutData.length > "0") {
                            for (i = 0; i < variablesOutData.length; i += 1) {
                                variablesOut[variablesOutData[i].origin] = variablesOutData[i].target;
                            }
                        }
                        var variablesIn = {};
                        if (propertiesData.out_doc_type == "1") {
                            var variablesInData = gridVariablesIn.getData();
                            if (variablesInData.length > "0") {
                                for (j = 0; j < variablesInData.length; j += 1) {
                                    variablesIn[variablesInData[j].origin] = variablesInData[j].target;
                                }
                            }
                        }

                        var dataToSend = {
                            spr_pro: propertiesData.out_doc_process,
                            spr_tas: propertiesData.spr_tas,
                            spr_name: propertiesData.out_doc_title,
                            spr_synchronous: propertiesData.out_doc_type,
                            spr_variables_out: variablesOut,
                            spr_variables_in: variablesIn
                        };

                        updateSubProcess(dataToSend);
                    }
                }
            ],
            buttonPanelPosition: 'bottom',
            buttonsPosition: 'right'
        });

        formProperties = new PMUI.form.Form({
            id: 'propSubForm',
            title: "",
            fieldset: true,
            visibleHeader: false,
            width: 926,
            height: 'auto',
            items: [
                {
                    id: 'propSubFormTitle',
                    pmType: "text",
                    name: 'out_doc_title',
                    label: "Sub-Process name".translate(),
                    required: true,
                    controlsWidth: 300
                },
                {
                    id: 'propSubFormProcess',
                    pmType: "dropdown",
                    name: 'out_doc_process',
                    label: "Process".translate(),
                    controlsWidth: 300,
                    required: true,
                    options: [
                        {
                            label: "- Select a process -".translate(),
                            value: "",
                            disabled: true,
                            selected: true
                        }
                    ],
                    value: "",
                    onChange: function (a, b) {
                        if (a.trim().length !== 0) {
                            formVarTexTargetOut.buttonHTML.enable();
                            formVarTexOriginIn.buttonHTML.enable();
                            formVarTexTargetOut.controls[0].setPlaceholder("Target");
                            formVarTexOriginIn.controls[0].setPlaceholder("Origin");
                        } else {
                            formVarTexTargetOut.buttonHTML.disable();
                            formVarTexOriginIn.buttonHTML.disable();
                        }
                        formVarTexTargetOut.setProcess(a);
                        formVarTexOriginIn.setProcess(a);
                        loadActivity(a);
                    }
                },
                {
                    id: 'propSubFormActivity',
                    pmType: "dropdown",
                    name: 'spr_tas',
                    label: "Starting activity".translate(),
                    controlsWidth: 300,
                    required: true,
                    options: [
                        {
                            label: "- Select starting activity -".translate(),
                            value: "",
                            disabled: true,
                            selected: true
                        }
                    ],
                    value: "",
                    onChange: function (a, b) {
                    }
                },
                {
                    id: 'propSubFormType',
                    pmType: "dropdown",
                    name: 'out_doc_type',
                    label: "Type".translate(),
                    controlsWidth: 150,
                    required: true,
                    options: [
                        {
                            label: "Asynchronous".translate(),
                            value: "0"
                        },
                        {
                            label: "Synchronous".translate(),
                            value: "1"
                        }
                    ],
                    value: "0",
                    onChange: function (a, b) {
                        isDirty = true;
                    }
                }
            ],
            onChange: function (newValue, prevValue) {
                switch (newValue.value) {
                    case "1" :
                        gridVariablesIn.setVisible(true);
                        gridVariablesIn.style.addProperties({marginLeft: '45px'});
                        formVariablesPanelIn.setVisible(true);
                        formVariablesPanelIn.getItems()[2].setWidth(80);
                        $(formVariablesPanelIn.html).css({width: '850px', marginLeft: '45px'});
                        windowPropertiesSub.getItems()[4].setVisible(true);
                        break;
                    case "0":
                        gridVariablesIn.setVisible(false);
                        formVariablesPanelIn.setVisible(false);
                        windowPropertiesSub.getItems()[4].setVisible(false);
                        break;
                    default :
                        break;
                }
            }
        });

        sepInputs = new PMSeparatorLineField({
            controlHeight: '1px',
            controlColor: "#CDCDCD",
            controlsWidth: "890px",
            marginLeft: '0%'
        });

        formProperties.addItem(sepInputs);

        gridVariablesOut = new PMUI.grid.GridPanel({
            id: 'propSubGridVariablesOut',
            emptyMessage: 'No records found'.translate(),
            nextLabel: 'Next'.translate(),
            previousLabel: 'Previous'.translate(),
            customStatusBar: function (currentPage, pageSize, numberItems, criteria, filter) {
                return messagePageGrid(currentPage, pageSize, numberItems, criteria, filter);
            },
            pageSize: 10,
            style: {
                cssClasses: ["mafe-gridPanel"]
            },
            columns: [
                {
                    title: 'Origin'.translate(),
                    dataType: 'string',
                    width: '40%',
                    alignmentCell: 'left',
                    columnData: "origin"
                },
                {
                    title: 'Target'.translate(),
                    dataType: 'string',
                    width: '40%',
                    alignmentCell: 'left',
                    columnData: "target"
                },
                {
                    id: 'propSubGridVariablesOutButtonDelete',
                    title: '',
                    dataType: 'button',
                    width: '20%',
                    buttonLabel: 'Delete'.translate(),
                    onButtonClick: function (row, grid) {
                        grid.removeItem(row);
                        isDirty = true;
                    },
                    buttonStyle: {
                        cssClasses: [
                            'mafe-button-delete'
                        ]
                    }

                }
            ]
        });

        gridVariablesIn = new PMUI.grid.GridPanel({
            id: 'propSubGridVariablesIn',
            emptyMessage: 'No records found'.translate(),
            nextLabel: 'Next'.translate(),
            previousLabel: 'Previous'.translate(),
            customStatusBar: function (currentPage, pageSize, numberItems, criteria, filter) {
                return messagePageGrid(currentPage, pageSize, numberItems, criteria, filter);
            },
            pageSize: 10,
            columns: [
                {
                    title: 'Origin'.translate(),
                    dataType: 'string',
                    width: '40%',
                    alignmentCell: 'left',
                    columnData: "origin"
                },
                {
                    title: 'Target'.translate(),
                    dataType: 'string',
                    width: '40%',
                    alignmentCell: 'left',
                    columnData: "target"
                },
                {
                    id: 'propSubGridVariablesInButtonDelete',
                    title: '',
                    dataType: 'button',
                    width: '20%',
                    buttonLabel: 'Delete'.translate(),
                    onButtonClick: function (row, grid) {
                        grid.removeItem(row);
                        isDirty = true;
                    },
                    buttonStyle: {
                        cssClasses: [
                            'mafe-button-delete'
                        ]
                    }
                }
            ]
        });

        formVariablesPanelOut = new PMUI.core.Panel({
            id: "formVariablesPanelOut",
            layout: "hbox",
            fieldset: true,
            width: DEFAULT_WINDOW_WIDTH - 70
        });

        formVariablesPanelIn = new PMUI.core.Panel({
            id: "formVariablesPanelIn",
            layout: "hbox",
            fieldset: true,
            legend: "Variables In".translate(),
            width: DEFAULT_WINDOW_WIDTH - 70
        });

        validateVariable = function (field) {
            var value;
            field.controls[0].style.removeClasses(['error'])
            value = field.getValue();
            if (value == '') {
                PMDesigner.msgWinWarning('Please insert variable before adding to the list.'.translate());
                field.controls[0].style.addClasses(['error'])
                return false;
            }
            if (typeVariables.indexOf(String(value.substring(0, 2))) != -1) {
                return true;
            }
            PMDesigner.msgWinWarning("The value introduced doesn\'t have the correct format with a vallid prefix (@@, @#, @=, @&)".translate());
            field.controls[0].style.addClasses(['error'])
            return false;
        };

        formVarButtonAddOut = new PMUI.ui.Button({
            id: 'formVarButtonAddOut',
            text: 'Add'.translate(),
            width: 60,
            visible: false,
            buttonType: "success",
            buttonStyle: {
                cssClasses: [
                    'mafe-button-properties'
                ]
            },
            style: {
                cssProperties: {
                    padding: "5px"
                }
            },
            handler: function () {
                var gridOut, i;
                if (validateVariable(formVarTexOriginOut) && validateVariable(formVarTexTargetOut)) {
                    a = formVarTexOriginOut.getValue();
                    b = formVarTexTargetOut.getValue();
                    gridVariablesOut.addDataItem({origin: a, target: b});
                    formVarTexOriginOut.setValue("");
                    formVarTexTargetOut.setValue("");
                    isDirty = true;

                    gridOut = document.getElementById("propSubGridVariablesOut").getElementsByTagName("table")[0];
                    i = gridOut.rows.length - 1;
                    gridOut.getElementsByClassName("pmui pmui-button")[i].style.marginTop = "2px";
                }
            }
        });

        formVarButtonAddIn = new PMUI.ui.Button({
            id: 'formVarButtonAddIn',
            text: 'Add'.translate(),
            width: 60,
            buttonStyle: {
                cssClasses: [
                    'mafe-button-properties'
                ]
            },
            style: {
                cssProperties: {
                    padding: "5px",
                }
            },
            buttonType: 'success',
            handler: function () {
                var gridOut, i;
                if (validateVariable(formVarTexOriginIn) && validateVariable(formVarTexTargetIn)) {
                    a = formVarTexOriginIn.getValue();
                    b = formVarTexTargetIn.getValue();
                    gridVariablesIn.addDataItem({origin: a, target: b});
                    formVarTexOriginIn.setValue("");
                    formVarTexTargetIn.setValue("");
                    isDirty = true;

                    gridOut = document.getElementById("propSubGridVariablesIn").getElementsByTagName("table")[0];
                    i = gridOut.rows.length - 1;
                    gridOut.getElementsByClassName("pmui pmui-button")[i].style.marginTop = "2px";
                }
            }
        });

        labelVariablesOut = new PMUI.ui.TextLabel({
            textMode: 'plain',
            text: 'Variables Out'.translate()
        });

        labelVariablesIn = new PMUI.ui.TextLabel({
            textMode: 'plain',
            text: 'Variables In'.translate()
        });

        loadDropProcess = function () {
            formProperties.getField('out_doc_process').clearOptions();
            formProperties.getField('out_doc_process').addOption({
                label: "- Select a process -".translate(),
                value: "",
                disabled: true,
                selected: true
            });
        };
        loadDataServer = function () {
            var restClient = new PMRestClient({
                typeRequest: 'post',
                multipart: true,
                data: {
                    calls: [
                        {
                            url: 'projects',
                            method: 'GET'
                        },
                        {
                            url: 'project/' + PMDesigner.project.id + '/subprocess/' + activity.act_uid,
                            method: 'GET'
                        }
                    ]
                },
                functionSuccess: function (xhr, response) {
                    var dt,
                        itemsOut,
                        yout,
                        oout,
                        jout,
                        dout,
                        xin,
                        yin,
                        oin,
                        jin,
                        din,
                        itemsIn,
                        xout;

                    //projects
                    dt = response[0].response;
                    for (var i = 0; i < dt.length; i += 1) {
                        if (dt[i].prj_uid !== PMDesigner.project.projectId) {
                            formProperties.getField('out_doc_process').addOption({
                                label: dt[i].prj_name,
                                value: dt[i].prj_uid
                            });
                        }
                    }
                    //subprocess
                    dt = response[1].response;
                    formProperties.getField('out_doc_title').setValue(dt.spr_name);
                    formProperties.getField('out_doc_process').setValue(dt.spr_pro === '0' ? '' : dt.spr_pro);
                    formProperties.getField('out_doc_type').setValue(dt.spr_synchronous);

                    if (dt.spr_variables_out !== false) { //Asynchronous
                        xout = JSON.stringify(dt.spr_variables_out);
                        itemsOut = [];
                        if (xout != "[]") {
                            yout = xout.substring(1, xout.length - 1);
                            oout = yout.replace(/"/g, '');
                            jout = oout.split(',');
                            for (i = 0; i < jout.length; i += 1) {
                                dout = jout[i].split(':');
                                itemsOut.push({
                                    origin: dout[0],
                                    target: dout[1]
                                });
                            }
                        }
                        gridVariablesOut.setDataItems(itemsOut);
                        gridVariablesIn.setVisible(false);
                        formVariablesPanelIn.setVisible(false);
                    }

                    if (dt.spr_variables_in !== false && dt.spr_variables_in !== undefined) { //Synchronous
                        xin = JSON.stringify(dt.spr_variables_in);
                        itemsIn = [];
                        if (xin != "[]") {
                            yin = xin.substring(1, xin.length - 1);
                            oin = yin.replace(/"/g, '');
                            jin = oin.split(',');
                            for (i = 0; i < jin.length; i += 1) {
                                din = jin[i].split(':');
                                itemsIn.push({
                                    origin: din[0],
                                    target: din[1]
                                });
                            }
                        }
                        gridVariablesIn.setDataItems(itemsIn);
                    }

                    if (dt.spr_synchronous === "1") {
                        gridVariablesIn.setVisible(true);
                        formVariablesPanelIn.setVisible(true);
                    }
                    if (formProperties.getField('out_doc_process').getValue() !== '') {
                        loadActivity(formProperties.getField('out_doc_process').getValue());
                    }
                    formProperties.getField('spr_tas').setValue(dt.spr_tas === '0' ? '' : dt.spr_tas);
                },
                functionFailure: function (xhr, response) {
                    PMDesigner.msgWinError(response.error.message);
                }
            });
            restClient.setBaseEndPoint('');
            restClient.executeRestClient();
        };
        loadActivity = function (value) {
            var restClient = new PMRestClient({
                typeRequest: 'post',
                multipart: true,
                data: {
                    calls: [
                        {
                            url: 'project/' + value + '/starting-tasks',
                            method: 'GET'
                        }
                    ]
                },
                functionSuccess: function (xhr, response) {
                    var dropdown,
                        i,
                        dt = response[0].response;
                    dt = dt.sort(function (a, b) {
                        return a.act_name.toString().toLowerCase() > b.act_name.toString().toLowerCase();
                    });
                    dropdown = formProperties.getField('spr_tas');
                    dropdown.clearOptions();
                    dropdown.addOption({
                        value: '',
                        label: '- Select starting activity -'.translate()
                    });
                    for (i = 0; i < dt.length; i += 1) {
                        dropdown.addOption({
                            value: dt[i].act_uid,
                            label: dt[i].act_name
                        });
                    }
                },
                functionFailure: function (xhr, response) {
                    PMDesigner.msgWinError(response.error.message);
                }
            });
            restClient.setBaseEndPoint('');
            restClient.executeRestClient();
        };

        formVariablesPanelOut.addItem(formVarTexOriginOut);
        formVariablesPanelOut.addItem(formVarTexTargetOut);
        formVariablesPanelOut.addItem(formVarButtonAddOut);

        formVariablesPanelIn.addItem(formVarTexOriginIn);
        formVariablesPanelIn.addItem(formVarTexTargetIn);
        formVariablesPanelIn.addItem(formVarButtonAddIn);

        gridVariablesIn.setVisible(false);
        formVariablesPanelIn.setVisible(false);

        windowPropertiesSub.addItem(formProperties);
        windowPropertiesSub.addItem(new PMUI.ui.TextLabel({
            width: 890,
            text: 'Variables Out'.translate(),
            style: {
                cssProperties: {
                    background: '#aaaaaa',
                    margin: '-15px 10px 10px 15px',
                    color: 'white',
                    padding: '6px',
                    'font-weight': 'bold'
                }
            },
            display: 'block'
        }));
        windowPropertiesSub.addItem(formVariablesPanelOut);
        windowPropertiesSub.addItem(gridVariablesOut);

        windowPropertiesSub.addItem(new PMUI.ui.TextLabel({
            width: 890,
            text: 'Variables In'.translate(),
            style: {
                cssProperties: {
                    background: '#aaaaaa',
                    margin: '15px 10px 10px 15px',
                    color: 'white',
                    padding: '6px',
                    'font-weight': 'bold'
                }
            },
            display: 'block',
            visible: false
        }));

        windowPropertiesSub.addItem(formVariablesPanelIn);
        windowPropertiesSub.addItem(gridVariablesIn);

        loadDropProcess();
        gridVariablesOut.setDataItems("");
        gridVariablesIn.setDataItems("");
        loadDataServer();

        if (formProperties.getItems()[1].getValue() != "0") {
            formVarTexTargetOut.setProcess(formProperties.getItems()[1].getValue());
            formVarTexOriginIn.setProcess(formProperties.getItems()[1].getValue());
        }
        windowPropertiesSub.open();
        if (formProperties.getField('out_doc_process').getValue().trim().length === 0) {
            formVarTexTargetOut.buttonHTML.disable();
            formVarTexOriginIn.buttonHTML.disable();
        } else {
            formVarTexTargetOut.buttonHTML.enable();
            formVarTexOriginIn.buttonHTML.enable();
        }
        windowPropertiesSub.showFooter();
        applyStyleWindowForm(windowPropertiesSub);
        formVariablesPanelIn.getItems()[0].dom.labelTextContainer.style.display = "none";
        formVariablesPanelIn.getItems()[1].dom.labelTextContainer.style.display = "none";
        formVariablesPanelOut.getItems()[0].dom.labelTextContainer.style.display = "none";
        formVariablesPanelOut.getItems()[1].dom.labelTextContainer.style.display = "none";
        formVariablesPanelOut.style.addProperties({marginLeft: '45px'});
        gridVariablesOut.style.addProperties({marginLeft: '45px'});
        if (formVariablesPanelIn.visible == true) {
            gridVariablesIn.setVisible(true);
            gridVariablesIn.style.addProperties({marginLeft: '45px'});
            formVariablesPanelIn.setVisible(true);
            formVariablesPanelIn.getItems()[2].setWidth(80);
            $(formVariablesPanelIn.html).css({width: '850px', marginLeft: '45px'});
            windowPropertiesSub.getItems()[4].setVisible(true);
        }

        formVariablesPanelOut.style.addProperties({width: '870px'});
        $(gridVariablesIn.html).find(".pmui-gridpanel-footer").css("position", "static");
        $(gridVariablesIn.html).find(".pmui-gridpanel-footer").css("margin-top", "0px");
        $(gridVariablesOut.html).find(".pmui-gridpanel-footer").css("position", "static");
        $(gridVariablesOut.html).find(".pmui-gridpanel-footer").css("margin-top", "0px");
        windowPropertiesSub.defineEvents();
        gridVariablesOut.html.children[0].style.display = "none";
        gridVariablesIn.html.children[0].style.display = "none";
        formProperties.getField('out_doc_title').setFocus();
        formVarButtonAddOut.setWidth(80);
        formVarButtonAddOut.setVisible(true);

    };
}());