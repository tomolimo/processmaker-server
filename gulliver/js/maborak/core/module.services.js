/***************************************************************************
*     			      module.dashboard.js
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
* @class services
*/
leimnud.Package.Public({
	info	:{
		Class	:"maborak",
		File	:"module.services.js",
		Name	:"services",
		Type	:"module",
		Version	:"0.1"
	},
	content	:{
		provider:function(provider){
			this.provider=provider;
			this.Execute=function(options,callback)
			{
				this.options = {
					service:"test",
					action :"test",
					data:{
						id:1,
						uid:1
					}
				}.concatMaborak(options || {});
				this.callback = callback || function(){};
				var rpc = new this.parent.module.rpc['json']({
					url		: this.provider,
					method	: 'POST',
					args	: "data="+this.options.toJSONString()
				});
				rpc.callback = function(rpc)
				{
					this.callback(rpc['json'].responseText);
				}.extend(this);
				rpc.make();
				return this;
			};
			this.expand(this);
			return this;
		},
		app:{
			rate:function()
			{
				this.url_provider="http://www2.maborak.net/projects/processmaker/gulliver/js/maborak/core/server/maborak.module.services.php";
				this.parent=leimnud;
				this.service = new leimnud.module.services.provider(this.url_provider);
				this.make=function(options)
				{
					this.options = {
					}.concatMaborak(options || {});
					this.service.Execute({service:'rate',action:'get',data:this.capsule()},function(r){
						var d = r.parseJSON();
						this.rate=d['RATE'];
						this.dom_set(d['RATE']);
					}.extend(this));
					var t = this.target();
					this.dom=[];
					for(var i=0;i<10;i++)
					{
						t.append(
							this.dom[i]=new DOM('img',{
								src:leimnud.info.images+"star_u.gif",
								onmouseover:function(evt,o){
									var k = (this.parent.browser.isIE)?evt:o;
									this.dom_set(k+1);
								}.extend(this,i),
								onmouseup:function(evt,o){
									var k = (this.parent.browser.isIE)?evt:o;
									this.set(k+1);
									this.disable();
								}.extend(this,i),
								onmouseout:function(evt,o){
									this.dom_set(this.rate);
								}.extend(this,i)
							},{width:"25px",height:"25px",cursor:"pointer"})
						);
					}
				};
				this.dom_set=function(v)
				{
					for(var i=0;i<v;i++)
					{
						this.dom[i].src=leimnud.info.images+"star_s.gif";
					}
					for(var j=i;j<this.dom.length;j++)
					{
						this.dom[j].src=leimnud.info.images+"star_u.gif";
					}
				};
				this.capsule=function()
				{
					return this.options;
				};
				this.get=function()
				{
					
				};
				this.set=function(d)
				{
					this.service.Execute({service:'rate',action:'set',data:{value:d,id:this.options.id}},function(r){
						//alert(r);return;
						var d = r.parseJSON();
						this.rate=d['RATE'];
						this.dom_set(d['RATE']);
					}.extend(this));
				};
				this.disable=function()
				{
					for(var i=0;i<this.dom.length;i++)
					{
						this.dom[i].onmouseover	=function(){};						
						this.dom[i].onmouseout	=function(){};
						this.dom[i].onmouseup	=function(){};
						this.dom[i].style.cursor="default";
					}
				}
				this.target=function()
				{
					var rnd  ="rss_"+new Date().getTime();
					document.write("<div id='"+rnd+"' style=''></div>");
					return $(rnd);
				};
				this.expand(this);
				return this;
			},
			comments:function()
			{
				this.url_provider= "http://www2.maborak.net/projects/processmaker/gulliver/js/maborak/core/server/maborak.module.services.php";
				this.parent      = leimnud;
				this.dom         = {};
				this.service = new leimnud.module.services.provider(this.url_provider);
				this.make=function(options)
				{
					this.options = {
						limit:10
					}.concatMaborak(options || {});					
					var t = this.target();
					t.append(
						this.dom.a = new DOM('div').append(
							new button('Post',this.post)
						),
						this.dom.b = new DOM('div')
					);
					this.service.Execute({service:'comments',action:'get'},function(r){
						var d = r.parseJSON();
						var l = (d.length>this.options.limit)?this.options.limit:d.length;
						this.dom.cs = [];
						for(var i=0;i<l;i++)
						{
							this.dom.b.append(this.create_comment(d[i].name,d[i].comment));
						}
					}.extend(this));
				};
				this.create_comment=function(n,c)
				{
					return new DOM('DIV',{},{margin:10,font:"normal 10px sans-serif"}).append(
						new DOM('fieldset',{innerHTML:c},{border:"1px solid #EEE",whiteSpace:"pre"}).append(new DOM('legend',{innerHTML:n},{fontWeight:"bold"}))
					);						
				};
				this.capsule=function()
				{
					return this.options;
				};
				this.target=function()
				{
					var rnd  ="rss_"+new Date().getTime();
					document.write("<div id='"+rnd+"' style=''></div>");
					return $(rnd);
				};
				this.post=function()
				{
					this.panel = new this.parent.module.panel();
					this.panel.options={
						position:{center:true},
						size:{w:500,h:250},
						fx:{modal:true,fadeIn:true,fadeOut:true}
					}
					this.panel.make();
					this.panel.addContent(new DOM('div',{},{margin:10}).append(
						new DOM('div',{innerHTML:"<b>Name:</b>"}).append(this.dom.name = new input()),
						this.dom.comment = new DOM('textarea',{},{width:"100%",height:150,marginTop:10}),
						new DOM('div',{},{textAlign:'center'}).append(
							this.dom.post = new button('Post',function(){
								if(this.dom.name.value.trim()==""){
									this.dom.name.failed();
									return;
								}
								this.dom.name.passed();
								this.dom.name.disable();
								this.dom.post.disable();
								this.dom.cancel.disable();
								this.dom.comment.disabled=true;
								this.panel.loader.show();
								this.service.Execute({service:'comments',action:'post',data:{name:this.dom.name.value,comment:this.dom.comment.value}},function(r){
										var a = this.dom.b.childNodes.length;
										var b = this.create_comment(this.dom.name.value.escapeHTML(),this.dom.comment.value.escapeHTML());
										if(a>0)
										{
											this.dom.b.firstChild.before(b);
										}
										else
										{
											this.dom.b.append(b);
										}
										this.panel.remove();
									}.extend(this));
							}.extend(this)),
							this.dom.cancel = new button('Cancel')
						)
					));
					return false;
				}
				this.expand(this);
				return this;
			}
		}
	}
});
