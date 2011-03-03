bpmnEventCompInter=function(){
VectorFigure.call(this);
this.stroke=1
};
bpmnEventCompInter.prototype=new VectorFigure;
bpmnEventCompInter.prototype.type="bpmnEventCompInter";
bpmnEventCompInter.prototype.paint=function(){
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
var x_cir =0;
var y_cir =0;

this.graphics.setColor("#c0c0c0");
this.graphics.fillEllipse(x_cir+3,y_cir+3,this.getWidth(),this.getHeight());

this.graphics.setColor("#f9faf2")
this.graphics.fillEllipse(x_cir,y_cir,this.getWidth(),this.getHeight())
this.graphics.setColor("#adae5e");
this.graphics.drawEllipse(x_cir,y_cir,this.getWidth(),this.getHeight());

var x_cir2=3;
var y_cir2=3;
this.graphics.setColor("#adae5e");
this.graphics.drawEllipse(x_cir2,y_cir2,this.getWidth()-6,this.getHeight()-6);
//var x_arrow=new Array(6,19,19,32,32,19,19);
//var y_arrow=new Array(22,33,22,33,11,22,11);
var cw = this.getWidth();
var ch = this.getHeight();
var x_arrow=new Array(cw*0.13,cw*0.42,cw*0.42,cw*0.71,cw*0.7,cw*0.42,cw*0.42);
var y_arrow=new Array(ch*0.5,ch*0.73,ch*0.5,ch*0.73,ch*0.25,ch*0.5,ch*0.25);
//var x_arrow=new Array(cw/7.5,cw/2.36,cw/2.36,cw/1.4,cw/1.42,cw/2.36,cw/2.36);
//var y_arrow=new Array(ch/2,ch/1.36,ch/2,ch/1.36,ch/4,ch/2,ch/4);
this.graphics.setColor( "#adae5e" );
this.graphics.fillPolygon(x_arrow,y_arrow);
this.graphics.setColor("#adae5e");
this.graphics.drawPolygon(x_arrow,y_arrow);
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

bpmnEventCompInter.prototype.setWorkflow=function(_40c5){
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
/*
this.output1=new OutputPort();
this.output1.setWorkflow(_40c5);
this.output1.setName("output1");
this.output1.setBackgroundColor(new Color(115, 115, 245));
this.addPort(this.output1,this.width/2,this.height);

this.output2=new OutputPort();
this.output2.setWorkflow(_40c5);
this.output2.setName("output2");
this.output2.setBackgroundColor(new Color(115, 115, 245));
this.addPort(this.output2,this.width,this.height/2);

this.input1=new InputPort();
this.input1.setWorkflow(_40c5);
this.input1.setName("input1");
this.input1.setBackgroundColor(new Color(245,115,115));
this.addPort(this.input1,0,this.height/2);

this.input2=new InputPort();
this.input2.setWorkflow(_40c5);
this.input2.setName("input2");
this.input2.setBackgroundColor(new Color(245,115,115));
this.addPort(this.input2,this.width/2,0);*/
}
};

bpmnEventCompInter.prototype.getContextMenu=function(){
if(this.id != null){
    this.workflow.handleContextMenu(this);
}
};

