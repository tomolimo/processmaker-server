(function () {
    var ListControls = function () {
        ListControls.prototype.init.call(this);
    };
    ListControls.prototype.init = function () {
        this.body = $("<div style='background:#262932;overflow:hidden;padding:4px;'></div>");
        this.load();
    };
    ListControls.prototype.load = function () {
        var controls = [{
            url: "" + $.imgUrl + "fd-text.png",
            label: "textbox".translate(),
            render: FormDesigner.main.TypesControl.text
        }, {
            url: "" + $.imgUrl + "fd-textarea.png",
            label: "textarea".translate(),
            render: FormDesigner.main.TypesControl.textarea
        }, {
            url: "" + $.imgUrl + "fd-dropdown.png",
            label: "dropdown".translate(),
            render: FormDesigner.main.TypesControl.dropdown
        }, {
            url: "" + $.imgUrl + "fd-checkbox.png",
            label: "checkbox".translate(),
            render: FormDesigner.main.TypesControl.checkbox
        }, {
            url: "" + $.imgUrl + "fd-checkgroup.png",
            label: "checkgroup".translate(),
            render: FormDesigner.main.TypesControl.checkgroup
        }, {
            url: "" + $.imgUrl + "fd-radio.png",
            label: "radio".translate(),
            render: FormDesigner.main.TypesControl.radio
        }, {
            url: "" + $.imgUrl + "fd-datetime.png",
            label: "datetime".translate(),
            render: FormDesigner.main.TypesControl.datetime
        }, {
            url: "" + $.imgUrl + "fd-suggest.png",
            label: "suggest".translate(),
            render: FormDesigner.main.TypesControl.suggest
        }, {
            url: "" + $.imgUrl + "fd-hidden.png",
            label: "hidden".translate(),
            render: FormDesigner.main.TypesControl.hidden
        }, {
            url: "" + $.imgUrl + "fd-h1.png",
            label: "title".translate(),
            render: FormDesigner.main.TypesControl.title
        }, {
            url: "" + $.imgUrl + "fd-h2.png",
            label: "subtitle".translate(),
            render: FormDesigner.main.TypesControl.subtitle
        }, {
            url: "" + $.imgUrl + "fd-label.png",
            label: "label".translate(),
            render: FormDesigner.main.TypesControl.annotation
        }, {
            url: "" + $.imgUrl + "fd-link.png",
            label: "link".translate(),
            render: FormDesigner.main.TypesControl.link
        }, {
            url: "" + $.imgUrl + "fd-image2.png",
            label: "image".translate(),
            render: FormDesigner.main.TypesControl.image
        }, {
            url: "" + $.imgUrl + "fd-file.png",
            label: "file".translate(),
            render: FormDesigner.main.TypesControl.file
        }, {
            url: "" + $.imgUrl + "fd-file-upload.png",
            label: "fileupload".translate(),
            render: FormDesigner.main.TypesControl.multipleFile
        }, {
            url: "" + $.imgUrl + "fd-submit.png",
            label: "submit".translate(),
            render: FormDesigner.main.TypesControl.submit
        }, {
            url: "" + $.imgUrl + "fd-button.png",
            label: "button".translate(),
            render: FormDesigner.main.TypesControl.button
        }, {
            url: "" + $.imgUrl + "fd-grid.png",
            label: "grid".translate(),
            render: FormDesigner.main.TypesControl.grid
        }, {
            url: "" + $.imgUrl + "fd-panel32.png",
            label: "panel".translate(),
            render: FormDesigner.main.TypesControl.panel
        }, {
            url: "" + $.imgUrl + "fd-subform.png",
            label: "subform".translate(),
            render: FormDesigner.main.TypesControl.subform
        }
        ];
        for (var i = 0; i < controls.length; i++) {
            this.addItem(controls[i]);
        }
    };
    ListControls.prototype.addItem = function (control) {
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
    FormDesigner.extendNamespace('FormDesigner.main.ListControls', ListControls);
}());