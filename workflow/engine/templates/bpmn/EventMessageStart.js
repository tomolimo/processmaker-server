bpmnEventMessageStart=function(){
  VectorFigure.call(this);
};

bpmnEventMessageStart.prototype=new VectorFigure;
bpmnEventMessageStart.prototype.type="bpmnEventMessageStart";
bpmnEventMessageStart.prototype.paint=function(){
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
  this.graphics.drawEllipse(x_cir,y_cir,this.getWidth(),this.getHeight());

  //draw the mail icon
  var cw = this.getWidth();
  var ch = this.getHeight();
  var x = new Array( cw*0.25, cw*0.25, cw*0.78, cw*0.78, cw*0.52, cw*0.25, cw*0.25, cw*0.78);
  var y = new Array( ch*0.31, ch*0.71, ch*0.71, ch*0.32, ch*0.52, ch*0.32, ch*0.31, ch*0.31);
  this.graphics.setColor("#4aa533");
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

bpmnEventMessageStart.prototype.setWorkflow=function(_40c5){
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

bpmnEventMessageStart.prototype.getContextMenu=function(){
  if(this.id != null){
    this.workflow.handleContextMenu(this);
  }
};

