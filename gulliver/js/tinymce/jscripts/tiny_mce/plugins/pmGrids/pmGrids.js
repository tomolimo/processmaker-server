 $(document).ready(function () {

    var getGridList = function(){
        var responseData
        responseData = '[{"id":"grid_01","name":"grid_01"},{"id":"grid_02","name":"grid_02"}]';
        /*$.ajax({
            url : "/processes/processes_Ajax",
            type: "POST",
            data: {action : 'getDynagridList', PRO_UID: tinyMCE.activeEditor.processID},
            dataType: "html",
            
            success: function (data) { 
                
                $.each(data, function(index, element) {
                    $('#listContainer').append($('<div class="gridCell">', {
                        text: element.name
                    }));
                });
            },
            failure: function(){
//                responseData = '[{"id":"1","name":"grid_01"},{"id":"2","name":"grid_02"}]';
            }
        });*/
        responseData = eval ("(" +responseData+ ")");
        return responseData;
    }
    
    var getGridFieldList = function (gridUid){
        var responseData
        if (gridUid=='1'||gridUid==1){
            responseData = eval ('([{"id":"1","name":"field01"},{"id":"2","name":"field02"}])');
        } else {
            responseData = eval ('([{"id":"3","name":"field03"},{"id":"4","name":"field04"}])');
        }
        /*$.ajax({
            url: "/processes/processes_Ajax",
            type: "POST",
            data: {action : 'getGridFieldList', PRO_UID: tinyMCE.activeEditor.processID, DYN_UID: gridUid},
            dataType: "json",
            success: function (data) { 
                $.each(responseData, function(index, element) {
                    $('#listContainer').append($('<div class="gridCell">', {
                        text: element.name
                    }));
                });
            }
        });*/
        
        $('#listContainer').html('');
        var divHeader = '<tr>';
        var divCell   = '<tr>';
        $.each(responseData, function(index, element){
            divHeader += "<td><input type='checkbox' class='headerField' name='headerField' value='"+element.name+"'/><input type='text' style='width:100px;' name='dynafield' class='dynaField' value='"+element.name+"' id='"+element.name+"'></td>";
            divCell   += "<td align='center'><input type='hidden' id='field_"+element.name+"' value='"+element.name+"'>"+element.name+"</td>";            
        });
        divHeader += "</tr>";
        divCell   += "</tr>";
            
        $('#listContainer').append(divHeader+divCell);

    }
    
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
               option.value = list[i].id;
               option.text  = list[i].name;
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

        gridCode  += fieldCode+"<!--@<"+gridName+"-->";
        tableCode += gridCode+"</table>"
        updateEditorContent (tableCode);
        closePluginPopup();
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