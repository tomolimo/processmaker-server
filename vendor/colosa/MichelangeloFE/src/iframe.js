var PMIframe = function (settings) {
    PMUI.core.Element.call(this, settings);

    this.src = null;
    this.name = null;
    this.scrolling = null;
    this.frameborder = null;
    this.errorMessage = null;
    this.data = null;

    PMIframe.prototype.init.call(this, settings);
};

PMIframe.prototype = new PMUI.core.Element();

PMIframe.prototype.type = "PMPMIframe";

PMIframe.prototype.family = 'PMPMIframe';

PMIframe.prototype.init = function (settings) {
    var defaults = {
        src: "",
        name: "",
        width: 770,
        height: 305,
        scrolling: 'no',
        frameborder: "0"
    };

    jQuery.extend(true, defaults, settings);

    this.setSrc(defaults.src)
        .setName(defaults.name)
        .setWidth(defaults.width)
        .setHeight(defaults.height)
        .setScrolling(defaults.scrolling)
        .setFrameborder(defaults.frameborder);
};

PMIframe.prototype.setSrc = function (src) {
    this.src = src;
    return this;
};
PMIframe.prototype.setName = function (name) {
    this.name = name;
    return this;
};
PMIframe.prototype.setScrolling = function (scrolling) {
    this.scrolling = scrolling;
    return this;
};
PMIframe.prototype.setFrameborder = function (frameborder) {
    this.frameborder = frameborder;
    return this;
};

PMIframe.prototype.createHTML = function () {
    var input;

    if (this.html) {
        return this.html;
    }

    input = PMUI.createHTMLElement("iframe");
    input.className = "PMIframeWin";
    input.id = this.id;
    input.name = "PMIframeWindow";
    input.src = this.src;
    input.frameBorder = this.frameborder;

    this.html = input;
    this.applyStyle();
    return this.html;
};

