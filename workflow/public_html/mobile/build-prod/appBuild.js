/**
 * [console.log function for fake execute in IOS]
 */
/*console.log = function(log) {
  var iframe = document.createElement("IFRAME");
  iframe.setAttribute("src", "ios-log:#iOS#" + log);
  document.documentElement.appendChild(iframe);
  iframe.parentNode.removeChild(iframe);
  iframe = null;  
};*/



/**
 * Function viewNewCase
 * Description: load the form with parameters
 * (server,workspace,accesToken,refreshToken, procId, taskID, formsArrayJSON, formId, jsonForm)  
 */
var viewNewCase = function (server,workspace,accessToken,refreshToken,caseFakeID,processId, taskId, arrayForms, formID, jsonForm, dataForm){    
    var seg, mil,date=new Date();
    seg = date.getUTCSeconds();
    mil= date.getUTCMilliseconds();

    var tokens,         
        dataJson, 
        arrayForms;
	tokens= {		
		accessToken:accessToken,
		refreshToken:refreshToken
	};

    //maskLoading.showMessage();
    
    if(jsonForm){          
        if(typeof jsonForm === "string"){                        
            dataJson = JSON.parse(jsonForm);
        }
        if(typeof jsonForm === "object"){                    
            dataJson = jsonForm;
        }
    }else{
    	dataJson = null;
    }
    
    if(arrayForms){
        if(typeof arrayForms === "string"){            
            arrayForms=JSON.parse(arrayForms);                        
        }            
            //arrayForms=JSON.parse(arrayForms);
        if(typeof arrayForms === "object")                    
            arrayForms = arrayForms;
    }

    if(dataForm){
        if(typeof dataForm === "string"){                        
              dataForm = JSON.parse(dataForm);      
        }
    }    
    
    window.dynaform = new PMDynaform.core.ProjectMobile({        
            server: server,
            caseFakeID:caseFakeID,
            processID: processId,
            taskID: taskId,
            workspace:workspace,
            formID:formID,
            token:tokens,
            submitRest:true,
            dynaforms : arrayForms,
            data: dataJson,
            dataForm:dataForm,
            onLine:true
        }); 

	window.dynaform.loadNewCase(); 
    date = new Date();
    seg= date.getUTCSeconds() -seg;
    mil = date.getUTCMilliseconds()-mil;
     if(mil < 0){
      seg--;
      mil=mil+1000;   
    }
    console.log("--"+seg+":"+mil+"--");  
     
};

var viewCase = function (server,workspace,accessToken,refreshToken,processId,taskId,caseId, typeList, formsArray, formID, jsonForm, dataForm){  
	 var seg, mil,date=new Date();
    seg = date.getUTCSeconds();
    mil= date.getUTCMilliseconds();

    var jsonf=null;   
	tokens= {		
		accessToken:accessToken,
		refreshToken:refreshToken
	};

    //maskLoading.showMessage();
    
    if(jsonForm){          
        if(typeof jsonForm === "string"){                        
            data=JSON.parse(jsonForm);
        }
        if(typeof jsonForm === "object"){                    
            data = jsonForm;
        }
    }else{
        data = null;
    }
    
    if(formsArray){
        if(typeof formsArray === "string"){            
            arrayForms=JSON.parse(formsArray);  
        }            
            //arrayForms=JSON.parse(formsArray);
        if(typeof formsArray === "object")                    
            arrayForms = formsArray;
    } 

     if(dataForm){
        if(typeof dataForm === "string"){            
            dataForm = JSON.parse(dataForm);    
        }            
                   
    }    
    
    window.dynaform = new PMDynaform.core.ProjectMobile({        
            server: server,
            workspace:workspace,            
            token:tokens,
            processID: processId,            
            caseID: caseId,
            taskID: taskId,
            typeList:typeList,
            dynaforms: arrayForms,
            formID:formID,
            data:data,
            dataForm:dataForm,
            submitRest:true 
        });    	
    window.dynaform.loadCase(); 
     date = new Date();
    seg= date.getUTCSeconds() -seg;
    mil = date.getUTCMilliseconds()-mil;
    if(mil < 0){
      seg--;
      mil=mil+1000;   
    }
    console.log("--"+seg+":"+mil+"--");           
};

var loadToolbar = function() {
	var div = document.createElement("div");
	div.style.cssText = "float:right; margin:1%;";
	div.innerHTML = '<button type="button" onclick="previousSample();" class="btn btn-info">previous form</button>  <button type="button" onclick="nextSample();return false;" class="btn btn-info">next form</button>';
	$(document.body).prepend(div);

};

/**
 * [adjustHeight description]
 * @return {[type]} [description] RFC
 */
var adjustHeight = function() {
    var windowHeight, 
        containerHeight;
    windowHeight= $(window).height();
    $("#container")[0].style.height= "auto";
    containerHeight = $("#container").height();
    if(containerHeight+50 < windowHeight){
        $("#container").height(windowHeight);
    }     
};

/*var maskLoading = {
    msgTpl : _.template($('#tpl-loading').html()),
    showMessage: function (){
        $('body').append(this.msgTpl({
            title : "Loading",
            msg: "Please wait while the data is loading..."
        }));
        //$('body').find("#shadow-form").css("height",this.view.$el.height()+"px");
    },
    hideMessage:function (){
        $('body').find(".pmdynaform-form-message-loading").remove();
        $("#shadow-form").remove();
    }
};*/

var kitKatMode = null;
var setKitKatMode = function(value) {
    kitKatMode = value;     
};