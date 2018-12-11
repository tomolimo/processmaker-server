var PMTooltipMessage = function (options) {
    PMUI.ui.Window.call(this, options);
    this.container = null;
    this.message = options.message;
    PMTooltipMessage.prototype.init.call(this, options);
};

PMTooltipMessage.prototype = new PMUI.ui.Window();
PMTooltipMessage.prototype.type = "PMTooltipMessage";


PMTooltipMessage.prototype.createHTML = function () {
    if (this.html) {
        return this.html;
    }
    PMUI.ui.Window.prototype.createHTML.call(this);
    this.closeButton.style.removeAllClasses();
    this.closeButton.setText("x");
    this.closeButton.style.addClasses(['mafe-tooltip-close']);
    this.header.appendChild(this.closeButton.getHTML());
    this.container = PMUI.createHTMLElement('div');
    this.container.innerHTML = this.message;
    this.body.appendChild(this.container);
    return this.html;
};

PMTooltipMessage.prototype.open = function (x, y) {
    PMUI.ui.Window.prototype.open.call(this);
    this.setVisible(false);
    this.setX(x);
    this.setY(y);
    this.header.className = "mafe-tooltip-header";
    this.body.className = "mafe-tooltip-body";
    this.style.addClasses(['mafe-tooltip']);
    this.setTitle("");
    $("#" + this.id).show("drop", "fast");
    this.closeButton.defineEvents();
};

PMTooltipMessage.prototype.setMessage = function (message) {
    this.message = message;
    if (this.html)
        this.container.innerHTML = message;
};