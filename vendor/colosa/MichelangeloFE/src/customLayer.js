var CustomLayer = function (options) {
    PMUI.draw.Layer.call(this, options);
    this.tooltip = null;
    this.listeners = {
        click: function () {
        }
    };

    CustomLayer.prototype.init.call(this, options);
};
CustomLayer.prototype = new PMUI.draw.Layer();

CustomLayer.prototype.type = "customLayer";

CustomLayer.prototype.init = function (options) {
    var defaults = {
        tooltip: "",
        listeners: {
            click: function (event, layer, shape) {
            }
        }
    };
    jQuery.extend(true, defaults, options);

    this.setTooltipMessage(defaults.tooltip)
        .setListeners(defaults.listeners);
};
CustomLayer.prototype.setTooltipMessage = function (message) {
    if (typeof message === "string") {
        this.tooltip = message;
    }
    if (this.html) {
        jQuery(this.html).attr("title", "");
        jQuery(this.html).tooltip({content: this.tooltip, tooltipClass: "mafe-action-tooltip"});
    }
    return this;
};
CustomLayer.prototype.setListeners = function (events) {
    if (typeof events === "object") {
        this.listeners.click = events.click;
    }
    return this;
};
CustomLayer.prototype.createHTML = function (modifying) {
    this.setProperties();
    PMUI.draw.Layer.prototype.createHTML.call(this, modifying);
    this.setTooltipMessage();
    this.defineEvents();
    return this.html;
};
CustomLayer.prototype.defineEvents = function () {
    var that = this;

    jQuery(that.html).on("click", function (event) {
        that.listeners.click(event, that, that.parent);
        event.stopPropagation();
    });
};