<?php
header('Content-Type: text/html; charset=utf-8'); 
?>
<div style="text-align:center;"><h2>module.rpc.js</h2></div>
<div class="explain_body">
Llamando a un Proceso Remoto (RPC)
<pre class="explain_code">
var rpc = new leimnud.module.rpc.xmlhttp(
	{
		url	:'ex.rpc.php',
		method	:"POST"
		args	:'argument='+{var1:1,var2:2,var3:[1,2,3]}.toJSONString()+"&other=OtherData"
	});
	rpc.callback=function(rpc)
	{
		alert(rpc.xmlhttp.responseText)
	};
	rpc.make();
</pre>
Sample: <input type="button" value="Call to Remote File" onclick="var rpc = new leimnud.module.rpc.xmlhttp(
	{
		url	:'sample.php',
		method	:'POST',
		args	:'argument='+{var1:1,var2:2,var3:[1,2,3]}.toJSONString()+'&other=OtherData'
	});
	rpc.callback=function(rpc)
	{
		new leimnud.module.app.alert().make({label:rpc.xmlhttp.responseText.escapeHTML()})
	};
	rpc.make();">
<br><br>
Añadir contenido a un panel por medio de Ajax.

Example:
<pre class="explain_code">
	var r = new leimnud.module.rpc.xmlhttp({url:"ex.core.php"});
	r.callback=leimnud.closure({Function:function(rpc){
		myPanel.addContent(rpc.xmlhttp.responseText);
	},args:r})
	r.make();

</pre>
Ejecutar javascript
<pre class="explain_code">
	var r = new leimnud.module.rpc.xmlhttp({url:"ex.core.php"});
	r.callback=leimnud.closure({Function:function(rpc){
		var scs=rpc.xmlhttp.responseText.extractScript();	//capturamos los scripts
		myPanel.addContent(rpc.xmlhttp.responseText.stripScript());//Eliminamos porque ya no los necesitamos
		scs.evalScript();	//interpretamos los scripts

	},args:r})
	r.make();

</pre>
</div>
