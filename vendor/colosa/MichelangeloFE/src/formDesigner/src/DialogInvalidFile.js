(function () {
    var DialogInvalidFile = function (appendTo, msg) {
        this.onAccept = new Function();
        this.msg = msg;
        DialogInvalidFile.prototype.init.call(this, appendTo);
    };
    DialogInvalidFile.prototype.init = function (appendTo) {
        var that = this;
        this.accept = $("<a href='#' class='fd-button fd-button-success'>" + "Ok".translate() + "</a>");
        this.accept.on("click", function () {
            that.onAccept();
            that.dialog.dialog("close");
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
                that.dialog.remove();
            }
        });
        FormDesigner.main.DialogStyle(this.dialog, "alert");

        this.dialog.append("<div style='font-size:14px;margin:20px;'>" +
            "Invalid file: ".translate() + that.msg + ". " +
            "Please upload a file with a valid extension (.json)".translate() + "." +
            "</div>");
        this.dialog.append(this.buttons);
    };
    FormDesigner.extendNamespace('FormDesigner.main.DialogInvalidFile', DialogInvalidFile);
}());