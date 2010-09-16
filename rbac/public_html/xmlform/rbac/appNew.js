var form_cGNXUnoxwrBVcEtDKzJhaWI3YUts;
var i;
function loadForm_cGNXUnoxwrBVcEtDKzJhaWI3YUts(ajaxServer)
{
if (typeof(G_Form)==='undefined') return alert('form.js was not loaded');
  form_cGNXUnoxwrBVcEtDKzJhaWI3YUts=new G_Form(document.getElementById('cGNXUnoxwrBVcEtDKzJhaWI3YUts'),'cGNXUnoxwrBVcEtDKzJhaWI3YUts');
  var myForm=form_cGNXUnoxwrBVcEtDKzJhaWI3YUts;
  myForm.ajaxServer=ajaxServer;
                  i = myForm.aElements.length;
      myForm.aElements[i] = new G_Text(myForm, myForm.element.elements['form[APP_CODE]'],'APP_CODE');
      myForm.aElements[i].setAttributes({"label":"C\u00f3digo de Aplicaci\u00f3n:","group":0,"mode":"edit","size":"25","validate":"Alpha10","mask":"","maxLength":"24","defaultValue":"","required":false,"dependentFields":"","linkField":"","strTo":"","readOnly":false,"sqlOption":[],"formula":"","function":"","options":[]});
                i = myForm.aElements.length;
      myForm.aElements[i] = new G_Text(myForm, myForm.element.elements['form[APP_DESCRIPTION]'],'APP_DESCRIPTION');
      myForm.aElements[i].setAttributes({"label":"Descripci\u00f3n:","group":0,"mode":"edit","size":"35","validate":"4","mask":"","maxLength":"120","defaultValue":"","required":false,"dependentFields":"","linkField":"","strTo":"","readOnly":false,"sqlOption":[],"formula":"","function":"","options":[]});
                                                  }
