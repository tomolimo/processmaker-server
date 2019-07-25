var PMLabelField = function (settings) {
    PMUI.form.Field.call(this, settings);

    PMLabelField.prototype.init.call(this, settings);
};

PMLabelField.prototype = new PMUI.form.Field();

PMLabelField.prototype.type = "PMLabelField";

PMLabelField.prototype.family = 'PMLabelField';

PMLabelField.prototype.init = function (settings) {
    var defaults = {
        text: "Mafe Label0",
        textMode: "plain"
    };

    jQuery.extend(true, defaults, settings);

    this.setText(defaults.text);
    this.setTextMode(defaults.textMode);
};

PMLabelField.prototype.setText = function (text) {
    this.controls[0].setText(text);
    return this;
};
PMLabelField.prototype.setTextMode = function (textMode) {
    this.controls[0].setTextMode(textMode);
    return this;
};

PMLabelField.prototype.setControls = function () {
    if (this.controls.length) {
        return this;
    }
    this.controls.push(new PMLabelControl());

    return this;
};

PMLabelField.prototype.getValue = function () {
    return null;
};

PMLabelField.prototype.createHTML = function () {

    PMUI.form.Field.prototype.createHTML.call(this);
    this.hideLabel();
    this.setLabelWidth('0%');
    return this.html;
};
   
