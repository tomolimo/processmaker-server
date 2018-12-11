var PMCommandMoveInLane = function (receiver) {
    PMCommandMoveInLane.superclass.call(this, receiver);
    this._parentLane = null;
    this._boundExceed = {
        x: 0,
        y: 0
    };
    PMCommandMoveInLane.prototype.initObject.call(this, receiver);
};

PMUI.inheritFrom('PMUI.command.CommandMove', PMCommandMoveInLane);

PMCommandMoveInLane.prototype.type = 'PMCommandMoveInLane';

PMCommandMoveInLane.prototype.initObject = function (receiver) {
    var i,
        size = this.receiver.getSize(),
        shape,
        boundExceed,
        laneWidth,
        laneHeight;

    if (this.receiver.getSize() > 0) {
        this._parentLane = receiver.get(0).getParent();
    }

    laneWidth = this._parentLane.getWidth();
    laneHeight = this._parentLane.getHeight();

    this.before.laneWidth = laneWidth;
    this.before.laneHeight = laneHeight;

    for (i = 0; i < size; i += 1) {
        shape = this.receiver.get(i);
        boundExceed = {
            x: shape.getWidth() - (laneWidth - shape.getX()),
            y: shape.getHeight() - (laneHeight - shape.getY())
        };
        if (boundExceed.x > 0 || boundExceed.y > 0) {
            if (this._boundExceed.x < boundExceed.x) {
                this._boundExceed.x = boundExceed.x;
            }
            if (this._boundExceed.y < boundExceed.y) {
                this._boundExceed.y = boundExceed.y;
            }
        }
    }
    this.after.laneWidth = laneWidth + this._boundExceed.x;
    this.after.laneHeight = laneHeight + this._boundExceed.y;
};

PMCommandMoveInLane.prototype.execute = function () {
    var res = PMCommandMoveInLane.superclass.prototype.execute.call(this);

    this._parentLane.setDimension(
        this.after.laneWidth,
        this.after.laneHeight
    );
    this._parentLane.updateAllRelatedDimensions(false);

    return res;
};

PMCommandMoveInLane.prototype.undo = function () {
    var res = PMCommandMoveInLane.superclass.prototype.undo.call(this);

    this._parentLane.setDimension(
        this.before.laneWidth,
        this.before.laneHeight
    );
    this._parentLane.updateAllRelatedDimensions();

    return res;
};