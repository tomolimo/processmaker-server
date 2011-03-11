bpmnEventTimerStart=function(){
  VectorFigure.call(this);
};

bpmnEventTimerStart.prototype=new VectorFigure;
bpmnEventTimerStart.prototype.type="bpmnEventTimerStart";
bpmnEventTimerStart.prototype.paint=function(){
  VectorFigure.prototype.paint.call(this);
  if(typeof workflow.zoomfactor == 'undefined')
    workflow.zoomfactor = 1;

  //Set the Limitation
  if(typeof this.limitFlag == 'undefined' || this.limitFlag == false) {
    this.originalWidth = 30;
    this.originalHeight = 30;
    this.orgXPos = this.getX();
    this.orgYPos = this.getY();
    this.orgFontSize =this.fontSize;
  }
  this.width  = this.originalWidth  * workflow.zoomfactor;
  this.height = this.originalHeight * workflow.zoomfactor;
  
  var x_cir = 0;
  var y_cir = 0;
  
  //draw the circle  
  this.graphics.setColor("#d0d0d0");
  this.graphics.fillEllipse(x_cir+2,y_cir+2,this.getWidth(),this.getHeight());
  this.graphics.setColor( "#F6FFDA" );
  this.graphics.fillEllipse(x_cir,y_cir,this.getWidth(),this.getHeight());
  this.graphics.setStroke(1);
  this.graphics.setColor("#97C759");
  this.graphics.drawEllipse(x_cir,y_cir,this.getWidth(),this.getHeight());
  this.graphics.setStroke(1);
  this.graphics.setColor("#98C951");
  //this.graphics.drawEllipse(x_cir,y_cir,this.getWidth(),this.getHeight());

  //draw the clock
  var cw = this.getWidth();
  var ch = this.getHeight();
  this.graphics.setColor("#98C951");
  this.graphics.drawEllipse(cw*0.15, ch*0.15, ch*0.7, ch*0.7 );

  var x = new Array( cw*0.60, cw*0.50, cw*0.75, 0.5);
  var y = new Array( ch*0.31, ch*0.50, ch*0.50, 0.5);
  this.graphics.setColor("#4aa533");
  //this.graphics.drawPolygon(x,y);
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
//  this.graphics.drawLine(22,8,20,10);  //10th min line
//  this.graphics.drawLine(20,15,24,15);  //15th min line
//  this.graphics.drawLine(18,20,22,22);  //25th min line
//  this.graphics.drawLine(15,25,15,21);  //30th min line
//  this.graphics.drawLine(7,21,11,19);  //40th min line
//  this.graphics.drawLine(5,15,10,15);  //45th min line
//  this.graphics.drawLine(6,8,10,10);  //50th min line
//  this.graphics.drawLine(15,5,15,8);  //60th min line


  //this.graphics.setColor("#4aa533");
  //this.graphics.drawEllipse(x_cir2,y_cir2,this.getWidth()-10,this.getHeight()-10);
  //this.graphics.drawLine(this.getWidth()/2,this.getHeight()/2,this.getWidth()/1.3,this.getHeight()/2);   //horizontal line
  //this.graphics.drawLine(this.getWidth()/2,this.getHeight()/2,this.getWidth()/2,this.getHeight()/4.5);    //vertical line
  this.graphics.paint();

  //Code Added to Dynamically shift Ports on resizing of shapes/
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

bpmnEventTimerStart.prototype.setWorkflow=function(_40c5){
VectorFigure.prototype.setWorkflow.call(this,_40c5);
if(_40c5!=null){
    var eventPortName = ['output1','output2'];
    var eventPortType = ['OutputPort','OutputPort'];
    var eventPositionX= [this.width/2,this.width];
    var eventPositionY= [this.height,this.height/2];

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

bpmnEventTimerStart.prototype.getContextMenu=function(){
if(this.id != null){
   this.workflow.handleContextMenu(this);
}
};
