var getValue = function (list) {   
    insertFormVar(document.getElementById('selectedField').value,list.value);
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
    //alert(oRPC.xmlhttp.responseText);
    responseData = eval ("(" +oRPC.xmlhttp.responseText+ ")");
    
    return responseData;
}

var getPrefixInfo = function (prefix){
    var oRPC = new leimnud.module.rpc.xmlhttp({
        url   : "../processes/processes_Ajax",
        async : false,
        method: "POST",
        args  : "action=getVariablePrefix&prefix="+prefix
    });    
    oRPC.make();
    return oRPC.xmlhttp.responseText;
}

leimnud.event.add(document.getElementById('type_variables'), 'change', function(event) {
    generateListValues();
});

leimnud.event.add(document.getElementById('prefix'), 'change', function(event) {
    document.getElementById('desc_prefix').textContent = getPrefixInfo(document.getElementById('prefix').value);
});

leimnud.event.add(document.getElementById('_Var_Form_'), 'change', function(event) {
    document.getElementById('selectedVariableLabel').textContent = document.getElementById('_Var_Form_').value
});

leimnud.event.add(document.getElementById('search'), 'keypress', function(e) {
	var key = e.keyCode;
	if(key == '13'){
            // elements that will be part of a function
            var list = getVariableList(document.getElementById('search').value, document.getElementById("process").value, document.getElementById('type_variables').value);
            for (var i=0; i< list.length; i++){
                console.log(list[i].sName);
            }
            e.cancelBubble = true;
            e.returnValue  = false;
            if (e.stopPropagation) {
                e.stopPropagation();
                e.preventDefault();
            }
            //return false;
	}
});

var generateListValues = function(){
	
	var list = getVariableList('', document.getElementById("process").value, document.getElementById('type_variables').value);
	var combo = document.getElementById("_Var_Form_");
    var option = document.createElement('option');
	for (m=combo.options.length-1;m>=0;m--){
		combo.options[m]=null
		}

	for (var i=0; i< list.length; i++){
        console.log(list[i].sValue);
        var combo = document.getElementById("_Var_Form_");
        var option = document.createElement('option');
        combo.options.add(option, 0);
        combo.options[0].value = list[i].sName;
        combo.options[0].text ='@@'+list[i].sName+'    ('+list[i].sLabel+')';
    }
}
