/***************************************************************************
*     				  		  module.drop.js
*                        ------------------------
*   Copyleft	: (c) 2007 maborak.com <maborak@maborak.com>
*   Version		: 0.2
*
***************************************************************************/

/***************************************************************************
*
*   This program is free software; you can redistribute it and/or modify
*   it under the terms of the GNU General Public License as published by
*   the Free Software Foundation; either version 2 of the License, or
*   (at your option) any later version.
*
***************************************************************************/
/**
* @class drop
*/
leimnud.Package.Public({
	info	:{
		Class	:"maborak",
		File	:"module.drop.js",
		Name	:"drop",
		Type	:"module",
		Version	:"0.1"
	},
	content	:function(options){
		this.options	= options || {};
		this.elements	= [];
		this.selected 	= false;
		this.selID		= false;
		this.lastSelected= false;
		this.make=function(options)
		{
            return this;
		};
		this.register = function(data)
		{
			var ev = data.events || {};
			data.events = ev;
			this.elements.push(data);
			return (this.elements.length-1);
		};
		this.unregister = function(index)
		{
			this.elements[index]=null;
		};
		this.generateIndex=function()
		{
			return this.elements.length;
		};
		this.capture = function(drag,StopOnAbsolute)
		{
			this.drag = drag.currentElementDrag;
			//window.status=this.drag;
			var position = this.parent.dom.position(this.drag,false,StopOnAbsolute || false);
			position={
				x:position.x+(this.drag.clientWidth/2),
				y:position.y+(this.drag.clientHeight/2)
			};
			this.selected = false;
			//console.info(position);
			for(var i=0;i<this.elements.length;i++)
			{
				if(this.elements[i]!==null)
				{
					var pt = this.parent.dom.positionRange(this.elements[i].element,StopOnAbsolute || false);
					if(position.x > pt.x1 && position.x < pt.x2 && position.y > pt.y1 && position.y < pt.y2)
					{
						this.selected = i;
						break;
					}
				}
			}
			if(this.selected===false)
			{
				if(this.selID!==false)
				{
					this.out(this.selID);
				}
			}
			else
			{
				if(this.selID!==false && this.selID!==this.selected)
				{
					this.out(this.selID);
				}
				this.over(this.selected);
			}
			this.lastSelected = (this.selected===false)?this.lastSelected:this.selected;
			//window.status=this.selected;
			//return (inTarget===false)?false:this.elements[inTarget].value;*/
		};
		this.setArrayPositions=function(StopOnAbsolute)
		{
			this.arrayPositions=[];
			for(var i=0;i<this.elements.length;i++)
			{
				this.arrayPositions.push(this.parent.dom.positionRange(this.elements[i].element,StopOnAbsolute || false));
			}
		};
		this.captureFromArray = function(drag,Final,StopOnAbsolute)
		{
			this.drag = drag.currentElementDrag;
			//window.status=this.drag;
			//var position = this.parent.dom.position(this.drag,Final || false,StopOnAbsolute || false);
			//var position = this.parent.dom.position(this.drag);
			this.position={
				x:parseInt(this.drag.style.left),
				y:parseInt(this.drag.style.top)
			};
		//	console.info(this.position);
			this.selected = false;
			for(var i=0;i<this.arrayPositions.length;i++)
			{
				var pt = this.arrayPositions[i];
				if(this.position.x >= pt.x1 && this.position.x <= pt.x2 && this.position.y >= pt.y1 && this.position.y <= pt.y2)
				{
					this.selected = i;
					break;
				}
			}
			this.lastSelected = (this.selected===false)?this.lastSelected:this.selected;
			//window.status=this.selected;
			if(this.selected===false)
			{
				if(this.selID!==false)
				{
					this.out(this.selID);
				}
			}
			else
			{
				if(this.selID!==false && this.selID!==this.selected)
				{
					this.out(this.selID);
				}
				this.over(this.selected);
			}			
			//return (inTarget===false)?false:this.elements[inTarget].value;*/
		};
		this.over=function(uid)
		{
			this.selID	= uid;
			if(this.elements[uid]!==null)
			{
				return this.launchEvents(this.elements[uid].events.over);
			}

		};
		this.out=function(uid)
		{
			this.selID=false;
			if(this.elements[uid]!==null)
			{
				return this.launchEvents(this.elements[uid].events.out);
			}
		};
		this.launchEvents=function(event)
		{
			if(event && event.isArray===true)
			{
				for(var i=0;i<event.length;i++)
				{
					if(typeof event[i]=="function")
					{
						event[i]();
					}
				}
			}
			else
			{
				return (event)?event():false;
			}
		};
		this.expand(this);
		return this;
	}
});
