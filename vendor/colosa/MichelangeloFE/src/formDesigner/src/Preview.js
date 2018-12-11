(function () {
    var Preview = function (dyn_uid, dyn_title, prj_uid, iframeOptions) {
        this.dyn_uid = dyn_uid;
        this.dyn_title = dyn_title;
        this.prj_uid = prj_uid;
        this.onClose = new Function();
        Preview.prototype.init.call(this, iframeOptions);
    };
    Preview.prototype.init = function (iframeOptions) {
        var that = this,
            defaultIframeOptions = {
                id: 'pm-dynaform-preview-iframe',
                name: 'pm-dynaform-preview-iframe'
            };

        jQuery.extend(true, defaultIframeOptions, iframeOptions);

        this.srcPreview = 'cases/pmDynaform?dyn_uid=' + this.dyn_uid + "&prj_uid=" + this.prj_uid;
        this.desktop = $("<div class='fd-designer-button' title='" + "Desktop".translate() + "'><img src='" + $.imgUrl + "fd-desktop.png' style='width:24px;height:24px;'/></div>");
        this.desktop.on("click", function () {
            that.iframe.css('width', '100%');
            return false;
        });
        $.hoverToolbarButtons(this.desktop, "fd-desktop.png", "fd-desktopw.png");
        this.tablet = $("<div class='fd-designer-button' title='" + "Tablet".translate() + "'><img src='" + $.imgUrl + "fd-tablet.png' style='width:24px;height:24px;'/></div>");
        this.tablet.on("click", function () {
            that.iframe.css('width', '800px');
            return false;
        });
        $.hoverToolbarButtons(this.tablet, "fd-tablet.png", "fd-tabletw.png");
        this.smartphone = $("<div class='fd-designer-button' title='" + "Smartphone".translate() + "'><img src='" + $.imgUrl + "fd-mobile_phone.png' style='width:24px;height:24px;'/></div>");
        this.smartphone.on("click", function () {
            that.iframe.css('width', '400px');
            return false;
        });
        $.hoverToolbarButtons(this.smartphone, "fd-mobile_phone.png", "fd-mobile_phonew.png");
        this.back = $("<div class='fd-designer-button' title='" + "Close".translate() + "'><img src='" + $.imgUrl + "fd-close.png' style='width:24px;height:24px;'/></div>");
        this.back.on("click", function () {
            that.close();
            return false;
        });
        $.hoverToolbarButtons(this.back, "fd-close.png", "fd-closew.png");

        this.title = $("<div style='float:left;font-family:Montserrat,sans-serif;font-size:20px;color:white;margin:5px;white-space:nowrap;'></div>");
        this.title.text(this.dyn_title).attr("title", this.dyn_title).tooltip({
            tooltipClass: "fd-tooltip",
            position: {my: "left top+1"}
        });

        this.buttons = $("<div style='position:absolute;right:0px;background-color:#3397e1;'>");
        this.buttons.append(this.desktop);
        this.buttons.append(this.tablet);
        this.buttons.append(this.smartphone);
        this.buttons.append(this.back);

        this.toolbar = $("<div class='fd-toolbar-preview' style='overflow:hidden;width:100%;height:33px;background-color:#3397e1;margin-bottom:15px;border:1px solid #2979b8;'></div>");
        this.toolbar.append(this.title);
        this.toolbar.append(this.buttons);

        this.iframe = $("<iframe></iframe>");
        this.iframe.attr("id", defaultIframeOptions.id);
        this.iframe.attr("name", defaultIframeOptions.name);
        this.iframe.css("border", "none");
        this.iframe.css("width", "100%");
        this.iframe.css("height", "100%");

        this.body = $("<div style='z-index:100;position:absolute;top:0;right:0;bottom:35px;left:0;'></div>");
        this.body.append(this.toolbar);
        this.body.append(this.iframe);
    };
    Preview.prototype.show = function () {
        jQuery('body').append(this.body);
        this.body.show();
    };
    Preview.prototype.close = function () {
        this.onClose();
        this.body.remove();
    };
    Preview.prototype.setData = function () {
        var that = this;
        that.iframe[0].src = this.srcPreview;
        that.iframe[0].onload = function () {
            var pm = this.contentWindow.window;
            if (pm && pm.PMDynaform) {
                pm.dynaform = new pm.PMDynaform.core.Project({
                    data: this.contentWindow.window.jsonData,
                    keys: {
                        server: HTTP_SERVER_HOSTNAME,
                        projectId: PMDesigner.project.id,
                        workspace: WORKSPACE
                    },
                    token: {
                        accessToken: PMDesigner.project.keys.access_token
                    },
                    isPreview: true,
                    isRTL: this.contentWindow ? this.contentWindow.window.isRTL : false
                });
                $(this.contentWindow.document).find('form').submit(function (e) {
                    e.preventDefault();
                    return false;
                });
                if (pm.PMDynaform.view && pm.PMDynaform.view.ModalProgressBar) {
                    pm.PMDynaform.view.ModalProgressBar.prototype.render = function () {
                        return this;
                    };
                }
            }
        };
    };
    FormDesigner.extendNamespace('FormDesigner.main.Preview', Preview);
}());

