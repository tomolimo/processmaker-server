(function () {
    var DialogMessage = function (appendTo, type, message) {
        this.type = type;
        this.message = message;
        this.onAccept = new Function();
        this.onClose = new Function();
        DialogMessage.prototype.init.call(this, appendTo);
    };
    DialogMessage.prototype.init = function (appendTo) {
        var that = this,
            title;
        this.accept = $("<a href='#' class='fd-button fd-button-success'>" + "Ok".translate() + "</a>");
        this.accept.on("click", function () {
            that.onAccept();
            that.dialog.dialog("close");
            return false;
        });
        this.buttons = $("<div class='fd-button-panel'><div></div></div>");
        this.buttons.find("div:nth-child(1)").append(this.accept);

        title = that.type === "success"? "Information" : "Errors";
        this.dialog = $("<div title='" + title.translate() + "'></div>");
        this.dialog.dialog({
            appendTo: appendTo ? appendTo : document.body,
            modal: true,
            autoOpen: true,
            width: 470,
            height: 170,
            resizable: false,
            close: function (event, ui) {
                that.onClose();
                that.dialog.remove();
            }
        });
        FormDesigner.main.DialogStyle(this.dialog, this.type);

        this.dialog.append("<div style='font-size:14px;margin:20px;'>" +
            this.message +
            "</div>");
        this.dialog.append(this.buttons);
        this.accept.focus();
    };
    FormDesigner.extendNamespace('FormDesigner.main.DialogMessage', DialogMessage);
}());