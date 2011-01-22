bpmnEventLinkEnd=function(){
VectorFigure.call(this);
//Setting width and height values as per the zoom ratio
if(typeof workflow.zoomWidth != 'undefined' || typeof workflow.zoomHeight != 'undefined')
      this.setDimension(workflow.zoomWidth, workflow.zoomHeight);
else
    this.setDimension(45,45);
this.stroke=3;
};
bpmnEventLinkEnd.prototype=new VectorFigure;
bpmnEventLinkEnd.prototype.type="bpmnEventLinkEnd";
bpmnEventLinkEnd.prototype.paint=function(){
VectorFigure.prototype.paint.call(this);
var x_cir = -4;
var y_cir = -4;
this.graphics.setStroke(this.stroke);
this.graphics.setColor("#000000");
this.graphics.drawEllipse(x_cir,y_cir,this.getWidth(),this.getHeight());
//var x_arrow=new Array(4,4,22,22,37,22,22); //Arrow working
//var y_arrow=new Array(11,26,26,31,18.5,6,11);
var x_arrow=new Array(4,4,this.getWidth()/2,this.getWidth()/2,this.getWidth()/1.2,this.getWidth()/2,this.getWidth()/2);
var y_arrow=new Array(this.getHeight()/4,this.getHeight()/1.7,this.getHeight()/1.7,this.getHeight()/1.5,this.getHeight()/2.5,this.getHeight()/7,this.getHeight()/4);
this.graphics.setColor( "#000000" );
this.graphics.fillPolygon(x_arrow,y_arrow);
this.graphics.setColor("#000000");
this.graphics.drawPolygon(x_arrow,y_arrow);
this.graphics.paint();
};


