(function () {
    var DialogOptions = function (appendTo, options) {
        this.onApply = new Function();
        this.onClose = new Function();
        this.appendTo = appendTo;
        this.options = options;
        DialogOptions.prototype.init.call(this);
    };
    DialogOptions.prototype.init = function () {
        var that = this;
        this.save = $("<a href='#' class='fd-button fd-button-success'>" + "Apply".translate() + "</a>");
        this.save.on("click", function () {
            if (!that.isValid()) {
                return false;
            }
            that.onApply();
            that.dialog.dialog("close").remove();
            return false;
        });
        this.cancel = $("<a href='#' class='fd-button fd-button-no'>" + "Cancel".translate() + "</a>");
        this.cancel.on("click", function () {
            that.dialog.dialog("close").remove();
            return false;
        });
        this.buttons = $("<div class='fd-button-panel'><div style='float:right'></div></div>");
        this.buttons.find("div:nth-child(1)").append(this.cancel);
        this.buttons.find("div:nth-child(1)").append(this.save);

        this.edit = $("<input type='text' style='width:100%;height:100%;box-sizing:border-box;'/>");
        this.add = $("<a href='#' class='fd-button fd-button-create' style='float:right;padding:7px 10px 7px 35px;margin-bottom:5px;'>" + "Create".translate() + "</a>");
        this.add.on("click", function () {
            var row = that.addItem("", "");
            var cells = row.find(".fd-table-td-edit");
            that.setInputEdit($(cells[0]));
            return false;
        });
        this.add.on("keydown", function (e) {
            var row = that.table.find("tr");
            if (row.length > 0 && e.keyCode === 9 && !e.shiftKey) {
                that.setInputEdit($($(row[1]).find(".fd-table-td-edit")[0]));
                return false;
            }
        });

        this.tbody = $("<tbody class='fd-tbody'></tbody>");
        this.tbody.sortable({
            placeholder: "fd-drag-drop-placeholder",
            handle: ".fd-drag-drop",
            stop: function (event, ui) {

            },
            start: function (event, ui) {
                ui.placeholder.html("<td class='fd-drag-drop-placeholder' colspan='6'></td>")
            }
        }).droppable({
            drop: function (event, ui) {
            }
        });

        this.table = $(
            "<table class='fd-table' style='width:100%;border:1px solid #bbb;'>" +
            "<thead>" +
            "<th class='fd-table-th' style='height:21px;width:16px;font-size:14px;'>" + "Key".translate() + "</th>" +
            "<th class='fd-table-th' style='height:21px;width:auto;font-size:14px;'>" + "Label".translate() + "</th>" +
            "<th class='fd-table-th' style='height:21px;width:40px;font-size:14px;'></th>" +
            "</thead>" +
            "</table>");
        this.table.append(this.tbody);
        this.msgError = $("<div style='color:red;'></div>");

        this.dialog = $("<div title='" + "Options".translate() + "'></div>");
        this.dialog.dialog({
            appendTo: this.appendTo ? this.appendTo : document.body,
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

        this.dialog.append("<p>" + "Please add option and click in cell for editing the cell value".translate() + ".</p>");
        this.dialog.append(this.add);
        this.dialog.append($("<div style='overflow-y:auto;height:210px;width:100%;'></div>").append(this.table));
        this.dialog.append(this.msgError);
        this.dialog.append(this.buttons);
        this.load();
    };
    DialogOptions.prototype.load = function () {
        for (var i = 0; i < this.options.length; i++)
            this.addItem(this.options[i].value, this.options[i].label);
    };
    DialogOptions.prototype.addItem = function (value, label) {
        var that = this, cell;
        var del = $("<td class='fd-table-td' style='text-align:center;height:27px;'>" +
            "<a href='#' class='fd-button fd-button-no' style='margin:0px 5px 0px 5px;font-family:SourceSansPro,Arial,Tahoma,Verdana;font-size:14px;padding: 5px 5px;'>" + "Delete".translate() + "</a>" +
            "<img class='fd-drag-drop' src='" + $.imgUrl + "fd-dragdrop.png' title='" + "up & down".translate() + "'/>" +
            "</td>");
        del.find(".fd-button").on("click", function () {
            var a = new FormDesigner.main.DialogConfirmDeleteOption();
            a.onAccept = function () {
                del.parent().remove();
            };
            return false;
        });
        del.find(".fd-button").on("keydown", function (e) {
            var ri = $(this).parent().parent()[0].rowIndex + 1;
            if (e.keyCode === 9 && !e.shiftKey && that.table.find("tr").length > ri) {
                that.setInputEdit($($(that.table.find("tr")[ri]).find(".fd-table-td-edit")[0]));
                return false;
            }
            if (e.keyCode === 9 && e.shiftKey) {
                var row = $(this).parent().parent();
                that.setInputEdit($($(row).find(".fd-table-td-edit")[1]));
                return false;
            }
        });
        var row = $("<tr style='height:20px;'></tr>");
        cell = $("<td class='fd-table-td fd-table-td-edit' style='padding:0px 2px 0px 1px;'></td>").text(value);
        row.append(cell);
        cell = $("<td class='fd-table-td fd-table-td-edit' style='padding:0px 2px 0px 1px;'></td>").text(label);
        row.append(cell);
        row.append(del);
        row.find(".fd-table-td-edit").on("click", function (e) {
            e.stopPropagation();
            that.setInputEdit($(e.target));
            return false;
        });
        this.tbody.append(row);
        return row;
    };
    DialogOptions.prototype.getOptions = function () {
        var a = [];
        if (this.edit.parent()[0] && this.edit.parent()[0].nodeName === "TD") {
            this.edit.parent().text(this.edit.val().trim());
            this.edit.remove();
        }
        var aux = "";
        this.tbody.find(".fd-table-td-edit").each(function (i, e) {
            if (i % 2 === 1) {
                a.push({
                    value: aux,
                    label: $(e).text()
                });
            }
            aux = $(e).text();
        });
        return a;
    };
    DialogOptions.prototype.isValid = function () {
        this.tbody.find(".fd-table-td-edit").removeClass("fd-table-td-error");
        var options = this.getOptions();
        for (var i = 0; i < options.length; i++) {
            for (var j = 0; j < options.length; j++) {
                if (i !== j && options[j].value === options[i].value) {
                    this.msgError.text("Duplicate value for key".translate() + ".");
                    $(this.tbody.find("tr")[j]).find(".fd-table-td-edit").addClass("fd-table-td-error");
                    return false;
                }
                if (options[j].label === "") {
                    this.msgError.text("The label is empty".translate() + ".");
                    $(this.tbody.find("tr")[j]).find(".fd-table-td-edit").addClass("fd-table-td-error");
                    return false;
                }
            }
        }
        this.msgError.text("");
        return true;
    };
    DialogOptions.prototype.setOptionsBoolean = function () {
        this.tbody.find("tr").each(function (i, e) {
            $(e).find(".fd-table-td-edit").each(function (i, cell) {
                if (0 === i)
                    $(cell).off();
            });
            $(e).find(".fd-button").hide();
            $(e).find(".fd-drag-drop").hide();
        });
        this.add.hide();
    };
    DialogOptions.prototype.setInputEdit = function (cell) {
        var that = this;
        if (that.edit.parent()[0] && that.edit.parent()[0].nodeName === "TD") {
            that.edit.parent().text(that.edit.val().trim());
        }
        that.edit.val(cell.text());
        cell.text("");
        cell.append(that.edit);
        setTimeout(function () {
            that.edit.focus();
        }, 50);
        that.edit.on("click", function (e) {
            e.stopPropagation();
            return false;
        });
        that.edit.on("keydown", function (e) {
            var cell = that.edit.parent();
            var ci = cell[0].cellIndex;
            if (e.keyCode === 9 && ci === 0 && !e.shiftKey) {
                that.setInputEdit(cell.next());
                return false;
            }
            if (e.keyCode === 9 && ci === 1 && e.shiftKey) {
                that.setInputEdit(cell.prev());
                return false;
            }
        });
    };
    FormDesigner.extendNamespace('FormDesigner.main.DialogOptions', DialogOptions);
}());