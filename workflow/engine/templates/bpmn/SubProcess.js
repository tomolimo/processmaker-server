bpmnSubProcess = function (_30ab) {
  VectorFigure.call(this);
  //Setting width and height values as per the zoom ratio
  if(typeof workflow.zoomTaskWidth != 'undefined' || typeof workflow.zoomTaskHeight != 'undefined')
    this.setDimension(workflow.zoomTaskWidth, workflow.zoomTaskHeight);
  else
    this.setDimension(165, 50);
  this.subProcessName = _30ab.subProcessName; //It will set the Default Task Name with appropriate count While dragging a task on the canvas
};

bpmnSubProcess.prototype = new VectorFigure;
bpmnSubProcess.prototype.type = "bpmnSubProcess"
bpmnSubProcess.prototype.setSubProcessName = function () {
  this.subProcessName = 'Sub Process';
};

bpmnSubProcess.prototype.coord_converter = function (bound_width, bound_height, text_length) {
  //bound_width = this.workflow.currentSelection.width;
  //bound_height = this.workflow.currentSelection.height;
  input_width = text_length * 6
  input_height = 10

  temp_width = bound_width - input_width;
  temp_width /= 2;
  temp_x = temp_width;

  temp_height = bound_height - 10;
  temp_height /= 2;
  temp_y = temp_height;

  var temp_coord = new Object();
  temp_coord.temp_x = temp_x;
  temp_coord.temp_y = temp_y;
  return temp_coord;
};

//curWidth = this.getWidth();

bpmnSubProcess.prototype.paint = function () {
  VectorFigure.prototype.paint.call(this);
  if(typeof workflow.zoomfactor == 'undefined')
  workflow.zoomfactor = 1;
  //For Zooming

  if(typeof this.limitFlag == 'undefined' || this.limitFlag == false)
  {
    this.originalWidth = 165;
    this.originalHeight = 40;
    this.orgXPos = this.getX();
    this.orgYPos = this.getY();
    this.orgFontSize =this.fontSize;
  }

  this.width  = this.originalWidth  * workflow.zoomfactor;
  this.height = this.originalHeight * workflow.zoomfactor;
  
  var x = new Array(6, this.getWidth() - 3, this.getWidth(), this.getWidth(), this.getWidth() - 3, 6, 3, 3, 6);
  var y = new Array(3, 3, 6, this.getHeight() - 3, this.getHeight(), this.getHeight(), this.getHeight() - 3, 6, 3);
  this.graphics.setStroke(this.stroke);
  this.graphics.setColor("#c0c0c0");
  this.graphics.fillPolygon(x, y);
  for (var i = 0; i < x.length; i++) {
    x[i] = x[i] - 3;
    y[i] = y[i] - 3;
  }
  this.graphics.setColor("#ffffff");
  this.graphics.fillPolygon(x, y);
  this.graphics.setColor("#5164b5"); //Blue Color
  this.graphics.drawPolygon(x, y);

  //Circle on right top corner
  //var x_cir = this.getWidth()/1.3;
  //var y_cir = this.getHeight()/8.75;
  //
  //this.graphics.setColor("#ffffff");
  //this.graphics.fillEllipse(x_cir,y_cir,this.getHeight()/5,this.getHeight()/5);
  //this.graphics.setColor("#5891B7");
  //this.graphics.setStroke(2);
  //this.graphics.drawEllipse(x_cir,y_cir,this.getHeight()/5,this.getHeight()/5);
    
  //Plus symbol on bottom
  //Plus symbol on bottom
  var zoomfactor = workflow.zoomfactor;
  var xb = this.getWidth()/2 - 5*zoomfactor;
  var yb = this.getHeight() - 5 - 11*zoomfactor;
  var x1 = new Array(xb, xb                , xb + 11.5*zoomfactor, xb + 11.5*zoomfactor );
  var y1 = new Array(yb, yb + 11*zoomfactor, yb + 11*zoomfactor, yb );
  
  this.graphics.setStroke(1);
  this.graphics.setColor("#5891B7");
  this.graphics.drawPolygon(x1,y1);
  var x_cross = new Array(xb+ 5*zoomfactor, xb+ 6*zoomfactor, xb+ 6*zoomfactor, xb+ 9*zoomfactor, xb+ 9*zoomfactor, xb+ 6*zoomfactor, xb+ 6*zoomfactor, xb+ 5*zoomfactor, xb+ 5*zoomfactor, xb+ 2*zoomfactor, xb+ 2*zoomfactor, xb+ 5*zoomfactor );
  var y_cross = new Array(yb+ 2*zoomfactor, yb+ 2*zoomfactor, yb+ 5*zoomfactor, yb+ 5*zoomfactor, yb+ 6*zoomfactor, yb+ 6*zoomfactor, yb+ 9*zoomfactor, yb+ 9*zoomfactor, yb+ 6*zoomfactor, yb+ 6*zoomfactor, yb+ 5*zoomfactor, yb+ 5*zoomfactor );
  this.graphics.setColor( "#5891B7" );
  this.graphics.fillPolygon(x_cross,y_cross);
  this.graphics.paint();

  var bpmnText = new jsGraphics(this.id);
  var padleft = 0.025*this.getWidth();
  var padtop = 0.15*this.getHeight();
  var rectwidth = this.getWidth() - 3*padleft;
  var rectheight = this.getHeight() - 3*padtop;


  if(typeof this.fontSize == 'undefined' || this.fontSize == '')
    this.fontSize = 11;
  var fontSize = zoomfactor * this.fontSize;
  bpmnText.setFont('verdana', +fontSize+'px', Font.PLAIN);
  bpmnText.drawStringRect(this.subProcessName,padleft,padtop,rectwidth,rectheight,'center');
  bpmnText.paint();
  
  this.bpmnNewText = bpmnText;

   if (this.input1 != null) {
      this.input1.setPosition(0, this.height / 2 -1);
  }
  if (this.output1 != null) {
      this.output1.setPosition(this.width / 2, this.height);
  }
  if (this.input2 != null) {
      this.input2.setPosition(this.width / 2, 0);
  }
  if (this.output2 != null) {
      this.output2.setPosition(this.width, this.height / 2-1);
  }

};

  jsGraphics.prototype.drawTextString = function (txt, x, y, dx, dy) {
    this.htm += '<div style="position:absolute; display:table-cell; vertical-align:middle; height:' + y + '; width:' + x + ';' + 'margin-left:' + dx + 'px;' + 'margin-top:' + dy + 'px;' + 'font-family:' + this.ftFam + ';' + 'font-size:' + this.ftSz + ';' + 'color:' + this.color + ';' + this.ftSty + '">' + txt + '<\/div>';
};

bpmnSubProcess.prototype.setWorkflow = function (_40c5) {
    VectorFigure.prototype.setWorkflow.call(this, _40c5);
    if (_40c5 != null) {
        /*Adding Port to the Task After dragging Task on the Canvas
         *Ports will be invisibe After Drag and Drop, But It will be created
         */
        var TaskPortName = ['output1','output2','input1','input2'];
        var TaskPortType = ['OutputPort','OutputPort','InputPort','InputPort'];
        var TaskPositionX= [this.width/2,this.width,0,this.width/2];
        var TaskPositionY= [this.height,this.height/2,this.height/2,0];

        for(var i=0; i< TaskPortName.length ; i++){
            eval('this.'+TaskPortName[i]+' = new '+TaskPortType[i]+'()');                               //Create New Port
            eval('this.'+TaskPortName[i]+'.setWorkflow(_40c5)');                                        //Add port to the workflow
            eval('this.'+TaskPortName[i]+'.setName("'+TaskPortName[i]+'")');                            //Set PortName
            eval('this.'+TaskPortName[i]+'.setZOrder(-1)');                                             //Set Z-Order of the port to -1. It will be below all the figure
            eval('this.'+TaskPortName[i]+'.setBackgroundColor(new Color(255, 255, 255))');              //Setting Background of the port to white
            eval('this.'+TaskPortName[i]+'.setColor(new Color(255, 255, 255))');                        //Setting Border of the port to white
            eval('this.addPort(this.'+TaskPortName[i]+','+TaskPositionX[i]+', '+TaskPositionY[i]+')');  //Setting Position of the port
        }
    }
};

bpmnSubProcessDialog = function (_2e5e) {
    this.figure = _2e5e;
    var title = 'Sub Process';
    Dialog.call(this, title);
    this.setDimension(400, 150); //Set the width and height of the Dialog box
}

bpmnSubProcessDialog.prototype = new Dialog();
bpmnSubProcessDialog.prototype.createHTMLElement = function () {
    var item = Dialog.prototype.createHTMLElement.call(this);
    var inputDiv = document.createElement("form");
    inputDiv.style.position = "absolute";
    inputDiv.style.left = "10px";
    inputDiv.style.top = "30px";
    inputDiv.style.width = "375px";
    inputDiv.style.font = "normal 10px verdana";
    item.appendChild(inputDiv);
    this.label = document.createTextNode("Sub Process Name");
    inputDiv.appendChild(this.label);
    this.input = document.createElement("textarea");
    this.input.style.border = "1px solid gray";
    this.input.style.font = "normal 10px verdana";
    //this.input.type = "text";
    this.input.maxLength = "500";
    this.input.cols = "50";
    this.input.rows = "3";
    var value = bpmnTask.prototype.trim(this.figure.workflow.currentSelection.subProcessName);
    if (value) this.input.value = value;
    else this.input.value = "";
    this.input.style.width = "100%";
    inputDiv.appendChild(this.input);
    this.input.focus();
    return item;
};

/*Double Click Event for opening the dialog Box*/
bpmnSubProcess.prototype.onDoubleClick = function () {
    var _409d = new bpmnSubProcessDialog(this);
    this.workflow.showDialog(_409d, this.workflow.currentSelection.x, this.workflow.currentSelection.y);
};


/**
 * This method will be called if the user pressed the OK button in buttonbar of the dialog.<br>
 * The string is first cleared and new string is painted.<br><br>
 **/
 bpmnSubProcessDialog.prototype.onOk = function () {
    this.figure.bpmnNewText.clear();

    len = Math.ceil(this.input.value.length/16);
    if(this.input.value.length < 19)
    {
        len = 1.5;
        if(this.input.value.length > 9)
            this.figure.rectWidth = this.input.value.length*8;
        else
            this.figure.rectWidth = 48;
    }
    else
        this.figure.rectWidth = 150;
    //tempcoord = this.workflow.currentSelection.coord_converter(this.workflow.currentSelection.width, this.workflow.currentSelection.height, this.input.value.length)
    this.figure.bpmnNewText.drawStringRect(this.input.value,20,20,this.figure.rectWidth,'left');
   // this.figure.bpmnNewText.drawTextString(this.input.value, this.workflow.currentSelection.width, this.workflow.currentSelection.height, tempcoord.temp_x, tempcoord.temp_y);
    this.figure.bpmnNewText.paint();
    this.figure.subProcessName = this.input.value; //Set Updated Text value

    if(this.figure.rectWidth<80)
       tempW = 110;
    else
       tempW = this.figure.rectWidth+35;
    this.workflow.currentSelection.setDimension(tempW, len*13+40);

    this.workflow.removeFigure(this);
};

bpmnSubProcess.prototype.getContextMenu = function () {
   this.workflow.handleContextMenu(this);
};