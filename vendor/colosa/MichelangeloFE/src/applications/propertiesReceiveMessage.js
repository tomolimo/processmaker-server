RowVariableCondition = function () {
    PMUI.form.FormPanel.call(this, {
        layout: 'hbox'
    });
    RowVariableCondition.prototype.init.call(this);
};
RowVariableCondition.prototype = new PMUI.form.FormPanel();
RowVariableCondition.prototype.createHTML = function () {
    var items;
    PMUI.form.FormPanel.prototype.createHTML.call(this);
    items = this.getItems();
    items[0].dom.labelTextContainer.style.display = 'none';
    items[1].dom.labelTextContainer.style.display = 'none';
    items[2].dom.labelTextContainer.style.display = 'none';
    items[2].style.addProperties({display: 'none'});
    items[2].controls[0].button.setButtonType('error');
    return this.html;
};
RowVariableCondition.prototype.init = function () {
    var that = this,
        typeData,
        field,
        remove;
    field = new PMUI.field.TextField({
        id: 'idField',
        name: '',
        required: true,
        controlsWidth: 425,
        proportion: 2.1,
        labelVisible: false
    });
    typeData = new PMUI.field.DropDownListField({
        id: 'idTypeData',
        name: '',
        valueType: 'string',
        value: '',
        readOnly: true,
        controlsWidth: 200,
        labelVisible: false,
        options: [{
            value: 'integer',
            label: 'Integer'.translate()
        }, {
            value: 'string',
            label: 'String'.translate()
        }, {
            value: 'float',
            label: 'Float'.translate()
        }, {
            value: 'boolean',
            label: 'Boolean'.translate()
        }, {
            value: 'date',
            label: 'Date'.translate()
        }, {
            value: 'datetime',
            label: 'Datetime'.translate()
        }
        ]
    });
    remove = new PMUI.field.ButtonField({
        id: 'idRemove',
        value: 'Delete'.translate(),
        labelVisible: false,
        handler: function (e, a) {
            that.getParent().removeItem(that);
        },
        name: 'delete',
        controlsWidth: 60
    });
    that.addItem(field);
    that.addItem(typeData);
    that.addItem(remove);
};

PropertiesReceiveMessage = function (menuOption) {
    this.variables = [];
    this.onApply = new Function();
    this.onCancel = new Function();
    this.menuOption = menuOption;
    Mafe.Window.call(this);
    PropertiesReceiveMessage.prototype.init.call(this);
};
PropertiesReceiveMessage.prototype = new Mafe.Window();
PropertiesReceiveMessage.prototype.init = function () {
    var that = this;
    that.setTitle(that.menuOption.getMenuTargetElement().evn_name);
    that.setButtons([
        new PMUI.ui.Button({
            id: 'btnClose',
            text: 'Cancel'.translate(),
            buttonType: 'error',
            height: 31,
            handler: function () {
                that.close();
                that.onCancel();
            }
        }),
        new PMUI.ui.Button({
            id: 'windowDynaformInformationSaveOpen',
            text: 'Apply'.translate(),
            buttonType: 'success',
            height: 31,
            handler: function () {
                that.onApply();
            }
        })
    ]);
    that.buttonAdd = new PMUI.ui.Button({
        text: 'Add Variable'.translate(),
        buttonType: 'success',
        height: 31,
        style: {cssProperties: {marginLeft: '50px', marginTop: '10px'}},
        handler: function () {
            that.addVariable();
        }
    });
    that.form = new Mafe.Form({
        title: that.menuOption.getMenuTargetElement().evn_name,
        width: DEFAULT_WINDOW_WIDTH - 60,
        style: {cssProperties: {'margin-left': '35px'}}
    });
    that.conditionForm = new Mafe.Form({
        visibleHeader: false,
        width: DEFAULT_WINDOW_WIDTH - 60,
        style: {cssProperties: {'margin-left': '35px'}},
        items: [{
            label: 'Condition',
            labelPosition: 'top',
            pmType: 'textarea',
            rows: 100,
            style: {cssClasses: ['mafe-textarea-resize']}
        }
        ]
    });

    that.addItem(that.buttonAdd);
    that.addItem(that.form);
    that.addItem(that.conditionForm);

    that.addVariable();
};
PropertiesReceiveMessage.prototype.addVariable = function () {
    var that = this, a, i;
    a = new RowVariableCondition(this.variables);
    that.form.addItem(a);
    //force padding
    for (i = 0; i < that.form.getItems().length; i += 1) {
        that.form.getItems()[i].style.addProperties({'padding': 'initial'});
    }
};
