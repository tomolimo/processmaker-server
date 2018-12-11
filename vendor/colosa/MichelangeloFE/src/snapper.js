/**
 * @class Snapper
 * Class snapper represents the helper shown while moving shapes.
 * @extend JCoreObject
 *
 * @constructor Creates an instance of the class Snapper
 * @param {Object} options Initialization options
 * @cfg {Point} [orientation="horizontal"] The default orientation of this snapper
 */
var PMSnapper = function (options) {
    PMUI.draw.Snapper.call(this, options);
    /**
     * Orientation of this snapper, it can be either "horizontal" or "vertical".
     * @property {string} [orientation=null]
     */
    this.orientation = null;
    /**
     * Data saved to define the positioning of this snapper in the canvas.
     * @property {Array} [data=[]]
     */
    this.data = [];
    /**
     * The visibility of this snapper.
     * @property {boolean} [visible=false]
     */
    this.visible = false;

    PMSnapper.prototype.initObject.call(this, options);
};

PMSnapper.prototype = new PMUI.draw.Snapper();

/**
 * The type of each instance of this class
 * @property {String}
 */
PMSnapper.prototype.type = "Snapper";

/**
 * Instance initializer which uses options to extend the config options to initialize the instance.
 * @param {Object} options The object that contains the config
 * @private
 */
PMSnapper.prototype.initObject = function (options) {
    var defaults = {
        orientation: "horizontal"
    };
    // extend recursively the defaultOptions with the given options
    $.extend(true, defaults, options);
    // call setters using the defaults object
    this.setOrientation(defaults.orientation);
    this.setDimension(defaults.width, defaults.height);
    // create the html (it's hidden initially)
    this.hide();
};
PMSnapper.prototype.getHTML = function () {
    if (!this.html) {
        this.createHTML();
    }
    return this.html;
};
/**
 * Creates the HTML representation of the snapper.
 * @returns {HTMLElement}
 */
PMSnapper.prototype.createHTML = function () {
    if (!this.html) {
        this.html = document.createElement("div");
        this.style.applyStyle();
        this.style.addProperties({
            position: "absolute",
            left: this.zoomX,
            top: this.zoomY,
            width: this.zoomWidth,
            height: this.zoomHeight,
            zIndex: this.zOrder
        });
        this.html.id = this.id;
        this.canvas.html.appendChild(this.html);
        this.setZOrder(99);
        this.html.className = 'mafe-snapper';
        if (this.getOrientation() === 'horizontal') {
            this.html.id = 'guide-h';
            this.html.style.borderTop = '1px dashed #55f';
            this.html.style.width = '100%';
        } else {
            this.html.id = 'guide-v';
            this.html.style.borderLeft = '1px dashed #55f';
            this.html.style.height = '100%';
        }
    }
    return this.html;
};

/**
 * Hides the snapper.
 * @chainable
 */
PMSnapper.prototype.hide = function () {
    this.visible = false;
    this.setVisible(this.visible);
    return this;
};

/**
 * Shows the snapper.
 * @chainable
 */
PMSnapper.prototype.show = function () {
    this.visible = true;
    this.setVisible(this.visible);
    return this;
};

/**
 * Fills the data for the snapper (using customShapes and regularShapes).
 * The data considered for each shape is:
 *
 * - Its absoluteX
 * - Its absoluteY
 * - Its absoluteX + width
 * - Its absoluteY + height
 *
 * @chainable
 */
PMSnapper.prototype.createSnapData = function () {
    var i,
        index = 0,
        shape,
        border = 0;

    // clear the data before populating it
    this.data = [];
    // populate the data array using the customShapes
    for (i = 0; i < this.canvas.customShapes.getSize(); i += 1) {
        shape = this.canvas.customShapes.get(i);
        if (!this.canvas.currentSelection.find('id', shape.getID())) {
            border = parseInt($(shape.getHTML()).css('borderTopWidth'), 10);
            if (this.orientation === 'horizontal') {
                this.data[index * 2] = shape.getAbsoluteY() - border;
                this.data[index * 2 + 1] = shape.getAbsoluteY() + shape.getZoomHeight();
            } else {
                this.data[index * 2] = shape.getAbsoluteX() - border;
                this.data[index * 2 + 1] = shape.getAbsoluteX() + shape.getZoomWidth();
            }
            index += 1;
        }

    }
    // populate the data array using the regularShapes
    for (i = 0; i < this.canvas.regularShapes.getSize(); i += 1) {
        shape = this.canvas.regularShapes.get(i);
        border = parseInt($(shape.getHTML()).css('borderTopWidth'), 10);
        if (this.orientation === 'horizontal') {
            this.data[index * 2] = shape.getAbsoluteY() - border;
            this.data[index * 2 + 1] = shape.getAbsoluteY() +
                shape.getZoomHeight();
        } else {
            this.data[index * 2] = shape.getAbsoluteX() - border;
            this.data[index * 2 + 1] = shape.getAbsoluteX() +
                shape.getZoomWidth();
        }
        index += 1;
    }
    return this;
};

/**
 * Sorts the data using the builtin `sort()` function, so that there's an strictly increasing order.
 * @chainable
 */
PMSnapper.prototype.sortData = function () {
    this.data.sort(function (a, b) {
        return a > b;
    });
    return this;
};

/**
 * Performs a binary search for `value` in `this.data`, return true if `value` was found in the data.
 * @param {number} value
 * @return {boolean}
 */
PMSnapper.prototype.binarySearch = function (value) {
    var low = 0,
        up = this.data.length - 1,
        mid;

    while (low <= up) {
        mid = parseInt((low + up) / 2, 10);
        if (this.data[mid] === value) {
            return value;
        }
        if (this.data[mid] > value) {
            up = mid - 1;
        } else {
            low = mid + 1;
        }
    }
    return false;
};

/**
 * Attaches listeners to this snapper, currently it only has the
 * mouseMove event which hides the snapper.
 * @param {Snapper} snapper
 * @chainable
 */
PMSnapper.prototype.attachListeners = function (snapper) {
    var $snapper = $(snapper.html).mousemove(
        function () {
            snapper.hide();
        }
    );
    return this;
};

/**
 * Sets the orientation of this snapper.
 * @param {string} orientation
 * @chainable
 */
PMSnapper.prototype.setOrientation = function (orientation) {
    if (orientation === "horizontal" || orientation === "vertical") {
        this.orientation = orientation;
    } else {
        throw new Error("setOrientation(): parameter is not valid".translate());
    }
    return this;
};

/**
 * Gets the orientation of this snapper.
 * @return {string}
 */
PMSnapper.prototype.getOrientation = function () {
    return this.orientation;
};