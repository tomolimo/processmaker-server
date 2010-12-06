Ext.onReady ( function() {
	
  var workflow  = new MyWorkflow("paintarea");
  workflow.setEnableSmoothFigureHandling(true);
  workflow.scrollArea.width = 2000;
   /*  var bpmnObj   = new bpmnTask();
  workflow.addFigure(bpmnObj,350,150);*/

 /* var bpmnObj   = new bpmnTask();
  workflow.addFigure(bpmnObj,250,150);*/

    // Add an simple annotation to the canvas
  //
/*  var annotation = new Annotation("this is my version of new ProcessMap");
  annotation.setDimension(234,30);
  workflow.addFigure(annotation,500,75);*/
	
  /*var bpmnObj   = new bpmnTask();
  workflow.addFigure(bpmnObj,50,50);*/
  

  /**********************************************************************************
   * 
   * Do the Ext (Yahoo UI) Stuff
   *
   **********************************************************************************/


var west= {
            xtype	:	"panel",
            split:true,
            initialSize: 200,
            width: 100,
            minSize: 305,
            maxSize: 400,
            titlebar: true,
            collapsible: true,
            autoScroll:true,
            animate: true,
            region	:	"west",
            items: {
                  region: 'center',
                  layout:'accordion',
                  layoutConfig:{animate:true},
                  items: [{
                          title: 'Shapes',
                          //html:'<p>Text Annotation <br>Task<br>Looping Task<br>Flow Connector<br>Message Connection<br>Association<br>Pool<br>Sub-ProcessMap<br>Looping Sub-Process<br>Lane<br>Group<br>Data Object<br>',
                          html:'<ul id="x-basicshapes"><li id="x-shapes-text" title="Text Annotation" >&nbsp;</li>\n\
                                             <li id="x-shapes-task" title="Task">&nbsp;</li>\n\
                                             <li id="x-shapes-loop" title="Looping Task">&nbsp;</li>\n\
                                             <li id="x-shapes-flow" title="Flow Connector">&nbsp;</li>\n\
                                             <li id="x-shapes-group" title="Group">&nbsp;</li>\n\
                                             <li id="x-shapes-loopsub" title="Looping Sub-Process">&nbsp;</li>\n\
                                             <li id="x-shapes-assoc" title="Association">&nbsp;</li>\n\
                                             <li id="x-shapes-msgconn" title="Message connection">&nbsp;</li>\n\
                                             <li id="x-shapes-lane" title="Lane">&nbsp;</li>\n\
                                             <li id="x-shapes-dataObject" title="Data Object">&nbsp;</li></ul>',
                          cls:'empty'
                         },
                         {
                          title: 'Events',
                      //    html: ' Empty Start<br>Message Start<br>Rule Start<br>Timer Start<br>Signal start event<br>Multiple start event<br>Link start<br> Empty Intermediate<br>Message Intermediate<br>Timer Intermediate<br>Error Intermediate<br>Compensation Intermediate<br>Rule Intermediate<br>Cancel Intermediate<br>Intermediate signal event<br>Multiple Intermediate event<br>Link Intermediate event<br>Empty End<br>Message End<br>Error End<br>Compensation End<br>Terminate<br>End signal event<br>Multiple end event<br>Cancel end<br>Link end<br>',
                          html:'<ul id="x-event-shapes"><li id="x-shapes-event-empty" title="Empty Start" >&nbsp;</li>\n\
                                             <li id="x-shapes-event-msgstart" title="Message Start">&nbsp;</li>\n\
                                             <li id="x-shapes-event-rule" title="Rule Start">&nbsp;</li>\n\
                                             <li id="x-shapes-event-timerstart" title="Timer Start">&nbsp;</li>\n\
                                             <li id="x-shapes-event-sigstart" title="Signal start event">&nbsp;</li>\n\
                                             <li id="x-shapes-event-mulstart" title="Multiple start event">&nbsp;</li>\n\
                                             <li id="x-shapes-event-linkstart" title="Link start">&nbsp;</li>\n\
                                             <li id="x-shapes-event-emptyinter" title="Empty Intermediate">&nbsp;</li>\n\
                                             <li id="x-shapes-event-msgconn" title="Message Intermediate">&nbsp;</li>\n\
                                             <li id="x-shapes-event-timerinter" title="Timer Intermediate">&nbsp;</li>\n\
                                             <li id="x-shapes-event-errorinter" title="Error Intermediate">&nbsp;</li>\n\
                                             <li id="x-shapes-event-compinter" title="Compensation Intermediate">&nbsp;</li>\n\
                                             <li id="x-shapes-event-ruleinter" title="Rule Intermediate">&nbsp;</li>\n\
                                             <li id="x-shapes-event-caninter" title="Cancel Intermediate">&nbsp;</li>\n\
                                             <li id="x-shapes-event-intersig" title="Intermediate signal">&nbsp;</li>\n\
                                             <li id="x-shapes-event-mulinter" title="Multiple Intermediate">&nbsp;</li>\n\
                                             <li id="x-shapes-event-linkinter" title="Link Intermediate">&nbsp;</li>\n\
                                             <li id="x-shapes-event-emptyend" title="Empty End">&nbsp;</li>\n\
                                             <li id="x-shapes-event-messageend" title="Message End">&nbsp;</li>\n\
                                             <li id="x-shapes-event-errorend" title="Error End">&nbsp;</li>\n\
                                             <li id="x-shapes-event-compend" title="Compensation End">&nbsp;</li>\n\
                                             <li id="x-shapes-event-terminate" title="Terminate">&nbsp;</li>\n\
                                             <li id="x-shapes-event-endsignal" title="End signal">&nbsp;</li>\n\
                                             <li id="x-shapes-event-multipleend" title="Multiple end">&nbsp;</li>\n\
                                             <li id="x-shapes-event-cancelend" title="Cancel end">&nbsp;</li>\n\
                                             <li id="x-shapes-event-linkend" title="Link end">&nbsp;</li></ul>',
                          cls:'empty'
                         },
                         {
                          title: 'Gateway',
                          //html: 'Exclusive Data-Based<br>Exclusive Event-Based<br>Inclusive Data-Based<br>Parallel<br> Complex<br>',
                          html:'<ul id="x-gateways-shapes"><li id="x-shapes-gateways-exc-data" title="Exclusive Data-Based" >&nbsp;</li>\n\
                                             <li id="x-shapes-gateways-exc-event" title="Exclusive Event-Based">&nbsp;</li>\n\
                                             <li id="x-shapes-gateways-inc-data" title="Inclusive Data-Based">&nbsp;</li>\n\
                                             <li id="x-shapes-gateways-parallel" title="Parallel">&nbsp;</li>\n\
                                             <li id="x-shapes-gateways-complex" title="Complex">&nbsp;</li></ul>',
                          cls:'empty'
                         }]
              }
          };
var north= {xtype	:	"panel",
            initialSize: 60,
              split:false,
              titlebar: false,
              collapsible: false,
              animate: false,
              region	:	"north",
              
            };
var south= {xtype	:	"panel",
            initialSize: 120,
            height: 100,
            split:true,
            titlebar: false,
            collapsible: true,
            autoScroll:true,
            animate: true,
            region	:	"south",
            items: {
                  region: 'center',
                  xtype: 'tabpanel',
                  items: [{
                          title: 'Properties',
                          html: 'Properties'
                         },
                         {
                          title: 'Debug Console',
                          html: 'Debug Console'
                         }]
              }
          };
var center= {width:100, 
            height:200,
            xtype	:	"panel",
            titlebar: true,
            title   :   "center region" , 
            autoScroll:true,
            fitToFrame:true,
            region	:	"center"
            
          };


  var main = new Ext.Panel({
        tbar: [  
          {text:'Save'}, 
          {text:'Save as'},  
          {text:'Undo'},  
          {text:'Redo'}
          
      ],  
      renderTo  : "center1",
      layout    : "border",
      height    : 1000,
      width :1000,
      items   : [west,north,south,center]
    });

var canvas = Ext.get('ext-gen68');

 contextCanvasMenu = new Ext.menu.Menu({
        items: [{
            text: 'Edit Process',
            handler: workflow.editProcess,
            scope: this
        }, {
            text: 'Export Process',
            handler: workflow.exportProcess,
            scope: this
        }, {
            text: 'Add Task',
            handler: workflow.addTask,
            scope: this
        }, {
            text: 'Add Subprocess',
            handler: workflow.subProcess,
            scope: this
        }, {
            text: 'Horizontal Line',
            handler: workflow.horiLine,
            scope: this
        }, {
            text: 'Vertical Line',
            handler: workflow.vertiLine,
            scope: this
        }, {
            text: 'Delete All Lines',
            handler: workflow.delLines,
            scope: this
        }, {
            text: 'Process Permission',
            handler: workflow.processPermission,
            scope: this
        }, {
            text: 'Web Entry',
            handler: workflow.webEntry,
            scope: this
        }, {
            text: 'Case Tracker',
            handler: workflow.caseTracker,
            scope: this
        }, {
            text: 'Process File Manager',
            handler: workflow.processFileManager,
            scope: this
        }, {
            text: 'Events',
            handler: workflow.events,
            scope: this
        }]
    });

canvas.on('contextmenu', function(e) {
    e.stopEvent();
    this.contextCanvasMenu.showAt(e.getXY());
}, this);

var simpleToolbar = new Ext.Toolbar('toolbar');
   simpleToolbar.addButton({ text: 'Save', cls: 'x-btn-text-icon scroll-bottom'});
   simpleToolbar.addButton({ text: 'Save As', cls: 'x-btn-text-icon scroll-bottom'});
   simpleToolbar.addButton({ text: 'Undo', cls: 'x-btn-text-icon'});
   simpleToolbar.addButton({ text: 'Redo', cls: 'x-btn-text-icon'});

  var menu = new FlowMenu(workflow);
  workflow.addSelectionListener(menu);
  workflow.scrollArea = document.getElementById("ext-gen68").parentNode;

  var dragsource=new Ext.dd.DragSource("x-shapes-text", {ddGroup:'TreeDD',dragData:{name: "bpmnAnnotation"}});
  var dragsource=new Ext.dd.DragSource("x-shapes-task", {ddGroup:'TreeDD',dragData:{name: "bpmnTask"}});
  var dragsource=new Ext.dd.DragSource("x-shapes-loop", {ddGroup:'TreeDD',dragData:{name: "bpmnLoopingTask"}});
  var dragsource=new Ext.dd.DragSource("x-shapes-flow", {ddGroup:'TreeDD',dragData:{name: "bpmnFlowConnector"}});
  var dragsource=new Ext.dd.DragSource("x-shapes-group", {ddGroup:'TreeDD',dragData:{name: "bpmnGroup"}});
  var dragsource=new Ext.dd.DragSource("x-shapes-loopsub", {ddGroup:'TreeDD',dragData:{name: "bpmnLoopingSubProcess"}});

  var dragsource=new Ext.dd.DragSource("x-shapes-event-empty", {ddGroup:'TreeDD',dragData:{name: "bpmnEventEmptyStart"}});
  var dragsource=new Ext.dd.DragSource("x-shapes-event-msgstart", {ddGroup:'TreeDD',dragData:{name: "bpmnEventMessageStart"}});
  var dragsource=new Ext.dd.DragSource("x-shapes-event-rule", {ddGroup:'TreeDD',dragData:{name: "bpmnEventRuleStart"}});
  var dragsource=new Ext.dd.DragSource("x-shapes-event-timerstart", {ddGroup:'TreeDD',dragData:{name: "bpmnEventTimerStart"}});
  var dragsource=new Ext.dd.DragSource("x-shapes-event-sigstart", {ddGroup:'TreeDD',dragData:{name: "bpmnEventSignalStart"}});
  var dragsource=new Ext.dd.DragSource("x-shapes-event-mulstart", {ddGroup:'TreeDD',dragData:{name: "bpmnEventMulStart"}});
  var dragsource=new Ext.dd.DragSource("x-shapes-event-linkstart", {ddGroup:'TreeDD',dragData:{name: "bpmnEventLinkStart"}});
  var dragsource=new Ext.dd.DragSource("x-shapes-event-emptyinter", {ddGroup:'TreeDD',dragData:{name: "bpmnEventEmptyInter"}});
  var dragsource=new Ext.dd.DragSource("x-shapes-event-msgconn", {ddGroup:'TreeDD',dragData:{name: "bpmnEventMessageInter"}});
  var dragsource=new Ext.dd.DragSource("x-shapes-event-timerinter", {ddGroup:'TreeDD',dragData:{name: "bpmnEventTimerInter"}});
  var dragsource=new Ext.dd.DragSource("x-shapes-event-errorinter", {ddGroup:'TreeDD',dragData:{name: "bpmnEventErrorInter"}});
  var dragsource=new Ext.dd.DragSource("x-shapes-event-compinter", {ddGroup:'TreeDD',dragData:{name: "bpmnEventCompInter"}});
  var dragsource=new Ext.dd.DragSource("x-shapes-event-ruleinter", {ddGroup:'TreeDD',dragData:{name: "bpmnEventRuleInter"}});
  var dragsource=new Ext.dd.DragSource("x-shapes-event-caninter", {ddGroup:'TreeDD',dragData:{name: "bpmnEventCancelInter"}});
  var dragsource=new Ext.dd.DragSource("x-shapes-event-intersig", {ddGroup:'TreeDD',dragData:{name: "bpmnEventInterSignal"}});
  var dragsource=new Ext.dd.DragSource("x-shapes-event-mulinter", {ddGroup:'TreeDD',dragData:{name: "bpmnEventMultipleInter"}});
  var dragsource=new Ext.dd.DragSource("x-shapes-event-linkinter", {ddGroup:'TreeDD',dragData:{name: "bpmnEventLinkInter"}});
  var dragsource=new Ext.dd.DragSource("x-shapes-event-emptyend", {ddGroup:'TreeDD',dragData:{name: "bpmnEventEmptyEnd"}});
  var dragsource=new Ext.dd.DragSource("x-shapes-event-messageend", {ddGroup:'TreeDD',dragData:{name: "bpmnEventMessageEnd"}});
  var dragsource=new Ext.dd.DragSource("x-shapes-event-errorend", {ddGroup:'TreeDD',dragData:{name: "bpmnEventErrorEnd"}});
  var dragsource=new Ext.dd.DragSource("x-shapes-event-compend", {ddGroup:'TreeDD',dragData:{name: "bpmnEventCompEnd"}});
  var dragsource=new Ext.dd.DragSource("x-shapes-event-terminate", {ddGroup:'TreeDD',dragData:{name: "bpmnEventTerminate"}});
  var dragsource=new Ext.dd.DragSource("x-shapes-event-endsignal", {ddGroup:'TreeDD',dragData:{name: "bpmnEventEndSignal"}});
  var dragsource=new Ext.dd.DragSource("x-shapes-event-multipleend", {ddGroup:'TreeDD',dragData:{name: "bpmnEventMultipleEnd"}});
  var dragsource=new Ext.dd.DragSource("x-shapes-event-cancelend", {ddGroup:'TreeDD',dragData:{name: "bpmnEventCancelEnd"}});
  var dragsource=new Ext.dd.DragSource("x-shapes-event-linkend", {ddGroup:'TreeDD',dragData:{name: "bpmnEventLinkEnd"}});

  var dragsource=new Ext.dd.DragSource("x-shapes-gateways-exc-data", {ddGroup:'TreeDD',dragData:{name: "bpmnExclusiveData"}});
  var dragsource=new Ext.dd.DragSource("x-shapes-gateways-exc-event", {ddGroup:'TreeDD',dragData:{name: "bpmnExclusiveEvent"}});
  var dragsource=new Ext.dd.DragSource("x-shapes-gateways-inc-data", {ddGroup:'TreeDD',dragData:{name: "bpmnInclusiveData"}});
  var dragsource=new Ext.dd.DragSource("x-shapes-gateways-parallel", {ddGroup:'TreeDD',dragData:{name: "bpmnEventParallel"}});
  var dragsource=new Ext.dd.DragSource("x-shapes-gateways-complex", {ddGroup:'TreeDD',dragData:{name: "bpmnEventComplex"}});
  var droptarget=new Ext.dd.DropTarget("ext-gen68",{ddGroup:'TreeDD'});

   workflow.taskNo= 0; //Initializing Count for the bpmnTask
   var count = 0;
   this.taskName='';
  droptarget.notifyDrop=function(dd, e, data)
        {
          if(data.name)
          {
           if(data.name == 'bpmnTask')
                {
                    count = ++workflow.taskNo; //Incrementing Task No and assigning it to a local variable
                }
            var xOffset    = workflow.getAbsoluteX();
            var yOffset    = workflow.getAbsoluteY();
            var scrollLeft = workflow.getScrollLeft();
            var scrollTop  = workflow.getScrollTop();
            workflow.addFigure(eval("new "+data.name+"("+count+")"),e.xy[0]-xOffset+scrollLeft,e.xy[1]-yOffset+scrollTop);
            return true;
          }
        }
        
});


/*
End=function(){
ImageFigure.call(this,this.type+".png");
this.inputPort=null;
this.setDimension(50,50);
};
End.prototype=new ImageFigure;
End.prototype.type="End";
End.prototype.setWorkflow=function(_4087){
ImageFigure.prototype.setWorkflow.call(this,_4087);
if(_4087!=null&&this.inputPort==null){
this.inputPort=new MyInputPort();
this.inputPort.setWorkflow(_4087);
this.inputPort.setBackgroundColor(new Color(115,115,245));
this.inputPort.setColor(null);
this.addPort(this.inputPort,0,this.height/2);
this.inputPort2=new MyInputPort();
this.inputPort2.setWorkflow(_4087);
this.inputPort2.setBackgroundColor(new Color(115,115,245));
this.inputPort2.setColor(null);
this.addPort(this.inputPort2,this.width/2,0);
this.inputPort3=new MyInputPort();
this.inputPort3.setWorkflow(_4087);
this.inputPort3.setBackgroundColor(new Color(115,115,245));
this.inputPort3.setColor(null);
this.addPort(this.inputPort3,this.width,this.height/2);
this.inputPort4=new MyInputPort();
this.inputPort4.setWorkflow(_4087);
this.inputPort4.setBackgroundColor(new Color(115,115,245));
this.inputPort4.setColor(null);
this.addPort(this.inputPort4,this.width/2,this.height);
}
};*/
/*
MyOutputPort=function(_32c8){
OutputPort.call(this,_32c8);
};
MyOutputPort.prototype=new OutputPort;
MyOutputPort.prototype.type="MyOutputPort";
MyOutputPort.prototype.onDrop=function(port){
if(this.getMaxFanOut()<=this.getFanOut()){
return;
}
if(this.parentNode.id==port.parentNode.id){
}else{
var _32ca=new CommandConnect(this.parentNode.workflow,this,port);
_32ca.setConnection(new ContextmenuConnection());
this.parentNode.workflow.getCommandStack().execute(_32ca);
}
};

MyInputPort=function(_3e4f){
InputPort.call(this,_3e4f);
};
MyInputPort.prototype=new InputPort;
MyInputPort.prototype.type="MyInputPort";
MyInputPort.prototype.onDrop=function(port){
if(port.getMaxFanOut&&port.getMaxFanOut()<=port.getFanOut()){
return;
}
if(this.parentNode.id==port.parentNode.id){
}else{
var _3e51=new CommandConnect(this.parentNode.workflow,port,this);
_3e51.setConnection(new ContextmenuConnection());
this.parentNode.workflow.getCommandStack().execute(_3e51);
}
};
*/
/*
ResizeImage=function(url){
this.url=url;
Node.call(this);
this.outputPort1=null;
this.outputPort2=null;
this.setDimension(100,100);
this.setColor(null);
};
ResizeImage.prototype=new Node;
ResizeImage.prototype.type="ResizeImage";
ResizeImage.prototype.createHTMLElement=function(){
var item=Node.prototype.createHTMLElement.call(this);
if(navigator.appName.toUpperCase()=="MICROSOFT INTERNET EXPLORER"){
this.d=document.createElement("div");
this.d.style.position="absolute";
this.d.style.left="0px";
this.d.style.top="0px";
this.d.style.filter="progid:DXImageTransform.Microsoft.AlphaImageLoader (src='"+this.url+"', sizingMethod='scale')";
item.appendChild(this.d);
}else{
this.img=document.createElement("img");
this.img.style.position="absolute";
this.img.style.left="0px";
this.img.style.top="0px";
this.img.src=this.url;
item.appendChild(this.img);
this.d=document.createElement("div");
this.d.style.position="absolute";
this.d.style.left="0px";
this.d.style.top="0px";
item.appendChild(this.d);
}
item.style.left=this.x+"px";
item.style.top=this.y+"px";
return item;
};
ResizeImage.prototype.setDimension=function(w,h){
Node.prototype.setDimension.call(this,w,h);
if(this.d!=null){
this.d.style.width=this.width+"px";
this.d.style.height=this.height+"px";
}
if(this.img!=null){
this.img.width=this.width;
this.img.height=this.height;
}
if(this.outputPort1!=null){
this.outputPort1.setPosition(this.width+3,this.height/3);
this.outputPort2.setPosition(this.width+3,this.height/3*2);
}
};
ResizeImage.prototype.setWorkflow=function(_309d){
Node.prototype.setWorkflow.call(this,_309d);
if(_309d!=null){
this.outputPort1=new OutputPort();
this.outputPort1.setMaxFanOut(1);
this.outputPort1.setWorkflow(_309d);
this.outputPort1.setBackgroundColor(new Color(245,115,115));
this.addPort(this.outputPort1,this.width+3,this.height/3);
this.outputPort2=new OutputPort();
this.outputPort2.setMaxFanOut(1);
this.outputPort2.setWorkflow(_309d);
this.outputPort2.setBackgroundColor(new Color(245,115,115));
this.addPort(this.outputPort2,this.width+3,this.height/3*2);
}
};
*/
ContextmenuConnection=function(){
Connection.call(this);
this.sourcePort=null;
this.targetPort=null;
this.lineSegments=new Array();
this.setColor(new Color(128,128,255));
this.setLineWidth(1);
};
ContextmenuConnection.prototype=new Connection();
ContextmenuConnection.prototype.getContextMenu=function(){
var menu=new Menu();
var oThis=this;
menu.appendMenuItem(new MenuItem("NULL Router",null,function(){
oThis.setRouter(null);
}));
menu.appendMenuItem(new MenuItem("Manhatten Router",null,function(){
oThis.setRouter(new ManhattanConnectionRouter());
}));
menu.appendMenuItem(new MenuItem("Bezier Router",null,function(){
oThis.setRouter(new BezierConnectionRouter());
}));
menu.appendMenuItem(new MenuItem("Fan Router",null,function(){
oThis.setRouter(new FanConnectionRouter());
}));
return menu;
};

function debug(msg)
{
  var console = document.getElementById("debug");
  console.innerHTML=console.innerHTML+"<br>"+msg;
}
