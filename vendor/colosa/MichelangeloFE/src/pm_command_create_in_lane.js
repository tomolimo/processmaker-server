var PMCommandCreateInLane = function (receiver) {
    PMCommandCreateInLane.superclass.call(this, receiver);
    this._parentLane = null;
    this._boundExceed = {};
    PMCommandCreateInLane.prototype.initObject.call(this, receiver);
};

PMUI.inheritFrom('PMUI.command.CommandCreate', PMCommandCreateInLane);

PMCommandCreateInLane.prototype.type = 'PMCommandCreateInLane';

PMCommandCreateInLane.prototype.initObject = function (receiver) {
    this._parentLane = receiver.getParent();

    if (!(this._parentLane instanceof PMLane)) {
        throw new Error('initObject(): The container element must be a PMLane instance.');
    }

    this._boundExceed = {
        x: receiver.getWidth() - (this._parentLane.getWidth() - receiver.getX()),
        y: receiver.getHeight() - (this._parentLane.getHeight() - receiver.getY())
    };

    this.before.laneWidth = this._parentLane.getWidth();
    this.before.laneHeight = this._parentLane.getHeight();

    this.after.laneWidth = this.before.laneWidth + (this._boundExceed.x > 0 ? this._boundExceed.x : 0);
    this.after.laneHeight = this.before.laneHeight + (this._boundExceed.y > 0 ? this._boundExceed.y : 0);
};

PMCommandCreateInLane.prototype.execute = function () {
    PMCommandCreateInLane.superclass.prototype.execute.call(this);

    if (this._boundExceed.x > 0 || this._boundExceed.y > 0) {
        this._parentLane.setDimension(
            this.after.laneWidth,
            this.after.laneHeight
        );
        this._parentLane.updateAllRelatedDimensions();
    }

    return this;
};

PMCommandCreateInLane.prototype.undo = function () {
    PMCommandCreateInLane.superclass.prototype.undo.call(this);

    if (this._boundExceed.x > 0 || this._boundExceed.y > 0) {
        this._parentLane.setDimension(
            this.before.laneWidth,
            this.before.laneHeight
        );
        this._parentLane.updateAllRelatedDimensions();
    }

    return this;
};