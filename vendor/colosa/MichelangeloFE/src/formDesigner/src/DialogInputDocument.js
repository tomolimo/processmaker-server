(function () {
    var DialogInputDocument = function (appendTo) {
        this.onClose = new Function();
        this.onLoad = new Function();
        this.onClick = new Function();
        DialogInputDocument.prototype.init.call(this, appendTo);
    };
    DialogInputDocument.prototype.init = function (appendTo) {
        var that = this;
        this.dialog = $("<div title='" + "Input Documents".translate() + "' style='padding:10px;'></div>");
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

        this.search = $("<p>" + "Search".translate() + " : <input type='text'/><img src='" + $.imgUrl + "fd-refresh.png' style='margin-top:-4px;vertical-align:middle;cursor: pointer;' title='" + "refresh".translate() + "'></p>");
        this.search.find(">input").on("keyup", function () {
            var input = this;
            that.body.find(">div").each(function (i, e) {
                var sw = false;
                $(e).find(">div").each(function (i, e) {
                    sw = sw || $(e).text().toLowerCase().indexOf(input.value.toLowerCase()) > -1;
                });
                if (sw)
                    $(e).show();
                else
                    $(e).hide();
            });
        });
        this.search.find("img").on("click", function () {
            that.load();
        });
        this.body = $("<div style='overflow-x:hidden;border:1px solid #bbb;height:305px;'></div>");
        this.dialog.append(this.search);
        this.dialog.append(this.body);
        this.load();
    };
    DialogInputDocument.prototype.load = function () {
        var that = this;
        this.body.find(">div").remove();
        var restProxy = new PMRestClient({
            endpoint: "input-documents",
            typeRequest: "get",
            functionSuccess: function (xhr, response) {
                var resp;
                for (var i = 0; i < response.length; i++) {
                    resp = response[i];
                    resp["label"] = response[i].inp_doc_title;
                    resp["value"] = response[i].inp_doc_uid;
                    that.addItem(resp);
                }
                that.onLoad(response);
            }
        });
        restProxy.executeRestClient();
    };
    DialogInputDocument.prototype.addItem = function (variable) {
        var that = this;
        var item = $("<div class='fd-list' style='cursor:pointer;'>" +
            "<div style='width:auto;display:inline-block;color:rgb(33, 54, 109);'>" + variable.label + "</div>" +
            "</div>");
        item.on("click", function () {
            that.onClick(variable);
        });
        this.body.append(item);
        return item;
    };
    FormDesigner.extendNamespace('FormDesigner.main.DialogInputDocument', DialogInputDocument);
}());