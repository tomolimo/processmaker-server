bpmnEventEndSignal=function(){
VectorFigure.call(this);
this.setDimension(30,30);
this.stroke=3;
};
bpmnEventEndSignal.prototype=new VectorFigure;
bpmnEventEndSignal.prototype.type="bpmnEventEndSignal";
bpmnEventEndSignal.prototype.paint=function(){
VectorFigure.prototype.paint.call(this);
var x=0;
var y=0;
this.graphics.setStroke(this.stroke);
this.graphics.setColor( "#ffffff" );
this.graphics.fillEllipse(x,y,this.getWidth(),this.getHeight());
this.graphics.setColor("#000000");
this.graphics.drawEllipse(x,y,this.getWidth(),this.getHeight());
//var x=new Array(8,38,22);
//var y=new Array(30,30,4);
var x=new Array(this.getWidth()/5.62,this.getWidth()/1.18,this.getWidth()/2.04);
var y=new Array(this.getHeight()/1.5,this.getHeight()/1.5,this.getHeight()/11.25);
this.graphics.setColor("#000000");
this.graphics.fillPolygon(x,y);
this.graphics.setColor("#000000");
this.graphics.drawPolygon(x,y);
this.graphics.paint();
};


bpmnEventEndSignal.prototype.getContextMenu=function(){
if(this.id != null){
    this.workflow.AddEventContextMenu(this.id);
}
};
