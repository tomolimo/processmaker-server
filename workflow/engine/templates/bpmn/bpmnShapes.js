bpmnTask = function (_30ab) {
    VectorFigure.call(this);

   if(typeof _30ab.boundaryEvent != 'undefined' && _30ab.boundaryEvent == true)
   {
      this.boundaryEvent = _30ab.boundaryEvent;
   }
   if(typeof _30ab.task_width != 'undefined' && typeof _30ab.task_height != 'undefined')
        this.setDimension(_30ab.task_width, _30ab.task_height);
    else
    this.setDimension(165, 40);

    this.taskName = _30ab.taskName; //It will set the Default Task Name with appropriate count While dragging a task on the canvas
};

bpmnTask.prototype = new VectorFigure;
bpmnTask.prototype.type = "bpmnTask"


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

//curWidth = this.getWidth();
bpmnTask.prototype.paint = function () {
    VectorFigure.prototype.paint.call(this);

    //Set the Task Limitation
    if (this.getWidth() > 200 || this.getHeight() > 100) {
        this.setDimension(200, 100);
    }
    else if (this.getWidth() < 165 || this.getHeight() < 40) {
        this.setDimension(165, 40);
    }

    var x = new Array(6, this.getWidth() - 3, this.getWidth(), this.getWidth(), this.getWidth() - 3, 6, 3, 3, 6);
    var y = new Array(3, 3, 6, this.getHeight() - 3, this.getHeight(), this.getHeight(), this.getHeight() - 3, 6, 3);
    this.stroke = 2;
    this.graphics.setStroke(this.stroke);
    this.graphics.setColor("#c0c0c0");
    this.graphics.fillPolygon(x, y);
    for (var i = 0; i < x.length; i++) {
        x[i] = x[i] - 3;
        y[i] = y[i] - 3;
    }
    this.graphics.setColor("#DBDFF6");
    this.graphics.fillPolygon(x, y);

    this.graphics.setColor("#5164b5"); //Blue Color
    this.graphics.drawPolygon(x, y);
    this.graphics.paint();
    this.x_text = this.workflow.getAbsoluteX(); //Get x co-ordinate from figure
    this.y_text = this.workflow.getAbsoluteY(); //Get x co-ordinate from figure
/* Created New Object of jsGraphics to draw String.
 * New object is created to implement changing of Text functionality
 */
    this.bpmnText = new jsGraphics(this.id);

/*if(this.taskName.length <= 17)
    {
        var padleft = 0.025*this.getWidth();
        var padtop = 0.36*this.getHeight();
        var rectwidth = this.getWidth() - padleft;
        var rectheight = 0.54*this.getHeight();
    }
    else
    {
        padleft = 0.1*this.getWidth();
        padtop = 0.15*this.getHeight();
        rectwidth = this.getWidth() - padleft;
        rectheight = 0.75*this.getHeight();
    }
*/
    var len = this.getWidth() / 18;
    if (len >= 6) {
        //len = 1.5;
        var padleft = 0.12 * this.getWidth();
        var padtop = 0.32 * this.getHeight() -3;
        this.rectWidth = this.getWidth() - 2 * padleft;
    }
    else {
        padleft = 0.1 * this.getWidth();
        padtop = 0.09 * this.getHeight() -3;
        this.rectWidth = this.getWidth() - 2 * padleft;
    }

    var rectheight = this.getHeight() - padtop -7;
    this.bpmnText.setFont('verdana', '11px', Font.PLAIN);
    this.bpmnText.drawStringRect(this.taskName, padleft, padtop, this.rectWidth, rectheight, 'center');
    // tempcoord = this.coord_converter(this.getWidth(), this.getHeight(), this.taskName.length);
    //  bpmnText.drawTextString(this.taskName, this.getWidth(), this.getHeight(), tempcoord.temp_x, tempcoord.temp_y);

    /****************************       Drawing Timer Boundary event starts here           *******************************/

    var boundaryTimer = new jsGraphics(this.id);

    var x_cir1=5;
    var y_cir1=45;
    boundaryTimer.setColor("#c0c0c0");
    boundaryTimer.fillEllipse(x[3]-x[3]/1.08,y[4]-12,30,30);

    boundaryTimer.setStroke(this.stroke);
    boundaryTimer.setColor( "#f9faf2" );
    boundaryTimer.fillEllipse(x[3]-x[3]/1.08,y[5]-12,30,30);
    boundaryTimer.setColor("#adae5e");
    boundaryTimer.drawEllipse(x[3]-x[3]/1.08,y[5]-12,30,30);
    var x_cir2=8;
    var y_cir2=48;
    boundaryTimer.setColor( "#f9faf2" );
    boundaryTimer.fillEllipse(x[3]-x[3]/1.08+3,y[5]-9,30-6,30-6);
    boundaryTimer.setColor("#adae5e");
    boundaryTimer.drawEllipse(x[3]-x[3]/1.08+3,y[5]-9,30-6,30-6);

    /*
    //drawing clock's minutes lines
    this.graphics.setColor("#adae5e");
    //this.graphics.drawEllipse(x_cir3,y_cir3,30-20,30-20);
    this.graphics.drawLine(30/2,30/2,30/1.3,30/2);
    this.graphics.drawLine(30/2,30/2,30/2,30/4.5);
    */


    //var x_cir3=10;
    //var y_cir3=10;
    //this.graphics.setColor( "#f9faf2" );
    //this.graphics.fillEllipse(x_cir3,y_cir3,30-20,30-20);
    boundaryTimer.setColor("#adae5e");
    //this.graphics.drawEllipse(x_cir3,y_cir3,30-20,30-20);
    boundaryTimer.drawLine(30/2.2+x[3]-x[3]/1.08,30/2+y[5]-10,30/1.6+x[3]-x[3]/1.08,30/2+y[5]-10);  //horizontal line
    boundaryTimer.drawLine(30/2.2+x[3]-x[3]/1.08,30/2+y[5]-10,30/2.2+x[3]-x[3]/1.08,30/3.7+y[5]-10);  //vertical line

    boundaryTimer.drawLine(x[3]-x[3]/1.08+24,y[5]-3,x[3]-x[3]/1.08+20,y[5]);  //10th min line 24,8,20,11
    boundaryTimer.drawLine(x[3]-x[3]/1.08+21,y[5]+4,x[3]-x[3]/1.08+25,y[5]+4);  //15th min line
    boundaryTimer.drawLine(x[3]-x[3]/1.08+24,y[5]+11,x[3]-x[3]/1.08+19,y[5]+9);  //25th min line
    boundaryTimer.drawLine(x[3]-x[3]/1.08+15,y[5]+11,x[3]-x[3]/1.08+15,y[5]+14);  //30th min line
    boundaryTimer.drawLine(x[3]-x[3]/1.08+8,y[5]+11,x[3]-x[3]/1.08+12,y[5]+8);  //40th min line
    boundaryTimer.drawLine(x[3]-x[3]/1.08+5,y[5]+4,x[3]-x[3]/1.08+8,y[5]+4);  //45th min line
    boundaryTimer.drawLine(x[3]-x[3]/1.08+8,y[5]-4,x[3]-x[3]/1.08+11,y[5]-1);  //50th min line
    boundaryTimer.drawLine(x[3]-x[3]/1.08+15,y[5]-7,x[3]-x[3]/1.08+15,y[5]-4);  //60th min line

    if(this.boundaryEvent == true)
        boundaryTimer.paint();
    /****************************       Drawing Timer Boundary event ends here           *******************************/

    this.bpmnText.paint();
    //this.bpmnNewText = this.bpmnText;

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

/*Workflow.prototype.onMouseUp=function(x,y){
  //Saving Task/Annotations position on Async Ajax call

this.dragging=false;
this.draggingLine=null;
};*/

Figure.prototype.onDragend=function(){

if(typeof workflow.currentSelection != 'undefined' && workflow.currentSelection != null)
  {
        var currObj =workflow.currentSelection;
        switch (currObj.type) {
        case 'bpmnTask':
        case 'bpmnSubProcess':
            currObj.actiontype = 'saveTaskPosition';
            currObj.workflow.saveShape(currObj);
            break;
        case 'bpmnAnnotation':
            currObj.actiontype = 'saveTextPosition';
            currObj.workflow.saveShape(currObj);
            break;
        }
        workflow.setBoundary(currObj);
  }

if(this.getWorkflow().getEnableSmoothFigureHandling()==true){
var _3dfe=this;
var _3dff=function(){
if(_3dfe.alpha<1){
_3dfe.setAlpha(Math.min(1,_3dfe.alpha+0.05));
}else{
window.clearInterval(_3dfe.timer);
_3dfe.timer=-1;
}
};
if(_3dfe.timer>0){
window.clearInterval(_3dfe.timer);
}
_3dfe.timer=window.setInterval(_3dff,20);
}else{
this.setAlpha(1);
}
this.command.setPosition(this.x,this.y);
this.workflow.commandStack.execute(this.command);
this.command=null;
this.isMoving=false;
this.workflow.hideSnapToHelperLines();
this.fireMoveEvent();
};

Figure.prototype.onKeyDown=function(_3e0e,ctrl){
if(_3e0e==46&&this.isDeleteable()==true){
workflow.getDeleteCriteria();
//this.workflow.commandStack.execute(new CommandDelete(this));
}
if(ctrl){
this.workflow.onKeyDown(_3e0e,ctrl);
}
};

bpmnTask.prototype.setWorkflow = function (_40c5) {
    VectorFigure.prototype.setWorkflow.call(this, _40c5);
    if (_40c5 != null) {
/*Adding Port to the Task After dragging Task on the Canvas
         *Ports will be invisibe After Drag and Drop, But It will be created
         */
        var TaskPortName = ['output1', 'output2', 'input1', 'input2'];
        var TaskPortType = ['OutputPort', 'OutputPort', 'InputPort', 'InputPort'];
        var TaskPositionX = [this.width / 2, this.width, 0, this.width / 2];
        var TaskPositionY = [this.height, this.height / 2, this.height / 2, 0];

        for (var i = 0; i < TaskPortName.length; i++) {
            eval('this.' + TaskPortName[i] + ' = new ' + TaskPortType[i] + '()'); //Create New Port
            eval('this.' + TaskPortName[i] + '.setWorkflow(_40c5)'); //Add port to the workflow
            eval('this.' + TaskPortName[i] + '.setName("' + TaskPortName[i] + '")'); //Set PortName
            eval('this.' + TaskPortName[i] + '.setZOrder(-1)'); //Set Z-Order of the port to -1. It will be below all the figure
            eval('this.' + TaskPortName[i] + '.setBackgroundColor(new Color(255, 255, 255))'); //Setting Background of the port to white
            eval('this.' + TaskPortName[i] + '.setColor(new Color(255, 255, 255))'); //Setting Border of the port to white
            eval('this.addPort(this.' + TaskPortName[i] + ',' + TaskPositionX[i] + ', ' + TaskPositionY[i] + ')'); //Setting Position of the port
        }
    }
};

InputPort.prototype.onDrop = function (port) {
    if (port.getMaxFanOut && port.getMaxFanOut() <= port.getFanOut()) {
        return;
    }
    if (this.parentNode.id == port.parentNode.id) {} else {
	var newObj = new Array();
	newObj = this.workflow.currentSelection;
	var preObj = port.parentNode;
	newObj.sPortType =port.properties.name;
	preObj.sPortType =this.properties.name;
	this.workflow.saveRoute(preObj,newObj);

        var _3f02 = new CommandConnect(this.parentNode.workflow, port, this);
        if (_3f02.source.type == _3f02.target.type) {
            return;
        }
        _3f02.setConnection(new DecoratedConnection());
        this.parentNode.workflow.getCommandStack().execute(_3f02);
    }
};

OutputPort.prototype.onDrop = function (port) {
    if (this.getMaxFanOut() <= this.getFanOut()) {
        return;
    }

    var connect = true;
    var conn = this.workflow.checkConnectionsExist(port, 'targetPort', 'OutputPort');
    if (conn == 0) //If no connection Exist then Allow connect
    connect = true;
    else if (conn < 2) //If One connection exist then Do not Allow to connect
    connect = false;


    if (this.parentNode.id == port.parentNode.id || connect == false) {

    } else {
        var _4070 = new CommandConnect(this.parentNode.workflow, this, port);
        if (_4070.source.type == _4070.target.type) {
            return;
        }
        _4070.setConnection(new DecoratedConnection());
        this.parentNode.workflow.getCommandStack().execute(_4070);

        //Saving Start Event
        var preObj = new Array();
        var bpmnType = this.workflow.currentSelection.type;
        if(bpmnType.match(/Event/) && bpmnType.match(/Start/) && port.parentNode.type.match(/Task/))
            {
                var tas_uid = port.parentNode.id;
                this.workflow.saveEvents(this.workflow.currentSelection,tas_uid);
            }
        else if(bpmnType.match(/Task/) && port.parentNode.type.match(/End/) && port.parentNode.type.match(/Event/))
            {
                preObj = this.workflow.currentSelection;
                var newObj = port.parentNode;
                newObj.conn = _4070.connection;
                this.workflow.saveRoute(preObj,newObj);
            }
        else if(port.parentNode.type.match(/Task/) && bpmnType.match(/Inter/) && bpmnType.match(/Event/))
            {
                var taskFrom = workflow.getStartEventConn(this,'sourcePort','InputPort');
                var taskTo =  workflow.getStartEventConn(this,'targetPort','OutputPort');

                if(typeof taskFrom[0] != 'undefined' || typeof taskTo[0] != 'undefined')
                  {
                    preObj.type = 'Task';
                    preObj.taskFrom = taskFrom[0].value;
                    preObj.taskTo = taskTo[0].value;

                    //save Event First
                    var tas_uid = port.parentNode.id;
                    this.workflow.saveEvents(workflow.currentSelection,preObj);
                  }
            }
        else if(bpmnType.match(/Task/) && port.parentNode.type.match(/Task/))
            {

		var preObj = this.workflow.currentSelection;
                var newObj = port.parentNode;
                newObj.conn = _4070.connection;
		newObj.sPortType =port.properties.name;
		preObj.sPortType =this.properties.name;
                this.workflow.saveRoute(preObj,newObj);
            }
        else if(bpmnType.match(/Gateway/) && port.parentNode.type.match(/Task/))
            {
                 var shape = new Array();
                 shape.type = '';
                 var preObj = this.workflow.currentSelection;
                 this.workflow.saveRoute(preObj,shape);
            }

    }
};


LineEndResizeHandle.prototype.onDrop=function(_3f3e){
var line=this.workflow.currentSelection;
line.isMoving=false;
if(line instanceof Connection){
this.command.setNewPorts(line.getSource(),_3f3e);

//If Input Port /Output Port is connected to respective ports, then should not connected
if(this.command.newSourcePort.type == this.command.newTargetPort.type)
    return;
else
    {
        this.command.newTargetPort.parentNode.conn = this.command.con;
	this.command.newTargetPort.parentNode.sPortType = this.command.newTargetPort.properties.name;
	this.command.newSourcePort.parentNode.sPortType = this.command.newSourcePort.properties.name;
        this.workflow.saveRoute(this.command.newSourcePort.parentNode,this.command.newTargetPort.parentNode);
        this.getWorkflow().getCommandStack().execute(this.command);
    }
}
this.command=null;
};

LineStartResizeHandle.prototype.onDrop=function(_410d){
var line=this.workflow.currentSelection;
line.isMoving=false;
if(line instanceof Connection){
this.command.setNewPorts(_410d,line.getTarget());

//If Input Port /Output Port is connected to respective ports, then should not connected
if(this.command.newSourcePort.type == this.command.newTargetPort.type)
    return;
else
    {
        this.command.newTargetPort.parentNode.conn = this.command.con;
	this.command.newTargetPort.parentNode.sPortType = this.command.newTargetPort.properties.name;
	this.command.newSourcePort.parentNode.sPortType = this.command.newSourcePort.properties.name;
        this.command.newTargetPort.parentNode.conn = this.command.con;
        this.workflow.saveRoute(this.command.newSourcePort.parentNode,this.command.newTargetPort.parentNode);
        this.getWorkflow().getCommandStack().execute(this.command);
    }
}
this.command=null;
};

ResizeHandle.prototype.onDragend=function(){
if(this.commandMove==null){
return;
}
var currentSelection = workflow.currentSelection;
if(currentSelection.type.match(/Task/))
{
  currentSelection.actiontype = 'saveTaskCordinates';
  workflow.saveShape(currentSelection);
}
else if(currentSelection.type.match(/Annotation/))
{
  currentSelection.actiontype = 'saveAnnotationCordinates';
  workflow.saveShape(currentSelection);
}
}
VectorFigure.prototype.addChild=function(_4078){
    _4078.setParent(this);
//_4078.setZOrder(this.getZOrder()+1);
//_4078.setParent(this);
//_4078.parent.addChild(_4078);
//this.children[_4078.id]=_4078;
//this.scrollarea.appendChild(_4078.getHTMLElement());
};

////// Decorators to add an arrow to the flow line. To show the direction of flow  //////////////
DecoratedConnection = function () {
    Connection.call(this);
    this.setTargetDecorator(new ArrowConnectionDecorator());
    this.setRouter(new ManhattanConnectionRouter());
};
DecoratedConnection.prototype = new Connection();
DecoratedConnection.prototype.type = "DecoratedConnection";
DecoratedConnection.prototype.getContextMenu = function () {
    if (this.id != null) {
        this.workflow.contextClicked = true;
        this.workflow.connectionContextMenu(this);
    }
};

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
    var zOrder = this.getZOrder();
    this.setZOrder(4000);
    this.setBackgroundColor(null);
    this.setColor(null);
    this.scrollarea.style.borderBottom = "0px";
    this.actionAdd.setPosition(0, 0);
    this.actionInterEvent.setPosition(20, 0);
    this.actionGateway.setPosition(20, 20);
    this.actionFront.setPosition(0, 18);
    this.actionBack.setPosition(0, 36);
    this.actionDelete.setPosition(0, 54);
};

ToolPalette.prototype.removechild = function (_4079) {
    if (_4079 != null) {
        var parentNode = this.html;
        if (parentNode != null) {
            if (typeof parentNode.children != 'undefined') {
                var len = parentNode.children[0].children.length;
                for (var i = 0; i < len; i++) {
                    var childNode = parentNode.children[0].children[i];
                    if (childNode == _4079.html) {
                        parentNode.children[0].removeChild(childNode);
                    }
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

    var newWorkflow = '';
    //If Right Clicked on the figure, Disabling Flow menu
    if (_39fb != null) {
        newWorkflow = _39fb.workflow;
    }
    else if (this.workflow != null) {
        newWorkflow = this.workflow;
    }
    else {
        newWorkflow = this.myworkflow;
    }

    var contextClicked = newWorkflow.contextClicked;
/*Check wheather the figure selected is same as previous figure.
    *If figure is different ,then remove the port from the previous selected figure.
    **/
    if (newWorkflow.currentSelection != null && typeof newWorkflow.preSelectedFigure != 'undefined') {
        if (newWorkflow.currentSelection.id != newWorkflow.preSelectedFigure.id) {
            newWorkflow.disablePorts(newWorkflow.preSelectedFigure);
        }
    }

    if (_39fb == this.currentFigure && contextClicked == true) {
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
    if (_39fd != null) {
        //Get the workflow object of the selected Figure object, so that we can compare with the new selected figure to remove ports
        _39fd.workflow.preSelectedFigure = _39fd.workflow.currentSelection;
        workflow.setBoundary(workflow.currentSelection);
        //Preventing Task from drawing outside canvas Code Starts here
        //@params - max X pos(canvas Width) = 918
        //@params - max Y pos(canvas Height) = 837
        ///////////////////////////////////////////


        //Preventing Task from drawing outside canvas Code Ends here
        if (_39fd.type == 'DecoratedConnection' || _39fd.workflow.contextClicked == true) {
            this.removechild(this.actionAdd);
            this.removechild(this.actionInterEvent);
            this.removechild(this.actionGateway);
            this.removechild(this.actionAnnotation);
            this.removechild(this.actionTask);
            this.removechild(this.actionEndEvent);
            this.removechild(this.actionBack);
            this.removechild(this.actionDelete);
            this.removechild(this.actionFront);
            _39fd.workflow.hideResizeHandles();
        }
        else {
            var pos = _39fd.getPosition();
            this.setPosition(pos.x + _39fd.getWidth() + 7, pos.y - 16);
            if (_39fd.workflow != null) {
                var bpmnShape = _39fd.workflow.currentSelection.type;
                this.addChild(this.actionFront);
                this.addChild(this.actionBack);
                this.addChild(this.actionDelete);
                var ports = '';
                //Disable Resize for All Events and Gateway
                if (bpmnShape.match(/Event/) || bpmnShape.match(/Gateway/) || bpmnShape.match(/bpmnDataobject/) || bpmnShape.match(/bpmnSubProcess/)) {
                    _39fd.workflow.hideResizeHandles();
                }
                if (bpmnShape.match(/Task/) || bpmnShape.match(/SubProcess/)) {
                    this.addChild(this.actionAdd);
                    this.addChild(this.actionInterEvent);
                    this.addChild(this.actionEndEvent);
                    this.addChild(this.actionGateway);
                    this.addChild(this.actionAnnotation);
                    this.actionAnnotation.setPosition(20, 60);
                    this.actionEndEvent.setPosition(20, 40)
                    this.removechild(this.actionTask);
                    ports = ['output1', 'input1', 'output2', 'input2'];
                    //ports = ['output1', 'output2'];
                    _39fd.workflow.enablePorts(_39fd, ports);
                }
                else if (bpmnShape.match(/Start/)) {
                    this.addChild(this.actionAdd);
                    this.addChild(this.actionAnnotation);
                    this.actionAnnotation.setPosition(20, 40);
                    this.addChild(this.actionInterEvent);
                    this.actionInterEvent.setPosition(20, 20)
                    this.addChild(this.actionGateway);
                    this.actionGateway.setPosition(20, 0)
                    this.removechild(this.actionEndEvent);
                    ports = ['output1', 'output2'];
                    _39fd.workflow.enablePorts(_39fd, ports);
                }
                else if (bpmnShape.match(/Inter/)) {
                    this.addChild(this.actionAdd);
                    this.addChild(this.actionAnnotation);
                    this.actionAnnotation.setPosition(20, 60);
                    this.addChild(this.actionInterEvent);
                    this.actionInterEvent.setPosition(20, 20)
                    this.addChild(this.actionGateway);
                    this.actionGateway.setPosition(20, 0);
                    this.addChild(this.actionEndEvent);
                    this.actionEndEvent.setPosition(20, 40);
                    ports = ['output1', 'input1', 'output2', 'input2'];
                    _39fd.workflow.enablePorts(_39fd, ports);
                }
                else if (bpmnShape.match(/End/)) {
                    this.addChild(this.actionAnnotation);
                    this.actionAnnotation.setPosition(0, 0);
                    this.removechild(this.actionInterEvent);
                    this.removechild(this.actionEndEvent);
                    this.removechild(this.actionTask);
                    this.removechild(this.actionGateway);
                    this.removechild(this.actionAdd);
                    ports = ['input1', 'input2'];
                    _39fd.workflow.enablePorts(_39fd, ports);
                }
                else if (bpmnShape.match(/Gateway/)) {
                    this.addChild(this.actionAdd);
                    this.addChild(this.actionAnnotation);
                    this.actionAnnotation.setPosition(20, 60);
                    this.addChild(this.actionInterEvent);
                    this.actionInterEvent.setPosition(20, 20)
                    this.addChild(this.actionGateway);
                    this.actionGateway.setPosition(20, 0);
                    this.addChild(this.actionEndEvent);
                    this.actionEndEvent.setPosition(20, 40);
                    ports = ['output1', 'input1', 'output2', 'input2'];
                    _39fd.workflow.enablePorts(_39fd, ports);
                }
                else if (bpmnShape.match(/Annotation/) || bpmnShape.match(/Dataobject/)) {
                    this.removechild(this.actionAdd);
                    this.removechild(this.actionInterEvent);
                    this.removechild(this.actionGateway);
                    this.removechild(this.actionEndEvent);
                    this.removechild(this.actionAnnotation);
                    this.removechild(this.actionEndEvent);
                    if (bpmnShape.match(/Annotation/)) {
                        ports = ['input1'];
                        _39fd.workflow.enablePorts(_39fd, ports);
                    }
                }
                else if (bpmnShape.match(/Pool/)) {
                    this.removechild(this.actionAdd);
                    this.removechild(this.actionInterEvent);
                    this.removechild(this.actionGateway);
                    this.removechild(this.actionEndEvent);
                    this.removechild(this.actionAnnotation);
                    this.removechild(this.actionEndEvent);
                    this.removechild(this.actionFront);
                    this.removechild(this.actionBack);
                    this.removechild(this.actionDelete);
                }
            }
        }
    }
};

bpmnTask.prototype.addShapes = function (_3896) {
    var xOffset = _3896.workflow.currentSelection.getX(); //Get x co-ordinate from figure
    var y = _3896.workflow.currentSelection.getY(); //Get y co-ordinate from figure
    //var xOffset = parseFloat(x + _3896.workflow.currentSelection.width); //Get x-offset co-ordinate from figure
    var yOffset = parseFloat(y + _3896.workflow.currentSelection.height + 25); //Get y-offset co-ordinate from figure
    var count;
    var shape = _3896.workflow.currentSelection.type;


    if (_3896.newShapeName == 'bpmnTask' && shape.match(/Event/)) {
        xOffset = _3896.workflow.currentSelection.getX() - 40; //Setting new offset value when currentselection is not Task i.e deriving task from events
    }

    if (_3896.newShapeName == 'bpmnTask' && shape.match(/Gateway/)) {
        xOffset = _3896.workflow.currentSelection.getX() - 35; //Setting new offset value when currentselection is not Task i.e deriving task from gateways
    }

    if (_3896.newShapeName.match(/Event/)) {
        xOffset = _3896.workflow.currentSelection.getX() + 40; //Setting new offset value when newShape is not Task i.e aligning events
    }

    if (_3896.newShapeName.match(/Gateway/)) {
        xOffset = _3896.workflow.currentSelection.getX() + 35; //Setting new offset value when newShape is not Task i.e aligning gateways
    }

    /* Incrementing Task No and assigning it to a local variable
     * taskNo Globally Declared in processmap.js
     * taskNo will have Last Task count
     * */
    if (_3896.newShapeName == 'bpmnTask') {
        count = ++_3896.workflow.taskNo;
        _3896.workflow.taskName = 'Task ' + count;
    }

    workflow.subProcessName = 'Sub Process';
    var newShape = eval("new " + _3896.newShapeName + "(_3896.workflow)");

    _3896.workflow.addFigure(newShape, xOffset, yOffset);

    //Assigning values to newShape Object for Saving Task automatically (Async Ajax Call)
    newShape.x = xOffset;
    newShape.y = yOffset;




    var conn = new DecoratedConnection();
    if (newShape.type.match(/Gateway/)) {
        conn.setTarget(newShape.getPort("input2"));
        conn.setSource(_3896.workflow.currentSelection.getPort("output1"));
        _3896.workflow.addFigure(conn);
    }
    if (newShape.type.match(/Start/)) {
        conn.setTarget(newShape.getPort("output1"));
        conn.setSource(_3896.workflow.currentSelection.getPort("input2"));
        _3896.workflow.addFigure(conn);
    }
    if (newShape.type.match(/Event/)) {
        conn.setTarget(newShape.getPort("input2"));
        conn.setSource(_3896.workflow.currentSelection.getPort("output1"));
        _3896.workflow.addFigure(conn);
    }
    if (newShape.type.match(/Task/)) {
        conn.setTarget(newShape.getPort("input2"));
        conn.setSource(_3896.workflow.currentSelection.getPort("output1"));
        _3896.workflow.addFigure(conn);
    }


     if (_3896.newShapeName.match(/Event/) && _3896.newShapeName.match(/End/)) {
        newShape.conn = conn;
        _3896.workflow.saveRoute(_3896.workflow.currentSelection,newShape);
     }
     else if (_3896.newShapeName == 'bpmnTask') {
        newShape.actiontype = 'addTask';
        newShape.conn = conn;
        _3896.workflow.saveShape(newShape); //Saving Task automatically (Async Ajax Call)
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
    this.palette.workflow.preSelectedObj = this.palette.workflow.currentSelection;
    bpmnTask.prototype.addShapes(this.palette);
};

ButtonDelete = function (_30a9) {
    Button.call(this, _30a9, 16, 16);
};
ButtonDelete.prototype = new Button;
ButtonDelete.prototype.type = "/skins/ext/images/gray/shapes/btn-del";
ButtonDelete.prototype.execute = function () {
    workflow.hideResizeHandles();
    workflow.getDeleteCriteria();
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
    this.setDimension(400, 150); //Set the width and height of the Dialog box
}

bpmnTaskDialog.prototype = new Dialog(this);
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
    this.input = document.createElement("textarea");
    this.input.size = '1';
    this.input.style.border = "1px solid gray";
    this.input.style.font = "normal 10px verdana";
    //this.input.type = "text";
    this.input.cols = "50";
    this.input.rows = "3";
    this.input.maxLength = "100";
    var value = bpmnTask.prototype.trim(workflow.currentSelection.taskName);
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

bpmnTask.prototype.trim = function (str) {
    if (str != null) return str.replace(/^\s+|\s+$/g, '');
    else return null;
};

/**
 * This method will be called if the user pressed the OK button in buttonbar of the dialog.<br>
 * The string is first cleared and new string is painted.<br><br>
 **/
bpmnTaskDialog.prototype.onOk = function () {
    this.figure.bpmnText.clear();
    //len = Math.ceil(this.input.value.length/16);
    var len = this.workflow.currentSelection.width / 18;
    if (len >= 6) {
        // len = 1.5;
        var padleft = 0.12 * this.workflow.currentSelection.width;
        var padtop = 0.32 * this.workflow.currentSelection.height  - 3;
        this.figure.rectWidth = this.workflow.currentSelection.width - 2 * padleft;
    }
    else {
        padleft = 0.1 * this.workflow.currentSelection.width;
        padtop = 0.09 * this.workflow.currentSelection.height  - 3;
        this.figure.rectWidth = this.workflow.currentSelection.width - 2 * padleft;
    }

    var rectheight = this.workflow.currentSelection.height - 2*padtop;
    //Aligning text more precisely onOK
/* if(this.input.value.length <= 17)
    {
        var padleft = 0.025*this.workflow.currentSelection.width;
        var padtop = 0.30*this.workflow.currentSelection.height;
        var rectwidth = this.workflow.currentSelection.width - 2*padleft;
    }
    else
    {
        padleft = 0.1*this.workflow.currentSelection.width;
        padtop = 0.09*this.workflow.currentSelection.height;
        rectwidth = this.workflow.currentSelection.width - 2*padleft;
        var rectheight = this.workflow.currentSelection.height - 10;

    }*/



    //tempcoord = this.workflow.currentSelection.coord_converter(this.workflow.currentSelection.width, this.workflow.currentSelection.height, this.input.value.length)
    this.figure.bpmnText.setFont('verdana', '11px', Font.PLAIN);
    this.figure.bpmnText.drawStringRect(this.input.value, padleft, padtop, this.figure.rectWidth, rectheight, 'center');
    // this.figure.bpmnNewText.drawTextString(this.input.value, this.workflow.currentSelection.width, this.workflow.currentSelection.height, tempcoord.temp_x, tempcoord.temp_y);
    this.figure.bpmnText.paint();
    this.workflow.currentSelection.taskName = this.input.value; //Set Updated Text value
    //Saving task name (whenever updated) onAsynch AJAX call
    this.figure.actiontype = 'updateTaskName';
    this.workflow.saveShape(this.figure);
    if (this.figure.rectWidth < 80) tempW = 110;
    else tempW = this.figure.rectWidth + 35;

    this.workflow.removeFigure(this);

};

bpmnTask.prototype.getContextMenu = function () {
    this.workflow.handleContextMenu(this);
};
