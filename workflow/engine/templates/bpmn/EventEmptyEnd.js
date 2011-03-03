bpmnEventEmptyEnd=function(){
VectorFigure.call(this);
this.stroke=2;
};
bpmnEventEmptyEnd.prototype=new VectorFigure;
bpmnEventEmptyEnd.prototype.type="bpmnEventEmptyEnd";
bpmnEventEmptyEnd.prototype.paint=function(){
VectorFigure.prototype.paint.call(this);
if(typeof workflow.zoomfactor == 'undefined')
 workflow.zoomfactor = 1;
  //Set the Task Limitation
if(typeof this.limitFlag == 'undefined' || this.limitFlag == false)
{
  this.originalWidth = 30;
  this.originalHeight = 30;
  this.orgXPos = this.getX();
  this.orgYPos = this.getY();
  this.orgFontSize =this.fontSize;
}

this.width  = this.originalWidth * workflow.zoomfactor;
this.height = this.originalHeight  * workflow.zoomfactor;

var x=0;
var y=0;
this.graphics.setColor("#c0c0c0");
this.graphics.fillEllipse(x+5,y+5,this.getWidth(),this.getHeight());
this.graphics.setStroke(this.stroke);
this.graphics.setColor( "#f5d4d4" );
this.graphics.fillEllipse(x,y,this.getWidth(),this.getHeight());
this.graphics.setColor("#a23838");
this.graphics.drawEllipse(x,y,this.getWidth(),this.getHeight());
this.graphics.paint();

/*Code Added to Dynamically shift Ports on resizing of shapes
 **/
if(this.input1!=null){
this.input1.setPosition(0,this.height/2);
}
if(this.output1!=null){
this.output1.setPosition(this.width/2,this.height);
}
if(this.input2!=null){
this.input2.setPosition(this.width/2,0);
}
if(this.output2!=null){
this.output2.setPosition(this.width,this.height/2);
}
};

bpmnEventEmptyEnd.prototype.setWorkflow=function(_40c5){
VectorFigure.prototype.setWorkflow.call(this,_40c5);
if(_40c5!=null){

    var eventPortName = ['input1','input2'];
    var eventPortType = ['InputPort','InputPort'];
    var eventPositionX= [this.width/2,0];
    var eventPositionY= [0,this.height/2];

    for(var i=0; i< eventPortName.length ; i++){
        eval('this.'+eventPortName[i]+' = new '+eventPortType[i]+'()');                               //Create New Port
        eval('this.'+eventPortName[i]+'.setWorkflow(_40c5)');                                        //Add port to the workflow
        eval('this.'+eventPortName[i]+'.setName("'+eventPortName[i]+'")');                            //Set PortName
        eval('this.'+eventPortName[i]+'.setZOrder(-1)');                                             //Set Z-Order of the port to -1. It will be below all the figure
        eval('this.'+eventPortName[i]+'.setBackgroundColor(new Color(255, 255, 255))');              //Setting Background of the port to white
        eval('this.'+eventPortName[i]+'.setColor(new Color(255, 255, 255))');                        //Setting Border of the port to white
        eval('this.addPort(this.'+eventPortName[i]+','+eventPositionX[i]+', '+eventPositionY[i]+')');  //Setting Position of the port
     }
    /*
this.input1=new InputPort();
this.input1.setWorkflow(_40c5);
this.input1.setName("input1");
this.input1.setBackgroundColor(new Color(245,115,115));
this.addPort(this.input1,this.width/2,0);

this.input2=new InputPort();
this.input2.setWorkflow(_40c5);
this.input2.setName("input2");
this.input2.setBackgroundColor(new Color(245,115,115));
this.addPort(this.input2,0,this.height/2);*/
}
};


bpmnEventEmptyEnd.prototype.getContextMenu=function(){
if(this.id != null){
   this.workflow.handleContextMenu(this);
}
};
