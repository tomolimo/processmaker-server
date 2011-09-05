<?php

/**
 * Default home page view
 *
 * @author MaBoRaK
 * @version 0.1
 */

echo '<?xml version="1.0" encoding="UTF-8" ?>';
?>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Web samples</title>
	<script type='text/javascript' src='../core/maborak.js'></script>
	<link rel="stylesheet" type="text/css" href="style.css" />
	<script type='text/javascript'>
	var ifr;
	var grid,winGrill, leimnud = new maborak();
	leimnud.make();
	leimnud.Package.Load("validator,app,rpc,fx,drag,drop,panel,grid",{Instance:leimnud,Type:"module"});
	leimnud.Package.Load("json",{Type:"file"});
	leimnud.exec(leimnud.fix.memoryLeak);
	leimnud.event.add(window,'load',function(){
		//alert((70*document.body.clientWidth)/100)
		winGrill = new leimnud.module.panel();
		winGrill.options={
			size:{w:(70*document.body.clientWidth)/100,h:(((100*document.body.clientHeight)/100)-10)},
			title	:"Data grid",
			//titleBar:false,
			position:{x:2,y:2},
			//footerBar:true,
			statusBarButtons:[
			{value:"Save File"},
			{value:"Load File"},
			{value:"Add"}
			],
			control:{
				roll:false,
				close:false
			},
			fx:{
				shadow:false,
				fadeIn:false
			}
		};
		winGrill.events={
			remove:function()
			{

			}
		};
		winGrill.setStyle={
			content:{padding:2}
		};
		winGrill.make();
		winGrill.loader.show();
		window.r = new leimnud.module.rpc.xmlhttp({
			url	: "grid.json.data.load.php",
			method	: "post",
			args	: "action=loadSimpleGrid"
		});
		r.callback=function(rpc,panel){
			panel.loader.hide();
			grid = new leimnud.module.grid();
			grid.make({
				paginator	:{
					limit	: 18,
					page	: 2
				},
				server	:"grid.json.data.load.php",
				target	:winGrill.elements.content,
				theme	:"gray",
				search	:true,
				data	:rpc.xmlhttp.responseText.parseJSON()
			});
			panel.elements.statusBarButtons[0].onmouseup=function(evt,data)
			{
				data = (window.event)?evt:data;
				var r = new leimnud.module.rpc.xmlhttp({
					url		:"grid.json.data.save.php",
					method	:"POST",
					args	:"data="+data.grid.save("json")
				});
				r.callback=function()
				{
					new leimnud.module.app.alert().make({label:"Saved",target:panel.elements.content});
				}
				r.make();
			}.args({grid:grid,panel:panel});
			panel.elements.statusBarButtons[1].onmouseup=function(evt,data)
			{
				data = (window.status)?evt:data;
				panel.elements.statusBarButtons[1].disabled=true;
				var selFile = new leimnud.module.panel();
				selFile.options={
					size:{w:250,h:82},
					title	:"Select a file:",
					position:{x:2,y:2,center:true},
					target	:panel.elements.content,
					//titleBar:false,
					statusBarButtons:[
					{value:"Ok"},
					{value:"Cancel"}
					],
					control:{
						roll:false,
						resize:false
					},
					fx:{
						modal:true,
						shadow:false,
						fadeIn:true
					}
				};
				selFile.setStyle={
					title:{textAlign:"left"},
					status:{textAlign:"right"},
					content:{overflow:"hidden",borderWidth:0,backgroundColor:"transparent"}
				};
				selFile.make();
				selFile.loader.show();
				var r = new leimnud.module.rpc.xmlhttp({
					url		:"grid.json.data.load.php",
					method	:"POST",
					args	:"action=scanDir"
				});
				r.callback=function(rpc)
				{
					selFile.loader.hide();
					var files = rpc.xmlhttp.responseText.parseJSON();
					var s = document.createElement("select");
					leimnud.dom.setStyle(s,{
						width:"100%",
						font:"normal 8pt Tahoma,MiscFixed"
					});
					for(var i=0;i<files.length;i++)
					{
						s.options[i] = new Option(files[i],files[i],true);
						leimnud.dom.setStyle(s.options[i],{
							padding:2,
							paddingLeft:10
						});
					}
					selFile.addContent(s);
				}
				r.make();
			}.args({grid:grid,panel:panel});
			panel.elements.statusBarButtons[2].onmouseup=grid.add;
			//panel.addContent(rpc.xmlhttp.responseText);
		}.args(winGrill);
		r.make();

		/* Segundo Grid */

		window.gridGenre = new leimnud.module.panel();
		gridGenre.options={
			size:{w:((30*document.body.clientWidth)/100)-15,h:((100*document.body.clientHeight)/100)-10},
			title	:"Grid",
			titleBar:false,
			statusBar:false,
			position:{x:((70*document.body.clientWidth)/100)+7,y:2}
		};
		gridGenre.setStyle={
			content:{padding:2}
		};
		gridGenre.make();

		window.gridCity = new leimnud.module.grid();
		gridCity.make({
			server	:"/grid/save",
			target	:gridGenre.elements.content,
			theme	:"gray",
			search	:true,
			title	:"Pa&iacute;ses usando <b>GNU/Linux</b>",
			noPaginator:true,
			data	:{
				column:[
				{
					title	:"Nro.",
					type	:"drag",
					paint	:"bg1",
					width	:"5%"
				},
				{
					title:"Pa&iacute;s",
					type:"text",
					edit:true,
					width:"75%",
					style:{
						fontWeight:"bold"
					}
				},
				{
					title	: "Usuarios",
					type	: "text",
					edit	: true,
					style:{
						fontWeight:"bold"
					},
					width	: "20%"
				}
				],
				rows:[
				{
					data:[{value:"Bolivia"},{value:"1"}]
				},
				{
					data:[{value:"Argentina"},{value:"1"}]
				},
				{
					data:[{value:"Brasil"},{value:"1"}]
				},
				{
					data:[{value:"Uruguay"},{value:"1"}]
				},
				{
					data:[{value:"Ecuador"},{value:"1"}]
				},
				{
					data:[{value:"Paraguay"},{value:"1"}]
				},
				{
					data:[{value:"<span style='color:red'>Chile<span>"},{value:0}]
				}
				]
			}
		});

		new leimnud.module.grid().make({
			server	:"/grid/save",
			target	:gridGenre.elements.content,
			theme	:"violet",
			//theme	:"violet",
			paginator	:{
				limit:88
			},
			title	:"<b>GNU/Linux</b> users (<i>Geek's</i>)",
			data	:{
				column:[
				{
					title	:"Nro.",
					type	:"drag",
					paint	:"bg1",
					width	:"5%"
				},
				{
					title:"Nickname",
					type:"text",
					edit:true,
					width:"45%",
					onchange	:function(data)
					{
						alert(data.index+":"+data.dom)
					},
					style:{
						fontWeight:"bold"
					}
				},
				{
					title	: "Distribuci&oacute;n",
					type	: "textDropdown",
					edit	: true,
					style:{
						fontWeight:"bold"
					},
					data:[
					[0,"Redhat Linux"],
					[1,"Ubuntu"],
					[2,"Slackware"],
					[3,"CentOS"]
					],
					onchange	:function(data)
					{
						alert(data.index+":"+data.dom)
					},
					width	: "45%"
				},
				{
					title:"IT.",
					type:"checkbox",
					edit:true,
					width:"5%",
					onchange	:function(data)
					{
						alert(data.index+":"+data.dom)
					},
					style:{
						fontWeight:"bold"
					}
				}
				],
				rows:[
				{
					data:[{value:"MaBoRaK"},{value:3},{value:true}]
				},
				{
					data:[{value:"Onti"},{value:1},{value:true}]
				},
				{
					data:[{value:"Ciroegans"},{value:0},{value:true}]
				},
				{
					data:[{value:"Ac|D_Burn"},{value:2},{value:false}]
				},
				{
					data:[{value:"Mandragora"},{value:0},{value:true}]
				}
				]
			}
		});
		/* Iframe */

		/*var panelIframe = new leimnud.module.panel();
		panelIframe.options={
			size:{w:((70*document.body.clientWidth)/100),h:((50*document.body.clientHeight)/100)-15},
			title	:"Grid",
			titleBar:false,
			statusBarButtons:[],
			loaderHidden:true,
			position:{x:2,y:((50*document.body.clientHeight)/100)+7},
			control:{resize:false}
		};
		panelIframe.setStyle={
			content:{padding:2,overflow:"hidden"}
		};
		panelIframe.make();
		panelIframe.loader.show();
		var ifr = document.createElement("iframe");
		ifr.src="tutoriales/ajax/index.html";
		leimnud.dom.setStyle(ifr,{
			width:"100%",
			height:"100%",
			border:"0px solid red"
		});
		ifr.onload=function()
		{
			panelIframe.loader.hide();
		}
		panelIframe.addContent(ifr);
		var dc = document.createElement("div");
		dc.style.textAlign="right";
		var dcS = document.createElement("input");
		dcS.type="button";
		dcS.value="Change iframe Location"
		dc.appendChild(dcS);
		dcS.onmouseup=function()
		{
			new leimnud.module.app.prompt().make(
			{
				label:"Enter url: ",
				action:function(value)
				{
					ifr.src=value;
					panelIframe.loader.show();
				}
			});
		};
		panelIframe.addContentStatus(dc);*/
	});
	</script>
	<style>
	input{
		font:normal 8pt sans-serif,Tahoma,MiscFixed;
	}
	body{
		background-color:white;
	}
	</style>
</head>

<body>
</body>

</html>
