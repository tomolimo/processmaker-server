(function () {
    var openTinyInMainWindow,
        dataOutPutDocument,
        openFormInMainWindow,
        messageRequired;

    PMDesigner.output = function (event) {
        var winMainOutputDocument, formOutput, rowData, updateOutPut, restClient, isDirtyFormOutput, clickedClose = true, that = this,
            setDataRow,
            clearDataRow,
            getGridOutput,
            disableAllItems,
            deleteDataRow,
            updateOutput,
            refreshGridPanelInMainWindow,
            openGridPanelInMainWindow,
            openFormForEditInMainWindow,
            editorTiny,
            outputFormDocPdfSecurityOpen,
            docMargin,
            password,
            outputFormDocPdfSecurityOwner,
            outputFormDocPdfSecurityEnabled,
            btnCloseWindowOutputDoc,
            btnSaveWindowOutputDoc,
            btnCancelTiny,
            newButtonOutput,
            gridOutput,
            winMainOutputDocument,
            btnSaveTiny,
            listOutputDocs;


        setDataRow = function (row) {
            dataOutPutDocument = row.getData();
            rowData = row;
        };

        clearDataRow = function () {
            dataOutPutDocument = '';
            rowData = '';
        };

        isDirtyFormOutput = function () {
            var message_window;
            $("input,select,textarea").blur();
            if (formOutput.isVisible()) {
                if (formOutput.isDirty()) {
                    message_window = new PMUI.ui.MessageWindow({
                        id: "cancelMessageTriggers",
                        width: 490,
                        title: "Output Documents".translate(),
                        windowMessageType: "warning",
                        bodyHeight: 'auto',
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
                                    clearDataRow();
                                    message_window.close();
                                    if (clickedClose) {
                                        tinymce.EditorManager.execCommand('mceRemoveControl', true, 'tinyeditor');
                                        winMainOutputDocument.close();
                                    } else {
                                        clearDataRow();
                                        openGridPanelInMainWindow();
                                    }

                                },
                                buttonType: "success"
                            }
                        ]
                    });
                    message_window.open();
                    message_window.showFooter();
                } else {
                    clearDataRow();
                    if (clickedClose) {
                        tinymce.EditorManager.execCommand('mceRemoveControl', true, 'tinyeditor');
                        winMainOutputDocument.close()
                    } else {
                        openGridPanelInMainWindow();
                    }
                }
            } else {
                winMainOutputDocument.close();
            }
        };
        getGridOutput = function () {
            var restClientGet = new PMRestClient({
                endpoint: 'output-documents',
                typeRequest: 'get',
                functionSuccess: function (xhr, response) {
                    listOutputDocs = response;
                    gridOutput.setDataItems(listOutputDocs);
                    gridOutput.sort('out_doc_title', 'asc');
                },
                functionFailure: function (xhr, response) {
                    PMDesigner.msgWinError(response.error.message);
                },
                messageError: "There are problems getting the output documents, please try again.".translate()
            });
            restClientGet.executeRestClient();
        };

        disableAllItems = function () {
            winMainOutputDocument.hideFooter();
            formOutput.reset();

            winMainOutputDocument.getItems()[0].setVisible(false);
            winMainOutputDocument.getItems()[1].setVisible(false);
            for (var i = 0; i <= winMainOutputDocument.getItems()[1].getItems().length - 1; i += 1) {
                winMainOutputDocument.getItems()[1].getItems()[i].setVisible(false);
            }
            btnSaveWindowOutputDoc.setVisible(false);
            btnCloseWindowOutputDoc.setVisible(false);
            btnSaveTiny.setVisible(false);
            btnCancelTiny.setVisible(false);

            winMainOutputDocument.footer.getItems()[2].setVisible(false);
        };

        refreshGridPanelInMainWindow = function () {
            disableAllItems();
            winMainOutputDocument.getItems()[0].setVisible(true);
            winMainOutputDocument.setTitle("Output Documents".translate());
            getGridOutput();
        };

        openGridPanelInMainWindow = function () {
            disableAllItems();
            winMainOutputDocument.getItems()[0].setVisible(true);
            winMainOutputDocument.setTitle("Output Documents".translate());
            $(winMainOutputDocument.body).removeClass("pmui-background");
        };

        openFormInMainWindow = function () {
            disableAllItems();
            winMainOutputDocument.showFooter();
            winMainOutputDocument.getItems()[1].setVisible(true);
            for (var i = 0; i < winMainOutputDocument.getItems()[1].getItems().length; i += 1) {
                if (winMainOutputDocument.getItems()[1].getItems()[i].type !== "PMTinyField") {
                    winMainOutputDocument.getItems()[1].getItems()[i].setVisible(true);
                }
            }
            btnSaveWindowOutputDoc.setVisible(true);
            btnCloseWindowOutputDoc.setVisible(true);
            winMainOutputDocument.footer.getItems()[2].setVisible(true);
            password.setVisible(false);
            winMainOutputDocument.setTitle("Create Output Document".translate());
            winMainOutputDocument.setHeight(520);
            formOutput.panel.style.addProperties({padding: '20px 10px'});
            formOutput.setFocus();

        };

        openFormForEditInMainWindow = function (outputDocumentData) {
            disableAllItems();
            winMainOutputDocument.showFooter();
            btnSaveWindowOutputDoc.setVisible(true);
            btnCloseWindowOutputDoc.setVisible(true);
            winMainOutputDocument.footer.getItems()[1].setVisible(false);
            formOutput.setWidth(700);
            winMainOutputDocument.getItems()[1].setVisible(true);
            winMainOutputDocument.setTitle("Edit Output Document".translate());
            $(winMainOutputDocument.body).addClass("pmui-background");
            for (var i = 0; i < winMainOutputDocument.getItems()[1].getItems().length; i += 1) {
                if (winMainOutputDocument.getItems()[1].getItems()[i].type !== "PMTinyField") {
                    winMainOutputDocument.getItems()[1].getItems()[i].setVisible(true);
                }
            }

            password.setVisible(false);
            if (dataOutPutDocument != '' && dataOutPutDocument != undefined) {
                var dataEdit = formOutput.getFields();
                dataEdit[0].setValue(dataOutPutDocument['out_doc_title']);
                dataEdit[1].setValue(dataOutPutDocument['out_doc_filename']);
                dataEdit[2].setValue(dataOutPutDocument['out_doc_description']);
                dataEdit[3].setValue(dataOutPutDocument['out_doc_report_generator']);
                dataEdit[4].setValue(dataOutPutDocument['out_doc_media']);
                dataEdit[5].setValue(dataOutPutDocument['out_doc_landscape']);
                dataEdit[6].setValue(dataOutPutDocument['out_doc_left_margin']);
                dataEdit[7].setValue(dataOutPutDocument['out_doc_right_margin']);
                dataEdit[8].setValue(dataOutPutDocument['out_doc_top_margin']);
                dataEdit[9].setValue(dataOutPutDocument['out_doc_bottom_margin']);
                dataEdit[10].setValue(dataOutPutDocument['out_doc_generate']);

                if (dataOutPutDocument["out_doc_generate"] != "DOC") {
                    dataEdit[11].setVisible(true);
                } else {
                    dataEdit[11].setVisible(false);
                }

                dataEdit[11].setValue(dataOutPutDocument['out_doc_pdf_security_enabled']);
                if (dataOutPutDocument['out_doc_pdf_security_enabled'] != 0) {
                    password.setVisible(true);
                }
                dataEdit[12].setValue(dataOutPutDocument['out_doc_pdf_security_open_password']);
                dataEdit[13].setValue(dataOutPutDocument['out_doc_pdf_security_owner_password']);

                dataOutPutDocument['out_doc_pdf_security_permissions'] = dataOutPutDocument['out_doc_pdf_security_permissions'].split("|");
                dataEdit[14].setValue(JSON.stringify(dataOutPutDocument['out_doc_pdf_security_permissions']));

                dataEdit[15].setValue(dataOutPutDocument['out_doc_versioning']);
                dataEdit[16].setValue(dataOutPutDocument['out_doc_destination_path']);
                dataEdit[17].setValue(dataOutPutDocument['out_doc_tags']);
                dataEdit[18].setValue(dataOutPutDocument["out_doc_open_type"]);
            }
            winMainOutputDocument.setHeight(520);
            formOutput.panel.style.addProperties({padding: '20px 10px'});
            formOutput.setFocus();
        };

        openTinyInMainWindow = function (outputDocumentData) {
            //Fix for IE11
            var isIe11 = /Trident\/7\.0;.*rv\s*\:?\s*11/.test(navigator.userAgent);

            if (isIe11) {
                tinyMCE.isGecko = false;
            }

            //Set TinyMCE
            disableAllItems();
            winMainOutputDocument.showFooter();
            tinyEditorField = 13;
            winMainOutputDocument.getItems()[1].setVisible(true);
            winMainOutputDocument.getItems()[1].getItems()[tinyEditorField].setVisible(true);
            formOutput.setWidth(890);
            btnSaveTiny.setVisible(true);
            btnCancelTiny.setVisible(true);
            if (!editorTiny.isInitialized) {
                editorTiny.createHTML();
                editorTiny.setParameterTiny();
                editorTiny.isInitialized = true;
            } else {
                tinyMCE.execCommand('mceFocus', false, 'tinyeditor');
            }
            var dataEdit = formOutput.getFields();
            winMainOutputDocument.setTitle("Edit Output Document".translate());
            if (dataOutPutDocument != '' && dataOutPutDocument != undefined) {
                dataOutPutDocument['out_doc_template'] = (dataOutPutDocument['out_doc_template'] != null) ? dataOutPutDocument['out_doc_template'] : ' ';
                dataEdit[19].setValue(dataOutPutDocument['out_doc_template']);
                dataEdit[19].setValueTiny(dataOutPutDocument['out_doc_template']);
                dataEdit[19].setHeight(425);

                dataEdit[18].setVisible(false);
                dataEdit[19].setVisible(true);
            }
            formOutput.panel.style.addProperties({padding: '0px 10px'});
            winMainOutputDocument.setHeight(520);
            if (!editorTiny.isInitialized)
                tinymce.execCommand('mceFocus', false, 'tinyeditor');
        };

        deleteDataRow = function () {
            confirmWindow = new PMUI.ui.MessageWindow({
                id: "outputMessageWindowWarning",
                windowMessageType: 'warning',
                bodyHeight: 'auto',
                width: 490,
                title: "Output Documents".translate(),
                message: "Do you want to delete this Output Document?".translate(),
                footerItems: [
                    {
                        id: 'confirmWindowButtonNo',
                        text: "No".translate(),
                        visible: true,
                        handler: function () {
                            confirmWindow.close();
                        },
                        buttonType: "error"
                    }, {
                        id: 'confirmWindowButtonYes',
                        text: "Yes".translate(),
                        visible: true,
                        handler: function () {
                            var restClient;
                            confirmWindow.close();
                            restClient = new PMRestClient({
                                endpoint: "output-document/" + dataOutPutDocument.out_doc_uid,
                                typeRequest: 'remove',
                                functionSuccess: function (xhr, response) {
                                    refreshGridPanelInMainWindow();
                                },
                                functionFailure: function (xhr, response) {
                                    PMDesigner.msgWinError(response.error.message);
                                },
                                messageError: "There are problems deleting the OutputDocument, please try again.".translate(),
                                messageSuccess: 'Output Document deleted successfully'.translate(),
                                flashContainer: gridOutput
                            });
                            restClient.executeRestClient();
                        },
                        buttonType: "success"
                    },
                ]
            });
            confirmWindow.open();
            confirmWindow.dom.titleContainer.style.height = "17px";
            confirmWindow.showFooter();
        };

        updateOutput = function (data) {
            dataOutPutDocument = '';
            var restClientUpdate = new PMRestClient({
                endpoint: "output-document/" + data.out_doc_uid,
                typeRequest: 'update',
                data: data,
                functionSuccess: function (xhr, response) {
                    dataOutPutDocument = data;
                    refreshGridPanelInMainWindow();
                },
                functionFailure: function (xhr, response) {
                    PMDesigner.msgWinError(response.error.message);
                },
                messageError: "There are problems updating the OutputDocument, please try again.".translate(),
                messageSuccess: 'Output Document edited successfully'.translate(),
                flashContainer: gridOutput
            });
            restClientUpdate.executeRestClient();
        };

        editorTiny = new PMTinyField({
            id: 'outputEditorTiny',
            theme: "advanced",
            plugins: "advhr,advimage,advlink,advlist,autolink,autoresize,contextmenu,directionality,emotions,example,example_dependency,fullpage,fullscreen,iespell,inlinepopups,insertdatetime,layer,legacyoutput,lists,media,nonbreaking,noneditable,pagebreak,paste,preview,print,save,searchreplace,style,tabfocus,table,template,visualblocks,visualchars,wordcount,xhtmlxtras,pmSimpleUploader,pmVariablePicker,style",
            mode: "specific_textareas",
            editorSelector: "tmceEditor",
            widthTiny: DEFAULT_WINDOW_WIDTH - 58,
            heightTiny: DEFAULT_WINDOW_HEIGHT - 100,
            directionality: 'ltr',
            verifyHtml: false,
            themeAdvancedButtons1: "pmSimpleUploader,|,pmVariablePicker,|,bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull,|,fontselect,fontsizeselect,|,cut,copy,paste,|,bullist,numlist,|,outdent,indent,blockquote",
            themeAdvancedButtons2: "tablecontrols,|,undo,redo,|,link,unlink,image,|,forecolor,backcolor,styleprops,|,hr,removeformat,visualaid,|,sub,sup,|,ltr,rtl,|,code",
            popupCss: "/js/tinymce/jscripts/tiny_mce/themes/advanced/skins/default/dialogTinyBpmn.css",
            skin: "o2k7",
            skin_variant: "silver"
        });

        docMargin = new PMUI.form.FormPanel({
            fieldset: true,
            layout: 'hbox',
            legend: "Margin".translate(),
            items: [
                {
                    pmType: 'panel',
                    proportion: 0.7
                },
                {
                    pmType: "panel",
                    layout: 'vbox',
                    items: [
                        {
                            id: 'outputDocMarginLeft',
                            pmType: "text",
                            label: "Left".translate(),
                            required: true,
                            value: 20,
                            name: "out_doc_left_margin",
                            controlsWidth: 50,
                            labelWidth: '35%'
                        }, {
                            id: 'outputDocMarginRight',
                            pmType: "text",
                            label: "Right".translate(),
                            required: true,
                            value: 20,
                            name: "out_doc_right_margin",
                            controlsWidth: 50,
                            labelWidth: '35%'
                        }
                    ]
                },
                {
                    pmType: "panel",
                    layout: 'vbox',
                    proportion: 1.5,
                    items: [
                        {
                            id: 'outputDocMarginTop',
                            pmType: "text",
                            label: "Top".translate(),
                            required: true,
                            value: 20,
                            name: "out_doc_top_margin",
                            controlsWidth: 50,
                            labelWidth: '27%'
                        }, {
                            id: 'outputDocMarginBottom',
                            pmType: "text",
                            label: "Bottom".translate(),
                            required: true,
                            value: 20,
                            name: "out_doc_bottom_margin",
                            controlsWidth: 50,
                            labelWidth: '27%'
                        }
                    ]
                }
            ]
        });

        //Field "Open Password - Owner Password"
        outputFormDocPdfSecurityOpen = new PMUI.field.PasswordField({
            id: "outputFormDocPdfSecurityOpen",
            name: "out_doc_pdf_security_open_password",
            value: "",
            label: "Open Password ".translate(),
            required: true,
            controlsWidth: 300
        });

        outputFormDocPdfSecurityOwner = new PMUI.field.PasswordField({
            id: "outputFormDocPdfSecurityOwner",
            name: "out_doc_pdf_security_owner_password",
            value: "",
            label: "Owner Password ".translate(),
            required: true,
            controlsWidth: 300

        });

        password = new PMUI.form.FormPanel({
            width: 500,
            height: 130,
            fieldset: true,
            visible: false,
            legend: "",
            items: [
                {
                    pmType: "panel",
                    layout: 'vbox',
                    items: [
                        outputFormDocPdfSecurityOpen,
                        outputFormDocPdfSecurityOwner
                    ]
                },
                {
                    pmType: "panel",
                    layout: 'vbox',
                    items: [
                        {
                            id: 'outputFormDocPdfSecurityPermissions',
                            pmType: 'checkbox',
                            label: "Allowed Permissions".translate(),
                            value: '',
                            name: 'out_doc_pdf_security_permissions',
                            required: false,
                            controlPositioning: 'horizontal',
                            separator: "|",
                            maxDirectionOptions: 4,
                            options: [
                                {
                                    id: 'monday',
                                    label: "print".translate(),
                                    value: 'print'
                                },
                                {
                                    id: 'monday',
                                    label: "modify".translate(),
                                    value: 'modify'
                                },
                                {
                                    id: 'monday',
                                    label: "copy".translate(),
                                    value: 'copy'
                                },
                                {
                                    id: 'monday',
                                    label: "forms".translate(),
                                    value: 'forms'
                                }

                            ]
                        }
                    ]
                }
            ],
            layout: "vbox"
        });

        //Field "PDF security"
        outputFormDocPdfSecurityEnabled = new PMUI.field.DropDownListField({
            id: "outputDocDPFSecurity",
            name: "out_doc_pdf_security_enabled",
            label: "PDF security".translate(),
            labelWidth: "27%",
            valueType: "number",
            visible: false,

            options: [
                {
                    value: 0,
                    label: "Disabled".translate(),
                    selected: true
                },
                {
                    value: 1,
                    label: "Enabled".translate()

                }
            ],

            controlsWidth: 100,

            onChange: function (newValue, prevValue) {
                var visible = true;

                if (newValue == 0) {
                    visible = false;

                    outputFormDocPdfSecurityOpen.setValue("");
                    outputFormDocPdfSecurityOwner.setValue("");
                }

                password.setVisible(visible);
            }
        });

        //the form is 700px width, but with the tiny grows to 890
        formOutput = new PMUI.form.Form({
            id: 'outputForm',
            name: 'outputForm',
            fieldset: true,
            title: "",
            visibleHeader: false,
            width: DEFAULT_WINDOW_WIDTH - 43,
            items: [
                {
                    id: 'outputDocTitle',
                    pmType: "text",
                    name: 'out_doc_title',
                    label: "Title".translate(),
                    labelWidth: '27%',
                    controlsWidth: 300,
                    required: true
                },
                new CriteriaField({
                    id: 'outputDocFilenameGenerated',
                    pmType: "text",
                    name: 'out_doc_filename',
                    label: "Filename generated".translate(),
                    labelWidth: '27%',
                    controlsWidth: 300,
                    required: true
                }),
                {
                    id: 'outputDocDescription',
                    pmType: "textarea",
                    name: 'out_doc_description',
                    label: "Description".translate(),
                    labelWidth: '27%',
                    controlsWidth: 500,
                    rows: 100,
                    style: {cssClasses: ['mafe-textarea-resize']}
                },
                {
                    id: 'outputDocReportGenerator',
                    pmType: "dropdown",
                    name: 'out_doc_report_generator',
                    label: "Report Generator".translate(),
                    labelWidth: '27%',
                    require: true,
                    controlsWidth: 165,
                    options: [
                        {
                            label: "TCPDF".translate(),
                            value: "TCPDF"
                        },
                        {
                            label: "HTML2PDF (Old Version)".translate(),
                            value: "HTML2PDF"
                        }
                    ],
                    value: "TCPDF"
                },
                {
                    id: 'outputDocMedia',
                    pmType: "dropdown",
                    name: 'out_doc_media',
                    label: "Media".translate(),
                    labelWidth: '27%',
                    controlsWidth: 165,
                    options: [
                        {label: "Letter".translate(), value: "Letter"},
                        {label: "Legal".translate(), value: "Legal"},
                        {label: "Executive".translate(), value: "Executive"},
                        {label: "B5".translate(), value: "B5"},
                        {label: "Folio".translate(), value: "Folio"},
                        {label: "A0Oversize".translate(), value: "A0Oversize"},
                        {label: "A0".translate(), value: "A0"},
                        {label: "A1".translate(), value: "A1"},
                        {label: "A2".translate(), value: "A2"},
                        {label: "A3".translate(), value: "A3"},
                        {label: "A4".translate(), value: "A4"},
                        {label: "A5".translate(), value: "A5"},
                        {label: "A6".translate(), value: "A6"},
                        {label: "A7".translate(), value: "A7"},
                        {label: "A8".translate(), value: "A8"},
                        {label: "A9".translate(), value: "A9"},
                        {label: "A10", value: "A10"},
                        {label: "Screenshot640".translate(), value: "SH640"},
                        {label: "Screenshot800".translate(), value: "SH800"},
                        {label: "Screenshot1024".translate(), value: "SH1024"}
                    ]
                },
                {
                    id: 'outputDocOrientation',
                    pmType: "dropdown",
                    name: 'out_doc_landscape',
                    labelWidth: '27%',
                    label: "Orientation".translate(),
                    controlsWidth: 165,
                    options: [
                        {
                            label: "Portrait".translate(),
                            selected: true,
                            value: 0
                        },
                        {
                            label: "Landscape".translate(),
                            value: 1
                        }
                    ],
                    valueType: 'number'
                },
                docMargin,
                {
                    id: 'outputDocToGenerate',
                    pmType: "dropdown",
                    name: 'out_doc_generate',
                    controlsWidth: 70,
                    labelWidth: '27%',
                    label: "Output Document to Generate".translate(),
                    options: [
                        {
                            label: "Both".translate(),
                            value: "BOTH"
                        },
                        {
                            label: "Doc".translate(),
                            value: "DOC"
                        },
                        {
                            label: "Pdf".translate(),
                            value: "PDF"
                        }
                    ],
                    value: "BOTH",
                    onChange: function (newValue, prevValue) {
                        if (newValue == "DOC") {
                            formOutput.getFields()[11].setVisible(false);
                            outputFormDocPdfSecurityEnabled.setVisible(false);
                            outputFormDocPdfSecurityEnabled.setValue(0);
                            password.setVisible(false);
                            outputFormDocPdfSecurityOpen.setValue("");
                            outputFormDocPdfSecurityOwner.setValue("");
                        } else {
                            formOutput.getFields()[11].setVisible(true);
                        }
                    }
                },
                outputFormDocPdfSecurityEnabled,
                password,
                {
                    id: 'outputDocEnableVersioning',
                    pmType: "dropdown",
                    name: "out_doc_versioning",
                    controlsWidth: 70,
                    labelWidth: '27%',
                    label: 'Enable versioning'.translate(),
                    options: [
                        {
                            label: "Yes".translate(),
                            value: 1
                        },
                        {
                            label: "No".translate(),
                            selected: true,
                            value: 0
                        }
                    ],
                    valueType: 'number'
                },
                new CriteriaField({
                    id: 'outputDocDestinationPath',
                    pmType: "text",
                    name: "out_doc_destination_path",
                    labelWidth: '27%',
                    label: "Destination Path".translate(),
                    controlsWidth: 340
                }),
                new CriteriaField({
                    id: 'outputDocTags',
                    pmType: "text",
                    name: "out_doc_tags",
                    labelWidth: '27%',
                    label: "Tags".translate(),
                    controlsWidth: 340
                }),
                {
                    id: "outputDocGenerateFileLink",
                    name: "cboByGeneratedFile",
                    pmType: "dropdown",
                    controlsWidth: 155,
                    labelWidth: "27%",
                    label: "By clicking on the generated file link".translate(),

                    options: [
                        {
                            value: 0,
                            label: "Open the file".translate()
                        },
                        {
                            label: "Download the file".translate(),
                            value: 1,
                            selected: true
                        }
                    ],

                    valueType: "number"
                }
            ],
            style: {
                cssProperties: {
                    marginLeft: '20px'
                }
            }
        });

        formOutput.style.addProperties({marginLeft: '20px'});
        gridOutput = new PMUI.grid.GridPanel({
            id: 'gridOutput',
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
                    id: 'gridOutputButtonShow',
                    title: '',
                    dataType: 'button',
                    buttonLabel: 'Show ID'.translate(),
                    columnData: "out_doc_uid",
                    buttonStyle: {
                        cssClasses: [
                            'mafe-button-show'
                        ]
                    },
                    onButtonClick: function (row, grid) {
                        var data = row.getData();
                        showUID(data.out_doc_uid);
                    }
                },
                {
                    title: 'Title'.translate(),
                    dataType: 'string',
                    width: '392px',
                    alignment: "left",
                    columnData: "out_doc_title",
                    sortable: true,
                    alignmentCell: 'left'
                },
                {
                    title: 'Type'.translate(),
                    dataType: 'string',
                    width: '100px',
                    alignmentCell: 'left',
                    columnData: "out_doc_type",
                    sortable: true
                },
                {
                    id: 'gridOutputButtonEdit',
                    title: '',
                    dataType: 'button',
                    buttonStyle: {
                        cssClasses: [
                            'mafe-button-edit'
                        ]
                    },
                    buttonLabel: 'Edit'.translate(),
                    onButtonClick: function (row, grid) {
                        messageRequired.hide();
                        setDataRow(row);
                        openFormForEditInMainWindow();
                    }
                },
                {
                    id: 'gridOutputButtonProperties',
                    title: '',
                    dataType: 'button',
                    buttonStyle: {
                        cssClasses: [
                            'mafe-button-properties'
                        ]
                    },
                    buttonLabel: 'Open Editor'.translate(),
                    onButtonClick: function (row, grid) {
                        setDataRow(row);
                        openTinyInMainWindow(row);
                    }
                },
                {
                    id: 'gridOutputButtonDelete',
                    title: '',
                    dataType: 'button',
                    buttonStyle: {
                        cssClasses: [
                            'mafe-button-delete'
                        ]
                    },
                    buttonLabel: 'Delete'.translate(),
                    onButtonClick: function (row, grid) {
                        setDataRow(row);
                        deleteDataRow();
                    }
                }
            ]
        });

        btnSaveWindowOutputDoc = new PMUI.ui.Button({
            id: 'btnSaveWindowOutputDoc',
            text: "Save".translate(),
            handler: function () {
                var dataAux = getData2PMUI(formOutput.html);
                if (dataAux.out_doc_title != "" && dataAux.out_doc_filename != "") {
                    if ((navigator.userAgent.indexOf("MSIE") != -1) || (navigator.userAgent.indexOf("Trident") != -1)) {
                        itemOutPut = getData2PMUI(formOutput.html);
                    } else {
                        itemOutPut = formOutput.getData();
                    }

                    itemOutPut['out_doc_type'] = "HTML";

                    var items = jQuery.parseJSON(itemOutPut['out_doc_pdf_security_permissions']);
                    itemOutPut['out_doc_pdf_security_permissions'] = '';
                    for (var i = 0; i < items.length; i += 1) {
                        itemOutPut['out_doc_pdf_security_permissions'] += (i == 0) ? items[i] : '|' + items[i];
                    }

                    itemOutPut["out_doc_landscape"] = parseInt(itemOutPut["out_doc_landscape"]);
                    itemOutPut["out_doc_pdf_security_enabled"] = parseInt(itemOutPut["out_doc_pdf_security_enabled"]);
                    itemOutPut["out_doc_versioning"] = parseInt(itemOutPut["out_doc_versioning"]);
                    itemOutPut["out_doc_open_type"] = parseInt(getData2PMUI(formOutput.html).cboByGeneratedFile);

                    if (dataOutPutDocument != '' && dataOutPutDocument != undefined) {
                        itemOutPut['out_doc_uid'] = dataOutPutDocument.out_doc_uid;
                        restClient = new PMRestClient({
                            endpoint: "output-document/" + dataOutPutDocument.out_doc_uid,
                            typeRequest: 'update',
                            data: itemOutPut,
                            functionSuccess: function (xhr, response) {
                                dataOutPutDocument = itemOutPut;
                                refreshGridPanelInMainWindow();
                            },
                            functionFailure: function (xhr, response) {
                                PMDesigner.msgWinError(response.error.message);
                            },
                            messageError: "There are problems updating the OutputDocument, please try again.".translate(),
                            messageSuccess: 'Output Document edited successfully'.translate(),
                            flashContainer: gridOutput
                        });
                        restClient.executeRestClient();
                    } else {
                        if (1 === parseInt(dataAux.out_doc_pdf_security_enabled) && (dataAux.out_doc_pdf_security_open_password.trim() === "" || dataAux.out_doc_pdf_security_owner_password.trim() === "")) {
                            password.getItems()[0].getItems()[0].isValid();
                            password.getItems()[0].getItems()[1].isValid();
                            return false;
                        }
                        itemOutPut['out_doc_uid'] = '';
                        restClient = new PMRestClient({
                            endpoint: "output-document",
                            typeRequest: 'post',
                            data: itemOutPut,
                            functionSuccess: function (xhr, response) {
                                dataOutPutDocument = itemOutPut;
                                refreshGridPanelInMainWindow();
                            },
                            functionFailure: function (xhr, response) {
                                PMDesigner.msgWinError(response.error.message);
                            },
                            messageError: "There are problems saved the OutputDocument, please try again.".translate(),
                            messageSuccess: 'Output Document saved successfully'.translate(),
                            flashContainer: gridOutput
                        });
                        restClient.executeRestClient();
                    }
                    clearDataRow();
                } else {
                    formOutput.getField("out_doc_title").isValid();
                    formOutput.getField("out_doc_filename").isValid();
                }
            },
            buttonType: 'success'
        });

        btnCloseWindowOutputDoc = new PMUI.ui.Button({
            id: 'btnCloseWindowOutputDoc',
            text: "Cancel".translate(),
            handler: function () {
                clickedClose = false;
                isDirtyFormOutput();
            },
            buttonType: 'error'
        });

        newButtonOutput = new PMUI.ui.Button({
            id: 'outputButtonNew',
            text: 'Create'.translate(),
            height: "36px",
            width: 100,
            style: {
                cssClasses: [
                    'mafe-button-create'
                ]
            },
            handler: function () {
                clearDataRow();
                openFormInMainWindow();
            }
        });

        btnCancelTiny = new PMUI.ui.Button({
            id: 'btnCloseTiny',
            text: 'Cancel'.translate(),
            handler: function () {
                /*if (typeof dataOutPutDocument['externalType'] != 'undefined' && dataOutPutDocument['externalType']) {
                 winMainOutputDocument.close();
                 return;
                 }*/
                PMDesigner.hideAllTinyEditorControls();
                clickedClose = false;
                isDirtyFormOutput();
            },
            buttonType: 'error'
        });

        btnSaveTiny = new PMUI.ui.Button({
            id: 'btnSaveTiny',
            text: 'Save'.translate(),
            handler: function () {
                PMDesigner.hideAllTinyEditorControls();
                dataOutPutDocument['out_doc_template'] = tinyMCE.activeEditor.getContent();
                updateOutput(dataOutPutDocument);
                if (typeof dataOutPutDocument['externalType'] != 'undefined' && dataOutPutDocument['externalType']) {
                    winMainOutputDocument.close();
                    return;
                }
                clearDataRow();
                refreshGridPanelInMainWindow();
            },
            buttonType: 'success'
        });

        winMainOutputDocument = new PMUI.ui.Window({
            id: "winMainOutputDocument",
            title: "Output Documents".translate(),
            height: DEFAULT_WINDOW_HEIGHT,
            width: DEFAULT_WINDOW_WIDTH,
            buttonPanelPosition: "bottom",
            onBeforeClose: function () {
                PMDesigner.hideAllTinyEditorControls();
                clickedClose = true;
                isDirtyFormOutput();
            },
            footerItems: [
                btnCancelTiny,
                btnSaveTiny,
                btnCloseWindowOutputDoc,
                btnSaveWindowOutputDoc]
        });

        formOutput.addItem(editorTiny);
        formOutput.footer.setVisible(false);

        winMainOutputDocument.addItem(gridOutput);
        winMainOutputDocument.addItem(formOutput);

        refreshGridPanelInMainWindow();

        validateKeysField(docMargin.getField('out_doc_left_margin').getControls()[0].getHTML(), ['isbackspace', 'isnumber']);
        validateKeysField(docMargin.getField('out_doc_right_margin').getControls()[0].getHTML(), ['isbackspace', 'isnumber']);
        validateKeysField(docMargin.getField('out_doc_top_margin').getControls()[0].getHTML(), ['isbackspace', 'isnumber']);
        validateKeysField(docMargin.getField('out_doc_bottom_margin').getControls()[0].getHTML(), ['isbackspace', 'isnumber']);

        if (typeof listOutputDocs !== "undefined") {
            winMainOutputDocument.open();
            $('#gridOutput .pmui-textcontrol').css({'margin-top': '5px', width: '250px'});
            messageRequired = $(document.getElementById("requiredMessage"));
            applyStyleWindowForm(winMainOutputDocument);

            editorTiny.isInitialized = false;
            winMainOutputDocument.footer.html.style.textAlign = 'right';

            gridOutput.dom.toolbar.appendChild(newButtonOutput.getHTML());
            newButtonOutput.defineEvents();
            winMainOutputDocument.defineEvents();
            disableAllItems();
            winMainOutputDocument.getItems()[0].setVisible(true);
        }
    };

    PMDesigner.output.showTiny = function (uid) {
        getItemdOutput = function () {
            var restClientGet = new PMRestClient({
                endpoint: 'output-document/' + uid,
                typeRequest: 'get',
                functionSuccess: function (xhr, response) {
                    dataOutPutDocument = response;
                },
                functionFailure: function (xhr, response) {
                    PMDesigner.msgWinError(response.error.message);
                },
                messageError: "There are problems getting the output documents, please try again.".translate()
            });
            restClientGet.executeRestClient();
        };
        getItemdOutput();
        dataOutPutDocument['externalType'] = true;
        openTinyInMainWindow(dataOutPutDocument);
    };

    PMDesigner.output.create = function () {
        openFormInMainWindow();
    };
}());
