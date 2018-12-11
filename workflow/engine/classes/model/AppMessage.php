<?php

/**
 * Skeleton subclass for representing a row from the 'APP_MESSAGE' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements. This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package workflow.engine.classes.model
 */
class AppMessage extends BaseAppMessage
{

    const TYPE_TEST = 1;
    const TYPE_TRIGGER = 2;
    const TYPE_DERIVATION = 3;
    const TYPE_EXTERNAL_REGISTRATION = 4;
    const MESSAGE_STATUS_SENT = 1;
    const MESSAGE_STATUS_PENDING = 2;
    const MESSAGE_STATUS_FAILED = 3;
    private $data_spool;
    private $status_spool;
    private $error_spool;
    public static $app_msg_status_values = ['sent' => 1, 'pending' => 2, 'failed' => 3];
    public static $app_msg_type_values = ['TEST' => 1, 'TRIGGER' => 2, 'DERIVATION' => 3, 'EXTERNAL_REGISTRATION' => 4];

    public function getSpoolStatus ()
    {
        return $this->status_spool;
    }

    public function getSpoolError ()
    {
        return $this->error_spool;
    }

    /**
     * @deprecated version 3.2.4
     *
     * This function is not used in the core
     *
     */
    public function quickSave2 ($data_spool)
    {
        $this->data_spool = $data_spool;

        $sUID = G::generateUniqueID();
        $spool = new AppMessage();

        $spool->setAppMsgUid( $sUID );
        $spool->setMsgUid( $data_spool['msg_uid'] );
        $spool->setAppUid( $data_spool['app_uid'] );
        $spool->setDelIndex( $data_spool['del_index'] );
        $spool->setAppMsgType( $data_spool['app_msg_type'] );
        $spool->setAppMsgSubject( $data_spool['app_msg_subject'] );
        $spool->setAppMsgFrom( $data_spool['app_msg_from'] );
        $spool->setAppMsgTo( $data_spool['app_msg_to'] );
        $spool->setAppMsgBody( $data_spool['app_msg_body'] );
        $spool->setAppMsgDate( date( 'Y-m-d H:i:s' ) );
        $spool->setAppMsgCc( $data_spool['app_msg_cc'] );
        $spool->setAppMsgBcc( $data_spool['app_msg_bcc'] );
        $spool->setappMsgAttach( $data_spool['app_msg_attach'] );
        $spool->setAppMsgTemplate( $data_spool['app_msg_template'] );
        $spool->setAppMsgStatus( $data_spool['app_msg_status'] );
        $spool->setAppMsgError( $data_spool['app_msg_error'] );

        if (! $spool->validate()) {
            $this->error_spool = $spool->getValidationFailures();
            $this->status_spool = 'error';

            $error_msg = "AppMessage::quickSave(): Validation error: \n";
            foreach ($errors as $key => $value) {
                $error_msg .= $value->getMessage( $key ) . "\n";
            }
            throw new Exception( $error_msg );
        } else {
            //echo "Saving - validation ok\n";
            $this->error_spool = '';
            $this->status = 'success';
            $spool->save();
        }
        return $sUID;
    }

    /**
     * @deprecated version 3.2.4
     *
     * This function is not used in the core
     *
     */
    public function quickSave ($aData)
    {
        if (isset( $aData['app_msg_uid'] )) {
            $o = EmployeePeer::retrieveByPk( $aData['app_msg_uid'] );
        }
        if (isset( $o ) && is_object( $o ) && get_class( $o ) == 'AppMessage') {
            $o->fromArray( $aData, BasePeer::TYPE_FIELDNAME );
            $o->setAppMsgDate( date( 'Y-m-d H:i:s' ) );
            $o->save();
            return $o->getAppMsgUid();
        } else {
            $this->fromArray( $aData, BasePeer::TYPE_FIELDNAME );
            $this->setAppMsgDate( date( 'Y-m-d H:i:s' ) );
            $this->save();
            return $this->getAppMsgUid();
        }
    }

    /**
     * Update the column APP_MSG_STATUS
     *
     * @param string $msgUid
     * @param integer $msgStatusId
     *
     * @return void
     *
    */
    public function updateStatus($msgUid, $msgStatusId)
    {
        $message = AppMessagePeer::retrieveByPk($msgUid);
        $message->fromArray($message, BasePeer::TYPE_FIELDNAME);
        $message->setAppMsgStatusId($msgStatusId);
        $msgStatus = array_search($msgStatusId, self::$app_msg_status_values);
        $message->setAppMsgStatus($msgStatus);
        $message->save();
    }

    /**
     * Get all status and labels
     *
     * @return array
    */
    public static function getAllStatus()
    {
        $status = [];
        $status[] = ['', G::LoadTranslation('ID_ALL')];
        foreach (AppMessage::$app_msg_status_values as $key => $value) {
            $status[] = [$value, G::LoadTranslation('ID_' . strtoupper($key))];
        }

        return $status;
    }

    /**
     * Build the row for the message to be inserted
     *
     * @param string $msgUid,
     * @param string $appUid,
     * @param integer $delIndex,
     * @param string $appMsgType,
     * @param string $appMsgSubject,
     * @param string $appMsgFrom,
     * @param string $appMsgTo,
     * @param string $appMsgBody,
     * @param string $appMsgCc,
     * @param string $appMsgBcc,
     * @param string $appMsgTemplate,
     * @param string $appMsgAttach,
     * @param string $appMsgStatus,
     * @param string $appMsgShowMsg,
     * @param string $appMsgError,
     * @param boolean $contentTypeIsHtml
     * @param integer $appNumber,
     * @param integer $proId,
     * @param integer $tasId,
     *
     * @return array
     */
    public static function buildMessageRow(
        $msgUid = '',
        $appUid = '',
        $delIndex = 0,
        $appMsgType = '',
        $appMsgSubject = '',
        $appMsgFrom = '',
        $appMsgTo = '',
        $appMsgBody = '',
        $appMsgCc = '',
        $appMsgBcc = '',
        $appMsgTemplate = '',
        $appMsgAttach = '',
        $appMsgStatus = 'pending',
        $appMsgShowMsg = '',
        $appMsgError = '',
        $contentTypeIsHtml = true,
        $appNumber = 0,
        $proId = 0,
        $tasId = 0
    )
    {
        $message = [
            "msg_uid" => $msgUid,
            "app_uid" => $appUid,
            "del_index" => $delIndex,
            "app_msg_type" => $appMsgType,
            "app_msg_type_id" => isset(AppMessage::$app_msg_type_values[$appMsgType]) ? AppMessage::$app_msg_type_values[$appMsgType] : 0,
            "app_msg_subject" => $appMsgSubject,
            "app_msg_from" => $appMsgFrom,
            "app_msg_to" => $appMsgTo,
            "app_msg_body" => $appMsgBody,
            "app_msg_date" => '',
            "app_msg_cc" => $appMsgCc,
            "app_msg_bcc" => $appMsgBcc,
            "app_msg_template" => $appMsgTemplate,
            "app_msg_status" => $appMsgStatus,
            "app_msg_status_id" => isset(AppMessage::$app_msg_status_values[$appMsgStatus]) ? AppMessage::$app_msg_status_values[$appMsgStatus] : 0,
            "app_msg_attach" => $appMsgAttach,
            "app_msg_send_date" => '',
            "app_msg_show_message" => $appMsgShowMsg,
            "app_msg_error" => $appMsgError,
            "contentTypeIsHtml" => $contentTypeIsHtml,
            "app_number" => $appNumber,
            "pro_id" => $proId,
            "tas_id" => $tasId
        ];

        return $message;
    }

    /**
     * Get the initial criteria for the appMessage
     *
     * @param int $appNumber
     * @param boolean $onlyVisible
     *
     * @return Criteria
     */
    public function getInitialCriteria($appNumber, $onlyVisible = true)
    {
        $criteria = new Criteria('workflow');
        //Search by appNumber
        $criteria->add(AppMessagePeer::APP_NUMBER, $appNumber);
        //Visible: if the user can be resend the email
        if ($onlyVisible) {
            $criteria->add(AppMessagePeer::APP_MSG_SHOW_MESSAGE, 1);
        }

        return $criteria;
    }

    /**
     * Returns the number of cases of a user
     *
     * @param int $appNumber
     * @param boolean $onlyVisible
     *
     * @return int
     */
    public function getCountMessage($appNumber, $onlyVisible = true)
    {
        $criteria = $this->getInitialCriteria($appNumber, $onlyVisible);

        return AppMessagePeer::doCount($criteria);
    }

    /**
     * Get the data by appNumber
     *
     * @param int $appNumber
     * @param boolean $onlyVisible
     * @param integer $start
     * @param integer $limit
     * @param string $sort
     * @param string $dir
     *
     * @return array
     */
    public function getDataMessage($appNumber, $onlyVisible = true, $start = null, $limit = null, $sort = null, $dir = 'DESC')
    {
        $criteria = $this->getInitialCriteria($appNumber, $onlyVisible);

        if (empty($sort)) {
            $sort = AppMessagePeer::APP_MSG_DATE;
        }
        if ($dir == 'DESC') {
            $criteria->addDescendingOrderByColumn($sort);
        } else {
            $criteria->addAscendingOrderByColumn($sort);
        }

        if (!is_null($limit) && !is_null($start)) {
            $criteria->setLimit($limit);
            $criteria->setOffset($start);
        }

        $dataset = AppMessagePeer::doSelectRS($criteria);
        $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
        $dataset->next();
        $messages = [];

        while ($row = $dataset->getRow()) {
            //Head for IE quirks mode
            $row['APP_MSG_BODY'] = '<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />' . $row['APP_MSG_BODY'];
            $messages[] = $row;
            $dataset->next();
        }

        return $messages;
    }
}

