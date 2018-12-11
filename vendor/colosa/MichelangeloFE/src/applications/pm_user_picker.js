var UserPicker = function (options) {
    this.relatedField = null;
    this.processId = null;
    this.workspace = null;
    this.window = null;
    this.currentVariable = {};
    this.pageSize = 10;
    UserPicker.prototype.init.call(this, options);
};

UserPicker.prototype.type = 'UserPicker';

UserPicker.prototype.family = 'UserPicker';

UserPicker.prototype.init = function (options) {
    var defaults = {
        relatedField: null,
        processId: PMDesigner.project.projectId,
        workspace: WORKSPACE
    };

    jQuery.extend(true, defaults, options);

    this.setRelatedField(defaults.relatedField)
        .setProcessId(defaults.processId)
        .setWorkspace(defaults.workspace);
};

UserPicker.prototype.setRelatedField = function (field) {
    if (field instanceof PMUI.form.Field) {
        this.relatedField = field;
    }
    return this;
};

UserPicker.prototype.setProcessId = function (process) {
    this.processId = process;
    return this;
};

UserPicker.prototype.setWorkspace = function (workspace) {
    this.workspace = workspace;
    return this;
};

UserPicker.prototype.open = function (callback) {
    var w, rc, fieldC, dataGrid, panel, textField, that = this, button, panelFilter;
    button = new PMUI.ui.Button({
        id: 'insertUser',
        text: 'Insert User'.translate(),
        handler: function () {
            if (callback && callback.success && typeof callback.success === 'function') {
                callback.success.call(that, that.currentVariable);
            }
            that.close();
        },
        disabled: true
    });

    textField = new PMUI.field.TextField({
        id: 'textFieldSearch',
        label: '',
        placeholder: 'Search ...'.translate()
    });

    w = new PMUI.ui.Window({
        id: 'processVariables',
        title: 'Process Users'.translate(),
        width: 480,
        height: 420,
        closable: true,
        modal: true,
        buttons: [
            button
        ],
        buttonsPosition: 'center'
    });

    textField = new PMUI.field.TextField({
        id: 'textFieldSearch',
        label: '',
        placeholder: 'Search ...'.translate(),
        width: 150
    });

    dataGrid = new PMUI.grid.GridPanel({
        id: 'gridPanel',
        selectable: true,
        pageSize: this.pageSize,
        nextLabel: 'Next'.translate(),
        previousLabel: 'Previous'.translate(),
        customStatusBar: function (currentPage, pageSize, numberItems, criteria, filter) {
            return messagePageGrid(currentPage, pageSize, numberItems, criteria, filter);
        },
        columns: [
            {
                id: 'gridPanelUserName',
                title: 'User Name'.translate(),
                columnData: 'usr_username',
                width: 150,
                sortable: true,
                alignmentCell: 'left'
            },
            {
                id: 'gridPanelRole',
                title: 'Role'.translate(),
                columnData: 'usr_role',
                width: 230,
                sortable: false,
                alignmentCell: 'left'
            }
        ],
        onRowClick: function (row, data) {
            button.enable();
            that.currentVariable.username = data.usr_username;
            that.currentVariable.uid = data.usr_uid;
        }

    });

    panelFilter = new PMUI.core.Panel({
        id: 'panelFilter',
        layout: 'vbox',
        items: [/*fieldC,*/ textField]
    });

    panel = new PMUI.core.Panel({
        id: 'paneldataGrid',
        layout: 'vbox',
        items: [panelFilter, dataGrid]
    });

    rc = new PMRestClient({
        typeRequest: 'get',
        functionSuccess: function (xhr, response) {
            console.log(response);
            that.window = w;
            dataGrid.setDataItems(response);
            w.open();
            w.showFooter();
            w.addItem(panel);
            panelFilter.setWidth(430);
            textField.controls[0].onKeyUp = function () {
                console.log(textField.controls[0].html.value);
                dataGrid.filter(textField.controls[0].html.value);
            };
            dataGrid.dom.toolbar.style.display = 'none';
            textField.dom.labelTextContainer.innerHTML = '';
            textField.dom.labelTextContainer.style.marginTop = 5;
            panel.style.addProperties({'padding-left': 20});
            textField.dom.labelTextContainer.style.display = 'none';
            textField.controls[0].setWidth(200);
        },
        functionFailure: function (xhr, response) {
            PMDesigner.msgWinError(response.error.message);
        }
    });
    rc.setBaseEndPoint('users').executeRestClient();
};

UserPicker.prototype.close = function () {
    if (this.window) {
        this.window.close();
        this.window = null;
    }
};
