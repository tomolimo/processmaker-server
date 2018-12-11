var CommandChangeEventMarker = function (receiver, options) {
    PMUI.command.Command.call(this, receiver);
    this.before = null;
    this.after = null;
    CommandChangeEventMarker.prototype.initObject.call(this, options);
};

PMUI.inheritFrom('PMUI.command.Command', CommandChangeEventMarker);
/**
 * Type of the instances of this class
 * @property {String}
 */
CommandChangeEventMarker.prototype.type = "CommandChangeEventMarker";

/**
 * Initializes the command parameters
 * @param {PMUI.draw.Core} receiver The object that will perform the action
 */
CommandChangeEventMarker.prototype.initObject = function (options) {
    var parsedClass = options;
    this.layer = this.receiver.getLayers().get(0);
    this.before = {
        zoomSprite: this.layer.zoomSprites,
        marker: this.receiver.evn_marker
    };
    this.after = {
        zoomSprite: [
            'mafe-event-' + this.receiver.getEventType().toLowerCase() + '-' +
            parsedClass.toLowerCase() + '-16',
            'mafe-event-' + this.receiver.getEventType().toLowerCase() + '-' +
            parsedClass.toLowerCase() + '-24',
            'mafe-event-' + this.receiver.getEventType().toLowerCase() + '-' +
            parsedClass.toLowerCase() + '-33',
            'mafe-event-' + this.receiver.getEventType().toLowerCase() + '-' +
            parsedClass.toLowerCase() + '-41',
            'mafe-event-' + this.receiver.getEventType().toLowerCase() + '-' +
            parsedClass.toLowerCase() + '-49'
        ],
        marker: options
    };
};

/**
 * Executes the command, changes the position of the element, and if necessary
 * updates the position of its children, and refreshes all connections
 */
CommandChangeEventMarker.prototype.execute = function () {
    var menuShape;
    this.layer.setZoomSprites(this.after.zoomSprite);
    this.receiver.setEventMarker(this.after.marker);
    this.receiver
        .updateBpmEventMarker(this.receiver.getBpmnElementType());
    PMDesigner.project.updateElement([]);
    menuShape = PMDesigner.getMenuFactory(this.receiver.getEventType());
    this.receiver.setContextMenu(menuShape);
    this.receiver.paint();
};

/**
 * Returns to the state before the command was executed
 */
CommandChangeEventMarker.prototype.undo = function () {
    var menuShape;
    this.layer.setZoomSprites(this.before.zoomSprite);
    this.receiver.setEventMarker(this.before.marker);
    this.receiver
        .updateBpmEventMarker(this.receiver.getBpmnElementType());
    this.receiver.extendedType = this.before.marker;
    PMDesigner.project.setDirty(true);
    $(this.receiver.html).trigger('changeelement');

    menuShape = PMDesigner.getMenuFactory(this.receiver.getEventType());
    this.receiver.setContextMenu(menuShape);
    this.receiver.paint();
};

/**
 *  Executes the command again after an undo action has been done
 */
CommandChangeEventMarker.prototype.redo = function () {
    this.execute();
};