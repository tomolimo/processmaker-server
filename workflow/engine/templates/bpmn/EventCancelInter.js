bpmnEventCancelInter=function(){
VectorFigure.call(this);
//Setting width and height values as per the zoom ratio
if(typeof workflow.zoomWidth != 'undefined' || typeof workflow.zoomHeight != 'undefined')
      this.setDimension(workflow.zoomWidth, workflow.zoomHeight);
else
    this.setDimension(45,45);
this.stroke=2;
};
bpmnEventCancelInter.prototype=new VectorFigure;
bpmnEventCancelInter.prototype.type="bpmnEventCancelInter";
bpmnEventCancelInter.prototype.paint=function(){
VectorFigure.prototype.paint.call(this);
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
var x=new Array(this.getWidth()/2.8,this.getWidth()/1.95,this.getWidth()/1.45,this.getWidth()/1.25,this.getWidth()/1.55,this.getWidth()/1.21,this.getWidth()/1.4,this.getWidth()/1.95,this.getWidth()/2.8,this.getWidth()/4.1,this.getWidth()/2.5,this.getWidth()/4.1);
var y=new Array(this.getHeight()/1.28,this.getHeight()/1.66,this.getHeight()/1.36,this.getHeight()/1.55,this.getHeight()/2.04,this.getHeight()/3.21,this.getHeight()/5.6,this.getHeight()/2.81,this.getHeight()/5.6,this.getHeight()/3.21,this.getHeight()/2.04,this.getHeight()/1.55);
this.graphics.setColor("#ffffff");
this.graphics.fillPolygon(x,y);
this.graphics.setColor("#000000");
this.graphics.drawPolygon(x,y);
this.graphics.paint();
};


