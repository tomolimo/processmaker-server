(function () {
    var AreaButtons = function () {
        AreaButtons.prototype.init.call(this);
    };
    AreaButtons.prototype.init = function () {
        this.save = $("<div class='fd-designer-button' title='" + "Save".translate() + "'><img src='" + $.imgUrl + "fd-save.png' style='width:24px;height:24px;'/></div>");
        $.hoverToolbarButtons(this.save, "fd-save.png", "fd-savew.png");
        this.export_ = $("<div class='fd-designer-button' title='" + "Export".translate() + "'><img src='" + $.imgUrl + "fd-export.png' style='width:24px;height:24px;'/></div>");
        $.hoverToolbarButtons(this.export_, "fd-export.png", "fd-exportw.png");
        this.import_ = $("<div class='fd-designer-button' title='" + "Import".translate() + "'><img src='" + $.imgUrl + "fd-import.png' style='width:24px;height:24px;'/></div>");
        $.hoverToolbarButtons(this.import_, "fd-import.png", "fd-importw.png");
        this.preview = $("<div class='fd-designer-button' title='" + "Preview".translate() + "'><img src='" + $.imgUrl + "fd-preview.png' style='width:24px;height:24px;'/></div>");
        $.hoverToolbarButtons(this.preview, "fd-preview.png", "fd-previeww.png");
        this.clear = $("<div class='fd-designer-button' title='" + "Clear".translate() + "'><img src='" + $.imgUrl + "fd-clear.png' style='width:24px;height:24px;'/></div>");
        $.hoverToolbarButtons(this.clear, "fd-clear.png", "fd-clearw.png");
        this.language = $("<div class='fd-designer-button' title='" + "Language".translate() + "'><img src='" + $.imgUrl + "fd-language.png' style='width:24px;height:24px;'/></div>");
        $.hoverToolbarButtons(this.language, "fd-language.png", "fd-languagew.png");
        this.close = $("<div class='fd-designer-button' title='" + "Close".translate() + "'><img src='" + $.imgUrl + "fd-close.png' style='width:24px;height:24px;'/></div>");
        $.hoverToolbarButtons(this.close, "fd-close.png", "fd-closew.png");
        this.body = $("<div style='position:absolute;right:0px;background-color:#3397e1;'></div>");
        this.body.append(this.save);
        this.body.append(this.export_);
        this.body.append(this.import_);
        this.body.append(this.preview);
        this.body.append(this.clear);
        this.body.append(this.language);
        this.body.append(this.close);
    };
    FormDesigner.extendNamespace('FormDesigner.main.AreaButtons', AreaButtons);
}());