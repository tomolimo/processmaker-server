Ext.namespace("emailServer");

emailServer.application = {
    init: function ()
    {
        var EMAILSERVEROPTION = "";
        var EMAILSERVEROPTION_AUX = "";

        var FLAGTEST = 1;

        var loadMaskData = new Ext.LoadMask(Ext.getBody(), {msg: _("ID_LOADING_GRID")});

        function emailServerProcessAjax(option, emailServerUid)
        {
            //Message
            var msg = "";

            switch (option) {
                case "INS":
                    msg = _("ID_EMAIL_SERVER_INSERT_DATA");
                    break;
                case "UPD":
                    msg = _("ID_EMAIL_SERVER_UPDATE_DATA");
                    break;
                case "DEL":
                    msg = _("ID_EMAIL_SERVER_DELETE_DATA");
                    break;
                //case "LST":
                //    break;
                case "TEST":
                    msg = _("ID_EMAIL_SERVER_TEST_DATA");
                    break;
            }

            var loadMaskAux = new Ext.LoadMask(Ext.getBody(), {msg: msg});
            loadMaskAux.show();

            //Data
            var p;

            /*----------------------------------********---------------------------------*/
                var emailDefault = 1;
            /*----------------------------------********---------------------------------*/

            switch (option) {
                case "INS":
                    var typeEmailEngine = Ext.getCmp("cboEmailEngine").getValue();


                    if (typeEmailEngine == "PHPMAILER") {
                        var rdoGrpOption = Ext.getCmp("rdoGrpSmtpSecure").getValue();
                        var smtpSecure = rdoGrpOption.getGroupValue();

                        p = {
                            option: option,

                            cboEmailEngine: typeEmailEngine,
                            server: Ext.getCmp("txtServer").getValue(),
                            port: Ext.getCmp("txtPort").getValue(),
                            reqAuthentication: (Ext.getCmp("chkReqAuthentication").checked)? 1 : 0,
                            accountFrom: Ext.getCmp("txtAccountFrom").getValue(),
                            password: Ext.getCmp("txtPassword").getValue(),
                            fromMail: Ext.getCmp("txtFromMail").getValue(),
                            fromName: Ext.getCmp("txtFromName").getValue(),
                            smtpSecure: smtpSecure,
                            sendTestMail: (Ext.getCmp("chkSendTestMail").checked)? 1 : 0,
                            mailTo: Ext.getCmp("txtMailTo").getValue(),
                            emailServerDefault: emailDefault
                        };
                    } else {
                        //MAIL
                        p = {
                            option: option,

                            cboEmailEngine: typeEmailEngine,
                            fromMail: Ext.getCmp("txtFromMail").getValue(),
                            fromName: Ext.getCmp("txtFromName").getValue(),
                            sendTestMail: (Ext.getCmp("chkSendTestMail").checked)? 1 : 0,
                            mailTo: Ext.getCmp("txtMailTo").getValue(),
                            emailServerDefault: emailDefault
                        };
                    }
                    break;
                case "UPD":
                    var typeEmailEngine = Ext.getCmp("cboEmailEngine").getValue();

                    if (typeEmailEngine == "PHPMAILER") {
                        var rdoGrpOption = Ext.getCmp("rdoGrpSmtpSecure").getValue();
                        var smtpSecure = rdoGrpOption.getGroupValue();

                        p = {
                            option: option,
                            emailServerUid: emailServerUid,

                            cboEmailEngine: typeEmailEngine,
                            server: Ext.getCmp("txtServer").getValue(),
                            port: Ext.getCmp("txtPort").getValue(),
                            reqAuthentication: (Ext.getCmp("chkReqAuthentication").checked)? 1 : 0,
                            accountFrom: Ext.getCmp("txtAccountFrom").getValue(),
                            password: Ext.getCmp("txtPassword").getValue(),
                            fromMail: Ext.getCmp("txtFromMail").getValue(),
                            fromName: Ext.getCmp("txtFromName").getValue(),
                            smtpSecure: smtpSecure,
                            sendTestMail: (Ext.getCmp("chkSendTestMail").checked)? 1 : 0,
                            mailTo: Ext.getCmp("txtMailTo").getValue(),
                            emailServerDefault: emailDefault
                        };
                    } else {
                        //MAIL
                        p = {
                            option: option,
                            emailServerUid: emailServerUid,

                            cboEmailEngine: typeEmailEngine,
                            fromMail: Ext.getCmp("txtFromMail").getValue(),
                            fromName: Ext.getCmp("txtFromName").getValue(),
                            sendTestMail: (Ext.getCmp("chkSendTestMail").checked)? 1 : 0,
                            mailTo: Ext.getCmp("txtMailTo").getValue(),
                            emailServerDefault: emailDefault
                        };
                    }
                    break;
                case "DEL":
                    p = {
                        option: option,
                        emailServerUid: emailServerUid
                    };
                    break;
                //case "LST":
                //    break;
                case "TEST":
                    var typeEmailEngine = Ext.getCmp("cboEmailEngine").getValue();

                    if (typeEmailEngine == "PHPMAILER") {
                        var rdoGrpOption = Ext.getCmp("rdoGrpSmtpSecure").getValue();
                        var smtpSecure = rdoGrpOption.getGroupValue();

                        p = {
                            option: option,

                            cboEmailEngine: typeEmailEngine,
                            server: Ext.getCmp("txtServer").getValue(),
                            port: Ext.getCmp("txtPort").getValue(),
                            reqAuthentication: (Ext.getCmp("chkReqAuthentication").checked)? 1 : 0,
                            accountFrom: Ext.getCmp("txtAccountFrom").getValue(),
                            password: Ext.getCmp("txtPassword").getValue(),
                            fromMail: Ext.getCmp("txtFromMail").getValue(),
                            fromName: Ext.getCmp("txtFromName").getValue(),
                            smtpSecure: smtpSecure,
                            sendTestMail: (Ext.getCmp("chkSendTestMail").checked)? 1 : 0,
                            mailTo: Ext.getCmp("txtMailTo").getValue(),
                            emailServerDefault: emailDefault
                        };
                    } else {
                        //MAIL
                        p = {
                            option: option,

                            cboEmailEngine: typeEmailEngine,
                            fromMail: Ext.getCmp("txtFromMail").getValue(),
                            fromName: Ext.getCmp("txtFromName").getValue(),
                            sendTestMail: (Ext.getCmp("chkSendTestMail").checked)? 1 : 0,
                            mailTo: Ext.getCmp("txtMailTo").getValue(),
                            emailServerDefault: emailDefault
                        };
                    }
                    break;
            }

            Ext.Ajax.request({
                url: "emailServerAjax",
                method: "POST",
                params: p,

                success: function (response, opts)
                {
                    var dataResponse = Ext.util.JSON.decode(response.responseText);

                    switch (option) {
                        case "INS":
                        case "UPD":
                        case "DEL":
                            if (dataResponse.status) {
                                if (dataResponse.status == "OK") {
                                    pagingData.moveFirst();

                                    switch (option) {
                                        case "INS":
                                        case "UPD":
                                            winData.hide();
                                            break;
                                    }
                                } else {
                                    Ext.MessageBox.show({
                                        title: _("ID_ERROR"),
                                        msg: dataResponse.message,

                                        icon: Ext.MessageBox.ERROR,
                                        buttons: {ok: _("ID_ACCEPT")}
                                    });

                                    winData.setDisabled(false);
                                }
                            }
                            break;
                        //case "LST":
                        //    break;

                        case "TEST":
                            showTestConnection(typeEmailEngine, dataResponse.data);

                            winTestConnection.show();
                            break;
                    }

                    loadMaskAux.hide();
                },
                failure: function (response, opts)
                {
                    loadMaskAux.hide();
                }
            });
        }

        function emailServerSetForm(option, emailServerUid)
        {
            switch (option) {
                case "INS":
                    Ext.getCmp("emailServerUid").setValue("");

                    Ext.getCmp("cboEmailEngine").setValue("PHPMAILER");

                    emailServerSetEmailEngine(Ext.getCmp("cboEmailEngine").getValue());

                    Ext.getCmp("txtServer").allowBlank = true;
                    Ext.getCmp("txtPort").allowBlank = true;
                    Ext.getCmp("txtAccountFrom").allowBlank = true;

                    Ext.getCmp("txtServer").setValue("");
                    Ext.getCmp("txtPort").setValue("");

                    Ext.getCmp("chkReqAuthentication").setValue(false);

                    emailServerSetPassword(Ext.getCmp("chkReqAuthentication").checked);

                    Ext.getCmp("txtAccountFrom").setValue("");
                    Ext.getCmp("txtPassword").setValue("");
                    Ext.getCmp("txtFromMail").setValue("");
                    Ext.getCmp("txtFromName").setValue("");

                    Ext.getCmp("rdoGrpSmtpSecure").setValue("No");

                    Ext.getCmp("chkSendTestMail").setValue(false);

                    emailServerSetMailTo(Ext.getCmp("chkSendTestMail").checked);

                    Ext.getCmp("txtMailTo").setValue("");

                    /*----------------------------------********---------------------------------*/

                    winData.setTitle(_("ID_EMAIL_SERVER_NEW"));
                    winData.setDisabled(false);
                    winData.show();

                    Ext.getCmp("txtServer").allowBlank = false;
                    Ext.getCmp("txtPort").allowBlank = false;
                    Ext.getCmp("txtAccountFrom").allowBlank = false;
                    break;
                case "UPD":
                    var record = grdpnlMain.getSelectionModel().getSelected();

                    if (typeof(record) != "undefined") {
                        Ext.getCmp("emailServerUid").setValue(record.get("MESS_UID"));

                        Ext.getCmp("cboEmailEngine").setValue(record.get("MESS_ENGINE"));
                        emailServerSetEmailEngine(record.get("MESS_ENGINE"));

                        Ext.getCmp("txtServer").setValue(record.get("MESS_SERVER"));
                        Ext.getCmp("txtPort").setValue((record.get("MESS_PORT") != 0)? record.get("MESS_PORT") : "");

                        Ext.getCmp("chkReqAuthentication").setValue((parseInt(record.get("MESS_RAUTH")) == 1)? true : false);

                        emailServerSetPassword(Ext.getCmp("chkReqAuthentication").checked);

                        Ext.getCmp("txtAccountFrom").setValue(record.get("MESS_ACCOUNT"));
                        Ext.getCmp("txtPassword").setValue(record.get("MESS_PASSWORD"));
                        Ext.getCmp("txtFromMail").setValue(record.get("MESS_FROM_MAIL"));
                        Ext.getCmp("txtFromName").setValue(record.get("MESS_FROM_NAME"));

                        Ext.getCmp("rdoGrpSmtpSecure").setValue((record.get("SMTPSECURE") != "")? record.get("SMTPSECURE") : "No");
                        Ext.getCmp("chkSendTestMail").setValue((parseInt(record.get("MESS_TRY_SEND_INMEDIATLY")) == 1)? true : false);
                        emailServerSetMailTo(Ext.getCmp("chkSendTestMail").checked);

                        Ext.getCmp("txtMailTo").setValue(record.get("MAIL_TO"));

                        /*----------------------------------********---------------------------------*/
                            Ext.getCmp("chkEmailServerDefault").setValue(true);
                        /*----------------------------------********---------------------------------*/

                        winData.setTitle(_("ID_EMAIL_SERVER_EDIT"));
                        winData.setDisabled(false);
                        winData.show();
                    }
                    break;
            }
        }

        function emailServerSetEmailEngine(cboEmailEngine)
        {
            Ext.getCmp("frmEmailServer").getForm().clearInvalid();

            if (cboEmailEngine == "PHPMAILER") {
                Ext.getCmp("txtServer").setVisible(true);
                Ext.getCmp("txtPort").setVisible(true);

                Ext.getCmp("chkReqAuthentication").setVisible(true);

                emailServerSetPassword(Ext.getCmp("chkReqAuthentication").checked);

                Ext.getCmp("txtAccountFrom").setVisible(true);
                Ext.getCmp("rdoGrpSmtpSecure").setVisible(true);

                Ext.getCmp("txtServer").allowBlank = false;
                Ext.getCmp("txtPort").allowBlank = false;
                Ext.getCmp("txtAccountFrom").allowBlank = false;
            } else {
                //MAIL
                Ext.getCmp("txtServer").setVisible(false);
                Ext.getCmp("txtPort").setVisible(false);

                Ext.getCmp("chkReqAuthentication").setVisible(false);

                emailServerSetPassword(false);

                Ext.getCmp("txtAccountFrom").setVisible(false);
                Ext.getCmp("rdoGrpSmtpSecure").setVisible(false);

                Ext.getCmp("txtServer").allowBlank = true;
                Ext.getCmp("txtPort").allowBlank = true;
                Ext.getCmp("txtAccountFrom").allowBlank = true;
                Ext.getCmp("txtPassword").allowBlank = true;
            }
        }

        function emailServerSetPassword(flagPassChecked)
        {
            if (flagPassChecked) {
                Ext.getCmp("txtPassword").setVisible(true);
                Ext.getCmp("txtPassword").allowBlank = false;
            } else {
                Ext.getCmp("txtPassword").setVisible(false);
                Ext.getCmp("txtPassword").allowBlank = true;
            }
        }

        function emailServerSetMailTo(flagMailToChecked)
        {
            if (flagMailToChecked) {
                Ext.getCmp("txtMailTo").setVisible(true);
                Ext.getCmp("txtMailTo").allowBlank = false;
            } else {
                Ext.getCmp("txtMailTo").setVisible(false);
                Ext.getCmp("txtMailTo").allowBlank = true;
            }
        }

        function showTestConnection(option, testData)
        {
            var msg = "";

            FLAGTEST = 1;

            if (option == "PHPMAILER") {
                if (typeof(testData.resolving_name) != "undefined") {
                    if (testData.resolving_name.result) {
                        msg =  msg + "<img src = \"/images/select-icon.png\" width=\"17\" height=\"17\" style=\"margin-right: 0.9em; color: #0000FF;\" />" + testData.resolving_name.title + "<br />";
                    } else {
                        msg =  msg + "<img src = \"/images/error.png\" width=\"21\" height=\"21\" style=\"margin-right: 0.6em;\" />" + testData.resolving_name.title + "<br /><span style=\"margin-left:2.3em; color: #0000FF;\">" + testData.resolving_name.message + "</span><br />";
                        FLAGTEST = 0;
                    }
                }

                if (typeof(testData.check_port) != "undefined") {
                    if (testData.check_port.result) {
                        msg =  msg + "<img src = \"/images/select-icon.png\" width=\"17\" height=\"17\" style=\"margin-right: 0.9em; color: #0000FF;\" />" + testData.check_port.title + "<br />";
                    } else {
                        msg =  msg + "<img src = \"/images/error.png\" width=\"21\" height=\"21\" style=\"margin-right: 0.6em;\" />" + testData.check_port.title + "<br /><span style=\"margin-left:2.3em; color: #0000FF;\">" + testData.check_port.message + "</span><br />";
                        FLAGTEST = 0;
                    }
                }

                if (typeof(testData.establishing_connection_host) != "undefined") {
                    if (testData.establishing_connection_host.result) {
                        msg =  msg + "<img src = \"/images/select-icon.png\" width=\"17\" height=\"17\" style=\"margin-right: 0.9em; color: #0000FF;\" />" + testData.establishing_connection_host.title + "<br />";
                    } else {
                        msg =  msg + "<img src = \"/images/error.png\" width=\"21\" height=\"21\" style=\"margin-right: 0.6em;\" />" + testData.establishing_connection_host.title + "<br /><span style=\"margin-left:2.3em; color: #0000FF;\">" + testData.establishing_connection_host.message + "</span><br />";
                        FLAGTEST = 0;
                    }
                }

                if (typeof(testData.login) != "undefined") {
                    if (testData.login.result != "") {
                        msg =  msg + "<img src = \"/images/select-icon.png\" width=\"17\" height=\"17\" style=\"margin-right: 0.9em; color: #0000FF;\" />" + testData.login.title + "<br />";
                    } else {
                        msg =  msg + "<img src = \"/images/error.png\" width=\"21\" height=\"21\" style=\"margin-right: 0.6em;\" />" + testData.login.title + "<br /><span style=\"margin-left:2.3em; color: #0000FF;\">" + testData.login.message + "</span><br />";
                        FLAGTEST = 0;
                    }
                }

                if (typeof(testData.sending_email) != "undefined") {
                    if (testData.sending_email.result) {
                        msg =  msg + "<img src = \"/images/select-icon.png\" width=\"17\" height=\"17\" style=\"margin-right: 0.9em; color: #0000FF;\" />" + testData.sending_email.title + "<br />";
                    } else {
                        msg =  msg + "<img src = \"/images/error.png\" width=\"21\" height=\"21\" style=\"margin-right: 0.6em;\" />" + testData.sending_email.title + "<br /><span style=\"margin-left:2.3em; color: #0000FF;\">" + testData.sending_email.message + "</span><br />";
                        FLAGTEST = 0;
                    }
                }

            } else {
                //MAIL
                if (typeof(testData.verifying_mail) != "undefined") {
                    if (testData.verifying_mail.result) {
                        msg =  msg + "<img src = \"/images/select-icon.png\" width=\"17\" height=\"17\" style=\"margin-right: 0.9em;\" />" + testData.verifying_mail.title + "<br />";
                    } else {
                        msg =  msg + "<img src = \"/images/error.png\" width=\"21\" height=\"21\" style=\"margin-right: 0.6em;\" />" + testData.verifying_mail.title + "<br /><span style=\"margin-left:2.3em; color: #0000FF;\">" + testData.verifying_mail.message + "</span><br />";
                        FLAGTEST = 0;
                    }
                }

                if (typeof(testData.sending_email) != "undefined") {
                    if (testData.sending_email.result) {
                        msg =  msg + "<img src = \"/images/select-icon.png\" width=\"17\" height=\"17\" style=\"margin-right: 0.9em; color: #0000FF;\" />" + testData.sending_email.title + "<br />";
                    } else {
                        msg =  msg + "<img src = \"/images/error.png\" width=\"21\" height=\"21\" style=\"margin-right: 0.6em;\" />" + testData.sending_email.title + "<br /><span style=\"margin-left:2.3em; color: #0000FF;\">" + testData.sending_email.message + "</span><br />";
                        FLAGTEST = 0;
                    }
                }
            }

            var html = "<div style=\"margin: 0 0 1em 0; border: 2px solid #FDD24B; background:#FFECB1; font: bold 1em arial; text-align: center;\">" + _("ID_EMAIL_SERVER_RESULT_TESTING") + "</div>";

            html = html + msg + "<br />";

            var formItems = Ext.getCmp("frmTestConnectionView").form.items;
            formItems.items[0].setValue(html);
        }

        function onMnuContext(grid, rowIndex, e)
        {
            e.stopEvent();

            var coords = e.getXY();

            mnuContext.showAt([coords[0], coords[1]]);
        }

        //Variables
        var pageSize = parseInt(CONFIG.pageSize);

        //Stores
        var storeData = new Ext.data.Store({
            proxy: new Ext.data.HttpProxy({
                url: "emailServerAjax",
                method: "POST"
            }),

            baseParams: {
                option: "LST",
                pageSize: pageSize
            },

            reader: new Ext.data.JsonReader({
                totalProperty: "resultTotal",
                root: "resultRoot",

                fields: [
                    {name: "MESS_UID", type: "string"},
                    {name: "MESS_ENGINE", type: "string"},
                    {name: "MESS_SERVER", type: "string"},
                    {name: "MESS_PORT", type: "int"},
                    {name: "MESS_RAUTH", type: "int"},
                    {name: "MESS_ACCOUNT", type: "string"},
                    {name: "MESS_PASSWORD", type: "string"},
                    {name: "MESS_FROM_MAIL", type: "string"},
                    {name: "MESS_FROM_NAME", type: "string"},
                    {name: "SMTPSECURE", type: "string"},
                    {name: "MESS_TRY_SEND_INMEDIATLY", type: "int"},
                    {name: "MAIL_TO", type: "string"},
                    {name: "MESS_DEFAULT", type: "int"}
                ]
            }),

            remoteSort: true,

            listeners: {
                beforeload: function (store, opt)
                {
                    loadMaskData.show();

                    btnEdit.setDisabled(true);
                    btnDelete.setDisabled(true);

                    this.baseParams = {
                        option: "LST",
                        pageSize: pageSize,
                        search: Ext.getCmp("txtSearch").getValue()
                    };
                },
                load: function (store, record, opt)
                {
                    loadMaskData.hide();
                }
            }
        });

        var storePageSize = new Ext.data.SimpleStore({
            fields: ["size"],
            data: [["20"], ["30"], ["40"], ["50"], ["100"]],
            autoLoad: true
        });

        var emailUrlValidationText = /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@([a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4}))|((([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]).){3}([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]))$/i;

        Ext.apply(Ext.form.VTypes, {
            emailUrlValidation: function(val, field)
            {
                return emailUrlValidationText.test(val);
            }
        });

        var storeDataEmailEngine = new Ext.data.ArrayStore({
            idIndex: 0,
            fields: ["id", "value"],

            data: [
                ["PHPMAILER", "SMTP (PHPMailer)"],
                ["MAIL", "Mail (PHP)"]
            ]
        });

        var cboEmailEngine = new Ext.form.ComboBox({
            id: "cboEmailEngine",
            name: "cboEmailEngine",

            valueField: "id",
            displayField: "value",

            value: "PHPMAILER",
            store: storeDataEmailEngine,

            fieldLabel: _("EMAIL_ENGINE"), //Email Engine
            triggerAction: "all",

            mode: "local",
            editable: false,
            lazyRender: true,
            selectOnFocus: true,
            forceSelection: true,

            listeners: {
                select: function(combo, value)
                {
                    emailServerSetEmailEngine(Ext.getCmp("cboEmailEngine").getValue());
                }
            }
        });

        var txtServer = new Ext.form.TextField({
            id: "txtServer",
            name: "txtServer",

            fieldLabel: _("ID_SERVER") //Server
        });

        var txtPort = new Ext.form.NumberField({
            id: "txtPort",
            name: "txtPort",

            fieldLabel: _("PORT_DEFAULT"),  //Port (default 25)

            anchor: "36%",
            maxLength: 3,
            emptyText: null
        });

        var chkReqAuthentication = new Ext.form.Checkbox({
            id: "chkReqAuthentication",
            name: "chkReqAuthentication",

            boxLabel: _("REQUIRE_AUTHENTICATION"), //Require authentication

            handler: function()
            {
                emailServerSetPassword(this.checked);
            }
        });


        var txtAccountFrom = new Ext.form.TextField({
            id: "txtAccountFrom",
            name: "txtAccountFrom",

            fieldLabel: _("ID_EMAIL_SERVER_ACCOUNT_FROM"), //Account From

            vtype: "emailUrlValidation"
        });

        var txtPassword = new Ext.form.TextField({
            id: "txtPassword",
            name: "txtPassword",
            inputType: "password",

            fieldLabel: _("ID_PASSWORD"), //Password

            hidden: true
        });

        var txtFromMail = new Ext.form.TextField({
            id: "txtFromMail",
            name: "txtFromMail",

            fieldLabel: _("ID_FROM_EMAIL"), //From Mail

            vtype: "email"
        });

        var txtFromName = new Ext.form.TextField({
            id: "txtFromName",
            name: "txtFromName",

            fieldLabel: _("ID_FROM_NAME") //From Name
        });

        var rdoGrpSmtpSecure = new Ext.form.RadioGroup({
            id: "rdoGrpSmtpSecure",
            name: "rdoGrpSmtpSecure",

            fieldLabel: _("USE_SECURE_CONNECTION"), //Use Secure Connection

            columns: 3,
            vertical: true,

            items: [
                {boxLabel: "No",  inputValue: "No",  name: "rdoGrpSmtpSecure", checked: true},
                {boxLabel: "TLS", inputValue: "tls", name: "rdoGrpSmtpSecure"},
                {boxLabel: "SSL", inputValue: "ssl", name: "rdoGrpSmtpSecure"}
            ]
        });

        var chkSendTestMail = new Ext.form.Checkbox({
            id: "chkSendTestMail",
            name: "chkSendTestMail",

            boxLabel: _("SEND_TEST_MAIL"), //Send a test mail

            handler: function()
            {
                emailServerSetMailTo(this.checked);
            }
        });

        var txtMailTo = new Ext.form.TextField({
            id: "txtMailTo",
            name: "txtMailTo",

            fieldLabel: _("MAIL_TO"), //Mail to

            hidden: true
        });

        var chkEmailServerDefault = new Ext.form.Checkbox({
            id: "chkEmailServerDefault",
            name: "chkEmailServerDefault",

            boxLabel: _("ID_EMAIL_SERVER_THIS_CONFIGURATION_IS_DEFAULT")
        });

        var btnTest = new Ext.Action({
            id: "btnTest",
            text: _("ID_TEST"),

            width: 55,

            handler: function ()
            {
                if (Ext.getCmp("frmEmailServer").getForm().isValid()) {
                    EMAILSERVEROPTION = "TEST";

                    winData.setDisabled(true);

                    emailServerProcessAjax(EMAILSERVEROPTION, "");
                } else {
                    Ext.MessageBox.alert(_("ID_INVALID_DATA"), _("ID_CHECK_FIELDS_MARK_RED"));
                }
            }
        });

        var btnSave = new Ext.Action({
            id: "btnSave",
            text: _("ID_SAVE_CHANGES"),

            width: 85,
            disabled: true,

            handler: function ()
            {
                if (Ext.getCmp("frmEmailServer").getForm().isValid()) {
                    winData.setDisabled(true);

                    emailServerProcessAjax(EMAILSERVEROPTION, Ext.getCmp("emailServerUid").getValue());
                } else {
                    Ext.MessageBox.alert(_("ID_INVALID_DATA"), _("ID_CHECK_FIELDS_MARK_RED"));
                }
            }
        });

        var btnCancel = new Ext.Action({
            id: "btnCancel",
            text: _("ID_CANCEL"),

            width: 85,
            disabled: false,

            handler: function ()
            {
                winData.hide();
            }
        });

        //Components
        var winData = new Ext.Window({
            layout: "fit",
            width: 550,
            height: 388,
            //title: "",
            modal: true,
            resizable: false,
            closeAction: "hide",

            items: [
                new Ext.FormPanel({
                    id: "frmEmailServer",

                    frame: true,
                    labelAlign: "right",
                    labelWidth: 150,
                    autoWidth: true,
                    autoScroll: false,

                    defaults: {width: 325},

                    items: [
                        {
                            xtype: "hidden",
                            id: "emailServerUid",
                            name: "emailServerUid"
                        },
                        cboEmailEngine,
                        txtServer,
                        txtPort,
                        chkReqAuthentication,
                        txtAccountFrom,
                        txtPassword,
                        txtFromMail,
                        txtFromName,
                        rdoGrpSmtpSecure,
                        chkSendTestMail,
                        txtMailTo
                        /*----------------------------------********---------------------------------*/
                    ]
                })
            ],

            buttons: [btnTest, btnSave, btnCancel]
        });

        var winTestConnection = new Ext.Window({
            layout: "fit",
            width: 480,
            height: 350,
            title: _("ID_EMAIL_SERVER_TITLE_TESTING"),
            modal: true,
            resizable: false,
            closeAction: "hide",

            items: [
                new Ext.FormPanel({
                    id: "frmTestConnectionView",

                    frame: true,
                    labelAlign: "right",
                    labelWidth: 1,
                    autoWidth: true,
                    autoScroll: true,
                    items: [
                        {
                            xtype: "displayfield",
                            fieldLabel: ""
                        }
                    ]
                })
            ],

            buttons: [
                {
                    id: "btnAccept",
                    text: _("ID_ACCEPT"),

                    handler: function ()
                    {
                        winTestConnection.hide();
                        winData.setDisabled(false);

                        if (FLAGTEST == 1) {
                            EMAILSERVEROPTION = EMAILSERVEROPTION_AUX;

                            btnSave.setDisabled(false);
                        } else {
                            btnSave.setDisabled(true);
                        }
                    }
                }
            ],

            listeners: {
                hide: function (win)
                {
                    winData.setDisabled(false);

                    if (FLAGTEST == 1) {
                        btnSave.setDisabled(false);
                    } else {
                        btnSave.setDisabled(true);
                    }
                }
            }
        });

        var btnNew = new Ext.Action({
            id: "btnNew",

            text: _("ID_NEW"),
            iconCls: "button_menu_ext ss_sprite ss_add",

            handler: function ()
            {
                EMAILSERVEROPTION = "INS";
                EMAILSERVEROPTION_AUX = "INS";

                emailServerSetForm(EMAILSERVEROPTION, "");

                Ext.getCmp("btnSave").disable();
            }
        });

        var btnEdit = new Ext.Action({
            id: "btnEdit",

            text: _("ID_EDIT"),
            iconCls: "button_menu_ext ss_sprite ss_pencil",

            handler: function ()
            {
                var record = grdpnlMain.getSelectionModel().getSelected();

                if (typeof(record) != "undefined") {
                    Ext.getCmp("btnSave").disable();

                    EMAILSERVEROPTION = "UPD";
                    EMAILSERVEROPTION_AUX = EMAILSERVEROPTION;

                    emailServerSetForm(EMAILSERVEROPTION, record.get("MESS_UID"));
                }
            }
        });

        var btnDelete = new Ext.Action({
            id: "btnDelete",

            text: _("ID_DELETE"),
            iconCls: "button_menu_ext ss_sprite ss_cross",

            handler: function ()
            {
                var record = grdpnlMain.getSelectionModel().getSelected();

                if (typeof(record) != "undefined") {
                    Ext.MessageBox.confirm(
                        _("ID_CONFIRM"),
                        _("ID_EMAIL_SERVER_DELETE_WARNING_MESSAGE"),
                        function (btn)
                        {
                            if (btn == "yes") {
                                EMAILSERVEROPTION = "DEL";

                                emailServerProcessAjax(EMAILSERVEROPTION, record.get("MESS_UID"));
                            }
                        }
                    );
                }
            }
        });

        var btnSearch = new Ext.Action({
            id: "btnSearch",

            text: _("ID_SEARCH"),

            handler: function ()
            {
                pagingData.moveFirst();
            }
        });

        var txtSearch = new Ext.form.TextField({
            id: "txtSearch",

            emptyText: _("ID_EMPTY_SEARCH"),
            width: 150,
            allowBlank: true,

            listeners: {
                specialkey: function (f, e)
                {
                    if (e.getKey() == e.ENTER) {
                        pagingData.moveFirst();
                    }
                }
            }
        });

        var btnTextClear = new Ext.Action({
            id: "btnTextClear",

            text: "X",
            ctCls: "pm_search_x_button",
            handler: function ()
            {
                txtSearch.reset();
            }
        });

        var cboPageSize = new Ext.form.ComboBox({
            id: "cboPageSize",

            mode: "local",
            triggerAction: "all",
            store: storePageSize,
            valueField: "size",
            displayField: "size",
            width: 50,
            editable: false,

            listeners: {
                select: function (combo, record, index)
                {
                    pageSize = parseInt(record.data["size"]);

                    pagingData.pageSize = pageSize;
                    pagingData.moveFirst();
                }
            }
        });

        var pagingData = new Ext.PagingToolbar({
            pageSize: pageSize,
            store: storeData,
            displayInfo: true,
            displayMsg: "Displaying data " + "{" + "0" + "}" + " - " + "{" + "1" + "}" + " of " + "{" + "2" + "}",
            emptyMsg: "No data to display",
            items: ["-", "Page size:", cboPageSize]
        });

        var rendererMessServer = function (value)
        {
            return (value != "")? value : "-";
        };

        var rendererMessPort = function (value)
        {
            return (value != 0)? value : "-";
        };

        var rendererMessSmtpSecure = function (value)
        {
            return (value != "")? value : "-";
        };

        var rendererMessDefault = function (value)
        {
            return (value == 1)? "<img src = \"/images/ext/default/saved.png\" width=\"17\" height=\"17\" style=\"margin-right: 0.9em;\" />" : "";
        };

        var cmodel = new Ext.grid.ColumnModel({
            defaults: {
                sortable: true
            },

            columns: [
                {id: "MESS_UID", dataIndex: "MESS_UID", hidden: true,  header: "uid_emailServer", width: 0, hideable: false, align: "left"},
                {id: "MESS_ENGINE", dataIndex: "MESS_ENGINE", hidden: false, header: _("EMAIL_ENGINE"), width: 80, hideable: true, align: "left"},
                {id: "MESS_SERVER", dataIndex: "MESS_SERVER", hidden: false, header: _("ID_SERVER"), width: 150, hideable: true, align: "center", renderer: rendererMessServer},
                {id: "MESS_PORT", dataIndex: "MESS_PORT", hidden: false, header: _("ID_EMAIL_SERVER_PORT"), width: 50,  hideable: true, align: "center", renderer: rendererMessPort},
                {id: "MESS_RAUTH", dataIndex: "MESS_RAUTH", hidden: true,  header: _("REQUIRE_AUTHENTICATION"), width: 50,  hideable: false, align: "left"},
                {id: "MESS_ACCOUNT", dataIndex: "MESS_ACCOUNT", hidden: false, header: _("ID_EMAIL_SERVER_ACCOUNT_FROM"), width: 130, hideable: true,  align: "left"},
                {id: "MESS_PASSWORD", dataIndex: "MESS_PASSWORD", hidden: true,  header: _("ID_PASSWORD"), width: 130, hideable: false, align: "left"},
                {id: "MESS_FROM_MAIL", dataIndex: "MESS_FROM_MAIL", hidden: false, header: _("ID_FROM_EMAIL"), width: 130, hideable: true, align: "left"},
                {id: "MESS_FROM_NAME", dataIndex: "MESS_FROM_NAME", hidden: false, header: _("ID_FROM_NAME"), width: 150, hideable: true, align: "left"},
                {id: "SMTPSECURE", dataIndex: "SMTPSECURE", hidden: false, header: _("USE_SECURE_CONNECTION"), width: 140, hideable: true, align: "center", renderer: rendererMessSmtpSecure},
                {id: "MESS_TRY_SEND_INMEDIATLY", dataIndex: "MESS_TRY_SEND_INMEDIATLY", hidden: true, header: _("SEND_TEST_MAIL"), width: 50,  hideable: false, align: "left"},
                {id: "MAIL_TO", dataIndex: "MAIL_TO", hidden: false, header: _("MAIL_TO"),  width: 150, hideable: true, align: "left"},
                {id: "MESS_DEFAULT", dataIndex: "MESS_DEFAULT", hidden: false, header: _("ID_EMAIL_SERVER_DEFAULT"), width: 50, hideable: true, align: "center", renderer: rendererMessDefault}
            ]
        });

        var smodel = new Ext.grid.RowSelectionModel({
            singleSelect: true,
            listeners: {
                rowselect: function (sm)
                {
                    btnEdit.setDisabled(false);
                    btnDelete.setDisabled(false);
                },
                rowdeselect: function (sm)
                {
                    btnEdit.setDisabled(true);
                    btnDelete.setDisabled(true);
                }
            }
        });

        var arrayAux1 = [];

        /*----------------------------------********---------------------------------*/

        arrayAux1.push(btnEdit);

        /*----------------------------------********---------------------------------*/

        var grdpnlMain = new Ext.grid.GridPanel({
            id: "grdpnlMain",

            store: storeData,
            colModel: cmodel,
            selModel: smodel,

            columnLines: true,
            viewConfig: {forceFit: true},
            enableColumnResize: true,
            enableHdMenu: true,

            tbar: arrayAux1,

            /*----------------------------------********---------------------------------*/

            title: _("ID_EMAIL_SERVER_TITLE"),
            border: false,

            listeners: {
                rowdblclick: function (grid, rowIndex, evt)
                {
                    var record = grdpnlMain.getSelectionModel().getSelected();

                    if (typeof(record) != "undefined") {
                        Ext.getCmp("btnSave").disable();

                        EMAILSERVEROPTION = "UPD";
                        EMAILSERVEROPTION_AUX = EMAILSERVEROPTION;

                        emailServerSetForm(EMAILSERVEROPTION, record.get("MESS_UID"));
                    }
                }
            }
        });

        var arrayAux2 = [];

        arrayAux2.push(btnEdit);

        /*----------------------------------********---------------------------------*/

        var mnuContext = new Ext.menu.Menu({
            id: "mnuContext",

            items: arrayAux2
        });

        //Initialize events
        grdpnlMain.on(
            "rowcontextmenu",
            function (grid, rowIndex, evt)
            {
                var sm = grid.getSelectionModel();
                sm.selectRow(rowIndex, sm.isSelected(rowIndex));
            },
            this
        );

        grdpnlMain.addListener("rowcontextmenu", onMnuContext, this);

        cboPageSize.setValue(pageSize);

        grdpnlMain.store.load();

        //Load all panels
        var viewport = new Ext.Viewport({
            layout: "fit",
            autoScroll: false,
            items: [grdpnlMain]
        });
    }
}

Ext.onReady(emailServer.application.init, emailServer.application);

