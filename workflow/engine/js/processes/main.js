var panel;
var dropProcess=function(uid)
{
  panel =new leimnud.module.panel();
  panel.options={
    size  :{w:450,h:250},
    position:{x:50,y:50,center:true},
    statusBarButtons:[
     {value:'Delete'},
     {value:G_STRINGS.CANCEL}
    ],
    title :G_STRINGS.ID_PROSESSESCASE,
    control :{close:true,resize:false},fx:{modal:true},
    statusBar:false,
    fx  :{shadow:true,modal:true}
  }; 
  panel.make();
  
  panel.elements.statusBarButtons[0].onmouseup=function()
  {
    window.location="processes_Delete.php?PRO_UID="+uid;
  };
  
  panel.elements.statusBarButtons[1].onmouseup=panel.remove;
  panel.loader.show();
  var r = new leimnud.module.rpc.xmlhttp({
    url:"process_DeleteCases.php",
    method:"GET",
    args:"PRO_UID="+uid
  });
  r.callback=function(rpc)
  {
    panel.loader.hide();
    panel.addContent(rpc.xmlhttp.responseText);
  };
  r.make();
}