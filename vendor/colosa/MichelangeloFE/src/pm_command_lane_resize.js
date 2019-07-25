/**
 * Command resize: command resize when a poll is resized (mazimized or minimized)
 * @class BPMNCommandPoolResize
 * @constructor
 */
var PMCommandLaneResize = function (receiver) {
    PMUI.command.CommandResize.call(this, receiver);
};
/**
 * Type of command of this object
 * @type {String}
 */
PMUI.inheritFrom('PMUI.command.CommandResize', PMCommandLaneResize);
PMCommandLaneResize.prototype.type = 'PMCommandLaneResize';
/**
 * Executes the command
 */
PMCommandLaneResize.prototype.execute = function () {
    PMUI.command.CommandResize.prototype.execute.call(this);
    this.receiver.updateAllRelatedDimensions(true);
};
/**
 * Inverse executes the command a.k.a. undo
 */
PMCommandLaneResize.prototype.undo = function () {
    this.receiver.oldWidth = this.receiver.getWidth();
    this.receiver.oldHeight = this.receiver.getHeight();
    PMUI.command.CommandResize.prototype.undo.call(this);
    this.receiver.updateAllRelatedDimensions(true);
};
/**
 * Executes the command a.k.a redo
 */
PMCommandLaneResize.prototype.redo = function () {
    this.execute();
};
