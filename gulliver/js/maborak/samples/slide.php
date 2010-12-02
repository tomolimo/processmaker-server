<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>drag</title>
		<script type="text/javascript" src="../core/maborak.js"></script>
		<script type="text/javascript">
		var leimnud = new maborak();
		leimnud.make();
		leimnud.Package.Load("rpc,drag,drop,panel,app,fx",{Instance:leimnud,Type:"module"});
		leimnud.Package.Load("json",{Type:"file"});
		leimnud.exec(leimnud.fix.memoryLeak);
		</script>
		<script type="text/javascript">
		var b,c;
		leimnud.event.add(window,"load",function(){
		leimnud.event.add(leimnud.dom.element("a"),"mouseup",function(){
			b=new leimnud.module.app.lightbox();
			b.make({
				images	:[
					"http://muzyka.onet.pl/_i/metal_hammer/inne/lacuna_coil.jpg",
					"http://www.sandraschleret.net/bandlogo.jpg",
					"http://upload.wikimedia.org/wikipedia/commons/thumb/1/1f/Cristina_Scabbia.jpg/800px-Cristina_Scabbia.jpg",
					"http://www.non.cl/imagenes/imagenesnoticias/cristina.gif",
					"http://www.metalsymphony.com/noticias/hotrev.jpg",
					"http://www2.centurymedia.com/eteam/blog/office_visits/cristina.jpg",
					"http://usuarios.fotolog.cl/j/a/jArDiNdErOsAs/27022007-01_26_08.jpg",
					"http://www.rock-in-chair.com/upload/cin6.jpg",
					"http://www.verbicidemagazine.com/images/interview/issue17/lacuna3.jpg",
					"http://centurymedia.com/temp/revolvercover/REV0707coverV4.jpg",
					"http://www.musicaldiscoveries.com/images/lc2006/cs2006b.jpg",
					"http://quehacer.sifunca.net/delete/MauiPHP.JPG"
				],
				initIn:0,
				resize:false,
				size:{w:400,h:310},
				target:leimnud.dom.element("target")
			});
		});
		leimnud.event.add(leimnud.dom.element("b"),"mouseup",function(){			
			c=new leimnud.module.app.slide();
			c.make({
				images	:[
					"http://muzyka.onet.pl/_i/metal_hammer/inne/lacuna_coil.jpg",
					"http://www.sandraschleret.net/bandlogo.jpg",
					"http://upload.wikimedia.org/wikipedia/commons/thumb/1/1f/Cristina_Scabbia.jpg/800px-Cristina_Scabbia.jpg",
					"http://www.non.cl/imagenes/imagenesnoticias/cristina.gif",
					"http://www.metalsymphony.com/noticias/hotrev.jpg",
					"http://www2.centurymedia.com/eteam/blog/office_visits/cristina.jpg",
					"http://usuarios.fotolog.cl/j/a/jArDiNdErOsAs/27022007-01_26_08.jpg",
					"http://www.rock-in-chair.com/upload/cin6.jpg",
					"http://www.verbicidemagazine.com/images/interview/issue17/lacuna3.jpg",
					"http://centurymedia.com/temp/revolvercover/REV0707coverV4.jpg",
					"http://www.musicaldiscoveries.com/images/lc2006/cs2006b.jpg",
				],
				thumbnail:{
					images:[
						{
							title:"Mi escritorio con fondo de Lame Immortelle",
							src:"http://muzyka.onet.pl/_i/metal_hammer/inne/lacuna_coil.jpg"
						},
						{
							title:"as 1",
							src:"http://muzyka.onet.pl/_i/metal_hammer/inne/lacuna_coil.jpg"
						},
						{
							title:"as 2",
							src:"http://muzyka.onet.pl/_i/metal_hammer/inne/lacuna_coil.jpg"
						},
						{
							title:"as 3",
							src:"http://muzyka.onet.pl/_i/metal_hammer/inne/lacuna_coil.jpg"
						},
						{
							title:"as 0",
							src:"http://muzyka.onet.pl/_i/metal_hammer/inne/lacuna_coil.jpg"
						},
						{
							title:"as 1",
							src:"http://muzyka.onet.pl/_i/metal_hammer/inne/lacuna_coil.jpg"
						},
						{
							title:"as 2",
							src:"http://muzyka.onet.pl/_i/metal_hammer/inne/lacuna_coil.jpg"
						},
						{
							title:"as 3",
							src:"http://muzyka.onet.pl/_i/metal_hammer/inne/lacuna_coil.jpg"
						},
						{
							title:"as 0",
							src:"http://muzyka.onet.pl/_i/metal_hammer/inne/lacuna_coil.jpg"
						},
						{
							title:"as 1",
							src:"http://muzyka.onet.pl/_i/metal_hammer/inne/lacuna_coil.jpg"
						},
						{
							title:"as 2",
							src:"http://muzyka.onet.pl/_i/metal_hammer/inne/lacuna_coil.jpg"
						},
						{
							title:"as 3",
							src:"http://muzyka.onet.pl/_i/metal_hammer/inne/lacuna_coil.jpg"
						}
					]//,
					//size:{}
				},
				initIn:0,
				resize:false,
				//size:{w:400,h:310},
				target:$("target")
			});
		});
		});
		</script>
		<style>
			body{
				padding:0px;
				margin:0px;
				background-color:#EEE
			}
		</style>
		<link rel="stylesheet" type="text/css" href="http://mauricio.newworkflow.colosa.net/skins/styles/simple/style.css" />
</head>
<body>
	<div id="target" style="border:1px solid #000;padding:10px;height:500px;position:relative;margin:10px;background-color:white;"></div>
	<input type="button" value="Lightbox" id="a">
	<input type="button" value="Horizontal Slide" id="b">
</body>
</html>
