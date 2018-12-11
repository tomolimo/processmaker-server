var PMLine = function (options) {
    PMUI.draw.RegularShape.call(this, options);
    /**
     * Defines the type artifact
     * @type {String}
     */
    this.art_type = null;
    /**
     * Defines the unique identifier
     * @type {String}
     */
    this.art_uid = null;
    this.art_orientation = null;
    this.label = null;
    this.box = null;
    this.extended = null;
    this.extendedType = null;
    this.relationship = null;
    PMLine.prototype.init.call(this, options);
};
/**
 * Constant that represents that a drag behavior for moving the shape should be
 * used
 * @property {Number}
 */
PMLine.prototype = new PMUI.draw.RegularShape();

PMLine.prototype.type = "PMArtifact";
PMLine.prototype.family = "RegularShape";

PMLine.prototype.init = function (options) {
    var defaults = {
        art_orientation: "vertical",
        label: "",
        art_type: 'PMArtifact',
        art_name: "",
        art_uid: PMUI.generateUniqueId()
    };
    jQuery.extend(true, defaults, options);
    this.setOrientation(defaults.art_orientation)
        .setLabel(defaults.label)
        .setArtifactUid(defaults.art_uid)
        .setArtifactType(defaults.art_type)
        .setName(defaults.art_name);
};

PMLine.prototype.setOrientation = function (orientation) {
    var availableOpt, option;
    orientation = orientation.toLowerCase();
    availableOpt = {
        "vertical": "vertical",
        "horizontal": "horizontal"
    };
    option = (availableOpt[orientation]) ? orientation : "vertical";
    this.art_orientation = option;
    this.style.addClasses(["mafe-line-" + this.art_orientation]);
    return this;
};
PMLine.prototype.setLabel = function (label) {

    return this;
};
/**
 * Sets the label element
 * @param {String} value
 * @return {*}
 */
PMLine.prototype.setName = function (value) {
    if (this.label) {
        this.label.setMessage(value);
    }
    return this;
};
/**
 * Returns the label text
 * @return {String}
 */
PMLine.prototype.getName = function () {
    var text = "";
    if (this.label) {
        text = this.label.getMessage();
    }
    return text;
};
PMLine.prototype.setExtendedType = function (type) {
    this.extendedType = type;
    return this;
};

PMLine.prototype.setRelationship = function (relationship) {
    this.relationship = relationship;
    return this;
};
PMLine.prototype.addRelationship = function (object) {
    if (typeof object === "object") {
        jQuery.extend(true, this.relationship, object);
    }
    return this;
};
PMLine.prototype.setExtended = function (extended) {
    var ext;
    ext = (typeof extended === 'object') ? extended : {};
    this.extended = ext;
    return this;
};

/**
 * Sets the artifact type property
 * @param {String} type
 * @return {*}
 */
PMLine.prototype.setArtifactType = function (type) {
    this.art_type = type;
    return this;
};
/**
 * Sets the artifact unique identifier
 * @param {String} value
 * @return {*}
 */
PMLine.prototype.setArtifactUid = function (value) {
    this.art_uid = value;
    return this;
};
PMLine.prototype.getExtendedObject = function () {
    this.extended = {
        extendedType: this.extendedType
    };
    return this.extended;
};
/**
 * Returns the clean object to be sent to the backend
 * @return {Object}
 */
PMLine.prototype.getDataObject = function () {
    var name = this.getName();
    return {
        art_uid: this.art_uid,
        art_name: name,
        art_type: this.art_type,
        bou_x: this.x,
        bou_y: this.y,
        bou_width: this.width,
        bou_height: this.height,
        bou_container: 'bpmnDiagram',
        _extended: this.getExtendedObject()
    };
};

PMLine.prototype.getArtifactType = function () {
    return this.art_type;
};

/**
 * @event mousedown
 * Moused down callback fired when the user mouse downs on the `shape`
 * @param {PMUI.draw.Shape} shape
 */
PMLine.prototype.onMouseDown = function (shape) {
    return function (e, ui) {
        e.stopPropagation();
    };
};

PMLine.prototype.createHTML = function () {
    var width = this.width || 20000,
        height = this.height || 20000;
    PMUI.draw.RegularShape.prototype.createHTML.call(this);
    this.style.removeAllProperties();
    this.style.addProperties({
        position: "absolute",
        cursor: "move"
    });
    if (this.art_orientation === "vertical") {
        this.height = height;
        this.width = 5;
        this.style.addProperties({
            width: "5px",
            height: height + "px",
            top: -parseInt(height / 3, 10)
        });
    } else {
        this.width = width;
        this.height = 5;
        this.style.addProperties({
            width: width + "px",
            height: "5px",
            left: -parseInt(width / 3, 10)
        });
    }
    return this.html;
};