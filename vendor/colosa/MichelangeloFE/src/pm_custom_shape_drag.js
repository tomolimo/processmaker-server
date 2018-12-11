var PMCustomShapeDragBehavior = function () {
};
PMCustomShapeDragBehavior.prototype = new PMUI.behavior.CustomShapeDragBehavior();
/**
 * Type of the instances
 * @property {String}
 */
PMCustomShapeDragBehavior.prototype.type = "CustomShapeDragBehavior";
/**
 * Attach the drag behavior and ui properties to the corresponding shape
 * @param {PMUI.draw.CustomShape} customShape
 */
PMCustomShapeDragBehavior.prototype.attachDragBehavior = function (customShape) {
    var dragOptions,
        $customShape = $(customShape.getHTML());
    dragOptions = {
        revert: false,
        helper: "none",
        cursorAt: false,
        revertDuration: 0,
        disable: false,
        grid: [1, 1],
        start: this.onDragStart(customShape),
        drag: this.onDrag(customShape, true),
        stop: this.onDragEnd(customShape, true)
    };
    $customShape.draggable({'cursor': "move"});
    $customShape.draggable(dragOptions);
};
/**
 * On drag start handler, it uses the {@link PMUI.behavior.RegularDragBehavior}.onDragStart
 * method to initialize the drag, but also initializes other properties
 * @param {PMUI.draw.CustomShape} customShape
 * @return {Function}
 */
PMCustomShapeDragBehavior.prototype.onDragStart = function (customShape) {
    return function (e, ui) {
        customShape.canvas.hideAllCoronas();
        if (customShape.canvas.canConnect) {
            if (customShape.canvas.connectStartShape.getID() !== customShape.getID()) {
                customShape.canvas.connectProcedure(customShape, e);
            }
            customShape.canvas.cancelConnect();
            return false;
        }

        if (customShape.canvas.currentSelection.asArray().length == 0) {
            customShape.canvas.addToSelection(customShape);
        }
        PMUI.behavior.RegularDragBehavior.prototype.onDragStart.call(this,
            customShape)(e, ui);
        customShape.previousXDragPosition = customShape.getX();
        customShape.previousYDragPosition = customShape.getY();
        if (customShape.canvas.snapToGuide) {
            //init snappers
            customShape.canvas.startSnappers(e);
        }
        customShape.canvas.isDragging = true;
        //to validate drag container
        customShape.canvas.setLassoLimits();
    };
};

/**
 * Procedure executed while dragging, it takes care of multiple drag, moving
 * connections, updating positions and children of the shapes being dragged
 * @param {PMUI.draw.CustomShape} customShape shape being dragged
 * @param {boolean} root return whether this is the shape where the drag started
 * @param {number} childDiffX x distance needed for the non-root shapes to move
 * @param {number} childDiffY y distance needed for the non-root shapes to move
 * @param {Object} e jQuery object containing the properties when a drag event
 * occur
 * @param {Object} ui JQuery UI object containing the properties when a drag
 * event occur
 */
PMCustomShapeDragBehavior.prototype.onDragProcedure = function (customShape, root, childDiffX, childDiffY, e, ui) {
    var i,
        j,
        sibling,
        diffX,
        diffY,
        port,
        child,
        connection,
        shape1,
        shape2,
        canvas = customShape.canvas,
        k,
        uiOffset,
        positionsX1 = [];
    uiOffset = {};
    uiOffset.x = ui.helper.position().left / canvas.zoomFactor;
    uiOffset.y = ui.helper.position().top / canvas.zoomFactor;
    uiOffset.diffX = customShape.x - uiOffset.x;
    uiOffset.diffY = customShape.y - uiOffset.y;
    // shapes
    if (root) {
        // Commented for problem on snappers
        if (customShape.canvas.snapToGuide) {
            customShape.canvas.processGuides(e, ui, customShape);
        }
        customShape.setPosition(uiOffset.x, uiOffset.y);

        diffX = customShape.x - customShape.previousXDragPosition;
        diffY = customShape.y - customShape.previousYDragPosition;

        customShape.previousXDragPosition = customShape.x;
        customShape.previousYDragPosition = customShape.y;

        for (i = 0; i < customShape.canvas.currentSelection.getSize(); i += 1) {
             sibling = customShape.canvas.currentSelection.get(i);
             if (sibling.id !== customShape.id) {
                sibling.setPosition(sibling.x + diffX, sibling.y + diffY);
             }
        }
    } else {
        customShape.setPosition(customShape.x, customShape.y);
    }
    // children
    if (root) {
        for (i = 0; i < customShape.canvas.currentSelection.getSize(); i += 1) {
            sibling = customShape.canvas.currentSelection.get(i);
            for (j = 0; j < sibling.children.getSize(); j += 1) {
                child = sibling.children.get(j);
                PMCustomShapeDragBehavior.prototype.onDragProcedure.call(this, child,
                    false, diffX, diffY, e, ui);
            }
        }
    } else {
        for (i = 0; i < customShape.children.getSize(); i += 1) {
            child = customShape.children.get(i);
            PMCustomShapeDragBehavior.prototype.onDragProcedure.call(this, child,
                false, childDiffX, childDiffY, e, ui);
        }
    }
    // connections
    if (root) {
        for (i = 0; i < customShape.canvas.currentSelection.getSize(); i += 1) {
            sibling = customShape.canvas.currentSelection.get(i);
            this.updateAndRepaintPositions({
                customShape: customShape,
                sibling: sibling,
                diffX: diffX,
                diffY: diffY
            });

        }
    } else {
        this.updateAndRepaintPositions({
            customShape: customShape,
            sibling: customShape,
            diffX: childDiffX,
            diffY: childDiffY
        });
    }
    if (customShape) {
        customShape.wasDragged = true;
    }
};

/**
 * Updates port position and repaint connections
 * @param options
 */
PMCustomShapeDragBehavior.prototype.updateAndRepaintPositions = function (options) {
    var j,
        port,
        connection,
        customShape = options.customShape,
        sibling = options.sibling;
    // move the segments of this connections
    for (j = 0; j < customShape.canvas.sharedConnections.getSize(); j += 1) {
        connection = customShape.canvas.sharedConnections.get(j);
        if (connection.srcPort.parent.getID() ===
            sibling.getID()) {
            // to avoid moving the connection twice
            // (two times per shape), move it only if the shape
            connection.move(options.diffX * customShape.canvas.zoomFactor,
                options.diffY * customShape.canvas.zoomFactor);
        }
    }
    for (j = 0; j < sibling.ports.getSize(); j += 1) {
        //for each port update its absolute position and repaint its connection
        port = sibling.ports.get(j);
        connection = port.connection;
        port.setPosition(port.x, port.y);

        if (!customShape.canvas.sharedConnections.contains(connection)) {
            connection
                .setSegmentColor(PMUI.util.Color.GREY, false)
                .setSegmentStyle("regular", false)// repaint:  false
                .disconnect()
                .connect();
        }
    }
};
/**
 * On drag handler, calls the drag procedure while the dragging is occurring,
 * and also takes care of the snappers
 * @param {PMUI.draw.CustomShape} customShape shape being dragged
 * @param {boolean} root return whether this is the shape where the drag started
 * @param {number} childDiffX x distance needed for the non-root shapes to move
 * @param {number} childDiffY y distance needed for the non-root shapes to move
 * @return {Function}
 */
PMCustomShapeDragBehavior.prototype.onDrag = function (customShape, root, childDiffX, childDiffY) {
    var self = this;
    return function (e, ui) {
        self.onDragProcedure(customShape, root, childDiffX,
            childDiffY, e, ui);
    };
};
/**
 * Procedure executed on drag end, it takes care of multiple drag, moving
 * connections, updating positions and children of the shapes being dragged
 * @param {PMUI.draw.CustomShape} customShape shape being dragged
 * @param {boolean} root return whether this is the shape where the drag started
 * @param {Object} e jQuery object containing the properties when a drag event
 * occur
 * @param {Object} ui JQuery UI object containing the properties when a drag
 * event occur
 */
PMCustomShapeDragBehavior.prototype.dragEndProcedure = function (customShape, root, e, ui) {
    var i,
        j,
        sibling,
        port,
        child,
        connection,
        shape1,
        shape2,
        canvas = customShape.canvas,
        diffX = 0,
        diffY = 0;
    if (root) {
        // the difference between this segment of code and the segment of code
        // found in dragProcedure is that it's not needed to move the shapes
        // anymore using differentials
        customShape.wasDragged = false;
        customShape.canvas.isDragging = false;

        // validate lasso limits
        if (canvas.lassoLimits.x.shape && canvas.lassoLimits.x.shape.getX() < 0) {
            diffX -= canvas.lassoLimits.x.shape.getX();
        }
        if (canvas.lassoLimits.y.shape && canvas.lassoLimits.y.shape.getY() < 0) {
            diffY -= canvas.lassoLimits.y.shape.getY();
        }

        for (i = 0; i < customShape.canvas.currentSelection.getSize();
             i += 1) {
            sibling = customShape.canvas.currentSelection.get(i);
            // if dragging with lasso is rebasing the limit
            if (diffX > 0 || diffY > 0) {
                sibling.setPosition(sibling.getX() + diffX,  sibling.getY() + diffY);
            }
            for (j = 0; j < sibling.children.getSize(); j += 1) {
                child = sibling.children.get(j);
                child.changedContainer = false;
                PMUI.behavior.CustomShapeDragBehavior.prototype.dragEndProcedure.call(this,
                    child, false, e, ui);
            }
        }
    } else {
        for (i = 0; i < customShape.children.getSize(); i += 1) {
            child = customShape.children.get(i);
            PMUI.behavior.CustomShapeDragBehavior.prototype.dragEndProcedure.call(this,
                child, false, e, ui);
        }
    }
    // connections
    if (root) {
        for (i = 0; i < customShape.canvas.currentSelection.getSize();
             i += 1) {
            sibling = customShape.canvas.currentSelection.get(i);
            for (j = 0; j < sibling.ports.getSize(); j += 1) {
                // for each port update its absolute position and repaint
                // its connection
                port = sibling.ports.get(j);
                connection = port.connection;

                port.setPosition(port.x, port.y);

                if (customShape.canvas.sharedConnections.
                        find('id', connection.getID())) {
                    // move the segments of this connections
                    if (connection.srcPort.parent.getID() ===
                        sibling.getID()) {
                        // to avoid moving the connection twice
                        // (two times per shape), move it only if the shape
                        // holds the sourcePort
                        connection.disconnect(true).connect({
                            algorithm: 'user',
                            points: connection.points,
                            dx: parseFloat($(connection.html).css('left')),
                            dy: parseFloat($(connection.html).css('top'))
                        });
                        connection.checkAndCreateIntersectionsWithAll();
                        connection.canvas.triggerUserStateChangeEvent(connection);
                    }
                } else {
                    connection
                        .setSegmentColor(connection.originalSegmentColor, false)
                        .setSegmentStyle(connection.originalSegmentStyle, false)
                        .disconnect()
                        .connect();
                    connection.setSegmentMoveHandlers();
                    connection.checkAndCreateIntersectionsWithAll();
                }
            }
        }
    } else {
        for (i = 0; i < customShape.ports.getSize(); i += 1) {
            //for each port update its absolute position and repaint
            //its connection
            port = customShape.ports.get(i);
            connection = port.connection;
            shape1 = connection.srcPort.parent;
            shape2 = connection.destPort.parent;

            port.setPosition(port.x, port.y);
            if (customShape.canvas.sharedConnections.
                    find('id', connection.getID())) {
                // to avoid moving the connection twice
                // (two times per shape), move it only if the shape
                // holds the sourcePort
                if (connection.srcPort.parent.getID() ===
                    customShape.getID()) {
                    connection.checkAndCreateIntersectionsWithAll();
                }
            } else {
                connection
                    .setSegmentColor(connection.originalSegmentColor, false)
                    .setSegmentStyle(connection.originalSegmentStyle, false)
                    .disconnect()
                    .connect();
                connection.setSegmentMoveHandlers();
                connection.checkAndCreateIntersectionsWithAll();
            }
        }
    }
};
/**
 * On drag end handler, ot calls drag end procedure, removes the snappers and,
 * fires the command move if necessary
 * @param {PMUI.draw.CustomShape} customShape
 * @return {Function}
 */
PMCustomShapeDragBehavior.prototype.onDragEnd = function (customShape) {
    var command,
        self = this;
    return function (e, ui) {
        // call to dragEnd procedure
        self.dragEndProcedure(customShape, true, e, ui);
        customShape.dragging = false;
        // hide the snappers
        customShape.canvas.verticalSnapper.hide();
        customShape.canvas.horizontalSnapper.hide();
        if (!customShape.changedContainer) {
            if (customShape.parent.getType() === 'PMLane') {
                command = new PMCommandMoveInLane(customShape.canvas.currentSelection);
            } else {
                command = new PMUI.command.CommandMove(customShape.canvas.currentSelection);
            }
            command.execute();
            customShape.canvas.commandStack.add(command);
        }
        customShape.changedContainer = false;
        // decrease the zIndex of the oldParent of customShape
        customShape.decreaseParentZIndex(customShape.oldParent);
        // force to apply zoom, when move more than two figures
        if (customShape.getCanvas().getCurrentSelection().getSize() > 1) {
            customShape.getCanvas().applyZoom(customShape.getCanvas().getZoomPropertiesIndex() + 1);
        }
        customShape.getCanvas().emptyCurrentSelection();
    };
};
