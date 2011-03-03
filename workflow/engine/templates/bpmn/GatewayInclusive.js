bpmnGatewayInclusive=function(width,_30ab){
  VectorFigure.call(this);
  this.stroke =1;
};

bpmnGatewayInclusive.prototype=new VectorFigure;
bpmnGatewayInclusive.prototype.type="bpmnGatewayInclusive";
bpmnGatewayInclusive.prototype.paint=function(){
  VectorFigure.prototype.paint.call(this);
  if(typeof workflow.zoomfactor == 'undefined')
    workflow.zoomfactor = 1;

  //Set the Task Limitation
  if(typeof this.limitFlag == 'undefined' || this.limitFlag == false)
  {
    this.originalWidth  = 40;
    this.originalHeight = 40;
    this.orgXPos = this.getX();
    this.orgYPos = this.getY();
    this.orgFontSize =this.fontSize;
  }

  this.width  = this.originalWidth * workflow.zoomfactor;
  this.height = this.originalHeight  * workflow.zoomfactor;

  var cw = this.getWidth();
  var ch = this.getHeight();
  var x=new Array(0,cw*0.5,cw,cw*0.5);
  var y=new Array(ch*0.5,ch,ch*0.5,0);
  //var x=new Array(0,this.width/2,this.width,this.width/2);
  //var y=new Array(this.height/2,this.height,this.height/2,0);
  
  var x2 = new Array();
  var y2 = new Array();
  
  for(var i=0;i<x.length;i++){
    x2[i]=x[i]+4;
    y2[i]=y[i]+1;
  }
  this.graphics.setStroke(this.stroke);
  this.graphics.setColor( "#c0c0c0" );
  this.graphics.fillPolygon(x2,y2);
  this.graphics.setStroke(1);
  this.graphics.setColor( "#ffffe5" );
  this.graphics.fillPolygon(x,y);
  this.graphics.setColor("#c8c865");
  this.graphics.drawPolygon(x,y);
  var x_cir = 15;
  var y_cir = 15;

  this.graphics.setColor("#c8c865");
  this.graphics.drawEllipse(this.getWidth()/4,this.getHeight()/4,this.getWidth()/2,this.getHeight()/2);
  this.graphics.paint();

  if (this.input1 != null) {
    this.input1.setPosition(0, this.height / 2);
  }
  if (this.input2 != null) {
    this.input2.setPosition(this.width / 2, 0);
  }
  if (this.output1 != null) {
    this.output1.setPosition(this.height / 2, this.width);
  }
  if (this.output2 != null) {
    this.output2.setPosition(this.width, this.height / 2);
  }
  if (this.output3 != null) {
  	this.output3.setPosition(0, this.height /2 );
  } 
};

bpmnGatewayInclusive.prototype.setWorkflow=function(_40c5){
  VectorFigure.prototype.setWorkflow.call(this,_40c5);
  if(_40c5!=null){
  	var h2 = this.height/2;
  	var w2 = this.width/2;
  	
    var gatewayPortName = ['output1',   'output2',   'output3',   'input1',   'input2'   ];
    var gatewayPortType = ['OutputPort','OutputPort','OutputPort','InputPort','InputPort'];
    var gatewayPositionX= [w2,         this.width, 0 ,  0,  w2 ];
    var gatewayPositionY= [this.width, h2,         h2,  h2, 0  ];

    for(var i=0; i< gatewayPortName.length ; i++){
      eval('this.'+gatewayPortName[i]+' = new '+gatewayPortType[i]+'()');                               //Create New Port
      eval('this.'+gatewayPortName[i]+'.setWorkflow(_40c5)');                                        //Add port to the workflow
      eval('this.'+gatewayPortName[i]+'.setName("'+gatewayPortName[i]+'")');                            //Set PortName
      eval('this.'+gatewayPortName[i]+'.setZOrder(-1)');                                             //Set Z-Order of the port to -1. It will be below all the figure
      eval('this.'+gatewayPortName[i]+'.setBackgroundColor(new Color(255, 255, 255))');              //Setting Background of the port to white
      eval('this.'+gatewayPortName[i]+'.setColor(new Color(255, 255, 255))');                        //Setting Border of the port to white
      eval('this.addPort(this.'+gatewayPortName[i]+','+gatewayPositionX[i]+', '+gatewayPositionY[i]+')');  //Setting Position of the port
    }
  }
};


bpmnGatewayInclusive.prototype.getContextMenu=function(){
  if(this.id != null){
    this.workflow.handleContextMenu(this);
  }
};
