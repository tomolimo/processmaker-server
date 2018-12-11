(function () {
    var DialogVariable = function (appendTo, render) {
        this.render = render;
        this.onClose = new Function();
        this.onLoad = new Function();
        this.onClick = new Function();
        DialogVariable.prototype.init.call(this, appendTo);
    };
    DialogVariable.prototype.init = function (appendTo) {
        var that = this;
        this.dialog = $("<div title='" + "Variable".translate() + "' style='padding:10px;'></div>");
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

        this.search = $("<div style='padding-bottom:10px;'><span style='vertical-align:top;'>" + "Search".translate() + " : </span><input type='text'/><img src='" + $.imgUrl + "fd-refresh.png' style='margin-top:-4px;vertical-align:middle;cursor: pointer;' title='" + "refresh".translate() + "'></div>");
        this.search.find("input").on("keyup", function () {
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
    };
    DialogVariable.prototype.load = function () {
        var that = this;
        this.body.find(">div").remove();
        var restProxy = new PMRestClient({
            endpoint: 'process-variables',
            typeRequest: 'get',
            functionSuccess: function (xhr, response) {
                response.sort(function (a, b) {
                    if (a.var_name < b.var_name)
                        return -1;
                    if (a.var_name > b.var_name)
                        return 1;
                    return 0;
                });
                for (var i = 0; i < response.length; i++) {
                    $.validDataTypeAndControlType(response[i].var_field_type, that.render, function () {
                        that.addItem(response[i]);
                    });
                }
                that.onLoad(response);
            }
        });
        restProxy.executeRestClient();
    };
    DialogVariable.prototype.addItem = function (variable) {
        var that = this;
        var item = $("<div class='fd-list' style='cursor:pointer;'>" +
            "<div style='width:auto;display:inline-block;margin:1px;color:rgb(238, 113, 15);'>[" + variable.var_field_type + "]</div>" +
            "<div style='width:auto;display:inline-block;color:rgb(33, 54, 109);'>" + variable.var_name + "</div>" +
            "</div>");
        item.on("click", function () {
            that.onClick(variable);
        });
        this.body.append(item);
        return item;
    };
    FormDesigner.extendNamespace('FormDesigner.main.DialogVariable', DialogVariable);
}());