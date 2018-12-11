(
    function () {
        var dataBaseConnectionOption,
            winFrmDataBaseConnectionShow,
            frmDataBaseConnection,
            closeClicked = true;

        PMDesigner.database = function (event) {
            var winGrdpnlDataBaseConnection,
                panelTest,
                grdpnlDataBaseConnection,
                flagError,
                titleOld,
                btnNew,
                btnTestConnection,
                btnCreate,
                btnCancel,
                testShow,
                isDirtyFrmDataBaseConnection,
                showForm,
                listDBConnection,
                refreshGridPanelInMainWindow,
                dataBaseConnectionsGetRestProxy,
                dataBaseConnectionPostTestRestProxy,
                dataBaseConnectionGetRestProxy,
                dataBaseConnectionPutRestProxy,
                dataBaseConnectionDeleteRestProxy,
                dataBaseConnectionPostRestProxy,
                cboEngineSetOptionsRestProxy,
                showEncoderOptions,
                hideEncoderOptions,
                cboConnectionTypeOracleSetOptions,
                cboEncodeSetOptionsRestProxy,
                winFrmDataBaseConnectionShow,
                cboEngine,
                txtUID,
                cboEncode,
                txtTns,
                txtServer,
                txtDataBaseName,
                disableAllItems,
                txtUsername,
                txtPassword,
                txtPort,
                btnBack,
                txtDescription,
                cboConnectionTypeOracle,
                dataBaseConnectionData;

            disableAllItems = function () {
                winGrdpnlDataBaseConnection.getItems()[0].setVisible(false);
                winGrdpnlDataBaseConnection.getItems()[1].setVisible(false);
                winGrdpnlDataBaseConnection.getItems()[2].setVisible(false);

                txtUID.setVisible(false);
                btnTestConnection.setVisible(false);
                btnBack.setVisible(false);
                btnCreate.setVisible(false);
                btnCancel.setVisible(false);
            };

            testShow = function (testData) {
                var msg = "", titleSummary, style, i, flag;
                flagError = 0;
                disableAllItems();
                titleOld = winGrdpnlDataBaseConnection.getTitle();
                winGrdpnlDataBaseConnection.setTitle("Testing Server Connection".translate());
                winGrdpnlDataBaseConnection.getItems()[2].setVisible(true);

                for (i = 0; i <= testData.length - 1; i += 1) {
                    flag = (typeof(testData[i].error) != "undefined") ? 1 : 0;
                    if (flag != 1) {
                        msg = msg + "<img src = \"/images/select-icon.png\" width=\"17\" height=\"17\" style=\"margin-right: 0.9em;\" />" + testData[i].test + "<br />";
                    } else {
                        msg = msg + "<img src = \"/images/error.png\" width=\"21\" height=\"21\" style=\"margin-right: 0.6em;\" />" + testData[i].error + "<br />";
                    }

                    if (typeof(testData[i].error) != "undefined" && flagError == 0) {
                        flagError = 1;
                    }
                }
                if (flag == 0) {
                    btnCreate.setVisible(true);
                    btnBack.setVisible(true);
                } else {
                    btnBack.setVisible(true);
                }

                titleSummary = "<div style=\"margin: 1em 1em 0.5em 1em; padding: 0 2em 0 2em; border: 2px solid #AFC5D0; background:#D7ECF1; font: bold 1em arial; text-align: center; width: " + (DEFAULT_WINDOW_WIDTH - 22) + ";\">Testing Database Server configuration</div>";

                style = $('#panelTest').attr("style");
                titleSummary = titleSummary + "<div style=\"margin-left: 0.5em; padding: 0.5em; height: 235px;\">" + msg + "</div>";
                $('#panelTest').empty();
                style = style + ' background: #FFFFFF; font: normal 0.8em arial;';
                $('#panelTest').attr("style", style);
                $('#panelTest').append(titleSummary);
            };

            isDirtyFrmDataBaseConnection = function () {
                var message_window;
                $("input,select,textarea").blur();
                if (frmDataBaseConnection.isVisible()) {
                    if (frmDataBaseConnection.isDirty()) {
                        message_window = new PMUI.ui.MessageWindow({
                            id: "cancelMessageTriggers",
                            windowMessageType: 'warning',
                            width: 490,
                            title: "Database Connections".translate(),
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
                                        dataBaseConnectionOption = "";
                                        if (closeClicked) {
                                            winGrdpnlDataBaseConnection.close();
                                        }
                                        refreshGridPanelInMainWindow(false);
                                    },
                                    buttonType: "success"
                                }
                            ],
                            title: 'Confirm'.translate()
                        });
                        message_window.open();
                        message_window.showFooter();
                    } else {
                        if (closeClicked) {
                            winGrdpnlDataBaseConnection.close();
                        } else {
                            dataBaseConnectionOption = "";
                            refreshGridPanelInMainWindow(false);
                        }
                    }
                } else {
                    winGrdpnlDataBaseConnection.close();
                }
            };
            showForm = function () {
                disableAllItems();
                winGrdpnlDataBaseConnection.setTitle(titleOld);
                winGrdpnlDataBaseConnection.getItems()[1].setVisible(true);
                btnTestConnection.setVisible(true);
                btnCancel.setVisible(true);
            }

            refreshGridPanelInMainWindow = function (load) {
                disableAllItems();
                winGrdpnlDataBaseConnection.hideFooter();
                dataBaseConnectionOption = "";
                winGrdpnlDataBaseConnection.getItems()[0].setVisible(true);
                winGrdpnlDataBaseConnection.setTitle("Database Connections".translate());
                load = load != null ? load : true;
                if (load) {
                    dataBaseConnectionsGetRestProxy(grdpnlDataBaseConnection);
                }
            };

            dataBaseConnectionsGetRestProxy = function (grdpnl) {
                var restProxy = new PMRestClient({
                    endpoint: "database-connections",
                    typeRequest: "get",
                    functionSuccess: function (xhr, response) {
                        listDBConnection = response;
                        grdpnl.setDataItems(listDBConnection);
                        grdpnl.sort('dbs_database_name', 'asc');
                    },
                    functionFailure: function (xhr, response) {
                        PMDesigner.msgWinError(response.error.message);
                    }
                });
                restProxy.executeRestClient();
            };

            dataBaseConnectionPostTestRestProxy = function (data) {
                var restProxy = new PMRestClient({
                    endpoint: "database-connection/test",
                    typeRequest: "post",
                    data: data,
                    functionSuccess: function (xhr, response) {
                        testShow(response);
                    },
                    functionFailure: function (xhr, response) {
                        PMDesigner.msgWinError(response.error.message);
                    }
                });
                restProxy.executeRestClient();
            };

            dataBaseConnectionGetRestProxy = function (dataBaseConnectionUid) {
                var restProxy = new PMRestClient({
                    endpoint: "database-connection/" + dataBaseConnectionUid,
                    typeRequest: "get",
                    functionSuccess: function (xhr, response) {
                        var data = response;
                        dataBaseConnectionOption = "PUT";
                        winFrmDataBaseConnectionShow("PUT", data);
                    },
                    functionFailure: function (xhr, response) {
                        PMDesigner.msgWinError(response.error.message);
                    }
                });
                restProxy.executeRestClient();
            };

            dataBaseConnectionPutRestProxy = function (dataBaseConnectionUid, data) {
                var restProxy = new PMRestClient({
                    endpoint: "database-connection/" + dataBaseConnectionUid,
                    typeRequest: "update",
                    data: data,
                    functionSuccess: function (xhr, response) {
                        refreshGridPanelInMainWindow();
                    },
                    functionFailure: function (xhr, response) {
                        PMDesigner.msgWinError(response.error.message);
                    },
                    messageSuccess: 'Database connection edited successfully'.translate(),
                    flashContainer: grdpnlDataBaseConnection
                });

                restProxy.executeRestClient();
            };

            dataBaseConnectionDeleteRestProxy = function (dataBaseConnectionUid) {
                var restProxy = new PMRestClient({
                    endpoint: "database-connection/" + dataBaseConnectionUid,
                    typeRequest: "remove",
                    functionSuccess: function (xhr, response) {
                        refreshGridPanelInMainWindow();
                    },
                    functionFailure: function (xhr, response) {
                        PMDesigner.msgWinError(response.error.message);
                    },
                    messageSuccess: 'Database connection deleted successfully'.translate(),
                    flashContainer: grdpnlDataBaseConnection
                });
                restProxy.executeRestClient();
            };

            dataBaseConnectionPostRestProxy = function (data) {
                var restProxy = new PMRestClient({
                    endpoint: "database-connection",
                    typeRequest: "post",
                    data: data,
                    functionSuccess: function (xhr, response) {
                        refreshGridPanelInMainWindow();
                    },
                    functionFailure: function (xhr, response) {
                        PMDesigner.msgWinError(response.error.message);

                        refreshGridPanelInMainWindow();
                    },
                    messageError: 'An unexpected error while deleting the DB Connection, please try again later.'.translate(),
                    messageSuccess: 'Database connection saved successfully'.translate(),
                    flashContainer: grdpnlDataBaseConnection
                });

                restProxy.executeRestClient();
            };

            cboEngineSetOptionsRestProxy = function (cboEngine, cboEncode) {
                cboEngine.clearOptions();
                var restProxy = new PMRestClient({
                    typeRequest: "get",
                    functionSuccess: function (xhr, response) {
                        var data = response,
                            i,
                            arrayOptions = [];
                        for (i = 0; i <= data.length - 1; i += 1) {
                            arrayOptions.push(
                                {
                                    value: data[i].id,
                                    label: data[i].name
                                }
                            );
                        }
                        cboEngine.setOptions(arrayOptions);
                        cboEngine.setValue(arrayOptions[0].value);
                        cboEncodeSetOptionsRestProxy(cboEngine.getValue(), cboEncode);
                    },
                    functionFailure: function (xhr, response) {
                        PMDesigner.msgWinError(response.error.message);
                    }
                });

                restProxy.setBaseEndPoint("system/db-engines");
                restProxy.executeRestClient();
            };

            hideEncoderOptions = function () {
                cboEncode.setVisible(true);
                cboConnectionTypeOracle.setVisible(true);
            };

            showEncoderOptions = function () {
                cboEncode.setVisible(true);
                cboConnectionTypeOracle.setVisible(false);
            };
            cboConnectionTypeOracleSetOptions = function (cboConnectionTypeOracle) {
                var arrayEnconde = [],
                    i,
                    arrayOptions = [];
                cboConnectionTypeOracle.clearOptions();

                arrayEnconde = [
                    {"value": "NORMAL", "text": "Normal"},
                    {"value": "TNS", "text": "TNS"}
                ];

                for (i = 0; i <= arrayEnconde.length - 1; i += 1) {
                    arrayOptions.push(
                        {
                            value: arrayEnconde[i].value,
                            label: arrayEnconde[i].text
                        }
                    );
                }

                cboConnectionTypeOracle.setOptions(arrayOptions);
            };
            cboEncodeSetOptionsRestProxy = function (selectedCboEngineValue, cboEncode) {
                var arrayEnconde = [],
                    arrayNewEnconde,
                    i,
                    portsDefault,
                    arrayOptions,
                    encode = selectedCboEngineValue;
                cboEncode.clearOptions();

                arrayEnconde["mysql"] = [
                    {"value": "big5", "text": "big5 - Big5 Traditional Chinese"},
                    {"value": "dec8", "text": "dec8 - DEC West European"},
                    {"value": "cp850", "text": "cp850 - DOS West European"},
                    {"value": "hp8", "text": "hp8 - HP West European"},
                    {"value": "koi8r", "text": "koi8r - KOI8-R Relcom Russian"},
                    {"value": "latin1", "text": "latin1 - cp1252 West European"},
                    {"value": "latin2", "text": "latin2 - ISO 8859-2 Central European"},
                    {"value": "swe7", "text": "swe7 - 7bit Swedish"},
                    {"value": "ascii", "text": "ascii - US ASCII"},
                    {"value": "ujis", "text": "ujis - EUC-JP Japanese"},
                    {"value": "sjis", "text": "sjis - Shift-JIS Japanese"},
                    {"value": "hebrew", "text": "hebrew - ISO 8859-8 Hebrew"},
                    {"value": "tis620", "text": "tis620 - TIS620 Thai"},
                    {"value": "euckr", "text": "euckr - EUC-KR Korean"},
                    {"value": "koi8u", "text": "koi8u - KOI8-U Ukrainian"},
                    {"value": "gb2312", "text": "gb2312 - GB2312 Simplified Chinese"},
                    {"value": "greek", "text": "greek - ISO 8859-7 Greek"},
                    {"value": "cp1250", "text": "cp1250 - Windows Central European"},
                    {"value": "gbk", "text": "gbk - GBK Simplified Chinese"},
                    {"value": "latin5", "text": "latin5 - ISO 8859-9 Turkish"},
                    {"value": "armscii8", "text": "armscii8 - ARMSCII-8 Armenian"},
                    {"value": "utf8", "text": "utf8 - UTF-8 Unicode"},
                    {"value": "ucs2", "text": "ucs2 - UCS-2 Unicode"},
                    {"value": "cp866", "text": "cp866 - DOS Russian"},
                    {"value": "keybcs2", "text": "keybcs2 - DOS Kamenicky Czech-Slovak"},
                    {"value": "macce", "text": "macce - Mac Central European"},
                    {"value": "macroman", "text": "macroman - Mac West European"},
                    {"value": "cp852", "text": "cp852 - DOS Central European"},
                    {"value": "latin7", "text": "atin7 - ISO 8859-13 Baltic"},
                    {"value": "cp1251", "text": "cp1251 - Windows Cyrillic"},
                    {"value": "cp1256", "text": "cp1256  - Windows Arabic"},
                    {"value": "cp1257", "text": "cp1257  - Windows Baltic"},
                    {"value": "binary", "text": "binary  - Binary pseudo charset"},
                    {"value": "geostd8", "text": "geostd8 - GEOSTD8 Georgian"},
                    {"value": "cp932", "text": "cp932] - SJIS for Windows Japanese"},
                    {"value": "eucjpms", "text": "eucjpms - UJIS for Windows Japanese"}
                ];

                arrayEnconde["pgsql"] = [
                    {"value": "BIG5", "text": "BIG5"},
                    {"value": "EUC_CN", "text": "EUC_CN"},
                    {"value": "EUC_JP", "text": "EUC_JP"},
                    {"value": "EUC_KR", "text": "EUC_KR"},
                    {"value": "EUC_TW", "text": "EUC_TW"},
                    {"value": "GB18030", "text": "GB18030"},
                    {"value": "GBK", "text": "GBK"},
                    {"value": "ISO_8859_5", "text": "ISO_8859_5"},
                    {"value": "ISO_8859_6", "text": "ISO_8859_6"},
                    {"value": "ISO_8859_7", "text": "ISO_8859_7"},
                    {"value": "ISO_8859_8", "text": "ISO_8859_8"},
                    {"value": "JOHAB", "text": "JOHAB"},
                    {"value": "KOI8", "text": "KOI8"},
                    {"value": "selected", "text": "LATIN1"},
                    {"value": "LATIN2", "text": "LATIN2"},
                    {"value": "LATIN3", "text": "LATIN3"},
                    {"value": "LATIN4", "text": "LATIN4"},
                    {"value": "LATIN5", "text": "LATIN5"},
                    {"value": "LATIN6", "text": "LATIN6"},
                    {"value": "LATIN7", "text": "LATIN7"},
                    {"value": "LATIN8", "text": "LATIN8"},
                    {"value": "LATIN9", "text": "LATIN9"},
                    {"value": "LATIN10", "text": "LATIN10"},
                    {"value": "SJIS", "text": "SJIS"},
                    {"value": "SQL_ASCII", "text": "SQL_ASCII"},
                    {"value": "UHC", "text": "UHC"},
                    {"value": "UTF8", "text": "UTF8"},
                    {"value": "WIN866", "text": "WIN866"},
                    {"value": "WIN874", "text": "WIN874"},
                    {"value": "WIN1250", "text": "WIN1250"},
                    {"value": "WIN1251", "text": "WIN1251"},
                    {"value": "WIN1252", "text": "WIN1252"},
                    {"value": "WIN1256", "text": "WIN1256"},
                    {"value": "WIN1258", "text": "WIN1258"}
                ];

                arrayEnconde["mssql"] = [
                    {"value": "utf8", "text": "utf8 - UTF-8 Unicode"}
                ];

                arrayEnconde["oracle"] = [
                    {"value": "UTF8", "text": "UTF8 - Unicode 3.0 UTF-8 Universal character set, CESU-8 compliant"},
                    {"value": "UTFE", "text": "UTFE - EBCDIC form of Unicode 3.0 UTF-8 Universal character set"},
                    {"value": "AL16UTF16", "text": "AL16UTF16 - Unicode 3.1 UTF-16 Universal character set"},
                    {"value": "AL32UTF8", "text": "AL32UTF8 - Unicode 3.1 UTF-8 Universal character set"}
                ];

                arrayEnconde["sqlsrv"] = [
                    {"value": "utf8", "text": "utf8 - UTF-8 Unicode"}
                ];

                arrayNewEnconde = (typeof(arrayEnconde[encode]) != "undefined") ? arrayEnconde[encode] : [];
                arrayOptions = [];

                for (i = 0; i <= arrayNewEnconde.length - 1; i += 1) {
                    arrayOptions.push(
                        {
                            value: arrayNewEnconde[i].value,
                            label: arrayNewEnconde[i].text
                        }
                    );
                }

                cboEncode.setOptions(arrayOptions);
                cboEncode.setValue('utf8');

                portsDefault = ["3306", "5432", "1433", "1521"];
                switch (encode) {
                    case "mysql":
                        txtPort.setValue(portsDefault[0]);
                        showEncoderOptions();
                        break;
                    case "pgsql":
                        txtPort.setValue(portsDefault[1]);
                        showEncoderOptions();
                        break;
                    case "mssql":
                        txtPort.setValue(portsDefault[2]);
                        showEncoderOptions();
                        break;
                    case "oracle":
                        txtPort.setValue(portsDefault[3]);
                        hideEncoderOptions();
                        break;
                }
            };

            winFrmDataBaseConnectionShow = function (option, data) {
                disableAllItems();
                dataBaseConnectionData = data;
                frmDataBaseConnection.reset();
                cboEngineSetOptionsRestProxy(cboEngine, cboEncode);
                cboConnectionTypeOracleSetOptions(cboConnectionTypeOracle);
                winGrdpnlDataBaseConnection.getItems()[1].setVisible(true);
                btnTestConnection.setVisible(true);
                btnCancel.setVisible(true);
                winGrdpnlDataBaseConnection.showFooter();

                switch (option) {
                    case "POST":
                        winGrdpnlDataBaseConnection.setTitle("Create Database Connection".translate());
                        frmDataBaseConnection.setTitle("");
                        txtTns.setVisible(false);
                        txtTns.setRequired(false);
                        txtServer.setVisible(true);
                        txtServer.setRequired(true);
                        txtDataBaseName.setVisible(true);
                        txtDataBaseName.setRequired(true);
                        txtPort.setVisible(true);
                        txtPort.setRequired(true);
                        txtPort.setValue("3306");
                        break;
                    case "PUT":
                        winGrdpnlDataBaseConnection.setTitle("Edit Database Connection".translate());
                        frmDataBaseConnection.setTitle("");
                        txtUID.setVisible(true);
                        txtUID.setValue(dataBaseConnectionData.dbs_uid);
                        cboEngine.setValue(dataBaseConnectionData.dbs_type);
                        cboEncodeSetOptionsRestProxy(dataBaseConnectionData.dbs_type, cboEncode);
                        cboEncode.setValue(dataBaseConnectionData.dbs_encode);
                        txtServer.setValue(dataBaseConnectionData.dbs_server);
                        txtDataBaseName.setValue(dataBaseConnectionData.dbs_database_name);
                        txtUsername.setValue(dataBaseConnectionData.dbs_username);
                        txtPassword.setValue(dataBaseConnectionData.dbs_password);
                        txtPort.setValue(dataBaseConnectionData.dbs_port);
                        txtDescription.setValue(dataBaseConnectionData.dbs_description);

                        if (dataBaseConnectionData.dbs_type == "oracle") {
                            cboConnectionTypeOracle.setValue(dataBaseConnectionData.dbs_connection_type);
                            cboConnectionTypeOracle.setVisible(true);

                            if (dataBaseConnectionData.dbs_connection_type == "TNS" && dataBaseConnectionData.dbs_tns != "") {
                                txtTns.setValue(dataBaseConnectionData.dbs_tns);
                                txtTns.setVisible(true);
                                txtTns.setRequired(true);

                                txtServer.setValue("");
                                txtServer.setVisible(false);
                                txtServer.setRequired(false);

                                txtDataBaseName.setValue("");
                                txtDataBaseName.setVisible(false);
                                txtDataBaseName.setRequired(false);

                                txtPort.setValue("");
                                txtPort.setVisible(false);
                                txtPort.setRequired(false);
                            } else {
                                txtTns.setValue("");
                                txtTns.setVisible(false);
                                txtTns.setRequired(false);

                                txtServer.setVisible(true);
                                txtServer.setRequired(true);

                                txtDataBaseName.setVisible(true);
                                txtDataBaseName.setRequired(true);

                                txtPort.setVisible(true);
                                txtPort.setRequired(true);
                            }
                        } else {
                            txtTns.setValue("");
                            txtTns.setVisible(false);
                            txtTns.setRequired(false);

                            txtServer.setVisible(true);
                            txtServer.setRequired(true);

                            txtDataBaseName.setVisible(true);
                            txtDataBaseName.setRequired(true);

                            txtPort.setVisible(true);
                            txtPort.setRequired(true);
                        }
                        break;
                }
                frmDataBaseConnection.setFocus();
            };

            txtUID = new PMUI.field.TextField({
                id: "txtUID",
                name: "txtUID",
                valueType: "string",
                controlsWidth: 300,
                label: "UID".translate(),
                maxLength: 200,
                readOnly: true,
                visible: false
            });

            cboEngine = new PMUI.field.DropDownListField({
                id: "cboEngine",
                name: "cboEngine",
                label: "Engine".translate(),
                options: null,
                controlsWidth: 150,
                onChange: function (newValue, prevValue) {
                    if (cboEngine.getValue() == "oracle") {
                        cboConnectionTypeOracleSetOptions(cboConnectionTypeOracle);
                    } else {
                        txtTns.setVisible(false);
                        txtTns.setRequired(false);

                        txtServer.setVisible(true);
                        txtServer.setRequired(true);

                        txtDataBaseName.setVisible(true);
                        txtDataBaseName.setRequired(true);

                        txtPort.setVisible(true);
                        txtPort.setRequired(true);
                    }

                    cboEncodeSetOptionsRestProxy(cboEngine.getValue(), cboEncode);
                }
            });

            cboEncode = new PMUI.field.DropDownListField({
                id: "cboEncode",
                name: "cboEncode",
                label: "Encode".translate(),
                options: null,
                controlsWidth: 300
            });

            cboConnectionTypeOracle = new PMUI.field.DropDownListField({
                id: "cboConnectionTypeOracle",
                name: "cboConnectionTypeOracle",
                label: "Select Connection Type".translate(),
                options: null,
                controlsWidth: 300,

                onChange: function (newValue, prevValue) {
                    if (cboConnectionTypeOracle.getValue() != "NORMAL") {
                        txtTns.setVisible(true);
                        txtTns.setRequired(true);

                        txtServer.setVisible(false);
                        txtServer.setRequired(false);

                        txtDataBaseName.setVisible(false);
                        txtDataBaseName.setRequired(false);

                        txtPort.setVisible(false);
                        txtPort.setRequired(false);
                    } else {
                        txtTns.setVisible(false);
                        txtTns.setRequired(false);

                        txtServer.setVisible(true);
                        txtServer.setRequired(true);

                        txtDataBaseName.setVisible(true);
                        txtDataBaseName.setRequired(true);

                        txtPort.setValue("1521");
                        txtPort.setVisible(true);
                        txtPort.setRequired(true);
                    }
                }
            });

            txtTns = new PMUI.field.TextField({
                id: "txtTns",
                name: "txtTns",
                valueType: "txtTns",
                controlsWidth: 300,
                label: "TNS".translate(),
                maxLength: 200,
                visible: false,
                required: true
            });

            txtServer = new PMUI.field.TextField({
                id: "txtServer",
                name: "txtServer",
                valueType: "string",
                controlsWidth: 300,
                label: "Server".translate(),
                maxLength: 200,
                required: true
            });

            txtDataBaseName = new PMUI.field.TextField({
                id: "txtDataBaseName",
                name: "txtDataBaseName",
                valueType: "string",
                controlsWidth: 300,
                label: "Database Name".translate(),
                maxLength: 200,
                required: true
            });

            txtUsername = new PMUI.field.TextField({
                id: "txtUsername",
                name: "txtUsername",
                valueType: "string",
                controlsWidth: 300,
                label: "Username".translate(),
                maxLength: 200,
                required: true
            });

            txtPassword = new PMUI.field.PasswordField({
                id: "txtPassword",
                name: "txtPassword",
                valueType: "string",
                controlsWidth: 300,
                label: "Password".translate(),
                maxLength: 200
            });

            txtPort = new PMUI.field.TextField({
                id: "txtPort",
                name: "txtPort",
                valueType: "string",
                controlsWidth: 300,
                label: "Port".translate(),
                value: "3306",
                controlsWidth: 300,
                maxLength: 200,
                required: true
            });

            txtDescription = new PMUI.field.TextAreaField({
                id: "txtDescription",
                name: "txtDescription",
                valueType: "string",
                controlsWidth: 500,
                label: "Description".translate(),
                height: "200px",
                style: {cssClasses: ['mafe-textarea-resize']}
            });

            btnTestConnection = new PMUI.ui.Button({
                id: "btnTestConnection",
                text: "Test Connection".translate(),
                buttonType: 'success',
                handler: function () {
                    var data;
                    if (frmDataBaseConnection.isValid()) {
                        if (cboEngine.getValue() == "oracle") {
                            data = {
                                dbs_type: cboEngine.getValue(),
                                dbs_encode: cboEncode.getValue(),
                                dbs_connection_type: cboConnectionTypeOracle.getValue(),
                                dbs_tns: txtTns.getValue(),
                                dbs_server: txtServer.getValue(),
                                dbs_database_name: txtDataBaseName.getValue(),
                                dbs_username: txtUsername.getValue(),
                                dbs_password: getData2PMUI(frmDataBaseConnection.html).txtPassword,
                                dbs_port: parseInt(getData2PMUI(frmDataBaseConnection.html).txtPort),
                                dbs_description: txtDescription.getValue()
                            };
                        } else {
                            data = {
                                dbs_type: cboEngine.getValue(),
                                dbs_encode: cboEncode.getValue(),
                                dbs_server: txtServer.getValue(),
                                dbs_database_name: txtDataBaseName.getValue(),
                                dbs_username: txtUsername.getValue(),
                                dbs_password: getData2PMUI(frmDataBaseConnection.html).txtPassword,
                                dbs_port: parseInt(getData2PMUI(frmDataBaseConnection.html).txtPort),
                                dbs_description: txtDescription.getValue()
                            };
                        }
                        dataBaseConnectionPostTestRestProxy(data);
                    }
                }
            });

            btnCreate = new PMUI.ui.Button({
                id: "btnCreate",
                text: "Save".translate(),
                buttonType: "success",
                handler: function () {
                    var data;
                    if (cboEngine.getValue() == "oracle") {
                        data = {
                            dbs_type: cboEngine.getValue(),
                            dbs_encode: cboEncode.getValue(),
                            dbs_connection_type: cboConnectionTypeOracle.getValue(),
                            dbs_tns: txtTns.getValue(),
                            dbs_server: txtServer.getValue(),
                            dbs_database_name: txtDataBaseName.getValue(),
                            dbs_username: txtUsername.getValue(),
                            dbs_password: getData2PMUI(frmDataBaseConnection.html).txtPassword,
                            dbs_port: parseInt(getData2PMUI(frmDataBaseConnection.html).txtPort),
                            dbs_description: txtDescription.getValue()
                        };
                    } else {
                        data = {
                            dbs_type: cboEngine.getValue(),
                            dbs_encode: cboEncode.getValue(),
                            dbs_server: txtServer.getValue(),
                            dbs_database_name: txtDataBaseName.getValue(),
                            dbs_username: txtUsername.getValue(),
                            dbs_password: txtPassword.getValue(),
                            dbs_port: parseInt(txtPort.getValue()),
                            dbs_description: txtDescription.getValue()
                        };
                    }

                    switch (dataBaseConnectionOption) {
                        case "POST":
                            if (frmDataBaseConnection.isValid()) {
                                dataBaseConnectionPostRestProxy(data);
                            } else {
                                return;
                            }
                            break;
                        case "PUT":
                            if (frmDataBaseConnection.isValid()) {
                                dataBaseConnectionPutRestProxy(dataBaseConnectionData.dbs_uid, data);
                            } else {
                                return;
                            }
                            break;
                    }
                }
            });

            btnCancel = new PMUI.ui.Button({
                id: "btnCancel",
                text: "Cancel".translate(),
                buttonType: 'error',
                handler: function () {
                    closeClicked = false;
                    isDirtyFrmDataBaseConnection();
                }
            });

            btnBack = new PMUI.ui.Button({
                id: "btnBack",
                text: "Back".translate(),
                handler: function () {
                    showForm();
                }
            });

            frmDataBaseConnection = new PMUI.form.Form({
                id: "frmDataBaseConnection",
                width: DEFAULT_WINDOW_WIDTH - 70,
                items: [
                    txtUID,
                    cboEngine,
                    cboEncode,
                    cboConnectionTypeOracle,
                    txtTns,
                    txtServer,
                    txtDataBaseName,
                    txtUsername,
                    txtPassword,
                    txtPort,
                    txtDescription
                ]
            });

            txtPort.controls[0].onKeyUp = function () {
                var txtPortfinalValue,
                    txtPortValue = getData2PMUI(frmDataBaseConnection.html).txtPort;
                if (/\D/.test(txtPortValue)) {
                    if (isNaN(parseInt(txtPortValue))) {
                        txtPortfinalValue = "";
                    } else {
                        txtPortfinalValue = parseInt(txtPortValue);
                    }
                    txtPort.setValue(txtPortfinalValue);
                }
            };

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
                    dataBaseConnectionOption = "POST";
                    winFrmDataBaseConnectionShow("POST", {});
                }
            });

            grdpnlDataBaseConnection = new PMUI.grid.GridPanel({
                id: "grdpnlDataBaseConnection",
                filterable: true,
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
                        id: 'grdpnlDataBaseConnectionButtonShow',
                        dataType: "button",
                        title: "",
                        buttonLabel: "Show ID".translate(),
                        buttonStyle: {
                            cssClasses: [
                                'mafe-button-show'
                            ]
                        },
                        width: "100px",
                        onButtonClick: function (row, grid) {
                            showUID(row.getData().dbs_uid);
                        }
                    },
                    {
                        columnData: "dbs_type",
                        title: "Type".translate(),
                        width: "100px",
                        sortable: true,
                        alignmentCell: 'left'
                    },
                    {
                        columnData: "dbs_server",
                        title: "Server".translate(),
                        width: "155px",
                        alignmentCell: 'left'
                    },
                    {
                        columnData: "dbs_database_name",
                        title: "Database Name".translate(),
                        width: "160px",
                        sortable: true,
                        alignmentCell: 'left'
                    },
                    {
                        columnData: "dbs_description",
                        title: "Description".translate(),
                        width: "200px",
                        alignmentCell: 'left'
                    },
                    {
                        id: 'grdpnlDataBaseConnectionButtonEdit',
                        dataType: "button",
                        title: "",
                        buttonLabel: "Edit".translate(),
                        buttonStyle: {
                            cssClasses: [
                                'mafe-button-edit'
                            ]
                        },
                        onButtonClick: function (row, grid) {
                            dataBaseConnectionGetRestProxy(row.getData().dbs_uid);
                        }
                    },
                    {
                        id: 'grdpnlDataBaseConnectionButtonDelete',
                        dataType: "button",
                        title: "",
                        buttonLabel: "Delete".translate(),
                        buttonStyle: {
                            cssClasses: [
                                'mafe-button-delete'
                            ]
                        },
                        onButtonClick: function (row, grid) {
                            var data = row.getData();
                            var msgWarning = new PMUI.ui.MessageWindow({
                                id: "msgWarning",
                                title: 'Database Connections'.translate(),
                                windowMessageType: 'warning',
                                width: 490,
                                message: "Do you want to delete this DB Connection?".translate(),
                                buttons: [
                                    {
                                        text: "No".translate(),
                                        handler: function () {
                                            msgWarning.close();
                                        },
                                        buttonType: "error"
                                    },
                                    {
                                        text: "Yes".translate(),
                                        handler: function () {
                                            dataBaseConnectionDeleteRestProxy(data.dbs_uid);
                                            msgWarning.close();
                                        },
                                        buttonType: "success"
                                    }
                                ]
                            });
                            msgWarning.open();
                            msgWarning.showFooter();
                        }
                    }
                ],
                dataItems: null
            });

            panelTest = new PMUI.core.Panel({
                id: 'panelTest',
                height: DEFAULT_WINDOW_HEIGHT - 71,
                display: 'inline-block'
            });

            winGrdpnlDataBaseConnection = new PMUI.ui.Window({
                id: "winGrdpnlDataBaseConnection",
                title: "Database Connections".translate(),
                width: DEFAULT_WINDOW_WIDTH,
                height: DEFAULT_WINDOW_HEIGHT,
                buttonPanelPosition: "bottom",
                buttons: [btnCancel, btnBack, btnTestConnection, btnCreate],
                onBeforeClose: function () {
                    closeClicked = true;
                    isDirtyFrmDataBaseConnection();
                }
            });

            winGrdpnlDataBaseConnection.addItem(grdpnlDataBaseConnection);
            winGrdpnlDataBaseConnection.addItem(frmDataBaseConnection);
            winGrdpnlDataBaseConnection.addItem(panelTest);

            refreshGridPanelInMainWindow();
            if (typeof listDBConnection !== "undefined") {
                winGrdpnlDataBaseConnection.open();
                frmDataBaseConnection.panel.html.style.padding = "10px";
                $('#grdpnlDataBaseConnection .pmui-textcontrol').css({'margin-top': '5px', width: '250px'});
                winGrdpnlDataBaseConnection.defineEvents();
                applyStyleWindowForm(winGrdpnlDataBaseConnection);
                winGrdpnlDataBaseConnection.footer.html.style.textAlign = 'right';
                $(btnNew.getHTML()).css({float: "right"})
                grdpnlDataBaseConnection.dom.toolbar.appendChild(btnNew.getHTML());
                btnNew.defineEvents();

                disableAllItems();
                winGrdpnlDataBaseConnection.getItems()[0].setVisible(true);
            }
        };

        PMDesigner.database.create = function () {
            PMDesigner.database();
            dataBaseConnectionOption = "POST";
            winFrmDataBaseConnectionShow("POST", {});
        };
    }()
);
