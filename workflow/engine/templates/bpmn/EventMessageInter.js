bpmnEventMessageInter=function(){
VectorFigure.call(this);
this.stroke=1;
};
bpmnEventMessageInter.prototype=new VectorFigure;
bpmnEventMessageInter.prototype.type="bpmnEventMessageInter";
bpmnEventMessageInter.prototype.paint=function(){
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
var cw = this.getWidth();
var ch = this.getHeight();
this.graphics.drawEllipse(cw*0.15, ch*0.15, ch*0.7, ch*0.7);
//var x=new Array(12,12,32,32,22,12,32);
//var y=new Array(14,31,31,14,23,14,14);
/*var x=new Array(this.getWidth()/3.75,this.getWidth()/3.75,this.getWidth()/1.28,this.getWidth()/1.28,this.getWidth()/2,this.getWidth()/3.75,this.getWidth()/3.75);
var y=new Array(this.getHeight()/3.21,this.getHeight()/1.36,this.getHeight()/1.36,this.getHeight()/2.64,this.getHeight()/1.73,this.getHeight()/2.64);
this.graphics.setStroke(1);
this.graphics.setColor( "#adae5e" );
//this.graphics.fillPolygon(x,y);
this.graphics.setColor("#adae5e");
this.graphics.drawPolygon(x,y);
//var x_tri=new Array(12,23.5,35);
//var y_tri=new Array(13,22,13);
var x_tri=new Array(this.getWidth()/3.75,this.getWidth()/1.91,this.getWidth()/1.28);
var y_tri=new Array(this.getHeight()/3.46,this.getHeight()/2.04,this.getHeight()/3.46);
this.graphics.setColor( "#adae5e" );
//this.graphics.fillPolygon(x_tri,y_tri);
this.graphics.setColor("#adae5e");
this.graphics.drawPolygon(x_tri,y_tri);*/

//draw the mail icon
  var cw = this.getWidth();
  var ch = this.getHeight();
  var x = new Array( cw*0.25, cw*0.25, cw*0.78, cw*0.78, cw*0.52, cw*0.25, cw*0.25, cw*0.78);
  var y = new Array( ch*0.31, ch*0.71, ch*0.71, ch*0.32, ch*0.52, ch*0.32, ch*0.31, ch*0.31);
  this.graphics.setColor("#4aa533");
  this.graphics.drawPolygon(x,y);
  
this.graphics.paint();
};

bpmnEventMessageInter.prototype.getContextMenu=function(){
if(this.id != null){
   this.workflow.handleContextMenu(this);
}
};
