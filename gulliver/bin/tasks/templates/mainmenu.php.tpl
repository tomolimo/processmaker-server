<?php
/**
 * {projectName}.php
 *
 */
global $G_TMP_MENU;
global $RBAC;

$G_TMP_MENU->AddIdRawOption('USERS',    'users/usersList');
//$G_TMP_MENU->AddIdRawOption('REPORTS',  'login/welcome');
$G_TMP_MENU->AddIdRawOption('WELCOME',  'login/welcome');

$G_TMP_MENU->Labels = array(
  G::LoadTranslation('ID_USERS'),
//  G::LoadTranslation('ID_REPORTS'),
  G::LoadTranslation('ID_WELCOME')

);

/*

if ($RBAC->userCanAccess('PM_USERS') != 1)
{
  $G_TMP_MENU->DisableOptionId('USERS');
}
*/