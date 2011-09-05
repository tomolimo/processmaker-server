var form_cGNXUnoxwrBvcDVYaXdwYmtvNjJtM2c___;
var i;
function loadForm_cGNXUnoxwrBvcDVYaXdwYmtvNjJtM2c___(ajaxServer)
{
if (typeof(G_Form)==='undefined') return alert('form.js was not loaded');
  form_cGNXUnoxwrBvcDVYaXdwYmtvNjJtM2c___=new G_Form(document.getElementById('cGNXUnoxwrBvcDVYaXdwYmtvNjJtM2c___'),'cGNXUnoxwrBvcDVYaXdwYmtvNjJtM2c___');
  var myForm=form_cGNXUnoxwrBvcDVYaXdwYmtvNjJtM2c___;
  myForm.ajaxServer=ajaxServer;
                  i = myForm.aElements.length;
      myForm.aElements[i] = new G_Text(myForm, myForm.element.elements['form[USR_FIRSTNAME]'],'USR_FIRSTNAME');
      myForm.aElements[i].setAttributes({"label":"Nombre:","group":"8","mode":"edit","size":"25","validate":"Alpha","mask":"","maxLength":"24","defaultValue":"","required":false,"dependentFields":"","linkField":"","strTo":"","readOnly":false,"sqlOption":[],"formula":"","function":"","options":[]});
                i = myForm.aElements.length;
      myForm.aElements[i] = new G_Text(myForm, myForm.element.elements['form[USR_MIDNAME]'],'USR_MIDNAME');
      myForm.aElements[i].setAttributes({"label":"Segundo Nombre:","group":0,"mode":"edit","size":"25","validate":"Alpha","mask":"","maxLength":"24","defaultValue":"","required":false,"dependentFields":"","linkField":"","strTo":"","readOnly":false,"sqlOption":[],"formula":"","function":"","options":[]});
                i = myForm.aElements.length;
      myForm.aElements[i] = new G_Text(myForm, myForm.element.elements['form[USR_LASTNAME]'],'USR_LASTNAME');
      myForm.aElements[i].setAttributes({"label":"Apellido:","group":0,"mode":"edit","size":"25","validate":"Alpha","mask":"","maxLength":"24","defaultValue":"","required":false,"dependentFields":"","linkField":"","strTo":"","readOnly":false,"sqlOption":[],"formula":"","function":"","options":[]});
                i = myForm.aElements.length;
      myForm.aElements[i] = new G_Text(myForm, myForm.element.elements['form[USR_EMAIL]'],'USR_EMAIL');
      myForm.aElements[i].setAttributes({"label":"Correo Electr\u00f3nico:","group":0,"mode":"edit","size":"25","validate":"Any","mask":"","maxLength":"24","defaultValue":"","required":false,"dependentFields":"","linkField":"","strTo":"","readOnly":false,"sqlOption":[],"formula":"","function":"","options":[]});
                                                  }
