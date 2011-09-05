var panel;
var panDel=function(uid)
{
	  panel =new leimnud.module.panel();
						panel.options={	
							size	:{w:800,h:400},
							position:{x:50,y:50,center:true},																												
							control	:{close:true,resize:false},fx:{modal:true},
							statusBar:false,
							fx	:{shadow:true,modal:true}
						};						
						panel.make();						
						panel.loader.show();
						var r = new leimnud.module.rpc.xmlhttp({
							url:"reports_Description.php",
							method:"POST",
							args:"PRO_UID="+uid
						});
						r.callback=function(rpc)
						{
							panel.loader.hide();
							panel.addContent(rpc.xmlhttp.responseText);						
						};
						r.make();						
};


var reports_Description_filter = function(sFrom, sTo, sStartedby, sProuid) {	  
  var oRPC = new leimnud.module.rpc.xmlhttp({		
		url  : '../reports/reports_Description',		
		args : 'action=reports_Description_filter&FROM=' + sFrom + '&TO=' + sTo + '&STARTEDBY=' + sStartedby + '&PRO_UID=' + sProuid
  });
  panel.clearContent();
  oRPC.callback=function(rpc)
						{
							//panel.loader.hide();
							panel.addContent(rpc.xmlhttp.responseText);						
						};						
  oRPC.make(); 
       
  
};
