var CommandChangeGatewayType = function (receiver, options) {
    PMUI.command.Command.call(this, receiver);
    this.before = null;
    this.after = null;
    CommandChangeGatewayType.prototype.initObject.call(this, options);
};

PMUI.inheritFrom('PMUI.command.Command', CommandChangeGatewayType);
/**
 * Type of the instances of this class
 * @property {String}
 */
CommandChangeGatewayType.prototype.type = "CommandChangeGatewayType";

/**
 * Initializes the command parameters
 * @param {PMUI.draw.Core} receiver The object that will perform the action
 */
CommandChangeGatewayType.prototype.initObject = function (options) {
    var parsedClass = options;
    this.layer = this.receiver.getLayers().get(0);
    this.before = {
        zoomSprite: this.layer.zoomSprites,
        type: this.receiver.extendedType,
        defaultFlow: this.receiver.canvas.items.find('id', this.receiver.gat_default_flow) ?
            this.receiver.canvas.items.find('id', this.receiver.gat_default_flow).relatedObject : null
    };
    this.after = {
        zoomSprite: [
            'mafe-gateway-' + parsedClass.toLowerCase() + '-20',
            'mafe-gateway-' + parsedClass.toLowerCase() + '-30',
            'mafe-gateway-' + parsedClass.toLowerCase() + '-41',
            'mafe-gateway-' + parsedClass.toLowerCase() + '-51',
            'mafe-gateway-' + parsedClass.toLowerCase() + '-61'
        ],
        type: options
    };
};

/**
 * Executes the command, changes the position of the element, and if necessary
 * updates the position of its children, and refreshes all connections
 */
CommandChangeGatewayType.prototype.execute = function () {
    var menuShape;
    this.layer.setZoomSprites(this.after.zoomSprite);
    this.receiver.setGatewayType(this.after.type);
    this.receiver.extendedType = this.after.type;
    this.receiver
        .updateBpmGatewayType(this.receiver.mapBpmnType[this.after.type]);
    PMDesigner.project.updateElement([]);
    this.receiver.paint();
    if (this.after.type === 'PARALLEL' && this.before.defaultFlow) {
        this.receiver.gat_default_flow = null;
        this.before.defaultFlow.changeFlowType('sequence');
        this.before.defaultFlow.setFlowType("SEQUENCE");
    }

    menuShape = PMDesigner.getMenuFactory(this.after.type);
    this.receiver.setContextMenu(menuShape);
};

/**
 * Returns to the state before the command was executed
 */
CommandChangeGatewayType.prototype.undo = function () {
    this.layer.setZoomSprites(this.before.zoomSprite);
    this.receiver.setGatewayType(this.before.type);
    this.receiver.extendedType = this.before.type;
    this.receiver
        .updateBpmGatewayType(this.receiver.mapBpmnType[this.before.type]);
    PMDesigner.project.setDirty(true);
    $(this.receiver.html).trigger('changeelement');
    this.receiver.paint();
    menuShape = PMDesigner.getMenuFactory(this.before.type);
    this.receiver.setContextMenu(menuShape);
    if (this.after.type === 'PARALLEL' && this.before.defaultFlow) {
        this.receiver.gat_default_flow = this.before.defaultFlow.getID();
        this.before.defaultFlow.setFlowCondition("");
        this.before.defaultFlow.changeFlowType('default');
        this.before.defaultFlow.setFlowType("DEFAULT");

    }
};

/**
 *  Executes the command again after an undo action has been done
 */
CommandChangeGatewayType.prototype.redo = function () {
    this.execute();
};

