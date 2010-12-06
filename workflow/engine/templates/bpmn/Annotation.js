bpmnAnnotation = function (_30ab) {
    VectorFigure.call(this);
    if(typeof _30ab.anno_width != 'undefined' && typeof _30ab.anno_height != 'undefined')
        this.setDimension(_30ab.anno_width, _30ab.anno_height);
    else
    this.setDimension(110, 60);
    this.setAnnotationName(_30ab.annotationName); //It will set the Default Task Name with appropriate count While dragging a task on the canvas
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

     //Set the Task Limitation
    if(this.getWidth() > 200 || this.getHeight() > 100)
    {
        this.setDimension(200, 100);
    }
    if(this.getWidth() < 110 || this.getHeight() < 60)
    {
        this.setDimension(110, 60);
    }
    this.graphics.setColor("#ffffff");
    this.graphics.fillRect(0,0, this.getWidth(), this.getHeight());
    this.graphics.setColor("#000000");
    this.graphics.drawLine(this.getWidth()/4,0,0,0);
    this.graphics.drawLine(0,0,0,this.getHeight());
    this.graphics.drawLine(0,this.getHeight(),this.getWidth()/4,this.getHeight());
    this.graphics.paint();
    /*var x = new Array(6, this.getWidth() - 3, this.getWidth(), this.getWidth(), this.getWidth() - 3, 6, 3, 3, 6);
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
    this.graphics.setColor("#ff0f0f");
    this.graphics.drawPolygon(x, y);
    this.graphics.paint();
    this.x_text = this.workflow.getAbsoluteX(); //Get x co-ordinate from figure
    this.y_text = this.workflow.getAbsoluteY(); //Get x co-ordinate from figure
    */
 /* New object is created to implement changing of Text functionality
 */
    this.bpmnText = new jsGraphics(this.id);
    var padleft = 0.10*this.getWidth();
    var padtop = 0.18*this.getHeight();
    var rectwidth = this.getWidth() - padleft;
    var rectheight = this.getHeight() - 2*padtop;
    this.bpmnText.setFont('verdana', '11px', Font.PLAIN);
    this.bpmnText.drawStringRect(this.annotationName,0,padtop,rectwidth,rectheight,'center');
    //bpmnText.drawStringRect(this.taskName,this.getWidth()/2-20,this.getHeight()/2-11,200,'left');
    //tempcoord = this.coord_converter(this.getWidth(), this.getHeight(), this.taskName.length);
    //bpmnText.drawTextString(this.taskName, this.getWidth(), this.getHeight(), tempcoord.temp_x, tempcoord.temp_y);
    this.bpmnText.paint();

    if(this.input1!=null){
    this.input1.setPosition(0,this.height/2);
    }

};

   jsGraphics.prototype.drawTextString = function (txt, x, y, dx, dy) {
    this.htm += '<div style="position:absolute; display:table-cell; vertical-align:middle; height:' + y + '; width:' + x + ';' + 'margin-left:' + dx + 'px;' + 'margin-top:' + dy + 'px;' + 'font-family:' + this.ftFam + ';' + 'font-size:' + this.ftSz + ';' + 'color:' + this.color + ';' + this.ftSty + '">' + txt + '<\/div>';
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
this.addPort(this.input1,0,this.height/2);
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
    this.figure.bpmnText.drawStringRect(this.input.value,20,20,this.figure.rectWidth,'left');
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

