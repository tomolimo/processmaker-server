var form_cGNXUnoxwrBsbzV6VnVaWFc2V094MzUw;
var i;
function loadForm_cGNXUnoxwrBsbzV6VnVaWFc2V094MzUw(ajaxServer)
{
if (typeof(G_Form)==='undefined') return alert('form.js was not loaded');
  form_cGNXUnoxwrBsbzV6VnVaWFc2V094MzUw=new G_Form(document.getElementById('cGNXUnoxwrBsbzV6VnVaWFc2V094MzUw'),'cGNXUnoxwrBsbzV6VnVaWFc2V094MzUw');
  var myForm=form_cGNXUnoxwrBsbzV6VnVaWFc2V094MzUw;
  myForm.ajaxServer=ajaxServer;
                  i = myForm.aElements.length;
      myForm.aElements[i] = new G_DropDown(myForm, myForm.element.elements['form[ROL_APPLICATION]'],'ROL_APPLICATION');
      myForm.aElements[i].setAttributes({"label":"Aplicaci\u00f3n:","group":0,"mode":"view","defaultValue":"","required":false,"dependentFields":"","readonly":false,"option":[],"sqlOption":[],"options":[]});
                i = myForm.aElements.length;
      myForm.aElements[i] = new G_Text(myForm, myForm.element.elements['form[ROL_CODE]'],'ROL_CODE');
      myForm.aElements[i].setAttributes({"label":"C\u00f3digo de Rol:","group":0,"mode":"edit","size":"25","validate":"4","mask":"","maxLength":"24","defaultValue":"","required":false,"dependentFields":"","linkField":"","strTo":"","readOnly":false,"sqlOption":[],"formula":"","function":"","options":[]});
                i = myForm.aElements.length;
      myForm.aElements[i] = new G_Text(myForm, myForm.element.elements['form[ROL_DESCRIPTION]'],'ROL_DESCRIPTION');
      myForm.aElements[i].setAttributes({"label":"Descripci\u00f3n:","group":0,"mode":"edit","size":"35","validate":"4","mask":"","maxLength":"120","defaultValue":"","required":false,"dependentFields":"","linkField":"","strTo":"","readOnly":false,"sqlOption":[],"formula":"","function":"","options":[]});
                                                        }
