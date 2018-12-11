/*global jCore*/
var PMLaneResizeBehavior = function () {
};

PMLaneResizeBehavior.prototype = new PMUI.behavior.RegularResizeBehavior();
PMLaneResizeBehavior.prototype.type = "PMLaneResizeBehavior";

PMLaneResizeBehavior.prototype.init = function (shape) {
    PMUI.behavior.RegularResizeBehavior
        .prototype.init.call(this, shape);
    $shape = $(shape.getHTML());
    $shape.resizable();
    $shape.resizable('option', 'minHeight', 30 * shape.canvas.getZoomFactor());
};
/**
 * Sets a shape's container to a given container
 * @param container
 * @param shape
 */
PMLaneResizeBehavior.prototype.onResizeStart = function (shape) {
    return PMUI.behavior.RegularResizeBehavior
        .prototype.onResizeStart.call(this, shape);
};
/**
 * Removes shape from its current container
 * @param shape
 */
PMLaneResizeBehavior.prototype.onResize = function (shape) {
    return function (e, ui) {
        PMUI.behavior.RegularResizeBehavior
            .prototype.onResize.call(this, shape)(e, ui);
    };
};

PMLaneResizeBehavior.prototype.onResizeEnd = function (shape) {
    return function (e, ui) {
        var i,
            j,
            label,
            command;
        shape.resizing = false;
        shape.canvas.isResizing = false;
        // last resize
        PMUI.behavior.RegularResizeBehavior.prototype.onResize.call(this, shape)(e, ui);
        // show the handlers again
        shape.showOrHideResizeHandlers(true);
        for (i = 0; i < shape.labels.getSize(); i += 1) {
            label = shape.labels.get(i);
            label.setLabelPosition(label.location, label.diffX, label.diffY);
        }
        // TESTING COMMANDS
        command = new PMCommandLaneResize(shape);
        command.execute();
        shape.canvas.commandStack.add(command);
        shape.parent.updateDimensionsWithLanes();
        return true;
    };
};
