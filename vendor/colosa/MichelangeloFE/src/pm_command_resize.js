var PMCommandResize = function (receiver) {
    PMCommandResize.superclass.call(this, receiver);
    this._boundExceed = null;
    this._parent;
    PMCommandResize.prototype.initObject.call(this, receiver);
};

PMUI.inheritFrom('PMUI.command.CommandResize', PMCommandResize);

PMCommandResize.prototype.type = 'PMCommandResize';

PMCommandResize.prototype.initObject = function (receiver) {
    this._parent = this.receiver.getParent();

    if (this._parent.getType() === 'PMLane') {
        this.before.parentWidth = this.after.parentWidth = this._parent.getWidth();
        this.before.parentHeight = this.after.parentHeight = this._parent.getHeight();

        if (this._boundExceed === null) {
            this._boundExceed = {
                x: this.receiver.getWidth() - (this._parent.getWidth() - this.receiver.getX()),
                y: this.receiver.getHeight() - (this._parent.getHeight() - this.receiver.getY())
            };
        }

        if (this._boundExceed.x > 0) {
            this.after.parentWidth = this.before.parentWidth + this._boundExceed.x;
        }
        if (this._boundExceed.y > 0) {
            this.after.parentHeight = this.before.parentHeight + this._boundExceed.y;
        }
    }
};

PMCommandResize.prototype.execute = function () {
    PMCommandResize.superclass.prototype.execute.call(this);

    if (this._boundExceed !== null && (this._boundExceed.x > 0 || this._boundExceed.y > 0)) {
        this._parent.setDimension(
            this.after.parentWidth,
            this.after.parentHeight
        );
        this._parent.updateAllRelatedDimensions();
    }

    return this;
};

PMCommandResize.prototype.undo = function () {
    PMCommandResize.superclass.prototype.undo.call(this);

    if (this._boundExceed !== null && (this._boundExceed.x > 0 || this._boundExceed.y > 0)) {
        this._parent.setDimension(
            this.before.parentWidth,
            this.before.parentHeight
        );
        this._parent.updateAllRelatedDimensions();
    }

    return this;
};

