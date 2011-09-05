<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>order</title>
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
			b = new leimnud.module.app.order().make({
				target:document.body
			});
		});
		</script>
		<style>
			body{
				
				margin:10px;
				background-color:#EEE;
				border:1px solid #666;
				height:300px;
				position:relative;
			}
		</style>
		<link rel="stylesheet" type="text/css" href="style.panel.css" />
</head>
<body>
</body>
</html>