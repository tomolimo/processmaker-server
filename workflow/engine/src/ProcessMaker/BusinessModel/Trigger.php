<?php
namespace ProcessMaker\BusinessModel;

class Trigger
{
    /**
     * Get criteria for Trigger
     *
     * return object
     */
    public function getTriggerCriteria()
    {
        try {
            $delimiter = \DBAdapter::getStringDelimiter();

            $criteria = new \Criteria("workflow");

            $criteria->addSelectColumn(\TriggersPeer::TRI_UID);
            $criteria->addAsColumn("TRI_TITLE", "CT.CON_VALUE");
            $criteria->addAsColumn("TRI_DESCRIPTION", "CD.CON_VALUE");
            $criteria->addSelectColumn(\TriggersPeer::TRI_TYPE);
            $criteria->addSelectColumn(\TriggersPeer::TRI_WEBBOT);
            $criteria->addSelectColumn(\TriggersPeer::TRI_PARAM);

            $criteria->addAlias("CT", \ContentPeer::TABLE_NAME);
            $criteria->addAlias("CD", \ContentPeer::TABLE_NAME);

            $arrayCondition = array();
            $arrayCondition[] = array(\TriggersPeer::TRI_UID, "CT.CON_ID", \Criteria::EQUAL);
            $arrayCondition[] = array("CT.CON_CATEGORY", $delimiter . "TRI_TITLE" . $delimiter, \Criteria::EQUAL);
            $arrayCondition[] = array("CT.CON_LANG", $delimiter . SYS_LANG . $delimiter, \Criteria::EQUAL);
            $criteria->addJoinMC($arrayCondition, \Criteria::LEFT_JOIN);

            $arrayCondition = array();
            $arrayCondition[] = array(\TriggersPeer::TRI_UID, "CD.CON_ID", \Criteria::EQUAL);
            $arrayCondition[] = array("CD.CON_CATEGORY", $delimiter . "TRI_DESCRIPTION" . $delimiter, \Criteria::EQUAL);
            $arrayCondition[] = array("CD.CON_LANG", $delimiter . SYS_LANG . $delimiter, \Criteria::EQUAL);
            $criteria->addJoinMC($arrayCondition, \Criteria::LEFT_JOIN);

            return $criteria;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * List of Triggers in process
     * @var string $sProcessUID. Uid for Process
     *
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @return array
     */
    public function getTriggers($sProcessUID = '')
    {
        $criteria = $this->getTriggerCriteria();

        $criteria->add(\TriggersPeer::PRO_UID, $sProcessUID);
        $criteria->addAscendingOrderByColumn('TRI_TITLE');

        $oDataset = \TriggersPeer::doSelectRS($criteria);
        $oDataset->setFetchmode(\ResultSet::FETCHMODE_ASSOC);

        $oDataset->next();
        $triggersArray = array();
        //$triggersArray[] = array('TRI_UID' => 'char', 'PRO_UID' => 'char', 'TRI_TITLE' => 'char', 'TRI_DESCRIPTION' => 'char');
        while ($aRow = $oDataset->getRow()) {
            if (($aRow['TRI_TITLE'] == null) || ($aRow['TRI_TITLE'] == "")) {
                // There is no transaltion for this Trigger name, try to get/regenerate the label
                $triggerObj = $this->getDataTrigger($aRow['TRI_UID']);
                $aRow['TRI_TITLE'] = $triggerObj['tri_title'];
                $aRow['TRI_DESCRIPTION'] = $triggerObj['tri_description'];
            } else {
                if ($aRow['TRI_PARAM'] != '' && $aRow['TRI_PARAM'] != 'PRIVATE') {
                    $aRow['TRI_PARAM'] = unserialize($aRow['TRI_PARAM']);
                    $aRow['TRI_PARAM'] = \G::json_encode($aRow['TRI_PARAM']);
                }
            }
            $triggersArray[] = array_change_key_case($aRow, CASE_LOWER);
            $oDataset->next();
        }
        return $triggersArray;
    }

    /**
     * Get data for TriggerUid
     * @var string $sTriggerUID. Uid for Trigger
     *
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @return array
     */
    public function getDataTrigger($sTriggerUID = '')
    {
        $triggerO = new \Triggers();
        $triggerArray = $triggerO->load($sTriggerUID);
        if (isset($triggerArray['PRO_UID'])) {
            unset($triggerArray['PRO_UID']);
        }
        if ($triggerArray['TRI_PARAM'] != '' && $triggerArray['TRI_PARAM'] != 'PRIVATE') {
            $triggerArray['TRI_PARAM'] = unserialize($triggerArray['TRI_PARAM']);
            $triggerArray['TRI_PARAM'] = \G::json_encode($triggerArray['TRI_PARAM']);
        }
        $triggerArray = array_change_key_case($triggerArray, CASE_LOWER);
        return $triggerArray;
    }


    /**
     * Delete Trigger
     * @var string $sTriggerUID. Uid for Trigger
     *
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @return void
     */
    public function deleteTrigger($sTriggerUID = '')
    {
        $oTrigger = new \Triggers();
        $oTrigger->load( $sTriggerUID );
        $res = $oTrigger->verifyDependecies($sTriggerUID);
        $messageEnd = '';
        if ($res->code != 0) {
            foreach ($res->dependencies as $Object => $aDeps) {
                $nDeps = count( $aDeps );
                $message = str_replace( '{N}', $nDeps, \G::LoadTranslation( 'ID_TRIGGERS_VALIDATION_ERR2' ) );
                $message = str_replace( '{Object}', $Object, $message );
                $messageEnd .= $message . "\n";
                foreach ($aDeps as $dep) {
                    if (substr( $Object, - 1 ) == 's') {
                        $Object = substr( $Object, 0, strlen( $Object ) - 1 );
                    }
                    $message = str_replace( '{Object}', $Object, \G::LoadTranslation( 'ID_TRIGGERS_VALIDATION_ERR3' ) );
                    $message = str_replace( '{Description}', '"' . $dep['DESCRIPTION'] . '"', $message );
                    $messageEnd .= $message . "\n";
                }
                $messageEnd .= "\n";
            }
            throw new \Exception($messageEnd);
        }

        $oTrigger->remove( $sTriggerUID );
        $oStepTrigger = new \StepTrigger();
        $oStepTrigger->removeTrigger( $sTriggerUID );
    }

    /**
     * Save Data for Trigger
     * @var string $sProcessUID. Uid for Process
     * @var string $dataTrigger. Data for Trigger
     * @var string $create. Create o Update Trigger
     * @var string $sTriggerUid. Uid for Trigger
     *
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @return array
     */
    public function saveTrigger($sProcessUID = '', $dataTrigger = array(), $create = false, $sTriggerUid = '')
    {
        if ( ($sProcessUID == '') || (count($dataTrigger) == 0) ) {
            return false;
        }
        $dataTrigger = array_change_key_case($dataTrigger, CASE_UPPER);

        if ( $create && (isset($dataTrigger['TRI_UID'])) ) {
            unset($dataTrigger['TRI_UID']);
        }

        $dataTrigger= (array)$dataTrigger;
        $dataTrigger['TRI_TYPE'] = 'SCRIPT';

        if (isset($dataTrigger['TRI_TITLE'])) {
            if (!$this->verifyNameTrigger($sProcessUID, $dataTrigger['TRI_TITLE'], $sTriggerUid)) {
                throw new \Exception(\G::LoadTranslation("ID_CANT_SAVE_TRIGGER"));
            }
        }

        $dataTrigger['PRO_UID'] = $sProcessUID;
        $oTrigger = new \Triggers();
        if ($create) {
            $oTrigger->create( $dataTrigger );
            $dataTrigger['TRI_UID'] = $oTrigger->getTriUid();
        }

        $oTrigger->update( $dataTrigger );
        if ($create) {
            $dataResp = $oTrigger->load( $dataTrigger['TRI_UID'] );
            $dataResp = array_change_key_case($dataResp, CASE_LOWER);
            if (isset($dataResp['pro_uid'])) {
                unset($dataResp['pro_uid']);
            }
            return $dataResp;
        }
        return array();
    }

    /**
     * Verify name for trigger in process
     * @var string $sProcessUID. Uid for Process
     * @var string $sTriggerName. Name for Trigger
     * @var string $sTriggerUid. Uid for Trigger
     *
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @return boolean
     */
    public function verifyNameTrigger($sProcessUID, $sTriggerName, $sTriggerUid = '')
    {
        $oCriteria = new \Criteria("workflow");
        $oCriteria->addSelectColumn( \TriggersPeer::TRI_UID );
        $oCriteria->add( \TriggersPeer::PRO_UID, $sProcessUID );
        if ($sTriggerUid != '') {
            $oCriteria->add( \TriggersPeer::TRI_UID, $sTriggerUid, \Criteria::NOT_EQUAL);
        }
        $oDataset = \TriggersPeer::doSelectRS( $oCriteria );
        $oDataset->setFetchmode( \ResultSet::FETCHMODE_ASSOC );
        while ($oDataset->next()) {
            $aRow = $oDataset->getRow();

            $oCriteria1 = new \Criteria( 'workflow' );
            $oCriteria1->addSelectColumn( 'COUNT(*) AS TRIGGERS' );
            $oCriteria1->add( \ContentPeer::CON_CATEGORY, 'TRI_TITLE' );
            $oCriteria1->add( \ContentPeer::CON_ID, $aRow['TRI_UID'] );
            $oCriteria1->add( \ContentPeer::CON_VALUE, $sTriggerName );
            $oCriteria1->add( \ContentPeer::CON_LANG, SYS_LANG );
            $oDataset1 = \ContentPeer::doSelectRS( $oCriteria1 );
            $oDataset1->setFetchmode( \ResultSet::FETCHMODE_ASSOC );
            $oDataset1->next();
            $aRow1 = $oDataset1->getRow();

            if ($aRow1['TRIGGERS']) {
                return false;
            }
        }
        return true;
    }

    /**
     * Verify if doesn't exists the Trigger in table TRIGGERS
     *
     * @param string $triggerUid            Unique id of Trigger
     * @param string $processUid            Unique id of Process
     * @param string $fieldNameForException Field name for the exception
     *
     * return void Throw exception if doesn't exists the Trigger in table TRIGGERS
     */
    public function throwExceptionIfNotExistsTrigger($triggerUid, $processUid, $fieldNameForException)
    {
        try {
            $criteria = new \Criteria("workflow");

            $criteria->addSelectColumn(\TriggersPeer::TRI_UID);

            if ($processUid != "") {
                $criteria->add(\TriggersPeer::PRO_UID, $processUid, \Criteria::EQUAL);
            }

            $criteria->add(\TriggersPeer::TRI_UID, $triggerUid, \Criteria::EQUAL);

            $rsCriteria = \TriggersPeer::doSelectRS($criteria);

            if (!$rsCriteria->next()) {
                throw new \Exception(\G::LoadTranslation("ID_TRIGGER_DOES_NOT_EXIST", array($fieldNameForException, $triggerUid)));
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Verify if exists the title of a Trigger
     *
     * @param string $processUid            Unique id of Process
     * @param string $triggerTitle          Title
     * @param string $fieldNameForException Field name for the exception
     * @param string $triggerUidExclude     Unique id of Trigger to exclude
     *
     * return void Throw exception if exists the title of a Trigger
     */
    public function throwExceptionIfExistsTitle($processUid, $triggerTitle, $fieldNameForException, $triggerUidExclude = "")
    {
        try {
            if (!$this->verifyNameTrigger($processUid, $triggerTitle, $triggerUidExclude)) {
                throw new \Exception(\G::LoadTranslation("ID_TRIGGER_TITLE_ALREADY_EXISTS", array($fieldNameForException, $triggerTitle)));
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }
}

