(function () {
    var Grid = function (parent) {
        this.onSelect = new Function();
        this.onRemove = new Function();
        this.onRemoveItem = new Function();
        this.onSetProperty = new Function();
        this.onVariableDrawDroppedItem = new Function();
        this.onDrawControl = new Function();
        this.parent = parent;
        this.properties = null;
        this.variable = parent.variable;
        this.disabled = false;
        Grid.prototype.init.call(this);
    };
    Grid.prototype.init = function () {
        var that = this;
        this.body = $("" +
            "<div class='itemVariables itemControls gridCellDragDrop fd-gridForm-grid' style='position:relative;font-size:11px;'>" +
            "<div class='fd-gridForm-grid-griditem-gridtitle'></div>" +
            "<div class='fd-gridForm-grid-griditem-gridplaceholder'>" + "Grid: drag & drop controls.<br>Supports: textbox, textarea, dropdown, checkbox, datetime, suggest, hidden, link, multiplefile.".translate() + "</div>" +
            "</div>");
        this.body.sortable({
            placeholder: "fd-gridForm-grid-placeholder",
            connectWith: ".gridCellDragDrop",
            items: ">*:not(.fd-gridForm-grid-griditem-gridtitle):not(.fd-gridForm-grid-griditem-gridplaceholder)",
            receive: function (event, ui) {
                that.sourceNode = ui.sender;
                that.targetNode = $(this);
            },
            stop: function (event, ui) {
                if (ui.item.attr("variable"))
                    that.variable = JSON.parse(ui.item.attr("variable"));
                that.targetNode = that.targetNode ? $(ui.item[0].parentNode) : that.targetNode;
                if (ui.item.attr("render")) {
                    $(ui.item).remove();
                    var properties = that.drawDroppedItem(ui.item.attr("render"));
                    that.onDrawControl(properties);
                }
            }
        });
        this.body.on("click", function (e) {
            e.stopPropagation();
            $.designerSelectElement(this, function () {
                if (that.disabled === true) {
                    return false;
                }
                that.onRemove();
            });
            that.onSelect(that.properties);
        });
        this.body.data("objectInstance", this);
        this.properties = new FormDesigner.main.Properties(FormDesigner.main.TypesControl.grid, this.body, that);
        this.properties.onSet = function (prop, value) {
            that.onSetProperty(prop, value, that);
            if (prop === "label") {
                that.body.find(".fd-gridForm-grid-griditem-gridtitle").text(value);
            }
        };
        this.properties.onClick = function (property) {
            var dialogCreateVariable;
            if (property === "variable") {
                dialogCreateVariable = new FormDesigner.main.DialogCreateVariable(null, FormDesigner.main.TypesControl.grid, [], that.properties.get()[property].value);
                dialogCreateVariable.onSave = function (variable) {
                    that.setVariable(variable);
                };
                dialogCreateVariable.onSelect = function (variable) {
                    dialogCreateVariable.dialog.dialog("close");
                    that.setVariable(variable);
                };
                FormDesigner.getNextNumberVar(that.getData(), that.properties, function (nextVar) {
                    dialogCreateVariable.setVarName(nextVar);
                });
            }
        };
        this.properties.onClickClearButton = function (property) {
            var b;
            if (property === "variable" && that.properties[property].value !== "") {
                var a = new FormDesigner.main.DialogConfirmClearVariable();
                a.onAccept = function () {
                    var label = that.properties.label.value;
                    that.parent.setNextLabel(that.properties);

                    that.properties.id.node.value = that.properties.id.value;
                    b = that.properties.set("variable", "");
                    b.node.textContent = "...";
                    b = that.properties.set("dataType", "");
                    b.node.textContent = "";
                    b = that.properties.set("label", label);
                    b.node.value = label;
                    b = that.properties.set("protectedValue", false);
                    b.node.checked = false;
                };
            }
        };
    };
    Grid.prototype.drawDroppedItem = function (render) {
        var that = this, properties = null;
        switch (render) {
            case FormDesigner.main.TypesControl.variable:
                if (that.onVariableDrawDroppedItem(that.variable) === false)
                    return;
                if (that.isVariableUsed(that.variable.var_uid)) {
                    var dialogInformation = new FormDesigner.main.DialogInformation();
                    dialogInformation.onAccept = function () {
                    };
                    return;
                }
                var dialogTypeControl = new FormDesigner.main.DialogTypeControl();
                dialogTypeControl.load(that.variable);
                dialogTypeControl.onSelectItem = function (event, item) {
                    that.drawDroppedItem(item.attr("render"));
                    that.variable = null;
                };
                dialogTypeControl.onClose = function () {
                    that.variable = null;
                };
                break;
            case that.inTypesControl(render):
                var gridItem = new FormDesigner.main.GridItem(render, that.variable, that);
                gridItem.onSelect = function (properties) {
                    that.onSelect(properties);
                };
                gridItem.onRemove = function () {
                    that.onRemoveItem();
                };
                gridItem.onSetProperty = function (prop, value, target) {
                    that.onSetProperty(prop, value, target);
                };
                that.targetNode.append(gridItem.html);
                properties = gridItem.properties;
                break;
        }
        return properties;
    };
    Grid.prototype.inTypesControl = function (val) {
        //uncomment to add new elements
        if (
            //val === FormDesigner.main.TypesControl.title ||
        //val === FormDesigner.main.TypesControl.subtitle ||
        //val === FormDesigner.main.TypesControl.label ||
        val === FormDesigner.main.TypesControl.link ||
            //val === FormDesigner.main.TypesControl.image ||
        val === FormDesigner.main.TypesControl.file ||
        val === FormDesigner.main.TypesControl.multipleFile ||
            //val === FormDesigner.main.TypesControl.submit ||
            //val === FormDesigner.main.TypesControl.button ||
        val === FormDesigner.main.TypesControl.text ||
        val === FormDesigner.main.TypesControl.textarea ||
        val === FormDesigner.main.TypesControl.dropdown ||
        val === FormDesigner.main.TypesControl.checkbox ||
            //render === FormDesigner.main.TypesControl.checkgroup ||
            //val === FormDesigner.main.TypesControl.radio ||
        val === FormDesigner.main.TypesControl.datetime ||
        val === FormDesigner.main.TypesControl.suggest ||
        val === FormDesigner.main.TypesControl.hidden
        //val === FormDesigner.main.TypesControl.annotation ||
        //val === FormDesigner.main.TypesControl.geomap ||
        //val === FormDesigner.main.TypesControl.qrcode ||
        //val === FormDesigner.main.TypesControl.signature ||
        //val === FormDesigner.main.TypesControl.imagem ||
        //val === FormDesigner.main.TypesControl.audiom ||
        //val === FormDesigner.main.TypesControl.videom
        ) {
            return val;
        }
        new FormDesigner.main.DialogUnsupported();//todo
        return null;
    };
    Grid.prototype.getData = function () {
        var columns = [], obj;
        this.body.find(">div").each(function (i, ele) {
            obj = $(ele).data("objectInstance");
            if (obj) {
                columns.push(obj.getData());
            }
        });
        var prop = {};
        var a = this.properties.get();
        for (var b in a) {
            prop[b] = a[b].value;
        }
        prop["columns"] = columns;
        prop["title"] = prop.label;
        return prop;
    };
    Grid.prototype.getVariables = function () {
        var obj, variables = [], variable, i;
        this.body.find(">div").each(function (ie, ele) {
            obj = $(ele).data("objectInstance");
            if (obj) {
                variable = $(ele).data("objectInstance").variable;
                for (i = 0; i < variables.length; i++) {
                    if (variable && variables[i] && variables[i].var_uid === variable.var_uid) {
                        break;
                    }
                }
                if (i === variables.length) {
                    variables.push(variable);
                }
            }
        });
        return variables;
    };
    Grid.prototype.isVariableUsed = function (var_uid) {
        var that = this;
        var variables = that.getVariables();
        for (var i = 0; i < variables.length; i++) {
            if (variables[i] && variables[i].var_uid === var_uid) {
                return true;
            }
        }
        return false;
    };
    Grid.prototype.getFieldObjects = function (filter) {
        var a = [], obj;
        this.body.find(">div").each(function (i, ele) {
            obj = $(ele).data("objectInstance");
            if (obj && filter.indexOf(obj.properties.type.value) > -1)
                a.push(obj);
        });
        return a;
    };
    Grid.prototype.setDisabled = function (disabled) {
        this.disabled = disabled;
        var obj;
        if (disabled) {
            this.body.sortable("disable");
        } else {
            this.body.sortable("enable");
        }
        this.body.find(">div").each(function (i, ele) {
            obj = $(ele).data("objectInstance");
            if (obj && obj.setDisabled) {
                obj.setDisabled(disabled);
            }
        });
        this.properties.setDisabled(disabled);
    };
    Grid.prototype.setVariable = function (variable) {
        var that = this, b;
        that.variable = variable;
        that.properties.set("var_uid", variable.var_uid);
        b = that.properties.set("variable", variable.var_name);
        if (b.node)
            b.node.textContent = variable.var_name;
        b = that.properties.set("dataType", variable.var_field_type);
        if (b.node)
            b.node.textContent = variable.var_field_type;
        b = that.properties.set("id", variable.var_name);
        if (b.node)
            b.node.value = variable.var_name;
        that.properties.set("name", variable.var_name);
    };
    FormDesigner.extendNamespace('FormDesigner.main.Grid', Grid);
}());