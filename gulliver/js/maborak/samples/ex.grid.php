<?php
header('Content-Type: text/html; charset=utf-8');
?>
<div style="text-align:center;"><h2>leimnud.module.grid</h2></div>
<div class="explain_body">
<pre class="explain_code">
	var r = new leimnud.module.rpc.xmlhttp({
		url	: "grid.json.data.load.php",
		method	: "post",
		args	: "action=loadSimpleGrid"
	});
	r.callback=function(rpc){
		var grid = new leimnud.module.grid();
		grid.make({
			paginator	:{
				limit	: 5,
				page	: 1
			},
			target	:document.getElementById("target"),
			theme	:"gray",
			title	:"Discografía - Data grid",
			search	:true,
			data	:rpc.xmlhttp.responseText.parseJSON()
		});
	};
	r.make();
</pre>
Crear un grid con datos locales.
<pre class="explain_code">
	var gridCity = new leimnud.module.grid();
	gridCity.make({
		target	:document.getElementById("target"),
		theme	:"gray",
		search	:true,
		title	:"Países usando GNU/Linux",
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
			}
			]
		}
	});
</pre>
</div>
