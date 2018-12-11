var CriteriaUserField = function (options) {
    this.renderType = (options && options.renderType) || "text";
    PMUI.field.TextField.call(this, options);
    this.process = null;
    this.workspace = null;
    this.buttonHTML = null;
    this.rows = options.rows;
    this.user_uid = null;
    CriteriaUserField.prototype.init.call(this, options);
};

CriteriaUserField.prototype = new PMUI.field.TextField();

CriteriaUserField.prototype.setProcess = function (process) {
    this.process = process;
    return this;
};

CriteriaUserField.prototype.setWorkspace = function (workspace) {
    this.workspace = workspace;
    return this;
};

CriteriaUserField.prototype.init = function (options) {
    var defaults = {
        process: PMDesigner.project.projectId,
        workspace: WORKSPACE
    };
    jQuery.extend(true, defaults, options);
    this.setProcess(defaults.process)
        .setWorkspace(defaults.workspace);
};

CriteriaUserField.prototype.createVariablePicker = function () {
    var vp = new UserPicker({
        relatedField: this,
        processId: this.process
    });
    return vp;
};

CriteriaUserField.prototype.setControls = function () {
    if (this.controls.length) {
        return this;
    }
    if (this.renderType === 'text') {
        this.controls.push(new PMUI.control.TextControl());
    } else {
        this.controls.push(new PMUI.control.TextAreaControl({style: {cssProperties: {resize: 'vertical'}}}));
    }
    return this;
};

CriteriaUserField.prototype.createCallBack = function () {
    var that = this, oldValue, newValue, init = 0;
    return {
        success: function (variable) {
            init = that.controls[0].html.selectionStart;
            prevText = that.controls[0].html.value.substr(0, init);
            lastText = that.controls[0].html.value.substr(that.controls[0].html.selectionEnd, that.controls[0].html.value.length);
            newValue = variable.username;

            that.setValue(newValue);
            that.user_uid = variable.uid;
            that.controls[0].html.selectionEnd = init + variable.username.length;
        }
    };
};

CriteriaUserField.prototype.createHTML = function () {
    var button, that = this;
    PMUI.field.TextField.prototype.createHTML.call(this);

    button = new PMUI.ui.Button({
        id: 'buttonCriteriaUserField',
        text: '...',
        handler: function () {
            that.createVariablePicker().open(that.createCallBack());
        },
        style: {
            cssProperties: {
                background: '#2d3e50',
                fontSize: 18,
                paddingLeft: '15px',
                paddingRight: '15px',
                borderRadius: '4px',
                verticalAlign: 'top'
            }
        }
    });

    this.buttonHTML = button;
    $(this.helper.html).before(button.getHTML())
    this.buttonHTML.style.addProperties({"margin-left": "10px"});
    this.buttonHTML.html.tabIndex = -1;

    if (this.rows != null)
        this.controls[0].setHeight(this.rows);
    button.defineEvents();

    return this.html;
};

// Overwrite original init function for FormItemFactory
PMUI.form.FormItemFactory.prototype.init = function () {
    var defaults = {
        products: {
            "criteria": CriteriaUserField,
            "field": PMUI.form.Field,
            "panel": PMUI.form.FormPanel,
            "text": PMUI.field.TextField,
            "password": PMUI.field.PasswordField,
            "dropdown": PMUI.field.DropDownListField,
            "radio": PMUI.field.RadioButtonGroupField,
            "checkbox": PMUI.field.CheckBoxGroupField,
            "textarea": PMUI.field.TextAreaField,
            "datetime": PMUI.field.DateTimeField,
            "optionsSelector": PMUI.field.OptionsSelectorField,
            "buttonField": PMUI.field.ButtonField,
            "annotation": PMUI.field.TextAnnotationField
        },
        defaultProduct: "panel"
    };
    this.setProducts(defaults.products)
        .setDefaultProduct(defaults.defaultProduct);
};
