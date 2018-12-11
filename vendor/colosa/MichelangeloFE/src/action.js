var PMAction = function (options) {

    this.name = null;
    this.action = null;
    this.selector = null;
    this.tooltip = null;
    this.execute = null;
    this.label = null;
    this.before = null;
    this.after = null;
    this.handler = null;
    this.eventsDefined = false;
    PMAction.prototype.init.call(this, options);
};

PMAction.prototype.type = "action";

PMAction.prototype.events = [
    "click", "click"
];

PMAction.prototype.init = function (options) {
    var defaults = {
        action: "click",
        selector: "",
        tooltip: "",
        execute: false,
        label: {
            selector: "",
            text: "",
            value: ""
        },
        before: function (event) {
            event.preventDefault();
            PMUI.removeCurrentMenu();
        },
        after: function (event) {
            event.stopPropagation();
        },
        handler: function (event) {
        }
    };
    jQuery.extend(true, defaults, options);

    this.setAction(defaults.action)
        .setSelector(defaults.selector)
        .setExecute(defaults.execute)
        .setLabel(defaults.label)
        .setBefore(defaults.before)
        .setAfter(defaults.after)
        .setHandler(defaults.handler)
        .setText(defaults.label.text)
        .setValue(defaults.label.value)
        .setTooltip(defaults.tooltip)
        .addEventListener();

};
PMAction.prototype.setAction = function (action) {
    this.action = action;
    return this;
};
PMAction.prototype.setSelector = function (selector) {
    this.selector = selector;
    return this;
};
PMAction.prototype.setExecute = function (option) {
    this.execute = option;
    return this;
};
PMAction.prototype.setLabel = function (label) {
    this.label = label;
    if (!label.selector) {
        this.label.selector = this.selector;
    }
    return this;
};
PMAction.prototype.setBefore = function (action) {
    this.before = action;
    return this;
};
PMAction.prototype.setAfter = function (action) {
    this.after = action;
    return this;
};
PMAction.prototype.setHandler = function (action) {
    this.handler = action;
    return this;
};
PMAction.prototype.setText = function (text) {
    if (typeof text === "string" && text.length > 0) {
        this.label.text = text;
        jQuery(this.label.selector).text(this.label.text);
    }
    return this;
};
PMAction.prototype.setValue = function (value) {
    if (typeof value === "string" && value.length > 0) {
        this.label.value = value;
        jQuery(this.label.selector).val(this.label.value);
    }
    return this;
};
PMAction.prototype.setTooltip = function (message) {
    var that = this;
    if (typeof message === "string") {
        this.tooltip = message;
        jQuery(this.selector).attr("title", "");
        jQuery(this.selector).tooltip({content: that.tooltip, tooltipClass: "mafe-action-tooltip"});
    }
    return this;
};
PMAction.prototype.addEventListener = function () {
    var that = this;
    if (this.execute === true && this.eventsDefined === false) {

        jQuery(this.selector).on(this.action, function (event) {
            try {
                that.before(event);
            } catch (e) {
                throw new Error('Before action '.translate() + e.message);
            }
            try {
                that.handler(event);
            } catch (e) {
                throw new Error('Handler '.translate() + e.message);
            }
            try {
                that.after(event);
            } catch (e) {
                throw new Error('After action '.translate() + e.message);
            }
        });

        jQuery(this.label.selector).text(this.label.text);
        this.eventsDefined = true;
    }
    return this;
};
PMAction.prototype.defineEvents = function () {
    this.setExecute(true);
    this.addEventListener();
};
