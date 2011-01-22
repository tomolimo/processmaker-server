bpmnEventCancelEnd=function(){
VectorFigure.call(this);
//Setting width and height values as per the zoom ratio
if(typeof workflow.zoomWidth != 'undefined' || typeof workflow.zoomHeight != 'undefined')
      this.setDimension(workflow.zoomWidth, workflow.zoomHeight);
else
    this.setDimension(30,30);
this.stroke=3;
};
bpmnEventCancelEnd.prototype=new VectorFigure;
bpmnEventCancelEnd.prototype.type="bpmnEventCancelEnd";
bpmnEventCancelEnd.prototype.paint=function(){
VectorFigure.prototype.paint.call(this);
 //Set the Task Limitation
    if (this.getWidth() < 30 || this.getHeight() < 30) {
        this.setDimension(30, 30);
 }

this.graphics.setStroke(this.stroke);
var x_cir = 0;
var y_cir = 0;

this.graphics.setColor("#c0c0c0");
this.graphics.fillEllipse(x_cir+5,y_cir+5,this.getWidth(),this.getHeight());
this.graphics.setColor("#f7f1e5");
this.graphics.fillEllipse(x_cir,y_cir,this.getWidth(),this.getHeight());
this.graphics.setColor("#c46508");
this.graphics.drawEllipse(x_cir,y_cir,this.getWidth(),this.getHeight());
this.graphics.setStroke(2);
//var x=new Array(16,23,31,36,29,37,32,23,16,11,18,11);
//var y=new Array(35,27,33,29,22,14,9,16,9,14,22,29);
var x=new Array(this.getWidth()/2.8,this.getWidth()/1.95,this.getWidth()/1.45,this.getWidth()/1.25,this.getWidth()/1.55,this.getWidth()/1.21,this.getWidth()/1.4,this.getWidth()/1.95,this.getWidth()/2.8,this.getWidth()/4.1,this.getWidth()/2.5,this.getWidth()/4.1);
var y=new Array(this.getHeight()/1.28,this.getHeight()/1.66,this.getHeight()/1.36,this.getHeight()/1.55,this.getHeight()/2.04,this.getHeight()/3.21,this.getHeight()/5.6,this.getHeight()/2.81,this.getHeight()/5.6,this.getHeight()/3.21,this.getHeight()/2.04,this.getHeight()/1.55);
this.graphics.setColor("#c46508");
this.graphics.fillPolygon(x,y);
this.graphics.setColor("#c46508");
//this.graphics.drawPolygon(x,y);
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

bpmnEventCancelEnd.prototype.setWorkflow=function(_40c5){
VectorFigure.prototype.setWorkflow.call(this,_40c5);
if(_40c5!=null){
    var eventPortName = ['input1','input2'];
    var eventPortType = ['InputPort','InputPort'];
    var eventPositionX= [this.width/2,0];
    var eventPositionY= [0,this.height/2];

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

bpmnEventCancelEnd.prototype.getContextMenu=function(){
if(this.id != null){
    this.workflow.handleContextMenu(this);
}
};
