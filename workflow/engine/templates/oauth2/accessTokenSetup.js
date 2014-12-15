Ext.namespace("accessTokenSetup");

accessTokenSetup.application = {
    init: function ()
    {
        var OACCESSTOKENOPTION = "";
        var loadMaskData = new Ext.LoadMask(Ext.getBody(), {msg: _("ID_LOADING_GRID")});

        function oauthAccessTokenProcessAjax(option, oauthAccessTokenId)
        {
            //Message
            var msg = "";

            switch (option) {
                case "UPD":
                    msg = "Update data...";
                    break;
                case "DEL":
                    msg = "Delete data...";
                    break;
                //case "LST":
                //    break;
            }

            var loadMaskAux = new Ext.LoadMask(Ext.getBody(), {msg: msg});
            loadMaskAux.show();

            //Data
            var p;

            switch (option) {
                case "UPD":
                    var arrayCheckbox = Ext.getCmp("chkgrpScope").getValue();
                    var scope = "";

                    for (var i = 0; i <= arrayCheckbox.length - 1; i++) {
                        scope = scope + ((scope != "")? " " : "") + arrayCheckbox[i].value;
                    }

                    p = {
                        option: option,
                        oauthAccessTokenId: oauthAccessTokenId,
                        scope: scope
                    };
                    break;
                case "DEL":
                    p = {
                        option: option,
                        oauthAccessTokenId: oauthAccessTokenId
                    };
                    break;
                //case "LST":
                //    break;
            }

            Ext.Ajax.request({
                url: "../oauth2/accessTokenSetupAjax",
                method: "POST",
                params: p,

                success: function (response, opts)
                {
                    var dataResponse = Ext.util.JSON.decode(response.responseText);

                    switch (option) {
                        case "UPD":
                        case "DEL":
                            if (dataResponse.status) {
                                if (dataResponse.status == "OK") {
                                    pagingData.moveFirst();
                                } else {
                                    Ext.MessageBox.alert(_("ID_ALERT"), dataResponse.message);
                                }
                            }
                            break;
                        //case "LST":
                        //    break;
                    }

                    loadMaskAux.hide();
                },
                failure: function (response, opts)
                {
                    loadMaskAux.hide();
                }
            });
        }

        function oauthAccessTokenSetForm(option, oauthAccessTokenId)
        {
            switch (option) {
                case "UPD":
                    var record = grdpnlMain.getSelectionModel().getSelected();

                    if (typeof(record) != "undefined") {
                        Ext.getCmp("oauthAccessTokenId").setValue(record.get("ACCESS_TOKEN")); //oauthAccessTokenId
                        Ext.getCmp("lblClientName").setText(record.get("CLIENT_NAME"));

                        winData.setTitle("Edit Application");
                        winData.show();

                        Ext.getCmp("btnSubmit").btnEl.dom.innerHTML = "Edit Application";

                        for (var i = 0; i <= SCOPE.length - 1; i++) {
                            Ext.getCmp("chkgrpScope").setValue("chk" + SCOPE[i].value, false)
                        }

                        var arrayScope = record.get("SCOPE").split(" ");

                        for (var i = 0; i <= arrayScope.length - 1; i++) {
                            Ext.getCmp("chkgrpScope").setValue("chk" + arrayScope[i], true);
                        }
                    }
                    break;
            }
        }

        //Variables
        var pageSize = parseInt(CONFIG.pageSize);

        //Stores
        var storeData = new Ext.data.Store({
            proxy: new Ext.data.HttpProxy({
                url: "../oauth2/accessTokenSetupAjax",
                method: "POST"
            }),

            //baseParams: {"option": "LST", "pageSize": pageSize},

            reader: new Ext.data.JsonReader({
                totalProperty: "resultTotal",
                root: "resultRoot",
                fields: [
                    {name: "ACCESS_TOKEN", type: "string"},
                    {name: "CLIENT_ID", type: "string"},
                    {name: "USER_ID", type: "string"},
                    {name: "EXPIRES", type: "string"},
                    {name: "SCOPE", type: "string"},
                    {name: "CLIENT_NAME", type: "string"},
                    {name: "CLIENT_DESCRIPTION", type: "string"}
                ]
            }),

            //autoLoad: true, //First call
            remoteSort: true,

            listeners: {
                beforeload: function (store, opt)
                {
                    loadMaskData.show();

                    this.baseParams = {
                        option: "LST",
                        pageSize: pageSize
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

        var chkgrpScopeItems = [];

        for (var i = 0; i <= SCOPE.length - 1; i++) {
            chkgrpScopeItems.push(
                {
                    xtype: "checkbox",
                    id: "chk" + SCOPE[i].value,
                    name: "chk" + SCOPE[i].value,
                    value:  SCOPE[i].value,
                    boxLabel: SCOPE[i].label
                }
            );
        }

        //Components
        var winData = new Ext.Window({
            layout: "fit",
            width: 400,
            height: 250,
            //title: "",
            modal: true,
            resizable: false,
            closeAction: "hide",

            items: [
                new Ext.FormPanel({
                    id: "frmOauthAccessToken",

                    frame: true,
                    labelAlign: "right",
                    labelWidth: 160,
                    autoWidth: true,
                    autoScroll: false,

                    defaults: {width: 200},

                    items: [
                        {
                            xtype: "hidden",
                            id: "oauthAccessTokenId",
                            name: "oauthAccessTokenId",
                        },
                        {
                            xtype: "label",
                            id: "lblClientName",

                            fieldLabel: "Application"
                        },
                        {
                            xtype: "checkboxgroup",
                            id: "chkgrpScope",
                            name: "chkgrpScope",

                            fieldLabel: "This Application Can Perform",
                            columns: 1,
                            items: chkgrpScopeItems
                        }
                    ]
                })
            ],
            buttons: [
                {
                    id: "btnSubmit",
                    //text: "",
                    handler: function ()
                    {
                        oauthAccessTokenProcessAjax(OACCESSTOKENOPTION, Ext.getCmp("oauthAccessTokenId").getValue());

                        winData.hide();
                    }
                },
                {
                    text: _("ID_CANCEL"),
                    handler: function ()
                    {
                        winData.hide();
                    }
                }
            ]
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
            id: "pagingData",

            pageSize: pageSize,
            store: storeData,
            displayInfo: true,
            displayMsg: "Displaying data " + "{" + "0" + "}" + " - " + "{" + "1" + "}" + " of " + "{" + "2" + "}",
            emptyMsg: "No data to display",
            items: ["-", "Page size:", cboPageSize]
        });

        var cmodel = new Ext.grid.ColumnModel({
            defaults: {
                sortable: true
            },

            columns: [
                {id: "ACCESS_TOKEN", dataIndex: "ACCESS_TOKEN", hidden: true, hideable: false},
                {id: "CLIENT_ID", dataIndex: "CLIENT_ID", hidden: true, hideable: false},
                {id: "CLIENT_NAME", dataIndex: "CLIENT_NAME", header: "Application", width: 200, align: "left"},
                {id: "CLIENT_DESCRIPTION", dataIndex: "CLIENT_DESCRIPTION", header: "Description", width: 250, align: "left"},
                {id: "USER_ID", dataIndex: "USER_ID", hidden: true, hideable: false},
                {id: "EXPIRES", dataIndex: "EXPIRES", hidden: true, hideable: false},
                {id: "SCOPE", dataIndex: "SCOPE", hidden: true, hideable: false},
                {
                    id: "ACTION",
                    dataIndex: "ACCESS_TOKEN",
                    header: "",
                    sortable: false,
                    menuDisabled: true,
                    hideable: false,
                    //width: 75,
                    //align: "center",
                    renderer: function (value, metaData, record, rowIndex, colIndex, store)
                    {
                        var id1 = Ext.id();
                        var id2 = Ext.id();

                        setTimeout(
                            function ()
                            {
                                var btn1 = new Ext.Button({
                                    text: "Edit",
                                    iconCls: "button_menu_ext ss_sprite ss_pencil",
                                    renderTo: id1,

                                    handler: function ()
                                    {
                                        var sm = grdpnlMain.getSelectionModel();
                                        sm.selectRow(rowIndex, true);

                                        var record = grdpnlMain.getSelectionModel().getSelected();

                                        if (typeof(record) != "undefined") {
                                            OACCESSTOKENOPTION = "UPD";

                                            oauthAccessTokenSetForm(OACCESSTOKENOPTION, record.get("ACCESS_TOKEN"));
                                        }
                                    }
                                });

                                var btn2 = new Ext.Button({
                                    text: "Remove Access",
                                    iconCls: "button_menu_ext ss_sprite ss_cross",
                                    renderTo: id2,

                                    handler: function ()
                                    {
                                        var sm = grdpnlMain.getSelectionModel();
                                        sm.selectRow(rowIndex, true);

                                        var record = grdpnlMain.getSelectionModel().getSelected();

                                        if (typeof(record) != "undefined") {
                                            Ext.MessageBox.confirm(
                                                _("ID_CONFIRM"),
                                                "Are you sure to remove access to the <b>"+record.get("CLIENT_NAME")+"</b> application?",
                                                function (btn)
                                                {
                                                    if (btn == "yes") {
                                                        OACCESSTOKENOPTION = "DEL";

                                                        oauthAccessTokenProcessAjax(OACCESSTOKENOPTION, record.get("ACCESS_TOKEN"));
                                                    }
                                                }
                                            );
                                        }
                                    }
                                });
                            },
                            5
                        );

                        return "<div style=\"text-align: center; line-height: 0;\"><div id=\"" + id1 + "\" style=\"display: inline-block;\"></div><div id=\"" + id2 + "\" style=\"display: inline-block; margin-left: 0.45em;\"></div></div>";
                    }
                }
            ]
        });

        var smodel = new Ext.grid.RowSelectionModel({
            singleSelect: true,
            listeners: {
                //
            }
        });

        var grdpnlMain = new Ext.grid.GridPanel({
            id: "grdpnlMain",

            store: storeData,
            colModel: cmodel,
            selModel: smodel,

            columnLines: true,
            viewConfig: {forceFit: true}, //Expand all columns
            enableColumnResize: true,
            enableHdMenu: true, //Menu of the column

            //tbar: ["->", txtSearch, btnTextClear, btnSearch],
            //bbar: pagingData,

            //style: "margin: 0 auto 0 auto;",
            //width: 550,
            //height: 450,
            title: "<div><div style=\"float: left;\">" + "User Applications" + "</div><div id=\"divClientSetup\" style=\"float: right;\"></div><div style=\"clear: both; height: 0; line-height:0; font-size: 0;\"></div></div>",
            border: false,

            listeners: {
                afterrender: function (grid)
                {
                    /*ar btn = new Ext.Button({
                        text: "&nbsp;" + "Setup My Applications",
                        iconCls: "button_menu_ext ss_sprite ss_cog",
                        renderTo: "divClientSetup",

                        handler: function ()
                        {
                            location.href = "clientSetup";
                        }
                    });*/
                }
            }
        });

        //Menu context

        //Initialize events
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

Ext.onReady(accessTokenSetup.application.init, accessTokenSetup.application);

