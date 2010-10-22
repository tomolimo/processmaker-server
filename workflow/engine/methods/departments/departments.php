<?php
/**
 * departments.php
 *
 * ProcessMaker Open Source Edition
 * Copyright (C) 2004 - 2008 Colosa Inc.23
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * For more information, contact Colosa Inc, 2566 Le Jeune Rd.,
 * Coral Gables, FL, 33134, USA, or email info@colosa.com.
 *
 */

$access = $RBAC->userCanAccess('PM_USERS');
if( $access != 1 ){
  switch ($access)
  {
  	case -1:
  	  G::SendTemporalMessage('ID_USER_HAVENT_RIGHTS_PAGE', 'error', 'labels');
  	  G::header('location: ../login/login');
  	  die;
  	break;
  	case -2:
  	  G::SendTemporalMessage('ID_USER_HAVENT_RIGHTS_SYSTEM', 'error', 'labels');
  	  G::header('location: ../login/login');
  	  die;
  	break;
  	default:
  	  G::SendTemporalMessage('ID_USER_HAVENT_RIGHTS_PAGE', 'error', 'labels');
  	  G::header('location: ../login/login');
  	  die;
  	break;  	
  }
}  
if (($RBAC_Response=$RBAC->userCanAccess("PM_USERS"))!=1) return $RBAC_Response;

  $G_MAIN_MENU            = 'processmaker';
  $G_SUB_MENU             = 'users';
  $G_ID_MENU_SELECTED     = 'USERS';
  $G_ID_SUB_MENU_SELECTED = 'DEPARTMENTS';

  $G_PUBLISH = new Publisher;
  $oHeadPublisher =& headPublisher::getSingleton();
  //$oHeadPublisher->addScriptFile('/jscore/departments/departments.js');
  
  $G_PUBLISH->AddContent('view',   'departments/departments_Tree' );
  $G_PUBLISH->AddContent('smarty', 'departments/departments_userList', '', '', array());
  
  G::RenderPage( "publish-treeview" );

  $departments_New        = G::encryptlink('departments_New');
  $departments_Edit       = G::encryptlink('departments_Edit');
  $departments_Delete     = G::encryptlink('departments_Delete');
  $departments_List       = G::encryptlink('departments_List');
  $departments_AddUser    = G::encryptlink('departments_AddUser');
  $departments_AddManager = G::encryptlink('departments_AddManager');
  $subdep_Edit            = G::encryptlink('subdep_Edit');
  $subdep_Delete          = G::encryptlink('subdep_Delete');
?>
<script>
  

  var oAux = document.getElementById("publisherContent[0]");
  oAux.id = "publisherContent[666]";
  var currentGroup=false;

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
  //alert('action=assignAllUsers&DEP_UID=' + currentGroup + '&aUsers=' + checks_selected_ids);return;
    var oRPC = new leimnud.module.rpc.xmlhttp({
      url   : '../departments/departments_Ajax',
      async : false,
      method: 'POST',
      args  : 'action=assignAllUsers&DEP_UID=' + currentGroup + '&aUsers=' + checks_selected_ids
    });
    resetChecks();
    oRPC.make();
    currentPopupWindow.remove();
    selectDpto(currentGroup);
}

function resetChecks(){
  checks_selected_ids.length = 0;
}

  function selectDpto( uid ){
    currentGroup = uid;
    var oRPC = new leimnud.module.rpc.xmlhttp({
      url   : 'departments_Ajax',
      args  : 'action=showUsers&sDptoUID=' + uid
    });
    oRPC.callback = function(rpc) {
      var scs = rpc.xmlhttp.responseText.extractScript();
      document.getElementById('spanUsersList').innerHTML = rpc.xmlhttp.responseText;
      scs.evalScript();
    }.extend(this);
    
    oRPC.make();
  }
  
  function addDepto() {
    popupWindow('' , '<?=$departments_New?>' , 500 , 200 );
  }
  
  function addSubDepto(depUid){
    popupWindow('' , '<?= $departments_New ?>?DEP_UID=' + encodeURIComponent( depUid ) , 500 , 200 );
  }

  function deleteDpto( uid ){
    new leimnud.module.app.confirm().make({
      label:"<?=G::LoadTranslation('ID_MSG_CONFIRM_DELETE_DEPARTMENT')?>",
      action:function()
      {
        ajax_function('<?=$departments_Delete?>', 'empty', 'DEP_UID='+uid, "POST" );
        refreshTree();
        document.getElementById('spanUsersList').innerHTML = '';
      }.extend(this)
    });
  }
  
  function addUserDpto( uid ) {
    oPanel = new leimnud.module.panel();
    oPanel.options = {
        size  :{w:400,h:512},
        position:{x:0,y:0,center:true},
        title : 'Add users to department ' + groupname,
        theme :"processmaker",
        statusBar:false,
        control :{resize:false,roll:false,drag:true},
        fx  :{modal:true,opacity:true,blinkToFront:false,fadeIn:false,drag:true}
      };
      oPanel.events = {
        remove: function() { 
          delete(oPanel);
          //resetChecks();
        }.extend(this)
      };
    oPanel.make();
    oPanel.loader.show();
    currentPopupWindow = oPanel;
    var oRPC = new leimnud.module.rpc.xmlhttp({
        url : '<?=$departments_AddUser?>?UID='+uid,
        args: ''
      });
      oRPC.callback = function(rpc) {
        oPanel.loader.hide();
        var scs=rpc.xmlhttp.responseText.extractScript();
        oPanel.addContent(rpc.xmlhttp.responseText);
        scs.evalScript();
        var inputs = document.getElementsByTagName("input");
        for(i=0; i<inputs.length; i++){
          if( inputs[i].type == "checkbox" ){
            try{
              inputs[i].onclick = function(){
                if(this.checked){
                  checks_selected_ids.push(this.value);
                } else {
                checks_selected_ids.deleteByValue(this.value);
                }
              };
            }catch(e){alert(e)}
          }
        } 
      }.extend(this);
    oRPC.make();
  }

    function addDepManager(suid, sDepParent){
    var k = new leimnud.module.rpc.xmlhttp({
            url   : '../departments/departments_Ajax',
             async : true,
             method: 'POST',
             args  : 'action=getDepManager&sDptoUID=' + suid
              
       });
       
       k.callback = function(rpc){
       if(rpc.xmlhttp.responseText>0){
          msgBox("this department has its manager","alert");return false;
         }else{
         popupWindow('' , '<?=$departments_AddManager?>?SUID=' + encodeURIComponent( suid )+'&SDEPPARENT=' + encodeURIComponent( sDepParent )+ '+&nobug' , 500 , 200 );
         }
       }.extend(this);
       k.make();
  }
  
  
  function savedepto( form ) {
    var actionform='departments_Save';
    ajax_post( actionform, form, 'POST' );
    currentPopupWindow.remove();
    refreshTree();
  }
  
  function savedeptomain( form ) {//alert(form.action);return;
  	var formAction ='departments_Save';
    ajax_post( formAction, form, 'POST' );
    selectDpto(getField('DEP_UID').value);
    refreshTree();
  }
  
  function savedeptoManager( form ) {
   var oRPC = new leimnud.module.rpc.xmlhttp({
      url   : '../departments/departments_Ajax',
      async : false,
      method: 'POST',
      args  : 'action=assignAllUsers&DEP_UID=' + getField('DEP_UID').value + '&aUsers=' + getField('DEP_MANAGER').value
    });
    //resetChecks();
    oRPC.make();
  
    ajax_post( form.action, form, 'POST' );
    currentPopupWindow.remove();
    //refreshTree();
    selectDpto(getField('DEP_UID').value);
  }
  
  function savesubdepto( form ) {
    ajax_post( form.action, form, 'POST' );
    currentPopupWindow.remove();
    refreshSubTree();
  }
  
  function AddUnassignedUser( uid ){//alert(uid);
    var oRPC = new leimnud.module.rpc.xmlhttp({
      url   : 'departments_Ajax',
      args  : 'action=showUnAssignedUsers&UID=' + uid
    });
    oRPC.callback = function(rpc) {
      var scs = rpc.xmlhttp.responseText.extractScript();
      document.getElementById('spanUsersList').innerHTML = rpc.xmlhttp.responseText;
      scs.evalScript();
    }.extend(this);
    
    oRPC.make();
  }
  
  function sselectDpto( suid, sDepParent ) {
    currentGroup = suid;
    var oRPC = new leimnud.module.rpc.xmlhttp({
      url   : 'departments_Ajax',
      args  : 'action=subshowUsers&sDptoUID=' + suid +'&sDepParent=' + sDepParent
    });
    oRPC.callback = function(rpc) {
      var scs = rpc.xmlhttp.responseText.extractScript();
      document.getElementById('spanUsersList').innerHTML = rpc.xmlhttp.responseText;
      scs.evalScript();
    }.extend(this);
    
    oRPC.make();
  }
  
  
  function sdeleteDpto( suid, sDepParent ){
    new leimnud.module.app.confirm().make({
      label:"<?=G::LoadTranslation('ID_MSG_CONFIRM_DELETE_DEPARTMENT')?>",
      action:function()
      {
        ajax_function('<?=$subdep_Delete?>', 'asdxxx', 'DEP_UID=' +suid+ '&DEP_PARENT=' +sDepParent, "POST" );
        refreshTree();
        document.getElementById('spanUsersList').innerHTML = '';
      }.extend(this)
    });
  }
  
  
  function refreshTree(){
    tree.refresh( document.getElementById("publisherContent[666]") , '<?=$departments_List?>');
  }

  function refreshSubTree(){
    tree.refresh( document.getElementById("publisherContent[666]") , '<?=$departments_List?>');
  }

  var removeUserFromDepartment = function(sDpto, sUser)
  {
    new leimnud.module.app.confirm().make({
      label:"<?=G::LoadTranslation('ID_MSG_CONFIRM_REMOVE_USER')?>",
      action:function()
      {
        var oRPC = new leimnud.module.rpc.xmlhttp({
          url   : '../departments/departments_Ajax',
          async : false,
          method: 'POST',
          args  : 'action=removeUserFromDepartment&DEP_UID=' + sDpto + '&USR_UID=' + sUser
        });
        oRPC.make();
        currentDept = sDpto;
        selectDpto(currentDept);
      }.extend(this)
    });
  };
  
  var removeUserManager = function(sDpto, sUser)
  {
    new leimnud.module.app.confirm().make({
      label:"<?=G::LoadTranslation('ID_MSG_CONFIRM_REMOVE_USER')?>",
      action:function()
      {
        var oRPC = new leimnud.module.rpc.xmlhttp({
          url   : '../departments/departments_Ajax',
          async : false,
          method: 'POST',
          args  : 'action=removeUserManager&DEP_UID=' + sDpto + '&USR_UID=' + sUser
        });
        oRPC.make();
        currentGroup = sDpto;
        selectDpto(currentGroup);
      }.extend(this)
    });
  };
  
 function DeleteManager(suid, sDepParent){
    var k = new leimnud.module.rpc.xmlhttp({
            url   : '../departments/departments_Ajax',
             async : true,
             method: 'POST',
             args  : 'action=getDepManageruid&sDptoUID=' + suid
              
       });
       k.callback = function(rpc){
       sUser= rpc.xmlhttp.responseText;
       
       if(rpc.xmlhttp.responseText!=''){
       removeUserManager(suid, sUser);
         }else{
         msgBox("this department doesn't has its manager","alert");return false;
         }
       }.extend(this);
       k.make();
  }  

</script>
