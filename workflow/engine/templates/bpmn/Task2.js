Task2=function(){
this.cornerWidth=15;
this.cornerHeight=15;
Node.call(this);
this.setDimension(100,100);
this.originalHeight=-1;
};

Task2.prototype=new Node;
Task2.prototype.type="Task2";
Task2.prototype.createHTMLElement=function(){
var item=document.createElement("div");
item.id=this.id;
item.style.position="absolute";
item.style.left=this.x+"px";
item.style.top=this.y+"px";
item.style.height=this.width+"px";
item.style.width=this.height+"px";
item.style.margin="0px";
item.style.padding="0px";
item.style.outline="none";
item.style.zIndex=""+Figure.ZOrderBaseIndex;
this.top_left=document.createElement("div");
this.top_left.style.background="url(/skins/ext/images/gray/shapes/circle.png) no-repeat top left";
this.top_left.style.position="absolute";
this.top_left.style.width=this.cornerWidth+"px";
this.top_left.style.height=this.cornerHeight+"px";
this.top_left.style.left="0px";
this.top_left.style.top="0px";
this.top_left.style.fontSize="2px";
this.top_right=document.createElement("div");
this.top_right.style.background="url(/skins/ext/images/gray/shapes/circle.png) no-repeat top right";
this.top_right.style.position="absolute";
this.top_right.style.width=this.cornerWidth+"px";
this.top_right.style.height=this.cornerHeight+"px";
this.top_right.style.left="0px";
this.top_right.style.top="0px";
this.top_right.style.fontSize="2px";
this.bottom_left=document.createElement("div");
this.bottom_left.style.background="url(/skins/ext/images/gray/shapes/circle.png) no-repeat bottom left";
this.bottom_left.style.position="absolute";
this.bottom_left.style.width=this.cornerWidth+"px";
this.bottom_left.style.height=this.cornerHeight+"px";
this.bottom_left.style.left="0px";
this.bottom_left.style.top="0px";
this.bottom_left.style.fontSize="2px";
this.bottom_right=document.createElement("div");
this.bottom_right.style.background="url(/skins/ext/images/gray/shapes/circle.png) no-repeat bottom right";
this.bottom_right.style.position="absolute";
this.bottom_right.style.width=this.cornerWidth+"px";
this.bottom_right.style.height=this.cornerHeight+"px";
this.bottom_right.style.left="0px";
this.bottom_right.style.top="0px";
this.bottom_right.style.fontSize="2px";
this.header=document.createElement("div");
this.header.style.position="absolute";
this.header.style.left=this.cornerWidth+"px";
this.header.style.top="0px";
this.header.style.height=(this.cornerHeight)+"px";
this.header.style.backgroundColor="#1e1b57";
this.header.style.borderTop="3px solid #1e1b57";
this.header.style.fontSize="9px";
this.header.style.color="white";
this.header.style.textAlign="center";
this.textarea=document.createElement("div");
this.textarea.style.position="absolute";
this.textarea.style.left="0px";
this.textarea.style.top=this.cornerHeight+"px";
this.textarea.style.background="url(/skins/ext/images/gray/shapes/bg.png)";
this.textarea.style.borderTop="2px solid #d98e3e";
this.textarea.style.borderBottom="2px solid white";
this.textarea.style.borderLeft="1px solid #1e1b57";
this.textarea.style.borderRight="1px solid #1e1b57";
this.textarea.style.overflow="auto";
this.textarea.style.fontSize="9pt";
this.textarea.style.color="white";
this.disableTextSelection(this.textarea);
this.footer=document.createElement("div");
this.footer.style.position="absolute";
this.footer.style.left=this.cornerWidth+"px";
this.footer.style.top="0px";
this.footer.style.height=(this.cornerHeight-1)+"px";
this.footer.style.backgroundColor="#1e1b57";
this.footer.style.borderBottom="1px solid #1e1b57";
this.footer.style.fontSize="2px";
item.appendChild(this.top_left);
item.appendChild(this.header);
item.appendChild(this.top_right);
item.appendChild(this.textarea);
item.appendChild(this.bottom_left);
item.appendChild(this.footer);
item.appendChild(this.bottom_right);
this.setTitle("Task 1");
this.setContent("Task Description");
return item;
};


Task2.prototype.setDimension=function(w,h){
Node.prototype.setDimension.call(this,w,h);
if(this.top_left!=null){
this.top_right.style.left=(this.width-this.cornerWidth)+"px";
this.bottom_right.style.left=(this.width-this.cornerWidth)+"px";
this.bottom_right.style.top=(this.height-this.cornerHeight)+"px";
this.bottom_left.style.top=(this.height-this.cornerHeight)+"px";
this.textarea.style.width=(this.width-2)+"px";
this.textarea.style.height=(this.height-this.cornerHeight*2)+"px";
this.header.style.width=(this.width-this.cornerWidth*2)+"px";
this.footer.style.width=(this.width-this.cornerWidth*2)+"px";
this.footer.style.top=(this.height-this.cornerHeight)+"px";
}
if(this.outputPort!=null){
this.outputPort.setPosition(this.width+5,this.height/2);
}
if(this.inputPort!=null){
this.inputPort.setPosition(-5,this.height/2);
}
};

Task2.prototype.setTitle=function(title){
this.header.innerHTML=title;
};
Task2.prototype.setContent=function(_3595){
this.textarea.innerHTML=_3595;
};
Task2.prototype.getTitle=function(){
return this.figure.header.innerHTML;
};
Task2.prototype.getContent=function(){
 return this.figure.textarea.innerHTML;
};
Task2.prototype.onDragstart=function(x,y){
var _3598=Node.prototype.onDragstart.call(this,x,y);
if(this.header==null){
return false;
}
if(y<this.cornerHeight&&x<this.width&&x>(this.width-this.cornerWidth)){
this.toggle();
return false;
}
if(this.originalHeight==-1){
if(this.canDrag==true&&x<parseInt(this.header.style.width)&&y<parseInt(this.header.style.height)){
return true;
}
}else{
return _3598;
}
};
Task2.prototype.setCanDrag=function(flag){
Node.prototype.setCanDrag.call(this,flag);
this.html.style.cursor="";
if(this.header==null){
return;
}
if(flag){
this.header.style.cursor="move";
}else{
this.header.style.cursor="";
}
};
Task2.prototype.setWorkflow=function(_359a){
Node.prototype.setWorkflow.call(this,_359a);
if(_359a!=null&&this.inputPort==null){
this.inputPort=new InputPort();
this.inputPort.setWorkflow(_359a);
this.inputPort.setName("input");
this.addPort(this.inputPort,-5,this.height/2);
this.outputPort=new OutputPort();
this.outputPort.setMaxFanOut(5);
this.outputPort.setWorkflow(_359a);
this.outputPort.setName("output");
this.addPort(this.outputPort,this.width+5,this.height/2);
}
};



Task2.prototype.toggle=function(){
if(this.originalHeight==-1){
this.originalHeight=this.height;
this.setDimension(this.width,this.cornerHeight*2);
this.setResizeable(false);
}else{
this.setDimension(this.width,this.originalHeight);
this.originalHeight=-1;
this.setResizeable(true);
}
};

Task2.prototype.getContextMenu=function(){
var menu=new Menu();
var oThis=this;
menu.appendMenuItem(new MenuItem("Steps",null,function(){
oThis.setBackgroundColor(new Color(0,0,255));
}));
menu.appendMenuItem(new MenuItem("Users & Users Groups",null,function(){
oThis.setBackgroundColor(new Color(0,255,0));
}));
menu.appendMenuItem(new MenuItem("Users & Users Groups (ad-hoc)",null,function(){
oThis.setBackgroundColor(new Color(128,128,128));
}));
menu.appendMenuItem(new MenuItem("Routing Rule",null,function(){
oThis.setBackgroundColor(new Color(0,0,0));
}));
menu.appendMenuItem(new MenuItem("Deleting Routing Rule",null,function(){
oThis.setBackgroundColor(new Color(0,0,0));
}));
menu.appendMenuItem(new MenuItem("Delete Task ",null,function(){
//oThis.;
}));
menu.appendMenuItem(new MenuItem("Properties ",null,function(){
oThis.setBackgroundColor(new Color(0,0,0));
}));

return menu;
};

/*
**Extended the Dialog class with 2 form element for editing the Task Name and Description
*/

Task2Dialog = function(_2e5e){
   this.figure=_2e5e;
   Dialog.call(this);
   this.setDimension(400,150);  //Set the width and height of the Dialog box
}

Task2Dialog.prototype= new Dialog;
Task2Dialog.prototype.createHTMLElement=function(){
var item=Dialog.prototype.createHTMLElement.call(this);
var _2e60=document.createElement("form");
_2e60.style.position="absolute";
_2e60.style.left="10px";
_2e60.style.top="30px";
_2e60.style.width="375px";
_2e60.style.font="normal 10px verdana";
item.appendChild(_2e60);
this.label=document.createTextNode("Task Name"); //New Text Field for Task Name
_2e60.appendChild(this.label);
this.input=document.createElement("input");
this.input.style.border="1px solid gray";
this.input.style.font="normal 10px verdana";
this.input.type="text";
var value=this.figure.header.innerHTML;
if(value){
this.input.value=value;
}else{
this.input.value="";
}
this.input.style.width="100%";
_2e60.appendChild(this.input);
this.input.focus();
this.label=document.createTextNode("Task Description"); //New Text Field for Task Description
_2e60.appendChild(this.label);
this.input=document.createElement("input");
this.input.style.border="1px solid gray";
this.input.style.font="normal 10px verdana";
this.input.type="text";
var value=this.figure.textarea.innerHTML;
if(value){
this.input.value=value;
}else{
this.input.value="";
}
this.input.style.width="100%";
_2e60.appendChild(this.input);
this.input.focus();

return item;
};
/*Double Click Event for opening the dialog Box*/
Task2.prototype.onDoubleClick=function(){
var _409d=new Task2Dialog(this);
this.workflow.showDialog(_409d);
};
/*Set the Task Name and Description*/
Task2Dialog.prototype.onOk=function(){
     this.figure.header.innerHTML = this.input.form.children[0].value;
     this.figure.textarea.innerHTML = this.input.form.children[1].value;
this.workflow.removeFigure(this);
};