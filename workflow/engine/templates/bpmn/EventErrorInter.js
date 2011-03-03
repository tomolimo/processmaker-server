bpmnEventErrorInter=function(){
VectorFigure.call(this);
this.stroke=1;
};
bpmnEventErrorInter.prototype=new VectorFigure;
bpmnEventErrorInter.prototype.type="bpmnEventErrorInter";
bpmnEventErrorInter.prototype.paint=function(){
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
this.graphics.setColor("#000000");
this.graphics.drawEllipse(x_cir,y_cir,this.getWidth(),this.getHeight());
var x_cir2=5;
var y_cir2=5;
this.graphics.setColor("#000000");
this.graphics.drawEllipse(x_cir2,y_cir2,this.getWidth()-10,this.getHeight()-10);
//var x=new Array(7,17,24,34,24,17);
//var y=new Array(33,23,33,13,26,16);
var cw = this.getWidth();
var ch = this.getHeight();
var x=new Array(cw*0.15,cw*0.38,cw*0.53,cw*0.75,cw*0.53,cw*0.38);
var y=new Array(ch*0.73,ch*0.51,ch*0.73,ch*0.28,ch*0.57,ch*0.35);
//var x=new Array(this.getWidth()/6.4,this.getWidth()/2.6,this.getWidth()/1.87,this.getWidth()/1.32,this.getWidth()/1.87,this.getWidth()/2.6);
//var y=new Array(this.getHeight()/1.36,this.getHeight()/1.95,this.getHeight()/1.36,this.getHeight()/3.46,this.getHeight()/1.73,this.getHeight()/2.8);
this.graphics.setColor("#ffffff");
this.graphics.fillPolygon(x,y);
this.graphics.setColor("#000000");
this.graphics.drawPolygon(x,y);
this.graphics.paint();
};


