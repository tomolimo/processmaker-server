//function onLoad(){
  //  generateListValues(document.getElementById('prefix').value);
//}
//window.onload=function(){
    //generateListValues(document.getElementById('prefix').value);
//};

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

var getPrefix = function (prefix) {
    if(document.getElementById('prefix').value=='ID_TO_STRING')
        prefix='@@';
    else if(document.getElementById('prefix').value=='ID_TO_FLOAT')
        prefix='@#';
    else if(document.getElementById('prefix').value=='ID_TO_INTEGER')
        prefix='@%';
    else if(document.getElementById('prefix').value=='ID_TO_URL')
        prefix='@?';
    else if(document.getElementById('prefix').value=='ID_SQL_ESCAPE')
        prefix='@$';
    else if(document.getElementById('prefix').value=='ID_REPLACE_WITHOUT_CHANGES')
        prefix='@=';
    return prefix;
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
    var prefix=getPrefix(document.getElementById('prefix').value);
    generateListValues(prefix);
});

leimnud.event.add(document.getElementById('prefix'), 'change', function(event) {
    var prefix=getPrefix(document.getElementById('prefix').value);
    generateListValues(prefix);
});

leimnud.event.add(document.getElementById('_Var_Form_'), 'change', function(event) {
    document.getElementById('selectedVariableLabel').textContent = document.getElementById('_Var_Form_').value
});

leimnud.event.add(document.getElementById('search'), 'keypress', function(e) {
    
    var prefix=getPrefix(document.getElementById('prefix').value);
    var key = e.keyCode;
    if(key == '13'){
        generateListValues(prefix);
        
        e.cancelBubble = true;
        e.returnValue  = false;
        if (e.stopPropagation) {
            e.stopPropagation();
            e.preventDefault();
        }
    }
});

function generateListValues (prefix){
    
    var list = getVariableList(document.getElementById('search').value, document.getElementById('process').value, document.getElementById('type_variables').value);
    var combo = document.getElementById("_Var_Form_");
    var option = document.createElement('option');
    for (m=combo.options.length-1;m>=0;m--){
        combo.options[m]=null
    }
    if(list.length>0){
        for (var i=0; i< list.length; i++){
            combo.options.add(option, 0);
            combo.options[0].value = list[i].sName;
            combo.options[0].text = prefix+list[i].sName+' ('+list[i].sLabel+')';
        }
    } else {
        combo.options.add(option, 0);
        combo.options[0].value = '0';
        combo.options[0].text = 'No results';
    }        
}
