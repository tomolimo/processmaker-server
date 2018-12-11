var PMCommandSwitchToLaneContainer = function (receiver) {
    PMCommandSwitchToLaneContainer.superclass.call(this, receiver);
    this._parentLane = null;
    this._boundExceed = {
        x: 0,
        y: 0
    };
    PMCommandSwitchToLaneContainer.prototype.initObject.call(this, receiver);
};

PMUI.inheritFrom("PMUI.command.CommandSwitchContainer", PMCommandSwitchToLaneContainer);

PMCommandSwitchToLaneContainer.prototype.type = 'PMCommandSwitchToLaneContainer';

PMCommandSwitchToLaneContainer.prototype.initObject = function (receiver) {
    this._parentLane = this.after.shapes[0].parent;
};

PMCommandSwitchToLaneContainer.prototype.execute = function () {
    var i, shape, boundExceed, laneWidth, laneHeight;
    PMCommandSwitchToLaneContainer.superclass.prototype.execute.call(this);

    if (this.before.laneWidth === undefined ||this.before.laneHeight === undefined) {
        laneWidth = this._parentLane.getWidth();
        laneHeight = this._parentLane.getHeight();

        this.before.laneWidth = laneWidth;
        this.before.laneHeight = laneHeight;

        for (i = 0; i < this.relatedShapes.length; i += 1) {
            shape = this.relatedShapes[i];
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
    }

    this._parentLane.setDimension(
        this.after.laneWidth,
        this.after.laneHeight
    );
    this._parentLane.updateAllRelatedDimensions();

    return this;
};

PMCommandSwitchToLaneContainer.prototype.undo = function () {
    PMCommandSwitchToLaneContainer.superclass.prototype.undo.call(this);

    this._parentLane.setDimension(
        this.before.laneWidth,
        this.before.laneHeight
    );
    this._parentLane.updateAllRelatedDimensions();

    return this;
};

