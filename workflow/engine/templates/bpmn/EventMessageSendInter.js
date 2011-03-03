bpmnEventMessageSendInter=function(){
VectorFigure.call(this);

this.stroke=1;
};
bpmnEventMessageSendInter.prototype=new VectorFigure;
bpmnEventMessageSendInter.prototype.type="bpmnEventMessageSendInter";
bpmnEventMessageSendInter.prototype.paint=function(){
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

var x_cir = 0;
var y_cir = 0;

this.graphics.setColor("#c0c0c0");
this.graphics.fillEllipse(x_cir+3,y_cir+3,this.getWidth(),this.getHeight());
this.graphics.setStroke(this.stroke);
this.graphics.setColor( "#f9faf2");
this.graphics.fillEllipse(x_cir,y_cir,this.getWidth(),this.getHeight());

this.graphics.setColor("#adae5e");
this.graphics.drawEllipse(x_cir,y_cir,this.getWidth(),this.getHeight());

var x_cir2=3;
var y_cir2=3;

this.graphics.setColor( "#f9faf2" );
this.graphics.fillEllipse(x_cir2,y_cir2,this.getWidth()-6,this.getHeight()-6);

this.graphics.setColor("#adae5e");
this.graphics.drawEllipse(x_cir2,y_cir2,this.getWidth()-6,this.getHeight()-6);
//var x=new Array(12,12,32,32,22,12,32);
//var y=new Array(14,31,31,14,23,14,14);
/*var x=new Array(this.getWidth()/3.75,this.getWidth()/3.75,this.getWidth()/1.28,this.getWidth()/1.28,this.getWidth()/1.95,this.getWidth()/3.75);
var y=new Array(this.getHeight()/2.64,this.getHeight()/1.36,this.getHeight()/1.36,this.getHeight()/2.64,this.getHeight()/1.73,this.getHeight()/2.64);
this.graphics.setStroke(1);
this.graphics.setColor( "#adae5e" );
this.graphics.fillPolygon(x,y);
this.graphics.setColor("#adae5e");
//this.graphics.drawPolygon(x,y);
//var x_tri=new Array(12,23.5,35);
//var y_tri=new Array(13,22,13);
var x_tri=new Array(this.getWidth()/3.75,this.getWidth()/1.91,this.getWidth()/1.28);
var y_tri=new Array(this.getHeight()/3.46,this.getHeight()/2.04,this.getHeight()/3.46);
this.graphics.setColor( "#adae5e" );
this.graphics.fillPolygon(x_tri,y_tri);
this.graphics.setColor("#adae5e");*/
//this.graphics.drawPolygon(x_tri,y_tri);
//draw the mail icon
  var cw = this.getWidth();
  var ch = this.getHeight();
  var x = new Array( cw*0.25, cw*0.25, cw*0.78, cw*0.78, cw*0.52, cw*0.25, cw*0.25, cw*0.78);
  var y = new Array( ch*0.31, ch*0.71, ch*0.71, ch*0.32, ch*0.52, ch*0.32, ch*0.31, ch*0.31);
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

bpmnEventMessageSendInter.prototype.setWorkflow=function(_40c5){
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

bpmnEventMessageSendInter.prototype.getContextMenu=function(){
if(this.id != null){
   this.workflow.handleContextMenu(this);
}
};
