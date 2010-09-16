function saveUserGroup(sUser) {

    var oRPC = new leimnud.module.rpc.xmlhttp({
      url   : '../groups/groups_Ajax',
      async : false,
      method: 'POST',
      args  : 'action=assignUser&GRP_UID=' + currentGroup + '&USR_UID=' + sUser
    });
    oRPC.make();
    currentPopupWindow.remove();
    selectGroup(currentGroup);
  }


function saveUsers(){
  if( checks_selected_ids.length == 0 ){
    new leimnud.module.app.alert().make({label: G_STRINGS.ID_MSG_GROUPS_ADDCONFIRM});
    return 0;
  }
    var oRPC = new leimnud.module.rpc.xmlhttp({
      url   : '../groups/groups_Ajax',
      async : false,
      method: 'POST',
      args  : 'action=assignAllUsers&GRP_UID=' + currentGroup + '&aUsers=' + checks_selected_ids
    });
    resetChecks();
    oRPC.make();
    currentPopupWindow.remove();
    selectGroup(currentGroup);
}

function resetChecks(){
  checks_selected_ids.length = 0;
}


function WindowSize() {
    var wSize = [0, 0];
    if (typeof window.innerWidth != 'undefined')
    {
      wSize = [
          window.innerWidth,
          window.innerHeight
      ];
    }
    else if (typeof document.documentElement != 'undefined'
        && typeof document.documentElement.clientWidth !=
        'undefined' && document.documentElement.clientWidth != 0)
    {
      wSize = [
          document.documentElement.clientWidth,
          document.documentElement.clientHeight
      ];
    }
    else   {
      wSize = [
          document.getElementsByTagName('body')[0].clientWidth,
          document.getElementsByTagName('body')[0].clientHeight
      ];
    }
    return wSize;
  }

 