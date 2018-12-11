(function () {
    var DialogTypeControl = function (appendTo) {
        this.onClose = new Function();
        this.onSelectItem = new Function();
        DialogTypeControl.prototype.init.call(this, appendTo);
    };
    DialogTypeControl.prototype.init = function (appendTo) {
        var that = this;
        this.dialog = $("<div title='" + "Select a Control".translate() + "' style='padding:10px;'></div>");
        this.dialog.dialog({
            appendTo: appendTo ? appendTo : document.body,
            modal: true,
            autoOpen: true,
            width: 500,
            height: 400,
            resizable: false,
            close: function (event, ui) {
                that.onClose(event, ui);
                that.dialog.remove();
            }
        });
        FormDesigner.main.DialogStyle(this.dialog);

        this.body = $("<div style='border:1px solid #bbb;'></div>");
        this.dialog.append("<p>" + "Please select the control you want to use with your variable.".translate() + "<p>");
        this.dialog.append(this.body);
    };
    DialogTypeControl.prototype.load = function (variable) {
        var controls = [{
            url: "" + $.imgUrl + "fd-ui-text-field.png",
            label: "textfield".translate(),
            render: FormDesigner.main.TypesControl.text
        }, {
            url: "" + $.imgUrl + "fd-ui-text-area.png",
            label: "textarea".translate(),
            render: FormDesigner.main.TypesControl.textarea
        }, {
            url: "" + $.imgUrl + "fd-ui-combo-box.png",
            label: "dropdown".translate(),
            render: FormDesigner.main.TypesControl.dropdown
        }, {
            url: "" + $.imgUrl + "fd-ui-check-boxes-list.png",
            label: "checkbox".translate(),
            render: FormDesigner.main.TypesControl.checkbox
        }, {
            url: "" + $.imgUrl + "fd-ui-radio-buttons-list.png",
            label: "radio".translate(),
            render: FormDesigner.main.TypesControl.radio
        }, {
            url: "" + $.imgUrl + "fd-calendar.png",
            label: 'datetime',
            render: FormDesigner.main.TypesControl.datetime
        }, {
            url: "" + $.imgUrl + "fd-ui-list-box.png",
            label: "suggest".translate(),
            render: FormDesigner.main.TypesControl.suggest
        }, {
            url: "" + $.imgUrl + "fd-ui-text-field-hidden.png",
            label: "hidden".translate(),
            render: FormDesigner.main.TypesControl.hidden
        }
        ];
        this.body.find(">div").remove();
        for (var i = 0; i < controls.length; i++) {
            if (variable.var_field_type === "string" && (controls[i].label === "textfield" || controls[i].label === "textarea" || controls[i].label === "dropdown" || controls[i].label === "checkbox" || controls[i].label === "radio" || controls[i].label === "suggest" || controls[i].label === "hidden")) {
                this.addItem(controls[i]);
            }
            if (variable.var_field_type === "integer" && (controls[i].label === "textfield" || controls[i].label === "dropdown" || controls[i].label === "checkbox" || controls[i].label === "radio" || controls[i].label === "suggest" || controls[i].label === "hidden")) {
                this.addItem(controls[i]);
            }
            if (variable.var_field_type === "float" && (controls[i].label === "textfield" || controls[i].label === "dropdown" || controls[i].label === "checkbox" || controls[i].label === "radio" || controls[i].label === "suggest" || controls[i].label === "hidden")) {
                this.addItem(controls[i]);
            }
            if (variable.var_field_type === "boolean" && (controls[i].label === "dropdown" || controls[i].label === "radio" || controls[i].label === "hidden" || controls[i].label === "checkbox")) {
                this.addItem(controls[i]);
            }
            if (variable.var_field_type === "datetime" && (controls[i].label === "datetime" || controls[i].label === "datetime" || controls[i].label === "suggest" || controls[i].label === "hidden")) {
                this.addItem(controls[i]);
            }
        }
    };
    DialogTypeControl.prototype.addItem = function (control) {
        var that = this;
        var item = $(
            "<div class='fd-list' style='width:auto;height:16px;position:relative;cursor:pointer;'>" +
            "<div style='display:inline-block;vertical-align:middle;'><img src=" + control.url + "></img></div>" +
            "<div style='display:inline-block;margin-left:10px;'>" + control.label + "</div>" +
            "</div>");
        this.body.append(item);
        item.attr("render", control.render);
        item.on("click", function (event) {
            that.onSelectItem(event, item);
            that.dialog.dialog("close").remove();
        });
        return item;
    };
    FormDesigner.extendNamespace('FormDesigner.main.DialogTypeControl', DialogTypeControl);
}());