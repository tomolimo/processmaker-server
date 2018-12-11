/**
 * Item Crown Class
 * @param options
 * @constructor
 */
var ItemCrown = function (options) {
    /**
     * Call Shape Constructor
     */
    PMUI.draw.Shape.call(this, options);
    /**
     * Define Id ItemCrown
     * @type {null}
     */
    this.id = null;
    /**
     * Parent Item Crown
     * @type {null}
     */
    this.parent = null;
    /**
     * Name Item Crown
     * @type {string}
     */
    this.name = null;
    /**
     * Class Name Item Crown
     * @type {string}
     */
    this.className = null;
    /**
     * Width of the Item Crown
     * @type {number}
     */
    this.width = 22;
    /**
     * Height of the Item Crown
     * @type {number}
     */
    this.height = 22;
    /**
     * Event OnClick
     * @type {null}
     */
    this.eventOnClick = null;
    /**
     * Event OnMouseDown
     * @type {null}
     */
    this.eventOnMouseDown = null;
    /**
     * Event OnMouseUp
     * @type {null}
     */
    this.eventOnMouseUp = null;
    /**
     * Event OnMouseMove
     * @type {null}
     */
    this.eventOnMouseMove = null;
    /**
     * Event OnMouseOut
     * @type {null}
     */
    this.eventOnMouseOut = null;
    this.init(options);
};
/**
 * Define new Object Shape
 * @type {PMUI.draw.Shape}
 */
ItemCrown.prototype = new PMUI.draw.Shape();
/**
 * Defines the object type
 * @type {String}
 */
ItemCrown.prototype.type = 'ItemCrown';
/**
 * Inicialize Item Crown
 * @param options
 * @returns {ItemCrown}
 */
ItemCrown.prototype.init = function (options) {
    if (typeof options === "object") {
        this.setId(options.id);
        this.setParent(options.parent);
        this.setName(options.name);
        this.setClassName(options.className);
        this.setEventOnClick(options.eventOnClick);
        this.setEventOnMouseDown(options.eventOnMouseDown);
        this.setEventOnMouseUp(options.eventOnMouseUp);
        this.setEventOnMouseMove(options.eventOnMouseMove);
        this.setEventOnMouseOut(options.eventOnMouseOut);
    }
    return this;
};
/**
 * Get Id Item Crown
 * @returns {null}
 */
ItemCrown.prototype.getId = function () {
    return this.id;
};
/**
 * Get parent Item Crown
 * @returns {null}
 */
ItemCrown.prototype.getParent = function () {
    return this.parent;
};
/**
 * Get Name
 * @returns {null|string|*}
 */
ItemCrown.prototype.getName = function () {
    return this.name;
};
/**
 * Get Class Name Style
 * @returns {null|*}
 */
ItemCrown.prototype.getClassName = function () {
    return this.className;
};
/**
 * Get Width
 * @returns {number|*}
 */
ItemCrown.prototype.getWidth = function () {
    return this.width;
};
/**
 * Get Height
 * @returns {number|*}
 */
ItemCrown.prototype.getHeight = function () {
    return this.height;
};
/**
 * Get Function EventOnclick
 * @returns {null|*}
 */
ItemCrown.prototype.getEventOnClick = function () {
    return this.eventOnClick;
};
/**
 * Get Function EventOnMouseDown
 * @returns {null|*}
 */
ItemCrown.prototype.getEventOnMouseDown = function () {
    return this.eventOnMouseDown;
};
/**
 * Set Id ItemCrown
 * @param id
 * @returns {ItemCrown}
 */
ItemCrown.prototype.setId = function (id) {
    if (id && typeof id === "string") {
        this.id = id;
    }
    return this;
};
/**
 * Set Parent ItemCrown
 * @param parent
 * @returns {ItemCrown}
 */
ItemCrown.prototype.setParent = function (parent) {
    if (typeof parent === "object" && !jQuery.isEmptyObject(parent)) {
        this.parent = parent;
    }
    return this;
};
/**
 * Set Name
 * @param name
 * @returns {ItemCrown}
 */
ItemCrown.prototype.setName = function (name) {
    if (name && typeof name === "string") {
        this.name = name;
    }
    return this;
};
/**
 * Set ClassName Style
 * @param className
 * @returns {ItemCrown}
 */
ItemCrown.prototype.setClassName = function (className) {
    if (className && typeof className === "string") {
        this.className = className
    }
    return this;
};
/**
 * Set Width
 * @param width
 * @returns {ItemCrown}
 */
ItemCrown.prototype.setWidth = function (width) {
    if (width) {
        this.width = width;
    }
    return this;
};
/**
 * Set Height
 * @param height
 * @returns {ItemCrown}
 */
ItemCrown.prototype.setHeight = function (height) {
    if (height) {
        this.height = height;
    }
    return this;
};
/**
 * Set Function EventOnClick
 * @param func
 * @returns {ItemCrown}
 */
ItemCrown.prototype.setEventOnClick = function (func) {
    if (func && typeof func === "function") {
        this.eventOnClick = func;
    }
    return this;
};
/**
 * Set Function EventOnMouseDown
 * @param func
 * @returns {ItemCrown}
 */
ItemCrown.prototype.setEventOnMouseDown = function (func) {
    if (func && typeof func === "function") {
        this.eventOnMouseDown = func;
    }
    return this;
};
/**
 * Set Function EventOnMouseUp
 * @param func
 * @returns {ItemCrown}
 */
ItemCrown.prototype.setEventOnMouseUp = function (func) {
    if (func && typeof func === "function") {
        this.eventOnMouseUp = func;
    }
    return this;
};
/**
 * Set Function EventOnMouseMove
 * @param func
 * @returns {ItemCrown}
 */
ItemCrown.prototype.setEventOnMouseMove = function (func) {
    if (func && typeof func === "function") {
        this.eventOnMouseMove = func;
    }
    return this;
};
/**
 * Set Function EventOnMouseOut
 * @param func
 * @returns {ItemCrown}
 */
ItemCrown.prototype.setEventOnMouseOut = function (func) {
    if (func && typeof func === "function") {
        this.eventOnMouseOut = func;
    }
    return this;
};
/**
 * Create HTML Item Crown
 * @returns {*}
 */
ItemCrown.prototype.createHtmlItem = function () {
    var htmlItemCrown,
        classItemCrown = "item-crown",
        positionDefault = 'relative';
    PMUI.draw.Shape.prototype.createHTML.call(this);
    htmlItemCrown = this.html;
    htmlItemCrown.className = this.getClassName() + " " + classItemCrown;
    htmlItemCrown.title = this.getName();
    htmlItemCrown.style.position = positionDefault;
    htmlItemCrown.style.width = this.getWidth() + "px";
    htmlItemCrown.style.height = this.getHeight() + "px";
    this.html = htmlItemCrown;
    return this;
};
/**
 * Listeners
 * @returns {ItemCrown}
 */
ItemCrown.prototype.attachListeners = function () {
    var htmlItemCrown;
    htmlItemCrown = this.html;
    if (htmlItemCrown) {
        jQuery(htmlItemCrown).click(this.onClick());
        jQuery(htmlItemCrown).mousedown(this.onMouseDown());
        jQuery(htmlItemCrown).mouseup(this.onMouseUp());
        jQuery(htmlItemCrown).mousemove(this.onMouseMove());
        jQuery(htmlItemCrown).mouseout(this.onMouseOut());
    }
    this.html = htmlItemCrown;
    return this;
};
/**
 * OnClick Event
 * @returns {Function}
 */
ItemCrown.prototype.onClick = function () {
    var that = this;
    return function (e) {
        e.stopPropagation();
        e.preventDefault();
        if (that.eventOnClick) {
            that.eventOnClick(that);
        }
    };
};
/**
 * OnMouseDown Event
 * @returns {Function}
 */
ItemCrown.prototype.onMouseDown = function () {
    var that = this;
    return function (e) {
        e.stopPropagation();
        e.preventDefault();
        if (that.eventOnMouseDown) {
            that.eventOnMouseDown(that);
        }
    };
};
/**
 * OnMouseUp Event
 * @returns {Function}
 */
ItemCrown.prototype.onMouseUp = function () {
    var that = this;
    return function (e) {
        e.stopPropagation();
        e.preventDefault();
        if (that.eventOnMouseUp) {
            that.eventOnMouseUp(that);
        }
    };
};
/**
 * OnMouseMove Event
 * @returns {Function}
 */
ItemCrown.prototype.onMouseMove = function () {
    var that = this;
    return function (e) {
        e.stopPropagation();
        e.preventDefault();
        if (that.eventOnMouseMove) {
            that.eventOnMouseMove(that);
        }
    };
};
/**
 * OnMouseOut Event
 * @returns {Function}
 */
ItemCrown.prototype.onMouseOut = function () {
    var that = this;
    return function (e) {
        e.stopPropagation();
        e.preventDefault();
        if (that.eventOnMouseOut) {
            that.eventOnMouseOut(that);
        }
    };
};
