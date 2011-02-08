bpmnEventEmptyStart=function(){
VectorFigure.call(this);
//Setting width and height values as per the zoom ratio
if(typeof workflow.zoomWidth != 'undefined' || typeof workflow.zoomHeight != 'undefined')
      this.setDimension(workflow.zoomWidth, workflow.zoomHeight);
else
    this.setDimension(30,30);

//Setting width and height values as per the zoom ratio
if(typeof workflow.zoomType != 'undefined')
{
   var zoomWidth = 30 * workflow.zoomType;
   var zoomHeight = 30 * workflow.zoomType;
   this.setDimension(zoomWidth, zoomHeight);
}
this.stroke=2;
};
bpmnEventEmptyStart.prototype=new VectorFigure;
bpmnEventEmptyStart.prototype.type="bpmnEventEmptyStart";
bpmnEventEmptyStart.prototype.paint=function(){
VectorFigure.prototype.paint.call(this);
if ((this.getWidth() < 30 || this.getHeight() < 30 ) && this.limitFlag != true) {
        this.setDimension(30, 30);
 }

var x_cir = 0;
var y_cir = 0;

this.graphics.setColor("#c0c0c0");
this.graphics.fillEllipse(x_cir+4,y_cir+4,this.getWidth(),this.getHeight());
this.graphics.setStroke(this.stroke);
this.graphics.setColor( "#e4f7df" );
this.graphics.fillEllipse(x_cir,y_cir,this.getWidth(),this.getHeight());
this.graphics.setColor("#4aa533");
this.graphics.drawEllipse(x_cir,y_cir,this.getWidth(),this.getHeight());
this.graphics.paint();

  
/*Code Added to Dynamically shift Ports on resizing of shapes
 **/
if(this.output1!=null){
this.output1.setPosition(this.width/2,this.height);
}
if(this.output2!=null){
this.output2.setPosition(this.width,this.height/2);
}
};

bpmnEventEmptyStart.prototype.setWorkflow=function(_40c5){
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

bpmnEventEmptyStart.prototype.getContextMenu=function(){
if(this.id != null){
    this.workflow.handleContextMenu(this);
}
};

