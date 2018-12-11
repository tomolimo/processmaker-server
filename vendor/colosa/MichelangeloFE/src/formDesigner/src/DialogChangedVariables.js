(function () {
    var DialogChangedVariables = function (appendTo, message) {
        this.onAccept = new Function();
        this.message = message;
        DialogChangedVariables.prototype.init.call(this, appendTo);
    };
    DialogChangedVariables.prototype.init = function (appendTo) {
        var that = this;
        this.accept = $("<a href='#' class='fd-button fd-button-yes'>" + "Ok".translate() + "</a>");
        this.accept.on("click", function () {
            that.onAccept();
            that.dialog.dialog("close");
            return false;
        });
        this.buttons = $("<div class='fd-button-panel'><div></div></div>");
        this.buttons.find("div:nth-child(1)").append(this.accept);

        this.dialog = $("<div title='" + "New variables created".translate() + "'></div>");
        this.dialog.dialog({
            appendTo: appendTo ? appendTo : document.body,
            modal: true,
            autoOpen: true,
            width: 470,
            height: 200,
            resizable: true,
            close: function (event, ui) {
                that.dialog.remove();
            }
        });
        FormDesigner.main.DialogStyle(this.dialog, "warning");

        this.message = this.message.replace(/[,\s]+$/, "");
        this.dialog.append("<div style='font-size:14px;margin:20px;height:85px;overflow-y:auto;'>" +
            "The imported dynaform include new variables and existing variables that require changes.".translate() + " " +
            "The changed variables have been added with the suffix “_1”.".translate() + " " +
            "Please take note of the changes to update your process logic.".translate() + " " +
            "The following variables have been created:<br>".translate() + this.message +
            "</div>");
        this.dialog.append(this.buttons);
    };
    FormDesigner.extendNamespace('FormDesigner.main.DialogChangedVariables', DialogChangedVariables);
}());