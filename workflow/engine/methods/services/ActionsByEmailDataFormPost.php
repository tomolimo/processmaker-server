<?php

if (PMLicensedFeatures
        ::getSingleton()
        ->verifyfeature('zLhSk5TeEQrNFI2RXFEVktyUGpnczV1WEJNWVp6cjYxbTU3R29mVXVZNWhZQT0=')) {
    $G_PUBLISH = new Publisher();
    try {
        if ($_REQUEST['APP_UID'] == '') {
            if($_GET['APP_UID'] == ''){
                 throw new Exception('The parameter APP_UID is empty.');
            } else {
                $_REQUEST['APP_UID'] = $_GET['APP_UID'];
            }
        }
    
        if ($_REQUEST['DEL_INDEX'] == '') {
            throw new Exception('The parameter DEL_INDEX is empty.');
        }
    
        if ($_REQUEST['ABER'] == '') {
            throw new Exception('The parameter ABER is empty.');
        }
    
        if (!isset($_REQUEST['form'])) {
            $_REQUEST['form'] = array();
        }
    
        $_REQUEST['APP_UID']   = G::decrypt($_REQUEST['APP_UID'],   URL_KEY);
        $_REQUEST['DEL_INDEX'] = G::decrypt($_REQUEST['DEL_INDEX'], URL_KEY);
        $_REQUEST['ABER']      = G::decrypt($_REQUEST['ABER'],      URL_KEY);
        G::LoadClass('case');
    
        $case = new Cases();
        $casesFields = $case->loadCase($_REQUEST['APP_UID'], $_REQUEST['DEL_INDEX']);
    
        $casesFields['APP_DATA'] = array_merge($casesFields['APP_DATA'], $_REQUEST['form']);
    
        //Get user info
        $current_user_uid = null;
        $currentUsrName   = null;
    
        $criteria = new Criteria("workflow");
    
        $criteria->addSelectColumn(AppDelegationPeer::USR_UID);
        $criteria->add(AppDelegationPeer::APP_UID, $_REQUEST["APP_UID"]);
        $criteria->add(AppDelegationPeer::DEL_INDEX, $_REQUEST["DEL_INDEX"]);
    
        $rsSQL = AppDelegationPeer::doSelectRS($criteria);
        $rsSQL->setFetchmode(ResultSet::FETCHMODE_ASSOC);
    
        while ($rsSQL->next()) {
            $row = $rsSQL->getRow();
    
            $current_user_uid = $row["USR_UID"];
        }
    
        if ($current_user_uid != null) {
            $criteria = new Criteria("workflow");
    
            $criteria->addSelectColumn(UsersPeer::USR_USERNAME);
            $criteria->add(UsersPeer::USR_UID, $current_user_uid);
    
            $rsSQL = UsersPeer::doSelectRS($criteria);
            $rsSQL->setFetchmode(ResultSet::FETCHMODE_ASSOC);
    
            $rsSQL->next();
    
            $row = $rsSQL->getRow();
            $currentUsrName = $row["USR_USERNAME"];
    
            $casesFields["APP_DATA"]["USER_LOGGED"]  = $current_user_uid;
            $casesFields["APP_DATA"]["USR_USERNAME"] = $currentUsrName;
        }
    
        foreach ($casesFields["APP_DATA"] as $index => $value) {
            $_SESSION[$index] = $value;
        }
    
        //Update case info
        $case->updateCase($_REQUEST['APP_UID'], $casesFields);
    
        G::LoadClass('wsBase');
    
        $wsBaseInstance = new wsBase();
        $result = $wsBaseInstance->derivateCase($casesFields['CURRENT_USER_UID'], $_REQUEST['APP_UID'], $_REQUEST ['DEL_INDEX'], true);
        $code = (is_array($result) ? $result['status_code'] : $result->status_code);
    
        $dataResponses = array();
        $dataResponses['ABE_REQ_UID'] = $_REQUEST['ABER'];
        $dataResponses['ABE_RES_CLIENT_IP'] = $_SERVER['REMOTE_ADDR'];
        $dataResponses['ABE_RES_DATA'] = serialize($_REQUEST['form']);
        $dataResponses['ABE_RES_STATUS'] = 'PENDING';
        $dataResponses['ABE_RES_MESSAGE'] = '';
    
        try {
            set_include_path(PATH_PLUGINS . 'actionsByEmail' . PATH_SEPARATOR . get_include_path());
            require_once 'classes/model/AbeResponses.php';
    
            $abeAbeResponsesInstance = new AbeResponses();
            $dataResponses['ABE_RES_UID'] = $abeAbeResponsesInstance->createOrUpdate($dataResponses);
        } catch (Exception $error) {
            throw $error;
        }
    
        if ($code == 0) {
            //Save Cases Notes
            include_once 'utils.php';
    
            $dataAbeRequests = loadAbeRequest($_REQUEST['ABER']);
            $dataAbeConfiguration = loadAbeConfiguration($dataAbeRequests['ABE_UID']);
    
            if ($dataAbeConfiguration['ABE_CASE_NOTE_IN_RESPONSE'] == 1) {
                $response = new stdclass();
                $response->usrUid = $casesFields['APP_DATA']['USER_LOGGED'];
                $response->appUid = $_REQUEST['APP_UID'];
                $response->noteText = "Check the information that was sent for the receiver: " . $dataAbeRequests['ABE_REQ_SENT_TO'];
    
                postNote($response);
            }
    
            $dataAbeRequests['ABE_REQ_ANSWERED'] = 1;
            $code == 0 ? uploadAbeRequest($dataAbeRequests) : '';
    
            if (isset ( $_FILES ['form'] )) {
                foreach ($_FILES ['form'] ['name'] as $fieldName => $value) {
                    if ($_FILES ['form'] ['error'] [$fieldName] == 0) {
                        $appDocument = new AppDocument ( );
    
                        if ( isset ( $_REQUEST['INPUTS'] [$fieldName] ) && $_REQUEST['INPUTS'] [$fieldName] != '' ) {
                            require_once 'classes/model/AppFolder.php';
                            require_once 'classes/model/InputDocument.php';
    
                            $inputDocument = new InputDocument();
                            $id = $inputDocument->load($_REQUEST['INPUTS'] [$fieldName]);
    
                            //Get the Custom Folder ID (create if necessary)
                            $oFolder=new AppFolder();
                            $folderId=$oFolder->createFromPath($id['INP_DOC_DESTINATION_PATH']);
    
                            //Tags
                            $fileTags=$oFolder->parseTags($id['INP_DOC_TAGS']);
    
                            $fields = array (
                                'APP_UID' => $_REQUEST['APP_UID'],
                                'DEL_INDEX' => $_REQUEST ['DEL_INDEX'],
                                'USR_UID' => $casesFields['APP_DATA']['USER_LOGGED'],
                                'DOC_UID' => $_REQUEST['INPUTS'] [$fieldName],
                                'APP_DOC_TYPE' => 'INPUT',
                                'APP_DOC_CREATE_DATE' => date ( 'Y-m-d H:i:s' ),
                                'APP_DOC_COMMENT' => '',
                                'APP_DOC_TITLE' => '',
                                'APP_DOC_FILENAME' => $_FILES ['form'] ['name'] [$fieldName],
                                'FOLDER_UID'          => $folderId,
                                'APP_DOC_TAGS'        => $fileTags
                            );
                        } else {
                            $fields = array (
                                'APP_UID' => $_REQUEST['APP_UID'],
                                'DEL_INDEX' => $_REQUEST ['DEL_INDEX'],
                                'USR_UID' => $casesFields['APP_DATA']['USER_LOGGED'],
                                'DOC_UID' => - 1,
                                'APP_DOC_TYPE' => 'ATTACHED',
                                'APP_DOC_CREATE_DATE' => date ( 'Y-m-d H:i:s' ),
                                'APP_DOC_COMMENT' => '',
                                'APP_DOC_TITLE' => '',
                                'APP_DOC_FILENAME' => $_FILES ['form'] ['name'] [$fieldName]
                            );
                        }
    
                        $appDocument->create($fields);
                        $docVersion = $appDocument->getDocVersion();
                        $appDocUid = $appDocument->getAppDocUid ();
                        $info = pathinfo ( $appDocument->getAppDocFilename () );
                        $extension = (isset ( $info ['extension'] ) ? $info ['extension'] : '');
                        $pathName = PATH_DOCUMENT . $_REQUEST['APP_UID'] . PATH_SEP;
                        $fileName = $appDocUid . '_'.$docVersion.'.' . $extension;
    
                        G::uploadFile ( $_FILES ['form'] ['tmp_name'] [$fieldName], $pathName, $fileName );
                    }
                }
            }
    
            $assign = $result['message'];
            $aMessage['MESSAGE'] = '<strong>The information was submitted. Thank you.</strong>';
        } else {
            throw new Exception('An error occurred while the application was being processed.<br /><br />
                                 Error code: '.$result->status_code.'<br />
                                 Error message: '.$result->message.'<br /><br />');
        }
    
        // Update
        $dataResponses['ABE_RES_STATUS'] = ($code == 0 ? 'SENT' : 'ERROR');
        $dataResponses['ABE_RES_MESSAGE'] = ($code == 0 ? '-' : $result->message);
    
        try {
            $abeAbeResponsesInstance = new AbeResponses();
            $abeAbeResponsesInstance->createOrUpdate($dataResponses);
        } catch (Exception $error) {
            throw $error;
        }
    
        $G_PUBLISH->AddContent('xmlform', 'xmlform', 'login/showInfo', '', $aMessage);
    } catch (Exception $error) {
        $G_PUBLISH->AddContent('xmlform', 'xmlform', 'login/showMessage', '', array('MESSAGE' => $error->getMessage().'Please contact to your system administrator.'));
    }
    
    G::RenderPage('publish', 'blank');
}

