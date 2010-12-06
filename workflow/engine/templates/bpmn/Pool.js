bpmnPool = function (workflow) {
    VectorFigure.call(this);
    this.setDimension(800, 600);
    var figures = workflow.getFigures();
    for(var i=0;i<figures.length;i++)
      {
        var test = figures[i];
      }
    //workflow.moveFront(workflow.getFigures());
    //ToolGeneric.prototype.execute.call(this);
   // this.processName = workflow.processInfo.title.label; //It will set the Default Task Name with appropriate count While dragging a task on the canvas
};

bpmnPool.prototype = new VectorFigure;
bpmnPool.prototype.type = "bpmnPool";
//bpmnPool.prototype.setTaskName = function (name) {
//    this.taskName = 'Data Object ' + name;
//};

bpmnPool.prototype.paint = function () {
    VectorFigure.prototype.paint.call(this);

    //Setting up Limitation in Width and Height
    if(this.getWidth() < 150 )
    {
        this.setDimension(150, this.getHeight());
    }
    if(this.getHeight() < 80 )
    {
        this.setDimension(this.getWidth(), 80);
    }

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
    //this.graphics.setColor("#000000");
    this.graphics.drawLine(45, 0-3,45,this.getHeight()-3);
    this.graphics.paint();
    this.x_text = this.workflow.getAbsoluteX(); //Get x co-ordinate from figure
    this.y_text = this.workflow.getAbsoluteY(); //Get x co-ordinate from figure
}
