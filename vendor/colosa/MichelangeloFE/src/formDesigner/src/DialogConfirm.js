(function () {
    var DialogConfirm = function (appendTo, type, message) {
        this.type = type;
        this.message = message;
        this.onAccept = new Function();
        this.onCancel = new Function();
        this.onClose = new Function();
        DialogConfirm.prototype.init.call(this, appendTo);
    };
    DialogConfirm.prototype.init = function (appendTo) {
        var that = this;
        this.accept = $("<a href='#' class='fd-button fd-button-yes'>" + "Yes".translate() + "</a>");
        this.accept.on("click", function () {
            that.onAccept();
            that.dialog.dialog("close");
            return false;
        });
        this.cancel = $("<a href='#' class='fd-button fd-button-no'>" + "No".translate() + "</a>");
        this.cancel.on("click", function () {
            that.onCancel();
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
                that.onClose();
            }
        });
        FormDesigner.main.DialogStyle(this.dialog, this.type);

        this.dialog.append("<div style='font-size:14px;margin:20px;'>" +
            this.message +
            "</div>");
        this.dialog.append(this.buttons);
    };
    FormDesigner.extendNamespace('FormDesigner.main.DialogConfirm', DialogConfirm);
}());