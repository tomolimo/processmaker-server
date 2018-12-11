(function () {
    var DialogInvalid = function (appendTo, property, type) {
        this.property = property;
        this.type = type;
        this.onAccept = new Function();
        this.onClose = new Function();
        DialogInvalid.prototype.init.call(this, appendTo);
    };
    DialogInvalid.prototype.init = function (appendTo) {
        var that = this;
        this.accept = $("<a href='#' class='fd-button fd-button-success'>" + "Ok".translate() + "</a>");
        this.accept.on("click", function () {
            that.onAccept();
            return false;
        });
        this.buttons = $("<div class='fd-button-panel'><div></div></div>");
        this.buttons.find("div:nth-child(1)").append(this.accept);

        this.dialog = $("<div title='" + "Errors".translate() + "'></div>");
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
        FormDesigner.main.DialogStyle(this.dialog, "alert");

        var msg = "The " + that.property + " is invalid.".translate();
        if (this.type === "required")
            msg = "The " + that.property + " is required.".translate();
        if (this.type === "duplicated")
            msg = "The " + that.property + " is duplicated.".translate();

        this.dialog.append("<div style='font-size:14px;margin:20px;'>" +
            msg +
            "</div>");
        this.dialog.append(this.buttons);
        this.accept.focus();
    };
    FormDesigner.extendNamespace('FormDesigner.main.DialogInvalid', DialogInvalid);
}());