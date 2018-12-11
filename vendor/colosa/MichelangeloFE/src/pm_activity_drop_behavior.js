/**
 * @class PMUI.behavior.ContainerDropBehavior
 * Encapsulates the drop behavior of a container
 * @extends PMUI.behavior.DropBehavior
 *
 * @constructor
 * Creates a new instance of the class
 * @param {Array} [selectors=[]] css selectors that this drop behavior will
 * accept
 */
var PMActivityDropBehavior = function (selectors) {
    PMUI.behavior.DropBehavior.call(this, selectors);

};
PMActivityDropBehavior.prototype = new PMUI.behavior.DropBehavior();
/**
 * Type of the instances
 * @property {String}
 */
PMActivityDropBehavior.prototype.type = "PMActivityDropBehavior";
/**
 * Default selectors for this drop behavior
 * @property {String}
 */
PMActivityDropBehavior.prototype.defaultSelector = ".custom_shape";

/**
 * On drop handler for this drop behavior, creates shapes when dropped from the
 * toolbar, or move shapes among containers
 * @param {PMUI.draw.Shape} shape
 * @return {Function}
 */
PMActivityDropBehavior.prototype.onDrop = function (shape) {
    return function (e, ui) {
        var customShape = null,
            canvas = shape.getCanvas(),
            selection,
            sibling,
            i,
            command,
            coordinates,
            id,
            shapesAdded = [],
            containerBehavior = shape.containerBehavior;

        if (canvas.readOnly) {
            return false;
        }
        shape.layers.getFirst().removeCSSClasses(['dropableClass']);
        if (!shape.isValidDropArea) {
            return false;
        }

        shape.entered = false;
        if (ui.helper && ui.helper.attr('id') === "drag-helper") {
            return false;
        }
        id = ui.draggable.attr('id');

        customShape = canvas.shapeFactory(id);
        if (customShape === null) {
            customShape = canvas.customShapes.find('id', id);
            if (!customShape || !shape.dropBehavior.dropHook(shape, e, ui)) {
                return false;
            }

            if (!(customShape.parent &&
                customShape.parent.id === shape.id)) {
                selection = canvas.currentSelection;
                for (i = 0; i < selection.getSize(); i += 1) {
                    sibling = selection.get(i);
                    coordinates = PMUI.getPointRelativeToPage(sibling);
                    coordinates = PMUI.pageCoordinatesToShapeCoordinates(shape, null,
                        coordinates.x, coordinates.y, customShape);
                    shapesAdded.push({
                        shape: sibling,
                        container: shape,
                        x: coordinates.x,
                        y: coordinates.y,
                        topLeft: false
                    });
                }
                command = new PMUI.command.CommandSwitchContainer(shapesAdded);
                command.execute();
                canvas.commandStack.add(command);
                canvas.multipleDrop = true;

            }
            shape.setBoundary(customShape, customShape.numberRelativeToActivity);
            // fix resize minWidth and minHeight and also fix the dimension
            // of this shape (if a child made it grow)
            canvas.hideAllFocusLabels();
            canvas.updatedElement = null;

        } else {
            coordinates = PMUI.pageCoordinatesToShapeCoordinates(shape, e, null, null, customShape);
            if (PMUI.validCoordinatedToCreate(shape, e, customShape)) {
                shape.addElement(customShape, coordinates.x, coordinates.y,
                    customShape.topLeftOnCreation);
                //since it is a new element in the designer, we triggered the
                //custom on create element event
                canvas.updatedElement = customShape;
                if (customShape.getType() === 'PMLane') {
                    command = new PMCommandCreateLane(customShape);
                    canvas.commandStack.add(command);
                    command.execute();
                } else {
                    // create the command for this new shape
                    command = new PMUI.command.CommandCreate(customShape);
                    canvas.commandStack.add(command);
                    command.execute();
                }
                canvas.hideAllFocusLabels();
            }
        }
    };
};

PMActivityDropBehavior.prototype.setSelectors = function (selectors, overwrite) {
    PMUI.behavior.DropBehavior.prototype
        .setSelectors.call(this, selectors, overwrite);
    this.selectors.push(".port");
    return this;
};

PMActivityDropBehavior.prototype.onDragEnter = function (customShape) {
    return function (e, ui) {
        var shapeRelative, i;
        if (customShape.extendedType !== "PARTICIPANT") {
            if (ui.helper && ui.helper.hasClass("dragConnectHandler")) {
                if (customShape.extendedType !== "TEXT_ANNOTATION") {
                    shapeRelative = customShape.canvas.dragConnectHandlers.get(0).relativeShape;
                    if (shapeRelative.id !== customShape.id) {
                        for (i = 0; i < 4; i += 1) {
                            customShape.showConnectDropHelper(i, customShape);
                        }
                    }
                } else {
                    if (customShape.extendedType !== "H_LABEL" && customShape.extendedType !== "V_LABEL") {
                        shapeRelative = customShape.canvas.dragConnectHandlers.get(3).relativeShape;
                        if (shapeRelative.id !== customShape.id) {
                            customShape.canvas.hideDropConnectHandlers();
                            customShape.showConnectDropHelper(3, customShape);
                        }
                    }
                }
            } else {
                customShape.layers.getFirst().addCSSClasses(['dropableClass']);
                return false;
            }
        } else {
            shapeRelative = customShape.canvas.dragConnectHandlers.get(0).relativeShape;
            if (shapeRelative.id !== customShape.id) {
                if (ui.helper && ui.helper.hasClass("dragConnectHandler")) {
                    for (i = 0; i < 10; i += 1) {
                        connectHandler = customShape.canvas.dropConnectHandlers.get(i);
                        connectHandler.setDimension(18 * customShape.canvas.getZoomFactor(), 18 * customShape.canvas.getZoomFactor());
                        connectHandler.setPosition(customShape.getZoomX() + i * customShape.getZoomWidth() / 10, customShape.getZoomY() - connectHandler.height / 2 - 1);
                        connectHandler.relativeShape = customShape;
                        connectHandler.attachDrop();
                        connectHandler.setVisible(true);
                    }

                    for (i = 0; i < 10; i += 1) {
                        connectHandler = customShape.canvas.dropConnectHandlers.get(i + 10);
                        connectHandler.setDimension(18 * customShape.canvas.getZoomFactor(), 18 * customShape.canvas.getZoomFactor());
                        connectHandler.setPosition(customShape.getZoomX() + i * customShape.getZoomWidth() / 10, customShape.getZoomY() + customShape.getZoomHeight() - connectHandler.height / 2 - 1);
                        connectHandler.relativeShape = customShape;
                        connectHandler.attachDrop();
                        connectHandler.setVisible(true);
                    }
                }
            }
        }
    }
};
PMActivityDropBehavior.prototype.onDragLeave = function (shape) {
    return function (e, ui) {
        shape.layers.getFirst().removeCSSClasses(['dropableClass']);
    };
};