var ToolbarPanel = function (options) {
    this.tooltip = null;
    ToolbarPanel.prototype.init.call(this, options);
};

ToolbarPanel.prototype = new PMUI.core.Panel();
ToolbarPanel.prototype.type = "ToolbarPanel";

ToolbarPanel.prototype.init = function (options) {
    var defaults = {
        buttons: [],
        tooltip: "",
        width: "96%"
    };
    jQuery.extend(true, defaults, options);
    PMUI.core.Panel.call(this, defaults);
    this.buttons = [];
    this.setTooltip(defaults.tooltip);
    this.setButtons(defaults.buttons);
};
ToolbarPanel.prototype.setTooltip = function (message) {
    if (typeof message === "string") {
        this.tooltip = message;
    }
    return this;
};

ToolbarPanel.prototype.setButtons = function (buttons) {
    var that = this;
    jQuery.each(buttons, function (index, button) {
        that.buttons.push(button);
    });
    return this;
};
ToolbarPanel.prototype.createHTMLButton = function (button) {
    var i,
        li = PMUI.createHTMLElement('li'),
        a = PMUI.createHTMLElement('a');

    li.id = button.selector;
    li.className = "mafe-toolbarpanel-btn";
    a.title = "";
    a.style.cursor = "move";
    jQuery(a).tooltip({
        content: button.tooltip,
        tooltipClass: "mafe-action-tooltip",
        position: {
            my: "left top", at: "left bottom", collision: "flipfit"
        }
    });

    for (i = 0; i < button.className.length; i += 1) {
        jQuery(a).addClass(button.className[i]);
    }

    li.appendChild(a);
    return li;
};

ToolbarPanel.prototype.createHTML = function () {
    var that = this, ul;
    PMUI.core.Panel.prototype.setElementTag.call(this, "ul");
    PMUI.core.Panel.prototype.createHTML.call(this);
    this.html.style.overflow = "visible";
    jQuery.each(this.buttons, function (i, button) {
        var html = that.createHTMLButton(button);
        that.html.appendChild(html);
        button.html = html;
    });
    return this.html;
};

ToolbarPanel.prototype.activate = function () {
    var that = this;
    jQuery.each(this.buttons, function (i, b) {
        jQuery(b.html).draggable({
            opacity: 0.7,
            helper: "clone",
            cursor: "hand"
        });
    });
    return this;
};

ToolbarPanel.prototype.getSelectors = function () {
    var selectors = [], that = this;
    jQuery.each(this.buttons, function (i, button) {
        selectors.push('#' + button.selector);
    });
    return selectors;
};