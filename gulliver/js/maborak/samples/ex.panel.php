<?php
header('Content-Type: text/html; charset=utf-8'); 
?>
<div style="text-align:center;"><h2>module.panel.js</h2></div>
<div style="text-align:center;"><span style="background-color:red;width:50px;margin-right:5px;"></span>  Obligatorio. <span style="background-color:#006699;width:50px;margin-right:5px;"></span>  Opcional.<br /></div>
<br>
<div class="explain_body">
Creando un <input type="button" onclick="explain.panel.samples.panel1(); return false;" value="panel simple">.
<pre class="explain_code">
	panel[0]=new leimnud.module.panel();	<span class="explain_comment"># Constructor</span>
	panel[0].options={			<span class="explain_comment"># Opciones</span>
		size:{w:100,h:100},		<span class="explain_comment"># Tamaño</span>
		position:{x:10,y:50},		<span class="explain_comment"># Posición</span>
		title:"Titulo del panel (opcional)",	<span class="explain_comment"># Titulo</span>
		theme:"panel"			<span class="explain_comment"># Tema</span>
	};
	panel[0].make();			<span class="explain_comment"># Compilar</span>
	panel[0].addContent("Hola mundo");	<span class="explain_comment"># Añadir contenido (String u Objeto DOM)</span>
</pre>
<br>
Al momento de crear el panel la variable que contiene el objeto tiene la capacidad.
<br>
<ol>
<li>Añadir/Eliminar contenido
<li>Modificar su comportamiento
<li>Cambiar su diseño:<br>
Se puede cambiar el diseño de un panel en 2 situaciones:
	<ol>
		<li>Antes de ejecutar <i>objetoPanel.make();</i>
		<pre class="explain_code">
			objetoPanel.setStyle={
				containerWindow	:{algo:"algo"},		<span class="explain_comment"># Contenedor principal que aloja a todos los elementos</span>
				frontend	:{algo:"algo"},		<span class="explain_comment"># Primer hijo del contenedor principal</span>
				backend		:{algo:"algo"},		<span class="explain_comment"># Segundo hijo por detrás para poner iFrame en IE (bug con selects)</span>
				iframe		:{algo:"algo"},		<span class="explain_comment"># Iframe para ocultar selects</span>
				titleBar	:{algo:"algo"},		<span class="explain_comment"># Contiene el titulo y sus botones</span>
				title		:{algo:"algo"},		<span class="explain_comment"># </span>
				roll		:{algo:"algo"},		<span class="explain_comment"># </span>
				close		:{algo:"algo"},		<span class="explain_comment"># </span>
				shadow		:{algo:"algo"},		<span class="explain_comment"># Sombra del panel</span>
				modal		:{algo:"algo"},		<span class="explain_comment"># Div que se crea para hacer modal un panel</span>
				tab		:{algo:"algo"},		<span class="explain_comment"># </span>
				content		:{algo:"algo"},		<span class="explain_comment"># </span>
				statusBar	:{algo:"algo"},		<span class="explain_comment"># </span>
				status		:{algo:"algo"},		<span class="explain_comment"># </span>
			}
			objetoPanel.make();
		</pre>
		</li>
		<li>Despues de ejecutar <i>objetoPanel.make();</i><br>Puedes modificar los Objetos DOM con:
		<br>
		<pre class="explain_code">
			leimnud.dom.setStyle(objetoPanel.elements.containerWindow,{algo:"algo"});
			leimnud.dom.setStyle(objetoPanel.elements.content,{algo:"algo"});
		</pre>
		
		</li>
	</ol>
</li>
<li>Re-compilarlo (en progreso)
</ol>
<b>Ejemplos:</b><br><br>
Eliminar un panel
<pre class="explain_code">
	myPanel.remove();
</pre>
Panel con el boton cerrar: <input type="button" value="ejecutar" onclick="explain.panel.samples.panel2();return false;";>
<pre class="explain_code">
	myPanel=new leimnud.module.panel();
	myPanel.options={
		size:{w:200,h:100},
		position:{x:50,y:50},
		title:"Panel 2",
		theme:"panel",
		control:{
			//close:true
			}
		};
	myPanel.make();
	myPanel.addContent("Hola mundo");
</pre>

Panel con el boton cerrar,modal,centrado, sin drag: <input type="button" value="ejecutar" onclick="explain.panel.samples.panel3();return false;";>
<pre class="explain_code">
	panel[2]=new leimnud.module.panel();
	panel[2].options={
		size:{w:200,h:100},
		position:{x:50,y:50,center:true},
		title:"Panel 2",
		theme:"panel",
		control:{
			close:true,
			drag:false
		},
		fx:{
			modal:true
		}
	};
	panel[2].setStyle={modal:{
		backgroundColor:"black"
	}};
	panel[2].make();
	panel[2].addContent("Hola mundo");
</pre>
<br>
<br>
<b>Tab panel</b><br>
Crear un tab-panel
<pre class="explain_code">
	var myPanel=new leimnud.module.panel();
	myPanel.options={
		size:{w:300,h:400},
		position:{x:0,y:0},
		title:"tab Panel",
		theme:"panel",
		control:{
			close:true,
			drag:true
		}
	};
	myPanel.tab={
		width	:110,			<span class="explain_comment">#ancho</span>
		optWidth:100,			<span class="explain_comment">#alto</span>
		step	:5,			<span class="explain_comment">#espacio entre cada opción</span>
		options:[{
			title	:"Opcion1",	<span class="explain_comment">#Titulo de la opción</span>
			content	:leimnud.closure({Function:function(panel){
				panel.addContent("Contenido de opcion1");
			},args:myPanel}),	<span class="explain_comment">#Contenido de la opción</span>
			selected:true		<span class="explain_comment">#seleccionado por Defecto</span>
		},{
			title	:"Opcion2",
			content	:leimnud.closure({Function:function(panel){
				panel.addContent("Contenido de opcion2");
			},args:myPanel})
		}]
	};
	myPanel.make();
</pre>
Seleccionar una opción
<pre class="explain_code">
	instancePanel.selectTab(2);		//opción 2
</pre>
<br>
<br>
<b>Métodos publicos</b><br>

Cambiar la posición de un panel
<pre class="explain_code">
	instancePanel.move({
		x:111,
		y:222
	});
</pre>
Cambiar el tamaño de un panel
<pre class="explain_code">
	instancePanel.resize({
		w:300,
		h:300
	});
</pre>
Centrar la posición de un panel
<pre class="explain_code">
	instancePanel.center();
	
	//Centrar el panel respecto al eje X
	instancePanel.center("x"); //opcional
	//Centrar el panel respecto al eje Y
	instancePanel.center("y"); //opcional
</pre>
</div>