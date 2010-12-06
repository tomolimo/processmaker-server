bpmnGroup=function(){
this.titlebar=null;
this.defaultBackgroundColor=new Color(230,230,250);
this.highlightBackgroundColor=new Color(250,250,200);
CompartmentFigure.call(this);
this.setDimension(800,300);
this.setBackgroundColor(this.defaultBackgroundColor);
};
bpmnGroup.prototype=new CompartmentFigure;
bpmnGroup.prototype.type='bpmnGroup';
bpmnGroup.prototype.title='Group';
bpmnGroup.prototype.createHTMLElement=function(){
var item=CompartmentFigure.prototype.createHTMLElement.call(this);
item.style.margin="0px";
item.style.padding="0px";
item.style.border="1px solid black";
item.style.cursor=null;
this.titlebar=document.createElement("div");
this.titlebar.style.position="absolute";
this.titlebar.style.left="0px";
this.titlebar.style.top="0px";
this.titlebar.style.width=(this.getWidth()-5)+"px";
this.titlebar.style.height="15px";
this.titlebar.style.margin="0px";
this.titlebar.style.padding="0px";
this.titlebar.style.font="normal 10px verdana";
this.titlebar.style.backgroundColor="gray";
this.titlebar.style.borderBottom="1px solid gray";
this.titlebar.style.borderLeft="5px solid transparent";
this.titlebar.style.whiteSpace="nowrap";
this.titlebar.style.textAlign="left";
this.titlebar.style.backgroundImage="url(/skins/ext/images/gray/panel/light-hd.gif)";
this.textNode=document.createTextNode(this.title);
this.titlebar.appendChild(this.textNode);
item.appendChild(this.titlebar);
return item;
};

bpmnGroup.prototype.onFigureEnter=function(_3570){
if(this.children[_3570.id]==null){
this.setBackgroundColor(this.highlightBackgroundColor);
}
CompartmentFigure.prototype.onFigureEnter.call(this,_3570);
};
bpmnGroup.prototype.onFigureLeave=function(_3571){
CompartmentFigure.prototype.onFigureLeave.call(this,_3571);
this.setBackgroundColor(this.defaultBackgroundColor);
};
bpmnGroup.prototype.onFigureDrop=function(_3572){
CompartmentFigure.prototype.onFigureDrop.call(this,_3572);
this.setBackgroundColor(this.defaultBackgroundColor);
};

bpmnGroup.prototype.setDimension=function(w,h){
CompartmentFigure.prototype.setDimension.call(this,w,h);
if(this.titlebar!=null){
this.titlebar.style.width=(this.getWidth()-5)+"px";
}
};
bpmnGroup.prototype.setTitle=function(title){
this.title=title;
};
bpmnGroup.prototype.getMinWidth=function(){
return 50;
};
bpmnGroup.prototype.getMinHeight=function(){
return 50;
};
bpmnGroup.prototype.setBackgroundColor=function(color){
this.bgColor=color;
if(this.bgColor!=null){
this.html.style.backgroundColor=this.bgColor.getHTMLStyle();
}else{
this.html.style.backgroundColor="transparent";
}
};
