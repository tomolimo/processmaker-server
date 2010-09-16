var form_cGNXUnoxwrBvcDVYaXRhVGczcHlueEtEWm1KR28yWnc___;
var i;
function loadForm_cGNXUnoxwrBvcDVYaXRhVGczcHlueEtEWm1KR28yWnc___(ajaxServer)
{
if (typeof(G_Form)==='undefined') return alert('form.js was not loaded');
  form_cGNXUnoxwrBvcDVYaXRhVGczcHlueEtEWm1KR28yWnc___=new G_Form(document.getElementById('cGNXUnoxwrBvcDVYaXRhVGczcHlueEtEWm1KR28yWnc___'),'cGNXUnoxwrBvcDVYaXRhVGczcHlueEtEWm1KR28yWnc___');
  var myForm=form_cGNXUnoxwrBvcDVYaXRhVGczcHlueEtEWm1KR28yWnc___;
  myForm.ajaxServer=ajaxServer;
                  i = myForm.aElements.length;
      myForm.aElements[i] = new G_DropDown(myForm, myForm.element.elements['form[USR_NAME]'],'USR_NAME');
      myForm.aElements[i].setAttributes({"label":"Usuario:","group":0,"mode":"edit","defaultValue":"","required":false,"dependentFields":"","readonly":false,"option":[],"sqlOption":[],"options":[]});
                i = myForm.aElements.length;
      myForm.aElements[i] = new G_DropDown(myForm, myForm.element.elements['form[USR_APPLICATION]'],'USR_APPLICATION');
      myForm.aElements[i].setAttributes({"label":"Aplicaci\u00f3n:","group":0,"mode":"edit","defaultValue":"","required":false,"dependentFields":"USR_ROLE","readonly":false,"option":["select application"],"sqlOption":[],"options":["select application"]});
                i = myForm.aElements.length;
      myForm.aElements[i] = new G_DropDown(myForm, myForm.element.elements['form[USR_ROLE]'],'USR_ROLE');
      myForm.aElements[i].setAttributes({"label":"Rol:","group":0,"mode":"edit","defaultValue":"","required":false,"dependentFields":"","readonly":false,"option":[],"sqlOption":[],"options":[]});
                                                          myForm.getElementByName('USR_APPLICATION').setDependentFields('USR_ROLE');
                                    }
