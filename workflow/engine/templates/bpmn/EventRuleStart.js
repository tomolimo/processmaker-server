bpmnEventRuleStart=function(){
VectorFigure.call(this);
//Setting width and height values as per the zoom ratio
if(typeof workflow.zoomWidth != 'undefined' || typeof workflow.zoomHeight != 'undefined')
      this.setDimension(workflow.zoomWidth, workflow.zoomHeight);
else
    this.setDimension(30,30);
this.stroke = 2;
};
bpmnEventRuleStart.prototype=new VectorFigure;
bpmnEventRuleStart.prototype.type="bpmnEventRuleStart";
bpmnEventRuleStart.prototype.paint=function(){
VectorFigure.prototype.paint.call(this);
var x_cir = 0;
var y_cir = 0;

this.graphics.setColor("#c0c0c0");
this.graphics.fillEllipse(x_cir+5,y_cir+5,this.getWidth(),this.getHeight());
this.graphics.setStroke(this.stroke);
this.graphics.setColor( "#e4f7df");
this.graphics.fillEllipse(x_cir,y_cir,this.getWidth(),this.getHeight());
this.graphics.setColor("#4aa533");
this.graphics.drawEllipse(x_cir,y_cir,this.getWidth(),this.getHeight());
this.graphics.setStroke(1);
var x=new Array(12,12,this.getWidth()-10,this.getWidth()-10);
var y=new Array(10,this.getHeight()-10,this.getHeight()-10,10);
//this.graphics.setColor("#adae5e");
//this.graphics.fillPolygon(x,y);
this.graphics.setColor("#4aa533");
this.graphics.drawPolygon(x,y);
this.graphics.drawLine(14,this.getHeight()/1.5,this.getWidth()-10,this.getHeight()/1.5);
this.graphics.drawLine(14,this.getHeight()/2,this.getWidth()-10,this.getHeight()/2);
this.graphics.drawLine(14,this.getHeight()/3,this.getWidth()-10,this.getHeight()/3);
this.graphics.drawLine(14,this.getHeight()/4,this.getWidth()-10,this.getHeight()/4);
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

bpmnEventRuleStart.prototype.setWorkflow=function(_40c5){
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

bpmnEventRuleStart.prototype.getContextMenu=function(){
if(this.id != null){
   this.workflow.handleContextMenu(this);
}
};
