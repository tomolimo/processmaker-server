var form_bjlLWDFaNmlvSsKwWDNaK2I3YUts;
var i;
function loadForm_bjlLWDFaNmlvSsKwWDNaK2I3YUts(ajaxServer)
{
if (typeof(G_Form)==='undefined') return alert('form.js was not loaded');
  form_bjlLWDFaNmlvSsKwWDNaK2I3YUts=new G_Form(document.getElementById('bjlLWDFaNmlvSsKwWDNaK2I3YUts'),'bjlLWDFaNmlvSsKwWDNaK2I3YUts');
  var myForm=form_bjlLWDFaNmlvSsKwWDNaK2I3YUts;
  myForm.ajaxServer=ajaxServer;
                  i = myForm.aElements.length;
      myForm.aElements[i] = new G_Text(myForm, myForm.element.elements['form[USER_NAME]'],'USER_NAME');
      myForm.aElements[i].setAttributes({"label":"Usuario","group":0,"mode":"edit","size":"30","validate":"Alpha10","mask":"","maxLength":"32","defaultValue":"","required":false,"dependentFields":"","linkField":"","strTo":"","readOnly":false,"sqlOption":[],"formula":"","function":"","options":[]});
                                            }
