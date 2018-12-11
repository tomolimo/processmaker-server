var PMSeparatorLineField = function (settings) {
    PMUI.form.Field.call(this, settings);
    PMSeparatorLineField.prototype.init.call(this, settings);
};

PMSeparatorLineField.prototype = new PMUI.form.Field();

PMSeparatorLineField.prototype.type = "PMSeparatorLineField";

PMSeparatorLineField.prototype.family = 'PMSeparatorLineField';

PMSeparatorLineField.prototype.init = function (settings) {
    var defaults = {
        controlHeight: 1,
        controlColor: "#CDCDCD",
        widthControl: "90%",
        marginLeft: "5%"
    };
    jQuery.extend(true, defaults, settings);
    this.setControlHeight(defaults.controlHeight)
        .setControlColor(defaults.controlColor)
        .setMarginLeft(defaults.marginLeft);
};

PMSeparatorLineField.prototype.setControlHeight = function (size) {
    this.controls[0].setHeight(size);
    return this;
};
PMSeparatorLineField.prototype.setControlColor = function (color) {
    this.controls[0].setColor(color);
    return this;
};
PMSeparatorLineField.prototype.setMarginLeft = function (marginLeft) {
    this.controls[0].setMarginLeft(marginLeft);
    return this;
};
PMSeparatorLineField.prototype.setControls = function () {
    if (this.controls.length) {
        return this;
    }
    this.controls.push(new PMSeparatorLine());

    return this;
};

PMSeparatorLineField.prototype.getValue = function () {
    return null;
};

PMSeparatorLineField.prototype.createHTML = function () {

    PMUI.form.Field.prototype.createHTML.call(this);

    this.hideLabel();
    this.setLabelWidth('0%');
    return this.html;
};