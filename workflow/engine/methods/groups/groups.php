<?php
/**
 * groups.php
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
  $G_ID_SUB_MENU_SELECTED = 'GROUPS';

  $dbc = new DBConnection();
  $ses = new DBSession($dbc);

  $Fields['WHERE'] = '';

  $G_PUBLISH = new Publisher;
  $oHeadPublisher =& headPublisher::getSingleton();
  $oHeadPublisher->addScriptFile('/jscore/groups/groups.js');
  
  $G_PUBLISH->AddContent('view', 'groups/groups_Tree' );
  $G_PUBLISH->AddContent('smarty', 'groups/groups_usersList', '', '', array());

  G::RenderPage( "publish-treeview",'blank' );

  $groups_Edit = G::encryptlink('groups_Edit');
  $groups_Delete = G::encryptlink('groups_Delete');
  $groups_List = G::encryptlink('groups_List');
  $groups_AddUser = G::encryptlink('groups_AddUser');
?>
<script>
  

  var oAux = document.getElementById("publisherContent[0]");
  oAux.id = "publisherContent[666]";
  var currentGroup=false;
  function editGroup( uid ) {
    popupWindow('' , '<?=$groups_Edit?>?UID=' + encodeURIComponent( uid )+'&nobug' , 500 , 200 );
    refreshTree();
  }
  function addGroup(){
    popupWindow('' , '<?=$groups_Edit?>' , 500 , 200 );
  }
  function addUserGroup( uid ){
    //popupWindow('' , '<?=$groups_AddUser?>?UID='+uid, 500 , 520 );
    oPanel = new leimnud.module.panel();
    oPanel.options = {
        size  :{w:400,h:512},
        position:{x:0,y:0,center:true},
        title : 'Add users to '+groupname+' group',
        theme :"processmaker",
        statusBar:false,
        control :{resize:false,roll:false,drag:true},
        fx  :{modal:true,opacity:true,blinkToFront:false,fadeIn:false,drag:true}
      };
      oPanel.events = {
        remove: function() { 
          delete(oPanel);
          resetChecks();
        }.extend(this)
      };
    oPanel.make();
    oPanel.loader.show();
    currentPopupWindow = oPanel;
    var oRPC = new leimnud.module.rpc.xmlhttp({
        url : '<?=$groups_AddUser?>?UID='+uid,
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
  function saveGroup( form ) {
    ajax_post( form.action, form, 'POST' );
    currentPopupWindow.remove();
    refreshTree();
  }
  
  function selectGroup( uid ){
    currentGroup = uid;
    var oRPC = new leimnud.module.rpc.xmlhttp({
      url   : 'groups_Ajax',
      args  : 'action=showUsers&sGroupUID=' + uid
    });
    oRPC.callback = function(rpc) {
      var scs = rpc.xmlhttp.responseText.extractScript();
      document.getElementById('spanUsersList').innerHTML = rpc.xmlhttp.responseText;
      scs.evalScript();
    }.extend(this);
    
    oRPC.make();
  }
  function deleteGroup( uid ){
    new leimnud.module.app.confirm().make({
      label:"<?=G::LoadTranslation('ID_MSG_CONFIRM_DELETE_GROUP')?>",
      action:function()
      {
        ajax_function('<?=$groups_Delete?>', 'asdxxx', 'GRP_UID='+uid, "POST" );
        refreshTree();
        document.getElementById('spanUsersList').innerHTML = '';
      }.extend(this)
    });
  }
  function refreshTree(){
    tree.refresh( document.getElementById("publisherContent[666]") , '<?=$groups_List?>');
  }

  var ofToAssignUser = function(sGroup, sUser)
  {
    new leimnud.module.app.confirm().make({
      label:"<?=G::LoadTranslation('ID_MSG_CONFIRM_REMOVE_USER')?>",
      action:function()
      {
        var oRPC = new leimnud.module.rpc.xmlhttp({
          url   : '../groups/groups_Ajax',
          async : false,
          method: 'POST',
          args  : 'action=ofToAssignUser&GRP_UID=' + sGroup + '&USR_UID=' + sUser
        });
        oRPC.make();
        currentGroup = sGroup;
        selectGroup(currentGroup);
      }.extend(this)
    });
  };

</script>
