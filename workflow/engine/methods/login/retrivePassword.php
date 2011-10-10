<?php
$data = $_POST['form'];
global $RBAC;
require_once PATH_RBAC."model/RbacUsers.php";
G::LoadClass('pmFunctions');
require_once 'classes/model/Users.php';
G::LoadClass("system");

$rbacUser = new RbacUsers();
$user = new Users();

$userData = $rbacUser->getByUsername($data['USR_USERNAME']);

if($userData['USR_EMAIL'] != '' && $userData['USR_EMAIL'] === $data['USR_EMAIL']) {
  $aSetup = getEmailConfiguration();
  if (count($aSetup) == 0 || !isset($aSetup['MESS_ENGINE'])) {
    G::SendTemporalMessage ('ID_EMAIL_ENGINE_IS_NOT_ENABLED', "warning");
    G::header('location: forgotPassword');
    die;
  }
  
  $newPass = G::generate_password();
  
  $aData['USR_UID']      = $userData['USR_UID'];
  $aData['USR_PASSWORD'] = md5($newPass);
  
  $rbacUser->update($aData);
  $user->update($aData);
  
  $sFrom    = ($aSetup['MESS_ACCOUNT'] != '' ? $aSetup['MESS_ACCOUNT'] . ' ' : '') . '<' . $aSetup['MESS_ACCOUNT'] . '>';
  $sSubject = G::LoadTranslation('ID_RESET_PASSWORD').' - ProcessMaker' ;
  $msg = '<h3>ProcessMaker Forgot password Service</h3>';
  $msg .='<p>'.G::LoadTranslation('ID_YOUR_USERMANE_IS').' :  <strong>'.$userData['USR_USERNAME'].'</strong></p>';
  $msg .='<p>'.G::LoadTranslation('ID_YOUR_PASSWORD_IS').' :  <strong>'.$newPass.'</strong></p>';
  switch ($aSetup['MESS_ENGINE']) {
    case 'MAIL':
      $engine = G::LoadTranslation('ID_MESS_ENGINE_TYPE_1');
    break;
    case 'PHPMAILER':
      $engine = G::LoadTranslation('ID_MESS_ENGINE_TYPE_2');
    break;
    case 'OPENMAIL':
      $engine = G::LoadTranslation('ID_MESS_ENGINE_TYPE_3');
    break;
  }

  $sBody = "
  <table style=\"background-color: white; font-family: Arial,Helvetica,sans-serif; color: black; font-size: 11px; text-align: left;\" cellpadding='10' cellspacing='0' width='100%'>
  <tbody><tr><td><img id='logo' src='http://".$_SERVER['SERVER_NAME']."/images/processmaker.logo.jpg' /></td></tr>
  <tr><td style='font-size: 14px;'>$msg</td></tr>
  <tr><td style='vertical-align:middel;'>
  <br /><hr><b>This Business Process is powered by ProcessMaker ver. ".System::getVersion().".<b><br />
  <a href='http://www.processmaker.com' style='color:#c40000;'>www.processmaker.com</a><br /></td>
  </tr></tbody></table>";

  G::LoadClass('spool');
  $oSpool = new spoolRun();

  $oSpool->setConfig( array(
    'MESS_ENGINE'   => $aSetup['MESS_ENGINE'],
    'MESS_SERVER'   => $aSetup['MESS_SERVER'],
    'MESS_PORT'     => $aSetup['MESS_PORT'],
    'MESS_ACCOUNT'  => $aSetup['MESS_ACCOUNT'],
    'MESS_PASSWORD' => $aSetup['MESS_PASSWORD'],
    'SMTPAuth'      => $aSetup['MESS_RAUTH'],
    'SMTPSecure'    => $aSetup['SMTPSecure']
  ));
  
  $oSpool->create(array(
    'msg_uid'          => '',
    'app_uid'          => '',
    'del_index'        => 0,
    'app_msg_type'     => 'TEST',
    'app_msg_subject'  => $sSubject,
    'app_msg_from'     => $sFrom,
    'app_msg_to'       => $data['USR_EMAIL'],
    'app_msg_body'     => $sBody,
    'app_msg_cc'       => '',
    'app_msg_bcc'      => '',
    'app_msg_attach'   => '',
    'app_msg_template' => '',
    'app_msg_status'   => 'pending',
    'app_msg_attach'=>'' 
  ));

  $oSpool->sendMail();
  G::header  ("location: login.html");  
  G::SendTemporalMessage ('ID_NEW_PASSWORD_SENT', "info");
} else {
  $msg = G::LoadTranslation('ID_USER') . ' ' . $data['USR_USERNAME'] . ' '. G::LoadTranslation('ID_IS_NOT_REGISTERED');
  G::SendTemporalMessage ($msg, "warning", 'string');
  G::header('location: forgotPassword');
}





























