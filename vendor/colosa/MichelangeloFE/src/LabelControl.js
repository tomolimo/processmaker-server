var PMLabelControl = function (settings) {
    PMUI.control.Control.call(this, settings);
    this.label = null;
    PMLabelControl.prototype.init.call(this, settings);
};

PMLabelControl.prototype = new PMUI.control.Control();

PMLabelControl.prototype.type = "PMLabelControl";

PMLabelControl.prototype.family = 'PMLabelControl';

PMLabelControl.prototype.init = function (settings) {
    this.label = new PMUI.ui.TextLabel();
};

PMLabelControl.prototype.setText = function (text) {
    if (this.label) {
        this.label.setText(text);
    }
    return this;
};

PMLabelControl.prototype.setTextMode = function (textMode) {
    if (this.label) {
        this.label.setTextMode(textMode);
    }
    return this;
};

PMLabelControl.prototype.createHTML = function () {
    PMUI.control.Control.prototype.createHTML.call(this);
    this.html = this.label.getHTML();
    this.setPositionMode('relative');
    return this.html;
};
