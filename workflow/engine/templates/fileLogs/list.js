Ext.namespace('fileLogs');

fileLogs.application = {
    init: function () {
        var loading = new Ext.LoadMask(Ext.getBody(), {msg: _('ID_LOADING_GRID')});

        var downloadFiles = function () {
            var records = gridFileLog.getSelectionModel().getSelections(),
                data = [], headers = {}, fd = new FormData();

            records.forEach(function (row) {
                data.push(row.data.fileName)
            });

            fd.append('files', JSON.stringify(data));
            headers['Authorization'] = 'Bearer ' + getToken();

            downloadFile('POST', urlProxy + 'filelogs/download', headers, fd);
        };

        var pageSize = parseInt(CONFIG.pageSize);
        var token = '';

        var getToken = function () {
            if (token === '') {
                credentials = RCBase64.decode(credentials);
                token = (credentials === '') ? "" : JSON.parse(credentials);
            }
            return token.access_token;
        };

        var storeFileLogs = new Ext.data.GroupingStore({
            remoteSort: true,
            proxy: new Ext.data.HttpProxy({
                api: {
                    read: urlProxy + 'filelogs/list'
                },
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + getToken()
                }
            }),
            reader: new Ext.data.JsonReader({
                root: 'data',
                totalProperty: 'totalRows',
                fields: [
                    {name: 'fileName'},
                    {name: 'fileCreated'},
                    {name: 'fileSize'}
                ]
            }),
            sortInfo: {
                field: 'fileCreated',
                direction: 'DESC'
            },
            listeners: {
                beforeload: function (store) {
                    loading.show();
                },
                load: function (store, record, opt) {
                    loading.hide();
                }
            }
        });

        var storePageSize = new Ext.data.SimpleStore({
            fields: ['size'],
            data: [['20'], ['30'], ['40'], ['50'], ['100']],
            autoLoad: true
        });

        var cboPageSize = new Ext.form.ComboBox({
            id: 'cboPageSize',

            mode: 'local',
            triggerAction: 'all',
            store: storePageSize,
            valueField: 'size',
            displayField: 'size',
            width: 50,
            editable: false,
            listeners: {
                select: function (combo, record, index) {
                    pageSize = parseInt(record.data['size']);
                    paging.pageSize = pageSize;
                    paging.moveFirst();
                }
            }
        });

        var paging = new Ext.PagingToolbar({
            id: 'paging',
            pageSize: pageSize,
            store: storeFileLogs,
            displayInfo: true,
            displayMsg: _('ID_GRID_PAGE_DISPLAYING_FILE_LOGS'),
            emptyMsg: _('ID_NO_RECORDS_FOUND'),
            items: ['-', _('ID_PAGE_SIZE') + '&nbsp;', cboPageSize]
        });

        var cmodel = new Ext.grid.ColumnModel({
            defaults: {
                width: 50,
                sortable: true
            },
            columns: [
                {header: _('ID_FILE_NAME'), dataIndex: 'fileName', width: 15},
                {header: _('ID_FILE_LOG_CREATED'), dataIndex: 'fileCreated', width: 15},
                {header: _('ID_FILE_LOG_SIZE'), dataIndex: 'fileSize', width: 10}
            ]
        });

        var smodel = new Ext.grid.RowSelectionModel({
            singleSelect: false,
            disableSelection: true,
            listeners: {
                beforerowselect: function (sm, rowIndex, keepExisting, record) {
                    sm.suspendEvents();
                    if (sm.isSelected(rowIndex)) {
                        // row already selected, deselect it
                        sm.deselectRow(rowIndex);
                    } else {
                        Ext.getCmp('btnDownload').enable();
                        sm.selectRow(rowIndex, true)
                    }
                    sm.resumeEvents();
                    return false;
                }
            }
        });

        var gridFileLog = new Ext.grid.GridPanel({
            id: 'gridFileLog',
            title: _('ID_STANDARD_LOGGING'),

            store: storeFileLogs,
            colModel: cmodel,
            selModel: smodel,
            cls: 'grid_with_checkbox',
            columnLines: true,
            viewConfig: {forceFit: true},
            enableColumnResize: true,
            enableHdMenu: false,
            tbar: [
                {
                    id: 'btnDownload',
                    text: _('ID_DOWNLOAD') + '&nbsp;',
                    iconCls: 'button_menu_ext ICON_STANDARD_LOGGING',
                    disabled: true,
                    handler: downloadFiles
                }
            ],
            bbar: paging,
            border: true
        });

        storeFileLogs.load();

        cboPageSize.setValue(pageSize);

        var viewport = new Ext.Viewport({
            layout: 'fit',
            autoScroll: false,
            items: [gridFileLog]
        });

        if (typeof(__FILE_LOGS_ERROR__) !== 'undefined') {
            PMExt.notify(_('ID_STANDARD_LOGGING'), __FILE_LOGS_ERROR__);
        }
    }
};

Ext.onReady(fileLogs.application.init, fileLogs.application);