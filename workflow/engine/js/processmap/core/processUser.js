var panel;
var panDel=function(uid)
{
	  panel =new leimnud.module.panel();
						panel.options={	
							size	:{w:500,h:400},
							position:{x:50,y:30,center:true},																												
							control	:{close:true,resize:false},fx:{modal:true},
							statusBar:false,
							fx	:{shadow:true,modal:true}
						};						
						panel.make();						
						panel.loader.show();
						var r = new leimnud.module.rpc.xmlhttp({
							url:"processes_AssigProcessUser.php"
						});
						r.callback=function(rpc)
						{
							panel.loader.hide();
							panel.addContent(rpc.xmlhttp.responseText);						
						};
						r.make();						
}
