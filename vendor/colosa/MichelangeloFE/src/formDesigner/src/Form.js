(function () {
    var Form = function () {
        this.id = PMUI.generateUniqueId();
        this.onRemove = new Function();
        this.onRemoveItem = new Function();
        this.onRemoveCell = new Function();
        this.onSelect = new Function();
        this.onDrawControl = new Function();
        this.onSetProperty = new Function();
        this.onSynchronizeVariables = new Function();
        this.sourceNode = null;
        this.targetNode = null;
        this.stopValidateRows = false;
        this.variable = null;
        this.dirty = null;
        this.subformSupport = true;
        this.disabled = false;
        this.recovery = false;
        this.checkColspan = true;
        Form.prototype.init.call(this);
    };
    Form.prototype.init = function () {
        var that = this;
        this.thead = $("<thead><tr><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr></thead>");
        this.tbody = $("<tbody class='itemsVariablesControls'></tbody>");
        this.tbody.sortable({
            placeholder: "fd-gridForm-placeholder",
            handle: ".formdesigner-move-row",
            stop: function (event, ui) {
                if (ui.item.attr("variable"))
                    that.variable = JSON.parse(ui.item.attr("variable"));
                if (ui.item.attr("render")) {
                    var c = that.addCell();
                    that.targetNode = c;
                    c.data("createdRow", true);
                    ui.item.replaceWith(that.addRow().append(c));
                    var properties = that.drawDroppedItem(ui.item.attr("render"));
                    that.onDrawControl(properties);
                }
                that.validateDragDrop();
                that.validateRows();
            }
        }).droppable({
            drop: function (event, ui) {
            }
        });
        this.table = $("<table style='width:100%;'></table>");
        this.table.append(this.thead);
        this.table.append(this.tbody);
        this.body = $("<div id='" + this.id + "' class=\"pm-mafe-form\" style='background:#F6F5F3;margin:6px;padding:10px;border-radius:5px;border:1px solid #DADADA;position:relative;'></div>");
        this.body.append(this.table);
        this.body.on("click", function (e) {
            e.stopPropagation();
            $.designerSelectElement(this, that.onRemove);
            that.onSelect(that.properties);
        });
        this.body.data("objectInstance", this);
        this.properties = new FormDesigner.main.Properties(FormDesigner.main.TypesControl.form, this.body, that);
        this.properties.onClick = function (property) {
            var a, b;
            if (property === "script") {
                a = new FormDesigner.main.DialogScript();
                a.onSave = function () {
                    that.properties.set(property, {
                        type: "js",
                        code: a.editor.getValue()
                    });
                };
                if (typeof that.properties.get()[property].value === "object") {
                    a.editor.setValue(that.properties.get()[property].value.code);
                }
            }
            if (property === "variable") {
                a = new FormDesigner.main.DialogCreateVariable(null, FormDesigner.main.TypesControl.form, [], that.properties.get()[property].value);
                a.onSave = function (variable) {
                    that.setVariable(variable);
                };
                a.onSelect = function (variable) {
                    a.dialog.dialog("close");
                    that.setVariable(variable);
                };
            }
        };
        this.properties.onClickClearButton = function (property) {
            var a, b;
            if (property === "variable" && that.properties[property].value !== "") {
                a = new FormDesigner.main.DialogConfirmClearVariable();
                a.onAccept = function () {
                    b = that.properties.set("variable", "");
                    b.node.textContent = "...";
                    b = that.properties.set("dataType", "");
                    b.node.textContent = "";
                    b = that.properties.set("protectedValue", false);
                    b.node.checked = false;
                };
            }
        };
        this.properties.onSet = function (prop, value) {
            that.onSetProperty(prop, value, that);
        };
        this.clear();
    };
    Form.prototype.addRow = function () {
        var row = $("<tr style='padding:5px;'></tr>");
        this.tbody.append(row);
        return row;
    };
    Form.prototype.addCell = function () {
        var that = this, cell;
        cell = $("<td style='height:56px;background:white;position:relative;vertical-align:top;border:1px dotted gray;' class='itemVariables itemControls cellDragDrop colspan-12' colspan='12'></td>");
        cell[0].disabled = false;
        cell[0].setDisabled = function (disabled) {
            cell[0].disabled = disabled;
            cell.data("properties").setDisabled(disabled);
        };
        cell.sortable({
            placeholder: "fd-gridForm-placeholder",
            connectWith: ".cellDragDrop",
            items: '>*:not(.containerCellDragDrop)',
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
                that.validateDragDrop();
                that.validateRows();
            }
        }).droppable({
            drop: function (event, ui) {
            }
        });
        var properties = new FormDesigner.main.Properties(FormDesigner.main.TypesControl.cell, cell, cell[0]);
        properties.onSet = function (prop, value) {
            if (prop === "colSpan" && properties[prop].node) {
                //calculate colspan
                value = $.trim(value);
                var dt = value.split(" "), s = 0, ss = "", i, r, col;
                for (i = 0; i < dt.length; i++) {
                    col = parseInt(dt[i], 10);
                    if (s + col <= 12 && col !== 0 && col > 0) {
                        s = s + col;
                        ss = ss + col + " ";
                    }
                }
                if (s < 12) {
                    r = 12 - s;
                    s = s + r;
                    ss = ss + r;
                }
                ss = $.trim(ss);
                //validation
                if (that.checkColspan === true && cell.parent().children().length > ss.split(" ").length) {
                    var a = new FormDesigner.main.DialogConfirm(null, "warning", "The colspan change is going to remove columns and content fields. Do you want to continue?".translate());
                    a.onAccept = function () {
                        that.checkColspan = false;
                        properties.set(prop, ss);
                        that.checkColspan = true;
                    };
                    a.onClose = function () {
                        that.checkColspan = false;
                        var oldValue = properties[prop].oldValue;
                        properties.set(prop, oldValue);
                        properties[prop].node.value = oldValue;
                        that.checkColspan = true;
                    };
                    return;
                }
                //update value in properties and input
                properties[prop].node.value = ss;
                properties[prop].value = ss;
                //complete cells
                var row = cell.parent();
                dt = ss.split(" ");
                if (row.children().length < dt.length) {
                    for (i = 0; i < dt.length; i++) {
                        if (i < row.children().length) {

                        } else {
                            row.append(that.addCell());
                        }
                        row.children()[i].style.width = (dt[i] * 100 / 12) + "%";//todo colspan
                        row.children()[i].colSpan = dt[i];
                    }
                }
                if (row.children().length >= dt.length) {
                    for (i = 0; i < dt.length; i++) {
                        row.children()[i].style.width = (dt[i] * 100 / 12) + "%";//todo colspan
                        row.children()[i].colSpan = dt[i];
                    }
                    while (row.children().length > i) {
                        $(row.children()[i]).remove();
                    }
                }
                //update icons
                $.designerSelectElement(row.children()[0]);
                //update all cells to new value
                row.children().each(function (i, e) {
                    var a = $(e).data("properties").onSet;
                    $(e).data("properties").onSet = new Function();
                    $(e).data("properties").set(prop, ss);
                    $(e).data("properties").onSet = a;
                });
                //update colspan class for resize media query
                row.children().each(function (i, e) {
                    for (var i = 1; i <= 12; i++) {
                        $(e).removeClass("colspan-" + i);
                    }
                    $(e).addClass("colspan-" + e.colSpan);
                });
            }
        };
        cell.data("properties", properties);
        cell.on("click", function (e) {
            e.stopPropagation();
            $.designerSelectElement(this, function () {
                that.validateRows();
                that.validateDragDrop();
                that.onRemoveCell();
            }, function () {
                if (that.disabled === true) {
                    return false;
                }
            });
            that.onSelect(properties);
        });
        cell.append("<div class='containerCellDragDrop' style='float:right;'></div>");
        return cell;
    };
    Form.prototype.drawDroppedItem = function (render, data) {
        var that = this, properties = null;
        switch (render) {
            case that.inTypesControl(render):
                var formItem = new FormDesigner.main.FormItem({
                    parentObject: that,
                    type: render,
                    variable: that.variable,
                    onSelect: function (properties) {
                        that.onSelect(properties);
                    }
                });
                formItem.onRemove = function () {
                    that.onRemoveItem();
                };
                formItem.onSetProperty = function (prop, value, target) {
                    that.onSetProperty(prop, value, target);
                };
                that.targetNode.append(formItem.html);
                properties = formItem.properties;
                break;
            case FormDesigner.main.TypesControl.grid:
                var grid = new FormDesigner.main.Grid(that);
                grid.onRemove = function () {
                    that.onRemoveItem();
                };
                grid.onRemoveItem = function () {
                    that.onRemoveItem();
                };
                grid.onSelect = function (properties) {
                    that.onSelect(properties);
                };
                grid.onVariableDrawDroppedItem = function (variable) {
                    if (that.isVariableUsed(variable.var_uid)) {
                        new FormDesigner.main.DialogInformation();
                        return false;
                    }
                };
                grid.onDrawControl = function (properties) {
                    that.onDrawControl(properties);
                };
                grid.onSetProperty = function (prop, value, target) {
                    that.onSetProperty(prop, value, target);
                };
                that.targetNode.append(grid.body);
                properties = grid.properties;
                break;
            case FormDesigner.main.TypesControl.form:
                var form = new FormDesigner.main.Form();
                form.onRemove = function () {
                    that.onRemoveItem();
                };
                form.onRemoveItem = function () {
                    that.onRemoveItem();
                };
                form.onRemoveCell = function () {
                    that.onRemoveItem();
                };
                form.onSelect = function (properties) {
                    that.onSelect(properties);
                };
                form.onDrawControl = function (properties) {
                    that.onDrawControl(properties);
                };
                form.onSetProperty = function (prop, value, target) {
                    that.onSetProperty(prop, value, target);
                };
                form.setData(data);
                form.setDisabled(true);
                form.properties.gridStore.disabled = false;
                form.properties.variable.disabled = false;
                form.properties.mode.disabled = false;
                form.properties.protectedValue.disabled = false;
                form.body[0].style.margin = "20px 6px 6px 6px";
                that.targetNode.append(form.body);
                that.validateDragDrop();
                that.validateRows();
                that.variable = null;
                properties = form.properties;
                break;
            case FormDesigner.main.TypesControl.variable:
                if (that.isVariableUsed(that.variable.var_uid)) {
                    var dialogInformation = new FormDesigner.main.DialogInformation();
                    dialogInformation.onAccept = function () {
                        if (that.targetNode.data("createdRow") === true) {
                            that.targetNode.parent().remove();
                        }
                    };
                    return;
                }
                that.stopValidateRows = true;
                var dialogTypeControl = new FormDesigner.main.DialogTypeControl();
                dialogTypeControl.load(that.variable);
                dialogTypeControl.onSelectItem = function (event, item) {
                    that.drawDroppedItem(item.attr("render"));
                    that.validateDragDrop();
                    that.validateRows();
                    that.variable = null;
                };
                dialogTypeControl.onClose = function () {
                    that.validateDragDrop();
                    that.validateRows();
                    that.variable = null;
                };
                break;
            case FormDesigner.main.TypesControl.subform:
                if (that.subformSupport === false) {
                    new FormDesigner.main.DialogUnsupported();
                    return;
                }
                that.stopValidateRows = true;
                var dialogDynaforms = new FormDesigner.main.DialogDynaforms(null, that.properties.id.value);
                dialogDynaforms.onSelectItem = function (event, item) {
                    var subDynaform = JSON.parse(item.attr("dynaform"));
                    //todo validation form with subform
                    var sf, sfi, sfj, jsp;
                    jsp = JSON.parse(subDynaform.dyn_content);
                    if (jsp.items.length > 0) {
                        sf = jsp.items[0];
                        for (sfi = 0; sfi < sf.items.length; sfi++) {
                            for (sfj = 0; sfj < sf.items[sfi].length; sfj++) {
                                if (sf.items[sfi][sfj].type === FormDesigner.main.TypesControl.form) {
                                    new FormDesigner.main.DialogUnsupported();
                                    return;
                                }
                            }
                        }
                    }
                    var prop = that.drawDroppedItem(FormDesigner.main.TypesControl.form, subDynaform);
                    prop.owner.subformSupport = false;
                };
                dialogDynaforms.onClose = function () {
                    that.validateDragDrop();
                    that.validateRows();
                    that.variable = null;
                };
                break;
        }
        return properties;
    };
    Form.prototype.validateDragDrop = function () {
        this.tbody.find("td").each(function (i, ele) {
            if (ele.childNodes.length >= 2) {
                $(ele).removeClass("itemVariables");
                $(ele).removeClass("itemControls");
                $(ele).removeClass("cellDragDrop");
            } else {
                $(ele).addClass("itemVariables");
                $(ele).addClass("itemControls");
                $(ele).addClass("cellDragDrop");
            }
        });
    };
    Form.prototype.validateRows = function () {
        var that = this, row, sw, cell, length;
        if (that.stopValidateRows === true) {
            that.stopValidateRows = false;
            return;
        }
        length = this.tbody.find(">tr").length;
        for (var i = 1; i < length; i++) {
            sw = true;
            row = this.tbody.find(">tr").last();
            cell = row.find(">td");
            if (cell.length === 1) {
                cell.each(function (i, ele) {
                    if (ele.childNodes.length >= 2) {
                        sw = false;
                    }
                });
                if (sw) {
                    row.remove();
                }
            }
        }
        this.tbody.find(">tr").last().find(">td").each(function (i, ele) {
            if (ele.childNodes.length >= 2) {
                that.addRow().append(that.addCell());
                return false;
            }
        });
        if (this.tbody.find(">tr").length === 0) {
            that.addRow().append(that.addCell());
        }
    };
    Form.prototype.inTypesControl = function (val) {
        if (
            val === FormDesigner.main.TypesControl.title ||
            val === FormDesigner.main.TypesControl.subtitle ||
            val === FormDesigner.main.TypesControl.label ||
            val === FormDesigner.main.TypesControl.link ||
            val === FormDesigner.main.TypesControl.image ||
            val === FormDesigner.main.TypesControl.file ||
            val === FormDesigner.main.TypesControl.multipleFile ||
            val === FormDesigner.main.TypesControl.submit ||
            val === FormDesigner.main.TypesControl.button ||
            val === FormDesigner.main.TypesControl.text ||
            val === FormDesigner.main.TypesControl.textarea ||
            val === FormDesigner.main.TypesControl.dropdown ||
            val === FormDesigner.main.TypesControl.checkbox ||
            val === FormDesigner.main.TypesControl.checkgroup ||
            val === FormDesigner.main.TypesControl.radio ||
            val === FormDesigner.main.TypesControl.datetime ||
            val === FormDesigner.main.TypesControl.suggest ||
            val === FormDesigner.main.TypesControl.hidden ||
            val === FormDesigner.main.TypesControl.annotation ||
            val === FormDesigner.main.TypesControl.geomap ||
            val === FormDesigner.main.TypesControl.qrcode ||
            val === FormDesigner.main.TypesControl.signature ||
            val === FormDesigner.main.TypesControl.imagem ||
            val === FormDesigner.main.TypesControl.audiom ||
            val === FormDesigner.main.TypesControl.videom ||
            val === FormDesigner.main.TypesControl.panel ||
            val === FormDesigner.main.TypesControl.msgPanel
        ) {
            return val;
        }
        return null;
    };
    Form.prototype.getData = function () {
        var data, fieldObject, rows, i, j, k, itemsrow, itemsTable, dataCell, flag, propertiesForm, property, cells, variables;
        data = {};
        itemsTable = [];
        variables = [];
        propertiesForm = this.properties.get();
        for (property in propertiesForm) {
            data[property] = propertiesForm[property].value;
        }
        rows = this.tbody[0].childNodes;
        for (i = 0; i < rows.length; i++) {
            itemsrow = [];
            flag = false;
            cells = rows[i].childNodes;
            for (j = 0; j < cells.length; j++) {
                dataCell = {};
                if (cells[j].children.length > 1) {
                    fieldObject = $(cells[j].children[1]).data("objectInstance");
                    if (fieldObject) {
                        dataCell = fieldObject.getData();
                        //get variable
                        if (fieldObject.variable) {
                            for (k = 0; k < variables.length; k++) {
                                if (variables[k] && variables[k].var_uid === fieldObject.variable.var_uid) {
                                    break;
                                }
                            }
                            if (k === variables.length && fieldObject.variable !== null) {
                                variables.push(fieldObject.variable);
                            }
                        }
                        //get variable in columns
                        if (dataCell.columns) {
                            var sb = fieldObject.getVariables();
                            for (var sbi = 0; sbi < sb.length; sbi++) {
                                for (k = 0; k < variables.length; k++) {
                                    if (sb[sbi] && variables[k] && variables[k].var_uid === sb[sbi].var_uid) {
                                        break;
                                    }
                                }
                                if (k === variables.length && sb[sbi] !== null) {
                                    variables.push(sb[sbi]);
                                }
                            }
                        }
                    }
                    flag = true;
                }
                dataCell["colSpan"] = cells[j].colSpan;
                itemsrow.push(dataCell);
            }
            if (flag) {
                itemsTable.push(itemsrow);
            }
        }
        data["items"] = itemsTable;
        data["variables"] = variables;
        return data;
    };
    Form.prototype.setData = function (dynaform) {
        if (dynaform === undefined) {
            return;
        }
        if (dynaform.dyn_content === undefined || dynaform.dyn_content === "" || dynaform.dyn_content === "{}") {
            dynaform.dyn_content = JSON.stringify({
                "name": dynaform.dyn_title,
                "description": dynaform.dyn_description,
                "items": []
            });
        }
        this.tbody.find(">tr").remove();
        var that = this, i, j, k, l, content, forms, form, rows, cells, row, properties, variables, propertiesColumns;

        content = JSON.parse(dynaform.dyn_content);
        that.properties.set("id", dynaform.dyn_uid);
        that.properties.set("name", dynaform.dyn_title);
        that.properties.set("description", dynaform.dyn_description);
        that.properties.set("mode", "edit");
        that.properties.set("script", "");
        that.properties.set("language", "en");
        that.properties.set("externalLibs", "");
        that.properties.set("gridStore", false);
        that.properties.set("variable", "");
        that.properties.set("dataType", "");
        that.properties.set("printable", false);
        that.properties.set("protectedValue", false);
        forms = content.items;
        for (i = 0; i < forms.length; i++) {
            form = forms[i];
            form = that.compatibilityType(form);
            that.properties.set("id", dynaform.dyn_uid);
            that.properties.set("name", dynaform.dyn_title);
            that.properties.set("description", dynaform.dyn_description);
            that.properties.set("mode", form.mode);
            that.properties.set("script", form.script);
            that.properties.set("language", form.language);
            that.properties.set("externalLibs", form.externalLibs);
            that.properties.set("gridStore", form.gridStore ? form.gridStore : false);//compatibility with older forms
            that.properties.set("variable", form.variable ? form.variable : "");//compatibility with older forms
            that.properties.set("dataType", form.dataType ? form.dataType : "");//compatibility with older forms
            that.properties.set("printable", form.printable ? form.printable : false);//compatibility with older forms
            that.properties.set("protectedValue", form.protectedValue ? form.protectedValue : false);//compatibility with older forms
            variables = form.variables;
            rows = form.items;
            for (j = 0; j < rows.length; j++) {
                cells = rows[j];
                if (cells.length > 0)
                    row = that.addRow();
                var cs = "";
                for (k = 0; k < cells.length; k++) {
                    cs = cs + cells[k].colSpan + " ";
                }
                cs = cs.trim();
                for (k = 0; k < cells.length; k++) {
                    //get variable
                    if (cells[k].var_uid) {
                        for (l = 0; l < variables.length; l++) {
                            if (variables[l] && cells[k].var_uid === variables[l].var_uid) {
                                that.variable = variables[l];
                                break;
                            }
                        }
                    }
                    that.targetNode = that.addCell();
                    that.targetNode[0].colSpan = cells[k].colSpan;
                    that.targetNode[0].style.width = (cells[k].colSpan * 100 / 12) + "%";//todo colspan
                    that.targetNode.removeClass("colspan-12");
                    that.targetNode.addClass("colspan-" + cells[k].colSpan);
                    that.targetNode.data("properties").set("colSpan", cs);
                    row.append(that.targetNode);
                    //load elements & properties
                    if (cells[k].type !== FormDesigner.main.TypesControl.form) {
                        properties = that.drawDroppedItem(cells[k].type);
                        if (properties) {
                            for (var pro in cells[k]) {
                                properties.set(pro, cells[k][pro]);
                                //load columns if element is grid
                                if (pro === "columns") {
                                    var grid = properties.ele.data("objectInstance");
                                    if (grid) {
                                        grid.targetNode = grid.body;
                                        var columns = properties[pro].value;
                                        for (var ic = 0; ic < columns.length; ic++) {
                                            //get variable for column
                                            if (columns[ic].var_uid) {
                                                for (l = 0; l < variables.length; l++) {
                                                    if (variables[l] && columns[ic].var_uid === variables[l].var_uid) {
                                                        grid.variable = variables[l];
                                                        break;
                                                    }
                                                }
                                            }
                                            propertiesColumns = grid.drawDroppedItem(columns[ic].type);
                                            if (propertiesColumns) {
                                                for (var proc in columns[ic]) {
                                                    propertiesColumns.set(proc, columns[ic][proc]);
                                                }
                                            }
                                            grid.variable = null;
                                        }
                                    }
                                }
                            }
                        }
                        that.variable = null;
                    }
                    //load form element
                    if (cells[k].type === FormDesigner.main.TypesControl.form) {
                        //get local dynaform
                        var subFormType = cells[k].type;
                        var subDynaform = {
                            dyn_content: JSON.stringify({
                                name: cells[k].name,
                                description: cells[k].description,
                                items: [
                                    cells[k]
                                ]
                            }),
                            dyn_description: cells[k].description,
                            dyn_title: cells[k].name,
                            dyn_type: "xmlform",
                            dyn_uid: cells[k].id,
                            dyn_version: 2
                        };
                        //get remote dynaform
                        var sw = false;
                        for (var i = 0; i < $.remoteDynaforms.length; i++) {
                            if ($.remoteDynaforms[i].dyn_uid === subDynaform.dyn_uid) {
                                subDynaform = $.remoteDynaforms[i];
                                sw = true;
                                //set subform data from owner form
                                var sd = JSON.parse(subDynaform.dyn_content);
                                sd.items[0].gridStore = cells[k].gridStore;
                                sd.items[0].variable = cells[k].variable;
                                sd.items[0].dataType = cells[k].dataType;
                                sd.items[0].mode = cells[k].mode;
                                sd.items[0].protectedValue = cells[k].protectedValue;
                                subDynaform.dyn_content = JSON.stringify(sd);
                                break;
                            }
                        }
                        if (sw === false && that.recovery === true) {
                            subDynaform.dyn_uid_old = subDynaform.dyn_uid;
                            var newName = subDynaform.dyn_title + " - " + dynaform.history_date;
                            for (var i = 0; i < $.remoteDynaforms.length; i++) {
                                if (newName === $.remoteDynaforms[i].dyn_title) {
                                    newName = newName + "-copy";
                                    i - 1;
                                }
                            }
                            subDynaform.dyn_title = newName;
                            $.recovered.date = dynaform.history_date;
                            $.recovered.data.push(subDynaform);
                        }
                        if (sw === false && that.recovery === false) {
                            subFormType = FormDesigner.main.TypesControl.msgPanel;
                            subDynaform = null;
                        }
                        that.stopValidateRows = true;
                        that.drawDroppedItem(subFormType, subDynaform);
                        that.variable = null;
                    }
                }
            }
        }
        that.validateDragDrop();
        that.validateRows();
        that.setLanguages();
    };
    Form.prototype.clear = function () {
        this.tbody.find(">tr").remove();
        this.addRow().append(this.addCell());
    };
    Form.prototype.getFieldObjects = function (filter) {
        var i, j, a, rows, cells, fieldObject;
        a = [];
        rows = this.tbody[0].childNodes;
        for (i = 0; i < rows.length; i++) {
            cells = rows[i].childNodes;
            for (j = 0; j < cells.length; j++) {
                if (cells[j].children.length > 1) {
                    fieldObject = $(cells[j].children[1]).data("objectInstance");
                    if (fieldObject && filter.indexOf(fieldObject.properties.type.value) > -1)
                        a.push(fieldObject);
                }
            }
        }
        return a;
    };
    Form.prototype.isDirty = function () {
        return this.dirty !== JSON.stringify(this.getData());
    };
    Form.prototype.setDirty = function () {
        this.dirty = JSON.stringify(this.getData());
    };
    Form.prototype.isVariableUsed = function (var_uid) {
        var that = this;
        var variable = that.getData();
        for (var i = 0; i < variable.variables.length; i++) {
            if (variable.variables[i] && variable.variables[i].var_uid === var_uid) {
                return true;
            }
        }
        return false;
    };
    Form.prototype.setLanguages = function () {
        var that = this;
        (new PMRestClient({
            endpoint: "dynaform/" + that.properties.id.value + "/list-language",
            typeRequest: "get",
            functionSuccess: function (xhr, response) {
                var i, a = that.properties.language.items[0];
                that.properties.language.items = [];
                that.properties.language.items.push(a)
                for (i = 0; i < response.length; i++) {
                    that.properties.language.items.push({
                        value: response[i]["Lang"],
                        label: response[i]["X-Poedit-Language"] + " (" + response[i]["Lang"] + ")"
                    });
                }
                if (that.properties.language.node !== undefined) {
                    $(that.properties.language.node).empty();
                    for (i = 0; i < that.properties.language.items.length; i++) {
                        $(that.properties.language.node).append("<option value='" + that.properties.language.items[i].value + "'>" + that.properties.language.items[i].label + "</option>");
                    }
                    $(that.properties.language.node).val("en");
                    $(that.properties.language.node).each(function (i, e) {
                        if (that.properties.language.value === e.value) {
                            $(that.properties.language.node).val(e.value);
                        }
                    });
                }
            }
        })).executeRestClient();
    };
    Form.prototype.compatibilityType = function (form) {
        var j, k;
        for (j = 0; j < form.items.length; j++) {
            for (k = 0; k < form.items[j].length; k++) {
                if (form.items[j][k].type === "form") {
                    form.items[j][k] = this.compatibilityType(form.items[j][k]);
                }
                if (form.items[j][k].type === "annotation") {
                    form.items[j][k].type = "label";
                }
            }
        }
        return form;
    };
    Form.prototype.setDisabled = function (disabled) {
        this.disabled = disabled;
        var obj;
        if (disabled) {
            this.tbody.sortable("disable");
            this.tbody.find("td").sortable("disable");
        } else {
            this.tbody.sortable("enable");
            this.tbody.find("td").sortable("enable");
        }
        this.tbody.find("td").each(function (i, cell) {
            cell.setDisabled(disabled);
            obj = $(cell.children[1]).data("objectInstance");
            if (obj && obj.setDisabled) {
                obj.setDisabled(disabled);
            }
        });
        this.tbody.find("td").css({
            "background-color": disabled ? "#F6F5F3" : "white"
        });
        this.tbody.find(".grid-item-field").css({
            "background-color": disabled ? "#F6F5F3" : "white"
        });
        this.properties.setDisabled(disabled);
    };
    Form.prototype.synchronizeVariables = function () {
        var that = this, calls = [];
        $.ajax({
            url: HTTP_SERVER_HOSTNAME + "/api/1.0/" + WORKSPACE + "/project/" + PMDesigner.project.id + "/process-variables",
            method: "GET",
            contentType: "application/json",
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Authorization", "Bearer " + PMDesigner.project.keys.access_token);
            },
            success: function (data) {
                var variables = that.getData().variables,
                    i,
                    j,
                    changeName;
                for (i = 0; i < variables.length; i++) {
                    variables[i].var_accepted_values = that._getVarAcceptedValues(variables[i]);
                    variables[i].var_uid_old = variables[i].var_uid;
                    variables[i].var_name_old = variables[i].var_name;
                    variables[i].prj_uid_old = variables[i].prj_uid;
                    changeName = true;
                    for (j = 0; j < data.length; j++) {
                        data[j].var_accepted_values = that._getVarAcceptedValues(data[j]);
                        if (changeName && that._compareVariable(variables[i], data[j])) {
                            variables[i].var_uid = data[j].var_uid;
                            variables[i].prj_uid = data[j].prj_uid;
                            variables[i].create = false;
                        } else {
                            if (variables[i].var_name === data[j].var_name) {
                                variables[i].var_name = variables[i].var_name + "_1";
                                j = -1;
                                changeName = false;
                            }
                        }
                    }
                }
                for (i = 0; i < variables.length; i++) {
                    if (variables[i].create === undefined) {
                        calls.push({
                            url: "process-variable", method: "POST", data: variables[i]
                        });
                    }
                }
                if (calls.length > 0) {
                    $.ajax({
                        url: HTTP_SERVER_HOSTNAME + "/api/1.0/" + WORKSPACE + "/project/" + PMDesigner.project.id + "/",
                        data: JSON.stringify({calls: calls}),
                        method: "POST",
                        contentType: "application/json",
                        beforeSend: function (xhr) {
                            xhr.setRequestHeader('X-Requested-With', 'MULTIPART');
                            xhr.setRequestHeader("Authorization", "Bearer " + PMDesigner.project.keys.access_token);
                        },
                        success: function (responses) {
                            for (var i = 0; i < responses.length; i++) {
                                if (responses[i].status === 201) {
                                    for (var j = 0; j < variables.length; j++) {
                                        if (responses[i].response.var_name === variables[j].var_name) {
                                            variables[j].var_uid = responses[i].response.var_uid;
                                            variables[j].prj_uid = responses[i].response.prj_uid;
                                        }
                                    }
                                }
                            }
                            that.onSynchronizeVariables(variables);
                        },
                        error: function (responses) {
                            for (var i = 0; i < responses.length; i++) {
                                if (responses[i].status === 201) {
                                    for (var j = 0; j < variables.length; j++) {
                                        if (responses[i].response.var_name === variables[j].var_name) {
                                            variables[j].var_uid = responses[i].response.var_uid;
                                            variables[j].prj_uid = responses[i].response.prj_uid;
                                        }
                                    }
                                }
                            }
                            that.onSynchronizeVariables(variables);
                        }
                    });
                } else {
                    that.onSynchronizeVariables(variables);
                }
            }
        });
    };
    /**
     * Gets the AcceptedValues values of a variable
     * @param variable
     * @returns {*|Array}
     * @private
     */
    Form.prototype._getVarAcceptedValues = function (variable) {
        var acceptedValues = variable.var_accepted_values;

        try {
            //In previous versions it was a string (acceptedValues)
            if (typeof acceptedValues === 'string') {
                acceptedValues = JSON.parse(acceptedValues);
            }
        } catch (e) {
            throw new Error('Accepted Values is an empty string '.translate() + e.message);
        }
        return acceptedValues;
    };
    /**
     * Compare the variable if it exists
     * @param importedVariable
     * @param currentVariable
     * @returns {boolean}
     * @private
     */
    Form.prototype._compareVariable = function (importedVariable, currentVariable) {
        //Four properties of the variable are compared (name, fieldType, sql and acceptedValues)
        return (
            importedVariable.var_name === currentVariable.var_name &&
            importedVariable.var_field_type === currentVariable.var_field_type &&
            importedVariable.var_sql === currentVariable.var_sql &&
            JSON.stringify(importedVariable.var_accepted_values) === JSON.stringify(currentVariable.var_accepted_values)
        );
    };
    Form.prototype.setVariable = function (variable) {
        var that = this, b;
        that.properties.set("var_uid", variable.var_uid);
        b = that.properties.set("variable", variable.var_name);
        if (b.node)
            b.node.textContent = variable.var_name;
        b = that.properties.set("dataType", variable.var_field_type);
        if (b.node)
            b.node.textContent = variable.var_field_type;
    };
    Form.prototype.setNextLabel = function (properties) {
        var nextLabel, 
            that = this;
        nextLabel = FormDesigner.getNextNumber(that.getData(), properties.type.value, "id") + 1;
        nextLabel = nextLabel.toString();
        while (nextLabel.length < 10) {
            nextLabel = "0" + nextLabel;
        }
        nextLabel = properties.type.value + nextLabel;
        properties.set("id", nextLabel.replace(/\s/g, ""));
        properties.set("name", nextLabel.replace(/\s/g, ""));

        nextLabel = FormDesigner.getNextNumber(that.getData(), properties.type.value, "label") + 1;
        nextLabel = properties.type.value + '_' + nextLabel;
        properties.set("label", nextLabel);
    };
    Form.prototype.setNextVar = function (properties) {
        var that = this,
            nextVar = "",
            dialogCreateVariable;
        nextVar = properties.type.value + "Var" + nextVar;
        dialogCreateVariable = new FormDesigner.main.DialogCreateVariable(null, properties.type.value, [], null);
        dialogCreateVariable.setVarName(nextVar);
        dialogCreateVariable.onSave = function (variable) {
            if (properties.owner.setVariable) {
                properties.owner.setVariable(variable);
            }
        };
        dialogCreateVariable.onSelect = function (variable) {
            if ($.countValue(that.getData(), "variable", variable.var_name) > 0) {
                $.isOpenValidator = true;
                var di = new FormDesigner.main.DialogInvalid(null, "variable", "duplicated");
                di.onClose = function () {
                    $.isOpenValidator = false;
                };
                di.onAccept = function () {
                    di.dialog.dialog("close");
                };
                return;
            }
            if (properties.owner.setVariable) {
                properties.owner.setVariable(variable);
            }
            dialogCreateVariable.dialog.dialog("close");
        };
        FormDesigner.getNextNumberVar(that.getData(), properties, function (nextVar) {
            dialogCreateVariable.setVarName(nextVar);
        });
    };
    FormDesigner.extendNamespace('FormDesigner.main.Form', Form);
}());