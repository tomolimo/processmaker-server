/**
* @author MaBoRaK
* @extends Class leimnud.module.boxi
* @param options Panel options
*/
leimnud.Package.Public({
	info	:{
		Class	:"maborak",
		File	:"module.panel.js",
		Name	:"boxi",
		Type	:"module",
		Version	:"0.1"
	},
	content	:function(options)
	{
		/* Constructor */
		this.make=function(options)
		{
			this.options={
				target:document,
				w:600,
				h:400,
				bg:"#000",
				opacity:80
			}.concatMaborak(options || {});
			var links = this.options.target.getElementsByTagName("a");
			for(var i=0;i<links.length;i++)
			{
				var onC=links[i].onclick;
				if(this.to_parse(links[i]))
				{
					links[i].onclick=this.href.args({
						l:links[i],
						c:onC,
						o:{}.concatMaborak(this.options).concatMaborak(this.load_options(links[i]))
					});
				}
			}
			return this;
		};
		this.load_options=function(l)
		{
			var r = l.rel || "";
			var y = (r.split("|")[1] || "").split(",");
			var o = {};
			for(var i=0;i<y.length;i++)
			{
				var u = y[i].split(":");
				var a = (u[0] || "").trim();
				var b = (u[1] || "").trim();
				if(a!="" && b!="")
				{
					if(a=="w")
					{
						o.w=parseInt(b);
					}
					else if(a=="h")
					{
						o.h=parseInt(b);
					}
					else if(a=="bg")
					{
						o.bg=b;
					}
					else if(a=="opacity")
					{
						o.opacity=parseInt(b);
					}
				}
			}
			return o;
		};
		this.to_parse = function(l)
		{
			var a = (l['rel'] || '').split('|');
			return (a[0]=="boxi")?true:false;
		};
		this.href = function(event,opt)
		{
			//alert(event);
			opt = arguments[1] || arguments[0];
			link = opt.l;
			onCl = opt.c;
			var options = opt.o;
			var p = new this.parent.module.panel();
			p.options={
				size:{w:options.w,h:options.h},
				position:{center:true},
		//		titleBar:false,
				fx:{modal:true,fadeIn:true,fadeOut:true}
			};
			p.styles.fx.opacityModal.Static=options.opacity;
			p.setStyle={
				content:{padding:5,borderTopWidth:1},
				containerWindow:{borderColor:"#000"},
				modal:{backgroundColor:options.bg},
				status:{font:"normal 8pt Tahoma,Sans-serif",color:"black"}
			};
			p.make();
			var rpc = new this.parent.module.rpc.xmlhttp({
				url	: link.href,
				method	: "GET"
			});
			p.elements.modal.onmouseup=p.remove;
			p.loader.show();
			if(link.title && link.title.trim()!=="")
			{
				p.addContentStatus(link.title);
			}
			rpc.callback = function(rpc)
			{
				p.loader.hide();
				var content = rpc.xmlhttp.responseText;
				var scripts = content.extractScript();
				p.addContent(content);
				scripts.evalScript();
				new this.parent.module.app.iframe(p.elements.content,false);
				/* Esto podria emular los formularios aun BETA*/
				/*var forms = this.elements.content.getElementsByTagName('form');
				for(var i=0;i<forms.length;i++)
				{
					var sub = new leimnud.module.app.submit({
							form	: forms[i]
					});
					sub.callback = function(){
					};
				}*/
			}.extend(this);
			rpc.make();
			return false;
		};
		this.expand(this);
	}
});
