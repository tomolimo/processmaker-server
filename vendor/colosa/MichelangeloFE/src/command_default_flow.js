var CommandDefaultFlow = function (receiver, options) {
    PMUI.command.Command.call(this, receiver);
    this.before = null;
    this.after = null;
    CommandDefaultFlow.prototype.initObject.call(this, options);
};

PMUI.inheritFrom('PMUI.command.Command', CommandDefaultFlow);
/**
 * Type of the instances of this class
 * @property {String}
 */
CommandChangeGatewayType.prototype.type = "CommandDefaultFlow";

/**
 * Initializes the command parameters
 * @param {PMUI.draw.Core} receiver The object that will perform the action
 */
CommandDefaultFlow.prototype.initObject = function (options) {

    this.before = {
        id: this.receiver.gat_default_flow
    };
    this.after = {
        id: options
    };
};

/**
 * Executes the command, changes the position of the element, and if necessary
 * updates the position of its children, and refreshes all connections
 */
CommandDefaultFlow.prototype.execute = function () {
    this.receiver.setDefaultFlow(this.after.id);
    PMDesigner.project.setDirty(true);
};

/**
 * Returns to the state before the command was executed
 */
CommandDefaultFlow.prototype.undo = function () {
    this.receiver.setDefaultFlow(this.before.id);
};

/**
 *  Executes the command again after an undo action has been done
 */
CommandDefaultFlow.prototype.redo = function () {
    this.execute();
};