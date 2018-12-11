/*global jCore*/
var PMParticipantResizeBehavior = function () {
};

PMParticipantResizeBehavior.prototype = new PMUI.behavior.RegularResizeBehavior();
PMParticipantResizeBehavior.prototype.type = "PMParticipantResizeBehavior";
/**
 * Sets a shape's container to a given container
 * @param container
 * @param shape
 */
PMParticipantResizeBehavior.prototype.onResizeStart = function (shape) {
    return PMUI.behavior.RegularResizeBehavior
        .prototype.onResizeStart.call(this, shape);
};
/**
 * Removes shape from its current container
 * @param shape
 */
PMParticipantResizeBehavior.prototype.onResize = function (shape) {
    return function (e, ui) {
        PMUI.behavior.RegularResizeBehavior
            .prototype.onResize.call(this, shape)(e, ui);
        if (shape.graphic) {
            shape.paint();
        }

    };
};
