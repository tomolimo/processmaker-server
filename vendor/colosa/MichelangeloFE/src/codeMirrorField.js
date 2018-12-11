var PMCodeMirrorField = function (settings) {
    PMUI.form.Field.call(this, settings);

    PMCodeMirrorField.prototype.init.call(this, settings);
};

PMCodeMirrorField.prototype = new PMUI.form.Field();

PMCodeMirrorField.prototype.type = "PMCodeMirrorField";

PMCodeMirrorField.prototype.family = 'PMCodeMirrorField';

PMCodeMirrorField.prototype.init = function (settings) {
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
        autofocus: "on"
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
        .setAutofocus(defaults.autofocus);
};

PMCodeMirrorField.prototype.setLineNumbers = function (lineNumbers) {
    this.controls[0].setLineNumbers(lineNumbers);
    return this;
};
PMCodeMirrorField.prototype.setMatchBrackets = function (matchBrackets) {
    this.controls[0].setMatchBrackets(matchBrackets);
    return this;
};
PMCodeMirrorField.prototype.setMode = function (mode) {
    this.controls[0].setMode(mode);
    return this;
};
PMCodeMirrorField.prototype.setIndentUnit = function (indentUnit) {
    this.controls[0].setIndentUnit(indentUnit);
    return this;
};
PMCodeMirrorField.prototype.setIndentWithTabs = function (indentWithTabs) {
    this.controls[0].setIndentWithTabs(indentWithTabs);
    return this;
};
PMCodeMirrorField.prototype.setEnterMode = function (enterMode) {
    this.controls[0].setEnterMode(enterMode);
    return this;
};
PMCodeMirrorField.prototype.setTabMode = function (tabMode) {
    this.controls[0].setTabMode(tabMode);
    return this;
};
PMCodeMirrorField.prototype.setLineWrapping = function (lineWrapping) {
    this.controls[0].setLineWrapping(lineWrapping);
    return this;
};
PMCodeMirrorField.prototype.setShowCursorWhenSelecting = function (showCursorWhenSelecting) {
    this.controls[0].setShowCursorWhenSelecting(showCursorWhenSelecting);
    return this;
};
PMCodeMirrorField.prototype.setAutofocus = function (autofocus) {
    this.controls[0].setAutofocus(autofocus);
    return this;
};

PMCodeMirrorField.prototype.setControls = function () {
    if (this.controls.length) {
        return this;
    }
    this.controls.push(new PMCodeMirror());
    return this;
};
