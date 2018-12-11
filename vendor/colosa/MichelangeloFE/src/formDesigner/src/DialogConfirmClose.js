(function () {
    var DialogConfirmClose = function (appendTo) {
        this.onAccept = new Function();
        DialogConfirmClose.prototype.init.call(this, appendTo);
    };
    DialogConfirmClose.prototype.init = function (appendTo) {
        var that = this;
        this.accept = $("<a href='#' class='fd-button fd-button-yes'>" + "Yes".translate() + "</a>");
        this.accept.on("click", function () {
            that.onAccept();
            that.dialog.dialog("close");
            return false;
        });
        this.cancel = $("<a href='#' class='fd-button fd-button-no'>" + "No".translate() + "</a>");
        this.cancel.on("click", function () {
            that.dialog.dialog("close");
            return false;
        });
        this.buttons = $("<div class='fd-button-panel'><div></div></div>");
        this.buttons.find("div:nth-child(1)").append(this.cancel);
        this.buttons.find("div:nth-child(1)").append(this.accept);

        this.dialog = $("<div title='" + "Confirm".translate() + "'></div>");
        this.dialog.dialog({
            appendTo: appendTo ? appendTo : document.body,
            modal: true,
            autoOpen: true,
            width: 470,
            height: 170,
            resizable: false,
            close: function (event, ui) {
                that.dialog.remove();
            }
        });
        FormDesigner.main.DialogStyle(this.dialog, "warning");

        this.dialog.append("<div style='font-size:14px;margin:20px;'>" +
            "Do you want to close? All your changes will be lost if you close it.".translate() +
            "</div>");
        this.dialog.append(this.buttons);
    };
    FormDesigner.extendNamespace('FormDesigner.main.DialogConfirmClose', DialogConfirmClose);
}());