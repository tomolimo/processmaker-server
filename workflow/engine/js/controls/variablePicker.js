var getValue = function (list) {
	console.log(list.value);
}

var getVariableList = function (queryText, proUid, varType){
    varType = varType.toLowerCase();
    var responseData
    var oRPC = new leimnud.module.rpc.xmlhttp({
        url   : "../processes/processes_Ajax",
        async : false,
        method: "POST",
        args  : "action=getVariableList&process="+proUid+"&queryText="+queryText+"&type="+varType
    }); 
    
     oRPC.make();
     responseData = eval ("(" +oRPC.xmlhttp.responseText+ ")");
     return responseData;
}

leimnud.event.add(document.getElementById('type_variables'), 'change', function(event) {
	console.log('Dropdown Type of Variables');
});

leimnud.event.add(document.getElementById('prefix'), 'change', function(event) {
	console.log('Dropdown Prefix');
});


leimnud.event.add(document.getElementById('search'), 'keypress', function(e) {
	var key = e.keyCode;
	if(key == '13')
	{
            var list = getVariableList('nuev','2527075735085b447a58523099748463','system');
            for (var i=0; i< list.length; i++){
                console.log(list[i].sName);
            }
            e.cancelBubble = true;
            e.returnValue = false;
            if (e.stopPropagation) {
                e.stopPropagation();
                e.preventDefault();
            }
            return false;
	}
});

leimnud.event.add(document.getElementById('type_variables'), 'change', function(event) {
    console.log('Dropdown Type of Variables');
});

