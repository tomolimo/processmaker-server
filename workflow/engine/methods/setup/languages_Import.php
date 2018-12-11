<?php

require_once "classes/model/Language.php";

global $RBAC;
$access = $RBAC->userCanAccess('PM_SETUP_ADVANCE');

if ($access != 1) {
    switch ($access) {
        case - 1:
            G::SendTemporalMessage('ID_USER_HAVENT_RIGHTS_PAGE', 'error', 'labels');
            break;
        case - 2:
            G::SendTemporalMessage('ID_USER_HAVENT_RIGHTS_SYSTEM', 'error', 'labels');
            break;
        default:
            G::SendTemporalMessage('ID_USER_HAVENT_RIGHTS_PAGE', 'error', 'labels');
            break;
    }
    G::header('location: ../login/login');
    exit(0);
}

$result = new stdClass();

try {
    //if the xmlform path is writeable
    if (!is_writable(PATH_XMLFORM)) {
        throw new Exception(G::LoadTranslation('IMPORT_LANGUAGE_ERR_NO_WRITABLE'));
    }

    //if all xml files within the xmlform directory are writeable
    if (!G::is_rwritable(PATH_XMLFORM)) {
        throw new Exception(G::LoadTranslation('IMPORT_LANGUAGE_ERR_NO_WRITABLE2'));
    }

    $sMaxExecutionTime = ini_get('max_execution_time');
    ini_set('max_execution_time', '0');

    $filter = new InputFilter();

    $languageFile = $_FILES['form']['tmp_name']['LANGUAGE_FILENAME'];
    $languageFilename = $_FILES['form']['name']['LANGUAGE_FILENAME'];
    $languageFile = $filter->xssFilterHard($languageFile, 'path');
    $languageFilename = $filter->xssFilterHard($languageFilename, 'path');
    if (substr_compare($languageFilename, ".gz", - 3, 3, true) == 0) {
        $zp = gzopen($languageFile, "r");
        $languageFile = tempnam(__FILE__, '');
        $handle = fopen($languageFile, "w");
        while (!gzeof($zp)) {
            $data = gzread($zp, 1024);
            fwrite($handle, $data);
        }
        gzclose($zp);
        fclose($handle);
    }

    $language = new Language();
    $importResults = $language->import($languageFile);

    $result->success = true;
    $result->msg = G::LoadTranslation('IMPORT_LANGUAGE_SUCCESS') . "\n";
    $result->msg .= G::LoadTranslation("ID_FILE_NUM_RECORD") . $importResults->recordsCount . "\n";
    $result->msg .= G::LoadTranslation("ID_SUCCESS_RECORD") . $importResults->recordsCountSuccess . "\n";
    $result->msg .= G::LoadTranslation("ID_FAILED_RECORD") . ($importResults->recordsCount - $importResults->recordsCountSuccess) . "\n";

    if ($importResults->errMsg != '') {
        $result->msg .= G::LoadTranslation("ID_ERROR_REGISTERED") . " \n" . $importResults->errMsg . "\n";
    }

    //saving metadata
    $configuration = new Configurations();
    $configuration->aConfig = Array('headers' => $importResults->headers, 'language' => $importResults->lang, 'import-date' => date('Y-m-d H:i:s'), 'user' => '', 'version' => '1.0');
    $configuration->saveConfig('LANGUAGE_META', $importResults->lang);

    $renegerateContent = new WorkspaceTools(config("system.workspace"));
    $messs = $renegerateContent->upgradeContent();

    $dir = PATH_CORE . 'content' . PATH_SEP . 'translations' . PATH_SEP;
    if (!is_writable($dir)) {
        throw new Exception(G::LoadTranslation('ID_TRANSLATIONS_FOLDER_PERMISSIONS'));
    }
    G::uploadFile($languageFile, $dir, $languageFilename, 0777);

    ini_set('max_execution_time', $sMaxExecutionTime);
} catch (Exception $oError) {
    $result->success = false;
    $result->msg = $oError->getMessage();
}

ob_clean();
echo G::json_encode($result);

