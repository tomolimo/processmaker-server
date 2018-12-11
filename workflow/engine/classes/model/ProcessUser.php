<?php
/**
 * ProcessUser.php
 * @package    workflow.engine.classes.model
 */

/**
 * Skeleton subclass for representing a row from the 'PROCESS_USER' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    workflow.engine.classes.model
 */
class ProcessUser extends BaseProcessUser
{
    public function create($aData)
    {
        $oConnection = Propel::getConnection(ProcessUserPeer::DATABASE_NAME);
        try {
            $criteria = new Criteria('workflow');
            $criteria->add(ProcessUserPeer::PU_UID,  $aData['PU_UID'] );
            $criteria->add(ProcessUserPeer::PRO_UID,  $aData['PRO_UID']  );
            $criteria->add(ProcessUserPeer::USR_UID,  $aData['USR_UID']  );
            $criteria->add(ProcessUserPeer::PU_TYPE,  $aData['PU_TYPE']  );
            $objects = ProcessUserPeer::doSelect($criteria, $oConnection);
            $oConnection->begin();
            foreach ($objects as $row) {
                $this->remove($row->getTasUid(), $row->getUsrUid(), $row->getTuType(), $row->getTuRelation() );
            }
            $oConnection->commit();

            $oProcessUser = new ProcessUser();
            $oProcessUser->fromArray($aData, BasePeer::TYPE_FIELDNAME);
            if ($oProcessUser->validate()) {
                $oConnection->begin();
                $iResult = $oProcessUser->save();
                $oConnection->commit();
                return $iResult;
            } else {
                $sMessage = '';
                $aValidationFailures = $oProcessUser->getValidationFailures();
                foreach ($aValidationFailures as $oValidationFailure) {
                    $sMessage .= $oValidationFailure->getMessage() . '<br />';
                }
                throw(new Exception('The registry cannot be created!<br />'.$sMessage));
            }
        } catch (Exception $oError) {
            $oConnection->rollback();
            throw($oError);
        }
    }

    /**
     * Remove the application document registry
     * @param string $sPuUid
     * @param string $sProUid
     * @param string $sUserUid
     * @return string
    **/
    public function remove($sPuUid)
    {
        $oConnection = Propel::getConnection(ProcessUserPeer::DATABASE_NAME);
        try {
            $oProcessUser = ProcessUserPeer::retrieveByPK($sPuUid);
            if (!is_null($oProcessUser)) {
                $oConnection->begin();
                $iResult = $oProcessUser->delete();
                $oConnection->commit();
                return $iResult;
            } else {
                throw(new Exception('This row doesn\'t exist!'));
            }
        } catch (Exception $oError) {
            $oConnection->rollback();
            throw($oError);
        }
    }

    public function Exists ($sUid)
    {
        try {
            $oObj = ProcessUserPeer::retrieveByPk($sUid);
            return (is_object($oObj) && get_class($oObj) == 'ProcessUser');
        } catch (Exception $oError) {
            throw($oError);
        }
    }

    public function validateUserAccess($proUid, $usrUid)
    {
        try {
            $oCriteria = new Criteria();
            $oCriteria->add(ProcessUserPeer::PRO_UID, $proUid);
            $oCriteria->add(ProcessUserPeer::PU_TYPE, 'SUPERVISOR');
            $oCriteria->add(ProcessUserPeer::USR_UID, $usrUid);
            $dataset = ProcessUserPeer::doSelectRS($oCriteria);
            $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
            //If the user is in Assigned supervisors list
            if ($dataset->next()) {
                return true;
            } else {
                //If the user is in a group in Assigned supervisors list
                $oCriteria = new Criteria();
                $oCriteria->add(ProcessUserPeer::PRO_UID, $proUid);
                $oCriteria->add(ProcessUserPeer::PU_TYPE, 'GROUP_SUPERVISOR');
                $dataset = ProcessUserPeer::doSelectRS($oCriteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
                $oGroups = new Groups();
                $aGroups = $oGroups->getActiveGroupsForAnUser($usrUid);
                while ($dataset->next()) {
                    $row = $dataset->getRow();
                    $groupUid = $row['USR_UID'];
                    if (in_array($groupUid, $aGroups)) {
                        return true;
                    }
                }
                return false;
            }
        } catch (Exception $oError) {
            throw ($oError);
        }
    }

    /**
     * Get the list of process where the user is supervisor
     * finding cases PRO_UID where $userUid is supervising
     *
     * @param string $userUid
     *
     * @return array
     * @throws Exception
     */
    public function getProUidSupervisor($userUid)
    {
        try {

            $processes = [];

            //Get the process when the user is supervisor
            $criteria = new Criteria('workflow');
            $criteria->add(ProcessUserPeer::PU_TYPE, 'SUPERVISOR');
            $criteria->add(ProcessUserPeer::USR_UID, $userUid);
            $dataset = ProcessUserPeer::doSelectRS($criteria);
            $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
            $dataset->next();

            while ($row = $dataset->getRow()) {
                $processes[] = $row['PRO_UID'];
                $dataset->next();
            }

            //Get the process when the user is assigned into the group supervisor
            $criteria = new Criteria('workflow');
            $criteria->add(ProcessUserPeer::PU_TYPE, 'GROUP_SUPERVISOR');
            $criteria->addSelectColumn(ProcessUserPeer::PRO_UID);
            $criteria->addJoin(ProcessUserPeer::USR_UID, GroupUserPeer::GRP_UID, Criteria::LEFT_JOIN);
            $criteria->add(GroupUserPeer::USR_UID, $userUid);
            $dataset = ProcessUserPeer::doSelectRS($criteria);
            $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
            $dataset->next();

            while ($row = $dataset->getRow()) {
                $processes[] = $row['PRO_UID'];
                $dataset->next();
            }

            return $processes;

        } catch (Exception $e) {
            throw $e;
        }
    }
}

