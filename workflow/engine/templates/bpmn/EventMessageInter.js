bpmnEventMessageInter=function(){
VectorFigure.call(this);
//Setting width and height values as per the zoom ratio
if(typeof workflow.zoomWidth != 'undefined' || typeof workflow.zoomHeight != 'undefined')
      this.setDimension(workflow.zoomWidth, workflow.zoomHeight);
else
    this.setDimension(30,30);
this.stroke=2;
};
bpmnEventMessageInter.prototype=new VectorFigure;
bpmnEventMessageInter.prototype.type="bpmnEventMessageInter";
bpmnEventMessageInter.prototype.paint=function(){
VectorFigure.prototype.paint.call(this);

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
this.graphics.drawEllipse(x_cir2,y_cir2,this.getWidth()-6,this.getHeight()-6);
//var x=new Array(12,12,32,32,22,12,32);
//var y=new Array(14,31,31,14,23,14,14);
var x=new Array(this.getWidth()/3.75,this.getWidth()/3.75,this.getWidth()/1.28,this.getWidth()/1.28,this.getWidth()/2,this.getWidth()/3.75,this.getWidth()/3.75);
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
this.graphics.drawPolygon(x_tri,y_tri);
this.graphics.paint();
};

bpmnEventMessageInter.prototype.getContextMenu=function(){
if(this.id != null){
   this.workflow.handleContextMenu(this);
}
};
