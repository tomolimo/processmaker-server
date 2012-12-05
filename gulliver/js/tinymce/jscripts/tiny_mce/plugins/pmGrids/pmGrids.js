$(document).ready(function () {
    var getGridList = function(){
        var responseData
//      responseData = '[{"id":"grid_01","name":"grid_01"},{"id":"grid_02","name":"grid_02"}]';
        var url = tinyMCE.activeEditor.domainURL+"processes/processes_Ajax";
        responseData = $.ajax({
            url : url,
            type: "POST",
            data: {action : 'getGridList', PRO_UID: tinyMCE.activeEditor.processID},
            async: false,
            dataType: "json"           
        }).responseText;
        responseData = eval("(" +responseData+ ")");
        return responseData;
    }
    
    var getGridFieldList = function(gridUid){
        var responseData = $.ajax({
            url : tinyMCE.activeEditor.domainURL+"processes/processes_Ajax",
            type: "POST",
            data: {action : 'getVariableGrid', PRO_UID: tinyMCE.activeEditor.processID, DYN_UID: gridUid},
            dataType: "json",
            async:false
        }).responseText;
        
        responseData = eval("("+responseData+")");
        $('#listContainer').html('');
        var divHeader = '<tr>';
        var divCell   = '<tr>';
        $.each(responseData, function(index, element){
            divHeader += "<td><input type='checkbox' class='headerField' name='headerField' value='"+element+"'/><input type='text' style='width:80px;' name='dynafield' class='dynaField' value='"+element+"' id='"+element+"'></td>";
            divCell   += "<td align='center'><input type='hidden' id='field_"+element+"' value='"+element+"'>"+element+"</td>";            
        });
        divHeader += '</tr>';
        divCell   += '</tr>';
        $('#listContainer').append(divHeader+divCell);            
    };
    
    var generateListValues = function(){
        var list = getGridList();
        var combo = document.getElementById("gridList");
        var option = document.createElement('option');
        for(i=(combo.length-1); i>=0; i--)
        {
            var aDelete = combo.options[i];
            aDelete.parentNode.removeChild(aDelete);
        }

        if(list.length>0){
            for(i=0; i<list.length; i++)
            {
               option = document.createElement("OPTION");
               option.value = list[i].sXmlForm;
               option.text  = list[i].sName;
               combo.add(option);
            }
        } else {
            option = document.createElement("OPTION");
            option.value = 0;
            option.text = 'No Grids';
            combo.add(option);
        }
    }
        
    var insertFormatedGrid = function(){
        var gridName = $("#gridList").val();
        var tableCode = "<table>"
        var gridCode  = "<!--"+gridName+"@>-->";
        var headerCode = "<tr>";
        var fieldCode  = "<tr>";
        
        $('#listContainer .headerField').each(function(i){
            if (this.checked == true) {
                headerCode += "<td>"+$('#'+this.value).val()+"</td>";
                fieldCode += "<td>"+$('#prefixList').val()+$('#field_'+this.value).val()+"</td>";
            }
        });
        headerCode += "</tr>";
        fieldCode  += "</tr>";
        if ($("#headersCheckbox").attr("checked")!="checked"){
            headerCode = '';
        }
        gridCode  += headerCode+fieldCode+"<!--@<"+gridName+"-->";
        tableCode += gridCode+"</table>"
        updateEditorContent (tableCode);
    }
    
    $('#gridList').change(function(){
        getGridFieldList($(this).val());
    });
    
    $('#addButton').click(function(){
        insertFormatedGrid();
    });
    
    $('#cancelButton').click(function(){
        closePluginPopup();
    });
    
    generateListValues();
    getGridFieldList($("#gridList").val());
});