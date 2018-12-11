/**
 * @class PMArtifact
 * Handle BPMN Annotations
 *
 *
 * @constructor
 * Creates a new instance of the class
 * @param {Object} options
 */
var PMArtifact = function (options) {
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
PMArtifact.prototype = new PMShape();

/**
 * Defines the object type
 * @type {String}
 */
PMArtifact.prototype.type = "PMArtifact";
PMArtifact.prototype.PMArtifactResizeBehavior = null;

/**
 * Initialize the object with the default values
 * @param {Object} options
 */
PMArtifact.prototype.initObject = function (options) {
    var defaults = {
        art_type: 'TEXT_ANNOTATION',
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
PMArtifact.prototype.setArtifactType = function (type) {
    this.art_type = type;
    return this;
};
/**
 * Sets the artifact unique identifier
 * @param {String} value
 * @return {*}
 */
PMArtifact.prototype.setArtifactUid = function (value) {
    this.art_uid = value;
    return this;
};
/**
 * Returns the clean object to be sent to the backend
 * @return {Object}
 */
PMArtifact.prototype.getDataObject = function () {
    var name = this.getName(),
        container,
        element_id;
    switch (this.parent.type) {
        case 'PMCanvas':
            container = 'bpmnDiagram';
            element_id = this.canvas.id;
            break;
        case 'PMPool':
            container = 'bpmnPool';
            element_id = this.parent.id;
            break;
        case 'PMLane':
            container = 'bpmnLane';
            element_id = this.parent.id;
            break;
        case 'PMActivity':
            container = 'bpmnActivity';
            element_id = this.parent.id;
            break;
        default:
            container = 'bpmnDiagram';
            element_id = this.canvas.id;
            break;
    }
    return {
        art_uid: this.id,
        art_name: name,
        art_type: this.art_type,
        bou_x: this.x,
        bou_y: this.y,
        bou_width: this.width,
        bou_height: this.height,
        bou_container: container,
        bou_element: element_id,
        _extended: this.getExtendedObject()
    };
};

PMArtifact.prototype.getArtifactType = function () {
    return this.art_type;
};

PMArtifact.prototype.updateHTML = function () {
    var height, width;
    height = this.height;
    width = this.width;
    PMShape.prototype.updateHTML.call(this);
    this.setDimension(width, height);
    return this;
};

/**
 * Extends the paint method to draw text annotation lines
 */
PMArtifact.prototype.paint = function () {
    if (this.getArtifactType() === 'GROUP') {
        PMShape.prototype.paint.call(this);
    } else {
        if (!this.graphics || this.graphics === null) {
            this.graphics = new JSGraphics(this.id);
        } else {
            this.graphics.clear();
        }
        this.graphics.setStroke(1);
        this.graphics.drawLine(0, 0, 0, this.getZoomHeight());
        this.graphics.drawLine(0, 0, Math.round(this.getZoomWidth() * 0.25), 0);
        this.graphics.drawLine(0, this.getZoomHeight(), Math.round(this.getZoomWidth() * 0.25), this.getZoomHeight());
        this.graphics.paint();
        for (i = 0; i < this.labels.getSize(); i += 1) {
            label = this.labels.get(i);
            label.paint();
        }
        if (this.corona) {
            this.corona.paint();
            this.corona.hide();
        }
    }
};

PMArtifact.prototype.setResizeBehavior = function (behavior) {
    var factory = new PMUI.behavior.BehaviorFactory({
        products: {
            "regularresize": PMUI.behavior.RegularResizeBehavior,
            "Resize": PMUI.behavior.RegularResizeBehavior,
            "yes": PMUI.behavior.RegularResizeBehavior,
            "resize": PMUI.behavior.RegularResizeBehavior,
            "noresize": PMUI.behavior.NoResizeBehavior,
            "NoResize": PMUI.behavior.NoResizeBehavior,
            "no": PMUI.behavior.NoResizeBehavior,
            "annotationResize": PMArtifactResizeBehavior
        },
        defaultProduct: "noresize"
    });
    this.resizeBehavior = factory.make(behavior);
    if (this.html) {
        this.resize.init(this);
    }
    return this;
};

PMArtifact.prototype.createWithBpmn = function () {
    var businessObject = {};
    var bpmnElementType = this.getBpmnElementType();

    businessObject.elem = PMDesigner.bpmnFactory.create(bpmnElementType, {id: this.id, text: this.getName()});

    if (!businessObject.di) {
        if (this.type === 'Connection') {
            businessObject.di = PMDesigner.bpmnFactory.createDiEdge(businessObject.elem, [], {
                id: businessObject.id + '_di'
            });
        } else {
            businessObject.di = PMDesigner.bpmnFactory.createDiShape(businessObject.elem, {}, {
                id: businessObject.id + '_di'
            });
        }
    }
    this.businessObject = businessObject;

};