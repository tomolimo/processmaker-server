/**
 * Input Document module
 * @param options
 * @constructor
 */
var InputDocument = function (options) {
    this.winMainInputDocument = null;
    this.externalType = false;
    this.inputDocumentOriginDataForUpdate = {};
    this.windowDialog = null;
    this.assignAccordion = null;
    this.clickedClose = true;
    this.onUpdateInputDocumentHandler = null;
    InputDocument.prototype.init.call(this, options);
};
/**
 * set close modulo InputDocument constructor
 * @param handler, the callback function
 */
InputDocument.prototype.setOnUpdateInputDocumentHandler = function (handler) {
    if (typeof handler === "function") {
        this.onUpdateInputDocumentHandler = handler;
    }
    return this;
};
/**
 * constructor
 * @param options
 */
InputDocument.prototype.init = function (options) {
    var defaults = {
        onUpdateInputDocumentHandler: null
    };
    $.extend(true, defaults, options);
    this.setOnUpdateInputDocumentHandler(defaults.onUpdateInputDocumentHandler);
    return this;
};

/**
 * Puts input document using rest proxy, to update data
 * @param inputDocumentUid
 * @param data
 */
InputDocument.prototype.inputDocumentPutRestProxy = function (inputDocumentUid, data) {
    var that = this,
        restProxy;

    restProxy = new PMRestClient({
        endpoint: "input-document/" + inputDocumentUid,
        typeRequest: 'update',
        data: data,
        functionSuccess: function (xhr, response) {
            var message;
            if (!that.externalType) {
                if (typeof flagInputDocument != 'undefined' && flagInputDocument) {
                    that.winMainInputDocument.close();
                    return;
                }
                that.inputDocumentsGetRestProxy();
                that.openGridPanelInMainWindow();
            } else {
                that.winMainInputDocument.close();
                message = new PMUI.ui.FlashMessage({
                    message: 'Input Document edited correctly.'.translate(),
                    duration: 3000,
                    severity: 'success',
                    appendTo: that.windowDialog
                });
                message.show();
            }
            if (typeof that.onUpdateInputDocumentHandler === "function") {
                that.onUpdateInputDocumentHandler(data, that);
            }
        },
        functionFailure: function (xhr, response) {
            PMDesigner.msgWinError(response.error.message);
        },
        messageSuccess: 'Input Document updated successfully'.translate(),
        flashContainer: that.grdpnlInputDocument
    });
    restProxy.executeRestClient();
};
/**
 * Deletes an input document
 * @param inputDocumentUid
 */
InputDocument.prototype.inputDocumentDeleteRestProxy = function (inputDocumentUid) {
    var that = this,
        restProxy;
    restProxy = new PMRestClient({
        endpoint: "input-document/" + inputDocumentUid,
        typeRequest: 'remove',
        functionSuccess: function (xhr, response) {
            that.inputDocumentsGetRestProxy();
        },
        functionFailure: function (xhr, response) {
            PMDesigner.msgWinError(response.error.message);
        },
        messageSuccess: 'Input Document deleted successfully'.translate(),
        flashContainer: that.grdpnlInputDocument
    });
    restProxy.executeRestClient();
};
/**
 * Creates an input document using rest proxy
 * @param data
 */
InputDocument.prototype.inputDocumentPostRestProxy = function (data) {
    var that = this,
        restProxy;
    restProxy = new PMRestClient({
        endpoint: "input-document",
        typeRequest: 'post',
        data: data,
        functionSuccess: function (xhr, response) {
            that.inputDocumentsGetRestProxy();
            that.openGridPanelInMainWindow();
        },
        functionFailure: function (xhr, response) {
            PMDesigner.msgWinError(response.error.message);
        },
        messageSuccess: 'Input Document saved successfully'.translate(),
        flashContainer: that.grdpnlInputDocument
    });
    restProxy.executeRestClient();
};
/**
 * Gets all input documents to populate the grid
 */
InputDocument.prototype.inputDocumentsGetRestProxy = function () {
    var that = this,
        restProxy = new PMRestClient({
            endpoint: 'input-documents',
            typeRequest: 'get',
            functionSuccess: function (xhr, response) {
                that.grdpnlInputDocument.setDataItems(response);
                that.grdpnlInputDocument.sort('inp_doc_title', 'asc');
                if (PMVariables.prototype.isWindowActive()) {
                    PMVariables.prototype.setInputDocumentsFromIDModule(PMUI.getPMUIObject(inp_doc_uid), response);
                }
            },
            functionFailure: function (xhr, response) {
                PMDesigner.msgWinError(response.error.message);
            }
        });
    restProxy.executeRestClient();
};
/**
 * Gets an specific input document data to edit it
 * @param inputDocumentUid
 */
InputDocument.prototype.inputDocumentFormGetProxy = function (inputDocumentUid) {
    var that = this,
        restProxy;
    restProxy = new PMRestClient({
        endpoint: "input-document/" + inputDocumentUid,
        typeRequest: 'get',
        functionSuccess: function (xhr, response) {
            var data = response;
            that.method = "PUT";
            that.openFormForEditInMainWindow(data);
        },
        functionFailure: function (xhr, response) {
            PMDesigner.msgWinError(response.error.message);
        }
    });
    restProxy.executeRestClient();
};

/**
 * Open Edit form and sets to main windows
 * @param inputDocumentData
 */
InputDocument.prototype.openFormForEditInMainWindow = function (inputDocumentData) {
    var that = this;

    that.inputDocumentOriginDataForUpdate = inputDocumentData;
    that.inputDocumentUid = inputDocumentData.inp_doc_uid;
    that.frmInputDocument.getField('inp_doc_original').setValue("");
    that.winMainInputDocument.setTitle("Edit Input Document".translate());

    that.frmInputDocument.getField('inp_doc_title').setValue(inputDocumentData.inp_doc_title);
    that.frmInputDocument.getField('inp_doc_description').setValue(inputDocumentData.inp_doc_description);
    that.frmInputDocument.getField('inp_doc_form_needed').setValue(inputDocumentData.inp_doc_form_needed);
    that.frmInputDocument.getField('inp_doc_original').setValue(inputDocumentData.inp_doc_original);

    if (inputDocumentData.inp_doc_form_needed !== "VIRTUAL") {
        that.frmInputDocument.getField('inp_doc_original').setVisible(true);
    } else {
        that.frmInputDocument.getField('inp_doc_original').setVisible(false);
    }
    that.frmInputDocument.getField('inp_doc_versioning').setValue(parseInt(inputDocumentData.inp_doc_versioning + ""));
    that.frmInputDocument.getField('inp_doc_destination_path').setValue(inputDocumentData.inp_doc_destination_path);
    that.frmInputDocument.getField('inp_doc_tags').setValue(inputDocumentData.inp_doc_tags);
    that.frmInputDocument.getField('inp_doc_type_file').setValue(inputDocumentData.inp_doc_type_file);
    that.frmInputDocument.getField('inp_doc_max_filesize').setValue(inputDocumentData.inp_doc_max_filesize);
    that.frmInputDocument.getField('inp_doc_max_filesize_unit').setValue(inputDocumentData.inp_doc_max_filesize_unit);
};
/**
 * Open the grid panel and sets to mai windows
 */
InputDocument.prototype.openGridPanelInMainWindow = function () {
    this.grdpnlInputDocument.setVisible(true);
    this.frmInputDocument.setVisible(false);
    this.winMainInputDocument.setTitle("Input Documents".translate());
    this.winMainInputDocument.hideFooter();
};
/**
 * Set the option external type of input Document
 */
InputDocument.prototype.setExternalType = function (value) {
    if (typeof value === "boolean") {
        this.externalType = value;
    }
    return this;
};
/**
 * Set the option window Dialog, a window that open this class
 */
InputDocument.prototype.setWindowDialog = function (value) {
    this.windowDialog = value;
    return this;
};

InputDocument.prototype.setAssignAccordion = function (obj) {
    this.assignAccordion = obj;
};

/**
 * Open create form an sets to main windows
 */
InputDocument.prototype.openFormInMainWindow = function () {
    this.grdpnlInputDocument.setVisible(false);
    this.frmInputDocument.setVisible(true);
    this.winMainInputDocument.setTitle("Create Input Document".translate());
    this.winMainInputDocument.showFooter();
};

InputDocument.prototype.checkIfValuesAreEqual = function (initialData, finalData) {
    var key1, key2;
    if (!Object.keys(initialData).length && Object.keys(finalData).length) {
        if (finalData['inp_doc_title'] != '' || finalData['inp_doc_form_needed'] != 'VIRTUAL' || finalData['inp_doc_description'] != '' || finalData['inp_doc_versioning'] != '0' || finalData['inp_doc_destination_path'] != '') {
            return false;
        }
    }

    for (key1 in initialData) {
        for (key2 in finalData) {
            if (typeof(initialData[key1]) != "undefined" &&
                typeof(finalData[key2]) != "undefined" &&
                key1 == key2 &&
                initialData[key1] != finalData[key2]
            ) {
                //Return
                return false;
            }
        }
    }
    return true;
};

/**
 * Generate all ui components(window, form, grid, fields)
 */

InputDocument.prototype.isDirtyFormInput = function () {
    $("input,select,textarea").blur();
    var that = this, message_window;
    if (this.frmInputDocument.isVisible()) {
        if (!this.externalType) {
            if (!(this.checkIfValuesAreEqual(this.inputDocumentOriginDataForUpdate, getData2PMUI(this.frmInputDocument.html)))) {
                message_window = new PMUI.ui.MessageWindow({
                    id: "cancelMessageTriggers",
                    width: 490,
                    title: "Input Documents".translate(),
                    windowMessageType: 'warning',
                    bodyHeight: 'auto',
                    message: 'Are you sure you want to discard your changes?'.translate(),
                    footerItems: [
                        {
                            text: 'No'.translate(),
                            handler: function () {
                                message_window.close();
                            },
                            buttonType: "error"
                        }, {
                            text: 'Yes'.translate(),
                            handler: function () {
                                message_window.close();
                                if (typeof flagInputDocument != 'undefined' && flagInputDocument) {
                                    if (that.clickedClose) {
                                        message_window.close();
                                        that.winMainInputDocument.close();
                                        return;
                                    } else {
                                        that.winMainInputDocument.close();
                                        flagInputDocument = false;
                                    }
                                    return;
                                } else {
                                    if (that.clickedClose) {
                                        message_window.close();
                                        that.winMainInputDocument.close();
                                        return;
                                    } else {
                                        inputDocumentOption = "";
                                        that.openGridPanelInMainWindow();
                                    }
                                }
                            },
                            buttonType: "success"
                        }
                    ]
                });
                message_window.open();
                message_window.showFooter();
                this.inputDocumentOriginDataForUpdate = {};
            } else {
                if (that.clickedClose) {
                    this.winMainInputDocument.close();
                } else {
                    inputDocumentOption = "";
                    this.openGridPanelInMainWindow();
                }
            }
        } else {
            that.winMainInputDocument.close();
        }
    } else {
        that.winMainInputDocument.close();
    }
};

InputDocument.prototype.build = function () {
    var ID = this,
        btnSave,
        btnCancel,
        winMainInputDocument,
        frmInputDocument,
        grdpnlInputDocument,
        btnNew,
        inp_doc_destination_path,
        inp_doc_tags,
        that = this;

    btnSave = new PMUI.ui.Button({
        id: "btnSave",
        text: "Save".translate(),
        handler: function () {
            var flagAux, data;

            if (!ID.frmInputDocument.isValid()) {
                flagAux = ID.frmInputDocument.visible;
            } else {
                flagAux = ID.frmInputDocument.isValid();
            }

            if (flagAux) {
                if (getData2PMUI(ID.frmInputDocument.html).inp_doc_title == "") {
                    return false;
                }
            }

            if (ID.frmInputDocument.getField("inp_doc_max_filesize").getValue() != "") {
                if (!/^\+?(0|[1-9]\d*)$/.test(ID.frmInputDocument.getField("inp_doc_max_filesize").getValue())) {
                    return false;
                }
            }

            //validation because getData method do not work in IE
            if (navigator.userAgent.indexOf("MSIE") !== -1 || navigator.userAgent.indexOf("Trident") !== -1) {
                data = getData2PMUI(that.frmInputDocument.html);
            } else {
                data = that.frmInputDocument.getData();
            }

            data["inp_doc_versioning"] = parseInt(data["inp_doc_versioning"]);

            switch (that.method) {
                case "POST":
                    that.inputDocumentPostRestProxy(data);
                    break;
                case "PUT":
                    that.inputDocumentPutRestProxy(that.inputDocumentUid, data);
                    break;
            }
        },
        buttonType: 'success'
    });

    btnCancel = new PMUI.ui.Button({
        id: "btnCancel",
        text: "Cancel".translate(),
        handler: function () {
            that.clickedClose = false;
            that.isDirtyFormInput();
        },
        buttonType: 'error'
    });
    btnNew = new PMUI.ui.Button({
        id: "btnNew",
        text: "Create".translate(),
        height: "36px",
        width: 100,
        style: {
            cssClasses: [
                'mafe-button-create'
            ]
        },
        handler: function () {
            that.openFormInMainWindow();
            that.method = "POST";
            that.frmInputDocument.reset();
        }
    });

    /* form panel*/
    this.frmInputDocument = new PMUI.form.Form({
        id: "frmInputDocument",
        width: 900,
        visibleHeader: false,
        items: [
            {
                pmType: "text",
                label: "Title".translate(),
                id: "inputDocTitle",
                name: "inp_doc_title",
                valueType: 'string',
                maxLength: 200,
                required: true,
                controlsWidth: 300
            },
            {
                pmType: "dropdown",
                name: "inp_doc_form_needed",
                id: "inputDocDocumentType",
                label: "Document Type".translate(),
                value: "VIRTUAL",
                controlsWidth: 130,
                options: [
                    {
                        value: "VIRTUAL",
                        label: "Digital".translate()
                    },
                    {
                        value: "REAL",
                        label: "Printed".translate()
                    },
                    {
                        value: "VREAL",
                        label: "Digital/Printed".translate()
                    }
                ],
                onChange: function (newValue, prevValue) {
                    var fields = that.frmInputDocument.getFields();
                    if (newValue != "VIRTUAL") {
                        fields[2].setVisible(true);
                    } else {
                        fields[2].setVisible(false);
                    }
                }
            },
            {
                pmType: "dropdown",
                id: "inputDocDocumentType",
                name: "inp_doc_original",
                label: "Format".translate(),
                value: "ORIGINAL",
                controlsWidth: 105,
                visible: false,
                options: [
                    {
                        value: "ORIGINAL",
                        label: "Original".translate()
                    },
                    {
                        value: "COPYLEGAL",
                        label: "Legal Copy".translate()
                    },
                    {
                        value: "COPY",
                        label: "Copy".translate()
                    }
                ]
            },
            {
                pmType: "textarea",
                id: "inputDocDescription",
                name: "inp_doc_description",
                label: "Description".translate(),
                controlsWidth: 380,
                rows: 100,
                style: {cssClasses: ['mafe-textarea-resize']}
            },
            {
                pmType: "dropdown",
                id: "inputDocEnableVersioning",
                name: "inp_doc_versioning",
                label: "Enable Versioning".translate(),
                value: 0,
                controlsWidth: 75,
                options: [
                    {
                        value: 0,
                        label: "NO".translate(),
                        selected: true
                    },
                    {
                        value: 1,
                        label: "YES".translate()
                    }
                ]
            }
        ],
        buttonPanelPosition: 'top'
    });
    inp_doc_destination_path = new CriteriaField({
        id: "inputDocDestinationPath",
        name: "inp_doc_destination_path",
        valueType: "string",
        label: "Destination Path".translate(),
        maxLength: 200,
        controlsWidth: 380
    });

    inp_doc_tags = new CriteriaField({
        id: "inputDocTags",
        name: "inp_doc_tags",
        valueType: "string",
        label: "Tags".translate(),
        maxLength: 200,
        value: "INPUT",
        controlsWidth: 380
    });

    inp_doc_allowed = new PMUI.field.TextField({
        label: "Allowed file extensions (Use .* to allow any extension)".translate(),
        id: "inputDocAllowedFileExtensions",
        name: "inp_doc_type_file",
        valueType: 'string',
        maxLength: 200,
        required: true,
        value: ".*",
        controlsWidth: 380

    });

    inp_doc_maximum = new PMUI.field.TextField({
        label: "Maximum file size (Use zero if unlimited)".translate(),
        id: "inputDocMaximumFileSize",
        name: "inp_doc_max_filesize",
        valueType: 'string',
        maxLength: 200,
        required: true,
        value: "0",
        controlsWidth: 380,
        validators: [
            {
                pmType: "regexp",
                criteria: /^\d*$/,
                errorMessage: "Please enter a positive integer value".translate()
            }
        ]

    });

    inp_doc_maximum_unit = new PMUI.field.DropDownListField({
        id: "inputDocUnit",
        name: "inp_doc_max_filesize_unit",
        label: "Unit".translate(),
        value: "ORIGINAL",
        controlsWidth: 105,
        visible: true,
        options: [
            {
                value: "KB",
                label: "KB".translate()
            },
            {
                value: "MB",
                label: "MB".translate()
            }
        ]
    });

    that.frmInputDocument.addItem(inp_doc_destination_path);
    that.frmInputDocument.addItem(inp_doc_tags);
    that.frmInputDocument.addItem(inp_doc_allowed);
    that.frmInputDocument.addItem(inp_doc_maximum);
    that.frmInputDocument.addItem(inp_doc_maximum_unit);

    /*grid panel*/

    this.grdpnlInputDocument = new PMUI.grid.GridPanel({
        id: "grdpnlInputDocument",
        pageSize: 10,
        width: "96%",
        style: {
            cssClasses: ["mafe-gridPanel"]
        },
        filterPlaceholder: 'Search ...'.translate(),
        emptyMessage: 'No records found'.translate(),
        nextLabel: 'Next'.translate(),
        previousLabel: 'Previous'.translate(),
        tableContainerHeight: 374,
        customStatusBar: function (currentPage, pageSize, numberItems, criteria, filter) {
            return messagePageGrid(currentPage, pageSize, numberItems, criteria, filter);
        },
        columns: [
            {
                id: 'grdpnlInputDocumentButtonShow',
                dataType: "button",
                title: "",
                buttonLabel: "Show ID".translate(),
                buttonStyle: {
                    cssClasses: [
                        'mafe-button-show'
                    ]
                },
                onButtonClick: function (row, grid) {
                    var data = row.getData();
                    showUID(data.inp_doc_uid);
                }
            },
            {
                columnData: "inp_doc_title",
                title: "Title".translate(),
                width: "607px",
                alignment: "left",
                sortable: true,
                alignmentCell: 'left'
            },
            {
                id: 'grdpnlInputDocumentButtonEdit',
                dataType: "button",
                title: "",
                buttonLabel: "Edit".translate(),
                buttonStyle: {
                    cssClasses: [
                        'mafe-button-edit'
                    ]
                },
                onButtonClick: function (row, grid) {
                    var data = row.getData();
                    that.inputDocumentOriginDataForUpdate = {};
                    that.openFormInMainWindow();
                    that.inputDocumentFormGetProxy(data.inp_doc_uid);

                }
            },
            {
                id: 'grdpnlInputDocumentButtonDelete',
                dataType: "button",
                title: "",
                buttonLabel: "Delete".translate(),
                buttonStyle: {
                    cssClasses: [
                        'mafe-button-delete'
                    ]
                },
                onButtonClick: function (row, grid) {
                    var data = row.getData(),
                        confirmWindow;
                    confirmWindow = new PMUI.ui.MessageWindow({
                        id: "inputMessageWindowWarning",
                        windowMessageType: 'warning',
                        width: 490,
                        bodyHeight: 'auto',
                        title: "Input Documents".translate(),
                        message: "Do you want to delete this Input Document?".translate(),
                        footerItems: [
                            {
                                id: 'confirmWindowButtonNo',
                                text: "No".translate(),
                                visible: true,
                                handler: function () {
                                    confirmWindow.close();
                                },
                                buttonType: "error"
                            },
                            {
                                id: 'confirmWindowButtonYes',
                                text: "Yes".translate(),
                                visible: true,
                                handler: function () {
                                    that.inputDocumentDeleteRestProxy(data.inp_doc_uid);
                                    confirmWindow.close();
                                    confirmWindow.close();
                                },
                                buttonType: "success"
                            }
                        ]
                    });
                    confirmWindow.open();
                    confirmWindow.dom.titleContainer.style.height = "17px";
                    confirmWindow.showFooter();
                }
            }
        ],
        dataItems: null
    });
    /* main windows */
    this.winMainInputDocument = new PMUI.ui.Window({
        id: "winMainInputDocument",
        title: "Input Documents".translate(),
        height: DEFAULT_WINDOW_HEIGHT,
        width: DEFAULT_WINDOW_WIDTH,
        buttonPanelPosition: 'bottom',
        footerAlign: 'right',
        visibleFooter: true,
        footerItems: [
            btnCancel,
            btnSave
        ],
        onBeforeClose: function () {
            that.clickedClose = true;
            that.isDirtyFormInput();
        }
    });
    // add form and grid to windows
    this.winMainInputDocument.addItem(this.grdpnlInputDocument);
    this.winMainInputDocument.addItem(this.frmInputDocument);
    this.winMainInputDocument.open();
    //custom css
    this.frmInputDocument.getField("inp_doc_type_file").html.style.padding = "6px 0";
    this.frmInputDocument.getField("inp_doc_type_file").html.style.float = "left";
    this.frmInputDocument.getField("inp_doc_max_filesize").html.style.padding = "6px 0";
    this.frmInputDocument.getField("inp_doc_max_filesize").html.style.float = "left";
    this.frmInputDocument.getField("inp_doc_max_filesize_unit").html.style.padding = "6px 0";
    this.frmInputDocument.getField("inp_doc_max_filesize_unit").html.style.float = "left";
    this.grdpnlInputDocument.setVisible(true);
    this.frmInputDocument.setVisible(false);
    this.winMainInputDocument.hideFooter();
    /* insert create button to grid*/
    this.grdpnlInputDocument.dom.toolbar.appendChild(btnNew.getHTML());
    btnNew.defineEvents();
    this.inputDocumentsGetRestProxy();
};
