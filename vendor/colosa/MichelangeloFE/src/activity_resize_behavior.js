/*global jCore*/
var PMActivityResizeBehavior = function () {
};

PMActivityResizeBehavior.prototype = new PMUI.behavior.RegularResizeBehavior();
PMActivityResizeBehavior.prototype.type = "PMActivityResizeBehavior";

/**
 * Sets a shape's container to a given container
 * @param container
 * @param shape
 */
PMActivityResizeBehavior.prototype.onResizeStart = function (shape) {
    return PMUI.behavior.RegularResizeBehavior
        .prototype.onResizeStart.call(this, shape);
};
/**
 * Removes shape from its current container
 * @param shape
 */
PMActivityResizeBehavior.prototype.onResize = function (shape) {
    return function (e, ui) {
        PMUI.behavior.RegularResizeBehavior
            .prototype.onResize.call(this, shape)(e, ui);
        shape.paint();
        shape.updateBoundaryPositions(false);
    };
};

PMActivityResizeBehavior.prototype.onResizeEnd = function (shape) {
    return function (e, ui) {
        var i,
            label,
            command,
            margin = 10;
        shape.resizing = false;
        shape.canvas.isResizing = false;
        // last resize
        PMUI.behavior.RegularResizeBehavior.prototype.onResize.call(this, shape)(e, ui);

        // show the handlers again
        shape.showOrHideResizeHandlers(true);

        // update the dimensions of the parent if possible (a shape might
        // have been resized out of the dimensions of its parent)
        shape.parent.updateDimensions(margin);

        if (shape.ports) {
            shape.firePortsChange();
        }

        // TESTING COMMANDS
        command = new PMCommandResize(shape);
        shape.canvas.commandStack.add(command);
        command.execute();
        for (i = 0; i < shape.labels.getSize(); i += 1) {
            label = shape.labels.get(i);
            label.setLabelPosition(label.location, label.diffX, label.diffY);
        }

        return true;
    };
};
