(function () {
    var ListMobileControls = function () {
        ListMobileControls.prototype.init.call(this);
    };
    ListMobileControls.prototype.init = function () {
        this.body = $("<div style='background:#262932;overflow:hidden;padding:4px;'></div>");
        this.load();
    };
    ListMobileControls.prototype.load = function () {
        var controls = [{
            url: "" + $.imgUrl + "fd-geomap-mobile.png",
            label: "geomap".translate(),
            render: FormDesigner.main.TypesControl.geomap
        }, {
            url: "" + $.imgUrl + "fd-qrcode-mobile.png",
            label: "qr code".translate(),
            render: FormDesigner.main.TypesControl.qrcode
        }, {
            url: "" + $.imgUrl + "fd-signature-mobile.png",
            label: "signature".translate(),
            render: FormDesigner.main.TypesControl.signature
        }, {
            url: "" + $.imgUrl + "fd-image2.png",
            label: "image".translate(),
            render: FormDesigner.main.TypesControl.imagem
        }, {
            url: "" + $.imgUrl + "fd-audio-mobile.png",
            label: "audio".translate(),
            render: FormDesigner.main.TypesControl.audiom
        }, {
            url: "" + $.imgUrl + "fd-video-mobile.png",
            label: "video".translate(),
            render: FormDesigner.main.TypesControl.videom
        }
        ];
        for (var i = 0; i < controls.length; i++) {
            this.addItem(controls[i]);
        }
    };
    ListMobileControls.prototype.addItem = function (control) {
        var item = $(
            "<div class='fd-list-responsive'>" +
            "<div style=''><img src='" + control.url + "'></img></div>" +
            "<div style=''>" + control.label + "</div>" +
            "</div>");
        item.attr("render", control.render);
        item.draggable({
            appendTo: document.body,
            revert: "invalid",
            helper: "clone",
            cursor: "move",
            zIndex: 1000,
            connectToSortable: ".itemControls,.itemsVariablesControls"
        });
        this.body.append(item);
        return item;
    };
    FormDesigner.extendNamespace('FormDesigner.main.ListMobileControls', ListMobileControls);
}());