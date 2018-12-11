(function () {
    PMDesigner.assigmentSupervisors = function (event) {
        var assigmentWindow = null,
            pageSizeAssignment = 8,
            restClient,
            assigmentPanelUser,
            assigmentPanelUserList,
            assigmentPanelGlobal,
            panelTitleUser,
            titleUser,
            panelSearchUser,
            searchGridUser,
            panelGridUser,
            buttonsUsers,
            gridUsers,
            panelTitleUserList,
            titleUserList,
            panelSearchUserList,
            searchGridUserList,
            panelGridUserList,
            buttonsUserList,
            gridUserList,
            applyStyles,
            loadServerData,
            loadUsers,
            loadUsersList,
            groupRows,
            assignee,
            remove,
            quickMessageWindow = new QuickMessageWindow();

        assigmentWindow = new PMUI.ui.Window({
            id: 'assigmentUserWindow',
            title: 'Assign Users and Groups as Supervisors'.translate(),
            width: DEFAULT_WINDOW_WIDTH + 1,
            height: DEFAULT_WINDOW_HEIGHT,
            footerHeight: 'auto',
            bodyHeight: 'auto',
            modal: true
        });
        restClient = new PMRestClient({
            endpoint: 'projects',
            typeRequest: 'get',
            messageError: 'There are problems, please try again.'.translate(),
            functionFailure: function (xhr, response) {
                PMDesigner.msgWinError(response.error.message);
            }
        });

        assigmentPanelUser = new PMUI.core.Panel({
            layout: "vbox",
            width: "49%",
            style: {
                cssClasses: [
                    'mafe-panel-assignment-white'
                ]
            }
        });
        assigmentPanelUserList = new PMUI.core.Panel({
            layout: "vbox",
            width: "50%",
            style: {
                cssClasses: [
                    'mafe-panel-assignment-smooth'
                ]
            }
        });
        assigmentPanelGlobal = new PMUI.core.Panel({
            layout: 'hbox',
            width: DEFAULT_WINDOW_WIDTH,
            style: {
                cssClasses: [
                    'mafe-assigment-panel-global'
                ]
            }
        });

        panelTitleUser = new PMUI.core.Panel({
            layout: 'hbox'
        });
        titleUser = new PMUI.ui.TextLabel({
            id: 'titleUser',
            label: ' ',
            textMode: 'plain',
            text: 'Available Users List'.translate(),
            style: {
                cssClasses: [
                    'mafe-designer-assigment-title'
                ]
            }
        });
        panelSearchUser = new PMUI.core.Panel({
            layout: 'hbox'
        });
        searchGridUser = new PMUI.field.TextField({
            id: 'searchGridUser',
            label: ' ',
            placeholder: 'Search ...'.translate()
        });
        panelGridUser = new PMUI.core.Panel({layout: 'hbox'});
        buttonsUsers = new PMUI.field.OptionsSelectorField({
            id: 'buttonsUsers',
            orientation: 'horizontal',
            items: [{
                text: 'All'.translate(),
                selected: true,
                value: 'all',
                style: {
                    cssClasses: [
                        'pmui-switch-icon-all'
                    ]
                }
            }, {
                text: 'Users'.translate(),
                value: 'user',
                style: {
                    cssClasses: [
                        'pmui-switch-icon-user'
                    ]
                }
            }, {
                text: 'Groups'.translate(),
                value: 'group',
                style: {
                    cssClasses: [
                        'pmui-switch-icon-group'
                    ]
                }
            }
            ],
            listeners: {
                select: function (item, event) {
                    groupRows(gridUsers, item.value);
                }
            },
            style: {
                cssClasses: [
                    'mafe-assigment-buttons'
                ]
            }
        });
        gridUsers = new PMUI.grid.GridPanel({
            id: 'gridUsers',
            pageSize: pageSizeAssignment,
            behavior: 'dragdropsort',
            filterable: true,
            filterPlaceholder: 'Search ...'.translate(),
            emptyMessage: 'No records found'.translate(),
            nextLabel: 'Next'.translate(),
            previousLabel: 'Previous'.translate(),
            customStatusBar: function (currentPage, pageSize, numberItems, criteria, filter) {
                return messagePageGrid(currentPage, pageSize, numberItems, criteria, filter);
            },
            columns: [{
                id: 'gridUsersButtonLabel',
                title: '',
                dataType: 'button',
                width: '10%',
                buttonLabel: function (row, data) {
                    return data.lastName;
                },
                buttonStyle: {
                    cssClasses: [
                        'mafe-grid-button'
                    ]
                },
                onButtonClick: function (row, grid) {
                    if (row.data.customKeys.obj_type === 'group') {
                        restClient.setTypeRequest('get');
                        restClient.functionSuccess = function (xhr, response) {
                            var stringUsers = '', i;
                            for (i = 0; i < response.length; i += 1) {
                                stringUsers = stringUsers + response[i].usr_firstname + ' ' + response[i].usr_lastname + ', ';
                            }
                            stringUsers = stringUsers.substring(0, stringUsers.length - 2);
                            if (stringUsers === '') {
                                stringUsers = 'No users';
                            }
                            quickMessageWindow.show($(row.html).find('a')[0], stringUsers);
                        };
                        restClient.setBaseEndPoint('group/' + row.data.customKeys.sup_uid + '/users');
                        restClient.executeRestClient();
                    }
                }
            }, {
                title: '',
                dataType: 'string',
                columnData: 'sup_name',
                alignmentCell: 'left',
                width: '80%'
            }, {
                id: 'gridUsersButtonAssign',
                title: '',
                dataType: 'button',
                width: '19%',
                buttonStyle: {
                    cssClasses: [
                        'mafe-button-edit'
                    ]
                },
                buttonLabel: function (row, data) {
                    row.getCells()[0].content.style.addClasses([row.data.customKeys.obj_type === 'user' ? 'button-icon-user' : 'button-icon-group']);
                    return 'Assign'.translate();
                },
                onButtonClick: function (row, grid) {
                    grid.removeItem(row);
                    grid.sort('sup_name', 'asc');
                    gridUserList.addItem(row);
                    buttonsUserList.setValue(buttonsUsers.getValue());
                    groupRows(gridUserList, buttonsUsers.getValue());
                    assignee(row);
                }
            }
            ],
            onDropOut: function (item, origin, destiny) {
                assignee(item);
            },
            onDrop: function (a, row) {
                buttonsUsers.setValue(buttonsUserList.getValue());
                groupRows(gridUsers, buttonsUserList.getValue());
            },
            style: {
                cssClasses: [
                    'mafe-designer-assigment-grid'
                ]
            }
        });

        panelTitleUserList = new PMUI.core.Panel({
            layout: 'hbox'
        });
        titleUserList = new PMUI.ui.TextLabel({
            id: 'titleUserList',
            textMode: 'plain',
            text: 'Assigned Users List'.translate(),
            style: {
                cssClasses: [
                    'mafe-designer-assigment-title'
                ]
            }
        });
        panelSearchUserList = new PMUI.core.Panel({
            layout: 'hbox'
        });
        searchGridUserList = new PMUI.field.TextField({
            id: 'searchGridUserList',
            label: ' ',
            placeholder: 'Search ...'.translate()
        });
        panelGridUserList = new PMUI.core.Panel({
            layout: 'hbox'
        });
        buttonsUserList = new PMUI.field.OptionsSelectorField({
            id: 'buttonsUserList',
            orientation: 'horizontal',
            items: [{
                text: 'All'.translate(),
                value: 'all',
                selected: true,
                style: {
                    cssClasses: [
                        'pmui-switch-icon-all'
                    ]
                }
            }, {
                text: 'Users'.translate(),
                value: 'user',
                style: {
                    cssClasses: [
                        'pmui-switch-icon-user'
                    ]
                }
            }, {
                text: 'Groups'.translate(),
                value: 'group',
                style: {
                    cssClasses: [
                        'pmui-switch-icon-group'
                    ]
                }
            }

            ],
            listeners: {
                select: function (item, event) {
                    groupRows(gridUserList, item.value);
                }
            },
            style: {
                cssClasses: [
                    'mafe-assigment-buttons'
                ]
            }
        });
        gridUserList = new PMUI.grid.GridPanel({
            id: 'gridUserList',
            pageSize: pageSizeAssignment,
            behavior: 'dragdropsort',
            filterPlaceholder: 'Search ...'.translate(),
            filterable: true,
            nextLabel: 'Next'.translate(),
            previousLabel: 'Previous'.translate(),
            customStatusBar: function (currentPage, pageSize, numberItems, criteria, filter) {
                return messagePageGrid(currentPage, pageSize, numberItems, criteria, filter);
            },
            emptyMessage: function () {
                var div = document.createElement('div'),
                    span = document.createElement('span');
                div.appendChild(span);
                div.className = 'mafe-grid-panel-empty';
                span.innerHTML = 'Drag & Drop a User or a Group here'.translate();
                return div;
            },
            onEmpty: function (grid, cell) {
                gridUserList.dom.tableContainer.style.overflow = 'hidden';
            },
            columns: [{
                id: 'gridUserListButtonLabel',
                title: '',
                width: '10%',
                dataType: 'button',
                buttonLabel: function (row, data) {
                    return data.lastName;
                },
                buttonStyle: {
                    cssClasses: [
                        'mafe-grid-button'
                    ]
                },
                onButtonClick: function (row, grid) {
                    var stringUsers, i;
                    if (row.data.customKeys.obj_type === 'group') {
                        restClient.setTypeRequest('get');
                        restClient.functionSuccess = function (xhr, response) {
                            stringUsers = '';
                            for (i = 0; i < response.length; i += 1) {
                                stringUsers = stringUsers + response[i].usr_firstname + ' ' + response[i].usr_lastname + ', ';
                            }
                            stringUsers = stringUsers.substring(0, stringUsers.length - 2);
                            if (stringUsers === '') {
                                stringUsers = 'No users';
                            }
                            quickMessageWindow.show($(row.html).find('a')[0], stringUsers);
                        };
                        restClient.setBaseEndPoint('group/' + row.data.customKeys.sup_uid + '/users');
                        restClient.executeRestClient();
                    }
                }
            }, {
                title: '',
                dataType: 'string',
                columnData: 'sup_name',
                width: '80%',
                alignmentCell: 'left'
            }, {
                id: 'gridUserListButtonDelete',
                title: '',
                dataType: 'button',
                width: '10%',
                buttonStyle: {
                    cssClasses: [
                        'mafe-button-delete'
                    ]
                },
                buttonLabel: function (row, data) {
                    row.getCells()[0].content.style.addClasses([row.data.customKeys.obj_type === 'user' ? 'button-icon-user' : 'button-icon-group']);
                    return 'Remove'.translate();
                },
                onButtonClick: function (row, grid) {
                    grid.removeItem(row);
                    grid.sort('sup_name', 'asc');
                    gridUsers.addItem(row);
                    buttonsUsers.setValue(buttonsUserList.getValue());
                    groupRows(gridUsers, buttonsUserList.getValue());
                    remove(row);
                }
            }
            ],
            onDropOut: function (item, origin, destiny) {
                remove(item);
            },
            onDrop: function (a, row) {
                buttonsUserList.setValue(buttonsUsers.getValue());
                groupRows(gridUserList, buttonsUsers.getValue());
            },
            style: {
                cssClasses: [
                    'mafe-designer-assigment-grid'
                ]
            }
        });

        applyStyles = function () {
            gridUsers.dom.toolbar.appendChild(buttonsUsers.getHTML());
            gridUsers.dom.toolbar.style.height = "76px";
            gridUserList.dom.toolbar.appendChild(buttonsUserList.getHTML());
            gridUserList.dom.toolbar.style.height = "76px";
            buttonsUsers.dom.labelTextContainer.style.display = "none";
            buttonsUserList.dom.labelTextContainer.style.display = "none";
            gridUsers.hideHeaders();
            gridUserList.hideHeaders();
            assigmentPanelUserList.setHeight('100%');
            gridUsers.filterControl.html.style.width = "300px";
            gridUserList.filterControl.html.style.width = "300px";
        };
        loadServerData = function () {
            var restClient = new PMRestClient({
                typeRequest: 'post',
                multipart: true,
                data: {
                    calls: [
                        {
                            url: 'project/' + PMDesigner.project.id + '/available-process-supervisors',
                            method: 'GET'
                        }, {
                            url: 'project/' + PMDesigner.project.id + '/process-supervisors',
                            method: 'GET'
                        }
                    ]
                },
                functionSuccess: function (xhr, response) {
                    loadUsers(response[0].response);
                    loadUsersList(response[1].response);
                },
                functionFailure: function (xhr, response) {
                    PMDesigner.msgWinError(response.error.message);
                }
            });
            restClient.setBaseEndPoint('');
            restClient.executeRestClient();
        };
        loadUsers = function (response) {
            var i;
            for (i = 0; i < response.length; i += 1) {
                if (response[i].obj_type === 'user') {
                    gridUsers.addDataItem({
                        sup_uid: response[i].usr_uid,
                        sup_name: response[i].usr_firstname + ' ' + response[i].usr_lastname,
                        obj_type: response[i].obj_type
                    });
                }
                if (response[i].obj_type === 'group') {
                    gridUsers.addDataItem({
                        sup_uid: response[i].grp_uid,
                        sup_name: response[i].grp_name,
                        obj_type: response[i].obj_type
                    });
                }
            }
            gridUsers.sort('sup_name', 'asc');
        };
        loadUsersList = function (response) {
            var i;
            for (i = 0; i < response.length; i += 1) {
                if (response[i].pu_type === 'SUPERVISOR') {
                    gridUserList.addDataItem({
                        sup_uid: response[i].usr_uid,
                        sup_name: response[i].usr_firstname + ' ' + response[i].usr_lastname,
                        obj_type: 'user',
                        pu_type: response[i].pu_type,
                        pu_uid: response[i].pu_uid
                    });
                }
                if (response[i].pu_type === 'GROUP_SUPERVISOR') {
                    gridUserList.addDataItem({
                        sup_uid: response[i].grp_uid,
                        sup_name: response[i].grp_name,
                        obj_type: 'group',
                        pu_type: response[i].pu_type,
                        pu_uid: response[i].pu_uid
                    });
                }
            }
            gridUserList.sort('sup_name', 'asc');
        };
        groupRows = function (grid, value) {
            var i, items;
            if (grid.memorystack === undefined) {
                grid.memorystack = [];
            }
            items = grid.getItems();
            while (grid.memorystack.length > 0) {
                grid.addItem(grid.memorystack.pop());
            }
            if (value !== 'all') {
                for (i = 0; i < items.length; i += 1) {
                    if (items[i].getData().obj_type !== value) {
                        grid.memorystack.push(items[i]);
                        grid.removeItem(items[i]);
                    }
                }
            }
            grid.sort('sup_name', 'asc');
        };
        assignee = function (row) {
            var restClient = new PMRestClient({
                endpoint: 'process-supervisor',
                typeRequest: 'post',
                data: {
                    usr_uid: row.data.customKeys.sup_uid,
                    pu_type: row.data.customKeys.obj_type === 'group' ? 'GROUP_SUPERVISOR' : 'SUPERVISOR'
                },
                functionSuccess: function (xhr, response) {
                    row.data.customKeys.pu_type = response.pu_type;
                    row.data.customKeys.pu_uid = response.pu_uid;
                },
                functionFailure: function (xhr, response) {
                    PMDesigner.msgWinError(response.error.message);
                },
                messageError: 'There are problems saving the assigned user, please try again.'.translate()
            });
            restClient.executeRestClient();
        };
        remove = function (row) {
            var restClient = new PMRestClient({
                endpoint: 'process-supervisor/' + row.data.customKeys.pu_uid,
                typeRequest: 'remove',
                functionSuccess: function () {
                },
                functionComplete: function () {
                },
                functionFailure: function (xhr, response) {
                    PMDesigner.msgWinError(response.error.message);
                }
            });
            restClient.executeRestClient();
        };

        assigmentWindow.open();
        panelTitleUser.addItem(titleUser);
        panelTitleUserList.addItem(titleUserList);
        assigmentWindow.body.style.overflow = "hidden";
        panelGridUser.addItem(gridUsers);
        panelGridUserList.addItem(gridUserList);

        assigmentPanelGlobal.addItem(assigmentPanelUser);
        assigmentPanelGlobal.addItem(assigmentPanelUserList);
        assigmentWindow.addItem(assigmentPanelGlobal);

        assigmentPanelUser.addItem(panelTitleUser);
        assigmentPanelUser.addItem(panelSearchUser);
        assigmentPanelUser.addItem(buttonsUsers);
        assigmentPanelUser.addItem(panelGridUser);

        assigmentPanelUserList.addItem(panelTitleUserList);
        assigmentPanelUserList.addItem(panelSearchUserList);
        assigmentPanelUserList.addItem(buttonsUserList);
        assigmentPanelUserList.addItem(panelGridUserList);

        assigmentWindow.defineEvents();
        applyStyles();

        loadServerData();
    };
}());