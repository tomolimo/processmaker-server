bpmnEventInterSignal=function(){
VectorFigure.call(this);
this.stroke=1;
};
bpmnEventInterSignal.prototype=new VectorFigure;
bpmnEventInterSignal.prototype.type="bpmnEventInterSignal";
bpmnEventInterSignal.prototype.paint=function(){
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

this.graphics.setStroke(this.stroke);
var x_cir = 0;
var y_cir = 0;

this.graphics.setColor("#c0c0c0");
this.graphics.fillEllipse(x_cir+3,y_cir+3,this.getWidth(),this.getHeight());

this.graphics.setColor("#f9faf2");
this.graphics.fillEllipse(x_cir,y_cir,this.getWidth(),this.getHeight());
this.graphics.setColor("#adae5e");
this.graphics.drawEllipse(x_cir,y_cir,this.getWidth(),this.getHeight());

var x_cir2=5;
var y_cir2=5;
this.graphics.setColor("#f9faf2");
this.graphics.fillEllipse(x_cir2,y_cir2,this.getWidth()-10,this.getHeight()-10);
this.graphics.setColor("#adae5e");
this.graphics.drawEllipse(x_cir2,y_cir2,this.getWidth()-10,this.getHeight()-10);
//var x=new Array(12,32,22);
//var y=new Array(32,32,9);
var cw = this.getWidth();
var ch = this.getHeight();
var x=new Array(cw*0.26,cw*0.71,cw*0.49);
var y=new Array(ch*0.71,ch*0.71,ch*0.2);
//var x=new Array(this.getWidth()/3.75,this.getWidth()/1.4,this.getWidth()/2.04);
//var y=new Array(this.getHeight()/1.4,this.getHeight()/1.4,this.getHeight()/5);
this.graphics.setColor("#adae5e");
this.graphics.fillPolygon(x,y);
this.graphics.setColor("#adae5e");
this.graphics.drawPolygon(x,y);
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

bpmnEventInterSignal.prototype.setWorkflow=function(_40c5){
VectorFigure.prototype.setWorkflow.call(this,_40c5);
if(_40c5!=null){
    var eventPortName = ['input1','input2','output1','output2'];
    var eventPortType = ['InputPort','InputPort','OutputPort','OutputPort'];
    var eventPositionX= [0,this.width/2,this.width,this.width/2];
    var eventPositionY= [this.height/2,0,this.height/2,this.height];

    for(var i=0; i< eventPortName.length ; i++){
        eval('this.'+eventPortName[i]+' = new '+eventPortType[i]+'()');                               //Create New Port
        eval('this.'+eventPortName[i]+'.setWorkflow(_40c5)');                                        //Add port to the workflow
        eval('this.'+eventPortName[i]+'.setName("'+eventPortName[i]+'")');                            //Set PortName
        eval('this.'+eventPortName[i]+'.setZOrder(-1)');                                             //Set Z-Order of the port to -1. It will be below all the figure
        eval('this.'+eventPortName[i]+'.setBackgroundColor(new Color(255, 255, 255))');              //Setting Background of the port to white
        eval('this.'+eventPortName[i]+'.setColor(new Color(255, 255, 255))');                        //Setting Border of the port to white
        eval('this.addPort(this.'+eventPortName[i]+','+eventPositionX[i]+', '+eventPositionY[i]+')');  //Setting Position of the port
     }
}
};

bpmnEventInterSignal.prototype.getContextMenu=function(){
if(this.id != null){
   this.workflow.handleContextMenu(this);
}
};
