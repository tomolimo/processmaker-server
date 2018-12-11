<?php
$isBrowserMobile = G::check_is_mobile(strtolower($_SERVER['HTTP_USER_AGENT']));
if (!((defined('REDIRECT_TO_MOBILE') && REDIRECT_TO_MOBILE == 1 && $isBrowserMobile))) {
    $isBrowserMobile = false;
    if ($RBAC->userCanAccess('PM_CASES') != 1) {
        header('location: ' . SYS_URI . 'login/login' . '?u=' . urlencode($_SERVER['REQUEST_URI']));
        die();
    }
}

$G_MAIN_MENU = 'processmaker';
$G_ID_MENU_SELECTED = 'CASES';

$_POST['qs'] = '';
$arrayAux = explode('?', $_SERVER['REQUEST_URI']);
preg_match('/^.*\/cases\/opencase\/([\w\-]{32})$/', $arrayAux[0], $arrayMatch);

$applicationUid = $arrayMatch[1];
$case = new \ProcessMaker\BusinessModel\Cases();
$arrayApplicationData = $case->getApplicationRecordByPk($applicationUid, [], false);

$G_PUBLISH = new Publisher();

if ($isBrowserMobile) {
    $delIndex = 0;
    if ($arrayApplicationData !== false) {
        $case = new \ProcessMaker\BusinessModel\Cases();

        $arrayResult = $case->getStatusInfo($applicationUid, 0, $_SESSION['USER_LOGGED']);
        $arrayDelIndex = [];

        if (!empty($arrayResult)) {
            $arrayDelIndex = $arrayResult['DEL_INDEX'];
        } else {
            $arrayResult = $case->getStatusInfo($applicationUid);
            $arrayDelIndex = $arrayResult['DEL_INDEX'];
        }
        if (count($arrayDelIndex) == 1) {
            $delIndex = $arrayDelIndex[0];
        }
    }
    $urlMobile = 'processmakerMobile://' . $applicationUid . '/' . $delIndex;
    G::header('Location: ' . $urlMobile);
    exit(0);
} else {
    if ($arrayApplicationData !== false) {
        $_SESSION['__CD__'] = '../';
        $_SESSION['__OPEN_APPLICATION_UID__'] = $applicationUid;
        $G_PUBLISH->AddContent('view', 'cases/cases_Load');
        $headPublisher = headPublisher::getSingleton();
        $headPublisher->addScriptFile('/jscore/src/PM.js');
        $headPublisher->addScriptFile('/jscore/src/Sessions.js');
    } else {
        $G_PUBLISH->AddContent(
            'xmlform',
            'xmlform',
            'login/showMessage',
            '',
            ['MESSAGE' => \G::LoadTranslation('ID_CASE_DOES_NOT_EXIST2', ['app_uid', $applicationUid])]
        );
    }
}

G::RenderPage('publish');
