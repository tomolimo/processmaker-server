/*global jCore*/
var PMArtifactResizeBehavior = function () {
};

PMArtifactResizeBehavior.prototype = new PMUI.behavior.RegularResizeBehavior();
PMArtifactResizeBehavior.prototype.type = "PMArtifactResizeBehavior";

/**
 * Sets a shape's container to a given container
 * @param container
 * @param shape
 */
PMArtifactResizeBehavior.prototype.onResizeStart = function (shape) {
    return PMUI.behavior.RegularResizeBehavior
        .prototype.onResizeStart.call(this, shape);
};
/**
 * Removes shape from its current container
 * @param shape
 */
PMArtifactResizeBehavior.prototype.onResize = function (shape) {
    return function (e, ui) {
        PMUI.behavior.RegularResizeBehavior
            .prototype.onResize.call(this, shape)(e, ui);
        if (shape.graphics) {
            shape.paint();
        }
    };
};
