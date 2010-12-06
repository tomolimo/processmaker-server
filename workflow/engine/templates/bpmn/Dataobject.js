bpmnDataobject = function (_30ab) {
    VectorFigure.call(this);
    this.setDimension(50, 80);
    this.setTaskName(_30ab.taskNo); //It will set the Default Task Name with appropriate count While dragging a task on the canvas
};

bpmnDataobject.prototype = new VectorFigure;
bpmnDataobject.prototype.type = "bpmnDataobject";
bpmnDataobject.prototype.setTaskName = function (name) {
    this.taskName = 'Data Object ' + name;
};

bpmnDataobject.prototype.paint = function () {
    VectorFigure.prototype.paint.call(this);
    var x = new Array(0, this.getWidth()-10, this.getWidth(), this.getWidth()-10, this.getWidth()-10, this.getWidth(), this.getWidth(), 0);
    var y = new Array(0, 0, 10, 10, 0, 10, this.getHeight(), this.getHeight());

    this.graphics.setStroke(this.stroke);
    this.graphics.setColor("#c0c0c0");
    this.graphics.fillPolygon(x, y);

    for (var i = 0; i < x.length; i++) {
        x[i] = x[i] - 3;
        y[i] = y[i] - 3;
    }
    this.graphics.setColor("#ffffff");
    this.graphics.fillPolygon(x, y);
    this.graphics.setColor("#ff0f0f");
    this.graphics.drawPolygon(x, y);
    this.graphics.paint();
    this.x_text = this.workflow.getAbsoluteX(); //Get x co-ordinate from figure
    this.y_text = this.workflow.getAbsoluteY(); //Get x co-ordinate from figure
}
