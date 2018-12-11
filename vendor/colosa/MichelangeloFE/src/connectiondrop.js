/**
 * @class PMConnectionDropBehavior
 * Extends the functionality to handle creation of connections
 *
 * @constructor
 * Creates a new instance of the object
 */
var PMConnectionDropBehavior = function (selectors) {
    PMUI.behavior.ConnectionDropBehavior.call(this, selectors);
};
PMConnectionDropBehavior.prototype = new PMUI.behavior.ConnectionDropBehavior();
/**
 * Defines the object type
 * @type {String}
 */
PMConnectionDropBehavior.prototype.type = "PMConnectionDropBehavior";

/**
 * Defines a Map of the basic Rules
 * @type {Object}
 */
PMConnectionDropBehavior.prototype.basicRules = {
    PMEvent: {
        PMEvent: {
            connection: 'regular',
            type: 'SEQUENCE'
        },
        PMActivity: {
            connection: 'regular',
            type: 'SEQUENCE'
        }
    },
    PMActivity: {
        PMActivity: {
            connection: 'regular',
            type: 'SEQUENCE'
        },
        PMArtifact: {
            connection: 'dotted',
            destDecorator: 'con_none',
            type: 'ASSOCIATION'
        },
        PMIntermediateEvent: {
            connection: 'regular',
            type: 'SEQUENCE'
        },
        PMEndEvent: {
            connection: 'regular',
            type: 'SEQUENCE'
        },
        PMGateway: {
            connection: 'regular',
            type: 'SEQUENCE'
        }
    },
    PMStartEvent: {
        PMActivity: {
            connection: 'regular',
            type: 'SEQUENCE'
        },
        PMIntermediateEvent: {
            connection: 'regular',
            type: 'SEQUENCE'
        },
        PMGateway: {
            connection: 'regular',
            type: 'SEQUENCE'
        }
    },
    PMIntermediateEvent: {
        PMActivity: {
            connection: 'regular',
            type: 'SEQUENCE'
        },
        PMIntermediateEvent: {
            connection: 'regular',
            type: 'SEQUENCE'
        },
        PMEndEvent: {
            connection: 'regular',
            type: 'SEQUENCE'
        },
        PMGateway: {
            connection: 'regular',
            type: 'SEQUENCE'
        }
    },
    PMBoundaryEvent: {
        PMActivity: {
            connection: 'regular',
            type: 'SEQUENCE'
        },
        PMIntermediateEvent: {
            connection: 'regular',
            type: 'SEQUENCE'
        },
        PMEndEvent: {
            connection: 'regular',
            type: 'SEQUENCE'
        },
        PMGateway: {
            connection: 'regular',
            type: 'SEQUENCE'
        }
    },
    PMGateway: {
        PMActivity: {
            connection: 'regular',
            type: 'SEQUENCE'
        },
        PMIntermediateEvent: {
            connection: 'regular',
            type: 'SEQUENCE'
        }
    },
    PMArtifact: {
        PMActivity: {
            connection: 'dotted',
            destDecorator: 'con_none',
            type: 'ASSOCIATION'
        }
    }
};

/**
 * Defines a Map of the init Rules
 * @type {Object}
 */

PMConnectionDropBehavior.prototype.initRules = {
    PMCanvas: {
        PMCanvas: {
            name: 'PMCanvas to PMCanvas',
            rules: PMConnectionDropBehavior.prototype.basicRules
        }
    },
    PMActivity: {
        PMCanvas: {
            name: 'PMActivity to PMCanvas',
            rules: PMConnectionDropBehavior.prototype.basicRules
        }
    }
};

/**
 * Handle the hook functionality when a drop start
 *  @param shape
 */
PMConnectionDropBehavior.prototype.dropStartHook = function (shape, e, ui) {
    shape.srcDecorator = null;
    shape.destDecorator = null;
    var draggableId = ui.draggable.attr("id"),
        source = shape.canvas.customShapes.find('id', draggableId),
        prop;
    if (source) {
        prop = this.validate(source, shape);
        if (prop) {
            shape.setConnectionType({
                type: prop.type,
                segmentStyle: prop.connection,
                srcDecorator: prop.srcDecorator,
                destDecorator: prop.destDecorator
            });

        } else {
            // verif if port is changed
            if (typeof source !== 'undefined') {
                if (!(ui.helper && ui.helper.attr('id') === "drag-helper")) {
                    return false;
                }
                shape.setConnectionType('none');
            }
        }
    }

    return true;
};

/**
 * Connection validations method
 * return an object if is valid otherwise return false
 * @param {Connection} source
 * @param {Connection} target
 */
PMConnectionDropBehavior.prototype.validate = function (source, target) {
    var sType,
        tType,
        rules,
        initRules,
        initRulesName,
        BPMNAuxMap = {
            PMEvent: {
                'START': 'PMStartEvent',
                'END': 'PMEndEvent',
                'INTERMEDIATE': 'PMIntermediateEvent',
                'BOUNDARY': 'PMBoundaryEvent'
            },
            bpmnArtifact: {
                'TEXTANNOTATION': 'bpmnAnnotation'
            }
        };

    if (source && target) {
        if (source.getID() === target.getID()) {
            return false;
        }

        if (this.initRules[source.getParent().getType()]
            && this.initRules[source.getParent().getType()][target.getParent().getType()]) {
            initRules = this.initRules[source.getParent().getType()][target.getParent().getType()].rules;
            initRulesName = this.initRules[source.getParent().getType()][target.getParent().getType()].name;
            // get the types
            sType = source.getType();
            tType = target.getType();
            //Custimize all PM events
            if (sType === 'PMEvent') {
                if (BPMNAuxMap[sType] && BPMNAuxMap[sType][source.getEventType()]) {
                    sType = BPMNAuxMap[sType][source.getEventType()];
                }
            }
            if (tType === 'PMEvent') {
                if (BPMNAuxMap[tType] && BPMNAuxMap[tType][target.getEventType()]) {
                    tType = BPMNAuxMap[tType][target.getEventType()];
                }
            }

            if (initRules[sType] && initRules[sType][tType]) {
                rules = initRules[sType][tType];
            } else {
                rules = false;
            }
            if (initRules) {
                switch (initRulesName) {
                    case 'bpmnPool to bpmnPool':
                        if (source.getParent().getID() !== target.getParent().getID()) {
                            rules = false;
                        }
                        break;
                    case 'bpmnLane to bpmnLane':
                        if (source.getFirstPool(source.parent).getID()
                            !== target.getFirstPool(target.parent).getID()) {
                            if (this.extraRules[sType]
                                && this.extraRules[sType][tType]) {
                                rules = this.extraRules[sType][tType];
                            } else {
                                rules = false;
                            }
                        }
                        break;
                    case 'bpmnActivity to bpmnLane':
                        if (this.basicRules[sType]
                            && this.basicRules[sType][tType]) {
                            rules = this.basicRules[sType][tType];
                        } else {
                            rules = false;
                        }
                        break;
                    default:
                        break;
                }
            } else {
                rules = false;
            }

        } else {
            // get the types
            sType = source.getType();
            tType = target.getType();
            //
            if (sType === 'PMEvent') {
                if (BPMNAuxMap[sType] && BPMNAuxMap[sType][source.getEventType()]) {
                    sType = BPMNAuxMap[sType][source.getEventType()];
                }
            }
            if (tType === 'PMEvent') {
                if (BPMNAuxMap[tType] && BPMNAuxMap[tType][target.getEventType()]) {
                    tType = BPMNAuxMap[tType][target.getEventType()];
                }
            }
            if (this.advancedRules[sType] && this.advancedRules[sType][tType]) {
                rules = this.advancedRules[sType][tType];
            } else {
                rules = false;
            }
        }
        return rules;
    }
};
PMConnectionDropBehavior.prototype.onDragEnter = function (customShape) {
    return function (e, ui) {
        var shapeRelative, i;
        if (customShape.extendedType !== "PARTICIPANT") {
            if (ui.helper && ui.helper.hasClass("dragConnectHandler")) {
                shapeRelative = customShape.canvas.dragConnectHandlers.get(0).relativeShape;
                if (shapeRelative.id !== customShape.id) {
                    for (i = 0; i < 4; i += 1) {
                        customShape.showConnectDropHelper(i, customShape);
                    }
                }
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
/**
 * Handle the functionality when a shape is dropped
 * @param shape
 */
PMConnectionDropBehavior.prototype.onDrop = function (shape) {
    var that = this;
    return function (e, ui) {
        return false;
        var canvas = shape.getCanvas(),
            id = ui.draggable.attr('id'),
            x,
            y,
            currLeft,
            currTop,
            startPoint,
            sourceShape,
            sourcePort,
            endPort,
            endPortXCoord,
            endPortYCoord,
            connection,
            currentConnection = canvas.currentConnection,
            srcPort,
            dstPort,
            port,
            prop,
            command,
            aux;
        shape.entered = false;
        if (!shape.drop.dropStartHook(shape, e, ui)) {
            return false;
        }
        if (shape.getConnectionType() === "none") {
            return true;
        }

        if (currentConnection) {
            srcPort = currentConnection.srcPort;
            dstPort = currentConnection.destPort;
            if (srcPort.id === id) {
                port = srcPort;
            } else if (dstPort.id === id) {
                port = dstPort;
            } else {
                port = null;
            }
        }
        if (ui.helper && ui.helper.attr('id') === "drag-helper") {
            //if its the helper then we need to create two ports and draw a
            // connection
            //we get the points and the corresponding shapes involved
            startPoint = shape.canvas.connectionSegment.startPoint;
            sourceShape = shape.canvas.connectionSegment.pointsTo;
            //determine the points where the helper was created
            if (sourceShape.parent && sourceShape.parent.id === shape.id) {
                return true;
            }
            sourceShape.setPosition(sourceShape.oldX, sourceShape.oldY);
            startPoint.x = startPoint.portX;
            startPoint.y = startPoint.portY;
            //create the ports
            sourcePort = new PMUI.draw.Port({
                width: 10,
                height: 10
            });
            endPort = new PMUI.draw.Port({
                width: 10,
                height: 10
            });

            //determine the position where the helper was dropped
            endPortXCoord = ui.offset.left - shape.canvas.getX() -
                shape.getAbsoluteX() + shape.canvas.getLeftScroll();
            endPortYCoord = ui.offset.top - shape.canvas.getY() -
                shape.getAbsoluteY() + shape.canvas.getTopScroll();
            // add ports to the corresponding shapes
            // addPort() determines the position of the ports
            sourceShape.addPort(sourcePort, startPoint.x, startPoint.y);
            shape.addPort(endPort, endPortXCoord, endPortYCoord,
                false, sourcePort);

            //add ports to the canvas array for regularShapes
            //shape.canvas.regularShapes.insert(sourcePort).insert(endPort);
            //create the connection
            connection = new PMFlow({
                srcPort: sourcePort,
                destPort: endPort,
                segmentColor: new PMUI.util.Color(0, 0, 0),
                name: "",
                canvas: shape.canvas,
                segmentStyle: shape.connectionType.segmentStyle,
                flo_type: shape.connectionType.type
            });

            connection.setSrcDecorator(new PMUI.draw.ConnectionDecorator({
                width: 1,
                height: 1,
                canvas: canvas,
                decoratorPrefix: (typeof shape.connectionType.srcDecorator !== 'undefined'
                && shape.connectionType.srcDecorator !== null) ?
                    shape.connectionType.srcDecorator : "mafe-decorator",
                decoratorType: "source",
                parent: connection
            }));

            connection.setDestDecorator(new PMUI.draw.ConnectionDecorator({
                width: 1,
                height: 1,
                canvas: canvas,
                decoratorPrefix: (typeof shape.connectionType.destDecorator !== 'undefined'
                && shape.connectionType.destDecorator !== null) ?
                    shape.connectionType.destDecorator : "mafe-decorator",
                decoratorType: "target",
                style: {
                    cssClasses: [
                        "mafe-connection-decoration-target"
                    ]
                },
                parent: connection
            }));
            connection.canvas.commandStack.add(new PMUI.command.CommandConnect(connection));

            //connect the two ports
            connection.connect();
            connection.setSegmentMoveHandlers();

            //add the connection to the canvas, that means insert its html to
            // the DOM and adding it to the connections array
            canvas.addConnection(connection);

            // Filling PMFlow fields
            connection.setTargetShape(endPort.parent);
            connection.setOriginShape(sourcePort.parent);
            connection.savePoints();

            // now that the connection was drawn try to create the intersections
            connection.checkAndCreateIntersectionsWithAll();

            //attaching port listeners
            sourcePort.attachListeners(sourcePort);
            endPort.attachListeners(endPort);

            // finally trigger createEvent
            canvas.triggerCreateEvent(connection, []);
        } else if (port) {
            connection = port.getConnection();
            if (connection.srcPort.getID() === port.getID()) {
                prop = PMConnectionDropBehavior.prototype.validate(
                    shape,
                    connection.destPort.getParent()
                );
            } else {
                prop = PMConnectionDropBehavior.prototype.validate(
                    connection.srcPort.getParent(),
                    shape
                );
            }

            if (prop) {
                port.setOldParent(port.getParent());
                port.setOldX(port.getX());
                port.setOldY(port.getY());

                x = ui.position.left;
                y = ui.position.top;
                port.setPosition(x, y);
                shape.dragging = false;
                if (shape.getID() !== port.parent.getID()) {
                    port.parent.removePort(port);
                    currLeft = ui.offset.left - canvas.getX() -
                        shape.absoluteX + shape.canvas.getLeftScroll();
                    currTop = ui.offset.top - canvas.getY() - shape.absoluteY +
                        shape.canvas.getTopScroll();
                    shape.addPort(port, currLeft, currTop, true);
                    canvas.regularShapes.insert(port);
                } else {
                    shape.definePortPosition(port, port.getPoint(true));
                }

                // LOGIC: when portChangeEvent is triggered it gathers the state
                // of the connection but since at this point there's only a segment
                // let's paint the connection, gather the state and then disconnect
                // it (the connection is later repainted on, I don't know how)
                aux = {
                    before: {
                        condition: connection.flo_condition,
                        type: connection.flo_type,
                        segmentStyle: connection.segmentStyle,
                        srcDecorator: connection.srcDecorator.getDecoratorPrefix(),
                        destDecorator: connection.destDecorator.getDecoratorPrefix()
                    },
                    after: {
                        type: prop.type,
                        segmentStyle: prop.connection,
                        srcDecorator: prop.srcDecorator,
                        destDecorator: prop.destDecorator
                    }
                };
                connection.connect();
                canvas.triggerPortChangeEvent(port);
                command = new PMCommandReconnect(port, aux);
                canvas.commandStack.add(command);
                canvas.hideDropConnectHandlers();

            } else {
                return false;
            }
        }
        return false;
    };
};