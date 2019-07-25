PMDesigner.gatewayProperties = function (gateway) {
    if (gateway.getGatewayType() !== "PARALLEL") {
        PMDesigner.RoutingRule(gateway);
    }
};

PMDesigner.RoutingRule = function (shape) {
    var formRoutingRule,
        formPanelSelected,
        arrayShapeIdRemoved = [],
        buttonAdd,
        windowConnections,
        warningMessageWindowDelete,
        warningMessageWindowDirty,
        containerLabels,
        deleteButton,
        buttonSave,
        buttonCancel,
        labelNextTask,
        labelCondition,
        typeShapeValueText,
        dataRouteGroup = [],
        arrayElementName = [],
        availableShapes  = [];

    warningMessageWindowDelete = new PMUI.ui.MessageWindow({
        id: 'warningMessageWindowDelete',
        windowMessageType: 'warning',
        width: 490,
        bodyHeight: 'auto',
        title: 'Routing Rule'.translate(),
        message: 'Do you want to delete this routing rule?'.translate(),
        footerItems: [
            {
                id: 'warningMessageWindowDeleteButtonNo',
                text: 'No'.translate(),
                visible: true,
                handler: function () {
                    warningMessageWindowDelete.close();
                },
                buttonType: "error"
            },
            {
                id: 'warningMessageWindowDeleteButtonYes',
                text: 'Yes'.translate(),
                visible: true,
                handler: function () {
                    deleteRow();
                    warningMessageWindowDelete.close();
                },
                buttonType: "success"
            }
        ]
    });
    warningMessageWindowDirty = new PMUI.ui.MessageWindow({
        id: 'warningMessageWindowDirty',
        windowMessageType: 'warning',
        width: 490,
        bodyHeight: 'auto',
        title: 'Routing Rule'.translate(),
        message: 'Are you sure you want to discard your changes?'.translate(),
        footerItems: [
            {
                id: 'warningMessageWindowDirtyButtonNo',
                text: 'No'.translate(),
                visible: true,
                handler: function () {
                    warningMessageWindowDirty.close();
                },
                buttonType: "error"
            }, {
                id: 'warningMessageWindowDirtyButtonYes',
                text: 'Yes'.translate(),
                visible: true,
                handler: function () {
                    warningMessageWindowDirty.close();
                    windowConnections.close();
                },
                buttonType: "success"
            }
        ]
    });

    buttonAdd = new PMUI.ui.Button({
        id: 'routingRuleButtonAdd',
        text: 'Add Routing Rule'.translate(),
        style: {
            cssProperties: {
                marginLeft: '50px',
                marginTop: '10px',
                marginBottom: '10px',
                padding: "5px"
            }
        },
        buttonType: 'success',
        handler: function () {
            // to add a new row
            addRow();
            enableSorting();
        }
    });
    buttonSave = new PMUI.ui.Button({
        id: 'windowConnectionsButtonSave',
        text: 'Save'.translate(),
        handler: function () {
            //validate routing rules form
            if (isValidRoutingRules()) {
                //remove all flows an get points
                var allPoints = removeConnectionsIntoCanvas();
                saveConnections(allPoints);
            }

        },
        buttonType: 'success'
    });
    buttonCancel = new PMUI.ui.Button({
        id: 'windowConnectionsButtonCancel',
        text: 'Cancel'.translate(),
        buttonType: 'error',
        handler: function () {
            if (formRoutingRule.isDirty()) {
                warningMessageWindowDirty.open();
                warningMessageWindowDirty.showFooter();
            } else {
                windowConnections.close();
            }
        }
    });

    labelNextTask = new PMUI.ui.TextLabel({
        text: 'Next Task'.translate()
    });
    labelNextTask.setWidth(382);
    labelNextTask.style.addProperties({padding: '5px 0px 0px 50px'});

    labelCondition = new PMUI.ui.TextLabel({
        text: 'Condition'.translate()
    });
    labelCondition.setWidth(410);
    labelCondition.style.addProperties({padding: '5px 0px 0px 5px'});
    labelCondition.style.addProperties({marginLeft: '31%'});

    containerLabels = new PMUI.core.Panel({
        layout: 'hbox',
        width: 'auto',
        height: 29,
        style: {
            cssProperties: {
                'border-bottom': '1px solid #c0c0c0'
            }
        }
    });
    containerLabels.addItem(labelNextTask);
    containerLabels.addItem(labelCondition);
    containerLabels.style.addProperties({'border-bottom': '1px solid #e7e7e7'});

    formRoutingRule = new PMUI.form.Form({
        id: 'formRoutingRule',
        visibleHeader: false,
        width: DEFAULT_WINDOW_WIDTH - 60,
        height: 'auto',
        items: [],
        style: {
            cssProperties: {
                'margin-left': '35px'
            }
        }
    });
    formRoutingRule.hideFooter();

    windowConnections = new PMUI.ui.Window({
        id: 'windowConnections',
        title: 'Routing Rule'.translate(),
        height: DEFAULT_WINDOW_HEIGHT,
        width: DEFAULT_WINDOW_WIDTH,
        footerAlign: 'right',
        buttonPanelPosition: 'bottom',
        items: [
            buttonAdd
        ],
        buttons: [
            buttonCancel,
            buttonSave
        ]
    });

    typeShapeValueText = (shape.gat_type === 'EXCLUSIVE') ? 'EXCLUSIVE'.translate() : 'INCLUSIVE'.translate();
    windowConnections.setTitle('Routing Rule'.translate() + ' - ' + typeShapeValueText);
    windowConnections.showFooter();
    windowConnections.addItem(containerLabels);
    if (formRoutingRule.dirty === null) {
        formRoutingRule.dirty = false;
    }
    windowConnections.addItem(formRoutingRule);
    windowConnections.open();

    loadConnections();
    enableSorting();
    /**
     * add a new row
     * @returns {PMUI.form.FormPanel|*}
     */
    function addRow() {
        var dropDownControl,
            criteriaField,
            deleteButton,
            newRow,
            max,
            i;
        newRow = new PMUI.form.FormPanel({
            layout: 'hbox'
        });

        dropDownControl = new PMUI.field.DropDownListField({
            id: 'dropdownNextTask',
            name: 'act_name',
            valueType: 'string',
            label: 'Next Task'.translate(),
            labelPosition: 'top',
            labelVisible: false,
            value: '',
            readOnly: false,
            controlsWidth: 360,
            proportion: 0.9,
            style: {
                cssProperties: {
                    'vertical-align': 'top'
                }
            }
        });

        criteriaField = new CriteriaField({
            id: 'textCondition',
            pmType: 'text',
            renderType: 'textarea',
            name: 'flo_condition',
            valueType: 'string',
            label: 'Condition'.translate(),
            labelPosition: 'top',
            labelVisible: false,
            controlsWidth: 345,
            required: false,
            style: {
                cssProperties: {
                    'vertical-align': 'top'
                }
            }
        });
        deleteButton = new PMUI.field.ButtonField({
            id: 'buttonDelete',
            value: 'Delete'.translate(),
            handler: function (e, a) {
                formPanelSelected = this.getParent();
                warningMessageWindowDelete.open();
                warningMessageWindowDelete.dom.titleContainer.style.height = '17px';
                warningMessageWindowDelete.showFooter();
                enableSorting();
            },
            name: 'delete',
            labelVisible: false,
            buttonAling: 'left',
            controlsWidth: 60,
            proportion: 0.1,
            style: {
                cssProperties: {
                    'vertical-align': 'top'
                }
            }
        });

        newRow.addItem(dropDownControl);
        newRow.addItem(criteriaField);
        newRow.addItem(deleteButton);
        loadOptions(dropDownControl);
        //apply styles;
        if (availableShapes && availableShapes.length > 0) {
            deleteButton.controls[0].button.setButtonType('error');
            $(deleteButton.getHTML()).find("a").css({
                padding: "5px"
            });
            formRoutingRule.addItem(newRow);
            for (i = 0, max = formRoutingRule.getItems().length; i < max; i += 1) {
                formRoutingRule.getItems()[i].style.addProperties({'padding': 'initial'});
            }
            dropDownControl.dom.labelTextContainer.style.display = 'none';
            criteriaField.dom.labelTextContainer.style.display = 'none';
            criteriaField.setValue(true);
        } else {
            PMDesigner.msgFlash('There are no items.'.translate(), windowConnections.footer, 'error');
        }
        return newRow;
    }

    function loadOptions(dropdown) {
        var i,
            customShapes,
            element,
            nameGroup,
            evnLabelMap;
        //Important! Any changes to synchronize the assessment of the condition
        //of the functions: countActivities and loadActivities
        dropdown.clearOptions();
        dropdown.setUID = function (uid) {
            this.uid = uid;
        };
        dropdown.getUID = function () {
            return this.uid;
        };
        dropdown.addOptionGroup({
            label: 'Task'.translate(),
            selected: true,
            options: []
        });
        dropdown.addOptionGroup({
            label: 'Sub-process'.translate(),
            options: []
        });
        dropdown.addOptionGroup({
            label: 'Event'.translate(),
            options: []
        });
        dropdown.addOptionGroup({
            label: 'Gateway'.translate(),
            options: []
        });
        customShapes = PMUI.getActiveCanvas().getCustomShapes();

        for (i = 0; i < customShapes.getSize(); i += 1) {
            element = customShapes.get(i);
            // verify pool and participant
            if (element.getType() !== 'PMParticipant' && element.getType() !== 'PMPool') {
                //itself verify and same parent
                if (shape.getID() !== element.getID()
                    && element.businessObject
                    && shape.businessObject
                    && shape.businessObject.elem.$parent
                    && element.businessObject.elem.$parent
                    && element.businessObject.elem.$parent.id === shape.businessObject.elem.$parent.id) {
                    switch (element.type) {
                        case 'PMActivity':
                            nameGroup = element.act_type === 'TASK' ? 'Task'.translate() : 'Sub-process'.translate();
                            dropdown.addOption({
                                value: element.act_uid,
                                label: element.act_name
                            }, nameGroup);
                            arrayElementName[element.act_uid] = element.act_name;
                            availableShapes.push(element);
                            break;
                        case 'PMEvent':
                            evnLabelMap = {
                                'END': 'End Event'.translate(),
                                'INTERMEDIATE': 'Intermediate Event'.translate()
                            };

                            if (element.evn_type !== 'START') {
                                dropdown.addOption({
                                    value: element.evn_uid,
                                    label: element.evn_name || evnLabelMap[element.evn_type]
                                }, 'Event'.translate());

                                arrayElementName[element.evn_uid] = element.evn_name || evnLabelMap[element.evn_type];
                                availableShapes.push(element);
                            }
                            break;
                        case 'PMGateway':
                            dropdown.addOption({
                                value: element.gat_uid,
                                label: element.gat_name || 'Gateway'.translate()
                            }, 'Gateway');
                            arrayElementName[element.gat_uid] = element.gat_name || 'Gateway'.translate();
                            availableShapes.push(element);
                            break;
                    }
                }
            }
        }
    }

    function removeConnectionsIntoCanvas() {
        var shapeDest, connection, dt, allPoints = {}, i, j;
        for (j = 0; j < arrayShapeIdRemoved.length; j += 1) {
            shapeDest = PMUI.getActiveCanvas().getCustomShapes().find('id', arrayShapeIdRemoved[j]);
            dt = shape.getPorts().asArray();
            for (i = 0; i < dt.length; i += 1) {
                connection = dt[i].getConnection();
                if (shape.getID() === connection.getSrcPort().getParent().getID() &&
                    shapeDest.getID() === connection.getDestPort().getParent().getID()) {
                    //caching points
                    allPoints[shapeDest.getID()] = getPoints(connection);
                    removeConnection(connection);

                    break;
                }
            }
        }
        arrayShapeIdRemoved = [];
        return allPoints;
    }

    /**
     * to remove a individual connection and getting points
     * @param connection
     */
    function removeConnection(connection) {

        PMUI.getActiveCanvas().emptyCurrentSelection();
        PMUI.getActiveCanvas().setCurrentConnection(connection);
        PMUI.getActiveCanvas().removeElements();
        connection.saveAndDestroy();
        PMUI.getActiveCanvas().removeConnection(connection);
    }

    /**
     * gets conenctions inital and final points
     * @param connection
     */
    function getPoints(connection) {
        var result = [];
        result[0] = connection.points[0];
        result[1] = connection.points[connection.points.length - 1];
        return result;
    }


    function editAllConnections(connArray) {
        var i,
            elem,
            canvas = PMUI.getActiveCanvas();
        for(i = 0; i < connArray.length; i += 1) {
            elem = connArray[i];
            if (elem && elem.connection) {
                elem.oldShapeDest.removePort(elem.connection.destPort);
                elem.shapeDest.addPort(elem.connection.destPort, 100, 100,
                    false, elem.connection.srcPort);

                elem.connection.lineSegments.clear();
                canvas.commandStack.add(new PMUI.command.CommandConnect(elem.connection));
                elem.connection.connect();
                canvas.triggerPortChangeEvent(elem.connection.destPort);
            }
        }
    }


    function createEndShape() {
        var customShape, canvas = PMUI.getActiveCanvas(), command, x, y;
        customShape = canvas.shapeFactory('END');

        x = shape.getX() + shape.getWidth();
        y = shape.getY() + shape.getHeight() + 20;

        canvas.addElement(customShape, x, y,
            customShape.topLeftOnCreation);

        //since it is a new element in the designer, we triggered the
        //custom on create element event
        canvas.updatedElement = customShape;

        // create the command for this new shape
        command = new PMUI.command.CommandCreate(customShape);
        canvas.commandStack.add(command);
        command.execute();
        return customShape;
    }

    /**
     * new method to validate routing rules
     * @returns {boolean}
     */
    function isValidRoutingRules() {
        var result = true,
            arrayAux = [],
            i,
            max,
            id,
            dt = formRoutingRule.getItems();
        if (!formRoutingRule.isValid()) {
            result = false;
            return;
        }

        for (i = 0, max = dt.length; i < max; i += 1) {
            id = dt[i].getField("act_name").getValue();
            if (typeof(arrayAux[id]) === "undefined") {
                arrayAux[id] = "1";
            } else {
                result = false;
                PMDesigner.msgWinError("The routing rule to \"{0}\" already exists".translate([arrayElementName[id]]));
                return;
            }
        }
        return result;
    }

    function saveConnections(allPoints) {
        var dt = formRoutingRule.getItems(),
            id,
            i,
            oldId,
            shapeDest,
            oldShapeDest,
            connection,
            newConnection,
            dataRouteAll,
            dataRoute,
            restClient,
            newPoints,
            conectionsArray = [];


        for (i = 0; i < dt.length; i += 1) {
            id = dt[i].getField('act_name').getValue();
            oldId = dt[i].getField('act_name').getUID();
            if (id !== '0') {
                dataRouteGroup.push(id);
                if (dt[i].getField('flo_condition').getValue() == "") {
                    dt[i].getField('flo_condition').setValue(true);
                }
                if (oldId === undefined) {
                    shapeDest = PMUI.getActiveCanvas().getCustomShapes().find('id', id);
                    //getting current connection

                    newPoints = allPoints[id];

                    newConnection = createConnection(shape, shapeDest, newPoints);
                    newConnection.setFlowCondition(dt[i].getField('flo_condition').getValue());
                }
                if (oldId !== undefined && id === oldId) {
                    shapeDest = PMUI.getActiveCanvas().getCustomShapes().find('id', id);
                    connection = isConnection(shape, shapeDest);

                    if (typeof(connection) != "object") {
                        connection = createConnection(shape, shapeDest);
                    }

                    connection.setFlowCondition(dt[i].getField('flo_condition').getValue());
                }
                if (oldId !== undefined && id !== oldId) {
                    shapeDest = PMUI.getActiveCanvas().getCustomShapes().find('id', id);
                    oldShapeDest = PMUI.getActiveCanvas().getCustomShapes().find('id', oldId);
                    connection = isConnection(shape, oldShapeDest);

                    if (typeof(connection) != "object") {
                        connection = createConnection(shape, shapeDest);
                    }

                    connection.setFlowCondition(dt[i].getField('flo_condition').getValue());

                    if (typeof(connection) != "object") {
                        connection = createConnection(shape, shapeDest);
                    }

                    connection.setFlowCondition(dt[i].getField('flo_condition').getValue());

                    conectionsArray.push({
                        "connection" : isConnection(shape, oldShapeDest) || null,
                        "shapeDest": shapeDest,
                        "oldShapeDest": oldShapeDest
                    });
                }
            } else {
                var customShape = createEndShape();
                createConnection(shape, customShape);
            }
        }
        editAllConnections(conectionsArray);

        /*update routing order*/
        dataRouteAll = [];
        for (i = 0; i < dataRouteGroup.length; i += 1) {
            dataRoute = {
                'rou_case': parseInt(i) + 1,
                'rou_next_task': dataRouteGroup[i],
                'pro_uid': PMDesigner.project.id
            };
            dataRouteAll.push(dataRoute);
        }
        restClient = new PMRestClient({
            endpoint: 'update-route-order',
            typeRequest: 'update',
            data: dataRouteAll,
            functionSuccess: function () {
                PMDesigner.msgFlash('Saved correctly'.translate(), document.body, 'success', 3000, 5);
            },
            functionFailure: function (xhr, response) {
                PMDesigner.msgWinError(response.error.message);
                PMDesigner.msgFlash('There are problems updating the routing rule, please try again.'.translate(), document.body, 'error', 3000, 5);
            }
        });
        restClient.executeRestClient();
        /*end update routing order*/

        windowConnections.close();
        PMDesigner.msgFlash('Saved correctly'.translate(), document.body);
        PMDesigner.project.dirty = true;
        PMDesigner.project.setDirty(true);
    }

    function createConnection(sourceShape, shape, points) {
        var sourcePort, endPort, connection, canvas = PMUI.getActiveCanvas(), points;
        sourcePort = new PMUI.draw.Port({
            width: 10,
            height: 10
        });
        endPort = new PMUI.draw.Port({
            width: 10,
            height: 10
        });
        if (!points) {
            points = findBestPorts(sourceShape, shape);
        }
        sourceShape.addPort(sourcePort, points[0].x - sourceShape.getZoomX(), points[0].y - sourceShape.getZoomY());
        shape.addPort(endPort, points[1].x - shape.getZoomX(), points[1].y - shape.getZoomY(), false, sourcePort);

        //add ports to the canvas array for regularShapes
        //shape.canvas.regularShapes.insert(sourcePort).insert(endPort);
        //create the connection
        connection = new PMFlow({
            srcPort: sourcePort,
            destPort: endPort,
            segmentColor: new PMUI.util.Color(0, 0, 0),
            name: '',
            canvas: shape.canvas,
            segmentStyle: shape.connectionType.segmentStyle,
            flo_type: shape.connectionType.type
        });
        connection.setSrcDecorator(new PMUI.draw.ConnectionDecorator({
            width: 11,
            height: 11,
            canvas: canvas,
            decoratorPrefix: (typeof shape.connectionType.srcDecorator !== 'undefined'
            && shape.connectionType.srcDecorator !== null) ?
                shape.connectionType.srcDecorator : 'mafe-sequence',
            decoratorType: 'source',
            parent: connection
        }));
        connection.setDestDecorator(new PMUI.draw.ConnectionDecorator({
            width: 11,
            height: 11,
            canvas: canvas,
            decoratorPrefix: (typeof shape.connectionType.destDecorator !== 'undefined'
            && shape.connectionType.destDecorator !== null) ?
                shape.connectionType.destDecorator : 'mafe-sequence',
            decoratorType: 'target',
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
        connection.flo_state = connection.points;
        return connection;
    }

    function findBestPorts(sourceShape, shape) {
        var result = [], i, j,
            distance = 99999999,
            initPoint,
            secondPoint,
            midPoints = getMiddlePoints(sourceShape),
            midPoints2 = getMiddlePoints(shape);
        for (i = 0; i < midPoints.length; i += 1) {
            initPoint = midPoints[i];
            for (j = 0; j < midPoints2.length; j += 1) {
                secondPoint = midPoints2[j];
                if (distance > initPoint.getManhattanDistance(secondPoint)) {
                    distance = initPoint.getManhattanDistance(midPoints2[j]);
                    result[0] = (initPoint);
                    result[1] = (midPoints2[j]);
                }
            }
        }
        return result;
    }

    function getMiddlePoints(shape) {
        return [
            new PMUI.util.Point(Math.round(shape.zoomWidth / 2) + shape.getZoomX(), 0 + shape.getZoomY()), // TOP
            new PMUI.util.Point(shape.zoomWidth + shape.getZoomX(), Math.round(shape.zoomHeight / 2) + shape.getZoomY()), // RIGHT
            new PMUI.util.Point(Math.round(shape.zoomWidth / 2) + shape.getZoomX(), shape.zoomHeight + shape.getZoomY()), // BOTTOM
            new PMUI.util.Point(0 + shape.getZoomX(), Math.round(shape.zoomHeight / 2) + shape.getZoomY())               // LEFT
        ];
    }

    function loadConnections() {
        var row, connection, dt = shape.getPorts().asArray(),
            i, j;
        for (i = 0; i < dt.length; i += 1) {
            connection = dt[i].getConnection();
            if (shape.getID() !== connection.getDestPort().getParent().getID() && shape.gat_default_flow !== connection.flo_uid) {
                row = addRow();
                row.getField('act_name').setValue(connection.getDestPort().getParent().getID());
                row.getField('act_name').setUID(connection.getDestPort().getParent().getID());
                row.getField('flo_condition').setValue(connection.getFlowCondition());

                row.getItems()[2].style.addProperties({display: 'none'});
                row.getItems()[2].controls[0].button.setButtonType('error');
                $(row.getItems()[2].getHTML()).find("a").css({
                    padding: "5px"
                });
                formRoutingRule.addItem(row);
                for (j = 0; j < formRoutingRule.getItems().length; j += 1) {
                    formRoutingRule.getItems()[j].style.addProperties({'padding': 'initial'});
                }
                row.getItems()[0].dom.labelTextContainer.style.display = 'none';
                row.getItems()[1].dom.labelTextContainer.style.display = 'none';
            }
        }
    }

    function isConnection(sourceShape, shape) {
        var connection,
            i,
            dt = sourceShape.getPorts().asArray();
        for (i = 0; i < dt.length; i += 1) {
            connection = dt[i].getConnection();
            if (sourceShape.getID() === connection.getSrcPort().getParent().getID() &&
                shape.getID() === connection.getDestPort().getParent().getID()) {
                return connection;
            }
        }
        return false;
    }

    function deleteRow() {
        arrayShapeIdRemoved.push(formPanelSelected.getField('act_name').getValue());
        formRoutingRule.removeItem(formPanelSelected);
        PMDesigner.msgFlash('Routing rule removed correctly'.translate(), windowConnections.footer);
    }

    function enableSorting() {
        var index = 0,
            div = $(formRoutingRule.getHTML()).find(">div:nth-child(2)").css({"overflow": "initial"});
        div.sortable({
            items: '>div',
            placeholder: 'steps-placeholder',
            cursor: "move",
            change: function (event, ui) {
                index = ui.placeholder.index();
            },
            start: function (event, ui) {
            },
            stop: function (event, ui) {
                var dt = [],
                    row,
                    formPanelSelected,
                    id,
                    i,
                    j,
                    shapeDest,
                    connection;

                ui.item.parent().find(">div").each(function (i, e) {
                    dt.push(PMUI.getPMUIObject(e));
                });
                for (i = 0; i < dt.length; i += 1) {
                    formPanelSelected = dt[i];
                    arrayShapeIdRemoved.push(formPanelSelected.getField('act_name').getValue());
                    formRoutingRule.removeItem(formPanelSelected);
                }
                for (i = 0; i < dt.length; i += 1) {
                    row = addRow();
                    row.getItems()[2].style.addProperties({display: 'none'});
                    row.getItems()[2].controls[0].button.setButtonType('error');
                    $(row.getItems()[2].getHTML()).find("a").css({
                        padding: "5px"
                    });
                    formRoutingRule.addItem(row);
                    for (j = 0; j < formRoutingRule.getItems().length; j += 1) {
                        formRoutingRule.getItems()[j].style.addProperties({'padding': 'initial'});
                    }
                    row.getItems()[0].dom.labelTextContainer.style.display = 'none';
                    row.getItems()[1].dom.labelTextContainer.style.display = 'none';
                    row.getField('act_name').setValue(dt[i].getField('act_name').getValue());
                    row.getField('flo_condition').setValue(dt[i].getField('flo_condition').getValue());
                    id = dt[i].getField('act_name').getValue();
                    shapeDest = PMUI.getActiveCanvas().getCustomShapes().find('id', id);
                    connection = isConnection(shape, shapeDest);
                }
                enableSorting();
            }
        });
    }
};

PMDesigner.RoutingRuleDeleteAllFlow = function (shape) {
    var warningMessageWindowDelete = new PMUI.ui.MessageWindow({
        windowMessageType: 'warning',
        width: 490,
        bodyHeight: 'auto',
        title: 'Routing Rule'.translate(),
        id: 'warningMessageWindowDelete',
        message: 'Do you want to delete all routing rules?'.translate(),
        footerItems: [
            {
                id: 'warningMessageWindowDeleteButtonNo',
                text: 'No'.translate(),
                visible: true,
                handler: function () {
                    warningMessageWindowDelete.close();
                },
                buttonType: "error"
            }, {
                id: 'warningMessageWindowDeleteButtonYes',
                text: 'Yes'.translate(),
                visible: true,
                handler: function () {
                    deleteAllConnection();
                    warningMessageWindowDelete.close();
                },
                buttonType: "success"
            }
        ]
    });

    function deleteAllConnection() {
        var connection, elements = [],
            i,
            sw,
            msg,
            ports;
        PMUI.getActiveCanvas().emptyCurrentSelection();
        //IMPORTANT: You must empty elements in another array due to the array reference indices managed.
        //referer: PMDesigner.canvas.removeConnection & element.getPorts().asArray()

        ports = shape.getPorts().asArray();
        for (i = 0; i < ports.length; i += 1) {
            elements.push(ports[i]);
        }
        sw = false;
        for (i = 0; i < elements.length; i += 1) {
            connection = elements[i].getConnection();
            if (shape.getID() !== connection.getDestPort().getParent().getID()) {
                PMUI.getActiveCanvas().setCurrentConnection(connection);
                PMUI.getActiveCanvas().removeElements();
                connection.saveAndDestroy();
                PMUI.getActiveCanvas().removeConnection(connection);
                sw = true;
            }
        }
        msg = sw ? 'Routing rules deleted successfully' : 'There aren\'t routing rules';
        PMDesigner.msgFlash(msg.translate(), document.body);
    }

    warningMessageWindowDelete.open();
    warningMessageWindowDelete.showFooter();
    warningMessageWindowDelete.dom.titleContainer.style.height = '17px';
};

PMDesigner.RoutingRuleSetOrder = function (diagram) {
    var restClient = new PMRestClient({
        endpoint: 'update-route-order-from-project',
        typeRequest: 'update',
        data: {},
        functionSuccess: function () {
            PMDesigner.msgFlash('Saved correctly'.translate(), document.body, 'success', 3000, 5);
        }
    });
    restClient.executeRestClient();
};