<?php

function postNote($httpData)
{
    //extract(getExtJSParams());
    $appUid = (isset($httpData->appUid))? $httpData->appUid : '';

    $usrUid = (isset($httpData->usrUid))? $httpData->usrUid : '' ;

    require_once ( "classes/model/AppNotes.php" );

    $appNotes = new AppNotes();
    $noteContent = addslashes($httpData->noteText);

    $result = $appNotes->postNewNote($appUid, $usrUid, $noteContent, false);
    //return true;

    //die();
    //send the response to client
    @ini_set('implicit_flush', 1);
    ob_start();
    //echo G::json_encode($result);
    @ob_flush();
    @flush();
    @ob_end_flush();
    ob_implicit_flush(1);
    //return true;
    //send notification in background
    $noteRecipientsList = array();
    G::LoadClass('case');
    $oCase = new Cases();

    $p = $oCase->getUsersParticipatedInCase($appUid);

    foreach ($p['array'] as $key => $userParticipated) {
        $noteRecipientsList[] = $key;
    }

    $noteRecipients = implode(",", $noteRecipientsList);

    $appNotes->sendNoteNotification($appUid, $usrUid, $noteContent, $noteRecipients);

}

function loadAbeRequest($AbeRequestsUid)
{
    require_once 'classes/model/AbeRequests.php';

    $criteria = new Criteria();
    $criteria->add(AbeRequestsPeer::ABE_REQ_UID, $AbeRequestsUid);
    $resultRequests = AbeRequestsPeer::doSelectRS($criteria);
    $resultRequests->setFetchmode(ResultSet::FETCHMODE_ASSOC);
    $resultRequests->next();
    $abeRequests = $resultRequests->getRow();

    return $abeRequests;
}

function loadAbeConfiguration($AbeConfigurationUid)
{
    require_once 'classes/model/AbeConfiguration.php';

    $criteria = new Criteria();
    $criteria->add(AbeConfigurationPeer::ABE_UID, $AbeConfigurationUid);
    $result = AbeConfigurationPeer::doSelectRS($criteria);
    $result->setFetchmode(ResultSet::FETCHMODE_ASSOC);
    $result->next();
    $abeConfiguration = $result->getRow();

    return $abeConfiguration;
}

function uploadAbeRequest($data)
{
    require_once 'classes/model/AbeRequests.php';

    try {
        $abeRequestsInstance = new AbeRequests();
        $abeRequestsInstance->createOrUpdate($data);
    } catch (Exception $error) {
        throw $error;
    }
}

