<?php
namespace ProcessMaker\BusinessModel;

use \G;
/**
 * @copyright Colosa - Bolivia
 */
class Pmgmail {

    /**
     * Get User by usrGmail
     *
     * @param string $usr_gmail Unique id of User
     *
     * return uid
     *
     */
    public function getUserByEmail($usr_gmail)
    {
    	//getting the user data
        require_once (PATH_HOME . "engine" . PATH_SEP . "classes" . PATH_SEP . "model" . PATH_SEP . "Users.php");
        $oUsers = new \Users();
        $response['user'] = $oUsers->loadByUserEmailInArray($usr_gmail);
		
        //getting the skin
        require_once (PATH_HOME . "engine" . PATH_SEP . "classes" . PATH_SEP . "class.system.php");
        $sysConf = new \System();
        $responseSysConfig = $sysConf->getSystemConfiguration( PATH_CONFIG . 'env.ini' );
        $response['enviroment'] = $responseSysConfig['default_skin'];

        return $response;
    }

    /**
     * Get Application data by appUid
     *
     * @param string $app_uid Unique id of the app
     * @param string $index
     *
     * return row app_cache_view
     *
     */
    public function getDraftApp($app_uid, $index=1)
    {
    	$c = new \Criteria( 'workflow' );

    	$c->clearSelectColumns();
    	$c->addSelectColumn( \AppCacheViewPeer::APP_NUMBER );
    	$c->addSelectColumn( \AppCacheViewPeer::APP_STATUS );
    	$c->addSelectColumn( \AppCacheViewPeer::DEL_INDEX );
    	$c->addSelectColumn( \AppCacheViewPeer::APP_DEL_PREVIOUS_USER );
    	$c->addSelectColumn( \AppCacheViewPeer::DEL_DELEGATE_DATE );
    	$c->addSelectColumn( \AppCacheViewPeer::USR_UID );
    	$c->addSelectColumn( \AppCacheViewPeer::PRO_UID );
    	$c->addSelectColumn( \AppCacheViewPeer::APP_PRO_TITLE );
    	$c->addSelectColumn( \AppCacheViewPeer::APP_TAS_TITLE );
    	$c->addSelectColumn( \AppCacheViewPeer::DEL_THREAD_STATUS );
    	$c->addSelectColumn( \AppCacheViewPeer::TAS_UID );
    	$c->addSelectColumn( \AppCacheViewPeer::DEL_LAST_INDEX );

    	$c->add( \AppCacheViewPeer::APP_UID, $app_uid );
    	$c->add( \AppCacheViewPeer::DEL_INDEX, $index );

    	$rs = \AppCacheViewPeer::doSelectRS( $c );
    	$rs->setFetchmode( \ResultSet::FETCHMODE_ASSOC );

    	$rows = Array ();
    	while ($rs->next()) {
    		$rows[] = $rs->getRow();
    	}
    	return $rows;
    }

    /**
     * Send email using appUid and mail
     *
     * @param string $app_uid Unique id of the app
     * @param string $mail
     *
     * return uid
     *
     */
    public function sendEmail($app_uid, $mailToAddresses, $index, $arrayTask = null, $arrayData = null)
    {
        require_once (PATH_HOME . "engine" . PATH_SEP . "classes" . PATH_SEP . "model" . PATH_SEP . "Application.php");
        //getting the default email server
        $defaultEmail = $this->emailAccount();

        if ($defaultEmail === null) {
            error_log(G::LoadTranslation('ID_EMAIL_ENGINE_IS_NOT_ENABLED'));
            return false;
        }
        $mailCcAddresses = "";
        $oApplication = new \Application();
        $formData = $oApplication->Load($app_uid);

        $frmData = unserialize($formData['APP_DATA']);
        $dataFormToShowString = "";

        foreach ($frmData as $field => $value) {
            if (($field != 'SYS_LANG') &&
                ($field != 'SYS_SKIN') &&
                ($field != 'SYS_SYS') &&
                ($field != 'APPLICATION') &&
                ($field != 'PROCESS') &&
                ($field != 'TASK') &&
                ($field != 'INDEX') &&
                ($field != 'USER_LOGGED') &&
                ($field != 'USR_USERNAME') &&
                ($field != 'DYN_CONTENT_HISTORY') &&
                ($field != 'PIN') &&
                (!is_array($value))
            ) {
                $dataFormToShowString .= " " . $field . " " . $value;
            }
        }
        $appData = $this->getDraftApp($app_uid, $index);

        foreach ($appData as $application) {
            $appNumber = $application['APP_NUMBER'];
            $appStatus = $application['APP_STATUS'];
            $index = $application['DEL_INDEX'];
            $prvUsr = $application['APP_DEL_PREVIOUS_USER'];
            $delegateDate = $application['DEL_DELEGATE_DATE'];
            $nextUsr = $application['USR_UID'];
            $proUid = $application['PRO_UID'];
            $proName = $application['APP_PRO_TITLE'];
            $tasName = $application['APP_TAS_TITLE'];
            $threadStatus = $application['DEL_THREAD_STATUS'];
            $tasUid = $application['TAS_UID'];
            $lastIndex = $application['DEL_LAST_INDEX'];

            if ($appStatus == "DRAFT") {
                $labelID = "PMDRFT";
            } else {
                $labelID = "PMIBX";
            }

            if (( string ) $mailToAddresses === "") {
                if ($arrayTask) {
                    $oCases = new \Cases ();

                    foreach ( $arrayTask as $aTask ) {
                        if (! isset ( $aTask ["USR_UID"] )) {
                            $aTask ["USR_UID"] = "";
                        }
                        $respTo = $oCases->getTo ( $aTask ["TAS_ASSIGN_TYPE"], $aTask ["TAS_UID"], $aTask ["USR_UID"], $arrayData );
                        $mailToAddresses = $respTo ['to'];
                        $mailCcAddresses = $respTo ['cc'];
                        
                        if ($aTask ["TAS_ASSIGN_TYPE"] === "SELF_SERVICE") {
                            $labelID = "PMUASS";
                            if (( string ) $mailToAddresses === "") { // Self Service Value Based
                                $criteria = new \Criteria ( "workflow" );
                                $criteria->addSelectColumn ( \AppAssignSelfServiceValuePeer::GRP_UID );
                                $criteria->add ( \AppAssignSelfServiceValuePeer::APP_UID, $app_uid );

                                $rsCriteria = \AppAssignSelfServiceValuePeer::doSelectRs ( $criteria );
                                $rsCriteria->setFetchmode ( \ResultSet::FETCHMODE_ASSOC );

                                while ( $rsCriteria->next () ) {
                                    $row = $rsCriteria->getRow ();
                                }
                                $targetIds = unserialize ( $row ['GRP_UID'] );
                                $oUsers = new \Users ();

                                if (is_array($targetIds)) {
                                    foreach ( $targetIds as $user ) {
                                        $usrData = $oUsers->loadDetails ( $user );
                                        $nextMail = $usrData ['USR_EMAIL'];
                                        $mailToAddresses .= ($mailToAddresses == '') ? $nextMail : ',' . $nextMail;
                                    }
                                } else {
                                    $group = new \Groups();
                                    $users = $group->getUsersOfGroup($targetIds);
                                    foreach ($users as $user) {
                                        $nextMail = $user['USR_EMAIL'];
                                        $mailToAddresses .= ($mailToAddresses == '') ? $nextMail : ',' . $nextMail;
                                    }
                                }
                            }
                            
                        }
                    }
                } else {
                    $oUsers = new \Users ();

                    $usrData = $oUsers->loadDetails ( $nextUsr );
                    $mailToAddresses = $usrData ['USR_EMAIL'];
                }
            }

            //first template
            $pathTemplate = PATH_DATA_SITE . "mailTemplates" . PATH_SEP . "pmGmail.html";
            if (!file_exists($pathTemplate)) {
                $file = @fopen($pathTemplate, "w");
                fwrite($file, '<div>');
                fwrite($file, '<span style="display: none !important;">');
                fwrite($file, '-**- Process Name: @#proName<br/>');
                fwrite($file, '-**- Case Number: @#appNumber<br/>');
                fwrite($file, '-**- Case UID: @#caseUid<br/>');
                fwrite($file, '-**- Task Name: @#taskName<br/>');
                fwrite($file, '-**- Index: @#index<br/>');
                fwrite($file, '-**- Action: @#action<br/>');
                fwrite($file, '-**- Previous User: @#prevUser<br/>');
                fwrite($file, '-**- Delegate Date: @#delDate<br/>');
                fwrite($file, '-**- Process Id: @#proUid<br/>');
                fwrite($file, '-**- Type: @#type<br/>');
                fwrite($file, '-**- FormFields: @@oform<br/>');
                fwrite($file, '</span>');
                fwrite($file, '</div>');
                fclose($file);
            }

            $change = array('[', ']', '"');
            $fdata = str_replace($change, ' ', $dataFormToShowString);
            $aFields = array(
                'proName' => $proName,
                'appNumber' => $appNumber,
                'caseUid' => $app_uid,
                'taskName' => $tasName,
                'index' => $index,
                'action' => $appStatus,
                'prevUser' => $prvUsr,
                'delDate' => $delegateDate,
                'proUid' => $proUid,
                'type' => $labelID,
                'oform' => $fdata
            );

            $subject = "[PM] " . $proName . " (" . $index . ") Case: " . $appNumber;

            $ws = new \wsBase();

            $resultMail = $ws->sendMessage(
                $app_uid,
                $defaultEmail, //From,
                $mailToAddresses,//$To,
                $mailCcAddresses,//$Cc
                '',
                $subject,
                'pmGmail.html',//template
                $aFields, //fields
                array(),
                true,
                0,
                array(),
                1
            );
            return $resultMail;
        }
        return 'The appUid cant be founded';
    }


    /**
     * Get if the license has the feature
     *
     * return uid
     *
     */
    public function hasGmailFeature()
    {
        require_once (PATH_HOME . "engine" . PATH_SEP . "classes" . PATH_SEP . "class.licensedFeatures.php");

        $licensedFeatures = new \PMLicensedFeatures();
        if (!$licensedFeatures->verifyfeature('7qhYmF1eDJWcEdwcUZpT0k4S0xTRStvdz09')) {
            return false;
        }else {
            return true;
        }
    }

    /**
     * Get the default 'email from account' that is used to send emails in the server email in PM
     *
     * return uid
     *
     */
    public function emailAccount()
    {
        $emailServer = new \EmailServer();
        $response = $emailServer->loadDefaultAccount();

        return $response['MESS_ACCOUNT'];
    }
    
    /**
     * Business Model to delete all the labels of an acount
     *
     * @param string $mail
     *
     * return uid
     *
     */
    public function deleteLabels($mail)
    {
    	require_once(PATH_HOME . "engine" . PATH_SEP . "classes" . PATH_SEP . "class.labelsGmail.php");
    	$oLabels = new \labelsGmail();
    
    	$response = $oLabels->deletePMGmailLabels($mail);
    
    	return $response;
    }

    public function modifyMailToPauseCase($appUid, $appDelIndex)
    {
        require_once (PATH_HOME . "engine" . PATH_SEP . "classes" . PATH_SEP . "class.labelsGmail.php");
        $oLabels = new \labelsGmail();
        $oResponse = $oLabels->setLabelsToPauseCase($appUid, $appDelIndex);
    }

    public function modifyMailToUnpauseCase($appUid, $appDelIndex)
    {
        require_once (PATH_HOME . "engine" . PATH_SEP . "classes" . PATH_SEP . "class.labelsGmail.php");
        $oLabels = new \labelsGmail();
        $oResponse = $oLabels->setLabelsToUnpauseCase($appUid, $appDelIndex);
    }
}

