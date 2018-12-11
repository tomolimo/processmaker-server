var PMCodeMirror = function (settings) {
    PMUI.control.TextAreaControl.call(this, settings);

    this.lineNumbers = null;
    this.matchBrackets = null;
    this.mode = null;
    this.indentUnit = null;
    this.indentWithTabs = null;
    this.enterMode = null;
    this.tabMode = null;
    this.lineWrapping = null;
    this.showCursorWhenSelecting = null;
    this.autofocus = null;
    this.height = null;
    this.dom = {};
    this.cm = null;

    PMCodeMirror.prototype.init.call(this, settings);
};

PMCodeMirror.prototype = new PMUI.control.TextAreaControl();

PMCodeMirror.prototype.type = "PMCodeMirrorControl";

PMCodeMirror.prototype.family = 'PMCodeMirrorControl';

PMCodeMirror.prototype.init = function (settings) {
    var defaults = {
        lineNumbers: true,
        matchBrackets: true,
        mode: "application/x-httpd-php-open",
        indentUnit: 4,
        indentWithTabs: true,
        enterMode: "keep",
        tabMode: "shift",
        lineWrapping: true,
        showCursorWhenSelecting: true,
        autofocus: "on",
        height: 120
    };

    jQuery.extend(true, defaults, settings);

    this.setLineNumbers(defaults.lineNumbers)
        .setMatchBrackets(defaults.matchBrackets)
        .setMode(defaults.mode)
        .setIndentUnit(defaults.indentUnit)
        .setIndentWithTabs(defaults.indentWithTabs)
        .setEnterMode(defaults.enterMode)
        .setTabMode(defaults.tabMode)
        .setLineWrapping(defaults.lineWrapping)
        .setShowCursorWhenSelecting(defaults.showCursorWhenSelecting)
        .setAutofocus(defaults.autofocus)
        .setHeight(defaults.height);
};

PMCodeMirror.prototype.setHeight = function (height) {
    this.height = height;
    return this;
};

PMCodeMirror.prototype.setLineNumbers = function (lineNumbers) {
    this.lineNumbers = lineNumbers;
    return this;
};
PMCodeMirror.prototype.setMatchBrackets = function (matchBrackets) {
    this.matchBrackets = matchBrackets;
    return this;
};
PMCodeMirror.prototype.setMode = function (mode) {
    this.mode = mode;
    return this;
};

PMCodeMirror.prototype.setIndentUnit = function (indentUnit) {
    this.indentUnit = indentUnit;
    return this;
};
PMCodeMirror.prototype.setIndentWithTabs = function (indentWithTabs) {
    this.indentWithTabs = indentWithTabs;
    return this;
};
PMCodeMirror.prototype.setEnterMode = function (enterMode) {
    this.enterMode = enterMode;
    return this;
};
PMCodeMirror.prototype.setTabMode = function (tabMode) {
    this.tabMode = tabMode;
    return this;
};
PMCodeMirror.prototype.setLineWrapping = function (lineWrapping) {
    this.lineWrapping = lineWrapping;
    return this;
};
PMCodeMirror.prototype.setShowCursorWhenSelecting = function (showCursorWhenSelecting) {
    this.showCursorWhenSelecting = showCursorWhenSelecting;
    return this;
};
PMCodeMirror.prototype.setAutofocus = function (autofocus) {
    this.autofocus = autofocus;
    return this;
};


PMCodeMirror.prototype.setParameterCodeMirror = function () {
    if (!this.html) {
        return this;
    }

    this.cm = CodeMirror.fromTextArea(
        this.dom.textArea,
        {
            height: this.height,
            lineNumbers: this.lineNumbers,
            matchBrackets: this.matchBrackets,
            mode: this.mode,
            indentUnit: this.indentUnit,
            indentWithTabs: this.indentWithTabs,
            enterMode: this.enterMode,
            tabMode: this.tabMode,
            lineWrapping: this.lineWrapping,
            showCursorWhenSelecting: this.showCursorWhenSelecting,
            autofocus: this.autofocus,
            extraKeys: {"Ctrl-Space": "autocomplete"}
        }
    );
    return this;
};

PMCodeMirror.prototype.getValueFromRawElement = function () {
    return (this.cm && this.cm.getValue()) || "";
};

PMCodeMirror.prototype.defineEvents = function () {
    var that = this;
    if (!this.eventsDefined) {
        if (this.cm) {
            this.cm.on("change", function () {
                that.onChangeHandler();
            });
        }
    }

    return this;
};

PMCodeMirror.prototype.createHTML = function () {
    var containerCode;

    if (this.html) {
        return this.html;
    }

    PMUI.control.TextAreaControl.prototype.createHTML.call(this);
    containerCode = PMUI.createHTMLElement("div");
    containerCode.appendChild(this.html);
    this.dom.textArea = this.html;
    this.html = containerCode;
    this.applyStyle();
    this.setParameterCodeMirror();
    return this.html;
};
