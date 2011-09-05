<?php

/**
 * Case HttpProxyController
 */
class caseProxy extends HttpProxyController
{

  function sendJsonResultGeneric($response, $callback) 
  {
    header("Content-Type: application/json");
    $finalResponse = json_encode($response);
    if ($callback != '') {
      print $callback . "($finalResponse);";
    } else {
      print $finalResponse;
    }
  }

  /**
   * Get Notes List
   * @param int $httpData->start
   * @param int $httpData->limit
   * @param string $httpData->appUid (optionalif it is not passed try use $_SESSION['APPLICATION'])
   * @return array containg the case notes
   */
  function getNotesList($httpData)
  {
    require_once ( "classes/model/AppNotes.php" );
    $appUid = null;
    
    if (isset($httpData->appUid) && trim($httpData->appUid) != "") {
      $appUid = $httpData->appUid;
    } 
    else {
      if (isset($_SESSION['APPLICATION'])) {
        $appUid = $_SESSION['APPLICATION'];
      }
    }

    if (!isset($appUid)) {
      throw new Exception('Can\'t resolve the Apllication ID for this request.');
    }

    $usrUid   = isset($_SESSION['USER_LOGGED']) ? $_SESSION['USER_LOGGED'] : "";
    $appNotes = new AppNotes();
    $response = $appNotes->getNotesList($appUid, $usrUid, $httpData->start, $httpData->limit);
    
    return $response['array'];
  }

  function postNote($httpData) 
  {
    //extract(getExtJSParams());
    if (isset($httpData->appUid) && trim($httpData->appUid) != "") {
      $appUid = $httpData->appUid;
    } 
    else {
      $appUid = $_SESSION['APPLICATION'];
    }
    
    if (!isset($appUid)) {
      throw new Exception('Can\'t resolve the Apllication ID for this request.');
    }

    $usrUid = (isset($_SESSION['USER_LOGGED'])) ? $_SESSION['USER_LOGGED'] : "";
    require_once ( "classes/model/AppNotes.php" );

    $appNotes = new AppNotes();
    $noteContent = addslashes($httpData->noteText);

    $result = $appNotes->postNewNote($appUid, $usrUid, $noteContent, false);

    // Disabling the controller response because we handle a special behavior
    $this->setSendResponse(false);

    //send the response to client
    @ini_set('implicit_flush', 1);
    ob_start();
    echo G::json_encode($result);
    @ob_flush();
    @flush();
    @ob_end_flush();
    ob_implicit_flush(1);

    //send notification in background
    $noteRecipientsList = array();
    G::LoadClass('case');
    $oCase = new Cases();

    $p = $oCase->getUsersParticipatedInCase($appUid);
    foreach($p['array'] as $key => $userParticipated){
      $noteRecipientsList[] = $key;
    }
    $noteRecipients = implode(",", $noteRecipientsList);

    $appNotes->sendNoteNotification($appUid, $usrUid, $noteContent, $noteRecipients);
  }
    
}