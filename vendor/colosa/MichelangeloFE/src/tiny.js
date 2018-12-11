var PMTiny = function (options) {
    PMUI.control.HTMLControl.call(this, options);
    this.theme = null;
    this.plugins = null;
    this.mode = null;
    this.editorSelector = null;
    this.widthTiny = null;
    this.heightTiny = null;
    this.directionality = null;
    this.verifyHtml = null;
    this.themeAdvancedButtons1 = null;
    this.themeAdvancedButtons2 = null;
    this.popupCss = null;
    this.skin = null;
    this.skinVariant = null;
    this.processID = null;
    this.domainURL = null;
    this.baseURL = null;
    PMTiny.prototype.init.call(this, options);
};

PMTiny.prototype = new PMUI.control.HTMLControl();

PMTiny.prototype.type = "PMTiny";
PMTiny.prototype.family = 'PMCodeMirrorControl';

PMTiny.prototype.init = function (options) {
    var defaults = {
        theme: "advanced",
        plugins: "advhr,advimage,advlink,advlist,autolink,autoresize,contextmenu,directionality,emotions,example,example_dependency,fullpage,fullscreen,iespell,inlinepopups,insertdatetime,layer,legacyoutput,lists,media,nonbreaking,noneditable,pagebreak,paste,preview,print,save,searchreplace,style,tabfocus,table,template,visualblocks,visualchars,wordcount,xhtmlxtras,pmSimpleUploader,pmVariablePicker,pmGrids,style",
        mode: "specific_textareas",
        editorSelector: "tmceEditor",
        widthTiny: DEFAULT_WINDOW_WIDTH - 60,
        heightTiny: DEFAULT_WINDOW_HEIGHT - 100,
        directionality: 'ltr',
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

    jQuery.extend(true, defaults, options);

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
        .setWidthTiny(defaults.widthTiny);
};

PMTiny.prototype.setTheme = function (theme) {
    this.theme = theme;
    return this;
};
PMTiny.prototype.setPlugins = function (plugins) {
    this.plugins = plugins;
    return this;
};
PMTiny.prototype.setMode = function (mode) {
    this.mode = mode;
    return this;
};
PMTiny.prototype.setEditorSelector = function (editorSelector) {
    this.editorSelector = editorSelector;
    return this;
};
PMTiny.prototype.setDirectionality = function (directionality) {
    this.directionality = directionality;
    return this;
};
PMTiny.prototype.setVerifyHtml = function (verifyHtml) {
    this.verifyHtml = verifyHtml;
    return this;
};
PMTiny.prototype.setThemeAdvancedButtons1 = function (themeAdvancedButtons1) {
    this.themeAdvancedButtons1 = themeAdvancedButtons1;
    return this;
};
PMTiny.prototype.setThemeAdvancedButtons2 = function (themeAdvancedButtons2) {
    this.themeAdvancedButtons2 = themeAdvancedButtons2;
    return this;
};
PMTiny.prototype.setPopupCss = function (popupCss) {
    this.popupCss = popupCss;
    return this;
};
PMTiny.prototype.setSkin = function (skin) {
    this.skin = skin;
    return this;
};
PMTiny.prototype.setSkinVariant = function (skinVariant) {
    this.skinVariant = skinVariant;
    return this;
};
PMTiny.prototype.setProcessID = function (processID) {
    this.processID = processID;
    return this;
};
PMTiny.prototype.setDomainURL = function (domainURL) {
    this.domainURL = domainURL;
    return this;
};
PMTiny.prototype.setBaseURL = function (baseURL) {
    this.baseURL = baseURL;
    return this;
};
PMTiny.prototype.setWidthTiny = function (widthTiny) {
    this.widthTiny = widthTiny;
    return this;
};
PMTiny.prototype.setHeightTiny = function (heightTiny) {
    this.heightTiny = heightTiny;
    return this;
};


PMTiny.prototype.setParameterTiny = function () {
    var that = this, domainURL;
    tinyMCE.baseURL = this.baseURL;
    domainURL = this.domainURL;
    tinyMCE.init({
        theme: this.theme,
        plugins: this.plugins,
        mode: this.mode,
        editor_selector: this.editorSelector,
        width: this.widthTiny,
        height: this.heightTiny,
        directionality: this.directionality,
        verify_html: this.verifyHtml,
        theme_advanced_buttons1: this.themeAdvancedButtons1,
        theme_advanced_buttons2: this.themeAdvancedButtons2,
        popup_css: this.popupCss,
        skin: this.skin,
        skin_variant: this.skinVariant,
        relative_urls: false,
        remove_script_host: false,
        convert_urls: this.convert_urls,
        oninit: function () {
            tinyMCE.activeEditor.processID = PMDesigner.project.id;
            tinyMCE.activeEditor.domainURL = domainURL;
            //added the tinyeditor reference to the PMUI control
            that.controlEditor = tinyMCE.activeEditor;
            tinyMCE.execCommand('mceFocus', false, 'tinyeditor');
        },
        onchange_callback: function (inst) {
            that.onChangeHandler();
            if (inst.isDirty()) {
                inst.save();
            }
            return true;
        },
        handle_event_callback: function (e) {
            if (this.isDirty()) {
                this.save();
            }
            return true;
        }
    });
};

PMTiny.prototype.createHTML = function () {
    var input;

    if (this.html) {
        return this.html;
    }

    input = PMUI.createHTMLElement("textArea");
    input.className = "tmceEditor";
    input.id = "tinyeditor";
    input.name = "tinyeditor";
    input.width = this.width;
    input.height = this.height;

    this.html = input;
    return this.html;
};

PMTiny.prototype.setValueTiny = function (value) {
    if (this.html) {
        if (this.html.id) {
            $('#' + this.html.id + '_ifr').height('100%');
        }
        if (this.controlEditor) {
            this.controlEditor.setContent(value);
        }
    }
    return this;
};

PMTiny.prototype.getValue = function (value) {
    if (this.html) {
        if (this.controlEditor) {
            return this.controlEditor.getContent(value);
        }
    }
    return '';
};

PMTiny.prototype.setVisible = function (visible) {
    visible = !!visible;
    this.visible = visible;

    if (this.html) {
        if (this.html.id) {
            if (visible) {
                $('#' + this.html.id + '_tbl').css("display", "block");
            } else {
                $('#' + this.html.id + '_tbl').css("display", "none");
            }
        }
    }

    return this;
};