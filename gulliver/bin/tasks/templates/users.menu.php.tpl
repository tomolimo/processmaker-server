<?php
/**
 * users.php
 *  
 */
global $G_TMP_MENU;

  $G_TMP_MENU->AddIdRawOption('USERS',      'users/usersList');
  $G_TMP_MENU->AddIdRawOption('ROLES',      'users/rolesList');
  $G_TMP_MENU->AddIdRawOption('PERMISSIONS','users/permissionsList');

  $G_TMP_MENU->Labels = array ( 
    G::LoadTranslation('ID_USERS_LIST'), 
    G::LoadTranslation('ID_ROLES'), 
    G::LoadTranslation('ID_PERMISSIONS')
   );
