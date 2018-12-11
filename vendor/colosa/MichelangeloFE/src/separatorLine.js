var PMSeparatorLine = function (settings) {
    PMUI.control.HTMLControl.call(this, settings);
    this.width = null;
    this.height = null;
    this.color = null;
    this.marginLeft = null;
    PMSeparatorLine.prototype.init.call(this, settings);
};

PMSeparatorLine.prototype = new PMUI.control.HTMLControl();

PMSeparatorLine.prototype.type = "PMSeparatorLine";

PMSeparatorLine.prototype.family = 'PMSeparatorLine';

PMSeparatorLine.prototype.init = function (settings) {
    var defaults = {
        width: "90%",
        height: '3px',
        color: "#C0C0C0",
        marginLeft: "0%"
    };
    jQuery.extend(true, defaults, settings);
    this.setWidth(defaults.width);
    this.setColor(defaults.color);
    this.setHeight(defaults.height);
    this.setMarginLeft(defaults.marginLeft);
};

PMSeparatorLine.prototype.setHeight = function (height) {
    this.height = height;
    if (this.html) {
        this.html.style.height = this.height;
    }
    return this;
};

PMSeparatorLine.prototype.setWidth = function (width) {
    this.width = width;
    if (this.html) {
        this.html.style.width = this.width;
    }
    return this;
};
PMSeparatorLine.prototype.setColor = function (color) {
    this.color = color;
    if (this.html) {
        this.html.style.background = this.color;
    }
    return this;
};
PMSeparatorLine.prototype.setMarginLeft = function (marginLeft) {
    this.marginLeft = marginLeft;
    if (this.html) {
        this.html.style.marginLeft = this.marginLeft;
    }
    return this;
};

PMSeparatorLine.prototype.createHTML = function () {
    var input;
    if (this.html) {
        return this.html;
    }
    input = PMUI.createHTMLElement("hr");
    input.className = "PMSeparatorLine";
    input.id = this.id;
    input.name = "PMSeparatorLine";
    this.html = input;
    this.setColor(this.color);
    this.setMarginLeft(this.marginLeft);
    this.applyStyle();
    return this.html;
};