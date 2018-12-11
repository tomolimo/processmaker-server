(function () {
    var DialogUnsupported = function (appendTo) {
        this.onAccept = new Function();
        DialogUnsupported.prototype.init.call(this, appendTo);
    };
    DialogUnsupported.prototype.init = function (appendTo) {
        var that = this;
        this.accept = $("<a href='#' class='fd-button fd-button-yes'>" + "Ok".translate() + "</a>");
        this.accept.on("click", function () {
            that.onAccept();
            that.dialog.dialog("close");
            return false;
        });
        this.buttons = $("<div class='fd-button-panel'><div></div></div>");
        this.buttons.find("div:nth-child(1)").append(this.accept);

        this.dialog = $("<div title='" + "Information".translate() + "'></div>");
        this.dialog.dialog({
            appendTo: appendTo ? appendTo : document.body,
            modal: true,
            autoOpen: true,
            width: 470,
            height: 170,
            resizable: true,
            close: function (event, ui) {
                that.dialog.remove();
            }
        });
        FormDesigner.main.DialogStyle(this.dialog, "success");

        var msg = "";
        var dt = $.globalInvalidProperties;
        for (var i = 0; i < dt.length; i++) {
            msg = msg + dt[i] + "<br> ";
        }
        msg = msg.replace(/[,\s]+$/, "");
        this.dialog.append("<div style='font-size:14px;margin:20px;'>" +
            "Unsupported element.".translate() +
            "</div>");
        this.dialog.append(this.buttons);
    };
    FormDesigner.extendNamespace('FormDesigner.main.DialogUnsupported', DialogUnsupported);
}());