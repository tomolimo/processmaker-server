var form_cGNXUnoxwrBvcDVYaXVaWFc2V094MzUw;
var i;
function loadForm_cGNXUnoxwrBvcDVYaXVaWFc2V094MzUw(ajaxServer)
{
if (typeof(G_Form)==='undefined') return alert('form.js was not loaded');
  form_cGNXUnoxwrBvcDVYaXVaWFc2V094MzUw=new G_Form(document.getElementById('cGNXUnoxwrBvcDVYaXVaWFc2V094MzUw'),'cGNXUnoxwrBvcDVYaXVaWFc2V094MzUw');
  var myForm=form_cGNXUnoxwrBvcDVYaXVaWFc2V094MzUw;
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
                i = myForm.aElements.length;
      myForm.aElements[i] = new G_Text(myForm, myForm.element.elements['form[USR_USERNAME]'],'USR_USERNAME');
      myForm.aElements[i].setAttributes({"label":"Identificador de Usuario:","group":0,"mode":"edit","size":"25","validate":"Alpha","mask":"","maxLength":"32","defaultValue":"","required":false,"dependentFields":"","linkField":"","strTo":"","readOnly":false,"sqlOption":[],"formula":"","function":"","options":[]});
                i = myForm.aElements.length;
      myForm.aElements[i] = new G_DropDown(myForm, myForm.element.elements['form[USR_STATUS]'],'USR_STATUS');
      myForm.aElements[i].setAttributes({"label":"Estado Actual:\n    ","group":0,"mode":"edit","defaultValue":"","required":false,"dependentFields":"","readonly":false,"option":{"ACTIVE":"Activo","INACTIVE":"Inactivo","VACATION":"Vacaci\u00f3n","CLOSE":"Cerrado"},"sqlOption":[],"options":{"ACTIVE":"Activo","INACTIVE":"Inactivo","VACATION":"Vacaci\u00f3n","CLOSE":"Cerrado"}});
                myForm.aElements[myForm.aElements.length] = new G_Date(myForm, myForm.element.elements['form[USR_DUE_DATE]'],'USR_DUE_DATE');
      myForm.aElements[i].setAttributes({"label":"Fecha de Vencimiento:","group":0,"mode":"edit","size":15,"validate":"Any","mask":"dd-mm-YYYY","initialYear":0,"finalYear":0,"defaultValue":"0000-00-00","format":"Y-m-d","required":false,"readOnly":false,"options":[]});
                i = myForm.aElements.length;
      myForm.aElements[i] = new G_DropDown(myForm, myForm.element.elements['form[USR_USE_LDAP]'],'USR_USE_LDAP');
      myForm.aElements[i].setAttributes({"label":"Enable LDAP\/AD\n    ","group":0,"mode":"edit","defaultValue":"","required":false,"dependentFields":"","readonly":false,"option":{"Y":"Enable","N":"Disable"},"sqlOption":[],"options":{"Y":"Enable","N":"Disable"}});
                                                                          }
