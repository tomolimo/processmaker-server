var PMCommandAddToLane = function (receiver, childObject, coordinates) {
    PMCommandAddToLane.superclass.call(this, receiver);
    this._boundExceed = {};
    PMCommandAddToLane.prototype.initObject.call(this, receiver, childObject, coordinates);
};

PMUI.inheritFrom('PMUI.command.Command', PMCommandAddToLane);

PMCommandAddToLane.prototype.type = 'PMCommandAddToLane';

PMCommandAddToLane.prototype.initObject = function (receiver, childObject, coordinates) {
    this.childObject = childObject;

    this._boundExceed = {
        x: childObject.getWidth() - (receiver.getWidth() - coordinates.x),
        y: childObject.getHeight() - (receiver.getHeight() - coordinates.y)
    };
    this.before = {
        laneWidth: this.receiver.getWidth(),
        laneHeight: this.receiver.getHeight()
    };
    this.after = {
        childX: this.childObject.getX(),
        childY: this.childObject.getY()
    };
};

PMCommandAddToLane.prototype.execute = function () {
    if (this._boundExceed.x > 0 || this._boundExceed.y > 0) {
        this.receiver.setDimension(
            this.receiver.width + (this._boundExceed.x >= 0 ? this._boundExceed.x : 0),
            this.receiver.height + (this._boundExceed.y >= 0 ? this._boundExceed.y : 0)
        );
        this.receiver.updateAllRelatedDimensions();
    }

    if (!this.receiver.getChildren().contains(this.childObject)) {
        this.receiver.getChildren().insert(this.childObject);
    }

    this.after.laneWidth = this.receiver.getWidth();
    this.after.laneHeight = this.receiver.getHeight();

    this.receiver.html.appendChild(this.childObject.getHTML());
    this.childObject.canvas.addToList(this.childObject);
    this.childObject.showOrHideResizeHandlers(false);
    this.childObject.canvas.triggerCreateEvent(this.childObject, []);
    return this;
};

PMCommandAddToLane.prototype.undo = function () {
    this.receiver.getChildren().remove(this.childObject);

    if (this.after.laneWidth !== this.before.laneWidth || this.after.laneHeight !== this.before.laneHeight) {
        this.receiver.setDimension(this.before.laneWidth, this.before.laneHeight);
        this.receiver.updateAllRelatedDimensions();
    }

    this.childObject.saveAndDestroy();
    this.childObject.canvas.triggerRemoveEvent(this.childObject, []);
    return this;
};

PMCommandAddToLane.prototype.redo = function () {
    this.execute();
    return this;
};


