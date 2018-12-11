(function () {
    var DialogContent = function (appendTo) {
        this.onSave = new Function();
        this.onClose = new Function();
        DialogContent.prototype.init.call(this, appendTo);
    };
    DialogContent.prototype.init = function (appendTo) {
        var that = this;
        this.save = $("<a href='#' class='fd-button fd-button-success'>" + "Save".translate() + "</a>");
        this.save.on("click", function () {
            that.onSave();
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

        $.ui.keyCode.TAB = null;//focus button prevents dialog with CodeMirror tab
        this.dialog = $("<div title='" + "Content".translate() + "' style='padding:10px;'></div>");
        this.dialog.dialog({
            appendTo: appendTo ? appendTo : document.body,
            modal: true,
            autoOpen: true,
            width: 940,
            height: 500,
            resizable: false,
            close: function (event, ui) {
                that.onClose(event, ui);
                that.dialog.remove();
                $.ui.keyCode.TAB = 9;
            }
        });
        FormDesigner.main.DialogStyle(this.dialog);

        this.textarea = $("<textarea></textarea>");
        this.body = $("<div style='border:1px solid #bbb;'></div>");
        this.body.append(this.textarea);
        this.dialog.append(this.body);
        this.dialog.append("<p>" + "Press".translate() + " <strong>ctrl-space</strong> " + "to activate autocompletion".translate() + ".</p>");
        this.dialog.append(this.buttons);

        this.editor = CodeMirror.fromTextArea(this.textarea[0], {
            lineNumbers: true,
            matchBrackets: true,
            autoCloseBrackets: true,
            extraKeys: {"Ctrl-Space": "autocomplete"},
            mode: "text/html",
            viewportMargin: Infinity,
            className: "CodeMirror-hints-custom-mafe"
        });
        this.editor.setSize(910, 350);
    };
    FormDesigner.extendNamespace('FormDesigner.main.DialogContent', DialogContent);
}());