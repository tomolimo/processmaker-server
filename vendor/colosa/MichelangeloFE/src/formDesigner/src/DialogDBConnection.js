(function () {
    var DialogDBConnection = function (appendTo) {
        this.onClose = new Function();
        this.onLoad = new Function();
        this.onClick = new Function();
        DialogDBConnection.prototype.init.call(this, appendTo);
    };
    DialogDBConnection.prototype.init = function (appendTo) {
        var that = this;
        this.dialog = $("<div title='" + "Database Connection".translate() + "' style='padding:10px;'></div>");
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
    DialogDBConnection.prototype.load = function () {
        var that = this;
        this.body.find(">div").remove();
        that.addItem({label: "PM Database", value: "workflow"});
        var restProxy = new PMRestClient({
            endpoint: "database-connections",
            typeRequest: "get",
            functionSuccess: function (xhr, response) {
                for (var i = 0; i < response.length; i++) {
                    if (response[i].dbs_connection_type === "TNS") {
                        that.addItem({
                            label: "[" + response[i].dbs_tns + "] " + response[i].dbs_type + " : " + response[i].dbs_database_description,
                            value: response[i].dbs_uid
                        });
                    } else {
                        that.addItem({
                            label: "[" + response[i].dbs_server + ":" + response[i].dbs_port + "] " + response[i].dbs_type + ": " + response[i].dbs_database_name + response[i].dbs_database_description,
                            value: response[i].dbs_uid
                        });
                    }
                }
                that.onLoad(response);
            }
        });
        restProxy.executeRestClient();
    };
    DialogDBConnection.prototype.addItem = function (variable) {
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
    FormDesigner.extendNamespace('FormDesigner.main.DialogDBConnection', DialogDBConnection);
}());