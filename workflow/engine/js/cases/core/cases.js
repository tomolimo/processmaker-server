var cases=function()
{
	this.parent = leimnud;
	this.panels = {};
	this.make=function(options)
	{
		this.options.target = this.parent.dom.element(this.options.target);
		var panel;
		/* Panel list Begin */
		this.panels.list = panel = new leimnud.module.panel();
		panel.options={
			size	:{w:310,h:250},
			position:{x:50,y:50},
			title	:"List",
			theme	:"processmaker",
			target	:this.options.target,
			statusBar:true,
			limit	:true,
			control	:{resize:false,close:true,roll:false},
			fx	:{opacity:true,rollWidth:150,fadeIn:false}
		};

		/* Panel list End */
		/* Panel step Begin */
			this.panels.step = panel = new this.parent.module.panel();
			this.panels.step.options={
				size:{w:260,h:550},
				title	:this.options.title,
				//headerBar:true,
				//titleBar:false,
				//elementToDrag:"headerBar",
				target:this.options.target,
				cursorToDrag:"move",
				position:{x:5,y:5},
				limit:true,
				fx:{shadow:false,modal:false,opacity:false}
			};
			this.panels.step.setStyle={
				statusBar:{
				}
			};
			this.panels.step.styles.fx.opacityModal.Static=0;
			this.panels.step.make();
			this.panels.step.events = {
				remove:[function() { delete(this.panels.step); }.extend(this)]
			};
		panel.events.remove.push(function()
		{
			var r = new this.parent.module.rpc.xmlhttp({
				url:this.options.dataServer,
				args:"showWindow=false"
			});
			r.make();
		}.extend(this));
		/* Load data BEGIN */
		panel.loader.show();
		var r = new this.parent.module.rpc.xmlhttp({
			url:this.options.dataServer,
			args:"action="+this.options.action+"&showWindow="+this.options.action
		});
		r.callback=function(rpc){
			this.panels.step.loader.hide();
			var scs=rpc.xmlhttp.responseText.extractScript();
			this.panels.step.addContent(rpc.xmlhttp.responseText);
			scs.evalScript();
		}.extend(this);
		r.make();
		/* Load data END */
		//this.panels.list.make();
	}
	this.expand(this);
};