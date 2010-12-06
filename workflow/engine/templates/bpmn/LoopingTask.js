bpmnLoopingTask=function(_30ab){
VectorFigure.call(this);
this.setDimension(110,60);
this.setTaskName(_30ab); //It will set the Default Task Name with appropriate count While dragging a task on the canvas
};

bpmnLoopingTask.prototype=new VectorFigure;
bpmnLoopingTask.prototype.type="bpmnLoopingTask";
bpmnLoopingTask.prototype.setTaskName=function(name){
this.taskName = 'Task '+name;
};

bpmnLoopingTask.prototype.paint=function(){
VectorFigure.prototype.paint.call(this);

var x_subtask=new Array(6, this.getWidth()-3, this.getWidth(), this.getWidth(),    this.getWidth()-3, 6,                3,                  3, 6);
var y_subtask=new Array(3, 3,                 6,               this.getHeight()-3, this.getHeight(),  this.getHeight(), this.getHeight()-3, 6, 3);
var x_subtask=new Array(6,125,128,128,125,6,3,3,6);
var y_subtask=new Array(3,3,6,87,90,90,87,6,3);
this.graphics.setStroke(this.stroke);
this.graphics.setColor( "#c0c0c0" );
this.graphics.fillPolygon(x_subtask,y_subtask);
for(var i=0;i<x_subtask.length;i++){
x_subtask[i]=x_subtask[i]-3;
y_subtask[i]=y_subtask[i]-3;
}
this.graphics.setColor( "#ffffff" );
this.graphics.fillPolygon(x_subtask,y_subtask);
this.graphics.setColor("#00ff00");
this.graphics.drawPolygon(x_subtask,y_subtask);
var x_task=new Array(15, this.getWidth()+6, this.getWidth()+9, this.getWidth()+9,    this.getWidth()+6, 15,                12,                  12, 15);
var y_task=new Array(3, 3,                 6,               this.getHeight()-3, this.getHeight(),  this.getHeight(), this.getHeight()-3, 6, 3);
this.graphics.setStroke(this.stroke);
this.graphics.setColor( "#c0c0c0" );
this.graphics.fillPolygon(x_task,y_task);
for(var i=0;i<x_task.length;i++){
x_task[i]=x_task[i]-3;
y_task[i]=y_task[i]-3;
}
this.graphics.setColor( "#ffffff" );
this.graphics.fillPolygon(x_task,y_task);
this.graphics.setColor("#00ff00");
this.graphics.drawPolygon(x_task,y_task);
this.graphics.paint();
};

