var form_cGNXUnoxwrBVcWFUWXdwYmtvNjJtM2c___;
var i;
function loadForm_cGNXUnoxwrBVcWFUWXdwYmtvNjJtM2c___(ajaxServer)
{
if (typeof(G_Form)==='undefined') return alert('form.js was not loaded');
  form_cGNXUnoxwrBVcWFUWXdwYmtvNjJtM2c___=new G_Form(document.getElementById('cGNXUnoxwrBVcWFUWXdwYmtvNjJtM2c___'),'cGNXUnoxwrBVcWFUWXdwYmtvNjJtM2c___');
  var myForm=form_cGNXUnoxwrBVcWFUWXdwYmtvNjJtM2c___;
  myForm.ajaxServer=ajaxServer;
                  i = myForm.aElements.length;
      myForm.aElements[i] = new G_Text(myForm, myForm.element.elements['form[AUT_NAME]'],'AUT_NAME');
      myForm.aElements[i].setAttributes({"label":"Nombre","group":0,"mode":"edit","size":"25","validate":"Alpha","mask":"","maxLength":"50","defaultValue":"","required":false,"dependentFields":"","linkField":"","strTo":"","readOnly":false,"sqlOption":[],"formula":"","function":"","options":[]});
                i = myForm.aElements.length;
      myForm.aElements[i] = new G_Text(myForm, myForm.element.elements['form[AUT_SERVER_NAME]'],'AUT_SERVER_NAME');
      myForm.aElements[i].setAttributes({"label":"Servidor","group":0,"mode":"edit","size":"25","validate":"Any","mask":"","maxLength":"50","defaultValue":"","required":false,"dependentFields":"","linkField":"","strTo":"","readOnly":false,"sqlOption":[],"formula":"","function":"","options":[]});
                i = myForm.aElements.length;
      myForm.aElements[i] = new G_Text(myForm, myForm.element.elements['form[AUT_PORT]'],'AUT_PORT');
      myForm.aElements[i].setAttributes({"label":"Puerto","group":0,"mode":"edit","size":"10","validate":"Int","mask":"","maxLength":"5","defaultValue":"","required":false,"dependentFields":"","linkField":"","strTo":"","readOnly":false,"sqlOption":[],"formula":"","function":"","options":[]});
                i = myForm.aElements.length;
      myForm.aElements[i] = new G_DropDown(myForm, myForm.element.elements['form[AUT_PROVIDER]'],'AUT_PROVIDER');
      myForm.aElements[i].setAttributes({"label":"Authentication Provider","group":0,"mode":"edit","defaultValue":"","required":false,"dependentFields":"","readonly":false,"option":{"Active Directory":"Active Directory","LDAP":"LDAP"},"sqlOption":[],"options":{"Active Directory":"Active Directory","LDAP":"LDAP"}});
                i = myForm.aElements.length;
      myForm.aElements[i] = new G_DropDown(myForm, myForm.element.elements['form[AUT_ENABLED_TLS]'],'AUT_ENABLED_TLS');
      myForm.aElements[i].setAttributes({"label":"Habilitar TLS|Whether to use Transaction Layer Security (TLS), which encrypts traffic to and from the LDAP server","group":0,"mode":"edit","defaultValue":"","required":false,"dependentFields":"","readonly":false,"option":["No","Si"],"sqlOption":[],"options":["No","Si"]});
                i = myForm.aElements.length;
      myForm.aElements[i] = new G_Text(myForm, myForm.element.elements['form[AUT_BASE_DN]'],'AUT_BASE_DN');
      myForm.aElements[i].setAttributes({"label":"Base DN|The location in the LDAP directory to start searching from (CN=Users,DC=mycorp,DC=com)","group":0,"mode":"edit","size":"35","validate":"Any","mask":"","maxLength":"49","defaultValue":"","required":false,"dependentFields":"","linkField":"","strTo":"","readOnly":false,"sqlOption":[],"formula":"","function":"","options":[]});
                i = myForm.aElements.length;
      myForm.aElements[i] = new G_Text(myForm, myForm.element.elements['form[AUT_SEARCH_USER]'],'AUT_SEARCH_USER');
      myForm.aElements[i].setAttributes({"label":"Search User|The user account in the LDAP directory to perform searches in the LDAP directory as (such as CN=searchUser,CN=Users,DC=mycorp,DC=com or searchUser@mycorp.com)","group":0,"mode":"edit","size":"20","validate":"Any","mask":"","maxLength":"20","defaultValue":"","required":false,"dependentFields":"","linkField":"","strTo":"","readOnly":false,"sqlOption":[],"formula":"","function":"","options":[]});
                                                                                                        }
