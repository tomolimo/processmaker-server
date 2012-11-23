var getValue = function (list) {
	console.log(list.value);
}

var getVariableList = function (queryText, proUid, varType){
    varType = varType.toLowerCase();
    var response
    var oRPC = new leimnud.module.rpc.xmlhttp({
        url   : "../processes/processes_Ajax",
        async : false,
        method: "POST",
        args  : "action=getVariableList&process="+proUid+"&queryText="+queryText+"&type="+varType
    });    
    oRPC.callback = function(rpc){
        console.log(rpc.xmlhttp.responseText);
        response = eval(rpc.xmlhttp.responseText);        
    }.extend(this);
    
    oRPC.make();
    console.log(response);
}

leimnud.event.add(document.getElementById('type_variables'), 'change', function(event) {
	console.log('Dropdown Type of Variables');
});

leimnud.event.add(document.getElementById('prefix'), 'change', function(event) {
	console.log('Dropdown Prefix');
});


leimnud.event.add(document.getElementById('search'), 'keypress', function(event) {
	var tecla = event.keyCode;
	if(tecla == '13')
	{
            var list = getVariableList('nuev','2527075735085b447a58523099748463','system');
            console.log(list);
            for (var i in list){
                console.log(list[i]);
            }
            console.log('Has pulsado enter');
            return false;
	}
});

leimnud.event.add(document.getElementById('type_variables'), 'change', function(event) {
	console.log('Dropdown Type of Variables');
});

