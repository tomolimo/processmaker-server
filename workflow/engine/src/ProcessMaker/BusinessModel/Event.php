<?php
namespace ProcessMaker\BusinessModel;

/**
 * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
 * @copyright Colosa - Bolivia
 */
class Event
{
    /**
     * Get list for Events
     * @var string $pro_uid. Uid for Process
     * @var string $filter.
     * @var string $evn_uid. Uid for Process
     *
     * @access public
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @return array
     */
    public function getEvents($pro_uid, $filter = '', $evn_uid = '')
    {
        $pro_uid = $this->validateProUid($pro_uid);
        if ($evn_uid != '') {
            $evn_uid = $this->validateEvnUid($evn_uid);
        }

        $oProcess = new \Process();
        if (!($oProcess->processExists($pro_uid))) {
            throw (new \Exception(\G::LoadTranslation("ID_PROCESS_NOT_EXIST", array('pro_uid',$pro_uid))));
        }

        $sDelimiter = \DBAdapter::getStringDelimiter();
        $oCriteria  = new \Criteria('workflow');
        $oCriteria->addSelectColumn(\EventPeer::EVN_UID);
        $oCriteria->addSelectColumn(\EventPeer::EVN_ACTION);
        $oCriteria->addSelectColumn(\EventPeer::EVN_STATUS);
        $oCriteria->addSelectColumn(\EventPeer::EVN_WHEN_OCCURS);
        $oCriteria->addSelectColumn(\EventPeer::EVN_RELATED_TO);

        $oCriteria->addAsColumn('EVN_DESCRIPTION', \ContentPeer::CON_VALUE);
        $aConditions = array();
        $aConditions[] = array(\EventPeer::EVN_UID, \ContentPeer::CON_ID );
        $aConditions[] = array(\ContentPeer::CON_CATEGORY, $sDelimiter . 'EVN_DESCRIPTION' . $sDelimiter );
        $aConditions[] = array(\ContentPeer::CON_LANG, $sDelimiter . SYS_LANG . $sDelimiter );
        $oCriteria->addJoinMC($aConditions, \Criteria::LEFT_JOIN);
        $oCriteria->add(\EventPeer::PRO_UID, $pro_uid);
        if ($evn_uid != '') {
            $oCriteria->add(\EventPeer::EVN_UID, $evn_uid);
        }

        switch ($filter) {
            case 'message':
                $oCriteria->add(\EventPeer::EVN_ACTION, "SEND_MESSAGE");
                break;
            case 'conditional':
                $oCriteria->add(\EventPeer::EVN_ACTION, "EXECUTE_CONDITIONAL_TRIGGER");
                break;
            case 'multiple':
                $oCriteria->add(\EventPeer::EVN_ACTION, "EXECUTE_TRIGGER");
                break;
        }
        $eventsArray = array();

        $oDataset = \EventPeer::doSelectRS($oCriteria);
        $oDataset->setFetchmode(\ResultSet::FETCHMODE_ASSOC);
        $oDataset->next();
        while ($aRow = $oDataset->getRow()) {
            $oEvent = new \Event();
            $aFields = $oEvent->load( $aRow['EVN_UID'] );
            $aRow = array_merge($aRow, $aFields);
            $eventsArray[] = array_change_key_case($aRow, CASE_LOWER);
            $oDataset->next();
        }

        if ($evn_uid != '' && empty($eventsArray)) {
            throw (new \Exception(\G::LoadTranslation("ID_ROW_DOES_NOT_EXIST")));
        } elseif ($evn_uid != '' && !empty($eventsArray)) {
            return current($eventsArray);
        }
        return $eventsArray;
    }

    /**
     * Save Event Post Put
     *
     * @param string $evn_uid
     *
     * @access public
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @return array
     */
    public function saveEvents($pro_uid, $dataEvent, $create = false)
    {
        $pro_uid = $this->validateProUid($pro_uid);
        if (!$create) {
            $dataEvent['evn_uid'] = $this->validateEvnUid($dataEvent['evn_uid']);
        }

        if ( ($pro_uid == '') || (count($dataEvent) == 0) ) {
            return false;
        }
        $dataEvent = array_change_key_case($dataEvent, CASE_UPPER);
        if ($dataEvent['EVN_RELATED_TO'] == 'SINGLE') {
            if (empty($dataEvent['TAS_UID'])) {
                throw (new \Exception(\G::LoadTranslation("ID_FIELD_REQUIRED", array('tas_uid'))));
            }
            $this->validateTasUid($dataEvent['TAS_UID']);
        } else {
            if (empty($dataEvent['EVN_TAS_UID_FROM'])) {
                throw (new \Exception(\G::LoadTranslation("ID_FIELD_REQUIRED", array('evn_tas_uid_from'))));
            }
            $this->validateTasUid($dataEvent['EVN_TAS_UID_FROM']);
            $dataEvent['TAS_UID'] = $dataEvent['EVN_TAS_UID_FROM'];

            if (empty($dataEvent['EVN_TAS_UID_TO'])) {
                throw (new \Exception(\G::LoadTranslation("ID_FIELD_REQUIRED", array('evn_tas_uid_to'))));
            }
            $this->validateTasUid($dataEvent['EVN_TAS_UID_TO']);
        }

        $this->validateTriUid($dataEvent['TRI_UID']);
        if ( $create && (isset($dataEvent['EVN_UID'])) ) {
            unset($dataEvent['EVN_UID']);
        }

        $dataEvent['PRO_UID'] = $pro_uid;
        $oEvent = new \Event();

        if ($create) {
            $uidNewEvent = $oEvent->create( $dataEvent );
            $dataEvent = $this->getEvents($pro_uid, '', $uidNewEvent);
            $dataEvent = array_change_key_case($dataEvent, CASE_LOWER);
            return $dataEvent;
        } else {
            $oEvent->update( $dataEvent );
            $uidNewEvent = $dataEvent['EVN_UID'];
        }
    }

    /**
     * Delete Event
     *
     * @param string $evn_uid
     *
     * @access public
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @return void
     */
    public function deleteEvent($pro_uid, $evn_uid)
    {
        $pro_uid = $this->validateProUid($pro_uid);
        $evn_uid = $this->validateEvnUid($evn_uid);

        try {
            $oEvent = new \Event();
            $oEvent->remove( $evn_uid );
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Validate Process Uid
     * @var string $pro_uid. Uid for process
     *
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @return string
     */
    public function validateProUid ($pro_uid)
    {
        $pro_uid = trim($pro_uid);
        if ($pro_uid == '') {
            throw (new \Exception(\G::LoadTranslation("ID_PROJECT_NOT_EXIST", array('prj_uid',''))));
        }
        $oProcess = new \Process();
        if (!($oProcess->processExists($pro_uid))) {
            throw (new \Exception(\G::LoadTranslation("ID_PROJECT_NOT_EXIST", array('prj_uid',$pro_uid))));
        }
        return $pro_uid;
    }

    /**
     * Validate Event Uid
     * @var string $evn_uid. Uid for event
     *
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @return string
     */
    public function validateEvnUid ($evn_uid)
    {
        $evn_uid = trim($evn_uid);
        if ($evn_uid == '') {
            throw (new \Exception(\G::LoadTranslation("ID_EVENT_NOT_EXIST", array('evn_uid',''))));
        }
        $oEvent = new \Event();
        if (!($oEvent->Exists($evn_uid))) {
            throw (new \Exception(\G::LoadTranslation("ID_EVENT_NOT_EXIST", array('evn_uid',$evn_uid))));
        }
        return $evn_uid;
    }

    /**
     * Validate Task Uid
     * @var string $tas_uid. Uid for task
     *
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @return string
     */
    public function validateTasUid($tas_uid)
    {
        $tas_uid = trim($tas_uid);
        if ($tas_uid == '') {
            throw (new \Exception(\G::LoadTranslation("ID_TASK_NOT_EXIST", array('tas_uid',''))));
        }
        $oTask = new \Task();
        if (!($oTask->taskExists($tas_uid))) {
            throw (new \Exception(\G::LoadTranslation("ID_TASK_NOT_EXIST", array('tas_uid',$tas_uid))));
        }
        return $tas_uid;
    }

    /**
     * Validate Trigger Uid
     * @var string $tri_uid. Uid for trigger
     *
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @return string
     */
    public function validateTriUid($tri_uid)
    {
        $tri_uid = trim($tri_uid);
        if ($tri_uid == '') {
            throw (new \Exception(\G::LoadTranslation("ID_TRIGGER_NOT_EXIST", array('tri_uid',''))));
        }

        $oTriggers = new \Triggers();
        if (!($oTriggers->TriggerExists($tri_uid))) {
            throw (new \Exception(\G::LoadTranslation("ID_TRIGGER_NOT_EXIST", array('tri_uid',$tri_uid))));
        }

        return $tri_uid;
    }
}

