var oPanel;
var oDashboards = {};
var newDashboard = function() {
  oPanel = new leimnud.module.panel();
  oPanel.options = {
  	size     : {w:500,h:205},
  	position : {x:0,y:0,center:true},
  	title    : '',
  	theme    : 'processmaker',
  	statusBar: true,
  	control  : {resize:false, roll:false},
  	fx       : {modal:true, opacity:true, blinkToFront:true, fadeIn:false}
  };
  oPanel.events = {
  	remove: function() { delete(oPanel); }.extend(this)
  };
  oPanel.make();
  oPanel.loader.show();
  var oRPC = new leimnud.module.rpc.xmlhttp({
  	url : 'dashboardAjax',
  	args: 'action=showAvailableDashboards'
  });
  oRPC.callback = function(rpc){
  	oPanel.loader.hide();
  	var scs = rpc.xmlhttp.responseText.extractScript();
  	oPanel.addContent(rpc.xmlhttp.responseText);
  	scs.evalScript();
  }.extend(this);
  oRPC.make();
};

var addDashboard = function(aDashboard) {
  oPanel.loader.show();
  var oRPC = new leimnud.module.rpc.xmlhttp({
  	url : 'dashboardAjax',
  	args: 'action=addDashboard&sDashboardClass=' + aDashboard[0] + '&sElement=' + aDashboard[1] + '&sType=' + aDashboard[2]
  });
  oRPC.callback = function(rpc){
  	oPanel.loader.hide();
  	oPanel.remove();
    eval(rpc.xmlhttp.responseText);
    $("dashboard").innerHTML = '';
  	window.Da=new leimnud.module.dashboard();
  	Da.make({target:$("dashboard"),data:oDashboards});
  }.extend(this);
  oRPC.make();
};

var removeDashboard = function(sClass, sType, sElement) {
  var oRPC = new leimnud.module.rpc.xmlhttp({
  	url : 'dashboardAjax',
  	args: 'action=removeDashboard&sDashboardClass=' + sClass + '&sType=' + sType + '&sElement=' + sElement
  });
  oRPC.callback = function(rpc){
    eval(rpc.xmlhttp.responseText);
    $("dashboard").innerHTML = '';
  	window.Da=new leimnud.module.dashboard();
  	Da.make({target:$("dashboard"),data:oDashboards});
  }.extend(this);
  oRPC.make();
};