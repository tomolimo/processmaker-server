<?php
namespace ProcessMaker\BusinessModel;

use \G;
use Luracast\Restler\User;

class ProcessSupervisor
{
    /**
     * Return supervisors
     * @param string $sProcessUID
     *
     * @return array
     *
     * @access public
     */
    public function getProcessSupervisors($sProcessUID = '')
    {
        try {
            $aResp = array();
            // Groups
            $oCriteria = new \Criteria('workflow');
            $oCriteria->addSelectColumn(\ProcessUserPeer::PU_UID);
            $oCriteria->addSelectColumn(\ProcessUserPeer::USR_UID);
            $oCriteria->addAsColumn('GRP_TITLE', \ContentPeer::CON_VALUE);
            $aConditions [] = array(\ProcessUserPeer::USR_UID, \ContentPeer::CON_ID);
            $aConditions [] = array(\ContentPeer::CON_CATEGORY, \DBAdapter::getStringDelimiter().'GRP_TITLE'.\DBAdapter::getStringDelimiter());
            $aConditions [] = array(\ContentPeer::CON_LANG, \DBAdapter::getStringDelimiter().SYS_LANG.\DBAdapter::getStringDelimiter());
            $oCriteria->addJoinMC($aConditions, \Criteria::LEFT_JOIN);
            $oCriteria->add(\ProcessUserPeer::PU_TYPE, 'GROUP_SUPERVISOR');
            $oCriteria->add(\ProcessUserPeer::PRO_UID, $sProcessUID);
            $oCriteria->addAscendingOrderByColumn(\ContentPeer::CON_VALUE);
            $oDataset = \ProcessUserPeer::doSelectRS($oCriteria);
            $oDataset->setFetchmode(\ResultSet::FETCHMODE_ASSOC);
            $oDataset->next();
            while ($aRow = $oDataset->getRow()) {
                $aResp[] = array('pu_uid' => $aRow['PU_UID'],
                                 'pu_type' => "GROUP_SUPERVISOR",
                                 'grp_uid' => $aRow['USR_UID'],
                                 'grp_name' => $aRow['GRP_TITLE']);
                $oDataset->next();
            }

            // Users
            $oCriteria = new \Criteria('workflow');
            $oCriteria->addSelectColumn(\ProcessUserPeer::USR_UID);
            $oCriteria->addSelectColumn(\ProcessUserPeer::PU_UID);
            $oCriteria->addSelectColumn(\UsersPeer::USR_FIRSTNAME);
            $oCriteria->addSelectColumn(\UsersPeer::USR_LASTNAME);
            $oCriteria->addSelectColumn(\UsersPeer::USR_USERNAME);
            $oCriteria->addSelectColumn(\UsersPeer::USR_EMAIL);
            $oCriteria->addJoin(\ProcessUserPeer::USR_UID, \UsersPeer::USR_UID, \Criteria::LEFT_JOIN);
            $oCriteria->add(\ProcessUserPeer::PU_TYPE, 'SUPERVISOR');
            $oCriteria->add(\ProcessUserPeer::PRO_UID, $sProcessUID);
            $oCriteria->add(\UsersPeer::USR_STATUS, 'ACTIVE');
            $oCriteria->addAscendingOrderByColumn(\UsersPeer::USR_FIRSTNAME);
            $oDataset = \ProcessUserPeer::doSelectRS($oCriteria);
            $oDataset->setFetchmode(\ResultSet::FETCHMODE_ASSOC);
            $oDataset->next();
            while ($aRow = $oDataset->getRow()) {
                $aResp[] = array('pu_uid' => $aRow['PU_UID'],
                                 'pu_type' => "SUPERVISOR",
                                 'usr_uid' => $aRow['USR_UID'],
                                 'usr_firstname' => $aRow['USR_FIRSTNAME'],
                                 'usr_lastname' => $aRow['USR_LASTNAME'],
                                 'usr_username' => $aRow['USR_USERNAME'],
                                 'usr_email' => $aRow['USR_EMAIL']);
                $oDataset->next();
            }
            if ($aResp == null) {
                $aResp = array();
            }
            return $aResp;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Return a spefic supervisor
     * @param string $sProcessUID
     * @param string $sPuUID
     *
     * @return object
     *
     * @access public
     */
    public function getProcessSupervisor($sProcessUID = '', $sPuUID = '')
    {
        try {
            $aResp = array();
            $oProcess = \ProcessUserPeer::retrieveByPK( $sPuUID );
            if (is_null($oProcess)) {
                throw new \Exception(\G::LoadTranslation("ID_NOT_VALID_RELATION", array($sPuUID)));
            }
            // Groups
            $oCriteria = new \Criteria('workflow');
            $oCriteria->addSelectColumn(\ProcessUserPeer::PU_UID);
            $oCriteria->addSelectColumn(\ProcessUserPeer::USR_UID);
            $oCriteria->addAsColumn('GRP_TITLE', \ContentPeer::CON_VALUE);
            $aConditions [] = array(\ProcessUserPeer::USR_UID, \ContentPeer::CON_ID);
            $aConditions [] = array(\ContentPeer::CON_CATEGORY, \DBAdapter::getStringDelimiter().'GRP_TITLE'.\DBAdapter::getStringDelimiter());
            $aConditions [] = array(\ContentPeer::CON_LANG, \DBAdapter::getStringDelimiter().SYS_LANG.\DBAdapter::getStringDelimiter());
            $oCriteria->addJoinMC($aConditions, \Criteria::LEFT_JOIN);
            $oCriteria->add(\ProcessUserPeer::PU_TYPE, 'GROUP_SUPERVISOR');
            $oCriteria->add(\ProcessUserPeer::PRO_UID, $sProcessUID);
            $oCriteria->add(\ProcessUserPeer::PU_UID, $sPuUID);
            $oCriteria->addAscendingOrderByColumn(\ContentPeer::CON_VALUE);
            $oDataset = \ProcessUserPeer::doSelectRS($oCriteria);
            $oDataset->setFetchmode(\ResultSet::FETCHMODE_ASSOC);
            $oDataset->next();
            while ($aRow = $oDataset->getRow()) {
                $aResp = array('pu_uid' => $aRow['PU_UID'],
                               'pu_type' => "GROUP_SUPERVISOR",
                               'grp_uid' => $aRow['USR_UID'],
                               'grp_name' => $aRow['GRP_TITLE']);
                $oDataset->next();
            }
            // Users
            $oCriteria = new \Criteria('workflow');
            $oCriteria->addSelectColumn(\ProcessUserPeer::USR_UID);
            $oCriteria->addSelectColumn(\ProcessUserPeer::PU_UID);
            $oCriteria->addSelectColumn(\UsersPeer::USR_FIRSTNAME);
            $oCriteria->addSelectColumn(\UsersPeer::USR_LASTNAME);
            $oCriteria->addSelectColumn(\UsersPeer::USR_USERNAME);
            $oCriteria->addSelectColumn(\UsersPeer::USR_EMAIL);
            $oCriteria->addJoin(\ProcessUserPeer::USR_UID, \UsersPeer::USR_UID, \Criteria::LEFT_JOIN);
            $oCriteria->add(\ProcessUserPeer::PU_TYPE, 'SUPERVISOR');
            $oCriteria->add(\ProcessUserPeer::PRO_UID, $sProcessUID);
            $oCriteria->add(\ProcessUserPeer::PU_UID, $sPuUID);
            $oCriteria->addAscendingOrderByColumn(\UsersPeer::USR_FIRSTNAME);
            $oDataset = \ProcessUserPeer::doSelectRS($oCriteria);
            $oDataset->setFetchmode(\ResultSet::FETCHMODE_ASSOC);
            $oDataset->next();
            while ($aRow = $oDataset->getRow()) {
                $aResp = array('pu_uid' => $aRow['PU_UID'],
                               'pu_type' => "SUPERVISOR",
                               'usr_uid' => $aRow['USR_UID'],
                               'usr_firstname' => $aRow['USR_FIRSTNAME'],
                               'usr_lastname' => $aRow['USR_LASTNAME'],
                               'usr_username' => $aRow['USR_USERNAME'],
                               'usr_email' => $aRow['USR_EMAIL'] );
                $oDataset->next();
            }
            return $aResp;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Return available supervisors
     * @param string $obj_type
     * @param string $sProcessUID
     *
     * @return array
     *
     * @access public
     */
    public function getAvailableProcessSupervisors($obj_type, $sProcessUID = '')
    {
        try {
            require_once (PATH_RBAC_HOME . "engine" . PATH_SEP . "classes" . PATH_SEP . "model" . PATH_SEP . "Roles.php");
            $aRespLi = array();
            $aRespLiGroups = array();
            $userRole = new \ProcessMaker\BusinessModel\User();
            // Groups
            $oCriteria = new \Criteria('workflow');
            $oCriteria->addSelectColumn(\ProcessUserPeer::USR_UID);
            $oCriteria->addSelectColumn(\ProcessUserPeer::PU_TYPE);
            $oCriteria->add(\ProcessUserPeer::PRO_UID, $sProcessUID);
            $oCriteria->add(\ProcessUserPeer::PU_TYPE, '%SUPERVISOR%', \Criteria::LIKE);
            $oDataset = \ProcessUserPeer::doSelectRS($oCriteria);
            $oDataset->setFetchmode(\ResultSet::FETCHMODE_ASSOC);
            $oDataset->next();
            $aUIDS = array();
            $aGRUS = array();
            while ($aRow = $oDataset->getRow()) {
                if ($aRow['PU_TYPE'] == 'SUPERVISOR') {
                    $aUIDS [] = $aRow ['USR_UID'];
                } else {
                    $aGRUS [] = $aRow ['USR_UID'];
                }
                $oDataset->next();
            }
            $oCriteria = new \Criteria('workflow');
            $oCriteria->addSelectColumn(\GroupwfPeer::GRP_UID);
            $oCriteria->addAsColumn('GRP_TITLE', \ContentPeer::CON_VALUE);
            $aConditions [] = array(\GroupwfPeer::GRP_UID, \ContentPeer::CON_ID);
            $aConditions [] = array(\ContentPeer::CON_CATEGORY, \DBAdapter::getStringDelimiter() . 'GRP_TITLE' . \DBAdapter::getStringDelimiter());
            $aConditions [] = array(\ContentPeer::CON_LANG, \DBAdapter::getStringDelimiter() . SYS_LANG . \DBAdapter::getStringDelimiter());
            $oCriteria->addJoinMC($aConditions, \Criteria::LEFT_JOIN);
            $oCriteria->add(\GroupwfPeer::GRP_UID, $aGRUS, \Criteria::NOT_IN);
            $oCriteria->addAscendingOrderByColumn(\ContentPeer::CON_VALUE);
            $oDataset = \GroupwfPeer::doSelectRS($oCriteria);
            $oDataset->setFetchmode(\ResultSet::FETCHMODE_ASSOC);
            $oDataset->next();
            if ($obj_type == 'group' || $obj_type == '') {
                while ($aRow = $oDataset->getRow()) {
                    $group = new \ProcessMaker\BusinessModel\Group();
                    $userGroup = $group->getUsers('USERS', $aRow['GRP_UID']);
                    foreach ($userGroup as $value) {
                        $permission = $userRole->loadUserRolePermission('PROCESSMAKER', $value["USR_UID"]);
                        foreach ($permission as $values) {
                            if ($values["PER_CODE"] == 'PM_SUPERVISOR') {
                                $aRespLiGroups[] = array('grp_uid' => $aRow['GRP_UID'],
                                                   'grp_name' => $aRow['GRP_TITLE'],
                                                   'obj_type' => "group");
                            }
                        }
                    }
                    $oDataset->next();
                }
            }
            $exclude = array("");
            for ($i = 0; $i<=count($aRespLiGroups)-1; $i++) {
                if (!in_array(trim($aRespLiGroups[$i]["grp_uid"]) ,$exclude)) {
                    $aRespLi[] = $aRespLiGroups[$i];
                    $exclude[] = trim($aRespLiGroups[$i]["grp_uid"]);
                }
            }
            $sDelimiter = \DBAdapter::getStringDelimiter();
            $oCriteria = new \Criteria('workflow');
            $oCriteria->addSelectColumn(\UsersPeer::USR_UID);
            $oCriteria->addSelectColumn(\UsersPeer::USR_ROLE);
            $oCriteria->add(\UsersPeer::USR_UID, $aUIDS, \Criteria::NOT_IN);
            $oDataset = \UsersPeer::doSelectRS($oCriteria);
            $oDataset->setFetchmode(\ResultSet::FETCHMODE_ASSOC);
            $oDataset->next();
            $aUIDS = array();
            while ($aRow = $oDataset->getRow()) {
                $permission = $userRole->loadUserRolePermission('PROCESSMAKER', $aRow['USR_UID']);
                foreach ($permission as $key => $value) {
                    if ($value["PER_CODE"] == 'PM_SUPERVISOR') {
                        $aUIDS [] = $aRow ['USR_UID'];
                    }
                }
                $oDataset->next();
            }
            $oCriteria = new \Criteria('workflow');
            $oCriteria->addSelectColumn(\UsersPeer::USR_UID);
            $oCriteria->addSelectColumn(\UsersPeer::USR_FIRSTNAME);
            $oCriteria->addSelectColumn(\UsersPeer::USR_LASTNAME);
            $oCriteria->addSelectColumn(\UsersPeer::USR_USERNAME);
            $oCriteria->addSelectColumn(\UsersPeer::USR_EMAIL);
            $oCriteria->add(\UsersPeer::USR_UID, $aUIDS, \Criteria::IN);
            $oCriteria->addAscendingOrderByColumn(\UsersPeer::USR_FIRSTNAME);
            $oCriteria->add(\UsersPeer::USR_STATUS, 'ACTIVE');
            $oDataset = \UsersPeer::doSelectRS($oCriteria);
            $oDataset->setFetchmode(\ResultSet::FETCHMODE_ASSOC);
            $oDataset->next();
            if ($obj_type == 'user' || $obj_type == '') {
                while ($aRow = $oDataset->getRow()) {
                    $aRespLi[] = array('usr_uid' => $aRow['USR_UID'],
                                       'usr_firstname' => $aRow['USR_FIRSTNAME'],
                                       'usr_lastname' => $aRow['USR_LASTNAME'],
                                       'usr_username' => $aRow['USR_USERNAME'],
                                       'usr_email' => $aRow['USR_EMAIL'],
                                       "obj_type" => "user" );
                    $oDataset->next();
                }
            }
            return $aRespLi;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Return dynaforms supervisor
     * @param string $sProcessUID
     *
     * @return array
     *
     * @access public
     */
    public function getProcessSupervisorDynaforms($sProcessUID = '')
    {
        try {
            $aResp = array();
            $sDelimiter = \DBAdapter::getStringDelimiter();
            $oCriteria = new \Criteria('workflow');
            $oCriteria->addSelectColumn(\StepSupervisorPeer::STEP_UID);
            $oCriteria->addSelectColumn(\StepSupervisorPeer::PRO_UID);
            $oCriteria->addSelectColumn(\StepSupervisorPeer::STEP_TYPE_OBJ);
            $oCriteria->addSelectColumn(\StepSupervisorPeer::STEP_UID_OBJ);
            $oCriteria->addSelectColumn(\StepSupervisorPeer::STEP_POSITION);
            $oCriteria->addAsColumn('DYN_TITLE', 'C.CON_VALUE');
            $oCriteria->addAlias('C', 'CONTENT');
            $aConditions = array();
            $aConditions[] = array(\StepSupervisorPeer::STEP_UID_OBJ, \DynaformPeer::DYN_UID );
            $aConditions[] = array(\StepSupervisorPeer::STEP_TYPE_OBJ, $sDelimiter . 'DYNAFORM' . $sDelimiter );
            $oCriteria->addJoinMC($aConditions, \Criteria::LEFT_JOIN);
            $aConditions = array();
            $aConditions[] = array(\DynaformPeer::DYN_UID, 'C.CON_ID' );
            $aConditions[] = array('C.CON_CATEGORY', $sDelimiter . 'DYN_TITLE' . $sDelimiter );
            $aConditions[] = array('C.CON_LANG', $sDelimiter . SYS_LANG . $sDelimiter );
            $oCriteria->addJoinMC($aConditions, \Criteria::LEFT_JOIN);
            $oCriteria->add(\StepSupervisorPeer::PRO_UID, $sProcessUID);
            $oCriteria->add(\StepSupervisorPeer::STEP_TYPE_OBJ, 'DYNAFORM');
            $oCriteria->addAscendingOrderByColumn(\StepSupervisorPeer::STEP_POSITION);
            $oDataset = \StepSupervisorPeer::doSelectRS($oCriteria);
            $oDataset->setFetchmode(\ResultSet::FETCHMODE_ASSOC);
            $oDataset->next();
            while ($aRow = $oDataset->getRow()) {
                $aResp[] = array('pud_uid' => $aRow['STEP_UID'],
                                 'pud_position' => $aRow['STEP_POSITION'],
                                 'dyn_uid' => $aRow['STEP_UID_OBJ'],
                                 'dyn_title' => $aRow['DYN_TITLE'],
                                 'obj_type' => "DYNAFORM");
                $oDataset->next();
            }
            return $aResp;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Return a specific dynaform supervisor
     * @param string $sProcessUID
     * @param string $sPudUID
     *
     * @return array
     *
     * @access public
     */
    public function getProcessSupervisorDynaform($sProcessUID = '', $sPudUID = '')
    {
        try {
            $aResp = array();
            $oDynaformSupervisor = \StepSupervisorPeer::retrieveByPK( $sPudUID );
            if (is_null( $oDynaformSupervisor ) ) {
                throw new \Exception(\G::LoadTranslation("ID_NOT_REGISTERED_PROCESS_SUPERVISOR", array($sPudUID)));
            }
            $sDelimiter = \DBAdapter::getStringDelimiter();
            $oCriteria = new \Criteria('workflow');
            $oCriteria->addSelectColumn(\StepSupervisorPeer::STEP_UID);
            $oCriteria->addSelectColumn(\StepSupervisorPeer::PRO_UID);
            $oCriteria->addSelectColumn(\StepSupervisorPeer::STEP_TYPE_OBJ);
            $oCriteria->addSelectColumn(\StepSupervisorPeer::STEP_UID_OBJ);
            $oCriteria->addSelectColumn(\StepSupervisorPeer::STEP_POSITION);
            $oCriteria->addAsColumn('DYN_TITLE', 'C.CON_VALUE');
            $oCriteria->addAlias('C', 'CONTENT');
            $aConditions = array();
            $aConditions[] = array(\StepSupervisorPeer::STEP_UID_OBJ, \DynaformPeer::DYN_UID );
            $aConditions[] = array(\StepSupervisorPeer::STEP_TYPE_OBJ, $sDelimiter . 'DYNAFORM' . $sDelimiter );
            $oCriteria->addJoinMC($aConditions, \Criteria::LEFT_JOIN);
            $aConditions = array();
            $aConditions[] = array(\DynaformPeer::DYN_UID, 'C.CON_ID' );
            $aConditions[] = array('C.CON_CATEGORY', $sDelimiter . 'DYN_TITLE' . $sDelimiter );
            $aConditions[] = array('C.CON_LANG', $sDelimiter . SYS_LANG . $sDelimiter );
            $oCriteria->addJoinMC($aConditions, \Criteria::LEFT_JOIN);
            $oCriteria->add(\StepSupervisorPeer::PRO_UID, $sProcessUID);
            $oCriteria->add(\StepSupervisorPeer::STEP_UID, $sPudUID);
            $oCriteria->add(\StepSupervisorPeer::STEP_TYPE_OBJ, 'DYNAFORM');
            $oCriteria->addAscendingOrderByColumn(\StepSupervisorPeer::STEP_POSITION);
            $oDataset = \StepSupervisorPeer::doSelectRS($oCriteria);
            $oDataset->setFetchmode(\ResultSet::FETCHMODE_ASSOC);
            $oDataset->next();
            while ($aRow = $oDataset->getRow()) {
                $aResp = array('pud_uid' => $aRow['STEP_UID'],
                                 'pud_position' => $aRow['STEP_POSITION'],
                                 'dyn_uid' => $aRow['STEP_UID_OBJ'],
                                 'dyn_title' => $aRow['DYN_TITLE']);
                $oDataset->next();
            }
            return $aResp;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Return available dynaform supervisor
     * @param string $sProcessUID
     *
     * @return array
     *
     * @access public
     */
    public function getAvailableProcessSupervisorDynaformInputDocument($sProcessUID = '')
    {
        $arrayProcessSupervisorsObject = array();

        try {
            $aResp = array();
            $oCriteria = $this->getProcessSupervisorDynaforms($sProcessUID);
            $aUIDS = array();
            foreach ($oCriteria as $oCriteria => $value) {
                $aUIDS[] = $value["dyn_uid"];
            }
            $sDelimiter = \DBAdapter::getStringDelimiter();
            $oCriteria = new \Criteria('workflow');
            $oCriteria->addSelectColumn(\DynaformPeer::DYN_UID);
            $oCriteria->addSelectColumn(\DynaformPeer::PRO_UID);
            $oCriteria->addAsColumn('DYN_TITLE', 'C.CON_VALUE');
            $oCriteria->addAlias('C', 'CONTENT');
            $aConditions = array();
            $aConditions[] = array(\DynaformPeer::DYN_UID, 'C.CON_ID');
            $aConditions[] = array('C.CON_CATEGORY', $sDelimiter . 'DYN_TITLE' . $sDelimiter);
            $aConditions[] = array('C.CON_LANG', $sDelimiter . SYS_LANG . $sDelimiter);
            $oCriteria->addJoinMC($aConditions, \Criteria::LEFT_JOIN);
            $oCriteria->add(\DynaformPeer::PRO_UID, $sProcessUID);
            $oCriteria->add(\DynaformPeer::DYN_TYPE, 'xmlform');
            $oCriteria->add(\DynaformPeer::DYN_UID, $aUIDS, \Criteria::NOT_IN);
            $oDataset = \StepSupervisorPeer::doSelectRS($oCriteria);
            $oDataset->setFetchmode(\ResultSet::FETCHMODE_ASSOC);
            $oDataset->next();
            $oDataset = \StepSupervisorPeer::doSelectRS($oCriteria);
            $oDataset->setFetchmode(\ResultSet::FETCHMODE_ASSOC);
            $oDataset->next();
            while ($aRow = $oDataset->getRow()) {
                $arrayProcessSupervisorsObject[] = array('dyn_uid' => $aRow['DYN_UID'],
                                 'dyn_title' => $aRow['DYN_TITLE'],
                                 'obj_uid' => $aRow['DYN_UID'],
                                 'obj_type' => "DYNAFORM");
                $oDataset->next();
            }
        } catch (Exception $e) {
            throw $e;
        }

        try {
            $oCriteria = $this->getProcessSupervisorInputDocuments($sProcessUID);
            $aUIDS = array();
            foreach ($oCriteria as $oCriteria => $value) {
                $aUIDS[] = $value["input_doc_uid"];
            }
            $sDelimiter = \DBAdapter::getStringDelimiter();
            $oCriteria = new \Criteria('workflow');
            $oCriteria->addSelectColumn(\InputDocumentPeer::INP_DOC_UID);
            $oCriteria->addSelectColumn(\InputDocumentPeer::PRO_UID);
            $oCriteria->addAsColumn('INP_DOC_TITLE', 'C.CON_VALUE');
            $oCriteria->addAlias('C', 'CONTENT');
            $aConditions = array();
            $aConditions[] = array(\InputDocumentPeer::INP_DOC_UID, 'C.CON_ID');
            $aConditions[] = array('C.CON_CATEGORY', $sDelimiter . 'INP_DOC_TITLE' . $sDelimiter);
            $aConditions[] = array('C.CON_LANG', $sDelimiter . SYS_LANG . $sDelimiter);
            $oCriteria->addJoinMC($aConditions, \Criteria::LEFT_JOIN);
            $oCriteria->add(\InputDocumentPeer::PRO_UID, $sProcessUID);
            $oCriteria->add(\InputDocumentPeer::INP_DOC_UID, $aUIDS, \Criteria::NOT_IN);
            $oDataset = \StepSupervisorPeer::doSelectRS($oCriteria);
            $oDataset->setFetchmode(\ResultSet::FETCHMODE_ASSOC);
            $oDataset->next();
            $oDataset = \StepSupervisorPeer::doSelectRS($oCriteria);
            $oDataset->setFetchmode(\ResultSet::FETCHMODE_ASSOC);
            $oDataset->next();
            while ($aRow = $oDataset->getRow()) {
                $arrayProcessSupervisorsObject[] = array('inp_doc_uid' => $aRow['INP_DOC_UID'],
                                 'inp_doc_title' => $aRow['INP_DOC_TITLE'],
                                 'obj_uid' => $aRow['INP_DOC_UID'],
                                 'obj_type'=>"INPUT-DOCUMENT");
                $oDataset->next();
            }
            return $arrayProcessSupervisorsObject;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getProcessSupervisorDynaformsInputsDocuments($sProcessUID = '')
    {
        $arrayProcessSupervisorsAssignedObject = array();

        try {
            $sDelimiter = \DBAdapter::getStringDelimiter();
            $oCriteria = new \Criteria('workflow');
            $oCriteria->addSelectColumn(\StepSupervisorPeer::STEP_UID);
            $oCriteria->addSelectColumn(\StepSupervisorPeer::PRO_UID);
            $oCriteria->addSelectColumn(\StepSupervisorPeer::STEP_TYPE_OBJ);
            $oCriteria->addSelectColumn(\StepSupervisorPeer::STEP_UID_OBJ);
            $oCriteria->addSelectColumn(\StepSupervisorPeer::STEP_POSITION);
            $oCriteria->addAsColumn('DYN_TITLE', 'C.CON_VALUE');
            $oCriteria->addAlias('C', 'CONTENT');
            $aConditions = array();
            $aConditions[] = array(\StepSupervisorPeer::STEP_UID_OBJ, \DynaformPeer::DYN_UID );
            $aConditions[] = array(\StepSupervisorPeer::STEP_TYPE_OBJ, $sDelimiter . 'DYNAFORM' . $sDelimiter );
            $oCriteria->addJoinMC($aConditions, \Criteria::LEFT_JOIN);
            $aConditions = array();
            $aConditions[] = array(\DynaformPeer::DYN_UID, 'C.CON_ID' );
            $aConditions[] = array('C.CON_CATEGORY', $sDelimiter . 'DYN_TITLE' . $sDelimiter );
            $aConditions[] = array('C.CON_LANG', $sDelimiter . SYS_LANG . $sDelimiter );
            $oCriteria->addJoinMC($aConditions, \Criteria::LEFT_JOIN);
            $oCriteria->add(\StepSupervisorPeer::PRO_UID, $sProcessUID);
            $oCriteria->add(\StepSupervisorPeer::STEP_TYPE_OBJ, 'DYNAFORM');
            $oCriteria->addAscendingOrderByColumn(\StepSupervisorPeer::STEP_POSITION);
            $oDataset = \StepSupervisorPeer::doSelectRS($oCriteria);
            $oDataset->setFetchmode(\ResultSet::FETCHMODE_ASSOC);
            $oDataset->next();
            while ($aRow = $oDataset->getRow()) {
                $arrayProcessSupervisorsAssignedObject[] = array('pud_uid' => $aRow['STEP_UID'],
                                 'pud_position' => $aRow['STEP_POSITION'],
                                 'dyn_uid' => $aRow['STEP_UID_OBJ'],
                                 'dyn_title' => $aRow['DYN_TITLE'],
                                 'obj_title' => "",
                                 'obj_uid' => $aRow['STEP_UID_OBJ'],
                                 'obj_type' => "DYNAFORM");
                $oDataset->next();
            }
        } catch (Exception $e) {
            throw $e;
        }

        try {
            $sDelimiter = \DBAdapter::getStringDelimiter();
            $oCriteria = new \Criteria('workflow');
            $oCriteria->addSelectColumn(\StepSupervisorPeer::STEP_UID);
            $oCriteria->addSelectColumn(\StepSupervisorPeer::PRO_UID);
            $oCriteria->addSelectColumn(\StepSupervisorPeer::STEP_TYPE_OBJ);
            $oCriteria->addSelectColumn(\StepSupervisorPeer::STEP_UID_OBJ);
            $oCriteria->addSelectColumn(\StepSupervisorPeer::STEP_POSITION);
            $oCriteria->addAsColumn('INP_DOC_TITLE', 'C.CON_VALUE');
            $oCriteria->addAlias('C', 'CONTENT');
            $aConditions = array();
            $aConditions[] = array(\StepSupervisorPeer::STEP_UID_OBJ, \InputDocumentPeer::INP_DOC_UID);
            $aConditions[] = array(\StepSupervisorPeer::STEP_TYPE_OBJ, $sDelimiter . 'INPUT_DOCUMENT' . $sDelimiter);
            $oCriteria->addJoinMC($aConditions, \Criteria::LEFT_JOIN);
            $aConditions = array();
            $aConditions[] = array(\InputDocumentPeer::INP_DOC_UID, 'C.CON_ID');
            $aConditions[] = array('C.CON_CATEGORY', $sDelimiter . 'INP_DOC_TITLE' . $sDelimiter);
            $aConditions[] = array('C.CON_LANG', $sDelimiter . SYS_LANG . $sDelimiter);
            $oCriteria->addJoinMC($aConditions, \Criteria::LEFT_JOIN);
            $oCriteria->add(\StepSupervisorPeer::PRO_UID, $sProcessUID);
            $oCriteria->add(\StepSupervisorPeer::STEP_TYPE_OBJ, 'INPUT_DOCUMENT');
            $oCriteria->addAscendingOrderByColumn(\StepSupervisorPeer::STEP_POSITION);
            $oDataset = \StepSupervisorPeer::doSelectRS($oCriteria);
            $oDataset->setFetchmode(\ResultSet::FETCHMODE_ASSOC);
            $oDataset->next();
            while ($aRow = $oDataset->getRow()) {
                $arrayProcessSupervisorsAssignedObject[] = array('pui_uid' => $aRow['STEP_UID'],
                                 'pui_position' => $aRow['STEP_POSITION'],
                                 'input_doc_uid' => $aRow['STEP_UID_OBJ'],
                                 'input_doc_title' => $aRow['INP_DOC_TITLE'],
                                 'obj_title' => "",
                                 'obj_uid' => $aRow['STEP_UID_OBJ'],
                                 'obj_type' => "INPUT-DOCUMENT");
                $oDataset->next();
            }
            return $arrayProcessSupervisorsAssignedObject;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Return available dynaform supervisor
     * @param string $sProcessUID
     *
     * @return array
     *
     * @access public
     */
    public function getAvailableProcessSupervisorDynaform($sProcessUID = '')
    {
        try {
            $aResp = array();
            $oCriteria = $this->getProcessSupervisorDynaforms($sProcessUID);
            $aUIDS = array();
            foreach ($oCriteria as $oCriteria => $value) {
                $aUIDS[] = $value["dyn_uid"];
            }
            $sDelimiter = \DBAdapter::getStringDelimiter();
            $oCriteria = new \Criteria('workflow');
            $oCriteria->addSelectColumn(\DynaformPeer::DYN_UID);
            $oCriteria->addSelectColumn(\DynaformPeer::PRO_UID);
            $oCriteria->addAsColumn('DYN_TITLE', 'C.CON_VALUE');
            $oCriteria->addAlias('C', 'CONTENT');
            $aConditions = array();
            $aConditions[] = array(\DynaformPeer::DYN_UID, 'C.CON_ID');
            $aConditions[] = array('C.CON_CATEGORY', $sDelimiter . 'DYN_TITLE' . $sDelimiter);
            $aConditions[] = array('C.CON_LANG', $sDelimiter . SYS_LANG . $sDelimiter);
            $oCriteria->addJoinMC($aConditions, \Criteria::LEFT_JOIN);
            $oCriteria->add(\DynaformPeer::PRO_UID, $sProcessUID);
            $oCriteria->add(\DynaformPeer::DYN_TYPE, 'xmlform');
            $oCriteria->add(\DynaformPeer::DYN_UID, $aUIDS, \Criteria::NOT_IN);
            $oDataset = \StepSupervisorPeer::doSelectRS($oCriteria);
            $oDataset->setFetchmode(\ResultSet::FETCHMODE_ASSOC);
            $oDataset->next();
            $oDataset = \StepSupervisorPeer::doSelectRS($oCriteria);
            $oDataset->setFetchmode(\ResultSet::FETCHMODE_ASSOC);
            $oDataset->next();
            while ($aRow = $oDataset->getRow()) {
                $aResp[] = array('dyn_uid' => $aRow['DYN_UID'],
                                 'dyn_title' => $aRow['DYN_TITLE'],
                                 'obj_type' => "DYNAFORM");
                $oDataset->next();
            }
            return $aResp;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Return input documents supervisor
     * @param string $sProcessUID
     *
     * @return array
     *
     * @access public
     */
    public function getProcessSupervisorInputDocuments($sProcessUID = '')
    {
        try {
            $aResp = array();
            $sDelimiter = \DBAdapter::getStringDelimiter();
            $oCriteria = new \Criteria('workflow');
            $oCriteria->addSelectColumn(\StepSupervisorPeer::STEP_UID);
            $oCriteria->addSelectColumn(\StepSupervisorPeer::PRO_UID);
            $oCriteria->addSelectColumn(\StepSupervisorPeer::STEP_TYPE_OBJ);
            $oCriteria->addSelectColumn(\StepSupervisorPeer::STEP_UID_OBJ);
            $oCriteria->addSelectColumn(\StepSupervisorPeer::STEP_POSITION);
            $oCriteria->addAsColumn('INP_DOC_TITLE', 'C.CON_VALUE');
            $oCriteria->addAlias('C', 'CONTENT');
            $aConditions = array();
            $aConditions[] = array(\StepSupervisorPeer::STEP_UID_OBJ, \InputDocumentPeer::INP_DOC_UID);
            $aConditions[] = array(\StepSupervisorPeer::STEP_TYPE_OBJ, $sDelimiter . 'INPUT_DOCUMENT' . $sDelimiter);
            $oCriteria->addJoinMC($aConditions, \Criteria::LEFT_JOIN);
            $aConditions = array();
            $aConditions[] = array(\InputDocumentPeer::INP_DOC_UID, 'C.CON_ID');
            $aConditions[] = array('C.CON_CATEGORY', $sDelimiter . 'INP_DOC_TITLE' . $sDelimiter);
            $aConditions[] = array('C.CON_LANG', $sDelimiter . SYS_LANG . $sDelimiter);
            $oCriteria->addJoinMC($aConditions, \Criteria::LEFT_JOIN);
            $oCriteria->add(\StepSupervisorPeer::PRO_UID, $sProcessUID);
            $oCriteria->add(\StepSupervisorPeer::STEP_TYPE_OBJ, 'INPUT_DOCUMENT');
            $oCriteria->addAscendingOrderByColumn(\StepSupervisorPeer::STEP_POSITION);
            $oDataset = \StepSupervisorPeer::doSelectRS($oCriteria);
            $oDataset->setFetchmode(\ResultSet::FETCHMODE_ASSOC);
            $oDataset->next();
            while ($aRow = $oDataset->getRow()) {
                $aResp[] = array('pui_uid' => $aRow['STEP_UID'],
                                 'pui_position' => $aRow['STEP_POSITION'],
                                 'input_doc_uid' => $aRow['STEP_UID_OBJ'],
                                 'input_doc_title' => $aRow['INP_DOC_TITLE'],
                                 'obj_type' => "INPUT_DOCUMENT");
                $oDataset->next();
            }
            return $aResp;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Return a specific input document supervisor
     * @param string $sProcessUID
     * @param string $sPuiUID
     *
     * @return array
     *
     * @access public
     */
    public function getProcessSupervisorInputDocument($sProcessUID = '', $sPuiUID = '')
    {
        try {
            $aResp = array();
            $oInputDocumentSupervisor = \StepSupervisorPeer::retrieveByPK( $sPuiUID );
            if (is_null( $oInputDocumentSupervisor ) ) {
                throw new \Exception(\G::LoadTranslation("ID_NOT_REGISTERED_PROCESS_SUPERVISOR", array($sPuiUID)));
            }
            $sDelimiter = \DBAdapter::getStringDelimiter();
            $oCriteria = new \Criteria('workflow');
            $oCriteria->addSelectColumn(\StepSupervisorPeer::STEP_UID);
            $oCriteria->addSelectColumn(\StepSupervisorPeer::PRO_UID);
            $oCriteria->addSelectColumn(\StepSupervisorPeer::STEP_TYPE_OBJ);
            $oCriteria->addSelectColumn(\StepSupervisorPeer::STEP_UID_OBJ);
            $oCriteria->addSelectColumn(\StepSupervisorPeer::STEP_POSITION);
            $oCriteria->addAsColumn('INP_DOC_TITLE', 'C.CON_VALUE');
            $oCriteria->addAlias('C', 'CONTENT');
            $aConditions = array();
            $aConditions[] = array(\StepSupervisorPeer::STEP_UID_OBJ, \InputDocumentPeer::INP_DOC_UID);
            $aConditions[] = array(\StepSupervisorPeer::STEP_TYPE_OBJ, $sDelimiter . 'INPUT_DOCUMENT' . $sDelimiter);
            $oCriteria->addJoinMC($aConditions, \Criteria::LEFT_JOIN);
            $aConditions = array();
            $aConditions[] = array(\InputDocumentPeer::INP_DOC_UID, 'C.CON_ID');
            $aConditions[] = array('C.CON_CATEGORY', $sDelimiter . 'INP_DOC_TITLE' . $sDelimiter);
            $aConditions[] = array('C.CON_LANG', $sDelimiter . SYS_LANG . $sDelimiter);
            $oCriteria->addJoinMC($aConditions, \Criteria::LEFT_JOIN);
            $oCriteria->add(\StepSupervisorPeer::PRO_UID, $sProcessUID);
            $oCriteria->add(\StepSupervisorPeer::STEP_UID, $sPuiUID);
            $oCriteria->add(\StepSupervisorPeer::STEP_TYPE_OBJ, 'INPUT_DOCUMENT');
            $oCriteria->addAscendingOrderByColumn(\StepSupervisorPeer::STEP_POSITION);
            $oDataset = \StepSupervisorPeer::doSelectRS($oCriteria);
            $oDataset->setFetchmode(\ResultSet::FETCHMODE_ASSOC);
            $oDataset->next();
            while ($aRow = $oDataset->getRow()) {
                $aResp = array('pui_uid' => $aRow['STEP_UID'],
                                 'pui_position' => $aRow['STEP_POSITION'],
                                 'input_doc_uid' => $aRow['STEP_UID_OBJ'],
                                 'input_doc_title' => $aRow['INP_DOC_TITLE']);
                $oDataset->next();
            }
            return $aResp;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Return available inputdocuments supervisor
     * @param string $sProcessUID
     *
     * @return array
     *
     * @access public
     */
    public function getAvailableProcessSupervisorInputDocument($sProcessUID = '')
    {
        try {
            $aResp = array();
            $oCriteria = $this->getProcessSupervisorInputDocuments($sProcessUID);
            $aUIDS = array();
            foreach ($oCriteria as $oCriteria => $value) {
                $aUIDS[] = $value["input_doc_uid"];
            }
            $sDelimiter = \DBAdapter::getStringDelimiter();
            $oCriteria = new \Criteria('workflow');
            $oCriteria->addSelectColumn(\InputDocumentPeer::INP_DOC_UID);
            $oCriteria->addSelectColumn(\InputDocumentPeer::PRO_UID);
            $oCriteria->addAsColumn('INP_DOC_TITLE', 'C.CON_VALUE');
            $oCriteria->addAlias('C', 'CONTENT');
            $aConditions = array();
            $aConditions[] = array(\InputDocumentPeer::INP_DOC_UID, 'C.CON_ID');
            $aConditions[] = array('C.CON_CATEGORY', $sDelimiter . 'INP_DOC_TITLE' . $sDelimiter);
            $aConditions[] = array('C.CON_LANG', $sDelimiter . SYS_LANG . $sDelimiter);
            $oCriteria->addJoinMC($aConditions, \Criteria::LEFT_JOIN);
            $oCriteria->add(\InputDocumentPeer::PRO_UID, $sProcessUID);
            $oCriteria->add(\InputDocumentPeer::INP_DOC_UID, $aUIDS, \Criteria::NOT_IN);
            $oDataset = \StepSupervisorPeer::doSelectRS($oCriteria);
            $oDataset->setFetchmode(\ResultSet::FETCHMODE_ASSOC);
            $oDataset->next();
            $oDataset = \StepSupervisorPeer::doSelectRS($oCriteria);
            $oDataset->setFetchmode(\ResultSet::FETCHMODE_ASSOC);
            $oDataset->next();
            while ($aRow = $oDataset->getRow()) {
                $aResp[] = array('inp_doc_uid' => $aRow['INP_DOC_UID'],
                                 'inp_doc_title' => $aRow['INP_DOC_TITLE'],
                                 'obj_type'=>"INPUT_DOCUMENT");
                $oDataset->next();
            }
            return $aResp;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Assign a supervisor of a process
     *
     * @param string $sProcessUID
     * @param string $sUsrUID
     * @param string $sTypeUID
     * @access public
     */
    public function addProcessSupervisor($sProcessUID, $sUsrUID, $sTypeUID)
    {
        $sPuUIDT = array();
        $oProcessUser = new \ProcessUser ( );
        $oTypeAssigneeG = \GroupwfPeer::retrieveByPK( $sUsrUID );
        $oTypeAssigneeU = \UsersPeer::retrieveByPK( $sUsrUID );
        if (is_null( $oTypeAssigneeG ) && is_null( $oTypeAssigneeU ) ) {
            throw new \Exception(\G::LoadTranslation("ID_USER_DOES_NOT_CORRESPOND_TYPE", array($sUsrUID, $sTypeUID)));
        }
        if (is_null( $oTypeAssigneeG ) && ! is_null( $oTypeAssigneeU) ) {
            if ( "SUPERVISOR"!= $sTypeUID ) {
                throw new \Exception(\G::LoadTranslation("ID_USER_DOES_NOT_CORRESPOND_TYPE", array($sUsrUID, $sTypeUID)));
            }
        }
        if (! is_null( $oTypeAssigneeG ) && is_null( $oTypeAssigneeU ) ) {
            if ( "GROUP_SUPERVISOR" != $sTypeUID ) {
                throw new \Exception(\G::LoadTranslation("ID_USER_DOES_NOT_CORRESPOND_TYPE", array($sUsrUID, $sTypeUID)));
            }
        }
        // validate Groups
        $oCriteria = new \Criteria('workflow');
        $oCriteria->addSelectColumn(\ProcessUserPeer::PU_UID);
        $oCriteria->addSelectColumn(\ProcessUserPeer::USR_UID);
        $oCriteria->addAsColumn('GRP_TITLE', \ContentPeer::CON_VALUE);
        $aConditions [] = array(\ProcessUserPeer::USR_UID, \ContentPeer::CON_ID);
        $aConditions [] = array(\ContentPeer::CON_CATEGORY, \DBAdapter::getStringDelimiter().'GRP_TITLE'.\DBAdapter::getStringDelimiter());
        $aConditions [] = array(\ContentPeer::CON_LANG, \DBAdapter::getStringDelimiter().SYS_LANG.\DBAdapter::getStringDelimiter());
        $oCriteria->addJoinMC($aConditions, \Criteria::LEFT_JOIN);
        $oCriteria->add(\ProcessUserPeer::PU_TYPE, 'GROUP_SUPERVISOR');
        $oCriteria->add(\ProcessUserPeer::PRO_UID, $sProcessUID);
        $oCriteria->add(\ProcessUserPeer::USR_UID, $sUsrUID);
        $oCriteria->addAscendingOrderByColumn(\ContentPeer::CON_VALUE);
        $oDataset = \ProcessUserPeer::doSelectRS($oCriteria);
        $oDataset->setFetchmode(\ResultSet::FETCHMODE_ASSOC);
        $oDataset->next();
        while ($aRow = $oDataset->getRow()) {
            $sPuUIDT = $aRow['PU_UID'];
            $oDataset->next();
        }
        // validate Users
        $oCriteria = new \Criteria('workflow');
        $oCriteria->addSelectColumn(\ProcessUserPeer::USR_UID);
        $oCriteria->addSelectColumn(\ProcessUserPeer::PU_UID);
        $oCriteria->addJoin(\ProcessUserPeer::USR_UID, \UsersPeer::USR_UID, \Criteria::LEFT_JOIN);
        $oCriteria->add(\ProcessUserPeer::PU_TYPE, 'SUPERVISOR');
        $oCriteria->add(\ProcessUserPeer::PRO_UID, $sProcessUID);
        $oCriteria->add(\ProcessUserPeer::USR_UID, $sUsrUID);
        $oCriteria->addAscendingOrderByColumn(\UsersPeer::USR_FIRSTNAME);
        $oDataset = \ProcessUserPeer::doSelectRS($oCriteria);
        $oDataset->setFetchmode(\ResultSet::FETCHMODE_ASSOC);
        $oDataset->next();
        while ($aRow = $oDataset->getRow()) {
            $sPuUIDT = $aRow['PU_UID'];
            $oDataset->next();
        }
        if (sizeof($sPuUIDT) == 0) {
            $sPuUID = \G::generateUniqueID();
            $oProcessUser->create(array('PU_UID' => $sPuUID,
                                        'PRO_UID' => $sProcessUID,
                                        'USR_UID' => $sUsrUID,
                                        'PU_TYPE' => $sTypeUID));
            $oCriteria = $this->getProcessSupervisor($sProcessUID, $sPuUID);
            return $oCriteria;
        } else {
            throw new \Exception(\G::LoadTranslation("ID_RELATION_EXIST"));
        }
    }

    /**
     * Assign a dynaform supervisor of a process
     *
     * @param string $sProcessUID
     * @param string $sDynUID
     * @param int $sPudPosition
     * @access public
     */
    public function addProcessSupervisorDynaform($sProcessUID, $sDynUID, $sPudPosition)
    {
        $oTypeDynaform = \DynaformPeer::retrieveByPK($sDynUID);
        if (is_null( $oTypeDynaform )) {
            throw new \Exception(\G::LoadTranslation("ID_DOES NOT_DYNAFORM", array($sDynUID)));
        }
        $aResp = array();
        $sPuUIDT = array();
        $sDelimiter = \DBAdapter::getStringDelimiter();
        $oCriteria = new \Criteria('workflow');
        $oCriteria->addSelectColumn(\StepSupervisorPeer::STEP_UID);
        $oCriteria->addAsColumn('DYN_TITLE', 'C.CON_VALUE');
        $oCriteria->addAlias('C', 'CONTENT');
        $aConditions = array();
        $aConditions[] = array(\StepSupervisorPeer::STEP_UID_OBJ, \DynaformPeer::DYN_UID );
        $aConditions[] = array(\StepSupervisorPeer::STEP_TYPE_OBJ, $sDelimiter . 'DYNAFORM' . $sDelimiter );
        $oCriteria->addJoinMC($aConditions, \Criteria::LEFT_JOIN);
        $aConditions = array();
        $aConditions[] = array(\DynaformPeer::DYN_UID, 'C.CON_ID' );
        $aConditions[] = array('C.CON_CATEGORY', $sDelimiter . 'DYN_TITLE' . $sDelimiter );
        $aConditions[] = array('C.CON_LANG', $sDelimiter . SYS_LANG . $sDelimiter );
        $oCriteria->addJoinMC($aConditions, \Criteria::LEFT_JOIN);
        $oCriteria->add(\StepSupervisorPeer::PRO_UID, $sProcessUID);
        $oCriteria->add(\StepSupervisorPeer::STEP_UID_OBJ, $sDynUID);
        $oCriteria->add(\StepSupervisorPeer::STEP_TYPE_OBJ, 'DYNAFORM');
        $oCriteria->addAscendingOrderByColumn(\StepSupervisorPeer::STEP_POSITION);
        $oDataset = \StepSupervisorPeer::doSelectRS($oCriteria);
        $oDataset->setFetchmode(\ResultSet::FETCHMODE_ASSOC);
        $oDataset->next();
        while ($aRow = $oDataset->getRow()) {
            $sPuUIDT = $aRow['STEP_UID'];
            $oDataset->next();
        }
        if (sizeof($sPuUIDT) == 0) {
            $oStepSupervisor = new \StepSupervisor();
            $oStepSupervisor->create(array('PRO_UID' => $sProcessUID,
                                           'STEP_TYPE_OBJ' => "DYNAFORM",
                                           'STEP_UID_OBJ' => $sDynUID,
                                           'STEP_POSITION' => $oStepSupervisor->getNextPositionAll($sProcessUID)));
            $sDelimiter = \DBAdapter::getStringDelimiter();
            $oCriteria = new \Criteria('workflow');
            $oCriteria->addSelectColumn(\StepSupervisorPeer::STEP_UID);
            $oCriteria->addSelectColumn(\StepSupervisorPeer::PRO_UID);
            $oCriteria->addSelectColumn(\StepSupervisorPeer::STEP_UID_OBJ);
            $oCriteria->addSelectColumn(\StepSupervisorPeer::STEP_POSITION);
            $oCriteria->addAsColumn('DYN_TITLE', 'C.CON_VALUE');
            $oCriteria->addAlias('C', 'CONTENT');
            $aConditions = array();
            $aConditions[] = array(\StepSupervisorPeer::STEP_UID_OBJ, \DynaformPeer::DYN_UID );
            $aConditions[] = array(\StepSupervisorPeer::STEP_TYPE_OBJ, $sDelimiter . 'DYNAFORM' . $sDelimiter );
            $oCriteria->addJoinMC($aConditions, \Criteria::LEFT_JOIN);
            $aConditions = array();
            $aConditions[] = array(\DynaformPeer::DYN_UID, 'C.CON_ID' );
            $aConditions[] = array('C.CON_CATEGORY', $sDelimiter . 'DYN_TITLE' . $sDelimiter );
            $aConditions[] = array('C.CON_LANG', $sDelimiter . SYS_LANG . $sDelimiter );
            $oCriteria->addJoinMC($aConditions, \Criteria::LEFT_JOIN);
            $oCriteria->add(\StepSupervisorPeer::PRO_UID, $sProcessUID);
            $oCriteria->add(\StepSupervisorPeer::STEP_UID_OBJ, $sDynUID);
            $oCriteria->add(\StepSupervisorPeer::STEP_TYPE_OBJ, 'DYNAFORM');
            $oCriteria->addAscendingOrderByColumn(\StepSupervisorPeer::STEP_POSITION);
            $oDataset = \StepSupervisorPeer::doSelectRS($oCriteria);
            $oDataset->setFetchmode(\ResultSet::FETCHMODE_ASSOC);
            $oDataset->next();
            while ($aRow = $oDataset->getRow()) {
                $aResp = array('pud_uid' => $aRow['STEP_UID'],
                               'pud_position' => $aRow['STEP_POSITION'],
                               'dyn_uid' => $aRow['STEP_UID_OBJ']);
                $oDataset->next();
                $aRespPosition = $this->updateProcessSupervisorDynaform($sProcessUID ,$aRow['STEP_UID'], $sPudPosition);
                $aResp = array_merge(array('dyn_title' => $aRow['DYN_TITLE']), $aRespPosition);
            }
            return $aResp;
        } else {
            throw new \Exception(\G::LoadTranslation("ID_RELATION_EXIST"));
        }
    }

    /**
     * Assign a inputdocument supervisor of a process
     *
     * @param string $sProcessUID
     * @param string $sInputDocumentUID
     * @param int $sPuiPosition
     * @access public
     */

    public function addProcessSupervisorInputDocument($sProcessUID, $sInputDocumentUID, $sPuiPosition)
    {
        $oTypeInputDocument= \InputDocumentPeer::retrieveByPK($sInputDocumentUID);
        if (is_null( $oTypeInputDocument )) {
            throw new \Exception(\G::LoadTranslation("ID_DOES NOT_INPUT_DOCUMENT", array($sInputDocumentUID)));
        }
        $aResp = array();
        $sPuUIDT = array();
        $sDelimiter = \DBAdapter::getStringDelimiter();
        $oCriteria = new \Criteria('workflow');
        $oCriteria->addSelectColumn(\StepSupervisorPeer::STEP_UID);
        $oCriteria->addAsColumn('INP_DOC_TITLE', 'C.CON_VALUE');
        $oCriteria->addAlias('C', 'CONTENT');
        $aConditions = array();
        $aConditions[] = array(\StepSupervisorPeer::STEP_UID_OBJ, \InputDocumentPeer::INP_DOC_UID);
        $aConditions[] = array(\StepSupervisorPeer::STEP_TYPE_OBJ, $sDelimiter . 'INPUT_DOCUMENT' . $sDelimiter);
        $oCriteria->addJoinMC($aConditions, \Criteria::LEFT_JOIN);
        $aConditions = array();
        $aConditions[] = array(\InputDocumentPeer::INP_DOC_UID, 'C.CON_ID');
        $aConditions[] = array('C.CON_CATEGORY', $sDelimiter . 'INP_DOC_TITLE' . $sDelimiter);
        $aConditions[] = array('C.CON_LANG', $sDelimiter . SYS_LANG . $sDelimiter);
        $oCriteria->addJoinMC($aConditions, \Criteria::LEFT_JOIN);
        $oCriteria->add(\StepSupervisorPeer::PRO_UID, $sProcessUID);
        $oCriteria->add(\StepSupervisorPeer::STEP_UID_OBJ, $sInputDocumentUID);
        $oCriteria->add(\StepSupervisorPeer::STEP_TYPE_OBJ, 'INPUT_DOCUMENT');
        $oCriteria->addAscendingOrderByColumn(\StepSupervisorPeer::STEP_POSITION);
        $oDataset = \StepSupervisorPeer::doSelectRS($oCriteria);
        $oDataset->setFetchmode(\ResultSet::FETCHMODE_ASSOC);
        $oDataset->next();
        while ($aRow = $oDataset->getRow()) {
            $sPuUIDT = $aRow['STEP_UID'];
            $oDataset->next();
        }
        if (sizeof($sPuUIDT) == 0) {
            $oStepSupervisor = new \StepSupervisor();
            $oStepSupervisor->create(array('PRO_UID' => $sProcessUID,
                                           'STEP_TYPE_OBJ' => "INPUT_DOCUMENT",
                                           'STEP_UID_OBJ' => $sInputDocumentUID,
                                           'STEP_POSITION' => $oStepSupervisor->getNextPositionAll($sProcessUID)));
            $sDelimiter = \DBAdapter::getStringDelimiter();
            $oCriteria = new \Criteria('workflow');
            $oCriteria->addSelectColumn(\StepSupervisorPeer::STEP_UID);
            $oCriteria->addSelectColumn(\StepSupervisorPeer::PRO_UID);
            $oCriteria->addSelectColumn(\StepSupervisorPeer::STEP_TYPE_OBJ);
            $oCriteria->addSelectColumn(\StepSupervisorPeer::STEP_UID_OBJ);
            $oCriteria->addSelectColumn(\StepSupervisorPeer::STEP_POSITION);
            $oCriteria->addAsColumn('INP_DOC_TITLE', 'C.CON_VALUE');
            $oCriteria->addAlias('C', 'CONTENT');
            $aConditions = array();
            $aConditions[] = array(\StepSupervisorPeer::STEP_UID_OBJ, \InputDocumentPeer::INP_DOC_UID);
            $aConditions[] = array(\StepSupervisorPeer::STEP_TYPE_OBJ, $sDelimiter . 'INPUT_DOCUMENT' . $sDelimiter);
            $oCriteria->addJoinMC($aConditions, \Criteria::LEFT_JOIN);
            $aConditions = array();
            $aConditions[] = array(\InputDocumentPeer::INP_DOC_UID, 'C.CON_ID');
            $aConditions[] = array('C.CON_CATEGORY', $sDelimiter . 'INP_DOC_TITLE' . $sDelimiter);
            $aConditions[] = array('C.CON_LANG', $sDelimiter . SYS_LANG . $sDelimiter);
            $oCriteria->addJoinMC($aConditions, \Criteria::LEFT_JOIN);
            $oCriteria->add(\StepSupervisorPeer::PRO_UID, $sProcessUID);
            $oCriteria->add(\StepSupervisorPeer::STEP_UID_OBJ, $sInputDocumentUID);
            $oCriteria->add(\StepSupervisorPeer::STEP_TYPE_OBJ, 'INPUT_DOCUMENT');
            $oCriteria->addAscendingOrderByColumn(\StepSupervisorPeer::STEP_POSITION);
            $oDataset = \StepSupervisorPeer::doSelectRS($oCriteria);
            $oDataset->setFetchmode(\ResultSet::FETCHMODE_ASSOC);
            $oDataset->next();
            while ($aRow = $oDataset->getRow()) {
                $aResp = array('pui_uid' => $aRow['STEP_UID'],
                               'pui_position' => $aRow['STEP_POSITION'],
                               'input_doc_uid' => $aRow['STEP_UID_OBJ']);
                $oDataset->next();
                $aRespPosition = $this->updateProcessSupervisorInputDocument($sProcessUID ,$aRow['STEP_UID'], $sPuiPosition);
                $aResp = array_merge(array('input_doc_title' => $aRow['INP_DOC_TITLE']), $aRespPosition);
            }
            return $aResp;
        } else {
            throw new \Exception(\G::LoadTranslation("ID_RELATION_EXIST"));
        }
    }

    /**
     * Remove a supervisor
     *
     * @param string $sProcessUID
     * @param string $sPuUID
     * @access public
     */
    public function removeProcessSupervisor($sProcessUID, $sPuUID)
    {
        $oConnection = \Propel::getConnection(\ProcessUserPeer::DATABASE_NAME);
        try {
            $oProcessUser = \ProcessUserPeer::retrieveByPK($sPuUID);
            if (!is_null($oProcessUser)) {
                $oConnection->begin();
                $iResult = $oProcessUser->delete();
                $oConnection->commit();
                return $iResult;
            } else {
                throw new \Exception(\G::LoadTranslation("ID_ROW_DOES_NOT_EXIST"));
            }
        } catch (\Exception $e) {
            $oConnection->rollback();
            throw $e;
        }
    }

    /**
     * Remove a dynaform supervisor
     *
     * @param string $sProcessUID
     * @param string $sPudUID
     * @access public
     */
    public function removeDynaformSupervisor($sProcessUID, $sPudUID)
    {
        try {
            $oDynaformSupervidor = \StepSupervisorPeer::retrieveByPK($sPudUID);
            if (!is_null($oDynaformSupervidor)) {
                $oProcessMap = new \processMap();
                $oProcessMap->removeSupervisorStep( $oDynaformSupervidor->getStepUid(), $sProcessUID, 'DYNAFORM', $oDynaformSupervidor->getStepUidObj(), $oDynaformSupervidor->getStepPosition() );
            } else {
                throw new \Exception(\G::LoadTranslation("ID_ROW_DOES_NOT_EXIST"));
            }
        } catch (Exception $oError) {
            throw ($oError);
        }
    }

    /**
     * Remove a input document supervisor
     *
     * @param string $sProcessUID
     * @param string $sPuiUID
     * @access public
     */
    public function removeInputDocumentSupervisor($sProcessUID, $sPuiUID)
    {
        try {
            $oInputDocumentSupervidor = \StepSupervisorPeer::retrieveByPK($sPuiUID);
            if (!is_null($oInputDocumentSupervidor)) {
                $oProcessMap = new \processMap();
                $oProcessMap->removeSupervisorStep( $oInputDocumentSupervidor->getStepUid(), $sProcessUID, 'INPUT_DOCUMENT', $oInputDocumentSupervidor->getStepUidObj(), $oInputDocumentSupervidor->getStepPosition() );
            } else {
                throw new \Exception(\G::LoadTranslation("ID_ROW_DOES_NOT_EXIST"));
            }
        } catch (Exception $oError) {
            throw ($oError);
        }
    }

    /**
     * Assign a dynaform supervisor of a process
     *
     * @param string $sProcessUID
     * @param string $sPudUID
     * @param string $sPudPosition
     * @access public
     */
    public function updateProcessSupervisorDynaform($sProcessUID, $sPudUID, $sPudPosition)
    {
        $oCriteria=\StepSupervisorPeer::retrieveByPK($sPudUID);
        $actualPosition = $oCriteria->getStepPosition();
        $tempPosition = (isset($sPudPosition)) ? $sPudPosition : $actualPosition;
        if (isset($tempPosition) && ($tempPosition != $actualPosition)) {
            $this->moveDyanformsInputDocuments($sProcessUID, $sPudUID, $tempPosition);
        }
        //Return
        unset($sPudPosition);
        $sPudPosition = $tempPosition;
        $oCriteria->setStepPosition($sPudPosition);
        $oCriteria->save();
        $oCriteria=array('pud_uid' => $oCriteria->getStepUid(),
                         'pud_position' => $oCriteria->getStepPosition(),
                         'dyn_uid' => $oCriteria->getStepUidObj());
        return $oCriteria;
    }



    /**
     * Assign a InputDocument supervisor of a process
     *
     * @param string $sProcessUID
     * @param string $sPuiUID
     * @param string $sPuiPosition
     * @access public
     */
    public function updateProcessSupervisorInputDocument($sProcessUID, $sPuiUID, $sPuiPosition)
    {
        $oCriteria=\StepSupervisorPeer::retrieveByPK($sPuiUID);
        $actualPosition = $oCriteria->getStepPosition();
        $tempPosition = (isset($sPuiPosition)) ? $sPuiPosition : $actualPosition;
        if (isset($tempPosition) && ($tempPosition != $actualPosition)) {
            $this->moveDyanformsInputDocuments($sProcessUID, $sPuiUID, $tempPosition);
        }
        //Return
        unset($sPuiPosition);
        $sPuiPosition = $tempPosition;
        $oCriteria->setStepPosition($sPuiPosition);
        $oCriteria->save();
        $oCriteria=array('pui_uid' => $oCriteria->getStepUid(),
                         'pui_position' => $oCriteria->getStepPosition(),
                         'inp_doc_uid' => $oCriteria->getStepUidObj());
        return $oCriteria;
    }

    /**
     * Validate Process Uid
     * @var string $pro_uid. Uid for Process
     * @var string $pu_uid. Uid for Step
     * @var string $pu_pos. Position for Step
     *
     * @return void
     */
    public function moveDyanformsInputDocuments($pro_uid, $pu_uid, $pu_pos)
    {
        $aSteps = $this->getProcessSupervisorDynaformsInputsDocuments($pro_uid);
        $step_pos = $pu_pos;
        $step_uid = $pu_uid;
        foreach ($aSteps as $dataStep) {
            if($dataStep['obj_type'] == 'DYNAFORM'){
                if ($dataStep['pud_uid'] == $step_uid) {
                    $prStepPos = (int)$dataStep['pud_position'];
                }
            }else{
                if ($dataStep['pui_uid'] == $step_uid) {
                    $prStepPos = (int)$dataStep['pui_position'];
                }
            }            
        }
        $seStepPos = $step_pos;
        //Principal Step is up
        if ($prStepPos == $seStepPos) {
            return true;
        } elseif ($prStepPos < $seStepPos) {
            $modPos = 'UP';
            $newPos = $seStepPos;
            $iniPos = $prStepPos+1;
            $finPos = $seStepPos;
        } else {
            $modPos = 'DOWN';
            $newPos = $seStepPos;
            $iniPos = $seStepPos;
            $finPos = $prStepPos-1;
        }
        $range = range($iniPos, $finPos);
        foreach ($aSteps as $dataStep) {
            if($dataStep['obj_type'] == 'DYNAFORM'){
                if ((in_array($dataStep['pud_position'], $range)) && ($dataStep['pud_uid'] != $step_uid)) {
                    $stepChangeIds[] = $dataStep['pud_uid'];
                    $stepChangePos[] = $dataStep['pud_position'];
                }
            }else{
                if ((in_array($dataStep['pui_position'], $range)) && ($dataStep['pui_uid'] != $step_uid)) {
                    $stepChangeIds[] = $dataStep['pui_uid'];
                    $stepChangePos[] = $dataStep['pui_position'];
                }
            }            
        }
        foreach ($stepChangeIds as $key => $value) {
            if ($modPos == 'UP') {
                $tempPos = ((int)$stepChangePos[$key])-1;
                $this ->changePosStep($value, $tempPos);
            } else {
                $tempPos = ((int)$stepChangePos[$key])+1;
                $this ->changePosStep($value, $tempPos);
            }
        }
        $this ->changePosStep($value, $tempPos);
    }

    /**
     * Validate Process Uid
     * @var string $pro_uid. Uid for Process
     * @var string $pu_uid. Uid for Step
     * @var string $pu_pos. Position for Step
     *
     * @return void
     */
    public function moveDynaforms($pro_uid, $pu_uid, $pu_pos)
    {
        $aSteps = $this->getProcessSupervisorDynaforms($pro_uid);
        $step_pos = $pu_pos;
        $step_uid = $pu_uid;
        foreach ($aSteps as $dataStep) {
            if ($dataStep['pud_uid'] == $step_uid) {
                $prStepPos = (int)$dataStep['pud_position'];
            }
        }
        $seStepPos = $step_pos;
        //Principal Step is up
        if ($prStepPos == $seStepPos) {
            return true;
        } elseif ($prStepPos < $seStepPos) {
            $modPos = 'UP';
            $newPos = $seStepPos;
            $iniPos = $prStepPos+1;
            $finPos = $seStepPos;
        } else {
            $modPos = 'DOWN';
            $newPos = $seStepPos;
            $iniPos = $seStepPos;
            $finPos = $prStepPos-1;
        }
        $range = range($iniPos, $finPos);
        foreach ($aSteps as $dataStep) {
            if ((in_array($dataStep['pud_position'], $range)) && ($dataStep['pud_uid'] != $step_uid)) {
                $stepChangeIds[] = $dataStep['pud_uid'];
                $stepChangePos[] = $dataStep['pud_position'];
            }
        }
        foreach ($stepChangeIds as $key => $value) {
            if ($modPos == 'UP') {
                $tempPos = ((int)$stepChangePos[$key])-1;
                $this ->changePosStep($value, $tempPos);
            } else {
                $tempPos = ((int)$stepChangePos[$key])+1;
                $this ->changePosStep($value, $tempPos);
            }
        }
        $this ->changePosStep($value, $tempPos);
    }

    /**
     * Validate Process Uid
     * @var string $pro_uid. Uid for Process
     * @var string $pu_uid. Uid for Step
     * @var string $pu_pos. Position for Step
     *
     * @return void
     */
    public function moveInputDocuments($pro_uid, $pu_uid, $pu_pos)
    {
        $aSteps = $this->getProcessSupervisorInputDocuments($pro_uid);
        $step_pos = $pu_pos;
        $step_uid = $pu_uid;
        foreach ($aSteps as $dataStep) {
            if ($dataStep['pui_uid'] == $step_uid) {
                $prStepPos = (int)$dataStep['pui_position'];
            }
        }
        $seStepPos = $step_pos;
        //Principal Step is up
        if ($prStepPos == $seStepPos) {
            return true;
        } elseif ($prStepPos < $seStepPos) {
            $modPos = 'UP';
            $newPos = $seStepPos;
            $iniPos = $prStepPos+1;
            $finPos = $seStepPos;
        } else {
            $modPos = 'DOWN';
            $newPos = $seStepPos;
            $iniPos = $seStepPos;
            $finPos = $prStepPos-1;
        }
        $range = range($iniPos, $finPos);
        foreach ($aSteps as $dataStep) {
            if ((in_array($dataStep['pui_position'], $range)) && ($dataStep['pui_uid'] != $step_uid)) {
                $stepChangeIds[] = $dataStep['pui_uid'];
                $stepChangePos[] = $dataStep['pui_position'];
            }
        }
        foreach ($stepChangeIds as $key => $value) {
            if ($modPos == 'UP') {
                $tempPos = ((int)$stepChangePos[$key])-1;
                $this ->changePosStep($value, $tempPos);
            } else {
                $tempPos = ((int)$stepChangePos[$key])+1;
                $this ->changePosStep($value, $tempPos);
            }
        }
        $this ->changePosStep($value, $tempPos);
    }

    /**
     * Validate Process Uid
     * @var string $pro_uid. Uid for process
     *
     */
    public function changePosStep ($step_uid, $pos)
    {
        $oCriteria=\StepSupervisorPeer::retrieveByPK($step_uid);
        $oCriteria->setStepPosition($pos);
        $oCriteria->save();
    }
}

