var setVariablePickerJS = function(){       
   if (document.getElementById('_Var_Form_').addEventListener)  // W3C DOM
      document.getElementById('_Var_Form_').addEventListener('dblclick', function(){
        if(this.value.substring(0,5) == 'gridt') {
            try {
                updateEditorContent(this.value.substring(5));
                closePluginPopup();
            } catch(err) {
                closePluginPopup();
            }
        } else {
            if (this.getAttribute('displayOption')=='event'){
                e.insertFormVar(this.value.substring(2), this.value.substring(2), 'dyn' );
            } else {
                insertFormVar(document.getElementById('selectedField').value, this.value);
            }
        }
      });
   else if (document.getElementById('selectedField').attachEvent) { // IE DOM
      var element = document.getElementById('_Var_Form_');
      element.attachEvent("ondblclick", function(){
          if(element.value.substring(0,5) == 'gridt') { 
            try {
                updateEditorContent(element.value.substring(5));
                closePluginPopup();
            } catch(err) {
                closePluginPopup();
            }
          } else {         
              if (element.displayOption=='event'){
                  e.insertFormVar(element.value.substring(2), element.value.substring(2), 'dyn' );
              } else {
                  insertFormVar(document.getElementById('selectedField').value, element.value);
              }
          }
      });
   }
    
 
   /**
     * Function getVariableList returns a list with all process variables
     *
     * @access public
     * @param string proUid process ID
     * @param string queryText text searched
     * @param string varType type of variables (System or Process)
     * @return array
     */
    
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

    /**
     * Function getPrefix returns selected prefix
     *
     * @access public
     * @param string prefix
     * @return string
     */
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

    /**
     * Function getPrefixInfo returns a prefix description
     *
     * @access public
     * @param string prefix
     * @return string
     */
    
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
        var selectElement = document.getElementById('_Var_Form_');
        if(selectElement.value.substring(0,5) == 'gridt') {
            document.getElementById('selectedVariableLabel').textContent = selectElement.options[selectElement.selectedIndex].text;
        } else {
            document.getElementById('selectedVariableLabel').textContent = selectElement.value
        }
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

    /**
     * Function generateListValues fills the dropdown with all variables according to filters
     *
     * @access public
     * @param string prefix
     * @return array
     */
    
    function generateListValues (prefix){
        var list = getVariableList(document.getElementById('search').value, document.getElementById('process').value, document.getElementById('type_variables').value);
        var combo = document.getElementById("_Var_Form_");
        var option = document.createElement('option');
        var isBpmn = document.getElementById('isBpmn').value;

        for(i=(combo.length-1); i>=0; i--)
        {
           aBorrar = combo.options[i];
           aBorrar.parentNode.removeChild(aBorrar);
        }

        if(list.length>0){
            for(i=0; i<list.length; i++)
            {
               var optionValue = prefix+list[i].sName;
               if(isBpmn) {
                  if (list[i].sType === "hidden"){
                      list[i].sLabel = "hidden";
                  }
                  if(list[i].sLabel && list[i].sLabel.toLowerCase() == 'grid') {
                     var gridValue = 'gridt<table border=1 cellspacing=0><tr><th>Header_1</th></tr><tbody><!--@>'+list[i].sName+'--><tr><td>column_name1</td></tr><!--@<'+list[i].sName+'--></tbody></table>'; 
                     optionValue = gridValue;
                  }      
               } 
               option = document.createElement("OPTION");
               option.value = optionValue;
               option.text = prefix+list[i].sName+' ('+list[i].sLabel+')';
               combo.add(option);
            }
        } else {
            option = document.createElement("OPTION");
            option.value = 0;
            option.text = 'No results';
            combo.add(option);
        }  
    }
}
// check wether the document has been already loaded or not, 
// whatever the state is this condition ensures that the events are always loaded
if (document.readyState == 'complete'){
    // if completed load the functions and events
    setVariablePickerJS();
} else {
    // if not set the function call in the body onload event
    document.body.onload = setVariablePickerJS;
}

