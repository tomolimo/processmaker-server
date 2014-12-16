Ext.namespace("audit");

audit.application = {
    init: function ()
    {
        var loadMaskAudit = new Ext.LoadMask(Ext.getBody(), {msg: _("ID_LOADING_GRID")});

        auditLogAjax = function (option)
        {
            var p;
            switch (option) {
                case "EMPTY":
                    p = {
                        "option": option
                    };
                    break;
            }

            Ext.Ajax.request({
                url: "auditLogAjax",
                method: "POST",
                params: p,

                success: function (response, opts)
                {
                    var dataResponse = eval("(" + response.responseText + ")"); //json

                    switch (option) {
                        case "EMPTY":
                            if (dataResponse.status && dataResponse.status == "OK") {
                                pagingAudit.moveFirst();
                            }
                            break;
                    }
                }
            });
        }

        logView = function ()
        {
            var record = grdpnlMain.getSelectionModel().getSelected();

            if (typeof record != "undefined") {
                var strData = "<b>" + _("ID_DATE_LABEL") + "</b><br />" + record.get("DATE") + "<br />";
                strData = strData + "<b>" + _("ID_USER") + "</b><br />" + record.get("WORKSPACE") + "<br />";
                strData = strData + "<b>" + _("ID_IP") + "</b><br />" + record.get("IP") + "<br />";
                strData = strData + "<b>" + _("ID_ACTION") + "</b><br />" + record.get("ACTION") + "<br />";
                strData = strData + "<b>" + _("ID_DESCRIPTION") + "</b><br />" + record.get("DESCRIPTION") + "<br />";

                var formItems = Ext.getCmp("frmLogView").form.items;
                formItems.items[0].setValue(strData);
            }
        }

        var pageSize = parseInt(CONFIG.pageSize);

        var storeAudit = new Ext.data.Store({
            proxy: new Ext.data.HttpProxy({
                url: "auditLogAjax",
                method: "POST"
            }),

            reader: new Ext.data.JsonReader({
                root: "resultRoot",
                totalProperty: "resultTotal",
                fields: [
                    {name: "DATE"},
                    {name: "USER"},
                    {name: "IP"},
                    {name: "ACTION"},
                    {name: "DESCRIPTION"}
                ]
            }),

            listeners: {
                beforeload: function (store)
                {
                    loadMaskAudit.show();

                    this.baseParams = {
                        "option": "LST",
                        "pageSize": pageSize,
                        "action": Ext.getCmp("cboAction").getValue(),
                        "description": Ext.getCmp("fldDescription").getValue(),
                        "dateFrom": Ext.getCmp("dateFrom").getValue(),
                        "dateTo": Ext.getCmp("dateTo").getValue()
                    };
                },
                load: function (store, record, opt)
                {
                    loadMaskAudit.hide();
                }
            }
        });

        var storePageSize = new Ext.data.SimpleStore({
            fields: ["size"],
            data: [["20"], ["30"], ["40"], ["50"], ["100"]],
            autoLoad: true
        });

        var dateFrom = new Ext.form.DateField({
            id: "dateFrom",
            format: "Y-m-d",
            editable: false,
            width: 90,
            value: ""
        });

        var dateTo = new Ext.form.DateField({
            id: "dateTo",
            format: "Y-m-d",
            editable: false,
            width: 90,
            value: ""
        });

        var fldDescription = new Ext.form.TextField({
            id: "fldDescription",
            valueField: "id",
            displayField: "value",
            emptyText: _('ID_ENTER_SEARCH_TERM'),
            value: "",
            triggerAction: "all",
            mode: "local",
            editable: false,
            width: 150
        });

        var storeAction = new Ext.data.ArrayStore({
            idIndex: 0,
            fields: ["id", "value"],
            data: ACTION
        });

        var cboAction = new Ext.form.ComboBox({
            id: "cboAction",
            valueField: "id",
            displayField: "value",
            value: "ALL",
            store: storeAction,
            triggerAction: "all",
            mode: "local",
            editable: false,
            width: 150,
            listeners: {
                select: function (combo, record, index)
                {
                    pagingAudit.moveFirst();
                }
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
                    pagingAudit.pageSize = pageSize;
                    pagingAudit.moveFirst();
                }
            }
        });

        var pagingAudit = new Ext.PagingToolbar({
            id: "pagingAudit",
            pageSize: pageSize,
            store: storeAudit,
            displayInfo: true,
            displayMsg: _("ID_CRON_GRID_PAGE_DISPLAYING_MESSAGE"),
            emptyMsg: _("ID_NO_RECORDS_FOUND"),
            items: ["-", _("ID_PAGE_SIZE") + "&nbsp;", cboPageSize]
        });

        var cmodel = new Ext.grid.ColumnModel({
            defaults: {
                width: 50,
                sortable: true
            },
            columns: [
                {id: "ID", dataIndex: "DATE", hidden: true, hideable: false},
                {header: _("ID_DATE_LABEL"), dataIndex: "DATE", width: 15},
                {header: _("ID_USER"), dataIndex: "USER", width: 15},
                {header: _("ID_IP"), dataIndex: "IP", width: 10},
                {header: _("ID_ACTION"), dataIndex: "ACTION", width: 15},
                {header: _("ID_DESCRIPTION"), dataIndex: "DESCRIPTION"}
            ]
        });

        var smodel = new Ext.grid.RowSelectionModel({
            singleSelect: true,
            listeners: {
                rowselect: function (sm)
                {
                },
                rowdeselect: function (sm)
                {
                }
            }
        });

        var grdpnlMain = new Ext.grid.GridPanel({
            id: "grdpnlMain",

            store: storeAudit,
            colModel: cmodel,
            selModel: smodel,

            columnLines: true,
            viewConfig: {forceFit: true},
            enableColumnResize: true,
            enableHdMenu: false,
            tbar: [
                "->",
                {xtype: "tbtext", text: _("ID_ACTION") + "&nbsp;"},
                cboAction, 
                "-",
                {xtype: "tbtext", text: _("ID_DESCRIPTION") + "&nbsp;"},
                fldDescription,
                "-",
                {xtype: "tbtext", text: _("ID_FROM") + "&nbsp;"},
                dateFrom,
                {xtype: "tbtext", text: _("ID_TO") + "&nbsp;"},
                dateTo,
                {
                    xtype: "button",
                    text: _("ID_RESET_FILTERS"),

                    handler: function ()
                    {
                        Ext.getCmp("dateFrom").reset(),
                        Ext.getCmp("dateTo").reset(),
                        Ext.getCmp("fldDescription").reset()
                    }
                },
                "-",
                {
                    xtype: "button",
                    text: _("ID_SEARCH"),

                    handler: function ()
                    {
                        pagingAudit.moveFirst();
                    }
                }
            ],
            bbar: pagingAudit,
            border: false,
            title: _("ID_AUDIT_LOG_ACTIONS"),
            listeners: {
                rowdblclick: function ()
                {
                    logView();
                }
            }
        });

        storeAudit.load();

        cboPageSize.setValue(pageSize);

        var viewport = new Ext.Viewport({
            layout: "fit",
            autoScroll: false,
            items: [grdpnlMain]
        });
    }
}

Ext.onReady(audit.application.init, audit.application);