/**
 * Reassign ByUser routines
 * Author Erik Amaru Ortiz <erik@colosa.com> 
 */

var reassignPanel;

function toReassignPanel(){
	if( checks_selected_ids.length == 0 ){
		new leimnud.module.app.alert().make({label: G_STRINGS.ID_REASSIGN_BYUSER});
		return 0;
	}
	
	/*oPanel = new leimnud.module.panel();
	oPanel.options = {
	  	size	: { w:1000, h:600 },
	  	position: { x:0,y:0,center:true },
	  	title	: G_STRINGS.ID_MSG_RESSIGN_BYUSER_PANEL,
	  	statusBar: false,
	  	control	: {resize:false,roll:false,drag:true},
	  	fx	: { modal:true, opacity:true, blinkToFront:false, fadeIn:false, drag:true}
  	};
  	oPanel.events = {
  		remove: function() { 
  			delete(oPanel);
  			//resetChecks();
  			//window.location = 'cases_ReassignByUser?REASSIGN_USER=' + getField('REASSIGN_USER').value;
  		}.extend(this)
  	};*/
	//oPanel.make();
	//oPanel.loader.show();
	
	var USER_SELETED = getField('REASSIGN_USER').value;
	
	var oRPC = new leimnud.module.rpc.xmlhttp({
	  	url : 'cases_Ajax',
	  	args: 'action=reassignByUserList&APP_UIDS='+checks_selected_ids+'&FROM_USR_ID='+USER_SELETED
  	});
  	oRPC.callback = function(rpc) {
	  	//oPanel.loader.hide();
	  	//var scs=rpc.xmlhttp.responseText.extractScript();
	  	//oPanel.addContent(rpc.xmlhttp.responseText);
	  	//scs.evalScript();
  		document.getElementById("publisherContent[0]").style.display  = 'none';
  		document.getElementById("publisherContent[1]").style.display  = 'none';
  		document.getElementById("publisherContent[10]").style.display = 'block';
  		document.getElementById("publisherContent[10]").innerHTML = rpc.xmlhttp.responseText; 
	  	
  	}.extend(this);
	oRPC.make();
	
	//reassignPanel = oPanel;
}

function toReassign(){
	var selects = document.getElementsByName('form[USERS]');
	var USER_SELETED = getField('REASSIGN_USER').value;
	var items = '';
	for(i=0; i<selects.length; i++){
		if( selects[i].value != "0" ){
			if( items != '') items += ',';
			id = selects[i].id;
			id = id.trim();
			items += selects[i].id.substring(5, selects[i].id.length-1) +'|'+ selects[i].value;
		}
	}
	
	if( items.trim() == '' ){
		new leimnud.module.app.alert().make({label: G_STRINGS.ID_REASSIGN_BYUSER});
		return 0;
	}
	
	new leimnud.module.app.confirm().make({
  		label:G_STRINGS.ID_REASSIGN_BYUSER_CONFIRM,
    	action:function(){
			var oRPC = new leimnud.module.rpc.xmlhttp({
			  	url : 'cases_Ajax',
			  	args: 'action=reassignByUser&items='+items+'&USR_UID='+USER_SELETED
		  	});
			//reassignPanel.loader.show();
		  	oRPC.callback = function(rpc) {
		  		//reassignPanel.loader.hide();
		  		//reassignPanel.clearContent();
		  		/*reassignPanel.events = {
		  		  		remove: function() { 
		  		  			delete(oPanel);
		  		  			window.location = 'cases_ReassignByUser?REASSIGN_USER=' + getField('REASSIGN_USER').value;
		  		  		}.extend(this)
		  		  	};*/
			  	var scs=rpc.xmlhttp.responseText.extractScript();
		  		//reassignPanel.addContent(rpc.xmlhttp.responseText);
		  		document.getElementById("publisherContent[10]").innerHTML = rpc.xmlhttp.responseText; 
			  	scs.evalScript();
		  	}.extend(this);
			oRPC.make();
			
		}.extend(this)
	});
}

function cancelReassign(){
	document.getElementById("publisherContent[0]").style.display  = 'block';
	document.getElementById("publisherContent[1]").style.display  = 'block';
	document.getElementById("publisherContent[10]").style.display = 'none';
	document.getElementById("publisherContent[10]").innerHTML = '';
}

function resetChecks(){
	checks_selected_ids.length = 0;
}


function WindowSize() {
  var wSize = [0, 0];
  if (typeof window.innerWidth != 'undefined')
  {
	  wSize = [
        window.innerWidth,
        window.innerHeight
    ];
  }
  else if (typeof document.documentElement != 'undefined'
      && typeof document.documentElement.clientWidth !=
      'undefined' && document.documentElement.clientWidth != 0)
  {
	  wSize = [
        document.documentElement.clientWidth,
        document.documentElement.clientHeight
    ];
  }
  else   {
	  wSize = [
        document.getElementsByTagName('body')[0].clientWidth,
        document.getElementsByTagName('body')[0].clientHeight
    ];
  }
  return wSize;
}

 