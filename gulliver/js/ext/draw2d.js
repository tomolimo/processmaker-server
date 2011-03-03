
/**This notice must be untouched at all times.
This is the COMPRESSED version of the Draw2D Library
WebSite: http://www.draw2d.org
Copyright: 2006 Andreas Herz. All rights reserved.
Created: 5.11.2006 by Andreas Herz (Web: http://www.freegroup.de )
LICENSE: LGPL
**/
var _errorStack_=[];
function pushErrorStack(e,_43a3){
_errorStack_.push(_43a3+"\n");
throw e;
}
AbstractEvent=function(){
this.type=null;
this.target=null;
this.relatedTarget=null;
this.cancelable=false;
this.timeStamp=null;
this.returnValue=true;
};
AbstractEvent.prototype.initEvent=function(sType,_43a5){
this.type=sType;
this.cancelable=_43a5;
this.timeStamp=(new Date()).getTime();
};
AbstractEvent.prototype.preventDefault=function(){
if(this.cancelable){
this.returnValue=false;
}
};
AbstractEvent.fireDOMEvent=function(_43a6,_43a7){
if(document.createEvent){
var evt=document.createEvent("Events");
evt.initEvent(_43a6,true,true);
_43a7.dispatchEvent(evt);
}else{
if(document.createEventObject){
var evt=document.createEventObject();
_43a7.fireEvent("on"+_43a6,evt);
}
}
};
EventTarget=function(){
this.eventhandlers={};
};
EventTarget.prototype.addEventListener=function(sType,_43aa){
if(typeof this.eventhandlers[sType]=="undefined"){
this.eventhandlers[sType]=[];
}
this.eventhandlers[sType][this.eventhandlers[sType].length]=_43aa;
};
EventTarget.prototype.dispatchEvent=function(_43ab){
_43ab.target=this;
if(typeof this.eventhandlers[_43ab.type]!="undefined"){
for(var i=0;i<this.eventhandlers[_43ab.type].length;i++){
this.eventhandlers[_43ab.type][i](_43ab);
}
}
return _43ab.returnValue;
};
EventTarget.prototype.removeEventListener=function(sType,_43ae){
if(typeof this.eventhandlers[sType]!="undefined"){
var _43af=[];
for(var i=0;i<this.eventhandlers[sType].length;i++){
if(this.eventhandlers[sType][i]!=_43ae){
_43af[_43af.length]=this.eventhandlers[sType][i];
}
}
this.eventhandlers[sType]=_43af;
}
};
String.prototype.trim=function(){
return (this.replace(new RegExp("^([\\s]+)|([\\s]+)$","gm"),""));
};
String.prototype.lefttrim=function(){
return (this.replace(new RegExp("^[\\s]+","gm"),""));
};
String.prototype.righttrim=function(){
return (this.replace(new RegExp("[\\s]+$","gm"),""));
};
String.prototype.between=function(left,right,_43ed){
if(!_43ed){
_43ed=0;
}
var li=this.indexOf(left,_43ed);
if(li==-1){
return null;
}
var ri=this.indexOf(right,li);
if(ri==-1){
return null;
}
return this.substring(li+left.length,ri);
};
UUID=function(){
};
UUID.prototype.type="UUID";
UUID.create=function(){
var _42d7=function(){
return (((1+Math.random())*65536)|0).toString(16).substring(1);
};
return (_42d7()+_42d7()+"-"+_42d7()+"-"+_42d7()+"-"+_42d7()+"-"+_42d7()+_42d7()+_42d7());
};
ArrayList=function(){
this.increment=10;
this.size=0;
this.data=new Array(this.increment);
};
ArrayList.EMPTY_LIST=new ArrayList();
ArrayList.prototype.type="ArrayList";
ArrayList.prototype.reverse=function(){
var _49dd=new Array(this.size);
for(var i=0;i<this.size;i++){
_49dd[i]=this.data[this.size-i-1];
}
this.data=_49dd;
};
ArrayList.prototype.getCapacity=function(){
return this.data.length;
};
ArrayList.prototype.getSize=function(){
return this.size;
};
ArrayList.prototype.isEmpty=function(){
return this.getSize()===0;
};
ArrayList.prototype.getLastElement=function(){
if(this.data[this.getSize()-1]!==null){
return this.data[this.getSize()-1];
}
};
ArrayList.prototype.getFirstElement=function(){
if(this.data[0]!==null&&this.data[0]!==undefined){
return this.data[0];
}
return null;
};
ArrayList.prototype.get=function(i){
return this.data[i];
};
ArrayList.prototype.add=function(obj){
if(this.getSize()==this.data.length){
this.resize();
}
this.data[this.size++]=obj;
};
ArrayList.prototype.addAll=function(obj){
for(var i=0;i<obj.getSize();i++){
this.add(obj.get(i));
}
};
ArrayList.prototype.remove=function(obj){
var index=this.indexOf(obj);
if(index>=0){
return this.removeElementAt(index);
}
return null;
};
ArrayList.prototype.insertElementAt=function(obj,index){
if(this.size==this.capacity){
this.resize();
}
for(var i=this.getSize();i>index;i--){
this.data[i]=this.data[i-1];
}
this.data[index]=obj;
this.size++;
};
ArrayList.prototype.removeElementAt=function(index){
var _49e9=this.data[index];
for(var i=index;i<(this.getSize()-1);i++){
this.data[i]=this.data[i+1];
}
this.data[this.getSize()-1]=null;
this.size--;
return _49e9;
};
ArrayList.prototype.removeAllElements=function(){
this.size=0;
for(var i=0;i<this.data.length;i++){
this.data[i]=null;
}
};
ArrayList.prototype.indexOf=function(obj){
for(var i=0;i<this.getSize();i++){
if(this.data[i]==obj){
return i;
}
}
return -1;
};
ArrayList.prototype.contains=function(obj){
for(var i=0;i<this.getSize();i++){
if(this.data[i]==obj){
return true;
}
}
return false;
};
ArrayList.prototype.resize=function(){
newData=new Array(this.data.length+this.increment);
for(var i=0;i<this.data.length;i++){
newData[i]=this.data[i];
}
this.data=newData;
};
ArrayList.prototype.trimToSize=function(){
if(this.data.length==this.size){
return;
}
var temp=new Array(this.getSize());
for(var i=0;i<this.getSize();i++){
temp[i]=this.data[i];
}
this.size=temp.length;
this.data=temp;
};
ArrayList.prototype.sort=function(f){
var i,j;
var _49f5;
var _49f6;
var _49f7;
var _49f8;
for(i=1;i<this.getSize();i++){
_49f6=this.data[i];
_49f5=_49f6[f];
j=i-1;
_49f7=this.data[j];
_49f8=_49f7[f];
while(j>=0&&_49f8>_49f5){
this.data[j+1]=this.data[j];
j--;
if(j>=0){
_49f7=this.data[j];
_49f8=_49f7[f];
}
}
this.data[j+1]=_49f6;
}
};
ArrayList.prototype.clone=function(){
var _49f9=new ArrayList(this.size);
for(var i=0;i<this.size;i++){
_49f9.add(this.data[i]);
}
return _49f9;
};
ArrayList.prototype.overwriteElementAt=function(obj,index){
this.data[index]=obj;
};
ArrayList.prototype.getPersistentAttributes=function(){
return {data:this.data,increment:this.increment,size:this.getSize()};
};
function trace(_385a){
var _385b=openwindow("about:blank",700,400);
_385b.document.writeln("<pre>"+_385a+"</pre>");
}
function openwindow(url,width,_385e){
var left=(screen.width-width)/2;
var top=(screen.height-_385e)/2;
property="left="+left+", top="+top+", toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=1,alwaysRaised,width="+width+",height="+_385e;
return window.open(url,"_blank",property);
}
function dumpObject(obj){
trace("----------------------------------------------------------------------------");
trace("- Object dump");
trace("----------------------------------------------------------------------------");
for(var i in obj){
try{
if(typeof obj[i]!="function"){
trace(i+" --&gt; "+obj[i]);
}
}
catch(e){
}
}
for(var i in obj){
try{
if(typeof obj[i]=="function"){
trace(i+" --&gt; "+obj[i]);
}
}
catch(e){
}
}
trace("----------------------------------------------------------------------------");
}
Drag=function(){
};
Drag.current=null;
Drag.currentTarget=null;
Drag.currentHover=null;
Drag.currentCompartment=null;
Drag.dragging=false;
Drag.isDragging=function(){
return this.dragging;
};
Drag.setCurrent=function(_3d9d){
this.current=_3d9d;
this.dragging=true;
};
Drag.getCurrent=function(){
return this.current;
};
Drag.clearCurrent=function(){
this.current=null;
this.dragging=false;
this.currentTarget=null;
};
Draggable=function(_3d9e,_3d9f){
this.id=UUID.create();
this.node=null;
EventTarget.call(this);
this.construct(_3d9e,_3d9f);
this.diffX=0;
this.diffY=0;
this.targets=new ArrayList();
};
Draggable.prototype=new EventTarget();
Draggable.prototype.construct=function(_3da0){
if(_3da0===null||_3da0===undefined){
return;
}
this.element=_3da0;
var oThis=this;
var _3da2=function(){
var _3da3=new DragDropEvent();
_3da3.initDragDropEvent("dblclick",true);
oThis.dispatchEvent(_3da3);
var _3da4=arguments[0]||window.event;
_3da4.cancelBubble=true;
_3da4.returnValue=false;
};
var _3da5=function(){
var _3da6=arguments[0]||window.event;
var _3da7=new DragDropEvent();
if(oThis.node!==null){
var _3da8=oThis.node.getWorkflow().getAbsoluteX();
var _3da9=oThis.node.getWorkflow().getAbsoluteY();
var _3daa=oThis.node.getWorkflow().getScrollLeft();
var _3dab=oThis.node.getWorkflow().getScrollTop();
_3da7.x=_3da6.clientX-oThis.element.offsetLeft+_3daa-_3da8;
_3da7.y=_3da6.clientY-oThis.element.offsetTop+_3dab-_3da9;
}
if(_3da6.button===2){
_3da7.initDragDropEvent("contextmenu",true);
oThis.dispatchEvent(_3da7);
}else{
_3da7.initDragDropEvent("dragstart",true);
if(oThis.dispatchEvent(_3da7)){
oThis.diffX=_3da6.clientX-oThis.element.offsetLeft;
oThis.diffY=_3da6.clientY-oThis.element.offsetTop;
Drag.setCurrent(oThis);
if(oThis.isAttached==true){
oThis.detachEventHandlers();
}
oThis.attachEventHandlers();
}
}
_3da6.cancelBubble=true;
_3da6.returnValue=false;
};
var _3dac=function(){
if(Drag.getCurrent()===null){
var _3dad=arguments[0]||window.event;
if(Drag.currentHover!==null&&oThis!==Drag.currentHover){
var _3dae=new DragDropEvent();
_3dae.initDragDropEvent("mouseleave",false,oThis);
Drag.currentHover.dispatchEvent(_3dae);
}
if(oThis!==null&&oThis!==Drag.currentHover){
var _3dae=new DragDropEvent();
_3dae.initDragDropEvent("mouseenter",false,oThis);
oThis.dispatchEvent(_3dae);
}
Drag.currentHover=oThis;
}else{
}
};
if(this.element.addEventListener){
this.element.addEventListener("mousemove",_3dac,false);
this.element.addEventListener("mousedown",_3da5,false);
this.element.addEventListener("dblclick",_3da2,false);
}else{
if(this.element.attachEvent){
this.element.attachEvent("onmousemove",_3dac);
this.element.attachEvent("onmousedown",_3da5);
this.element.attachEvent("ondblclick",_3da2);
}else{
throw "Drag not supported in this browser.";
}
}
};
Draggable.prototype.onDrop=function(_3daf,_3db0){
};
Draggable.prototype.attachEventHandlers=function(){
var oThis=this;
oThis.isAttached=true;
this.tempMouseMove=function(){
var _3db2=arguments[0]||window.event;
var _3db3=new Point(_3db2.clientX-oThis.diffX,_3db2.clientY-oThis.diffY);
if(oThis.node!==null&&oThis.node.getCanSnapToHelper()){
_3db3=oThis.node.getWorkflow().snapToHelper(oThis.node,_3db3);
}
oThis.element.style.left=_3db3.x+"px";
oThis.element.style.top=_3db3.y+"px";
if(oThis.node!==null){
var _3db4=oThis.node.getWorkflow().getScrollLeft();
var _3db5=oThis.node.getWorkflow().getScrollTop();
var _3db6=oThis.node.getWorkflow().getAbsoluteX();
var _3db7=oThis.node.getWorkflow().getAbsoluteY();
var _3db8=oThis.getDropTarget(_3db2.clientX+_3db4-_3db6,_3db2.clientY+_3db5-_3db7);
var _3db9=oThis.getCompartment(_3db2.clientX+_3db4-_3db6,_3db2.clientY+_3db5-_3db7);
if(Drag.currentTarget!==null&&_3db8!=Drag.currentTarget){
var _3dba=new DragDropEvent();
_3dba.initDragDropEvent("dragleave",false,oThis);
Drag.currentTarget.dispatchEvent(_3dba);
}
if(_3db8!==null&&_3db8!==Drag.currentTarget){
var _3dba=new DragDropEvent();
_3dba.initDragDropEvent("dragenter",false,oThis);
_3db8.dispatchEvent(_3dba);
}
Drag.currentTarget=_3db8;
if(Drag.currentCompartment!==null&&_3db9!==Drag.currentCompartment){
var _3dba=new DragDropEvent();
_3dba.initDragDropEvent("figureleave",false,oThis);
Drag.currentCompartment.dispatchEvent(_3dba);
}
if(_3db9!==null&&_3db9.node!=oThis.node&&_3db9!==Drag.currentCompartment){
var _3dba=new DragDropEvent();
_3dba.initDragDropEvent("figureenter",false,oThis);
_3db9.dispatchEvent(_3dba);
}
Drag.currentCompartment=_3db9;
}
var _3dbb=new DragDropEvent();
_3dbb.initDragDropEvent("drag",false);
oThis.dispatchEvent(_3dbb);
};
oThis.tempMouseUp=function(){
oThis.detachEventHandlers();
var _3dbc=arguments[0]||window.event;
if(oThis.node!==null){
var _3dbd=oThis.node.getWorkflow().getScrollLeft();
var _3dbe=oThis.node.getWorkflow().getScrollTop();
var _3dbf=oThis.node.getWorkflow().getAbsoluteX();
var _3dc0=oThis.node.getWorkflow().getAbsoluteY();
var _3dc1=oThis.getDropTarget(_3dbc.clientX+_3dbd-_3dbf,_3dbc.clientY+_3dbe-_3dc0);
var _3dc2=oThis.getCompartment(_3dbc.clientX+_3dbd-_3dbf,_3dbc.clientY+_3dbe-_3dc0);
if(_3dc1!==null){
var _3dc3=new DragDropEvent();
_3dc3.initDragDropEvent("drop",false,oThis);
_3dc1.dispatchEvent(_3dc3);
}
if(_3dc2!==null&&_3dc2.node!==oThis.node){
var _3dc3=new DragDropEvent();
_3dc3.initDragDropEvent("figuredrop",false,oThis);
_3dc2.dispatchEvent(_3dc3);
}
if(Drag.currentTarget!==null){
var _3dc3=new DragDropEvent();
_3dc3.initDragDropEvent("dragleave",false,oThis);
Drag.currentTarget.dispatchEvent(_3dc3);
Drag.currentTarget=null;
}
}
var _3dc4=new DragDropEvent();
_3dc4.initDragDropEvent("dragend",false);
oThis.dispatchEvent(_3dc4);
oThis.onDrop(_3dbc.clientX,_3dbc.clientY);
Drag.currentCompartment=null;
Drag.clearCurrent();
};
if(document.body.addEventListener){
document.body.addEventListener("mousemove",this.tempMouseMove,false);
document.body.addEventListener("mouseup",this.tempMouseUp,false);
}else{
if(document.body.attachEvent){
document.body.attachEvent("onmousemove",this.tempMouseMove);
document.body.attachEvent("onmouseup",this.tempMouseUp);
}else{
throw new Error("Drag doesn't support this browser.");
}
}
};
Draggable.prototype.detachEventHandlers=function(){
this.isAttached=false;
if(document.body.removeEventListener){
document.body.removeEventListener("mousemove",this.tempMouseMove,false);
document.body.removeEventListener("mouseup",this.tempMouseUp,false);
}else{
if(document.body.detachEvent){
document.body.detachEvent("onmousemove",this.tempMouseMove);
document.body.detachEvent("onmouseup",this.tempMouseUp);
}else{
throw new Error("Drag doesn't support this browser.");
}
}
};
Draggable.prototype.getDropTarget=function(x,y){
for(var i=0;i<this.targets.getSize();i++){
var _3dc8=this.targets.get(i);
if(_3dc8.node.isOver(x,y)&&_3dc8.node!==this.node){
return _3dc8;
}
}
return null;
};
Draggable.prototype.getCompartment=function(x,y){
var _3dcb=null;
for(var i=0;i<this.node.getWorkflow().compartments.getSize();i++){
var _3dcd=this.node.getWorkflow().compartments.get(i);
if(_3dcd.isOver(x,y)&&_3dcd!==this.node){
if(_3dcb===null){
_3dcb=_3dcd;
}else{
if(_3dcb.getZOrder()<_3dcd.getZOrder()){
_3dcb=_3dcd;
}
}
}
}
return _3dcb===null?null:_3dcb.dropable;
};
Draggable.prototype.getLeft=function(){
return this.element.offsetLeft;
};
Draggable.prototype.getTop=function(){
return this.element.offsetTop;
};
DragDropEvent=function(){
AbstractEvent.call(this);
};
DragDropEvent.prototype=new AbstractEvent();
DragDropEvent.prototype.initDragDropEvent=function(sType,_3dcf,_3dd0){
this.initEvent(sType,_3dcf);
this.relatedTarget=_3dd0;
};
DropTarget=function(_3dd1){
EventTarget.call(this);
this.construct(_3dd1);
};
DropTarget.prototype=new EventTarget();
DropTarget.prototype.construct=function(_3dd2){
this.element=_3dd2;
};
DropTarget.prototype.getLeft=function(){
var el=this.element;
var ol=el.offsetLeft;
while((el=el.offsetParent)!==null){
ol+=el.offsetLeft;
}
return ol;
};
DropTarget.prototype.getTop=function(){
var el=this.element;
var ot=el.offsetTop;
while((el=el.offsetParent)!==null){
ot+=el.offsetTop;
}
return ot;
};
DropTarget.prototype.getHeight=function(){
return this.element.offsetHeight;
};
DropTarget.prototype.getWidth=function(){
return this.element.offsetWidth;
};
PositionConstants=function(){
};
PositionConstants.NORTH=1;
PositionConstants.SOUTH=4;
PositionConstants.WEST=8;
PositionConstants.EAST=16;
Color=function(red,green,blue){
if(typeof green=="undefined"){
var rgb=this.hex2rgb(red);
this.red=rgb[0];
this.green=rgb[1];
this.blue=rgb[2];
}else{
this.red=red;
this.green=green;
this.blue=blue;
}
};
Color.prototype.type="Color";
Color.prototype.getHTMLStyle=function(){
return "rgb("+this.red+","+this.green+","+this.blue+")";
};
Color.prototype.getRed=function(){
return this.red;
};
Color.prototype.getGreen=function(){
return this.green;
};
Color.prototype.getBlue=function(){
return this.blue;
};
Color.prototype.getIdealTextColor=function(){
var _39c7=105;
var _39c8=(this.red*0.299)+(this.green*0.587)+(this.blue*0.114);
return (255-_39c8<_39c7)?new Color(0,0,0):new Color(255,255,255);
};
Color.prototype.hex2rgb=function(_39c9){
_39c9=_39c9.replace("#","");
return ({0:parseInt(_39c9.substr(0,2),16),1:parseInt(_39c9.substr(2,2),16),2:parseInt(_39c9.substr(4,2),16)});
};
Color.prototype.hex=function(){
return (this.int2hex(this.red)+this.int2hex(this.green)+this.int2hex(this.blue));
};
Color.prototype.int2hex=function(v){
v=Math.round(Math.min(Math.max(0,v),255));
return ("0123456789ABCDEF".charAt((v-v%16)/16)+"0123456789ABCDEF".charAt(v%16));
};
Color.prototype.darker=function(_39cb){
var red=parseInt(Math.round(this.getRed()*(1-_39cb)));
var green=parseInt(Math.round(this.getGreen()*(1-_39cb)));
var blue=parseInt(Math.round(this.getBlue()*(1-_39cb)));
if(red<0){
red=0;
}else{
if(red>255){
red=255;
}
}
if(green<0){
green=0;
}else{
if(green>255){
green=255;
}
}
if(blue<0){
blue=0;
}else{
if(blue>255){
blue=255;
}
}
return new Color(red,green,blue);
};
Color.prototype.lighter=function(_39cf){
var red=parseInt(Math.round(this.getRed()*(1+_39cf)));
var green=parseInt(Math.round(this.getGreen()*(1+_39cf)));
var blue=parseInt(Math.round(this.getBlue()*(1+_39cf)));
if(red<0){
red=0;
}else{
if(red>255){
red=255;
}
}
if(green<0){
green=0;
}else{
if(green>255){
green=255;
}
}
if(blue<0){
blue=0;
}else{
if(blue>255){
blue=255;
}
}
return new Color(red,green,blue);
};
Point=function(x,y){
this.x=x;
this.y=y;
};
Point.prototype.type="Point";
Point.prototype.getX=function(){
return this.x;
};
Point.prototype.getY=function(){
return this.y;
};
Point.prototype.getPosition=function(p){
var dx=p.x-this.x;
var dy=p.y-this.y;
if(Math.abs(dx)>Math.abs(dy)){
if(dx<0){
return PositionConstants.WEST;
}
return PositionConstants.EAST;
}
if(dy<0){
return PositionConstants.NORTH;
}
return PositionConstants.SOUTH;
};
Point.prototype.equals=function(o){
return this.x==o.x&&this.y==o.y;
};
Point.prototype.getDistance=function(other){
return Math.sqrt((this.x-other.x)*(this.x-other.x)+(this.y-other.y)*(this.y-other.y));
};
Point.prototype.getTranslated=function(other){
return new Point(this.x+other.x,this.y+other.y);
};
Point.prototype.getPersistentAttributes=function(){
return {x:this.x,y:this.y};
};
Dimension=function(x,y,w,h){
Point.call(this,x,y);
this.w=w;
this.h=h;
};
Dimension.prototype=new Point();
Dimension.prototype.type="Dimension";
Dimension.prototype.translate=function(dx,dy){
this.x+=dx;
this.y+=dy;
return this;
};
Dimension.prototype.resize=function(dw,dh){
this.w+=dw;
this.h+=dh;
return this;
};
Dimension.prototype.setBounds=function(rect){
this.x=rect.x;
this.y=rect.y;
this.w=rect.w;
this.h=rect.h;
return this;
};
Dimension.prototype.isEmpty=function(){
return this.w<=0||this.h<=0;
};
Dimension.prototype.getWidth=function(){
return this.w;
};
Dimension.prototype.getHeight=function(){
return this.h;
};
Dimension.prototype.getRight=function(){
return this.x+this.w;
};
Dimension.prototype.getBottom=function(){
return this.y+this.h;
};
Dimension.prototype.getTopLeft=function(){
return new Point(this.x,this.y);
};
Dimension.prototype.getCenter=function(){
return new Point(this.x+this.w/2,this.y+this.h/2);
};
Dimension.prototype.getBottomRight=function(){
return new Point(this.x+this.w,this.y+this.h);
};
Dimension.prototype.equals=function(o){
return this.x==o.x&&this.y==o.y&&this.w==o.w&&this.h==o.h;
};
SnapToHelper=function(_4603){
this.workflow=_4603;
};
SnapToHelper.NORTH=1;
SnapToHelper.SOUTH=4;
SnapToHelper.WEST=8;
SnapToHelper.EAST=16;
SnapToHelper.CENTER=32;
SnapToHelper.NORTH_EAST=SnapToHelper.NORTH|SnapToHelper.EAST;
SnapToHelper.NORTH_WEST=SnapToHelper.NORTH|SnapToHelper.WEST;
SnapToHelper.SOUTH_EAST=SnapToHelper.SOUTH|SnapToHelper.EAST;
SnapToHelper.SOUTH_WEST=SnapToHelper.SOUTH|SnapToHelper.WEST;
SnapToHelper.NORTH_SOUTH=SnapToHelper.NORTH|SnapToHelper.SOUTH;
SnapToHelper.EAST_WEST=SnapToHelper.EAST|SnapToHelper.WEST;
SnapToHelper.NSEW=SnapToHelper.NORTH_SOUTH|SnapToHelper.EAST_WEST;
SnapToHelper.prototype.snapPoint=function(_4604,_4605,_4606){
return _4605;
};
SnapToHelper.prototype.snapRectangle=function(_4607,_4608){
return _4607;
};
SnapToHelper.prototype.onSetDocumentDirty=function(){
};
SnapToGrid=function(_3e3c){
SnapToHelper.call(this,_3e3c);
};
SnapToGrid.prototype=new SnapToHelper();
SnapToGrid.prototype.type="SnapToGrid";
SnapToGrid.prototype.snapPoint=function(_3e3d,_3e3e,_3e3f){
_3e3f.x=this.workflow.gridWidthX*Math.floor(((_3e3e.x+this.workflow.gridWidthX/2)/this.workflow.gridWidthX));
_3e3f.y=this.workflow.gridWidthY*Math.floor(((_3e3e.y+this.workflow.gridWidthY/2)/this.workflow.gridWidthY));
return 0;
};
SnapToGrid.prototype.snapRectangle=function(_3e40,_3e41){
_3e41.x=_3e40.x;
_3e41.y=_3e40.y;
_3e41.w=_3e40.w;
_3e41.h=_3e40.h;
return 0;
};
SnapToGeometryEntry=function(type,_3c08){
this.type=type;
this.location=_3c08;
};
SnapToGeometryEntry.prototype.getLocation=function(){
return this.location;
};
SnapToGeometryEntry.prototype.getType=function(){
return this.type;
};
SnapToGeometry=function(_363e){
SnapToHelper.call(this,_363e);
this.rows=null;
this.cols=null;
};
SnapToGeometry.prototype=new SnapToHelper();
SnapToGeometry.THRESHOLD=5;
SnapToGeometry.prototype.snapPoint=function(_363f,_3640,_3641){
if(this.rows===null||this.cols===null){
this.populateRowsAndCols();
}
if((_363f&SnapToHelper.EAST)!==0){
var _3642=this.getCorrectionFor(this.cols,_3640.getX()-1,1);
if(_3642!==SnapToGeometry.THRESHOLD){
_363f&=~SnapToHelper.EAST;
_3641.x+=_3642;
}
}
if((_363f&SnapToHelper.WEST)!==0){
var _3643=this.getCorrectionFor(this.cols,_3640.getX(),-1);
if(_3643!==SnapToGeometry.THRESHOLD){
_363f&=~SnapToHelper.WEST;
_3641.x+=_3643;
}
}
if((_363f&SnapToHelper.SOUTH)!==0){
var _3644=this.getCorrectionFor(this.rows,_3640.getY()-1,1);
if(_3644!==SnapToGeometry.THRESHOLD){
_363f&=~SnapToHelper.SOUTH;
_3641.y+=_3644;
}
}
if((_363f&SnapToHelper.NORTH)!==0){
var _3645=this.getCorrectionFor(this.rows,_3640.getY(),-1);
if(_3645!==SnapToGeometry.THRESHOLD){
_363f&=~SnapToHelper.NORTH;
_3641.y+=_3645;
}
}
return _363f;
};
SnapToGeometry.prototype.snapRectangle=function(_3646,_3647){
var _3648=_3646.getTopLeft();
var _3649=_3646.getBottomRight();
var _364a=this.snapPoint(SnapToHelper.NORTH_WEST,_3646.getTopLeft(),_3648);
_3647.x=_3648.x;
_3647.y=_3648.y;
var _364b=this.snapPoint(SnapToHelper.SOUTH_EAST,_3646.getBottomRight(),_3649);
if(_364a&SnapToHelper.WEST){
_3647.x=_3649.x-_3646.getWidth();
}
if(_364a&SnapToHelper.NORTH){
_3647.y=_3649.y-_3646.getHeight();
}
return _364a|_364b;
};
SnapToGeometry.prototype.populateRowsAndCols=function(){
this.rows=[];
this.cols=[];
var _364c=this.workflow.getDocument().getFigures();
var index=0;
for(var i=0;i<_364c.getSize();i++){
var _364f=_364c.get(i);
if(_364f!=this.workflow.getCurrentSelection()){
var _3650=_364f.getBounds();
this.cols[index*3]=new SnapToGeometryEntry(-1,_3650.getX());
this.rows[index*3]=new SnapToGeometryEntry(-1,_3650.getY());
this.cols[index*3+1]=new SnapToGeometryEntry(0,_3650.x+(_3650.getWidth()-1)/2);
this.rows[index*3+1]=new SnapToGeometryEntry(0,_3650.y+(_3650.getHeight()-1)/2);
this.cols[index*3+2]=new SnapToGeometryEntry(1,_3650.getRight()-1);
this.rows[index*3+2]=new SnapToGeometryEntry(1,_3650.getBottom()-1);
index++;
}
}
};
SnapToGeometry.prototype.getCorrectionFor=function(_3651,value,side){
var _3654=SnapToGeometry.THRESHOLD;
var _3655=SnapToGeometry.THRESHOLD;
for(var i=0;i<_3651.length;i++){
var entry=_3651[i];
var _3658;
if(entry.type===-1&&side!==0){
_3658=Math.abs(value-entry.location);
if(_3658<_3654){
_3654=_3658;
_3655=entry.location-value;
}
}else{
if(entry.type===0&&side===0){
_3658=Math.abs(value-entry.location);
if(_3658<_3654){
_3654=_3658;
_3655=entry.location-value;
}
}else{
if(entry.type===1&&side!==0){
_3658=Math.abs(value-entry.location);
if(_3658<_3654){
_3654=_3658;
_3655=entry.location-value;
}
}
}
}
}
return _3655;
};
SnapToGeometry.prototype.onSetDocumentDirty=function(){
this.rows=null;
this.cols=null;
};
Border=function(){
this.color=null;
};
Border.prototype.type="Border";
Border.prototype.dispose=function(){
this.color=null;
};
Border.prototype.getHTMLStyle=function(){
return "";
};
Border.prototype.setColor=function(c){
this.color=c;
};
Border.prototype.getColor=function(){
return this.color;
};
Border.prototype.refresh=function(){
};
LineBorder=function(width){
Border.call(this);
this.width=1;
if(width){
this.width=width;
}
this.figure=null;
};
LineBorder.prototype=new Border();
LineBorder.prototype.type="LineBorder";
LineBorder.prototype.dispose=function(){
Border.prototype.dispose.call(this);
this.figure=null;
};
LineBorder.prototype.setLineWidth=function(w){
this.width=w;
if(this.figure!==null){
this.figure.html.style.border=this.getHTMLStyle();
}
};
LineBorder.prototype.getHTMLStyle=function(){
if(this.getColor()!==null){
return this.width+"px solid "+this.getColor().getHTMLStyle();
}
return this.width+"px solid black";
};
LineBorder.prototype.refresh=function(){
this.setLineWidth(this.width);
};
Figure=function(){
this.construct();
};
Figure.prototype.type="Figure";
Figure.ZOrderBaseIndex=100;
Figure.setZOrderBaseIndex=function(index){
Figure.ZOrderBaseIndex=index;
};
Figure.prototype.construct=function(){
this.lastDragStartTime=0;
this.x=0;
this.y=0;
this.width=10;
this.height=10;
this.border=null;
this.id=UUID.create();
this.html=this.createHTMLElement();
this.canvas=null;
this.workflow=null;
this.draggable=null;
this.parent=null;
this.isMoving=false;
this.canSnapToHelper=true;
this.snapToGridAnchor=new Point(0,0);
this.timer=-1;
this.model=null;
this.alpha=1;
this.alphaBeforeOnDrag=1;
this.properties={};
this.moveListener=new ArrayList();
this.setDimension(this.width,this.height);
this.setDeleteable(true);
this.setCanDrag(true);
this.setResizeable(true);
this.setSelectable(true);
};
Figure.prototype.dispose=function(){
this.canvas=null;
this.workflow=null;
this.moveListener=null;
if(this.draggable!==null){
this.draggable.removeEventListener("mouseenter",this.tmpMouseEnter);
this.draggable.removeEventListener("mouseleave",this.tmpMouseLeave);
this.draggable.removeEventListener("dragend",this.tmpDragend);
this.draggable.removeEventListener("dragstart",this.tmpDragstart);
this.draggable.removeEventListener("drag",this.tmpDrag);
this.draggable.removeEventListener("dblclick",this.tmpDoubleClick);
this.draggable.node=null;
this.draggable.target.removeAllElements();
}
this.draggable=null;
if(this.border!==null){
this.border.dispose();
}
this.border=null;
if(this.parent!==null){
this.parent.removeChild(this);
}
};
Figure.prototype.getProperties=function(){
return this.properties;
};
Figure.prototype.getProperty=function(key){
return this.properties[key];
};
Figure.prototype.setProperty=function(key,value){
this.properties[key]=value;
this.setDocumentDirty();
};
Figure.prototype.getId=function(){
return this.id;
};
Figure.prototype.setId=function(id){
this.id=id;
if(this.html!==null){
this.html.id=id;
}
};
Figure.prototype.setCanvas=function(_3c3c){
this.canvas=_3c3c;
};
Figure.prototype.getWorkflow=function(){
return this.workflow;
};
Figure.prototype.setWorkflow=function(_3c3d){
if(this.draggable===null){
this.html.tabIndex="0";
var oThis=this;
this.keyDown=function(event){
event.cancelBubble=true;
event.returnValue=true;
oThis.onKeyDown(event.keyCode,event.ctrlKey);
};
if(this.html.addEventListener){
this.html.addEventListener("keydown",this.keyDown,false);
}else{
if(this.html.attachEvent){
this.html.attachEvent("onkeydown",this.keyDown);
}
}
this.draggable=new Draggable(this.html,Draggable.DRAG_X|Draggable.DRAG_Y);
this.draggable.node=this;
this.tmpContextMenu=function(_3c40){
oThis.onContextMenu(oThis.x+_3c40.x,_3c40.y+oThis.y);
};
this.tmpMouseEnter=function(_3c41){
oThis.onMouseEnter();
};
this.tmpMouseLeave=function(_3c42){
oThis.onMouseLeave();
};
this.tmpDragend=function(_3c43){
oThis.onDragend();
};
this.tmpDragstart=function(_3c44){
var w=oThis.workflow;
w.showMenu(null);
if(w.toolPalette&&w.toolPalette.activeTool){
_3c44.returnValue=false;
w.onMouseDown(oThis.x+_3c44.x,_3c44.y+oThis.y);
w.onMouseUp(oThis.x+_3c44.x,_3c44.y+oThis.y);
return;
}
if(!(oThis instanceof ResizeHandle)&&!(oThis instanceof Port)){
var line=w.getBestLine(oThis.x+_3c44.x,_3c44.y+oThis.y);
if(line!==null){
_3c44.returnValue=false;
w.setCurrentSelection(line);
w.showLineResizeHandles(line);
w.onMouseDown(oThis.x+_3c44.x,_3c44.y+oThis.y);
return;
}else{
if(oThis.isSelectable()){
w.showResizeHandles(oThis);
w.setCurrentSelection(oThis);
}
}
}
_3c44.returnValue=oThis.onDragstart(_3c44.x,_3c44.y);
};
this.tmpDrag=function(_3c47){
oThis.onDrag();
};
this.tmpDoubleClick=function(_3c48){
oThis.onDoubleClick();
};
this.draggable.addEventListener("contextmenu",this.tmpContextMenu);
this.draggable.addEventListener("mouseenter",this.tmpMouseEnter);
this.draggable.addEventListener("mouseleave",this.tmpMouseLeave);
this.draggable.addEventListener("dragend",this.tmpDragend);
this.draggable.addEventListener("dragstart",this.tmpDragstart);
this.draggable.addEventListener("drag",this.tmpDrag);
this.draggable.addEventListener("dblclick",this.tmpDoubleClick);
}
this.workflow=_3c3d;
};
Figure.prototype.createHTMLElement=function(){
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
return item;
};
Figure.prototype.setParent=function(_3c4a){
this.parent=_3c4a;
};
Figure.prototype.getParent=function(){
return this.parent;
};
Figure.prototype.getZOrder=function(){
return this.html.style.zIndex;
};
Figure.prototype.setZOrder=function(index){
this.html.style.zIndex=index;
};
Figure.prototype.hasFixedPosition=function(){
return false;
};
Figure.prototype.getMinWidth=function(){
return 5;
};
Figure.prototype.getMinHeight=function(){
return 5;
};
Figure.prototype.getHTMLElement=function(){
if(this.html===null){
this.html=this.createHTMLElement();
}
return this.html;
};
Figure.prototype.paint=function(){
};
Figure.prototype.setBorder=function(_3c4c){
if(this.border!==null){
this.border.figure=null;
}
this.border=_3c4c;
this.border.figure=this;
this.border.refresh();
this.setDocumentDirty();
};
Figure.prototype.onRemove=function(_3c4d){
};
Figure.prototype.onContextMenu=function(x,y){
var menu=this.getContextMenu();
if(menu!==null){
this.workflow.showMenu(menu,x,y);
}
};
Figure.prototype.getContextMenu=function(){
return null;
};
Figure.prototype.onDoubleClick=function(){
};
Figure.prototype.onMouseEnter=function(){
};
Figure.prototype.onMouseLeave=function(){
};
Figure.prototype.onDrag=function(){
this.x=this.draggable.getLeft();
this.y=this.draggable.getTop();
if(this.isMoving==false){
this.isMoving=true;
this.alphaBeforeOnDrag=this.getAlpha();
this.setAlpha(this.alphaBeforeOnDrag*0.5);
}
this.fireMoveEvent();
};
Figure.prototype.onDragend=function(){
if(this.getWorkflow().getEnableSmoothFigureHandling()===true){
var oThis=this;
var _3c52=function(){
if(oThis.alpha<oThis.alphaBeforeOnDrag){
oThis.setAlpha(Math.min(1,oThis.alpha+0.05));
}else{
window.clearInterval(oThis.timer);
oThis.timer=-1;
}
};
if(oThis.timer>0){
window.clearInterval(oThis.timer);
}
oThis.timer=window.setInterval(_3c52,20);
}else{
this.setAlpha(this.alphaBeforeOnDrag);
}
this.command.setPosition(this.x,this.y);
this.workflow.commandStack.execute(this.command);
this.command=null;
this.isMoving=false;
this.workflow.hideSnapToHelperLines();
this.fireMoveEvent();
};
Figure.prototype.onDragstart=function(x,y){
this.command=this.createCommand(new EditPolicy(EditPolicy.MOVE));
return this.command!==null;
};
Figure.prototype.setCanDrag=function(flag){
this.canDrag=flag;
if(flag){
this.html.style.cursor="move";
}else{
this.html.style.cursor="";
}
};
Figure.prototype.getCanDrag=function(){
return this.canDrag;
};
Figure.prototype.setAlpha=function(_3c56){
if(this.alpha===_3c56){
return;
}
this.alpha=Math.max(0,Math.min(1,_3c56));
if(this.alpha==1){
this.html.style.filter="";
this.html.style.opacity="";
}else{
this.html.style.filter="alpha(opacity="+Math.round(this.alpha*100)+")";
this.html.style.opacity=this.alpha;
}
};
Figure.prototype.getAlpha=function(){
return this.alpha;
};
Figure.prototype.setDimension=function(w,h){
this.width=Math.max(this.getMinWidth(),w);
this.height=Math.max(this.getMinHeight(),h);
if(this.html===null){
return;
}
this.html.style.width=this.width+"px";
this.html.style.height=this.height+"px";
this.fireMoveEvent();
if(this.workflow!==null&&this.workflow.getCurrentSelection()==this){
this.workflow.showResizeHandles(this);
}
};
Figure.prototype.setPosition=function(xPos,yPos){
this.x=xPos;
this.y=yPos;
if(this.html===null){
return;
}
this.html.style.left=this.x+"px";
this.html.style.top=this.y+"px";
this.fireMoveEvent();
if(this.workflow!==null&&this.workflow.getCurrentSelection()==this){
this.workflow.showResizeHandles(this);
}
};
Figure.prototype.isResizeable=function(){
return this.resizeable;
};
Figure.prototype.setResizeable=function(flag){
this.resizeable=flag;
};
Figure.prototype.isSelectable=function(){
return this.selectable;
};
Figure.prototype.setSelectable=function(flag){
this.selectable=flag;
};
Figure.prototype.isStrechable=function(){
return true;
};
Figure.prototype.isDeleteable=function(){
return this.deleteable;
};
Figure.prototype.setDeleteable=function(flag){
this.deleteable=flag;
};
Figure.prototype.setCanSnapToHelper=function(flag){
this.canSnapToHelper=flag;
};
Figure.prototype.getCanSnapToHelper=function(){
return this.canSnapToHelper;
};
Figure.prototype.getSnapToGridAnchor=function(){
return this.snapToGridAnchor;
};
Figure.prototype.setSnapToGridAnchor=function(point){
this.snapToGridAnchor=point;
};
Figure.prototype.getBounds=function(){
return new Dimension(this.getX(),this.getY(),this.getWidth(),this.getHeight());
};
Figure.prototype.getWidth=function(){
return this.width;
};
Figure.prototype.getHeight=function(){
return this.height;
};
Figure.prototype.getY=function(){
return this.y;
};
Figure.prototype.getX=function(){
return this.x;
};
Figure.prototype.getAbsoluteY=function(){
return this.y;
};
Figure.prototype.getAbsoluteX=function(){
return this.x;
};
Figure.prototype.onKeyDown=function(_3c60,ctrl){
if(_3c60==46){
this.workflow.getCommandStack().execute(this.createCommand(new EditPolicy(EditPolicy.DELETE)));
}
if(ctrl){
this.workflow.onKeyDown(_3c60,ctrl);
}
};
Figure.prototype.getPosition=function(){
return new Point(this.x,this.y);
};
Figure.prototype.isOver=function(iX,iY){
var x=this.getAbsoluteX();
var y=this.getAbsoluteY();
var iX2=x+this.width;
var iY2=y+this.height;
return (iX>=x&&iX<=iX2&&iY>=y&&iY<=iY2);
};
Figure.prototype.attachMoveListener=function(_3c68){
if(_3c68===null||this.moveListener===null){
return;
}
this.moveListener.add(_3c68);
};
Figure.prototype.detachMoveListener=function(_3c69){
if(_3c69===null||this.moveListener===null){
return;
}
this.moveListener.remove(_3c69);
};
Figure.prototype.fireMoveEvent=function(){
this.setDocumentDirty();
var size=this.moveListener.getSize();
for(var i=0;i<size;i++){
this.moveListener.get(i).onOtherFigureMoved(this);
}
};
Figure.prototype.setModel=function(model){
if(this.model!==null){
this.model.removePropertyChangeListener(this);
}
this.model=model;
if(this.model!==null){
this.model.addPropertyChangeListener(this);
}
};
Figure.prototype.getModel=function(){
return this.model;
};
Figure.prototype.onOtherFigureMoved=function(_3c6d){
};
Figure.prototype.setDocumentDirty=function(){
if(this.workflow!==null){
this.workflow.setDocumentDirty();
}
};
Figure.prototype.disableTextSelection=function(_3c6e){
_3c6e.onselectstart=function(){
return false;
};
_3c6e.unselectable="on";
_3c6e.style.MozUserSelect="none";
_3c6e.onmousedown=function(){
return false;
};
};
Figure.prototype.createCommand=function(_3c6f){
if(_3c6f.getPolicy()==EditPolicy.MOVE){
if(!this.canDrag){
return null;
}
return new CommandMove(this);
}
if(_3c6f.getPolicy()==EditPolicy.DELETE){
if(!this.isDeleteable()){
return null;
}
return new CommandDelete(this);
}
if(_3c6f.getPolicy()==EditPolicy.RESIZE){
if(!this.isResizeable()){
return null;
}
return new CommandResize(this);
}
return null;
};
Node=function(){
this.bgColor=null;
this.lineColor=new Color(128,128,255);
this.lineStroke=1;
this.ports=new ArrayList();
Figure.call(this);
};
Node.prototype=new Figure();
Node.prototype.type="Node";
Node.prototype.dispose=function(){
for(var i=0;i<this.ports.getSize();i++){
this.ports.get(i).dispose();
}
this.ports=null;
Figure.prototype.dispose.call(this);
};
Node.prototype.createHTMLElement=function(){
var item=Figure.prototype.createHTMLElement.call(this);
item.style.width="auto";
item.style.height="auto";
item.style.margin="0px";
item.style.padding="0px";
if(this.lineColor!==null){
item.style.border=this.lineStroke+"px solid "+this.lineColor.getHTMLStyle();
}
item.style.fontSize="1px";
if(this.bgColor!==null){
item.style.backgroundColor=this.bgColor.getHTMLStyle();
}
return item;
};
Node.prototype.paint=function(){
Figure.prototype.paint.call(this);
for(var i=0;i<this.ports.getSize();i++){
this.ports.get(i).paint();
}
};
Node.prototype.getPorts=function(){
return this.ports;
};
Node.prototype.getInputPorts=function(){
var _47c0=new ArrayList();
for(var i=0;i<this.ports.getSize();i++){
var port=this.ports.get(i);
if(port instanceof InputPort){
_47c0.add(port);
}
}
return _47c0;
};
Node.prototype.getOutputPorts=function(){
var _47c3=new ArrayList();
for(var i=0;i<this.ports.getSize();i++){
var port=this.ports.get(i);
if(port instanceof OutputPort){
_47c3.add(port);
}
}
return _47c3;
};
Node.prototype.getPort=function(_47c6){
if(this.ports===null){
return null;
}
for(var i=0;i<this.ports.getSize();i++){
var port=this.ports.get(i);
if(port.getName()==_47c6){
return port;
}
}
};
Node.prototype.getInputPort=function(_47c9){
if(this.ports===null){
return null;
}
for(var i=0;i<this.ports.getSize();i++){
var port=this.ports.get(i);
if(port.getName()==_47c9&&port instanceof InputPort){
return port;
}
}
};
Node.prototype.getOutputPort=function(_47cc){
if(this.ports===null){
return null;
}
for(var i=0;i<this.ports.getSize();i++){
var port=this.ports.get(i);
if(port.getName()==_47cc&&port instanceof OutputPort){
return port;
}
}
};
Node.prototype.addPort=function(port,x,y){
this.ports.add(port);
port.setOrigin(x,y);
port.setPosition(x,y);
port.setParent(this);
port.setDeleteable(false);
this.html.appendChild(port.getHTMLElement());
if(this.workflow!==null){
this.workflow.registerPort(port);
}
};
Node.prototype.removePort=function(port){
if(this.ports!==null){
this.ports.remove(port);
}
try{
this.html.removeChild(port.getHTMLElement());
}
catch(exc){
}
if(this.workflow!==null){
this.workflow.unregisterPort(port);
}
var _47d3=port.getConnections();
for(var i=0;i<_47d3.getSize();++i){
this.workflow.removeFigure(_47d3.get(i));
}
};
Node.prototype.setWorkflow=function(_47d5){
var _47d6=this.workflow;
Figure.prototype.setWorkflow.call(this,_47d5);
if(_47d6!==null){
for(var i=0;i<this.ports.getSize();i++){
_47d6.unregisterPort(this.ports.get(i));
}
}
if(this.workflow!==null){
for(var i=0;i<this.ports.getSize();i++){
this.workflow.registerPort(this.ports.get(i));
}
}
};
Node.prototype.setBackgroundColor=function(color){
this.bgColor=color;
if(this.bgColor!==null){
this.html.style.backgroundColor=this.bgColor.getHTMLStyle();
}else{
this.html.style.backgroundColor="transparent";
}
};
Node.prototype.getBackgroundColor=function(){
return this.bgColor;
};
Node.prototype.setColor=function(color){
this.lineColor=color;
if(this.lineColor!==null){
this.html.style.border=this.lineStroke+"px solid "+this.lineColor.getHTMLStyle();
}else{
this.html.style.border="0px";
}
};
Node.prototype.setLineWidth=function(w){
this.lineStroke=w;
if(this.lineColor!==null){
this.html.style.border=this.lineStroke+"px solid "+this.lineColor.getHTMLStyle();
}else{
this.html.style.border="0px";
}
};
Node.prototype.getModelSourceConnections=function(){
throw "You must override the method [Node.prototype.getModelSourceConnections]";
};
Node.prototype.refreshConnections=function(){
if(this.workflow!==null){
this.workflow.refreshConnections(this);
}
};
VectorFigure=function(){
this.bgColor=null;
this.lineColor=new Color(0,0,0);
this.stroke=1;
this.graphics=null;
Node.call(this);
};
VectorFigure.prototype=new Node;
VectorFigure.prototype.type="VectorFigure";
VectorFigure.prototype.dispose=function(){
Node.prototype.dispose.call(this);
this.bgColor=null;
this.lineColor=null;
if(this.graphics!==null){
this.graphics.clear();
}
this.graphics=null;
};
VectorFigure.prototype.createHTMLElement=function(){
var item=Node.prototype.createHTMLElement.call(this);
item.style.border="0px";
item.style.backgroundColor="transparent";
return item;
};
VectorFigure.prototype.setWorkflow=function(_4671){
Node.prototype.setWorkflow.call(this,_4671);
if(this.workflow===null){
this.graphics.clear();
this.graphics=null;
}
};
VectorFigure.prototype.paint=function(){
if(this.html===null){
return;
}
try{
if(this.graphics===null){
this.graphics=new jsGraphics(this.html);
}else{
this.graphics.clear();
}
Node.prototype.paint.call(this);
for(var i=0;i<this.ports.getSize();i++){
this.getHTMLElement().appendChild(this.ports.get(i).getHTMLElement());
}
}
catch(e){
pushErrorStack(e,"VectorFigure.prototype.paint=function()["+area+"]");
}
};
VectorFigure.prototype.setDimension=function(w,h){
Node.prototype.setDimension.call(this,w,h);
if(this.graphics!==null){
this.paint();
}
};
VectorFigure.prototype.setBackgroundColor=function(color){
this.bgColor=color;
if(this.graphics!==null){
this.paint();
}
};
VectorFigure.prototype.getBackgroundColor=function(){
return this.bgColor;
};
VectorFigure.prototype.setLineWidth=function(w){
this.stroke=w;
if(this.graphics!==null){
this.paint();
}
};
VectorFigure.prototype.setColor=function(color){
this.lineColor=color;
if(this.graphics!==null){
this.paint();
}
};
VectorFigure.prototype.getColor=function(){
return this.lineColor;
};
SVGFigure=function(width,_4a07){
this.bgColor=null;
this.lineColor=new Color(0,0,0);
this.stroke=1;
this.context=null;
Node.call(this);
if(width&&_4a07){
this.setDimension(width,_4a07);
}
};
SVGFigure.prototype=new Node();
SVGFigure.prototype.type="SVGFigure";
SVGFigure.prototype.createHTMLElement=function(){
var item=new MooCanvas(this.id,{width:100,height:100});
item.style.position="absolute";
item.style.left=this.x+"px";
item.style.top=this.y+"px";
item.style.zIndex=""+Figure.ZOrderBaseIndex;
this.context=item.getContext("2d");
return item;
};
SVGFigure.prototype.paint=function(){
this.context.clearRect(0,0,this.getWidth(),this.getHeight());
this.context.fillStyle="rgba(200,0,0,0.3)";
this.context.fillRect(0,0,this.getWidth(),this.getHeight());
};
SVGFigure.prototype.setDimension=function(w,h){
Node.prototype.setDimension.call(this,w,h);
this.html.width=w;
this.html.height=h;
this.html.style.width=w+"px";
this.html.style.height=h+"px";
if(this.context!==null){
if(this.context.element){
this.context.element.style.width=w+"px";
this.context.element.style.height=h+"px";
}
this.paint();
}
};
SVGFigure.prototype.setBackgroundColor=function(color){
this.bgColor=color;
if(this.graphics!==null){
this.paint();
}
};
SVGFigure.prototype.getBackgroundColor=function(){
return this.bgColor;
};
SVGFigure.prototype.setLineWidth=function(w){
this.stroke=w;
if(this.context!==null){
this.paint();
}
};
SVGFigure.prototype.setColor=function(color){
this.lineColor=color;
if(this.context!==null){
this.paint();
}
};
SVGFigure.prototype.getColor=function(){
return this.lineColor;
};
Label=function(msg){
this.msg=msg;
this.bgColor=null;
this.color=new Color(0,0,0);
this.fontSize=10;
this.textNode=null;
this.align="center";
Figure.call(this);
};
Label.prototype=new Figure();
Label.prototype.type="Label";
Label.prototype.createHTMLElement=function(){
var item=Figure.prototype.createHTMLElement.call(this);
this.textNode=document.createTextNode(this.msg);
item.appendChild(this.textNode);
item.style.color=this.color.getHTMLStyle();
item.style.fontSize=this.fontSize+"pt";
item.style.width="auto";
item.style.height="auto";
item.style.paddingLeft="3px";
item.style.paddingRight="3px";
item.style.textAlign=this.align;
item.style.MozUserSelect="none";
this.disableTextSelection(item);
if(this.bgColor!==null){
item.style.backgroundColor=this.bgColor.getHTMLStyle();
}
return item;
};
Label.prototype.isResizeable=function(){
return false;
};
Label.prototype.setWordwrap=function(flag){
this.html.style.whiteSpace=flag?"wrap":"nowrap";
};
Label.prototype.setAlign=function(align){
this.align=align;
this.html.style.textAlign=align;
};
Label.prototype.setBackgroundColor=function(color){
this.bgColor=color;
if(this.bgColor!==null){
this.html.style.backgroundColor=this.bgColor.getHTMLStyle();
}else{
this.html.style.backgroundColor="transparent";
}
};
Label.prototype.setColor=function(color){
this.color=color;
this.html.style.color=this.color.getHTMLStyle();
};
Label.prototype.setFontSize=function(size){
this.fontSize=size;
this.html.style.fontSize=this.fontSize+"pt";
};
Label.prototype.setDimension=function(w,h){
};
Label.prototype.getWidth=function(){
if(window.getComputedStyle){
return parseInt(getComputedStyle(this.html,"").getPropertyValue("width"));
}
return parseInt(this.html.clientWidth);
};
Label.prototype.getHeight=function(){
if(window.getComputedStyle){
return parseInt(getComputedStyle(this.html,"").getPropertyValue("height"));
}
return parseInt(this.html.clientHeight);
};
Label.prototype.getText=function(){
return this.msg;
};
Label.prototype.setText=function(text){
this.msg=text;
this.html.removeChild(this.textNode);
this.textNode=document.createTextNode(this.msg);
this.html.appendChild(this.textNode);
};
Label.prototype.setStyledText=function(text){
this.msg=text;
this.html.removeChild(this.textNode);
this.textNode=document.createElement("div");
this.textNode.style.whiteSpace="nowrap";
this.textNode.innerHTML=text;
this.html.appendChild(this.textNode);
};
Oval=function(){
VectorFigure.call(this);
};
Oval.prototype=new VectorFigure();
Oval.prototype.type="Oval";
Oval.prototype.paint=function(){
if(this.html===null){
return;
}
try{
VectorFigure.prototype.paint.call(this);
this.graphics.setStroke(this.stroke);
if(this.bgColor!==null){
this.graphics.setColor(this.bgColor.getHTMLStyle());
this.graphics.fillOval(0,0,this.getWidth()-1,this.getHeight()-1);
}
if(this.lineColor!==null){
this.graphics.setColor(this.lineColor.getHTMLStyle());
this.graphics.drawOval(0,0,this.getWidth()-1,this.getHeight()-1);
}
this.graphics.paint();
}
catch(e){
pushErrorStack(e,"Oval.prototype.paint=function()");
}
};
Circle=function(_3e42){
Oval.call(this);
if(_3e42){
this.setDimension(_3e42,_3e42);
}
};
Circle.prototype=new Oval();
Circle.prototype.type="Circle";
Circle.prototype.setDimension=function(w,h){
if(w>h){
Oval.prototype.setDimension.call(this,w,w);
}else{
Oval.prototype.setDimension.call(this,h,h);
}
};
Circle.prototype.isStrechable=function(){
return false;
};
Rectangle=function(width,_42b8){
this.bgColor=null;
this.lineColor=new Color(0,0,0);
this.lineStroke=1;
Figure.call(this);
if(width&&_42b8){
this.setDimension(width,_42b8);
}
};
Rectangle.prototype=new Figure();
Rectangle.prototype.type="Rectangle";
Rectangle.prototype.dispose=function(){
Figure.prototype.dispose.call(this);
this.bgColor=null;
this.lineColor=null;
};
Rectangle.prototype.createHTMLElement=function(){
var item=Figure.prototype.createHTMLElement.call(this);
item.style.width="auto";
item.style.height="auto";
item.style.margin="0px";
item.style.padding="0px";
item.style.border=this.lineStroke+"px solid "+this.lineColor.getHTMLStyle();
item.style.fontSize="1px";
item.style.lineHeight="1px";
item.innerHTML="&nbsp";
if(this.bgColor!==null){
item.style.backgroundColor=this.bgColor.getHTMLStyle();
}
return item;
};
Rectangle.prototype.setBackgroundColor=function(color){
this.bgColor=color;
if(this.bgColor!==null){
this.html.style.backgroundColor=this.bgColor.getHTMLStyle();
}else{
this.html.style.backgroundColor="transparent";
}
};
Rectangle.prototype.getBackgroundColor=function(){
return this.bgColor;
};
Rectangle.prototype.setColor=function(color){
this.lineColor=color;
if(this.lineColor!==null){
this.html.style.border=this.lineStroke+"px solid "+this.lineColor.getHTMLStyle();
}else{
this.html.style.border=this.lineStroke+"0px";
}
};
Rectangle.prototype.getColor=function(){
return this.lineColor;
};
Rectangle.prototype.getWidth=function(){
return Figure.prototype.getWidth.call(this)+2*this.lineStroke;
};
Rectangle.prototype.getHeight=function(){
return Figure.prototype.getHeight.call(this)+2*this.lineStroke;
};
Rectangle.prototype.setDimension=function(w,h){
Figure.prototype.setDimension.call(this,w-2*this.lineStroke,h-2*this.lineStroke);
};
Rectangle.prototype.setLineWidth=function(w){
var diff=w-this.lineStroke;
this.setDimension(this.getWidth()-2*diff,this.getHeight()-2*diff);
this.lineStroke=w;
var c="transparent";
if(this.lineColor!==null){
c=this.lineColor.getHTMLStyle();
}
this.html.style.border=this.lineStroke+"px solid "+c;
};
Rectangle.prototype.getLineWidth=function(){
return this.lineStroke;
};
ImageFigure=function(url){
if(url===undefined){
url=null;
}
this.url=url;
Node.call(this);
this.setDimension(40,40);
};
ImageFigure.prototype=new Node;
ImageFigure.prototype.type="Image";
ImageFigure.prototype.createHTMLElement=function(){
var item=Node.prototype.createHTMLElement.call(this);
item.style.width=this.width+"px";
item.style.height=this.height+"px";
item.style.margin="0px";
item.style.padding="0px";
item.style.border="0px";
if(this.url!==null){
item.style.backgroundImage="url("+this.url+")";
}else{
item.style.backgroundImage="";
}
return item;
};
ImageFigure.prototype.setColor=function(color){
};
ImageFigure.prototype.isResizeable=function(){
return false;
};
ImageFigure.prototype.setImage=function(url){
if(url===undefined){
url=null;
}
this.url=url;
if(this.url!==null){
this.html.style.backgroundImage="url("+this.url+")";
}else{
this.html.style.backgroundImage="";
}
};
Port=function(_3e6f,_3e70){
Corona=function(){
};
Corona.prototype=new Circle();
Corona.prototype.setAlpha=function(_3e71){
Circle.prototype.setAlpha.call(this,Math.min(0.3,_3e71));
this.setDeleteable(false);
this.setCanDrag(false);
this.setResizeable(false);
this.setSelectable(false);
};
if(_3e6f===null||_3e6f===undefined){
this.currentUIRepresentation=new Circle();
}else{
this.currentUIRepresentation=_3e6f;
}
if(_3e70===null||_3e70===undefined){
this.connectedUIRepresentation=new Circle();
this.connectedUIRepresentation.setColor(null);
}else{
this.connectedUIRepresentation=_3e70;
}
this.disconnectedUIRepresentation=this.currentUIRepresentation;
this.hideIfConnected=false;
this.uiRepresentationAdded=true;
this.parentNode=null;
this.originX=0;
this.originY=0;
this.coronaWidth=10;
this.corona=null;
Rectangle.call(this);
this.setDimension(8,8);
this.setBackgroundColor(new Color(100,180,100));
this.setColor(new Color(90,150,90));
Rectangle.prototype.setColor.call(this,null);
this.dropable=new DropTarget(this.html);
this.dropable.node=this;
this.dropable.addEventListener("dragenter",function(_3e72){
_3e72.target.node.onDragEnter(_3e72.relatedTarget.node);
});
this.dropable.addEventListener("dragleave",function(_3e73){
_3e73.target.node.onDragLeave(_3e73.relatedTarget.node);
});
this.dropable.addEventListener("drop",function(_3e74){
_3e74.relatedTarget.node.onDrop(_3e74.target.node);
});
};
Port.prototype=new Rectangle();
Port.prototype.type="Port";
Port.ZOrderBaseIndex=5000;
Port.setZOrderBaseIndex=function(index){
Port.ZOrderBaseIndex=index;
};
Port.prototype.setHideIfConnected=function(flag){
this.hideIfConnected=flag;
};
Port.prototype.dispose=function(){
var size=this.moveListener.getSize();
for(var i=0;i<size;i++){
var _3e79=this.moveListener.get(i);
this.parentNode.workflow.removeFigure(_3e79);
_3e79.dispose();
}
Rectangle.prototype.dispose.call(this);
this.parentNode=null;
this.dropable.node=null;
this.dropable=null;
this.disconnectedUIRepresentation.dispose();
this.connectedUIRepresentation.dispose();
};
Port.prototype.createHTMLElement=function(){
var item=Rectangle.prototype.createHTMLElement.call(this);
item.style.zIndex=Port.ZOrderBaseIndex;
this.currentUIRepresentation.html.zIndex=Port.ZOrderBaseIndex;
item.appendChild(this.currentUIRepresentation.html);
this.uiRepresentationAdded=true;
return item;
};
Port.prototype.setUiRepresentation=function(_3e7b){
if(_3e7b===null){
_3e7b=new Figure();
}
if(this.uiRepresentationAdded){
this.html.removeChild(this.currentUIRepresentation.getHTMLElement());
}
this.html.appendChild(_3e7b.getHTMLElement());
_3e7b.paint();
this.currentUIRepresentation=_3e7b;
};
Port.prototype.onMouseEnter=function(){
this.setLineWidth(2);
};
Port.prototype.onMouseLeave=function(){
this.setLineWidth(0);
};
Port.prototype.setDimension=function(width,_3e7d){
Rectangle.prototype.setDimension.call(this,width,_3e7d);
this.connectedUIRepresentation.setDimension(width,_3e7d);
this.disconnectedUIRepresentation.setDimension(width,_3e7d);
this.setPosition(this.x,this.y);
};
Port.prototype.setBackgroundColor=function(color){
this.currentUIRepresentation.setBackgroundColor(color);
};
Port.prototype.getBackgroundColor=function(){
return this.currentUIRepresentation.getBackgroundColor();
};
Port.prototype.getConnections=function(){
var _3e7f=new ArrayList();
var size=this.moveListener.getSize();
for(var i=0;i<size;i++){
var _3e82=this.moveListener.get(i);
if(_3e82 instanceof Connection){
_3e7f.add(_3e82);
}
}
return _3e7f;
};
Port.prototype.setColor=function(color){
this.currentUIRepresentation.setColor(color);
};
Port.prototype.getColor=function(){
return this.currentUIRepresentation.getColor();
};
Port.prototype.setLineWidth=function(width){
this.currentUIRepresentation.setLineWidth(width);
};
Port.prototype.getLineWidth=function(){
return this.currentUIRepresentation.getLineWidth();
};
Port.prototype.paint=function(){
try{
this.currentUIRepresentation.paint();
}
catch(e){
pushErrorStack(e,"Port.prototype.paint=function()");
}
};
Port.prototype.setPosition=function(xPos,yPos){
this.originX=xPos;
this.originY=yPos;
Rectangle.prototype.setPosition.call(this,xPos,yPos);
if(this.html===null){
return;
}
this.html.style.left=(this.x-this.getWidth()/2)+"px";
this.html.style.top=(this.y-this.getHeight()/2)+"px";
};
Port.prototype.setParent=function(_3e87){
if(this.parentNode!==null){
this.parentNode.detachMoveListener(this);
}
this.parentNode=_3e87;
if(this.parentNode!==null){
this.parentNode.attachMoveListener(this);
}
};
Port.prototype.attachMoveListener=function(_3e88){
Rectangle.prototype.attachMoveListener.call(this,_3e88);
if(this.hideIfConnected==true){
this.setUiRepresentation(this.connectedUIRepresentation);
}
};
Port.prototype.detachMoveListener=function(_3e89){
Rectangle.prototype.detachMoveListener.call(this,_3e89);
if(this.getConnections().getSize()==0){
this.setUiRepresentation(this.disconnectedUIRepresentation);
}
};
Port.prototype.getParent=function(){
return this.parentNode;
};
Port.prototype.onDrag=function(){
Rectangle.prototype.onDrag.call(this);
this.parentNode.workflow.showConnectionLine(this.parentNode.x+this.x,this.parentNode.y+this.y,this.parentNode.x+this.originX,this.parentNode.y+this.originY);
};
Port.prototype.getCoronaWidth=function(){
return this.coronaWidth;
};
Port.prototype.setCoronaWidth=function(width){
this.coronaWidth=width;
};
Port.prototype.setOrigin=function(x,y){
this.originX=x;
this.originY=y;
};
Port.prototype.onDragend=function(){
this.setAlpha(1);
this.setPosition(this.originX,this.originY);
this.parentNode.workflow.hideConnectionLine();
document.body.focus();
};
Port.prototype.onDragEnter=function(port){
var _3e8e=new EditPolicy(EditPolicy.CONNECT);
_3e8e.canvas=this.parentNode.workflow;
_3e8e.source=port;
_3e8e.target=this;
var _3e8f=this.createCommand(_3e8e);
if(_3e8f===null){
return;
}
this.parentNode.workflow.connectionLine.setColor(new Color(0,150,0));
this.parentNode.workflow.connectionLine.setLineWidth(3);
this.showCorona(true);
};
Port.prototype.onDragLeave=function(port){
this.parentNode.workflow.connectionLine.setColor(new Color(0,0,0));
this.parentNode.workflow.connectionLine.setLineWidth(1);
this.showCorona(false);
};
Port.prototype.onDrop=function(port){
var _3e92=new EditPolicy(EditPolicy.CONNECT);
_3e92.canvas=this.parentNode.workflow;
_3e92.source=port;
_3e92.target=this;
var _3e93=this.createCommand(_3e92);
if(_3e93!==null){
this.parentNode.workflow.getCommandStack().execute(_3e93);
}
};
Port.prototype.getAbsolutePosition=function(){
return new Point(this.getAbsoluteX(),this.getAbsoluteY());
};
Port.prototype.getAbsoluteBounds=function(){
return new Dimension(this.getAbsoluteX(),this.getAbsoluteY(),this.getWidth(),this.getHeight());
};
Port.prototype.getAbsoluteY=function(){
return this.originY+this.parentNode.getY();
};
Port.prototype.getAbsoluteX=function(){
return this.originX+this.parentNode.getX();
};
Port.prototype.onOtherFigureMoved=function(_3e94){
this.fireMoveEvent();
};
Port.prototype.getName=function(){
return this.name;
};
Port.prototype.setName=function(name){
this.name=name;
};
Port.prototype.isOver=function(iX,iY){
var x=this.getAbsoluteX()-this.coronaWidth-this.getWidth()/2;
var y=this.getAbsoluteY()-this.coronaWidth-this.getHeight()/2;
var iX2=x+this.width+(this.coronaWidth*2)+this.getWidth()/2;
var iY2=y+this.height+(this.coronaWidth*2)+this.getHeight()/2;
return (iX>=x&&iX<=iX2&&iY>=y&&iY<=iY2);
};
Port.prototype.showCorona=function(flag,_3e9d){
if(flag===true){
this.corona=new Corona();
this.corona.setAlpha(0.3);
this.corona.setBackgroundColor(new Color(0,125,125));
this.corona.setColor(null);
this.corona.setDimension(this.getWidth()+(this.getCoronaWidth()*2),this.getWidth()+(this.getCoronaWidth()*2));
this.parentNode.getWorkflow().addFigure(this.corona,this.getAbsoluteX()-this.getCoronaWidth()-this.getWidth()/2,this.getAbsoluteY()-this.getCoronaWidth()-this.getHeight()/2);
}else{
if(flag===false&&this.corona!==null){
this.parentNode.getWorkflow().removeFigure(this.corona);
this.corona=null;
}
}
};
Port.prototype.createCommand=function(_3e9e){
if(_3e9e.getPolicy()===EditPolicy.MOVE){
if(!this.canDrag){
return null;
}
return new CommandMovePort(this);
}
if(_3e9e.getPolicy()===EditPolicy.CONNECT){
if(_3e9e.source.parentNode.id===_3e9e.target.parentNode.id){
return null;
}else{
return new CommandConnect(_3e9e.canvas,_3e9e.source,_3e9e.target);
}
}
return null;
};
InputPort=function(_3865){
Port.call(this,_3865);
};
InputPort.prototype=new Port();
InputPort.prototype.type="InputPort";
InputPort.prototype.onDragstart=function(x,y){
if(!this.canDrag){
return false;
}
return true;
};
InputPort.prototype.onDragEnter=function(port){
if(port instanceof OutputPort){
Port.prototype.onDragEnter.call(this,port);
}else{
if(port instanceof LineStartResizeHandle){
var line=this.workflow.currentSelection;
if(line instanceof Connection&&line.getSource() instanceof InputPort){
Port.prototype.onDragEnter.call(this,line.getTarget());
}
}else{
if(port instanceof LineEndResizeHandle){
var line=this.workflow.currentSelection;
if(line instanceof Connection&&line.getTarget() instanceof InputPort){
Port.prototype.onDragEnter.call(this,line.getSource());
}
}
}
}
};
InputPort.prototype.onDragLeave=function(port){
if(port instanceof OutputPort){
Port.prototype.onDragLeave.call(this,port);
}else{
if(port instanceof LineStartResizeHandle){
var line=this.workflow.currentSelection;
if(line instanceof Connection&&line.getSource() instanceof InputPort){
Port.prototype.onDragLeave.call(this,line.getTarget());
}
}else{
if(port instanceof LineEndResizeHandle){
var line=this.workflow.currentSelection;
if(line instanceof Connection&&line.getTarget() instanceof InputPort){
Port.prototype.onDragLeave.call(this,line.getSource());
}
}
}
}
};
InputPort.prototype.createCommand=function(_386c){
if(_386c.getPolicy()==EditPolicy.CONNECT){
if(_386c.source.parentNode.id==_386c.target.parentNode.id){
return null;
}
if(_386c.source instanceof OutputPort){
return new CommandConnect(_386c.canvas,_386c.source,_386c.target);
}
return null;
}
return Port.prototype.createCommand.call(this,_386c);
};
OutputPort=function(_43dd){
Port.call(this,_43dd);
this.maxFanOut=100;
};
OutputPort.prototype=new Port();
OutputPort.prototype.type="OutputPort";
OutputPort.prototype.onDragEnter=function(port){
if(this.getMaxFanOut()<=this.getFanOut()){
return;
}
if(port instanceof InputPort){
Port.prototype.onDragEnter.call(this,port);
}else{
if(port instanceof LineStartResizeHandle){
var line=this.workflow.currentSelection;
if(line instanceof Connection&&line.getSource() instanceof OutputPort){
Port.prototype.onDragEnter.call(this,line.getTarget());
}
}else{
if(port instanceof LineEndResizeHandle){
var line=this.workflow.currentSelection;
if(line instanceof Connection&&line.getTarget() instanceof OutputPort){
Port.prototype.onDragEnter.call(this,line.getSource());
}
}
}
}
};
OutputPort.prototype.onDragLeave=function(port){
if(port instanceof InputPort){
Port.prototype.onDragLeave.call(this,port);
}else{
if(port instanceof LineStartResizeHandle){
var line=this.workflow.currentSelection;
if(line instanceof Connection&&line.getSource() instanceof OutputPort){
Port.prototype.onDragLeave.call(this,line.getTarget());
}
}else{
if(port instanceof LineEndResizeHandle){
var line=this.workflow.currentSelection;
if(line instanceof Connection&&line.getTarget() instanceof OutputPort){
Port.prototype.onDragLeave.call(this,line.getSource());
}
}
}
}
};
OutputPort.prototype.onDragstart=function(x,y){
if(!this.canDrag){
return false;
}
if(this.maxFanOut===-1){
return true;
}
if(this.getMaxFanOut()<=this.getFanOut()){
return false;
}
return true;
};
OutputPort.prototype.setMaxFanOut=function(count){
this.maxFanOut=count;
};
OutputPort.prototype.getMaxFanOut=function(){
return this.maxFanOut;
};
OutputPort.prototype.getFanOut=function(){
if(this.getParent().workflow===null){
return 0;
}
var count=0;
var lines=this.getParent().workflow.getLines();
var size=lines.getSize();
for(var i=0;i<size;i++){
var line=lines.get(i);
if(line instanceof Connection){
if(line.getSource()==this){
count++;
}else{
if(line.getTarget()==this){
count++;
}
}
}
}
return count;
};
OutputPort.prototype.createCommand=function(_43ea){
if(_43ea.getPolicy()===EditPolicy.CONNECT){
if(_43ea.source.parentNode.id===_43ea.target.parentNode.id){
return null;
}
if(_43ea.source instanceof InputPort){
return new CommandConnect(_43ea.canvas,_43ea.target,_43ea.source);
}
return null;
}
return Port.prototype.createCommand.call(this,_43ea);
};
Line=function(){
this.lineColor=new Color(0,0,0);
this.stroke=1;
this.canvas=null;
this.parent=null;
this.workflow=null;
this.html=null;
this.graphics=null;
this.id=UUID.create();
this.startX=30;
this.startY=30;
this.endX=100;
this.endY=100;
this.alpha=1;
this.isMoving=false;
this.model=null;
this.zOrder=Line.ZOrderBaseIndex;
this.corona=Line.CoronaWidth;
this.properties={};
this.moveListener=new ArrayList();
this.setSelectable(true);
this.setDeleteable(true);
};
Line.prototype.type="Line";
Line.ZOrderBaseIndex=200;
Line.ZOrderBaseIndex=200;
Line.CoronaWidth=5;
Line.setZOrderBaseIndex=function(index){
Line.ZOrderBaseIndex=index;
};
Line.setDefaultCoronaWidth=function(width){
Line.CoronaWidth=width;
};
Line.prototype.dispose=function(){
this.canvas=null;
this.workflow=null;
if(this.graphics!==null){
this.graphics.clear();
}
this.graphics=null;
};
Line.prototype.getZOrder=function(){
return this.zOrder;
};
Line.prototype.setZOrder=function(index){
if(this.html!==null){
this.html.style.zIndex=index;
}
this.zOrder=index;
};
Line.prototype.setCoronaWidth=function(width){
this.corona=width;
};
Line.prototype.createHTMLElement=function(){
var item=document.createElement("div");
item.id=this.id;
item.style.position="absolute";
item.style.left="0px";
item.style.top="0px";
item.style.height="0px";
item.style.width="0px";
item.style.zIndex=this.zOrder;
return item;
};
Line.prototype.setId=function(id){
this.id=id;
if(this.html!==null){
this.html.id=id;
}
};
Line.prototype.getId=function(){
return this.id;
};
Line.prototype.getProperties=function(){
return this.properties;
};
Line.prototype.getProperty=function(key){
return this.properties[key];
};
Line.prototype.setProperty=function(key,value){
this.properties[key]=value;
this.setDocumentDirty();
};
Line.prototype.getHTMLElement=function(){
if(this.html===null){
this.html=this.createHTMLElement();
}
return this.html;
};
Line.prototype.getWorkflow=function(){
return this.workflow;
};
Line.prototype.isResizeable=function(){
return true;
};
Line.prototype.setCanvas=function(_3df8){
this.canvas=_3df8;
if(this.graphics!==null){
this.graphics.clear();
}
this.graphics=null;
};
Line.prototype.setWorkflow=function(_3df9){
this.workflow=_3df9;
if(this.graphics!==null){
this.graphics.clear();
}
this.graphics=null;
};
Line.prototype.paint=function(){
if(this.html===null){
return;
}
try{
if(this.graphics===null){
this.graphics=new jsGraphics(this.html);
}else{
this.graphics.clear();
}
this.graphics.setStroke(this.stroke);
this.graphics.setColor(this.lineColor.getHTMLStyle());
this.graphics.drawLine(this.startX,this.startY,this.endX,this.endY);
this.graphics.paint();
}
catch(e){
pushErrorStack(e,"Line.prototype.paint=function()");
}
};
Line.prototype.attachMoveListener=function(_3dfa){
this.moveListener.add(_3dfa);
};
Line.prototype.detachMoveListener=function(_3dfb){
this.moveListener.remove(_3dfb);
};
Line.prototype.fireMoveEvent=function(){
var size=this.moveListener.getSize();
for(var i=0;i<size;i++){
this.moveListener.get(i).onOtherFigureMoved(this);
}
};
Line.prototype.onOtherFigureMoved=function(_3dfe){
};
Line.prototype.setLineWidth=function(w){
this.stroke=w;
if(this.graphics!==null){
this.paint();
}
this.setDocumentDirty();
};
Line.prototype.setColor=function(color){
this.lineColor=color;
if(this.graphics!==null){
this.paint();
}
this.setDocumentDirty();
};
Line.prototype.getColor=function(){
return this.lineColor;
};
Line.prototype.setAlpha=function(_3e01){
if(_3e01==this.alpha){
return;
}
try{
this.html.style.MozOpacity=_3e01;
}
catch(exc1){
}
try{
this.html.style.opacity=_3e01;
}
catch(exc2){
}
try{
var _3e02=Math.round(_3e01*100);
if(_3e02>=99){
this.html.style.filter="";
}else{
this.html.style.filter="alpha(opacity="+_3e02+")";
}
}
catch(exc3){
}
this.alpha=_3e01;
};
Line.prototype.setStartPoint=function(x,y){
this.startX=x;
this.startY=y;
if(this.graphics!==null){
this.paint();
}
this.setDocumentDirty();
};
Line.prototype.setEndPoint=function(x,y){
this.endX=x;
this.endY=y;
if(this.graphics!==null){
this.paint();
}
this.setDocumentDirty();
};
Line.prototype.getStartX=function(){
return this.startX;
};
Line.prototype.getStartY=function(){
return this.startY;
};
Line.prototype.getStartPoint=function(){
return new Point(this.startX,this.startY);
};
Line.prototype.getEndX=function(){
return this.endX;
};
Line.prototype.getEndY=function(){
return this.endY;
};
Line.prototype.getEndPoint=function(){
return new Point(this.endX,this.endY);
};
Line.prototype.isSelectable=function(){
return this.selectable;
};
Line.prototype.setSelectable=function(flag){
this.selectable=flag;
};
Line.prototype.isDeleteable=function(){
return this.deleteable;
};
Line.prototype.setDeleteable=function(flag){
this.deleteable=flag;
};
Line.prototype.getLength=function(){
return Math.sqrt((this.startX-this.endX)*(this.startX-this.endX)+(this.startY-this.endY)*(this.startY-this.endY));
};
Line.prototype.getAngle=function(){
var _3e09=this.getLength();
var angle=-(180/Math.PI)*Math.asin((this.startY-this.endY)/_3e09);
if(angle<0){
if(this.endX<this.startX){
angle=Math.abs(angle)+180;
}else{
angle=360-Math.abs(angle);
}
}else{
if(this.endX<this.startX){
angle=180-angle;
}
}
return angle;
};
Line.prototype.createCommand=function(_3e0b){
if(_3e0b.getPolicy()==EditPolicy.MOVE){
var x1=this.getStartX();
var y1=this.getStartY();
var x2=this.getEndX();
var y2=this.getEndY();
return new CommandMoveLine(this,x1,y1,x2,y2);
}
if(_3e0b.getPolicy()==EditPolicy.DELETE){
if(this.isDeleteable()==false){
return null;
}
return new CommandDelete(this);
}
return null;
};
Line.prototype.setModel=function(model){
if(this.model!==null){
this.model.removePropertyChangeListener(this);
}
this.model=model;
if(this.model!==null){
this.model.addPropertyChangeListener(this);
}
};
Line.prototype.getModel=function(){
return this.model;
};
Line.prototype.onRemove=function(_3e11){
};
Line.prototype.onContextMenu=function(x,y){
var menu=this.getContextMenu();
if(menu!==null){
this.workflow.showMenu(menu,x,y);
}
};
Line.prototype.getContextMenu=function(){
return null;
};
Line.prototype.onDoubleClick=function(){
};
Line.prototype.setDocumentDirty=function(){
if(this.workflow!==null){
this.workflow.setDocumentDirty();
}
};
Line.prototype.containsPoint=function(px,py){
return Line.hit(this.corona,this.startX,this.startY,this.endX,this.endY,px,py);
};
Line.hit=function(_3e17,X1,Y1,X2,Y2,px,py){
X2-=X1;
Y2-=Y1;
px-=X1;
py-=Y1;
var _3e1e=px*X2+py*Y2;
var _3e1f;
if(_3e1e<=0){
_3e1f=0;
}else{
px=X2-px;
py=Y2-py;
_3e1e=px*X2+py*Y2;
if(_3e1e<=0){
_3e1f=0;
}else{
_3e1f=_3e1e*_3e1e/(X2*X2+Y2*Y2);
}
}
var lenSq=px*px+py*py-_3e1f;
if(lenSq<0){
lenSq=0;
}
return Math.sqrt(lenSq)<_3e17;
};
ConnectionRouter=function(){
};
ConnectionRouter.prototype.type="ConnectionRouter";
ConnectionRouter.prototype.getDirection=function(r,p){
var _43c4=Math.abs(r.x-p.x);
var _43c5=3;
var i=Math.abs(r.y-p.y);
if(i<=_43c4){
_43c4=i;
_43c5=0;
}
i=Math.abs(r.getBottom()-p.y);
if(i<=_43c4){
_43c4=i;
_43c5=2;
}
i=Math.abs(r.getRight()-p.x);
if(i<_43c4){
_43c4=i;
_43c5=1;
}
return _43c5;
};
ConnectionRouter.prototype.getEndDirection=function(conn){
var p=conn.getEndPoint();
var rect=conn.getTarget().getParent().getBounds();
return this.getDirection(rect,p);
};
ConnectionRouter.prototype.getStartDirection=function(conn){
var p=conn.getStartPoint();
var rect=conn.getSource().getParent().getBounds();
return this.getDirection(rect,p);
};
ConnectionRouter.prototype.route=function(_43cd){
};
NullConnectionRouter=function(){
};
NullConnectionRouter.prototype=new ConnectionRouter();
NullConnectionRouter.prototype.type="NullConnectionRouter";
NullConnectionRouter.prototype.invalidate=function(){
};
NullConnectionRouter.prototype.route=function(_4649){
_4649.addPoint(_4649.getStartPoint());
_4649.addPoint(_4649.getEndPoint());
};
ManhattanConnectionRouter=function(){
this.MINDIST=20;
};
ManhattanConnectionRouter.prototype=new ConnectionRouter();
ManhattanConnectionRouter.prototype.type="ManhattanConnectionRouter";
ManhattanConnectionRouter.prototype.route=function(conn){
var _3998=conn.getStartPoint();
var _3999=this.getStartDirection(conn);
var toPt=conn.getEndPoint();
var toDir=this.getEndDirection(conn);
this._route(conn,toPt,toDir,_3998,_3999);
};
ManhattanConnectionRouter.prototype._route=function(conn,_399d,_399e,toPt,toDir){
var TOL=0.1;
var _39a2=0.01;
var UP=0;
var RIGHT=1;
var DOWN=2;
var LEFT=3;
var xDiff=_399d.x-toPt.x;
var yDiff=_399d.y-toPt.y;
var point;
var dir;
if(((xDiff*xDiff)<(_39a2))&&((yDiff*yDiff)<(_39a2))){
conn.addPoint(new Point(toPt.x,toPt.y));
return;
}
if(_399e==LEFT){
if((xDiff>0)&&((yDiff*yDiff)<TOL)&&(toDir===RIGHT)){
point=toPt;
dir=toDir;
}else{
if(xDiff<0){
point=new Point(_399d.x-this.MINDIST,_399d.y);
}else{
if(((yDiff>0)&&(toDir===DOWN))||((yDiff<0)&&(toDir==UP))){
point=new Point(toPt.x,_399d.y);
}else{
if(_399e==toDir){
var pos=Math.min(_399d.x,toPt.x)-this.MINDIST;
point=new Point(pos,_399d.y);
}else{
point=new Point(_399d.x-(xDiff/2),_399d.y);
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
if(_399e==RIGHT){
if((xDiff<0)&&((yDiff*yDiff)<TOL)&&(toDir===LEFT)){
point=toPt;
dir=toDir;
}else{
if(xDiff>0){
point=new Point(_399d.x+this.MINDIST,_399d.y);
}else{
if(((yDiff>0)&&(toDir===DOWN))||((yDiff<0)&&(toDir===UP))){
point=new Point(toPt.x,_399d.y);
}else{
if(_399e==toDir){
var pos=Math.max(_399d.x,toPt.x)+this.MINDIST;
point=new Point(pos,_399d.y);
}else{
point=new Point(_399d.x-(xDiff/2),_399d.y);
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
if(_399e==DOWN){
if(((xDiff*xDiff)<TOL)&&(yDiff<0)&&(toDir==UP)){
point=toPt;
dir=toDir;
}else{
if(yDiff>0){
point=new Point(_399d.x,_399d.y+this.MINDIST);
}else{
if(((xDiff>0)&&(toDir===RIGHT))||((xDiff<0)&&(toDir===LEFT))){
point=new Point(_399d.x,toPt.y);
}else{
if(_399e===toDir){
var pos=Math.max(_399d.y,toPt.y)+this.MINDIST;
point=new Point(_399d.x,pos);
}else{
point=new Point(_399d.x,_399d.y-(yDiff/2));
}
}
}
if(xDiff>0){
dir=LEFT;
}else{
dir=RIGHT;
}
}
}else{
if(_399e==UP){
if(((xDiff*xDiff)<TOL)&&(yDiff>0)&&(toDir===DOWN)){
point=toPt;
dir=toDir;
}else{
if(yDiff<0){
point=new Point(_399d.x,_399d.y-this.MINDIST);
}else{
if(((xDiff>0)&&(toDir===RIGHT))||((xDiff<0)&&(toDir===LEFT))){
point=new Point(_399d.x,toPt.y);
}else{
if(_399e===toDir){
var pos=Math.min(_399d.y,toPt.y)-this.MINDIST;
point=new Point(_399d.x,pos);
}else{
point=new Point(_399d.x,_399d.y-(yDiff/2));
}
}
}
if(xDiff>0){
dir=LEFT;
}else{
dir=RIGHT;
}
}
}
}
}
}
this._route(conn,point,dir,toPt,toDir);
conn.addPoint(_399d);
};
BezierConnectionRouter=function(_3a03){
if(!_3a03){
this.cheapRouter=new ManhattanConnectionRouter();
}else{
this.cheapRouter=null;
}
this.iteration=5;
};
BezierConnectionRouter.prototype=new ConnectionRouter();
BezierConnectionRouter.prototype.type="BezierConnectionRouter";
BezierConnectionRouter.prototype.drawBezier=function(_3a04,_3a05,t,iter){
var n=_3a04.length-1;
var q=[];
var _3a0a=n+1;
for(var i=0;i<_3a0a;i++){
q[i]=[];
q[i][0]=_3a04[i];
}
for(var j=1;j<=n;j++){
for(var i=0;i<=(n-j);i++){
q[i][j]=new Point((1-t)*q[i][j-1].x+t*q[i+1][j-1].x,(1-t)*q[i][j-1].y+t*q[i+1][j-1].y);
}
}
var c1=[];
var c2=[];
for(var i=0;i<n+1;i++){
c1[i]=q[0][i];
c2[i]=q[i][n-i];
}
if(iter>=0){
this.drawBezier(c1,_3a05,t,--iter);
this.drawBezier(c2,_3a05,t,--iter);
}else{
for(var i=0;i<n;i++){
_3a05.push(q[i][n-i]);
}
}
};
BezierConnectionRouter.prototype.route=function(conn){
if(this.cheapRouter!==null&&(conn.getSource().getParent().isMoving===true||conn.getTarget().getParent().isMoving===true)){
this.cheapRouter.route(conn);
return;
}
var _3a10=[];
var _3a11=conn.getStartPoint();
var toPt=conn.getEndPoint();
this._route(_3a10,conn,toPt,this.getEndDirection(conn),_3a11,this.getStartDirection(conn));
var _3a13=[];
this.drawBezier(_3a10,_3a13,0.5,this.iteration);
for(var i=0;i<_3a13.length;i++){
conn.addPoint(_3a13[i]);
}
conn.addPoint(toPt);
};
BezierConnectionRouter.prototype._route=function(_3a15,conn,_3a17,_3a18,toPt,toDir){
var TOL=0.1;
var _3a1c=0.01;
var _3a1d=90;
var UP=0;
var RIGHT=1;
var DOWN=2;
var LEFT=3;
var xDiff=_3a17.x-toPt.x;
var yDiff=_3a17.y-toPt.y;
var point;
var dir;
if(((xDiff*xDiff)<(_3a1c))&&((yDiff*yDiff)<(_3a1c))){
_3a15.push(new Point(toPt.x,toPt.y));
return;
}
if(_3a18===LEFT){
if((xDiff>0)&&((yDiff*yDiff)<TOL)&&(toDir===RIGHT)){
point=toPt;
dir=toDir;
}else{
if(xDiff<0){
point=new Point(_3a17.x-_3a1d,_3a17.y);
}else{
if(((yDiff>0)&&(toDir===DOWN))||((yDiff<0)&&(toDir===UP))){
point=new Point(toPt.x,_3a17.y);
}else{
if(_3a18===toDir){
var pos=Math.min(_3a17.x,toPt.x)-_3a1d;
point=new Point(pos,_3a17.y);
}else{
point=new Point(_3a17.x-(xDiff/2),_3a17.y);
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
if(_3a18===RIGHT){
if((xDiff<0)&&((yDiff*yDiff)<TOL)&&(toDir==LEFT)){
point=toPt;
dir=toDir;
}else{
if(xDiff>0){
point=new Point(_3a17.x+_3a1d,_3a17.y);
}else{
if(((yDiff>0)&&(toDir===DOWN))||((yDiff<0)&&(toDir===UP))){
point=new Point(toPt.x,_3a17.y);
}else{
if(_3a18===toDir){
var pos=Math.max(_3a17.x,toPt.x)+_3a1d;
point=new Point(pos,_3a17.y);
}else{
point=new Point(_3a17.x-(xDiff/2),_3a17.y);
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
if(_3a18===DOWN){
if(((xDiff*xDiff)<TOL)&&(yDiff<0)&&(toDir===UP)){
point=toPt;
dir=toDir;
}else{
if(yDiff>0){
point=new Point(_3a17.x,_3a17.y+_3a1d);
}else{
if(((xDiff>0)&&(toDir===RIGHT))||((xDiff<0)&&(toDir===LEFT))){
point=new Point(_3a17.x,toPt.y);
}else{
if(_3a18===toDir){
var pos=Math.max(_3a17.y,toPt.y)+_3a1d;
point=new Point(_3a17.x,pos);
}else{
point=new Point(_3a17.x,_3a17.y-(yDiff/2));
}
}
}
if(xDiff>0){
dir=LEFT;
}else{
dir=RIGHT;
}
}
}else{
if(_3a18===UP){
if(((xDiff*xDiff)<TOL)&&(yDiff>0)&&(toDir===DOWN)){
point=toPt;
dir=toDir;
}else{
if(yDiff<0){
point=new Point(_3a17.x,_3a17.y-_3a1d);
}else{
if(((xDiff>0)&&(toDir===RIGHT))||((xDiff<0)&&(toDir===LEFT))){
point=new Point(_3a17.x,toPt.y);
}else{
if(_3a18===toDir){
var pos=Math.min(_3a17.y,toPt.y)-_3a1d;
point=new Point(_3a17.x,pos);
}else{
point=new Point(_3a17.x,_3a17.y-(yDiff/2));
}
}
}
if(xDiff>0){
dir=LEFT;
}else{
dir=RIGHT;
}
}
}
}
}
}
this._route(_3a15,conn,point,dir,toPt,toDir);
_3a15.push(_3a17);
};
FanConnectionRouter=function(){
};
FanConnectionRouter.prototype=new NullConnectionRouter();
FanConnectionRouter.prototype.type="FanConnectionRouter";
FanConnectionRouter.prototype.route=function(conn){
var _3846=conn.getStartPoint();
var toPt=conn.getEndPoint();
var lines=conn.getSource().getConnections();
var _3849=new ArrayList();
var index=0;
for(var i=0;i<lines.getSize();i++){
var _384c=lines.get(i);
if(_384c.getTarget()==conn.getTarget()||_384c.getSource()==conn.getTarget()){
_3849.add(_384c);
if(conn==_384c){
index=_3849.getSize();
}
}
}
if(_3849.getSize()>1){
this.routeCollision(conn,index);
}else{
NullConnectionRouter.prototype.route.call(this,conn);
}
};
FanConnectionRouter.prototype.routeNormal=function(conn){
conn.addPoint(conn.getStartPoint());
conn.addPoint(conn.getEndPoint());
};
FanConnectionRouter.prototype.routeCollision=function(conn,index){
var start=conn.getStartPoint();
var end=conn.getEndPoint();
conn.addPoint(start);
var _3852=10;
var _3853=new Point((end.x+start.x)/2,(end.y+start.y)/2);
var _3854=end.getPosition(start);
var ray;
if(_3854==PositionConstants.SOUTH||_3854==PositionConstants.EAST){
ray=new Point(end.x-start.x,end.y-start.y);
}else{
ray=new Point(start.x-end.x,start.y-end.y);
}
var _3856=Math.sqrt(ray.x*ray.x+ray.y*ray.y);
var _3857=_3852*ray.x/_3856;
var _3858=_3852*ray.y/_3856;
var _3859;
if(index%2===0){
_3859=new Point(_3853.x+(index/2)*(-1*_3858),_3853.y+(index/2)*_3857);
}else{
_3859=new Point(_3853.x+(index/2)*_3858,_3853.y+(index/2)*(-1*_3857));
}
conn.addPoint(_3859);
conn.addPoint(end);
};
Graphics=function(_3601,_3602,_3603){
this.jsGraphics=_3601;
this.xt=_3603.x;
this.yt=_3603.y;
this.radian=_3602*Math.PI/180;
this.sinRadian=Math.sin(this.radian);
this.cosRadian=Math.cos(this.radian);
};
Graphics.prototype.setStroke=function(x){
this.jsGraphics.setStroke(x);
};
Graphics.prototype.drawLine=function(x1,y1,x2,y2){
var _x1=this.xt+x1*this.cosRadian-y1*this.sinRadian;
var _y1=this.yt+x1*this.sinRadian+y1*this.cosRadian;
var _x2=this.xt+x2*this.cosRadian-y2*this.sinRadian;
var _y2=this.yt+x2*this.sinRadian+y2*this.cosRadian;
this.jsGraphics.drawLine(_x1,_y1,_x2,_y2);
};
Graphics.prototype.fillRect=function(x,y,w,h){
var x1=this.xt+x*this.cosRadian-y*this.sinRadian;
var y1=this.yt+x*this.sinRadian+y*this.cosRadian;
var x2=this.xt+(x+w)*this.cosRadian-y*this.sinRadian;
var y2=this.yt+(x+w)*this.sinRadian+y*this.cosRadian;
var x3=this.xt+(x+w)*this.cosRadian-(y+h)*this.sinRadian;
var y3=this.yt+(x+w)*this.sinRadian+(y+h)*this.cosRadian;
var x4=this.xt+x*this.cosRadian-(y+h)*this.sinRadian;
var y4=this.yt+x*this.sinRadian+(y+h)*this.cosRadian;
this.jsGraphics.fillPolygon([x1,x2,x3,x4],[y1,y2,y3,y4]);
};
Graphics.prototype.fillPolygon=function(_3619,_361a){
var rotX=[];
var rotY=[];
for(var i=0;i<_3619.length;i++){
rotX[i]=this.xt+_3619[i]*this.cosRadian-_361a[i]*this.sinRadian;
rotY[i]=this.yt+_3619[i]*this.sinRadian+_361a[i]*this.cosRadian;
}
this.jsGraphics.fillPolygon(rotX,rotY);
};
Graphics.prototype.setColor=function(color){
this.jsGraphics.setColor(color.getHTMLStyle());
};
Graphics.prototype.drawPolygon=function(_361f,_3620){
var rotX=[];
var rotY=[];
for(var i=0;i<_361f.length;i++){
rotX[i]=this.xt+_361f[i]*this.cosRadian-_3620[i]*this.sinRadian;
rotY[i]=this.yt+_361f[i]*this.sinRadian+_3620[i]*this.cosRadian;
}
this.jsGraphics.drawPolygon(rotX,rotY);
};
Connection=function(){
Line.call(this);
this.sourcePort=null;
this.targetPort=null;
this.canDrag=true;
this.sourceDecorator=null;
this.targetDecorator=null;
this.sourceAnchor=new ConnectionAnchor();
this.targetAnchor=new ConnectionAnchor();
this.router=Connection.defaultRouter;
this.lineSegments=new ArrayList();
this.children=new ArrayList();
this.setColor(new Color(0,0,115));
this.setLineWidth(1);
};
Connection.prototype=new Line();
Connection.prototype.type="Connection";
Connection.defaultRouter=new ManhattanConnectionRouter();
Connection.setDefaultRouter=function(_427a){
Connection.defaultRouter=_427a;
};
Connection.prototype.disconnect=function(){
if(this.sourcePort!==null){
this.sourcePort.detachMoveListener(this);
this.fireSourcePortRouteEvent();
}
if(this.targetPort!==null){
this.targetPort.detachMoveListener(this);
this.fireTargetPortRouteEvent();
}
};
Connection.prototype.reconnect=function(){
if(this.sourcePort!==null){
this.sourcePort.attachMoveListener(this);
this.fireSourcePortRouteEvent();
}
if(this.targetPort!==null){
this.targetPort.attachMoveListener(this);
this.fireTargetPortRouteEvent();
}
};
Connection.prototype.isResizeable=function(){
return this.getCanDrag();
};
Connection.prototype.setCanDrag=function(flag){
this.canDrag=flag;
};
Connection.prototype.getCanDrag=function(){
return this.canDrag;
};
Connection.prototype.addFigure=function(_427c,_427d){
var entry={};
entry.figure=_427c;
entry.locator=_427d;
this.children.add(entry);
if(this.graphics!==null){
this.paint();
}
var oThis=this;
var _4280=function(){
var _4281=arguments[0]||window.event;
_4281.returnValue=false;
oThis.getWorkflow().setCurrentSelection(oThis);
oThis.getWorkflow().showLineResizeHandles(oThis);
};
if(_427c.getHTMLElement().addEventListener){
_427c.getHTMLElement().addEventListener("mousedown",_4280,false);
}else{
if(_427c.getHTMLElement().attachEvent){
_427c.getHTMLElement().attachEvent("onmousedown",_4280);
}
}
};
Connection.prototype.setSourceDecorator=function(_4282){
this.sourceDecorator=_4282;
if(this.graphics!==null){
this.paint();
}
};
Connection.prototype.getSourceDecorator=function(){
return this.sourceDecorator;
};
Connection.prototype.setTargetDecorator=function(_4283){
this.targetDecorator=_4283;
if(this.graphics!==null){
this.paint();
}
};
Connection.prototype.getTargetDecorator=function(){
return this.targetDecorator;
};
Connection.prototype.setSourceAnchor=function(_4284){
this.sourceAnchor=_4284;
this.sourceAnchor.setOwner(this.sourcePort);
if(this.graphics!==null){
this.paint();
}
};
Connection.prototype.setTargetAnchor=function(_4285){
this.targetAnchor=_4285;
this.targetAnchor.setOwner(this.targetPort);
if(this.graphics!==null){
this.paint();
}
};
Connection.prototype.setRouter=function(_4286){
if(_4286!==null){
this.router=_4286;
}else{
this.router=new NullConnectionRouter();
}
if(this.graphics!==null){
this.paint();
}
};
Connection.prototype.getRouter=function(){
return this.router;
};
Connection.prototype.setWorkflow=function(_4287){
Line.prototype.setWorkflow.call(this,_4287);
for(var i=0;i<this.children.getSize();i++){
this.children.get(i).isAppended=false;
}
};
Connection.prototype.paint=function(){
if(this.html===null){
return;
}
try{
for(var i=0;i<this.children.getSize();i++){
var entry=this.children.get(i);
if(entry.isAppended==true){
this.html.removeChild(entry.figure.getHTMLElement());
}
entry.isAppended=false;
}
if(this.graphics===null){
this.graphics=new jsGraphics(this.html);
}else{
this.graphics.clear();
}
this.graphics.setStroke(this.stroke);
this.graphics.setColor(this.lineColor.getHTMLStyle());
this.startStroke();
this.router.route(this);
if(this.getSource().getParent().isMoving==false&&this.getTarget().getParent().isMoving==false){
if(this.targetDecorator!==null){
this.targetDecorator.paint(new Graphics(this.graphics,this.getEndAngle(),this.getEndPoint()));
}
if(this.sourceDecorator!==null){
this.sourceDecorator.paint(new Graphics(this.graphics,this.getStartAngle(),this.getStartPoint()));
}
}
this.finishStroke();
for(var i=0;i<this.children.getSize();i++){
var entry=this.children.get(i);
this.html.appendChild(entry.figure.getHTMLElement());
entry.isAppended=true;
entry.locator.relocate(entry.figure);
}
}
catch(e){
pushErrorStack(e,"Connection.prototype.paint=function()");
}
};
Connection.prototype.getStartPoint=function(){
if(this.isMoving==false){
return this.sourceAnchor.getLocation(this.targetAnchor.getReferencePoint());
}else{
return Line.prototype.getStartPoint.call(this);
}
};
Connection.prototype.getEndPoint=function(){
if(this.isMoving==false){
return this.targetAnchor.getLocation(this.sourceAnchor.getReferencePoint());
}else{
return Line.prototype.getEndPoint.call(this);
}
};
Connection.prototype.startStroke=function(){
this.oldPoint=null;
this.lineSegments=new ArrayList();
};
Connection.prototype.finishStroke=function(){
this.graphics.paint();
this.oldPoint=null;
};
Connection.prototype.getPoints=function(){
var _428b=new ArrayList();
var line=null;
for(var i=0;i<this.lineSegments.getSize();i++){
line=this.lineSegments.get(i);
_428b.add(line.start);
}
if(line!==null){
_428b.add(line.end);
}
return _428b;
};
Connection.prototype.addPoint=function(p){
p=new Point(parseInt(p.x),parseInt(p.y));
if(this.oldPoint!==null){
this.graphics.drawLine(this.oldPoint.x,this.oldPoint.y,p.x,p.y);
var line={};
line.start=this.oldPoint;
line.end=p;
this.lineSegments.add(line);
}
this.oldPoint={};
this.oldPoint.x=p.x;
this.oldPoint.y=p.y;
};
Connection.prototype.refreshSourcePort=function(){
var model=this.getModel().getSourceModel();
var _4291=this.getModel().getSourcePortName();
var _4292=this.getWorkflow().getDocument().getFigures();
var count=_4292.getSize();
for(var i=0;i<count;i++){
var _4295=_4292.get(i);
if(_4295.getModel()==model){
var port=_4295.getOutputPort(_4291);
this.setSource(port);
}
}
this.setRouter(this.getRouter());
};
Connection.prototype.refreshTargetPort=function(){
var model=this.getModel().getTargetModel();
var _4298=this.getModel().getTargetPortName();
var _4299=this.getWorkflow().getDocument().getFigures();
var count=_4299.getSize();
for(var i=0;i<count;i++){
var _429c=_4299.get(i);
if(_429c.getModel()==model){
var port=_429c.getInputPort(_4298);
this.setTarget(port);
}
}
this.setRouter(this.getRouter());
};
Connection.prototype.setSource=function(port){
if(this.sourcePort!==null){
this.sourcePort.detachMoveListener(this);
}
this.sourcePort=port;
if(this.sourcePort===null){
return;
}
this.sourceAnchor.setOwner(this.sourcePort);
this.fireSourcePortRouteEvent();
this.sourcePort.attachMoveListener(this);
this.setStartPoint(port.getAbsoluteX(),port.getAbsoluteY());
};
Connection.prototype.getSource=function(){
return this.sourcePort;
};
Connection.prototype.setTarget=function(port){
if(this.targetPort!==null){
this.targetPort.detachMoveListener(this);
}
this.targetPort=port;
if(this.targetPort===null){
return;
}
this.targetAnchor.setOwner(this.targetPort);
this.fireTargetPortRouteEvent();
this.targetPort.attachMoveListener(this);
this.setEndPoint(port.getAbsoluteX(),port.getAbsoluteY());
};
Connection.prototype.getTarget=function(){
return this.targetPort;
};
Connection.prototype.onOtherFigureMoved=function(_42a0){
if(_42a0==this.sourcePort){
this.setStartPoint(this.sourcePort.getAbsoluteX(),this.sourcePort.getAbsoluteY());
}else{
this.setEndPoint(this.targetPort.getAbsoluteX(),this.targetPort.getAbsoluteY());
}
};
Connection.prototype.containsPoint=function(px,py){
for(var i=0;i<this.lineSegments.getSize();i++){
var line=this.lineSegments.get(i);
if(Line.hit(this.corona,line.start.x,line.start.y,line.end.x,line.end.y,px,py)){
return true;
}
}
return false;
};
Connection.prototype.getStartAngle=function(){
var p1=this.lineSegments.get(0).start;
var p2=this.lineSegments.get(0).end;
if(this.router instanceof BezierConnectionRouter){
p2=this.lineSegments.get(5).end;
}
var _42a7=Math.sqrt((p1.x-p2.x)*(p1.x-p2.x)+(p1.y-p2.y)*(p1.y-p2.y));
var angle=-(180/Math.PI)*Math.asin((p1.y-p2.y)/_42a7);
if(angle<0){
if(p2.x<p1.x){
angle=Math.abs(angle)+180;
}else{
angle=360-Math.abs(angle);
}
}else{
if(p2.x<p1.x){
angle=180-angle;
}
}
return angle;
};
Connection.prototype.getEndAngle=function(){
if(this.lineSegments.getSize()===0){
return 90;
}
var p1=this.lineSegments.get(this.lineSegments.getSize()-1).end;
var p2=this.lineSegments.get(this.lineSegments.getSize()-1).start;
if(this.router instanceof BezierConnectionRouter){
p2=this.lineSegments.get(this.lineSegments.getSize()-5).end;
}
var _42ab=Math.sqrt((p1.x-p2.x)*(p1.x-p2.x)+(p1.y-p2.y)*(p1.y-p2.y));
var angle=-(180/Math.PI)*Math.asin((p1.y-p2.y)/_42ab);
if(angle<0){
if(p2.x<p1.x){
angle=Math.abs(angle)+180;
}else{
angle=360-Math.abs(angle);
}
}else{
if(p2.x<p1.x){
angle=180-angle;
}
}
return angle;
};
Connection.prototype.fireSourcePortRouteEvent=function(){
var _42ad=this.sourcePort.getConnections();
for(var i=0;i<_42ad.getSize();i++){
_42ad.get(i).paint();
}
};
Connection.prototype.fireTargetPortRouteEvent=function(){
var _42af=this.targetPort.getConnections();
for(var i=0;i<_42af.getSize();i++){
_42af.get(i).paint();
}
};
Connection.prototype.createCommand=function(_42b1){
if(_42b1.getPolicy()==EditPolicy.MOVE){
return new CommandReconnect(this);
}
if(_42b1.getPolicy()==EditPolicy.DELETE){
if(this.isDeleteable()==true){
return new CommandDelete(this);
}
return null;
}
return null;
};
ConnectionAnchor=function(owner){
this.owner=owner;
};
ConnectionAnchor.prototype.type="ConnectionAnchor";
ConnectionAnchor.prototype.getLocation=function(_3723){
return this.getReferencePoint();
};
ConnectionAnchor.prototype.getOwner=function(){
return this.owner;
};
ConnectionAnchor.prototype.setOwner=function(owner){
this.owner=owner;
};
ConnectionAnchor.prototype.getBox=function(){
return this.getOwner().getAbsoluteBounds();
};
ConnectionAnchor.prototype.getReferencePoint=function(){
if(this.getOwner()===null){
return null;
}else{
return this.getOwner().getAbsolutePosition();
}
};
ChopboxConnectionAnchor=function(owner){
ConnectionAnchor.call(this,owner);
};
ChopboxConnectionAnchor.prototype=new ConnectionAnchor();
ChopboxConnectionAnchor.prototype.type="ChopboxConnectionAnchor";
ChopboxConnectionAnchor.prototype.getLocation=function(_3830){
var r=new Dimension();
r.setBounds(this.getBox());
r.translate(-1,-1);
r.resize(1,1);
var _3832=r.x+r.w/2;
var _3833=r.y+r.h/2;
if(r.isEmpty()||(_3830.x==_3832&&_3830.y==_3833)){
return new Point(_3832,_3833);
}
var dx=_3830.x-_3832;
var dy=_3830.y-_3833;
var scale=0.5/Math.max(Math.abs(dx)/r.w,Math.abs(dy)/r.h);
dx*=scale;
dy*=scale;
_3832+=dx;
_3833+=dy;
return new Point(Math.round(_3832),Math.round(_3833));
};
ChopboxConnectionAnchor.prototype.getBox=function(){
return this.getOwner().getParent().getBounds();
};
ChopboxConnectionAnchor.prototype.getReferencePoint=function(){
return this.getBox().getCenter();
};
ConnectionDecorator=function(){
this.color=new Color(0,0,0);
this.backgroundColor=new Color(250,250,250);
};
ConnectionDecorator.prototype.type="ConnectionDecorator";
ConnectionDecorator.prototype.paint=function(g){
};
ConnectionDecorator.prototype.setColor=function(c){
this.color=c;
};
ConnectionDecorator.prototype.setBackgroundColor=function(c){
this.backgroundColor=c;
};
ArrowConnectionDecorator=function(_3e34,width){
ConnectionDecorator.call(this);
if(_3e34===undefined||_3e34<1){
this.lenght=15;
}
if(width===undefined||width<1){
this.width=10;
}
};
ArrowConnectionDecorator.prototype=new ConnectionDecorator();
ArrowConnectionDecorator.prototype.type="ArrowConnectionDecorator";
ArrowConnectionDecorator.prototype.paint=function(g){
if(this.backgroundColor!==null){
g.setColor(this.backgroundColor);
g.fillPolygon([3,this.lenght,this.lenght,3],[0,(this.width/2),-(this.width/2),0]);
}
g.setColor(this.color);
g.setStroke(1);
g.drawPolygon([3,this.lenght,this.lenght,3],[0,(this.width/2),-(this.width/2),0]);
};
ArrowConnectionDecorator.prototype.setDimension=function(l,width){
this.width=w;
this.lenght=l;
};
CompartmentFigure=function(){
Node.call(this);
this.children=new ArrayList();
this.setBorder(new LineBorder(1));
this.dropable=new DropTarget(this.html);
this.dropable.node=this;
this.dropable.addEventListener("figureenter",function(_3c1b){
_3c1b.target.node.onFigureEnter(_3c1b.relatedTarget.node);
});
this.dropable.addEventListener("figureleave",function(_3c1c){
_3c1c.target.node.onFigureLeave(_3c1c.relatedTarget.node);
});
this.dropable.addEventListener("figuredrop",function(_3c1d){
_3c1d.target.node.onFigureDrop(_3c1d.relatedTarget.node);
});
};
CompartmentFigure.prototype=new Node();
CompartmentFigure.prototype.type="CompartmentFigure";
CompartmentFigure.prototype.onFigureEnter=function(_3c1e){
};
CompartmentFigure.prototype.onFigureLeave=function(_3c1f){
};
CompartmentFigure.prototype.onFigureDrop=function(_3c20){
};
CompartmentFigure.prototype.getChildren=function(){
return this.children;
};
CompartmentFigure.prototype.addChild=function(_3c21){
_3c21.setZOrder(this.getZOrder()+1);
_3c21.setParent(this);
this.children.add(_3c21);
};
CompartmentFigure.prototype.removeChild=function(_3c22){
_3c22.setParent(null);
this.children.remove(_3c22);
};
CompartmentFigure.prototype.setZOrder=function(index){
Node.prototype.setZOrder.call(this,index);
for(var i=0;i<this.children.getSize();i++){
this.children.get(i).setZOrder(index+1);
}
};
CompartmentFigure.prototype.setPosition=function(xPos,yPos){
var oldX=this.getX();
var oldY=this.getY();
Node.prototype.setPosition.call(this,xPos,yPos);
for(var i=0;i<this.children.getSize();i++){
var child=this.children.get(i);
child.setPosition(child.getX()+this.getX()-oldX,child.getY()+this.getY()-oldY);
}
};
CompartmentFigure.prototype.onDrag=function(){
var oldX=this.getX();
var oldY=this.getY();
Node.prototype.onDrag.call(this);
for(var i=0;i<this.children.getSize();i++){
var child=this.children.get(i);
child.setPosition(child.getX()+this.getX()-oldX,child.getY()+this.getY()-oldY);
}
};
CanvasDocument=function(_3671){
this.canvas=_3671;
};
CanvasDocument.prototype.type="CanvasDocument";
CanvasDocument.prototype.getFigures=function(){
var _3672=new ArrayList();
var _3673=this.canvas.figures;
var _3674=this.canvas.dialogs;
for(var i=0;i<_3673.getSize();i++){
var _3676=_3673.get(i);
if(_3674.indexOf(_3676)==-1&&_3676.getParent()===null&&!(_3676 instanceof WindowFigure)){
_3672.add(_3676);
}
}
return _3672;
};
CanvasDocument.prototype.getFigure=function(id){
return this.canvas.getFigure(id);
};
CanvasDocument.prototype.getLines=function(){
return this.canvas.getLines();
};
CanvasDocument.prototype.getLine=function(id){
return this.canvas.getLine(id);
};
Annotation=function(msg){
this.msg=msg;
this.alpha=1;
this.color=new Color(0,0,0);
this.bgColor=new Color(241,241,121);
this.fontSize=10;
this.textNode=null;
Figure.call(this);
};
Annotation.prototype=new Figure();
Annotation.prototype.type="Annotation";
Annotation.prototype.createHTMLElement=function(){
var item=Figure.prototype.createHTMLElement.call(this);
item.style.color=this.color.getHTMLStyle();
item.style.backgroundColor=this.bgColor.getHTMLStyle();
item.style.fontSize=this.fontSize+"pt";
item.style.width="auto";
item.style.height="auto";
item.style.margin="0px";
item.style.padding="0px";
item.onselectstart=function(){
return false;
};
item.unselectable="on";
item.style.cursor="default";
this.textNode=document.createTextNode(this.msg);
item.appendChild(this.textNode);
this.disableTextSelection(item);
return item;
};
Annotation.prototype.onDoubleClick=function(){
var _43f2=new AnnotationDialog(this);
this.workflow.showDialog(_43f2);
};
Annotation.prototype.setBackgroundColor=function(color){
this.bgColor=color;
if(this.bgColor!==null){
this.html.style.backgroundColor=this.bgColor.getHTMLStyle();
}else{
this.html.style.backgroundColor="transparent";
}
};
Annotation.prototype.getBackgroundColor=function(){
return this.bgColor;
};
Annotation.prototype.setFontSize=function(size){
this.fontSize=size;
this.html.style.fontSize=this.fontSize+"pt";
};
Annotation.prototype.getText=function(){
return this.msg;
};
Annotation.prototype.setText=function(text){
this.msg=text;
this.html.removeChild(this.textNode);
this.textNode=document.createTextNode(this.msg);
this.html.appendChild(this.textNode);
};
Annotation.prototype.setStyledText=function(text){
this.msg=text;
this.html.removeChild(this.textNode);
this.textNode=document.createElement("div");
this.textNode.innerHTML=text;
this.html.appendChild(this.textNode);
};
ResizeHandle=function(_3d76,type){
Rectangle.call(this,5,5);
this.type=type;
var _3d78=this.getWidth();
var _3d79=_3d78/2;
switch(this.type){
case 1:
this.setSnapToGridAnchor(new Point(_3d78,_3d78));
break;
case 2:
this.setSnapToGridAnchor(new Point(_3d79,_3d78));
break;
case 3:
this.setSnapToGridAnchor(new Point(0,_3d78));
break;
case 4:
this.setSnapToGridAnchor(new Point(0,_3d79));
break;
case 5:
this.setSnapToGridAnchor(new Point(0,0));
break;
case 6:
this.setSnapToGridAnchor(new Point(_3d79,0));
break;
case 7:
this.setSnapToGridAnchor(new Point(_3d78,0));
break;
case 8:
this.setSnapToGridAnchor(new Point(_3d78,_3d79));
case 9:
this.setSnapToGridAnchor(new Point(_3d79,_3d79));
break;
}
this.setBackgroundColor(new Color(0,255,0));
this.setWorkflow(_3d76);
this.setZOrder(10000);
};
ResizeHandle.prototype=new Rectangle();
ResizeHandle.prototype.type="ResizeHandle";
ResizeHandle.prototype.getSnapToDirection=function(){
switch(this.type){
case 1:
return SnapToHelper.NORTH_WEST;
case 2:
return SnapToHelper.NORTH;
case 3:
return SnapToHelper.NORTH_EAST;
case 4:
return SnapToHelper.EAST;
case 5:
return SnapToHelper.SOUTH_EAST;
case 6:
return SnapToHelper.SOUTH;
case 7:
return SnapToHelper.SOUTH_WEST;
case 8:
return SnapToHelper.WEST;
case 9:
return SnapToHelper.CENTER;
}
};
ResizeHandle.prototype.onDragend=function(){
var _3d7a=this.workflow.currentSelection;
if(this.commandMove!==null){
this.commandMove.setPosition(_3d7a.getX(),_3d7a.getY());
this.workflow.getCommandStack().execute(this.commandMove);
this.commandMove=null;
}
if(this.commandResize!==null){
this.commandResize.setDimension(_3d7a.getWidth(),_3d7a.getHeight());
this.workflow.getCommandStack().execute(this.commandResize);
this.commandResize=null;
}
this.workflow.hideSnapToHelperLines();
};
ResizeHandle.prototype.setPosition=function(xPos,yPos){
this.x=xPos;
this.y=yPos;
if(this.html===null){
return;
}
this.html.style.left=this.x+"px";
this.html.style.top=this.y+"px";
};
ResizeHandle.prototype.onDragstart=function(x,y){
if(!this.canDrag){
return false;
}
var _3d7f=this.workflow.currentSelection;
this.commandMove=_3d7f.createCommand(new EditPolicy(EditPolicy.MOVE));
this.commandResize=_3d7f.createCommand(new EditPolicy(EditPolicy.RESIZE));
return true;
};
ResizeHandle.prototype.onDrag=function(){
var oldX=this.getX();
var oldY=this.getY();
Rectangle.prototype.onDrag.call(this);
var diffX=oldX-this.getX();
var diffY=oldY-this.getY();
var _3d84=this.workflow.currentSelection.getX();
var _3d85=this.workflow.currentSelection.getY();
var _3d86=this.workflow.currentSelection.getWidth();
var _3d87=this.workflow.currentSelection.getHeight();
switch(this.type){
case 1:
this.workflow.currentSelection.setPosition(_3d84-diffX,_3d85-diffY);
this.workflow.currentSelection.setDimension(_3d86+diffX,_3d87+diffY);
break;
case 2:
this.workflow.currentSelection.setPosition(_3d84,_3d85-diffY);
this.workflow.currentSelection.setDimension(_3d86,_3d87+diffY);
break;
case 3:
this.workflow.currentSelection.setPosition(_3d84,_3d85-diffY);
this.workflow.currentSelection.setDimension(_3d86-diffX,_3d87+diffY);
break;
case 4:
this.workflow.currentSelection.setPosition(_3d84,_3d85);
this.workflow.currentSelection.setDimension(_3d86-diffX,_3d87);
break;
case 5:
this.workflow.currentSelection.setPosition(_3d84,_3d85);
this.workflow.currentSelection.setDimension(_3d86-diffX,_3d87-diffY);
break;
case 6:
this.workflow.currentSelection.setPosition(_3d84,_3d85);
this.workflow.currentSelection.setDimension(_3d86,_3d87-diffY);
break;
case 7:
this.workflow.currentSelection.setPosition(_3d84-diffX,_3d85);
this.workflow.currentSelection.setDimension(_3d86+diffX,_3d87-diffY);
break;
case 8:
this.workflow.currentSelection.setPosition(_3d84-diffX,_3d85);
this.workflow.currentSelection.setDimension(_3d86+diffX,_3d87);
break;
}
this.workflow.moveResizeHandles(this.workflow.getCurrentSelection());
};
ResizeHandle.prototype.setCanDrag=function(flag){
Rectangle.prototype.setCanDrag.call(this,flag);
if(this.html===null){
return;
}
if(!flag){
this.html.style.cursor="";
return;
}
switch(this.type){
case 1:
this.html.style.cursor="nw-resize";
break;
case 2:
this.html.style.cursor="s-resize";
break;
case 3:
this.html.style.cursor="ne-resize";
break;
case 4:
this.html.style.cursor="w-resize";
break;
case 5:
this.html.style.cursor="se-resize";
break;
case 6:
this.html.style.cursor="n-resize";
break;
case 7:
this.html.style.cursor="sw-resize";
break;
case 8:
this.html.style.cursor="e-resize";
break;
case 9:
this.html.style.cursor="resize";
break;
}
};
ResizeHandle.prototype.onKeyDown=function(_3d89,ctrl){
this.workflow.onKeyDown(_3d89,ctrl);
};
ResizeHandle.prototype.fireMoveEvent=function(){
};
LineStartResizeHandle=function(_3659){
ResizeHandle.call(this,_3659,9);
this.setDimension(10,10);
this.setBackgroundColor(new Color(100,255,0));
this.setZOrder(10000);
};
LineStartResizeHandle.prototype=new ResizeHandle();
LineStartResizeHandle.prototype.type="LineStartResizeHandle";
LineStartResizeHandle.prototype.onDragend=function(){
if(this.workflow.currentSelection instanceof Connection){
if(this.command!==null){
this.command.cancel();
}
}else{
if(this.command!==null){
this.getWorkflow().getCommandStack().execute(this.command);
}
}
this.command=null;
};
LineStartResizeHandle.prototype.onDragstart=function(x,y){
if(!this.canDrag){
return false;
}
this.command=this.workflow.currentSelection.createCommand(new EditPolicy(EditPolicy.MOVE));
return this.command!==null;
};
LineStartResizeHandle.prototype.onDrag=function(){
var oldX=this.getX();
var oldY=this.getY();
Rectangle.prototype.onDrag.call(this);
var diffX=oldX-this.getX();
var diffY=oldY-this.getY();
var _3660=this.workflow.currentSelection.getStartPoint();
var line=this.workflow.currentSelection;
line.setStartPoint(_3660.x-diffX,_3660.y-diffY);
line.isMoving=true;
};
LineStartResizeHandle.prototype.onDrop=function(_3662){
var line=this.workflow.currentSelection;
line.isMoving=false;
if(line instanceof Connection){
this.command.setNewPorts(_3662,line.getTarget());
this.getWorkflow().getCommandStack().execute(this.command);
}
this.command=null;
};
LineEndResizeHandle=function(_4407){
ResizeHandle.call(this,_4407,9);
this.setDimension(10,10);
this.setBackgroundColor(new Color(0,255,0));
this.setZOrder(10000);
};
LineEndResizeHandle.prototype=new ResizeHandle();
LineEndResizeHandle.prototype.type="LineEndResizeHandle";
LineEndResizeHandle.prototype.onDragend=function(){
if(this.workflow.currentSelection instanceof Connection){
if(this.command!==null){
this.command.cancel();
}
}else{
if(this.command!==null){
this.workflow.getCommandStack().execute(this.command);
}
}
this.command=null;
};
LineEndResizeHandle.prototype.onDragstart=function(x,y){
if(!this.canDrag){
return false;
}
this.command=this.workflow.currentSelection.createCommand(new EditPolicy(EditPolicy.MOVE));
return this.command!==null;
};
LineEndResizeHandle.prototype.onDrag=function(){
var oldX=this.getX();
var oldY=this.getY();
Rectangle.prototype.onDrag.call(this);
var diffX=oldX-this.getX();
var diffY=oldY-this.getY();
var _440e=this.workflow.currentSelection.getEndPoint();
var line=this.workflow.currentSelection;
line.setEndPoint(_440e.x-diffX,_440e.y-diffY);
line.isMoving=true;
};
LineEndResizeHandle.prototype.onDrop=function(_4410){
var line=this.workflow.currentSelection;
line.isMoving=false;
if(line instanceof Connection){
this.command.setNewPorts(line.getSource(),_4410);
this.getWorkflow().getCommandStack().execute(this.command);
}
this.command=null;
};
Canvas=function(_4262){
try{
if(_4262){
this.construct(_4262);
}
this.enableSmoothFigureHandling=false;
this.canvasLines=new ArrayList();
}
catch(e){
pushErrorStack(e,"Canvas=function(/*:String*/id)");
}
};
Canvas.IMAGE_BASE_URL="";
Canvas.prototype.type="Canvas";
Canvas.prototype.construct=function(_4263){
this.canvasId=_4263;
this.html=document.getElementById(this.canvasId);
this.scrollArea=document.body.parentNode;
};
Canvas.prototype.setViewPort=function(divId){
this.scrollArea=document.getElementById(divId);
};
Canvas.prototype.addFigure=function(_4265,xPos,yPos,_4268){
try{
if(this.enableSmoothFigureHandling===true){
if(_4265.timer<=0){
_4265.setAlpha(0.001);
}
var _4269=_4265;
var _426a=function(){
if(_4269.alpha<1){
_4269.setAlpha(Math.min(1,_4269.alpha+0.05));
}else{
window.clearInterval(_4269.timer);
_4269.timer=-1;
}
};
if(_4269.timer>0){
window.clearInterval(_4269.timer);
}
_4269.timer=window.setInterval(_426a,30);
}
_4265.setCanvas(this);
if(xPos&&yPos){
_4265.setPosition(xPos,yPos);
}
if(_4265 instanceof Line){
this.canvasLines.add(_4265);
this.html.appendChild(_4265.getHTMLElement());
}else{
var obj=this.canvasLines.getFirstElement();
if(obj===null){
this.html.appendChild(_4265.getHTMLElement());
}else{
this.html.insertBefore(_4265.getHTMLElement(),obj.getHTMLElement());
}
}
if(!_4268){
_4265.paint();
}
}
catch(e){
pushErrorStack(e,"Canvas.prototype.addFigure= function( /*:Figure*/figure,/*:int*/ xPos,/*:int*/ yPos, /*:boolean*/ avoidPaint)");
}
};
Canvas.prototype.removeFigure=function(_426c){
if(this.enableSmoothFigureHandling===true){
var oThis=this;
var _426e=_426c;
var _426f=function(){
if(_426e.alpha>0){
_426e.setAlpha(Math.max(0,_426e.alpha-0.05));
}else{
window.clearInterval(_426e.timer);
_426e.timer=-1;
oThis.html.removeChild(_426e.html);
_426e.setCanvas(null);
}
};
if(_426e.timer>0){
window.clearInterval(_426e.timer);
}
_426e.timer=window.setInterval(_426f,20);
}else{
this.html.removeChild(_426c.html);
_426c.setCanvas(null);
}
if(_426c instanceof Line){
this.canvasLines.remove(_426c);
}
};
Canvas.prototype.getEnableSmoothFigureHandling=function(){
return this.enableSmoothFigureHandling;
};
Canvas.prototype.setEnableSmoothFigureHandling=function(flag){
this.enableSmoothFigureHandling=flag;
};
Canvas.prototype.getWidth=function(){
return parseInt(this.html.style.width);
};
Canvas.prototype.setWidth=function(width){
if(this.scrollArea!==null){
this.scrollArea.style.width=width+"px";
}else{
this.html.style.width=width+"px";
}
};
Canvas.prototype.getHeight=function(){
return parseInt(this.html.style.height);
};
Canvas.prototype.setHeight=function(_4272){
if(this.scrollArea!==null){
this.scrollArea.style.height=_4272+"px";
}else{
this.html.style.height=_4272+"px";
}
};
Canvas.prototype.setBackgroundImage=function(_4273,_4274){
if(_4273!==null){
if(_4274){
this.html.style.background="transparent url("+_4273+") ";
}else{
this.html.style.background="transparent url("+_4273+") no-repeat";
}
}else{
this.html.style.background="transparent";
}
};
Canvas.prototype.getY=function(){
return this.y;
};
Canvas.prototype.getX=function(){
return this.x;
};
Canvas.prototype.getAbsoluteY=function(){
var el=this.html;
var ot=el.offsetTop;
while((el=el.offsetParent)!==null){
ot+=el.offsetTop;
}
return ot;
};
Canvas.prototype.getAbsoluteX=function(){
var el=this.html;
var ol=el.offsetLeft;
while((el=el.offsetParent)!==null){
ol+=el.offsetLeft;
}
return ol;
};
Canvas.prototype.getScrollLeft=function(){
return this.scrollArea.scrollLeft;
};
Canvas.prototype.getScrollTop=function(){
return this.scrollArea.scrollTop;
};
Workflow=function(id){
try{
if(!id){
return;
}
this.menu=null;
this.gridWidthX=10;
this.gridWidthY=10;
this.snapToGridHelper=null;
this.verticalSnapToHelperLine=null;
this.horizontalSnapToHelperLine=null;
this.snapToGeometryHelper=null;
this.figures=new ArrayList();
this.lines=new ArrayList();
this.commonPorts=new ArrayList();
this.dropTargets=new ArrayList();
this.compartments=new ArrayList();
this.selectionListeners=new ArrayList();
this.dialogs=new ArrayList();
this.toolPalette=null;
this.dragging=false;
this.tooltip=null;
this.draggingLine=null;
this.draggingLineCommand=null;
this.commandStack=new CommandStack();
this.oldScrollPosLeft=0;
this.oldScrollPosTop=0;
this.currentSelection=null;
this.currentMenu=null;
this.connectionLine=new Line();
this.resizeHandleStart=new LineStartResizeHandle(this);
this.resizeHandleEnd=new LineEndResizeHandle(this);
this.resizeHandle1=new ResizeHandle(this,1);
this.resizeHandle2=new ResizeHandle(this,2);
this.resizeHandle3=new ResizeHandle(this,3);
this.resizeHandle4=new ResizeHandle(this,4);
this.resizeHandle5=new ResizeHandle(this,5);
this.resizeHandle6=new ResizeHandle(this,6);
this.resizeHandle7=new ResizeHandle(this,7);
this.resizeHandle8=new ResizeHandle(this,8);
this.resizeHandleHalfWidth=parseInt(this.resizeHandle2.getWidth()/2);
Canvas.call(this,id);
this.setPanning(false);
if(this.html!==null){
this.html.style.backgroundImage="url(grid_10.png)";
this.html.className="Workflow";
oThis=this;
this.html.tabIndex="0";
var _42f2=function(){
var _42f3=arguments[0]||window.event;
_42f3.cancelBubble=true;
_42f3.returnValue=false;
_42f3.stopped=true;
var diffX=_42f3.clientX;
var diffY=_42f3.clientY;
var _42f6=oThis.getScrollLeft();
var _42f7=oThis.getScrollTop();
var _42f8=oThis.getAbsoluteX();
var _42f9=oThis.getAbsoluteY();
var line=oThis.getBestLine(diffX+_42f6-_42f8,diffY+_42f7-_42f9,null);
if(line!==null){
line.onContextMenu(diffX+_42f6-_42f8,diffY+_42f7-_42f9);
}else{
oThis.onContextMenu(diffX+_42f6-_42f8,diffY+_42f7-_42f9);
}
};
this.html.oncontextmenu=function(){
return false;
};
var oThis=this;
var _42fc=function(event){
var ctrl=event.ctrlKey;
oThis.onKeyDown(event.keyCode,ctrl);
};
var _42ff=function(){
var _4300=arguments[0]||window.event;
if(_4300.returnValue==false){
return;
}
var diffX=_4300.clientX;
var diffY=_4300.clientY;
var _4303=oThis.getScrollLeft();
var _4304=oThis.getScrollTop();
var _4305=oThis.getAbsoluteX();
var _4306=oThis.getAbsoluteY();
oThis.onMouseDown(diffX+_4303-_4305,diffY+_4304-_4306);
};
var _4307=function(){
var _4308=arguments[0]||window.event;
if(oThis.currentMenu!==null){
oThis.removeFigure(oThis.currentMenu);
oThis.currentMenu=null;
}
if(_4308.button==2){
return;
}
var diffX=_4308.clientX;
var diffY=_4308.clientY;
var _430b=oThis.getScrollLeft();
var _430c=oThis.getScrollTop();
var _430d=oThis.getAbsoluteX();
var _430e=oThis.getAbsoluteY();
oThis.onMouseUp(diffX+_430b-_430d,diffY+_430c-_430e);
};
var _430f=function(){
var _4310=arguments[0]||window.event;
var diffX=_4310.clientX;
var diffY=_4310.clientY;
var _4313=oThis.getScrollLeft();
var _4314=oThis.getScrollTop();
var _4315=oThis.getAbsoluteX();
var _4316=oThis.getAbsoluteY();
oThis.currentMouseX=diffX+_4313-_4315;
oThis.currentMouseY=diffY+_4314-_4316;
var obj=oThis.getBestFigure(oThis.currentMouseX,oThis.currentMouseY);
if(Drag.currentHover!==null&&obj===null){
var _4318=new DragDropEvent();
_4318.initDragDropEvent("mouseleave",false,oThis);
Drag.currentHover.dispatchEvent(_4318);
}else{
var diffX=_4310.clientX;
var diffY=_4310.clientY;
var _4313=oThis.getScrollLeft();
var _4314=oThis.getScrollTop();
var _4315=oThis.getAbsoluteX();
var _4316=oThis.getAbsoluteY();
oThis.onMouseMove(diffX+_4313-_4315,diffY+_4314-_4316);
}
if(obj===null){
Drag.currentHover=null;
}
if(oThis.tooltip!==null){
if(Math.abs(oThis.currentTooltipX-oThis.currentMouseX)>10||Math.abs(oThis.currentTooltipY-oThis.currentMouseY)>10){
oThis.showTooltip(null);
}
}
};
var _4319=function(_431a){
var _431a=arguments[0]||window.event;
var diffX=_431a.clientX;
var diffY=_431a.clientY;
var _431d=oThis.getScrollLeft();
var _431e=oThis.getScrollTop();
var _431f=oThis.getAbsoluteX();
var _4320=oThis.getAbsoluteY();
var line=oThis.getBestLine(diffX+_431d-_431f,diffY+_431e-_4320,null);
if(line!==null){
line.onDoubleClick();
}
};
if(this.html.addEventListener){
this.html.addEventListener("contextmenu",_42f2,false);
this.html.addEventListener("mousemove",_430f,false);
this.html.addEventListener("mouseup",_4307,false);
this.html.addEventListener("mousedown",_42ff,false);
this.html.addEventListener("keydown",_42fc,false);
this.html.addEventListener("dblclick",_4319,false);
}else{
if(this.html.attachEvent){
this.html.attachEvent("oncontextmenu",_42f2);
this.html.attachEvent("onmousemove",_430f);
this.html.attachEvent("onmousedown",_42ff);
this.html.attachEvent("onmouseup",_4307);
this.html.attachEvent("onkeydown",_42fc);
this.html.attachEvent("ondblclick",_4319);
}else{
throw "Open-jACOB Draw2D not supported in this browser.";
}
}
}
}
catch(e){
pushErrorStack(e,"Workflow=function(/*:String*/id)");
}
};
Workflow.prototype=new Canvas();
Workflow.prototype.type="Workflow";
Workflow.COLOR_GREEN=new Color(0,255,0);
Workflow.prototype.clear=function(){
this.scrollTo(0,0,true);
this.gridWidthX=10;
this.gridWidthY=10;
this.snapToGridHelper=null;
this.verticalSnapToHelperLine=null;
this.horizontalSnapToHelperLine=null;
var _4322=this.getDocument();
var _4323=_4322.getLines().clone();
for(var i=0;i<_4323.getSize();i++){
(new CommandDelete(_4323.get(i))).execute();
}
var _4325=_4322.getFigures().clone();
for(var i=0;i<_4325.getSize();i++){
(new CommandDelete(_4325.get(i))).execute();
}
this.commonPorts.removeAllElements();
this.dropTargets.removeAllElements();
this.compartments.removeAllElements();
this.selectionListeners.removeAllElements();
this.dialogs.removeAllElements();
this.commandStack=new CommandStack();
this.currentSelection=null;
this.currentMenu=null;
Drag.clearCurrent();
};
Workflow.prototype.onScroll=function(){
var _4326=this.getScrollLeft();
var _4327=this.getScrollTop();
var _4328=_4326-this.oldScrollPosLeft;
var _4329=_4327-this.oldScrollPosTop;
for(var i=0;i<this.figures.getSize();i++){
var _432b=this.figures.get(i);
if(_432b.hasFixedPosition&&_432b.hasFixedPosition()==true){
_432b.setPosition(_432b.getX()+_4328,_432b.getY()+_4329);
}
}
this.oldScrollPosLeft=_4326;
this.oldScrollPosTop=_4327;
};
Workflow.prototype.setPanning=function(flag){
this.panning=flag;
if(flag){
this.html.style.cursor="move";
}else{
this.html.style.cursor="default";
}
};
Workflow.prototype.scrollTo=function(x,y,fast){
if(fast){
this.scrollArea.scrollLeft=x;
this.scrollArea.scrollTop=y;
}else{
var steps=40;
var xStep=(x-this.getScrollLeft())/steps;
var yStep=(y-this.getScrollTop())/steps;
var oldX=this.getScrollLeft();
var oldY=this.getScrollTop();
for(var i=0;i<steps;i++){
this.scrollArea.scrollLeft=oldX+(xStep*i);
this.scrollArea.scrollTop=oldY+(yStep*i);
}
}
};
Workflow.prototype.showTooltip=function(_4336,_4337){
if(this.tooltip!==null){
this.removeFigure(this.tooltip);
this.tooltip=null;
if(this.tooltipTimer>=0){
window.clearTimeout(this.tooltipTimer);
this.tooltipTimer=-1;
}
}
this.tooltip=_4336;
if(this.tooltip!==null){
this.currentTooltipX=this.currentMouseX;
this.currentTooltipY=this.currentMouseY;
this.addFigure(this.tooltip,this.currentTooltipX+10,this.currentTooltipY+10);
var oThis=this;
var _4339=function(){
oThis.tooltipTimer=-1;
oThis.showTooltip(null);
};
if(_4337==true){
this.tooltipTimer=window.setTimeout(_4339,5000);
}
}
};
Workflow.prototype.showDialog=function(_433a,xPos,yPos){
if(xPos){
this.addFigure(_433a,xPos,yPos);
}else{
this.addFigure(_433a,200,100);
}
this.dialogs.add(_433a);
};
Workflow.prototype.showMenu=function(menu,xPos,yPos){
if(this.menu!==null){
this.html.removeChild(this.menu.getHTMLElement());
this.menu.setWorkflow();
}
this.menu=menu;
if(this.menu!==null){
this.menu.setWorkflow(this);
this.menu.setPosition(xPos,yPos);
this.html.appendChild(this.menu.getHTMLElement());
this.menu.paint();
}
};
Workflow.prototype.onContextMenu=function(x,y){
var menu=this.getContextMenu();
if(menu!==null){
this.showMenu(menu,x,y);
}
};
Workflow.prototype.getContextMenu=function(){
return null;
};
Workflow.prototype.setToolWindow=function(_4343,x,y){
this.toolPalette=_4343;
if(y){
this.addFigure(_4343,x,y);
}else{
this.addFigure(_4343,20,20);
}
this.dialogs.add(_4343);
};
Workflow.prototype.setSnapToGrid=function(flag){
if(flag){
this.snapToGridHelper=new SnapToGrid(this);
}else{
this.snapToGridHelper=null;
}
};
Workflow.prototype.setSnapToGeometry=function(flag){
if(flag){
this.snapToGeometryHelper=new SnapToGeometry(this);
}else{
this.snapToGeometryHelper=null;
}
};
Workflow.prototype.setGridWidth=function(dx,dy){
this.gridWidthX=dx;
this.gridWidthY=dy;
};
Workflow.prototype.addFigure=function(_434a,xPos,yPos){
try{
Canvas.prototype.addFigure.call(this,_434a,xPos,yPos,true);
_434a.setWorkflow(this);
var _434d=this;
if(_434a instanceof CompartmentFigure){
this.compartments.add(_434a);
}
if(_434a instanceof Line){
this.lines.add(_434a);
}else{
this.figures.add(_434a);
_434a.draggable.addEventListener("drag",function(_434e){
var _434f=_434d.getFigure(_434e.target.element.id);
if(_434f===null){
return;
}
if(_434f.isSelectable()==false){
return;
}
_434d.moveResizeHandles(_434f);
});
}
_434a.paint();
this.setDocumentDirty();
}
catch(e){
pushErrorStack(e,"Workflow.prototype.addFigure=function(/*:Figure*/ figure ,/*:int*/ xPos, /*:int*/ yPos)");
}
};
Workflow.prototype.removeFigure=function(_4350){
Canvas.prototype.removeFigure.call(this,_4350);
this.figures.remove(_4350);
this.lines.remove(_4350);
this.dialogs.remove(_4350);
_4350.setWorkflow(null);
if(_4350 instanceof CompartmentFigure){
this.compartments.remove(_4350);
}
if(_4350 instanceof Connection){
_4350.disconnect();
}
if(this.currentSelection==_4350){
this.setCurrentSelection(null);
}
this.setDocumentDirty();
_4350.onRemove(this);
};
Workflow.prototype.moveFront=function(_4351){
this.html.removeChild(_4351.getHTMLElement());
this.html.appendChild(_4351.getHTMLElement());
};
Workflow.prototype.moveBack=function(_4352){
this.html.removeChild(_4352.getHTMLElement());
this.html.insertBefore(_4352.getHTMLElement(),this.html.firstChild);
};
Workflow.prototype.getBestCompartmentFigure=function(x,y,_4355){
var _4356=null;
for(var i=0;i<this.figures.getSize();i++){
var _4358=this.figures.get(i);
if((_4358 instanceof CompartmentFigure)&&_4358.isOver(x,y)==true&&_4358!=_4355){
if(_4356===null){
_4356=_4358;
}else{
if(_4356.getZOrder()<_4358.getZOrder()){
_4356=_4358;
}
}
}
}
return _4356;
};
Workflow.prototype.getBestFigure=function(x,y,_435b){
var _435c=null;
for(var i=0;i<this.figures.getSize();i++){
var _435e=this.figures.get(i);
if(_435e.isOver(x,y)==true&&_435e!=_435b){
if(_435c===null){
_435c=_435e;
}else{
if(_435c.getZOrder()<_435e.getZOrder()){
_435c=_435e;
}
}
}
}
return _435c;
};
Workflow.prototype.getBestLine=function(x,y,_4361){
var _4362=null;
var count=this.lines.getSize();
for(var i=0;i<count;i++){
var line=this.lines.get(i);
if(line.containsPoint(x,y)==true&&line!=_4361){
if(_4362===null){
_4362=line;
}else{
if(_4362.getZOrder()<line.getZOrder()){
_4362=line;
}
}
}
}
return _4362;
};
Workflow.prototype.getFigure=function(id){
for(var i=0;i<this.figures.getSize();i++){
var _4368=this.figures.get(i);
if(_4368.id==id){
return _4368;
}
}
return null;
};
Workflow.prototype.getFigures=function(){
return this.figures;
};
Workflow.prototype.getDocument=function(){
return new CanvasDocument(this);
};
Workflow.prototype.addSelectionListener=function(w){
if(w!==null){
if(w.onSelectionChanged){
this.selectionListeners.add(w);
}else{
throw "Object doesn't implement required callback method [onSelectionChanged]";
}
}
};
Workflow.prototype.removeSelectionListener=function(w){
this.selectionListeners.remove(w);
};
Workflow.prototype.setCurrentSelection=function(_436b){
if(_436b===null||this.currentSelection!=_436b){
this.hideResizeHandles();
this.hideLineResizeHandles();
}
this.currentSelection=_436b;
for(var i=0;i<this.selectionListeners.getSize();i++){
var w=this.selectionListeners.get(i);
if(w.onSelectionChanged){
w.onSelectionChanged(this.currentSelection,this.currentSelection?this.currentSelection.getModel():null);
}
}
if(_436b instanceof Line){
this.showLineResizeHandles(_436b);
if(!(_436b instanceof Connection)){
this.draggingLineCommand=line.createCommand(new EditPolicy(EditPolicy.MOVE));
if(this.draggingLineCommand!==null){
this.draggingLine=_436b;
}
}
}
};
Workflow.prototype.getCurrentSelection=function(){
return this.currentSelection;
};
Workflow.prototype.getLine=function(id){
var count=this.lines.getSize();
for(var i=0;i<count;i++){
var line=this.lines.get(i);
if(line.getId()==id){
return line;
}
}
return null;
};
Workflow.prototype.getLines=function(){
return this.lines;
};
Workflow.prototype.registerPort=function(port){
port.draggable.targets=this.dropTargets;
this.commonPorts.add(port);
this.dropTargets.add(port.dropable);
};
Workflow.prototype.unregisterPort=function(port){
port.draggable.targets=null;
this.commonPorts.remove(port);
this.dropTargets.remove(port.dropable);
};
Workflow.prototype.getCommandStack=function(){
return this.commandStack;
};
Workflow.prototype.showConnectionLine=function(x1,y1,x2,y2){
this.connectionLine.setStartPoint(x1,y1);
this.connectionLine.setEndPoint(x2,y2);
if(this.connectionLine.canvas===null){
Canvas.prototype.addFigure.call(this,this.connectionLine);
}
};
Workflow.prototype.hideConnectionLine=function(){
if(this.connectionLine.canvas!==null){
Canvas.prototype.removeFigure.call(this,this.connectionLine);
}
};
Workflow.prototype.showLineResizeHandles=function(_4378){
var _4379=this.resizeHandleStart.getWidth()/2;
var _437a=this.resizeHandleStart.getHeight()/2;
var _437b=_4378.getStartPoint();
var _437c=_4378.getEndPoint();
Canvas.prototype.addFigure.call(this,this.resizeHandleStart,_437b.x-_4379,_437b.y-_4379);
Canvas.prototype.addFigure.call(this,this.resizeHandleEnd,_437c.x-_4379,_437c.y-_4379);
this.resizeHandleStart.setCanDrag(_4378.isResizeable());
this.resizeHandleEnd.setCanDrag(_4378.isResizeable());
if(_4378.isResizeable()){
this.resizeHandleStart.setBackgroundColor(Workflow.COLOR_GREEN);
this.resizeHandleEnd.setBackgroundColor(Workflow.COLOR_GREEN);
this.resizeHandleStart.draggable.targets=this.dropTargets;
this.resizeHandleEnd.draggable.targets=this.dropTargets;
}else{
this.resizeHandleStart.setBackgroundColor(null);
this.resizeHandleEnd.setBackgroundColor(null);
}
};
Workflow.prototype.hideLineResizeHandles=function(){
if(this.resizeHandleStart.canvas!==null){
Canvas.prototype.removeFigure.call(this,this.resizeHandleStart);
}
if(this.resizeHandleEnd.canvas!==null){
Canvas.prototype.removeFigure.call(this,this.resizeHandleEnd);
}
};
Workflow.prototype.showResizeHandles=function(_437d){
this.hideLineResizeHandles();
this.hideResizeHandles();
if(this.getEnableSmoothFigureHandling()==true&&this.getCurrentSelection()!=_437d){
this.resizeHandle1.setAlpha(0.01);
this.resizeHandle2.setAlpha(0.01);
this.resizeHandle3.setAlpha(0.01);
this.resizeHandle4.setAlpha(0.01);
this.resizeHandle5.setAlpha(0.01);
this.resizeHandle6.setAlpha(0.01);
this.resizeHandle7.setAlpha(0.01);
this.resizeHandle8.setAlpha(0.01);
}
var _437e=this.resizeHandle1.getWidth();
var _437f=this.resizeHandle1.getHeight();
var _4380=_437d.getHeight();
var _4381=_437d.getWidth();
var xPos=_437d.getX();
var yPos=_437d.getY();
Canvas.prototype.addFigure.call(this,this.resizeHandle1,xPos-_437e,yPos-_437f);
Canvas.prototype.addFigure.call(this,this.resizeHandle3,xPos+_4381,yPos-_437f);
Canvas.prototype.addFigure.call(this,this.resizeHandle5,xPos+_4381,yPos+_4380);
Canvas.prototype.addFigure.call(this,this.resizeHandle7,xPos-_437e,yPos+_4380);
this.moveFront(this.resizeHandle1);
this.moveFront(this.resizeHandle3);
this.moveFront(this.resizeHandle5);
this.moveFront(this.resizeHandle7);
this.resizeHandle1.setCanDrag(_437d.isResizeable());
this.resizeHandle3.setCanDrag(_437d.isResizeable());
this.resizeHandle5.setCanDrag(_437d.isResizeable());
this.resizeHandle7.setCanDrag(_437d.isResizeable());
if(_437d.isResizeable()){
var green=new Color(0,255,0);
this.resizeHandle1.setBackgroundColor(green);
this.resizeHandle3.setBackgroundColor(green);
this.resizeHandle5.setBackgroundColor(green);
this.resizeHandle7.setBackgroundColor(green);
}else{
this.resizeHandle1.setBackgroundColor(null);
this.resizeHandle3.setBackgroundColor(null);
this.resizeHandle5.setBackgroundColor(null);
this.resizeHandle7.setBackgroundColor(null);
}
if(_437d.isStrechable()&&_437d.isResizeable()){
this.resizeHandle2.setCanDrag(_437d.isResizeable());
this.resizeHandle4.setCanDrag(_437d.isResizeable());
this.resizeHandle6.setCanDrag(_437d.isResizeable());
this.resizeHandle8.setCanDrag(_437d.isResizeable());
Canvas.prototype.addFigure.call(this,this.resizeHandle2,xPos+(_4381/2)-this.resizeHandleHalfWidth,yPos-_437f);
Canvas.prototype.addFigure.call(this,this.resizeHandle4,xPos+_4381,yPos+(_4380/2)-(_437f/2));
Canvas.prototype.addFigure.call(this,this.resizeHandle6,xPos+(_4381/2)-this.resizeHandleHalfWidth,yPos+_4380);
Canvas.prototype.addFigure.call(this,this.resizeHandle8,xPos-_437e,yPos+(_4380/2)-(_437f/2));
this.moveFront(this.resizeHandle2);
this.moveFront(this.resizeHandle4);
this.moveFront(this.resizeHandle6);
this.moveFront(this.resizeHandle8);
}
};
Workflow.prototype.hideResizeHandles=function(){
if(this.resizeHandle1.canvas!==null){
Canvas.prototype.removeFigure.call(this,this.resizeHandle1);
}
if(this.resizeHandle2.canvas!==null){
Canvas.prototype.removeFigure.call(this,this.resizeHandle2);
}
if(this.resizeHandle3.canvas!==null){
Canvas.prototype.removeFigure.call(this,this.resizeHandle3);
}
if(this.resizeHandle4.canvas!==null){
Canvas.prototype.removeFigure.call(this,this.resizeHandle4);
}
if(this.resizeHandle5.canvas!==null){
Canvas.prototype.removeFigure.call(this,this.resizeHandle5);
}
if(this.resizeHandle6.canvas!==null){
Canvas.prototype.removeFigure.call(this,this.resizeHandle6);
}
if(this.resizeHandle7.canvas!==null){
Canvas.prototype.removeFigure.call(this,this.resizeHandle7);
}
if(this.resizeHandle8.canvas!==null){
Canvas.prototype.removeFigure.call(this,this.resizeHandle8);
}
};
Workflow.prototype.moveResizeHandles=function(_4385){
var _4386=this.resizeHandle1.getWidth();
var _4387=this.resizeHandle1.getHeight();
var _4388=_4385.getHeight();
var _4389=_4385.getWidth();
var xPos=_4385.getX();
var yPos=_4385.getY();
this.resizeHandle1.setPosition(xPos-_4386,yPos-_4387);
this.resizeHandle3.setPosition(xPos+_4389,yPos-_4387);
this.resizeHandle5.setPosition(xPos+_4389,yPos+_4388);
this.resizeHandle7.setPosition(xPos-_4386,yPos+_4388);
if(_4385.isStrechable()){
this.resizeHandle2.setPosition(xPos+(_4389/2)-this.resizeHandleHalfWidth,yPos-_4387);
this.resizeHandle4.setPosition(xPos+_4389,yPos+(_4388/2)-(_4387/2));
this.resizeHandle6.setPosition(xPos+(_4389/2)-this.resizeHandleHalfWidth,yPos+_4388);
this.resizeHandle8.setPosition(xPos-_4386,yPos+(_4388/2)-(_4387/2));
}
};
Workflow.prototype.onMouseDown=function(x,y){
this.dragging=true;
this.mouseDownPosX=x;
this.mouseDownPosY=y;
if(this.toolPalette!==null&&this.toolPalette.getActiveTool()!==null){
this.toolPalette.getActiveTool().execute(x,y);
}
this.showMenu(null);
var line=this.getBestLine(x,y);
if(line!==null&&line.isSelectable()){
this.setCurrentSelection(line);
}else{
this.setCurrentSelection(null);
}
};
Workflow.prototype.onMouseUp=function(x,y){
this.dragging=false;
if(this.draggingLineCommand!==null){
this.getCommandStack().execute(this.draggingLineCommand);
this.draggingLine=null;
this.draggingLineCommand=null;
}
};
Workflow.prototype.onMouseMove=function(x,y){
if(this.dragging===true&&this.draggingLine!==null){
var diffX=x-this.mouseDownPosX;
var diffY=y-this.mouseDownPosY;
this.draggingLine.startX=this.draggingLine.getStartX()+diffX;
this.draggingLine.startY=this.draggingLine.getStartY()+diffY;
this.draggingLine.setEndPoint(this.draggingLine.getEndX()+diffX,this.draggingLine.getEndY()+diffY);
this.mouseDownPosX=x;
this.mouseDownPosY=y;
this.showLineResizeHandles(this.currentSelection);
}else{
if(this.dragging===true&&this.panning===true){
var diffX=x-this.mouseDownPosX;
var diffY=y-this.mouseDownPosY;
this.scrollTo(this.getScrollLeft()-diffX,this.getScrollTop()-diffY,true);
this.onScroll();
}
}
};
Workflow.prototype.onKeyDown=function(_4395,ctrl){
if(_4395==46&&this.currentSelection!==null){
this.commandStack.execute(this.currentSelection.createCommand(new EditPolicy(EditPolicy.DELETE)));
}else{
if(_4395==90&&ctrl){
this.commandStack.undo();
}else{
if(_4395==89&&ctrl){
this.commandStack.redo();
}
}
}
};
Workflow.prototype.setDocumentDirty=function(){
try{
for(var i=0;i<this.dialogs.getSize();i++){
var d=this.dialogs.get(i);
if(d!==null&&d.onSetDocumentDirty){
d.onSetDocumentDirty();
}
}
if(this.snapToGeometryHelper!==null){
this.snapToGeometryHelper.onSetDocumentDirty();
}
if(this.snapToGridHelper!==null){
this.snapToGridHelper.onSetDocumentDirty();
}
}
catch(e){
pushErrorStack(e,"Workflow.prototype.setDocumentDirty=function()");
}
};
Workflow.prototype.snapToHelper=function(_4399,pos){
if(this.snapToGeometryHelper!==null){
if(_4399 instanceof ResizeHandle){
var _439b=_4399.getSnapToGridAnchor();
pos.x+=_439b.x;
pos.y+=_439b.y;
var _439c=new Point(pos.x,pos.y);
var _439d=_4399.getSnapToDirection();
var _439e=this.snapToGeometryHelper.snapPoint(_439d,pos,_439c);
if((_439d&SnapToHelper.EAST_WEST)&&!(_439e&SnapToHelper.EAST_WEST)){
this.showSnapToHelperLineVertical(_439c.x);
}else{
this.hideSnapToHelperLineVertical();
}
if((_439d&SnapToHelper.NORTH_SOUTH)&&!(_439e&SnapToHelper.NORTH_SOUTH)){
this.showSnapToHelperLineHorizontal(_439c.y);
}else{
this.hideSnapToHelperLineHorizontal();
}
_439c.x-=_439b.x;
_439c.y-=_439b.y;
return _439c;
}else{
var _439f=new Dimension(pos.x,pos.y,_4399.getWidth(),_4399.getHeight());
var _439c=new Dimension(pos.x,pos.y,_4399.getWidth(),_4399.getHeight());
var _439d=SnapToHelper.NSEW;
var _439e=this.snapToGeometryHelper.snapRectangle(_439f,_439c);
if((_439d&SnapToHelper.WEST)&&!(_439e&SnapToHelper.WEST)){
this.showSnapToHelperLineVertical(_439c.x);
}else{
if((_439d&SnapToHelper.EAST)&&!(_439e&SnapToHelper.EAST)){
this.showSnapToHelperLineVertical(_439c.getX()+_439c.getWidth());
}else{
this.hideSnapToHelperLineVertical();
}
}
if((_439d&SnapToHelper.NORTH)&&!(_439e&SnapToHelper.NORTH)){
this.showSnapToHelperLineHorizontal(_439c.y);
}else{
if((_439d&SnapToHelper.SOUTH)&&!(_439e&SnapToHelper.SOUTH)){
this.showSnapToHelperLineHorizontal(_439c.getY()+_439c.getHeight());
}else{
this.hideSnapToHelperLineHorizontal();
}
}
return _439c.getTopLeft();
}
}else{
if(this.snapToGridHelper!==null){
var _439b=_4399.getSnapToGridAnchor();
pos.x=pos.x+_439b.x;
pos.y=pos.y+_439b.y;
var _439c=new Point(pos.x,pos.y);
this.snapToGridHelper.snapPoint(0,pos,_439c);
_439c.x=_439c.x-_439b.x;
_439c.y=_439c.y-_439b.y;
return _439c;
}
}
return pos;
};
Workflow.prototype.showSnapToHelperLineHorizontal=function(_43a0){
if(this.horizontalSnapToHelperLine===null){
this.horizontalSnapToHelperLine=new Line();
this.horizontalSnapToHelperLine.setColor(new Color(175,175,255));
this.addFigure(this.horizontalSnapToHelperLine);
}
this.horizontalSnapToHelperLine.setStartPoint(0,_43a0);
this.horizontalSnapToHelperLine.setEndPoint(this.getWidth(),_43a0);
};
Workflow.prototype.showSnapToHelperLineVertical=function(_43a1){
if(this.verticalSnapToHelperLine===null){
this.verticalSnapToHelperLine=new Line();
this.verticalSnapToHelperLine.setColor(new Color(175,175,255));
this.addFigure(this.verticalSnapToHelperLine);
}
this.verticalSnapToHelperLine.setStartPoint(_43a1,0);
this.verticalSnapToHelperLine.setEndPoint(_43a1,this.getHeight());
};
Workflow.prototype.hideSnapToHelperLines=function(){
this.hideSnapToHelperLineHorizontal();
this.hideSnapToHelperLineVertical();
};
Workflow.prototype.hideSnapToHelperLineHorizontal=function(){
if(this.horizontalSnapToHelperLine!==null){
this.removeFigure(this.horizontalSnapToHelperLine);
this.horizontalSnapToHelperLine=null;
}
};
Workflow.prototype.hideSnapToHelperLineVertical=function(){
if(this.verticalSnapToHelperLine!==null){
this.removeFigure(this.verticalSnapToHelperLine);
this.verticalSnapToHelperLine=null;
}
};
WindowFigure=function(title){
this.title=title;
this.titlebar=null;
Figure.call(this);
this.setDeleteable(false);
this.setCanSnapToHelper(false);
this.setZOrder(WindowFigure.ZOrderIndex);
};
WindowFigure.prototype=new Figure();
WindowFigure.prototype.type=":WindowFigure";
WindowFigure.ZOrderIndex=50000;
WindowFigure.setZOrderBaseIndex=function(index){
WindowFigure.ZOrderBaseIndex=index;
};
WindowFigure.prototype.hasFixedPosition=function(){
return true;
};
WindowFigure.prototype.hasTitleBar=function(){
return true;
};
WindowFigure.prototype.createHTMLElement=function(){
var item=Figure.prototype.createHTMLElement.call(this);
item.style.margin="0px";
item.style.padding="0px";
item.style.border="1px solid black";
item.style.backgroundImage="url(window_bg.png)";
item.style.zIndex=WindowFigure.ZOrderIndex;
item.style.cursor=null;
item.className="WindowFigure";
if(this.hasTitleBar()){
this.titlebar=document.createElement("div");
this.titlebar.style.position="absolute";
this.titlebar.style.left="0px";
this.titlebar.style.top="0px";
this.titlebar.style.width=this.getWidth()+"px";
this.titlebar.style.height="15px";
this.titlebar.style.margin="0px";
this.titlebar.style.padding="0px";
this.titlebar.style.font="normal 10px verdana";
this.titlebar.style.backgroundColor="blue";
this.titlebar.style.borderBottom="2px solid gray";
this.titlebar.style.whiteSpace="nowrap";
this.titlebar.style.textAlign="center";
this.titlebar.style.backgroundImage="url(window_toolbar.png)";
this.titlebar.className="WindowFigure_titlebar";
this.textNode=document.createTextNode(this.title);
this.titlebar.appendChild(this.textNode);
this.disableTextSelection(this.titlebar);
item.appendChild(this.titlebar);
}
return item;
};
WindowFigure.prototype.setDocumentDirty=function(_4415){
};
WindowFigure.prototype.onDragend=function(){
};
WindowFigure.prototype.onDragstart=function(x,y){
if(this.titlebar===null){
return false;
}
if(this.canDrag===true&&x<parseInt(this.titlebar.style.width)&&y<parseInt(this.titlebar.style.height)){
return true;
}
return false;
};
WindowFigure.prototype.isSelectable=function(){
return false;
};
WindowFigure.prototype.setCanDrag=function(flag){
Figure.prototype.setCanDrag.call(this,flag);
this.html.style.cursor="";
if(this.titlebar===null){
return;
}
if(flag){
this.titlebar.style.cursor="move";
}else{
this.titlebar.style.cursor="";
}
};
WindowFigure.prototype.setWorkflow=function(_4419){
var _441a=this.workflow;
Figure.prototype.setWorkflow.call(this,_4419);
if(_441a!==null){
_441a.removeSelectionListener(this);
}
if(this.workflow!==null){
this.workflow.addSelectionListener(this);
}
};
WindowFigure.prototype.setDimension=function(w,h){
Figure.prototype.setDimension.call(this,w,h);
if(this.titlebar!==null){
this.titlebar.style.width=this.getWidth()+"px";
}
};
WindowFigure.prototype.setTitle=function(title){
this.title=title;
};
WindowFigure.prototype.getMinWidth=function(){
return 50;
};
WindowFigure.prototype.getMinHeight=function(){
return 50;
};
WindowFigure.prototype.isResizeable=function(){
return false;
};
WindowFigure.prototype.setAlpha=function(_441e){
};
WindowFigure.prototype.setBackgroundColor=function(color){
this.bgColor=color;
if(this.bgColor!==null){
this.html.style.backgroundColor=this.bgColor.getHTMLStyle();
}else{
this.html.style.backgroundColor="transparent";
this.html.style.backgroundImage="";
}
};
WindowFigure.prototype.setColor=function(color){
this.lineColor=color;
if(this.lineColor!==null){
this.html.style.border=this.lineStroke+"px solid "+this.lineColor.getHTMLStyle();
}else{
this.html.style.border="0px";
}
};
WindowFigure.prototype.setLineWidth=function(w){
this.lineStroke=w;
this.html.style.border=this.lineStroke+"px solid black";
};
WindowFigure.prototype.onSelectionChanged=function(_4422,model){
};
Button=function(_47ac,width,_47ae){
this.x=0;
this.y=0;
this.width=24;
this.height=24;
this.id=UUID.create();
this.enabled=true;
this.active=false;
this.palette=_47ac;
this.html=this.createHTMLElement();
if(width!==undefined&&_47ae!==undefined){
this.setDimension(width,_47ae);
}else{
this.setDimension(24,24);
}
};
Button.prototype.type="Button";
Button.prototype.dispose=function(){
};
Button.prototype.getImageUrl=function(){
return this.type+".png";
};
Button.prototype.createHTMLElement=function(){
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
if(this.getImageUrl()!==null){
item.style.backgroundImage="url("+this.getImageUrl()+")";
}else{
item.style.backgroundImage="";
}
var oThis=this;
this.omousedown=function(event){
if(oThis.enabled){
oThis.setActive(true);
}
event.cancelBubble=true;
event.returnValue=false;
};
this.omouseup=function(event){
if(oThis.enabled){
oThis.setActive(false);
oThis.execute();
oThis.palette.setActiveTool(null);
}
event.cancelBubble=true;
event.returnValue=false;
};
if(item.addEventListener){
item.addEventListener("mousedown",this.omousedown,false);
item.addEventListener("mouseup",this.omouseup,false);
}else{
if(item.attachEvent){
item.attachEvent("onmousedown",this.omousedown);
item.attachEvent("onmouseup",this.omouseup);
}
}
return item;
};
Button.prototype.getHTMLElement=function(){
if(this.html===null){
this.html=this.createHTMLElement();
}
return this.html;
};
Button.prototype.execute=function(){
};
Button.prototype.setTooltip=function(_47b3){
this.tooltip=_47b3;
if(this.tooltip!==null){
this.html.title=this.tooltip;
}else{
this.html.title="";
}
};
Button.prototype.getWorkflow=function(){
return this.getToolPalette().getWorkflow();
};
Button.prototype.getToolPalette=function(){
return this.palette;
};
Button.prototype.setActive=function(flag){
if(!this.enabled){
return;
}
this.active=flag;
if(flag===true){
this.html.style.border="1px inset";
}else{
this.html.style.border="0px";
}
};
Button.prototype.isActive=function(){
return this.active;
};
Button.prototype.setEnabled=function(flag){
this.enabled=flag;
if(flag){
this.html.style.filter="alpha(opacity=100)";
this.html.style.opacity="1.0";
}else{
this.html.style.filter="alpha(opacity=30)";
this.html.style.opacity="0.3";
}
};
Button.prototype.setDimension=function(w,h){
this.width=w;
this.height=h;
if(this.html===null){
return;
}
this.html.style.width=this.width+"px";
this.html.style.height=this.height+"px";
};
Button.prototype.setPosition=function(xPos,yPos){
this.x=Math.max(0,xPos);
this.y=Math.max(0,yPos);
if(this.html===null){
return;
}
this.html.style.left=this.x+"px";
this.html.style.top=this.y+"px";
};
Button.prototype.getWidth=function(){
return this.width;
};
Button.prototype.getHeight=function(){
return this.height;
};
Button.prototype.getY=function(){
return this.y;
};
Button.prototype.getX=function(){
return this.x;
};
Button.prototype.getPosition=function(){
return new Point(this.x,this.y);
};
ToggleButton=function(_3f94){
Button.call(this,_3f94);
this.isDownFlag=false;
};
ToggleButton.prototype=new Button();
ToggleButton.prototype.type="ToggleButton";
ToggleButton.prototype.createHTMLElement=function(){
var item=document.createElement("div");
item.id=this.id;
item.style.position="absolute";
item.style.left=this.x+"px";
item.style.top=this.y+"px";
item.style.height="24px";
item.style.width="24px";
item.style.margin="0px";
item.style.padding="0px";
if(this.getImageUrl()!==null){
item.style.backgroundImage="url("+this.getImageUrl()+")";
}else{
item.style.backgroundImage="";
}
var oThis=this;
this.omousedown=function(event){
if(oThis.enabled){
if(!oThis.isDown()){
Button.prototype.setActive.call(oThis,true);
}
}
event.cancelBubble=true;
event.returnValue=false;
};
this.omouseup=function(event){
if(oThis.enabled){
if(oThis.isDown()){
Button.prototype.setActive.call(oThis,false);
}
oThis.isDownFlag=!oThis.isDownFlag;
oThis.execute();
}
event.cancelBubble=true;
event.returnValue=false;
};
if(item.addEventListener){
item.addEventListener("mousedown",this.omousedown,false);
item.addEventListener("mouseup",this.omouseup,false);
}else{
if(item.attachEvent){
item.attachEvent("onmousedown",this.omousedown);
item.attachEvent("onmouseup",this.omouseup);
}
}
return item;
};
ToggleButton.prototype.isDown=function(){
return this.isDownFlag;
};
ToggleButton.prototype.setActive=function(flag){
Button.prototype.setActive.call(this,flag);
this.isDownFlag=flag;
};
ToggleButton.prototype.execute=function(){
};
ToolGeneric=function(_3e57){
this.x=0;
this.y=0;
this.enabled=true;
this.tooltip=null;
this.palette=_3e57;
this.html=this.createHTMLElement();
this.setDimension(10,10);
};
ToolGeneric.prototype.type="ToolGeneric";
ToolGeneric.prototype.dispose=function(){
};
ToolGeneric.prototype.getImageUrl=function(){
return this.type+".png";
};
ToolGeneric.prototype.getWorkflow=function(){
return this.getToolPalette().getWorkflow();
};
ToolGeneric.prototype.getToolPalette=function(){
return this.palette;
};
ToolGeneric.prototype.createHTMLElement=function(){
var item=document.createElement("div");
item.id=this.id;
item.style.position="absolute";
item.style.left=this.x+"px";
item.style.top=this.y+"px";
item.style.height="24px";
item.style.width="24px";
item.style.margin="0px";
item.style.padding="0px";
if(this.getImageUrl()!==null){
item.style.backgroundImage="url("+this.getImageUrl()+")";
}else{
item.style.backgroundImage="";
}
var oThis=this;
this.click=function(event){
if(oThis.enabled){
oThis.palette.setActiveTool(oThis);
}
event.cancelBubble=true;
event.returnValue=false;
};
if(item.addEventListener){
item.addEventListener("click",this.click,false);
}else{
if(item.attachEvent){
item.attachEvent("onclick",this.click);
}
}
if(this.tooltip!==null){
item.title=this.tooltip;
}else{
item.title="";
}
return item;
};
ToolGeneric.prototype.getHTMLElement=function(){
if(this.html===null){
this.html=this.createHTMLElement();
}
return this.html;
};
ToolGeneric.prototype.execute=function(x,y){
if(this.enabled){
this.palette.setActiveTool(null);
}
};
ToolGeneric.prototype.setTooltip=function(_3e5d){
this.tooltip=_3e5d;
if(this.tooltip!==null){
this.html.title=this.tooltip;
}else{
this.html.title="";
}
};
ToolGeneric.prototype.setActive=function(flag){
if(!this.enabled){
return;
}
if(flag===true){
this.html.style.border="1px inset";
}else{
this.html.style.border="0px";
}
};
ToolGeneric.prototype.setEnabled=function(flag){
this.enabled=flag;
if(flag){
this.html.style.filter="alpha(opacity=100)";
this.html.style.opacity="1.0";
}else{
this.html.style.filter="alpha(opacity=30)";
this.html.style.opacity="0.3";
}
};
ToolGeneric.prototype.setDimension=function(w,h){
this.width=w;
this.height=h;
if(this.html===null){
return;
}
this.html.style.width=this.width+"px";
this.html.style.height=this.height+"px";
};
ToolGeneric.prototype.setPosition=function(xPos,yPos){
this.x=Math.max(0,xPos);
this.y=Math.max(0,yPos);
if(this.html===null){
return;
}
this.html.style.left=this.x+"px";
this.html.style.top=this.y+"px";
};
ToolGeneric.prototype.getWidth=function(){
return this.width;
};
ToolGeneric.prototype.getHeight=function(){
return this.height;
};
ToolGeneric.prototype.getY=function(){
return this.y;
};
ToolGeneric.prototype.getX=function(){
return this.x;
};
ToolGeneric.prototype.getPosition=function(){
return new Point(this.x,this.y);
};
ToolPalette=function(title){
WindowFigure.call(this,title);
this.setDimension(75,400);
this.activeTool=null;
this.children={};
};
ToolPalette.prototype=new WindowFigure();
ToolPalette.prototype.type="ToolPalette";
ToolPalette.prototype.dispose=function(){
WindowFigure.prototype.dispose.call(this);
};
ToolPalette.prototype.createHTMLElement=function(){
var item=WindowFigure.prototype.createHTMLElement.call(this);
this.scrollarea=document.createElement("div");
this.scrollarea.style.position="absolute";
this.scrollarea.style.left="0px";
if(this.hasTitleBar()){
this.scrollarea.style.top="15px";
}else{
this.scrollarea.style.top="0px";
}
this.scrollarea.style.width=this.getWidth()+"px";
this.scrollarea.style.height="15px";
this.scrollarea.style.margin="0px";
this.scrollarea.style.padding="0px";
this.scrollarea.style.font="normal 10px verdana";
this.scrollarea.style.borderBottom="2px solid gray";
this.scrollarea.style.whiteSpace="nowrap";
this.scrollarea.style.textAlign="center";
this.scrollarea.style.overflowX="auto";
this.scrollarea.style.overflowY="auto";
this.scrollarea.style.overflow="auto";
item.appendChild(this.scrollarea);
return item;
};
ToolPalette.prototype.setDimension=function(w,h){
WindowFigure.prototype.setDimension.call(this,w,h);
if(this.scrollarea!==null){
this.scrollarea.style.width=this.getWidth()+"px";
if(this.hasTitleBar()){
this.scrollarea.style.height=(this.getHeight()-15)+"px";
}else{
this.scrollarea.style.height=this.getHeight()+"px";
}
}
};
ToolPalette.prototype.addChild=function(item){
this.children[item.id]=item;
this.scrollarea.appendChild(item.getHTMLElement());
};
ToolPalette.prototype.getChild=function(id){
return this.children[id];
};
ToolPalette.prototype.getActiveTool=function(){
return this.activeTool;
};
ToolPalette.prototype.setActiveTool=function(tool){
if(this.activeTool!=tool&&this.activeTool!==null){
this.activeTool.setActive(false);
}
if(tool!==null){
tool.setActive(true);
}
this.activeTool=tool;
};
Dialog=function(title){
this.buttonbar=null;
if(title){
WindowFigure.call(this,title);
}else{
WindowFigure.call(this,"Dialog");
}
this.setDimension(400,300);
};
Dialog.prototype=new WindowFigure();
Dialog.prototype.type="Dialog";
Dialog.prototype.createHTMLElement=function(){
var item=WindowFigure.prototype.createHTMLElement.call(this);
var oThis=this;
this.buttonbar=document.createElement("div");
this.buttonbar.style.position="absolute";
this.buttonbar.style.left="0px";
this.buttonbar.style.bottom="0px";
this.buttonbar.style.width=this.getWidth()+"px";
this.buttonbar.style.height="30px";
this.buttonbar.style.margin="0px";
this.buttonbar.style.padding="0px";
this.buttonbar.style.font="normal 10px verdana";
this.buttonbar.style.backgroundColor="#c0c0c0";
this.buttonbar.style.borderBottom="2px solid gray";
this.buttonbar.style.whiteSpace="nowrap";
this.buttonbar.style.textAlign="center";
this.buttonbar.className="Dialog_buttonbar";
this.okbutton=document.createElement("button");
this.okbutton.style.border="1px solid gray";
this.okbutton.style.font="normal 10px verdana";
this.okbutton.style.width="80px";
this.okbutton.style.margin="5px";
this.okbutton.className="Dialog_okbutton";
this.okbutton.innerHTML="Ok";
this.okbutton.onclick=function(){
var error=null;
try{
oThis.onOk();
}
catch(e){
error=e;
}
oThis.workflow.removeFigure(oThis);
if(error!==null){
throw error;
}
};
this.buttonbar.appendChild(this.okbutton);
this.cancelbutton=document.createElement("button");
this.cancelbutton.innerHTML="Cancel";
this.cancelbutton.style.font="normal 10px verdana";
this.cancelbutton.style.border="1px solid gray";
this.cancelbutton.style.width="80px";
this.cancelbutton.style.margin="5px";
this.cancelbutton.className="Dialog_cancelbutton";
this.cancelbutton.onclick=function(){
var error=null;
try{
oThis.onCancel();
}
catch(e){
error=e;
}
oThis.workflow.removeFigure(oThis);
if(error!==null){
throw error;
}
};
this.buttonbar.appendChild(this.cancelbutton);
item.appendChild(this.buttonbar);
return item;
};
Dialog.prototype.onOk=function(){
};
Dialog.prototype.onCancel=function(){
};
Dialog.prototype.setDimension=function(w,h){
WindowFigure.prototype.setDimension.call(this,w,h);
if(this.buttonbar!==null){
this.buttonbar.style.width=this.getWidth()+"px";
}
};
Dialog.prototype.setWorkflow=function(_4657){
WindowFigure.prototype.setWorkflow.call(this,_4657);
this.setFocus();
};
Dialog.prototype.setFocus=function(){
};
Dialog.prototype.onSetDocumentDirty=function(){
};
InputDialog=function(){
Dialog.call(this);
this.setDimension(400,100);
};
InputDialog.prototype=new Dialog();
InputDialog.prototype.type="InputDialog";
InputDialog.prototype.createHTMLElement=function(){
var item=Dialog.prototype.createHTMLElement.call(this);
return item;
};
InputDialog.prototype.onOk=function(){
this.workflow.removeFigure(this);
};
InputDialog.prototype.onCancel=function(){
this.workflow.removeFigure(this);
};
PropertyDialog=function(_3872,_3873,label){
this.figure=_3872;
this.propertyName=_3873;
this.label=label;
Dialog.call(this);
this.setDimension(400,120);
};
PropertyDialog.prototype=new Dialog();
PropertyDialog.prototype.type="PropertyDialog";
PropertyDialog.prototype.createHTMLElement=function(){
var item=Dialog.prototype.createHTMLElement.call(this);
var _3876=document.createElement("form");
_3876.style.position="absolute";
_3876.style.left="10px";
_3876.style.top="30px";
_3876.style.width="375px";
_3876.style.font="normal 10px verdana";
item.appendChild(_3876);
this.labelDiv=document.createElement("div");
this.labelDiv.innerHTML=this.label;
this.disableTextSelection(this.labelDiv);
_3876.appendChild(this.labelDiv);
this.input=document.createElement("input");
this.input.style.border="1px solid gray";
this.input.style.font="normal 10px verdana";
this.input.type="text";
var value=this.figure.getProperty(this.propertyName);
if(value){
this.input.value=value;
}else{
this.input.value="";
}
this.input.style.width="100%";
_3876.appendChild(this.input);
this.input.focus();
return item;
};
PropertyDialog.prototype.onOk=function(){
Dialog.prototype.onOk.call(this);
this.figure.setProperty(this.propertyName,this.input.value);
};
AnnotationDialog=function(_3725){
this.figure=_3725;
Dialog.call(this);
this.setDimension(400,100);
};
AnnotationDialog.prototype=new Dialog();
AnnotationDialog.prototype.type="AnnotationDialog";
AnnotationDialog.prototype.createHTMLElement=function(){
var item=Dialog.prototype.createHTMLElement.call(this);
var _3727=document.createElement("form");
_3727.style.position="absolute";
_3727.style.left="10px";
_3727.style.top="30px";
_3727.style.width="375px";
_3727.style.font="normal 10px verdana";
item.appendChild(_3727);
this.label=document.createTextNode("Text");
_3727.appendChild(this.label);
this.input=document.createElement("input");
this.input.style.border="1px solid gray";
this.input.style.font="normal 10px verdana";
this.input.type="text";
var value=this.figure.getText();
if(value){
this.input.value=value;
}else{
this.input.value="";
}
this.input.style.width="100%";
_3727.appendChild(this.input);
this.input.focus();
return item;
};
AnnotationDialog.prototype.onOk=function(){
this.workflow.getCommandStack().execute(new CommandSetText(this.figure,this.input.value));
this.workflow.removeFigure(this);
};
PropertyWindow=function(){
this.currentSelection=null;
WindowFigure.call(this,"Property Window");
this.setDimension(200,100);
};
PropertyWindow.prototype=new WindowFigure();
PropertyWindow.prototype.type="PropertyWindow";
PropertyWindow.prototype.dispose=function(){
WindowFigure.prototype.dispose.call(this);
};
PropertyWindow.prototype.createHTMLElement=function(){
var item=WindowFigure.prototype.createHTMLElement.call(this);
item.appendChild(this.createLabel("Type:",15,25));
item.appendChild(this.createLabel("X :",15,50));
item.appendChild(this.createLabel("Y :",15,70));
item.appendChild(this.createLabel("Width :",85,50));
item.appendChild(this.createLabel("Height :",85,70));
this.labelType=this.createLabel("",50,25);
this.labelX=this.createLabel("",40,50);
this.labelY=this.createLabel("",40,70);
this.labelWidth=this.createLabel("",135,50);
this.labelHeight=this.createLabel("",135,70);
this.labelType.style.fontWeight="normal";
this.labelX.style.fontWeight="normal";
this.labelY.style.fontWeight="normal";
this.labelWidth.style.fontWeight="normal";
this.labelHeight.style.fontWeight="normal";
item.appendChild(this.labelType);
item.appendChild(this.labelX);
item.appendChild(this.labelY);
item.appendChild(this.labelWidth);
item.appendChild(this.labelHeight);
return item;
};
PropertyWindow.prototype.onSelectionChanged=function(_3632){
WindowFigure.prototype.onSelectionChanged.call(this,_3632);
if(this.currentSelection!==null){
this.currentSelection.detachMoveListener(this);
}
this.currentSelection=_3632;
if(_3632!==null&&_3632!=this){
this.labelType.innerHTML=_3632.type;
if(_3632.getX){
this.labelX.innerHTML=_3632.getX();
this.labelY.innerHTML=_3632.getY();
this.labelWidth.innerHTML=_3632.getWidth();
this.labelHeight.innerHTML=_3632.getHeight();
this.currentSelection=_3632;
this.currentSelection.attachMoveListener(this);
}else{
this.labelX.innerHTML="";
this.labelY.innerHTML="";
this.labelWidth.innerHTML="";
this.labelHeight.innerHTML="";
}
}else{
this.labelType.innerHTML="&lt;none&gt;";
this.labelX.innerHTML="";
this.labelY.innerHTML="";
this.labelWidth.innerHTML="";
this.labelHeight.innerHTML="";
}
};
PropertyWindow.prototype.getCurrentSelection=function(){
return this.currentSelection;
};
PropertyWindow.prototype.onOtherFigureMoved=function(_3633){
if(_3633==this.currentSelection){
this.onSelectionChanged(_3633);
}
};
PropertyWindow.prototype.createLabel=function(text,x,y){
var l=document.createElement("div");
l.style.position="absolute";
l.style.left=x+"px";
l.style.top=y+"px";
l.style.font="normal 10px verdana";
l.style.whiteSpace="nowrap";
l.style.fontWeight="bold";
l.innerHTML=text;
return l;
};
ColorDialog=function(){
this.maxValue={"h":"359","s":"100","v":"100"};
this.HSV={0:359,1:100,2:100};
this.slideHSV={0:359,1:100,2:100};
this.SVHeight=165;
this.wSV=162;
this.wH=162;
Dialog.call(this,"Color Chooser");
this.loadSV();
this.setColor(new Color(255,0,0));
this.setDimension(219,244);
};
ColorDialog.prototype=new Dialog();
ColorDialog.prototype.type="ColorDialog";
ColorDialog.prototype.createHTMLElement=function(){
var oThis=this;
var item=Dialog.prototype.createHTMLElement.call(this);
this.outerDiv=document.createElement("div");
this.outerDiv.id="plugin";
this.outerDiv.style.top="15px";
this.outerDiv.style.left="0px";
this.outerDiv.style.width="201px";
this.outerDiv.style.position="absolute";
this.outerDiv.style.padding="9px";
this.outerDiv.display="block";
this.outerDiv.style.background="#0d0d0d";
this.plugHEX=document.createElement("div");
this.plugHEX.id="plugHEX";
this.plugHEX.innerHTML="F1FFCC";
this.plugHEX.style.color="white";
this.plugHEX.style.font="normal 10px verdana";
this.outerDiv.appendChild(this.plugHEX);
this.SV=document.createElement("div");
this.SV.onmousedown=function(event){
oThis.mouseDownSV(oThis.SVslide,event);
};
this.SV.id="SV";
this.SV.style.cursor="crosshair";
this.SV.style.background="#FF0000 url(SatVal.png)";
this.SV.style.position="absolute";
this.SV.style.height="166px";
this.SV.style.width="167px";
this.SV.style.marginRight="10px";
this.SV.style.filter="progid:DXImageTransform.Microsoft.AlphaImageLoader(src='SatVal.png', sizingMethod='scale')";
this.SV.style["float"]="left";
this.outerDiv.appendChild(this.SV);
this.SVslide=document.createElement("div");
this.SVslide.onmousedown=function(event){
oThis.mouseDownSV(event);
};
this.SVslide.style.top="40px";
this.SVslide.style.left="40px";
this.SVslide.style.position="absolute";
this.SVslide.style.cursor="crosshair";
this.SVslide.style.background="url(slide.gif)";
this.SVslide.style.height="9px";
this.SVslide.style.width="9px";
this.SVslide.style.lineHeight="1px";
this.outerDiv.appendChild(this.SVslide);
this.H=document.createElement("form");
this.H.id="H";
this.H.onmousedown=function(event){
oThis.mouseDownH(event);
};
this.H.style.border="1px solid #000000";
this.H.style.cursor="crosshair";
this.H.style.position="absolute";
this.H.style.width="19px";
this.H.style.top="28px";
this.H.style.left="191px";
this.outerDiv.appendChild(this.H);
this.Hslide=document.createElement("div");
this.Hslide.style.top="-7px";
this.Hslide.style.left="-8px";
this.Hslide.style.background="url(slideHue.gif)";
this.Hslide.style.height="5px";
this.Hslide.style.width="33px";
this.Hslide.style.position="absolute";
this.Hslide.style.lineHeight="1px";
this.H.appendChild(this.Hslide);
this.Hmodel=document.createElement("div");
this.Hmodel.style.height="1px";
this.Hmodel.style.width="19px";
this.Hmodel.style.lineHeight="1px";
this.Hmodel.style.margin="0px";
this.Hmodel.style.padding="0px";
this.Hmodel.style.fontSize="1px";
this.H.appendChild(this.Hmodel);
item.appendChild(this.outerDiv);
return item;
};
ColorDialog.prototype.onOk=function(){
Dialog.prototype.onOk.call(this);
};
browser=function(v){
return (Math.max(navigator.userAgent.toLowerCase().indexOf(v),0));
};
ColorDialog.prototype.showColor=function(c){
this.plugHEX.style.background="#"+c;
this.plugHEX.innerHTML=c;
};
ColorDialog.prototype.getSelectedColor=function(){
var rgb=this.hex2rgb(this.plugHEX.innerHTML);
return new Color(rgb[0],rgb[1],rgb[2]);
};
ColorDialog.prototype.setColor=function(color){
if(color===null){
color=new Color(100,100,100);
}
var hex=this.rgb2hex(Array(color.getRed(),color.getGreen(),color.getBlue()));
this.updateH(hex);
};
ColorDialog.prototype.XY=function(e,v){
var z=browser("msie")?Array(event.clientX+document.body.scrollLeft,event.clientY+document.body.scrollTop):Array(e.pageX,e.pageY);
return z[v];
};
ColorDialog.prototype.mkHSV=function(a,b,c){
return (Math.min(a,Math.max(0,Math.ceil((parseInt(c)/b)*a))));
};
ColorDialog.prototype.ckHSV=function(a,b){
if(a>=0&&a<=b){
return (a);
}else{
if(a>b){
return (b);
}else{
if(a<0){
return ("-"+oo);
}
}
}
};
ColorDialog.prototype.mouseDownH=function(e){
this.slideHSV[0]=this.HSV[0];
var oThis=this;
this.H.onmousemove=function(e){
oThis.dragH(e);
};
this.H.onmouseup=function(e){
oThis.H.onmousemove="";
oThis.H.onmouseup="";
};
this.dragH(e);
};
ColorDialog.prototype.dragH=function(e){
var y=this.XY(e,1)-this.getY()-40;
this.Hslide.style.top=(this.ckHSV(y,this.wH)-5)+"px";
this.slideHSV[0]=this.mkHSV(359,this.wH,this.Hslide.style.top);
this.updateSV();
this.showColor(this.commit());
this.SV.style.backgroundColor="#"+this.hsv2hex(Array(this.HSV[0],100,100));
};
ColorDialog.prototype.mouseDownSV=function(o,e){
this.slideHSV[0]=this.HSV[0];
var oThis=this;
function reset(){
oThis.SV.onmousemove="";
oThis.SV.onmouseup="";
oThis.SVslide.onmousemove="";
oThis.SVslide.onmouseup="";
}
this.SV.onmousemove=function(e){
oThis.dragSV(e);
};
this.SV.onmouseup=reset;
this.SVslide.onmousemove=function(e){
oThis.dragSV(e);
};
this.SVslide.onmouseup=reset;
this.dragSV(e);
};
ColorDialog.prototype.dragSV=function(e){
var x=this.XY(e,0)-this.getX()-1;
var y=this.XY(e,1)-this.getY()-20;
this.SVslide.style.left=this.ckHSV(x,this.wSV)+"px";
this.SVslide.style.top=this.ckHSV(y,this.wSV)+"px";
this.slideHSV[1]=this.mkHSV(100,this.wSV,this.SVslide.style.left);
this.slideHSV[2]=100-this.mkHSV(100,this.wSV,this.SVslide.style.top);
this.updateSV();
};
ColorDialog.prototype.commit=function(){
var r="hsv";
var z={};
var j="";
for(var i=0;i<=r.length-1;i++){
j=r.substr(i,1);
z[i]=(j=="h")?this.maxValue[j]-this.mkHSV(this.maxValue[j],this.wH,this.Hslide.style.top):this.HSV[i];
}
return (this.updateSV(this.hsv2hex(z)));
};
ColorDialog.prototype.updateSV=function(v){
this.HSV=v?this.hex2hsv(v):Array(this.slideHSV[0],this.slideHSV[1],this.slideHSV[2]);
if(!v){
v=this.hsv2hex(Array(this.slideHSV[0],this.slideHSV[1],this.slideHSV[2]));
}
this.showColor(v);
return v;
};
ColorDialog.prototype.loadSV=function(){
var z="";
for(var i=this.SVHeight;i>=0;i--){
z+="<div style=\"background:#"+this.hsv2hex(Array(Math.round((359/this.SVHeight)*i),100,100))+";\"><br/></div>";
}
this.Hmodel.innerHTML=z;
};
ColorDialog.prototype.updateH=function(v){
this.plugHEX.innerHTML=v;
this.HSV=this.hex2hsv(v);
this.SV.style.backgroundColor="#"+this.hsv2hex(Array(this.HSV[0],100,100));
this.SVslide.style.top=(parseInt(this.wSV-this.wSV*(this.HSV[1]/100))+20)+"px";
this.SVslide.style.left=(parseInt(this.wSV*(this.HSV[1]/100))+5)+"px";
this.Hslide.style.top=(parseInt(this.wH*((this.maxValue["h"]-this.HSV[0])/this.maxValue["h"]))-7)+"px";
};
ColorDialog.prototype.toHex=function(v){
v=Math.round(Math.min(Math.max(0,v),255));
return ("0123456789ABCDEF".charAt((v-v%16)/16)+"0123456789ABCDEF".charAt(v%16));
};
ColorDialog.prototype.hex2rgb=function(r){
return ({0:parseInt(r.substr(0,2),16),1:parseInt(r.substr(2,2),16),2:parseInt(r.substr(4,2),16)});
};
ColorDialog.prototype.rgb2hex=function(r){
return (this.toHex(r[0])+this.toHex(r[1])+this.toHex(r[2]));
};
ColorDialog.prototype.hsv2hex=function(h){
return (this.rgb2hex(this.hsv2rgb(h)));
};
ColorDialog.prototype.hex2hsv=function(v){
return (this.rgb2hsv(this.hex2rgb(v)));
};
ColorDialog.prototype.rgb2hsv=function(r){
var max=Math.max(r[0],r[1],r[2]);
var delta=max-Math.min(r[0],r[1],r[2]);
var H;
var S;
var V;
if(max!=0){
S=Math.round(delta/max*100);
if(r[0]==max){
H=(r[1]-r[2])/delta;
}else{
if(r[1]==max){
H=2+(r[2]-r[0])/delta;
}else{
if(r[2]==max){
H=4+(r[0]-r[1])/delta;
}
}
}
var H=Math.min(Math.round(H*60),360);
if(H<0){
H+=360;
}
}
return ({0:H?H:0,1:S?S:0,2:Math.round((max/255)*100)});
};
ColorDialog.prototype.hsv2rgb=function(r){
var R;
var B;
var G;
var S=r[1]/100;
var V=r[2]/100;
var H=r[0]/360;
if(S>0){
if(H>=1){
H=0;
}
H=6*H;
F=H-Math.floor(H);
A=Math.round(255*V*(1-S));
B=Math.round(255*V*(1-(S*F)));
C=Math.round(255*V*(1-(S*(1-F))));
V=Math.round(255*V);
switch(Math.floor(H)){
case 0:
R=V;
G=C;
B=A;
break;
case 1:
R=B;
G=V;
B=A;
break;
case 2:
R=A;
G=V;
B=C;
break;
case 3:
R=A;
G=B;
B=V;
break;
case 4:
R=C;
G=A;
B=V;
break;
case 5:
R=V;
G=A;
B=B;
break;
}
return ({0:R?R:0,1:G?G:0,2:B?B:0});
}else{
return ({0:(V=Math.round(V*255)),1:V,2:V});
}
};
LineColorDialog=function(_363b){
ColorDialog.call(this);
this.figure=_363b;
var color=_363b.getColor();
this.updateH(this.rgb2hex(color.getRed(),color.getGreen(),color.getBlue()));
};
LineColorDialog.prototype=new ColorDialog();
LineColorDialog.prototype.type="LineColorDialog";
LineColorDialog.prototype.onOk=function(){
var _363d=this.workflow;
ColorDialog.prototype.onOk.call(this);
if(typeof this.figure.setColor=="function"){
_363d.getCommandStack().execute(new CommandSetColor(this.figure,this.getSelectedColor()));
if(_363d.getCurrentSelection()==this.figure){
_363d.setCurrentSelection(this.figure);
}
}
};
BackgroundColorDialog=function(_371f){
ColorDialog.call(this);
this.figure=_371f;
var color=_371f.getBackgroundColor();
if(color!==null){
this.updateH(this.rgb2hex(color.getRed(),color.getGreen(),color.getBlue()));
}
};
BackgroundColorDialog.prototype=new ColorDialog();
BackgroundColorDialog.prototype.type="BackgroundColorDialog";
BackgroundColorDialog.prototype.onOk=function(){
var _3721=this.workflow;
ColorDialog.prototype.onOk.call(this);
if(typeof this.figure.setBackgroundColor=="function"){
_3721.getCommandStack().execute(new CommandSetBackgroundColor(this.figure,this.getSelectedColor()));
if(_3721.getCurrentSelection()==this.figure){
_3721.setCurrentSelection(this.figure);
}
}
};
AnnotationDialog=function(_3725){
this.figure=_3725;
Dialog.call(this);
this.setDimension(400,100);
};
AnnotationDialog.prototype=new Dialog();
AnnotationDialog.prototype.type="AnnotationDialog";
AnnotationDialog.prototype.createHTMLElement=function(){
var item=Dialog.prototype.createHTMLElement.call(this);
var _3727=document.createElement("form");
_3727.style.position="absolute";
_3727.style.left="10px";
_3727.style.top="30px";
_3727.style.width="375px";
_3727.style.font="normal 10px verdana";
item.appendChild(_3727);
this.label=document.createTextNode("Text");
_3727.appendChild(this.label);
this.input=document.createElement("input");
this.input.style.border="1px solid gray";
this.input.style.font="normal 10px verdana";
this.input.type="text";
var value=this.figure.getText();
if(value){
this.input.value=value;
}else{
this.input.value="";
}
this.input.style.width="100%";
_3727.appendChild(this.input);
this.input.focus();
return item;
};
AnnotationDialog.prototype.onOk=function(){
this.workflow.getCommandStack().execute(new CommandSetText(this.figure,this.input.value));
this.workflow.removeFigure(this);
};
Command=function(label){
this.label=label;
};
Command.prototype.type="Command";
Command.prototype.getLabel=function(){
return this.label;
};
Command.prototype.canExecute=function(){
return true;
};
Command.prototype.execute=function(){
};
Command.prototype.cancel=function(){
};
Command.prototype.undo=function(){
};
Command.prototype.redo=function(){
};
CommandStack=function(){
this.undostack=[];
this.redostack=[];
this.maxundo=50;
this.eventListeners=new ArrayList();
};
CommandStack.PRE_EXECUTE=1;
CommandStack.PRE_REDO=2;
CommandStack.PRE_UNDO=4;
CommandStack.POST_EXECUTE=8;
CommandStack.POST_REDO=16;
CommandStack.POST_UNDO=32;
CommandStack.POST_MASK=CommandStack.POST_EXECUTE|CommandStack.POST_UNDO|CommandStack.POST_REDO;
CommandStack.PRE_MASK=CommandStack.PRE_EXECUTE|CommandStack.PRE_UNDO|CommandStack.PRE_REDO;
CommandStack.prototype.type="CommandStack";
CommandStack.prototype.setUndoLimit=function(count){
this.maxundo=count;
};
CommandStack.prototype.markSaveLocation=function(){
this.undostack=[];
this.redostack=[];
};
CommandStack.prototype.execute=function(_39ad){
if(_39ad===null){
return;
}
if(_39ad.canExecute()==false){
return;
}
this.notifyListeners(_39ad,CommandStack.PRE_EXECUTE);
this.undostack.push(_39ad);
_39ad.execute();
this.redostack=[];
if(this.undostack.length>this.maxundo){
this.undostack=this.undostack.slice(this.undostack.length-this.maxundo);
}
this.notifyListeners(_39ad,CommandStack.POST_EXECUTE);
};
CommandStack.prototype.undo=function(){
var _39ae=this.undostack.pop();
if(_39ae){
this.notifyListeners(_39ae,CommandStack.PRE_UNDO);
this.redostack.push(_39ae);
_39ae.undo();
this.notifyListeners(_39ae,CommandStack.POST_UNDO);
}
};
CommandStack.prototype.redo=function(){
var _39af=this.redostack.pop();
if(_39af){
this.notifyListeners(_39af,CommandStack.PRE_REDO);
this.undostack.push(_39af);
_39af.redo();
this.notifyListeners(_39af,CommandStack.POST_REDO);
}
};
CommandStack.prototype.canRedo=function(){
return this.redostack.length>0;
};
CommandStack.prototype.canUndo=function(){
return this.undostack.length>0;
};
CommandStack.prototype.addCommandStackEventListener=function(_39b0){
this.eventListeners.add(_39b0);
};
CommandStack.prototype.removeCommandStackEventListener=function(_39b1){
this.eventListeners.remove(_39b1);
};
CommandStack.prototype.notifyListeners=function(_39b2,state){
var event=new CommandStackEvent(_39b2,state);
var size=this.eventListeners.getSize();
for(var i=0;i<size;i++){
this.eventListeners.get(i).stackChanged(event);
}
};
CommandStackEvent=function(_3ddf,_3de0){
this.command=_3ddf;
this.details=_3de0;
};
CommandStackEvent.prototype.type="CommandStackEvent";
CommandStackEvent.prototype.getCommand=function(){
return this.command;
};
CommandStackEvent.prototype.getDetails=function(){
return this.details;
};
CommandStackEvent.prototype.isPostChangeEvent=function(){
return 0!=(this.getDetails()&CommandStack.POST_MASK);
};
CommandStackEvent.prototype.isPreChangeEvent=function(){
return 0!=(this.getDetails()&CommandStack.PRE_MASK);
};
CommandStackEventListener=function(){
};
CommandStackEventListener.prototype.type="CommandStackEventListener";
CommandStackEventListener.prototype.stackChanged=function(event){
};
CommandAdd=function(_442d,_442e,x,y,_4431){
Command.call(this,"add figure");
if(_4431===undefined){
_4431=null;
}
this.parent=_4431;
this.figure=_442e;
this.x=x;
this.y=y;
this.workflow=_442d;
};
CommandAdd.prototype=new Command();
CommandAdd.prototype.type="CommandAdd";
CommandAdd.prototype.execute=function(){
this.redo();
};
CommandAdd.prototype.redo=function(){
if(this.x&&this.y){
this.workflow.addFigure(this.figure,this.x,this.y);
}else{
this.workflow.addFigure(this.figure);
}
this.workflow.setCurrentSelection(this.figure);
if(this.parent!==null){
this.parent.addChild(this.figure);
}
};
CommandAdd.prototype.undo=function(){
this.workflow.removeFigure(this.figure);
this.workflow.setCurrentSelection(null);
if(this.parent!==null){
this.parent.removeChild(this.figure);
}
};
CommandDelete=function(_465e){
Command.call(this,"delete figure");
this.parent=_465e.parent;
this.figure=_465e;
this.workflow=_465e.workflow;
this.connections=null;
this.compartmentDeleteCommands=null;
};
CommandDelete.prototype=new Command();
CommandDelete.prototype.type="CommandDelete";
CommandDelete.prototype.execute=function(){
this.redo();
};
CommandDelete.prototype.undo=function(){
if(this.figure instanceof CompartmentFigure){
for(var i=0;i<this.compartmentDeleteCommands.getSize();i++){
var _4660=this.compartmentDeleteCommands.get(i);
this.figure.addChild(_4660.figure);
this.workflow.getCommandStack().undo();
}
}
this.workflow.addFigure(this.figure);
if(this.figure instanceof Connection){
this.figure.reconnect();
}
this.workflow.setCurrentSelection(this.figure);
if(this.parent!==null){
this.parent.addChild(this.figure);
}
for(var i=0;i<this.connections.getSize();++i){
this.workflow.addFigure(this.connections.get(i));
this.connections.get(i).reconnect();
}
};
CommandDelete.prototype.redo=function(){
if(this.figure instanceof CompartmentFigure){
if(this.compartmentDeleteCommands===null){
this.compartmentDeleteCommands=new ArrayList();
var _4661=this.figure.getChildren().clone();
for(var i=0;i<_4661.getSize();i++){
var child=_4661.get(i);
this.figure.removeChild(child);
var _4664=new CommandDelete(child);
this.compartmentDeleteCommands.add(_4664);
this.workflow.getCommandStack().execute(_4664);
}
}else{
for(var i=0;i<this.compartmentDeleteCommands.getSize();i++){
this.workflow.redo();
}
}
}
this.workflow.removeFigure(this.figure);
this.workflow.setCurrentSelection(null);
if(this.figure instanceof Node&&this.connections===null){
this.connections=new ArrayList();
var ports=this.figure.getPorts();
for(var i=0;i<ports.getSize();i++){
var port=ports.get(i);
for(var c=0,c_size=port.getConnections().getSize();c<c_size;c++){
if(!this.connections.contains(port.getConnections().get(c))){
this.connections.add(port.getConnections().get(c));
}
}
}
}
if(this.connections===null){
this.connections=new ArrayList();
}
if(this.parent!==null){
this.parent.removeChild(this.figure);
}
for(var i=0;i<this.connections.getSize();++i){
this.workflow.removeFigure(this.connections.get(i));
}
};
CommandMove=function(_3161,x,y){
Command.call(this,"move figure");
this.figure=_3161;
if(x==undefined){
this.oldX=_3161.getX();
this.oldY=_3161.getY();
}else{
this.oldX=x;
this.oldY=y;
}
this.oldCompartment=_3161.getParent();
};
CommandMove.prototype=new Command();
CommandMove.prototype.type="CommandMove";
CommandMove.prototype.setStartPosition=function(x,y){
this.oldX=x;
this.oldY=y;
};
CommandMove.prototype.setPosition=function(x,y){
this.newX=x;
this.newY=y;
this.newCompartment=this.figure.workflow.getBestCompartmentFigure(x,y,this.figure);
};
CommandMove.prototype.canExecute=function(){
return this.newX!=this.oldX||this.newY!=this.oldY;
};
CommandMove.prototype.execute=function(){
this.redo();
};
CommandMove.prototype.undo=function(){
this.figure.setPosition(this.oldX,this.oldY);
if(this.newCompartment!==null){
this.newCompartment.removeChild(this.figure);
}
if(this.oldCompartment!==null){
this.oldCompartment.addChild(this.figure);
}
this.figure.workflow.moveResizeHandles(this.figure);
};
CommandMove.prototype.redo=function(){
this.figure.setPosition(this.newX,this.newY);
if(this.oldCompartment!==null){
this.oldCompartment.removeChild(this.figure);
}
if(this.newCompartment!==null){
this.newCompartment.addChild(this.figure);
}
this.figure.workflow.moveResizeHandles(this.figure);
};
CommandResize=function(_3fbe,width,_3fc0){
Command.call(this,"resize figure");
this.figure=_3fbe;
if(width===undefined){
this.oldWidth=_3fbe.getWidth();
this.oldHeight=_3fbe.getHeight();
}else{
this.oldWidth=width;
this.oldHeight=_3fc0;
}
};
CommandResize.prototype=new Command();
CommandResize.prototype.type="CommandResize";
CommandResize.prototype.setDimension=function(width,_3fc2){
this.newWidth=width;
this.newHeight=_3fc2;
};
CommandResize.prototype.canExecute=function(){
return this.newWidth!=this.oldWidth||this.newHeight!=this.oldHeight;
};
CommandResize.prototype.execute=function(){
this.redo();
};
CommandResize.prototype.undo=function(){
this.figure.setDimension(this.oldWidth,this.oldHeight);
this.figure.workflow.moveResizeHandles(this.figure);
};
CommandResize.prototype.redo=function(){
this.figure.setDimension(this.newWidth,this.newHeight);
this.figure.workflow.moveResizeHandles(this.figure);
};
CommandSetText=function(_42d5,text){
Command.call(this,"set text");
this.figure=_42d5;
this.newText=text;
this.oldText=_42d5.getText();
};
CommandSetText.prototype=new Command();
CommandSetText.prototype.type="CommandSetText";
CommandSetText.prototype.execute=function(){
this.redo();
};
CommandSetText.prototype.redo=function(){
this.figure.setText(this.newText);
};
CommandSetText.prototype.undo=function(){
this.figure.setText(this.oldText);
};
CommandSetColor=function(_43fa,color){
Command.call(this,"set color");
this.figure=_43fa;
this.newColor=color;
this.oldColor=_43fa.getColor();
};
CommandSetColor.prototype=new Command();
CommandSetColor.prototype.type="CommandSetColor";
CommandSetColor.prototype.execute=function(){
this.redo();
};
CommandSetColor.prototype.undo=function(){
this.figure.setColor(this.oldColor);
};
CommandSetColor.prototype.redo=function(){
this.figure.setColor(this.newColor);
};
CommandSetBackgroundColor=function(_4658,color){
Command.call(this,"set background color");
this.figure=_4658;
this.newColor=color;
this.oldColor=_4658.getBackgroundColor();
};
CommandSetBackgroundColor.prototype=new Command();
CommandSetBackgroundColor.prototype.type="CommandSetBackgroundColor";
CommandSetBackgroundColor.prototype.execute=function(){
this.redo();
};
CommandSetBackgroundColor.prototype.undo=function(){
this.figure.setBackgroundColor(this.oldColor);
};
CommandSetBackgroundColor.prototype.redo=function(){
this.figure.setBackgroundColor(this.newColor);
};
CommandConnect=function(_4668,_4669,_466a){
Command.call(this,"create connection");
this.workflow=_4668;
this.source=_4669;
this.target=_466a;
this.connection=null;
};
CommandConnect.prototype=new Command();
CommandConnect.prototype.type="CommandConnect";
CommandConnect.prototype.setConnection=function(_466b){
this.connection=_466b;
};
CommandConnect.prototype.execute=function(){
if(this.connection===null){
this.connection=new Connection();
}
this.connection.setSource(this.source);
this.connection.setTarget(this.target);
this.workflow.addFigure(this.connection);
};
CommandConnect.prototype.redo=function(){
this.workflow.addFigure(this.connection);
this.connection.reconnect();
};
CommandConnect.prototype.undo=function(){
this.workflow.removeFigure(this.connection);
};
CommandReconnect=function(con){
Command.call(this,"reconnect connection");
this.con=con;
this.oldSourcePort=con.getSource();
this.oldTargetPort=con.getTarget();
this.oldRouter=con.getRouter();
this.con.setRouter(new NullConnectionRouter());
};
CommandReconnect.prototype=new Command();
CommandReconnect.prototype.type="CommandReconnect";
CommandReconnect.prototype.canExecute=function(){
return true;
};
CommandReconnect.prototype.setNewPorts=function(_3e4a,_3e4b){
this.newSourcePort=_3e4a;
this.newTargetPort=_3e4b;
};
CommandReconnect.prototype.execute=function(){
this.redo();
};
CommandReconnect.prototype.cancel=function(){
var start=this.con.sourceAnchor.getLocation(this.con.targetAnchor.getReferencePoint());
var end=this.con.targetAnchor.getLocation(this.con.sourceAnchor.getReferencePoint());
this.con.setStartPoint(start.x,start.y);
this.con.setEndPoint(end.x,end.y);
this.con.getWorkflow().showLineResizeHandles(this.con);
this.con.setRouter(this.oldRouter);
};
CommandReconnect.prototype.undo=function(){
this.con.setSource(this.oldSourcePort);
this.con.setTarget(this.oldTargetPort);
this.con.setRouter(this.oldRouter);
if(this.con.getWorkflow().getCurrentSelection()==this.con){
this.con.getWorkflow().showLineResizeHandles(this.con);
}
};
CommandReconnect.prototype.redo=function(){
this.con.setSource(this.newSourcePort);
this.con.setTarget(this.newTargetPort);
this.con.setRouter(this.oldRouter);
if(this.con.getWorkflow().getCurrentSelection()==this.con){
this.con.getWorkflow().showLineResizeHandles(this.con);
}
};
CommandMoveLine=function(line,_3c17,_3c18,endX,endY){
Command.call(this,"move line");
this.line=line;
this.startX1=_3c17;
this.startY1=_3c18;
this.endX1=endX;
this.endY1=endY;
};
CommandMoveLine.prototype=new Command();
CommandMoveLine.prototype.type="CommandMoveLine";
CommandMoveLine.prototype.canExecute=function(){
return this.startX1!=this.startX2||this.startY1!=this.startY2||this.endX1!=this.endX2||this.endY1!=this.endY2;
};
CommandMoveLine.prototype.execute=function(){
this.startX2=this.line.getStartX();
this.startY2=this.line.getStartY();
this.endX2=this.line.getEndX();
this.endY2=this.line.getEndY();
this.redo();
};
CommandMoveLine.prototype.undo=function(){
this.line.setStartPoint(this.startX1,this.startY1);
this.line.setEndPoint(this.endX1,this.endY1);
if(this.line.workflow.getCurrentSelection()==this.line){
this.line.workflow.showLineResizeHandles(this.line);
}
};
CommandMoveLine.prototype.redo=function(){
this.line.setStartPoint(this.startX2,this.startY2);
this.line.setEndPoint(this.endX2,this.endY2);
if(this.line.workflow.getCurrentSelection()==this.line){
this.line.workflow.showLineResizeHandles(this.line);
}
};
CommandMovePort=function(port){
Command.call(this,"move port");
this.port=port;
};
CommandMovePort.prototype=new Command();
CommandMovePort.prototype.type="CommandMovePort";
CommandMovePort.prototype.execute=function(){
this.port.setAlpha(1);
this.port.setPosition(this.port.originX,this.port.originY);
this.port.parentNode.workflow.hideConnectionLine();
};
CommandMovePort.prototype.undo=function(){
};
CommandMovePort.prototype.redo=function(){
};
CommandMovePort.prototype.setPosition=function(x,y){
};
Menu=function(){
this.menuItems=new ArrayList();
Figure.call(this);
this.setSelectable(false);
this.setDeleteable(false);
this.setCanDrag(false);
this.setResizeable(false);
this.setSelectable(false);
this.setZOrder(10000);
this.dirty=false;
};
Menu.prototype=new Figure();
Menu.prototype.type="Menu";
Menu.prototype.createHTMLElement=function(){
var item=document.createElement("div");
item.style.position="absolute";
item.style.left=this.x+"px";
item.style.top=this.y+"px";
item.style.margin="0px";
item.style.padding="0px";
item.style.zIndex=""+Figure.ZOrderBaseIndex;
item.style.border="1px solid gray";
item.style.background="lavender";
item.style.cursor="pointer";
item.style.width="auto";
item.style.height="auto";
item.className="Menu";
return item;
};
Menu.prototype.setWorkflow=function(_460a){
this.workflow=_460a;
};
Menu.prototype.setDimension=function(w,h){
};
Menu.prototype.appendMenuItem=function(item){
this.menuItems.add(item);
item.parentMenu=this;
this.dirty=true;
};
Menu.prototype.getHTMLElement=function(){
var html=Figure.prototype.getHTMLElement.call(this);
if(this.dirty){
this.createList();
}
return html;
};
Menu.prototype.createList=function(){
this.dirty=false;
this.html.innerHTML="";
var oThis=this;
for(var i=0;i<this.menuItems.getSize();i++){
var item=this.menuItems.get(i);
var li=document.createElement("a");
li.innerHTML=item.getLabel();
li.style.display="block";
li.style.fontFamily="Verdana, Arial, Helvetica, sans-serif";
li.style.fontSize="9pt";
li.style.color="dimgray";
li.style.borderBottom="1px solid silver";
li.style.paddingLeft="5px";
li.style.paddingRight="5px";
li.style.whiteSpace="nowrap";
li.style.cursor="pointer";
li.className="MenuItem";
this.html.appendChild(li);
li.menuItem=item;
if(li.addEventListener){
li.addEventListener("click",function(event){
var _4614=arguments[0]||window.event;
_4614.cancelBubble=true;
_4614.returnValue=false;
var diffX=_4614.clientX;
var diffY=_4614.clientY;
var _4617=document.body.parentNode.scrollLeft;
var _4618=document.body.parentNode.scrollTop;
this.menuItem.execute(diffX+_4617,diffY+_4618);
},false);
li.addEventListener("mouseup",function(event){
event.cancelBubble=true;
event.returnValue=false;
},false);
li.addEventListener("mousedown",function(event){
event.cancelBubble=true;
event.returnValue=false;
},false);
li.addEventListener("mouseover",function(event){
this.style.backgroundColor="silver";
},false);
li.addEventListener("mouseout",function(event){
this.style.backgroundColor="transparent";
},false);
}else{
if(li.attachEvent){
li.attachEvent("onclick",function(event){
var _461e=arguments[0]||window.event;
_461e.cancelBubble=true;
_461e.returnValue=false;
var diffX=_461e.clientX;
var diffY=_461e.clientY;
var _4621=document.body.parentNode.scrollLeft;
var _4622=document.body.parentNode.scrollTop;
event.srcElement.menuItem.execute(diffX+_4621,diffY+_4622);
});
li.attachEvent("onmousedown",function(event){
event.cancelBubble=true;
event.returnValue=false;
});
li.attachEvent("onmouseup",function(event){
event.cancelBubble=true;
event.returnValue=false;
});
li.attachEvent("onmouseover",function(event){
event.srcElement.style.backgroundColor="silver";
});
li.attachEvent("onmouseout",function(event){
event.srcElement.style.backgroundColor="transparent";
});
}
}
}
};
MenuItem=function(label,_3d72,_3d73){
this.label=label;
this.iconUrl=_3d72;
this.parentMenu=null;
this.action=_3d73;
};
MenuItem.prototype.type="MenuItem";
MenuItem.prototype.isEnabled=function(){
return true;
};
MenuItem.prototype.getLabel=function(){
return this.label;
};
MenuItem.prototype.execute=function(x,y){
this.parentMenu.workflow.showMenu(null);
this.action(x,y);
};
Locator=function(){
};
Locator.prototype.type="Locator";
Locator.prototype.relocate=function(_42d8){
};
ConnectionLocator=function(_464f){
Locator.call(this);
this.connection=_464f;
};
ConnectionLocator.prototype=new Locator;
ConnectionLocator.prototype.type="ConnectionLocator";
ConnectionLocator.prototype.getConnection=function(){
return this.connection;
};
ManhattanMidpointLocator=function(_4425){
ConnectionLocator.call(this,_4425);
};
ManhattanMidpointLocator.prototype=new ConnectionLocator;
ManhattanMidpointLocator.prototype.type="ManhattanMidpointLocator";
ManhattanMidpointLocator.prototype.relocate=function(_4426){
var conn=this.getConnection();
var p=new Point();
var _4429=conn.getPoints();
var index=Math.floor((_4429.getSize()-2)/2);
if(_4429.getSize()<=index+1){
return;
}
var p1=_4429.get(index);
var p2=_4429.get(index+1);
p.x=(p2.x-p1.x)/2+p1.x+5;
p.y=(p2.y-p1.y)/2+p1.y+5;
_4426.setPosition(p.x,p.y);
};
EditPartFactory=function(){
};
EditPartFactory.prototype.type="EditPartFactory";
EditPartFactory.prototype.createEditPart=function(model){
};
AbstractObjectModel=function(){
this.listeners=new ArrayList();
this.id=UUID.create();
};
AbstractObjectModel.EVENT_ELEMENT_ADDED="element added";
AbstractObjectModel.EVENT_ELEMENT_REMOVED="element removed";
AbstractObjectModel.EVENT_CONNECTION_ADDED="connection addedx";
AbstractObjectModel.EVENT_CONNECTION_REMOVED="connection removed";
AbstractObjectModel.prototype.type="AbstractObjectModel";
AbstractObjectModel.prototype.getModelChildren=function(){
return new ArrayList();
};
AbstractObjectModel.prototype.getModelParent=function(){
return this.modelParent;
};
AbstractObjectModel.prototype.setModelParent=function(_3e66){
this.modelParent=_3e66;
};
AbstractObjectModel.prototype.getId=function(){
return this.id;
};
AbstractObjectModel.prototype.firePropertyChange=function(_3e67,_3e68,_3e69){
var count=this.listeners.getSize();
if(count===0){
return;
}
var event=new PropertyChangeEvent(this,_3e67,_3e68,_3e69);
for(var i=0;i<count;i++){
try{
this.listeners.get(i).propertyChange(event);
}
catch(e){
alert("Method: AbstractObjectModel.prototype.firePropertyChange\n"+e+"\nProperty: "+_3e67+"\nListener Class:"+this.listeners.get(i).type);
}
}
};
AbstractObjectModel.prototype.addPropertyChangeListener=function(_3e6d){
if(_3e6d!==null){
this.listeners.add(_3e6d);
}
};
AbstractObjectModel.prototype.removePropertyChangeListener=function(_3e6e){
if(_3e6e!==null){
this.listeners.remove(_3e6e);
}
};
AbstractObjectModel.prototype.getPersistentAttributes=function(){
return {id:this.id};
};
AbstractConnectionModel=function(){
AbstractObjectModel.call(this);
};
AbstractConnectionModel.prototype=new AbstractObjectModel();
AbstractConnectionModel.prototype.type="AbstractConnectionModel";
AbstractConnectionModel.prototype.getSourceModel=function(){
throw "you must override the method [AbstractConnectionModel.prototype.getSourceModel]";
};
AbstractConnectionModel.prototype.getTargetModel=function(){
throw "you must override the method [AbstractConnectionModel.prototype.getTargetModel]";
};
AbstractConnectionModel.prototype.getSourcePortName=function(){
throw "you must override the method [AbstractConnectionModel.prototype.getSourcePortName]";
};
AbstractConnectionModel.prototype.getTargetPortName=function(){
throw "you must override the method [AbstractConnectionModel.prototype.getTargetPortName]";
};
AbstractConnectionModel.prototype.getSourcePortModel=function(){
throw "you must override the method [AbstractConnectionModel.prototype.getSourcePortModel]";
};
AbstractConnectionModel.prototype.getTargetPortModel=function(){
throw "you must override the method [AbstractConnectionModel.prototype.getTargetPortModel]";
};
PropertyChangeEvent=function(model,_3b86,_3b87,_3b88){
this.model=model;
this.property=_3b86;
this.oldValue=_3b87;
this.newValue=_3b88;
};
PropertyChangeEvent.prototype.type="PropertyChangeEvent";
GraphicalViewer=function(id){
try{
Workflow.call(this,id);
this.factory=null;
this.model=null;
this.initDone=false;
}
catch(e){
pushErrorStack(e,"GraphicalViewer=function(/*:String*/ id)");
}
};
GraphicalViewer.prototype=new Workflow();
GraphicalViewer.prototype.type="GraphicalViewer";
GraphicalViewer.prototype.setEditPartFactory=function(_3f9b){
this.factory=_3f9b;
this.checkInit();
};
GraphicalViewer.prototype.setModel=function(model){
try{
if(model instanceof AbstractObjectModel){
this.model=model;
this.checkInit();
this.model.addPropertyChangeListener(this);
}else{
alert("Invalid model class type:"+model.type);
}
}
catch(e){
pushErrorStack(e,"GraphicalViewer.prototype.setModel=function(/*:AbstractObjectModel*/ model )");
}
};
GraphicalViewer.prototype.propertyChange=function(event){
switch(event.property){
case AbstractObjectModel.EVENT_ELEMENT_REMOVED:
var _3f9e=this.getFigure(event.oldValue.getId());
this.removeFigure(_3f9e);
break;
case AbstractObjectModel.EVENT_ELEMENT_ADDED:
var _3f9e=this.factory.createEditPart(event.newValue);
_3f9e.setId(event.newValue.getId());
this.addFigure(_3f9e);
this.setCurrentSelection(_3f9e);
break;
}
};
GraphicalViewer.prototype.checkInit=function(){
if(this.factory!==null&&this.model!==null&&this.initDone==false){
try{
var _3f9f=this.model.getModelChildren();
var count=_3f9f.getSize();
for(var i=0;i<count;i++){
var child=_3f9f.get(i);
var _3fa3=this.factory.createEditPart(child);
_3fa3.setId(child.getId());
this.addFigure(_3fa3);
}
}
catch(e){
pushErrorStack(e,"GraphicalViewer.prototype.checkInit=function()[addFigures]");
}
try{
var _3fa4=this.getDocument().getFigures();
var count=_3fa4.getSize();
for(var i=0;i<count;i++){
var _3fa3=_3fa4.get(i);
if(_3fa3 instanceof Node){
this.refreshConnections(_3fa3);
}
}
}
catch(e){
pushErrorStack(e,"GraphicalViewer.prototype.checkInit=function()[refreshConnections]");
}
}
};
GraphicalViewer.prototype.refreshConnections=function(node){
try{
var _3fa6=new ArrayList();
var _3fa7=node.getModelSourceConnections();
var count=_3fa7.getSize();
for(var i=0;i<count;i++){
var _3faa=_3fa7.get(i);
_3fa6.add(_3faa.getId());
var _3fab=this.getLine(_3faa.getId());
if(_3fab===null){
_3fab=this.factory.createEditPart(_3faa);
var _3fac=_3faa.getSourceModel();
var _3fad=_3faa.getTargetModel();
var _3fae=this.getFigure(_3fac.getId());
var _3faf=this.getFigure(_3fad.getId());
var _3fb0=_3fae.getOutputPort(_3faa.getSourcePortName());
var _3fb1=_3faf.getInputPort(_3faa.getTargetPortName());
_3fab.setTarget(_3fb1);
_3fab.setSource(_3fb0);
_3fab.setId(_3faa.getId());
this.addFigure(_3fab);
this.setCurrentSelection(_3fab);
}
}
var ports=node.getOutputPorts();
count=ports.getSize();
for(var i=0;i<count;i++){
var _3fb3=ports.get(i).getConnections();
var _3fb4=_3fb3.getSize();
for(var ii=0;ii<_3fb4;ii++){
var _3fb6=_3fb3.get(ii);
if(!_3fa6.contains(_3fb6.getId())){
this.removeFigure(_3fb6);
_3fa6.add(_3fb6.getId());
}
}
}
}
catch(e){
pushErrorStack(e,"GraphicalViewer.prototype.refreshConnections=function(/*:Node*/ node )");
}
};
GraphicalEditor=function(id){
try{
this.view=new GraphicalViewer(id);
this.initializeGraphicalViewer();
}
catch(e){
pushErrorStack(e,"GraphicalEditor=function(/*:String*/ id)");
}
};
GraphicalEditor.prototype.type="GraphicalEditor";
GraphicalEditor.prototype.initializeGraphicalViewer=function(){
};
GraphicalEditor.prototype.getGraphicalViewer=function(){
return this.view;
};
var whitespace="\n\r\t ";
XMLP=function(_3b16){
_3b16=SAXStrings.replace(_3b16,null,null,"\r\n","\n");
_3b16=SAXStrings.replace(_3b16,null,null,"\r","\n");
this.m_xml=_3b16;
this.m_iP=0;
this.m_iState=XMLP._STATE_PROLOG;
this.m_stack=new Stack();
this._clearAttributes();
};
XMLP._NONE=0;
XMLP._ELM_B=1;
XMLP._ELM_E=2;
XMLP._ELM_EMP=3;
XMLP._ATT=4;
XMLP._TEXT=5;
XMLP._ENTITY=6;
XMLP._PI=7;
XMLP._CDATA=8;
XMLP._COMMENT=9;
XMLP._DTD=10;
XMLP._ERROR=11;
XMLP._CONT_XML=0;
XMLP._CONT_ALT=1;
XMLP._ATT_NAME=0;
XMLP._ATT_VAL=1;
XMLP._STATE_PROLOG=1;
XMLP._STATE_DOCUMENT=2;
XMLP._STATE_MISC=3;
XMLP._errs=[];
XMLP._errs[XMLP.ERR_CLOSE_PI=0]="PI: missing closing sequence";
XMLP._errs[XMLP.ERR_CLOSE_DTD=1]="DTD: missing closing sequence";
XMLP._errs[XMLP.ERR_CLOSE_COMMENT=2]="Comment: missing closing sequence";
XMLP._errs[XMLP.ERR_CLOSE_CDATA=3]="CDATA: missing closing sequence";
XMLP._errs[XMLP.ERR_CLOSE_ELM=4]="Element: missing closing sequence";
XMLP._errs[XMLP.ERR_CLOSE_ENTITY=5]="Entity: missing closing sequence";
XMLP._errs[XMLP.ERR_PI_TARGET=6]="PI: target is required";
XMLP._errs[XMLP.ERR_ELM_EMPTY=7]="Element: cannot be both empty and closing";
XMLP._errs[XMLP.ERR_ELM_NAME=8]="Element: name must immediatly follow \"<\"";
XMLP._errs[XMLP.ERR_ELM_LT_NAME=9]="Element: \"<\" not allowed in element names";
XMLP._errs[XMLP.ERR_ATT_VALUES=10]="Attribute: values are required and must be in quotes";
XMLP._errs[XMLP.ERR_ATT_LT_NAME=11]="Element: \"<\" not allowed in attribute names";
XMLP._errs[XMLP.ERR_ATT_LT_VALUE=12]="Attribute: \"<\" not allowed in attribute values";
XMLP._errs[XMLP.ERR_ATT_DUP=13]="Attribute: duplicate attributes not allowed";
XMLP._errs[XMLP.ERR_ENTITY_UNKNOWN=14]="Entity: unknown entity";
XMLP._errs[XMLP.ERR_INFINITELOOP=15]="Infininte loop";
XMLP._errs[XMLP.ERR_DOC_STRUCTURE=16]="Document: only comments, processing instructions, or whitespace allowed outside of document element";
XMLP._errs[XMLP.ERR_ELM_NESTING=17]="Element: must be nested correctly";
XMLP.prototype._addAttribute=function(name,value){
this.m_atts[this.m_atts.length]=new Array(name,value);
};
XMLP.prototype._checkStructure=function(_3b19){
if(XMLP._STATE_PROLOG==this.m_iState){
if((XMLP._TEXT==_3b19)||(XMLP._ENTITY==_3b19)){
if(SAXStrings.indexOfNonWhitespace(this.getContent(),this.getContentBegin(),this.getContentEnd())!=-1){
return this._setErr(XMLP.ERR_DOC_STRUCTURE);
}
}
if((XMLP._ELM_B==_3b19)||(XMLP._ELM_EMP==_3b19)){
this.m_iState=XMLP._STATE_DOCUMENT;
}
}
if(XMLP._STATE_DOCUMENT==this.m_iState){
if((XMLP._ELM_B==_3b19)||(XMLP._ELM_EMP==_3b19)){
this.m_stack.push(this.getName());
}
if((XMLP._ELM_E==_3b19)||(XMLP._ELM_EMP==_3b19)){
var _3b1a=this.m_stack.pop();
if((_3b1a===null)||(_3b1a!=this.getName())){
return this._setErr(XMLP.ERR_ELM_NESTING);
}
}
if(this.m_stack.count()===0){
this.m_iState=XMLP._STATE_MISC;
return _3b19;
}
}
if(XMLP._STATE_MISC==this.m_iState){
if((XMLP._ELM_B==_3b19)||(XMLP._ELM_E==_3b19)||(XMLP._ELM_EMP==_3b19)||(XMLP.EVT_DTD==_3b19)){
return this._setErr(XMLP.ERR_DOC_STRUCTURE);
}
if((XMLP._TEXT==_3b19)||(XMLP._ENTITY==_3b19)){
if(SAXStrings.indexOfNonWhitespace(this.getContent(),this.getContentBegin(),this.getContentEnd())!=-1){
return this._setErr(XMLP.ERR_DOC_STRUCTURE);
}
}
}
return _3b19;
};
XMLP.prototype._clearAttributes=function(){
this.m_atts=[];
};
XMLP.prototype._findAttributeIndex=function(name){
for(var i=0;i<this.m_atts.length;i++){
if(this.m_atts[i][XMLP._ATT_NAME]==name){
return i;
}
}
return -1;
};
XMLP.prototype.getAttributeCount=function(){
return this.m_atts?this.m_atts.length:0;
};
XMLP.prototype.getAttributeName=function(index){
return ((index<0)||(index>=this.m_atts.length))?null:this.m_atts[index][XMLP._ATT_NAME];
};
XMLP.prototype.getAttributeValue=function(index){
return ((index<0)||(index>=this.m_atts.length))?null:__unescapeString(this.m_atts[index][XMLP._ATT_VAL]);
};
XMLP.prototype.getAttributeValueByName=function(name){
return this.getAttributeValue(this._findAttributeIndex(name));
};
XMLP.prototype.getColumnNumber=function(){
return SAXStrings.getColumnNumber(this.m_xml,this.m_iP);
};
XMLP.prototype.getContent=function(){
return (this.m_cSrc==XMLP._CONT_XML)?this.m_xml:this.m_cAlt;
};
XMLP.prototype.getContentBegin=function(){
return this.m_cB;
};
XMLP.prototype.getContentEnd=function(){
return this.m_cE;
};
XMLP.prototype.getLineNumber=function(){
return SAXStrings.getLineNumber(this.m_xml,this.m_iP);
};
XMLP.prototype.getName=function(){
return this.m_name;
};
XMLP.prototype.next=function(){
return this._checkStructure(this._parse());
};
XMLP.prototype._parse=function(){
if(this.m_iP==this.m_xml.length){
return XMLP._NONE;
}
if(this.m_iP==this.m_xml.indexOf("<?",this.m_iP)){
return this._parsePI(this.m_iP+2);
}else{
if(this.m_iP==this.m_xml.indexOf("<!DOCTYPE",this.m_iP)){
return this._parseDTD(this.m_iP+9);
}else{
if(this.m_iP==this.m_xml.indexOf("<!--",this.m_iP)){
return this._parseComment(this.m_iP+4);
}else{
if(this.m_iP==this.m_xml.indexOf("<![CDATA[",this.m_iP)){
return this._parseCDATA(this.m_iP+9);
}else{
if(this.m_iP==this.m_xml.indexOf("<",this.m_iP)){
return this._parseElement(this.m_iP+1);
}else{
if(this.m_iP==this.m_xml.indexOf("&",this.m_iP)){
return this._parseEntity(this.m_iP+1);
}else{
return this._parseText(this.m_iP);
}
}
}
}
}
}
};
XMLP.prototype._parseAttribute=function(iB,iE){
var iNB,iNE,iEq,iVB,iVE;
var _3b23,strN,strV;
this.m_cAlt="";
iNB=SAXStrings.indexOfNonWhitespace(this.m_xml,iB,iE);
if((iNB==-1)||(iNB>=iE)){
return iNB;
}
iEq=this.m_xml.indexOf("=",iNB);
if((iEq==-1)||(iEq>iE)){
return this._setErr(XMLP.ERR_ATT_VALUES);
}
iNE=SAXStrings.lastIndexOfNonWhitespace(this.m_xml,iNB,iEq);
iVB=SAXStrings.indexOfNonWhitespace(this.m_xml,iEq+1,iE);
if((iVB==-1)||(iVB>iE)){
return this._setErr(XMLP.ERR_ATT_VALUES);
}
_3b23=this.m_xml.charAt(iVB);
if(SAXStrings.QUOTES.indexOf(_3b23)==-1){
return this._setErr(XMLP.ERR_ATT_VALUES);
}
iVE=this.m_xml.indexOf(_3b23,iVB+1);
if((iVE==-1)||(iVE>iE)){
return this._setErr(XMLP.ERR_ATT_VALUES);
}
strN=this.m_xml.substring(iNB,iNE+1);
strV=this.m_xml.substring(iVB+1,iVE);
if(strN.indexOf("<")!=-1){
return this._setErr(XMLP.ERR_ATT_LT_NAME);
}
if(strV.indexOf("<")!=-1){
return this._setErr(XMLP.ERR_ATT_LT_VALUE);
}
strV=SAXStrings.replace(strV,null,null,"\n"," ");
strV=SAXStrings.replace(strV,null,null,"\t"," ");
iRet=this._replaceEntities(strV);
if(iRet==XMLP._ERROR){
return iRet;
}
strV=this.m_cAlt;
if(this._findAttributeIndex(strN)==-1){
this._addAttribute(strN,strV);
}else{
return this._setErr(XMLP.ERR_ATT_DUP);
}
this.m_iP=iVE+2;
return XMLP._ATT;
};
XMLP.prototype._parseCDATA=function(iB){
var iE=this.m_xml.indexOf("]]>",iB);
if(iE==-1){
return this._setErr(XMLP.ERR_CLOSE_CDATA);
}
this._setContent(XMLP._CONT_XML,iB,iE);
this.m_iP=iE+3;
return XMLP._CDATA;
};
XMLP.prototype._parseComment=function(iB){
var iE=this.m_xml.indexOf("-"+"->",iB);
if(iE==-1){
return this._setErr(XMLP.ERR_CLOSE_COMMENT);
}
this._setContent(XMLP._CONT_XML,iB,iE);
this.m_iP=iE+3;
return XMLP._COMMENT;
};
XMLP.prototype._parseDTD=function(iB){
var iE,strClose,iInt,iLast;
iE=this.m_xml.indexOf(">",iB);
if(iE==-1){
return this._setErr(XMLP.ERR_CLOSE_DTD);
}
iInt=this.m_xml.indexOf("[",iB);
strClose=((iInt!=-1)&&(iInt<iE))?"]>":">";
while(true){
if(iE==iLast){
return this._setErr(XMLP.ERR_INFINITELOOP);
}
iLast=iE;
iE=this.m_xml.indexOf(strClose,iB);
if(iE==-1){
return this._setErr(XMLP.ERR_CLOSE_DTD);
}
if(this.m_xml.substring(iE-1,iE+2)!="]]>"){
break;
}
}
this.m_iP=iE+strClose.length;
return XMLP._DTD;
};
XMLP.prototype._parseElement=function(iB){
var iE,iDE,iNE,iRet;
var iType,strN,iLast;
iDE=iE=this.m_xml.indexOf(">",iB);
if(iE==-1){
return this._setErr(XMLP.ERR_CLOSE_ELM);
}
if(this.m_xml.charAt(iB)=="/"){
iType=XMLP._ELM_E;
iB++;
}else{
iType=XMLP._ELM_B;
}
if(this.m_xml.charAt(iE-1)=="/"){
if(iType==XMLP._ELM_E){
return this._setErr(XMLP.ERR_ELM_EMPTY);
}
iType=XMLP._ELM_EMP;
iDE--;
}
iDE=SAXStrings.lastIndexOfNonWhitespace(this.m_xml,iB,iDE);
if(iE-iB!=1){
if(SAXStrings.indexOfNonWhitespace(this.m_xml,iB,iDE)!=iB){
return this._setErr(XMLP.ERR_ELM_NAME);
}
}
this._clearAttributes();
iNE=SAXStrings.indexOfWhitespace(this.m_xml,iB,iDE);
if(iNE==-1){
iNE=iDE+1;
}else{
this.m_iP=iNE;
while(this.m_iP<iDE){
if(this.m_iP==iLast){
return this._setErr(XMLP.ERR_INFINITELOOP);
}
iLast=this.m_iP;
iRet=this._parseAttribute(this.m_iP,iDE);
if(iRet==XMLP._ERROR){
return iRet;
}
}
}
strN=this.m_xml.substring(iB,iNE);
if(strN.indexOf("<")!=-1){
return this._setErr(XMLP.ERR_ELM_LT_NAME);
}
this.m_name=strN;
this.m_iP=iE+1;
return iType;
};
XMLP.prototype._parseEntity=function(iB){
var iE=this.m_xml.indexOf(";",iB);
if(iE==-1){
return this._setErr(XMLP.ERR_CLOSE_ENTITY);
}
this.m_iP=iE+1;
return this._replaceEntity(this.m_xml,iB,iE);
};
XMLP.prototype._parsePI=function(iB){
var iE,iTB,iTE,iCB,iCE;
iE=this.m_xml.indexOf("?>",iB);
if(iE==-1){
return this._setErr(XMLP.ERR_CLOSE_PI);
}
iTB=SAXStrings.indexOfNonWhitespace(this.m_xml,iB,iE);
if(iTB==-1){
return this._setErr(XMLP.ERR_PI_TARGET);
}
iTE=SAXStrings.indexOfWhitespace(this.m_xml,iTB,iE);
if(iTE==-1){
iTE=iE;
}
iCB=SAXStrings.indexOfNonWhitespace(this.m_xml,iTE,iE);
if(iCB==-1){
iCB=iE;
}
iCE=SAXStrings.lastIndexOfNonWhitespace(this.m_xml,iCB,iE);
if(iCE==-1){
iCE=iE-1;
}
this.m_name=this.m_xml.substring(iTB,iTE);
this._setContent(XMLP._CONT_XML,iCB,iCE+1);
this.m_iP=iE+2;
return XMLP._PI;
};
XMLP.prototype._parseText=function(iB){
var iE,iEE;
iE=this.m_xml.indexOf("<",iB);
if(iE==-1){
iE=this.m_xml.length;
}
iEE=this.m_xml.indexOf("&",iB);
if((iEE!=-1)&&(iEE<=iE)){
iE=iEE;
}
this._setContent(XMLP._CONT_XML,iB,iE);
this.m_iP=iE;
return XMLP._TEXT;
};
XMLP.prototype._replaceEntities=function(strD,iB,iE){
if(SAXStrings.isEmpty(strD)){
return "";
}
iB=iB||0;
iE=iE||strD.length;
var iEB,iEE,strRet="";
iEB=strD.indexOf("&",iB);
iEE=iB;
while((iEB>0)&&(iEB<iE)){
strRet+=strD.substring(iEE,iEB);
iEE=strD.indexOf(";",iEB)+1;
if((iEE===0)||(iEE>iE)){
return this._setErr(XMLP.ERR_CLOSE_ENTITY);
}
iRet=this._replaceEntity(strD,iEB+1,iEE-1);
if(iRet==XMLP._ERROR){
return iRet;
}
strRet+=this.m_cAlt;
iEB=strD.indexOf("&",iEE);
}
if(iEE!=iE){
strRet+=strD.substring(iEE,iE);
}
this._setContent(XMLP._CONT_ALT,strRet);
return XMLP._ENTITY;
};
XMLP.prototype._replaceEntity=function(strD,iB,iE){
if(SAXStrings.isEmpty(strD)){
return -1;
}
iB=iB||0;
iE=iE||strD.length;
switch(strD.substring(iB,iE)){
case "amp":
strEnt="&";
break;
case "lt":
strEnt="<";
break;
case "gt":
strEnt=">";
break;
case "apos":
strEnt="'";
break;
case "quot":
strEnt="\"";
break;
default:
if(strD.charAt(iB)=="#"){
strEnt=String.fromCharCode(parseInt(strD.substring(iB+1,iE)));
}else{
return this._setErr(XMLP.ERR_ENTITY_UNKNOWN);
}
break;
}
this._setContent(XMLP._CONT_ALT,strEnt);
return XMLP._ENTITY;
};
XMLP.prototype._setContent=function(iSrc){
var args=arguments;
if(XMLP._CONT_XML==iSrc){
this.m_cAlt=null;
this.m_cB=args[1];
this.m_cE=args[2];
}else{
this.m_cAlt=args[1];
this.m_cB=0;
this.m_cE=args[1].length;
}
this.m_cSrc=iSrc;
};
XMLP.prototype._setErr=function(iErr){
var _3b3d=XMLP._errs[iErr];
this.m_cAlt=_3b3d;
this.m_cB=0;
this.m_cE=_3b3d.length;
this.m_cSrc=XMLP._CONT_ALT;
return XMLP._ERROR;
};
SAXDriver=function(){
this.m_hndDoc=null;
this.m_hndErr=null;
this.m_hndLex=null;
};
SAXDriver.DOC_B=1;
SAXDriver.DOC_E=2;
SAXDriver.ELM_B=3;
SAXDriver.ELM_E=4;
SAXDriver.CHARS=5;
SAXDriver.PI=6;
SAXDriver.CD_B=7;
SAXDriver.CD_E=8;
SAXDriver.CMNT=9;
SAXDriver.DTD_B=10;
SAXDriver.DTD_E=11;
SAXDriver.prototype.parse=function(strD){
var _3b3f=new XMLP(strD);
if(this.m_hndDoc&&this.m_hndDoc.setDocumentLocator){
this.m_hndDoc.setDocumentLocator(this);
}
this.m_parser=_3b3f;
this.m_bErr=false;
if(!this.m_bErr){
this._fireEvent(SAXDriver.DOC_B);
}
this._parseLoop();
if(!this.m_bErr){
this._fireEvent(SAXDriver.DOC_E);
}
this.m_xml=null;
this.m_iP=0;
};
SAXDriver.prototype.setDocumentHandler=function(hnd){
this.m_hndDoc=hnd;
};
SAXDriver.prototype.setErrorHandler=function(hnd){
this.m_hndErr=hnd;
};
SAXDriver.prototype.setLexicalHandler=function(hnd){
this.m_hndLex=hnd;
};
SAXDriver.prototype.getColumnNumber=function(){
return this.m_parser.getColumnNumber();
};
SAXDriver.prototype.getLineNumber=function(){
return this.m_parser.getLineNumber();
};
SAXDriver.prototype.getMessage=function(){
return this.m_strErrMsg;
};
SAXDriver.prototype.getPublicId=function(){
return null;
};
SAXDriver.prototype.getSystemId=function(){
return null;
};
SAXDriver.prototype.getLength=function(){
return this.m_parser.getAttributeCount();
};
SAXDriver.prototype.getName=function(index){
return this.m_parser.getAttributeName(index);
};
SAXDriver.prototype.getValue=function(index){
return this.m_parser.getAttributeValue(index);
};
SAXDriver.prototype.getValueByName=function(name){
return this.m_parser.getAttributeValueByName(name);
};
SAXDriver.prototype._fireError=function(_3b46){
this.m_strErrMsg=_3b46;
this.m_bErr=true;
if(this.m_hndErr&&this.m_hndErr.fatalError){
this.m_hndErr.fatalError(this);
}
};
SAXDriver.prototype._fireEvent=function(iEvt){
var hnd,func,args=arguments,iLen=args.length-1;
if(this.m_bErr){
return;
}
if(SAXDriver.DOC_B==iEvt){
func="startDocument";
hnd=this.m_hndDoc;
}else{
if(SAXDriver.DOC_E==iEvt){
func="endDocument";
hnd=this.m_hndDoc;
}else{
if(SAXDriver.ELM_B==iEvt){
func="startElement";
hnd=this.m_hndDoc;
}else{
if(SAXDriver.ELM_E==iEvt){
func="endElement";
hnd=this.m_hndDoc;
}else{
if(SAXDriver.CHARS==iEvt){
func="characters";
hnd=this.m_hndDoc;
}else{
if(SAXDriver.PI==iEvt){
func="processingInstruction";
hnd=this.m_hndDoc;
}else{
if(SAXDriver.CD_B==iEvt){
func="startCDATA";
hnd=this.m_hndLex;
}else{
if(SAXDriver.CD_E==iEvt){
func="endCDATA";
hnd=this.m_hndLex;
}else{
if(SAXDriver.CMNT==iEvt){
func="comment";
hnd=this.m_hndLex;
}
}
}
}
}
}
}
}
}
if(hnd&&hnd[func]){
if(0==iLen){
hnd[func]();
}else{
if(1==iLen){
hnd[func](args[1]);
}else{
if(2==iLen){
hnd[func](args[1],args[2]);
}else{
if(3==iLen){
hnd[func](args[1],args[2],args[3]);
}
}
}
}
}
};
SAXDriver.prototype._parseLoop=function(_3b49){
var _3b4a,_3b49;
_3b49=this.m_parser;
while(!this.m_bErr){
_3b4a=_3b49.next();
if(_3b4a==XMLP._ELM_B){
this._fireEvent(SAXDriver.ELM_B,_3b49.getName(),this);
}else{
if(_3b4a==XMLP._ELM_E){
this._fireEvent(SAXDriver.ELM_E,_3b49.getName());
}else{
if(_3b4a==XMLP._ELM_EMP){
this._fireEvent(SAXDriver.ELM_B,_3b49.getName(),this);
this._fireEvent(SAXDriver.ELM_E,_3b49.getName());
}else{
if(_3b4a==XMLP._TEXT){
this._fireEvent(SAXDriver.CHARS,_3b49.getContent(),_3b49.getContentBegin(),_3b49.getContentEnd()-_3b49.getContentBegin());
}else{
if(_3b4a==XMLP._ENTITY){
this._fireEvent(SAXDriver.CHARS,_3b49.getContent(),_3b49.getContentBegin(),_3b49.getContentEnd()-_3b49.getContentBegin());
}else{
if(_3b4a==XMLP._PI){
this._fireEvent(SAXDriver.PI,_3b49.getName(),_3b49.getContent().substring(_3b49.getContentBegin(),_3b49.getContentEnd()));
}else{
if(_3b4a==XMLP._CDATA){
this._fireEvent(SAXDriver.CD_B);
this._fireEvent(SAXDriver.CHARS,_3b49.getContent(),_3b49.getContentBegin(),_3b49.getContentEnd()-_3b49.getContentBegin());
this._fireEvent(SAXDriver.CD_E);
}else{
if(_3b4a==XMLP._COMMENT){
this._fireEvent(SAXDriver.CMNT,_3b49.getContent(),_3b49.getContentBegin(),_3b49.getContentEnd()-_3b49.getContentBegin());
}else{
if(_3b4a==XMLP._DTD){
}else{
if(_3b4a==XMLP._ERROR){
this._fireError(_3b49.getContent());
}else{
if(_3b4a==XMLP._NONE){
return;
}
}
}
}
}
}
}
}
}
}
}
}
};
SAXStrings=function(){
};
SAXStrings.WHITESPACE=" \t\n\r";
SAXStrings.QUOTES="\"'";
SAXStrings.getColumnNumber=function(strD,iP){
if(SAXStrings.isEmpty(strD)){
return -1;
}
iP=iP||strD.length;
var arrD=strD.substring(0,iP).split("\n");
var _3b4e=arrD[arrD.length-1];
arrD.length--;
var _3b4f=arrD.join("\n").length;
return iP-_3b4f;
};
SAXStrings.getLineNumber=function(strD,iP){
if(SAXStrings.isEmpty(strD)){
return -1;
}
iP=iP||strD.length;
return strD.substring(0,iP).split("\n").length;
};
SAXStrings.indexOfNonWhitespace=function(strD,iB,iE){
if(SAXStrings.isEmpty(strD)){
return -1;
}
iB=iB||0;
iE=iE||strD.length;
for(var i=iB;i<iE;i++){
if(SAXStrings.WHITESPACE.indexOf(strD.charAt(i))==-1){
return i;
}
}
return -1;
};
SAXStrings.indexOfWhitespace=function(strD,iB,iE){
if(SAXStrings.isEmpty(strD)){
return -1;
}
iB=iB||0;
iE=iE||strD.length;
for(var i=iB;i<iE;i++){
if(SAXStrings.WHITESPACE.indexOf(strD.charAt(i))!=-1){
return i;
}
}
return -1;
};
SAXStrings.isEmpty=function(strD){
return (strD===null)||(strD.length===0);
};
SAXStrings.lastIndexOfNonWhitespace=function(strD,iB,iE){
if(SAXStrings.isEmpty(strD)){
return -1;
}
iB=iB||0;
iE=iE||strD.length;
for(var i=iE-1;i>=iB;i--){
if(SAXStrings.WHITESPACE.indexOf(strD.charAt(i))==-1){
return i;
}
}
return -1;
};
SAXStrings.replace=function(strD,iB,iE,strF,strR){
if(SAXStrings.isEmpty(strD)){
return "";
}
iB=iB||0;
iE=iE||strD.length;
return strD.substring(iB,iE).split(strF).join(strR);
};
Stack=function(){
this.m_arr=[];
};
Stack.prototype.clear=function(){
this.m_arr=[];
};
Stack.prototype.count=function(){
return this.m_arr.length;
};
Stack.prototype.destroy=function(){
this.m_arr=null;
};
Stack.prototype.peek=function(){
if(this.m_arr.length===0){
return null;
}
return this.m_arr[this.m_arr.length-1];
};
Stack.prototype.pop=function(){
if(this.m_arr.length===0){
return null;
}
var o=this.m_arr[this.m_arr.length-1];
this.m_arr.length--;
return o;
};
Stack.prototype.push=function(o){
this.m_arr[this.m_arr.length]=o;
};
function isEmpty(str){
return (str===null)||(str.length==0);
}
function trim(_3b67,_3b68,_3b69){
if(isEmpty(_3b67)){
return "";
}
if(_3b68===null){
_3b68=true;
}
if(_3b69===null){
_3b69=true;
}
var left=0;
var right=0;
var i=0;
var k=0;
if(_3b68==true){
while((i<_3b67.length)&&(whitespace.indexOf(_3b67.charAt(i++))!=-1)){
left++;
}
}
if(_3b69==true){
k=_3b67.length-1;
while((k>=left)&&(whitespace.indexOf(_3b67.charAt(k--))!=-1)){
right++;
}
}
return _3b67.substring(left,_3b67.length-right);
}
function __escapeString(str){
var _3b6f=/&/g;
var _3b70=/</g;
var _3b71=/>/g;
var _3b72=/"/g;
var _3b73=/'/g;
str=str.replace(_3b6f,"&amp;");
str=str.replace(_3b70,"&lt;");
str=str.replace(_3b71,"&gt;");
str=str.replace(_3b72,"&quot;");
str=str.replace(_3b73,"&apos;");
return str;
}
function __unescapeString(str){
var _3b75=/&amp;/g;
var _3b76=/&lt;/g;
var _3b77=/&gt;/g;
var _3b78=/&quot;/g;
var _3b79=/&apos;/g;
str=str.replace(_3b75,"&");
str=str.replace(_3b76,"<");
str=str.replace(_3b77,">");
str=str.replace(_3b78,"\"");
str=str.replace(_3b79,"'");
return str;
}
function addClass(_4688,_4689){
if(_4688){
if(_4688.indexOf("|"+_4689+"|")<0){
_4688+=_4689+"|";
}
}else{
_4688="|"+_4689+"|";
}
return _4688;
}
DOMException=function(code){
this._class=addClass(this._class,"DOMException");
this.code=code;
};
DOMException.INDEX_SIZE_ERR=1;
DOMException.DOMSTRING_SIZE_ERR=2;
DOMException.HIERARCHY_REQUEST_ERR=3;
DOMException.WRONG_DOCUMENT_ERR=4;
DOMException.INVALID_CHARACTER_ERR=5;
DOMException.NO_DATA_ALLOWED_ERR=6;
DOMException.NO_MODIFICATION_ALLOWED_ERR=7;
DOMException.NOT_FOUND_ERR=8;
DOMException.NOT_SUPPORTED_ERR=9;
DOMException.INUSE_ATTRIBUTE_ERR=10;
DOMException.INVALID_STATE_ERR=11;
DOMException.SYNTAX_ERR=12;
DOMException.INVALID_MODIFICATION_ERR=13;
DOMException.NAMESPACE_ERR=14;
DOMException.INVALID_ACCESS_ERR=15;
DOMImplementation=function(){
this._class=addClass(this._class,"DOMImplementation");
this._p=null;
this.preserveWhiteSpace=false;
this.namespaceAware=true;
this.errorChecking=true;
};
DOMImplementation.prototype.escapeString=function DOMNode__escapeString(str){
return __escapeString(str);
};
DOMImplementation.prototype.unescapeString=function DOMNode__unescapeString(str){
return __unescapeString(str);
};
DOMImplementation.prototype.hasFeature=function DOMImplementation_hasFeature(_468d,_468e){
var ret=false;
if(_468d.toLowerCase()=="xml"){
ret=(!_468e||(_468e=="1.0")||(_468e=="2.0"));
}else{
if(_468d.toLowerCase()=="core"){
ret=(!_468e||(_468e=="2.0"));
}
}
return ret;
};
DOMImplementation.prototype.loadXML=function DOMImplementation_loadXML(_4690){
var _4691;
try{
_4691=new XMLP(_4690);
}
catch(e){
alert("Error Creating the SAX Parser. Did you include xmlsax.js or tinyxmlsax.js in your web page?\nThe SAX parser is needed to populate XML for <SCRIPT>'s W3C DOM Parser with data.");
}
var doc=new DOMDocument(this);
this._parseLoop(doc,_4691);
doc._parseComplete=true;
return doc;
};
DOMImplementation.prototype.translateErrCode=function DOMImplementation_translateErrCode(code){
var msg="";
switch(code){
case DOMException.INDEX_SIZE_ERR:
msg="INDEX_SIZE_ERR: Index out of bounds";
break;
case DOMException.DOMSTRING_SIZE_ERR:
msg="DOMSTRING_SIZE_ERR: The resulting string is too long to fit in a DOMString";
break;
case DOMException.HIERARCHY_REQUEST_ERR:
msg="HIERARCHY_REQUEST_ERR: The Node can not be inserted at this location";
break;
case DOMException.WRONG_DOCUMENT_ERR:
msg="WRONG_DOCUMENT_ERR: The source and the destination Documents are not the same";
break;
case DOMException.INVALID_CHARACTER_ERR:
msg="INVALID_CHARACTER_ERR: The string contains an invalid character";
break;
case DOMException.NO_DATA_ALLOWED_ERR:
msg="NO_DATA_ALLOWED_ERR: This Node / NodeList does not support data";
break;
case DOMException.NO_MODIFICATION_ALLOWED_ERR:
msg="NO_MODIFICATION_ALLOWED_ERR: This object cannot be modified";
break;
case DOMException.NOT_FOUND_ERR:
msg="NOT_FOUND_ERR: The item cannot be found";
break;
case DOMException.NOT_SUPPORTED_ERR:
msg="NOT_SUPPORTED_ERR: This implementation does not support function";
break;
case DOMException.INUSE_ATTRIBUTE_ERR:
msg="INUSE_ATTRIBUTE_ERR: The Attribute has already been assigned to another Element";
break;
case DOMException.INVALID_STATE_ERR:
msg="INVALID_STATE_ERR: The object is no longer usable";
break;
case DOMException.SYNTAX_ERR:
msg="SYNTAX_ERR: Syntax error";
break;
case DOMException.INVALID_MODIFICATION_ERR:
msg="INVALID_MODIFICATION_ERR: Cannot change the type of the object";
break;
case DOMException.NAMESPACE_ERR:
msg="NAMESPACE_ERR: The namespace declaration is incorrect";
break;
case DOMException.INVALID_ACCESS_ERR:
msg="INVALID_ACCESS_ERR: The object does not support this function";
break;
default:
msg="UNKNOWN: Unknown Exception Code ("+code+")";
}
return msg;
};
DOMImplementation.prototype._parseLoop=function DOMImplementation__parseLoop(doc,p){
var iEvt,iNode,iAttr,strName;
iNodeParent=doc;
var _4698=0;
var _4699=[];
var _469a=[];
if(this.namespaceAware){
var iNS=doc.createNamespace("");
iNS.setValue("http://www.w3.org/2000/xmlns/");
doc._namespaces.setNamedItem(iNS);
}
while(true){
iEvt=p.next();
if(iEvt==XMLP._ELM_B){
var pName=p.getName();
pName=trim(pName,true,true);
if(!this.namespaceAware){
iNode=doc.createElement(p.getName());
for(var i=0;i<p.getAttributeCount();i++){
strName=p.getAttributeName(i);
iAttr=iNode.getAttributeNode(strName);
if(!iAttr){
iAttr=doc.createAttribute(strName);
}
iAttr.setValue(p.getAttributeValue(i));
iNode.setAttributeNode(iAttr);
}
}else{
iNode=doc.createElementNS("",p.getName());
iNode._namespaces=iNodeParent._namespaces._cloneNodes(iNode);
for(var i=0;i<p.getAttributeCount();i++){
strName=p.getAttributeName(i);
if(this._isNamespaceDeclaration(strName)){
var _469e=this._parseNSName(strName);
if(strName!="xmlns"){
iNS=doc.createNamespace(strName);
}else{
iNS=doc.createNamespace("");
}
iNS.setValue(p.getAttributeValue(i));
iNode._namespaces.setNamedItem(iNS);
}else{
iAttr=iNode.getAttributeNode(strName);
if(!iAttr){
iAttr=doc.createAttributeNS("",strName);
}
iAttr.setValue(p.getAttributeValue(i));
iNode.setAttributeNodeNS(iAttr);
if(this._isIdDeclaration(strName)){
iNode.id=p.getAttributeValue(i);
}
}
}
if(iNode._namespaces.getNamedItem(iNode.prefix)){
iNode.namespaceURI=iNode._namespaces.getNamedItem(iNode.prefix).value;
}
for(var i=0;i<iNode.attributes.length;i++){
if(iNode.attributes.item(i).prefix!=""){
if(iNode._namespaces.getNamedItem(iNode.attributes.item(i).prefix)){
iNode.attributes.item(i).namespaceURI=iNode._namespaces.getNamedItem(iNode.attributes.item(i).prefix).value;
}
}
}
}
if(iNodeParent.nodeType==DOMNode.DOCUMENT_NODE){
iNodeParent.documentElement=iNode;
}
iNodeParent.appendChild(iNode);
iNodeParent=iNode;
}else{
if(iEvt==XMLP._ELM_E){
iNodeParent=iNodeParent.parentNode;
}else{
if(iEvt==XMLP._ELM_EMP){
pName=p.getName();
pName=trim(pName,true,true);
if(!this.namespaceAware){
iNode=doc.createElement(pName);
for(var i=0;i<p.getAttributeCount();i++){
strName=p.getAttributeName(i);
iAttr=iNode.getAttributeNode(strName);
if(!iAttr){
iAttr=doc.createAttribute(strName);
}
iAttr.setValue(p.getAttributeValue(i));
iNode.setAttributeNode(iAttr);
}
}else{
iNode=doc.createElementNS("",p.getName());
iNode._namespaces=iNodeParent._namespaces._cloneNodes(iNode);
for(var i=0;i<p.getAttributeCount();i++){
strName=p.getAttributeName(i);
if(this._isNamespaceDeclaration(strName)){
var _469e=this._parseNSName(strName);
if(strName!="xmlns"){
iNS=doc.createNamespace(strName);
}else{
iNS=doc.createNamespace("");
}
iNS.setValue(p.getAttributeValue(i));
iNode._namespaces.setNamedItem(iNS);
}else{
iAttr=iNode.getAttributeNode(strName);
if(!iAttr){
iAttr=doc.createAttributeNS("",strName);
}
iAttr.setValue(p.getAttributeValue(i));
iNode.setAttributeNodeNS(iAttr);
if(this._isIdDeclaration(strName)){
iNode.id=p.getAttributeValue(i);
}
}
}
if(iNode._namespaces.getNamedItem(iNode.prefix)){
iNode.namespaceURI=iNode._namespaces.getNamedItem(iNode.prefix).value;
}
for(var i=0;i<iNode.attributes.length;i++){
if(iNode.attributes.item(i).prefix!=""){
if(iNode._namespaces.getNamedItem(iNode.attributes.item(i).prefix)){
iNode.attributes.item(i).namespaceURI=iNode._namespaces.getNamedItem(iNode.attributes.item(i).prefix).value;
}
}
}
}
if(iNodeParent.nodeType==DOMNode.DOCUMENT_NODE){
iNodeParent.documentElement=iNode;
}
iNodeParent.appendChild(iNode);
}else{
if(iEvt==XMLP._TEXT||iEvt==XMLP._ENTITY){
var _469f=p.getContent().substring(p.getContentBegin(),p.getContentEnd());
if(!this.preserveWhiteSpace){
if(trim(_469f,true,true)==""){
_469f="";
}
}
if(_469f.length>0){
var _46a0=doc.createTextNode(_469f);
iNodeParent.appendChild(_46a0);
if(iEvt==XMLP._ENTITY){
_4699[_4699.length]=_46a0;
}else{
_469a[_469a.length]=_46a0;
}
}
}else{
if(iEvt==XMLP._PI){
iNodeParent.appendChild(doc.createProcessingInstruction(p.getName(),p.getContent().substring(p.getContentBegin(),p.getContentEnd())));
}else{
if(iEvt==XMLP._CDATA){
_469f=p.getContent().substring(p.getContentBegin(),p.getContentEnd());
if(!this.preserveWhiteSpace){
_469f=trim(_469f,true,true);
_469f.replace(/ +/g," ");
}
if(_469f.length>0){
iNodeParent.appendChild(doc.createCDATASection(_469f));
}
}else{
if(iEvt==XMLP._COMMENT){
var _469f=p.getContent().substring(p.getContentBegin(),p.getContentEnd());
if(!this.preserveWhiteSpace){
_469f=trim(_469f,true,true);
_469f.replace(/ +/g," ");
}
if(_469f.length>0){
iNodeParent.appendChild(doc.createComment(_469f));
}
}else{
if(iEvt==XMLP._DTD){
}else{
if(iEvt==XMLP._ERROR){
throw (new DOMException(DOMException.SYNTAX_ERR));
}else{
if(iEvt==XMLP._NONE){
if(iNodeParent==doc){
break;
}else{
throw (new DOMException(DOMException.SYNTAX_ERR));
}
}
}
}
}
}
}
}
}
}
}
}
var _46a1=_4699.length;
for(intLoop=0;intLoop<_46a1;intLoop++){
var _46a2=_4699[intLoop];
var _46a3=_46a2.getParentNode();
if(_46a3){
_46a3.normalize();
if(!this.preserveWhiteSpace){
var _46a4=_46a3.getChildNodes();
var _46a5=_46a4.getLength();
for(intLoop2=0;intLoop2<_46a5;intLoop2++){
var child=_46a4.item(intLoop2);
if(child.getNodeType()==DOMNode.TEXT_NODE){
var _46a7=child.getData();
_46a7=trim(_46a7,true,true);
_46a7.replace(/ +/g," ");
child.setData(_46a7);
}
}
}
}
}
if(!this.preserveWhiteSpace){
var _46a1=_469a.length;
for(intLoop=0;intLoop<_46a1;intLoop++){
var node=_469a[intLoop];
if(node.getParentNode()!==null){
var _46a9=node.getData();
_46a9=trim(_46a9,true,true);
_46a9.replace(/ +/g," ");
node.setData(_46a9);
}
}
}
};
DOMImplementation.prototype._isNamespaceDeclaration=function DOMImplementation__isNamespaceDeclaration(_46aa){
return (_46aa.indexOf("xmlns")>-1);
};
DOMImplementation.prototype._isIdDeclaration=function DOMImplementation__isIdDeclaration(_46ab){
return (_46ab.toLowerCase()=="id");
};
DOMImplementation.prototype._isValidName=function DOMImplementation__isValidName(name){
return name.match(re_validName);
};
re_validName=/^[a-zA-Z_:][a-zA-Z0-9\.\-_:]*$/;
DOMImplementation.prototype._isValidString=function DOMImplementation__isValidString(name){
return (name.search(re_invalidStringChars)<0);
};
re_invalidStringChars=/\x01|\x02|\x03|\x04|\x05|\x06|\x07|\x08|\x0B|\x0C|\x0E|\x0F|\x10|\x11|\x12|\x13|\x14|\x15|\x16|\x17|\x18|\x19|\x1A|\x1B|\x1C|\x1D|\x1E|\x1F|\x7F/;
DOMImplementation.prototype._parseNSName=function DOMImplementation__parseNSName(_46ae){
var _46af={};
_46af.prefix=_46ae;
_46af.namespaceName="";
delimPos=_46ae.indexOf(":");
if(delimPos>-1){
_46af.prefix=_46ae.substring(0,delimPos);
_46af.namespaceName=_46ae.substring(delimPos+1,_46ae.length);
}
return _46af;
};
DOMImplementation.prototype._parseQName=function DOMImplementation__parseQName(_46b0){
var _46b1={};
_46b1.localName=_46b0;
_46b1.prefix="";
delimPos=_46b0.indexOf(":");
if(delimPos>-1){
_46b1.prefix=_46b0.substring(0,delimPos);
_46b1.localName=_46b0.substring(delimPos+1,_46b0.length);
}
return _46b1;
};
DOMNodeList=function(_46b2,_46b3){
this._class=addClass(this._class,"DOMNodeList");
this._nodes=[];
this.length=0;
this.parentNode=_46b3;
this.ownerDocument=_46b2;
this._readonly=false;
};
DOMNodeList.prototype.getLength=function DOMNodeList_getLength(){
return this.length;
};
DOMNodeList.prototype.item=function DOMNodeList_item(index){
var ret=null;
if((index>=0)&&(index<this._nodes.length)){
ret=this._nodes[index];
}
return ret;
};
DOMNodeList.prototype._findItemIndex=function DOMNodeList__findItemIndex(id){
var ret=-1;
if(id>-1){
for(var i=0;i<this._nodes.length;i++){
if(this._nodes[i]._id==id){
ret=i;
break;
}
}
}
return ret;
};
DOMNodeList.prototype._insertBefore=function DOMNodeList__insertBefore(_46b9,_46ba){
if((_46ba>=0)&&(_46ba<this._nodes.length)){
var _46bb=[];
_46bb=this._nodes.slice(0,_46ba);
if(_46b9.nodeType==DOMNode.DOCUMENT_FRAGMENT_NODE){
_46bb=_46bb.concat(_46b9.childNodes._nodes);
}else{
_46bb[_46bb.length]=_46b9;
}
this._nodes=_46bb.concat(this._nodes.slice(_46ba));
this.length=this._nodes.length;
}
};
DOMNodeList.prototype._replaceChild=function DOMNodeList__replaceChild(_46bc,_46bd){
var ret=null;
if((_46bd>=0)&&(_46bd<this._nodes.length)){
ret=this._nodes[_46bd];
if(_46bc.nodeType==DOMNode.DOCUMENT_FRAGMENT_NODE){
var _46bf=[];
_46bf=this._nodes.slice(0,_46bd);
_46bf=_46bf.concat(_46bc.childNodes._nodes);
this._nodes=_46bf.concat(this._nodes.slice(_46bd+1));
}else{
this._nodes[_46bd]=_46bc;
}
}
return ret;
};
DOMNodeList.prototype._removeChild=function DOMNodeList__removeChild(_46c0){
var ret=null;
if(_46c0>-1){
ret=this._nodes[_46c0];
var _46c2=[];
_46c2=this._nodes.slice(0,_46c0);
this._nodes=_46c2.concat(this._nodes.slice(_46c0+1));
this.length=this._nodes.length;
}
return ret;
};
DOMNodeList.prototype._appendChild=function DOMNodeList__appendChild(_46c3){
if(_46c3.nodeType==DOMNode.DOCUMENT_FRAGMENT_NODE){
this._nodes=this._nodes.concat(_46c3.childNodes._nodes);
}else{
this._nodes[this._nodes.length]=_46c3;
}
this.length=this._nodes.length;
};
DOMNodeList.prototype._cloneNodes=function DOMNodeList__cloneNodes(deep,_46c5){
var _46c6=new DOMNodeList(this.ownerDocument,_46c5);
for(var i=0;i<this._nodes.length;i++){
_46c6._appendChild(this._nodes[i].cloneNode(deep));
}
return _46c6;
};
DOMNodeList.prototype.toString=function DOMNodeList_toString(){
var ret="";
for(var i=0;i<this.length;i++){
ret+=this._nodes[i].toString();
}
return ret;
};
DOMNamedNodeMap=function(_46ca,_46cb){
this._class=addClass(this._class,"DOMNamedNodeMap");
this.DOMNodeList=DOMNodeList;
this.DOMNodeList(_46ca,_46cb);
};
DOMNamedNodeMap.prototype=new DOMNodeList;
DOMNamedNodeMap.prototype.getNamedItem=function DOMNamedNodeMap_getNamedItem(name){
var ret=null;
var _46ce=this._findNamedItemIndex(name);
if(_46ce>-1){
ret=this._nodes[_46ce];
}
return ret;
};
DOMNamedNodeMap.prototype.setNamedItem=function DOMNamedNodeMap_setNamedItem(arg){
if(this.ownerDocument.implementation.errorChecking){
if(this.ownerDocument!=arg.ownerDocument){
throw (new DOMException(DOMException.WRONG_DOCUMENT_ERR));
}
if(this._readonly||(this.parentNode&&this.parentNode._readonly)){
throw (new DOMException(DOMException.NO_MODIFICATION_ALLOWED_ERR));
}
if(arg.ownerElement&&(arg.ownerElement!=this.parentNode)){
throw (new DOMException(DOMException.INUSE_ATTRIBUTE_ERR));
}
}
var _46d0=this._findNamedItemIndex(arg.name);
var ret=null;
if(_46d0>-1){
ret=this._nodes[_46d0];
if(this.ownerDocument.implementation.errorChecking&&ret._readonly){
throw (new DOMException(DOMException.NO_MODIFICATION_ALLOWED_ERR));
}else{
this._nodes[_46d0]=arg;
}
}else{
this._nodes[this.length]=arg;
}
this.length=this._nodes.length;
arg.ownerElement=this.parentNode;
return ret;
};
DOMNamedNodeMap.prototype.removeNamedItem=function DOMNamedNodeMap_removeNamedItem(name){
var ret=null;
if(this.ownerDocument.implementation.errorChecking&&(this._readonly||(this.parentNode&&this.parentNode._readonly))){
throw (new DOMException(DOMException.NO_MODIFICATION_ALLOWED_ERR));
}
var _46d4=this._findNamedItemIndex(name);
if(this.ownerDocument.implementation.errorChecking&&(_46d4<0)){
throw (new DOMException(DOMException.NOT_FOUND_ERR));
}
var _46d5=this._nodes[_46d4];
if(this.ownerDocument.implementation.errorChecking&&_46d5._readonly){
throw (new DOMException(DOMException.NO_MODIFICATION_ALLOWED_ERR));
}
return this._removeChild(_46d4);
};
DOMNamedNodeMap.prototype.getNamedItemNS=function DOMNamedNodeMap_getNamedItemNS(_46d6,_46d7){
var ret=null;
var _46d9=this._findNamedItemNSIndex(_46d6,_46d7);
if(_46d9>-1){
ret=this._nodes[_46d9];
}
return ret;
};
DOMNamedNodeMap.prototype.setNamedItemNS=function DOMNamedNodeMap_setNamedItemNS(arg){
if(this.ownerDocument.implementation.errorChecking){
if(this._readonly||(this.parentNode&&this.parentNode._readonly)){
throw (new DOMException(DOMException.NO_MODIFICATION_ALLOWED_ERR));
}
if(this.ownerDocument!=arg.ownerDocument){
throw (new DOMException(DOMException.WRONG_DOCUMENT_ERR));
}
if(arg.ownerElement&&(arg.ownerElement!=this.parentNode)){
throw (new DOMException(DOMException.INUSE_ATTRIBUTE_ERR));
}
}
var _46db=this._findNamedItemNSIndex(arg.namespaceURI,arg.localName);
var ret=null;
if(_46db>-1){
ret=this._nodes[_46db];
if(this.ownerDocument.implementation.errorChecking&&ret._readonly){
throw (new DOMException(DOMException.NO_MODIFICATION_ALLOWED_ERR));
}else{
this._nodes[_46db]=arg;
}
}else{
this._nodes[this.length]=arg;
}
this.length=this._nodes.length;
arg.ownerElement=this.parentNode;
return ret;
};
DOMNamedNodeMap.prototype.removeNamedItemNS=function DOMNamedNodeMap_removeNamedItemNS(_46dd,_46de){
var ret=null;
if(this.ownerDocument.implementation.errorChecking&&(this._readonly||(this.parentNode&&this.parentNode._readonly))){
throw (new DOMException(DOMException.NO_MODIFICATION_ALLOWED_ERR));
}
var _46e0=this._findNamedItemNSIndex(_46dd,_46de);
if(this.ownerDocument.implementation.errorChecking&&(_46e0<0)){
throw (new DOMException(DOMException.NOT_FOUND_ERR));
}
var _46e1=this._nodes[_46e0];
if(this.ownerDocument.implementation.errorChecking&&_46e1._readonly){
throw (new DOMException(DOMException.NO_MODIFICATION_ALLOWED_ERR));
}
return this._removeChild(_46e0);
};
DOMNamedNodeMap.prototype._findNamedItemIndex=function DOMNamedNodeMap__findNamedItemIndex(name){
var ret=-1;
for(var i=0;i<this._nodes.length;i++){
if(this._nodes[i].name==name){
ret=i;
break;
}
}
return ret;
};
DOMNamedNodeMap.prototype._findNamedItemNSIndex=function DOMNamedNodeMap__findNamedItemNSIndex(_46e5,_46e6){
var ret=-1;
if(_46e6){
for(var i=0;i<this._nodes.length;i++){
if((this._nodes[i].namespaceURI==_46e5)&&(this._nodes[i].localName==_46e6)){
ret=i;
break;
}
}
}
return ret;
};
DOMNamedNodeMap.prototype._hasAttribute=function DOMNamedNodeMap__hasAttribute(name){
var ret=false;
var _46eb=this._findNamedItemIndex(name);
if(_46eb>-1){
ret=true;
}
return ret;
};
DOMNamedNodeMap.prototype._hasAttributeNS=function DOMNamedNodeMap__hasAttributeNS(_46ec,_46ed){
var ret=false;
var _46ef=this._findNamedItemNSIndex(_46ec,_46ed);
if(_46ef>-1){
ret=true;
}
return ret;
};
DOMNamedNodeMap.prototype._cloneNodes=function DOMNamedNodeMap__cloneNodes(_46f0){
var _46f1=new DOMNamedNodeMap(this.ownerDocument,_46f0);
for(var i=0;i<this._nodes.length;i++){
_46f1._appendChild(this._nodes[i].cloneNode(false));
}
return _46f1;
};
DOMNamedNodeMap.prototype.toString=function DOMNamedNodeMap_toString(){
var ret="";
for(var i=0;i<this.length-1;i++){
ret+=this._nodes[i].toString()+" ";
}
if(this.length>0){
ret+=this._nodes[this.length-1].toString();
}
return ret;
};
DOMNamespaceNodeMap=function(_46f5,_46f6){
this._class=addClass(this._class,"DOMNamespaceNodeMap");
this.DOMNamedNodeMap=DOMNamedNodeMap;
this.DOMNamedNodeMap(_46f5,_46f6);
};
DOMNamespaceNodeMap.prototype=new DOMNamedNodeMap;
DOMNamespaceNodeMap.prototype._findNamedItemIndex=function DOMNamespaceNodeMap__findNamedItemIndex(_46f7){
var ret=-1;
for(var i=0;i<this._nodes.length;i++){
if(this._nodes[i].localName==_46f7){
ret=i;
break;
}
}
return ret;
};
DOMNamespaceNodeMap.prototype._cloneNodes=function DOMNamespaceNodeMap__cloneNodes(_46fa){
var _46fb=new DOMNamespaceNodeMap(this.ownerDocument,_46fa);
for(var i=0;i<this._nodes.length;i++){
_46fb._appendChild(this._nodes[i].cloneNode(false));
}
return _46fb;
};
DOMNamespaceNodeMap.prototype.toString=function DOMNamespaceNodeMap_toString(){
var ret="";
for(var ind=0;ind<this._nodes.length;ind++){
var ns=null;
try{
var ns=this.parentNode.parentNode._namespaces.getNamedItem(this._nodes[ind].localName);
}
catch(e){
break;
}
if(!(ns&&(""+ns.nodeValue==""+this._nodes[ind].nodeValue))){
ret+=this._nodes[ind].toString()+" ";
}
}
return ret;
};
DOMNode=function(_4700){
this._class=addClass(this._class,"DOMNode");
if(_4700){
this._id=_4700._genId();
}
this.namespaceURI="";
this.prefix="";
this.localName="";
this.nodeName="";
this.nodeValue="";
this.nodeType=0;
this.parentNode=null;
this.childNodes=new DOMNodeList(_4700,this);
this.firstChild=null;
this.lastChild=null;
this.previousSibling=null;
this.nextSibling=null;
this.attributes=new DOMNamedNodeMap(_4700,this);
this.ownerDocument=_4700;
this._namespaces=new DOMNamespaceNodeMap(_4700,this);
this._readonly=false;
};
DOMNode.ELEMENT_NODE=1;
DOMNode.ATTRIBUTE_NODE=2;
DOMNode.TEXT_NODE=3;
DOMNode.CDATA_SECTION_NODE=4;
DOMNode.ENTITY_REFERENCE_NODE=5;
DOMNode.ENTITY_NODE=6;
DOMNode.PROCESSING_INSTRUCTION_NODE=7;
DOMNode.COMMENT_NODE=8;
DOMNode.DOCUMENT_NODE=9;
DOMNode.DOCUMENT_TYPE_NODE=10;
DOMNode.DOCUMENT_FRAGMENT_NODE=11;
DOMNode.NOTATION_NODE=12;
DOMNode.NAMESPACE_NODE=13;
DOMNode.prototype.hasAttributes=function DOMNode_hasAttributes(){
if(this.attributes.length===0){
return false;
}else{
return true;
}
};
DOMNode.prototype.getNodeName=function DOMNode_getNodeName(){
return this.nodeName;
};
DOMNode.prototype.getNodeValue=function DOMNode_getNodeValue(){
return this.nodeValue;
};
DOMNode.prototype.setNodeValue=function DOMNode_setNodeValue(_4701){
if(this.ownerDocument.implementation.errorChecking&&this._readonly){
throw (new DOMException(DOMException.NO_MODIFICATION_ALLOWED_ERR));
}
this.nodeValue=_4701;
};
DOMNode.prototype.getNodeType=function DOMNode_getNodeType(){
return this.nodeType;
};
DOMNode.prototype.getParentNode=function DOMNode_getParentNode(){
return this.parentNode;
};
DOMNode.prototype.getChildNodes=function DOMNode_getChildNodes(){
return this.childNodes;
};
DOMNode.prototype.getFirstChild=function DOMNode_getFirstChild(){
return this.firstChild;
};
DOMNode.prototype.getLastChild=function DOMNode_getLastChild(){
return this.lastChild;
};
DOMNode.prototype.getPreviousSibling=function DOMNode_getPreviousSibling(){
return this.previousSibling;
};
DOMNode.prototype.getNextSibling=function DOMNode_getNextSibling(){
return this.nextSibling;
};
DOMNode.prototype.getAttributes=function DOMNode_getAttributes(){
return this.attributes;
};
DOMNode.prototype.getOwnerDocument=function DOMNode_getOwnerDocument(){
return this.ownerDocument;
};
DOMNode.prototype.getNamespaceURI=function DOMNode_getNamespaceURI(){
return this.namespaceURI;
};
DOMNode.prototype.getPrefix=function DOMNode_getPrefix(){
return this.prefix;
};
DOMNode.prototype.setPrefix=function DOMNode_setPrefix(_4702){
if(this.ownerDocument.implementation.errorChecking){
if(this._readonly){
throw (new DOMException(DOMException.NO_MODIFICATION_ALLOWED_ERR));
}
if(!this.ownerDocument.implementation._isValidName(_4702)){
throw (new DOMException(DOMException.INVALID_CHARACTER_ERR));
}
if(!this.ownerDocument._isValidNamespace(this.namespaceURI,_4702+":"+this.localName)){
throw (new DOMException(DOMException.NAMESPACE_ERR));
}
if((_4702=="xmlns")&&(this.namespaceURI!="http://www.w3.org/2000/xmlns/")){
throw (new DOMException(DOMException.NAMESPACE_ERR));
}
if((_4702=="")&&(this.localName=="xmlns")){
throw (new DOMException(DOMException.NAMESPACE_ERR));
}
}
this.prefix=_4702;
if(this.prefix!=""){
this.nodeName=this.prefix+":"+this.localName;
}else{
this.nodeName=this.localName;
}
};
DOMNode.prototype.getLocalName=function DOMNode_getLocalName(){
return this.localName;
};
DOMNode.prototype.insertBefore=function DOMNode_insertBefore(_4703,_4704){
var _4705;
if(this.ownerDocument.implementation.errorChecking){
if(this._readonly){
throw (new DOMException(DOMException.NO_MODIFICATION_ALLOWED_ERR));
}
if(this.ownerDocument!=_4703.ownerDocument){
throw (new DOMException(DOMException.WRONG_DOCUMENT_ERR));
}
if(this._isAncestor(_4703)){
throw (new DOMException(DOMException.HIERARCHY_REQUEST_ERR));
}
}
if(_4704){
var _4706=this.childNodes._findItemIndex(_4704._id);
if(this.ownerDocument.implementation.errorChecking&&(_4706<0)){
throw (new DOMException(DOMException.NOT_FOUND_ERR));
}
var _4707=_4703.parentNode;
if(_4707){
_4707.removeChild(_4703);
}
this.childNodes._insertBefore(_4703,this.childNodes._findItemIndex(_4704._id));
_4705=_4704.previousSibling;
if(_4703.nodeType==DOMNode.DOCUMENT_FRAGMENT_NODE){
if(_4703.childNodes._nodes.length>0){
for(var ind=0;ind<_4703.childNodes._nodes.length;ind++){
_4703.childNodes._nodes[ind].parentNode=this;
}
_4704.previousSibling=_4703.childNodes._nodes[_4703.childNodes._nodes.length-1];
}
}else{
_4703.parentNode=this;
_4704.previousSibling=_4703;
}
}else{
_4705=this.lastChild;
this.appendChild(_4703);
}
if(_4703.nodeType==DOMNode.DOCUMENT_FRAGMENT_NODE){
if(_4703.childNodes._nodes.length>0){
if(_4705){
_4705.nextSibling=_4703.childNodes._nodes[0];
}else{
this.firstChild=_4703.childNodes._nodes[0];
}
_4703.childNodes._nodes[0].previousSibling=_4705;
_4703.childNodes._nodes[_4703.childNodes._nodes.length-1].nextSibling=_4704;
}
}else{
if(_4705){
_4705.nextSibling=_4703;
}else{
this.firstChild=_4703;
}
_4703.previousSibling=_4705;
_4703.nextSibling=_4704;
}
return _4703;
};
DOMNode.prototype.replaceChild=function DOMNode_replaceChild(_4709,_470a){
var ret=null;
if(this.ownerDocument.implementation.errorChecking){
if(this._readonly){
throw (new DOMException(DOMException.NO_MODIFICATION_ALLOWED_ERR));
}
if(this.ownerDocument!=_4709.ownerDocument){
throw (new DOMException(DOMException.WRONG_DOCUMENT_ERR));
}
if(this._isAncestor(_4709)){
throw (new DOMException(DOMException.HIERARCHY_REQUEST_ERR));
}
}
var index=this.childNodes._findItemIndex(_470a._id);
if(this.ownerDocument.implementation.errorChecking&&(index<0)){
throw (new DOMException(DOMException.NOT_FOUND_ERR));
}
var _470d=_4709.parentNode;
if(_470d){
_470d.removeChild(_4709);
}
ret=this.childNodes._replaceChild(_4709,index);
if(_4709.nodeType==DOMNode.DOCUMENT_FRAGMENT_NODE){
if(_4709.childNodes._nodes.length>0){
for(var ind=0;ind<_4709.childNodes._nodes.length;ind++){
_4709.childNodes._nodes[ind].parentNode=this;
}
if(_470a.previousSibling){
_470a.previousSibling.nextSibling=_4709.childNodes._nodes[0];
}else{
this.firstChild=_4709.childNodes._nodes[0];
}
if(_470a.nextSibling){
_470a.nextSibling.previousSibling=_4709;
}else{
this.lastChild=_4709.childNodes._nodes[_4709.childNodes._nodes.length-1];
}
_4709.childNodes._nodes[0].previousSibling=_470a.previousSibling;
_4709.childNodes._nodes[_4709.childNodes._nodes.length-1].nextSibling=_470a.nextSibling;
}
}else{
_4709.parentNode=this;
if(_470a.previousSibling){
_470a.previousSibling.nextSibling=_4709;
}else{
this.firstChild=_4709;
}
if(_470a.nextSibling){
_470a.nextSibling.previousSibling=_4709;
}else{
this.lastChild=_4709;
}
_4709.previousSibling=_470a.previousSibling;
_4709.nextSibling=_470a.nextSibling;
}
return ret;
};
DOMNode.prototype.removeChild=function DOMNode_removeChild(_470f){
if(this.ownerDocument.implementation.errorChecking&&(this._readonly||_470f._readonly)){
throw (new DOMException(DOMException.NO_MODIFICATION_ALLOWED_ERR));
}
var _4710=this.childNodes._findItemIndex(_470f._id);
if(this.ownerDocument.implementation.errorChecking&&(_4710<0)){
throw (new DOMException(DOMException.NOT_FOUND_ERR));
}
this.childNodes._removeChild(_4710);
_470f.parentNode=null;
if(_470f.previousSibling){
_470f.previousSibling.nextSibling=_470f.nextSibling;
}else{
this.firstChild=_470f.nextSibling;
}
if(_470f.nextSibling){
_470f.nextSibling.previousSibling=_470f.previousSibling;
}else{
this.lastChild=_470f.previousSibling;
}
_470f.previousSibling=null;
_470f.nextSibling=null;
return _470f;
};
DOMNode.prototype.appendChild=function DOMNode_appendChild(_4711){
if(this.ownerDocument.implementation.errorChecking){
if(this._readonly){
throw (new DOMException(DOMException.NO_MODIFICATION_ALLOWED_ERR));
}
if(this.ownerDocument!=_4711.ownerDocument){
throw (new DOMException(DOMException.WRONG_DOCUMENT_ERR));
}
if(this._isAncestor(_4711)){
throw (new DOMException(DOMException.HIERARCHY_REQUEST_ERR));
}
}
var _4712=_4711.parentNode;
if(_4712){
_4712.removeChild(_4711);
}
this.childNodes._appendChild(_4711);
if(_4711.nodeType==DOMNode.DOCUMENT_FRAGMENT_NODE){
if(_4711.childNodes._nodes.length>0){
for(var ind=0;ind<_4711.childNodes._nodes.length;ind++){
_4711.childNodes._nodes[ind].parentNode=this;
}
if(this.lastChild){
this.lastChild.nextSibling=_4711.childNodes._nodes[0];
_4711.childNodes._nodes[0].previousSibling=this.lastChild;
this.lastChild=_4711.childNodes._nodes[_4711.childNodes._nodes.length-1];
}else{
this.lastChild=_4711.childNodes._nodes[_4711.childNodes._nodes.length-1];
this.firstChild=_4711.childNodes._nodes[0];
}
}
}else{
_4711.parentNode=this;
if(this.lastChild){
this.lastChild.nextSibling=_4711;
_4711.previousSibling=this.lastChild;
this.lastChild=_4711;
}else{
this.lastChild=_4711;
this.firstChild=_4711;
}
}
return _4711;
};
DOMNode.prototype.hasChildNodes=function DOMNode_hasChildNodes(){
return (this.childNodes.length>0);
};
DOMNode.prototype.cloneNode=function DOMNode_cloneNode(deep){
try{
return this.ownerDocument.importNode(this,deep);
}
catch(e){
return null;
}
};
DOMNode.prototype.normalize=function DOMNode_normalize(){
var inode;
var _4716=new DOMNodeList();
if(this.nodeType==DOMNode.ELEMENT_NODE||this.nodeType==DOMNode.DOCUMENT_NODE){
var _4717=null;
for(var i=0;i<this.childNodes.length;i++){
inode=this.childNodes.item(i);
if(inode.nodeType==DOMNode.TEXT_NODE){
if(inode.length<1){
_4716._appendChild(inode);
}else{
if(_4717){
_4717.appendData(inode.data);
_4716._appendChild(inode);
}else{
_4717=inode;
}
}
}else{
_4717=null;
inode.normalize();
}
}
for(var i=0;i<_4716.length;i++){
inode=_4716.item(i);
inode.parentNode.removeChild(inode);
}
}
};
DOMNode.prototype.isSupported=function DOMNode_isSupported(_4719,_471a){
return this.ownerDocument.implementation.hasFeature(_4719,_471a);
};
DOMNode.prototype.getElementsByTagName=function DOMNode_getElementsByTagName(_471b){
return this._getElementsByTagNameRecursive(_471b,new DOMNodeList(this.ownerDocument));
};
DOMNode.prototype._getElementsByTagNameRecursive=function DOMNode__getElementsByTagNameRecursive(_471c,_471d){
if(this.nodeType==DOMNode.ELEMENT_NODE||this.nodeType==DOMNode.DOCUMENT_NODE){
if((this.nodeName==_471c)||(_471c=="*")){
_471d._appendChild(this);
}
for(var i=0;i<this.childNodes.length;i++){
_471d=this.childNodes.item(i)._getElementsByTagNameRecursive(_471c,_471d);
}
}
return _471d;
};
DOMNode.prototype.getXML=function DOMNode_getXML(){
return this.toString();
};
DOMNode.prototype.getElementsByTagNameNS=function DOMNode_getElementsByTagNameNS(_471f,_4720){
return this._getElementsByTagNameNSRecursive(_471f,_4720,new DOMNodeList(this.ownerDocument));
};
DOMNode.prototype._getElementsByTagNameNSRecursive=function DOMNode__getElementsByTagNameNSRecursive(_4721,_4722,_4723){
if(this.nodeType==DOMNode.ELEMENT_NODE||this.nodeType==DOMNode.DOCUMENT_NODE){
if(((this.namespaceURI==_4721)||(_4721=="*"))&&((this.localName==_4722)||(_4722=="*"))){
_4723._appendChild(this);
}
for(var i=0;i<this.childNodes.length;i++){
_4723=this.childNodes.item(i)._getElementsByTagNameNSRecursive(_4721,_4722,_4723);
}
}
return _4723;
};
DOMNode.prototype._isAncestor=function DOMNode__isAncestor(node){
return ((this==node)||((this.parentNode)&&(this.parentNode._isAncestor(node))));
};
DOMNode.prototype.importNode=function DOMNode_importNode(_4726,deep){
var _4728;
this.getOwnerDocument()._performingImportNodeOperation=true;
try{
if(_4726.nodeType==DOMNode.ELEMENT_NODE){
if(!this.ownerDocument.implementation.namespaceAware){
_4728=this.ownerDocument.createElement(_4726.tagName);
for(var i=0;i<_4726.attributes.length;i++){
_4728.setAttribute(_4726.attributes.item(i).name,_4726.attributes.item(i).value);
}
}else{
_4728=this.ownerDocument.createElementNS(_4726.namespaceURI,_4726.nodeName);
for(var i=0;i<_4726.attributes.length;i++){
_4728.setAttributeNS(_4726.attributes.item(i).namespaceURI,_4726.attributes.item(i).name,_4726.attributes.item(i).value);
}
for(var i=0;i<_4726._namespaces.length;i++){
_4728._namespaces._nodes[i]=this.ownerDocument.createNamespace(_4726._namespaces.item(i).localName);
_4728._namespaces._nodes[i].setValue(_4726._namespaces.item(i).value);
}
}
}else{
if(_4726.nodeType==DOMNode.ATTRIBUTE_NODE){
if(!this.ownerDocument.implementation.namespaceAware){
_4728=this.ownerDocument.createAttribute(_4726.name);
}else{
_4728=this.ownerDocument.createAttributeNS(_4726.namespaceURI,_4726.nodeName);
for(var i=0;i<_4726._namespaces.length;i++){
_4728._namespaces._nodes[i]=this.ownerDocument.createNamespace(_4726._namespaces.item(i).localName);
_4728._namespaces._nodes[i].setValue(_4726._namespaces.item(i).value);
}
}
_4728.setValue(_4726.value);
}else{
if(_4726.nodeType==DOMNode.DOCUMENT_FRAGMENT){
_4728=this.ownerDocument.createDocumentFragment();
}else{
if(_4726.nodeType==DOMNode.NAMESPACE_NODE){
_4728=this.ownerDocument.createNamespace(_4726.nodeName);
_4728.setValue(_4726.value);
}else{
if(_4726.nodeType==DOMNode.TEXT_NODE){
_4728=this.ownerDocument.createTextNode(_4726.data);
}else{
if(_4726.nodeType==DOMNode.CDATA_SECTION_NODE){
_4728=this.ownerDocument.createCDATASection(_4726.data);
}else{
if(_4726.nodeType==DOMNode.PROCESSING_INSTRUCTION_NODE){
_4728=this.ownerDocument.createProcessingInstruction(_4726.target,_4726.data);
}else{
if(_4726.nodeType==DOMNode.COMMENT_NODE){
_4728=this.ownerDocument.createComment(_4726.data);
}else{
throw (new DOMException(DOMException.NOT_SUPPORTED_ERR));
}
}
}
}
}
}
}
}
if(deep){
for(var i=0;i<_4726.childNodes.length;i++){
_4728.appendChild(this.ownerDocument.importNode(_4726.childNodes.item(i),true));
}
}
this.getOwnerDocument()._performingImportNodeOperation=false;
return _4728;
}
catch(eAny){
this.getOwnerDocument()._performingImportNodeOperation=false;
throw eAny;
}
};
DOMNode.prototype.__escapeString=function DOMNode__escapeString(str){
return __escapeString(str);
};
DOMNode.prototype.__unescapeString=function DOMNode__unescapeString(str){
return __unescapeString(str);
};
DOMDocument=function(_472c){
this._class=addClass(this._class,"DOMDocument");
this.DOMNode=DOMNode;
this.DOMNode(this);
this.doctype=null;
this.implementation=_472c;
this.documentElement=null;
this.all=[];
this.nodeName="#document";
this.nodeType=DOMNode.DOCUMENT_NODE;
this._id=0;
this._lastId=0;
this._parseComplete=false;
this.ownerDocument=this;
this._performingImportNodeOperation=false;
};
DOMDocument.prototype=new DOMNode;
DOMDocument.prototype.getDoctype=function DOMDocument_getDoctype(){
return this.doctype;
};
DOMDocument.prototype.getImplementation=function DOMDocument_implementation(){
return this.implementation;
};
DOMDocument.prototype.getDocumentElement=function DOMDocument_getDocumentElement(){
return this.documentElement;
};
DOMDocument.prototype.createElement=function DOMDocument_createElement(_472d){
if(this.ownerDocument.implementation.errorChecking&&(!this.ownerDocument.implementation._isValidName(_472d))){
throw (new DOMException(DOMException.INVALID_CHARACTER_ERR));
}
var node=new DOMElement(this);
node.tagName=_472d;
node.nodeName=_472d;
this.all[this.all.length]=node;
return node;
};
DOMDocument.prototype.createDocumentFragment=function DOMDocument_createDocumentFragment(){
var node=new DOMDocumentFragment(this);
return node;
};
DOMDocument.prototype.createTextNode=function DOMDocument_createTextNode(data){
var node=new DOMText(this);
node.data=data;
node.nodeValue=data;
node.length=data.length;
return node;
};
DOMDocument.prototype.createComment=function DOMDocument_createComment(data){
var node=new DOMComment(this);
node.data=data;
node.nodeValue=data;
node.length=data.length;
return node;
};
DOMDocument.prototype.createCDATASection=function DOMDocument_createCDATASection(data){
var node=new DOMCDATASection(this);
node.data=data;
node.nodeValue=data;
node.length=data.length;
return node;
};
DOMDocument.prototype.createProcessingInstruction=function DOMDocument_createProcessingInstruction(_4736,data){
if(this.ownerDocument.implementation.errorChecking&&(!this.implementation._isValidName(_4736))){
throw (new DOMException(DOMException.INVALID_CHARACTER_ERR));
}
var node=new DOMProcessingInstruction(this);
node.target=_4736;
node.nodeName=_4736;
node.data=data;
node.nodeValue=data;
node.length=data.length;
return node;
};
DOMDocument.prototype.createAttribute=function DOMDocument_createAttribute(name){
if(this.ownerDocument.implementation.errorChecking&&(!this.ownerDocument.implementation._isValidName(name))){
throw (new DOMException(DOMException.INVALID_CHARACTER_ERR));
}
var node=new DOMAttr(this);
node.name=name;
node.nodeName=name;
return node;
};
DOMDocument.prototype.createElementNS=function DOMDocument_createElementNS(_473b,_473c){
if(this.ownerDocument.implementation.errorChecking){
if(!this.ownerDocument._isValidNamespace(_473b,_473c)){
throw (new DOMException(DOMException.NAMESPACE_ERR));
}
if(!this.ownerDocument.implementation._isValidName(_473c)){
throw (new DOMException(DOMException.INVALID_CHARACTER_ERR));
}
}
var node=new DOMElement(this);
var qname=this.implementation._parseQName(_473c);
node.nodeName=_473c;
node.namespaceURI=_473b;
node.prefix=qname.prefix;
node.localName=qname.localName;
node.tagName=_473c;
this.all[this.all.length]=node;
return node;
};
DOMDocument.prototype.createAttributeNS=function DOMDocument_createAttributeNS(_473f,_4740){
if(this.ownerDocument.implementation.errorChecking){
if(!this.ownerDocument._isValidNamespace(_473f,_4740,true)){
throw (new DOMException(DOMException.NAMESPACE_ERR));
}
if(!this.ownerDocument.implementation._isValidName(_4740)){
throw (new DOMException(DOMException.INVALID_CHARACTER_ERR));
}
}
var node=new DOMAttr(this);
var qname=this.implementation._parseQName(_4740);
node.nodeName=_4740;
node.namespaceURI=_473f;
node.prefix=qname.prefix;
node.localName=qname.localName;
node.name=_4740;
node.nodeValue="";
return node;
};
DOMDocument.prototype.createNamespace=function DOMDocument_createNamespace(_4743){
var node=new DOMNamespace(this);
var qname=this.implementation._parseQName(_4743);
node.nodeName=_4743;
node.prefix=qname.prefix;
node.localName=qname.localName;
node.name=_4743;
node.nodeValue="";
return node;
};
DOMDocument.prototype.getElementById=function DOMDocument_getElementById(_4746){
retNode=null;
for(var i=0;i<this.all.length;i++){
var node=this.all[i];
if((node.id==_4746)&&(node._isAncestor(node.ownerDocument.documentElement))){
retNode=node;
break;
}
}
return retNode;
};
DOMDocument.prototype._genId=function DOMDocument__genId(){
this._lastId+=1;
return this._lastId;
};
DOMDocument.prototype._isValidNamespace=function DOMDocument__isValidNamespace(_4749,_474a,_474b){
if(this._performingImportNodeOperation==true){
return true;
}
var valid=true;
var qName=this.implementation._parseQName(_474a);
if(this._parseComplete==true){
if(qName.localName.indexOf(":")>-1){
valid=false;
}
if((valid)&&(!_474b)){
if(!_4749){
valid=false;
}
}
if((valid)&&(qName.prefix=="")){
valid=false;
}
}
if((valid)&&(qName.prefix=="xml")&&(_4749!="http://www.w3.org/XML/1998/namespace")){
valid=false;
}
return valid;
};
DOMDocument.prototype.toString=function DOMDocument_toString(){
return ""+this.childNodes;
};
DOMElement=function(_474e){
this._class=addClass(this._class,"DOMElement");
this.DOMNode=DOMNode;
this.DOMNode(_474e);
this.tagName="";
this.id="";
this.nodeType=DOMNode.ELEMENT_NODE;
};
DOMElement.prototype=new DOMNode;
DOMElement.prototype.getTagName=function DOMElement_getTagName(){
return this.tagName;
};
DOMElement.prototype.getAttribute=function DOMElement_getAttribute(name){
var ret="";
var attr=this.attributes.getNamedItem(name);
if(attr){
ret=attr.value;
}
return ret;
};
DOMElement.prototype.setAttribute=function DOMElement_setAttribute(name,value){
var attr=this.attributes.getNamedItem(name);
if(!attr){
attr=this.ownerDocument.createAttribute(name);
}
var value=new String(value);
if(this.ownerDocument.implementation.errorChecking){
if(attr._readonly){
throw (new DOMException(DOMException.NO_MODIFICATION_ALLOWED_ERR));
}
if(!this.ownerDocument.implementation._isValidString(value)){
throw (new DOMException(DOMException.INVALID_CHARACTER_ERR));
}
}
if(this.ownerDocument.implementation._isIdDeclaration(name)){
this.id=value;
}
attr.value=value;
attr.nodeValue=value;
if(value.length>0){
attr.specified=true;
}else{
attr.specified=false;
}
this.attributes.setNamedItem(attr);
};
DOMElement.prototype.removeAttribute=function DOMElement_removeAttribute(name){
return this.attributes.removeNamedItem(name);
};
DOMElement.prototype.getAttributeNode=function DOMElement_getAttributeNode(name){
return this.attributes.getNamedItem(name);
};
DOMElement.prototype.setAttributeNode=function DOMElement_setAttributeNode(_4757){
if(this.ownerDocument.implementation._isIdDeclaration(_4757.name)){
this.id=_4757.value;
}
return this.attributes.setNamedItem(_4757);
};
DOMElement.prototype.removeAttributeNode=function DOMElement_removeAttributeNode(_4758){
if(this.ownerDocument.implementation.errorChecking&&_4758._readonly){
throw (new DOMException(DOMException.NO_MODIFICATION_ALLOWED_ERR));
}
var _4759=this.attributes._findItemIndex(_4758._id);
if(this.ownerDocument.implementation.errorChecking&&(_4759<0)){
throw (new DOMException(DOMException.NOT_FOUND_ERR));
}
return this.attributes._removeChild(_4759);
};
DOMElement.prototype.getAttributeNS=function DOMElement_getAttributeNS(_475a,_475b){
var ret="";
var attr=this.attributes.getNamedItemNS(_475a,_475b);
if(attr){
ret=attr.value;
}
return ret;
};
DOMElement.prototype.setAttributeNS=function DOMElement_setAttributeNS(_475e,_475f,value){
var attr=this.attributes.getNamedItem(_475e,_475f);
if(!attr){
attr=this.ownerDocument.createAttributeNS(_475e,_475f);
}
var value=new String(value);
if(this.ownerDocument.implementation.errorChecking){
if(attr._readonly){
throw (new DOMException(DOMException.NO_MODIFICATION_ALLOWED_ERR));
}
if(!this.ownerDocument._isValidNamespace(_475e,_475f)){
throw (new DOMException(DOMException.NAMESPACE_ERR));
}
if(!this.ownerDocument.implementation._isValidString(value)){
throw (new DOMException(DOMException.INVALID_CHARACTER_ERR));
}
}
if(this.ownerDocument.implementation._isIdDeclaration(name)){
this.id=value;
}
attr.value=value;
attr.nodeValue=value;
if(value.length>0){
attr.specified=true;
}else{
attr.specified=false;
}
this.attributes.setNamedItemNS(attr);
};
DOMElement.prototype.removeAttributeNS=function DOMElement_removeAttributeNS(_4762,_4763){
return this.attributes.removeNamedItemNS(_4762,_4763);
};
DOMElement.prototype.getAttributeNodeNS=function DOMElement_getAttributeNodeNS(_4764,_4765){
return this.attributes.getNamedItemNS(_4764,_4765);
};
DOMElement.prototype.setAttributeNodeNS=function DOMElement_setAttributeNodeNS(_4766){
if((_4766.prefix=="")&&this.ownerDocument.implementation._isIdDeclaration(_4766.name)){
this.id=_4766.value;
}
return this.attributes.setNamedItemNS(_4766);
};
DOMElement.prototype.hasAttribute=function DOMElement_hasAttribute(name){
return this.attributes._hasAttribute(name);
};
DOMElement.prototype.hasAttributeNS=function DOMElement_hasAttributeNS(_4768,_4769){
return this.attributes._hasAttributeNS(_4768,_4769);
};
DOMElement.prototype.toString=function DOMElement_toString(){
var ret="";
var ns=this._namespaces.toString();
if(ns.length>0){
ns=" "+ns;
}
var attrs=this.attributes.toString();
if(attrs.length>0){
attrs=" "+attrs;
}
ret+="<"+this.nodeName+ns+attrs+">";
ret+=this.childNodes.toString();
ret+="</"+this.nodeName+">";
return ret;
};
DOMAttr=function(_476d){
this._class=addClass(this._class,"DOMAttr");
this.DOMNode=DOMNode;
this.DOMNode(_476d);
this.name="";
this.specified=false;
this.value="";
this.nodeType=DOMNode.ATTRIBUTE_NODE;
this.ownerElement=null;
this.childNodes=null;
this.attributes=null;
};
DOMAttr.prototype=new DOMNode;
DOMAttr.prototype.getName=function DOMAttr_getName(){
return this.nodeName;
};
DOMAttr.prototype.getSpecified=function DOMAttr_getSpecified(){
return this.specified;
};
DOMAttr.prototype.getValue=function DOMAttr_getValue(){
return this.nodeValue;
};
DOMAttr.prototype.setValue=function DOMAttr_setValue(value){
if(this.ownerDocument.implementation.errorChecking&&this._readonly){
throw (new DOMException(DOMException.NO_MODIFICATION_ALLOWED_ERR));
}
this.setNodeValue(value);
};
DOMAttr.prototype.setNodeValue=function DOMAttr_setNodeValue(value){
this.nodeValue=new String(value);
this.value=this.nodeValue;
this.specified=(this.value.length>0);
};
DOMAttr.prototype.toString=function DOMAttr_toString(){
var ret="";
ret+=this.nodeName+"=\""+this.__escapeString(this.nodeValue)+"\"";
return ret;
};
DOMAttr.prototype.getOwnerElement=function(){
return this.ownerElement;
};
DOMNamespace=function(_4771){
this._class=addClass(this._class,"DOMNamespace");
this.DOMNode=DOMNode;
this.DOMNode(_4771);
this.name="";
this.specified=false;
this.value="";
this.nodeType=DOMNode.NAMESPACE_NODE;
};
DOMNamespace.prototype=new DOMNode;
DOMNamespace.prototype.getValue=function DOMNamespace_getValue(){
return this.nodeValue;
};
DOMNamespace.prototype.setValue=function DOMNamespace_setValue(value){
this.nodeValue=new String(value);
this.value=this.nodeValue;
};
DOMNamespace.prototype.toString=function DOMNamespace_toString(){
var ret="";
if(this.nodeName!=""){
ret+=this.nodeName+"=\""+this.__escapeString(this.nodeValue)+"\"";
}else{
ret+="xmlns=\""+this.__escapeString(this.nodeValue)+"\"";
}
return ret;
};
DOMCharacterData=function(_4774){
this._class=addClass(this._class,"DOMCharacterData");
this.DOMNode=DOMNode;
this.DOMNode(_4774);
this.data="";
this.length=0;
};
DOMCharacterData.prototype=new DOMNode;
DOMCharacterData.prototype.getData=function DOMCharacterData_getData(){
return this.nodeValue;
};
DOMCharacterData.prototype.setData=function DOMCharacterData_setData(data){
this.setNodeValue(data);
};
DOMCharacterData.prototype.setNodeValue=function DOMCharacterData_setNodeValue(data){
if(this.ownerDocument.implementation.errorChecking&&this._readonly){
throw (new DOMException(DOMException.NO_MODIFICATION_ALLOWED_ERR));
}
this.nodeValue=new String(data);
this.data=this.nodeValue;
this.length=this.nodeValue.length;
};
DOMCharacterData.prototype.getLength=function DOMCharacterData_getLength(){
return this.nodeValue.length;
};
DOMCharacterData.prototype.substringData=function DOMCharacterData_substringData(_4777,count){
var ret=null;
if(this.data){
if(this.ownerDocument.implementation.errorChecking&&((_4777<0)||(_4777>this.data.length)||(count<0))){
throw (new DOMException(DOMException.INDEX_SIZE_ERR));
}
if(!count){
ret=this.data.substring(_4777);
}else{
ret=this.data.substring(_4777,_4777+count);
}
}
return ret;
};
DOMCharacterData.prototype.appendData=function DOMCharacterData_appendData(arg){
if(this.ownerDocument.implementation.errorChecking&&this._readonly){
throw (new DOMException(DOMException.NO_MODIFICATION_ALLOWED_ERR));
}
this.setData(""+this.data+arg);
};
DOMCharacterData.prototype.insertData=function DOMCharacterData_insertData(_477b,arg){
if(this.ownerDocument.implementation.errorChecking&&this._readonly){
throw (new DOMException(DOMException.NO_MODIFICATION_ALLOWED_ERR));
}
if(this.data){
if(this.ownerDocument.implementation.errorChecking&&((_477b<0)||(_477b>this.data.length))){
throw (new DOMException(DOMException.INDEX_SIZE_ERR));
}
this.setData(this.data.substring(0,_477b).concat(arg,this.data.substring(_477b)));
}else{
if(this.ownerDocument.implementation.errorChecking&&(_477b!=0)){
throw (new DOMException(DOMException.INDEX_SIZE_ERR));
}
this.setData(arg);
}
};
DOMCharacterData.prototype.deleteData=function DOMCharacterData_deleteData(_477d,count){
if(this.ownerDocument.implementation.errorChecking&&this._readonly){
throw (new DOMException(DOMException.NO_MODIFICATION_ALLOWED_ERR));
}
if(this.data){
if(this.ownerDocument.implementation.errorChecking&&((_477d<0)||(_477d>this.data.length)||(count<0))){
throw (new DOMException(DOMException.INDEX_SIZE_ERR));
}
if(!count||(_477d+count)>this.data.length){
this.setData(this.data.substring(0,_477d));
}else{
this.setData(this.data.substring(0,_477d).concat(this.data.substring(_477d+count)));
}
}
};
DOMCharacterData.prototype.replaceData=function DOMCharacterData_replaceData(_477f,count,arg){
if(this.ownerDocument.implementation.errorChecking&&this._readonly){
throw (new DOMException(DOMException.NO_MODIFICATION_ALLOWED_ERR));
}
if(this.data){
if(this.ownerDocument.implementation.errorChecking&&((_477f<0)||(_477f>this.data.length)||(count<0))){
throw (new DOMException(DOMException.INDEX_SIZE_ERR));
}
this.setData(this.data.substring(0,_477f).concat(arg,this.data.substring(_477f+count)));
}else{
this.setData(arg);
}
};
DOMText=function(_4782){
this._class=addClass(this._class,"DOMText");
this.DOMCharacterData=DOMCharacterData;
this.DOMCharacterData(_4782);
this.nodeName="#text";
this.nodeType=DOMNode.TEXT_NODE;
};
DOMText.prototype=new DOMCharacterData;
DOMText.prototype.splitText=function DOMText_splitText(_4783){
var data,inode;
if(this.ownerDocument.implementation.errorChecking){
if(this._readonly){
throw (new DOMException(DOMException.NO_MODIFICATION_ALLOWED_ERR));
}
if((_4783<0)||(_4783>this.data.length)){
throw (new DOMException(DOMException.INDEX_SIZE_ERR));
}
}
if(this.parentNode){
data=this.substringData(_4783);
inode=this.ownerDocument.createTextNode(data);
if(this.nextSibling){
this.parentNode.insertBefore(inode,this.nextSibling);
}else{
this.parentNode.appendChild(inode);
}
this.deleteData(_4783);
}
return inode;
};
DOMText.prototype.toString=function DOMText_toString(){
return this.__escapeString(""+this.nodeValue);
};
DOMCDATASection=function(_4785){
this._class=addClass(this._class,"DOMCDATASection");
this.DOMCharacterData=DOMCharacterData;
this.DOMCharacterData(_4785);
this.nodeName="#cdata-section";
this.nodeType=DOMNode.CDATA_SECTION_NODE;
};
DOMCDATASection.prototype=new DOMCharacterData;
DOMCDATASection.prototype.splitText=function DOMCDATASection_splitText(_4786){
var data,inode;
if(this.ownerDocument.implementation.errorChecking){
if(this._readonly){
throw (new DOMException(DOMException.NO_MODIFICATION_ALLOWED_ERR));
}
if((_4786<0)||(_4786>this.data.length)){
throw (new DOMException(DOMException.INDEX_SIZE_ERR));
}
}
if(this.parentNode){
data=this.substringData(_4786);
inode=this.ownerDocument.createCDATASection(data);
if(this.nextSibling){
this.parentNode.insertBefore(inode,this.nextSibling);
}else{
this.parentNode.appendChild(inode);
}
this.deleteData(_4786);
}
return inode;
};
DOMCDATASection.prototype.toString=function DOMCDATASection_toString(){
var ret="";
ret+="<![CDATA["+this.nodeValue+"]]>";
return ret;
};
DOMComment=function(_4789){
this._class=addClass(this._class,"DOMComment");
this.DOMCharacterData=DOMCharacterData;
this.DOMCharacterData(_4789);
this.nodeName="#comment";
this.nodeType=DOMNode.COMMENT_NODE;
};
DOMComment.prototype=new DOMCharacterData;
DOMComment.prototype.toString=function DOMComment_toString(){
var ret="";
ret+="<!--"+this.nodeValue+"-->";
return ret;
};
DOMProcessingInstruction=function(_478b){
this._class=addClass(this._class,"DOMProcessingInstruction");
this.DOMNode=DOMNode;
this.DOMNode(_478b);
this.target="";
this.data="";
this.nodeType=DOMNode.PROCESSING_INSTRUCTION_NODE;
};
DOMProcessingInstruction.prototype=new DOMNode;
DOMProcessingInstruction.prototype.getTarget=function DOMProcessingInstruction_getTarget(){
return this.nodeName;
};
DOMProcessingInstruction.prototype.getData=function DOMProcessingInstruction_getData(){
return this.nodeValue;
};
DOMProcessingInstruction.prototype.setData=function DOMProcessingInstruction_setData(data){
this.setNodeValue(data);
};
DOMProcessingInstruction.prototype.setNodeValue=function DOMProcessingInstruction_setNodeValue(data){
if(this.ownerDocument.implementation.errorChecking&&this._readonly){
throw (new DOMException(DOMException.NO_MODIFICATION_ALLOWED_ERR));
}
this.nodeValue=new String(data);
this.data=this.nodeValue;
};
DOMProcessingInstruction.prototype.toString=function DOMProcessingInstruction_toString(){
var ret="";
ret+="<?"+this.nodeName+" "+this.nodeValue+" ?>";
return ret;
};
DOMDocumentFragment=function(_478f){
this._class=addClass(this._class,"DOMDocumentFragment");
this.DOMNode=DOMNode;
this.DOMNode(_478f);
this.nodeName="#document-fragment";
this.nodeType=DOMNode.DOCUMENT_FRAGMENT_NODE;
};
DOMDocumentFragment.prototype=new DOMNode;
DOMDocumentFragment.prototype.toString=function DOMDocumentFragment_toString(){
var xml="";
var _4791=this.getChildNodes().getLength();
for(intLoop=0;intLoop<_4791;intLoop++){
xml+=this.getChildNodes().item(intLoop).toString();
}
return xml;
};
DOMDocumentType=function(){
alert("DOMDocumentType.constructor(): Not Implemented");
};
DOMEntity=function(){
alert("DOMEntity.constructor(): Not Implemented");
};
DOMEntityReference=function(){
alert("DOMEntityReference.constructor(): Not Implemented");
};
DOMNotation=function(){
alert("DOMNotation.constructor(): Not Implemented");
};
Strings=new Object();
Strings.WHITESPACE=" \t\n\r";
Strings.QUOTES="\"'";
Strings.isEmpty=function Strings_isEmpty(strD){
return (strD===null)||(strD.length===0);
};
Strings.indexOfNonWhitespace=function Strings_indexOfNonWhitespace(strD,iB,iE){
if(Strings.isEmpty(strD)){
return -1;
}
iB=iB||0;
iE=iE||strD.length;
for(var i=iB;i<iE;i++){
if(Strings.WHITESPACE.indexOf(strD.charAt(i))==-1){
return i;
}
}
return -1;
};
Strings.lastIndexOfNonWhitespace=function Strings_lastIndexOfNonWhitespace(strD,iB,iE){
if(Strings.isEmpty(strD)){
return -1;
}
iB=iB||0;
iE=iE||strD.length;
for(var i=iE-1;i>=iB;i--){
if(Strings.WHITESPACE.indexOf(strD.charAt(i))==-1){
return i;
}
}
return -1;
};
Strings.indexOfWhitespace=function Strings_indexOfWhitespace(strD,iB,iE){
if(Strings.isEmpty(strD)){
return -1;
}
iB=iB||0;
iE=iE||strD.length;
for(var i=iB;i<iE;i++){
if(Strings.WHITESPACE.indexOf(strD.charAt(i))!=-1){
return i;
}
}
return -1;
};
Strings.replace=function Strings_replace(strD,iB,iE,strF,strR){
if(Strings.isEmpty(strD)){
return "";
}
iB=iB||0;
iE=iE||strD.length;
return strD.substring(iB,iE).split(strF).join(strR);
};
Strings.getLineNumber=function Strings_getLineNumber(strD,iP){
if(Strings.isEmpty(strD)){
return -1;
}
iP=iP||strD.length;
return strD.substring(0,iP).split("\n").length;
};
Strings.getColumnNumber=function Strings_getColumnNumber(strD,iP){
if(Strings.isEmpty(strD)){
return -1;
}
iP=iP||strD.length;
var arrD=strD.substring(0,iP).split("\n");
var _47a9=arrD[arrD.length-1];
arrD.length--;
var _47aa=arrD.join("\n").length;
return iP-_47aa;
};
StringBuffer=function(){
this._a=[];
};
StringBuffer.prototype.append=function StringBuffer_append(d){
this._a[this._a.length]=d;
};
StringBuffer.prototype.toString=function StringBuffer_toString(){
return this._a.join("");
};
XMLSerializer=function(){
alert("do not init this class. Use the static methods instead");
};
XMLSerializer.toXML=function(obj,_39df,_39e0){
if(_39df==undefined){
_39df="model";
}
_39e0=_39e0?_39e0:"";
var t=XMLSerializer.getTypeName(obj);
var s=_39e0+"<"+_39df+" type=\""+t+"\">";
switch(t){
case "int":
case "number":
case "boolean":
s+=obj;
break;
case "string":
s+=XMLSerializer.xmlEncode(obj);
break;
case "date":
s+=obj.toLocaleString();
break;
case "Array":
case "array":
s+="\n";
var _39e3=_39e0+"   ";
for(var i=0;i<obj.length;i++){
s+=XMLSerializer.toXML(obj[i],("element"),_39e3);
}
s+=_39e0;
break;
default:
if(obj!==null){
s+="\n";
if(obj instanceof ArrayList){
obj.trimToSize();
}
var _39e5=obj.getPersistentAttributes();
var _39e3=_39e0+"   ";
for(var name in _39e5){
s+=XMLSerializer.toXML(_39e5[name],name,_39e3);
}
s+=_39e0;
}
break;
}
s+="</"+_39df+">\n";
return s;
};
XMLSerializer.isSimpleVar=function(t){
switch(t){
case "int":
case "string":
case "String":
case "Number":
case "number":
case "Boolean":
case "boolean":
case "bool":
case "dateTime":
case "Date":
case "date":
case "float":
return true;
}
return false;
};
XMLSerializer.getTypeName=function(obj){
if(obj===null){
return "undefined";
}
if(obj instanceof Array){
return "Array";
}
if(obj instanceof Date){
return "Date";
}
var t=typeof (obj);
if(t=="number"){
return (parseInt(obj).toString()==obj)?"int":"number";
}
if(XMLSerializer.isSimpleVar(t)){
return t;
}
return obj.type.replace("@NAMESPACE"+"@","");
};
XMLSerializer.xmlEncode=function(_39ea){
var _39eb=_39ea;
var amp=/&/gi;
var gt=/>/gi;
var lt=/</gi;
var quot=/"/gi;
var apos=/'/gi;
var _39f1="&#62;";
var _39f2="&#38;#60;";
var _39f3="&#38;#38;";
var _39f4="&#34;";
var _39f5="&#39;";
_39eb=_39eb.replace(amp,_39f3);
_39eb=_39eb.replace(quot,_39f4);
_39eb=_39eb.replace(lt,_39f2);
_39eb=_39eb.replace(gt,_39f1);
_39eb=_39eb.replace(apos,_39f5);
return _39eb;
};
XMLDeserializer=function(){
alert("do not init this class. Use the static methods instead");
};
XMLDeserializer.fromXML=function(node,_3b95){
var _3b96=""+node.getAttributes().getNamedItem("type").getNodeValue();
var value=node.getNodeValue();
switch(_3b96){
case "int":
try{
return parseInt(""+node.getChildNodes().item(0).getNodeValue());
}
catch(e){
alert("Error:"+e+"\nDataType:"+_3b96+"\nXML Node:"+node);
}
case "string":
case "String":
try{
if(node.getChildNodes().getLength()>0){
return ""+node.getChildNodes().item(0).getNodeValue();
}
return "";
}
catch(e){
alert("Error:"+e+"\nDataType:"+_3b96+"\nXML Node:"+node);
}
case "Number":
case "number":
try{
return parseFloat(""+node.getChildNodes().item(0).getNodeValue());
}
catch(e){
alert("Error:"+e+"\nDataType:"+_3b96+"\nXML Node:"+node);
}
case "Boolean":
case "boolean":
case "bool":
try{
return "true"==(""+node.getChildNodes().item(0).getNodeValue()).toLowerCase();
}
catch(e){
alert("Error:"+e+"\nDataType:"+_3b96+"\nXML Node:"+node);
}
case "dateTime":
case "Date":
case "date":
try{
return new Date(""+node.getChildNodes().item(0).getNodeValue());
}
catch(e){
alert("Error:"+e+"\nDataType:"+_3b96+"\nXML Node:"+node);
}
case "float":
try{
return parseFloat(""+node.getChildNodes().item(0).getNodeValue());
}
catch(e){
alert("Error:"+e+"\nDataType:"+_3b96+"\nXML Node:"+node);
}
break;
}
_3b96=_3b96.replace("@NAMESPACE"+"@","");
var obj=eval("new "+_3b96+"()");
if(_3b95!=undefined&&obj.setModelParent!=undefined){
obj.setModelParent(_3b95);
}
var _3b99=node.getChildNodes();
for(var i=0;i<_3b99.length;i++){
var child=_3b99.item(i);
var _3b9c=child.getNodeName();
if(obj instanceof Array){
_3b9c=i;
}
obj[_3b9c]=XMLDeserializer.fromXML(child,obj instanceof AbstractObjectModel?obj:_3b95);
}
return obj;
};
EditPolicy=function(_3863){
this.policy=_3863;
};
EditPolicy.DELETE="DELETE";
EditPolicy.MOVE="MOVE";
EditPolicy.CONNECT="CONNECT";
EditPolicy.RESIZE="RESIZE";
EditPolicy.prototype.type="EditPolicy";
EditPolicy.prototype.getPolicy=function(){
return this.policy;
};
AbstractPalettePart=function(){
this.x=0;
this.y=0;
this.html=null;
};
AbstractPalettePart.prototype.type="AbstractPalettePart";
AbstractPalettePart.prototype=new Draggable();
AbstractPalettePart.prototype.createHTMLElement=function(){
var item=document.createElement("div");
item.id=this.id;
item.style.position="absolute";
item.style.height="24px";
item.style.width="24px";
return item;
};
AbstractPalettePart.prototype.setEnviroment=function(_3172,_3173){
this.palette=_3173;
this.workflow=_3172;
};
AbstractPalettePart.prototype.getHTMLElement=function(){
if(this.html===null){
this.html=this.createHTMLElement();
Draggable.call(this,this.html);
}
return this.html;
};
AbstractPalettePart.prototype.onDrop=function(_3174,_3175){
var _3176=this.workflow.getScrollLeft();
var _3177=this.workflow.getScrollTop();
var _3178=this.workflow.getAbsoluteX();
var _3179=this.workflow.getAbsoluteY();
this.setPosition(this.x,this.y);
this.execute(_3174+_3176-_3178,_3175+_3177-_3179);
};
AbstractPalettePart.prototype.execute=function(x,y){
alert("inerited class should override the method 'AbstractPalettePart.prototype.execute'");
};
AbstractPalettePart.prototype.setTooltip=function(_317c){
this.tooltip=_317c;
if(this.tooltip!==null){
this.html.title=this.tooltip;
}else{
this.html.title="";
}
};
AbstractPalettePart.prototype.setDimension=function(w,h){
this.width=w;
this.height=h;
if(this.html===null){
return;
}
this.html.style.width=this.width+"px";
this.html.style.height=this.height+"px";
};
AbstractPalettePart.prototype.setPosition=function(xPos,yPos){
this.x=Math.max(0,xPos);
this.y=Math.max(0,yPos);
if(this.html===null){
return;
}
this.html.style.left=this.x+"px";
this.html.style.top=this.y+"px";
this.html.style.cursor="move";
};
AbstractPalettePart.prototype.getWidth=function(){
return this.width;
};
AbstractPalettePart.prototype.getHeight=function(){
return this.height;
};
AbstractPalettePart.prototype.getY=function(){
return this.y;
};
AbstractPalettePart.prototype.getX=function(){
return this.x;
};
AbstractPalettePart.prototype.getPosition=function(){
return new Point(this.x,this.y);
};
AbstractPalettePart.prototype.disableTextSelection=function(e){
if(typeof e.onselectstart!="undefined"){
e.onselectstart=function(){
return false;
};
}else{
if(typeof e.style.MozUserSelect!="undefined"){
e.style.MozUserSelect="none";
}
}
};
ExternalPalette=function(_3d6d,divId){
this.html=document.getElementById(divId);
this.workflow=_3d6d;
this.parts=new ArrayList();
};
ExternalPalette.prototype.type="ExternalPalette";
ExternalPalette.prototype.getHTMLElement=function(){
return this.html;
};
ExternalPalette.prototype.addPalettePart=function(part){
if(!(part instanceof AbstractPalettePart)){
throw "parameter is not instanceof [AbstractPalettePart]";
}
this.parts.add(part);
this.html.appendChild(part.getHTMLElement());
part.setEnviroment(this.workflow,this);
};
