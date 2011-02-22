<?php

/**
 * class.spool.php
 * @package workflow.engine.classes
 * @brief insert mail into the spool database
 *
 * @package Tomahawk_Mail
 * @author Ian K Armstrong <ika@[REMOVE_THESE_CAPITALS]openmail.cc>
 * @copyright Copyright (c) 2007, Ian K Armstrong
 * @license http://www.opensource.org/licenses/gpl-3.0.html GNU Public License
 * @link http://www.openmail.cc
 *
 * @category web_mail
 * @subpackage send
 * @filesource
 * @version
 *
 * @file class.insert.php
 *
 */

  require_once ( "classes/model/AppMessage.php" );

class insert
{
    private $db_spool;
    private $status;

   /**
    * construct of insert
    *
    * @param  string    $pPRO_UID  
    * @return void
    */
    function __construct($db_spool=array())
    {
      if(count($db_spool)>0)
        $db_spool  = $this->db_insert($db_spool);

    }

   /**
    * returnStatus
    *
    * @return $this->status;
    */
    public function returnStatus()
    {
      return $this->status;

    }

   /**
    * db_insert
    *
    * @param  array  $db_spool
    * @return string $sUID;
    */
    public function db_insert($db_spool)
    {
      $sUID  = G::generateUniqueID();
      $spool = new AppMessage();
      $spool->setAppMsgUid($sUID);
      $spool->setMsgUid($db_spool['msg_uid']);
      $spool->setAppUid($db_spool['app_uid']);
      $spool->setDelIndex($db_spool['del_index']);
      $spool->setAppMsgType($db_spool['app_msg_type']);
      $spool->setAppMsgSubject($db_spool['app_msg_subject']);
      $spool->setAppMsgFrom($db_spool['app_msg_from']);
      $spool->setAppMsgTo($db_spool['app_msg_to']);
      $spool->setAppMsgBody($db_spool['app_msg_body']);
      $spool->setAppMsgDate(date('Y-m-d H:i:s'));
      $spool->setAppMsgCc($db_spool['app_msg_cc']);
      $spool->setAppMsgBcc($db_spool['app_msg_bcc']);
      $spool->setappMsgAttach($db_spool['app_msg_attach']);
      $spool->setAppMsgTemplate($db_spool['app_msg_template']);
      $spool->setAppMsgStatus($db_spool['app_msg_status']);
      $spool->setAppMsgSendDate(date('Y-m-d H:i:s')); // Add by Ankit

      if(!$spool->validate()) {
        $errors       = $spool->getValidationFailures();
        $this->status = 'error';

        foreach($errors as $key => $value) {
          echo "Validation error - " . $value->getMessage($key) . "\n";
        }
      }
      else {
              //echo "Saving - validation ok\n";
        $this->status = 'success';
              $spool->save();
      }
      return $sUID;

    }




} // end of class



?>
