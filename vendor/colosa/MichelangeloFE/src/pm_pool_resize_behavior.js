var PMPoolResizeBehavior = function () {
    this.oldHeight = null;
    this.isTop = false;
};

PMPoolResizeBehavior.prototype = new PMUI.behavior.RegularResizeBehavior();
PMPoolResizeBehavior.prototype.type = "PMPoolResizeBehavior";
PMPoolResizeBehavior.prototype.init = function (shape) {
    PMUI.behavior.RegularResizeBehavior
        .prototype.init.call(this, shape);
    $shape = $(shape.getHTML());
    $shape.resizable();
    $shape.resizable('option', 'minWidth', 200 * shape.canvas.getZoomFactor());
    $shape.resizable('option', 'minHeight', 30 * shape.canvas.getZoomFactor());
};

/**
 * Sets a shape's container to a given container
 * @param container
 * @param shape
 */
PMPoolResizeBehavior.prototype.onResizeStart = function (shape) {
    return function (e, ui) {
        PMUI.behavior.RegularResizeBehavior
            .prototype.onResizeStart.call(this, shape)(e, ui);
        //shape.hideAllChilds();
        shape.hasMinimun = false;
        if (shape.bpmnLanes.getSize() > 0) {
            this.lastLaneHeight = shape.bpmnLanes.getLast().getHeight();
            this.firstLaneHeight = shape.bpmnLanes.getFirst().getHeight();
        }

    };
};
/**
 * Removes shape from its current container
 * @param shape
 */
PMPoolResizeBehavior.prototype.onResize = function (shape) {
    return function (e, ui) {
        var i,
            port,
            diffH,
            newWidth,
            lane,
            top = true,
            newY,
            canvas = shape.canvas;
        shape.setPosition(ui.position.left / canvas.zoomFactor,
            ui.position.top / canvas.zoomFactor);
        shape.setDimension(ui.size.width / canvas.zoomFactor,
            ui.size.height / canvas.zoomFactor);
        if (shape.graphic) {
            shape.paint();
        }
        //to resize last lane
        diffH = (ui.size.height / canvas.zoomFactor) - (ui.originalSize.height / canvas.zoomFactor);
        newWidth = shape.getWidth() - shape.headLineCoord - 1.2;

        if (shape.bpmnLanes.getSize() > 0) {
            if (ui.originalPosition.top === ui.position.top) {
                this.isTop = false;
                shape.setMinimunsResize();
                lane = shape.bpmnLanes.getLast();
                lane.setDimension(newWidth, this.lastLaneHeight + diffH);
                for (i = 0; i < shape.bpmnLanes.getSize() - 1; i += 1) {
                    lane = shape.bpmnLanes.get(i);
                    lane.setDimension(newWidth, lane.getHeight());
                }
            } else {
                this.isTop = true;
                shape.setMinimunsResize(true);

                lane = shape.bpmnLanes.getFirst();
                lane.setDimension(newWidth, this.firstLaneHeight + diffH);
                newY = this.firstLaneHeight + diffH;

                for (i = 1; i < shape.bpmnLanes.getSize(); i += 1) {
                    lane = shape.bpmnLanes.get(i);
                    lane.setPosition(lane.getX(), newY);
                    lane.setDimension(newWidth, lane.getHeight());
                    newY += lane.getHeight();
                }
            }

        }
    };
};
/**
 * Adds a shape to a given container
 * @param container
 * @param shape
 */
PMPoolResizeBehavior.prototype.onResizeEnd = function (shape) {
    return function (e, ui) {
        var i,
            size,
            label,
            delta;
        shape.resizing = false;
        shape.canvas.isResizing = false;
        // last resize
        PMUI.behavior.RegularResizeBehavior.prototype.onResize.call(this, shape)(e, ui);
        // show the handlers again
        shape.showOrHideResizeHandlers(true);
        // update the dimensions of the parent if possible (a shape might
        // have been resized out of the dimensions of its parent)
        shape.updateBpmnOnResize();
        for (i = 0, size = shape.labels.getSize(); i < size; i += 1) {
            label = shape.labels.get(i);
            label.setLabelPosition(label.location, label.diffX, label.diffY);
        }
        delta = {
            dx: shape.x - shape.oldX,
            dy: shape.y - shape.oldY
        };
        options = {
            isTop: this.isTop,
            beforeHeightsOpt: {
                lastLaneHeight: this.lastLaneHeight,
                firstLaneHeight: this.firstLaneHeight
            },
            delta: delta
        };
        var command = new PMCommandPoolResize(shape, options);
        shape.getCanvas().commandStack.add(command);
        shape.canvas.refreshArray.clear();
        shape.poolChildConnectionOnResize(true, true);
        shape.refreshAllPoolConnections(false, delta);
        //force to activate save button
        PMDesigner.project.updateElement([]);
    };
};

/**
 * Updates the minimum height and maximum height of the JQqueryUI's resizable plugin.
 * @param {PMUI.draw.Shape} shape
 * @chainable
 */
PMPoolResizeBehavior.prototype.updateResizeMinimums = function (shape) {
    var minW,
        minH,
        children = shape.getChildren(),
        limits = children.getDimensionLimit(),
        margin = 15,
        $shape = $(shape.getHTML());
    if (children.getSize() > 0) {
        minW = (limits[1] + margin) * shape.canvas.getZoomFactor();
        minH = (limits[2] + margin) * shape.canvas.getZoomFactor();
    } else {
        minW = 300 * shape.canvas.getZoomFactor();
        minH = 30 * shape.canvas.getZoomFactor();
    }
    // update jQueryUI's minWidth and minHeight
    $shape.resizable();
    $shape.resizable('option', 'minWidth', minW);
    $shape.resizable('option', 'minHeight', minH);
    return this;
};