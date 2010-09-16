var t,j,l,k,g,dAd,drop,myPanel,myPanel2,explain,panel=[],arg=[],wilDebug;
var samp = function()
{
	explain = {
		core:{
			content:function(){
				myPanel.command(myPanel.loader.show);
				var r = new leimnud.module.rpc.xmlhttp({url:"ex.core.php"});
				r.callback=leimnud.closure({Function:function(rpc){
					myPanel.command(myPanel.loader.hide);
					myPanel.addContent(rpc.xmlhttp.responseText);
				},args:r})
				r.make();
			},
			samples:{
				
			}
		},
		rpc:{
			content:function()
			{
				myPanel.command(myPanel.loader.show);
				var r = new leimnud.module.rpc.xmlhttp({url:"ex.rpc.php"});
				r.callback=leimnud.closure({Function:function(rpc){
					myPanel.command(myPanel.loader.hide);
					var scs=rpc.xmlhttp.responseText.extractScript();
					myPanel.addContent(rpc.xmlhttp.responseText.stripScript());
					scs.evalScript();
				},args:r})
				r.make();
			},
			samples:{
				
			}
		},
		panel:{
			content:function(){
				myPanel.command(myPanel.loader.show);
				var r = new leimnud.module.rpc.xmlhttp({url:"ex.panel.php"});
				r.callback=leimnud.closure({Function:function(rpc){
					myPanel.command(myPanel.loader.hide);
					/*var tg = new RegExp('(?:<pre.*class\=\"explain_code\">)((\n|\r|.)*?)(?:<\/pre>)', 'img');
					rpc.xmlhttp.responseText.match(tg).length);*/
					myPanel.addContent(rpc.xmlhttp.responseText);
					/*[1,2,3].map(leimnud.closure({Function:function(kk){
						alert(3333+":"+kk)
					}}));*/
				},args:r})
				r.make();

			},
			samples:{
				panel1:function()
				{
					panel[0]=new leimnud.module.panel();
					panel[0].options={
						control:{roll:true}
					};
					panel[0].make();
					panel[0].addContent("Hola mundo");
				},
				panel2:function()
				{
					panel[1]=new leimnud.module.panel();
					panel[1].options={
						size:{w:200,h:100},
						position:{x:50,y:50},
						title:"Panel 2",
						theme:"processmaker_fixed",
						control:{
							close:true
						},
						fx:{
							shadow:false
						}
					};
					panel[1].make();
					panel[1].addContent("Hola mundo");
				},
				panel3:function()
				{
					panel[2]=new leimnud.module.panel();
					panel[2].options={
						size:{w:400,h:100},
						position:{x:50,y:50,center:true},
						title:"Panel 2",
						theme:"processmaker_fixed",
						control:{
							close:true,
							drag:false
						},
						fx:{
							modal:true
						}
					};
					panel[2].setStyle={
						modal:{
							backgroundColor:"black"
						},
						shadow:{
						backgroundColor:"black"	
					}};
					panel[2].make();
					panel[2].addContent("Hola mundo");
				}
			}
		},
		grid:{
			content:function()
			{
				myPanel.loader.show();
				window.r = new leimnud.module.rpc.xmlhttp({
					url	: "grid.json.data.load.php",
					method	: "post",
					args	: "action=loadSimpleGrid"
				});
				r.callback=function(rpc,panel){
					window.grid = new leimnud.module.grid();
					grid.make({
						paginator	:{
							limit	: 5,
							page	: 1
						},
						server	:"grid.json.data.load.php",
						target	:panel.elements.content,
						theme	:"gray",
						title	:"Discografía - Data grid",
						search	:true,
						data	:rpc.xmlhttp.responseText.parseJSON()
					});
					var dv = $dce("div");
					myPanel.addContent(dv);
					var r = new leimnud.module.rpc.xmlhttp({url:"ex.grid.php"});
					r.callback=function(rpc){
						panel.loader.hide();
						dv.innerHTML=rpc.xmlhttp.responseText;
					};
					r.make();
				}.args(myPanel);
				r.make();
			}
		},
		app:{
			content:function()
			{
				myPanel.loader.show();
				var r = new leimnud.module.rpc.xmlhttp({url:"ex.app.php"});
				r.callback=leimnud.closure({Function:function(rpc){
					myPanel.loader.hide();
					//return;
					myPanel.addContent(rpc.xmlhttp.responseText);
					explain.app.samples.menu1();
 					new leimnud.module.app.iframe("content_to_iframe");
					var sub = new leimnud.module.app.submit({
						form	: document.forms['algun_formulario']
						});
					sub.callback = function(){
						alert(sub.rpc.xmlhttp.responseText)
					};
				},args:r})
				r.make();
			},
			samples:{
				menu1:function()
				{	
					var menuObserver=leimnud.factory(leimnud.pattern.observer,true);
					leimnud.event.add(document.body,"click",leimnud.closure({instance:menuObserver,method:menuObserver.update}));
					var menu = new leimnud.module.app.menuRight();
					menu.make({
						target:"menu1",
						menu:[
							{text:"Option 1",launch:function(){alert("option 1")}},
							{text:"Option 2",launch:function(){alert("option 2")}},
							{text:"Option 3",launch:function(){alert("option 3")}}
						]
					});
					menuObserver.register(leimnud.closure({instance:menu,method:menu.remove}),menu);
				}
			}
		}
	};
	myPanel=new leimnud.module.panel();
	var bod=leimnud.dom.capture("tag.body 0");
	myPanel.options={
		size:{w:bod.clientWidth-50,h:bod.clientHeight-50},
		position:{x:270,y:50,center:true},
		title:"Tutorial de maborak.js",
		theme:"processmaker",
		control:{
			//resize:true,
			//drag:false,
			//close:true,
			roll:true
		},
		statusBar:true
	};
	myPanel.setStyle={
		content:{
			padding:15,
			font:"normal 11px sans-serif, MiscFixed"
		}
		//modal:{backgroundColor:"black"},
		//shadow:{backgroundColor:"black"},
		//containerWindow:{border:"1px solid #666"}
	};
	myPanel.styles.fx.opacityModal.Static=80
	myPanel.tab={
		width	:110,
		optWidth:100,
		step	:(leimnud.browser.isIE?-1:5),
		options:[{
			title	:"Core",
			content	:explain.core.content
		},{
			title	:"Paneles",
			content	:explain.panel.content
		},{
			title	:"Ajax / Rpc",
			content	:explain.rpc.content
		},{
			title	:"Drag & Drop",
			content	:"fgfgf"
		},{
			title	:"Data grid",
			content	:explain.grid.content
		},{
			title	:"Aplicaciones",
			content	:explain.app.content,
			selected:true
		}]
	};
	myPanel.make();
	/*wilDebug = new leimnud.module.panel();
	wilDebug.options={
		size:{w:300,h:300},
		position:{x:5,y:5},
		control:{
			roll:true
		},
		fx:{
			shadow:false,
			fadeIn:true
		}
	};
	wilDebug.events={
		remove:function()
		{
			
		}
	};
	wilDebug.make();*/
};
leimnud.Package.Public({
	info	:{
		Class	:"maborak",
		File	:"module.samples.js",
		Name	:"samples",
		Type	:"module",
		Version	:"0.1"
	},
	content	:samp
});
