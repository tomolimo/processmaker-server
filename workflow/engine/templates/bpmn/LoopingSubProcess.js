bpmnLoopingSubProcess=function(_30ab){
VectorFigure.call(this);
this.setDimension(110,60);
this.setTaskName(_30ab); //It will set the Default Task Name with appropriate count While dragging a task on the canvas
};

bpmnLoopingSubProcess.prototype=new VectorFigure;
bpmnLoopingSubProcess.prototype.type="bpmnLoopingSubProcess";
bpmnLoopingSubProcess.prototype.setTaskName=function(name){
this.taskName = 'Task '+name;
};
bpmnLoopingSubProcess.prototype.paint=function(){
VectorFigure.prototype.paint.call(this);
var x=new Array(6, this.getWidth()-3, this.getWidth(), this.getWidth(),    this.getWidth()-3, 6,                3,                  3, 6);
var y=new Array(3, 3,                 6,               this.getHeight()-3, this.getHeight(),  this.getHeight(), this.getHeight()-3, 6, 3);
this.graphics.setStroke(this.stroke);
this.graphics.setColor( "#c0c0c0" );
this.graphics.fillPolygon(x,y);

for(var i=0;i<x.length;i++){
x[i]=x[i]-3;
y[i]=y[i]-3;
}
this.graphics.setColor( "#ffffff" );
this.graphics.fillPolygon(x,y);
this.graphics.setColor("#0000ff");
this.graphics.drawPolygon(x,y);
this.graphics.paint();
this.x_text = this.workflow.getAbsoluteX();  //Get x co-ordinate from figure
this.y_text = this.workflow.getAbsoluteY();  //Get x co-ordinate from figure
/* Created New Object of jsGraphics to draw String.
 * New object is created to implement changing of Text functionality
 */
var bpmnText = new jsGraphics(this.id);
//bpmnText.drawStringRect(this.taskName,this.getWidth()/2-20,this.getHeight()/2-11,200,'left');
bpmnText.drawString(this.taskName,this.getWidth()/2.5,this.getHeight()/2.5);

bpmnText.paint();
this.bpmnNewText = bpmnText;

if(this.inputPort1!=null){
this.inputPort1.setPosition(this.width,this.height/2);
}
if(this.outputPort1!=null){
this.outputPort1.setPosition(this.width/2,0);
}
if(this.inputPort2!=null){
this.inputPort2.setPosition(this.width/2,this.height);
}
if(this.outputPort2!=null){
this.outputPort2.setPosition(0,this.height/2);
}


};

bpmnLoopingSubProcess.prototype.setWorkflow=function(_40c5){
VectorFigure.prototype.setWorkflow.call(this,_40c5);
if(_40c5!=null){
this.inputPort1=new InputPort();
this.inputPort1.setWorkflow(_40c5);
this.inputPort1.setName("input1");
this.inputPort1.setBackgroundColor(new Color(115, 115, 245));
this.addPort(this.inputPort1,0,this.height/2);

this.inputPort2=new InputPort();
this.inputPort2.setWorkflow(_40c5);
this.inputPort2.setName("input2");
this.inputPort2.setBackgroundColor(new Color(115, 115, 245));
this.addPort(this.inputPort2,this.width/2,0);

this.outputPort1=new OutputPort();
this.outputPort1.setWorkflow(_40c5);
this.outputPort1.setName("output1");
this.outputPort1.setBackgroundColor(new Color(245,115,115));
this.addPort(this.outputPort1,this.width,this.height/2);

this.outputPort2=new OutputPort();
this.outputPort2.setWorkflow(_40c5);
this.outputPort2.setName("output2");
this.outputPort2.setBackgroundColor(new Color(245,115,115));
this.addPort(this.outputPort2,this.width/2,this.height);
}
};


/*Double Click Event for opening the dialog Box*/
bpmnLoopingSubProcess.prototype.onDoubleClick=function(){
  var _409d=new bpmnTaskDialog(this);
  this.workflow.showDialog(_409d,this.workflow.currentSelection.x,this.workflow.currentSelection.y);
};


/**
 * This method will be called if the user pressed the OK button in buttonbar of the dialog.<br>
 * The string is first cleared and new string is painted.<br><br>
**/
bpmnLoopingSubProcess.prototype.onOk=function(){
     this.figure.bpmnNewText.clear();
     //this.figure.bpmnNewText.drawStringRect(this.input.value,this.workflow.currentSelection.width/2-30,this.workflow.currentSelection.height/2-10,200,'left');
     this.figure.bpmnNewText.drawString(this.input.value,this.workflow.currentSelection.width/2.5,this.workflow.currentSelection.height/2.5);
     this.figure.bpmnNewText.paint();
     this.figure.taskName = this.input.value;  //Set Updated Text value
     this.workflow.removeFigure(this);
};