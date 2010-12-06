bpmnTask = function (_30ab) {
    VectorFigure.call(this);
    this.setDimension(110, 60);
    this.setTaskName(_30ab.taskNo); //It will set the Default Task Name with appropriate count While dragging a task on the canvas
};

bpmnTask.prototype = new VectorFigure;
bpmnTask.prototype.type = "bpmnTask";
bpmnTask.prototype.setTaskName = function (name) {
    this.taskName = 'Task ' + name;
};

bpmnTask.prototype.coord_converter = function (bound_width, bound_height, text_length) {
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



bpmnTask.prototype.paint = function () {
    VectorFigure.prototype.paint.call(this);
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
    this.graphics.paint();
    this.x_text = this.workflow.getAbsoluteX(); //Get x co-ordinate from figure
    this.y_text = this.workflow.getAbsoluteY(); //Get x co-ordinate from figure
/* Created New Object of jsGraphics to draw String.
 * New object is created to implement changing of Text functionality
 */
    var bpmnText = new jsGraphics(this.id);
    //bpmnText.drawStringRect(this.taskName,this.getWidth()/2-20,this.getHeight()/2-11,200,'left');
    tempcoord = this.coord_converter(this.getWidth(), this.getHeight(), this.taskName.length);
    bpmnText.drawTextString(this.taskName, this.getWidth(), this.getHeight(), tempcoord.temp_x, tempcoord.temp_y);
    bpmnText.paint();
    this.bpmnNewText = bpmnText;

/*Code Added to Dynamically shift Ports on resizing of shapes
 **/
    if (this.input1 != null) {
        this.input1.setPosition(0, this.height / 2);
    }
    if (this.output1 != null) {
        this.output1.setPosition(this.width / 2, this.height);
    }
    if (this.input2 != null) {
        this.input2.setPosition(this.width / 2, 0);
    }
    if (this.output2 != null) {
        this.output2.setPosition(this.width, this.height / 2);
    }


};

jsGraphics.prototype.drawTextString = function (txt, x, y, dx, dy) {
    this.htm += '<div style="position:absolute; display:table-cell; vertical-align:middle; height:' + y + '; width:' + x + ';' + 'margin-left:' + dx + 'px;' + 'margin-top:' + dy + 'px;' + 'font-family:' + this.ftFam + ';' + 'font-size:' + this.ftSz + ';' + 'color:' + this.color + ';' + this.ftSty + '">' + txt + '<\/div>';
};

bpmnTask.prototype.setWorkflow = function (_40c5) {
    VectorFigure.prototype.setWorkflow.call(this, _40c5);
    if (_40c5 != null) {
        this.output1 = new OutputPort();
        this.output1.setWorkflow(_40c5);
        this.output1.setName("output1");
        this.output1.setBackgroundColor(new Color(115, 115, 245));
        this.addPort(this.output1, this.width / 2, this.height);

        this.output2 = new OutputPort();
        this.output2.setWorkflow(_40c5);
        this.output2.setName("output2");
        this.output2.setBackgroundColor(new Color(115, 115, 245));
        this.addPort(this.output2, this.width, this.height / 2);

        this.input1 = new InputPort();
        this.input1.setWorkflow(_40c5);
        this.input1.setName("input1");
        this.input1.setBackgroundColor(new Color(245, 115, 115));
        this.addPort(this.input1, 0, this.height / 2);

        this.input2 = new InputPort();
        this.input2.setWorkflow(_40c5);
        this.input2.setName("input2");
        this.input2.setBackgroundColor(new Color(245, 115, 115));
        this.addPort(this.input2, this.width / 2, 0);
    }
};

InputPort.prototype.onDrop=function(port){
if(port.getMaxFanOut&&port.getMaxFanOut()<=port.getFanOut()){
return;
}
if(this.parentNode.id==port.parentNode.id){
}else{
var _3f02=new CommandConnect(this.parentNode.workflow,port,this);
if(_3f02.source.type == _3f02.target.type){
    return;
}
_3f02.setConnection(new DecoratedConnection());
this.parentNode.workflow.getCommandStack().execute(_3f02);
}
};

OutputPort.prototype.onDrop=function(port){
if(this.getMaxFanOut()<=this.getFanOut()){
return;
}
if(this.parentNode.id==port.parentNode.id){
}else{
var _4070=new CommandConnect(this.parentNode.workflow,this,port);
if(_4070.source.type == _4070.target.type){
    return;
}
_4070.setConnection(new DecoratedConnection());
this.parentNode.workflow.getCommandStack().execute(_4070);
}
};

////// Decorators to add an arrow to the flow line. To show the direction of flow  //////////////

        DecoratedConnection=function(){
        Connection.call(this);
        this.setTargetDecorator(new ArrowConnectionDecorator());
        this.setRouter(new ManhattanConnectionRouter());
        };
        DecoratedConnection.prototype=new Connection();
        DecoratedConnection.prototype.type="DecoratedConnection";

////////--------------------------------------------------------------------------------------------///////

FlowMenu = function (_39f9) {
    this.actionAdd = new ButtonAdd(this);
    this.actionTask = new ButtonTask(this);
    this.actionInterEvent = new ButtonInterEvent(this);
    this.actionEndEvent = new ButtonEndEvent(this);
    this.actionGateway = new ButtonGateway(this);
    this.actionFront = new ButtonMoveFront(this);
    this.actionBack = new ButtonMoveBack(this);
    this.actionDelete = new ButtonDelete(this);
    this.actionAnnotation = new ButtonAnnotation(this);
    ToolPalette.call(this);
    this.setDimension(20, 80);
    this.setBackgroundColor(new Color(220, 255, 255));
    this.currentFigure = null;
    this.myworkflow = _39f9;
    this.added = false;
    this.setDeleteable(false);
    this.setCanDrag(false);
    this.setResizeable(false);
    this.setSelectable(false);
    this.setBackgroundColor(null);
    this.setColor(null);
    this.scrollarea.style.borderBottom = "0px";
    this.actionAdd.setPosition(0, 0);
    this.actionInterEvent.setPosition(20, 0);
    this.actionGateway.setPosition(20, 20);
    this.actionFront.setPosition(0, 18);
    this.actionBack.setPosition(0, 36);
    this.actionDelete.setPosition(0, 54);
    this.addChild(this.actionFront);
    this.addChild(this.actionBack);
    this.addChild(this.actionDelete);
};

ToolPalette.prototype.removechild = function (_4079) {
    if (_4079 != null) {
        var parentNode = this.html;
        if (parentNode != null) {
            var len = parentNode.children[0].children.length;
            for (var i = 0; i < len; i++) {
                var childNode = parentNode.children[0].children[i];
                if (childNode == _4079.html) {
                    parentNode.children[0].removeChild(childNode);
                }
            }
        }
    }
};
FlowMenu.prototype = new ToolPalette;
FlowMenu.prototype.setAlpha = function (_39fa) {
    Figure.prototype.setAlpha.call(this, _39fa);
};
FlowMenu.prototype.hasTitleBar = function () {
    return false;
};
FlowMenu.prototype.setFigure = function (_3087) {

}
FlowMenu.prototype.onSelectionChanged = function (_39fb) {
    if (_39fb == this.currentFigure) {
        return;
    }
    if (this.added == true) {
        this.myworkflow.removeFigure(this);
        this.added = false;
    }
    if (_39fb != null && this.added == false) {
        if (this.myworkflow.getEnableSmoothFigureHandling() == true) {
            this.setAlpha(0.01);
        }
        this.myworkflow.addFigure(this, 100, 100);
        this.added = true;
    }
    if (this.currentFigure != null) {
        this.currentFigure.detachMoveListener(this);
    }
    this.currentFigure = _39fb;
    if (this.currentFigure != null) {
        this.currentFigure.attachMoveListener(this);
        this.onOtherFigureMoved(this.currentFigure);
    }
};
FlowMenu.prototype.setWorkflow = function (_39fc) {
    Figure.prototype.setWorkflow.call(this, _39fc);
};
FlowMenu.prototype.onOtherFigureMoved = function (_39fd) {
    var pos = _39fd.getPosition();
    this.setPosition(pos.x + _39fd.getWidth() + 7, pos.y - 16);
    if (_39fd.workflow != null) {
        var bpmnShape = _39fd.workflow.currentSelection.type;
        switch (bpmnShape) {
        case 'bpmnTask':
            this.addChild(this.actionAdd);
            this.addChild(this.actionInterEvent);
            this.addChild(this.actionEndEvent);
            this.addChild(this.actionGateway);
            this.addChild(this.actionAnnotation);
            this.actionAnnotation.setPosition(20,60);
            this.actionEndEvent.setPosition(20,40)
            this.removechild(this.actionTask);
            break;
        case 'bpmnEventEmptyEnd':
        case 'bpmnEventMessageEnd':
        case 'bpmnEventErrorEnd':
        case 'bpmnEventCancelEnd':
        case 'bpmnEventCompEnd':
        case 'bpmnEventMultipleEnd':
        case 'bpmnEventEndSignal':
        case 'bpmnEventTerminate':
            this.addChild(this.actionAnnotation);
            this.actionAnnotation.setPosition(0,0);
            this.removechild(this.actionInterEvent);
            this.removechild(this.actionEndEvent);
            this.removechild(this.actionTask);
            this.removechild(this.actionGateway);
            this.removechild(this.actionAdd);
            break;
        case 'bpmnEventEmptyStart':
        case 'bpmnEventMessageStart':
        case 'bpmnEventTimerStart':
        case 'bpmnEventRuleStart':
        case 'bpmnEventSignalStart':
        case 'bpmnEventMulStart':
            this.addChild(this.actionAdd);
            this.addChild(this.actionAnnotation);
            this.actionAnnotation.setPosition(20,40);
            this.addChild(this.actionInterEvent);
            this.actionInterEvent.setPosition(20,20)
            this.addChild(this.actionGateway);
            this.actionGateway.setPosition(20,0)
            break;
        case 'bpmnEventEmptyInter':
        case 'bpmnEventMessageSendInter':
        case 'bpmnEventMessageRecInter':
        case 'bpmnEventTimerInter':
        case 'bpmnEventCompInter':
        case 'bpmnEventRuleInter':
        case 'bpmnEventLinkInter':
        case 'bpmnEventInterSignal':
        case 'bpmnEventMultipleInter':
            this.addChild(this.actionAdd);
            this.addChild(this.actionAnnotation);
            this.actionAnnotation.setPosition(20,60);
            this.addChild(this.actionInterEvent);
            this.actionInterEvent.setPosition(20,20)
            this.addChild(this.actionGateway);
            this.actionGateway.setPosition(20,0);
            this.addChild(this.actionEndEvent);
            this.actionEndEvent.setPosition(20,40);
            break;
        case 'bpmnGatewayInclusive':
        case 'bpmnGatewayExclusiveData':
        case 'bpmnGatewayExclusiveEvent':
        case 'bpmnGatewayComplex':
        case 'bpmnGatewayParallel':
            this.addChild(this.actionAdd);
            this.addChild(this.actionAnnotation);
            this.actionAnnotation.setPosition(20,60);
            this.addChild(this.actionInterEvent);
            this.actionInterEvent.setPosition(20,20)
            this.addChild(this.actionGateway);
            this.actionGateway.setPosition(20,0);
            this.addChild(this.actionEndEvent);
            this.actionEndEvent.setPosition(20,40);
            break;
        default:
            this.addChild(this.actionAdd);
            this.removechild(this.actionTask);
            this.removechild(this.actionInterEvent);
            this.removechild(this.actionGateway);
            break;
        }
    }
};

bpmnTask.prototype.addShapes = function (_3896) {
    var x = _3896.workflow.currentSelection.getX(); //Get x co-ordinate from figure
    var y = _3896.workflow.currentSelection.getY(); //Get y co-ordinate from figure
    var xOffset = parseFloat(x + _3896.workflow.currentSelection.width); //Get x-offset co-ordinate from figure
    var yOffset = parseFloat(y + _3896.workflow.currentSelection.height); //Get y-offset co-ordinate from figure
    var count;
    var shape = _3896.workflow.currentSelection.type;

/* Incrementing Task No and assigning it to a local variable
     * taskNo Globally Declared in processmap.js
     * taskNo will have Last Task count
     * */
    if (_3896.newShapeName == 'bpmnTask') count = ++_3896.workflow.taskNo;

    NewShape = eval("new " + _3896.newShapeName + "(_3896.workflow)");
    _3896.workflow.addFigure(NewShape, xOffset, yOffset);
    var conn = new DecoratedConnection();
    if(NewShape.getPort("input1") != null){
    conn.setTarget(NewShape.getPort("input1"));
    conn.setSource(_3896.workflow.currentSelection.getPort("output1"));
    _3896.workflow.addFigure(conn);
    }
    else
        {
              conn.setTarget(NewShape.getPort("output1"));
              conn.setSource(_3896.workflow.currentSelection.getPort("input1"));
             _3896.workflow.addFigure(conn);
        }
}

ButtonInterEvent = function (_30a8) {
    Button.call(this, _30a8, 16, 16);
};
ButtonInterEvent.prototype = new Button;
ButtonInterEvent.prototype.type = "/skins/ext/images/gray/shapes/interevent";
ButtonInterEvent.prototype.execute = function () {
    var count = 0;
    this.palette.newShapeName = 'bpmnEventEmptyInter';
    bpmnTask.prototype.addShapes(this.palette);
};

ButtonEndEvent = function (_30a8) {
    Button.call(this, _30a8, 16, 16);
};
ButtonEndEvent.prototype = new Button;
ButtonEndEvent.prototype.type = "/skins/ext/images/gray/shapes/endevent";
ButtonEndEvent.prototype.execute = function () {
    var count = 0;
    this.palette.newShapeName = 'bpmnEventEmptyEnd';
    bpmnTask.prototype.addShapes(this.palette);
};


ButtonGateway = function (_30a8) {
    Button.call(this, _30a8, 16, 16);
};
ButtonGateway.prototype = new Button;
ButtonGateway.prototype.type = "/skins/ext/images/gray/shapes/gateway-small";
ButtonGateway.prototype.execute = function () {
    this.palette.newShapeName = 'bpmnGatewayExclusiveData';
    bpmnTask.prototype.addShapes(this.palette);
};

ButtonAnnotation = function (_30a8) {
    Button.call(this, _30a8, 16, 16);
};
ButtonAnnotation.prototype = new Button;
ButtonAnnotation.prototype.type = "/skins/ext/images/gray/shapes/annotation";
ButtonAnnotation.prototype.execute = function () {
    var count = 0;
    this.palette.newShapeName = 'bpmnAnnotation';
    bpmnTask.prototype.addShapes(this.palette);
};

ButtonTask = function (_30a8) {
    Button.call(this, _30a8, 16, 16);
};
ButtonTask.prototype = new Button;
ButtonTask.prototype.type = "/skins/ext/images/gray/shapes/Task";
ButtonTask.prototype.execute = function () {
    this.palette.newShapeName = 'bpmnTask';
    bpmnTask.prototype.addShapes(this.palette);
};


ButtonAdd = function (_30a8) {
    Button.call(this, _30a8, 16, 16);
};
ButtonAdd.prototype = new Button;
ButtonAdd.prototype.type = "/skins/ext/images/gray/shapes/btn-add";
ButtonAdd.prototype.execute = function () {
    this.palette.newShapeName = 'bpmnTask';
    bpmnTask.prototype.addShapes(this.palette);
};

ButtonDelete = function (_30a9) {
    Button.call(this, _30a9, 16, 16);
};
ButtonDelete.prototype = new Button;
ButtonDelete.prototype.type = "/skins/ext/images/gray/shapes/btn-del";
ButtonDelete.prototype.execute = function () {
    var shape = this.palette.workflow.currentSelection.type;
/* Decrementing Task No and assigning it to a local variable
     * taskNo Globally Declared in processmap.js
     * taskNo will have Last Task count
     * */
    if (shape == 'bpmnTask') {
        --this.palette.workflow.taskNo;
    }
    this.palette.workflow.getCommandStack().execute(new CommandDelete(this.palette.workflow.getCurrentSelection()));
    ToolGeneric.prototype.execute.call(this);
};
ButtonMoveFront = function (_3e22) {
    Button.call(this, _3e22, 16, 16);
};
ButtonMoveFront.prototype = new Button;
ButtonMoveFront.prototype.type = "/skins/ext/images/gray/shapes/btn-movefrnt";
ButtonMoveFront.prototype.execute = function () {
    this.palette.workflow.moveFront(this.palette.workflow.getCurrentSelection());
    ToolGeneric.prototype.execute.call(this);
};
ButtonMoveBack = function (_4091) {
    Button.call(this, _4091, 16, 16);
};
ButtonMoveBack.prototype = new Button;
ButtonMoveBack.prototype.type = "/skins/ext/images/gray/shapes/btn-movebk";
ButtonMoveBack.prototype.execute = function () {
    this.palette.workflow.moveBack(this.palette.workflow.getCurrentSelection());
    ToolGeneric.prototype.execute.call(this);
};

bpmnTaskDialog = function (_2e5e) {
    this.figure = _2e5e;
    var title = 'Task Detail';
    Dialog.call(this, title);
    this.setDimension(400, 100); //Set the width and height of the Dialog box
}

bpmnTaskDialog.prototype = new Dialog();
bpmnTaskDialog.prototype.createHTMLElement = function () {
    var item = Dialog.prototype.createHTMLElement.call(this);
    var inputDiv = document.createElement("form");
    inputDiv.style.position = "absolute";
    inputDiv.style.left = "10px";
    inputDiv.style.top = "30px";
    inputDiv.style.width = "375px";
    inputDiv.style.font = "normal 10px verdana";
    item.appendChild(inputDiv);
    this.label = document.createTextNode("Task Name");
    inputDiv.appendChild(this.label);
    this.input = document.createElement("input");
    this.input.style.border = "1px solid gray";
    this.input.style.font = "normal 10px verdana";
    this.input.type = "text";
    var value = bpmnTaskDialog.prototype.trim(this.figure.html.textContent);
    if (value) this.input.value = value;
    else this.input.value = "";
    this.input.style.width = "100%";
    inputDiv.appendChild(this.input);
    this.input.focus();
    return item;
};

/*Double Click Event for opening the dialog Box*/
bpmnTask.prototype.onDoubleClick = function () {
    var _409d = new bpmnTaskDialog(this);
    this.workflow.showDialog(_409d, this.workflow.currentSelection.x, this.workflow.currentSelection.y);
};


/**
 * This method will be called if the user pressed the OK button in buttonbar of the dialog.<br>
 * The string is first cleared and new string is painted.<br><br>
 **/
bpmnTaskDialog.prototype.onOk = function () {
    this.figure.bpmnNewText.clear();
    tempcoord = this.workflow.currentSelection.coord_converter(this.workflow.currentSelection.width, this.workflow.currentSelection.height, this.input.value.length)
    //this.figure.bpmnNewText.drawStringRect(this.input.value,this.workflow.currentSelection.width/2-30,this.workflow.currentSelection.height/2-10,200,'left');
    this.figure.bpmnNewText.drawTextString(this.input.value, this.workflow.currentSelection.width, this.workflow.currentSelection.height, tempcoord.temp_x, tempcoord.temp_y);
    this.figure.bpmnNewText.paint();
    this.figure.taskName = this.input.value; //Set Updated Text value
    //alert(this.input.value.length);
    //this.workflow.currentSelection.width = this.input.value.length;
    //VectorFigure.prototype.paint.call(this.figure);
    this.workflow.removeFigure(this);
};

bpmnTask.prototype.getContextMenu = function () {
    if (this.id != null) {
        this.canvasTask = Ext.get(this.id);
        this.contextTaskmenu = new Ext.menu.Menu({
            items: [{
                text: 'Steps',
                scope: this
            },
            {
                text: 'Users & Users Group',
                scope: this
            },
            {
                text: 'Users & Users Groups (ad-hoc)',
                scope: this
            },
            {
                text: 'Routing Rule',
                scope: this
            },
            {
                text: 'Deleting Routing Rule',
                scope: this
            },
            {
                text: 'Delete Task',
                scope: this
            },
            {
                text: 'Properties',
                scope: this
            }]
        });
    }

    this.canvasTask.on('contextmenu', function (e) {
        e.stopEvent();
        this.contextTaskmenu.showAt(e.getXY());
    }, this);

};