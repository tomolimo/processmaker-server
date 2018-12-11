PMDesigner.complexRoutingRule = function (shape) {
    var formRoutingRule,
        formPanelSelected,
        arrayShapeIdRemoved = [],
        buttonAdd,
        windowConnections,
        warningMessageWindowDelete,
        containerLabels,
        buttonSave,
        buttonCancel,
        labelNextTask,
        labelCondition,
        warningMessageWindowDirty,
        deleteButton;

    //Window
    //button add routing rule
    buttonAdd = new PMUI.ui.Button({
        id: 'routingRuleButtonAdd',
        text: 'Add Routing Rule'.translate(),
        style: {
            cssProperties: {
                marginLeft: '50px',
                marginTop: '10px',
                marginBottom: '10px',
                fontSize: '16px'
            }
        },
        buttonType: 'success',
        height: 31,
        handler: function () {
            var item, btnDel;
            if (countActivities() > 0) {
                item = addRow();
                item.getItems()[2].style.addProperties({display: 'none'});
                item.getItems()[2].controls[0].button.setButtonType('error');
                formRoutingRule.addItem(item);
                for (var i = 0; i < formRoutingRule.getItems().length; i += 1) {
                    //formRoutingRule.getItems()[i].style.addProperties({'box-sizing': 'initial'});
                    //item.style.addProperties({padding : 'initial'});
                    formRoutingRule.getItems()[i].style.addProperties({'padding': 'initial'});
                }
                item.getItems()[0].dom.labelTextContainer.style.display = "none";
                item.getItems()[1].dom.labelTextContainer.style.display = "none";

            } else {
                PMDesigner.msgFlash('There are no items.'.translate(), windowConnections.footer);
            }
        }
    });
    //button Save
    buttonSave = new PMUI.ui.Button({
        id: 'windowConnectionsButtonSave',
        text: 'Apply'.translate(),
        handler: function () {
            removeConnectionsIntoCanvas();
            saveConnections();
        },
        buttonType: 'success',
        height: 31
    });
    //Button cancel
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

    windowConnections = new PMUI.ui.Window({
        id: 'windowConnections',
        title: 'Routing Rule'.translate(),
        height: DEFAULT_WINDOW_HEIGHT,
        width: DEFAULT_WINDOW_WIDTH,
        footerAlign: 'right',
        buttonPanelPosition: 'top',
        items: [
            buttonAdd
        ],
        buttons: [
            buttonCancel,
            buttonSave
        ]
    });
    windowConnections.showFooter();
    //END WINDOW

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

    labelNextTask = new PMUI.ui.TextLabel({
        text: 'Next Task'.translate(),
        style: {
            cssProperties: {
                'font-weight': 'bold'
            }
        }
    });

    labelCondition = new PMUI.ui.TextLabel({
        text: 'Description'.translate(),
        style: {
            cssProperties: {
                'font-weight': 'bold'
            }
        }
    });

    containerLabels.addItem(labelNextTask);
    containerLabels.addItem(labelCondition);

    windowConnections.addItem(containerLabels);

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

    warningMessageWindowDelete = new PMUI.ui.MessageWindow({
        id: 'warningMessageWindowDelete',
        windowMessageType: 'warning',
        width: 490,
        title: "Routing Rule".translate(),
        bodyHeight: 'auto',
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
            }, {
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
        title: "Routing Rule".translate(),
        message: 'Are you sure you want to discard your changes?'.translate(),
        footerItems: [{
            id: 'warningMessageWindowDirtyButtonNo',
            text: 'No'.translate(),
            visible: true,
            handler: function () {
                warningMessageWindowDirty.close();
            },
            buttonType: "error"
        },
            {
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

    //main
    formRoutingRule.hideFooter();
    windowConnections.addItem(formRoutingRule);
    windowConnections.open();
    labelNextTask.setWidth(382);
    labelNextTask.style.addProperties({padding: '5px 0px 0px 50px'});
    labelCondition.setWidth(410);
    labelCondition.style.addProperties({padding: '5px 0px 0px 0px'});
    containerLabels.style.addProperties({'border-bottom': '1px solid #e7e7e7'});
    windowConnections.setTitle('Routing Rule'.translate() + ' - ' + ((shape.gat_type === 'COMPLEX') ? 'Exclusive (Manual)'.translate() : shape.gat_type));
    loadConnections();

    function countActivities() {
        //Important! Any changes to synchronize the assessment of the condition 
        //of the functions: countActivities and loadActivities
        var n = 0, i, dt;
        dt = PMDesigner.project.getDirtyObject().diagrams[0].activities;
        for (i = 0; i < dt.length; i += 1) {
            n = n + 1;
        }

        dt = PMDesigner.project.getDirtyObject().diagrams[0].events;
        for (i = 0; i < dt.length; i += 1) {
            if (dt[i].evn_type !== 'START') {
                n = n + 1;
            }
        }
        return n;
    }

    function addRow() {

        var dropDownControl, description, deleteButton, newRow;

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
            readOnly: true,
            controlsWidth: 360,
            proportion: 1.1,
            style: {
                cssProperties: {
                    'vertical-align': 'top'
                }
            }
        });

        description = new PMUI.field.TextField({
            id: 'textCondition',
            pmType: 'text',
            name: 'flo_description',
            valueType: 'string',
            label: 'Description'.translate(),
            labelPosition: 'top',
            labelVisible: false,
            controlsWidth: 320,
            required: true,
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
                var i;
                for (i = 0; i < formRoutingRule.getItems().length; i += 1) {
                    if (formRoutingRule.getItems()[i].getItems()[2].controls[0].button.id == this.id) {
                        formPanelSelected = formRoutingRule.getItems()[i];
                        warningMessageWindowDelete.open();
                        warningMessageWindowDelete.dom.titleContainer.style.height = "17px";
                        warningMessageWindowDelete.showFooter();
                    }
                }

            },
            name: 'delete',
            labelVisible: false,
            buttonAling: 'left',
            controlsWidth: 100,
            proportion: 0.3,
            style: {
                cssProperties: {
                    'vertical-align': 'top'
                }
            }
        });

        newRow.addItem(dropDownControl);
        newRow.addItem(description);
        newRow.addItem(deleteButton);
        loadActivities(dropDownControl);
        return newRow;
    }

    function loadActivities(dropdown) {
        var i, dt;
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
        dt = PMDesigner.project.getDirtyObject().diagrams[0].activities, nameGroup;
        dt = dt.sort(function (a, b) {
            return a.act_name.toString().toLowerCase() > b.act_name.toString().toLowerCase();
        });
        for (i = 0; i < dt.length; i += 1) {
            nameGroup = dt[i].act_type === 'TASK' ? 'Task'.translate() : 'Sub-process'.translate();
            dropdown.addOption({
                value: dt[i].act_uid,
                label: dt[i].act_name
            }, nameGroup);
        }

        dropdown.addOptionGroup({
            label: 'End of process'.translate(),
            options: []
        });
        dt = PMDesigner.project.getDirtyObject().diagrams[0].events;
        dt = dt.sort(function (a, b) {
            return a.evn_name.toString().toLowerCase() > b.evn_name.toString().toLowerCase();
        });
        for (i = 0; i < dt.length; i += 1) {
            if (dt[i].evn_type !== 'START') {
                dropdown.addOption({
                    value: dt[i].evn_uid,
                    label: dt[i].evn_name
                }, 'End of process'.translate());
            }
        }
    }

    function removeConnectionsIntoCanvas() {
        var shapeDest, connection, dt, i, j;
        for (j = 0; j < arrayShapeIdRemoved.length; j += 1) {
            shapeDest = getShapeForId(arrayShapeIdRemoved[j]);
            dt = shape.getPorts().asArray();
            for (i = 0; i < dt.length; i += 1) {
                connection = dt[i].getConnection();
                if (shape.getID() === connection.getSrcPort().getParent().getID() &&
                    shapeDest.getID() === connection.getDestPort().getParent().getID()) {
                    PMDesigner.canvas.emptyCurrentSelection();
                    PMDesigner.canvas.setCurrentConnection(connection);
                    PMDesigner.canvas.removeElements();
                    connection.saveAndDestroy();
                    PMDesigner.canvas.removeConnection(connection);
                    break;
                }
            }
        }
        arrayShapeIdRemoved = [];
    }

    function editShapeDestConnection(shapeDest, oldShape) {
        var connection, canvas = PMDesigner.canvas;
        connection = isConnection(shape, oldShape);
        oldShape.removePort(connection.destPort);
        shapeDest.addPort(connection.destPort, 100, 100,
            false, connection.srcPort);

        connection.canvas.commandStack.add(new PMUI.command.CommandConnect(connection));
        connection.connect();
        canvas.triggerPortChangeEvent(connection.destPort);
    }

    function createEndShape() {
        var customShape, canvas = PMDesigner.canvas, command, x, y;
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

    function saveConnections() {
        var dt, i;
        if (!formRoutingRule.isValid()) {
            return;
        }
        dt = formRoutingRule.getItems(), id, oldId, shapeDest, oldShapeDest, connection;
        for (i = 0; i < dt.length; i += 1) {
            id = dt[i].getField('act_name').getValue();
            oldId = dt[i].getField('act_name').getUID();
            if (id !== '0') {
                if (oldId === undefined) {
                    shapeDest = getShapeForId(id);
                    createConnection(shape, shapeDest).setFlowCondition(dt[i].getField('flo_description').getValue());
                }
                if (oldId !== undefined && id === oldId) {
                    shapeDest = getShapeForId(id);
                    connection = isConnection(shape, shapeDest);
                    $a = connection;
                    connection.setFlowCondition(dt[i].getField('flo_description').getValue());
                }
                if (oldId !== undefined && id !== oldId) {
                    shapeDest = getShapeForId(id);
                    oldShapeDest = getShapeForId(oldId);
                    connection = isConnection(shape, oldShapeDest);
                    connection.setFlowCondition(dt[i].getField('flo_description').getValue());
                    editShapeDestConnection(shapeDest, oldShapeDest);
                }
            } else {
                var customShape = createEndShape();
                createConnection(shape, customShape);
            }
        }
        windowConnections.close();
        PMDesigner.msgFlash('Saved correctly'.translate(), document.body);
        PMDesigner.project.dirty = true;
    }

    function getShapeForId(id) {
        var dt = PMDesigner.canvas.getChildren().asArray(), i;
        for (i = 0; i < dt.length; i += 1) {
            if (dt[i].act_uid === id || dt[i].evn_uid === id) {
                return dt[i];
            }
        }
        return null;
    }

    function createConnection(sourceShape, shape) {
        //referer to /processmaker/vendor/colosa/MichelangeloFE/src/connectiondrop.js
        //method PMConnectionDropBehavior.prototype.onDrop
        var sourcePort, endPort, connection, canvas = PMDesigner.canvas;
        sourcePort = new PMUI.draw.Port({
            width: 10,
            height: 10
        });
        endPort = new PMUI.draw.Port({
            width: 10,
            height: 10
        });

        sourceShape.addPort(sourcePort, 100, 100);
        shape.addPort(endPort, 100, 100,
            false, sourcePort);

        //add ports to the canvas array for regularShapes
        //shape.canvas.regularShapes.insert(sourcePort).insert(endPort);
        //create the connection
        connection = new PMFlow({
            srcPort: sourcePort,
            destPort: endPort,
            segmentColor: new PMUI.util.Color(92, 156, 204),
            name: "",
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
                shape.connectionType.srcDecorator : "mafe-sequence",
            decoratorType: "source",
            parent: connection
        }));
        connection.setDestDecorator(new PMUI.draw.ConnectionDecorator({
            width: 11,
            height: 11,
            canvas: canvas,
            decoratorPrefix: (typeof shape.connectionType.destDecorator !== 'undefined'
            && shape.connectionType.destDecorator !== null) ?
                shape.connectionType.destDecorator : "mafe-sequence",
            decoratorType: "target",
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
        return connection;
    }

    function loadConnections() {
        var row, connection, dt = shape.getPorts().asArray(), i, j;
        for (i = 0; i < dt.length; i += 1) {
            connection = dt[i].getConnection();
            if (shape.getID() !== connection.getDestPort().getParent().getID()) {
                row = addRow();
                row.getField('act_name').setValue(connection.getDestPort().getParent().getID());
                row.getField('act_name').setUID(connection.getDestPort().getParent().getID());
                row.getField('flo_description').setValue(connection.getFlowCondition());

                row.getItems()[2].style.addProperties({display: 'none'});
                row.getItems()[2].controls[0].button.setButtonType('error');
                formRoutingRule.addItem(row);
                for (j = 0; j < formRoutingRule.getItems().length; j += 1) {
                    formRoutingRule.getItems()[j].style.addProperties({'padding': 'initial'});
                }
                row.getItems()[0].dom.labelTextContainer.style.display = "none";
                row.getItems()[1].dom.labelTextContainer.style.display = "none";
            }
        }
    }

    function isConnection(sourceShape, shape) {
        var connection, dt, i;
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

};