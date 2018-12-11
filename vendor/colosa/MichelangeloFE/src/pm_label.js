/**
 * @class PMArtifact
 * Handle BPMN Annotations
 *
 *
 * @constructor
 * Creates a new instance of the class
 * @param {Object} options
 */
var PMLabel = function (options) {
    PMShape.call(this, options);
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

    PMArtifact.prototype.initObject.call(this, options);
};
PMLabel.prototype = new PMShape();

/**
 * Defines the object type
 * @type {String}
 */
PMLabel.prototype.type = "PMArtifact";
PMLabel.prototype.PMArtifactResizeBehavior = null;

/**
 * Initialize the object with the default values
 * @param {Object} options
 */
PMArtifact.prototype.initObject = function (options) {
    var defaults = {
        art_type: 'PMArtifact',
        art_name: ""
    };

    jQuery.extend(true, defaults, options);
    this.setArtifactUid(defaults.art_uid);
    this.setArtifactType(defaults.art_type);
    this.setName(defaults.art_name);

};

/**
 * Sets the artifact type property
 * @param {String} type
 * @return {*}
 */
PMLabel.prototype.setArtifactType = function (type) {
    this.art_type = type;
    return this;
};
/**
 * Sets the artifact unique identifier
 * @param {String} value
 * @return {*}
 */
PMLabel.prototype.setArtifactUid = function (value) {
    this.art_uid = value;
    return this;
};
/**
 * Returns the clean object to be sent to the backend
 * @return {Object}
 */
PMLabel.prototype.getDataObject = function () {
    var name = this.getName();
    return {
        art_uid: this.id,
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

PMLabel.prototype.getArtifactType = function () {
    return this.art_type;
};
PMLabel.prototype.createLayer = function (options) {

    var layer;
    options.parent = this;
    layer = new CustomLayer(options);
    this.addLayer(layer);
    return layer;
};
PMArtifact.prototype.updateHTML = function () {
    var height, width;
    height = this.height;
    width = this.width;
    PMShape.prototype.updateHTML.call(this);
    this.setDimension(width, height);
    return this;
};