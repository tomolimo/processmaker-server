bpmnLane = function (_30ab) {
    VectorFigure.call(this);
    this.setDimension(500, 300);
  //  this.setTaskName(_30ab.taskNo); //It will set the Default Task Name with appropriate count While dragging a task on the canvas
};

bpmnLane.prototype = new VectorFigure;
bpmnLane.prototype.type = "bpmnLane";
bpmnLane.prototype.setTaskName = function (name) {
    this.taskName = 'Data Object ' + name;
};

bpmnLane.prototype.paint = function () {
    VectorFigure.prototype.paint.call(this);
    var x = new Array(0, this.getWidth(), this.getWidth(), 0);
    var y = new Array(0, 0, this.getHeight(), this.getHeight());
    
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
    this.graphics.drawLine(45, 0-3, 45, this.getHeight()-3);
    this.graphics.drawLine(90, 0-3, 90, this.getHeight()-3);
    this.graphics.drawLine(45, 150-3, this.getWidth()-3, 150-3);
    this.graphics.paint();
    this.x_text = this.workflow.getAbsoluteX(); //Get x co-ordinate from figure
    this.y_text = this.workflow.getAbsoluteY(); //Get x co-ordinate from figure
}
