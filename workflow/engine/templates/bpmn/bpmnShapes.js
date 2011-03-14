bpmnTask = function (oWorkflow) {
    VectorFigure.call(this);

   if(typeof oWorkflow.boundaryEvent != 'undefined' && oWorkflow.boundaryEvent == true){
      this.boundaryEvent = oWorkflow.boundaryEvent;
   }
   //Getting width and height from DB
   if(typeof oWorkflow.task_width != 'undefined' && typeof oWorkflow.task_height != 'undefined' && oWorkflow.task_width != '') {
        this.width  =  oWorkflow.task_width;
        this.height = oWorkflow.task_height;
        this.originalWidth  = oWorkflow.task_width;
        this.originalHeight = oWorkflow.task_height;
   }
   else {
       this.width  =  165;
       this.height  = 40;
       this.originalWidth = 165;
       this.originalHeight = 40;
   }

   this.orgXPos = oWorkflow.orgXPos;
   this.orgYPos = oWorkflow.orgYPos;
   
   this.taskName = ''; //It will set the Default Task Name with appropriate count While dragging a task on the canvas
   if( ! Ext.isIE )
     this.html.addClass('x-task');
};

bpmnTask.prototype = new VectorFigure;
bpmnTask.prototype.type = "bpmnTask"


bpmnTask.prototype.coord_converter = function (bound_width, bound_height, text_length) {
  input_width = text_length * 6;
  input_height = 10;

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

  if(typeof workflow.zoomfactor == 'undefined') {
    this.originalWidth = 165;
    this.originalHeight = 40;
    workflow.zoomfactor = 1;
  }

  if(workflow.zoomfactor == 1) {
    if ((this.getWidth() > 200 || this.getHeight() > 100) && this.limitFlag == false) {
        this.originalWidth  = 200;
        this.originalHeight = 100;
        this.width  = 200;
        this.height = 100;
    }
    if ((this.getWidth() < 165 || this.getHeight() < 40) && this.limitFlag == false) {
        this.originalWidth  = 165;
        this.originalHeight = 40;
        this.width  = 165;
        this.height = 40;
    }
  }
  else {
    this.width  = this.originalWidth   *  workflow.zoomfactor;
    this.height = this.originalHeight  *  workflow.zoomfactor;
  }
  //For Zooming

    //Set the Task Limitation
    /*if ((this.getWidth() >= 200 || this.getHeight() >= 100 ) && this.limitFlag != true) {
        this.originalWidth = 200;
        this.originalHeight = 100;
    }
    else if ((this.getWidth() <= 165 || this.getHeight() <= 40) && this.limitFlag != true) {
        this.originalWidth = 165;
        this.originalHeight = 40;
    }*/

  

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
  //erik: overridden the drawStringRect method
  this.bpmnText.drawStringRect = function(txt, x, y, width, height, halign, cls)
  {
    var classBk = typeof(cls) != 'undefined' ? 'class="'+cls+'" ' : '';
    this.htm += '<div '+classBk+' style="position:absolute;overflow:hidden;'+
      'left:' + x + 'px;'+
      'top:' + y + 'px;'+
      'width:'+width +'px;'+
      'height:'+height +'px;'+
      'text-align:'+halign+';'+
      'font-family:' +  this.ftFam + ';'+
      'font-size:' + this.ftSz + ';'+
      'line-height: 100%;'+
      'color:' + this.color + ';' + this.ftSty + '">'+
      '<span style="display:inline-block; vertical-align:middle">' +txt + '<\/span>'+
      '<\/div>';
  };

  var zoomRate = workflow.zoomfactor;
  var len = this.getWidth() / 18;
  if (len >= 6) {
    this.padleft = 0.05 * this.getWidth();
    this.padtop  = 0.13 * this.getHeight() -1;
    this.rectWidth = this.getWidth() - 2 * this.padleft;
  }
  else {
    this.padleft = 2; //0.06 * this.getWidth();
    this.padtop = 2; //0.09 * this.getHeight() -3;
    this.rectWidth = this.getWidth() - 2 * this.padleft;
  }

  this.rectheight = this.getHeight() - this.padtop -3;
  if ( this.rectheight < 7 ) this.rectheight = 7;


  if(typeof this.taskName == 'undefined')
    this.taskName = '';

  //if (typeof this.fontSize == 'undefined' || this.fontSize == '')
    this.fontSize = 11;
  var fontSize = zoomRate * this.fontSize;

  this.graphics.setFont('verdana', + fontSize+'px', Font.PLAIN);

  this.graphics.drawStringRect(this.taskName, this.padleft, this.padtop, this.rectWidth, this.rectheight, 'center', 'x-task');
  this.graphics.paint();
  //***** Drawing Timer Boundary event starts here 
  this.boundaryTimer = new jsGraphics(this.id);

  var x_cir1=5;
  var y_cir1=45;
  this.x3 = x[3];
  this.y4 = y[4];
  this.y5 = y[5];

  var xbt = 13*zoomRate;           //x-base boundaryTimer
  var ybt = this.y4 - 13*zoomRate; //y-base boundaryTimer
  var dbt = 30*zoomRate;           //diameter boundaryTimer
  var ycbt = ybt + 11*zoomRate;    //y-center boundaryTimer
  this.graphics.setColor("#c0c0c0");
  this.graphics.fillEllipse(xbt+2, ybt+2, dbt, dbt);
  this.graphics.setStroke(this.stroke-1);
  this.graphics.setColor( "#f9faf2" );
  this.graphics.fillEllipse(xbt, ybt, dbt, dbt);
  this.graphics.setColor("#adae5e");
  this.graphics.drawEllipse(xbt,ybt, dbt, dbt);

  var x_cir2=8;
  var y_cir2=48;
  //this.boundaryTimer.setColor( "#f9faf2" );
  //this.boundaryTimer.fillEllipse(xbt, ybt-9*zoomRate,(30-6)*zoomRate,(30-6)*zoomRate);
  this.graphics.setColor("#adae5e");
  this.graphics.drawEllipse(xbt+(3*zoomRate), ybt+3*zoomRate,(24.4)*zoomRate,(24.4)*zoomRate);

  this.graphics.setColor("#adae5e");
  this.graphics.drawLine(dbt*0.45 +xbt, dbt*0.45+this.y5-10*zoomRate, dbt/1.6+xbt, dbt/2  +this.y5-10*zoomRate);  //horizontal line
  this.graphics.drawLine(dbt*0.45 +xbt, dbt*0.45+this.y5-10*zoomRate, dbt/2.2+xbt, dbt/3.7+this.y5-10*zoomRate);  //vertical line

  this.graphics.setStroke(this.stroke-1);
  this.graphics.drawLine(xbt +24*zoomRate,ycbt  -3*zoomRate, xbt+20*zoomRate, ycbt            );  //10th min line
  this.graphics.drawLine(xbt +21*zoomRate,ycbt  +4*zoomRate, xbt+25*zoomRate, ycbt +4*zoomRate);  //15th min line
  this.graphics.drawLine(xbt +24*zoomRate,ycbt +11*zoomRate, xbt+19*zoomRate, ycbt +9*zoomRate);  //25th min line
  this.graphics.drawLine(xbt +15*zoomRate,ycbt +11*zoomRate, xbt+15*zoomRate, ycbt+14*zoomRate);  //30th min line
  this.graphics.drawLine(xbt +8 *zoomRate,ycbt +11*zoomRate, xbt+12*zoomRate, ycbt +8*zoomRate);  //40th min line
  this.graphics.drawLine(xbt +5 *zoomRate,ycbt  +4*zoomRate, xbt+8 *zoomRate, ycbt +4*zoomRate);  //45th min line
  this.graphics.drawLine(xbt +8 *zoomRate,ycbt  -4*zoomRate, xbt+11*zoomRate, ycbt -1*zoomRate);  //50th min line
  this.graphics.drawLine(xbt+15 *zoomRate,ycbt  -7*zoomRate, xbt+15*zoomRate, ycbt -4*zoomRate);  //60th min line

  if(this.boundaryEvent == true) {
    this.graphics.paint();
  }
  //****************Drawing Timer Boundary event ends here ****************

  //this.bpmnText.paint();
  
  //Code Added to Dynamically shift Ports on resizing of shapes
  if (this.input1 != null) {
    this.input1.setPosition(0, this.height / 2 -1);
  }
  if (this.output1 != null) {
    this.output1.setPosition(this.width / 2, this.height -3);
  }
  if (this.input2 != null) {
    this.input2.setPosition(this.width / 2, 0);
  }
  if (this.output2 != null) {
    this.output2.setPosition(this.width-3, this.height / 2-1);
  }

};


Figure.prototype.onDragend=function() {
  if(typeof workflow.currentSelection != 'undefined' && workflow.currentSelection != null){
    var currObj = workflow.currentSelection;
    currObj.orgXPos  = eval(currObj.getX()/workflow.zoomfactor);
    currObj.orgYPos  = eval(currObj.getY()/workflow.zoomfactor);
    //setPosition();
    if(typeof currObj.id != 'undefined' && currObj.id.length == 32){
      switch (currObj.type) {
        case 'bpmnTask':
        case 'bpmnSubProcess':
          currObj.actiontype = 'saveTaskPosition';
          currObj.workflow.savePosition(currObj);
          break;
        case 'bpmnAnnotation':
          currObj.actiontype = 'saveTextPosition';
          currObj.workflow.savePosition(currObj);
          break;
        default:
          if(currObj.type.match(/Gateway/)){
            currObj.actiontype = 'saveGatewayPosition';
            currObj.workflow.savePosition(currObj);
          }
          else if(currObj.type.match(/Event/)) {
            currObj.actiontype = 'saveEventPosition';
            currObj.workflow.savePosition(currObj);
          }
      }
    }
    workflow.setBoundary(currObj);
  }

  if(this.getWorkflow().getEnableSmoothFigureHandling()==true) {
    var _3dfe=this;
    var _3dff=function(){
      if(_3dfe.alpha<1){
        _3dfe.setAlpha(Math.min(1,_3dfe.alpha+0.05));
      }
      else {
        window.clearInterval(_3dfe.timer);
        _3dfe.timer=-1;
      }
    };
    if(_3dfe.timer>0){
      window.clearInterval(_3dfe.timer);
    }
    _3dfe.timer=window.setInterval(_3dff,20);
    }
    else{
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
    var TaskPositionY = [this.height-1, this.height / 2, this.height / 2, 0+1];

    for (var i = 0; i < TaskPortName.length; i++) {
      eval('this.' + TaskPortName[i] + ' = new ' + TaskPortType[i] + '()'); //Create New Port
      eval('this.' + TaskPortName[i] + '.setWorkflow(_40c5)'); //Add port to the workflow
      eval('this.' + TaskPortName[i] + '.setName("' + TaskPortName[i] + '")'); //Set PortName
      eval('this.' + TaskPortName[i] + '.setZOrder(-1)'); //Set Z-Order of the port to -1. It will be below all the figure
      eval('this.' + TaskPortName[i] + '.setBackgroundColor(new Color(255, 255, 255))'); //Setting Background of the port to white
      eval('this.' + TaskPortName[i] + '.setColor(new Color(255, 255, 255))'); //Setting Border of the port to white
      var oPort = eval('this.addPort(this.' + TaskPortName[i] + ',' + TaskPositionX[i] + ', ' + TaskPositionY[i] + ')'); //Setting Position of the port
      var test = oPort;
    }
  }
};

InputPort.prototype.onDrop = function (port) {
  if (port.getMaxFanOut && port.getMaxFanOut() <= port.getFanOut()) {
    return;
  }
  if (this.parentNode.id == port.parentNode.id) {
  } 
  else {
    var _4070 = new CommandConnect(this.parentNode.workflow, port, this);
    if (_4070.source.type == _4070.target.type) {
      return;
    }

    if(this.workflow.currentSelection.type.match(/Annotation/))    //Setting connection to Dotted for Annotation
        _4070.setConnection(new DottedConnection());
    else
        _4070.setConnection(new DecoratedConnection());

    this.parentNode.workflow.getCommandStack().execute(_4070);

    //Saving Start Event
    var preObj   = new Array();
    var bpmnType = this.workflow.currentSelection.type;

    //Routing from end event to task
    if(bpmnType.match(/End/) && bpmnType.match(/Event/) && port.parentNode.type.match(/Task/)) {
      preObj = this.workflow.currentSelection; //end event
      var newObj = port.parentNode;  //task
      newObj.conn = _4070.connection;
      newObj.reverse = 1;           //setting reverse parameter if user is routing from down to up
      this.workflow.saveRoute(preObj,newObj);
    }
    //Routing from task to start event
    else if(bpmnType.match(/Task/) && port.parentNode.type.match(/Event/)) {
      preObj = this.workflow.currentSelection;  //task
      newObj = port.parentNode;                 //start event
      var tas_uid = preObj.id;
      this.workflow.saveEvents(newObj,tas_uid);
    }
    //Routing from task to task
    else if(bpmnType.match(/Task/) && port.parentNode.type.match(/Task/)){
      preObj = workflow.currentSelection;
      newObj = port.parentNode;
      newObj.conn = _4070.connection;
      newObj.sPortType =port.properties.name;
      preObj.sPortType =this.properties.name;
      workflow.saveRoute(newObj,preObj);
    }
    //Routing from task to gateway
    else if(bpmnType.match(/Task/) && port.parentNode.type.match(/Gateway/)){
      var shape = new Array();
      shape.type = '';
      preObj = workflow.currentSelection;
      newObj = port.parentNode;
      workflow.saveRoute(newObj,shape);
    }
    //Routing from gateway to task
    else if(bpmnType.match(/Gateway/) && (port.parentNode.type.match(/Gateway/) || port.parentNode.type.match(/Task/))){
      preObj = this.workflow.currentSelection;
      newObj = port.parentNode;
      this.workflow.saveRoute(preObj,newObj);
    }
    //Routing from task to Intermediate event
    else if(port.parentNode.type.match(/Inter/) && port.parentNode.type.match(/Event/) && bpmnType.match(/Task/)){
      workflow.saveEvents(port.parentNode);
    }
    else if(port.parentNode.type.match(/Task/) && bpmnType.match(/Inter/) && bpmnType.match(/Event/)){
      workflow.saveEvents(workflow.currentSelection);
    }
    else if(bpmnType.match(/Annotation/)) { //Routing from task to Annotation
      newObj = port.parentNode;
      preObj = this.workflow.currentSelection;
      newObj.actiontype = 'updateText';
      preObj.actiontype = 'updateText';
      this.workflow.saveShape(preObj);
    }
  }
};

OutputPort.prototype.onDrop = function (port) {
  if (this.getMaxFanOut() <= this.getFanOut()) {
    return;
  }

  var connect = true;
  var conn = port.workflow.checkConnectionsExist(port, 'targetPort', 'OutputPort');
  if (conn == 0) //If no connection Exist then Allow connect
    connect = true;
  else 
  	if (conn < 2) //If One connection exist then Do not Allow to connect
      connect = false;

  if (this.parentNode.id == port.parentNode.id || connect == false) {
  } 
  else {
    var _4070 = new CommandConnect(this.parentNode.workflow, this, port);
    if (_4070.source.type == _4070.target.type) {
      return;
    }

    if(port.parentNode.type.match(/Annotation/))            //Setting connection to Dotted for Annotation
        _4070.setConnection(new DottedConnection());
    else
        _4070.setConnection(new DecoratedConnection());

    this.parentNode.workflow.getCommandStack().execute(_4070);

    //Saving Start Event
    var preObj = new Array();
    var bpmnType = this.workflow.currentSelection.type;
    if(bpmnType.match(/Event/) && port.parentNode.type.match(/Task/)){
      var tas_uid = port.parentNode.id;
      this.workflow.saveEvents(this.workflow.currentSelection,tas_uid);
    }
    else if(bpmnType.match(/Task/) && port.parentNode.type.match(/End/) && port.parentNode.type.match(/Event/)){
      preObj = this.workflow.currentSelection;
      var newObj = port.parentNode;
      newObj.conn = _4070.connection;
      this.workflow.saveRoute(preObj,newObj);
    }
    else if(port.parentNode.type.match(/Task/) && bpmnType.match(/Inter/) && bpmnType.match(/Event/)){
      this.workflow.saveEvents(workflow.currentSelection);
    }
    else if(port.parentNode.type.match(/Event/) && port.parentNode.type.match(/Inter/) && bpmnType.match(/Task/)){
      this.workflow.saveEvents(port.parentNode);
    }
    else if(bpmnType.match(/Task/) && port.parentNode.type.match(/Task/)){
      preObj = this.workflow.currentSelection;
      newObj = port.parentNode;
      newObj.conn = _4070.connection;
      newObj.sPortType =port.properties.name;
      preObj.sPortType =this.properties.name;
      this.workflow.saveRoute(preObj,newObj);
    }
    else if(bpmnType.match(/Gateway/) && (port.parentNode.type.match(/Task/) || port.parentNode.type.match(/Gateway/))){ //Routing from gateway to task
      var shape = new Array();
      shape.type = '';
      preObj = this.workflow.currentSelection;
      this.workflow.saveRoute(preObj,shape);
    }
    else if(bpmnType.match(/Task/) && port.parentNode.type.match(/Gateway/)){ //Routing from task to gateway
      newObj = port.parentNode;
      preObj = this.workflow.currentSelection;
      this.workflow.saveRoute(newObj,preObj);
    }
    else if(port.parentNode.type.match(/Annotation/)){ //Routing from task to Annotation
      newObj = port.parentNode;
      preObj = this.workflow.currentSelection;
      newObj.actiontype = 'updateText';
      this.workflow.saveShape(newObj);
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
    else {
      this.command.newTargetPort.parentNode.conn      = this.command.con;
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
    else{
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
  if(typeof currentSelection.id != 'undefined' && currentSelection.id.length == 32){
    if(currentSelection.type.match(/Task/)) {
      currentSelection.actiontype = 'saveTaskCordinates';
      workflow.savePosition(currentSelection);
    }
    else if(currentSelection.type.match(/Annotation/)){
      currentSelection.actiontype = 'saveAnnotationCordinates';
      workflow.savePosition(currentSelection);
    }
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


//dotted connection and its router
DottedConnectionRouter=function(_354a){
if(!_354a){
this.cheapRouter=new ManhattanConnectionRouter();
}else{
this.cheapRouter=null;
}
this.iteration=4;
};
DottedConnectionRouter.prototype=new ConnectionRouter;
DottedConnectionRouter.prototype.type="DottedConnectionRouter";
DottedConnectionRouter.prototype.drawBezier=function(_354b,_354c,t,iter){
  var n=_354b.length-1;
  var q=new Array();
  var _3551=n+1;
  for(var i=0;i<_3551;i++){
    q[i]=new Array();
    q[i][0]=_354b[i];
  }
  for(var j=1;j<=n;j++){
    for(var i=0;i<=(n-j);i++){
      q[i][j]=new Point((1-t)*q[i][j-1].x+t*q[i+1][j-1].x,(1-t)*q[i][j-1].y+t*q[i+1][j-1].y);
    }
  }
  var c1=new Array();
  var c2=new Array();
  for(var i=0;i<n+1;i++){
    c1[i]=q[0][i];
    c2[i]=q[i][n-i];
  }
  if(iter>=0){
    this.drawBezier(c1,_354c,t,--iter);
    this.drawBezier(c2,_354c,t,--iter);
  }
  else{
    for(var i=0;i<n;i++){
      _354c.push(q[i][n-i]);
    }
  }
};
DottedConnectionRouter.prototype.route=function(conn){
  if(this.cheapRouter!=null&&(conn.getSource().getParent().isMoving==true||conn.getTarget().getParent().isMoving==true)){
    this.cheapRouter.route(conn);
    return;
  }
  var arrayPoints=new Array();
  var fromPt=conn.getStartPoint();
  var toPt  =conn.getEndPoint();
  this._route(arrayPoints, conn, toPt, this.getEndDirection(conn), fromPt, this.getStartDirection(conn) );
  var _355a=new Array();
  this.drawBezier(arrayPoints,_355a,0.5,this.iteration);
  conn.addPoint(fromPt);
  for(var i=0;i<_355a.length;i++){
    conn.addPoint(_355a[i]);
  }
  conn.addPoint(toPt);
};
DottedConnectionRouter.prototype._route=function(_355c,conn,_355e,_355f,toPt,toDir){
  var TOL=0.1;
  var _3563=0.01;
  var _3564=90;
  var UP=0;
  var RIGHT=1;
  var DOWN=2;
  var LEFT=3;
  var xDiff=_355e.x-toPt.x;
  var yDiff=_355e.y-toPt.y;
  var point;
  var dir;
  if(((xDiff*xDiff)<(_3563))&&((yDiff*yDiff)<(_3563))){
    _355c.push(new Point(toPt.x,toPt.y));
    return;
  }
  if(_355f==LEFT){
    if((xDiff>0)&&((yDiff*yDiff)<TOL)&&(toDir==RIGHT)){
    point=toPt;
    dir=toDir;
    }else{
    if(xDiff<0){
    point=new Point(_355e.x-_3564,_355e.y);
    }else{
    if(((yDiff>0)&&(toDir==DOWN))||((yDiff<0)&&(toDir==UP))){
    point=new Point(toPt.x,_355e.y);
    }else{
    if(_355f==toDir){
    var pos=Math.min(_355e.x,toPt.x)-_3564;
    point=new Point(pos,_355e.y);
    }else{
    point=new Point(_355e.x-(xDiff/2),_355e.y);
    }
    }
    }
    if(yDiff>0){
    dir=UP;
    }else{
    dir=DOWN;
    }
    }
  }
  else{
  if(_355f==RIGHT){
  if((xDiff<0)&&((yDiff*yDiff)<TOL)&&(toDir==LEFT)){
  point=toPt;
  dir=toDir;
  }else{
  if(xDiff>0){
  point=new Point(_355e.x+_3564,_355e.y);
  }else{
  if(((yDiff>0)&&(toDir==DOWN))||((yDiff<0)&&(toDir==UP))){
  point=new Point(toPt.x,_355e.y);
  }else{
  if(_355f==toDir){
  var pos=Math.max(_355e.x,toPt.x)+_3564;
  point=new Point(pos,_355e.y);
  }else{
  point=new Point(_355e.x-(xDiff/2),_355e.y);
  }
  }
  }
  if(yDiff>0){
  dir=UP;
  }else{
  dir=DOWN;
  }
  }
  }else{
    if(_355f==DOWN){
    if(((xDiff*xDiff)<TOL)&&(yDiff<0)&&(toDir==UP)){
      point=toPt;
      dir=toDir;
    }
    else {
      if(yDiff>0){
        point=new Point(_355e.x,_355e.y+_3564);
      }
      else {
        if(((xDiff>0)&&(toDir==RIGHT))||((xDiff<0)&&(toDir==LEFT))){
          point=new Point(_355e.x,toPt.y);
        }
        else{
          if(_355f==toDir){
            var pos=Math.max(_355e.y,toPt.y)+_3564;
            point=new Point(_355e.x,pos);
          }
          else{
            point=new Point(_355e.x,_355e.y-(yDiff/2));
          }
        }
      }
      if(xDiff>0){
        dir=LEFT;
      }
      else{
        dir=RIGHT;
      }
    }
    }
    else {
      if(_355f==UP){
        if(((xDiff*xDiff)<TOL)&&(yDiff>0)&&(toDir==DOWN)){
          point=toPt;
          dir=toDir;
        }
        else{
          if(yDiff<0){
            point=new Point(_355e.x,_355e.y-_3564);
          }
          else{
            if(((xDiff>0)&&(toDir==RIGHT))||((xDiff<0)&&(toDir==LEFT))){
              point=new Point(_355e.x,toPt.y);
            }
            else{
              if(_355f==toDir){
                var pos=Math.min(_355e.y,toPt.y)-_3564;
                point=new Point(_355e.x,pos);
              }
              else{
                point=new Point(_355e.x,_355e.y-(yDiff/2));
              }
            }
          }
          if(xDiff>0){
            dir=LEFT;
          }
          else{
            dir=RIGHT;
          }
        }
      }
    }
    }
  }
  this._route(_355c,conn,point,dir,toPt,toDir);
  _355c.push(_355e);
};

DottedConnection = function () {
  Connection.call(this);   
  this.setColor(new Color(0,0,80));
  this.setRouter(new DottedConnectionRouter());
  this.setRouter(new FanConnectionRouter());
};
DottedConnection.prototype = new Connection();
DottedConnection.prototype.type = "DottedConnection";
DottedConnection.prototype.addPoint=function(p){
  p=new Point(parseInt(p.x),parseInt(p.y));
  var bgColor = new Color(255,255,255);
  var fgColor = new Color(0,0,128);
  if(this.oldPoint!=null){
    //this.graphics.setColor(new Color(250,250,250));
    this.graphics.setColor(bgColor.getHTMLStyle());
    this.graphics.setStroke( 2);
    this.graphics.drawLine(this.oldPoint.x,this.oldPoint.y,p.x,p.y);
    this.graphics.setColor(fgColor.getHTMLStyle());
    this.graphics.setStroke( Stroke.DOTTED );
    this.graphics.drawLine(this.oldPoint.x,this.oldPoint.y,p.x,p.y);
    //this.graphics.drawLine(p.x,p.y,p.x,p.y);
    var line=new Object();
    line.start=this.oldPoint;
    line.end=p;
    this.lineSegments.add(line);
  }
  this.oldPoint=new Object();
  this.oldPoint.x=p.x;
  this.oldPoint.y=p.y;
};
DottedConnection.prototype.getContextMenu = function () {
  if (this.id != null) {
    this.workflow.contextClicked = true;
    this.workflow.connectionContextMenu(this);
  }
};

////////--------------------------------------------------------------------------------------------///////
FlowMenu = function (_39f9) {
  this.actionAdd        = new ButtonAdd(this);
  this.actionTask       = new ButtonTask(this);
  this.actionInterEvent = new ButtonInterEvent(this);
  this.actionEndEvent   = new ButtonEndEvent(this);
  this.actionGateway    = new ButtonGateway(this);
  this.actionFront      = new ButtonMoveFront(this);
  this.actionBack       = new ButtonMoveBack(this);
  this.actionDelete     = new ButtonDelete(this);
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
  //Check wheather the figure selected is same as previous figure.
  //If figure is different ,then remove the port from the previous selected figure.
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
    var countConn = 0;
    //workflow.setBoundary(workflow.currentSelection);
  
    //Preventing Task from drawing outside canvas Code Ends here
    if (_39fd.type == 'DecoratedConnection' || _39fd.type == 'DottedConnection' || _39fd.workflow.contextClicked == true) {
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
                this.removechild(this.actionInterEvent);
                this.removechild(this.actionEndEvent);
                this.removechild(this.actionAnnotation);
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
                ports = ['output1', 'input1', 'output2', 'input2','output3'];
                _39fd.workflow.enablePorts(_39fd, ports);
            }
            else if (bpmnShape.match(/Annotation/) || bpmnShape.match(/Dataobject/)) {
                this.removechild(this.actionAdd);
                this.removechild(this.actionAnnotation);
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

bpmnTask.prototype.addShapes = function (oStore) {
    var xOffset = workflow.currentSelection.getX(); //Get x co-ordinate from figure
    var y       = workflow.currentSelection.getY(); //Get y co-ordinate from figure
    //var xOffset = parseFloat(x + _3896.workflow.currentSelection.width); //Get x-offset co-ordinate from figure
    var yOffset = parseFloat(y + workflow.currentSelection.height + 25); //Get y-offset co-ordinate from figure
    var shape   = workflow.currentSelection.type;
    var count;
    if (oStore.newShapeName == 'bpmnTask' && shape.match(/Event/)) {
        xOffset = workflow.currentSelection.getX() - 67; //Setting new offset value when currentselection is not Task i.e deriving task from events
    }
    else if (oStore.newShapeName == 'bpmnTask' && shape.match(/Gateway/)) {
        xOffset = workflow.currentSelection.getX() - 62; //Setting new offset value when currentselection is not Task i.e deriving task from gateways
    }
    else if (oStore.newShapeName.match(/Gateway/) && shape.match(/Gateway/)) {
        xOffset = workflow.currentSelection.getX(); //Setting new offset value when currentselection is not Task i.e deriving task from gateways
    }
    else if (oStore.newShapeName.match(/Event/)) {
        xOffset = workflow.currentSelection.getX() + 67; //Setting new offset value when newShape is not Task i.e aligning events
    }
    else if (oStore.newShapeName.match(/Gateway/)) {
        xOffset = workflow.currentSelection.getX() + 62; 
    }
    else if (oStore.newShapeName.match(/Annotation/) ) {
        xOffset = workflow.currentSelection.getX() + 250; 
        yOffset = workflow.currentSelection.getY() - 10.5; 
    }

    workflow.subProcessName = 'Sub Process';
    workflow.annotationName = 'Annotation';
    var newShape = eval("new " + oStore.newShapeName + "(workflow)");
    workflow.addFigure(newShape, xOffset, yOffset);
    //Assigning values to newShape Object for Saving Task automatically (Async Ajax Call)
    newShape.x = xOffset;
    newShape.y = yOffset;

    if(shape.match(/Annotation/) || oStore.newShapeName.match(/Annotation/))
        var conn = new DottedConnection();
    else
            conn = new DecoratedConnection();
    
    if (newShape.type.match(/Gateway/)) {
        conn.setTarget(newShape.getPort("input2"));
        conn.setSource(workflow.currentSelection.getPort("output1"));
        workflow.addFigure(conn);
        newShape.actiontype = 'addGateway';
        workflow.saveShape(newShape);
    }
    else if (newShape.type.match(/Start/)) {
        conn.setTarget(newShape.getPort("output1"));
        conn.setSource(workflow.currentSelection.getPort("input2"));
        workflow.addFigure(conn);
    }
    else if (newShape.type.match(/Event/)) {
        conn.setTarget(newShape.getPort("input2"));
        conn.setSource(workflow.currentSelection.getPort("output1"));
        workflow.addFigure(conn);
        newShape.conn = conn;
        newShape.actiontype = 'addEvent';
        workflow.saveShape(newShape);
    }
    else if (newShape.type.match(/Task/)) {
        conn.setTarget(newShape.getPort("input2"));
        conn.setSource(workflow.currentSelection.getPort("output1"));
        workflow.addFigure(conn);
    }
    else if (newShape.type.match(/Annotation/)) {
        conn.setTarget(newShape.getPort("input1"));
        conn.setSource(workflow.currentSelection.getPort("output2"));
        workflow.addFigure(conn);
        newShape.actiontype = 'addText';
        newShape.conn = conn;
        workflow.saveShape(newShape); //Saving Task automatically (Async Ajax Call)
    }
    /*if (oStore.newShapeName.match(/Event/) && oStore.newShapeName.match(/End/)) {
        newShape.conn = conn;
        workflow.saveRoute(workflow.currentSelection,newShape);
    }*/
    if (oStore.newShapeName == 'bpmnTask') {
        newShape.actiontype = 'addTask';
        newShape.conn = conn;
        workflow.saveShape(newShape); //Saving Task automatically (Async Ajax Call)
    }
}

ButtonInterEvent = function (_30a8) {
  Button.call(this, _30a8, 16, 16);
};
ButtonInterEvent.prototype = new Button;
ButtonInterEvent.prototype.type = "/images/ext/gray/shapes/interevent";
ButtonInterEvent.prototype.execute = function () {
  var count = 0;
  this.palette.newShapeName = 'bpmnEventEmptyInter';
  bpmnTask.prototype.addShapes(this.palette);
};

ButtonEndEvent = function (_30a8) {
    Button.call(this, _30a8, 16, 16);
};
ButtonEndEvent.prototype = new Button;
ButtonEndEvent.prototype.type = "/images/ext/gray/shapes/endevent";
ButtonEndEvent.prototype.execute = function () {
  var count = 0;
  this.palette.newShapeName = 'bpmnEventEmptyEnd';
  bpmnTask.prototype.addShapes(this.palette);
};

ButtonGateway = function (_30a8) {
  Button.call(this, _30a8, 16, 16);
};
ButtonGateway.prototype = new Button;
ButtonGateway.prototype.type = "/images/ext/gray/shapes/gateway-small";
ButtonGateway.prototype.execute = function () {
  this.palette.newShapeName = 'bpmnGatewayExclusiveData';
  workflow.preSelectedObj = workflow.currentSelection;
  bpmnTask.prototype.addShapes(this.palette);
};

ButtonAnnotation = function (_30a8) {
  Button.call(this, _30a8, 16, 16);
};
ButtonAnnotation.prototype = new Button;
ButtonAnnotation.prototype.type = "/images/ext/gray/shapes/annotation";
ButtonAnnotation.prototype.execute = function () {
  var count = 0;
  this.palette.newShapeName = 'bpmnAnnotation';
  this.palette.workflow.preSelectedObj = this.palette.workflow.currentSelection;
  bpmnTask.prototype.addShapes(this.palette);
};

ButtonTask = function (_30a8) {
  Button.call(this, _30a8, 16, 16);
};
ButtonTask.prototype = new Button;
ButtonTask.prototype.type = "/images/ext/gray/shapes/Task";
ButtonTask.prototype.execute = function () {
  this.palette.newShapeName = 'bpmnTask';
  bpmnTask.prototype.addShapes(this.palette);
};


ButtonAdd = function (_30a8) {
  Button.call(this, _30a8, 16, 16);
};
ButtonAdd.prototype = new Button;
ButtonAdd.prototype.type = "/images/ext/gray/shapes/btn-add";
ButtonAdd.prototype.execute = function () {
  this.palette.newShapeName = 'bpmnTask';
  this.palette.workflow.preSelectedObj = this.palette.workflow.currentSelection;
  bpmnTask.prototype.addShapes(this.palette);
};

ButtonDelete = function (_30a9) {
  Button.call(this, _30a9, 16, 16);
};
ButtonDelete.prototype = new Button;
ButtonDelete.prototype.type = "/images/ext/gray/shapes/btn-del";
ButtonDelete.prototype.execute = function () {
  workflow.hideResizeHandles();
  workflow.getDeleteCriteria();
};
ButtonMoveFront = function (_3e22) {
  Button.call(this, _3e22, 16, 16);
};
ButtonMoveFront.prototype = new Button;
ButtonMoveFront.prototype.type = "/images/ext/gray/shapes/btn-movefrnt";
ButtonMoveFront.prototype.execute = function () {
  this.palette.workflow.moveFront(this.palette.workflow.getCurrentSelection());
  ToolGeneric.prototype.execute.call(this);
};
ButtonMoveBack = function (_4091) {
  Button.call(this, _4091, 16, 16);
};
ButtonMoveBack.prototype = new Button;
ButtonMoveBack.prototype.type = "/images/ext/gray/shapes/btn-movebk";
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

//Double Click Event for opening the dialog Box
bpmnTask.prototype.onDoubleClick = function () {
  var _409d = new bpmnTaskDialog(this);
  workflow.showDialog(_409d, this.workflow.currentSelection.x, this.workflow.currentSelection.y);
};

/**
 * erik: Setting task target to Drop user & group assignment
 */
bpmnTask.prototype.onMouseEnter = function () {
  if( this.type == 'bpmnTask' ) {
    _targetTask = {id: this.id, name: this.taskName};
  }
};

bpmnTask.prototype.trim = function (str) {
  if (str != null) 
    return str.replace(/^\s+|\s+$/g, '');
  else 
  	return null;
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
  this.figure.bpmnText.setFont('verdana', +this.figure.fontSize+'px', Font.PLAIN);
  this.figure.bpmnText.drawStringRect(this.input.value, padleft, padtop, this.figure.rectWidth, rectheight, 'center');
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
