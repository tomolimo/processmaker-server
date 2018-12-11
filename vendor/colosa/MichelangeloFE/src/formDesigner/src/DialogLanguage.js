(function () {
    var DialogLanguage = function (appendTo, dyn_uid) {
        this.onClose = new Function();
        this.onLoad = new Function();
        this.dyn_uid = dyn_uid;
        DialogLanguage.prototype.init.call(this, appendTo);
    };
    DialogLanguage.prototype.init = function (appendTo) {
        var that = this;
        this.dialog = $("<div title='" + "Languages".translate() + "' style='padding:10px;'></div>");
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

        this.uploadFile = $("<form style='margin-top:11px;float:right;'>" +
            "<input type='file' name='LANGUAGE' class='fd-gridform-language-upload'/>" +
            "</form>");
        this.uploadFile.find(".fd-gridform-language-upload").on("change", function (e) {
            var win = window, fd = new FormData(), xhr, val = 'LANGUAGE';
            fd.append(val, e.target.files[0]);
            if (win.XMLHttpRequest)
                xhr = new XMLHttpRequest();
            else if (win.ActiveXObject)
                xhr = new ActiveXObject('Microsoft.XMLHTTP');
            xhr.open('POST', '/api/1.0/' + WORKSPACE + '/project/' + PMDesigner.project.id + '/dynaform/' + that.dyn_uid + '/upload-language', true);
            xhr.setRequestHeader('Authorization', 'Bearer ' + PMDesigner.project.keys.access_token);
            xhr.onload = function () {
                if (this.status === 200) {
                    that.uploadFile[0].reset();
                    that.load();
                } else {
                    that.uploadFile[0].reset();
                }
            };
            xhr.send(fd);
        });

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
        this.dialog.append(this.uploadFile);
        this.dialog.append(this.search);
        this.dialog.append(this.body);
        this.load();
    };
    DialogLanguage.prototype.load = function () {
        var that = this;
        this.body.find(">div").remove();
        var restProxy = new PMRestClient({
            endpoint: "dynaform/" + this.dyn_uid + "/list-language",
            typeRequest: "get",
            functionSuccess: function (xhr, response) {
                //default lang
                var item = that.addItem({
                    label: "English [processmaker.en.po]",
                    value: "en"
                });
                item.find(".fd-button").hide();
                //lang
                for (var i = 0; i < response.length; i++) {
                    that.addItem({
                        label: response[i]["X-Poedit-Language"] + " [" + response[i]["File-Name"] + "]",
                        value: response[i].Lang
                    });
                }
                that.onLoad(response);
            }
        });
        restProxy.executeRestClient();
    };
    DialogLanguage.prototype.addItem = function (variable) {
        var that = this;
        var item = $("<div class='fd-list' style='cursor:pointer;height:27px;'>" +
            "<div style='width:410px;display:inline-block;color:rgb(33, 54, 109);'>" + variable.label + "</div>" +
            "<div style='width:auto;display:inline-block;color:rgb(33, 54, 109);'>" +
            "<a href='#' class='fd-button fd-button-no' style='margin:0px 5px 0px 5px;font-family:SourceSansPro,Arial,Tahoma,Verdana;font-size:14px;padding:5px 5px;'>" + "Delete".translate() + "</a>" +
            "</div>" +
            "</div>");
        item.find(".fd-button").on("click", function (e) {
            e.stopPropagation();
            var a = new FormDesigner.main.DialogConfirmDeleteLang();
            a.onAccept = function () {
                var restProxy = new PMRestClient({
                    endpoint: "dynaform/" + that.dyn_uid + "/delete-language/" + variable.value,
                    typeRequest: "post",
                    functionSuccess: function (xhr, response) {
                        that.load();
                    }
                });
                restProxy.executeRestClient();
            }
            return false;
        });
        item.on("click", function (e) {
            e.stopPropagation();
            var restProxy = new PMRestClient({
                endpoint: "dynaform/" + that.dyn_uid + "/download-language/" + variable.value,
                typeRequest: "get",
                functionSuccess: function (xhr, response) {
                    var name = "processmaker." + response.lang + ".po";
                    if (window.navigator.msSaveBlob) {
                        window.navigator.msSaveBlob(new Blob([response.labels], {'type': 'application/octet-stream'}), name);
                        return false;
                    }
                    var a = document.createElement('a');
                    document.body.appendChild(a);
                    a.href = window.URL.createObjectURL(new Blob([response.labels], {'type': 'application/octet-stream'}));
                    a.download = name;
                    a.click();
                    document.body.removeChild(a);
                }
            });
            restProxy.executeRestClient();
            return false;
        });
        this.body.append(item);
        return item;
    };
    FormDesigner.extendNamespace('FormDesigner.main.DialogLanguage', DialogLanguage);
}());