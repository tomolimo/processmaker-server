<?php
namespace ProcessMaker\BusinessModel;

use \G;
use \SubProcessPeer;

class Subprocess
{
    /**
     * Get SubProcess in Process
     *
     * return object
     */
    public function getSubprocesss($pro_uid, $tas_uid)
    {
        try {
            $pro_uid = $this->validateProUid($pro_uid);
            $tas_uid = $this->validateTasUid($tas_uid);

            $oCriteria = new \Criteria('workflow');
            $del = \DBAdapter::getStringDelimiter();
            $oCriteria->add(SubProcessPeer::PRO_PARENT, $pro_uid);
            $oCriteria->add(SubProcessPeer::TAS_PARENT, $tas_uid);

            $oCriteria->addAsColumn('CON_VALUE', 'C1.CON_VALUE', 'CON_TITLE');
            $oCriteria->addAlias("C1", 'CONTENT');
            $tasTitleConds = array();
            $tasTitleConds[] = array(SubProcessPeer::TAS_PARENT, 'C1.CON_ID' );
            $tasTitleConds[] = array('C1.CON_CATEGORY', $del . 'TAS_TITLE' . $del );
            $tasTitleConds[] = array('C1.CON_LANG', $del . SYS_LANG . $del );
            $oCriteria->addJoinMC($tasTitleConds, \Criteria::LEFT_JOIN);

            $oDataset = SubProcessPeer::doSelectRS($oCriteria);
            $oDataset->setFetchmode(\ResultSet::FETCHMODE_ASSOC);
            $oDataset->next();
            $aRow = $oDataset->getRow();
            $aRow = array_change_key_case($aRow, CASE_LOWER);

            $response['spr_uid'] = $aRow['sp_uid'];
            $response['spr_pro_parent'] = $aRow['pro_parent'];
            $response['spr_tas_parent'] = $aRow['tas_parent'];
            $response['spr_pro'] = $aRow['pro_uid'];
            $response['spr_tas'] = $aRow['tas_uid'];
            $response['spr_name'] = $aRow['con_value'];
            $response['spr_synchronous'] = $aRow['sp_synchronous'];
            $response['spr_variables_out'] = unserialize($aRow['sp_variables_out']);
            if ((int)$response['spr_synchronous'] === 1) {
                $response['spr_variables_in'] = unserialize($aRow['sp_variables_in']);
            }
            return $response;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Put SubProcess in Process
     * @var string $pro_uid. Uid for Process
     * @var string $spr_uid. Uid for SubProcess
     * @var array $spr_data. Data for SubProcess
     *
     *
     * @author Brayan Pereyra (Cochalo) <brayan@colosa.com>
     * @copyright Colosa - Bolivia
     *
     * @return array
     */
    public function putSubprocesss($pro_uid, $tas_uid, $spr_data)
    {
        $pro_uid = $this->validateProUid($pro_uid);
        $tas_uid = $this->validateTasUid($tas_uid);
        if (empty($spr_data)) {
            throw (new \Exception("The request data is empty."));
        }
        if (isset($spr_data['spr_pro'])) {
            $spr_data['spr_pro'] = $this->validateProUid($spr_data['spr_pro']);
        }
        if (isset($spr_data['spr_tas'])) {
            $spr_data['spr_tas'] = $this->validateTasUid($spr_data['spr_tas']);
        }

        $dataTemp = $this->getSubprocesss($pro_uid, $tas_uid);
        $spr_data = array_merge($dataTemp, $spr_data);
        $spr_data['spr_variables_in'] = (isset($spr_data['spr_variables_in'])) ? $spr_data['spr_variables_in'] : array();

        $oSubProcess = new \SubProcess();
        $aData = array (
            'SP_UID' => $spr_data['spr_uid'],
            'PRO_UID' => $spr_data['spr_pro'],
            'TAS_UID' => $spr_data['spr_tas'],
            'PRO_PARENT' => $pro_uid,
            'TAS_PARENT' => $tas_uid,
            'SP_TYPE' => 'SIMPLE',
            'SP_SYNCHRONOUS' => (int)$spr_data['spr_synchronous'],
            'SP_SYNCHRONOUS_TYPE' => 'ALL',
            'SP_SYNCHRONOUS_WAIT' => 0,
            'SP_VARIABLES_OUT' => serialize( $spr_data['spr_variables_out'] ),
            'SP_VARIABLES_IN' => serialize( $spr_data['spr_variables_in'] ),
            'SP_GRID_IN' => ''
        );
        $oSubProcess->update( $aData );

        $lang = defined( 'SYS_LANG' ) ? SYS_LANG : 'en';
        \Content::addContent( 'TAS_TITLE', '', $tas_uid, $lang, $spr_data['spr_name']);
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
            throw (new \Exception("The project with prj_uid: '', does not exist."));
        }
        $oProcess = new \Process();
        if (!($oProcess->processExists($pro_uid))) {
            throw (new \Exception("The project with prj_uid: '$pro_uid', does not exist."));
        }
        return $pro_uid;
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
            throw (new \Exception("The task with tas_uid: '', does not exist."));
        }
        $oTask = new \Task();
        if (!($oTask->taskExists($tas_uid))) {
            throw (new \Exception("The task with tas_uid: '$tas_uid', does not exist."));
        }
        return $tas_uid;
    }
}

