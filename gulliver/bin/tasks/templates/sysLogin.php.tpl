<?php
/**
 * sysLogin.php
 *  
 * 
 */
if (isset($_POST['form']['USER_ENV'])) {
  session_start();
  $_SESSION['sysLogin'] = $_POST['form'];
  G::header('location: /sys' . $_POST['form']['USER_ENV'] . '/'.SYS_LANG.'/'.SYS_SKIN.'/login/sysLoginVerify'); 
  die;
}

$G_PUBLISH = new Publisher;
$G_PUBLISH->AddContent('xmlform', 'xmlform', 'login/sysLogin', '', '', 'sysLogin');
G::RenderPage( "publish" );
?>
