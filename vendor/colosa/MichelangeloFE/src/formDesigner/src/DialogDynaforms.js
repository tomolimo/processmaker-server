(function () {
    var DialogDynaforms = function (appendTo, uidParentDynaform) {
        this.onClose = new Function();
        this.onSelectItem = new Function();
        this.uidParentDynaform = uidParentDynaform;
        DialogDynaforms.prototype.init.call(this, appendTo);
    };
    DialogDynaforms.prototype.init = function (appendTo) {
        var that = this;
        this.dialog = $("<div title='" + "Select a Control".translate() + "' style='padding:10px;'></div>");
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

        this.body = $("<div style='border:1px solid #bbb;'></div>");
        this.dialog.append("<p>Please select the control you want to use with your variable.</p>".translate());
        this.dialog.append(this.body);
        this.load();
    };
    DialogDynaforms.prototype.load = function () {
        var that = this;
        this.body.find(">div").remove();
        (new PMRestClient({
            endpoint: 'dynaforms',
            typeRequest: 'get',
            functionSuccess: function (xhr, response) {
                for (var i = 0; i < response.length; i++) {
                    if (response[i].dyn_uid !== that.uidParentDynaform) {//todo
                        that.addItem(response[i]);
                    }
                }
            },
            messageError: 'There are problems getting the list of DynaForms, please try again.'.translate()
        })).executeRestClient();
    };
    DialogDynaforms.prototype.addItem = function (dynaform) {
        var that = this;
        var item = $(
            "<div class='fd-list' style='width:auto;cursor:pointer;'>" +
            "<div style='display:inline-block;background-size:contain;background-image:url(" + $.imgUrl + "fd-application-form.png);width:16px;height:16px;vertical-align:middle;'></div>" +
            "<div style='display:inline-block;margin-left:10px;'>" + dynaform.dyn_title + "</div>" +
            "</div>");
        this.body.append(item);
        item.attr("render", "subform");
        item.attr("dynaform", JSON.stringify(dynaform));
        item.on("click", function (event) {
            that.onSelectItem(event, item);
            that.dialog.dialog("close").remove();
        });
        return item;
    };
    FormDesigner.extendNamespace('FormDesigner.main.DialogDynaforms', DialogDynaforms);
}());