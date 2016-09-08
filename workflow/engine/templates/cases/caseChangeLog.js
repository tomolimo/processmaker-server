Ext.onReady(function () {
    Ext.state.Manager.setProvider(new Ext.state.CookieProvider());
    Ext.QuickTips.init();
    var store;
    var viewport = new Ext.Viewport({
        layout: 'border',
        items: [
            new Ext.grid.GridPanel({
                region: 'center',
                "width": "100%",
                "height": 300,
                "stateful": true,
                "stateId": "stateGrid",
                "enableColumnHide": false,
                "view": new Ext.grid.GroupingView({
                    forceFit: true,
                    enableGroupingMenu: false
                }),
                "store": store = new Ext.data.GroupingStore({
                    pageSize: 15,
                    fields: [
                        {name: 'record'},
                        {name: 'field'},
                        {name: 'previousValue'},
                        {name: 'currentValue'},
                        {name: 'previousValueType'},
                        {name: 'currentValueType'},
                        {name: 'task'},
                        {name: 'updateDate'},
                        {name: 'user'}
                    ],
                    groupField: 'record',
                    remoteSort: true,
                    proxy: new Ext.data.HttpProxy({
                        url: 'ajaxListener?action=changeLogAjax&idHistory=' + ID_HISTORY,
                        reader: new Ext.data.JsonReader({
                            fields: [
                                {name: 'record'},
                                {name: 'field'},
                                {name: 'previousValue'},
                                {name: 'currentValue'},
                                {name: 'previousValueType'},
                                {name: 'currentValueType'},
                                {name: 'task'},
                                {name: 'updateDate'},
                                {name: 'user'}
                            ],
                            root: 'data',
                            totalProperty: 'totalCount'
                        }),
                    })
                }),
                "colModel": new Ext.grid.ColumnModel({
                    "columns": [
                        {
                            header: _('ID_FIELD_NAME'),
                            width: 120,
                            sortable: false,
                            dataIndex: 'field'
                        },
                        {
                            header: _('ID_PREV_VALUES'),
                            flex: 1,
                            sortable: false,
                            dataIndex: 'previousValue',
                            renderer: function (value, p, record) {
                                return value;
                            }
                        },
                        {
                            header: _('ID_CURRENT_VALUES'),
                            flex: 1,
                            sortable: false,
                            dataIndex: 'currentValue',
                            renderer: function (value, p, record) {
                                return value;
                            }
                        },
                        {
                            header: '',
                            width: 1,
                            sortable: false,
                            hidden: true,
                            hideable: true,
                            dataIndex: 'record'
                        }
                    ]})
            })

        ]
    });

    store.load();
});
