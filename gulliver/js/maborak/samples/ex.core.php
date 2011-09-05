<?php
header('Content-Type: text/html; charset=utf-8'); 
?>
<div style="text-align:center;"><h2>Documentación de maborak</h2></div>
<div class="explain_body">
Bueno, espero que con este pequeño manual se despejen algunas dudas que se tenían respecto a como funcionaba la clase <b>maborak</b>.
<br>
En principio aclarar 2 cosas:
<ol>
<li>La clase padre de todos los archivos javascript es <b>maborak</b>(<i>maborak.js</i>)
<li>Leimnud es simplemente una instancia de la clase <b>maborak</b>
</ol>
Es por esa razón que una pagina que va a usar maborak se inicializa en una variable global <b>leimnud</b>.
<pre class="explain_code">
	var <b>leimnud</b> = new <b>maborak();</b>
	leimnud.make();
</pre>
Una página lista para usar <b>maborak</b> quedaría de la siguiente forma.
<pre class="explain_code"><?php echo htmlentities('<html>
        <head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                <title>Prueba leimnud</title>
                <script type="text/javascript" src="').'<b>../core/maborak.js</b>'.htmlentities('" charset="utf-8"></script>
		<script type="text/javascript">').'
			<span style="color:green;">var leimnud = new maborak();
			leimnud.make();
			leimnud.Package.Load("samples,rpc,drag,drop,panel,app",{Instance:leimnud,Type:"module"});
			leimnud.Package.Load("common",{Type:"file"});
			leimnud.Package.Load("json",{Type:"file"});
			leimnud.exec(leimnud.fix.memoryLeak);</span>

		'.htmlentities('</script>
		<style type=text/css>
			body{
				font:normal 8pt Tahoma,MiscFixed;
			}
		</style>
        </head>
	<body>
        </body>
</html>');?>
</pre>
<br />
<b>Organización de archivos</b>
<pre class="explain_code">
	<b>js/</b>
	   <b>maborak/</b>
		 core/	<span class="explain_comment"># Archivo base / módulos</span>
			<i>maborak.js
			module.rpc.js
			module.panel.js
			module.app.js</i>
		 samples/	<span class="explain_comment"># Ejemplos</span>
			<i>maborak.html
			comoImplementar.html</i>
	   <b>json/</b>
		 core/	<span class="explain_comment"># Archivo base / módulos</span>
			<i>json.js</i>
		 samples/	<span class="explain_comment"># Ejemplos</span>
			<i>example.html</i>
</pre>

<br />
Explicar las funcionalidades de <b>maborak</b> en forma detallada es muchísimo y mas que todo son funcionalidades BASE para levantar una aplicación, una herramienta similar es Prototype que 
NO TE OFRECE APLICACIONES sinó la base para realizarlas (Documentadas).

Example:
<pre class="explain_code">
	/**
	* @class Manage Patterns Design
	*/
	pattern:{
		observer:function(event)
		{
			this.event = event;
			this.g="aaa";
			this.db = [];
			this.register=function(launch,Class)
			{
				this.event = event;
				this.Class = Class;
				this.launch = launch;
				if(this.verify())
				{
					return this.write();
				}
			};
			this.verify=function()
			{
				return (typeof this.launch==="function")?true:false;
			};
			this.write=function()
			{
				var cap = {
					update:this.parent.closure({instance:this,method:this.update}),
					unregister:this.parent.closure({instance:this,method:this.unregister,args:this.db.length})
				};
				this.db.push(this.launch);
				if(this.Class)
				{
					this.Class.observer = cap;
				}
				delete this.event;
				delete this.Class;
				delete this.launch;
				return cap;
			};
			this.update=function()
			{
				var ln = this.db.length;
				for(i=0;i&lt;ln;i++)
				{
					if(typeof this.db[i]=="function")
					{
						this.db[i]();
					}
				}
			};
			this.unregister=function(uid)
			{
				alert(uid);
			};
		}
	}
</pre>
<br />
Las aplicaciones de <b>maborak</b> se encuentran en sus módulos: rpc, panel, app, drag, drop, etc.
</div>
