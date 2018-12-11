(function () {
    var DialogCreateVariable = function (appendTo, type, options, var_name) {
        this.onSave = new Function();
        this.onSelect = new Function();
        this.onClose = new Function();
        this.appendTo = appendTo;
        this.type = type;
        this.options = options;
        this.var_name = var_name;
        DialogCreateVariable.prototype.init.call(this);
    };
    DialogCreateVariable.prototype.init = function () {
        var that = this;
        this.var_dbconnection = "workflow";
        this.var_sql = "";
        this.var_accepted_values = that.options;
        this.inp_doc_uid = "";

        this.add = $("<a href='#' class='fd-button fd-button-create' style='padding:7px 10px 7px 35px;margin-bottom:5px;position:absolute;right:10px;top:10px;'>" + "Variables".translate() + "</a>");
        this.add.on("click", function () {
            that.dialog.dialog("close");
            var pmvariables = new PMVariables();
            pmvariables.onWindowClose = function (variable) {
                if (variable !== null && variable.var_name_old === that.var_name) {
                    that.onSave(variable);
                }
            };
            pmvariables.load();
            return false;
        });

        this.buttonSave = $("<a href='#' class='fd-button fd-button-success'>" + "Save".translate() + "</a>");
        this.buttonSave.on("click", function () {
            that.save();
            return false;
        });
        this.cancel = $("<a href='#' class='fd-button fd-button-no'>" + "Cancel".translate() + "</a>");
        this.cancel.on("click", function () {
            that.dialog.dialog("close").remove();
            return false;
        });
        this.buttons = $("<div class='fd-button-panel'><div style='float:right'></div></div>");
        this.buttons.find("div:nth-child(1)").append(this.cancel);
        this.buttons.find("div:nth-child(1)").append(this.buttonSave);

        this.optionSelect = $(
            "   <div style='padding:10px;'>" +
            "       <label>" +
            "           <input type='radio' id='optionCreate' name='optionCreateSelect' checked/>" +
            "           <span style='width:110px;display:inline-block;vertical-align:super;'>" + "Create variable".translate() + "</span>" +
            "       </label>" +
            "       <label>" +
            "           <input type='radio' id='optionSelect' name='optionCreateSelect'/>" +
            "           <span style='width:110px;display:inline-block;vertical-align:super;'>" + "Select variable".translate() + "</span>" +
            "       </label>" +
            "   </div>");
        this.optionSelect.find("#optionCreate").on("click", function () {
            that.buttonSave.show();
            that.content.show();
            that.selectVariable.dialog.hide();
            that.dialog.css({height: heightCreate});
        });
        this.optionSelect.find("#optionSelect").on("click", function () {
            that.buttonSave.hide();
            that.content.hide();
            that.selectVariable.dialog.show();
            that.selectVariable.load();
            that.dialog.css({height: 452});
        });

        this.selectVariable = new FormDesigner.main.DialogVariable(null, this.type);
        this.selectVariable.onClick = function (variable) {
            that.onSelect(variable);
        };
        this.selectVariable.dialog.dialog("destroy");
        this.selectVariable.dialog.hide();

        this.content = $(
            "<div>" +
            "   <div style='padding:10px;'>" +
            "       <label><span style='width:110px;display:inline-block;vertical-align:top;'>" + "Variable Name:".translate() + "</span>" +
            "           <input type='text' name='var_name' id='var_name' style='width:200px;'/>" +
            "       </label>" +
            "   </div>" +
            "   <div style='padding:0px 10px 10px 10px;'>" +
            "       <a title='" + "Settings".translate() + "' style='cursor:pointer;margin-bottom:5px;text-decoration:underline;' id='buttonSettings'>" + "Settings".translate() + "</a>" +
            "   </div>" +
            "   <div id='settings' style='padding: 0px 10px 10px;'>" +
            "       <label style='display:block;margin-bottom:5px;'>" +
            "           <span style='width:110px;display:inline-block;vertical-align:top;'>" + "Type:".translate() + "</span>" +
            "           <select id='var_field_type' style='width:auto;'></select>" +
            "       </label>" +
            "       <label style='display:block;margin-bottom:5px;' id='var_dbconnection'>" +
            "           <span style='width:110px;display:inline-block;vertical-align:top;'>" + "Database Connection:".translate() + "</span>" +
            "           <div style='overflow:hidden;width:250px;display:inline-block;'><a style='cursor:pointer;white-space:nowrap;text-decoration:underline;' id='buttonDbConnection'>PM Database</a></div>" +
            "       </label>" +
            "       <label style='display:block;margin-bottom:5px;' id='var_sql'>" +
            "           <span style='width:110px;display:inline-block;vertical-align:top;'>" + "Sql:".translate() + "</span>" +
            "           <div style='overflow:hidden;width:250px;display:inline-block;'><a style='cursor:pointer;white-space:nowrap;text-decoration:underline;' id='buttonSql'>...</a></div>" +
            "       </label>" +
            "       <label style='display:block;margin-bottom:5px;' id='var_accepted_values'>" +
            "           <span style='width:110px;display:inline-block;vertical-align:top;'>" + "Options:".translate() + "</span>" +
            "           <div style='overflow:hidden;width:250px;display:inline-block;'><a style='cursor:pointer;white-space:nowrap;text-decoration:underline;' id='buttonOptions'>[]</a></div>" +
            "       </label>" +
            "       <label style='display:block;margin-bottom:5px;' id='inp_doc_uid'>" +
            "           <span style='width:110px;display:inline-block;vertical-align:top;'>" + "Input Document<span style='color:red;'>*</span>:".translate() + "</span>" +
            "           <div style='overflow:hidden;width:250px;display:inline-block;'><a style='cursor:pointer;white-space:nowrap;text-decoration:underline;' id='buttonInputDocument'>...</a></div>" +
            "       </label>" +
            "   </div>" +
            "</div>"
        );

        this.content.find("#settings").css({display: "none"});

        var show = true;
        var heightCreate = 180;
        this.content.find("#buttonSettings").on("click", function () {
            if (show) {
                heightCreate = 260;
                that.dialog.css({height: heightCreate});
                that.content.find("#settings").css({display: "block"});
                show = false;
            } else {
                heightCreate = 165;
                that.dialog.css({height: heightCreate});
                that.content.find("#settings").css({display: "none"});
                show = true;
            }
            return false;
        });

        var a;
        this.content.find("#buttonDbConnection").on("click", function () {
            a = new FormDesigner.main.DialogDBConnection(null);
            a.onClick = function (option) {
                a.dialog.dialog("close").remove();
                that.var_dbconnection = option.value;
                that.content.find("#buttonDbConnection").text(option.label);
            };
            return false;
        });
        this.content.find("#buttonSql").on("click", function () {
            a = new FormDesigner.main.DialogSql(null);
            a.onSave = function () {
                that.var_sql = a.editor.getValue();
                that.content.find("#buttonSql").text(a.editor.getValue());
            };
            a.editor.setValue(that.var_sql);
            return false;
        });
        this.content.find("#buttonOptions").on("click", function () {
            a = new FormDesigner.main.DialogOptions(null, that.var_accepted_values);
            a.onApply = function () {
                that.var_accepted_values = a.getOptions();
                that.content.find("#buttonOptions").text(JSON.stringify(a.getOptions()));
            };
            if (that.content.find("#var_field_type").val() === "boolean") {
                a.setOptionsBoolean();
            }
            return false;
        });
        this.content.find("#buttonInputDocument").on("click", function () {
            a = new FormDesigner.main.DialogInputDocument(null);
            a.onClick = function (option) {
                a.dialog.dialog("close").remove();
                that.inp_doc_uid = option.value;
                that.content.find("#buttonInputDocument").text(option.label);
            };
            return false;
        });

        this.dialog = $("<div title='" + "Create/Select Variable".translate() + "'></div>");
        this.dialog.dialog({
            appendTo: this.appendTo ? this.appendTo : document.body,
            modal: true,
            autoOpen: true,
            width: 500,
            height: 195,
            resizable: false,
            position: ["center", 50],
            close: function (event, ui) {
                that.onClose(event, ui);
                that.dialog.remove();
            }
        });
        this.dialog.append(this.optionSelect);
        this.dialog.append(this.content);
        this.dialog.append(this.selectVariable.dialog);
        this.dialog.append(this.buttons);
        this.dialog.append(this.add);
        FormDesigner.main.DialogStyle(this.dialog);

        $.validkeys(that.content.find("#var_name")[0], ['isbackspace', 'isnumber', 'isletter', 'isunderscore']);
        that.content.find("#var_name").focus();

        //data
        that.content.find("#var_field_type").on("change", function () {
            that.setFieldsVisibility(this.value);
        });
        var types = [{
            value: "string", label: "String"
        }, {
            value: "integer", label: "Integer"
        }, {
            value: "float", label: "Float"
        }, {
            value: "boolean", label: "Boolean"
        }, {
            value: "datetime", label: "Datetime"
        }, {
            value: "grid", label: "Grid"
        }, {
            value: "array", label: "Array"
        }, {
            value: "file", label: "File"
        }, {
            value: "multiplefile", label: "Multiple File"
        }
        ];
        that.content.find("#var_field_type").empty();
        for (var i = 0; i < types.length; i++) {
            $.validDataTypeAndControlType(types[i].value, that.type, function () {
                that.addItem(types[i]);
            });
        }
        that.setFieldsVisibility(that.content.find("#var_field_type").val());
    };
    DialogCreateVariable.prototype.save = function () {
        var that = this;
        if (!that.isValid()) {
            var a = new FormDesigner.main.DialogMessage(null, "alert", "A valid variable starts with a letter or underscore, followed by any number of letters, numbers, or underscores.".translate());
            a.onClose = function () {
            };
            a.onAccept = function () {
            };
            return false;
        }
        if (!that.isValidInputDocument()) {
            var a = new FormDesigner.main.DialogMessage(null, "alert", "The input document is required, please select the value.".translate());
            a.onClose = function () {
            };
            a.onAccept = function () {
            };
            return false;
        }
        $.ajax({
            url: HTTP_SERVER_HOSTNAME + "/api/1.0/" + WORKSPACE + "/project/" + PMDesigner.project.id + "/process-variable",
            data: JSON.stringify({
                var_name: that.content.find("#var_name").val(),
                var_field_type: that.content.find("#var_field_type").val(),
                var_dbconnection: that.var_dbconnection,
                var_sql: that.var_sql,
                var_label: that.content.find("#var_field_type").val(),
                var_default: "",
                var_accepted_values: that.var_accepted_values,
                var_field_size: 10,
                inp_doc_uid: that.inp_doc_uid
            }),
            method: "POST",
            contentType: "application/json",
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Authorization", "Bearer " + PMDesigner.project.keys.access_token);
            },
            success: function (variable) {
                that.dialog.dialog("close").remove();
                that.onSave(variable);
            },
            error: function (responses) {
                var msg;
                try {
                    msg = JSON.parse(responses.responseText).error.message;
                } catch (e) {
                    msg = "Error";
                }
                var a = new FormDesigner.main.DialogMessage(null, "alert", msg);
                a.onClose = function () {
                };
                a.onAccept = function () {
                };
            }
        });
    };
    DialogCreateVariable.prototype.isValid = function () {
        return /^[a-zA-Z_\x7f-\xff]+[a-zA-Z0-9_\x7f-\xff]*$/.test(this.content.find("#var_name").val());
    };
    DialogCreateVariable.prototype.isValidInputDocument = function () {
        if (this.content.find("#var_field_type").val() === "file" || this.content.find("#var_field_type").val() === "multipleFile") {
            return this.inp_doc_uid !== "";
        }
        return true;
    };
    DialogCreateVariable.prototype.setVarName = function (var_name) {
        this.content.find("#var_name").val(var_name);
    };
    DialogCreateVariable.prototype.addItem = function (type) {
        var that = this;
        that.content.find("#var_field_type").append("<option value='" + type.value + "'>" + type.label + "</option>");
    };
    DialogCreateVariable.prototype.setFieldsVisibility = function (type) {
        var that = this;
        that.content.find("#var_dbconnection").show();
        that.content.find("#var_sql").show();
        that.content.find("#var_accepted_values").show();
        that.content.find("#inp_doc_uid").hide();
        if (type === "boolean") {
            that.content.find("#var_dbconnection").hide();
            that.content.find("#var_sql").hide();
            that.var_accepted_values = [{value: "1", label: "true"}, {value: "0", label: "false"}];
            that.content.find("#buttonOptions").text(JSON.stringify(that.var_accepted_values));
        }
        if (type === "datetime" || type === "grid" || type === "file" || type === "multiplefile") {
            that.content.find("#var_dbconnection").hide();
            that.content.find("#var_sql").hide();
            that.content.find("#var_accepted_values").hide();
        }
        if (type === "file") {
            that.content.find("#inp_doc_uid").show();
        }
    };
    FormDesigner.extendNamespace('FormDesigner.main.DialogCreateVariable', DialogCreateVariable);
}());