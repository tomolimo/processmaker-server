bpmnEventBoundaryInter=function(){
VectorFigure.call(this);
//Setting width and height values as per the zoom ratio
if(typeof workflow.zoomWidth != 'undefined' || typeof workflow.zoomHeight != 'undefined')
      this.setDimension(workflow.zoomWidth, workflow.zoomHeight);
else
      this.setDimension(30,30);
this.stroke = 2;
};
bpmnEventBoundaryInter.prototype=new VectorFigure;
bpmnEventBoundaryInter.prototype.type="bpmnEventBoundaryTimerInter";
bpmnEventBoundaryInter.prototype.paint=function(){
VectorFigure.prototype.paint.call(this);
var x_cir1=0;
var y_cir1=0;

this.graphics.setColor("#c0c0c0");
this.graphics.fillEllipse(x_cir1+3,y_cir1+3,this.getWidth(),this.getHeight());

this.graphics.setStroke(this.stroke);
this.graphics.setColor( "#f9faf2" );
this.graphics.fillEllipse(x_cir1,y_cir1,this.getWidth(),this.getHeight());
this.graphics.setColor("#adae5e");
this.graphics.drawEllipse(x_cir1,y_cir1,this.getWidth(),this.getHeight());
var x_cir2=3;
var y_cir2=3;
this.graphics.setColor( "#f9faf2" );
this.graphics.fillEllipse(x_cir2,y_cir2,this.getWidth()-6,this.getHeight()-6);
this.graphics.setColor("#adae5e");
this.graphics.drawEllipse(x_cir2,y_cir2,this.getWidth()-6,this.getHeight()-6);

this.graphics.setColor("#adae5e");
//this.graphics.drawEllipse(x_cir3,y_cir3,this.getWidth()-20,this.getHeight()-20);
this.graphics.drawLine(this.getWidth()/2.2,this.getHeight()/2,this.getWidth()/1.6,this.getHeight()/2);  //horizontal line
this.graphics.drawLine(this.getWidth()/2.2,this.getHeight()/2,this.getWidth()/2.2,this.getHeight()/3.7);  //vertical line

this.graphics.drawLine(24,8,20,11);  //10th min line
this.graphics.drawLine(22,15,25,15);  //15th min line
this.graphics.drawLine(24,22,19,20);  //25th min line
this.graphics.drawLine(15,22,15,25);  //30th min line
this.graphics.drawLine(8,22,12,19);  //40th min line
this.graphics.drawLine(5,15,8,15);  //45th min line
this.graphics.drawLine(8,8,11,11);  //50th min line
this.graphics.drawLine(15,5,15,8);  //60th min line

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

bpmnEventBoundaryInter.prototype.setWorkflow=function(_40c5){
VectorFigure.prototype.setWorkflow.call(this,_40c5);
if(_40c5!=null){
    var eventPortName = ['input2','output2'];
    var eventPortType = ['InputPort','OutputPort'];
    var eventPositionX= [this.width/2,this.width/2];
    var eventPositionY= [0,this.height];

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

bpmnEventBoundaryInter.prototype.getContextMenu=function(){
if(this.id != null){
   this.workflow.handleContextMenu(this);
}
};
