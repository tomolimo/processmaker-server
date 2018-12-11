/**
 * Command resize: command resize when a poll is resized (mazimized or minimized)
 * @class BPMNCommandPoolResize
 * @constructor
 */
var PMCommandPoolResize = function (receiver, options) {
    this.isTop = options.isTop;
    this.beforeHeightsOpt = options.beforeHeightsOpt;
    this.delta = options.delta;
    if (receiver.bpmnLanes.getSize() > 0) {
        this.afterHeightsOpt = {
            lastLaneHeight: receiver.bpmnLanes.getLast().getHeight(),
            firstLaneHeight: receiver.bpmnLanes.getFirst().getHeight()
        };
    }
    PMUI.command.CommandResize.call(this, receiver);
};
/**
 * Type of command of this object
 * @type {String}
 */
PMUI.inheritFrom('PMUI.command.CommandResize', PMCommandPoolResize);
PMCommandPoolResize.prototype.type = 'PMCommandPoolResize';

/**
 * Executes the command
 */
PMCommandPoolResize.prototype.execute = function () {
    var i,
        pool = this.receiver,
        lane,
        newY,
        newWidth,
        delta;
    PMUI.command.CommandResize.prototype.execute.call(this);

    if (pool.bpmnLanes.getSize() > 0) {
        newWidth = pool.getWidth() - pool.headLineCoord - 1.2;
        if (this.isTop) {
            lane = pool.bpmnLanes.getFirst();
            lane.setDimension(newWidth, this.afterHeightsOpt.firstLaneHeight);
            newY = this.afterHeightsOpt.firstLaneHeight;
            for (i = 1; i < pool.bpmnLanes.getSize(); i += 1) {
                lane = pool.bpmnLanes.get(i);
                lane.setPosition(lane.getX(), newY);
                lane.setDimension(newWidth, lane.getHeight());
                newY += lane.getHeight();
            }
        } else {
            pool.setMinimunsResize();
            lane = pool.bpmnLanes.getLast();
            lane.setDimension(newWidth, this.afterHeightsOpt.lastLaneHeight);
            for (i = 0; i < pool.bpmnLanes.getSize() - 1; i += 1) {
                lane = pool.bpmnLanes.get(i);
                lane.setDimension(newWidth, lane.getHeight());
            }
        }
    }
    pool.canvas.refreshArray.clear();
    delta = {
        dx: this.delta.dx,
        dy: this.delta.dy
    };
    pool.poolChildConnectionOnResize(true, true);
    pool.refreshAllPoolConnections(false, delta);
};
/**
 * Inverse executes the command a.k.a. undo
 */
PMCommandPoolResize.prototype.undo = function () {
    var i,
        pool = this.receiver,
        lane,
        newWidth,
        newY,
        delta;
    this.receiver.oldHeight = this.receiver.getHeight();
    PMUI.command.CommandResize.prototype.undo.call(this);
    if (this.receiver.graphic) {
        this.receiver.paint();
    }
    if (pool.bpmnLanes.getSize() > 0) {
        newWidth = pool.getWidth() - pool.headLineCoord - 1.2;
        if (this.isTop) {
            lane = pool.bpmnLanes.getFirst();
            lane.setDimension(newWidth, this.beforeHeightsOpt.firstLaneHeight);
            newY = this.beforeHeightsOpt.firstLaneHeight;
            for (i = 1; i < pool.bpmnLanes.getSize(); i += 1) {
                lane = pool.bpmnLanes.get(i);
                lane.setPosition(lane.getX(), newY);
                lane.setDimension(newWidth, lane.getHeight());
                newY += lane.getHeight();
            }
        } else {
            lane = pool.bpmnLanes.getLast();
            lane.setDimension(newWidth, this.beforeHeightsOpt.lastLaneHeight);
            for (i = 0; i < pool.bpmnLanes.getSize() - 1; i += 1) {
                lane = pool.bpmnLanes.get(i);
                lane.setDimension(newWidth, lane.getHeight());
            }
        }
    }
    pool.canvas.refreshArray.clear();
    delta = {
        dx: this.delta.dx * -1,
        dy: this.delta.dy * -1
    };
    pool.poolChildConnectionOnResize(true, true);
    pool.refreshAllPoolConnections(false, delta);
};
/**
 * Executes the command a.k.a redo
 */
PMCommandPoolResize.prototype.redo = function () {
    this.execute();
};
