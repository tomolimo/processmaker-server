bpmnAnnotation = function (oWorkflow) {
    VectorFigure.call(this);
   //Getting width and height from DB
   if(typeof oWorkflow.anno_width != 'undefined' && typeof oWorkflow.anno_height != 'undefined')
    {
        this.width = oWorkflow.anno_width;
        this.height = oWorkflow.anno_height;
    }
   else
    {
        this.width = 110;
        this.height = 60;
    }
   this.setAnnotationName(oWorkflow.annotationName); //It will set the Default Task Name with appropriate count While dragging a task on the canvas
};

bpmnAnnotation.prototype = new VectorFigure;
bpmnAnnotation.prototype.type = "bpmnAnnotation";
bpmnAnnotation.prototype.setAnnotationName = function (name) {
    if(typeof name != 'undefined')
        this.annotationName = name;
    else
        this.annotationName = 'Annotation';
};

bpmnAnnotation.prototype.coord_converter = function (bound_width, bound_height, text_length) {
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



bpmnAnnotation.prototype.paint = function () {
    VectorFigure.prototype.paint.call(this);

     if(typeof workflow.sType == 'undefined')
        workflow.sType = 1;
  //Set the Task Limitation
     if(typeof this.limitFlag == 'undefined' || this.limitFlag == false)
     {
       this.originalWidth = 110;
       this.originalHeight = 60;
       this.orgXPos = this.getX();
       this.orgYPos = this.getY();
       this.orgFontSize =this.fontSize;
     }

    this.width  = this.originalWidth * workflow.sType;
    this.height = this.originalHeight  * workflow.sType;

    this.graphics.setColor("#ffffff");
    this.graphics.fillRect(0,0, this.getWidth(), this.getHeight());
    this.graphics.setColor("#000000");
    this.graphics.drawLine(this.getWidth()/4,0,0,0);
    this.graphics.drawLine(0,0,0,this.getHeight());
    this.graphics.drawLine(0,this.getHeight(),this.getWidth()/4,this.getHeight());
    this.graphics.setStroke(Stroke.DOTTED);
    this.graphics.drawLine(0,this.getHeight()/2,-this.getWidth()/2,-this.getHeight()/4);
    this.graphics.paint();
  
    /* New object is created to implement changing of Text functionality
    */
    this.bpmnText = new jsGraphics(this.id) ;
    this.padleft = 0.05*this.getWidth();
    this.padtop = 0.13*this.getHeight() -1;
    this.rectwidth = this.getWidth() - this.padleft;
    this.rectheight = this.getHeight() - 2 * this.padtop;

    //Setting text size to zoom font size if Zoomed
    if(typeof this.fontSize == 'undefined' || this.fontSize == '')
        this.fontSize = 11;
    else if(this.fontSize < 11)
        this.fontSize = 11;

    this.bpmnText.setFont('verdana', this.fontSize, Font.PLAIN);
    this.bpmnText.drawStringAnno(this.annotationName,0,this.padtop,this.rectwidth,this.rectheight,'left');
    this.bpmnText.paint();

    if(this.input1!=null){
        this.input1.setPosition(0,this.height/2);
    }

};

   jsGraphics.prototype.drawStringAnno = function(txt, x, y, width,height, halign)
	{
		this.htm += '<div style="position:absolute;overflow:hidden;'+
			'left:' + x + 'px;'+
			'top:' + y + 'px;'+
			'width:'+width +'px;'+
			'height:'+height +'px;'+
			'text-align:'+halign+';'+
			'font-family:' +  this.ftFam + ';'+
			'font-size:' + this.ftSz + ';'+
                        'padding-left:6px;'+
			'color:' + this.color + ';' + this.ftSty + '">'+
			txt +
			'<\/div>';
	};



bpmnAnnotation.prototype.setWorkflow=function(_40c5){
VectorFigure.prototype.setWorkflow.call(this,_40c5);
if(_40c5!=null){
this.input1=new InputPort();
this.input1.setWorkflow(_40c5);
this.input1.setName('input1');
this.input1.setZOrder(-1);
this.input1.setBackgroundColor(new Color(255, 255, 255));
this.input1.setColor(new Color(255, 255, 255));
//this.addPort(this.input1,0,this.height/2);
this.addPort(this.input1,-this.getWidth()/2,-this.getHeight()/4);
};
};

bpmnAnnotationDialog = function (_2e5e) {
    this.figure = _2e5e;
    var title = 'Annotation';
    Dialog.call(this, title);
    this.setDimension(400, 150); //Set the width and height of the Dialog box
}

bpmnAnnotationDialog.prototype = new Dialog();
bpmnAnnotationDialog.prototype.createHTMLElement = function () {
    var item = Dialog.prototype.createHTMLElement.call(this);
    var inputDiv = document.createElement("form");
    inputDiv.style.position = "absolute";
    inputDiv.style.left = "10px";
    inputDiv.style.top = "30px";
    inputDiv.style.width = "375px";
    inputDiv.style.font = "normal 10px verdana";
    item.appendChild(inputDiv);
    this.label = document.createTextNode("Annotation Name");
    inputDiv.appendChild(this.label);
    this.input = document.createElement("textarea");
    this.input.style.border = "1px solid gray";
    this.input.style.font = "normal 10px verdana";
    //this.input.type = "text";
    this.input.maxLength = "500";
    this.input.cols = "50";
    this.input.rows = "3";
    var value = bpmnTask.prototype.trim(this.figure.workflow.currentSelection.annotationName);
    if (value) this.input.value = value;
    else this.input.value = "";
    this.input.style.width = "100%";
    inputDiv.appendChild(this.input);
    this.input.focus();
    return item;
};

/*Double Click Event for opening the dialog Box*/
bpmnAnnotation.prototype.onDoubleClick = function () {
    var _409d = new bpmnAnnotationDialog(this);
    this.workflow.showDialog(_409d, this.workflow.currentSelection.x, this.workflow.currentSelection.y);
};


/**
 * This method will be called if the user pressed the OK button in buttonbar of the dialog.<br>
 * The string is first cleared and new string is painted.<br><br>
 **/
 bpmnAnnotationDialog.prototype.onOk = function () {
    this.figure.bpmnText.clear();

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
    this.figure.bpmnText.drawStringAnno(this.input.value,20,20,this.figure.rectWidth,'left');
   // this.figure.bpmnNewText.drawTextString(this.input.value, this.workflow.currentSelection.width, this.workflow.currentSelection.height, tempcoord.temp_x, tempcoord.temp_y);
    this.figure.bpmnText.paint();
    this.figure.annotationName = this.input.value; //Set Updated Text value

    //Updating Annotation Text Async into the DB
    this.figure.actiontype = 'updateText';
    this.workflow.saveShape(this.figure);

    if(this.figure.rectWidth<80)
       tempW = 110;
    else
       tempW = this.figure.rectWidth+35;
    this.workflow.currentSelection.setDimension(tempW, len*13+40);

    this.workflow.removeFigure(this);
};

