var VariablePicker = function (options) {
    this.relatedField = null;
    this.processId = null;
    this.workspace = null;
    this.window = null;
    this.currentVariable = null;
    this.pageSize = 10;
    VariablePicker.prototype.init.call(this, options);
};

VariablePicker.prototype.type = 'VariablePicker';

VariablePicker.prototype.family = 'VariablePicker';

VariablePicker.prototype.init = function (options) {
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

VariablePicker.prototype.setRelatedField = function (field) {
    if (field instanceof PMUI.form.Field) {
        this.relatedField = field;
    }
    return this;
};

VariablePicker.prototype.setProcessId = function (process) {
    this.processId = process;
    return this;
};

VariablePicker.prototype.setWorkspace = function (workspace) {
    this.workspace = workspace;
    return this;
};

VariablePicker.prototype.getURL = function () {
    var url = '/api/1.0/' + this.workspace + '/project/' + this.processId + '/variables';
    return url;
};

VariablePicker.prototype.open = function (callback) {
    var w, rc, fieldC, dataGrid, panel, textField, that = this;

    button = new PMUI.ui.Button({
        id: 'insertVariable',
        text: 'Insert Variable'.translate(),
        handler: function () {
            if (callback && callback.success && typeof callback.success === 'function') {
                that.currentVariable = fieldC.getValue() + that.currentVariable;
                callback.success.call(that, that.currentVariable);
            }
            that.returnFocus();
            that.close();
        },
        disabled: true
    });

    textField = new PMUI.field.TextField({
        id: 'textFieldSearch',
        label: '',
        placeholder: 'Text to Search'.translate()
    });

    w = new PMUI.ui.Window({
        id: 'processVariables',
        title: 'Process Variables'.translate(),
        width: 480,
        height: 475,
        closable: true,
        modal: true,
        buttons: [
            button
        ],
        buttonsPosition: 'center'
    });

    fieldC = new PMUI.field.DropDownListField({
        id: 'prefixDropDownListField',
        label: 'Prefix'.translate(),
        helper: '@@ string, @# float, @% integer, @= original type, @& object.'.translate(),
        options: [
            {
                id: 'prefixDropDownListField1',
                label: '@@',
                value: '@@'
            },
            {
                id: 'prefixDropDownListField2',
                label: '@#',
                value: '@#'
            },
            {
                id: 'prefixDropDownListField3',
                label: '@%',
                value: '@%'
            },
            {
                id: 'prefixDropDownListField6',
                label: '@=',
                value: '@='
            },
            {
                id: 'prefixDropDownListField7',
                label: '@&',
                value: '@&'
            }
        ],
        onChange: function (newValue, oldValue) {
        }
    });

    textField = new PMUI.field.TextField({
        id: 'textFieldSearch',
        label: '',
        placeholder: 'Text to search'.translate(),
        width: 150
    });

    dataGrid = new PMUI.grid.GridPanel({
        id: 'gridPanel',
        selectable: true,
        pageSize: this.pageSize,
        nextLabel: 'Next'.translate(),
        previousLabel: 'Previous'.translate(),
        tableContainerHeight: 280,
        customStatusBar: function (currentPage, pageSize, numberItems, criteria, filter) {
            return messagePageGrid(currentPage, pageSize, numberItems, criteria, filter);
        },
        columns: [
            {
                id: 'gridPanelVariable',
                title: 'Variable'.translate(),
                columnData: 'var_name',
                width: 150,
                sortable: true,
                alignmentCell: 'left'
            },
            {
                id: 'gridPanelLabel',
                title: 'Type'.translate(),
                columnData: 'var_label',
                width: 230,
                sortable: false,
                alignmentCell: 'left'
            }
        ],
        onRowClick: function (row, data) {
            button.enable();
            that.currentVariable = data.var_name;
        }

    });

    panelFilter = new PMUI.core.Panel({
        id: 'panelFilter',
        layout: 'hbox',
        items: [fieldC, textField]
    });

    panel = new PMUI.core.Panel({
        id: 'paneldataGrid',
        layout: 'vbox',
        items: [panelFilter, dataGrid]
    });

    rc = new PMRestClient({
        typeRequest: 'get',
        functionSuccess: function (xhr, response) {
            that.window = w;
            dataGrid.setDataItems(response);
            w.open();
            w.showFooter();
            w.addItem(panel);
            panelFilter.setWidth(430);
            fieldC.setControlsWidth(70);
            textField.controls[0].onKeyUp = function () {
                dataGrid.filter(textField.controls[0].html.value);
            };
            dataGrid.dom.toolbar.style.display = 'none';
            $(dataGrid.dom.footer).css("margin-top", "0px");
            $(dataGrid.dom.footer).css("position", "static");
            $(dataGrid.dom.footer).css("padding-left", "10px");
            $(dataGrid.dom.footer).css("padding-right", "10px");

            textField.dom.labelTextContainer.innerHTML = '';
            textField.dom.labelTextContainer.style.marginTop = 5;
            fieldC.dom.labelTextContainer.style.paddingLeft = 20;
            panel.style.addProperties({'padding-left': 20});
            fieldC.dom.labelTextContainer.style.width = 60;
            textField.dom.labelTextContainer.style.display = 'none';
            textField.controls[0].setWidth(200);
            $(dataGrid.html).find(".pmui-gridpanel-footer").css("position", "static");
            $(dataGrid.html).find(".pmui-gridpanel-footer").css("margin-top", "0px");
        }
    });
    rc.setBaseEndPoint('projects/' + this.processId + '/variables').executeRestClient();
};

VariablePicker.prototype.close = function () {
    if (this.window) {
        this.window.close();
        this.window = null;
    }
};

/**
 * Set focus to Input of the Variable Picker
 */
VariablePicker.prototype.returnFocus = function () {
    var that = this;
    if (that.relatedField && that.relatedField.html) {
        jQuery(that.relatedField.html).find(":input").focus();
    }
};
