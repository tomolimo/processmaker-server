/*global jCore, $ */
/**
 * @class AdamMarker
 * Handle Activity Markers
 *
 * @constructor
 * Creates a new instance of the class
 * @param {Object} options
 */
var PMMarker = function (options) {
    PMUI.draw.Shape.call(this, options);
    /**
     * Defines the positions of the markers
     * @type {Array}
     * @private
     */
    this.positions = ['left+2 top+2', 'center top+5', 'right top',
        'left+5 bottom-1', 'center bottom-2', 'right-5 bottom-1'];
    /**
     * Defines the offset of the markers
     * @type {Array}
     * @private
     */
    this.offset = ['5 5', '0 5', '0 5', '5 -1', '0 -1', '-5 -1'];
    /**
     * Define the marker type property
     * @type {null}
     */
    this.markerType = null;
    PMMarker.prototype.initObject.call(this, options);
};
PMMarker.prototype = new PMUI.draw.Shape();
/**
 * Defines the object type
 * @type {String}
 */
PMMarker.prototype.type = 'PMMarker';

/**
 * Initialize the object with the default values
 * @param {Object} options
 */
PMMarker.prototype.initObject = function (options) {
    var defaults = {
        canvas: null,
        parent: null,
        position: 0,
        width: 21,
        height: 21,
        markerZoomClasses: [],
        markerType: null
    };
    $.extend(true, defaults, options);
    this.setParent(defaults.parent)
        .setPosition(defaults.position)
        .setHeight(defaults.height)
        .setWidth(defaults.width)
        .setMarkerZoomClasses(defaults.markerZoomClasses)
        .setMarkerType(defaults.markerType);
};

/**
 * Applies zoom to the Marker
 * @return {*}
 */
PMMarker.prototype.applyZoom = function () {
    var newSprite;
    this.removeAllClasses();
    this.setProperties();
    newSprite = this.markerZoomClasses[this.parent.canvas.zoomPropertiesIndex];
    this.html.className = newSprite;
    this.currentZoomClass = newSprite;
    return this;
};

/**
 * Create the HTML for the marker
 * @return {*}
 */
PMMarker.prototype.createHTML = function () {
    PMUI.draw.Shape.prototype.createHTML.call(this);

    this.html.id = this.id;
    this.setProperties();
    this.html.className = this.markerZoomClasses[
        this.parent.canvas.getZoomPropertiesIndex()
        ];
    this.currentZoomClass = this.html.className;
    this.parent.html.appendChild(this.html);
    return this.html;
};

/**
 * Updates the painting of the marker
 * @param update
 */
PMMarker.prototype.paint = function (update) {
    if (this.getHTML() === null || update) {
        this.createHTML();
    }
    $(this.html).position({
        of: $(this.parent.html),
        my: this.positions[this.position],
        at: this.positions[this.position],
        collision: 'none'
    });
};

/**
 * Sets the marker type property
 * @param {String} newType
 * @return {*}
 */
PMMarker.prototype.setMarkerType = function (newType) {
    this.markerType = newType;
    return this;
};

/**
 * Sets the position of the marker
 * @param {Number} newPosition
 * @return {*}
 */
PMMarker.prototype.setPosition = function (newPosition) {
    if (newPosition !== null && typeof newPosition === 'number') {
        this.position = newPosition;
    }
    return this;
};

/**
 * Sets the parent of the marker
 * @param {AdamActivity} newParent
 * @return {*}
 */
PMMarker.prototype.setParent = function (newParent) {
    this.parent = newParent;
    return this;
};

/**
 * Sets the elements class
 * @param eClass
 * @return {*}
 */
PMMarker.prototype.setEClass = function (eClass) {
    this.currentZoomClass = eClass;
    return this;
};

/**
 * Sets the array of zoom classes
 * @param {Object} classes
 * @return {*}
 */
PMMarker.prototype.setMarkerZoomClasses = function (classes) {
    this.markerZoomClasses = classes;
    return this;
};

/**
 * Sets the marker HTML properties
 * @return {*}
 */
PMMarker.prototype.setProperties = function () {
    this.html.style.width = this.width * this.parent.getCanvas().getZoomFactor() + 'px';
    this.html.style.height = this.height * this.parent.getCanvas().getZoomFactor() + 'px';
    return this;
};

/**
 * Remove all classes of HTML
 * @return {*}
 */
PMMarker.prototype.removeAllClasses = function () {
    this.html.className = '';
    return this;
};
