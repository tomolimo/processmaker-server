/*upgrade system routine*/

function upgradeSystem(wsCount) {
  document.getElementById("form[THETITLE3]").innerHTML = wsCount + " workspaces to update.";
  document.getElementById("form[SUBTITLE4]").innerHTML = "&nbsp;&nbsp;<img src='/images/alert.gif' width='13' height='13' border='0'> Please wait..."; 
  updateWorkspace(wsCount);  
};
   
function updateWorkspace(id) {
  if(id < 0){
    return false;
  }
  
  var oRPC = new leimnud.module.rpc.xmlhttp({
    async : true,
    method: "POST",
    url:  "../setup/upgrade_SystemAjax",
    args  : "id=" + id
  });
  oRPC.callback = function(rpc) {
  document.getElementById("form[SUBTITLE4]").innerHTML = rpc.xmlhttp.responseText;
  updateWorkspace(id-1)
  }.extend(this);
  oRPC.make();
};   