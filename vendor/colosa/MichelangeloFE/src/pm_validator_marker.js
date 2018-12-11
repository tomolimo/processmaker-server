/**
 * @class AdamMarker
 * Handle Activity Markers
 *
 * @constructor
 * Creates a new instance of the class
 * @param {Object} options
 */
var PMVAlidatorMarker = function (options) {
    PMMarker.call(this, options);
    PMVAlidatorMarker.prototype.initObject.call(this, options);
};
PMVAlidatorMarker.prototype = new PMMarker();
/**
 * Defines the object type
 * @type {String}
 */
PMVAlidatorMarker.prototype.type = 'PMVAlidatorMarker';

/**
 * Initialize the object with the default values
 * @param {Object} options
 */
PMVAlidatorMarker.prototype.initObject = function (options) {
    var defaults = {
        errors: {}
    };
    $.extend(true, defaults, options);
    this.setErrors(defaults.errors);
};

PMVAlidatorMarker.prototype.createHTML = function () {
    PMMarker.prototype.createHTML.call(this);
    this.html.className = 'PMVAlidatorMarker';
    return this.html;
};

PMVAlidatorMarker.prototype.paint = function () {
    PMMarker.prototype.paint.call(this);
    if (this.styleMarker) {
        this.styleMarker.paint();
    }
};
PMVAlidatorMarker.prototype.applyZoom = function () {
    this.setProperties();
    if (this.styleMarker) {
        this.styleMarker.applyZoom();
    }
    return this;
};

PMVAlidatorMarker.prototype.setErrors = function (errors) {
    this.errors = errors;
    this.errors.style.parent = this;
    this.styleMarker = new MarkerItem(this.errors.style);
    return this;
};

PMVAlidatorMarker.prototype.show = function () {
    if (this.html) {
        this.html.style.visibility = 'visible';
    }
    return this;
};

PMVAlidatorMarker.prototype.hide = function () {
    if (this.html) {
        this.html.style.visibility = 'hidden';
    }
    return this;
};

PMVAlidatorMarker.prototype.removeBoxMarker = function () {
    if (this.styleMarker) {
        this.styleMarker.removeErrorBox();
    }
    return this;
};

var MarkerItem = function (options) {
    PMUI.draw.Shape.call(this, options);
    this.markerZoomClassesError = [];
    this.markerZoomClassesWarning = [];
    this.infoDiv = null;
    this.typeMarker = "error";
    MarkerItem.prototype.initObject.call(this, options);
};
MarkerItem.prototype = new PMUI.draw.Shape();
MarkerItem.prototype.type = 'MarkerItem';
MarkerItem.prototype.initObject = function (options) {
    var defaults = {
        width: 14,
        height: 14
    };
    $.extend(true, defaults, options);
    this.setParent(defaults.parent)
        .setHeight(defaults.height)
        .setWidth(defaults.width)
        .setMarkerZoomClassesError(defaults.markerZoomClassesError)
        .setMarkerZoomClassesWarning(defaults.markerZoomClassesWarning);
};

MarkerItem.prototype.setParent = function (newParent) {
    this.parent = newParent;
    return this;
};
/**
 * Set style icons type Error
 * @param classes
 * @returns {MarkerItem}
 */
MarkerItem.prototype.setMarkerZoomClassesError = function (classes) {
    this.markerZoomClassesError = classes;
    return this;
};
/**
 * Set style icons type Warning
 * @param classes
 * @returns {MarkerItem}
 */
MarkerItem.prototype.setMarkerZoomClassesWarning = function (classes) {
    this.markerZoomClassesWarning = classes;
    return this;
};
/**
 * Set the type of Error (error, warning)
 * @param type
 * @returns {MarkerItem}
 */
MarkerItem.prototype.setTypeMarker = function (type) {
    this.typeMarker = type;
    if (this.html) {
        switch (this.getTypeMarker()) {
            case 'warning':
                this.html.className = this.markerZoomClassesWarning[this.parent.canvas.getZoomPropertiesIndex()];
                break;
            default:
                this.html.className = this.markerZoomClassesError[this.parent.canvas.getZoomPropertiesIndex()];
        }
    }
    return this;
};
/**
 * Get the type error marker.
 * @returns {string|*}
 */
MarkerItem.prototype.getTypeMarker = function () {
    return this.typeMarker;
};
/**
 * Apply zoom styles.
 * @returns {MarkerItem}
 */
MarkerItem.prototype.applyZoom = function () {
    var newSprite;
    this.removeAllClasses();
    this.setProperties();
    switch (this.getTypeMarker()) {
        case 'warning':
            newSprite = this.markerZoomClassesWarning[this.parent.canvas.getZoomPropertiesIndex()];
            break;
        default:
            newSprite = this.markerZoomClassesError[this.parent.canvas.getZoomPropertiesIndex()];
    }
    if (this.html) {
        this.html.className = newSprite;
    }
    this.currentZoomClass = newSprite;
    return this;
};
/**
 * Create DIV message of errors and events(show and hide).
 * @returns {*}
 */
MarkerItem.prototype.createHTML = function () {
    var that = this;
    PMUI.draw.Shape.prototype.createHTML.call(this);
    this.html.id = this.id;
    this.setProperties();
    this.currentZoomClass = this.html.className;
    this.parent.html.appendChild(this.html);
    $(this.html).mouseenter(function () {
        return that.showErrorBox();
    });
    $(this.html).mouseout(function () {
        return that.removeErrorBox();
    });
    return this.html;
};
/**
 * Create HTML.
 */
MarkerItem.prototype.paint = function () {
    if (this.getHTML() === null) {
        this.createHTML();
    }
};
/**
 * Show event to make hover.
 * @param item
 * @returns {Function}
 */
MarkerItem.prototype.showErrorBox = function () {
    var self = this,
        i,
        h,
        error,
        shape,
        shapeValidator,
        leftPixels = 7,
        left,
        right,
        widthContainer,
        widthWindow,
        freeSpace,
        missingSpace,
        boxErrors,
        numErrorsShape = 0;
    // Get parent Item Marker
    if (self.getParent()) {
        shapeValidator = self.getParent();
        if (shapeValidator.getParent()) {
            shape = shapeValidator.getParent();
        }
    }
    // Show List Errors
    if (shape) {
        if ($('.arrow_box')) {
            $('.arrow_box').remove();
        }
        numErrorsShape = shape.getNumErrors();
        if (numErrorsShape > 0) {
            // Crete Box Container
            self.infoDiv = document.createElement("div");
            h = document.createElement("ul");
            // Add Errors list
            for (i = 0; i < numErrorsShape; i += 1) {
                error = shape.errors.get(i);
                $(h).append('<li>' + error.description + '</li>');
            }
            self.infoDiv.appendChild(h);
            // Add class style arrow_box
            self.infoDiv.className = 'arrow_box';
            // Set Position of the container of errors
            self.infoDiv.style.left = shape.getAbsoluteX() + shape.getZoomWidth() + leftPixels + "px";
            self.infoDiv.style.top = shape.getAbsoluteY() + shape.canvas.getY() + "px";
            // Add the Container Errors to Body
            document.body.appendChild(self.infoDiv);
            // Reposition Container of Errors
            boxErrors = $('.arrow_box')[0];
            if (boxErrors) {
                right = boxErrors.getBoundingClientRect().right;
                left = boxErrors.getBoundingClientRect().left;
                widthContainer = right - left;
                widthWindow = $(window).width();
                freeSpace = widthWindow - left;

                if (freeSpace > 0 && widthContainer > 0) {
                    missingSpace = freeSpace - widthContainer;
                    if (missingSpace < 0) {
                        boxErrors.style.minWidth = freeSpace + "px";
                    }
                }
            }
        }
    }
};
/**
 * Remove Error Box
 */
MarkerItem.prototype.removeErrorBox = function () {
    var self = this;
    if (self.infoDiv) {
        $(self.infoDiv).remove();
    }
};
/**
 * Set Properties MarkerItem
 * @returns {MarkerItem}
 */
MarkerItem.prototype.setProperties = function () {
    if (this.html) {
        this.html.style.position = 'relative';
        this.html.style.width = this.width * PMUI.getActiveCanvas().getZoomFactor() + 'px';
        this.html.style.height = this.height * PMUI.getActiveCanvas().getZoomFactor() + 'px';
    }
    return this;
};
/**
 * Remove All Clasess MarkerItem
 * @returns {MarkerItem}
 */
MarkerItem.prototype.removeAllClasses = function () {
    if (this.html) {
        this.html.className = '';
    }
    return this;
};
