/**
 * @class ActivityContainerBehavior
 * Encapsulates the behavior of a regular container
 * @extends PMUI.behavior.RegularContainerBehavior
 *
 * @constructor
 * Creates a new instance of the class
 */
var ActivityContainerBehavior = function () {
};

ActivityContainerBehavior.prototype = new PMUI.behavior.RegularContainerBehavior();
/**
 * Type of the instances
 * @property {String}
 */
ActivityContainerBehavior.prototype.type = "ActivityContainerBehavior";
/**
 * Adds a shape to a given container given its coordinates
 * @param {PMUI.draw.BehavioralElement} container container using this behavior
 * @param {PMUI.draw.Shape} shape shape to be added
 * @param {number} x x coordinate where the shape will be added
 * @param {number} y y coordinate where the shape will be added
 * @param {boolean} topLeftCorner Determines whether the x and y coordinates
 * will be considered from the top left corner or from the center
 */
ActivityContainerBehavior.prototype.addToContainer = function (container, shape, x, y, topLeftCorner) {
    var shapeLeft = 0,
        shapeTop = 0,
        shapeWidth,
        shapeHeight,
        canvas,
        topLeftFactor = (topLeftCorner === true) ? 0 : 1;

    if (container.family === "Canvas") {
        canvas = container;
    } else {
        canvas = container.canvas;
    }
    shapeWidth = shape.getZoomWidth();
    shapeHeight = shape.getZoomHeight();

    shapeLeft += x - (shapeWidth / 2) * topLeftFactor;
    shapeTop += y - (shapeHeight / 2) * topLeftFactor;

    shapeLeft /= canvas.zoomFactor;
    shapeTop /= canvas.zoomFactor;

    shape.setParent(container);
    container.getChildren().insert(shape);
    this.addShape(container, shape, shapeLeft, shapeTop);

    // fix the zIndex of this shape and it's children
    shape.fixZIndex(shape, 1);
    canvas.addToList(shape);
    if (shape.getType() === 'PMEvent' && shape.getEventType() === 'BOUNDARY') {
        container.boundaryArray.insert(shape);
        if (container.boundaryPlaces.isEmpty()) {
            container.makeBoundaryPlaces();
        }
        shape.attachToActivity();
    } else {
        // fix resize minWidth and minHeight and also fix the dimension
        // of this shape (if a child made it grow)
        container.updateDimensions(10);
    }
};