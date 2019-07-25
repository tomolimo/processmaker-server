var CreateShapeHelper = function (options) {
    this.html = null;
    CreateShapeHelper.prototype.initObject.call(this, options);
};

CreateShapeHelper.prototype.initObject = function (options) {
    var defaults = {
        parent: null,
        x: 0,
        y: 0,
        zOrder: 7,
        className: ''
    };

    $.extend(true, defaults, options);
    this.setParent(defaults.parent);
    this.setPosition(defaults.x, defaults.y);
    this.setZOrder(defaults.zOrder);
    this.setClasName(defaults.className);
};
CreateShapeHelper.prototype.setClasName = function (name) {
    this.className = name;
    return this;
};
CreateShapeHelper.prototype.setZOrder = function (zOrder) {
    this.zOrder = zOrder;
    return this;
};
CreateShapeHelper.prototype.setParent = function (parent) {
    this.parent = parent;
    return this;
};
CreateShapeHelper.prototype.setPosition = function (x, y) {
    this.x = x + 2;
    this.y = y + 2;
    return this;
};
CreateShapeHelper.prototype.createHTML = function () {
    this.html = document.createElement('div');
    this.html.id = 'CreateShapeHelper';
    this.html.className = this.className;
    this.html.style.position = "absolute";
    this.html.style.left = this.x + "px";
    this.html.style.top = this.y + "px";
    this.html.style.height = "30px";
    this.html.style.width = "30px";
    this.html.style.zIndex = this.zOrder;
    return this.html;
};

CreateShapeHelper.prototype.paint = function () {
    if (this.html === null) {
        this.parent.html.appendChild(this.createHTML());
    }

    return this;
};
