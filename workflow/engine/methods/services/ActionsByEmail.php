<?php
/*----------------------------------********---------------------------------*/
if (PMLicensedFeatures
        ::getSingleton()
        ->verifyfeature('zLhSk5TeEQrNFI2RXFEVktyUGpnczV1WEJNWVp6cjYxbTU3R29mVXVZNWhZQT0=')) {
    // since all the request parameters using this script are encrypted 
    // using the URL_KEY the probability of injecting any kind of code using
    // this entry point are only possible knowing the aforementioned key.
    switch (G::decrypt(urldecode(utf8_encode($_REQUEST['ACTION'])), URL_KEY)) {
        case 'processABE' :
            $G_PUBLISH = new Publisher();
            try {
                // Validations
                if (!isset($_REQUEST['APP_UID'])) {
                    $_REQUEST['APP_UID'] = '';
                }

                if (!isset($_REQUEST['DEL_INDEX'])) {
                    $_REQUEST['DEL_INDEX'] = '';
                }

                if ($_REQUEST['APP_UID'] == '') {
                    throw new Exception('The parameter APP_UID is empty.');
                }

                if ($_REQUEST['DEL_INDEX'] == '') {
                    throw new Exception('The parameter DEL_INDEX is empty.');
                }

                $_REQUEST['APP_UID'] = G::decrypt(urldecode(utf8_encode($_REQUEST['APP_UID'])), URL_KEY);
                $_REQUEST['DEL_INDEX'] = G::decrypt(urldecode(utf8_encode($_REQUEST['DEL_INDEX'])), URL_KEY);
                $_REQUEST['FIELD'] = G::decrypt(urldecode(utf8_encode($_REQUEST['FIELD'])), URL_KEY);
                $_REQUEST['VALUE'] = G::decrypt(urldecode(utf8_encode($_REQUEST['VALUE'])), URL_KEY);
                $_REQUEST['ABER'] = G::decrypt(urldecode(utf8_encode($_REQUEST['ABER'])), URL_KEY);

                G::LoadClass('case');

                $cases = new Cases();
                $caseFieldsABE = $cases->loadCase($_REQUEST['APP_UID'], $_REQUEST['DEL_INDEX']);

                if (is_null($caseFieldsABE['DEL_FINISH_DATE'])) {
                    $dataField = array();
                    $dataField[$_REQUEST['FIELD']] = $_REQUEST['VALUE'];
                    $caseFieldsABE ['APP_DATA'] = array_merge($caseFieldsABE ['APP_DATA'], $dataField);

                    $dataResponses = array();
                    $dataResponses['ABE_REQ_UID'] = $_REQUEST['ABER'];
                    $dataResponses['ABE_RES_CLIENT_IP'] = $_SERVER['REMOTE_ADDR'];
                    $dataResponses['ABE_RES_DATA'] = serialize($_REQUEST['VALUE']);
                    $dataResponses['ABE_RES_STATUS'] = 'PENDING';
                    $dataResponses['ABE_RES_MESSAGE'] = '';

                    try {
                        require_once 'classes/model/AbeResponses.php';
                        $abeAbeResponsesInstance = new AbeResponses();
                        $dataResponses['ABE_RES_UID'] = $abeAbeResponsesInstance->createOrUpdate($dataResponses);
                    } catch (Exception $error) {
                        throw $error;
                    }

                    $cases->updateCase($_REQUEST['APP_UID'], $caseFieldsABE);

                    G::LoadClass('wsBase');

                    $ws = new wsBase();

                    $result = $ws->derivateCase($caseFieldsABE['CURRENT_USER_UID'], $_REQUEST['APP_UID'], $_REQUEST['DEL_INDEX'], true);
                    $code = (is_array($result) ? $result['status_code'] : $result->status_code);

                    if ($code != 0) {
                        throw new Exception('An error occurred while the application was being processed.<br /><br />
                                 Error code: ' . $result->status_code . '<br />
                                 Error message: ' . $result->message . '<br /><br />');
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

                    $message = '<strong>The answer has been submited. Thank you</strong>';

                    //Save Cases Notes
                    G::LoadClass('actionsByEmailUtils');

                    $dataAbeRequests = loadAbeRequest($_REQUEST['ABER']);
                    $dataAbeConfiguration = loadAbeConfiguration($dataAbeRequests['ABE_UID']);

                    if ($dataAbeConfiguration['ABE_CASE_NOTE_IN_RESPONSE'] == 1) {
                        $response = new stdclass();
                        $response->usrUid = $caseFieldsABE['APP_DATA']['USER_LOGGED'];
                        $response->appUid = $_REQUEST['APP_UID'];
                        $response->noteText = "Check the information that was sent for the receiver: " . $dataAbeRequests['ABE_REQ_SENT_TO'];

                        postNote($response);
                    }

                    $dataAbeRequests['ABE_REQ_ANSWERED'] = 1;
                    $code == 0 ? uploadAbeRequest($dataAbeRequests) : '';
                } else {
                    $message = '<strong>The response has already been sent.</strong>';
                }

                $G_PUBLISH->AddContent('xmlform', 'xmlform', 'login/showInfo', '', array('MESSAGE' => $message));
            } catch (Exception $error) {
                $G_PUBLISH->AddContent('xmlform', 'xmlform', 'login/showMessage', '', array('MESSAGE' => $error->getMessage() . 'Please contact to your system administrator.'));
            }

            G::RenderPage('publish', 'blank');


            break;
    }
}
/*----------------------------------********---------------------------------*/