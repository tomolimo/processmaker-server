(function () {
    var DialogRegularExpression = function (appendTo, dataType) {
        this.dataType = dataType;
        this.onClose = new Function();
        this.onClick = new Function();
        DialogRegularExpression.prototype.init.call(this, appendTo);
    };
    DialogRegularExpression.prototype.init = function (appendTo) {
        var that = this;
        this.dialog = $("<div title='" + "Regular Expression".translate() + "' style='padding:10px;'></div>");
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

        this.body = $("<div style='overflow-x:hidden;border:1px solid #bbb;height:265px;'></div>");
        this.dialog.append("<span>" +
            "Enter a regular expression which is a search pattern which matches the text entered in the field. ".translate() +
            "To learn more about regular expressions, see the <a href=\"http://wiki.processmaker.com/3.0/Text_and_Textarea_Controls#Validate\" target=\"_blank\">wiki</a>.<br><br>".translate() +
            "Examples:<br>".translate() +
            "</span>");
        this.dialog.append(this.body);
        this.load();
    };
    DialogRegularExpression.prototype.load = function () {
        var that = this, data;
        data = [{
            dataType: "integer", name: "An integer".translate(), value: "^[-+]?[0-9]+$"
        }, {
            dataType: "float", name: "An integer or decimal number".translate(), value: "[-+]?[0-9]+\\.[0-9]+$"
        }, {
            dataType: "string", name: "An email address".translate(), value: "^\\w+(\\.\\w+)*@(\\w+\\.)+\\w{2,4}$"
        }
        ];
        this.body.find(">div").remove();
        for (var i = 0; i < data.length; i++) {
            if (that.dataType === data[i].dataType || that.dataType === "") {
                that.addItem(data[i]);
            }
        }
    };
    DialogRegularExpression.prototype.addItem = function (regex) {
        var that = this;
        var item = $("<div class='fd-list' style='cursor:pointer;'>" +
            "<div style='width:150px;display:inline-block;margin:1px;color:rgb(238, 113, 15);'>" + regex.name + "</div>" +
            "<div style='width:auto;display:inline-block;color:rgb(33, 54, 109);'>" + regex.value + "</div>" +
            "</div>");
        item.on("click", function () {
            that.onClick(regex);
        });
        this.body.append(item);
        return item;
    };
    FormDesigner.extendNamespace('FormDesigner.main.DialogRegularExpression', DialogRegularExpression);
}());