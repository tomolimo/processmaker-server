var oLeyendsPanel;

var showUploadedDocumentTracker = function(APP_DOC_UID) {
  oPanel2 = new leimnud.module.panel();
  oPanel2.options = {
  	size	:{w:300,h:300},
  	position:{x:0,y:0,center:true},
  	title	:'',
  	theme	:'processmaker',
  	statusBar:true,
  	control	:{resize:false,roll:false},
  	fx	:{modal:true,opacity:true,blinkToFront:true,fadeIn:false}
  };
  oPanel2.events = {
  	remove: function() { delete(oPanel2); }.extend(this)
  };
  oPanel2.make();
  oPanel2.loader.show();
  var oRPC = new leimnud.module.rpc.xmlhttp({
  	url : 'tracker_Ajax',
  	args: 'action=showUploadedDocumentTracker&APP_DOC_UID=' + APP_DOC_UID
  });
  oRPC.callback = function(rpc){
  	oPanel2.loader.hide();
  	var scs=rpc.xmlhttp.responseText.extractScript();
  	oPanel2.addContent(rpc.xmlhttp.responseText);
  	scs.evalScript();
  }.extend(this);
  oRPC.make();
};

var showGeneratedDocumentTracker = function(APP_DOC_UID) {
  oPanel2 = new leimnud.module.panel();
  oPanel2.options = {
  	size	:{w:300,h:250},
  	position:{x:0,y:0,center:true},
  	title	:'',
  	theme	:'processmaker',
  	statusBar:true,
  	control	:{resize:false,roll:false},
  	fx	:{modal:true,opacity:true,blinkToFront:true,fadeIn:false}
  };
  oPanel2.events = {
  	remove: function() { delete(oPanel2); }.extend(this)
  };
  oPanel2.make();
  oPanel2.loader.show();
  var oRPC = new leimnud.module.rpc.xmlhttp({
  	url : 'tracker_Ajax',
  	args: 'action=showGeneratedDocumentTracker&APP_DOC_UID=' + APP_DOC_UID
  });
  oRPC.callback = function(rpc){
  	oPanel2.loader.hide();
  	var scs=rpc.xmlhttp.responseText.extractScript();
  	oPanel2.addContent(rpc.xmlhttp.responseText);
  	scs.evalScript();
  }.extend(this);
  oRPC.make();
};
/*
var tracker_MessagesView = function(APP_UID) { alert(123);
  oPanel2 = new leimnud.module.panel();
  oPanel2.options = {
  	size	:{w:300,h:300},
  	position:{x:0,y:0,center:true},
  	title	:'',
  	theme	:'processmaker',
  	statusBar:true,
  	control	:{resize:false,roll:false},
  	fx	:{modal:true,opacity:true,blinkToFront:true,fadeIn:false}
  };
  oPanel2.events = {
  	remove: function() { delete(oPanel2); }.extend(this)
  };
  oPanel2.make();
  oPanel2.loader.show();
  var oRPC = new leimnud.module.rpc.xmlhttp({
  	url : 'tracker_Ajax',
  	args: 'action=tracker_MessagesView&APP_UID=' + APP_UID
  });
  oRPC.callback = function(rpc){
  	oPanel2.loader.hide();
  	var scs=rpc.xmlhttp.responseText.extractScript();
  	oPanel2.addContent(rpc.xmlhttp.responseText);
  	scs.evalScript();
  }.extend(this);
  oRPC.make();
};
*/