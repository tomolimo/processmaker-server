bpmnEventTimerInter=function(){
VectorFigure.call(this);
this.stroke = 1;
};
bpmnEventTimerInter.prototype=new VectorFigure;
bpmnEventTimerInter.prototype.type="bpmnEventTimerInter";
bpmnEventTimerInter.prototype.paint=function(){
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

var cw = this.getWidth();
var ch = this.getHeight();

var x_cir1=0;
var y_cir1=0;

this.graphics.setColor("#c0c0c0");
this.graphics.fillEllipse(x_cir1+3,y_cir1+3,this.getWidth(),this.getHeight());

this.graphics.setStroke(this.stroke);
this.graphics.setColor( "#f9faf2" );
this.graphics.fillEllipse(x_cir1,y_cir1,this.getWidth(),this.getHeight());
this.graphics.setColor("#adae5e");
this.graphics.drawEllipse(x_cir1,y_cir1,this.getWidth(),this.getHeight());
this.graphics.setStroke(this.stroke);
var x_cir2=3;
var y_cir2=3;
this.graphics.setColor( "#f9faf2" );
this.graphics.fillEllipse(x_cir2,y_cir2,this.getWidth()-6,this.getHeight()-6);
this.graphics.setColor("#adae5e");
this.graphics.drawEllipse(cw*0.15, ch*0.15, ch*0.7, ch*0.7);
//var x_cir3=10;
//var y_cir3=10;
//this.graphics.setColor( "#f9faf2" );
//this.graphics.fillEllipse(x_cir3,y_cir3,this.getWidth()-20,this.getHeight()-20);
this.graphics.setColor("#adae5e");
//this.graphics.drawEllipse(x_cir3,y_cir3,this.getWidth()-20,this.getHeight()-20);

//this.graphics.drawLine(cw*0.5,ch*0.5,cw*0.77,ch*0.5);
//this.graphics.drawLine(cw*0.5,ch*0.5,cw*0.5,ch*0.22);
this.graphics.drawLine( cw*0.56, ch*0.5, cw*0.43, ch*0.5);   //horizontal
this.graphics.drawLine( cw*0.6, ch*0.3, cw*0.43, ch*0.5);

this.graphics.drawLine(cw*0.73,ch*0.26,cw*0.66,ch*0.30);  //10th min line
this.graphics.drawLine(cw*0.66,ch*0.50,cw*0.80,ch*0.50);  //15th min line
this.graphics.drawLine(cw*0.60,ch*0.66,cw*0.73,ch*0.73);  //25th min line
this.graphics.drawLine(cw*0.50,ch*0.83,cw*0.50,ch*0.70);  //30th min line
this.graphics.drawLine(cw*0.23,ch*0.70,cw*0.36,ch*0.63);  //40th min line
this.graphics.drawLine(cw*0.16,ch*0.50,cw*0.30,ch*0.50);  //45th min line
this.graphics.drawLine(cw*0.26,ch*0.26,cw*0.36,ch*0.36);  //50th min line
this.graphics.drawLine(cw*0.50,ch*0.16,cw*0.50,ch*0.26);  //60th min line
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

bpmnEventTimerInter.prototype.setWorkflow=function(_40c5){
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

bpmnEventTimerInter.prototype.getContextMenu=function(){
if(this.id != null){
   this.workflow.handleContextMenu(this);
}
};
