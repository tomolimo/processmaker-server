leimnud.Package.Public({
	info	:{
		Class	:"maborak",
		File	:"module.rss.js",
		Name	:"rss",
		Type	:"module",
		Version :"0.2"
	},
	content	:function(){
		this.make=function(options)
		{
			options	     = options || {};
			this.modes   = ["remote","local"];
			this.options = {
				mode:(this.modes.inArray((options.mode || "remote"))?options.mode:"local")
			};
			this.options = (this.options.mode=="remote" || options.openFeed)?{
				rpc	:"json",
				mode	:"remote",
				fileJson:this.parent.info.base+"data/maborak.module.rss.feeds.json",
				proxy	:this.parent.info.base+"server/maborak.module.rss.php"
			}:{
				mode      :"local",
				rpc       :"xmlhttp",
				proxy	  :"server/maborak.module.rss.php",
				fileJson  :"data/maborak.module.rss.feeds.json"
			}.concatMaborak(this.options);
			this.options = this.options.concatMaborak({
				fromObject:false,
				theme	  :"gray",
				multiple  :true,
				Default	  :0,
				target	  :document.body,
				toolbar	  :true,
				saveDataIn:"json", /*Support: cookie,json*/
				cookieFeed:"maborak.module.rss.feeds",
				width	  :250,
				inline	  :false,
				title	  :"Rss Reader",
				feed	  :[]
			}).concatMaborak(options || {}).concatMaborak({
				multiple  :((this.options.openFeed)?false:true)
			});
			this.options.target=this.setTarget();
			this.driverRPC = ["xmlhttp","json"];
			this.options.rpc = (this.driverRPC.inArray(this.options.rpc))?this.options.rpc:"xmlhttp";
			this.elements={
				interface:{},
				rootItems:[],
				items:[]
			};
			var tg = new DOM('div',{
				className:"module_rss_container___"+this.options.theme
			},{width:this.options.width});
			this.options.feed = (!this.options.feed.isArray)?[this.options.feed]:this.options.feed;
			$(this.options.target).append(tg);
			this.options.target = tg;
			if(this.options.saveDataIn=="cookie")
			{
				this.options.feed = this.options.feed.concat((this.parent.cookie.get(this.options.cookieFeed) || "[]").parseJSON());
				return this.beforeLoad();
			}
			else if(this.options.saveDataIn=="json")
			{
				this.message("Loading feeds......");
				if(this.options.openFeed)
				{
					this.options.feed = [
						{url:this.options.openFeed,proxy:true}
					];
					this.clearMessage();
					return this.beforeLoad();
				}
				else
				{
					var r = new this.parent.module.rpc[this.options.rpc]({
						method	:"GET",
//						debug	:true,
						url	:this.options.fileJson
					});
					r.callback=function(rpc)
					{
						try{
                            var ed = rpc[this.options.rpc].responseText.parseJSON();
                            var f = ed.feeds || [];
                        }
                        catch(e)
                        {
                            var ed = {category:["General"],feeds:[]};
                            var f = ed.feeds;
                        }
                        this.categoryArray=(ed.category && ed.category.isArray && ed.category.length>0)?ed.category:["General"];
						this.options.feed = this.options.feed.concat(f || []);
						this.clearMessage();
						return this.beforeLoad();
					}.extend(this);
					r.make();
				}
			}
		};
		this.setTarget=function()
		{
			if(this.options.inline===true)
			{
				var rnd  ="rss_"+new Date().getTime();
				document.write("<div id='"+rnd+"' style='width:300px;'></div>");
				return $(rnd);
			}
			else
			{
				return this.options.target;
			}
		};
		this.beforeLoad=function()
		{
			if(this.options.fromObject)
			{
				this.show(this.options.fromObject);
			}
			else
			{
				this.renderHeader();
				this.load(this.options.Default);
			}
			return true;
		};
		this.reload=function()
		{
			this.load(this.current);
			return false;
		};
		this.load=function(feedIndex)
		{
			this.lock();
			this.clear();
			if(this.options.feed.length===0){
				this.message("No se registraron Feeds.");
				return false;
			}
			this.clearMessage();
			this.message("Loading....");
			this.current = feedIndex;
			var feed= this.options.feed[feedIndex];
			var url	= (this.options.mode=="local" && feed.proxy===true)?this.options.proxy || feed.url:feed.url;
			var rpc = new this.parent.module.rpc[this.options.rpc]({
				url	: url,
				method	: ((this.options.mode=="local")?"POST":"GET"),
				args	: ((feed.proxy && this.options.mode==='local')?"action=proxy&url="+encodeURIComponent(feed.url):"")
			});
			rpc.callback = function(rpc)
			{
				this.clearMessage();
				this.feedArray = this.feedXmlToObject(rpc[this.options.rpc].responseXML);
				this.renderItems(this.feedArray);
				this.unlock();
			}.extend(this);
			rpc.make();
		};
		this.feedXmlToObject=function(DOMDocument)
		{
			var p = DOMDocument;
			var atom = this.tag(p,'feed',0);
			var rss  = this.tag(p,'rss',0);
			var rdf  = this.tag(p,'RDF',0);
			this.type = ((rss || rdf) && !atom)?"rss":((atom && !rss)?"atom":"error");
			var channel = (this.type=='atom')?atom:this.tag(rss,'channel',0);
			var f = {
				title		: (this.tag(channel,'title',0,true) 		|| '').escapeHTML(),
				description	: (this.tag(channel,'description',0,true)	|| '').stripScript(),
				language	: (this.tag(channel,'language',0,true) 		|| 'en').escapeHTML(),
				generator	: (this.tag(channel,'generator',0,true) 	|| 'text').escapeHTML(),
				lastBuildDate	:  this.tag(channel,'lastBuildDate',0,true)	|| new Date(),
				author		: (this.tag(channel,'author',0,true)		|| '').escapeHTML(),
				email		: (this.tag(channel,'email',0,true)		|| '').escapeHTML(),
				item		: []
			};
			var items = this.tag((rdf)?rdf:channel,(this.type=='rss')?'item':'entry');
			for(var i=0;i<items.length;i++)
			{
				var item = items[i];
				f.item.push({
					title		: (this.tag(item,'title',0,true)	|| '').escapeHTML(),
					link		: ((this.type=='rss')?(this.tag(item,'link',0,true) || ""):(this.tag(item,'link',0).getAttribute('href') || "")).escapeHTML(),
					pubDate		: (this.tag(item,(this.type=='rss')?'pubDate':'updated',0,true)	|| new Date()),
					content		: (this.tag(item,(this.type=='rss')?((this.tag(item,'encoded',0,true))?'encoded':'content'):'content',0,true) || '').stripScript(),
					description	: (this.tag(item,(this.type=='rss')?'description':'summary',0,true)	|| '').stripScript(),
					readed		: this.readed(this.current,i)
				});

			}
			return f;
		};
		this.readed=function(feed,index)
		{
			var cr = (this.parent.cookie.get(this.options.cookieFeed+"_readed") || "{}").parseJSON();
			return (cr[this.current] && cr[this.current].inArray(index))?true:false;
		};
		this.setReaded=function(item)
		{
			var r = this.feedArray.item[item];
			if(!r.readed)
			{
				r.readed=true;
				var t  = this.elements.items[item].title;
				var cr = (this.parent.cookie.get(this.options.cookieFeed+"_readed") || "{}").parseJSON();
				var cc = cr[this.current];
				if(cc){cc.push(item);}else{cr[this.current]=[item];}
				this.parent.cookie.set(this.options.cookieFeed+"_readed",cr.toJSONString());
				t.className = t.className+" module_rss_itemReaded___"+this.options.theme;
			}
			return true;
		};
		this.clear=function()
		{
			this.parent.dom.remove(this.elements.rootItems);
			this.elements.items=[];
			this.elements.rootItems=[];
		};
		this.message=function(msg)
		{
			this.elements.message = new DOM('div',{innerHTML:msg,className:"module_rss_header___"+this.options.theme},{textAlign:"center",borderBottomWidth:1});
			this.options.target.appendChild(this.elements.message);
		};
		this.clearMessage=function()
		{
			if(this.elements.message)
			{
				this.elements.message.remove();
				delete this.elements.message;
			}
		};
		this.renderHeader=function(Obj)
		{
			$(this.options.target).append(
				new DOM('div',{className:"module_rss_title___"+this.options.theme,innerHTML:this.options.title}),
/*				new DOM('div',{className:"module_rss_separator___"+this.options.theme}),*/
				this.fsDOM = new DOM('div',{
					className:"module_rss_header___"+this.options.theme
					},false,{
						position:'relative'
					}),
				//(this.options.toolbar)?new DOM('div',{className:"module_rss_separator___"+this.options.theme}):false,
				new DOM('div',{className:"module_rss_header___"+this.options.theme},{display:((this.options.toolbar && this.options.multiple)?"":"none")}).append(
					this.elements.interface.details = new button('Details',this.showDetails),
					this.elements.interface.reload  = new button('Reload',this.reload),
					this.elements.interface.add	= new button('Add feed',this.open)
				)/*,
				this.elements.interface.detailsContent  = new DOM('div',{className:"module_rss_separator___"+this.options.theme})*/
			);
			this.fsDOM.append(
					(this.options.multiple)?(this.feedSelector()):false
			)
		};
		this.feedSelector=function()
		{
			this.elements.feedSelector =  new DOM('select',{onchange:function(){
				var s = this.elements.feedSelector;
				this.load(s.options[s.selectedIndex].value);
				return false;
			}.extend(this)},{width:this.fsDOM.parentNode.offsetWidth-12,font:"normal 8pt Tahoma,sans-serif"});
			var j=0;
			for(var i=0;i<this.options.feed.length;i++)
			{
				var feed = this.options.feed[i];
				if(feed && feed.url)
				{
					//this.elements.feedSelector.appendChild(new Option("asdadasd" || feed.title || feed.url,i,(i===this.options.Default)?true:false));
					this.elements.feedSelector.options[j] = new Option((i+1)+".- "+(feed.title || feed.url),i,(i===this.options.Default)?true:false);
					j=j+1;
				}
			}
			return this.elements.feedSelector;
		};
		this.renderItems=function(Obj)
		{
			for(var i=0;i<Obj.item.length;i++)
			{
				var feedItem = Obj.item[i];
				var container= 	new DOM('div',{className:"module_rss_itemContainer___"+	this.options.theme},{
					height:0
				}).append(
					itemPubDate	= new DOM('div',{className:"module_rss_itemPubDate___"+this.options.theme,innerHTML:"<b>Published date:</b> "+feedItem.pubDate}),
					description 	= new DOM('div',{className:"module_rss_itemDescription___"+this.options.theme,innerHTML:feedItem.description || "<br>"}),
					hr		= new DOM('hr' ,{className:"module_rss_itemHr___"+this.options.theme}),
					content		= new DOM('div',{className:"module_rss_itemContent___"+this.options.theme,innerHTML:feedItem.content || "<br>"}),
					link		= new DOM('div',{className:"module_rss_itemLink___"+this.options.theme}).append(
						new button("Save").disable(),
						new button("Read",function(evt,button,i){
							var ln = (this.parent.browser.isIE)?button:i;
							var link = this.feedArray.item[ln].link;
							window.open(link);
						}.extend(this,i),false,{title:"Read more"}),
						new button("Send",this.send.args(i))
					)
				);
				var header,title,buttons,item;
				this.options.target.appendChild(item = new DOM('div',{
						className:"module_rss_item___"+this.options.theme+" "+(((i+1)==Obj.item.length)?"module_rss_itemLast___"+this.options.theme:"")
					}).append(
						header = new DOM('div',{
								className:"module_rss_itemHeader___"+this.options.theme,
								title	:"Published date: "+feedItem.pubDate,
								onmouseover:function(){
									this.className=this.className+" module_rss_itemOver";
								},
								onmouseout:function()
								{
									this.className=this.className.split(" ")[0];
								}
							}).append(
								title   = new DOM('div',{
									className:"module_rss_itemTitle___"+this.options.theme+((feedItem.readed)?" module_rss_itemReaded___"+this.options.theme:""),
									innerHTML:(i+1)+".- "+feedItem.title
								}),
								buttons = new DOM('div',{className:"module_rss_itemButtons___"+this.options.theme}).append(
							)
						),
						container
					)
				);
				//console.info((itemPubDate.clientHeight)+":"+(content.clientHeight)+":"+(description.clientHeight)+":"+(link.clientHeight));
				var contentHeight = ((itemPubDate.clientHeight)+(description.clientHeight)+(hr.clientHeight)+(content.clientHeight)+(link.clientHeight));
				this.parent.dom.setStyle(container,{
					display:"none",
					overflow:((contentHeight>3)?"auto":"hidden")
				});
				contentHeight = (contentHeight>300)?300:contentHeight;
				header.onmouseup=function(evt,data)
				{
					data = (this.parent.browser.isIE)?evt:data;
					return this.show(data.index);
				}.extend(this,{index:i});
				this.elements.rootItems.push(item);
				this.elements.items.push({
					title		:title,
					container	:container,
					height		:contentHeight
				});
			}
		};
		this.show=function(item)
		{
			if(this.inprogress){return false;}
			this.inprogress=true;
			data = this.elements.items[item];
			var title = data.title;
			var container = data.container;
			var visible = (container.style.display=="none")?false:true;
			if(!visible)
			{
				container.setStyle({
					height:1,
					display:""
				});
				this.setReaded(item);
				title.setStyle({fontWeight:"bold"});
				new this.parent.module.fx.algorithm().make({
					duration 	: 500,
					begin		: container.clientHeight,
					transition	: "sineInOut",
					end	 	: data.height,
					onTransition: function(fx){
						this.parent.dom.setStyle(container,{
							height:fx.result
						});
					}.extend(this),
					onFinish:function(fx)
					{
						this.parent.dom.setStyle(container,{
							height:fx.end
						});
						this.inprogress=false;
					}.extend(this)
				});
			}
			else
			{
				new this.parent.module.fx.algorithm().make({
					duration 	: 500,
					begin		: container.clientHeight,
					transition	: "sineOut",
					end	 	: 4,
					onTransition: function(fx){
						this.parent.dom.setStyle(container,{
							height:fx.result
						});
					}.extend(this),
					onFinish:function(fx)
					{
						this.parent.dom.setStyle(container,{
							height:fx.end,
							display:"none"
						});
						this.parent.dom.setStyle(title,{
							fontWeight:"normal"
						});
						this.inprogress=false;
					}.extend(this)
				});

			}
			return false;
		};
		this.lock=function()
		{
			if(this.options.multiple)
			{
				this.elements.feedSelector.disabled=true;
			}
			if(this.options.toolbar)
			{
				this.elements.interface.details.disable();
				this.elements.interface.reload.disable();
			}
		};
		this.unlock=function()
		{
			if(this.options.multiple)
			{
				this.elements.feedSelector.disabled=false;
			}
			if(this.options.toolbar)
			{
				this.elements.interface.details.enable();
				this.elements.interface.reload.enable();
			}
		};
		this.showDetails=function()
		{
			var myPanel=new leimnud.module.panel();
			myPanel.options={
				size:{w:300,h:370},
				position:{x:0,y:0,center:true},
				title:"",
				theme:"processmaker",
				control:{
					resize:false
				},
				fx:{
					modal:true,
					opacity:false
				},
				statusBar:false
			};
			myPanel.setStyle={
				content:{padding:2,background:"transparent",borderWidth:0}
			};
			myPanel.make();

			var gridCity = new leimnud.module.grid();
			gridCity.make({
				target	:myPanel.elements.content,
				theme	:"gray",
				search	:false,
				title	:"<b>Feed: </b>"+(this.options.feed[this.current].title || this.options.feed[this.current].url),
				noPaginator:true,
				data	:{
					column:[
					{
						title:"Properties",
						type:"text",
						width:"40%",
						style:{
							fontWeight:"bold"
						},
						styleValues:{
							textAlign:"right",
							border:"1px solid #AAA"
						}
					},
					{
						title	: "Value",
						type	: "text",
						style:{
							fontWeight:"bold"
						},styleValues:{
							border:"1px solid #AAA"
						},
						width	: "60%"
					}
					],
					rows:[
					{
						data:[{value:"Items"},{value:this.feedArray.item.length}]
					},
					{data:[{value:"Title"},{value:this.feedArray.title || ""}]},
					{data:[{value:"Description"},{value:this.feedArray.description || ""}]},
					{data:[{value:"Author"},{value:this.feedArray.author || ""}]},
					{data:[{value:"Email"},{value:this.feedArray.email || ""}]},
					{data:[{value:"Language"},{value:this.feedArray.language || ""}]},
					{data:[{value:"Generator"},{value:this.feedArray.generator || ""}]},
					{data:[{value:"Last update"},{value:this.feedArray.lastUpdate || ""}]},
					{data:[{value:"Feed url"},{value:"<a href='"+this.options.feed[this.current].url+"'>URL</a>" || ""}]}
					]
				}
			});
		};
		this.addFeed=function(obj)
		{
			var index = this.options.feed.length;
			if(this.options.saveDataIn=="cookie")
			{
				var added = (this.parent.cookie.get(this.options.cookieFeed) || "[]").parseJSON();
				added.push(obj);
				this.parent.cookie.set(this.options.cookieFeed,added.toJSONString());
			}
			else if(this.options.saveDataIn=="json")
			{
				this.elements.interface.add.disable();
				var ojs = encodeURIComponent(obj.toJSONString());
				//console.info(ojs);
				var r = new this.parent.module.rpc[this.options.rpc]({
					method	:"POST",
					url	:this.options.proxy,
					args	:"action=add&data="+ojs
				});
				r.callback=function(rpc){
					try
					{
						var result = rpc[this.options.rpc].responseText.parseJSON();
					}
					catch(e)
					{
						var result = {ok:"Error read/write json"};
					}
					if(result.ok!=="ok")
					{
						new this.parent.module.app.alert().make({label:"<b>"+this.options.fileJson+"</b><br>"+result});
					}
					this.elements.interface.add.enable();
				}.extend(this);
				r.make();
			}
			this.options.feed.push(obj);
			var fls = this.elements.feedSelector.options;
			fls[fls.length] = new Option((fls.length+1)+".- "+(obj.title),index,true);
/*			this.elements.feedSelector.append(
				new Option(obj.title,index,true)
			);*/
			this.load(index);
		};
		this.send=function(evt,b,index)
		{
			index = (this.parent.browser.isIE)?b:index;
			new this.parent.module.app.prompt().make({
				label:"Email address:",
				action:function(value)
				{
					var f = this.feedArray.item[index];
					var c = f.content || ""+f.description || "";
					var l = f.link;
					var ft= this.feedArray.title;
					var r = new this.parent.module.rpc[this.options.rpc]({
						url	:this.options.proxy,
						method	:((this.options.mode=="remote")?"GET":"POST"),
						args	:"action=sendmail&to="+value.trim()+"&subject="+encodeURIComponent(f.title)+"&content="+encodeURIComponent(c)+"&link="+encodeURIComponent(l)+"&feed="+encodeURIComponent(ft)
					});
					r.callback=function(){
						/*new this.parent.module.app.alert().make({label:"Success"});*/
					}.extend(this);
					r.make();
				}.extend(this)
			});
		};
		this.open=function()
		{
			var myPanel=new leimnud.module.panel();
			myPanel.options={
				size:{w:330,h:155},
				position:{x:0,y:0,center:true},
				title:"Add New feed",
				theme:"processmaker",
				statusBarButtons:[
					{value:"Test Feed"},
					{value:"Add Feed"},
					{value:"Reset"},
					{value:"Cancel"}
				],
				control:{
					resize:false
				},
				fx:{
					modal:true,
					opacity:false
				},
				statusBar:true
			};
			myPanel.setStyle={
				content:{padding:10,background:"transparent",borderWidth:0,overflow:"hidden"}
			};
			myPanel.make();
			var table = $dce("table");
			myPanel.addContent(table);
			table.className="app_grid_table___"+this.options.theme;
			var url,title,probe;
			$(table).append(
				new DOM('tbody').append(
					new DOM('tr').append(
						new DOM('td',{innerHTML:"Url:"},{width:"25%",textAlign:"right"}),
						new DOM('td',false,{width:"75%",padding:1}).append(
							url = new input(false,{width:"100%"})
						)
					),
					new DOM('tr').append(
						new DOM('td',{innerHTML:"Title:"},{textAlign:"right"}),
						new DOM('td',false,{padding:1}).append(
							title = new input(false,{width:"100%"})
						)
					),
                    new DOM('tr').append(
						new DOM('td',{innerHTML:"Category:"},{textAlign:"right"}),
						new DOM('td',false,{padding:1}).append(
							category = new this.parent.module.dom.select({
                                data    :this.categoryArray.toSelect().concat([{value:"new",text:"--- New category ---"}]),
                                properties:{onchange:function(){
                                    //alert(category.options[category.selectedIndex].value)
                                    if(category.selected().value==="new")
                                    {
                                        new leimnud.module.app.prompt().make({
                                            label:"Category name:",
                                            action:function(val)
                                            {
                                                //category.clear();
                                                this.categoryArray.push(val);
                                                category.addOption({
                                                    text    :val,
                                                    value   :this.categoryArray.length,
                                                    key     :(category.options.length-1),
                                                    selected:true
                                                });
                                            }.extend(this),
                                            cancel:function()
                                            {
                                                category.selectedIndex=0;
                                            }
                                        });
                                    }
                                    else
                                    {
                                    
                                    }
                                }.extend(this)},
                                style:{marginLeft:2}
						    })
                        )
					),
					new DOM('tr').append(
						probe = new DOM('td',{innerHTML:"",colSpan:2},{textAlign:"center",padding:1})
					)
				)
			);
			var but0 = myPanel.elements.statusBarButtons[0];
			var but1 = myPanel.elements.statusBarButtons[1];
			var but2 = myPanel.elements.statusBarButtons[2];
			url.focus();
			myPanel.elements.statusBarButtons[3].onmouseup=myPanel.remove;
			but1.disable();
			but0.onmouseup=function()
			{
				var t = encodeURIComponent(url.value.trim());
				if(!t){return false;}
				but0.disable();
				var rpc = new this.parent.module.rpc[this.options.rpc]({
					url	: ((this.options.mode==='local')?this.options.proxy:url.value.trim()),
//					debug	: true,
					method	: ((this.options.mode==='local')?"POST":"GET"),
					args	: ((this.options.mode==='local')?"action=proxy&url="+encodeURIComponent(url.value.trim()):"")
				});
				probe.innerHTML="Loading feed.............";
				rpc.callback = function(rpc)
				{
					probe.innerHTML="Validating.............";
					var xml = rpc[this.options.rpc].responseXML;
					var atom = this.tag(xml,'feed',0);
					var rss  = this.tag(xml,'rss',0);
					var rdf  = this.tag(xml,'RDF',0);
					but0.enable();
					if(atom || rss || rdf)
					{
						url.disable();
						title.disable();
						but0.disable();
						var tp = (rss)?"RSS":((rdf)?"RDF":"ATOM");
						probe.innerHTML="<span style='color:green;'>Feed <b>"+tp+"</b> valid</span>";
						but1.enable();
					}
					else
					{
						but1.disable();
						probe.innerHTML="<span style='color:red;'>Feed invalid</span>";
					}
				}.extend(this);
				rpc.make();
				return false;
			}.extend(this);
			but1.onmouseup=function()
			{
				var u = url.value.trim();
				var t = title.value.trim() || u;
				this.addFeed({
					url	:u,
					title	:t,
					proxy	:true
				});
				myPanel.remove();
				return false;
			}.extend(this);
			but2.onmouseup=function()
			{
				title.enable();
				url.enable();
				but0.enable();
				but1.disable();
				return false;
			}.extend(this);
			return false;
		};
		this.tag=function(DOM,tag,node,value)
		{
			try
			{
				var o = DOM.getElementsByTagName(tag);
				return (typeof node==='number')?((value)?o[node].firstChild.nodeValue:o[node]):o;
			}
			catch(e)
			{
				return false;
			}
		};
		this.expand(this);
	}
});
