<?php

use ProcessMaker\Core\System;

$data = $_POST['form'];
global $RBAC;

$rbacUser = new RbacUsers();
$user = new Users();
$data['USR_USERNAME'] = strip_tags($data['USR_USERNAME']);
$userData = $rbacUser->getByUsername($data['USR_USERNAME']);
$userExists = $userData === false ? false : true;

if ($userExists === true && $userData['USR_EMAIL'] != '' && $userData['USR_EMAIL'] === $data['USR_EMAIL'] && ($userData['USR_AUTH_TYPE'] === '' || $userData['USR_AUTH_TYPE'] == 'MYSQL')) {
    $setup = System::getEmailConfiguration();
    if (count($setup) == 0 || !isset($setup['MESS_ENGINE'])) {
        G::SendTemporalMessage('ID_EMAIL_ENGINE_IS_NOT_ENABLED', "warning");
        G::header('location: forgotPassword');
        die;
    }

    $newPass = G::generate_password();

    $infoUser = [];
    $infoUser['USR_UID'] = $userData['USR_UID'];
    $infoUser['USR_PASSWORD'] = Bootstrap::hashPassword($newPass);
    $userProperty = new UsersProperties();
    $aUserPropertyData = $userProperty->load($infoUser['USR_UID']);
    if (is_array($aUserPropertyData)) {
        $aUserPropertyData['USR_LOGGED_NEXT_TIME'] = 1;
        $userProperty = $userProperty->update($aUserPropertyData);
    }

    if (!isset($from)) {
        $from = '';
    }
    $from = G::buildFrom($setup, $from);

    $subject = G::LoadTranslation('ID_RESET_PASSWORD') . ' - ProcessMaker';
    $msg = '<h3>ProcessMaker Forgot password Service</h3>';
    $msg .= '<p>' . G::LoadTranslation('ID_YOUR_USERMANE_IS') . ' :  <strong>' . $userData['USR_USERNAME'] . '</strong></p>';
    $msg .= '<p>' . G::LoadTranslation('ID_YOUR_PASSWORD_IS') . ' :  <strong>' . $newPass . '</strong></p>';
    switch ($setup['MESS_ENGINE']) {
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

    $body = "
  <table style=\"background-color: white; font-family: Arial,Helvetica,sans-serif; color: black; font-size: 11px; text-align: left;\" cellpadding='10' cellspacing='0' width='100%'>
  <tbody><tr><td><img id='logo' src='http://" . $_SERVER['SERVER_NAME'] . "/images/processmaker.logo.jpg' /></td></tr>
  <tr><td style='font-size: 14px;'>$msg</td></tr>
  <tr><td style='vertical-align:middel;'>
  <br /><hr><b>This Business Process is powered by ProcessMaker ver. " . System::getVersion() . ".<b><br />
  <a href='http://www.processmaker.com' style='color:#c40000;'>www.processmaker.com</a><br /></td>
  </tr></tbody></table>";

    $spool = new SpoolRun();

    $spool->setConfig($setup);
    $messageArray = AppMessage::buildMessageRow(
        '',
        '',
        0,
        'TEST',
        $subject,
        $from,
        $data['USR_EMAIL'],
        $body,
        '',
        '',
        '',
        '',
        'pending'
    );
    $spool->create($messageArray);

    try {
        $spool->sendMail();
        $rbacUser->update($infoUser);
        $user->update($infoUser);
        G::header("location: login");
        G::SendTemporalMessage('ID_NEW_PASSWORD_SENT', "info");
    } catch (phpmailerException $e) {
        G::header("location: login");
        G::SendTemporalMessage(G::LoadTranslation('MISSING_OR_NOT_CONFIGURED_SMTP'), "warning", 'string');
    } catch (Exception $e) {
        G::header("location: login");
        G::SendTemporalMessage($e->getMessage(), "warning", 'string');
    }
} else {
    $msg = G::LoadTranslation('ID_THE_USERNAME_EMAIL_IS_INCORRECT');
    if ($userData !== false && $userData['USR_AUTH_TYPE'] !== '' && $userData['USR_EMAIL'] === $data['USR_EMAIL']) {
        $msg = G::LoadTranslation('ID_USER_NOT_FUNCTIONALITY');
    }
    G::SendTemporalMessage($msg, "warning", 'string');
    G::header('location: forgotPassword');
}

