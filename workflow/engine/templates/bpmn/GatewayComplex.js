bpmnGatewayComplex=function(width,_30ab){
VectorFigure.call(this);
//Setting width and height values as per the zoom ratio
if(typeof workflow.zoomWidth != 'undefined' || typeof workflow.zoomHeight != 'undefined')
      this.setDimension(workflow.zoomWidth+10, workflow.zoomHeight+10);
else
    this.setDimension(40,40);
this.stroke=2;
};
bpmnGatewayComplex.prototype=new VectorFigure;
bpmnGatewayComplex.prototype.type="bpmnGatewayComplex";
bpmnGatewayComplex.prototype.paint=function(){
VectorFigure.prototype.paint.call(this);
var x=new Array(0,this.width/2,this.width,this.width/2);
var y=new Array(this.height/2,this.height,this.height/2,0);

var x2 = new Array();
var y2 = new Array();

for(var i=0;i<x.length;i++){
x2[i]=x[i]+4;
y2[i]=y[i]+1;
}
this.graphics.setStroke(this.stroke);
this.graphics.setColor( "#c0c0c0" );
this.graphics.fillPolygon(x2,y2);
this.graphics.setStroke(this.stroke);
this.graphics.setColor( "#fdf3e0" );
this.graphics.fillPolygon(x,y);
this.graphics.setColor("#a27628");
this.graphics.drawPolygon(x,y);
/*var x_cross=new Array(5,15,25,30,20,30,25,15,5,0,10,0);
var y_cross=new Array(30,20,30,25,15,5,0,10,0,5,15,25);
this.graphics.setColor( "#000000" );
this.graphics.fillPolygon(x_cross,y_cross);
var x_plus =new Array(10,20,20,30,30,20,20,10,10,0,0,10);
var y_plus=new Array(30,30,20,20,10,10,0,0,10,10,20,20);
this.graphics.fillPolygon(x_plus,y_plus);*/
this.graphics.setStroke(4);
this.graphics.drawLine(this.getWidth()/4.5,this.getHeight()/2,this.getWidth()/1.3,this.getHeight()/2);   //horizontal line
this.graphics.drawLine(this.getWidth()/3,this.getHeight()/1.5,this.getWidth()/1.5,this.getHeight()/3);   //cross line
this.graphics.drawLine(this.getWidth()/2,this.getHeight()/1.3,this.getWidth()/2,this.getHeight()/4.5);    //vertical line
this.graphics.drawLine(this.getWidth()/1.5,this.getHeight()/1.5,this.getWidth()/3,this.getHeight()/3);   //cross line
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

};

bpmnGatewayComplex.prototype.setWorkflow=function(_40c5){
VectorFigure.prototype.setWorkflow.call(this,_40c5);
if(_40c5!=null){
    var gatewayPortName = ['input1','input2','output1','output2'];
    var gatewayPortType = ['InputPort','InputPort','OutputPort','OutputPort'];
    var gatewayPositionX= [0,this.width/2,this.height/2,this.width];
    var gatewayPositionY= [this.height/2,0,this.width,this.height/2];

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


bpmnGatewayComplex.prototype.getContextMenu=function(){
if(this.id != null){
   this.workflow.handleContextMenu(this);
}
};
