<?php
header('Content-Type: text/html; charset=utf-8');
?>
<div style="text-align:center;"><h2>leimnud.module.app.menuRight</h2></div>
<div class="explain_body">
Crear menu contextual.
<pre class="explain_code">
	var menu = new leimnud.module.app.menuRight();
	menu.make({
		target:this.panels.editor.elements.content,
		menu:[
			{text:"Option 1",launch:functionCallback},
			{text:"Option 2",launch:functionCallback},
			{text:"Option 3",launch:functionCallback}
		]
	});
</pre>
<br>
Example:
<div style="margin:10px;padding:10px;border:1px solid #006699;color:white;background-color:#006699;" id="menu1">Right click</div>
En este caso el menú desaparecerá con un click en el <b>elemento al que se añadió el menú</b> para eliminar el menu 
desde el body se debe añadir un modelo observador.

<br><br>Sería muy pesado hacer un <b>menuInstance.remove();</b> para todos nuestros menus.

<br><br>Example:
<pre class="explain_code"><span style="color:red;">
	var menuObserver=leimnud.factory(leimnud.pattern.observer,true);	
	leimnud.event.add(document.body,"click",leimnud.closure({instance:menuObserver,method:menuObserver.update}));</span>
	var menu = new leimnud.module.app.menuRight();
	menu.make({
		target:this.panels.editor.elements.content,
		menu:[
			{text:"Option 1",launch:functionCallback},
			{text:"Option 2",launch:functionCallback},
			{text:"Option 3",launch:functionCallback}
		]
	});
	<span style="color:red;">menuObserver.register(leimnud.closure({instance:menu,method:menu.remove}),menu);</span>
</pre>
<br>
<div style="text-align:center;"><h2>leimnud.module.app.iframe</h2></div>
Convertir <b>links</b> en un contenido a <b>post-back ajax</b>.
<br>
<pre class="explain_code"><?php echo htmlentities(utf8_decode('
<script type="text/javascript">
	new leimnud.module.app.iframe("content")
</script>
<div id="content">
	Link a página 2 <a href="link2.php">here</a> .
</div>'))?>
</pre>
<br>
Example:
<div id="content_to_iframe" style="border:1px dashed orange;margin:10px;padding:10px;">Link a alguna página  <a href="link2.php">lClick Here</a></div>
<br><br>
<div style="text-align:center;"><h2>leimnud.module.app.submit</h2></div>
Convertir <b>post-back</b> de un formulario a <b>post-back ajax</b>.
<br>
<pre class="explain_code"><?php echo htmlentities(utf8_decode('
<script type="text/javascript">
	var sub = new leimnud.module.app.submit({
		form	: document.forms["algo"]
	});
	sub.callback = function(){
		alert(sub.rpc.xmlhttp.responseText)
	};
</script>
<form name="algo" action="post.php" method="post">
	<input type="text" name="a" value="0">
	<input type="hidden" value="hiddenText" name="hiddenValue">
	<input type="checkbox" name="c[]" value="1">
	<input type="checkbox" name="c[]" value="2">
	<input type="checkbox" name="c[]" value="3">
	<input type="text" value="asdasd3">
	<textarea name="elTextarea">333</textarea>
	<select name="selectObj[]" style="height:50px;" multiple>
		<option value=1>1</option>
		<option value=2>2</option>
		<option value=3>3</option>
		<option value=4>4</option>
		<option value=5>5</option>
		<option value=6>6</option>
		<option value=7>7</option>
	</select>
	<input name="rd" type="radio" value="1"/>
	<input name="rd" type="radio" value="2" checked/>
	<input name="rd" type="radio" value="3" />
	<input type="submit" name="submit" value="enviar">
</form>'))?>
</pre>
<br>
Example:
<div style="border:1px dashed orange;margin:10px;padding:10px;">
		<form name="algun_formulario" action="post.php" method="post" enctype="multipart/form-data">
			<input type="text" name="a" value="0" />
			<input type="hidden" value="hiddenText" name="hiddenValue">
			<input type="checkbox" name="c[]" value="1">
			<input type="checkbox" name="c[]" value="2">
			<input type="checkbox" name="c[]" value="3">
			<input type="text" value="asdasd3">
			<textarea name="elTextarea">333</textarea>
			<select name="selectObj[]" style="height:50px;" multiple>
				<option value=1>1</option>
				<option value=2>2</option>
				<option value=3>3</option>
				<option value=4>4</option>
				<option value=5>5</option>
				<option value=6>6</option>
				<option value=7>7</option>
			</select>
			<input name="rd" type="radio" value="1"/>
			<input name="rd" type="radio" value="2" checked/>
			<input name="rd" type="radio" value="3" />
			<input type="submit" name="submit" value="enviar" />
		</form>
</div>
</div>
<br><br>
<div style="text-align:center;"><h2>leimnud.module.app.confirm</h2></div>
<pre class="explain_code">
new leimnud.module.app.confirm().make(
{
   label:"Está a punto de cerrar 321 pestañas. ¿Está seguro de querer continuar?",
   action:<b style='color:green;'>functionOnTRUE</b>,
   cancel:<b style='color:red;'>functionOnFALSE</b> //Optional
});
</pre>
Sample: <input class="module_app_button___gray" type="button" value="Confirm" onclick="new leimnud.module.app.confirm().make({label:'Está a punto de cerrar 321 pestañas. ¿Está seguro de querer continuar?'});">

<div style="text-align:center;"><h2>leimnud.module.app.alert</h2></div>
<pre class="explain_code">
new leimnud.module.app.alert().make(
{
   label:"Actualización exitosa",
   action:<b style='color:green;'>functionOnTRUE</b> //Optional   
});
</pre>
Sample: <input type="button" value="Alert" onclick="new leimnud.module.app.alert().make({label:'Actualización exitosa'});">

<div style="text-align:center;"><h2>leimnud.module.app.prompt</h2></div>
<pre class="explain_code">
new leimnud.module.app.prompt().make(
{
   label:"Enter your name:",
   action:function(value)
   {
   	alert(value);
   }
});
</pre>
Sample: <input type="button" value="Prompt" onclick="new leimnud.module.app.prompt().make({label:'Enter your name:',
   action:function(value)
   {
   	alert(value);
   }});">
