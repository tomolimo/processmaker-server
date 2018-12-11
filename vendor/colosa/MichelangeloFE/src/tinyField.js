var PMTinyField = function (settings) {
    PMUI.form.Field.call(this, settings);
    PMTinyField.prototype.init.call(this, settings);
};

PMTinyField.prototype = new PMUI.form.Field();

PMTinyField.prototype.type = "PMTinyField";

PMTinyField.prototype.family = 'PMTinyField';

PMTinyField.prototype.init = function (settings) {
    var defaults = {
        theme: "advanced",
        plugins: "advhr,advimage,advlink,advlist,autolink,autoresize,contextmenu,directionality,emotions,example,example_dependency,fullpage,fullscreen,iespell,inlinepopups,insertdatetime,layer,legacyoutput,lists,media,nonbreaking,noneditable,pagebreak,paste,preview,print,save,searchreplace,style,tabfocus,table,template,visualblocks,visualchars,wordcount,xhtmlxtras,pmSimpleUploader,pmVariablePicker,pmGrids,style",
        mode: "specific_textareas",
        editorSelector: "tmceEditor",
        widthTiny: DEFAULT_WINDOW_WIDTH - 60,
        heightTiny: DEFAULT_WINDOW_HEIGHT - 100,
        directionality: 'rtl',
        verifyHtml: false,
        themeAdvancedButtons1: "pmSimpleUploader,|,pmVariablePicker,|,pmGrids,|,bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull,|,fontselect,fontsizeselect,|,cut,copy,paste,|,bullist,numlist,|,outdent,indent,blockquote",
        themeAdvancedButtons2: "tablecontrols,|,undo,redo,|,link,unlink,image,|,forecolor,backcolor,styleprops,|,hr,removeformat,visualaid,|,sub,sup,|,ltr,rtl,|,code",
        popupCss: "/js/tinymce/jscripts/tiny_mce/themes/advanced/skins/default/dialog.css",
        skin: "o2k7",
        skinVariant: "silver",
        processID: null,
        domainURL: "/sys" + WORKSPACE + "/" + LANG + "/" + SKIN + "/",
        baseURL: "/js/tinymce/jscripts/tiny_mce"
    };

    jQuery.extend(true, defaults, settings);

    this.setTheme(defaults.theme)
        .setPlugins(defaults.plugins)
        .setMode(defaults.mode)
        .setEditorSelector(defaults.editorSelector)
        .setDirectionality(defaults.directionality)
        .setVerifyHtml(defaults.verifyHtml)
        .setThemeAdvancedButtons1(defaults.themeAdvancedButtons1)
        .setThemeAdvancedButtons2(defaults.themeAdvancedButtons2)
        .setPopupCss(defaults.popupCss)
        .setSkin(defaults.skin)
        .setSkinVariant(defaults.skinVariant)
        .setProcessID(defaults.processID)
        .setDomainURL(defaults.domainURL)
        .setBaseURL(defaults.baseURL)
        .setHeightTiny(defaults.heightTiny)
        .setWidthTiny(defaults.widthTiny)
        .hideLabel(true);
};

PMTinyField.prototype.setTheme = function (theme) {
    this.controls[0].setTheme(theme);
    return this;
};
PMTinyField.prototype.setPlugins = function (plugins) {
    this.controls[0].setPlugins(plugins);
    return this;
};
PMTinyField.prototype.setMode = function (mode) {
    this.controls[0].setMode(mode);
    return this;
};
PMTinyField.prototype.setEditorSelector = function (editorSelector) {
    this.controls[0].setEditorSelector(editorSelector);
    return this;
};
PMTinyField.prototype.setDirectionality = function (directionality) {
    this.controls[0].setDirectionality(directionality);
    return this;
};
PMTinyField.prototype.setVerifyHtml = function (verifyHtml) {
    this.controls[0].setVerifyHtml(verifyHtml);
    return this;
};
PMTinyField.prototype.setThemeAdvancedButtons1 = function (themeAdvancedButtons1) {
    this.controls[0].setThemeAdvancedButtons1(themeAdvancedButtons1);
    return this;
};
PMTinyField.prototype.setThemeAdvancedButtons2 = function (themeAdvancedButtons2) {
    this.controls[0].setThemeAdvancedButtons2(themeAdvancedButtons2);
    return this;
};
PMTinyField.prototype.setPopupCss = function (popupCss) {
    this.controls[0].setPopupCss(popupCss);
    return this;
};
PMTinyField.prototype.setSkin = function (skin) {
    this.skin = skin;
    this.controls[0].setSkin(skin);
    return this;
};
PMTinyField.prototype.setSkinVariant = function (skinVariant) {
    this.controls[0].setSkinVariant(skinVariant);
    return this;
};
PMTinyField.prototype.setProcessID = function (processID) {
    this.controls[0].setProcessID(processID);
    return this;
};
PMTinyField.prototype.setDomainURL = function (domainURL) {
    this.controls[0].setDomainURL(domainURL);
    return this;
};
PMTinyField.prototype.setBaseURL = function (baseURL) {
    this.controls[0].setBaseURL(baseURL);
    return this;
};
PMTinyField.prototype.setWidthTiny = function (widthTiny) {
    this.controls[0].setWidthTiny(widthTiny);
    return this;
};
PMTinyField.prototype.setHeightTiny = function (heightTiny) {
    this.controls[0].setHeightTiny(heightTiny);
    return this;
};
PMTinyField.prototype.hideLabel = function (value) {
    jQuery(this.dom.labelTextContainer).hide();
    this.labelVisible = !value;
    return this;
}
PMTinyField.prototype.setParameterTiny = function () {
    this.controls[0].setParameterTiny();
    return this;
}
PMTinyField.prototype.setValueTiny = function (value) {
    this.controls[0].setValueTiny(value);
    return this;
}

PMTinyField.prototype.setControls = function () {
    if (this.controls.length) {
        return this;
    }
    this.controls.push(new PMTiny());
    return this;
};



