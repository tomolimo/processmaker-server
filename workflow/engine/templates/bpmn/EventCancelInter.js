bpmnEventCancelInter=function(){
VectorFigure.call(this);
this.stroke=1;
};
bpmnEventCancelInter.prototype=new VectorFigure;
bpmnEventCancelInter.prototype.type="bpmnEventCancelInter";
bpmnEventCancelInter.prototype.paint=function(){
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
//var x=new Array(16,23,31,36,29,37,32,23,16,11,18,11);
//var y=new Array(35,27,33,29,22,14,9,16,9,14,22,29);
var cw = this.getWidth();
var ch = this.getHeight();
var x=new Array(cw*0.35,cw*0.51,cw*0.68,cw*0.8,cw*0.64,cw*0.82,cw*0.71,cw*0.51,cw*0.35,cw*0.24,cw*0.4,cw*0.24);
var y=new Array(ch*0.78,ch*0.6,ch*0.73,ch*0.64,ch*0.49,ch*0.31,ch*0.17,ch*0.35,ch*0.17,ch*0.31,ch*0.49,ch*0.64);
//var x=new Array(cw/2.8,cw/1.95,cw/1.45,cw/1.25,cw/1.55,cw/1.21,cw/1.4,cw/1.95,cw/2.8,cw/4.1,cw/2.5,cw/4.1);
//var y=new Array(ch/1.28,ch/1.66,ch/1.36,ch/1.55,ch/2.04,ch/3.21,ch/5.6,ch/2.81,ch/5.6,ch/3.21,ch/2.04,ch/1.55);
this.graphics.setColor("#ffffff");
this.graphics.fillPolygon(x,y);
this.graphics.setColor("#000000");
this.graphics.drawPolygon(x,y);
this.graphics.paint();
};


