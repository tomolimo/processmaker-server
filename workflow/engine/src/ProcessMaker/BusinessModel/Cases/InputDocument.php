<?php
namespace ProcessMaker\BusinessModel\Cases;

class InputDocument
{
    /**
     * Get data of Cases InputDocument
     *
     * @param string $applicationUid
     * @param string $userUid
     *
     * return array Return an array with data of an InputDocument
     */
    public function getCasesInputDocuments($applicationUid, $userUid)
    {
        try {
            $sApplicationUID = $applicationUid;
            $sUserUID = $userUid;
            \G::LoadClass('case');
            $oCase = new \Cases();
            $fields = $oCase->loadCase( $sApplicationUID );
            $sProcessUID = $fields['PRO_UID'];
            $sTaskUID = '';
            $oCaseRest = new \ProcessMaker\BusinessModel\Cases();
            $oCaseRest->getAllUploadedDocumentsCriteria( $sProcessUID, $sApplicationUID, $sTaskUID, $sUserUID);
            $result = array ();
            global $_DBArray;
            foreach ($_DBArray['inputDocuments'] as $key => $row) {
                if (isset( $row['DOC_VERSION'] )) {
                    $docrow = array ();
                    $docrow['app_doc_uid'] = $row['APP_DOC_UID'];
                    $docrow['app_doc_filename'] = $row['APP_DOC_FILENAME'];
                    $docrow['doc_uid'] = $row['DOC_UID'];
                    $docrow['app_doc_version'] = $row['DOC_VERSION'];
                    $docrow['app_doc_create_date'] =     $row['CREATE_DATE'];
                    $docrow['app_doc_create_user'] = $row['CREATED_BY'];
                    $docrow['app_doc_type'] = $row['TYPE'];
                    $docrow['app_doc_index'] = $row['APP_DOC_INDEX'];
                    $docrow['app_doc_link'] = 'cases/' . $row['DOWNLOAD_LINK'];
                    $result[] = $docrow;
                }
            }
            return $result;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get data of Cases InputDocument
     *
     * @param string $applicationUid
     * @param string $userUid
     * @param string $inputDocumentUid
     *
     * return array Return an array with data of an InputDocument
     */
    public function getCasesInputDocument($applicationUid, $userUid, $inputDocumentUid)
    {
        try {
            $sApplicationUID = $applicationUid;
            $sUserUID = $userUid;
            \G::LoadClass('case');
            $oCase = new \Cases();
            $fields = $oCase->loadCase( $sApplicationUID );
            $sProcessUID = $fields['PRO_UID'];
            $sTaskUID = '';
            $oCaseRest = new \ProcessMaker\BusinessModel\Cases();
            $oCaseRest->getAllUploadedDocumentsCriteria( $sProcessUID, $sApplicationUID, $sTaskUID, $sUserUID );
            $result = array ();
            global $_DBArray;
            foreach ($_DBArray['inputDocuments'] as $key => $row) {
                if (isset( $row['DOC_VERSION'] )) {
                    $docrow = array ();
                    $docrow['app_doc_uid'] = $row['APP_DOC_UID'];
                    $docrow['app_doc_filename'] = $row['APP_DOC_FILENAME'];
                    $docrow['doc_uid'] = $row['DOC_UID'];
                    $docrow['app_doc_version'] = $row['DOC_VERSION'];
                    $docrow['app_doc_create_date'] = $row['CREATE_DATE'];
                    $docrow['app_doc_create_user'] = $row['CREATED_BY'];
                    $docrow['app_doc_type'] = $row['TYPE'];
                    $docrow['app_doc_index'] = $row['APP_DOC_INDEX'];
                    $docrow['app_doc_link'] = 'cases/' . $row['DOWNLOAD_LINK'];
                    if ($docrow['app_doc_uid'] == $inputDocumentUid) {
                        $oAppDocument = \AppDocumentPeer::retrieveByPK( $inputDocumentUid, $row['DOC_VERSION'] );
                        if (is_null( $oAppDocument )) {
                            throw new \Exception(\G::LoadTranslation("ID_CASES_INPUT_DOES_NOT_EXIST", array($inputDocumentUid)));
                        }
                        $result = $docrow;
                    }
                }
            }
            $oResponse = json_decode(json_encode($result), false);
            return $oResponse;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Delete InputDocument
     *
     * @param string $inputDocumentUid
     *
     * return array Return an array with data of an InputDocument
     */
    public function removeInputDocument($inputDocumentUid)
    {
        try {
            $oAppDocument = \AppDocumentPeer::retrieveByPK( $inputDocumentUid, 1 );
            if (is_null( $oAppDocument ) || $oAppDocument->getAppDocStatus() == 'DELETED') {
                throw new \Exception(\G::LoadTranslation("ID_CASES_INPUT_DOES_NOT_EXIST", array($inputDocumentUid)));
            }
            \G::LoadClass('wsBase');
            $ws = new \wsBase();
            $ws->removeDocument($inputDocumentUid);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get data of Cases InputDocument
     *
     * @param string $applicationUid
     * @param string $taskUid
     * @param string $appDocComment
     * @param string $inputDocumentUid
     * @param string $userUid
     *
     * return array Return an array with data of an InputDocument
     */
    public function addCasesInputDocument($applicationUid, $taskUid, $appDocComment, $inputDocumentUid, $userUid)
    {
        try {
            if ((isset( $_FILES['form'] )) && ($_FILES['form']['error'] != 0)) {
                $code = $_FILES['form']['error'];
                switch ($code) {
                    case UPLOAD_ERR_INI_SIZE:
                        $message = \G::LoadTranslation( 'ID_UPLOAD_ERR_INI_SIZE' );
                        break;
                    case UPLOAD_ERR_FORM_SIZE:
                        $message = \G::LoadTranslation( 'ID_UPLOAD_ERR_FORM_SIZE' );
                        break;
                    case UPLOAD_ERR_PARTIAL:
                        $message = \G::LoadTranslation( 'ID_UPLOAD_ERR_PARTIAL' );
                        break;
                    case UPLOAD_ERR_NO_FILE:
                        $message = \G::LoadTranslation( 'ID_UPLOAD_ERR_NO_FILE' );
                        break;
                    case UPLOAD_ERR_NO_TMP_DIR:
                        $message = \G::LoadTranslation( 'ID_UPLOAD_ERR_NO_TMP_DIR' );
                        break;
                    case UPLOAD_ERR_CANT_WRITE:
                        $message = \G::LoadTranslation( 'ID_UPLOAD_ERR_CANT_WRITE' );
                        break;
                    case UPLOAD_ERR_EXTENSION:
                        $message = \G::LoadTranslation( 'ID_UPLOAD_ERR_EXTENSION' );
                        break;
                    default:
                        $message = \G::LoadTranslation( 'ID_UPLOAD_ERR_UNKNOWN' );
                        break;
                }
                \G::SendMessageText( $message, "ERROR" );
                $backUrlObj = explode( "sys" . SYS_SYS, $_SERVER['HTTP_REFERER'] );
                \G::header( "location: " . "/sys" . SYS_SYS . $backUrlObj[1] );
                die();
            }
            \G::LoadClass("case");
            $appDocUid = \G::generateUniqueID();
            $docVersion = '';
            $appDocType = 'INPUT';
            $case = new \Cases();
            $delIndex = \AppDelegation::getCurrentIndex($applicationUid);
            $case->thisIsTheCurrentUser($applicationUid, $delIndex, $userUid, "REDIRECT", "casesListExtJs");
            //Load the fields
            $arrayField = $case->loadCase($applicationUid);
            $arrayField["APP_DATA"] = array_merge($arrayField["APP_DATA"], \G::getSystemConstants());
            //Triggers
            $arrayTrigger = $case->loadTriggers($taskUid, "INPUT_DOCUMENT", $inputDocumentUid, "AFTER");
            //Add Input Document
            if (empty($_FILES)) {
                throw new \Exception(\G::LoadTranslation("ID_CASES_INPUT_FILENAME_DOES_NOT_EXIST"));
            }
            if (!$_FILES["form"]["error"]) {
                $_FILES["form"]["error"] = 0;
            }
            if (isset($_FILES) && isset($_FILES["form"]) && count($_FILES["form"]) > 0) {
                $appDocUid = $case->addInputDocument($inputDocumentUid,
                    $appDocUid,
                    $docVersion,
                    $appDocType,
                    $appDocComment,
                    '',
                    $applicationUid,
                    $delIndex,
                    $taskUid,
                    $userUid,
                    "xmlform",
                    $_FILES["form"]["name"],
                    $_FILES["form"]["error"],
                    $_FILES["form"]["tmp_name"]);
            }
            //Trigger - Execute after - Start
            $arrayField["APP_DATA"] = $case->executeTriggers ($taskUid,
                "INPUT_DOCUMENT",
                $inputDocumentUid,
                "AFTER",
                $arrayField["APP_DATA"]);
            //Trigger - Execute after - End
            //Save data
            $arrayData = array();
            $arrayData["APP_NUMBER"] = $arrayField["APP_NUMBER"];
            //$arrayData["APP_PROC_STATUS"] = $arrayField["APP_PROC_STATUS"];
            $arrayData["APP_DATA"]  = $arrayField["APP_DATA"];
            $arrayData["DEL_INDEX"] = $delIndex;
            $arrayData["TAS_UID"]   = $taskUid;
            $case->updateCase($applicationUid, $arrayData);
            return($this->getCasesInputDocument($applicationUid, $userUid, $appDocUid));
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
